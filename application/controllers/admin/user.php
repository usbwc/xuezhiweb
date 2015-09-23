<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }
    public  function  index(){
        $this->load->view('admin/name');
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
