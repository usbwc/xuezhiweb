<?php

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 14-4-10
 * Time: 上午11:16
 */
class remain_prompt_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }


    public function add($data){
        $this->db->insert('remain_prompt', $data);
        return $this->db->insert_id();
    }

    public function get_by_rpid($id){
        $this->db->where('id',$id);
        $query = $this->db->get('remain_prompt');
        return $query->row_array();
    }

    public function get_by_uid($uid){
        $this->db->where('uid',$uid);
        $query = $this->db->get('remain_prompt');
        return $query->result_array();
    }
}