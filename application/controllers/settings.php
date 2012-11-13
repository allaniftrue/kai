<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Settings extends CI_Controller {

	public function __construct() {

		parent::__construct();
		$this->load->model("Userq");
                $this->load->model("Logsq");

                $this->load->library("Mlib_headers");
		$this->load->library("Mlib_trac");
                
		$this->mlib_trac->trac_login();

	}

	public function profile() {
                $data['profile'] = $this->Userq->user_profile($this->session->userdata('uid'));
		$this->load->view('settings/profile_view',$data);
	}
        
        
        
       /* File Upload */    
        private function __get_size() {
            if (isset($_SERVER["CONTENT_LENGTH"])){
                return (int)$_SERVER["CONTENT_LENGTH"];            
            } else {
                throw new Exception('Getting content length is not supported.');
            }      
        }

        private function __save($file_n_path) {
            $input = fopen("php://input", "r");
            $temp = tmpfile();
            $realSize = stream_copy_to_stream($input, $temp);
            fclose($input);

            if ($realSize != $this->__get_size()){            
                return false;
            }


            $target = fopen($file_n_path, "w");        
            fseek($temp, 0, SEEK_SET);
            stream_copy_to_stream($temp, $target);
            fclose($target);

            return true;
        }

        public function upload() {

            $path = FCPATH.'profile/';
            $the_file = $this->input->get('qqfile');

            $the_file = explode('.', $the_file);
            $the_file = array_filter($the_file, 'strlen');
            $total = count($the_file);
            $ext = $the_file[$total-1];

            $the_file[0] = $filename = sha1($the_file[0].uniqid());
            $the_file = $the_file[0].'.'.$ext;

            if($this->__save($path.$the_file)) {

                $config['image_library']    = 'gd2';
                $config['source_image']     = $path.$the_file;
                $config['create_thumb']     = TRUE;
                $config['width']            = 160;
                $config['height']           = 160;

                $this->load->library('image_lib', $config); 
                $this->image_lib->resize();


                $this->db->select('picture');
                $sql = $this->db->get_where('pre_profile',array('id'=>$this->session->userdata('uid')));
                $result = $sql->result();
                $num_res = count($result);

                if($num_res > 0) {

                    if(file_exists($path.$result[0]->picture) && file_exists($path.str_replace('_thumb', '', $result[0]->picture))):
                        unlink($path.$result[0]->picture);
                        unlink($path.str_replace('_thumb', '', $result[0]->picture));
                    endif;

                    $data = array('picture'=> $filename.'_thumb.'.$ext);
                    $this->db->where('id', $this->session->userdata('uid'));
                    $stat_prof = $this->db->update('pre_profile', $data);
                } else {
                    $data = array('id'=>$this->session->userdata('uid'),'picture'=> $filename.'_thumb.'.$ext);
                    $stat_prof = $this->db->insert('pre_profile', $data);
                }

                $json=array('status'=>'Success', 'issue'=> 'Photo updated', 
                            'filename'=> base_url().'profile/'.$filename."_thumb.".$ext);	


            } else {
                $json=array('status'=>'Error', 'issue'=> $this->upload->display_errors('',''));
            }
        echo json_encode($json);
        }
        
        
        public function save_profile() {
            
            $lastname = $this->input->post('lastname');
            $firstname = $this->input->post('firstname');
            $email = $this->input->post('email');
            $contact = $this->input->post('contact');
            $address = $this->input->post('address');
            
            if(! empty($lastname) && ! empty($firstname) && ! empty($email) && ! empty($contact) && ! empty($address)) {
                
                $sql = $this->db->get_where('pre_profile',array('id'=>$this->session->userdata('uid')));
                $num_res = $sql->num_rows();
                
                if($num_res == 1) {
                    
                    $data = array(
                                    'lastname'  => $lastname,
                                    'firstname' => $firstname,
                                    'email'     => $email,
                                    'contact'   => $contact,
                                    'address'   => $address,
                                 );

                     $this->db->where('id', $this->session->userdata('uid'));
                     $this->db->update('profile', $data); 
                     $aff_rows = $this->db->affected_rows();
                     
                     if($aff_rows > 0) {
                         echo json_encode(array("status"=>1,"message"=>"Profile successfuly updated."));
                     } else {
                         echo json_encode(array("status"=>0,"message"=>"Failed to update profile information."));
                     }
                     
                } else {
                    $data = array(
                                    'id'        => $this->session->userdata('uid'),
                                    'lastname'  => $lastname,
                                    'firstname' => $firstname,
                                    'email'     => $email,
                                    'contact'   => $contact,
                                    'address'   => $address,
                                 );

                     $this->db->insert('pre_profile', $data); 
                     $aff_rows = $this->db->affected_rows();
                     
                     if($aff_rows > 0) {
                         
                         /* Log */
                         $this->Logsq->login_log("Profile change");
                         
                         echo json_encode(array("status"=>1,"message"=>"Profile successfuly updated."));
                     } else {
                         echo json_encode(array("status"=>0,"message"=>"Failed to save profile information."));
                     }
                }
                
            } else {
                echo json_encode(array("status"=>0,"message"=>"Some fields are missing."));
            }
            
        }
        
        /* Account Settings -- Password */
        public function admin() {
            $this->load->view('settings/account_view');
        }
        
        public function change_password() {
            
            $this->load->library('Mlib_sec');
            
            $old_password = $this->input->post('oldpassword');
            $new_password = $this->input->post('newpassword');
            
//            $attempts = 3;
            
            $this->db->select('salt,password');
            $sql = $this->db->get_where('pre_users',array('id'=>$this->session->userdata('uid')));
            $result = $sql->result();
            
            $format = PBKDF2_HASH_ALGORITHM.":".PBKDF2_ITERATIONS.":".$result[0]->salt.":".$result[0]->password;
            $is_valid = $this->mlib_sec->validate_password($old_password,$format);
            
            if($is_valid === TRUE) {
                
                $hashed = $this->mlib_sec->create_hash($new_password);
                $hashed_parts = explode(":", $hashed);
                
                $array = array(
                                'password'=>$hashed_parts[3],
                                'salt'=>$hashed_parts[2]
                );
                
                $this->db->where('id',$this->session->userdata('uid'));
                $this->db->update('pre_users', $array);
                $aff_rows = $this->db->affected_rows();
                
                if($aff_rows == 1) {
                    echo json_encode(array("status"=>1,"message"=>"New password saved."));
                } else {
                    echo json_encode(array("status"=>0,"message"=>"Unable to update account information.  Please try again later."));
                }
                
            } else {
                /* @TODO
                 * Capture and limit change password attempts
                 * 
                 */
                echo json_encode(array("status"=>0,"message"=>"Wrong information provided."));
                
                
            }
        }
        
        
        
        

}