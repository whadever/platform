<?php
class Dashboard extends CI_controller{
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','form'));
		$this->load->library(array('session','table'));
		$this->load->model('dashboard_model','',TRUE);
		if(!isset($_GET['uid']))
		{
			$redirect_login_page = base_url().'user';
			if(!$this->session->userdata('user')){redirect($redirect_login_page); }
		}
	}
	function index(){
		if(isset($_GET['uid']))
		{		
			$uid = $_GET['uid'];
			$user = $this->dashboard_model->user_load($uid);					
			$sesData['user']= $user;
            $this->session->set_userdata($sesData);							
		}
		$user = $this->session->userdata('user'); 
		$user_uid = $user->uid;
		$data['title'] = 'Dashboard';
		$data['client'] = $this->dashboard_model->user_client($user->company_id);
		$data['client_background'] = $this->dashboard_model->client_background($user->company_id);
		
		$data['users_create_access'] = $this->dashboard_model->get_create_user_access($user_uid)->row();
		$data['users_create_app_access'] = $this->dashboard_model->get_create_user_app_access($user_uid)->result();
		//$data['master_admin_access'] = $this->dashboard_model->get_master_admin_access()->result();
		$data['user_apps'] = $this->dashboard_model->get_user_app_info($user_uid)->result();

		$data['construction_data'] = $this->dashboard_model->get_construction_info($user_uid);
		$data['construction_last_data'] = $this->dashboard_model->get_construction_last_info();

		$this->load->view('dashboard/dashboard',$data);
	}
	function db_backups($file_name = ''){

		if($file_name == ''){
			$data['title'] = 'Database Backups';
			$data['maincontent'] = "";
			/*show list*/
			$files = scandir("/home/wclp/db_backups", 1);
			foreach ($files as $key => $file_name) {
				if($file_name != '.' && $file_name != '..'){
					$data['maincontent'] .= "<a href='".base_url()."dashboard/db_backups/{$file_name}'>{$file_name}</a><br />";
				}
			}
			$this->load->view('includes/header',$data);
			$this->load->view('home',$data);
			$this->load->view('includes/footer',$data);
		}else{
			$file = "/home/wclp/db_backups/".$file_name;
			header('Content-Description: File Transfer');
			header('Content-Type: application/octet-stream');
			header('Content-Disposition: attachment; filename="'.basename($file).'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate');
			header('Pragma: public');
			header('Content-Length: ' . filesize($file));
			readfile($file);
			exit;
		}
	}
}
