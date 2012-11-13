<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
Class Logsq extends CI_Model{

	public function __construct() {
		
	}

	public function login_log($action) {

		$is_login = $this->session->userdata("is_login");

		if($is_login === TRUE) {

			$user_data = $this->session->all_userdata();

			$data = array(
							'id'=>$this->session->userdata("uid"),
							'ip_address'=>$user_data['ip_address'],
							'user_agent'=>$user_data['user_agent'],
							'last_activity'=>$user_data['last_activity'],
							'computer_name'=>gethostname(),
							'action'=>$action
			);

			$this->db->insert("pre_logs", $data);
		} else {
			redirect(base_url(),"refresh");
		}
	}

}