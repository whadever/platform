<?php 
class User extends CI_Controller {

	private $payment_mode = 'prod';
	private $payment_url, $payment_username, $payment_password;

    public function __construct(){
        parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation'));
		$this->load->model('user_model','',TRUE);
		$this->load->model('client_model','',TRUE);
		$this->load->library('session');
		$this->load->library('breadcrumbs');
		$this->load->helper('html');
		$this->cms = $this->load->database('cms', TRUE);

		if($this->payment_mode == 'dev'){
			$this->payment_username = "WilliamsSolutionsDev";
			$this->payment_password = "password12";
			$this->payment_url = "uat.paymentexpress.com/pxpost.aspx";
		}else{
			$this->payment_username = "WilliamsBusinessS1026";
			$this->payment_password = "d0a49be2";
			$this->payment_url = "sec.paymentexpress.com/pxpost.aspx";
		}
    }
	
	public function index(){	
		$data=array();
        if($this->session->userdata('user')) {
			$user =  $this->session->userdata('user');
            if($user->role==3){
				redirect("client");
			}else if($user->role==1){
				redirect("user/user_list");
			}else{
				redirect("dashboard");
			}	                   		
		} else {
			$data['title']='Login';	
			$result=$this->user_model->user_login_default();
			$result->backgroundWclp=$this->user_model->client_background($result->backgroundWclp_id)->filename;
			if(!isset($result)){
				$subdomain = str_replace(".wclp.co.nz", "", $_SERVER["HTTP_HOST"]);
				if($subdomain =='wclp.co.nz' || $subdomain =='' || $subdomain =='www')
				{
					$data['company'] = (object) array('id' => '13',
						'client_name' => 'Williams Corporation',
						'url' => 'wclp.co.nz',
						'colour_one' => '#cc1618',
						'colour_two' => '#fab800',
						'file_id' => 22,
						'plan_id' => 1,
						'pricing' => 0,
						'filename' => 'william_platform_logo.png'
					);
					
				}
				else{
					redirect("user/error");
				}
			} else {
		    
		    	$data['company']=$result;
			}	
		    //echo '<pre>'; print_r($data); die();
	        $this->load->view('user/login',$data);			
		}		
	}
	public function error(){
    
		$this->load->view('user/error',$data);
     }
     
    public function UpdateFirstLogin($uid,$up){
    	$update = array(
			'first_login' => $up
		);
		$this->user_model->user_update($uid,$update);
	}
     
    public function user_login(){
    
		$data=array();
		$username=$this->input->post('username',true);
		$password=$this->input->post('password',true);
		$result=$this->user_model->user_login($username,$password);
		if($result){
			/*if the company is deactivated we have to send the user to payment page*/
			if(!$result->is_active && $result->uid != 1){
				/*if admin redirect him to payment page. otherwise display the login form with a message*/
				if($result->role == 1){

					$sesData['payment_user'] = $result;
					$this->session->set_userdata($sesData);
					redirect('user/activation_payment');
				}else{
					$data['title']='Login';
					$result=$this->user_model->user_login_default();
					$result->backgroundWclp=$this->user_model->client_background($result->backgroundWclp_id)->filename;
					if(!isset($result)){
						$data['company'] = (object) array('id' => '13',
							'client_name' => 'Williams Corporation',
							'url' => 'wclp.co.nz',
							'colour_one' => '#cc1618',
							'colour_two' => '#fab800',
							'file_id' => 22,
							'plan_id' => 1,
							'pricing' => 0,
							'filename' => 'william_platform_logo.png'
						);
					} else {

						$data['company']=$result;
					}
					$sesData['exception']='Your company\'s trial is expired. Please renew it by log in as an admin.';
					$this->session->set_userdata($sesData);

					redirect(site_url('user'));


				}

			}
			$sesData['user']=$result;
			$this->session->set_userdata($sesData);
			$user = $this->session->userdata('user'); 
			
			$update = array(
				'last_login' => date("Y-m-d")
			);
			$this->user_model->user_update($user->uid,$update);
			
			if($result->last_login=='0000-00-00'){
				$update = array(
					'first_login' => 1
				);
				$this->user_model->user_update($user->uid,$update);
			}

			if($user->role==3){
				redirect("dashboardglobal");
			}else if($user->role==1){
				redirect("user/user_list");
			}else{
				redirect("dashboard");
			}
			
		}else{
			$data['title']='Login';
			$result=$this->user_model->user_login_default();
			$result->backgroundWclp=$this->user_model->client_background($result->backgroundWclp_id)->filename;
			if(!isset($result)){
				$data['company'] = (object) array('id' => '13',
					'client_name' => 'Williams Corporation',
					'url' => 'wclp.co.nz',
					'colour_one' => '#cc1618',
					'colour_two' => '#fab800',
					'file_id' => 22,
					'plan_id' => 1,
					'pricing' => 0,
					'filename' => 'william_platform_logo.png'
				);
			} else {
		    
		    	$data['company']=$result;
			}
			$sesData['exception']='Your username or password is incorrect, please try again.';
			$this->session->set_userdata($sesData);
			$this->load->view('user/login',$data);
		}
     }
	 public function forgot_password(){
    
		$data=array();
		$data['title']='Forgot Password';
		$company_detail=$this->user_model->user_login_default();
		$company_detail->backgroundWclp=$this->user_model->client_background($company_detail->backgroundWclp_id)->filename;
		if(!isset($company_detail)){
			$data['company'] = (object) array('id' => '13',
				'client_name' => 'Williams Corporation',
				'url' => 'wclp.co.nz',
				'colour_one' => '#cc1618',
				'colour_two' => '#fab800',
				'file_id' => 22,
				'plan_id' => 1,
				'pricing' => 0,
				'filename' => 'william_platform_logo.png'
			);
		} else {
	
			$data['company']=$company_detail;
		}
		$username=$this->input->post('username',true);
		$email=$this->input->post('email',true);
		if(isset($username) && isset($email))
		{
			$result=$this->user_model->forgot_password($username,$email);
			if($result){
			
				$company=$this->user_model->user_reset_link($username,$email); 
				$to = $email;
				$from= 'notification@wclp.co.nz';
				$notes_from = $company->client_name;
				$subject = $company->client_name.' : Reset Password';
	
				$headers = "From: ".$from . "\r\n";
				$headers .= "Reply-To: notification@wclp.co.nz\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

				$message = "Hello ".$username.",<br/><br/>
				You (or someone else) have requested a password reset.<br/><br/>
				To reset your password please follow this link: <a href='http://".$company->url.'/user/reset_password/'.md5(time())."'>".$company->url.'/user/reset_password/'.md5(time())."</a><br/><br/>
				If you did not request this, please ignore this email and it will expire in 24hours.<br/><br/>
				For any further issues please contact Williams Business on: +64 3 260 0604<br/><br/>
				Kind regards,<br/>
				Williams Business Solutions";
				mail($to, $subject, $message, $headers);
				$this->load->view('user/forgot_password_email',$data);
			
			}
			else{
				$sesData['exception']='Your username or email is incorrect, please try again.';
				//$this->session->set_userdata($sesData);
				$this->load->view('user/forgot_password',$data);
			}
		}
		else{
			$this->load->view('user/forgot_password',$data);
		}
     }
     public function reset_password($reset_password){
    
		$data=array();
		$data['title']='Reset Password';
		$company_detail=$this->user_model->user_login_default();
		$company_detail->backgroundWclp=$this->user_model->client_background($company_detail->backgroundWclp_id)->filename;
		if(!isset($company_detail)){
			$data['company'] = (object) array('id' => '13',
				'client_name' => 'Williams Corporation',
				'url' => 'wclp.co.nz',
				'colour_one' => '#cc1618',
				'colour_two' => '#fab800',
				'file_id' => 22,
				'plan_id' => 1,
				'pricing' => 0,
				'filename' => 'william_platform_logo.png'
			);
		} else {
	
			$data['company']=$company_detail;
		}
		$email=$this->input->post('email',true);
		$password=$this->input->post('password',true);
		$confirm_password=$this->input->post('confirm_password',true);
		$data['reset_password'] = $reset_password;
		if(isset($email) && isset($password) && $password == $confirm_password)
		{
			$result=$this->user_model->reset_password($email,$password, $reset_password);
			if($result){
				$this->load->view('user/reset_password_success',$data);
			
			}
			else{
				$sesData['exception']='Your username or email or confirm password is incorrect, please try again.';
				//$this->session->set_userdata($sesData);
				$this->load->view('user/reset_password',$data);
			}
		}
		else{
			$this->load->view('user/reset_password',$data);
		}
     }
    
    public function forgot_password_success(){
    
		$data=array();
		$username=$this->input->post('username',true);
		$password=$this->input->post('email',true);
		$result=$this->user_model->forgot_password($username,$email);
		if($result){
			$sesData['user']=$result;
			$this->session->set_userdata($sesData);
			$user=  $this->session->userdata('user'); 
			if($user->role==3){
				redirect("client");
			}else if($user->role==1){
				redirect("user/user_list");
			}else{
				redirect("dashboard");
			}
			
		}else{
			$data['title']='Forgot Password';
			$result=$this->user_model->user_login_default();
			$result->backgroundWclp=$this->user_model->client_background($result->backgroundWclp_id)->filename;
			if(!isset($result)){
				$data['company'] = (object) array('id' => '13',
					'client_name' => 'Williams Corporation',
					'url' => 'wclp.co.nz',
					'colour_one' => '#cc1618',
					'colour_two' => '#fab800',
					'file_id' => 22,
					'plan_id' => 1,
					'pricing' => 0,
					'filename' => 'william_platform_logo.png'
				);
			} else {
		    
		    	$data['company']=$result;
			}
			//$sesData['exception']='Your username or password is incorrect, please try again.';
			$this->session->set_userdata($sesData);
			$this->load->view('user/forgot_password',$data);
		}
     }
    public function user_logout()
    {
        $this->session->unset_userdata('user');
        $this->session->unset_userdata('plan');
        $this->session->unset_userdata('company_info');
        redirect("user");
    }

	public function clear_search()
    {
        $this->session->unset_userdata('name');
		$this->session->unset_userdata('system');
    }
    
    public function user_access_list() {
        $user=  $this->session->userdata('user');             
		$data['message'] = '';
		$get = $_GET;		
		$data['title'] = 'User list';
		$data['action'] = site_url('user/user_list');			    	  
		
		$user_results = $this->user_model->user_list($get)->result();
		$this->load->library('table');
		$this->table->set_empty("");
		$a=1;
		$this->table->set_heading(
            ' #',
			'Username',
			'Email',			
			'Application Permission',		
			'Action'
		);
		foreach ($user_results as $user_result){	
			$user_id = $user_result->uid;
			$app_pers = $this->user_model->user_app_permission($user_id)->result();
			$app_per = "";
			for($i = 0; $i < count($app_pers); $i++){
				if($i!=count($app_pers)-1){
					$app_per .= $app_pers[$i]->application_name.', ';
				}else{
					$app_per .= $app_pers[$i]->application_name;
				}
			}		
			$this->table->add_row(
	            '&nbsp;'.$a.'.',
				anchor('user/user_detail/'.$user_result->uid, $user_result->username),
				$user_result->email,			
				$app_per,
	            anchor('user/user_update/'.$user_result->uid, img(base_url().'images/edit.png'),array('class'=>'update', 'title'=>'Update User')).' '.
	            anchor('#deleteModal', img(base_url().'images/delete.png'), array('class'=>'delete', 'title'=>'Delete User','data-id'=>$user_result->uid, 'role'=>'button', 'data-toggle'=>'modal'))
			); $a++;
		}
                
		$tmpl_user= array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered">' );
        $this->table->set_template($tmpl_user);
		$data['user_table'] = $this->table->generate();
                		
		$data['maincontent'] = $this->load->view('user/user_list',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
			
	}
    
    public function user_list() {
        $user=  $this->session->userdata('user');  
        
            $uid = $user->uid;
            $userrole=$user->role;
			$company_id=$user->company_id;
			
        $f_query = $this->db->query("SELECT first_login FROM users where uid=".$uid)->row();
		$first_login = $f_query->first_login;

		$data['message'] = '';
		$get = $_POST;		
		$data['title'] = 'User list';
		$data['action'] = site_url('user/user_list');		    	  
		
		$user_results = $this->user_model->user_list($uid,$userrole,$company_id,$get)->result();
		$users_plan = $this->user_model->users_total($company_id, $user->plan_id);
		$data['users_plan']=$users_plan;
		$data['users']=$user_results;
		$data['user'] = $user;
		$this->load->library('table');
		$this->table->set_empty("");
		$a=1;
		$this->table->set_heading(
            'ID',
			'Name',
			'Email',			
			'Systems',
			'Permissions',		
			'Edit'
		);
		foreach ($user_results as $user_result){
                    
			$user_id = $user_result->uid;
			$user_role = $user_result->role;
			$app_pers = $this->user_model->user_app_permission($user_id)->result();
                        //print_r($app_pers); echo ' ';
			$app_per = "";
			$app_role_per = '';
			for($i = 0; $i < count($app_pers); $i++){
				if($i!=count($app_pers)-1){
					//$app_per .= $app_pers[$i]->application_name.', ';
                                        $app_per .= $app_pers[$i]->application_name;
                                        $app_per .= empty($app_pers[$i]->application_name) ? '' : '<br>';
                                        
				}else{
					$app_per .= $app_pers[$i]->application_name;
				}
			}

			$app_role_perss = $this->user_model->user_app_role_permission_one($user_id)->result();
			$app_role_per = "";
			foreach($app_role_perss as $app_role_pers){
				
				$app_ro = $this->user_model->user_app_role_permission($app_role_pers->application_id,$app_role_pers->application_role_id)->row();
				$app_role_per .= $app_ro->application_role_name;
				$app_role_per .= empty($app_ro->application_role_name) ? '' : '<br>';
			}
            
            if($a=='1' && $user_role=='1'){
            	if($first_login=='3'){
            		$update_icon = '<div id="third" class="third" data-tipso="Click here to modify user`s permission and to assign user system(s).">&nbsp;</div>';
				}else{
					$update_icon = '<div id="third" data-tipso="Click here to modify user`s permission and to assign user system(s).">'.anchor('user/user_update/'.$user_result->uid, img(base_url().'images/edit.png'),array('class'=>'update', 'title'=>'Update User')).'</div>';
				}
				
			}else{
				$update_icon = anchor('user/user_update/'.$user_result->uid, img(base_url().'images/edit.png'),array('class'=>'update', 'title'=>'Update User'));
			}       
            
            $delete_icon = anchor('#deleteModal_'.$user_result->uid, img(base_url().'images/delete.png'), array('class'=>'delete', 'title'=>'Delete User','data-id'=>$user_result->uid, 'role'=>'button', 'data-toggle'=>'modal'));
                        
                        
                        
			$this->table->add_row(
	            '00'.$a,
				($user_role=='1') ? $user_result->username : anchor('user/user_detail/'.$user_result->uid, $user_result->username),
				$user_result->email,			
				$app_per,
				$app_role_per,
                $update_icon           
	                            
           ); $a++;
		}
                
		$tmpl_user= array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered table-striped">' );
        $this->table->set_template($tmpl_user);
		$data['user_table'] = $this->table->generate();
                		
		$data['maincontent'] = $this->load->view('user/user_list',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
			
	}
    public function user_detail($uid) {               
		if ($this->uri->segment(4)=='user_update_success')
		$data['message'] = 'User has been update successfully';		
		else
		$data['message'] = '';
				
        $user_details = $this->user_model->user_details($uid);               
        $data['title'] = 'User Detail for: ' . $user_details->username;
        $data['user_id']=$user_details->uid;
        $data['username']=$user_details->username;
								
		$this->load->library('table');
		$this->table->set_empty("");

        $this->table->add_row('User ID',$user_details->uid); 
        $this->table->add_row('UserName',$user_details->username); 
        $this->table->add_row('Email',$user_details->email); 
        $this->table->add_row('Status',$user_details->status == 1 ? 'Active' : 'Block');  
        //table table-hover 
        $tmpl_user= array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered">' );
        $this->table->set_template($tmpl_user);
                
		$data['table'] = $this->table->generate(); 
                
        $user_results = $this->user_model->application_user_list($uid)->result();
		
		$this->load->library('table');
		$this->table->set_empty("");
		$i=1;
		$this->table->set_heading(
            'ID',	
            'Systems',
			'Permissions'		
		);
		foreach ($user_results as $user_result){			
			$this->table->add_row(
				'00'.$i,
				$user_result->application_name,
				$user_result->application_role_name
			); $i++;
		}
                
		$tmpl_user= array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered">' );
        $this->table->set_template($tmpl_user);
		$data['user_table'] = $this->table->generate();
                
        $data['maincontent'] = $this->load->view('user/user_details',$data,true);	
                
        $this->load->view('includes/header',$data);
        $this->load->view('home',$data);
        $this->load->view('includes/footer',$data);
	}
	
    public function user_add() {
        $data['title'] = 'User add';		
		$data['action'] = site_url('user/user_add');
                
        $user=  $this->session->userdata('user');       
        $uid = $user->uid;
        $userrole=$user->role;
        $company_id=$user->company_id;
        $data['user'] = $user; 
        if( $this->input->post('submit')){		  
			$post = $this->input->post(); 
			

			$select_development = $post['hds_dev_permission'];            
            if($select_development == ''){$hds_dev_permission_id='';}
            else{$hds_dev_permission_id = implode(",", $select_development);}   

			$select_cms = $post['cms_group_id'];            
            if($select_cms == ''){$cms_group_id='';}
            else{$cms_group_id = implode(",", $select_cms);}      
       
			$user_add = array(
				'company_id' => $company_id,
				'username' => $post['username'],
				'password' => MD5($post['pass']),
				'email' => $post['email'],                    
				'role' => 2,
				'status' => 1,
                'created_by'=>$uid,
				'created' => date("Y-m-d H:i:s")
			);
			$user_id = $this->user_model->user_save($user_add);
                        
			$output = array_slice($post, 3);                         
            $output2= array_pop($output);
            //print_r(count($output)); die();
			for($i=1; $i<=count($output)/2; $i++){
			
				$app_data[] = array( 
					'company_id' => $company_id,             	
					'user_id' => $user_id,                               
					'application_id' => $post['app'.$i],
					'application_role_id'=>$post['approle'.$i],
					'hds_dev_permission'=> $hds_dev_permission_id,
					'cms_group_id'=> $cms_group_id
				);
						
			}
            $this->user_model->user_application_save($app_data);
            			
			$this->session->set_flashdata('success-message', 'User Successfully Added.');
			redirect('user/user_list');
	    }
		
        $data['maincontent'] = $this->load->view('user/user_add',$data,true);
        $this->load->view('includes/header',$data);
        $this->load->view('home',$data);
        $this->load->view('includes/footer',$data);
			
	}
		
    public function user_update($uid) {          
        $user=  $this->session->userdata('user');
        $user_role_id =$user->role; 
        $user_id =$user->uid; 
        $company_id=$user->company_id;

		$logged_user_id = $user->uid;

        $data['title'] = 'User update';
        $data['action'] = site_url('user/user_update/'.$uid);
	
		if(!$this->input->post('submit')) {			
            $data['user_info'] = $this->user_model->user_uid($uid)->row();		
		}else {                   
            $post = $this->input->post();
            $user=  $this->session->userdata('user');

			$select_development = $post['hds_dev_permission'];            
            if($select_development == ''){$hds_dev_permission_id='';}
            else{$hds_dev_permission_id = implode(",", $select_development);} 

			$select_cms = $post['cms_group_id'];            
            if($select_cms == ''){$cms_group_id='';}
            else{$cms_group_id = implode(",", $select_cms);} 


            $user_update = array(
                    'username' => $post['username'],
                    'email' => $post['email']
            );	
            $this->user_model->user_update($uid, $user_update);
            $this->user_model->user_app_role_delete($uid);

            $output = array_slice($post, 3);                         
            $output2= array_pop($output);
            for($i=1; $i<=count($output)/2; $i++){

				$app_data[] = array(  
					'company_id' => $company_id,              	
					'user_id' => $uid,                               
					'application_id' => $post['app'.$i],
					'application_role_id'=>$post['approle'.$i],
					'hds_dev_permission'=> $hds_dev_permission_id,
					'cms_group_id'=> $cms_group_id
				);
									
            }
		
			if($uid != $logged_user_id){
            	$this->user_model->user_application_save($app_data);
			}

            $this->session->set_flashdata('success-message', 'User Successfully Updated.');
            redirect('user/user_list/');
			
		}	
        $data['maincontent'] = $this->load->view('user/user_add',$data,true);		
        $this->load->view('includes/header',$data);
        $this->load->view('home',$data);
        $this->load->view('includes/footer',$data);
    }
    
    public function upgrade_plan() {          
                          
		$post = $this->input->post();

		$plan_id = $post['plan_id'];	
		if($this->user_model->upgrade_plan($post['company_id'], $plan_id)){
			//$this->session->userdata('user')->plan_id = $post['plan_id'];
			//$this->session->set_userdata('plan_id', $plan_id);
			$this->session->set_flashdata('success-message', 'User Successfully Updated. Logout and login to get more features!');
        	redirect('user/user_list/');
        }
        else{
        	$this->session->set_flashdata('error-message', 'Something is wrong!');
        	redirect('user/user_list/');
        }
			
			
        /*$data['maincontent'] = $this->load->view('user/user_list',$data,true);		
        $this->load->view('includes/header',$data);
        $this->load->view('home',$data);
        $this->load->view('includes/footer',$data);*/
    }

	public function user_password_update($uid) {

        $user = $this->session->userdata('user'); 
		
		//if(!$uid){
			$uid = $user->uid; //task #4534
		//}

		$data['action'] = site_url('user/user_password_update/'.$uid);

		$this->load->library('google');

		if(!$this->input->post('submit')) {
            $data['user_info'] = $this->user_model->user_uid($uid)->row();		
		}else {                   
            $post = $this->input->post();

            $user_update = array(
                    'username' => $post['username'],
                    'email' => $post['email'],
					'password' => MD5($post['password'])
            );	
            $this->user_model->user_update($uid, $user_update);
            $this->session->set_flashdata('success-message', 'User Successfully Updated.');
            redirect('user/user_setting/'.$uid);
			
		}
		$data['user_app_roles'] = $this->db->get_where('users_application',array('user_id' => $user->uid))->result();
        $data['maincontent'] = $this->load->view('user/user_password_update',$data,true);		
        $this->load->view('includes/header',$data);
        $this->load->view('home',$data);
        $this->load->view('includes/footer',$data);
    }
    
    public function check_current_password($current_password) {          
        $user = $this->session->userdata('user'); 
        $uid = $user->uid;
        
        $this->db->where('uid', $uid);
		$row = $this->db->get('users')->row();  
		if($row->password==MD5($current_password)){
			echo '1';
		}else{
			echo '0';
		}
    }

	public function user_setting($uid) {          
        $user=  $this->session->userdata('user'); 	
	
        $user_results = $this->user_model->user_uid($uid)->result();	
		$data['user_info'] = $this->user_model->user_uid($uid)->row();
		$data['users']=$user_results;
		$this->load->library('table');
		$this->table->set_empty("");
		$a=1;
		$this->table->set_heading(
            'ID',
			'Name',
			'Email',			
			'System',
			'Permission'
		);
		foreach ($user_results as $user_result){
                    
			$user_id = $user_result->uid;
			$app_pers = $this->user_model->user_app_permission($user_id)->result();
                        //print_r($app_pers); echo ' ';
			$app_per = "";
			for($i = 0; $i < count($app_pers); $i++){
				if($i!=count($app_pers)-1){
					//$app_per .= $app_pers[$i]->application_name.', ';
                                        $app_per .= $app_pers[$i]->application_name;
                                        $app_per .= empty($app_pers[$i]->application_name) ? '' : '<br>';
                                        
				}else{
					$app_per .= $app_pers[$i]->application_name;
				}
			}
			
			$app_role_perss = $this->user_model->user_app_role_permission_one($user_id)->result();
			$app_role_per = "";
			foreach($app_role_perss as $app_role_pers){
				
				$app_ro = $this->user_model->user_app_role_permission($app_role_pers->application_id,$app_role_pers->application_role_id)->row();
				$app_role_per .= $app_ro->application_role_name;
				$app_role_per .= empty($app_ro->application_role_name) ? '' : '<br>';
			}
         
			$this->table->add_row(
	            '00'.$a,
				$user_result->username,
				$user_result->email,			
				$app_per,
				$app_role_per
			); $a++;
		}
                
		$tmpl_user= array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered table-striped">' );
        $this->table->set_template($tmpl_user);
		$data['user_table'] = $this->table->generate();	
		
        $data['maincontent'] = $this->load->view('user/user_setting',$data,true);		
        $this->load->view('includes/header',$data);
        $this->load->view('home',$data);
        $this->load->view('includes/footer',$data);
    }
	
    public function user_delete($uid){
		$this->user_model->user_delete($uid);
        $this->user_model->user_app_role_delete($uid);
        $this->session->set_flashdata('warning-message', 'User Successfully Removed.');
		redirect('user/user_list');
	}   

	public function user_email_check(){		
		$get = $_GET;	
		$this->user_model->user_email_check($get);			
	} 

	public function username_check(){		
		$get = $_GET;	
		$this->user_model->username_check($get);			
	}

	/*user select plan and applications*/
	public function select_plan(){
		$user=  $this->session->userdata('user');
		if(!$user || $user->role != 1){
			exit;
		}
		/*task #4670. is there discount?*/
		$discount_amount = 0;
		$sql = "select code.discount, company_discount.id " .
			" FROM wp_discount_codes code JOIN wp_company_discounts company_discount ON code.id = company_discount.discount_code_id ".
			" WHERE wp_company_id = {$user->company_id} AND company_discount.months_left != 0 LIMIT 0, 1";

		$discount = $this->db->query($sql)->row();
		if($discount){
			$discount_amount = $discount->discount;
		}

		$this->db->select('price.*, wp_plans.no_of_users');
		$this->db->join('wp_plan_prices price','price.plan_id = wp_plans.id');
		$this->db->join('application','application.id = price.application_id','left');
		$res = $this->db->get('wp_plans')->result();
		$plans = array();
		foreach($res as $r){
			$plans[$r->plan_id][$r->application_id] = $r->price - $r->price * $discount_amount / 100;
			$plans[$r->plan_id]['no_of_users'] = $r->no_of_users;
		}
		/*current plan*/
		$this->db->select('wp_plans.*');
		$this->db->join('wp_plans','wp_plans.id = wp_company.plan_id');
		$data['current_plan'] = $this->db->get_where('wp_company',array('wp_company.id'=>$user->company_id),1,0)->row();

		/*current applications*/
		$this->db->select('wp_company_applications.*');
		$data['current_applications'] = $this->db->get_where('wp_company_applications',array('company_id'=>$user->company_id))->result();

		$data['plans'] = $plans;

		/*company info*/
		$data['company'] = $this->db->get_where('wp_company',array('id'=>$user->company_id),1,0)->row();

		$data['maincontent'] = $this->load->view('user/plans',$data,true);

		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);


	}

	public function get_total_amount($from_class = false){

		$post = $this->input->post();
		$total = 0;
		$package = $post['package'];
		$applications = $post['plan_'.$package];
		$applications[] = 0; //must have user pricing
		$applications = "(".implode(',',$applications).")";

		$this->db->select("SUM(price) price");
		$this->db->where("plan_id = {$package} AND application_id in {$applications}");
		$price = $this->db->get('wp_plan_prices')->row()->price;

		if ($this->input->is_ajax_request() && !$from_class) {
			/*task #4670. is there discount?*/
			$user=  $this->session->userdata('user');
			$discount_amount = 0;
			$sql = "select code.discount, company_discount.id " .
				" FROM wp_discount_codes code JOIN wp_company_discounts company_discount ON code.id = company_discount.discount_code_id ".
				" WHERE wp_company_id = {$user->company_id} AND company_discount.months_left != 0 LIMIT 0, 1";

			$discount = $this->db->query($sql)->row();
			if($discount){
				$discount_amount = $discount->discount;
			}
			$price = $price - $price * $discount_amount / 100;
			echo round($price, 2);
		}
		return round($price, 2);
	}

	/*payment from Billing & Ad-Ons page*/
	public function payment(){

		$post = $this->input->post();

		$user =  $this->session->userdata('user');

		$client = $this->db->get_where('wp_company',array('id'=>$user->company_id),1,0)->row();

		$package = $post['package'];
		$amount = $this->get_total_amount(true);

		$payment_successful = false;

		if(!empty($client->payment_token) && $post['new_payment_info'] == 'no'){
			/*using the existing payment information*/
			/*charging*/
			$cmdDoTxnTransaction  = "<Txn>";
			$cmdDoTxnTransaction .= "<PostUsername>{$this->payment_username}</PostUsername>"; #Insert your Payment Express Username here
			$cmdDoTxnTransaction .= "<PostPassword>{$this->payment_password}</PostPassword>"; #Insert your Payment Express Password here
			$cmdDoTxnTransaction .= "<Amount>".number_format($amount, 2, '.','')."</Amount>";
			$cmdDoTxnTransaction .= "<InputCurrency>NZD</InputCurrency>";
			$cmdDoTxnTransaction .= "<TxnType>Purchase</TxnType>";
			$cmdDoTxnTransaction .= "<MerchantReference>{$client->client_name} Plan-{$client->plan_id}</MerchantReference>";
			$cmdDoTxnTransaction .= "<DpsBillingId>{$client->payment_token}</DpsBillingId>";
			$cmdDoTxnTransaction .= "</Txn>";

			$URL = $this->payment_url;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://".$URL);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$cmdDoTxnTransaction);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //Needs to be included if no *.crt is available to verify SSL certificates

			$result = curl_exec ($ch);
			curl_close ($ch);


			if($this->_parse_xml($result)){

				$payment_successful = true;

			}

		}else{

			$name = $post['CardName'];
			$ccnum = $post['CardNum'];
			$ccmm = $post['ExMnth'];
			$ccyy = $post['ExYear'];

			$result = $this->_process_payment($amount, $name, $ccnum, $ccmm, $ccyy, $client->client_name." Plan-{$package}", true, "Auth");

			if($this->_parse_xml($result,"billingID")){

				$billingId = $this->_parse_xml($result, 'billingID');

				/*completing the transaction*/

				$DPSTxnRef = $this->_parse_xml($result,'DPSTxnRef');

				$cmdDoTxnTransaction  = "<Txn>";

				$cmdDoTxnTransaction .= "<PostUsername>{$this->payment_username}</PostUsername>";

				$cmdDoTxnTransaction .= "<PostPassword>{$this->payment_password}</PostPassword>";

				$cmdDoTxnTransaction .= "<Amount>".number_format($amount, 2, '.','')."</Amount>";

				$cmdDoTxnTransaction .= "<InputCurrency>NZD</InputCurrency>";

				$cmdDoTxnTransaction .= "<DpsTxnRef>{$DPSTxnRef}</DpsTxnRef>";

				$cmdDoTxnTransaction .= "<TxnType>Complete</TxnType>";

				$cmdDoTxnTransaction .= "</Txn>";

				$URL = $this->payment_url;

				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,"https://".$URL);
				curl_setopt($ch, CURLOPT_POST, 1);
				curl_setopt($ch, CURLOPT_POSTFIELDS,$cmdDoTxnTransaction);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //Needs to be included if no *.crt is available to verify SSL certificates

				$result = curl_exec ($ch);
				curl_close ($ch);

				if($this->_parse_xml($result)){

					$payment_successful = true;

					$this->db->where('id', $user->company_id);

					$this->db->update('wp_company',array('payment_token' => $billingId));
				}

			}
		}

		if($payment_successful){

			/*payment successful, now activating the plan and adding the applications */

			$applications = $post['plan_'.$package];

			$update_data = array(
				'plan_id' => $package,
				'last_payment_date' => date("Y-m-d H:i:s"),
				'next_payment_amount' => $amount

			);

			$this->db->update('wp_company',$update_data,array('id' => $user->company_id ));

			/*deleting old applications for this company*/
			$this->db->delete('wp_company_applications', array('company_id' => $user->company_id));

			/*user pricing is a must*/
			$this->db->insert('wp_company_applications',array(
				'company_id' => $user->company_id,
				'application_id' => 0
			));
			/*must have CMS*/
			$this->db->insert('wp_company_applications',array(
				'company_id' => $user->company_id,
				'application_id' => 4
			));
			foreach($applications as $app_id){
				$this->db->insert('wp_company_applications',array(
					'company_id' => $user->company_id,
					'application_id' => $app_id
				));
			}

			/*adding payment history*/
			$this->db->insert('wp_payment_history',array(
				'company_id' => $user->company_id,
				'payment_at' => date("Y-m-d H:i:s"),
				'amount' => $amount
			));


			/*************sending receipt mail**************/
			$tokens = array('#plan_name#', '#login_link#', '#package_table#', '#total#');
			$token_values = array();
			$plan_name = $this->db->get_where('wp_plans',array('id' => $package),1,0)->row()->name;
			$token_values[] = $plan_name;
			$token_values[] = "https://".$client->url;
			/*packages*/
			$applications[] = 0;
			$applications[] = 4;
			$this->db->select("application.application_full_name, wp_company_applications.*, wp_plan_prices.price");
			$this->db->join("application","application.id = wp_company_applications.application_id","left");
			$this->db->join("wp_plan_prices","wp_company_applications.application_id = wp_plan_prices.application_id");
			$this->db->where("wp_company_applications.company_id",$client->id);
			$this->db->where('wp_company_applications.application_id in ('.implode(',',$applications).')');
			$this->db->where('wp_plan_prices.plan_id',$package);
			$this->db->order_by('wp_company_applications.application_id','asc');
			$packages = $this->db->get("wp_company_applications")->result();

			$tbl = "<table style='width: 60%; font-size: 22px'><tr><td style='padding: 5px 0; border-style: solid; border-color: black; border-image: none; border-width: 1px 0px;'>Name</td><td style='padding: 5px 0; border-style: solid; border-color: black; border-image: none; border-width: 1px 0px; text-align: center'>QTY</td><td style='padding: 5px 0; border-style: solid; border-color: black; border-image: none; border-width: 1px 0px; text-align: right'>PAID</td></tr>";
			$total = 0;
			foreach($packages as $pkg){
				$tbl .= "<tr>";
				if($pkg->application_id == 0){
					$tbl .= "<td style='padding: 5px 0'>Basic Plan (Platform Access)</td>";
				}else{
					$tbl .= "<td style='padding: 5px 0'>{$pkg->application_full_name}</td>";
				}
				$tbl .= "<td style='padding: 5px 0;text-align: center'>1</td>";
				if($pkg->price == 0){
					$tbl .= "<td style='padding: 5px 0;text-align: right'>FREE</td>";
				}else{
					$tbl .= "<td style='padding: 5px 0;text-align: right'>NZD {$pkg->price}</td>";
					$total += $pkg->price;

				}
				$tbl .= "</tr>";
			}
			$total = number_format($total,2);
			$tbl .= "<tr><td colspan='3' style='text-align: right'>TOTAL Each Month: NZD {$total}</td></tr>";
			//$tbl .= "<tr><td colspan='3' style='text-align: right'>First Month: FREE<sup>*</sup></td></tr>";
			$tbl .= "</table>";
			$token_values[] = $tbl;
			$token_values[] = $total - 1;
			$token_values[] = $total;

			$this->load->library("WPMailTemplate");
			$mail_body = $this->wpmailtemplate->get_mail_body('payment_receipt_1st_month',$tokens,$token_values);

			$headers = "MIME-Version: 1.0\r\n";
			$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			$headers .= "From: " . "noreply@wclp.co.nz" . "\r\n";
			mail($user->email, "Williams Platform Payment Receipt", $mail_body, $headers);

			$this->output
				    ->set_content_type('application/json')
				    ->set_output(json_encode(array('status' => 'success')));
		}else{

			$this->output
				    ->set_content_type('application/json')
				    ->set_output(json_encode(array('status' => 'error')));
		}

	}

	/*payment page for company admin*/
	public function activation_payment(){

		$user = ($this->session->userdata('user'))?$this->session->userdata('user'):$this->session->userdata('payment_user');

		$company_info = $this->db->get_where('wp_company',array('id' => $user->company_id),1,0)->row();

		$data['company_info'] = $company_info;

		$applications = $this->db->query("SELECT GROUP_CONCAT(application_id) applications FROM wp_company_applications WHERE company_id = {$user->company_id} GROUP BY company_id")->row()->applications;

		/*getting the total amount*/
		$package = $company_info->plan_id;
		$applications = "(0,".$applications.")";
		$this->db->select("SUM(price) price");
		$this->db->where("plan_id = {$package} AND application_id in {$applications}");
		$price = $this->db->get('wp_plan_prices')->row()->price;
		$price = round($price, 2);

		$data['total_price'] = $price;

		$data['plan'] = $this->db->get_where('wp_plans',array('id'=>$package),1,0)->row()->name;

		/*application prices*/
		$this->db->select("application.application_full_name app, wp_plan_prices.price,application.id");
		$this->db->join("application","application.id = wp_plan_prices.application_id","left");
		$this->db->where("plan_id = {$package} AND application_id in {$applications}");

		$data['application_prices'] = $this->db->get('wp_plan_prices')->result();

		/*displaying the payment form*/
		$this->load->view('user/payment',$data);
	}

	private function _parse_xml($data, $return = null)
	{
		$xml_parser = xml_parser_create();
		xml_parse_into_struct($xml_parser, $data, $vals, $index);
		xml_parser_free($xml_parser);

		$params = array();
		$level = array();
		foreach ($vals as $xml_elem) {
			if ($xml_elem['type'] == 'open') {
				if (array_key_exists('attributes',$xml_elem)) {
					list($level[$xml_elem['level']],$extra) = array_values($xml_elem['attributes']);
				}
				else {
					$level[$xml_elem['level']] = $xml_elem['tag'];
				}
			}
			if ($xml_elem['type'] == 'complete') {
				$start_level = 1;
				$php_stmt = '$params';

				while($start_level < $xml_elem['level']) {
					$php_stmt .= '[$level['.$start_level.']]';
					$start_level++;
				}
				$php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
				eval($php_stmt);
			}
		}

		 //Uncommenting this block will display the entire array and show all values returned.
        /*echo "<pre>";
        print_r ($params);
        echo "</pre>";
		exit;*/

		$success = $params[TXN][SUCCESS];

		$MerchantReference = $params[TXN][$success][MERCHANTREFERENCE];
		$CardHolderName = $params[TXN][$success][CARDHOLDERNAME];
		$AuthCode = $params[TXN][$success][AUTHCODE];
		$Amount = $params[TXN][$success][AMOUNT];
		$CurrencyName = $params[TXN][$success][CURRENCYNAME];
		$TxnType = $params[TXN][$success][TXNTYPE];
		$CardNumber = $params[TXN][$success][CARDNUMBER];
		$DateExpiry = $params[TXN][$success][DATEEXPIRY];
		$CardHolderResponseText = $params[TXN][$success][CARDHOLDERRESPONSETEXT];
		$CardHolderResponseDescription = $params[TXN][$success][CARDHOLDERRESPONSEDESCRIPTION];
		$MerchantResponseText = $params[TXN][$success][MERCHANTRESPONSETEXT];
		$DPSTxnRef = $params[TXN][$success][DPSTXNREF];
		$DPSBILLINGID = $params[TXN][$success][DPSBILLINGID];

		//return $success;
		if($return == 'billingID') {
			return $DPSBILLINGID;
		}
		if($return == 'DPSTxnRef'){
			return $DPSTxnRef;
		}
		return $success;
	}

	/*will come to this page after selecting the plan in main site*/
	public function register($page = 'company-info'){

		if(!$this->input->post() && !$this->session->userdata('company_info')){
			die("Not a valid request.");
		}
		if($page == 'company-info'){

			/*the user may come from clicking the back button to this page.
			in this case he has filled up company information before and we have to show them in the form.*/
			$data['company_info'] = ($this->session->userdata('company_info'))?$this->session->userdata('company_info'):array();

			if($this->input->post()){
				/*user came from williams platform directly*/

				$post = $this->input->post();
				/*getting the selected plan*/

				$package = $post['package'];

				$applications = ($post['plan_'.$package]) ? $post['plan_'.$package] : array();

				$this->session->set_userdata('plan',array('package' => $package, 'applications' => $applications));

			}
			/*displaying the registration form*/
			$this->load->view('user/register',$data);

		}

		if($page == 'submit'){

			/*the user submitted company info*/
			
			//$data['company_url'] = $this->input->post('company_url').".wclp.co.nz";
			//print_r($data); exit;
			
			$this->form_validation->set_rules('company_name', 'Company Name', 'required');
			//$this->form_validation->set_rules('person_in_charge', 'Person in Charge', 'required');
			$this->form_validation->set_rules('phone_number', 'Phone Number', 'required');
			$this->form_validation->set_rules('country', 'Country', 'required');
			//$this->form_validation->set_rules('address', 'Address', 'required');
			$this->form_validation->set_rules('password', 'Password', 'required|matches[re-password]');
			$this->form_validation->set_rules('re-password', 'Re Type Password', 'required');
			$this->form_validation->set_rules('company_url', 'URL', 'required|callback_url_check|is_unique[wp_company.url]');
			$this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]');
			$this->form_validation->set_rules('username', 'Admin Username', 'required|is_unique[users.username]');
			$this->form_validation->set_message('is_unique', '%s is already registered');

			if ($this->form_validation->run() == FALSE)
			{
				/*displaying the registration form again*/
				$data['company_info'] = $this->session->userdata('company_info');
				$this->load->view('user/register',$data);

			}else{
				/*saving the company info*/
				$info = $this->input->post();

				$plan = $this->session->userdata('plan');
				$package = $plan['package'];
				$applications = $plan['applications'];
				array_push($applications,0); //must have user pricing
				array_push($applications,4); //must have CMS
				$apps = "(".implode(',',$applications).")";
				$this->db->select("SUM(price) price");
				$this->db->where("plan_id = {$package} AND application_id in {$apps}");
				$price = $this->db->get('wp_plan_prices')->row()->price;
				$amount = round($price, 2);

				$company_url = $info['company_url'].".wclp.co.nz";
				/*creating the company*/
				$company_add = array(
					'client_name' => $info['company_name'],
					'url' => $company_url,
					'person_in_charge' => $info['person_in_charge'],
					'phone_number' => $info['phone_number'],
					'country' => $info['country'],
					'address' => $info['address'],
					'plan_id' => $package,
					'payment_token' => '',
					'next_payment_date' => date('Y-m-d',strtotime("+30 day")),
					//'next_payment_amount' => $amount-1
					'next_payment_amount' => $amount //removed the $1 authentication
				);
				$company_id = $this->client_model->company_add($company_add);

				$client_add = array(
					'company_id' => $company_id,
					'email' => $info['email'],
					'username' => $info['username'],
					'password' => MD5($info['password']),
					'role' => 1,
					'status' => 1,
					'created' => date("Y-m-d,h:m:s")
				);
				$client_id = $this->client_model->client_add($client_add);

				$plan_name = $this->db->get_where('wp_plans',array('id' => $package),1,0)->row()->name;

				$this->_create_default_templates_for_rs($company_id);

				/*sending mail to office@williamsbusiness.co.nz. task #4359*/
				$h = "MIME-Version: 1.0\r\n";
				$h .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$h .= "From: " . "noreply@wclp.co.nz" . "\r\n";

				$m_body = "Hi, <br>";
				$m_body .= "A new company has been created";
				$m_body .= "<table>";
				$m_body .= "<tr><td>Company Name:</td><td>{$info['company_name']}</td></tr>";
				$m_body .= "<tr><td>URL:</td><td>{$info['company_url']}</td></tr>";
				$m_body .= "<tr><td>Email:</td><td>{$info['email']}</td></tr>";
				$m_body .= "<tr><td>Country:</td><td>{$info['country']}</td></tr>";
				$m_body .= "<tr><td>Phone No.:</td><td>{$info['phone_number']}</td></tr>";
				$m_body .= "<tr><td>Address:</td><td>{$info['address']}</td></tr>";
				$m_body .= "<tr><td>Plan:</td><td>{$plan_name}</td></tr>";

				mail("office@williamsbusiness.co.nz", "Williams Platform: New Company", $m_body, $h);

				$company_apps = array();
				$client_apps = array();
				foreach($applications as $app_id){
					/*adding applications to the company*/
					$company_apps[] = array(
						'company_id' => $company_id,
						'application_id' => $app_id
					);
					/*adding applicatins to the admin*/
					$client_apps[] = array(
						'company_id' => $company_id,
						'application_id' => $app_id,
						'user_id' => $client_id,
						'application_role_id' => 1
					);

				}
				$this->db->insert_batch('wp_company_applications',$company_apps);
				$this->db->insert_batch('users_application',$client_apps);

				/*task #4484*/
				$this->_send_thank_you_mail($company_id, $info['username'], $info['email']);

				$this->session->set_userdata('successful_payment',1);

				redirect('https://'.$info['company_url'].".wclp.co.nz");

			}

		}

	}

	public function url_check($str)
	{
		$pattern = "/^[0-9a-zA-Z-]+$/";

		if (preg_match($pattern, $str) !== 1)
		{
			$this->form_validation->set_message('url_check', 'The URL should be in <u><b>your-domain.wclp.co.nz</b></u> format.');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}

	/*submit payment by user*/


public function payment_submit(){

		$post = $this->input->post();

		$user = ($this->session->userdata('user'))?$this->session->userdata('user'):$this->session->userdata('payment_user');

		$company_info = $this->db->get_where('wp_company',array('id' => $user->company_id),1,0)->row();

		#$applications = $this->db->query("SELECT GROUP_CONCAT(application_id) applications FROM wp_company_applications WHERE company_id = {$user->company_id} GROUP BY company_id")->row()->applications;


		$applications = implode(', ',$post['plan']);
		

		
		/*getting the total amount*/
		$package = $company_info->plan_id;
		$applications = "(0, 4, ".$applications.")";
		$this->db->select("SUM(price) price");
		$this->db->where("plan_id = {$package} AND application_id in {$applications}");
		$price = $this->input->post('total_price');
		$amount = round($price, 2);
		$name = $post['CardName'];
		$ccnum = $post['CardNum'];
		$ccmm = $post['ExMnth'];
		$ccyy = $post['ExYear'];

		$result = $this->_process_payment($amount,$name,$ccnum,$ccmm,$ccyy,$company_info->client_name." Plan-{$package}",true,"Auth");

		if($this->_parse_xml($result, 'billingID')){

			$billingId = $this->_parse_xml($result, 'billingID');

			/*completing the transaction*/

			$DPSTxnRef = $this->_parse_xml($result,'DPSTxnRef');

			$cmdDoTxnTransaction  = "<Txn>";

			$cmdDoTxnTransaction .= "<PostUsername>{$this->payment_username}</PostUsername>";

			$cmdDoTxnTransaction .= "<PostPassword>{$this->payment_password}</PostPassword>";

			$cmdDoTxnTransaction .= "<Amount>".number_format($amount, 2, '.','')."</Amount>";

			$cmdDoTxnTransaction .= "<InputCurrency>NZD</InputCurrency>";

			$cmdDoTxnTransaction .= "<DpsTxnRef>{$DPSTxnRef}</DpsTxnRef>";

			$cmdDoTxnTransaction .= "<TxnType>Complete</TxnType>";

			$cmdDoTxnTransaction .= "</Txn>";

			$URL = $this->payment_url;

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL,"https://".$URL);
			curl_setopt($ch, CURLOPT_POST, 1);
			curl_setopt($ch, CURLOPT_POSTFIELDS,$cmdDoTxnTransaction);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //Needs to be included if no *.crt is available to verify SSL certificates

			$result = curl_exec ($ch);
			curl_close ($ch);

			if($this->_parse_xml($result)){

				$this->db->where('id',$company_info->id);

				$this->db->update('wp_company',array(
					'payment_token' => $billingId,
					'next_payment_date' => date('Y-m-d',strtotime("+30 day")),
					'last_payment_date' => date('Y-m-d'),
					'next_payment_amount' => $amount, //removed the $1 authentication
					'is_active' => 1
				));

				/*adding payment history*/
				$this->db->insert('wp_payment_history',array(
					'company_id' => $company_info->id,
					'payment_at' => date("Y-m-d H:i:s"),
					'amount' => $amount
				));


				/*************sending receipt mail**************/
				$tokens = array('#plan_name#', '#login_link#', '#package_table#', '#total#');
				$token_values = array();
				$plan_name = $this->db->get_where('wp_plans',array('id' => $package),1,0)->row()->name;
				$token_values[] = $plan_name;
				$token_values[] = "https://".$company_info->url;
				/*packages*/
				$this->db->select("application.application_full_name, wp_company_applications.*, wp_plan_prices.price");
				$this->db->join("application","application.id = wp_company_applications.application_id","left");
				$this->db->join("wp_plan_prices","wp_company_applications.application_id = wp_plan_prices.application_id");
				$this->db->where("wp_company_applications.company_id",$company_info->id);
				$this->db->where('wp_company_applications.application_id in '.$applications);
				$this->db->where('wp_plan_prices.plan_id',$package);
				$this->db->order_by('wp_company_applications.application_id','asc');
				$packages = $this->db->get("wp_company_applications")->result();

				$tbl = "<table style='width: 60%; font-size: 22px'><tr><td style='padding: 5px 0; border-style: solid; border-color: black; border-image: none; border-width: 1px 0px;'>Name</td><td style='padding: 5px 0; border-style: solid; border-color: black; border-image: none; border-width: 1px 0px; text-align: center'>QTY</td><td style='padding: 5px 0; border-style: solid; border-color: black; border-image: none; border-width: 1px 0px; text-align: right'>PAID</td></tr>";
				$total = 0;
				foreach($packages as $pkg){
					$tbl .= "<tr>";
					if($pkg->application_id == 0){
						$tbl .= "<td style='padding: 5px 0'>Basic Plan (Platform Access)</td>";
					}else{
						$tbl .= "<td style='padding: 5px 0'>{$pkg->application_full_name}</td>";
					}
					$tbl .= "<td style='padding: 5px 0;text-align: center'>1</td>";
					if($pkg->price == 0){
						$tbl .= "<td style='padding: 5px 0;text-align: right'>FREE</td>";
					}else{
						$tbl .= "<td style='padding: 5px 0;text-align: right'>NZD {$pkg->price}</td>";
						$total += $pkg->price;

					}
					$tbl .= "</tr>";
				}
				$total = number_format($total,2);
				$tbl .= "<tr><td colspan='3' style='text-align: right'>TOTAL Each Month: NZD {$total}</td></tr>";
				//$tbl .= "<tr><td colspan='3' style='text-align: right'>First Month: FREE<sup>*</sup></td></tr>";
				$tbl .= "</table>";
				$token_values[] = $tbl;
				//$token_values[] = $total - 1;
				$token_values[] = $total;

				$this->load->library("WPMailTemplate");
				$mail_body = $this->wpmailtemplate->get_mail_body('payment_receipt_1st_month',$tokens,$token_values);

				$headers = "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
				$headers .= "From: " . "noreply@wclp.co.nz" . "\r\n";
				mail($user->email, "Williams Platform Payment Receipt", $mail_body, $headers);

				$this->session->set_userdata('successful_payment',1);
				$this->db->delete('wp_company_applications',array('company_id' => $company_info->id));
				$paid_apps = explode(', ', $applications);
				foreach ($paid_apps as $app) {
				
					$data_app = array(
							'company_id' => $company_info->id,
							'application_id' => $app
						);
					$this->db->insert('wp_company_applications',$data_app);
				}

				$data_app = array(
						'company_id' => $company_info->id,
						'application_id' => 0
					);
				$this->db->insert('wp_company_applications',$data_app);

				$data_app = array(
						'company_id' => $company_info->id,
						'application_id' => 4
					);
				$this->db->insert('wp_company_applications',$data_app);


				redirect('https://'.$company_info->url);

			}

		}
		$this->session->set_flashdata('warning-message', 'Payment failed.');
		$data['total_price'] = $amount;

		/*redirecting to the payment form*/
		//$this->load->view('user/payment',$data);
		redirect(site_url('user/activation_payment'));
	}
	
	/*user update payment info*/
	public function payment_info_update(){

		$user = $this->session->userdata('user');

		if($user->role != 1) die("access denied");

		$client = $this->db->get_where('wp_company',array('id'=>$user->company_id),1,0)->row();

		$post = $this->input->post();

		if($post){

			$name = $post['CardName'];
			$ccnum = $post['CardNum'];
			$ccmm = $post['ExMnth'];
			$ccyy = $post['ExYear'];

			$result = $this->_process_payment(1,$name,$ccnum,$ccmm,$ccyy,$client->client_name." Payment info Update.",true,"Auth");

			if($this->_parse_xml($result, 'billingID')){

				$token = $this->_parse_xml($result, 'billingID');

				$this->db->where('id', $user->company_id);

				$this->db->update('wp_company',array('payment_token' => $token));

				$this->session->set_flashdata('success-message', 'Payment information updated.');

				//redirect("https://".$_SERVER['HTTP_HOST']."/client/profile");
				redirect(site_url('client/profile'));

			}else{

				$this->session->set_flashdata('warning-message', 'Error updating payment information.');

			}

		}

		$data['maincontent'] = $this->load->view('user/payment_info_update',array(),true);
		$this->load->view('includes/header');
		$this->load->view('home',$data);
		$this->load->view('includes/footer');
	}

	/*user remove payment info*/
	public  function payment_info_remove(){

		$user = $this->session->userdata('user');

		if($user->role != 1) die("access denied");

		if($this->input->method() == 'post'){
			$this->db->where('id',$user->company_id);
			$this->db->update('wp_company',array('payment_token'=>''));

			$this->session->set_flashdata('success-message', 'Payment information removed.');

			redirect(site_url('client/profile'));
		}

	}

	/*if company is deactivated show the payment page*/


	private function _process_payment($amount, $name, $ccnum, $ccmm, $ccyy, $merchant_reference, $addBilling = false, $txnType = 'Purchase'){

		/*processing the request*/
		$cmdDoTxnTransaction  = "<Txn>";

		$cmdDoTxnTransaction .= "<PostUsername>{$this->payment_username}</PostUsername>";

		$cmdDoTxnTransaction .= "<PostPassword>{$this->payment_password}</PostPassword>";

		$cmdDoTxnTransaction .= "<Amount>".number_format($amount, 2, '.','')."</Amount>";

		$cmdDoTxnTransaction .= "<InputCurrency>NZD</InputCurrency>";
		$cmdDoTxnTransaction .= "<CardHolderName>$name</CardHolderName>";
		$cmdDoTxnTransaction .= "<CardNumber>$ccnum</CardNumber>";
		$cmdDoTxnTransaction .= "<DateExpiry>$ccmm$ccyy</DateExpiry>";
		$cmdDoTxnTransaction .= "<TxnType>{$txnType}</TxnType>";

		if($addBilling){
			$cmdDoTxnTransaction .= "<EnableAddBillCard>1</EnableAddBillCard>";
		}
		$cmdDoTxnTransaction .= "<MerchantReference>$merchant_reference</MerchantReference>";
		$cmdDoTxnTransaction .= "</Txn>";

		$URL = $this->payment_url;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,"https://".$URL);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,$cmdDoTxnTransaction);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0); //Needs to be included if no *.crt is available to verify SSL certificates

		$result = curl_exec ($ch);
		curl_close ($ch);
		
		return $result;
		
	} 

	private function _create_default_templates_for_rs($company_id){

		/*Monthly Financial Report*/
		$data = array(
			'name' => "Monthly Financial Report" ,
			'wp_company_id' => $company_id,
			'created'=>date("Y-m-d H:i:s")
		);

		$this->db->insert('rs_forms', $data);

		$form_id = $this->db->insert_id();

		$data = array();

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 1,
			'order' => 0,
			'type' => 'text',
			'title' => 'Periods of Report',
			'select_options' =>  null,
			'required' =>  0
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 1,
			'order' => 1,
			'type' => 'text',
			'title' => 'Months Profit or Loss',
			'select_options' =>  null,
			'required' =>  0
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 1,
			'order' => 2,
			'type' => 'text',
			'title' => 'Months Closing Equity',
			'select_options' =>  null,
			'required' =>  0
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 1,
			'order' => 3,
			'type' => 'text',
			'title' => 'Comments about Actual vs Budget',
			'select_options' =>  null,
			'required' =>  0
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 1,
			'order' => 4,
			'type' => 'text',
			'title' => 'Forcasts or Changes for Next Month',
			'select_options' =>  null,
			'required' =>  0
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 2,
			'order' => 0,
			'type' => 'document',
			'title' => 'P&L Month Actual vs Budget',
			'select_options' =>  null,
			'required' =>  0
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 2,
			'order' => 1,
			'type' => 'document',
			'title' => 'Current Balance Sheet for Month',
			'select_options' =>  null,
			'required' =>  0
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 2,
			'order' => 2,
			'type' => 'document',
			'title' => 'Years Budget',
			'select_options' =>  null,
			'required' =>  0
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 2,
			'order' => 3,
			'type' => 'document',
			'title' => 'P&L Previous 12 Month',
			'select_options' =>  null,
			'required' =>  0
		);

		$this->db->insert_batch('rs_form_fields', $data);

		/*Weekly Marketing Report*/
		$data = array(
			'name' => "Weekly Marketing Report" ,
			'wp_company_id' => $company_id,
			'created'=>date("Y-m-d H:i:s")
		);

		$this->db->insert('rs_forms', $data);

		$form_id = $this->db->insert_id();

		$data = array();

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 1,
			'order' => 0,
			'type' => 'text',
			'title' => 'How many Facebook posts have been completed since your last report?',
			'select_options' =>  null,
			'required' =>  1
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 1,
			'order' => 1,
			'type' => 'text',
			'title' => 'Bullet point what the Facebook posts were about',
			'select_options' =>  null,
			'required' =>  1
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 2,
			'order' => 0,
			'type' => 'text',
			'title' => 'Has any other marketing material been created?',
			'select_options' =>  null,
			'required' =>  1
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 2,
			'order' => 1,
			'type' => 'text',
			'title' => 'Marketing comments',
			'select_options' =>  null,
			'required' =>  0
		);

		$this->db->insert_batch('rs_form_fields', $data);

		/*Daily Report*/
		$data = array(
			'name' => "Daily Report" ,
			'wp_company_id' => $company_id,
			'created'=>date("Y-m-d H:i:s")
		);

		$this->db->insert('rs_forms', $data);

		$form_id = $this->db->insert_id();

		$data = array();

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 1,
			'order' => 0,
			'type' => 'text',
			'title' => 'From 1 - 10, rate your day',
			'select_options' =>  null,
			'required' =>  1
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 1,
			'order' => 1,
			'type' => 'radio-group-yes-no-na',
			'title' => 'Were your key things from last report accomplished',
			'select_options' =>  null,
			'required' =>  1
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 1,
			'order' => 2,
			'type' => 'text',
			'title' => 'Three key things accomplished today',
			'select_options' =>  null,
			'required' =>  1
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 1,
			'order' => 3,
			'type' => 'text',
			'title' => 'Comments',
			'select_options' =>  null,
			'required' =>  0
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 2,
			'order' => 0,
			'type' => 'text',
			'title' => 'Three key things to complete next working day',
			'select_options' =>  null,
			'required' =>  1
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 2,
			'order' => 1,
			'type' => 'numbers',
			'title' => 'Number of unread emails',
			'select_options' =>  null,
			'required' =>  0
		);

		$data[] =  array(
			'form_id' => $form_id,
			'column' => 2,
			'order' => 2,
			'type' => 'text',
			'title' => 'Anything required from directors',
			'select_options' =>  null,
			'required' =>  0
		);

		$this->db->insert_batch('rs_form_fields', $data);


	}

	/*this will be sent on register after 30 days trial. task #4484*/
	private function _send_thank_you_mail($company_id, $admin_name, $admin_email){

		$client = $this->db->get_where('wp_company',array('id'=>$company_id),1,0)->row();

		/*is the payment after the 30 days trial period?*/
		/*$this->db->where(array('company_id'=>$user->company_id));
		$this->db->from('wp_payment_history');
		if($this->db->count_all_results() != 1) return;*/

		$this->load->library("WPMailTemplate");
		$tokens = array("#name#","#applications#", "#url#", "#admin#");
		$name = ($client->person_in_charge) ? $client->person_in_charge : $admin_name;
		$token_values = array($name);

		/*applications*/
		$sql = "SELECT GROUP_CONCAT(application_name  SEPARATOR ', ') apps FROM wp_company_applications
				JOIN application ON application.id = wp_company_applications.application_id
				JOIN wp_company ON wp_company_applications.company_id = wp_company.id
				WHERE wp_company.id = {$client->id}
				GROUP BY wp_company.id";

		$apps = $this->db->query($sql)->row()->apps;

		$token_values[] = $apps;
		$token_values[] = 'https://'.$client->url;
		$token_values[] = $admin_name;

		$mail_body = $this->wpmailtemplate->get_mail_body('on_register_thank_you_mail',$tokens,$token_values);

		$headers = "MIME-Version: 1.0\r\n";
		$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		$headers .= "From: " . "Williams Platform <noreply@wclp.co.nz>" . "\r\n";
		mail($admin_email, "Thank you for Joining Williams Platform", $mail_body, $headers);
	}

	public function request_google_api_token(){

		$this->load->library('google');

		//$this->google->setAuthConfigFile(APPPATH.'/libraries/third_party/client_id.json');
		//$this->google->addScope(Google_Service_Drive::DRIVE_METADATA_READONLY);
		$this->google->addScope("https://www.googleapis.com/auth/calendar");
		$this->google->setAccessType("offline");
		$this->google->setRedirectUri('https://wclp.co.nz/user/request_google_api_token');		/*getting client info*/

		if($_GET['code'] && $_COOKIE['gCal']){
			$this->google->authenticate($_GET['code']);
			$access_token = $this->google->getAccessToken();
			$this->db->where('uid',$_COOKIE['gCal']);
			$this->db->where('google_calendar_token','requested');
			$this->db->update('users',array('google_calendar_token'=>$access_token));

			$this->db->select('url');
			$this->db->where('uid',$_COOKIE['gCal']);
			$this->db->join('wp_company','wp_company.id = users.company_id');
			$url = $this->db->get('users',1,0)->row()->url;
			setcookie("gCal", "", time()-3600,'/','wclp.co.nz');
			redirect("https://{$url}/user/user_password_update");

		}elseif($_COOKIE['gCal']){
			$this->db->select('url');
			$this->db->where('uid',$_COOKIE['gCal']);
			$this->db->join('wp_company','wp_company.id = users.company_id');
			$url = $this->db->get('users',1,0)->row()->url;
			setcookie("gCal", "", time()-3600,'/','wclp.co.nz');
			redirect("https://{$url}/user/user_password_update");
		}else{
			$user = $this->session->userdata('user');
			$this->db->where('uid',$user->uid);
			$this->db->update('users',array('google_calendar_token'=>'requested'));
			/*setting cookie*/
			setcookie('gCal',$user->uid,0,'/','wclp.co.nz');

			$auth_url = $this->google->createAuthUrl();

			header('Location: ' . filter_var($auth_url, FILTER_SANITIZE_URL));
		}
	}

	public function revoke_google_api_token(){
		$this->load->library('google');
		$user = $this->session->userdata('user');
		$access_token = $this->google->revokeToken();
		$this->db->where('uid',$user->uid);
		$this->db->update('users',array('google_calendar_token'=>$access_token));
		setcookie("gCal", "", time()-3600,'/','wclp.co.nz');

		redirect("user/user_password_update");
		
	
	}

	public function get_username(){

		if($this->session->userdata('user')) {
			$user =  $this->session->userdata('user');
			echo $user->username; exit;
		}

	}

	public function calc_total(){

		$total = $this->input->post('subtotal');


		echo $total;
		
	}
	
}