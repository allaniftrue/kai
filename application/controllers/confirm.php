<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Confirm extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $token = $this->uri->segment(2);
        
        if(!empty($token)) {
            $sql = $this->db->query("
                                        SELECT * from `pre_tokens` 
                                        WHERE 
                                        `token`='".$token."' AND `visited`='0' AND `date` > DATE_SUB(NOW(),INTERVAL 24 HOUR)
                                    ");        
            $num_res = $sql->num_rows();
            
            if($num_res === 1) {
                
                $array = array(
                                "message"   =>  "Account successfully verified",
                                "alert_type"=>  "success"
                );
                $this->session->set_userdata($array);
                
                redirect(base_url(), "location");
                
            } else {
//            redirect to registration
            }
            
            
        } else {
//            redirect to registration
        }
        
    }
}