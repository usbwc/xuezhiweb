<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once 'XZ_Controller.php';

class medical extends XZ_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('medical_model');
        $this->load->model('box_model');
        $this->load->model('prompt_model');
        $this->load->model('take_history_model');
        $this->load->model('unit_model');
        $this->load->model('detection_model');
    }

    //药品列表
    public function getMedicalList () {
        $result = $this->medical_model->get_all();
        parent::ajaxReturn('medical_list',$result);
    }
    //单位
    public function getUnitList()
    {
        $arr = $this->unit_model->get_all_unit();
        parent::ajaxReturn('unit_list',$arr);
    }
    //提醒

    public function getPrompt()
    {
        $uid = $this->input->post('uid');

        $promptArray = $this->prompt_model->get_by_uid($uid);
        parent::ajaxReturn('prompt_list',$promptArray,'查询成功');

    }
    public function addPrompt()
    {
        log_message('debug','addPrompt '.print_r($_POST,true));
        $newData['uid'] = $this->input->post('uid');
        $newData['mid'] = $this->input->post('mid');
        if(!$this->medical_model->exist_id($newData['mid']))
        {
            parent::ajaxError('药品不存在');
        }


        $doseString = $this->input->post('dose');
        parent::verifyDose($doseString);
        $newData['dose'] = $doseString;

        $newData['precaution'] = $this->input->post('precaution');

        $timeString = $this->input->post('time');
        parent::checkDatetime($timeString,'H:i:s');
        $newData['time'] = date('H:i:s',strtotime($timeString));

        $newData['valid'] = $this->input->post('valid');
        parent::verifyValidCode($newData['valid']);

        $newData['addtime'] = date('Y-m-d H:i:s');
        $newID = $this->prompt_model->add($newData);
        if(!$newID){
            parent::ajaxError('添加失败');
        }
        $newData['id'] =  $newID;
        parent::ajaxReturn('prompt_list',array($newData),'添加提醒成功');
    }
    public function setPrompt()
    {
        log_message('debug','addPrompt '.print_r($_POST,true));
        $newData['id'] = $this->input->post('id');
        if(!$this->prompt_model->get_by_id($newData['id'])){
            parent::ajaxError('提醒不存在');
        }
        $uid = $this->input->post('uid');
        $mid = $this->input->post('mid');
        if(!$this->medical_model->exist_id($mid))
        {
            parent::ajaxError('药品不存在');
        }

        if(isset($_POST['dose'])){
            $doseString = $this->input->post('dose');
            parent::verifyDose($doseString);
            $newData['dose'] = $doseString;
        }

        if(isset($_POST['precaution'])){
            $newData['precaution'] = $this->input->post('precaution');
        }
        if($this->input->post('time')){
            $timeString = $this->input->post('time');
            parent::checkDatetime($timeString,'H:i:s');
            $newData['time'] = date('H:i:s',strtotime($timeString));
        }
        if(isset($_POST['valid'])){
            $newData['valid'] = $this->input->post('valid');
            parent::verifyValidCode($newData['valid']);
        }
        if(count($newData) == 1){
            parent::ajaxError('没有修改任何字段');
        }
        $this->prompt_model->update($newData);
        parent::ajaxReturn('prompt_list',array($this->prompt_model->get_by_id($newData['id'])),'修改提醒成功');
    }

    public function delPrompt(){
        $id  = $this->input->post('id');
        if(!$this->prompt_model->get_by_id($id)){
            parent::ajaxError('提醒不存在');
        }
        $this->prompt_model->del($id);
        if($this->prompt_model->get_by_id($id)){
            parent::ajaxError('删除失败');
        }
        parent::ajaxReturn('del_result',array('id'=>$id),'删除提醒成功');

    }

    //个人药箱
    public function getBox()
    {
        $uid = $this->input->post('uid');
        $boxArray = $this->box_model->get_by_uid($uid);
        parent::ajaxReturn('box_list',$boxArray,'查询成功');

    }
    public function addBox()
    {
        log_message('debug','addBox '.print_r($_POST,true));
        $newData['uid'] = $this->input->post('uid');
        $newData['mid'] = $this->input->post('mid');
        if(!$this->medical_model->exist_id($newData['mid']))
        {
            parent::ajaxError('药品不存在');
        }

        if($this->box_model->get_by_uid_mid($newData['uid'],$newData['mid'])){
            parent::ajaxError('当前药品已经存在');
        }
        $warningString = $this->input->post('warning');
        parent::verifyDose($warningString);
        $newData['warning'] = $warningString;

        $remainString = $this->input->post('remain');
        parent::verifyDose($remainString);
        $newData['remain'] = $remainString;

        $newData['addtime'] = date('Y-m-d H:i:s');

        $newData['valid'] = $this->input->post('valid');
        parent::verifyValidCode($newData['valid']);

        $newID = $this->box_model->add($newData);
        if(!$newID){
            parent::ajaxError('添加药箱失败');
        }

        $newData['id']  = $newID;
        parent::ajaxReturn('box_list',array($newData),'添加药箱成功');
    }

    public function setBox()
    {
        log_message('debug','setBox '.print_r($_POST,true));
        $newData['id'] = $this->input->post('id');

        if(!$this->box_model->get_by_id($newData['id'])){
            parent::ajaxError('要修改的记录不存在');
        }

        if(isset($_POST['warning'])){
            $warningString = $this->input->post('warning');
            parent::verifyDose($warningString);
            $newData['warning'] = $warningString;
        }

        if(isset($_POST['remain'])) {
            $remainString = $this->input->post('remain');
            parent::verifyDose($remainString);
            $newData['remain'] = $remainString;
        }

        if(isset($_POST['valid'])){
            $newData['valid'] = $this->input->post('valid');
            parent::verifyValidCode($newData['valid']);
        }

        if(count($newData) == 1){
            parent::ajaxError('没有修改任何字段');
        }
        $this->box_model->update($newData);
        parent::ajaxReturn('prompt_list',array($this->box_model->get_by_id($newData['id'])),'修改药箱成功');
    }

    public function delBox(){
        $id  = $this->input->post('id');
        if(!$this->box_model->get_by_id($id)){
            parent::ajaxError('药箱不存在');
        }
        $this->box_model->del($id);
        if($this->box_model->get_by_id($id)){
            parent::ajaxError('删除失败');
        }
        parent::ajaxReturn('del_result',array('id'=>$id),'删除药箱成功');
    }



    //吃药
    public function getTakeHistory()
    {
        $date = $this->input->post('date');
        parent::checkDatetime($date,'Y-m-d');
        $uid = $this->input->post('uid');
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

    public function addTakeHistory()
    {
        $newData['uid'] = $this->input->post('uid');
        $newData['mid'] = $this->input->post('mid');
        if(!$this->medical_model->exist_id($newData['mid']))
        {
            parent::ajaxError('药品不存在');
        }
        $newData['dose'] = $this->input->post('dose');
        parent::verifyDose($newData['dose']);
        $newData['taketime'] = date('Y-m-d H:i:s');

        $newID = $this->take_history_model->add($newData);
        if($newID){
            $newData['id'] = $newID;
            $takeArrayGroupByDate[date('Y-m-d')] = array($newData);
            $boxData = $this->box_model->get_by_uid_mid($newData['uid'],$newData['mid']);
            if($boxData){
                $newBoxData['id'] = $boxData['id'];
                $boxRemain = $boxData['remain'];
                $newBoxData['remain'] = (float)$boxRemain -  (float)$newData['dose'];
                if($newBoxData['remain']>0){
                    $this->box_model->update($newBoxData);
                } else {
                    $this->take_history_model->del($newID);
                    parent::ajaxError('药品数量不足');
                }
            }
            parent::ajaxReturn('take_history_list',$takeArrayGroupByDate);
        } else {
            parent::ajaxError('添加吃药记录失败');
        }
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
        $data['SCR'] = $this->input->post('SCR');
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
        parent::ajaxReturn('detection_list', $arr);

    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
