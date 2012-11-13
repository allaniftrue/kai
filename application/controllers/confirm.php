<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Confirm extends CI_Controller {
    public function __construct() {
        parent::__construct();
    }
    
    public function index(){
        $content = file_get_contents(FCPATH.'templates/confirm.txt');
        
        echo $content;
    }
}