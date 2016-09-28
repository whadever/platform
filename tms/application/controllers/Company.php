<?php 
class Company extends CI_Controller {
	
	private $limit = 10;
	
	function __construct() {
		
		parent::__construct();
		
		
		
		$this->load->model('company_model','',TRUE);
        $this->load->model('company_notes_model','',TRUE);
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->library('Wbs_helper');
        $this->load->library('breadcrumbs');
		$this->load->helper(array('form', 'url'));
        $this->load->helper('email');

        $redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}        
	}
        
        public function index(){
            $data['title'] = 'Company';
            
             //$companys = $this->company_model->company_list_search_count($sort_by,$order_by,$offset,$this->limit,$get)->result();
            $companys = $this->company_model->get_company()->result();
            $data['companys']=  $companys;
            
            
            
            $data['maincontent'] = $this->load->view('company',$data,true);		
            $this->load->view('includes/header',$data);           
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
            
            
            
        }
        


        public function company_list($sort_by = 'id', $order_by = 'desc', $offset = 0){
           //echo $this->input->post('company_sort');
            $company_name =  $this->input->post('company_name');
            if($this->input->post('company_sort')==''){
               $sort_by = 'company_name' ;
               $order_by = 'asc';
            }
            else if($this->input->post('company_sort')=='id'){
                $sort_by = 'id' ;
               $order_by = 'desc';
            }
            else{
                $sort_by = $this->input->post('company_sort') ;
                $order_by = 'asc';
            };
           
            $data['title'] = 'Company';
            $data['sort_by']=$this->input->post('company_sort');
           
            
             $get = $_GET;
            $this->limit = 50;
            
            //$companys = $this->company_model->company_list_search_count($sort_by,$order_by,$offset,$this->limit,$get)->result();
            $companys = $this->company_model->get_company_list_all($company_name, $sort_by,$order_by,$offset,$this->limit,$get)->result();
            $data['companys']=  $companys;
            
            
              
              
            
            $data['maincontent'] = $this->load->view('company',$data,true);
            //$data['maincontent'] = $this->load->view('company_list',$data,true);
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }
	
    
	
	public function company_add() {
            
            $user=  $this->session->userdata('user');          
            $user_id =$user->uid; 
			$wp_company_id=$user->company_id;
            
            $data['title'] = 'Add New Company';
            $data['action'] = site_url('company/company_add');
            
            $set_company_no=  $this->company_model->get_company_no();
            
            
				  
		$this->_set_rules();
		
		if ( $this->form_validation->run() === FALSE ) {
                        // print_r('error 1'); 
			$data['maincontent'] = $this->load->view('company_add',$data,true);		
			$this->load->view('includes/header',$data);
			//$this->load->view('includes/sidebar',$data);
			$this->load->view('home',$data);
			$this->load->view('includes/footer',$data);
			
		}else {

                    $post = $this->input->post();
                    $config['upload_path'] = UPLOAD_FILE_PATH_PROJECT;
                    $config['allowed_types'] = '*';

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
		    
                    $file_insert_id = 0;
                    if ($this->upload->do_upload('upload_file')){
                        $upload_data = $this->upload->data();
                        // print_r($upload_data); 
                        // insert data to file table
                        // get latest id from frim table and insert it to loan table
                        $file = array(
                            'filename'=>$upload_data['file_name'],
                            'filetype'=>$upload_data['file_type'],
                            'filesize'=>$upload_data['file_size'],
                            'filepath'=>$upload_data['full_path'],
                            'filename_custom'=>$post['upload_filename'],
                            'created'=>date("Y-m-d H:i:s"),
                            'uid'=>$user_id
                        );
                        $file_insert_id = $this->company_model->file_insert($file);                        
                    }else{
                        //print 'error in file uploading...'; 
                        //print $this->upload->display_errors() ; 
                    } 

			$profile = array(
				'company_no' => $set_company_no+1, 
				'wp_company_id' => $wp_company_id,                               
                //'company_date' => $this->wbs_helper->to_mysql_date($this->input->post('company_date')),
				'company_name' => $this->input->post('company_name'),
				'company_description' => $this->input->post('company_description'),
				'company_status' =>$this->input->post('company_status'),				
				
				'created'=>date("Y-m-d H:i:s"),
				'created_by' => $user_id
			);	
				
			    $id = $this->company_model->company_save($profile);
				// set form input name="id"
				//$this->validation->id = $id;
			
                $this->session->set_flashdata('success-message', 'Company Successfully Added');
				redirect('company/company_list');
			
		} 
	}
	
	public function company_delete($pid){
               
		// delete company
		$this->company_model->delete_company_with_requests_notes($pid);
                 $this->session->set_flashdata('warning-message', 'Company Successfully Removed');
		// redirect to company list page
		redirect('company/company_list');
	}
        public function company_close($pid){
               
		// delete project
		$this->company_model->close_company($pid);
                $this->session->set_flashdata('warning-message', 'Company Successfully Closed.');
		// redirect to project list page
		redirect('company/company_list');
	}
	
	function company_update($pid){
            
                $user=  $this->session->userdata('user');          
                $user_id =$user->uid; 
		
		$data['title'] = 'Update Company';
		$data['action'] = site_url('company/company_update/'.$pid);
                
		
		$this->_set_rules();	
		// run validation
		if ($this->form_validation->run() === FALSE){
			
			$data['company'] = $this->company_model->get_company_detail($pid)->row();
		
		}else{
			// save data                   
                
                            
			$company_update = array(
				'company_no' => $this->input->post('company_no'),
                //'company_date' => $this->wbs_helper->to_mysql_date($this->input->post('company_date')),
				'company_name' => $this->input->post('company_name'),
				'company_description' => $this->input->post('company_description'),
				'company_status' =>$this->input->post('company_status'),
				
				'updated' => date("Y-m-d H:i:s"),
				'updated_by' => $user_id		
			);
			//var_dump($Student);
			$this->company_model->update($pid,$company_update);
			//$data['company'] = (array)$this->company_profile_model->get_by_cid($cid)->row();
            $this->session->set_flashdata('success-message', 'Company Successfully Updated');
			redirect('company/company_detail/'.$pid);
		}
		
		// load view
		$data['maincontent'] = $this->load->view('company_add',$data,true);
		
		$this->load->view('includes/header',$data);
		//$this->load->view('includes/sidebar',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	function _set_rules(){
            //$this->form_validation->set_rules('compname', 'compname', 'trim|required|is_unique[company_profile.compname]');
            //$this->form_validation->set_rules('company_id', 'Company Id', 'callback_company_id');
            $this->form_validation->set_rules('company_name', 'Company Name');
           //$this->form_validation->set_rules('company_name', 'Company Name', 'required|min_length[5]|max_length[12]');
           // $this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[company_profile.email_addr_1]');
        }
		
   
        
    function addprojecrequest($pid=0){
        if ($pid <=0){
         redirect('company/company_list');
        }
   }
   function company_detail($pid=0){
		
		if ($pid <=0){
             redirect('company/company_list');
        }
        
        
        
        $company = $this->company_model->get_company_detail($pid)->row();
        
        //print_r($company);

        //$emp = $this->employee_profile_model->emp_load($salary->eid);

		$data['title'] = 'Company Detail for : '  . $company->company_name;
                $data['company_id']=$pid;
                $data['company_title']=$company->company_name;
		$data['company'] = $company;
		$this->load->library('table');
		$this->table->set_empty("");
                // $cell = array('data' => 'Blue', 'class' => 'highlight', 'colspan' => 2);
                $this->table->add_row('Company Name',$company->company_name); 
                $this->table->add_row('Date Created',date('Y-m-d', strtotime($company->created)));
                $this->table->add_row('Created By',$company->username); 
                $this->table->add_row('Company Status',$company->company_status==1?'Open':'Closed'); 
                       
                //$this->table->add_row('Created', date('d/m/Y', $company->created)); 
                //$this->table->add_row('Updated',date('d/m/Y',$company->updated)); 
                $cell_label = array('data' => 'Company Description', 'class' => 'highlight', 'colspan' => 2);
                $this->table->add_row($cell_label);
                
                $cell_data = array('data' => '<div class="description">'.$company->company_description.'</div>', 'class' => 'highlight', 'colspan' => 2);
                //$this->table->add_row('Company Description'); 
                $this->table->add_row($cell_data); 
                
                $tmpl0 = array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered">' );
                $this->table->set_template($tmpl0);
		$data['table'] = $this->table->generate();
                $this->table->clear();
                
                $user=  $this->session->userdata('user');  
                $user_id =$user->uid; 
                $role_id = $user->rid; 
                
                $company_open_bug= $this->company_model->get_company_open_bug($pid, $user_id, $role_id);
                $open_bug=$company_open_bug->num_rows;
                $open_bug_list= $company_open_bug->result();
                
                
                //$this->table->set_caption('Open Tasks('.$open_bug.')'); 
                $title = array('data' => 'Title', 'class' => 'title', 'width' =>'50%');
                $this->table->set_heading('Task Id', $title, 'Status');
                foreach ($open_bug_list as $open_request){                  

		$this->table->add_row(                       
			$open_request->id,
			'<a href="'.  base_url().'request/request_detail/'.$open_request->id.'?from=company">'.$open_request->request_title.'</a>',
			$open_request->request_status==1?'Open':'Closed'
			); 
		}
              $tmpl = array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" id="company_request" class="table table-striped">' );
              $this->table->set_template($tmpl);
              $data['open_bug_table']=  $this->table->generate();
              $this->table->clear();
              
              
                
                $company_close_request= $this->company_model->get_company_close_request($pid, $user_id, $role_id);
                $close_request=$company_close_request->num_rows;
                $close_request_list= $company_close_request->result();
                
                
                //$this->table->set_caption('Close Tasks('.$close_request.')'); 
                $this->table->set_heading('Task Id', $title, 'Status');
               
                foreach ($close_request_list as $close_request){                  

		$this->table->add_row(                       
			$close_request->id,
                        '<a href="'.  base_url().'request/request_detail/'.$close_request->id.'?from=company">'.$close_request->request_title.'</a>',			
			$close_request->request_status==1?'Open':'Closed'
			); 
		}
              $tmpl2 = array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" id="company_close_request" class="table table-striped">' );
              $this->table->set_template($tmpl2);
              $data['close_request_table']=  $this->table->generate();
              $this->table->clear();
              
              
              //copmany open project
              $company_open_project= $this->company_model->get_company_open_project($pid);
              $company_open_project_num=$company_open_project->num_rows;
              $company_open_project_list= $company_open_project->result();
                
                
                //$this->table->set_caption('Company Project('.$company_project_num.')'); 
                $this->table->set_heading('Project Id', $title, 'Status');
               
                foreach ($company_open_project_list as $project){                  

		$this->table->add_row(                       
			$project->id,
            '<a href="'.  base_url().'project/project_detail/'.$project->id.'">'.$project->project_name.'</a>',			
			$project->project_status==1?'Open':'Closed'
			); 
		}
              $tmpl3 = array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" id="company_project" class="table table-striped">' );
              $this->table->set_template($tmpl3);
              $data['company_open_project_table']=  $this->table->generate();
              $this->table->clear();
              
              
              //copmany close project
              $company_close_project= $this->company_model->get_company_close_project($pid);
              $company_close_project_num=$company_close_project->num_rows;
              $company_close_project_list= $company_close_project->result();
                
                
                //$this->table->set_caption('Company Project('.$company_project_num.')'); 
                $this->table->set_heading('Project Id', $title, 'Status');
               
                foreach ($company_close_project_list as $project){                  

		$this->table->add_row(                       
			$project->id,
            '<a href="'.  base_url().'project/project_detail/'.$project->id.'">'.$project->project_name.'</a>',			
			$project->project_status==1?'Open':'Closed'
			); 
		}
              $tmpl4 = array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" id="company_project" class="table table-striped">' );
              $this->table->set_template($tmpl4);
              $data['company_close_project_table']=  $this->table->generate();
              $this->table->clear();
              
              
              
	      //company notes
	      $prev_notes = $this->company_notes_model->getPriviousCompanyNotes($pid);
              $data['prev_notes'] = $this->notes_image_tmpl($prev_notes); 
		       
		  
                $data['maincontent']=$this->load->view('company_detail',$data,true);
		  
		  $this->load->view('includes/header', $data);
		 // $this->load->view('includes/sidebar', $data);
		  $this->load->view('home',$data);
		  $this->load->view('includes/footer', $data);
    }
    public function notes_image_tmpl($prev_notes){
      
      $user=  $this->session->userdata('user');          
      $user_id =$user->uid; 
      
      $align_class='';
      $tmpl='';
      if(empty($prev_notes)){$tmpl= "<p>No Notes Found</a>";}
      foreach ($prev_notes as $notes) {
           
           
           
        if($notes->notes_by==$user_id)
        {
           $showuser= 'Me'; 
           $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
           $notified_user= $this->company_notes_model->getNotifiedUserName($notes->notify_user_id);
           $align_class = 'right';
           if(!$notes->notes_image_id == null){
               $show_file= $this->company_notes_model->getNotesImage($notes->notes_image_id);
               
               $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div> </div>';
           }else{
               $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body">'.$notes->notes_body.'</div><div style=""><span class="time-left1">'.$creation_time.'</span>'.$notified_user.'</div> </div>';
           }
           
           
           $tmpl .= '<div class="clear"></div>';

        }else{
            $showuser= $notes->username; 
            $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
            $notified_user= $this->company_notes_model->getNotifiedUserName($notes->notify_user_id);
            $align_class ='left';
            if(!$notes->notes_image_id == null){
                $show_file= $this->company_notes_model->getNotesImage($notes->notes_image_id);
                $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div> <div style="float: left;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div> </div>';
            }
            else{
                $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div>  <div style="float: left;" class="notes_body">'.$notes->notes_body.'</div><div style=""><span class="time-right1">'.$creation_time.'</span>'.$notified_user.'</div></div>'; 
            }
            
            $tmpl .= '<div class="clear"></div>';
        }
       } 
       return $tmpl;
      
  }
	
	
   
	
         
        
   
    
	
}
