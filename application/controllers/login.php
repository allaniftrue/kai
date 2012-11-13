<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}

	public function auth() {
		
		$this->load->library('Mlib_sec');

		$username = $this->input->post('username');
		$password = $this->input->post('password');
		$csrf_val = $this->input->post('csrf_name');
		$csrf_session = $this->session->userdata('hash_value');

		if($csrf_session === $csrf_val) {
			
			$sql = $this->db->get_where('users', array('username'=>$username));
			$num_rows = $sql->num_rows();

			if($num_rows === 1) {
				
				$result = $sql->result(); 

				$format = PBKDF2_HASH_ALGORITHM.":".PBKDF2_ITERATIONS.":".$result[0]->salt.":".$result[0]->password;
				$is_valid = $this->mlib_sec->validate_password($password,$format);
				
				if($is_valid === TRUE) {

					$this->load->model("Logsq");
					$array = array(
                                                        'uid'=>$result[0]->id,
                                                        'username'=>$username,
                                                        'is_login'=>TRUE
					);
					$this->session->set_userdata($array);
					/* Log */
					$this->Logsq->login_log("Login");

					redirect(base_url()."dashboard", "refresh");
				} else {
					#show error invalid username/password
				}

			} else {
				#show error invalid username/password
			}
			
			$this->session->unset_userdata('hash_value');

		} else {
			redirect(base_url(),'redirect');
		}

	}
}