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
    }


    public function getMedicalList () {
        $result = $this->medical_model->get_all();
        parent::ajaxReturn('medical_list',$result);
    }

    public function addRemainMedicalPrompt()
    {
        $newData['uid'] = $this->input->post('uid');
        $newData['mid'] = $this->input->post('mid');
        $newData['remain'] = $this->input->post('remain');
        $newData['unit'] = $this->input->post('unit');
        $newData['warning'] = $this->input->post('warning');
        $newData['addtime'] = $this->input->post('addtime');

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
        $newData['rpid'] = $this->input->post('id');
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
        parent::ajaxReturn('remain_prompt_list',$rArray);
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
        parent::ajaxReturn('take_history_list',$takeArrayGroupByDate);
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
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
