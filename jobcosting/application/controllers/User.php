<?php 
class User extends CI_Controller {  

    public function __construct()
    {
        parent::__construct();

		$this->load->library(array('table','form_validation', 'session'));
		$this->load->helper(array('form', 'url'));
	
    }
	
	public function index(){	
                
		if($this->session->userdata('user')){
            redirect("job");		
		}else{
			redirect('http://'.$_SERVER['SERVER_NAME']);			
		}
		
	}
	 
    public function user_logout()
    {
        $this->session->unset_userdata('user');
        redirect('http://'.$_SERVER['SERVER_NAME']);
    }
	
}