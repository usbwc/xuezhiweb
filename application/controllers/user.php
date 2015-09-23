<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }
    public  function  index(){
        $flower = $this->cache->get("test");
        if(!$flower){
            $this->cache->set("test","v",'5');
            echo "no";
        } else {
            echo "yes ".$flower;
        }
    }



    public function loginAction() {
        log_message('debug','loginAction '.print_r($_POST,true));

        $this->load->library('Crypt3Des');
        //验证手机号
        $phone = $this->input->post('phone');
        $isValidatePhone = $this->util->isMobile($phone);
        if(!$isValidatePhone){
            echo json_encode(array('status'=>-1,'msg'=>'手机号格式错误'));
            exit();
        }
        $code = $this->input->post('code');

        //$decryptPwd = $this->crypt3des->decrypt($pwd);
        $this->load->library('sms');
        $result = json_decode($this->sms->getSMS($phone,$code) ,true);
        if($result['status'] == 200){
            $hasReg = $this->user_model->exist_mobile($phone);
            if($hasReg){
                //注册过
                $userDetail = $this->user_model->get_user_detail_by_mobile($phone);
                unset($userDetail['reg_time']);
                unset($userDetail['reg_ip']);
                echo json_encode(array('status'=>200,'user'=>$userDetail));
            } else {
                //新用户
                $newUser['mobile'] = $phone;
                $newUser['code'] = substr(md5(time()),0,10);
                $newUser['reg_time'] = time();
                $newUser['reg_ip']= $this->input->ip_address();
                $userID = $this->user_model->add_user($newUser);
                $userDetail = $this->user_model->get_user_detail_by_mobile($phone);
                unset($userDetail['reg_time']);
                unset($userDetail['reg_ip']);
                echo json_encode(array('status'=>200,'user'=>$userDetail));
            }

        } else {
            echo json_encode(array('status'=>-1,'msg'=>'验证码错误'));
        }

    }






}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
