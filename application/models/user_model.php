<?php

/**
 * Created by PhpStorm.
 * User: wang
 * Date: 14-4-10
 * Time: 上午11:16
 */
class user_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    public function exist_user($uid)
    {
        $this->db->where('id',$uid);
        $query = $this->db->get('user');
        $user = $query->row_array();
        if($user){
            return true;
        }
        return false;
    }

    public function exist_mobile($mobile)
    {
        $this->db->select('id');
        $query = $this->db->get_where('user',array('mobile'=>$mobile));
        $user =  $query->row_array();
        if($user){
            return true;
        }
        return false;
    }

    public function get_user_detail_by_mobile($mobile)
    {
        $query = $this->db->get_where('user',array('mobile'=>$mobile));
        return $query->row_array();
    }

    public function add_user ($userData)
    {
        $this->db->insert('user', $userData);
        return $this->db->insert_id();
    }













    public function validate($phone,$pwd)
    {
        //TODO select field
        $this->db->where('phone',$phone);
        $query = $this->db->get('user');
        $user = $query->row_array();
        if($user){
            if(password_verify($pwd,$user['pwd']))
            {
                return $user;
            }
        }
        return false;
    }









    public function get_simple_user($uid)
    {
        $this->db->select('nick,username,avatar,type');
        $query = $this->db->get_where('user',array('id'=>$uid));
        return $query->row_array();
    }

    public function get_simple_user_by_username($username)
    {
        $this->db->select('id,nick,username,avatar,type');
        $query = $this->db->get_where('user',array('username'=>$username));
        return $query->row_array();
    }

    public function get_uid_by_name($username){

        $this->db->select('id');
        $query = $this->db->get_where('user',array('username'=>$username));
        $user =  $query->row_array();
        return $user['id'];
    }



    public function update_pwd($id,$hash)
    {
        $data = array(
            'pwd' => $hash,
        );

        $this->db->where('id', $id);
        $this->db->update('user', $data);
    }
    public function update_pwd_by_mobile($mobile,$hash){
        $data = array(
            'pwd' => $hash,
        );

        $this->db->where('mobile', $mobile);
        $this->db->update('user', $data);
    }

    public function update_user($data){
        $this->db->where('id', $data['id']);
        $this->db->update('user', $data);
    }



}