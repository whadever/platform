<?php 
class Report extends CI_Controller {
	
	private $limit = 50;
	
	function __construct() {
		
		parent::__construct();		
		
		$this->load->model('report_model', '', TRUE);
		$this->load->helper(array('form', 'url'));
		date_default_timezone_set("NZ");
		
	}
	
               
	public function index() {		
		
            $data['title'] = 'Milestone Report';
			$data['developments'] = $this->report_model->get_devlopments();

            $data['maincontent'] = $this->load->view('report',$data,true);
            $this->load->view('includes/header',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);  
	}

	public function data_report() {		
		
            $data['title'] = 'Data Report';
			$get = $_GET;
			$data['developments'] = $this->report_model->get_devlopments($get);

            //$data['maincontent'] = $this->load->view('data_report',$data,true);
            //$this->load->view('includes/header',$data);
            //$this->load->view('data_report',$data);
            //$this->load->view('includes/footer',$data);

			$data['maincontent'] = $this->load->view('data_report',$data,true);
            $this->load->view('includes/header',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);  
	}

	public function contractor_report() {		
		
            $data['title'] = 'Contractor Report';

			$data['developments'] = $this->report_model->get_devlopments();

			$data['maincontent'] = $this->load->view('contractor_report',$data,true);
            $this->load->view('includes/header',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);  
	}

	public function responsibility_report() {		
		
            $data['title'] = 'Responsibility Report';

			$data['developments'] = $this->report_model->get_devlopments();

			$data['maincontent'] = $this->load->view('responsibility_report',$data,true);
            $this->load->view('includes/header',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);  
	}

	public function under_caution_report() {		
		
            $data['title'] = 'Under Caution Report';

			$data['developments'] = $this->report_model->get_devlopments();

			$data['maincontent'] = $this->load->view('under_caution_report',$data,true);
            $this->load->view('includes/header',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);  
	}
        
}
?>