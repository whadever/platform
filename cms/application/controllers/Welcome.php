<?php 

class Welcome extends CI_Controller {
    
        private $limit = 5;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation', 'session'));
        $this->load->model('welcome_model','',TRUE);
		$this->load->model('user_model','',TRUE);
        $this->load->library('Wbs_helper');
		$this->ums = $this->load->database('ums', TRUE);
	}
	
	public function index()
	{
		$data['title'] = 'Home';

		if($this->session->userdata('user'))
		{		
			$user = $this->session->userdata('user');

			$result = $this->user_model->user_new_load($user->uid);


			$sesData['user'] = $result;
        	$this->session->set_userdata($sesData);
        	$user = $this->session->userdata('user');
	
			$user_group = explode(',',$user->group_id);
			for($i = 0; $i < count($user_group); $i++){
				if($i<1){
					$user_group_id = $user_group[$i];
				}
			}
			$sesData['user_group_id'] = $user_group_id;
        	$this->session->set_userdata($sesData);

			if($user->rid==1){
				redirect('user/user_role_list');
			}else if($user->rid==2){
				redirect('home/consent_home');
			}else if($user->rid==3){
				redirect('home/consent_home');
			}
						
		}
		else{
			$user = $this->session->userdata('user');
			redirect("http://".$_SERVER['SERVER_NAME']);
		}
	}

	public function userChangePermission($user_group_id)
	{
		$sesData['user_group_id'] = $user_group_id;
        $this->session->set_userdata($sesData);
	}
	
}
?>