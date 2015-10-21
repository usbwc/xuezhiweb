<?php

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 14-4-10
 * Time: 上午11:16
 */
class medical_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function exist_id($hid){
        $this->db->where('id',$hid);
        $query = $this->db->get('medical');
        $user = $query->row_array();
        if($user){
            return true;
        }
        return false;
    }

    public function get_by_id($id){
        $this->db->select('id as mid,name,unit,dose,precaution');
        $this->db->where('id',$id);
        $query = $this->db->get('medical');
        return $query->row_array();
    }


    public function get_all()
    {
        $this->db->select('id as mid,name,unit,dose,precaution');
        $query = $this->db->get('medical');
        return $query->result_array();
    }

    public function del($id)
    {
        $this->db->delete('medical', array('id' => $id));
    }

    public function update($data)
    {
        $this->db->where('id', $data['id']);
        $this->db->update('medical', $data);
    }

    public function exist($name)
    {
        $this->db->where('name', $name);
        $query = $this->db->get('medical');
        return $query->row_array();

    }
    public function add($name){
        $this->db->insert('medical', array('name'=>$name));
        return $this->db->insert_id();
    }

}