<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Client extends CI_Controller {  

    public function __construct()
    {
        parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation'));
		$this->load->model('client_model','',TRUE);
		$this->load->library('session');
        //$this->load->model('permission_model');
    }
	
	public function check_job_number(){
		$get = $_GET;	
		$this->client_model->check_job_number($get);			
	}

	public function archive_update(){
		$get = $_GET;
		$id = $get['id'];
		$archive = array(
			'archive' => $get['value']
		);	
		$this->client_model->client_update($id,$archive);			
	}

 	public function client_add() 
	{
      	$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;
	
		if ( $this->input->post('submit')) 
		{
			$post = $this->input->post();
			$full_address = $post['number'].' '.$post['street'].' '.$post['suburb'].' '.$post['city'];		
			$client_add_info = array(
				'wp_company_id' => $wp_company_id,
				'address' => $full_address,
				'number' => $post['number'],
				'street' => $post['street'],
				'suburb' => $post['suburb'],
				'city' => $post['city'],
				'job_number' => $post['job_number'],
				'legal_description' => $post['legal_description'],
				'corrosion_zone' => $post['corrosion_zone'],
				'wind_zone' => $post['wind_zone'],
				'note' => $post['note'],
				'status' => 1,
				'created' => time()
			);
			
			$id = $this->client_model->client_save($client_add_info);
			$data['message'] = 'Client has been saved successfully';
			redirect('client/client_list');  
	    }
			
	}

	public function client_list() 
	{
       

		$get = $_POST;		
		$data['title'] = 'Property List';
		$data['action'] = site_url('user/user_list');			    	  
		
		$user_results = $this->client_model->client_list($get)->result();
		
		$this->load->library('table');
		$this->table->set_empty("");
		
		$tmpl = '<table id="client_table" class="table">';

		$i=1;

		$this->table->set_heading(array('data'=>'Job Number', 'style'=>'width:10%',  'class'=>'res-hidden'), array('data'=>'Property', 'style'=>'width:27%'), array('data'=>'Legal Description', 'style'=>'width:15%'), array('data'=>'Note', 'style'=>'width:17%',  'class'=>'res-hidden'), array('data'=>'Corrosion Zone', 'style'=>'width:10%',  'class'=>'res-hidden'), array('data'=>'Wind Zone', 'style'=>'width:10%',  'class'=>'res-hidden'), array('data'=>'Edit', 'style'=>'width:5%;'),array('data'=>'Remove', 'style'=>'width:5%') );

		foreach ($user_results as $user_result)
		{
			
			$tmpl .= "<tr id='check_".$user_result->id."'><td class='res-hidden'>".$user_result->job_number."</td><td class='uname'>".$user_result->number." ".$user_result->street." ".$user_result->suburb." ".$user_result->city."</td><td>".$user_result->legal_description."</td><td class='res-hidden'>".$user_result->note."</td><td class='res-hidden'>".$user_result->corrosion_zone."</td><td class='res-hidden'>".$user_result->wind_zone."</td><td><a href='#UpdateUser_".$user_result->id."' data-toggle ='modal'>Edit</a></td><td style='text-align:center'><a href='#Delete_Client_".$user_result->id."' data-toggle ='modal'>Remove</a></td>";
			
			$action = base_url().'client/client_update/'.$user_result->id;
			$delete_action = base_url().'client/client_delete/'.$user_result->id;
   
			$form_attributes = array('class' => 'user-edit-form', 'name'=>'edit_user', 'id' => 'entry-form','method'=>'post');


			$tmpl .= '<div id="Delete_Client_'.$user_result->id.'" class="modal hide fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-header">
							
							<h3 id="myModalLabel">Delete Property</h3>
						</div>
						<div class="modal-body">';
			$tmpl .= form_open($delete_action, $form_attributes);
			$tmpl .= '<div class="row"><div class="col-xs-12 col-sm-12 col-md-12"><p>Are you sure to delete '.$user_result->job_number.'</p></div>';
			$tmpl .='<div class="col-xs-12 col-sm-3 col-md-3"><button type="button" class="btn cancel" data-dismiss="modal" aria-hidden="true">Cancel</button></div><div class="col-xs-12 col-sm-3 col-md-3"><input value="client_list" type="hidden" name="url" /><input type="submit" value="Delete" class="btn create" /></div></div>';
			$tmpl .= form_close();
			$tmpl .=	'</div>';
			$tmpl .='</div>';
			

			$tmpl .= '<div id="UpdateUser_'.$user_result->id.'" class="modal hide fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-header"><h3 id="myModalLabel">Edit Client</h3></div><div class="modal-body">';
	
			
			$id = form_hidden('id', $user_result->id);

			$client_name = form_label('Client Name:', 'client_name');
			$client_name .= form_input(array(
	              'name'        => 'client_name',
	              'id'          => 'edit_client_name',
	              'value'       => isset($user_result->client_name) ? $user_result->client_name : '',
	              'class'       => 'form-control',
                  'required'    => TRUE
			));
	

	
			$email = form_label('Email:', 'email');
			$email .= form_input(array(
						  'name'        => 'email',
						  'id'          => 'user-email',
						  'value'       => $user_result->email,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));

			$number = form_label('Number:*', 'number');
			$number .= form_input(array(
						  'name'        => 'number',
						  'id'          => 'number',
						  'value'       => $user_result->number,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));
			$street = form_label('Street:*', 'street');
			$street .= form_input(array(
						  'name'        => 'street',
						  'id'          => 'street',
						  'value'       => $user_result->street,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));
			$suburb = form_label('Suburb:*', 'suburb');
			$suburb .= form_input(array(
						  'name'        => 'suburb',
						  'id'          => 'suburb',
						  'value'       => $user_result->suburb,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));
			$city = form_label('City:*', 'city');
			$city .= form_input(array(
						  'name'        => 'city',
						  'id'          => 'city',
						  'value'       => $user_result->city,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));

			$job_number = form_label('Job Number:*', 'job_number');
			$job_number .= form_input(array(
						  'name'        => 'job_number',
						  'id'          => 'job_number',
						  'value'       => $user_result->job_number,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));
			$legal_description = form_label('Legal Description:*', 'legal_description');
			$legal_description .= form_input(array(
						  'name'        => 'legal_description',
						  'id'          => 'legal_description',
						  'value'       => $user_result->legal_description,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));
			$note = form_label('Notes:', 'note');
			$note .= form_input(array(
						  'name'        => 'note',
						  'id'          => 'note',
						  'value'       => $user_result->note,
						  'class'       => 'form-control'

			));
			$corrosion_zone = form_label('Corrosion Zone:', 'corrosion_zone');
			$corrosion_zone .= form_input(array(
						  'name'        => 'corrosion_zone',
						  'id'          => 'note',
						  'value'       => $user_result->corrosion_zone,
						  'class'       => 'form-control'

			));
			$wind_zone = form_label('Wind Zone:', 'wind_zone');
			$wind_zone .= form_input(array(
						  'name'        => 'wind_zone',
						  'id'          => 'note',
						  'value'       => $user_result->wind_zone,
						  'class'       => 'form-control'

			));
	
			//$submit = form_label('', 'submit');
			$submit = form_submit(array(
						  'name'        => 'submit',
						  'id'          => 'save_user',
						  'value'       => 'Save',
						  'class'       => 'btn create',
						  'type'        => 'submit',
						  
			));

			$tmpl .='<div class="row">';
			$tmpl .= form_open($action, $form_attributes);
			$tmpl .= '<div class="col-xs-12 col-sm-12 col-md-12">Property Address</div>';
			$tmpl .= '<div class="col-xs-12 col-sm-4 col-md-4"><div class="form-group">'. $number . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-8 col-md-8"><div class="form-group">'. $street . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">'. $suburb . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">'. $city . '</div></div>';
		
			$tmpl .= '<div class="col-xs-12 col-sm-12 col-md-12"><div class="form-group">'. $job_number . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-12 col-md-12"><div class="form-group">'. $legal_description . '</div></div>';					
			$tmpl .= '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">'. $corrosion_zone . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">'. $wind_zone . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-12 col-md-12"><div class="form-group">'. $note . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-6 col-md-6"><input value="client_list" type="hidden" name="url" /></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-3 col-md-3"><div class="form-group"><button type="button" class="btn cancel" data-dismiss="modal" aria-hidden="true">Cancel</button></div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-3 col-md-3"><div class="form-group">'. $submit . '</div></div>';

			$tmpl .= form_close();
			$tmpl .='</div>';

			$tmpl .='</div>';
			$tmpl .='</div></tr>';
		
			$i++;
		}
		
		$tmp =  array ( 'table_open'  => $tmpl ) ;
		$this->table->set_template($tmp); 
                
		$data['user_table'] = $this->table->generate();
		$data['maincontent'] = $this->load->view('client/client_list',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);
			
	}


	public function archive_list() 
	{
       

		$get = $_POST;		
		$data['title'] = 'Property List';
		$data['action'] = site_url('user/user_list');			    	  
		
		$user_results = $this->client_model->archive_list($get)->result();
		
		$this->load->library('table');
		$this->table->set_empty("");
		
		$tmpl = '<table id="client_table" class="table">';

		$i=1;

		$this->table->set_heading(array('data'=>'Job Number', 'style'=>'width:16%',  'class'=>'res-hidden'), array('data'=>'Property', 'style'=>'width:30%'), array('data'=>'Note', 'style'=>'width:40%',  'class'=>'res-hidden'), array('data'=>'Edit', 'style'=>'width:7%;'),array('data'=>'Remove', 'style'=>'width:7%'),array('data'=>'Archive', 'style'=>'width:7%') );

		foreach ($user_results as $user_result)
		{
			
			$tmpl .= "<tr id='check_".$user_result->id."'><td class='res-hidden'>".$user_result->job_number."</td><td class='uname'>".$user_result->number." ".$user_result->street." ".$user_result->suburb." ".$user_result->city."</td><td class='res-hidden'>".$user_result->note."</td><td><a href='#UpdateUser_".$user_result->id."' data-toggle ='modal'>Edit</a></td><td style='text-align:center'><a href='#Delete_Client_".$user_result->id."' data-toggle ='modal'>Remove</a></td><td style='text-align:center'><input checked onclick='Archive(".$user_result->id.",0)' type='checkbox' /></td>";
			
			$action = base_url().'client/client_update/'.$user_result->id;
			$delete_action = base_url().'client/client_delete/'.$user_result->id;
   
			$form_attributes = array('class' => 'user-edit-form', 'name'=>'edit_user', 'id' => 'entry-form','method'=>'post', 'onsubmit' => 'return check_edit_form('.$user_result->id.')');


			$tmpl .= '<div id="Delete_Client_'.$user_result->id.'" class="modal hide fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<div class="modal-header">
							
							<h3 id="myModalLabel">Delete Property</h3>
						</div>
						<div class="modal-body">';
			$tmpl .= form_open($delete_action, $form_attributes);
			$tmpl .= '<div class="row"><div class="col-xs-12 col-sm-12 col-md-12"><p>Are you sure to delete '.$user_result->job_number.'</p></div>';
			$tmpl .='<div class="col-xs-12 col-sm-3 col-md-3"><button type="button" class="btn cancel" data-dismiss="modal" aria-hidden="true">Cancel</button></div><div class="col-xs-12 col-sm-3 col-md-3"><input value="archive_list" type="hidden" name="url" /><input type="submit" value="Delete" class="btn create" /></div></div>';
			$tmpl .= form_close();
			$tmpl .=	'</div>';
			$tmpl .='</div>';
			

			$tmpl .= '<div id="UpdateUser_'.$user_result->id.'" class="modal hide fade edit_modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true"><div class="modal-header"><h3 id="myModalLabel">Edit Client</h3></div><div class="modal-body">';
	
			
			$id = form_hidden('id', $user_result->id);

			$client_name = form_label('Client Name:*', 'client_name');
			$client_name .= form_input(array(
	              'name'        => 'client_name',
	              'id'          => 'edit_client_name',
	              'value'       => isset($user_result->client_name) ? $user_result->client_name : '',
	              'class'       => 'form-control',
                  'required'    => TRUE
			));
	

	
			$email = form_label('Email:*', 'email');
			$email .= form_input(array(
						  'name'        => 'email',
						  'id'          => 'user-email',
						  'value'       => $user_result->email,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));

			$number = form_label('Number:*', 'number');
			$number .= form_input(array(
						  'name'        => 'number',
						  'id'          => 'number',
						  'value'       => $user_result->number,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));
			$street = form_label('Street:*', 'street');
			$street .= form_input(array(
						  'name'        => 'street',
						  'id'          => 'street',
						  'value'       => $user_result->street,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));
			$suburb = form_label('Suburb:*', 'suburb');
			$suburb .= form_input(array(
						  'name'        => 'suburb',
						  'id'          => 'suburb',
						  'value'       => $user_result->suburb,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));
			$city = form_label('City:*', 'city');
			$city .= form_input(array(
						  'name'        => 'city',
						  'id'          => 'city',
						  'value'       => $user_result->city,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));

			$job_number = form_label('Job Number:*', 'job_number');
			$job_number .= form_input(array(
						  'name'        => 'job_number',
						  'id'          => 'job_number',
						  'value'       => $user_result->job_number,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));
			$legal_description = form_label('Legal Description:*', 'legal_description');
			$legal_description .= form_input(array(
						  'name'        => 'legal_description',
						  'id'          => 'legal_description',
						  'value'       => $user_result->legal_description,
						  'class'       => 'form-control',
						  'required'    => TRUE

			));
			$note = form_label('Notes:', 'note');
			$note .= form_input(array(
						  'name'        => 'note',
						  'id'          => 'note',
						  'value'       => $user_result->note,
						  'class'       => 'form-control'

			));
			$corrosion_zone = form_label('Corrosion Zone:', 'corrosion_zone');
			$corrosion_zone .= form_input(array(
						  'name'        => 'corrosion_zone',
						  'id'          => 'note',
						  'value'       => $user_result->corrosion_zone,
						  'class'       => 'form-control'

			));
			$wind_zone = form_label('Wind Zone:', 'wind_zone');
			$wind_zone .= form_input(array(
						  'name'        => 'wind_zone',
						  'id'          => 'note',
						  'value'       => $user_result->wind_zone,
						  'class'       => 'form-control'

			));
	
			//$submit = form_label('', 'submit');
			$submit = form_submit(array(
						  'name'        => 'submit',
						  'id'          => 'save_user',
						  'value'       => 'Save',
						  'class'       => 'btn create',
						  'type'        => 'submit',
						  
			));

			$tmpl .='<div class="row">';
			$tmpl .= form_open($action, $form_attributes);
			$tmpl .= '<div class="col-xs-12 col-sm-12 col-md-12">Property Address</div>';
			$tmpl .= '<div class="col-xs-12 col-sm-4 col-md-4"><div class="form-group">'. $number . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-8 col-md-8"><div class="form-group">'. $street . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">'. $suburb . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">'. $city . '</div></div>';
		
			$tmpl .= '<div class="col-xs-12 col-sm-12 col-md-12"><div class="form-group">'. $job_number . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-12 col-md-12"><div class="form-group">'. $legal_description . '</div></div>';	
			$tmpl .= '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">'. $corrosion_zone . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group">'. $wind_zone . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-12 col-md-12"><div class="form-group">'. $note . '</div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-6 col-md-6"><input value="archive_list" type="hidden" name="url" /></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-3 col-md-3"><div class="form-group"><button type="button" class="btn cancel" data-dismiss="modal" aria-hidden="true">Cancel</button></div></div>';
			$tmpl .= '<div class="col-xs-12 col-sm-3 col-md-3"><div class="form-group">'. $submit . '</div></div>';

			$tmpl .= form_close();
			$tmpl .='</div>';

			$tmpl .='</div>';
			$tmpl .='</div></tr>';
		
			$i++;
		}
		
		$tmp =  array ( 'table_open'  => $tmpl ) ;
		$this->table->set_template($tmp); 
                
		$data['user_table'] = $this->table->generate();
		$data['maincontent'] = $this->load->view('client/archive_list',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('includes/home',$data);
		$this->load->view('includes/footer',$data);
			
	}

	public function archive_clear_search()
    {
        $this->session->unset_userdata('pro_search1');
    }

	public function clear_search()
    {
        $this->session->unset_userdata('pro_search');
    }
	
	public function client_update($uid) 
	{
            	       
		$data['title'] = 'Client update';
		$data['action'] = site_url('client/client_update/'.$uid);
        
		$post = $this->input->post();

		$url = $post['url'];

		$full_address = $post['number'].' '.$post['street'].' '.$post['suburb'].' '.$post['city'];
		$client_update = array(
				'address' => $full_address,
				'number' => $post['number'],
				'street' => $post['street'],
				'suburb' => $post['suburb'],
				'city' => $post['city'],
				'job_number' => $post['job_number'],
				'legal_description' => $post['legal_description'],
				'corrosion_zone' => $post['corrosion_zone'],
				'wind_zone' => $post['wind_zone'],
				'note' => $post['note'],
				'updated' => time(),
		);
				                        
		$this->client_model->client_update($uid, $client_update);				
		redirect('client/'.$url);
		
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

	public function client_delete($cid)
	{
		$post = $this->input->post();
		$url = $post['url'];

		$this->client_model->client_delete($cid);
		redirect('client/'.$url);
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

	
	
	
}