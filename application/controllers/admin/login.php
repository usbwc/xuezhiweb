<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
    }
    public  function  index(){


        $this->load->view('admin/login.html');


    }
    public function loginAction()
    {
        $pass = $this->input->post('name');
        $json =$this->input->post('json');

        $this->load->library('Aes');
        $result = $this->aes->cryptoJsAesDecrypt($pass,$json);

        if($pass == 'admin' && $result == 'admin') {
            $this->load->library('session');
            $this->session->set_userdata(array('isAdmin'=>true));
            echo json_encode(array('status'=>0));
        } else {
            echo json_encode(array('status'=>-1,'msg'=>'登陆失败'));
        }
    }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
