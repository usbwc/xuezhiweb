<?php
header ( 'Content-type:text/html;charset=GBK' );
include_once $_SERVER ['DOCUMENT_ROOT'] . '/upacp_sdk_php/gbk/func/common.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/upacp_sdk_php/gbk/func/SDKConfig.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/upacp_sdk_php/gbk/func/secureUtil.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/upacp_sdk_php/gbk/func/httpClient.php';
include_once $_SERVER ['DOCUMENT_ROOT'] . '/upacp_sdk_php/gbk/func/log.class.php';

/**
 * 文件传输类交易
 */
 
 
/**
 *	以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己需要，按照技术文档编写。该代码仅供参考
 */


// 初始化日志
$log = new PhpLog ( SDK_LOG_FILE_PATH, "PRC", SDK_LOG_LEVEL );
$log->LogInfo ( "===========处理后台请求开始============" );

$params = array(
		'version' => '5.0.0',				//版本号
		'encoding' => 'GBK',		//编码方式
		'certId' => getSignCertId (),			//证书ID
		'txnType' => '76',				//交易类型	
		'signMethod' => '01',		//签名方法
		'txnSubType' => '01',				//交易子类
		'bizType' => '000000',			//业务类型
		'accessType' => '0',		//接入类型
		'merId' => '898340183980105',			//商户代码，此demo商户号无权限，请替换实际商户号测试
		'settleDate' => '0106',				//清算日期
		'txnTime' => date('YmdHis'),				//订单发送时间
		'fileType' => '00',					//文件类型			 	//保留域
	);

// 签名
sign ( $params );

$log->LogInfo ( "后台请求地址为>" . SDK_FILE_QUERY_URL );
// 发送信息到后台
$result = sendHttpRequest ( $params, SDK_FILE_QUERY_URL );
$log->LogInfo ( "后台返回结果为>" . $result );

//返回结果展示
$result_arr = coverStringToArray ( $result );
// 处理文件
deal_file ( $result_arr );

$html = create_html ( $result_arr, SDK_FRONT_NOTIFY_URL );
echo $html;
?>
