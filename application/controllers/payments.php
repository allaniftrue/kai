<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payments extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        
        $data['payments'] = $this->Paymentsq->my_payments();
        
        $this->load->view("payments/payments_view",$data);
    }
    
    public function message() {
        $message_id = $this->input->post('mid');
        $sql = $this->db->get_where("pre_payments",array("uid"=>$message_id));
        $num_res = $sql->num_rows();
        
        if($num_res === 1) {
            $result = $sql->result();
            $message = ! empty($result[0]->message) ? $result[0]->message : "<em>No message included</em>";
            $array = array(
                            "title"     => "Message",
                            "message"   =>  $message
            );
            
            echo json_encode($array);
        } else {
            $array = array(
                            "title"     => "Message",
                            "message"   => "<em>Unable to fetch message</em>"
            );
            
            echo json_encode($array);
        }
        
    }
    
    public function attachment() {
        
        $attachment_id = $this->uri->segment(3);
        
        if(is_numeric($attachment_id)) {
            
            $this->load->helper('download');
            $this->load->helper('file');
            
            $sql = $this->db->get_where("pre_payments",array("id"=>$this->session->userdata('uid')));
            $num_res = $sql->num_rows();
            
            if($num_res === 1) {
                
                $result = $sql->result();
                $file = file_get_contents(FCPATH."attachments/".$result[0]->attachment);
                $info = get_mime_by_extension(FCPATH."attachments/".$result[0]->attachment);
                
                $ext = explode("/", $info);
                force_download("transaction_recept".".".$ext[1],$file);
                
            } else {
                $this->Logsq->login_log("Forbidden access to attached transaction receipt");
                die("<em>Forbidden Access</em>");
            }
        }
        
    }
    
}