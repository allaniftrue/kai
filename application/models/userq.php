<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

Class Userq extends CI_Model {

	public function user_profile($id="") {

            $is_login = $this->session->userdata('is_login');
            $uid = empty($uid) ? $this->session->userdata('uid') : $id;

            if($is_login === TRUE) {
                
                $sql = $this->db->query("
                                            SELECT a.username,a.date,a.expiration,a.status,b.* FROM pre_users a, pre_profile b
                                            WHERE
                                            a.id=b.id AND a.id=$uid
                ");

                return $sql->result();
            }
	}
}