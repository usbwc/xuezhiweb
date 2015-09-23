<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'admin_controller.php';

class Carer extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('carer_model');
        $this->load->model('');
    }


    public function index()
    {
        $allHospital = parent::getHospital();
        $this->load->view('admin/header.html');
        $this->load->view('admin/dashboard.html');
        $this->load->view('admin/footer.html');
    }

    public function add()
    {
        $data['star_level_array'] = $this->config->item('star_level');
        $data['nurse_service_status_array'] = $this->config->item('nurse_service_status');
        $data['nurse_service_level_array'] = $this->config->item('nursing_level');
        $data['hospital_array'] = parent::getHospital();
        $data['department_array'] = parent::getDepartment();

        $this->load->view('admin/header.html');
        $this->load->view('admin/addCarer.html',$data);
        $this->load->view('admin/footer.html');
    }

    public function edit($cid)
    {
        $data['star_level_array'] = $this->config->item('star_level');
        $data['nurse_service_status_array'] = $this->config->item('nurse_service_status');
        $data['nurse_service_level_array'] = $this->config->item('nursing_level');
        $data['hospital_array'] = parent::getHospital();
        $data['department_array'] = parent::getDepartment();
        $data['carer'] = $this->carer_model->get_by_id($cid);

        $carer_department_array = array();
        $arr = $this->carer_model->get_departments_of_carer($cid);
        foreach($arr as $record)
        {
            $carer_department_array[] = $record['did'];
        }
        $data['carer_department_array'] = $carer_department_array;
        //var_dump($data);
        $this->load->view('admin/header.html');
        $this->load->view('admin/editCarer.html',$data);
        $this->load->view('admin/footer.html');
    }

    public function test(){
        echo ROOTPATH.';';
    }

    private function mkdirs($dir)
    {
        if(!is_dir($dir))
        {
            if(!$this->mkdirs(dirname($dir))){
                return false;
            }
            if(!mkdir($dir,0777)){
                return false;
            }
        }
        return true;
    }

    public function addAction()
    {
        $avatar = $this->input->post('avatar');
        $newAvatar = '';
        if($avatar!=null &&strlen($avatar)>0){
            if(file_exists(ROOTPATH.$avatar)){
                $str = md5($avatar);
                $f1 = substr($str,0,2);
                $f2 = substr($str,2,2);
                $f3 = substr($str,4,2);
                $targetPath = ROOTPATH.'/uploads/'.$f1.'/'.$f2.'/'.$f3;
                if(!file_exists($targetPath)){
                    $this->mkdirs($targetPath);
                }

                rename(ROOTPATH.$avatar,$targetPath.'/'.$str.'.jpg');
                $newAvatar = '/uploads/'.$f1.'/'.$f2.'/'.$f3.'/'.$str.'.jpg';
                log_message('debug','$newAvatar = '.$newAvatar);
            }

        }
        if($newAvatar == ''){
            $newAvatar = '/uploads/default.jpg';
        }
        $_POST['avatar'] = $newAvatar;
        $departments = $this->input->post('departments');
        $departmentArray = explode(',',$departments);

        unset($_POST['departments']);
        $newID = $this->carer_model->add($_POST);

        $departmentArrayDB = array();
        foreach($departmentArray as $did){
            if(strlen($did) >0){
                $departmentArrayDB[] = array('cid'=>$newID,'did'=>$did);
            }
        }
        $this->carer_model->add_department_of_carer($departmentArrayDB);
        echo json_encode(array('status'=>0));
    }

    public function editAction()
    {
        $cid = $this->input->post('id');
        $avatar = $this->input->post('avatar');
        if(strstr($avatar,'default'))
        {
            //默认图片
        }
        else
        {
            $newAvatar = '';
            if($avatar!=null &&strlen($avatar)>0){
                if(file_exists(ROOTPATH.$avatar)){
                    $str = md5($avatar);
                    $f1 = substr($str,0,2);
                    $f2 = substr($str,2,2);
                    $f3 = substr($str,4,2);
                    $targetPath = ROOTPATH.'/uploads/'.$f1.'/'.$f2.'/'.$f3;
                    if(!file_exists($targetPath)){
                        $this->mkdirs($targetPath);
                    }

                    rename(ROOTPATH.$avatar,$targetPath.'/'.$str.'.jpg');
                    $newAvatar = '/uploads/'.$f1.'/'.$f2.'/'.$f3.'/'.$str.'.jpg';
                    log_message('debug','$newAvatar = '.$newAvatar);
                }

            }
            if($newAvatar == ''){
                $newAvatar = '/uploads/default.jpg';
            }
            $_POST['avatar'] = $newAvatar;
        }
        $departments = $this->input->post('departments');
        $departmentArray = explode(',',$departments);

        unset($_POST['departments']);

        $departmentArrayDB = array();
        foreach($departmentArray as $did){
            if(strlen($did) >0){
                $departmentArrayDB[] = array('cid'=>$cid,'did'=>$did);
            }
        }
        $this->load->database();
        $this->db->trans_start();
        $this->carer_model->remove_department_of_carer($cid);
        $this->carer_model->add_department_of_carer($departmentArrayDB);
        $this->carer_model->update($_POST);
        $this->db->trans_complete();
        echo json_encode(array('status'=>0));
    }

    public function delAction()
    {
        $cid = $this->input->post('id');

        $this->db->trans_start();
        $this->carer_model->remove_department_of_carer($cid);
        $this->carer_model->remove($cid);
        $this->db->trans_complete();
        echo json_encode(array('status'=>0));
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
