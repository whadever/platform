<?php 
class User extends CI_Controller {  

    public function __construct()
    {
        parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation'));
		$this->load->model('user_model','',TRUE);
		$this->load->library('session');
        $this->load->model('permission_model');
		//$this->ums = $this->load->database('ums', TRUE);
    }
	
	public function index(){	

		$data=array();
        if($this->session->userdata('user')){
        	redirect("welcome");				
		} 
		else{
			redirect("http://".$_SERVER['SERVER_NAME']);
		}		
	}

	function developments_load(){
    	$this->user_model->developments_load();
    }
	
	
    public function user_logout()
    {
        $this->session->unset_userdata('user');
        redirect("http://".$_SERVER['SERVER_NAME']);
    }
	
 	public function user_add() {

            
            if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
            
		$data['title'] = 'User add';		
	    $data['action'] = site_url('user/user_add');	
		if ( $this->input->post('submit')) {
		  
 	  	  $post = $this->input->post();	

			$user_permission = $post['user_permission'];
			$user_per = '';			
			if($_POST['rid'] == 3){
				for($i = 0; $i < count($user_permission); $i++){ 
					if($i<(count($user_permission)-1))
					{
						$user_per = $user_per.$user_permission[$i].',';
					}
					else
					{
						$user_per = $user_per.$user_permission[$i];
					}
				}
			}

	      $user_add = array(
				'name' => $post['name'],
				'pass' => MD5($post['pass']),
				'email' => $post['email'],
				'rid' => $post['rid'],
				'status' => $post['status'],
				'user_permission' => $user_per,
				'created' => time()
			   );	
				
	      $id = $this->user_model->user_save($user_add);
		  $data['message'] = 'User has been saved successfully';
	    }
		
		//$data['maincontent'] = $this->load->view('user_add',$data,true);
				
		//$this->load->view('includes/header',$data);
		//$this->load->view('includes/sidebar',$data);
		//$this->load->view('home',$data);
		//$this->load->view('includes/footer',$data);

		$data['devlopment_sub_sidebar']=$this->load->view('user_sub_sidebar',$data,true);
		$data['devlopment_content'] = $this->load->view('user_add',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('admindevelopment/development_sidebar',$data);
		$this->load->view('developments/development_home',$data);
		$this->load->view('includes/footer',$data);
			
	}
	
	public function user_role_add() {
		
            
		$data['title'] = 'User Role add';		
	    $data['action'] = site_url('user/user_role_add');	
		if ( $this->input->post('submit')) {
		  
 	  	  $post = $this->input->post();	
	      $user_role_add = array(
				'rname' => $post['rname'],
				'rdesc' => $post['rdesc'],
			   );	
				
	      $id = $this->user_model->user_role_save($user_role_add);
		  $data['message'] = 'User Role has been saved successfully';
	    }
		
		$data['maincontent'] = $this->load->view('user_role_add',$data,true);
				
		$this->load->view('includes/header',$data);
		//$this->load->view('includes/sidebar',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
			
	}
	
	public function user_list() {
            
            if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
		
                if ($this->uri->segment(3)=='user_delete_success')
		$data['message'] = 'User was deleted successfully';
		else if ($this->uri->segment(3)=='user_update_success')
		$data['message'] = 'User has been update successfully';
		else if ($this->uri->segment(3)=='user_role_delete_success')
		$data['message'] = 'User role was deleted successfully';
		else if ($this->uri->segment(3)=='user_role_update_success')
		$data['message'] = 'User role has been update successfully';
		else
		$data['message'] = '';

		$get = $_GET;		
		$data['title'] = 'User List';
		$data['action'] = site_url('user/user_list');			    	  
		
		$user_results = $this->user_model->user_list($get)->result();
		//print_r($user_results);
		
		//$r = $this->db->get('users_roles')->result();
		//print_r($r);
		
		$this->load->library('table');
		$this->table->set_empty("");
		$i=1;
		$this->table->set_heading(
                        ' #',
		'Username',
		'Email',
		'User Role',
		'Status',		
		'Action'
		);
		foreach ($user_results as $user_result){
			
			$this->table->add_row(
            $i,
			$user_result->name,
			$user_result->email,
			$user_result->rname,
			$user_result->status == 1 ? 'Active' : 'Block',
			anchor('user/user_update/'.$user_result->uid,'update',array('class'=>'update')).' '.
            anchor('user/user_delete/'.$user_result->uid,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure you want to remove this User?')"))
			); $i++;
		}
                
		
		$data['user_table'] = $this->table->generate();
		
		$data['role_title'] = 'User Role list';			    	  
		
		$user_role_results = $this->user_model->user_role_list()->result();
		
		$this->load->library('table');
		$this->table->set_empty("");
		
		$this->table->set_heading(
		'User Role Name',
		'Role Description'		
		//'Action'
		);
		foreach ($user_role_results as $user_result){
			
			$this->table->add_row(
			$user_result->rname,
			$user_result->rdesc
			//anchor('user/user_role_update/'.$user_result->rid,'update',array('class'=>'update')).' '.
            //anchor('user/user_role_delete/'.$user_result->rid,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure you want to remove this User Role?')"))
			
			);
		}
		
		$data['user_role_table'] = $this->table->generate();
		
		//$data['maincontent'] = $this->load->view('user_list',$data,true);
				
		//$this->load->view('includes/header',$data);
		//$this->load->view('includes/sidebar',$data);
		//$this->load->view('home',$data);
		//$this->load->view('includes/footer',$data);

		$data['devlopment_sub_sidebar']=$this->load->view('user_sub_sidebar',$data,true);
		$data['devlopment_content'] = $this->load->view('user_list',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('admindevelopment/development_sidebar',$data);
		$this->load->view('developments/development_home',$data);
		$this->load->view('includes/footer',$data);
			
	}
	
	public function user_update($uid) {
            
                $user=  $this->session->userdata('user');
                $user_role_id =$user->rid; 
                
                $user_id =$user->uid; 
                
                if($user_id!=1){
                    if($user_id != $uid){                   
                     redirect ('permission/access_denied');
                    }
                } 
                
                
		$data['title'] = 'User update';
                $data['action'] = site_url('user/user_update/'.$uid);
        
                
                
                
			  
		$this->_set_rules();		
		if ( $this->form_validation->run() === FALSE ) {
			
			$data['user'] = $this->user_model->user_uid($uid)->row();		
		}else {			
			$post = $this->input->post();
			$user_permission = $post['user_permission'];
			$user_per = '';			
			if($_POST['rid'] == 3){
				for($i = 0; $i < count($user_permission); $i++){ 
					if($i<(count($user_permission)-1))
					{
						$user_per = $user_per.$user_permission[$i].',';
					}
					else
					{
						$user_per = $user_per.$user_permission[$i];
					}
				}
			}

            $user=  $this->session->userdata('user');
            $user_role_id =$user->rid; 
            if($user_role_id==1){
                $user_update = array(
                        'name' => $post['name'],
                        'email' => $post['email'],
                        'rid' => $post['rid'],
                        'status' => $post['status'],
                        'user_permission' => $user_per,
                        'updated' => time(),
                   );	
            }else{
                    $user_update = array(
                        'name' => $post['name'],
                        'pass' => MD5($post['pass']),
                        'email' => $post['email'],                                    
                        'updated' => time(),
                     );
            }
                        
			$id = $this->user_model->user_update($uid, $user_update);				
			redirect('user/user_list/'.$uid.'/user_update_success');
			
		}	
			//$data['maincontent'] = $this->load->view('user_add',$data,true);		
			//$this->load->view('includes/header',$data);
			//$this->load->view('includes/sidebar',$data);
			//$this->load->view('home',$data);
			//$this->load->view('includes/footer',$data);

		$data['devlopment_sub_sidebar']=$this->load->view('user_sub_sidebar',$data,true);
		$data['devlopment_content'] = $this->load->view('user_add',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('admindevelopment/development_sidebar',$data);
		$this->load->view('developments/development_home',$data);
		$this->load->view('includes/footer',$data);
	}

	public function update_password($uid) {
    	$post = $this->input->post();
        $update_passowrd = array(
	        'pass' => MD5($post['new_password']),
		);		     
		$id = $this->user_model->user_update($uid, $update_passowrd);				
		redirect('user/user_detail/'.$uid);	

	}

	public function all_update_password($uid) {
    	$post = $this->input->post();
        $update_passowrd = array(
	        'pass' => MD5($post['new_password']),
		);		     
		$id = $this->user_model->user_update($uid, $update_passowrd);				
		redirect('user/user_update/'.$uid);	
	}
	
	public function user_check_password($uid, $enter_old_password){	
		$enter_old_pass = MD5($enter_old_password);
		$this->user_model->old_password_check($uid, $enter_old_pass);			
	}

	public function user_detail($uid) {
		//$data['title'] = 'User Details page';	
                
		if ($this->uri->segment(4)=='user_update_success')
		$data['message'] = 'User has been update successfully';		
		else
		$data['message'] = '';
		
		$user=  $this->session->userdata('user');	
	
		$user_details = $this->user_model->user_details($uid);
		$data['user'] = $user_details;             
		$data['title'] = 'Detail Information for: ' . $user_details->username;	
		$data['user_id'] = $user_details->uid;	
				
		$user_roles = $this->user_model->user_app_role_load($uid);
		
		$this->load->library('table');
		$this->table->set_empty("");
				
		// $cell = array('data' => 'Blue', 'class' => 'highlight', 'colspan' => 2);
		$this->table->add_row('User ID',$user_details->uid);       
		$this->table->add_row('UserName',$user_details->username); 
		$this->table->add_row('Email',$user_details->email); 
		if($user_roles->application_role_id == 2)
		{	
			$pass = '<a href="#UpdatePassword" title="Update Password" role="button" data-toggle="modal" class="update-password">Update Password</a>';
			$this->table->add_row('Password',$pass);
		} 
		$this->table->add_row('Status',$user_details->status == 1 ? 'Active' : 'Block');  
		                                                                             
		$data['table'] = $this->table->generate(); 
		                
		$data['maincontent'] = $this->load->view('user_details',$data,true);	
		                
		$this->load->view('includes/header',$data);
		//$this->load->view('includes/sidebar',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function user_role_update($rid) {
		$data['title'] = 'User Role update';
		$data['action'] = site_url('user/user_role_update/'.$rid);
			  
		$this->_set_userrole_rules();		
		if ( $this->form_validation->run() === FALSE ) {
			
			$data['urole'] = $this->user_model->user_role_rid($rid)->row();		
		}else {			
			$post = $this->input->post();
			$user_role_update = array(
				'rname' => $post['rname'],
				'rdesc' => $post['rdesc'],
			   );	
				
			 $id = $this->user_model->user_role_update($rid, $user_role_update);				
			 redirect('user/user_list/user_role_update_success');
			
		}	
		$data['maincontent'] = $this->load->view('user_role_add',$data,true);		
		$this->load->view('includes/header',$data);
		//$this->load->view('includes/sidebar',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	public function user_role_delete($rid){
		// delete sub company
		$this->user_model->user_role_delete($rid);
		// redirect to company list page
		redirect('user/user_list/user_role_delete_success');
	}
	
	public function user_delete($uid){
		// delete sub company
		$this->user_model->user_delete($uid);
		// redirect to company list page
		redirect('user/user_list/user_delete_success');
	}
	
	function _set_rules(){
            $this->form_validation->set_rules('name', 'User name', 'trim|required');
			//$this->form_validation->set_rules('pass', 'Password', 'trim|required');
			$this->form_validation->set_rules('email', 'Email', 'trim|required');	
    }
    
	function _set_userrole_rules(){
            $this->form_validation->set_rules('rname', 'trim|required');
    }    
	
}