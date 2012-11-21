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
        if($this->session->userdata('usertype') === 'admin' && $this->session->userdata('is_login') === TRUE) {
            
            $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
            
            if ($this->cache->apc->is_supported()) {
                if ( ! $unmanaged_payments = $this->cache->get('unmanaged_payments'))
                {
                    $this->db->where('status','0');
                    $unmanaged_payments = $this->db->count_all_results('pre_payments');
                    $this->cache->save('unmanaged_payments', $unmanaged_payments, 10000);
                }
                $unmanaged_payments = $this->cache->get('unmanaged_payments');
            } else {
                   $this->db->where('status','0');
                   $unmanaged_payments = $this->db->count_all_results('pre_payments');
            }
            return $unmanaged_payments;
        }
    }
}