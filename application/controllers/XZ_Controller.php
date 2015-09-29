<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 15/9/7
 * Time: 下午2:39
 */
class XZ_Controller extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model('user_model');
	}

	public function ajaxReturn($type,$data,$msg='',$status=200)
	{
		$version = $this->config->item('version');
		$response = array('status'=>$status,'msg'=>$msg, 'type'=>$type ,$type=>$data,'version'=>$version);
		log_message('debug',print_r($response,true));
		$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
		exit();
	}
	public function ajaxError($msg='',$status=-1)
	{
		$response = array('status'=>$status,'msg'=>$msg);
		$this->output->set_content_type('application/json', 'utf-8')->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES))->_display();
		exit();
	}

	public function verifyUser()
	{
		$uid = $this->input->post('uid');
		$pwd = $this->input->post('pwd');
		$time = $this->input->post('timestamp');
		$timeEncrypt = $this->input->post('timesign');
		if(!$uid||!$pwd){
			$this->ajaxError('参数错误,请保证手机时间为北京时间,或者尝试重新登录');
		}
		$this->load->library('Crypt3Des');
		$timeOri = $this->crypt3des->decrypt($timeEncrypt);
		if($time!=$timeOri){
			$this->ajaxError('参数错误,请保证手机时间为北京时间,或者尝试重新登录');
		}
		$timeDis = time()-$time;
		if(abs($timeDis)>600){
			$this->ajaxError('参数错误,请保证手机时间为北京时间,或者尝试重新登录');
		}

		$decryptPwd = $this->crypt3des->decrypt($pwd);
		$result = $this->user_model->validate_by_id($uid,$decryptPwd);
		if(!$result){
			$this->ajaxError('没有此用户或者密码错误');
		}
	}

}