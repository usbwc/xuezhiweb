<?php

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 14-4-10
 * Time: 上午11:16
 */
class company_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }


    public function add($data){
        $this->db->insert('company', $data);
        return $this->db->insert_id();
    }


    public function get_all_company(){

        $query = $this->db->get('company');
        return $query->result_array();

    }


}