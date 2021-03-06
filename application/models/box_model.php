<?php

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 14-4-10
 * Time: 上午11:16
 */
class box_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }


    public function add($data){
        $this->db->insert('medical_box', $data);
        return $this->db->insert_id();
    }

    public function get_by_id($id){
        $this->db->where('id',$id);
        $query = $this->db->get('medical_box');
        return $query->row_array();
    }

    public function get_by_uid_mid($uid,$mid){
        $this->db->where('uid',$uid);
        $this->db->where('mid',$mid);
        $query = $this->db->get('medical_box');
        return $query->row_array();
    }


    public function get_by_uid($uid){
        $this->db->where('uid',$uid);
        $query = $this->db->get('medical_box');
        return $query->result_array();
    }



    public function update($data){
        $this->db->where('id',$data['id']);
        $this->db->update('medical_box',$data);
    }

    public function del($id)
    {
        $this->db->delete('medical_box', array('id' => $id));
    }
}