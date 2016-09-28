<?php 

class Welcome extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation', 'session'));
        $this->load->model('user_model','',TRUE);
        $this->load->library('Wbs_helper');
		//$this->ums = $this->load->database('ums', TRUE);
	}
	
	public function index(){	
		$data['title'] = 'Home';

		if($this->session->userdata('user'))
		{	
			$user = $this->session->userdata('user');
	
			$uid = $user->uid;
			$user = $this->user_model->user_load($uid);					
			$sesData['user']= $user;
            $this->session->set_userdata($sesData);	

			$user1 = $this->session->userdata('user');
			$user_role = $this->user_model->user_app_role_load($user1->uid);
	
			if($user_role->application_role_id==1){
				$this->load->view('admin_front_page',$data);
				//redirect('admindevelopment/development_list, 'refresh');
			}else if($user_role->application_role_id==2 || $user_role->application_role_id==4 || $user_role->application_role_id==5){
				$data['maincontent'] = $this->load->view('front_page',$data,true);
				$this->load->view('home',$data);
			}else if($user_role->application_role_id==3){
				$data['maincontent'] = $this->load->view('front_page',$data,true);
				$this->load->view('home',$data);
			}else{
				redirect("user");
			}
						
		}else{
			$user = $this->session->userdata('user');
			
			redirect("user");
		}
	
	}
	
}
