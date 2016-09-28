<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('session'));
		
	}
	
	
        public function index(){	

		$data=array();
		//$this->load->library('session');
        if($this->session->userdata('user')) {
					
		   //redirect("welcome");	
                    redirect("user/user_list");	
                   
		
		} else {
		   $data['title']='Login';
			
	        $this->load->view('user/login',$data);
			
		}
		
	}
	
	public function loadImageFile(){

         $config['upload_path'] = './uploads/imagepicker';
         $config['allowed_types'] = '*';
         $config['overwrite'] = TRUE;
         $this->load->library('upload', $config);
         $this->upload->initialize($config);
         
         //print_r($_FILES);
         if ($this->upload->do_upload('upload')){
            $upload_data = $this->upload->data();
            echo base_url().'uploads/imagepicker/'.$upload_data['file_name'];           
        }else{
            //echo 'error in file uploading...'; 
           print $this->upload->display_errors() ;  
        }
          
    }
}
