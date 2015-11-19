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
        $this->load->model('no_take_history_model');
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
        $unit = $this->input->post('unit_id');
        if(!$this->unit_model->get_unit($unit)){
            parent::ajaxError('单位不存在');
        }
        $newData['unit_id'] = $unit;

        $numberPerDay = $this->input->post('num_per_day');
        parent::verifyDose($numberPerDay,'numberPerDay不合法');
        if($numberPerDay>10){
            parent::ajaxError('numberPerDay太大');
        }
        $numberPerDay = intval($numberPerDay);

        $newData['precaution'] = $this->input->post('precaution');
        $newData['valid'] = $this->input->post('valid');
        parent::verifyValidCode($newData['valid']);

        $newData['addtime'] = date('Y-m-d H:i:s');

        $promptArray = array();
        for($i=1;$i<=$numberPerDay;$i++)
        {
            $timeString = $this->input->post('time_'.$i);
            parent::checkDatetime($timeString,'H:i:s');
            $doseString = $this->input->post('dose_'.$i);
            parent::verifyDose($doseString);

            $newData['dose'] = $doseString;
            $newData['time'] = date('H:i:s',strtotime($timeString));
            $promptArray[] = $newData;
        }

        foreach($promptArray as &$prompt){
            $newID = $this->prompt_model->add($prompt);
            if(!$newID){
                parent::ajaxError('添加失败');
            }
            $prompt['id'] = $newID;
        }

        parent::ajaxReturn('prompt_list',$promptArray,'添加提醒成功');
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
        $newData['mid'] = $mid;

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

        $warningString = $this->input->post('warning');
        parent::verifyDose($warningString);
        $newData['warning'] = $warningString;

        $remainString = $this->input->post('remain');
        parent::verifyDose($remainString);
        $newData['remain'] = $remainString;

        $existBox = $this->box_model->get_by_uid_mid($newData['uid'],$newData['mid']);
        if($existBox){
            $newBoxData['id'] = $existBox['id'];
            $boxRemain = $existBox['remain'];
            $newBoxData['remain'] = (float)$boxRemain +  (float)$newData['remain'];
            $this->box_model->update($newBoxData);
            $existBox['remain'] = $newBoxData['remain'];
            parent::ajaxReturn('box_list',array($existBox),'药箱存在,更新remain成功');
        }


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
        $oriTakeArray = $this->take_history_model->get_by_uid_month($uid,$date);
        $takeArrayGroupByDate = array();

        foreach($oriTakeArray as $oriTake){
            $date = date('Y-m-d',strtotime($oriTake['taketime']));
            $takeArrayGroupByDate[$date][] = $oriTake;
        }

        $resultArray = array();
        foreach($takeArrayGroupByDate as $key=>$data) {
            $resultArray[] = array('date'=>$key,'take_list'=>$data);
        }
        if(count($resultArray) == 0){
            $resultArray[] = array('date'=>$date,'take_list'=>array());
        }
        parent::ajaxReturn('take_history_list',$resultArray);
    }

    public function addTakeHistory()
    {
        $newData['uid'] = $this->input->post('uid');
        $newData['mid'] = $this->input->post('mid');
        $newData['pid'] = $this->input->post('pid');
        if(!$this->prompt_model->get_by_id($newData['pid'])){
            parent::ajaxError('提醒不存在');
        }
        if(!$this->medical_model->exist_id($newData['mid']))
        {
            parent::ajaxError('药品不存在');
        }
        $newData['dose'] = $this->input->post('dose');
        parent::verifyDose($newData['dose']);
        $newData['taketime'] = date('Y-m-d H:i:s');

        $existPromptRecord = $this->take_history_model->get_by_pid_today($newData['pid']);
        if($existPromptRecord){
            parent::ajaxError('今天已经添加过吃药本记录了');
        }
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
                    parent::ajaxError('药品数量不足',-2,array('mid'=>$newData['mid']));
                }
            }
            parent::ajaxReturn('take_history_list',$takeArrayGroupByDate);
        } else {
            parent::ajaxError('添加吃药记录失败');
        }
    }

    public function getDetection()
    {
        $uid = $this->input->get_post('uid');
        $arr = $this->detection_model->get_all($uid);
        parent::ajaxReturn('detection_list', $arr);

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
    public function setDetection()
    {
        $data['id'] = $this->input->post('id');
        $exist = $this->detection_model->get_by_id($data['id']);
        if(!$exist){
            parent::ajaxError('不存在');
        }
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
        $this->detection_model->update($data);
        parent::ajaxReturn('detection_list', array($this->detection_model->get_by_id($data['id'])));
    }

    public function getNoTakeHistory()
    {
        $uid = $this->input->post('uid');
        $date = $this->input->post('date');
        parent::checkDatetime($date,'Y-m-d');
        $resultArray = $this->no_take_history_model->get_by_uid_month($uid,$date);
        parent::ajaxReturn('no_take_history_list',array('date'=>$date,'no_take_list'=>$resultArray));
    }

    public function refresh()
    {
        $date = $this->input->get('date');
        if(!$date){
            $date = date('Y-m-d',time()-3600*24);
        }
        log_message('debug','$refresh = '.$date);

        $promptArray = $this->prompt_model->get_all_valid();
        $noTakePromptArray = array();
        foreach ($promptArray as $prompt){
            $uid = $prompt['uid'];
            $pid = $prompt['id'];
            $result = $this->take_history_model->get_by_uid_pid($uid,$pid,$date);
            if(!$result){
                $noTakePromptArray[] = array('id'=>$pid,'uid'=>$uid,'time'=>$prompt['time']);
            }
        }
        log_message('debug','$noTakePromptArray = '.print_r($noTakePromptArray,true));
         foreach($noTakePromptArray as $prompt){
            $newData['pid']=$prompt['id'];
            $newData['uid']=$prompt['uid'];
            $newData['no_take_date'] = $date.' '.$prompt['time'];
            $exist = $this->no_take_history_model->get_by_uid_pid($newData['uid'],$newData['pid'], $date);
             if(!$exist){
                 log_message('debug','no_take_history_model add = '.print_r($newData,true));
                 $this->no_take_history_model->add($newData);
            };
        }
        //parent::ajaxReturn('no_',$noTakePromptArray);
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
