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
    
    public function count_unmanaged_payments() {
        
        $this->db->where('status','0');
        $unmanaged_payments = $this->db->count_all_results('pre_payments');
        
        return $unmanaged_payments;
    }
    
    public function count_approved_payments() {
        $this->db->where('status','1');
        $unmanaged_payments = $this->db->count_all_results('pre_payments');
        
        return $unmanaged_payments;
    }
    
    public function get_approved_transactions_n_userinfo($num,$offset) {
        $sql = $this->db->query("
                            SELECT a.username,b.*,c.* FROM pre_users a, pre_profile b,pre_payments c
                            WHERE 
                            a.id=b.id AND a.id=c.id AND c.status='1' ORDER BY c.payment_date DESC LIMIT $offset,$num
        ");
        
        return $sql->result();
    }
    
    public function get_unmanaged_transactions_n_userinfo($num,$offset) {
        $sql = $this->db->query("
                            SELECT a.username,b.*,c.* FROM pre_users a, pre_profile b,pre_payments c
                            WHERE 
                            a.id=b.id AND a.id=c.id AND c.status='0' ORDER BY c.payment_date ASC LIMIT $offset,$num
        ");
        
        return $sql->result();
    }
    
    public function get_userinfo_transactions($num,$offset) {
        $sql = $this->db->query("
                            SELECT a.username,b.*,c.* FROM pre_users a, pre_profile b,pre_payments c
                            WHERE 
                            a.id=b.id AND a.id=c.id ORDER BY c.status ASC LIMIT $offset,$num
        ");
        
        return $sql->result();
    }
    
    public function count_all_payments() {
        
        $sql = $this->db->query("
                            SELECT a.username,b.*,c.* FROM pre_users a, pre_profile b,pre_payments c
                            WHERE 
                            a.id=b.id AND a.id=c.id
        ");
        
        return $sql->num_rows();
    }
}