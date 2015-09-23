<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'admin_controller.php';

class Hospital extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('hospital_model');
    }

    public function index()
    {
        $this->load->view('admin/header.html');
        $this->load->view('admin/hospital.html');
        $this->load->view('admin/footer.html');
    }

    public function addAction()
    {
        $name = $this->input->post('name');
        if(strlen($name)<5){
            echo json_encode(array('status'=>-1,'msg'=>'医院名称太短'));
        } else {
            if($this->hospital_model->exist($name)){
                echo json_encode(array('status'=>-1,'msg'=>'医院已经存在'));
            } else {
                $newID = $this->hospital_model->add($name);
                if($newID){
                    echo json_encode(array('status'=>0));
                } else {
                    echo json_encode(array('status'=>-1,'msg'=>'添加失败'));
                }
            }
        }

    }


    public function updateAction(){
        $id = $this->input->post('id');
        $name = $this->input->post('name');
        if(is_numeric($id) && strlen($name)>0) {
            $this->hospital_model->update(array('id'=>$id,'name'=>$name));

            echo json_encode(array('status'=>0));
        }else {
            echo json_encode(array('status'=>-1,'msg'=>'参数错误,修改失败'));

        }
    }

    public function delAction()
    {
        $id = $this->input->post('id');
        if(is_numeric($id)){
            $this->hospital_model->del($id);
            echo json_encode(array('status'=>0));
        } else {
            echo json_encode(array('status'=>-1,'msg'=>'参数错误,删除失败'));

        }
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
