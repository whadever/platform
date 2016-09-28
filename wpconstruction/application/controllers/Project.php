<?php 
class Project extends CI_Controller {
	
	private $limit = 10;
	
	function __construct() {
		
		parent::__construct();
		
		
		
		$this->load->model('project_model','',TRUE);
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
        
	}
        
        public function index(){
            $data['title'] = 'Project';
            $data['maincontent'] = $this->load->view('project',$data,true);
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }
        
        public function project_list($sort_by = 'id', $order_by = 'desc', $offset = 0){
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
           
            $data['title'] = 'Project';
            $data['sort_by']=$this->input->post('project_sort');
            $data['project_search_name']=$this->input->post('project_name');
           
            
             $get = $_GET;
            $this->limit = 50;
            //$projects = $this->project_model->project_list_search_count($project_name, $sort_by,$order_by,$offset,$this->limit,$get)->result();
            $projects = $this->project_model->get_project_list($project_name, $sort_by,$order_by,$offset,$this->limit,$get)->result();
            $data['projects']=  $projects;
            
            $data['maincontent'] = $this->load->view('project',$data,true);
            //$data['maincontent'] = $this->load->view('project_list',$data,true);
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }
	
    
	
	public function project_add($cid=0) {
            
            $user=  $this->session->userdata('user');          
            $user_id =$user->uid; 
             if ($cid > 0){
                $data['company_id']=$cid;
            }
            $data['title'] = 'Add New Project';
            $data['action'] = site_url('project/project_add/'.$cid);            
            $set_project_no=  $this->project_model->get_project_no();
            $this->_set_rules();
		
            if ( $this->form_validation->run() === FALSE ) {
                    // print_r('error 1'); 
                    $data['maincontent'] = $this->load->view('project_add',$data,true);		
                    $this->load->view('includes/header',$data);
                    //$this->load->view('includes/sidebar',$data);
                    $this->load->view('home',$data);
                    $this->load->view('includes/footer',$data);

            }else {
                //$post = $this->input->post();  
				$select_manager = $this->input->post('assign_manager_id');            
                if($select_manager == ''){$select_manager_id=0;}
                else{ $select_manager_id = implode(",", $select_manager);}

                $select_developer = $this->input->post('assign_developer_id');            
                if($select_developer == ''){$select_developer_id=0;}
                else{ $select_developer_id = implode(",", $select_developer);}

              
                $profile = array(
                    'project_no' => $set_project_no+1,
                    'company_id' =>$this->input->post('company_id'),
                    'project_name' => $this->input->post('project_name'),
                    'project_date' => $this->wbs_helper->to_mysql_date($this->input->post('project_date')),
                    //'project_date' => $this->input->post('project_date'),
                    'project_description' => $this->input->post('project_description'),
                    'project_status' =>$this->input->post('project_status'),
					'assign_manager_id' => $select_manager_id,
                    'assign_developer_id' => $select_developer_id,				
                    'created'=>date("Y-m-d H:i:s"),
                    'created_by' => $user_id
               );	
				
                $this->project_model->project_save($profile);
                $this->session->set_flashdata('success-message', 'Project Successfully Added.');
                if ($cid > 0){
                     redirect('company/company_detail/'.$cid);
                }else{
                    redirect('project/project_list');
                }	
	} 
    }
	
	public function project_delete($pid){
               
		// delete project
		$this->project_model->delete_project_with_requests_notes($pid);
                $this->session->set_flashdata('warning-message', 'Project Successfully Removed.');
		// redirect to project list page
		redirect('project/project_list');
	}
        public function project_close($pid){
               
		// delete project
		$this->project_model->close_project($pid);
                $this->session->set_flashdata('warning-message', 'Project Successfully Closed.');
		// redirect to project list page
		redirect('project/project_list');
	}
	
	function project_update($pid){
            
                $user=  $this->session->userdata('user');          
                $user_id =$user->uid; 		
		$data['title'] = 'Update Project';
		$data['action'] = site_url('project/project_update/'.$pid);        
		
		$this->_set_rules();	
		// run validation
		if ($this->form_validation->run() === FALSE){
			
			$data['project'] = $this->project_model->get_project_detail($pid)->row();
		
		}else{
			// save data
			$project_update = array(
				'project_no' => $this->input->post('project_no'),
                'company_id' =>$this->input->post('company_id'),
				'project_name' => $this->input->post('project_name'),
                'project_date' => $this->wbs_helper->to_mysql_date($this->input->post('project_date')),
                //'project_date' => $this->input->post('project_date'),                            
				'project_description' => $this->input->post('project_description'),
				'project_status' =>$this->input->post('project_status'),
				'assign_manager_id' => implode(",", $this->input->post('assign_manager_id')),
                'assign_developer_id' => implode(",", $this->input->post('assign_developer_id')),				
				'updated' => date("Y-m-d H:i:s"),
				'updated_by' => $user_id		
			);
                        //print_r($project_update); exit;
			
			$this->project_model->update_project($pid, $project_update);
			//$data['project'] = (array)$this->project_profile_model->get_by_cid($cid)->row();
                        $this->session->set_flashdata('success-message', 'Project Successfully Updated.');
			redirect('project/project_list');
		}
		
		// load view
		$data['maincontent'] = $this->load->view('project_add',$data,true);
		
		$this->load->view('includes/header',$data);
		//$this->load->view('includes/sidebar',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}
	
	function _set_rules(){
            //$this->form_validation->set_rules('compname', 'compname', 'trim|required|is_unique[project_profile.compname]');
            //$this->form_validation->set_rules('project_id', 'Project Id', 'callback_project_id');
            $this->form_validation->set_rules('project_name', 'Project Name');
           //$this->form_validation->set_rules('project_name', 'Project Name', 'required|min_length[5]|max_length[12]');
           // $this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[project_profile.email_addr_1]');
        }
		
   
        
    function addprojecrequest($pid=0){
        if ($pid <=0){
         redirect('project/project_list');
        }
   }

   function project_detail($pid=0){
		
	if ($pid <=0){
             redirect('project/project_list');
        }
        
        $project = $this->project_model->get_project_detail($pid)->row();
		$assign_manager =   $this->project_model->get_assign_manager($project->assign_manager_id);
        $assign_developer = $this->project_model->get_assign_developer($project->assign_developer_id);
        
        //print_r($project);

        //$emp = $this->employee_profile_model->emp_load($salary->eid);

        $data['title'] = 'Project Detail for : '  . $project->project_name;
        $data['project_id']=$pid;
        $data['project_title'] = $project->project_name;
        $data['company_id']=$project->company_id;
        $data['project'] = $project;
        $this->load->library('table');
        $this->table->set_empty("");
        // $cell = array('data' => 'Blue', 'class' => 'highlight', 'colspan' => 2);
        $this->table->add_row('Project Name',$project->project_name); 
        
        $this->table->add_row('Project Company',$project->company_name); 
		$this->table->add_row('Assign Manager', $assign_manager); 
        $this->table->add_row('Assign Contractor', $assign_developer);  
        
        $this->table->add_row('Project Create Date',date('Y-m-d', strtotime($project->created)));       
        //$this->table->add_row('Created', date('d/m/Y', $project->created)); 
        //$this->table->add_row('Updated',date('d/m/Y',$project->updated)); 
        $this->table->add_row('Project Created By',$project->username);  
        $this->table->add_row('Project Status',$project->project_status==1?'Open':'Closed'); 
        //$this->table->add_row('Project Description',$project->project_description); 
        
        $cell_label = array('data' => 'Project Description', 'class' => 'highlight', 'colspan' => 2);
        $this->table->add_row($cell_label);
        $cell_data = array('data' => '<div class="description">'.$project->project_description.'</div>', 'class' => '', 'colspan' => 2);        
        $this->table->add_row($cell_data);
        
        $tmpl_project= array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="table table-bordered">' );
        $this->table->set_template($tmpl_project);

        $data['table'] = $this->table->generate();
        $this->table->clear();
                
        $user=  $this->session->userdata('user');  
        $user_id =$user->uid; 
        $role_id = $user->rid;        
                
        $project_open_bug= $this->project_model->get_project_open_bug($pid, $user_id, $role_id);
        $data['count_open_bug']=$project_open_bug->num_rows;
        $open_bug_list= $project_open_bug->result();


        //$this->table->set_caption('Open Tasks('.$open_bug.')'); 
        $title = array('data' => 'Title', 'class' => 'title', 'width' =>'50%');
        $this->table->set_heading('Task No', $title, 'Status');
        foreach ($open_bug_list as $open_request){                  

        $this->table->add_row(                       
                $open_request->request_no,
                '<a href="'.  base_url().'request/request_detail/'.$open_request->id.'?from=project">'.$open_request->request_title.'</a>',
                $open_request->request_status==1?'Open':'Closed'
                ); 
        }
        $tmpl = array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" id="project_open_bug_table" class="table table-striped">' );
        $this->table->set_template($tmpl);
        $data['open_bug_table']=  $this->table->generate();
        $this->table->clear();

        $project_close_request= $this->project_model->get_project_close_request($pid, $user_id, $role_id);
        $data['count_close_bug']=$project_close_request->num_rows;
        $close_request_list= $project_close_request->result();


        //$this->table->set_caption('Close Tasks('.$close_request.')'); 
        $this->table->set_heading('Task No', $title, 'Status');

        foreach ($close_request_list as $close_request){                  

        $this->table->add_row(                       
                $close_request->request_no,
                '<a href="'.  base_url().'request/request_detail/'.$close_request->id.'?from=project">'.$close_request->request_title.'</a>',			
                $close_request->request_status==1?'Open':'Closed'
                ); 
        }
        $tmpl2 = array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" id="project_close_bug_table" class="table table-striped">' );
        $this->table->set_template($tmpl2);
        $data['close_request_table']=  $this->table->generate();
        $this->table->clear();
	      
	       
	$prev_notes = $this->project_notes_model->getPriviousProjectNotes($pid);
        $data['prev_notes'] = $this->notes_image_tmpl($prev_notes);	       
		  
        $data['maincontent']=$this->load->view('project_detail',$data,true);

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
           $notified_user= $this->project_notes_model->getNotifiedUserName($notes->notify_user_id);
           $align_class = 'right';
           if(!$notes->notes_image_id == null){
               $show_file= $this->project_notes_model->getNotesImage($notes->notes_image_id);
               
               $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div> </div>';
           }else{
               $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body">'.$notes->notes_body.'</div><div style=""><span class="time-left1">'.$creation_time.'</span>'.$notified_user.'</div> </div>';
           }
           
           
           $tmpl .= '<div class="clear"></div>';

        }else{
            $showuser= $notes->username; 
            $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
            $notified_user= $this->project_notes_model->getNotifiedUserName($notes->notify_user_id);
            $align_class ='left';
            if(!$notes->notes_image_id == null){
                $show_file= $this->project_notes_model->getNotesImage($notes->notes_image_id);
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
  
  
  public function project_hours($pid){
        
        $data['title'] = 'Project Hours'; 
        $data['projects_hours'] = $this->project_model->get_project_hours($pid)->result();
        $data['projects_total_hours'] = $this->project_model->get_project_total_hours($pid)->row();
        
        $data['maincontent'] = $this->load->view('project_hours',$data,true);
        //$data['maincontent'] = $this->load->view('project_list',$data,true);
		
        $this->load->view('includes/header',$data);
        //$this->load->view('includes/sidebar',$data);
        $this->load->view('home',$data);
        $this->load->view('includes/footer',$data);
        
    }
	
	
   
	
         
        
   
    
	
}
