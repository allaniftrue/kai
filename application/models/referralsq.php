<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

Class Referralsq extends CI_Model {
    public function __construct() {
        parent::__construct();
    }
    
    public function my_referrals($num,$offset) {
        
        $username=!empty($username) ? $username : $this->session->userdata('username');
        
        $this->db->select('*');
        $this->db->from('pre_users');
        $this->db->where("referrer",$username);
        $this->db->join('pre_profile', 'pre_users.id = pre_profile.id');
        $this->db->limit($num,$offset);
        $sql = $this->db->get();
        
        return $sql->result();
        
    }
    
    public function my_referrals_total() {
        $username=!empty($username) ? $username : $this->session->userdata('username');
        
        $this->db->select('*');
        $this->db->from('pre_users');
        $this->db->where("referrer",$username);
        $this->db->join('pre_profile', 'pre_users.id = pre_profile.id');
        $sql = $this->db->get();
        
        return $sql->num_rows();
        
    }
}