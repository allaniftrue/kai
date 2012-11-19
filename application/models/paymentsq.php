<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

Class Paymentsq extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function has_paid($id="",$type="boolean") {
        
        $id = ! empty($id) ? $id : $this->session->userdata('uid');
        
        $sql = $this->db->get_where("pre_payments", array("id"=>$id, 'status'=>'1'));
        $num_res = $sql->num_rows();
        
        if($type === "boolean") {
            if($num_res > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {
            return $sql->result();
        }
    }
    
    public function my_payments($id="") {
        
        $id = ! empty($id) ? $id : $this->session->userdata('uid');
        
        $this->db->order_by("payment_date","DESC");
        $sql = $this->db->get_where("pre_payments", array("id"=>$id));
        return $sql->result();
        
    }
    
    
    public function total_paid() {
        
        $stack = $this->has_paid("","object");
        $count = count($stack);
        $total = 0;
        
        if($count > 0) {
            for($i=0; $i<$count; $i++) {
                $total += $stack[$i]->amount;
            }
        }
        return $total;
    }
}