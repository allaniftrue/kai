<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Referrals extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        
        $this->load->library("Mlib_trac");
        $this->load->library('pagination');
        $this->mlib_trac->trac_login();
        $this->load->model("Referralsq");
    }
    
    public function index() {
        
        $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
        $total_page = $this->Referralsq->my_referrals_total();
        
        $config["uri_segment"] = 2;
        $config['base_url'] = base_url()."referrals/";
        $config['total_rows'] = $total_page;
        $config['per_page'] = 30; 
        $config['num_links'] = 20;
        $config['full_tag_open'] = '<div class="pagination"><ul>';
        $config['full_tag_close'] = '</ul></div>';
        $config['num_tag_open'] = "<li>";
        $config['num_tag_close'] = "</li>";
        $config['cur_tag_open'] = '<li class="active"><a href="#">';
        $config['cur_tag_close'] = "</a></li>";
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        
        $this->pagination->initialize($config); 
        
        $referrals = $this->Referralsq->my_referrals($config['per_page'],$page);
        
        $data["referrals"] = $referrals;
        $this->load->view('referrals/referrals_view',$data);
    }
}