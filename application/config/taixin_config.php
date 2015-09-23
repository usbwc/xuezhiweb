<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
$config['star_level'] =
    array(
        '1'=>'一星',
        '2'=>'二星',
        '3'=>'三星'
    );
$config['comment_level'] =
    array(
        '1'=>'非常满意',
        '2'=>'满意',
        '3'=>'一般'
    );
$config['nursing_level'] =
    array(
        '1'=>'初级',
        '2'=>'中级',
        '3'=>'高级'
    );
$config['nurse_service_status'] =
    array(
        '1'=>'待命',
        '2'=>'服务中',
        '3'=>'暂不能服务'
    );
$config['nurse_service_status_key_standby'] = 1;
$config['nurse_service_status_key_in_service'] = 2;
$config['nurse_service_status_key_unable'] = 3;

$config['version'] = 1;

$config['schedule_type_day'] = 1;
$config['schedule_type_night'] = 2;
$config['schedule_type_all'] = 3;

$config['patient_status_all'] = 1;
$config['patient_status_half'] = 2;
$config['patient_status_cannt'] = 3;


$config['schedule_status_temp'] = 1;
$config['schedule_status_confirm'] = 2;


$config['addon_wait_pay'] = 1;
$config['addon_payed'] = 2;


$config['order_status'] =
    array(
        '1'=>'WAIT_PAYMENT',
        '2'=>'WAIT_SERVE',
        '3'=>'IN_SERVICE',
        '4'=>'WAIT_COMMENT',
        '5'=>'FINISHED',
        '6'=>'CANCELED',
        '7'=>'WAIT_CASH'
    );

$config['order_status_reverse'] =
    array(
        'WAIT_PAYMENT'=>'1',
        'WAIT_SERVE'=>'2',
        'IN_SERVICE'=>'3',
        'WAIT_COMMENT'=>'4',
        'FINISHED'=>'5',
        'CANCELED'=>'6',
        'WAIT_CASH'=>'7'
    );
$config['order_status_chinese'] =
    array(
        '1'=>'待支付',
        '2'=>'等待服务',
        '3'=>'服务中',
        '4'=>'等待评价',
        '5'=>'结束',
        '6'=>'取消',
        '7'=>'等待现金支付'
    );

$config['refund_init'] = 1;
$config['refund_done'] = 2;