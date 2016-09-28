<?php 

class Welcome extends CI_Controller {
    
        private $limit = 5;
	
	public function __construct() {
		parent::__construct();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation', 'session'));       
        $this->load->library('Wbs_helper');
		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
			redirect($redirect_login_page,'refresh'); 		 
	
		}
	}
	
	public function index(){
            
		//$data=array();
		$data['title'] = 'Home';
                $user=  $this->session->userdata('user'); 
          
                $user_role_id =$user->rid; 
                if($user_role_id==1){
                    
                    redirect('user/user_list', 'refresh');
                }
                else{
                    echo 'bbbb'; 
                    $data['maincontent'] = $this->load->view('front_page',$data,true);
                    $this->load->view('home',$data);
                }		
	}
	
}
?>