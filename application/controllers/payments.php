<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Payments extends CI_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->load->helper('file');
        $this->load->library("Mlib_trac");
        
        $this->mlib_trac->trac_login();
    }
    
    public function index() {
        
        $array = array(
                        "file"  =>  ""
        );
        $this->session->set_userdata($array);
        
        $data['payments'] = $this->Paymentsq->my_payments();
        $this->load->view("payments/payments_view",$data);
    }
    
    public function message() {
        
        $message_id = $this->input->post('mid');
        $sql = $this->db->get_where("pre_payments",array("uid"=>$message_id));
        $num_res = $sql->num_rows();
        $result = $sql->result();
        
        if($result[0]->id === $this->session->userdata('uid') || $this->session->userdata('usertype') === 'admin'){
            if($num_res === 1) {

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
            
            if($this->session->userdata('usertype') === 'admin') {
                $sql = $this->db->get_where("pre_payments", array('uid'=>$attachment_id));
                $num_res = $sql->num_rows();
            } else {
                $sql = $this->db->get_where("pre_payments", array("id"=>$this->session->userdata('uid'),"uid"=>$attachment_id));
                $num_res = $sql->num_rows();
            }
            
            
            if($num_res === 1) {
                
                $result = $sql->result();
                
                if(file_exists(FCPATH."attachments/".$result[0]->attachment)) {
                    $file = file_get_contents(FCPATH."attachments/".$result[0]->attachment);
                    $info = get_mime_by_extension(FCPATH."attachments/".$result[0]->attachment);
                    
                    $this->Logsq->login_log("Downloaded attachment ". $result[0]->attachment . " with payment ID: ". $result[0]->uid);
                    $ext = explode("/", $info);
                    force_download("transaction_recept".".".$ext[1],$file);
                } else {
                    show_404();
                }
                
            } else {
                $this->Logsq->login_log("Forbidden access to attached transaction receipt");
                show_404();
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
        
        public function send() {
            
            $attachment = trim($this->session->userdata("file"));
            $paymentcenter = $this->input->post('paymentcenter');
            $transaction = $this->input->post("transaction");
            $amount = $this->input->post('amount');
            $message = $this->input->post('message');
            
            if(! empty($attachment)) {
                $this->load->helper("array");
                $this->load->library("email");
                
                $this->db->select("pre_profile.email");
                $this->db->from('pre_users');
                $this->db->where("pre_users.usertype","admin");
                $this->db->join('pre_profile', 'pre_users.id = pre_profile.id');
                $sql = $this->db->get();
                $num_res = $sql->num_rows();
                $result = $sql->result_array();
                
                
                $e_message = "<p>A new transaction has been made.</p><p>Payment Center: $paymentcenter <br />Transaction: $transaction <br />Amount: $amount <br />Message: <br />$message</p>";
                $address = implode(",", elements_only($result, "email"));
                
                $config['mailtype'] = 'html';
                $this->email->initialize($config);
                $this->email->to("ajmcagadas@gmail.com");
                $this->email->from('no-reply@'.base_host(), COMPANY_NAME. " Alert Mailer");
                $this->email->subject(COMPANY_NAME." new transaction");
                $this->email->message($e_message);
            
                if($this->email->send()) {
                    $date = date("Y-m-d");
                    $data = array(
                                    "id"                =>  $this->session->userdata('uid'),
                                    "payment_center"    =>  $paymentcenter,
                                    "transaction"       =>  $transaction,
                                    "amount"            =>  $amount,
                                    "message"           =>  $message,
                                    "attachment"        =>  $this->session->userdata('file'),
                                    "status"            =>  '0',
                                    "payment_date"      =>  $date
                    );
                    
                    $this->db->trans_start();
                    $this->db->trans_strict(FALSE);
                    
                    $this->db->insert('pre_payments', $data); 
                    $aff_rows = $this->db->affected_rows();
                    $last_id = $this->db->insert_id();
                    $this->db->trans_complete();
                    
                    if($aff_rows > 0) {
                        
                        $array = array(
                                    "file"  =>  ""
                        );

                        $this->session->set_userdata($array);

                        echo json_encode(
                                            array(
                                                    "status"    =>  1,
                                                    "title"     =>  "Success",
                                                    "message"   =>  "Transaction saved.  Your transaction will be moderated in a short while",
                                                    "lastid"    =>  $last_id,
                                                    "date"      =>  date("M d, y", strtotime($date))
                                            )
                        );
                    } else {
                        echo json_encode(
                                            array(
                                                    "status"    =>  0,
                                                    "title"     =>  "Error",
                                                    "message"   =>  "Failed to save transaction.  Please retry the sending the payment information"
                                            )
                        );
                    }
                    
                } else {
                    echo json_encode(
                                        array(
                                                "status"    =>  0,
                                                "title"     =>  "Error",
                                                "message"   =>  "Failed to send an alert"
                                        )
                    );
                }
                   
                
            } else {
                echo json_encode(
                                        array(
                                                "status"    =>  0,
                                                "title"     =>  "Error",
                                                "message"   =>  "Please attach the transaction receipt"
                                        )
                );
            }
            
        }
        
        public function remove() {
            
            $payid = $this->input->post('payid');
            
            if(is_numeric($payid)) {
                
                $this->db->trans_start();
                $this->db->delete('pre_payments', array('uid' => $payid)); 
                $aff_rows = $this->db->affected_rows();
                $this->db->trans_complete();
                
                if($aff_rows === 1) {
                    echo json_encode(
                                        array(
                                                "status"    =>  1
                                        )
                    );
                } else {
                    echo json_encode(
                                        array(
                                                "status"    =>  0,
                                                "title"     =>  "Error",
                                                "message"   =>  "Unable to remove transaction message"
                                        )
                    );
                }
                
                
            } else {
                echo json_encode(
                                        array(
                                                "status"    =>  0,
                                                "title"     =>  "Error",
                                                "message"   =>  "Unable to find transaction message"
                                        )
                );
            }
            
        }
    
        public function paymentcenters() {
            $this->load->helper('array');
            $payment_center = $this->input->post("paymentcenter");
            
            $this->db->distinct();
            $this->db->select("payment_center");
            $this->db->like("payment_center",$payment_center);
            $sql = $this->db->get("pre_payments");
            
            echo json_encode(elements_only($sql->result_array(),"payment_center"));
            
        }
    
}