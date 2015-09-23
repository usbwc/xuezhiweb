<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'admin_controller.php';

class dashboard extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
    }


    public  function  index(){
        $allHospital = parent::getHospital();
        $this->load->view('admin/header.html');
        $this->load->view('admin/dashboard.html');
        $this->load->view('admin/footer.html');
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
