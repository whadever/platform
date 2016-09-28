<?php
class Client extends CI_controller{
	
	function __construct(){
		parent::__construct();
		$this->load->helper(array('url','form'));
		$this->load->library(array('session','table'));
		$this->load->model('client_model','',TRUE);
		if(!$this->session->userdata('user') && $this->uri->segment(2) != 'login_using_customer_code'){
			redirect("user");
		}
	}
	function index(){
		$user = $this->session->userdata('user');
		if($user->uid != 1) die('access denied');
		$data['title'] = 'Manage client';
		$data['users'] = $this->client_model->get_client_info()->result();

		$data['maincontent'] = $this->load->view('client/client_list',$data,true);

		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	function client_add(){
		$user = $this->session->userdata('user'); 
		$data['title'] = 'Create Client';

		if($this->input->post('submit')){
			$post = $this->input->post();

			$config['upload_path'] = UPLOAD_LOGO;
	        $config['allowed_types'] = '*';
			//$config['overwrite'] = TRUE;
	        $this->load->library('upload', $config);
	        $this->upload->initialize($config);
			
			if ($this->upload->do_upload('logo'))
        	{
	        	$upload_data = $this->upload->data();	        
		        $document = array(             
		        	'filename'=>$upload_data['file_name'],
		            'filetype'=>$upload_data['file_type'],
		            'filesize'=>$upload_data['file_size'],
		            'filepath'=>$upload_data['full_path']
		        );
	            $file_id = $this->client_model->file_add($document);                     
			}
			else
			{
				$file_id = '0';
			}
			
			$config['upload_path'] = UPLOAD_BACKGROUND;
	        $config['allowed_types'] = '*';
			//$config['overwrite'] = TRUE;
	        $this->load->library('upload', $config);
	        $this->upload->initialize($config);
			
			if ($this->upload->do_upload('backgroundWclp'))
        	{
	        	$upload_data = $this->upload->data();	        
		        $backgroundWclp = array(             
		        	'filename'=>$upload_data['file_name'],
		            'filetype'=>$upload_data['file_type'],
		            'filesize'=>$upload_data['file_size'],
		            'filepath'=>$upload_data['full_path']
		        );
	            $backgroundWclp_id = $this->client_model->file_add($backgroundWclp);                     
			}
			else
			{
				$backgroundWclp_id = '0';
			}

			$company_add = array(
				'client_name' => $post['client_name'],
				'url' => $post['url'],
				'person_in_charge' => $post['person_in_charge'],
				'phone_number' => $post['phone_number'],
				'colour_one' => $post['colour_one'],
				'colour_two' => $post['colour_two'],
				'file_id' => $file_id,
				'backgroundWclp_id' => $backgroundWclp_id,
				'plan_id' => $post['plan_id'],
				'website' => $post['website']
			);
			$company_id = $this->client_model->company_add($company_add);

			$client_add = array(
				'company_id' => $company_id,
				'email' => $post['email'],
				'username' => $post['username'],		
				'password' => MD5($post['password']),
				'role' => 1,
				'status' => 1,
				'created' => date("Y-m-d,h:m:s")
			);
			$client_id = $this->client_model->client_add($client_add);

			$applications = $post['application'];
			for($i = 0; $i < count($applications); $i++)
			{
				$application_id = $applications[$i];
				$application_add = array(
					'company_id' => $company_id,
					'user_id' => $client_id,
					'application_id' => $application_id
				);
				$this->client_model->application_add($application_add); 
			}
			
			$profile = array( 
				'wp_company_id' => $company_id,                               
                //'company_date' => $this->wbs_helper->to_mysql_date($this->input->post('company_date')),
				'company_name' => $post['client_name'],
				'company_description' => $post['client_name'],
				'company_status' =>1,				
				
				'created'=>date("Y-m-d H:i:s"),
				'created_by' => $client_id
			);	
			$tms_company_id = $this->client_model->tms_company_save($profile);
			
			redirect("client?success=1");
			
		}

		$data['maincontent'] = $this->load->view('client/client_add',$data,true);

		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

	function client_update($uid){
		$user = $this->session->userdata('user'); 
		if($user->uid != 1) die('access denied');
		if($this->input->post('submit')){
			$post = $this->input->post();

			$config['upload_path'] = UPLOAD_LOGO;
	        $config['allowed_types'] = '*';
			//$config['max_size'] = '100000KB';
			//$config['overwrite'] = TRUE;
	        $this->load->library('upload', $config);
	        $this->upload->initialize($config);
			
			if ($this->upload->do_upload('logo'))
        	{
	        	$upload_data = $this->upload->data();	        
		        $document = array(             
		        	'filename'=>$upload_data['file_name'],
		            'filetype'=>$upload_data['file_type'],
		            'filesize'=>$upload_data['file_size'],
		            'filepath'=>$upload_data['full_path']
		        );
	            $file_id = $this->client_model->file_add($document);                     
			}
			else
			{
				$file_id = $post['file_id'];
			}
			
			$config['upload_path'] = UPLOAD_BACKGROUND;
	        $config['allowed_types'] = '*';
			//$config['max_size'] = '100000KB';
			//$config['overwrite'] = TRUE;
	        $this->load->library('upload', $config);
	        $this->upload->initialize($config);
			
			if ($this->upload->do_upload('backgroundWclp'))
        	{
	        	$upload_data = $this->upload->data();	        
		        $backgroundWclp = array(             
		        	'filename'=>$upload_data['file_name'],
		            'filetype'=>$upload_data['file_type'],
		            'filesize'=>$upload_data['file_size'],
		            'filepath'=>$upload_data['full_path']
		        );
	            $backgroundWclp_id = $this->client_model->file_add($backgroundWclp);                     
			}
			else
			{
				$backgroundWclp_id = $post['backgroundWclp_id'];
			}
			
			$company_id = $post['company_id'];
			$company_update = array(
				'client_name' => $post['client_name'],
				'url' => $post['url'],
				'person_in_charge' => $post['person_in_charge'],
				'phone_number' => $post['phone_number'],
				'country' 	=> $post['country'],
				'time_zone' => $post['time_zone'],
				'colour_one' => $post['colour_one'],
				'colour_two' => $post['colour_two'],
				'file_id' => $file_id,
				'backgroundWclp_id' => $backgroundWclp_id,
				'plan_id' => $post['plan_id'],
				'website' => $post['website']
			);
			$this->client_model->company_update($company_id,$company_update);

			$client_update = array(
				'email' => $post['email']
			);
			$this->client_model->client_update($uid,$client_update);

			//$this->client_model->client_application_delete($uid);
			$this->client_model->company_application_delete($company_id);

			$applications = $post['application'];
			for($i = 0; $i < count($applications); $i++)
			{
				$application_id = $applications[$i];
				$application_add = array(
					'company_id' => $company_id,
					//'user_id' => $uid,
					'application_id' => $application_id
				);
				$this->client_model->application_add($application_add); 
			}
			redirect("client?success=3");
			
		}else{
			$data['user'] = $this->client_model->client_uid($uid)->row();
			$client = $data['user'];
			$data['title'] = $client->client_name;

			$data['maincontent'] = $this->load->view('client/client_update',$data,true);

			$this->load->view('includes/header',$data);
			$this->load->view('home',$data);
			$this->load->view('includes/footer',$data);
		}

	}

	/*company admin update company profile*/
	function profile(){

		$user = $this->session->userdata('user');

		if($this->input->post('submit')){
			$post = $this->input->post();

			$config['upload_path'] = UPLOAD_LOGO;
			$config['allowed_types'] = '*';
			//$config['max_size'] = '100000KB';
			//$config['overwrite'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('logo'))
			{
				$upload_data = $this->upload->data();
				$document = array(
					'filename'=>$upload_data['file_name'],
					'filetype'=>$upload_data['file_type'],
					'filesize'=>$upload_data['file_size'],
					'filepath'=>$upload_data['full_path']
				);
				$file_id = $this->client_model->file_add($document);
			}
			else
			{
				$file_id = $post['file_id'];
			}

			$config['upload_path'] = UPLOAD_BACKGROUND;
			$config['allowed_types'] = '*';
			//$config['max_size'] = '100000KB';
			//$config['overwrite'] = TRUE;
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('backgroundWclp'))
			{
				$upload_data = $this->upload->data();
				$backgroundWclp = array(
					'filename'=>$upload_data['file_name'],
					'filetype'=>$upload_data['file_type'],
					'filesize'=>$upload_data['file_size'],
					'filepath'=>$upload_data['full_path']
				);
				$backgroundWclp_id = $this->client_model->file_add($backgroundWclp);
			}
			else
			{
				$backgroundWclp_id = $post['backgroundWclp_id'];
			}

			$company_update = array(
				'client_name' => $post['client_name'],
				//'url' => $post['url'],
				'person_in_charge' => $post['person_in_charge'],
				'address' => $post['address'],
				'phone_number' => $post['phone_number'],
				'colour_one' => $post['colour_one'],
				'colour_two' => $post['colour_two'],
				'file_id' => $file_id,
				'backgroundWclp_id' => $backgroundWclp_id,
				//'plan_id' => $post['plan_id'],
				//'pricing' => $post['pricing'],
				'website' => $post['website']
			);
			$this->client_model->company_update($user->company_id,$company_update);

			/*$client_update = array(
				'email' => $post['email']
			);
			$this->client_model->client_update($uid,$client_update);*/

			$this->session->set_flashdata('success-message', 'Profile Updated.');
			redirect(site_url('user/user_list'));

		}else{
			$data['user'] = $this->client_model->client_uid($user->uid)->row();
			$client = $data['user'];
			$data['title'] = $client->client_name;

			$data['maincontent'] = $this->load->view('client/profile',$data,true);

			$this->load->view('includes/header',$data);
			$this->load->view('home',$data);
			$this->load->view('includes/footer',$data);
		}

	}

	public function client_detail($uid) {               

        $client_details = $this->client_model->client_details($uid)->row(); 
             
        $data['title'] = 'Client Detail for: ' . $client_details->client_name;
        $data['user_id']=$client_details->uid;
        $data['username']=$client_details->username;
								
		$this->load->library('table');
		$this->table->set_empty("");

        $this->table->add_row('Client Name',$client_details->client_name); 
		$this->table->add_row('URL',$client_details->url);
		$this->table->add_row('URL',$client_details->plan_id);
		$this->table->add_row('Status',$client_details->pricing == 0 ? 'Full Price' : 'Discounted Price'); 
		$this->table->add_row('Colour One',$client_details->colour_one); 
		$this->table->add_row('Colour Two',$client_details->colour_two); 
		$this->table->add_row('Logo','<img style="width:30px;" src="'.base_url().'uploads/logo/'.$client_details->filename.'" />');
		$this->table->add_row('Company Admin',$client_details->username); 

        $this->table->add_row('Status',$client_details->status == 1 ? 'Active' : 'Block');  
        //table table-hover 
        $tmpl_user= array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered">' );
        $this->table->set_template($tmpl_user);
                
		$data['client_table'] = $this->table->generate(); 
                
        $app_client_results = $this->client_model->application_client_list($uid,$client_details->company_id)->result();
		
		$this->load->library('table');
		$this->table->set_empty("");
		$i=1;
		$this->table->set_heading(
            'ID',	
            'Systems'	
		);
		foreach ($app_client_results as $app_client_result){			
			$this->table->add_row(
				'00'.$i,
				$app_client_result->application_name
			); $i++;
		}
                
		$tmpl_user= array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered">' );
        $this->table->set_template($tmpl_user);
		$data['app_client_table'] = $this->table->generate();
                
		$data['maincontent'] = $this->load->view('client/client_detail',$data,true);

        $this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

	public function check_username(){
		$get = $_GET;	
		$this->client_model->check_username($get);			
	}

	public function client_delete($company_id){
		$this->client_model->client_delete($company_id);
		redirect('client?success=2');
	}

	/*task #4525*/
	public function get_customer_support_code(){
		$n = rand(0,13);
		//$code = substr(str_shuffle(MD5(microtime())), $n, 10).str_shuffle(date("ymd"));
		$code = substr(str_shuffle(MD5(microtime())), $n, 8);
		$user = $this->session->userdata('user');
		//$this->db->where('user_id',$user->uid);
		//$this->db->delete('customer_support_codes');
		$this->db->insert('customer_support_codes',array(
			'user_id' => $user->uid,
			'code' => $code,
			'created' => time()
		));
		echo $code;
	}
	/*task #4525*/
	public function login_using_customer_code(){

		if($this->input->post('code')){

			$user = $this->session->userdata('user');

			if($user->role == 3){

				$code = $this->db->get_where('customer_support_codes',array('code'=>$this->input->post('code')),0,1)->row();

				if($code && (time() - $code->created) < 2 * 3600){

					$query = "select users.*, wp_company.* from users inner join wp_company on users.company_id = wp_company.id where users.uid = ".$code->user_id;

					$user = $this->db->query($query)->row();

					$this->db->where('id',$code->id);

					$this->db->update('customer_support_codes',array('redirected' => 1));
					
					if($_SERVER['https']){
					
						redirect("https://{$user->url}/client/login_using_customer_code?code={$this->input->post('code')}");

					}else{

						redirect("http://{$user->url}/client/login_using_customer_code?code={$this->input->post('code')}");

					}


				}else{

					die("invalid code");
				}
			}
		}elseif($this->input->get('code')){

			$code = $this->db->get_where('customer_support_codes',array('code'=>$this->input->get('code'), 'redirected' => 1),0,1)->row();

			if($code && (time() - $code->created) < 2 * 3600){

				$query = "select users.*, wp_company.* from users inner join wp_company on users.company_id = wp_company.id where users.uid = ".$code->user_id;

				$user = $this->db->query($query)->row();

				$sesData['user'] = $user;

				$this->session->set_userdata($sesData);

				$this->db->where('id',$code->id);

				$this->db->update('customer_support_codes',array('redirected' => 0));

				if($_SERVER['https']){

					redirect("https://{$user->url}");

				}else{

					redirect("http://{$user->url}");

				}


			}else{

				die("invalid code");
			}

		}
	}

	/*task #4670*/
	public function generate_discount_code(){

		$post = $this->input->post();

		$user = $this->session->userdata('user');

		if($user->role == 3){

			$n = rand(0,13);

			$code = substr(str_shuffle(MD5(microtime())), $n, 8);

			$data = array(
				'code' => $code,
				'expire_at' => time() + $post['expire'] * 3600,
				'discount' => $post['amount'],
				'months' => $post['months']
			);

			if($this->db->insert('wp_discount_codes', $data)){
				echo $code;exit;
			}
		}
	}

	public function apply_discount_code(){

		if($this->input->post('code')){
			$this->db->where('code', $this->input->post('code'));
			$this->db->where('expire_at > ', time());
			$code = $this->db->get('wp_discount_codes',1,0)->row();
			if(empty($code)){
				$this->session->set_flashdata('warning-message', 'Invalid Code.');
				redirect(site_url('user/user_list'));exit;
			}
			$this->db->where('discount_code_id', $code->id);
			$this->db->where('wp_company_id', $this->session->userdata('user')->company_id);
			if($this->db->get('wp_company_discounts')->result()){
				$this->session->set_flashdata('warning-message', 'This code is used.');
				redirect(site_url('user/user_list'));exit;
			}
			$data = array(
				'discount_code_id' => $code->id,
				'wp_company_id' => $this->session->userdata('user')->company_id,
				'months_left' => ($code->months == 0) ? -1 : $code->months
			);

			if($this->db->insert('wp_company_discounts',$data)){
				$msg = "You have got {$code->discount}% discount";
				if($code->months != 0){
					$msg .= " for {$code->months} months";
				}
				$msg .=".";

				$this->session->set_flashdata('success-message', $msg);
				redirect(site_url('user/user_list'));exit;
			}

			$this->session->set_flashdata('warning-message', 'An error occured.');
			redirect(site_url('user/user_list'));exit;

		}
	}

}
