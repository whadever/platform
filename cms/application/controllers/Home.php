<?php 

class Home extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation', 'session'));
        //$this->load->model('welcome_model','',TRUE);
        //$this->load->library('Wbs_helper');
	}
	
	public function consent_home(){	
		//$data=array();
		//$data['title'] = 'Home';
        $user=  $this->session->userdata('user');
		$this->load->view('home_page');                
		
	}
	
}