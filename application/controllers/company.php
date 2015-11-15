<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'XZ_Controller.php';

class Company extends XZ_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('company_model');
    }

    public function getCompanyList () {
        $result = $this->company_model->get_all_company();
        parent::ajaxReturn('company_list',$result);
    }

    




}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
