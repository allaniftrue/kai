<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Confirm extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        
        $token = $this->uri->segment(2);
        
        if(! empty($token)) {
            
            $sql = $this->db->query("
                                        SELECT * from `pre_tokens` 
                                        WHERE 
                                        `token`=".$this->db->escape($token)." AND `visited`='0' AND `date` > DATE_SUB(NOW(),INTERVAL 24 HOUR)
                                            AND `source`='registration'
                                    ");   
            
            $num_res = $sql->num_rows();
            
            if($num_res === 1) {
                
                $result = $sql->result();
                
                $array = array(
                                "visited"=>'1'
                );
                
                $this->db->update('pre_tokens', $array, array("uid"=>$result[0]->uid));
                $aff_rows = $this->db->affected_rows();
                
                if($aff_rows === 1) {
                    
                    $data = array(
                                    "confirmation"  =>  '1'
                    );
                    
                    $this->db->update('pre_users', $data, array('id'=>$result[0]->id));
                    $affected_rows = $this->db->affected_rows();
                    
                    if($affected_rows === 1) {
                        $array = array(
                                        "message"   =>  "Account successfully verified",
                                        "alert_type"=>  "success"
                        );

                        $this->session->set_userdata($array);
                    } else {
                        $array = array(
                                "message"   =>  "An error occurred while processing confirmation",
                                "alert_type"=>  "error"
                        );

                        $this->session->set_userdata($array);
                    }
                    
                } else {
                    
                    $array = array(
                                "message"   =>  "An error occurred while processing confirmation",
                                "alert_type"=>  "error"
                    );
                    $this->session->set_userdata($array);
                }
                
                redirect(base_url(), "location");
                
            } else {
                redirect(base_url().'ref',"refresh");
            }
            
            
        } else {
            redirect(base_url().'ref',"refresh");
        }
        
    }
}