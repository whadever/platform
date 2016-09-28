<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {  

    public function __construct()
    {
        parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation'));
		$this->load->model('user_model','',TRUE);
		$this->load->library('session');
		$this->ums = $this->load->database('ums', TRUE);
    }
	
	public function index()
	{
		
		$data=array();
        if($this->session->userdata('user')) 
		{			
		   redirect("dashboard");	
		} 
		else 
		{
			redirect("http://".$_SERVER['SERVER_NAME']);
		}
	}
	
	public function user_login()
    {
	 	$data=array();
        $username=$this->input->post('name',true);
        $password=$this->input->post('pass',true);
		//$user_name_result = $this->user_model->user_name_check($username);

		$result = $this->user_model->user_login($username,$password);
        
        if($result) 
		{            
            $sesData['user']=$result;
            $this->session->set_userdata($sesData);
		
			redirect("dashboard");					      	    
        } 
		else 
		{
			$data['title']='Login';
            $sesData['user_password_error']='Please enter the correct username and password';
            $this->session->set_userdata($sesData);
            $this->load->view('user/login',$data);
        }

    }
	 
    public function user_logout()
    {
        $this->session->unset_userdata('user');
        redirect("http://".$_SERVER['SERVER_NAME']);
    }
	
 	public function user_add() 
	{
        
		$data['title'] = 'User add';		
		if ( $this->input->post('submit')) 
		{
			$post = $this->input->post();	
			$user_add = array(
				'fullname' => $post['fullname'],
				'name' => $post['name'],
				'pass' => MD5($post['pass']),
				'email' => $post['email'],
				'rid' => 2,
				'status' => 1,
				'created' => time()
			);
			
			$this->user_model->user_save($user_add);
			redirect('user/user_list');  
	    }
		
		$data['maincontent'] = $this->load->view('user/user_add',$data,true);
		
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);
			
	}
	
	public function user_list() 
	{
        $data['title'] = 'User';
            
		$data['maincontent'] = $this->load->view('user/user_list',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);
			
	}
	
	public function user_update($uid) 
	{
        $data['title'] = 'Edit User';		
		if ( $this->input->post('submit')) 
		{
			$post = $this->input->post();
			
			if(!empty($post['pass']))
			{
				$user_update = array(
					'fullname' => $post['fullname'],
					'name' => $post['name'],
					'pass' => MD5($post['pass']),
					'email' => $post['email']
				);
			}
			else
			{
				$user_update = array(
					'fullname' => $post['fullname'],
					'name' => $post['name'],
					'email' => $post['email']
				);
			}
			
			$this->user_model->user_update($uid,$user_update);
			redirect('user/user_list');  
	    }
	    else
	    {
			$data['user'] = $this->user_model->user_uid($uid)->row();
		}
		
		$data['maincontent'] = $this->load->view('user/user_update',$data,true);
		
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);
		
	}
	
	public function user_delete($uid)
	{
		$this->user_model->user_delete($uid);
		redirect('user/user_list');
	}
	
	public function user_email_check(){
		$get = $_GET;	
		$this->user_model->user_email_check($get);			
	}
	
	public function user_name_check(){
		$get = $_GET;	
		$this->user_model->user_name_check($get);			
	}
	
	public function user_setting($uid)
	{

		$data['title'] = "Settings";
		$user_result = $this->user_model->user_details($uid);

		$data['user_info'] = $user_result;
		$data['maincontent'] = $this->load->view('user/user_setting',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);

	}

	public function user_profile($id)
	{

		$data['title'] = "Profile";

		if( $this->input->post('submit') )
		{
			$post = $this->input->post();
			$user_update = array(
				'username' => $post['username'],
				'email' => $post['email']
			);
			
			$this->user_model->user_update($id,$user_update);
			redirect("user/user_profile/$id");  
		}
		else
		{
			$data['users'] = $this->user_model->user_load($id);
		}

		$data['maincontent'] = $this->load->view('user/user_profile',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);

	}

	public function update_email($uid)
	{
		
		$post = $this->input->post();
		$uid = $post['uid'];
		$user_update_email = array(
						'email' => $post['email'],
						'updated' => time(),
				   		);

		$this->user_model->update_email($uid,$user_update_email);
		redirect('user/user_setting/'.$uid);

	}

	public function update_password($uid)
	{
		
		$post = $this->input->post();
		$uid = $post['uid'];
		$user_update_password = array(
						'password' => MD5($post['pass'])
				   		);

		$this->user_model->update_password($uid,$user_update_password);
		redirect('user/user_setting/'.$uid);

	}
	
	public function user_detail($uid) 
	{
		if ($this->uri->segment(4)=='user_update_success')
		$data['message'] = 'User has been update successfully';		
		else
		$data['message'] = '';
				
		$user_details = $this->user_model->user_details($uid);
	   
		$data['title'] = 'User Profile';	
		$data['user_id'] = $user_details->uid;	
		
		
		$this->load->library('table');
		$this->table->set_empty("");
		
		$tmpl = array ( 'table_open'  => '<table class="table table-bordered">' );

		$this->table->set_template($tmpl); 
      
		$this->table->add_row('Full Name',$user_details->fullname); 
		$this->table->add_row('Email',$user_details->email); 
		$this->table->add_row('Role',$user_details->rname); 
		//$this->table->add_row('Status',$user_details->status == 1 ? 'Active' : 'Block');  
																	 

		$data['table'] = $this->table->generate(); 
		
		$data['maincontent'] = $this->load->view('user/user_details',$data,true);	
		
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);
	}
	
}