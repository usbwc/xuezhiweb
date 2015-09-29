<?php

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 14-4-10
 * Time: 上午11:16
 */
class take_prompt_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }


    public function add($data){
        $this->db->insert('take_prompt', $data);
        return $this->db->insert_id();
    }

    public function get_by_tpid($id){
        $this->db->where('id',$id);
        $query = $this->db->get('take_prompt');
        return $query->row_array();
    }

    public function get_by_rpid($rpid){
        $this->db->where('rpid',$rpid);
        $query = $this->db->get('take_prompt');
        return $query->result_array();
    }

    public function update($data){
        $this->db->where('id',$data['id']);
        $this->db->update('take_prompt',$data);
    }
}