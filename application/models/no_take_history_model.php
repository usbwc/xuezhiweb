<?php

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 14-4-10
 * Time: 上午11:16
 */
class no_take_history_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }


    public function add($data){
        $this->db->insert('no_take_history', $data);
        return $this->db->insert_id();
    }

    public function get_by_uid_month($uid,$date){
        $this->db->where('uid',$uid);
        $this->db->where('DATE_FORMAT(no_take_date,\'%Y-%m\')',date('Y-m',strtotime($date)));
        $this->db->order_by('no_take_date','asc');
        $query = $this->db->get('no_take_history');
        return $query->result_array();
    }

    public function get_by_uid_pid($uid,$pid,$date){
        $this->db->where('uid',$uid);
        $this->db->where('pid',$pid);
        $this->db->where('DATE_FORMAT(no_take_date,\'%Y-%m-%d\')',date('Y-m-d',strtotime($date)));
        $query = $this->db->get('no_take_history');
        return $query->row_array();
    }


}