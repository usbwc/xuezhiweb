<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 15/1/7
 * Time: 上午11:29
 */



class Util {

	/**
	 * @param $mobile
	 * @return bool $mobile
	 */
	public function isMobile($mobile) {
		if (!is_numeric($mobile)) {
			return false;
		}
		return preg_match('#^13[\d]{9}$|^14[5,7]{1}\d{8}$|^15[^4]{1}\d{8}$|^17[0,6,7,8]{1}\d{8}$|^18[\d]{9}$#', $mobile) ? true : false;
	}


	public function validateInt($int){
		if(is_int($int)){
			if($int>=0){
				return true;
			}
		} else{
			$intValue = intval($int);
			if($intValue.'' === $int.''){
				if($intValue>0){
					return true;
				}
			}
		}
		return false;
	}

	public function ajaxError($msg ,$status = -1){
		$data = array('status'=>$status,'msg'=>$msg);
		echo json_encode($data);
		exit();
	}

}

?>