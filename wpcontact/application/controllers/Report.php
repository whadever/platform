<?php 
class Report extends CI_Controller {
	
	private $limit = 50;
	
	function __construct() {
		
		parent::__construct();		
		
		$this->load->model('report_model','',TRUE);
		$this->load->helper(array('form', 'url'));
        $this->load->library(array('table','form_validation', 'session'));

		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}
	}
	
               
	
    public function report_list() {	
            
           
            
            $open_request= $this->report_model->getOpenRequest();           
            $data['user_open_task']= $open_request;
            
            $close_request= $this->report_model->getCloseRequest();
            $data['user_close_task']= $close_request;
             
            $new_request= $this->report_model->getNewRequest();            
            $data['user_new_task']= $new_request;
            
            $overdue_request= $this->report_model->getOverdueRequest();            
            $data['user_overdue_task']= $overdue_request;
            
             
            $data['user_name']= $this->report_model->getUserName();
            
            $data['title'] = 'Reports';
            $data['maincontent'] = $this->load->view('report/report',$data,true);
            
            
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);  
	}
        public function open_task_report() {	
            
           
            
            $open_request= $this->report_model->getOpenRequest();
            //print_r($new_request);
            $data['user_new_task']= $open_request;
            $data['user_name']= $this->report_model->getUserName();
            
            $data['title'] = 'Open task report';
            $data['maincontent'] = $this->load->view('report/open_task_report',$data,true);
            
            
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);  
	}
        
        public function new_task_report () {	
            
           
            
            $new_request= $this->report_model->getNewRequest();
            //print_r($new_request);
            $data['user_new_task']= $new_request;
            $data['user_name']= $this->report_model->getUserName();
            
            $data['title'] = 'New task report';
            $data['maincontent'] = $this->load->view('report/new_task_report',$data,true);
            
            
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);  
	}
        public function close_task_report() {	
            
           
            
            $new_request= $this->report_model->getCloseRequest();
            //print_r($new_request);
            $data['user_new_task']= $new_request;
            $data['user_name']= $this->report_model->getUserName();
            
            $data['title'] = 'Close task report';
            $data['maincontent'] = $this->load->view('report/close_task_report',$data,true);
            
            
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);  
	}
        public function overdue_task_report() {	
            
           
            
            $new_request= $this->report_model->getOverdueRequest();
            //print_r($new_request);
            $data['user_new_task']= $new_request;
            $data['user_name']= $this->report_model->getUserName();
            
            $data['title'] = 'Overdue task report';
            $data['maincontent'] = $this->load->view('report/overdue_task_report',$data,true);
            
            
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);  
	}

        
}
?>