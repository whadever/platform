<?php 
class Contact extends CI_Controller {
	
	private $limit = 10;
	private $user_app_role = '';

	function __construct() {
		parent::__construct();
		$this->load->model('contact_model','',TRUE);
        $this->load->model('project_notes_model','',TRUE);
        $this->load->library(array('table','form_validation', 'session'));  
		$this->load->library('Wbs_helper');
        $this->load->library('breadcrumbs');
		$this->load->helper(array('form', 'url'));
        $this->load->helper('email');
        date_default_timezone_set("NZ");

		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}

        /*getting user's application role*/
        $user = $this->session->userdata('user');
        $sql = "select LOWER(ar.application_role_name) role
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 5 limit 0, 1";
        $this->user_app_role = $this->db->query($sql)->row()->role;
        
	}
        
	public function index(){
		$data['title'] = 'Contact';
        $contacts = $this->contact_model->get_contact_list(0,0,'contact_first_name','asc')->result();
		$data['contacts']=  $contacts;
		$data['maincontent'] = $this->load->view('contact/contact_list',$data,true);	
		$this->load->view('includes/header',$data);
		$this->load->view('contact/contact_home',$data);
		$this->load->view('includes/footer',$data);

        /*log*/
        $this->wbs_helper->log('Contact',"Visited contact page");
	}
        
	public function contact_list($sort_by = 'id', $order_by = 'desc', $offset = 0){
		$project_name =  $this->input->post('project_name');
            if($this->input->post('project_sort')==''){
               $sort_by = 'project_name' ;
               $order_by = 'asc';
            }
            else if($this->input->post('project_sort')=='id'){
                $sort_by = 'id' ;
               $order_by = 'desc';
            }
            else{
                $sort_by = $this->input->post('project_sort') ;
                $order_by = 'asc';
            };
           
            $data['title'] = 'Contact';
            $data['sort_by']=$this->input->post('project_sort');
            $data['project_search_name']=$this->input->post('project_name');
           
            
            $get = $_GET;
            $this->limit = 50;
            
            //$contacts = $this->contact_model->get_contact_list($project_name, $sort_by,$order_by,$offset,$this->limit,$get)->result();
            $contacts = $this->contact_model->get_contact_list(0,0,'contact_first_name','asc')->result();
            $data['contacts']=  $contacts;
            
            $data['maincontent'] = $this->load->view('contact/contact_list',$data,true);
		
            $this->load->view('includes/header',$data);
            $this->load->view('contact/contact_home',$data);
            $this->load->view('includes/footer',$data);

       /*log*/
        $this->wbs_helper->log('contact list',"Viewed contact list");
	}
	
	public function contact_add($contact_id=0){

        if($this->user_app_role == 'contractor') return;

        $data['user_app_role'] = $this->user_app_role;

		if($contact_id != 0)
		{
			$contact = $this->contact_model->get_contact_details($contact_id);
			$data['contact'] = $contact;
		}
		$user=  $this->session->userdata('user');          
		$user_id =$user->uid;  
		$wp_company_id = $user->company_id;

		$data['title'] = 'Add New Contact';
		$data['action'] = site_url('contact/contact_add/'.$contact_id);
        $this->_set_rules();   
 
		if($this->form_validation->run() === FALSE )
		{
            /*getting system users' list*/
            $query = "SELECT users.uid, users.username FROM users LEFT JOIN users_application ON users_application.user_id = users.uid LEFT JOIN contact_contact_list ON contact_contact_list.system_user_id = users.uid
                      WHERE users.company_id='$wp_company_id' AND users_application.application_id=5 AND ( users_application.application_role_id!=1 ) AND contact_contact_list.system_user_id IS NULL OR contact_contact_list.system_user_id = ''";
            if($data['contact']->system_user_id){
                $query .= " OR contact_contact_list.system_user_id = {$data['contact']->system_user_id} ";
            }
            $data['system_users'] = array();
            $system_users = $this->db->query($query)->result();
            foreach($system_users as $system_user){
                $data['system_users'][$system_user->uid] = $system_user->username;
            }
			$data['maincontent'] = $this->load->view('contact/contact_add',$data,true);		
			$this->load->view('includes/header',$data);
			$this->load->view('contact/contact_home',$data);
			$this->load->view('includes/footer',$data);
		}
		else
		{

			$post = $this->input->post();
            $config['upload_path'] = UPLOAD_FILE_PATH_REQUEST_DOCUMENT;
            $config['allowed_types'] = '*';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $document_insert_id = 0;

            /*log*/
            $contact = $this->contact_model->get_contact_details($contact_id);
            $this->wbs_helper->log('Contact update',"Updated  <b>{$contact->contact_first_name} {$contact->contact_last_name}</b>");

            if ($this->upload->do_upload('upload_document')){
                $upload_data = $this->upload->data();
                //print_r($upload_data); 
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $document = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    //'filename_custom'=>$post['upload_document'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $document_insert_id = $this->contact_model->file_insert($document);                        
            }else{
                // print 'error in file uploading...'; 
                // print $this->upload->display_errors() ;  
            } 

            $image_insert_id = 0;
            if ($this->upload->do_upload('upload_image')){
                $upload_data = $this->upload->data();
                //print_r($upload_data); 
                // insert data to file table
                // get latest id from frim table and insert it to loan table
                $image = array(
                    'filename'=>$upload_data['file_name'],
                    'filetype'=>$upload_data['file_type'],
                    'filesize'=>$upload_data['file_size'],
                    'filepath'=>$upload_data['full_path'],
                    //'filename_custom'=>$post['upload_image'],
                    'created'=>date("Y-m-d H:i:s"),
                    'uid'=>$user_id
                );
                $image_insert_id = $this->contact_model->file_insert($image);                        
            }else{
                // print 'error in image uploading...'; 
                // print $this->upload->display_errors() ;  
            } 

			if( $image_insert_id == 0)
			{
				$image_insert_id = $this->input->post('image_id');
			}
              
                $contact_data = array(
                    'contact_first_name' => $this->input->post('contact_first_name'),
                    'contact_last_name' => $this->input->post('contact_last_name'),
                    'company_id' =>$this->input->post('company_id'),	
                    'contact_phone_number' => $this->input->post('contact_phone_number'),
                    'contact_mobile_number' => $this->input->post('contact_mobile_number'),
                    'contact_email' => $this->input->post('contact_email'),
                    'category_id' => $this->input->post('category_id'),
                    'contact_title' => $this->input->post('contact_title'),                          
                    'contact_address' => $this->input->post('contact_address'),
                    'contact_city' => $this->input->post('contact_city'),  
                    'contact_country' => $this->input->post('contact_country'),
					'contact_website' => $this->input->post('contact_website'),
                    'contact_image_id' =>$image_insert_id,  
					'status'  => '1',
					'contact_notes' => $this->input->post('contact_notes'),
					'system_user_id' => $this->input->post('system_user_id'),
                    'created' => date("Y-m-d H:i:s"),
                    'created_by' =>$user_id
                );	
                $this->contact_model->contact_save($contact_data,$contact_id);
                $this->session->set_flashdata('success-message', 'Contact Successfully Added.');
                
				redirect('contact/contact_list');
                	
		} 
    }
	
	public function contact_delete($contact_id){
        if($this->user_app_role == 'contractor') return;
		$this->contact_model->delete_project_with_requests_notes($pid);
		$this->session->set_flashdata('warning-message', 'Contact Successfully Removed.');
		redirect('contact/contact_list');
	}
	
	function _set_rules(){
            //$this->form_validation->set_rules('compname', 'compname', 'trim|required|is_unique[project_profile.compname]');
            //$this->form_validation->set_rules('project_id', 'Project Id', 'callback_project_id');
            $this->form_validation->set_rules('contact_position', 'Position');
           //$this->form_validation->set_rules('project_name', 'Project Name', 'required|min_length[5]|max_length[12]');
           // $this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[project_profile.email_addr_1]');
	}
		
	function contact_details($contact_id=0){
		if ($contact_id <=0){
             redirect('contact/contact_list');
        }
        
        $contact = $this->contact_model->get_contact_details($contact_id);
		
        $data['title'] = 'Contact details for : '  . $contact->contact_first_name.' '.$contact->contact_last_name;
        $data['contact'] = $contact;
             
        $data['maincontent'] = $this->load->view('contact/contact_detail',$data,true);
        $this->load->view('includes/header', $data);
        $this->load->view('contact/contact_home',$data);
        $this->load->view('includes/footer', $data);

        /*log*/
        $this->wbs_helper->log('Contact details',"Viewed details of <b>{$contact->contact_first_name} {$contact->contact_last_name}</b>");
    }

    function edit_note($cid){
        if($this->user_app_role == 'contractor') return;

        /*log*/
        $contact = $this->contact_model->get_contact_details($cid);
        if($contact->contact_notes != $this->input->post('note')){

            $this->wbs_helper->log('Contact note',"Updated note for contact <b>{$contact->contact_first_name} {$contact->contact_last_name}</b>");
        }

        $note =  $this->input->post('note');
        $query = "update contact_contact_list set contact_notes = '{$note}' where id = {$cid}";
        $res = $this->db->simple_query($query);
        echo $res; exit;
    }
    
    
}
