<?php
class Dashboardglobal extends CI_controller{
	
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
		$user = $this->session->userdata('user');
		if($user->role!=3 && $user->company_id!=0)
			exit(0);
		$user_uid = $user->uid;
		$data['title'] = 'Dashboard';
		//$data['master_admin_access'] = $this->dashboard_model->get_master_admin_access()->result();

		$this->load->view('dashboard/dashboardglobal',$data);
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
