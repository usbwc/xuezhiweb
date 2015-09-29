<?php

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 14-4-10
 * Time: 上午11:16
 */
class unit_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }


    public function add($data){
        $this->db->insert('unit', $data);
        return $this->db->insert_id();
    }

    public function get_unit($unit_id){
        $this->db->where('id',$unit_id);
        $query = $this->db->get('unit');
        $result = $query->row_array();
        if($result){
            return $result['unit'];
        }
        return '';
    }
    public function get_all_unit(){

        $query = $this->db->get('unit');
        return $query->result_array();

    }


}