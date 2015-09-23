<?php
header ( 'Content-type:text/html;charset=GBK' );
 include_once $_SERVER ['DOCUMENT_ROOT'] . '/upacp_sdk_php/gbk/func/common.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/upacp_sdk_php/gbk/func/SDKConfig.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/upacp_sdk_php/gbk/func/secureUtil.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/upacp_sdk_php/gbk/func/httpClient.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/upacp_sdk_php/gbk/func/log.class.php';

/**
 *	查询交易
 */

/**
 *	以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己需要，按照技术文档编写。该代码仅供参考
 */


// 初始化日志
$log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
$log->LogInfo ( "===========处理后台请求开始============" );

$params = array(
		'version' => '5.0.0',			//版本号
		'encoding' => 'GBK',				//编码方式
		'certId' => getSignCertId (),	//证书ID	
		'signMethod' => '01',		//签名方法
		'txnType' => '00',					//交易类型	
		'txnSubType' => '00',				//交易子类
		'bizType' => '000000',			//业务类型
		'accessType' => '0',				//接入类型
		'channelType' => '07',					//渠道类型
		'orderId' => '20150106215110',			//被查询的交易的订单号
		'merId' => '898340183980105',			//商户代码
		'txnTime' => '20150106212559',				//被查询的交易的订单发送时间
	);


// 签名
sign ( $params );

$log->LogInfo ( "后台请求地址为>" . SDK_SINGLE_QUERY_URL );
// 发送信息到后台
$result = sendHttpRequest ( $params, SDK_SINGLE_QUERY_URL );
$log->LogInfo ( "后台返回结果为>" . $result );


//返回结果展示
$result_arr = coverStringToArray ( $result );

$html = create_html ( $result_arr, SDK_FRONT_NOTIFY_URL );
echo $html;
?>

