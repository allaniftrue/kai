<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class All_users extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->library("Mlib_trac");
        $this->mlib_trac->is_admin();
        
        $this->load->model("Userq");
    }
    
    public function index() {
        
        $data['all_users'] = $this->Userq->all_users();
        $this->load->view("all_users/all_users_view",$data);
    }
    
    public function remove() {
        $id = $this->input->post('id');
        
        if(is_numeric($id) && !empty($id)) {
            $array = array('id' => $id);
            
            $sql = $this->db->delete('pre_users', $array); 
            $user_aff_row = $this->db->affected_rows();
            
            $query = $this->db->delete('pre_profile', $array); 
            $profile_aff_row = $this->db->affected_rows();
            
            if($user_aff_row === 1 && $profile_aff_row === 1) {
                echo json_encode(array(
                                    'status'    =>  1,
                                    'message'   =>  "User successfully removed"
                ));
            } else {
                echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  "Unable to remove user"
                ));
            }
            
        } else {
            echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  "An error occurred while removing the user"
            ));
        }
    }
    
    public function users() {
        
        $id=  $this->input->post('id');
        $users = $this->Userq->usernames();
        $num_res = count($users);
        $user_list = array();
        $selected_user = $this->Userq->user_profile($id);
        
        if($num_res > 0):
            for($i=0; $i < $num_res; $i++) {
                array_push($user_list, $users[$i]->username);
            }
        else:
            $user_list = array("No users found");
        endif;
        
        if($num_res > 0) {
            echo json_encode(array(
                                    'status'    =>  1,
                                    'list'      =>  $user_list,
                                    'username'  =>  $selected_user[0]->username,
                                    'referrer'  =>  $selected_user[0]->referrer,
                                    'sponsor'   =>  $selected_user[0]->sponsor
            ));
        } else {
            echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  "An error occurred while getting user list"
            ));
        }
    }
    
    public function sponsor_referrer() {
        
        $id = $this->input->post('id');
        $referrer = $this->input->post('referrer');
        $sponsor = $this->input->post('sponsor');
        
        if(is_numeric($id) && (! empty($referrer) || ! empty($sponsor))) {
            
            $data = array(
                            'referrer' => $referrer,
                            'sponsor'  => $sponsor
            );

            $this->db->where('id', $id);
            $this->db->update('pre_profile', $data); 
            $aff_rows = $this->db->affected_rows();
            
            if($aff_rows > 0) {
                echo json_encode(array(
                                    'status'    =>  1,
                                    'message'   =>  "Referrer/Sponsor successfully updated"
                ));
            } else {
                echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  "There was an error while processing your request"
                ));
            }
            
        } else {
            echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  "Must select a user for sponsor / referrer"
            ));
        }
    }
    
    public function toggle_type() {
        $id = $this->input->post('id');
        
        if(is_numeric($id) && !empty($id)) {
            $data = array(
                            'usertype'  =>  'admin'
            );
        } else {
            echo json_encode(array(
                                    'status'    =>  0,
                                    'message'   =>  "Failed to process your request"
            ));
        }
    }
}
