<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Transactions extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library("Mlib_trac");
        $this->load->library("pagination");
        
        $this->mlib_trac->is_admin();
        
        $this->load->model("Userq");
    }
    
    public function index() {
        
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $total_page = $this->Paymentsq->count_all_payments();
        
        $config["uri_segment"] = 2;
        $config['base_url'] = base_url()."transactions/";
        $config['total_rows'] = $total_page;
        $config['per_page'] = 30; 
        $config['num_links'] = 20;
        
        $this->pagination->initialize($config); 
        
        $data['transactions_info'] = $this->Paymentsq->get_userinfo_transactions($config['per_page'],$page);
        $this->load->view('payments/transactions_view',$data);
    }
    
    public function pending() {
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $total_page = $this->Paymentsq->count_unmanaged_payments();
        
        $config["uri_segment"] = 3;
        $config['base_url'] = base_url()."transactions/pending";
        $config['total_rows'] = $total_page;
        $config['per_page'] = 30; 
        $config['num_links'] = 20;
        $this->pagination->initialize($config); 
        
        $data['transactions_info'] = $this->Paymentsq->get_unmanaged_transactions_n_userinfo($config['per_page'],$page);
        $this->load->view('payments/transactions_view',$data);
    }
    
    public function approved() {
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $total_page = $this->Paymentsq->count_approved_payments();
        
        $config["uri_segment"] = 3;
        $config['base_url'] = base_url()."transactions/approved";
        $config['total_rows'] = $total_page;
        $config['per_page'] = 30; 
        $config['num_links'] = 20;
        $this->pagination->initialize($config); 
        
        $data['transactions_info'] = $this->Paymentsq->get_approved_transactions_n_userinfo($config['per_page'],$page);
        $this->load->view('payments/transactions_view',$data);
    }
    
    public function remove() {
        $id = $this->input->post('id');
        
        if(is_numeric($id)) {
            
            $this->db->where('uid', $id);
            $this->db->delete('pre_payments'); 
            $aff_rows = $this->db->affected_rows();
            
            if($aff_rows > 0) {
                echo json_encode(array(
                                    'status'    =>  1,
                                    'message'   =>  "Transaction successfuly removed"
                ));
            } else {
                echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  "An error occurred while processing your request"
                ));
            }
            
        } else {
            echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  "An error occurred while removing transaction"
            ));
        }
    }
    
    public function claimed() {
        $id = $this->input->post('id');
        
        if(is_numeric($id)) {
            

            $sql = $this->db->get_where('pre_payments', array('uid'=>$id));
            $payments = $sql->result();
            
            $data = array(
                            'status' => '1'
            );

            $this->db->where('uid', $id);
            $this->db->update('pre_payments',$data);
            $aff_rows = $this->db->affected_rows();
            
            if($aff_rows > 0) {
                
                $sql = $this->db->get_where('pre_profile',array('id'=>$payments[0]->id));
                $result = $sql->result();

                $more_credits = $result[0]->credits + $payments[0]->amount;

                $this->db->trans_start();
                $data = array(
                                'credits'   =>  $more_credits
                );

                $this->db->where('id',$payments[0]->id);
                $this->db->update('pre_profile',$data);
                $aff_rows = $this->db->affected_rows();
                $this->db->trans_complete();
                
                if($aff_rows > 0) {
                    echo json_encode(array(
                                    'status'    =>  1,
                                    'message'   =>  "Successfully updated transaction and credits are added to the user"
                    ));
                } else {
                    echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  "Successfully updated transaction but there was an error in adding the credit to the user"
                    ));
                }
            } else {
                echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  "An error occurred while processing transaction approval",
                                    'aff'       =>  $aff_rows
                ));
            }
        } else {
            echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  "Unable to process transaction approval"
            ));
        }
    }
}