<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once 'admin_controller.php';

class Order extends Admin_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_model');
    }

    public  function  index(){
        $statusArray = $this->config->item('order_status_chinese');

        $data['status_array'] = json_encode($statusArray);
        $this->load->view('admin/header.html');
        $this->load->view('admin/order.html',$data);
        $this->load->view('admin/footer.html');
    }


}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
