<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class medical extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('medical_model');
        $this->load->model('remain_prompt_model');
        $this->load->model('take_prompt_model');
        $this->load->model('take_history_model');
    }


    public function getStaticMedicalList () {
        $version = $this->config->item('version');
        $result = $this->medical_model->get_all();
        $data = array('status'=>200,'type'=>'medical_list','medical_list'=>$result,'version'=>$version);
        echo json_encode($data);
    }

    public function addRemainMedicalPrompt()
    {
        $version = $this->config->item('version');

        $newData['uid'] = $this->input->post('uid');
        $newData['mid'] = $this->input->post('mid');
        $newData['remain'] = $this->input->post('remain');
        $newData['unit'] = $this->input->post('unit');
        $newData['warning'] = $this->input->post('warning');
        $newData['addtime'] = $this->input->post('addtime');

        $newID = $this->remain_prompt_model->add($newData);
        $newData['rpid']= $newID;
        $newData['take_prompt_list'] = array();
        $data = array('status'=>200,'type'=>'remain_prompt_list','remain_prompt_list'=>array($newData),'version'=>$version);
        echo json_encode($data);    }

    public function addTakeMedicalPrompt()
    {
        $version = $this->config->item('version');

        $newData['uid'] = $this->input->post('uid');
        $newData['rpid'] = $this->input->post('rpid');
        $newData['time'] = $this->input->post('time');
        $newData['dose'] = $this->input->post('dose');
        $newData['remark'] = $this->input->post('remark');
        $newID = $this->take_prompt_model->add($newData);
        $newData['tpid']= $newID;

        $data = array('status'=>200,'type'=>'take_prompt_list','take_prompt_list'=>array($newData),'version'=>$version);
        echo json_encode($data);
    }


    public function getPrompt()
    {
        $version = $this->config->item('version');

        $uid = $this->input->get('uid');
        $rArray = $this->remain_prompt_model->get_by_uid($uid);
        foreach($rArray as &$rData){

            $rData['rpid'] = $rData['id'];
            unset($rData['id']);
            $take_prompt_list= $this->take_prompt_model->get_by_rpid($rData['rpid']);
            foreach($take_prompt_list as &$tData){
                $tData['tpid'] = $tData['id'];
                unset($rData['id']);
            }
            $rData['take_prompt_list'] = $take_prompt_list;
        }
        $data = array('status'=>200,'type'=>'remain_prompt_list','remain_prompt_list'=>$rArray,'version'=>$version);
        echo json_encode($data);

    }

    public function getTakeHistory()
    {
        $date = $this->input->get('date');

        $uid = $this->input->get('uid');
        $oriTakeArray = $this->take_history_model->get_by_uid($uid,$date);
        $takeArrayGroupByDate = array();
        foreach($oriTakeArray as $oriTake){
            $date = date('Y-m-d',strtotime($oriTake['taketime']));
            $takeArrayGroupByDate[$date][] = $oriTake;

        }
        echo json_encode($takeArrayGroupByDate);

    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
