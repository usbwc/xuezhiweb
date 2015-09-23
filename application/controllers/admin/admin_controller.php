<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends CI_Controller {
    public function __construct(){
        parent::__construct();
        $this->load->library('session');
        $isAdmin = $this->session->userdata('isAdmin');
        if(!$isAdmin){
            header("Location: /admin/login");
        }
    }

    function getHospital(){
        $this->load->model('hospital_model');
        return $this->hospital_model->get_all();
    }
    function getDepartment(){
        $this->load->model('department_model');
        return $this->department_model->get_all();
    }


}