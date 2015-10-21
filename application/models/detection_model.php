<?php

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 14-4-10
 * Time: 上午11:16
 */
class detection_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }



    public function get_all($uid)
    {
        $this->db->where('uid',$uid);
        $query = $this->db->get('detection');
        return $query->result_array();
    }

    public function get_by_id($id){
        $this->db->where('id',$id);
        $query = $this->db->get('detection');
        return $query->row_array();
    }

    public function add($data){
        $this->db->insert('detection', $data);
        return $this->db->insert_id();
    }

    public function update($data){
        $this->db->where('id',$data['id']);
        $this->db->update('detection',$data);
    }
}