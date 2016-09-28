<?php 
class Overview extends CI_Controller {
	private $limit = 10;

	function __construct() {
		parent::__construct();
		$this->load->model('overview_model','',TRUE);
		$this->load->model('user_model','',TRUE);
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->library('Wbs_helper');
		$this->load->helper(array('form', 'url'));
        $this->load->helper('email');
        date_default_timezone_set("NZ");

		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}
		              
	}
        
    public function index(){
		if($this->session->userdata('user')){

			$user = $this->session->userdata('user');
			$result = $this->user_model->user_new_load($user->uid);

			$sesData['user'] = $result;
			$this->session->set_userdata($sesData);
			$user = $this->session->userdata('user');												
		}

		$data['title'] = 'Overview';  
		$user=  $this->session->userdata('user');               
		$data['user']=$user;
		$user_id =$user->uid; 
		$role_id = $user->rid;
     
		$data['new_company_list'] = $this->overview_model->get_overview_new_company_list()->result();
		$data['new_contact_list'] = $this->overview_model->get_overview_new_contact_list()->result();
        $data['new_category_list'] = $this->overview_model->get_overview_new_category_list()->result();   
 
		$data['maincontent'] = $this->load->view('overview',$data,true); 
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
           	
}