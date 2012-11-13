<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Dashboard extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->model("Userq");
		$this->load->library("Mlib_headers");
		$this->load->library("Mlib_trac");
		$this->mlib_trac->trac_login();
	}

	public function index() {
            
                $data['userinfo'] = $this->Userq->user_profile($this->session->userdata('uid'));
		$this->load->view('dashboard_view',$data);
	}
}