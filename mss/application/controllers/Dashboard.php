<?php 
class Dashboard extends CI_Controller {  

    public function __construct()
    {
        parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation'));
		$this->load->model('user_model','',TRUE);
		$this->load->library('session');
    }
	
	public function index()
	{	
		$data['title'] = 'Dashboard';
		$data['maincontent'] = $this->load->view('dashboard/dashboard', $data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	
}