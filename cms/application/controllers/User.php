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
		$this->ums = $this->load->database('ums', TRUE);
    }
	
	public function index()
	{	
		$data=array();
        if($this->session->userdata('user')){
			$user = $this->session->userdata('user');
			$uid = $user->uid;
        	redirect("welcome");				
		} 
		else{
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
            //$data['first_name']=$result->name;
            
            $sesData['user']=$result;
            $this->session->set_userdata($sesData);
		
			redirect("welcome");					      	    
        } 
		else 
		{
			//$data['title']='Login';
            //$sesData['user_password_error']='Please enter the correct username and password';
            //$this->session->set_userdata($sesData);
            //$this->load->view('login',$data);
			redirect("https://horncastledevelopments.co.nz/user");
        }



		/* if($user_name_result)
		{
        	$result=$this->user_model->user_login($username,$password);
        	if($result) 
			{
            	$sesData['user']=$result;
            	$this->session->set_userdata($sesData);
			
				redirect("welcome");					      	    
        	} 
			else 
			{
				$data['title']='Login';
            	$sesData['password_error']='Password Invalid!';
            	$this->session->set_userdata($sesData);
            	$this->load->view('login',$data);
        	}
		}
		else
		{
				$data['title']='Login';
				$sesData['username_error']='Username not found!';
				$sesData['password_error']='Password Invalid!';
				$this->session->set_userdata($sesData);
				$this->load->view('login',$data);
		} */




    }
	 
    public function user_logout($cids='')
    {
		$user=  $this->session->userdata('user');
		$user_id = $user->uid; 
		$tab_ids = $cids;
		$this->user_model->insert_tab_ids($tab_ids,$user_id);

        $this->session->unset_userdata('user');
        redirect("http://".$_SERVER['SERVER_NAME']);
    }
	
 	public function user_add() 
	{
            
		if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
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
				'group_id' => $post['group_id'],
				'status' => 1,
				'created' => time()
			);
			
			$id = $this->user_model->user_save($user_add);
			$data['message'] = 'User has been saved successfully';
			redirect('user/user_list/success');  
	    }
		
		$data['maincontent'] = $this->load->view('user_add',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
			
	}
	
	public function user_role_add() 
	{ 
		$data['title'] = 'User Role add';		
	    $data['action'] = site_url('user/user_role_add');	
		if ( $this->input->post('submit')) 
		{
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
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
			
	}
	
	public function user_list() 
	{
            
		//if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
		
		if ($this->uri->segment(3)=='user_delete_success')
		$data['message'] = 'User has deleted successfully';
		else if ($this->uri->segment(3)=='user_update_success')
		$data['message'] = 'User has updated successfully';
		else if ($this->uri->segment(3)=='user_role_delete_success')
		$data['message'] = 'User role has deleted successfully';
		else if ($this->uri->segment(3)=='user_role_update_success')
		$data['message'] = 'User role has updated successfully';
		else if ($this->uri->segment(3) == 'success')
		$data['message'] = 'User has created successfully';
		else
		$data['message'] = '';
		
		$data['title'] = 'User list';
		$data['action'] = site_url('user/user_list');			    	  
		
		$user_results = $this->user_model->user_list()->result();
		
		$this->load->library('table');
		$this->table->set_empty("");
		
		$tmpl = '<table id="cms_user_table" border="1" cellpadding="4" cellspacing="4" style="border-radius:10px;" class="cmstable">';

		$i=1;
		//$this->table->set_heading('ID','Full Name','Email Address','Permission Group','Edit');

		$this->table->set_heading(array('data'=>'ID', 'style'=>'width:50px'), array('data'=>'Full Name', 'style'=>'width:150px;'), array('data'=>'User Login', 'style'=>'width:150px;'), array('data'=>'Email Address', 'style'=>'width:150px'), array('data'=>'Permission Group', 'style'=>'width:250px;'), array('data'=>'Edit', 'style'=>'width:50px;') );

		foreach ($user_results as $user_result)
		{
			
			$tmpl .= "<tr id='check_".$user_result->uid."' onclick='selectrow(".$user_result->uid.",this.className)'><td>".$user_result->uid."</td><td class='uname'>".$user_result->fullname."</td><td class='uname'>".$user_result->name."</td><td>".$user_result->email."</td><td>".$user_result->group_name."</td><td style='text-align:center'><a href='#UpdateUser_".$user_result->uid."' data-toggle ='modal'><img src='".base_url()."images/icon/icon_edit.png' /></a></td>";
			
			$action = base_url().'user/user_update/'.$user_result->uid;
   
			$form_attributes = array('class' => 'user-edit-form', 'name'=>'edit_user', 'id' => 'entry-form','method'=>'post', 'onsubmit' => 'return check_edit_form('.$user_result->uid.')');
			
			$tmpl .= '<div id="UpdateUser_'.$user_result->uid.'" class="modal hide fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-header"><button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button><h3 id="myModalLabel">Edit User</h3></div><div class="modal-body">';
	
			
			$uid = form_hidden('uid', $user_result->uid);

			$fullname = form_label('Full Name', 'fullname');
			$fullname .= form_input(array(
	              'name'        => 'fullname',
	              'id'          => 'edit-fullname',
	              'value'       => isset($user_result->fullname) ? $user_result->fullname : '',
	              'class'       => 'form-text',
                  'required'    => TRUE
			));
	
			$name = form_label('User Login', 'name');
			$name .= form_input(array(
						  'name'        => 'name',
						  'id'          => $user_result->uid,
						  'value'       => $user_result->name,
						  'class'       => 'form-text',
						  'onblur' 		=> "check_username(this.value,this.id);",
						  'required'    => TRUE
			));
	
			$email = form_label('User Email', 'email');
			$email .= form_input(array(
						  'name'        => 'email',
						  'id'          => 'user-email',
						  'value'       => $user_result->email,
						  'class'       => 'form-text',
						  'required'    => TRUE

			));
	
			$ci = & get_instance();
			$ci->load->model('user_model');
			$user_options = $ci->user_model->user_group_load();

			$user_default = $user_result->group_id;
			$permission_group = form_label('Permissions Group', 'group_id');
			$permission_group .= form_dropdown('group_id', $user_options, $user_default);
	
	
			$pass = form_label('User Password', 'pass');
			$pass .= form_password(array(
						  'name'        => 'pass',
						  'id'          => 'password_'.$user_result->uid,
						  'value'       => '',
						  'class'       => 'form-text',
					  	  'autocomplete'	=> 'off',
						  'disabled'    => TRUE

			));
	
			$retype_pass = form_label('Retype Password', 'repass');
			$retype_pass .= form_password(array(
						  'name'        => 'repass',
						  'id'          => 'retype_password_'.$user_result->uid,
						  'value'       => '******',
						  'class'       => 'form-text',
					  'autocomplete'	=> 'off'

			));
	
			$submit = form_label('', 'submit');
				$submit .= form_submit(array(
						  'name'        => 'submit',
						  'id'          => 'save_user',
						  'value'       => '',
						  'class'       => 'form-submit cms_save',
						  'type'        => 'submit',
						  
			));

			$tmpl .= form_open($action, $form_attributes);
			$tmpl .= '<div id="uid-wrapper" class="field-wrapper">'. $uid . '</div>';
			$tmpl .= '<div id="name-wrapper" class="field-wrapper">'. $fullname . '</div>';
			$tmpl .= '<div id="name-wrapper" class="field-wrapper">'. $name . '<div id="username_alert' . $user_result->uid . '"></div></div>';
			$tmpl .= '<div id="email-wrapper" class="field-wrapper">'. $email . '<div id="email_alert"></div></div>';
			$tmpl .= '<div id="access-wrapper" class="field-wrapper">'. $permission_group . '</div>';
			$tmpl .= '<div id="pass-wrapper" class="field-wrapper">'. $pass . '<img onclick="enable_password('.$user_result->uid.')" src="'.base_url().'images/icon/edit_pass.png" /></div>';
			//$tmpl .= '<div id="re-pass-wrapper_'.$user_result->uid.'" class="field-wrapper" style="display:none">'. $retype_pass . '</div>';
			
			$tmpl .= '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
			$tmpl .= form_fieldset_close();
			$tmpl .= form_close();

			$tmpl .='</div>';
			$tmpl .='</div></tr>';
		
			$i++;
		}
		
		$tmp =  array ( 'table_open'  => $tmpl ) ;
		$this->table->set_template($tmp); 
                
		$data['user_table'] = $this->table->generate();
		$data['maincontent'] = $this->load->view('user/user_list',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
			
	}
	
	public function user_role_list() 
	{
            
		//if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
		
		if ($this->uri->segment(3)=='user_delete_success')
		$data['message'] = 'User was deleted successfully';
		else if ($this->uri->segment(3)=='user_update_success')
		$data['message'] = 'User has been update successfully';
		else if ($this->uri->segment(3)=='user_role_delete_success')
		$data['message'] = 'User role was deleted successfully';
		else if ($this->uri->segment(3)=='user_role_update_success')
		$data['message'] = 'User role has been update successfully';
		else if ($this->uri->segment(3)=='group_add_success')
		$data['message'] = 'User permission group has added successfully';
		else if ($this->uri->segment(3)=='group_delete_success')
		$data['message'] = 'Permission group has deleted successfully';
		else
		$data['message'] = '';

		$get = $_GET;		
				    	  
		$data['role_title'] = 'Group list';

		$data['group_infos'] = $this->user_model->user_group_list()->result();
		
		$user_role_results = $this->user_model->user_role_list()->result();
		
		$this->load->library('table');
		$this->table->set_empty("");
		
		$this->table->set_heading(
		'User Role Name',
		'Role Description'
		);
		foreach ($user_role_results as $user_result){
			
			$this->table->add_row(
			$user_result->rname,
			$user_result->rdesc
			);
		}
		
		$data['user_role_table'] = $this->table->generate();
		
		$data['maincontent'] = $this->load->view('user/user_role_list',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
			
	}


	public function user_category_list() 
	{
            
		//if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
		
		if ($this->uri->segment(3)=='user_delete_success')
		$data['message'] = 'User was deleted successfully';
		else if ($this->uri->segment(3)=='user_update_success')
		$data['message'] = 'User has been update successfully';
		else if ($this->uri->segment(3)=='user_role_delete_success')
		$data['message'] = 'User role was deleted successfully';
		else if ($this->uri->segment(3)=='user_role_update_success')
		$data['message'] = 'User role has been update successfully';
		else if ($this->uri->segment(3)=='group_add_success')
		$data['message'] = 'User permission group has added successfully';
		else if ($this->uri->segment(3)=='group_delete_success')
		$data['message'] = 'Permission group has deleted successfully';
		else
		$data['message'] = '';

		$get = $_GET;		
				    	  
		$data['role_title'] = 'Group list';

		$data['category_info'] = $this->user_model->user_category_list()->result();
		
		$user_role_results = $this->user_model->user_role_list()->result();
		
		$this->load->library('table');
		$this->table->set_empty("");
		
		$this->table->set_heading(
		'User Role Name',
		'Role Description'
		);
		foreach ($user_role_results as $user_result){
			
			$this->table->add_row(
			$user_result->rname,
			$user_result->rdesc
			);
		}
		
		$data['user_role_table'] = $this->table->generate();
		
		$data['maincontent'] = $this->load->view('user/user_category_list',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
			
	}
	
	public function user_update($uid) 
	{
            	
		$user=  $this->session->userdata('user');
		$user_role_id =$user->rid; 
		$user_id =$user->uid; 

		/*if($user_id!=1)
		{
			if($user_id != $uid)
			{                   
				redirect ('permission/access_denied');
			}
		} */ 
                
                
		$data['title'] = 'User update';
		$data['action'] = site_url('user/user_update/'.$uid);
        
		
                    
		$this->_set_edit_rules();		
		if ( $this->form_validation->run() === FALSE ) 
		{	
			$data['user'] = $this->user_model->user_uid($uid)->row();		
		}else 
		{			
		
			$post = $this->input->post(); 
			
			$user=  $this->session->userdata('user');
			$user_role_id =$user->rid; 
			if(isset($post['pass']))
			{
				$pass = $post['pass'];
			}
			else
			{
				$pass = '';
			}
			if($user_role_id==1)
			{

				if($pass == '')
				{
					$user_update = array(
						'fullname' => $post['fullname'],
						'name' => $post['name'],
						'email' => $post['email'],
						'group_id'=> $post['group_id'],
						'updated' => time(),
				   );
				}	
				else
				{
					$user_update = array(
						'fullname' => $post['fullname'],
						'name' => $post['name'],
						'pass' => MD5($post['pass']),
						'email' => $post['email'],
						'group_id'=> $post['group_id'],
						'updated' => time(),
				   );
				}
			}
			else
			{
					$user_update = array(
						'name' => $post['name'],
						'pass' => MD5($post['pass']),
						'email' => $post['email'],                                    
						'updated' => time(),
					 );
			}
                        
			 $id = $this->user_model->user_update($uid, $user_update);				
			 redirect('user/user_list/user_update_success');
			
		}	
		
		$data['maincontent'] = $this->load->view('user_add',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
		
	}
	
	public function user_detail($uid) 
	{
		if ($this->uri->segment(4)=='user_update_success')
		$data['message'] = 'User has been update successfully';		
		else
		$data['message'] = '';
				
		$user_details = $this->user_model->user_details($uid);
	   
		$data['title'] = 'Detail Information for: ' . $user_details->name;	
		$data['user_id'] = $user_details->uid;	
		
		
		$this->load->library('table');
		$this->table->set_empty("");
		
		// $cell = array('data' => 'Blue', 'class' => 'highlight', 'colspan' => 2);
		$this->table->add_row('User ID',$user_details->uid);       
		$this->table->add_row('UserName',$user_details->name); 
		 $this->table->add_row('Email',$user_details->email); 
		$this->table->add_row('Role',$user_details->rname); 
		$this->table->add_row('Status',$user_details->status == 1 ? 'Active' : 'Block');  
																	 
		
		$data['table'] = $this->table->generate(); 
		
		$data['maincontent'] = $this->load->view('user_details',$data,true);	
		
		$this->load->view('includes/header',$data);
		//$this->load->view('includes/sidebar',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	public function user_role_update($rid) 
	{
		$data['title'] = 'User Role update';
		$data['action'] = site_url('user/user_role_update/'.$rid);
			  
		$this->_set_userrole_rules();		
		if( $this->form_validation->run() === FALSE ) 
		{
			
			$data['urole'] = $this->user_model->user_role_rid($rid)->row();		
		}
		else 
		{			
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
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	public function user_role_delete($rid)
	{
		$this->user_model->user_role_delete($rid);
		redirect('user/user_list/user_role_delete_success');
	}
	public function user_delete($uid)
	{
		$this->user_model->user_delete($uid);
		redirect('user/user_list/user_delete_success');
	}
	function _set_rules()
	{
		$this->form_validation->set_rules('name', 'User name', 'trim|required');
		$this->form_validation->set_rules('pass', 'Password', 'trim|required');
    }
	function _set_edit_rules()
	{
		$this->form_validation->set_rules('name', 'User name', 'trim|required');
		$this->form_validation->set_rules('email', 'Email', 'trim|required');	
    }
	function _set_userrole_rules()
	{
		$this->form_validation->set_rules('rname', 'trim|required');
    }    
	function update_permission($permission_id,$permission_type,$permission_value)
	{
		$this->user_model->update_user_permission($permission_id,$permission_type,$permission_value);
	}

	function update_category($category_id,$user_id,$move_type)
	{

		if($move_type == 1)
		{
			$category_info = array(
					'user_id' => $user_id,
					'category_id' =>$category_id
					);
	
			$this->user_model->insert_user_category($category_info);
		}
		else
		{
			$this->user_model->delete_user_to_category($category_id,$user_id);
		}

	}

	function delete_permission_group($group_id)
	{
		$this->user_model->delete_permission_group($group_id);
		redirect('user/user_role_list/group_delete_success');
	}
	function delete_user_category($category_id)
	{
		$this->user_model->delete_user_category($category_id);
		redirect('user/user_category_list/category_delete_success');
	}
	function add_permission_group()
	{
		if( $this->input->post('submit')) 
		{
			$post = $this->input->post();	
			$group_info = array(
				'group_name' => $post['group_name'],
				'status' => 1
			);
				
			$id = $this->user_model->add_permission_group($group_info);
			redirect('user/user_role_list/group_add_success');
	    }
		
		
	}
	function add_user_category()
	{

		if( $this->input->post('submit')) 
		{
			$post = $this->input->post();	
			$category_info = array(
				'category_name' => $post['category_name'],
				'status' => 1
			);
				
			$id = $this->user_model->add_user_category($category_info);
			redirect('user/user_category_list/group_add_success');
	    }

	}

	function search_user($username)
	{
		
		$username = mysql_real_escape_string($username);	
		$userid = $this->user_model->search_user($username);
		
		echo $userid;
	}

	function search_email($user_email)
	{
		
		$user_email = mysql_real_escape_string(urldecode($user_email));	
		$email = $this->user_model->search_email($user_email);
		
		echo $email;
	}

	function search_edit_user($username, $user_id)
	{
		
		$username = mysql_real_escape_string($username);	
		$userid = $this->user_model->search_edit_user($username, $user_id);
		
		echo $userid;
	}


	function permission_field_add()
	{
		$data['title'] = '';

		if($this->input->post('submit')) 
		{
			$post = $this->input->post();	
			$add = array(
				'permission_name' => $post['permission_name'],
				'published' => 1
			);
				
			$id = $this->user_model->permission_field_add($add);

			$results = $this->user_model->groups_load()->result();
			foreach($results as $result){
				$add1 = array(
					'group_id' => $result->id,
					'permission_id' => $id,
					'read_type' => 1,
					'display_type' => 1,
					'published' => 1
				);
					
				$this->user_model->groups_permission_add($add1);
			}
	    }
		
		$data['maincontent'] = $this->load->view('user/permission_field_add',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	function keyword_permission($group_id,$keyword)
	{	
		$add = array(
			'group_id' => $group_id,
			'keyword' => urldecode($keyword)
		);	
		$id = $this->user_model->keyword_permission_add($add);		
		echo $id;
	}

	function keyword_permission_delete($id)
	{	
		$this->user_model->keyword_permission_delete($id);		
	}

	public function column_description() 
	{		
		$data['maincontent'] = $this->load->view('user/column_description',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);			
	}

	function column_description_id($id)
	{	
		$row = $this->user_model->column_description_id($id)->row();
		echo $row->description;		
	}

	function column_description_update($id)
	{
		$add = array(
			'description' => urldecode($_GET['description'])
		);	
		$this->user_model->column_description_update($id,$add);		
	}
	
}