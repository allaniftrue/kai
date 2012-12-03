<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Products extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library("Mlib_trac");
        $this->mlib_trac->is_admin();

        $this->load->model("Userq");
        $this->load->helper("file");
    }
    
    public function index() {
        $data['thumb'] = $this->session->userdata('product_image_thumb') ? $this->session->userdata('product_image_thumb') : '';
        $this->load->view('products/products_view',$data);
    }
    
    public function screenshot() {
        
        $path = FCPATH.'_products/';
        $the_file = $this->input->get('qqfile');

        $the_file = explode('.', $the_file);
        $the_file = array_filter($the_file, 'strlen');
        $total = count($the_file);
        $ext = $the_file[$total-1];

        $the_file[0] = $filename = sha1($the_file[0].uniqid());
        $the_file = $the_file[0].'.'.$ext;
        
        $allowed_files = array('jpg','jpeg','gif','png');
        
        if(in_array($ext, $allowed_files)) {
            
            if(save_file($path.$the_file)) {
                
                $config['image_library']    = 'gd2';
                $config['source_image']     = $path.$the_file;
                $config['create_thumb']     = TRUE;
                $config['maintain_ratio']   = TRUE;
                $config['width']            = 160;
                $config['height']           = 160;

                $this->load->library('image_lib', $config); 
                $this->image_lib->resize();
                
                $array = array(
                                'product_image'         =>  $the_file,
                                'product_image_thumb'   =>  $filename.'_thumb.'.$ext
                );
                $this->session->set_userdata($array);
                
                $this->output->set_output(
                                            json_encode(array(
                                                    "success"   =>  TRUE,
                                                    'status'    =>  1, 
                                                    "filename"  =>  $filename.'_thumb.'.$ext
                                            ))
                );
                
            } else {
                $this->output->set_output(
                                            json_encode(array(
                                                                "success"   =>  FALSE,
                                                                'status'    =>  0, 
                                                                'message'   =>  $this->upload->display_errors('','')
                                            ))
                );
            }
        } else {
            $this->output->set_output(
                                        json_encode(array(
                                                            "success"   =>  FALSE,
                                                            'status'    =>  0,
                                                            'message'   =>  'File type not supported, please compress the file in a zip format'
                                        ))
            );
        }
    }
    
    
    public function file_upload() {
        
        $path = FCPATH.'warehouse/';
        $file = $_FILES['file']['name'];
        $tmp_file = $_FILES['file']['tmp_name'];
        $mime = $_FILES['file']['type'];
        
        $allowed_mimes = array('application/x-zip', 'application/zip', 'application/x-zip-compressed');
        $data['thumb'] = $this->session->userdata('product_image_thumb') ? $this->session->userdata('product_image_thumb') : '';
        
        if(in_array($mime, $allowed_mimes)) {
            
            $the_file = sha1($tmp_file.uniqid());
            
            if(save_file_alt($tmp_file, $path.$the_file, ($_FILES['file']['size']/1024))) {
                
                $array_data = array(
                                        'id'            =>  $this->session->userdata('uid'),
                                        'image'         =>  $this->session->userdata('product_image'),
                                        'name'          =>  $this->input->post('name'),
                                        'description'   =>  $this->input->post('description'),
                                        'cost'          =>  $this->input->post('cost'),
                                        'file'          =>  $the_file,
                                        'date_added'    =>  date('Y-m-d')
                );
                
                $this->db->insert('pre_products',$array_data);
                $aff_rows = $this->db->affected_rows();
                
                if($aff_rows > 0) {
                    /* clear session data */
                    $data['thumb'] = '';
                    $array = array(
                                    'product_image'         =>  '',
                                    'product_image_thumb'   =>  ''
                    );
                    $this->session->set_userdata($array);
                    $array = array('status'=>1,'message'=>"Product successfully saved");
                } else {
                    $array = array('status'=>0,'message'=>"There was an error while saving the new product");
                }
                $this->session->set_userdata($array);
                
            } else {
                $array = array('status'=>0,'message'   =>  "Ooops!  Your upload triggered the following error: ".$_FILES['file']['error']);
                $this->session->set_userdata($array);
            }
        } else {
            $array = array('status'=>0,'message'=>"File type not supported, please compress the file in a zip format");
            $this->session->set_userdata($array);
        }
        
        $this->load->view('products/products_view',$data);
    }
    
    public function remove() {
        $filename = $this->input->post("file");
        $path = FCPATH."_products/";
        $file = $path.$filename;

        if(file_exists($file)) {

                if(unlink($file)) {

                    $array = array(
                                'product_image'         =>  '',
                                'product_image_thumb'   =>  ''
                    );
                    $this->session->set_userdata($array);
                    $this->output->set_output(
                                                json_encode(array(
                                                                    "status"    => 1
                                                ))
                    );

                } else {
                    $this->output->set_output(
                                                json_encode(
                                                        array(
                                                                "status"    =>  0,
                                                                "title"     =>  "Error",
                                                                "message"   =>  "Unable to remove file"
                                                        ))
                    );
                }
        } else {
            $this->output->set_output(
                                        json_encode( array(
                                                                    "status"    =>  0,
                                                                    "title"     =>  "Error",
                                                                    "message"   =>  "File does not exist"
                                                            ))
            );
        }
    }
    
    public function add() {
        
        $this->load->helper('file');
        
        $img = $this->session->userdata('product_image');
        $name = $this->input->post('name');
        $cost = $this->input->post('cost');
        $file = $this->input->post('file');
        $description = $this->input->post('description');
        
        $mime = get_mime_by_extension($file);
        $allowed = array('application/x-zip', 'application/zip', 'application/x-zip-compressed');
        
//        var_dump(is_dir(FCPATH.'warehouse'));
//        echo APPPATH; die();
        
        if(! empty($name) && is_numeric($cost) && ! empty($description)) {
            $config['upload_path']      = FCPATH.'warehouse';
//            $config['allowed_types']    = 'zip';
            $config['file_name']        = uniqid(rand(), TRUE);
            $config['encrypt_name']     = TRUE;
            $config['max_filename']     = 0;
            $config['remove_spaces']    = TRUE;
            
            $this->load->library('upload');
            $this->load->library('upload', $config);

            if($this->upload->do_upload('file')){
                print $this->upload->data(); #die();
            } else {
//                print $this->upload->display_errors();
                echo $this->upload->display_errors('<p>', '</p>');
            }

        } else {
            $this->output->set_output(
                                    json_encode( array(
                                                                "status"    =>  0,
                                                                "title"     =>  "Error",
                                                                "message"   =>  "Some fields are missing"
                                                        ))
            );
        }
    }
}