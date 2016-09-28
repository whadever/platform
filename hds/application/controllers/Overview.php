<?php 
class Overview extends CI_Controller {
	
	private $limit = 10;
	
	function __construct() {
		
		parent::__construct();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->model('overview_model','',TRUE);
		$this->load->library('Wbs_helper');
                $this->load->helper('email');
                //if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
	}
        
        public function index(){
            $data['title'] = 'Overview';
           
            
           $user=  $this->session->userdata('user'); 
           
           $data['user']=$user;
           $user_id =$user->uid; 
           $role_id = $user->rid;
           //$user_name= $user->name;
           
           $overview_requests= $this->overview_model->get_overview_requests($user_id, $role_id);
           $data['new_request']= $overview_requests->num_rows;
           $data['requests'] = $overview_requests->result();
           
		
            $data['maincontent'] = $this->load->view('overview',$data,true); 
           $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }
	
    
	
	
		
   
    
	
}
