<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'XZ_Controller.php';

class medical extends XZ_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('medical_model');
        $this->load->model('remain_prompt_model');
        $this->load->model('take_prompt_model');
        $this->load->model('take_history_model');
        $this->load->model('unit_model');
        $this->load->model('detection_model');
    }


    public function getMedicalList () {
        $result = $this->medical_model->get_all();
        parent::ajaxReturn('medical_list',$result);
    }

    public function addRemainMedicalPrompt()
    {
        log_message('debug','addRemainMedicalPrompt '.print_r($_POST,true));
        $newData['uid'] = $this->input->post('uid');
        $newData['mid'] = $this->input->post('mid');
        if(!$this->medical_model->exist_id($newData['mid']))
        {
            parent::ajaxError('药品不存在');
        }
        $newData['remain'] = $this->input->post('remain');
        $newData['unit'] = $this->input->post('unit');
        $newData['warning'] = $this->input->post('warning');
        $newData['addtime'] = date('Y-m-d H:i:s');

        $newID = $this->remain_prompt_model->add($newData);
        $newData['rpid']= $newID;
        $newData['take_prompt_list'] = array();
        parent::ajaxReturn('remain_prompt_list',$newData);
    }
    public function updateRemainMedicalPromptValid()
    {
        $uid = $this->input->post('uid');
        $newData['id'] = $this->input->post('id');
        $remainPrompt = $this->remain_prompt_model->get_by_rpid($newData['id']);
        if(!$remainPrompt){
            parent::ajaxError('要修改的记录不存在');
        }


        $newData['valid'] = $this->input->post('valid');
        if(!in_array($newData['valid'],array('1','0'))){
            parent::ajaxError('valid值不正确');
        }
        $this->remain_prompt_model->update($newData);
        parent::ajaxReturn('update_remain_prompt_valid_result',$newData['valid']);
    }


    public function addTakeMedicalPrompt()
    {
        $newData['uid'] = $this->input->post('uid');
        $newData['rpid'] = $this->input->post('rpid');
        if(!$this->remain_prompt_model->get_by_rpid($newData['rpid']))
        {
            parent::ajaxError('药品不存在');
        }

        $newData['time'] = date('H:i:s',strtotime($this->input->post('time')));
        $newData['dose'] = $this->input->post('dose');
        $newData['remark'] = $this->input->post('remark');
        $newID = $this->take_prompt_model->add($newData);
        $newData['tpid']= $newID;
        parent::ajaxReturn('take_prompt_list',array($newData));
    }
    public function updateTakeMedicalPromptValid()
    {
        $uid = $this->input->post('uid');
        $newData['id'] = $this->input->post('id');
        $takePrompt = $this->take_prompt_model->get_by_tpid($newData['id']);
        if(!$takePrompt){
            parent::ajaxError('要修改的记录不存在');
        }
        $newData['valid'] = $this->input->post('valid');
        if(!in_array($newData['valid'],array('1','0'))){
            parent::ajaxError('valid值不正确');
        }
        $this->take_prompt_model->update($newData);
        parent::ajaxReturn('update_take_prompt_valid_result',$newData['valid']);
    }

    public function getPrompt()
    {
        $uid = $this->input->post('uid');
        if(!$this->user_model->exist_user($uid)){
            parent::ajaxError('用户不存在');
        }
        log_message('debug','getPrompt uid = '.$uid);
        $rArray = $this->remain_prompt_model->get_by_uid($uid);
        log_message('debug','getPrompt $rArray = '.print_r($rArray,true));

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
        parent::ajaxReturn('remain_prompt_list',$rArray);
    }

    public function getTakeHistory()
    {
        $date = $this->input->get_post('date');
        $uid = $this->input->get_post('uid');
        $oriTakeArray = $this->take_history_model->get_by_uid($uid,$date);
        $takeArrayGroupByDate = array();

        foreach($oriTakeArray as $oriTake){
            $date = date('Y-m-d',strtotime($oriTake['taketime']));
            $takeArrayGroupByDate[$date][] = $oriTake;
        }

        $resultArray = array();
        foreach($takeArrayGroupByDate as $key=>$data) {
            $resultArray[] = array('date'=>$key,'take_list'=>$data);
        }

        parent::ajaxReturn('take_history_list',$resultArray);
    }
    public function getUnitList()
    {
        $arr = $this->unit_model->get_all_unit();
        parent::ajaxReturn('unit_list',$arr);
    }

    public function takeTakeMedical()
    {
        $newData['uid'] = $this->input->post('uid');
        $newData['rpid'] = $this->input->post('rpid');
        $newData['dose'] = $this->input->post('dose');
        $newData['unit'] = $this->input->post('unit');
        $newData['taketime'] = date('Y-m-d H:i:s');
        $remain = $this->remain_prompt_model->get_by_rpid($newData['rpid']);
        $newData['mid'] = $remain['mid'];
        $newID = $this->take_history_model->add($newData);
        if($newID){
            $newData['id'] = $newID;
            $takeArrayGroupByDate[date('Y-m-d')] = array($newData);
            $remainData = $this->remain_prompt_model->get_by_rpid($newData['rpid']);
            $remain = $remainData['remain'];
            $remainData['remain'] = (float)$remain -  (float)$newData['dose'];
            $this->remain_prompt_model->update($remainData);

            parent::ajaxReturn('take_history_list',$takeArrayGroupByDate);
        } else {
            parent::ajaxError('添加吃药记录失败');
        }
    }

    public function addMedicalDose()
    {
        $uid = $this->input->post('uid');
        $newData['id'] = $this->input->post('rpid');
        $addDose = $this->input->post('dose');
        $remainData = $this->remain_prompt_model->get_by_rpid($newData['id']);
        $remain = $remainData['remain'];
        $newData['remain'] = (float)$remain +  (float)$addDose;
        $this->remain_prompt_model->update($newData);
        parent::ajaxReturn('add_medical_dose_result', $newData['remain']);
    }
    public function addDetection()
    {
        $data['uid'] = $this->input->post('uid');
        $data['TG'] = $this->input->post('TG');
        $data['TCHO'] = $this->input->post('TCHO');
        $data['LOLC'] = $this->input->post('LOLC');
        $data['HDLC'] = $this->input->post('HDLC');
        $data['ALT'] = $this->input->post('ALT');
        $data['AST'] = $this->input->post('AST');
        $data['CK'] = $this->input->post('CK');
        $data['GLU'] = $this->input->post('GLU');
        $data['HBA1C'] = $this->input->post('HBA1C');
        $data['time'] = date('Y-m-d H:i:s');
        $newID = $this->detection_model->add($data);
        if($newID){
            $data['id'] = $newID;
            parent::ajaxReturn('detection_list', array($data));
        } else {
            parent::ajaxError('添加失败');
        }
    }

    public function getDetection()
    {
        $uid = $this->input->get_post('uid');
        $arr = $this->detection_model->get_all($uid);
        parent::ajaxReturn('detection_list', array($arr));

    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
