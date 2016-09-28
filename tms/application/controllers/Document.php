<?php 
class Document extends CI_Controller {
	
	private $limit = 10;
	
	function __construct() {
		
		parent::__construct();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation', 'session'));
		
		$this->load->library('Wbs_helper');
                //$this->load->helper('email');
                $this->load->helper('download');
                //if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
                
		}
        
        public function index(){
		
            
           $redirect_login_page = base_url().'user';
            if(!$this->session->userdata('user')){redirect($redirect_login_page); }
            $data['title'] = 'Documents';
               
          $data['filename']='';
           
           
		
            $data['maincontent'] = $this->load->view('documents',$data,true); 
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }
        
        
        public function download_file($filename){
            $data['title'] = 'Documents';
            $redirect_login_page = base_url().'user';
            if(!$this->session->userdata('user')){redirect($redirect_login_page); }
            
            $contents = @file_get_contents(base_url()."uploads/request/document/".$filename);
			
            if (!$contents) {
                 $data['filenotfound'] = 'Opps! This file is not found at the Server.';
            }else{
                $data = file_get_contents(base_url()."uploads/request/document/".$filename); 
           
                // Read the file's contents
                $name = $filename;
                force_download($name, $data);
            }
            
            $data['maincontent'] = $this->load->view('documents',$data,true); 
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }


        public function download_notefile($filename){
            $data['title'] = 'Documents';
            $redirect_login_page = base_url().'user';
            if(!$this->session->userdata('user')){redirect($redirect_login_page); }
            
            $contents = @file_get_contents(base_url()."uploads/notes/images/".$filename);
            if (!$contents) {
                 $data['filenotfound'] = 'Opps! This file is not found at the Server.';
            }else{
                $filedata = file_get_contents(base_url()."uploads/notes/images/".$filename); 
             
                // Read the file's contents
                $name = $filename;
                force_download($name, $filedata);
            }
            $data['maincontent'] = $this->load->view('documents',$data,true); 
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        
        }
    
	
}