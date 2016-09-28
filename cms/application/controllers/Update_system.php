<?php 

class Update_system extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->model('system_model','',TRUE);

	}

	public function index()
	{
		$user=  $this->session->userdata('user');          
        $user_id =$user->uid; 

		$data['title'] = 'System';	
		
		$data['maincontent'] = $this->load->view('system',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	} 
	public function system_add()
	{

		$add = array(
			'date' => date("Y-m-d", strtotime($_POST['date']))
		);
		$this->system_model->system_add($add);
		redirect('update_system');
	}
	
}

?>