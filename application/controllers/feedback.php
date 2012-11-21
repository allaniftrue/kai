<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Feedback extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->library("Mlib_headers");
        $this->load->library("Mlib_trac");

        $this->mlib_trac->trac_login();
    }
    
    public function index() {
        
        $this->load->view('feedback/feedback_view');
    }
    
    public function send() {
        
        $this->load->library("email");
        $this->load->helper("array");
        $this->load->model("Userq");
        
        $subject = $this->input->post("subject");
        $msg = $this->input->post("message");
        
        if(! empty($subject) && !empty($msg)) {
            $this->load->library('email');
            
            $message = "Feedback message from ". $this->session->userdata('username') . ": <br />" . $msg;
            
            $config['mailtype'] = 'html';
            $this->email->initialize($config);

            $this->email->from('no-reply@'.base_host(), COMPANY_NAME. " Feedback Notifier");
            $this->email->to(elements_only($this->Userq->get_administrators("array"), 'email')); 

            $this->email->subject($subject);
            $this->email->message($message);	

            if($this->email->send()) {
                echo json_encode(
                                array(
                                        "status"    =>  1,
                                        "message"   =>  "Thank you for your feed back.  We are taking feedbacks seriously to improve our services"
                                )
                 );
            } else {
                echo json_encode(
                                array(
                                        "status"    =>  0,
                                        "message"   =>  "Subject and message can not be empty"
                                )
                    );
            }
            
        } else {
            echo json_encode(
                                array(
                                        "status"    =>  0,
                                        "message"   =>  "Subject and message can not be empty"
                                )
                 );
        }
        
    }
}