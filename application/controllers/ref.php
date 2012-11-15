<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Ref extends CI_Controller {

	public function __construct() {
		parent::__construct();
	}
        
        public function captcha() {
            
            $xml = simplexml_load_file("http://api.textcaptcha.com/ecvqkbwfr74s8c84k08ows84o64oopq3");
            
            $this->session->set_userdata('question', (string)$xml->question);
            $this->session->set_userdata('answer', (string)$xml->answer);
            
            return $xml;
            
        }
        
        public function index() {
            
            $ref_name = $this->uri->segment(2);
            
            $this->db->select('id');
            $sql = $this->db->get_where('pre_users', array('username'=>$ref_name));
            $num_res = $sql->num_rows();
            
            $captcha = $this->captcha();
            $data['question'] = $captcha->question;
            
            if($num_res === 1 || $ref_name !== FALSE) {
                $data['refid'] = $sql->result();
                $data['referrer'] = $ref_name;
                $this->load->view('registration/registration_referral_view', $data);
                
            } else {
                $this->load->view('registration/registration_view', $data);
            }
        }
        
        public function register() {
            
            $this->load->library('email');
            $this->load->library('Mlib_sec');
            $this->load->helper('string');

            $referrer = $this->input->post('referrer');
            $sponsor = $this->input->post('sponsor');
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $lastname = $this->input->post('lastname');
            $firstname = $this->input->post('firstname');
            $email = $this->input->post('email');
            $contact = $this->input->post('contact');
            $address = $this->input->post('address');
            $question = md5($this->input->post('question'));
            $is_human = FALSE;
            $mailed = FALSE;
            
            if(empty($username) && empty($password)) { redirect(base_url(),'refresh'); die();}
            
            $sql = $this->db->get_where('pre_users',array('username'=>trim($username)));
            $num_res = $sql->num_rows();
            
            
            if($num_res > 0) {
                echo json_encode(array('status'=>0,'id'=>'username','msg'=>"Username already in use"));
            } else {
                
                /*check if email is already in use*/
                $sql = $this->db->get_where('pre_profile',array('email'=>trim($email)));
                $num_res = $sql->num_rows();
                
                if($num_res > 0) {
                    echo json_encode(array('status'=>0,'id'=>'email','msg'=>"Email already in use")); die();
                }
                
                /*Check Referrer*/
                $sql_referrer = $this->db->get_where("pre_users", array("username"=>$referrer,'status'=>'active','confirmation'=>'1'));
                $num_res_referrer = $sql_referrer->num_rows();
                
                if($num_res_referrer != 1) { $referrer = NULL; } 
                
                /* check sponsor */
                $sql_sponsor = $this->db->get_where("pre_users",array("username"=>$sponsor,'status'=>'active','confirmation'=>'1'));
                $num_res_sponsor = $sql_sponsor->num_rows();

                if($num_res_sponsor != 1) { $sponsor = NULL; } 
                
                $stored_answer = $this->session->userdata('answer');
                
                if(is_array($stored_answer)) {
                    if(in_array($question,$stored_answer)) {
                        $is_human = TRUE;
                    } else {
                        $captcha = $this->captcha();
                        echo json_encode(array('status'=>0,'id'=>'question','msg'=>"You have provided a wrong answer",'question'  =>  $captcha->question));
                    }
                } else {
                    if($question === $stored_answer) {
                        $is_human = TRUE;
                    } else {
                        $captcha = $this->captcha();
                        echo json_encode(array('status'=>0,'id'=>'question','msg'=>"You have provided a wrong answer",'question'  =>  $captcha->question));
                    }
                }
                
                if($is_human === TRUE) {
                    if(! empty($username) && ! empty($password) && ! empty($lastname) && ! empty($firstname) && ! empty($email) && ! empty($contact) && ! empty($address)) {
                        $hashed = $this->mlib_sec->create_hash($password);
                        $hashed_parts = explode(":", $hashed);
                        $token = md5(uniqid(rand(), true));
                        $url =  parse_url(base_url());
                        $url = $url['host'];
                        
                        $content = file_get_contents(FCPATH.'templates/confirm.txt');
                        $confirmation_link = base_url().'confirm/'.$token;
                        
                        if(! empty($content)) {
                            $content = str_replace('##BASE_URL##', $url, $content);
                            $content = str_replace('##CONFIMATION_LINK##',$confirmation_link,$content);
                        } else {
                            $content = "Open this link to verify your account: ".$confirmation_link;
                        }
                        
                        $array = array(
                                        'username'  =>  $username,
                                        'salt'      =>  $hashed_parts[2],
                                        'password'  =>  $hashed_parts[3],
                                        'date'      => date('Y-m-d')
                        );
                        
                        $this->db->insert('pre_users',$array);
                        $affected_rows = $this->db->affected_rows();
                        
                        if($affected_rows > 0) { 
                            
                            $last_id = $this->db->insert_id();
                            
                            $token_data = array(
                                                    'id'    =>  $last_id,
                                                    'token' =>  $token,
                                                    'date'  =>  date('Y-m-d H:i:s'),
                                                    'source'=>  "registration"
                            );
                            $this->db->insert('pre_tokens',$token_data);
                            $affected_rows = $this->db->affected_rows();
                            
                            if($affected_rows > 0) {
                                $config['mailtype'] = 'html';
                                $this->email->initialize($config);
                                
                                $this->email->from('no-reply@'.$url, $url); 
                                $this->email->to($email); 
                                $this->email->subject('Account Verification');
                                $this->email->message($content);	

                                if($this->email->send()) {
                                    $mailed =TRUE;
                                } else {
                                    echo json_encode(array('status'=>0,'msg'=>"Failed to send the confirmation link"));
                                }
                            } else {
                                echo json_encode(array('status'=>0,'msg'=>"There was an error in processing the confirmation link, please retry your registration"));
                            }
                            
                        } else {
                            echo json_encode(array('status'=>0,'msg'=>"There was an error while saving the information"));
                        }
                        
                        $array = array(
                                        'id'        =>  $last_id,
                                        'lastname'  =>  $lastname,
                                        'firstname' =>  $firstname,
                                        'email'     =>  $email,
                                        'contact'   =>  $contact,
                                        'address'   =>  $address,
                                        'referrer'  =>  $referrer,
                                        'sponsor'   =>  $sponsor
                        );
                        
                        $this->db->insert('pre_profile',$array);
                        $aff_rows = $this->db->affected_rows();
                        
                        if($aff_rows > 0) {
                            if($mailed === TRUE) {
                                
                                $captcha = $this->captcha();
                                
                                echo json_encode(
                                                 array(
                                                    'status'    =>  1,
                                                    'msg'       =>  "Please check you email to verify your account, the link is valid only for 24 hours.",
                                                    'question'  =>  $captcha->question
                                                 )
                                );
                                
                            } else {
          
                                echo json_encode(
                                                    array(
                                                            'status'    =>  1,
                                                            'msg'       =>  "Your account was created but an error occured while sending the email"
                                                     )
                                );
                                
                            }
                        } else {
                            echo json_encode(array('status'=>0,'msg'=>"There was an error while saving the information"));
                        }
                    }
                }
                
           }
            
        }
}