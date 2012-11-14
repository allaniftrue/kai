<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Home extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->helper('string');
	}

	public function index(){

		$data['unique_name'] = uniqid(); 
		$csrf_session = $this->session->userdata('hash_value');
		$hash = ! empty($csrf_session) ? $csrf_session : random_string('alnum', 64);
                
                $message = $this->session->userdata("message");
                $alert_type = $this->session->userdata("alert_type");
		
		if(empty($csrf_session)) {
			$array = array(
						'hash_value'=>$hash
			);
			
			$this->session->set_userdata($array);
		}
                
                if(!empty($message) && ! empty($alert_type)) {
                    $data["message"] = $message;
                    $data["alert_type"] = $alert_type;
                }
                
		$data['hash'] = $this->session->userdata('hash_value');
		$this->load->view('home_view',$data);
	}
}