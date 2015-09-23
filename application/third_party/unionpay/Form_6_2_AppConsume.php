<?php
header ( 'Content-type:text/html;charset=gbk' );
include_once 'func/common.php';
include_once 'func/SDKConfig.php';
include_once 'func/secureUtil.php';
include_once 'func/httpClient.php';
include_once 'func/log.class.php';
/**
 * 消费交易-控件后台订单推送 
 */


/**
 *	以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己需要，按照技术文档编写。该代码仅供参考
 */
// 初始化日志
$log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
$log->LogInfo ( "============处理前台请求开始===============" );
// 初始化日志
$params = array(
		'version' => '5.0.0',						//版本号
		'encoding' => 'UTF-8',						//编码方式
		'certId' => getSignCertId (),				//证书ID
		'txnType' => '01',								//交易类型	
		'txnSubType' => '01',							//交易子类
		'bizType' => '000000',							//业务类型
		'frontUrl' =>  SDK_FRONT_NOTIFY_URL,  				//前台通知地址，控件接入的时候不会起作用
		'backUrl' => SDK_BACK_NOTIFY_URL,				//后台通知地址	
		'signMethod' => '01',		//签名方法
		'channelType' => '07',					//渠道类型
		'accessType' => '0',							//接入类型
		'merId' => '898340183980105',					//商户代码
		'orderId' => date('YmdHis'),					//商户订单号
		'txnTime' => date('YmdHis'),				//订单发送时间
		'txnAmt' => '100',								//交易金额
		'currencyCode' => '156',						//交易币种
		'defaultPayType' => '0001',						//默认支付方式	
		);


// 签名
sign ( $params );

$log->LogInfo ( "后台请求地址为>" . SDK_App_Request_Url );
// 发送信息到后台
$result = sendHttpRequest ( $params, SDK_App_Request_Url );
$log->LogInfo ( "后台返回结果为>" . $result );


//返回结果展示
$result_arr = coverStringToArray ( $result );

$html = create_html ( $result_arr, SDK_FRONT_NOTIFY_URL );
echo $html;
?>

