<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'admin_controller.php';

class db extends Admin_Controller {


	public  function getCarer()
	{
		$table = 'tx_carer';

		$primaryKey = 'id';
		$columns = array(
			array(
				'db' => '`c`.`id`',
				'dt' => 'id',
				'formatter' => function( $d, $row ) {
					return 'row_'.$d;
				},
				'field' => 'id'
			),
			array( 'db' => 'hospital_id', 'dt' => 'hospital_id' ,'field' => 'hospital_id'),
			array( 'db' => 'hospital_name', 'dt' => 'hospital_name' ,'field' => 'hospital_name'),
			array( 'db' => 'name', 'dt' => 'name' ,'field' => 'name'),
			array( 'db' => 'gender',  'dt' => 'gender' ,'field' => 'gender'),
			array( 'db' => 'star_level', 'dt' => 'star_level' ,'field' => 'star_level',
				'formatter' => function( $d, $row ) {
					$star_level_array = $this->config->item('star_level');
					foreach($star_level_array as $k=>$v){
						if($k == $d){
						return $v;
						}
					}
					return '';
			}),
			array( 'db' => 'age',   'dt' => 'age' ,'field' => 'age'),
			array( 'db' => 'hometown',   'dt' => 'hometown' ,'field' => 'hometown'),
			array( 'db' => 'nursing_exp',   'dt' => 'nursing_exp' ,'field' => 'nursing_exp'),
			array( 'db' => 'nursing_level',   'dt' => 'nursing_level' ,'field' => 'nursing_level',
				'formatter' => function( $d, $row ) {
					$nurse_service_level_array = $this->config->item('nursing_level');
					foreach($nurse_service_level_array as $k=>$v){
						if($k == $d){
							return $v;
						}
					}
					return '';
				}
				),
			array( 'db' => 'service_charge_per_all_care', 'dt' => 'service_charge_per_all_care' ,'field' => 'service_charge_per_all_care'),
			array( 'db' => 'service_charge_per_all_half_care', 'dt' => 'service_charge_per_all_half_care' ,'field' => 'service_charge_per_all_half_care'),
			array( 'db' => 'service_charge_per_all_cannt_care', 'dt' => 'service_charge_per_all_cannt_care' ,'field' => 'service_charge_per_all_cannt_care'),
			array( 'db' => 'service_charge_per_day_care', 'dt' => 'service_charge_per_day_care' ,'field' => 'service_charge_per_day_care'),
			array( 'db' => 'service_charge_per_day_half_care', 'dt' => 'service_charge_per_day_half_care' ,'field' => 'service_charge_per_day_half_care'),
			array( 'db' => 'service_charge_per_day_cannt_care', 'dt' => 'service_charge_per_day_cannt_care' ,'field' => 'service_charge_per_day_cannt_care'),
			array( 'db' => 'service_charge_per_night_care', 'dt' => 'service_charge_per_night_care' ,'field' => 'service_charge_per_night_care'),
			array( 'db' => 'service_charge_per_night_half_care', 'dt' => 'service_charge_per_night_half_care' ,'field' => 'service_charge_per_night_half_care'),
			array( 'db' => 'service_charge_per_night_cannt_care', 'dt' => 'service_charge_per_night_cannt_care' ,'field' => 'service_charge_per_night_cannt_care'),

			array( 'db' => 'nurse_service_status',   'dt' => 'nurse_service_status' ,'field' => 'nurse_service_status'),
			array( 'db' => 'job_num',  'dt' => 'job_num' ,'field' => 'job_num'),
			array( 'db' => 'language_level', 'dt' => 'language_level' ,'field' => 'language_level'),
			array( 'db' => 'education', 'dt' => 'education' ,'field' => 'education'),
			array( 'db' => 'nation',  'dt' => 'nation' ,'field' => 'nation'),
			array( 'db' => 'intro',  'dt' => 'intro' ,'field' => 'intro'),
			array( 'db' => 'certificate',  'dt' => 'certificate' ,'field' => 'service_content'),
			array( 'db' => 'c.service_content',   'dt' => 'service_content','field' => 'service_content'),
			array( 'db' => '`c`.`avatar`',  'dt' => 'avatar','field' => 'avatar'),
			array( 'db' => 'GROUP_CONCAT(de.department_name)',  'dt' => 'departments','field' => 'GROUP_CONCAT(de.department_name)'),
		);

		$sql_details = array(
			'user' => 'root',
			'pass' => 'root',
			'db'   => 'taixin',
			'host' => 'localhost'
		);


		require('ssp.customized.class.php' );

		$joinQuery = "FROM `tx_carer` AS `c`
		left JOIN `tx_carer_department` AS `d` ON (`c`.`id` = `d`.`cid`)
		left JOIN `tx_department` AS `de` on (`de`.`id` = `d`.`did`)
		left JOIN `tx_hospital` AS `h` on (`h`.`id` =`c`.`hospital_id`)";
		//$extraWhere = "`u`.`salary` >= 90000";
		$extraWhere = '';

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere,'c.id' )
		);
	}

	public function getHospital()
	{
		$table = 'tx_hospital';

		$primaryKey = 'id';


		$columns = array(
			array('db' => 'id', 'dt' => 0),
			array('db' => 'hospital_name', 'dt' => 1)
		);

		$sql_details = array(
			'user' => 'root',
			'pass' => 'root',
			'db' => 'taixin',
			'host' => 'localhost'
		);

		require('ssp.class.php');
		echo json_encode(
			SSP::simple($_GET, $sql_details, $table, $primaryKey, $columns)
		);
	}

	public function getOrder()
	{
		$table = 'tx_order';

		$primaryKey = 'id';

		$columns = array(
			array(
				'db' => '`o`.`id`',
				'dt' => 'id',
				'formatter' => function( $d, $row ) {
					return 'row_'.$d;
				},
				'field' => 'id'
			),
			array( 'db' => '`o`.`show_id`',  'dt' => 'show_id','field' => 'show_id'),
			array( 'db' => '`o`.`order_time`',  'dt' => 'order_time','field' => 'order_time'),
			array( 'db' => '`o`.`order_mobile`',  'dt' => 'mobile1','field' => 'order_mobile'),
			array( 'db' => '`o`.`cared_name`',   'dt' => 'cared_name' ,'field' => 'cared_name'),
			array( 'db' => '`o`.`cared_gender`',     'dt' => 'cared_gender','field' => 'cared_gender'),
			array( 'db' => '`h`.`hospital_name`',     'dt' => 'hospital_name','field' => 'hospital_name'),
			array( 'db' => '`u`.`mobile`',     'dt' => 'mobile2','field' => 'mobile'),
			array( 'db' => '`o`.`status`',     'dt' => 'status','field' => 'status'),
			array( 'db' => '`o`.`cid`',     'dt' => 'cid','field' => 'cid'),

		);

		$sql_details = array(
			'user' => 'root',
			'pass' => 'root',
			'db'   => 'taixin',
			'host' => 'localhost'
		);

		require('ssp.customized.class.php' );

		$joinQuery = "FROM `tx_order` AS `o` JOIN `tx_user` AS `u` ON (`u`.`id` = `o`.`uid`) JOIN  `tx_hospital` AS `h` ON (`h`.`id` = `o`.`hospital_id`)";
		//$extraWhere = "`u`.`salary` >= 90000";
		$extraWhere = '';

		echo json_encode(
			SSP::simple( $_GET, $sql_details, $table, $primaryKey, $columns, $joinQuery, $extraWhere )
		);

	}

}