<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: wang
 * Date: 15/1/7
 * Time: 上午11:29
 */



class Sms {
    // 配置项

// 发送验证码
    public  function getSMS($mobile,$code){
        $response = $this->postRequest( 'https://web.sms.mob.com/sms/verify', array(
            'appkey' => 'ad4423202d28',
            'phone' => $mobile,
            'zone' => '86',
            'code' => $code,
        ) );
        log_message('debug',$response);
        return $response;
    }

	/**
	 * 发起一个post请求到指定接口
	 *
	 * @param string $api 请求的接口
	 * @param array $params post参数
	 * @param int $timeout 超时时间
	 * @return string 请求结果
	 */
	private function postRequest( $api, array $params = array(), $timeout = 30 ) {
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $api );
		// 以返回的形式接收信息
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
		// 设置为POST方式
		curl_setopt( $ch, CURLOPT_POST, 1 );
		curl_setopt( $ch, CURLOPT_POSTFIELDS, http_build_query( $params ) );
		// 不验证https证书
		curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );
		curl_setopt( $ch, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/x-www-form-urlencoded;charset=UTF-8',
			'Accept: application/json',
		) );
		// 发送数据
		$response = curl_exec( $ch );
		// 不要忘记释放资源
		curl_close( $ch );
		return $response;
	}


}

?>