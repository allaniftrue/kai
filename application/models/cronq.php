<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 
Class Cronq extends CI_Model{
    
    public function __construct() {
        parent::__construct();
        global $unverifieds;
        global $hasnotpaid;
        
//        $unverifieds = $this->total_unverified();
//        $hasnotpaid = $this->no_payments();
    }
    
    private function total_unverified() {
        $sql = $this->db->get_where("pre_payments",array("status"=>"0"));
        return $sql->num_rows();
    }
    
    private function no_payments() {
        $sql = $this->db->get_where("pre_payments",array("id"=>$this->session->userdata('uid')));
        return $sql->num_rows();
    }
}