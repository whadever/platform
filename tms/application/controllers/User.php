<?php 
class User extends CI_Controller {  

    public function __construct()
    {
        parent::__construct();
		
		
		$this->load->model('User_model','',TRUE);
		$this->load->library(array('table','form_validation', 'session'));
		                
        $this->load->library('breadcrumbs');
		$this->load->helper(array('form', 'url'));

	
    }
	
	public function index(){	
                
		if($this->session->userdata('user')){
			$user = $this->session->userdata('user');	
            redirect("overview");		
		} else {
			
		    $user = $this->session->userdata('user'); 
			$wp_company_id = $user->company_id;
		
			$this->db->select("wp_company.*");
		 	$this->db->where('id', $wp_company_id);	
			$wpdata = $this->db->get('wp_company')->row();
			$main_url = 'http://'.$wpdata->url;
	
	        $this->session->unset_userdata('user');
	        //redirect($main_url);
			redirect('http://'.$_SERVER['SERVER_NAME']);			
		}
		
	}
	
    public function user_login()
     {
            $data=array();
            $username=$this->input->post('name',true);
            $password=$this->input->post('pass',true);
            $result=$this->User_model->user_login($username,$password);

            if($result) {
                $sesData['user']=$result;
                $this->session->set_userdata($sesData);
                $user=  $this->session->userdata('user'); 

               	$user_role_id =$user->rid; 
              if($user_role_id==1){
                  //redirect('user/user_list', 'refresh');
                  redirect("overview");
              }
              else{	
                  redirect("overview");
              }
        } else {
			$data['title']='Login';
            $sesData['exception']='Username Or Password Invalid!';
            $this->session->set_userdata($sesData);
            $this->load->view('login',$data);
        }
     }
	 
    public function user_logout()
    {
		$user = $this->session->userdata('user'); 
		$wp_company_id = $user->company_id;
	
		$this->db->select("wp_company.*");
	 	$this->db->where('id', $wp_company_id);	
		$wpdata = $this->db->get('wp_company')->row();
		$main_url = 'http://'.$wpdata->url;

        $this->session->unset_userdata('user');
        redirect($main_url);
    }
    public function user_list() {
            
          // if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
		
                if ($this->uri->segment(3)=='user_delete_success')
		$data['message'] = 'User was deleted successfully';
                else if ($this->uri->segment(3)=='user_add_success')
		$data['message'] = 'User has been saved successfully';
		else if ($this->uri->segment(3)=='user_update_success')
		$data['message'] = 'User has been update successfully';
		else if ($this->uri->segment(3)=='user_role_delete_success')
		$data['message'] = 'User role was deleted successfully';
		else if ($this->uri->segment(3)=='user_role_update_success')
		$data['message'] = 'User role has been update successfully';
                
		else
		$data['message'] = '';

		$get = $_GET;		
		$data['title'] = 'User list';
		$data['action'] = site_url('user/user_list');			    	  
		
		$user_results = $this->User_model->user_list($get);
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
                                '&nbsp;'.$i.'.',
			anchor('user/user_detail/'.$user_result->uid, $user_result->name),
			$user_result->email,
			$user_result->rname,
			$user_result->status == 1 ? 'Active' : 'Block',
                                /*
                                 * anchor('user/user_delete/'.$user_result->uid,'delete',array('class'=>'delete','onclick'=>"return confirm('Are you sure you want to remove this User?')"))
                                 * <a href="#myModal" class="trash" data-id="3" role="button" data-toggle="modal"><i class="fa fa-trash-o">x</i></a>
                                 */
            anchor('user/user_update/'.$user_result->uid,'update',array('class'=>'update')).' '.
            anchor('#deleteModal', 'delete', array('class'=>'delete','data-id'=>$user_result->uid, 'role'=>'button', 'data-toggle'=>'modal'))
			); $i++;
		}
                
		$tmpl_user= array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered">' );
        $this->table->set_template($tmpl_user);
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
                $tmpl_role= array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered">' );
                $this->table->set_template($tmpl_role);
		
		$data['user_role_table'] = $this->table->generate();
		
		$data['maincontent'] = $this->load->view('user_list',$data,true);
				
		$this->load->view('includes/header',$data);
		//$this->load->view('includes/sidebar',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
			
	}
        public function user_detail($uid) {
		//$data['title'] = 'User Details page';	
                
		if ($this->uri->segment(4)=='user_update_success')
		$data['message'] = 'User has been update successfully';		
		else
		$data['message'] = '';
				
                $user_details = $this->User_model->user_details($uid);               
                $data['title'] = 'User Detail for: ' . $user_details->username;
                $data['user_id']=$user_details->uid;
                $data['username']=$user_details->username;
				
				
		$this->load->library('table');
		$this->table->set_empty("");
				
                // $cell = array('data' => 'Blue', 'class' => 'highlight', 'colspan' => 2);
                $this->table->add_row('User ID',$user_details->uid);       
                $this->table->add_row('UserName',$user_details->username); 
                $this->table->add_row('Email',$user_details->email); 
                //$this->table->add_row('Role',$user_details->rname); 
                $this->table->add_row('Status',$user_details->status == 1 ? 'Active' : 'Block');  
                  //table table-hover 
                $tmpl_user= array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered">' );
                $this->table->set_template($tmpl_user);
                
		$data['table'] = $this->table->generate();                 
                
                $data['maincontent'] = $this->load->view('user_details',$data,true);	
                
                $this->load->view('includes/header',$data);
                //$this->load->view('includes/sidebar',$data);
                $this->load->view('home',$data);
                $this->load->view('includes/footer',$data);
	}
	
 	public function user_add() {
            
            if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
            
            $data['title'] = 'User add';		
	    $data['action'] = site_url('user/user_add');	
            if ( $this->input->post('submit')) {
		  
                $post = $this->input->post();	
                $user_add = array(
                    'name' => $post['name'],
                    'pass' => MD5($post['pass']),
                    'email' => $post['email'],
                    'rid' => $post['rid'],
                    'status' => $post['status'],
                    'created' => time()
               );	
				
                $id = $this->user_model->user_save($user_add);
		//$data['message'] = 'User has been saved successfully';
                $this->session->set_flashdata('success-message', 'User Successfully Added.');
                redirect('user/user_list');
	    }
		
            $data['maincontent'] = $this->load->view('user_add',$data,true);

            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
			
	}
	
	
	
	
	
	public function user_update($uid) {
            
                $user=  $this->session->userdata('user');
                $user_role_id =$user->rid; 
                
                $user_id =$user->uid; 
                
                if($user_role_id!=1){
                    if($user_id != $uid){                   
                     redirect ('permission/access_denied');
                    }
                } 
                
                
		$data['title'] = 'User update';
                $data['action'] = site_url('user/user_update/'.$uid);
        
                
                
                
			  
		$this->_set_rules_update();		
		if ( $this->form_validation->run() === FALSE ) {
			
                    $data['user_info'] = $this->User_model->user_uid($uid)->row();		
		}else {			
			$post = $this->input->post();
                        $user=  $this->session->userdata('user');
                        $user_role_id =$user->rid; 
                        if($user_role_id==1){
                            $user_update = array(
                                    'username' => $post['name'],
                                    
                                    'email' => $post['email']
                               );	
                        }else{
                                $user_update = array(
                                    'username' => $post['name'],
                                    'email' => $post['email']
                                 );
                        }
                        
			 $id = $this->User_model->user_update($uid, $user_update);
                         $this->session->set_flashdata('success-message', 'User Successfully Updated.');
			 //redirect('user/user_detail/'.$uid);
                         redirect('user/user_detail/'.$user->uid);
			
		}	
			$data['maincontent'] = $this->load->view('user_add',$data,true);		
			$this->load->view('includes/header',$data);
			//$this->load->view('includes/sidebar',$data);
			$this->load->view('home',$data);
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
		redirect('user/user_list');
	}
	
	public function user_delete($uid){
		// delete sub company
		$this->user_model->user_delete($uid);
		// redirect to company list page
                $this->session->set_flashdata('warning-message', 'User Successfully Removed.');
		redirect('user/user_list');
	}
	
	function _set_rules(){
            $this->form_validation->set_rules('name', 'User name', 'trim|required');
            $this->form_validation->set_rules('pass', 'Password', 'trim|required');
            //$this->form_validation->set_rules('email', 'Email', 'trim|required');	
        }
        function _set_rules_update(){
            $this->form_validation->set_rules('name', 'User name', 'trim|required');
            //$this->form_validation->set_rules('pass', 'Password', 'trim|required');
            //$this->form_validation->set_rules('email', 'Email', 'trim|required');	
        }
    
	function _set_userrole_rules(){
            $this->form_validation->set_rules('rname', 'trim|required');
    }    
	
}