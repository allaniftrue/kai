<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Credits extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("Mlib_trac");
        $this->mlib_trac->is_admin();

        $this->load->model("Userq");
    }
    
    public function index() {
        
        $data['all_users'] = $this->Userq->all_users();
        $this->load->view("credits/credits_view",$data);
    }
    
    public function add() {
        $id = $this->input->post('id');
        $points = $this->input->post('points');
        
        if(is_numeric($id) && is_numeric($points)) {
            
            $this->db->select('credits');
            $sql = $this->db->get_where('pre_profile',array('id'=>$id));
            $result = $sql->result();
            
            $more_credits = $result[0]->credits + $points;
            
            $this->db->trans_start();
            $data = array(
                            'credits'   =>  $more_credits
            );
            
            $this->db->where('id',$id);
            $this->db->update('pre_profile',$data);
            $aff_rows = $this->db->affected_rows();
            $this->db->trans_complete();
            
            if($aff_rows > 0) {
                echo json_encode(array(
                                    'status'    =>  1,
                                    'message'   =>  'Credit successfully added'
                ));
            } else {
                echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  'Failed to add credits'
                ));
            }
        } else {
            echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  'Unable to process request'
            ));
        }
    }

}