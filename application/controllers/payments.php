<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payments extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->helper('file');
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
                
                if(file_exists(FCPATH."attachments/".$result[0]->attachment)) {
                    $file = file_get_contents(FCPATH."attachments/".$result[0]->attachment);
                    $info = get_mime_by_extension(FCPATH."attachments/".$result[0]->attachment);
                    $ext = explode("/", $info);
                    force_download("transaction_recept".".".$ext[1],$file);
                } else {
                    die("<em>File does not exist</em>");
                }
                
            } else {
                $this->Logsq->login_log("Forbidden access to attached transaction receipt");
                die("<em>Forbidden Access</em>");
            }
        }
        
    }
    
    public function upload() {

            $path = FCPATH.'attachments/';
            $the_file = $this->input->get('qqfile');

            $the_file = explode('.', $the_file);
            $the_file = array_filter($the_file, 'strlen');
            $total = count($the_file);
            $ext = $the_file[$total-1];

            $the_file[0] = $filename = sha1($the_file[0].uniqid());
            $the_file = $the_file[0].'.'.$ext;

            if(save_file($path.$the_file)) {
                
                $array = array(
                                "file"  =>  $the_file
                );
                $this->session->set_userdata($array);
                
                echo json_encode(
                                    array(
                                            "success"    =>  TRUE,
                                            "filename"  =>  $this->session->userdata('file')
                                    )
                );
                
            } else {
                echo json_encode(array('status'=>'Error', 'issue'=> $this->upload->display_errors('','')));
            }
        }
        
        public function unattach() {
            
            $filename = $this->input->post("file");
            $path = FCPATH."attachments/";
            $file = $path.$filename;
            
            if(file_exists($file)) {
                
                    if(unlink($file)) {

                        $array = array(
                                    "file"  =>  ""
                        );
                        $this->session->set_userdata($array);

                        echo json_encode(
                                            array(
                                                    "status"    => 1
                                            )
                        );

                    } else {
                        echo json_encode(
                                            array(
                                                    "status"    =>  0,
                                                    "title"     =>  "Error",
                                                    "message"   =>  "Unable to remove file"
                                            )
                        );
                    }
            } else {
                echo json_encode(
                                        array(
                                                "status"    =>  0,
                                                "title"     =>  "Error",
                                                "message"   =>  "File does not exist"
                                        )
                    );
            }
        }
    
    
}