<?php 
class Stage extends CI_Controller {
	
	private $limit = 10;
	
	function __construct() {
		
		parent::__construct();
		
		$this->load->helper(array('form', 'url', 'file', 'html', 'email'));
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->model('stage_model','',TRUE);
		$this->load->library('Wbs_helper');
		date_default_timezone_set("NZ");
     
		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}   


	}
        
    public function index(){
            $data['title'] = 'Stage';
            $data['maincontent'] = $this->load->view('stage/stages',$data,true);
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
    }    
	
    public function stage_document_search($development_id, $stage_id, $search)
	{
		$this->stage_model->stage_document_search($development_id, $stage_id, $search);
	}
	
	
	function _set_rules(){
            //$this->form_validation->set_rules('compname', 'compname', 'trim|required|is_unique[project_profile.compname]');
            $this->form_validation->set_rules('project_id', 'Project Id', 'callback_project_id');
            $this->form_validation->set_rules('project_name', 'Project Name');
           //$this->form_validation->set_rules('project_name', 'Project Name', 'required|min_length[5]|max_length[12]');
           // $this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[project_profile.email_addr_1]');
        }
		
   public function stage_add() {        
        
		$user=  $this->session->userdata('user');          
        $user_id =$user->uid;

		$config['upload_path'] = UPLOAD_STAGE_FEATURE_IMAGE_PATH;
        $config['allowed_types'] = '*';
		$config['max_size'] = '100000KB';
		$config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$photo_insert_id = 0;
			if ($this->upload->do_upload('feature_photo')){
	            $upload_data = $this->upload->data();
	            
	            $document = array(
	                
	                'filename'=>$upload_data['file_name'],
	                'filetype'=>$upload_data['file_type'],
	                'filesize'=>$upload_data['file_size'],
	                'filepath'=>$upload_data['full_path'],                
	                'created'=>date("Y-m-d H:i:s"),
	                'uid'=>$user_id
	            );
	            $photo_insert_id = $this->stage_model->stage_feature_photo_insert($document); 	             
	
	        }

			$development_id = $post['development_id'];
			$stage_no = $post['stage_no'];

			$stage_add = array(
				'development_id' => $development_id,
				'stage_no' => $stage_no,
				'stage_name' => $post['stage_name'],
				'number_of_lots' => $post['number_of_lots'],	
				'under_construction' => $post['under_construction'],
				'awaiting_construction' => $post['awaiting_construction'],
				'total_homes_completed' => $post['total_homes_completed'],
				'construction_start_date' => $this->wbs_helper->to_mysql_date($post['construction_start_date']),
				'maintainence_bond_date' => $this->wbs_helper->to_mysql_date($post['maintainence_bond_date']),
				'fid' => $photo_insert_id,
				'created' => date("Y-m-d"),
				'created_by' => $user_id

		    );	
			
		    $this->stage_model->stage_add($stage_add);

			redirect('stage/stage_info/'.$development_id.'/'.$stage_no);
			
		} 
		
	}

	public function stage_update($stage_id) {        
        
		$user=  $this->session->userdata('user');          
        $user_id =$user->uid;

		$config['upload_path'] = UPLOAD_STAGE_FEATURE_IMAGE_PATH;
        $config['allowed_types'] = '*';
		$config['max_size'] = '100000KB';
		$config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$photo_insert_id = $post['fid'];
			if ($this->upload->do_upload('feature_photo')){
	            $upload_data = $this->upload->data();
	            
	            $document = array(
	                
	                'filename'=>$upload_data['file_name'],
	                'filetype'=>$upload_data['file_type'],
	                'filesize'=>$upload_data['file_size'],
	                'filepath'=>$upload_data['full_path'],                
	                'created'=>date("Y-m-d H:i:s"),
	                'uid'=>$user_id
	            );
	            $photo_insert_id = $this->stage_model->stage_feature_photo_insert($document); 	             
	
	        }

			$development_id = $post['development_id'];
			$stage_no = $post['stage_no'];

			$stage_update = array(
				'development_id' => $development_id,
				'stage_no' => $stage_no,
				'stage_name' => $post['stage_name'],
				'number_of_lots' => $post['number_of_lots'],	
				'under_construction' => $post['under_construction'],
				'awaiting_construction' => $post['awaiting_construction'],
				'total_homes_completed' => $post['total_homes_completed'],
				'construction_start_date' => $this->wbs_helper->to_mysql_date($post['construction_start_date']),
				'maintainence_bond_date' => $this->wbs_helper->to_mysql_date($post['maintainence_bond_date']),
				'fid' => $photo_insert_id,
				'updated_by' => $user_id

		    );	
			
		    $this->stage_model->stage_update($stage_id,$stage_update);

			redirect('stage/stage_info/'.$development_id.'/'.$stage_no);
			
		} 
		
	}

	public function milestone_delete($did,$stage_no,$mid){
		$this->stage_model->milestone_delete($mid);
		// redirect to project list page
		redirect('stage/stage_info/'.$did.'/'.$stage_no);
	}

   function stage_info($did=0, $sid=0){
		
		if ($did <=0){
             redirect('developments/developments_list');
        }
        
        $data['development_id']=$did;
        $data['stage_id']=$sid;
        
        $development = $this->stage_model->get_development_detail($did)->row();
        
        $stage = $this->stage_model->get_stage_detail($did,$sid)->row();
		$data['stage_detail'] = $stage;
		if(isset($stage->fid)){
		$data['stage_feature_photo'] = $this->stage_model->get_stage_feature_photo($stage->fid)->row();
		}
        //$emp = $this->employee_profile_model->emp_load($salary->eid);

		$stage_milestone = $this->stage_model->get_stage_milestone_detail($did,$sid)->result();
		$data['milestone_details'] = $stage_milestone;
		
		$data['title'] = $development->development_name;
        $data['number_of_stages'] = $development->number_of_stages;
		$data['development_details'] = $development;
		$this->load->library('table');
		$this->table->set_empty("");
                // $cell = array('data' => 'Blue', 'class' => 'coloum-highlight');
                $cell1 = array('data' => 'Stage Name:', 'class' => 'coloum-highlight');                             
                //$cell2 = array('data' => 'Stage Name:', 'class' => 'coloum-highlight');              
                $cell3 = array('data' => 'Number of Lots:', 'class' => 'coloum-highlight');  
                $cell4 = array('data' => 'Under Construction:', 'class' => 'coloum-highlight');
                $cell5 = array('data' => 'Awaiting Construction:', 'class' => 'coloum-highlight');
                $cell6 = array('data' => 'Total Homes Completed:', 'class' => 'coloum-highlight');
                $cell7 = array('data' => 'Construction Start Date:', 'class' => 'coloum-highlight');
                $cell8 = array('data' => 'Maintainence Bond Date:', 'class' => 'coloum-highlight');
                
                $this->table->add_row($cell1, isset($stage->stage_name)?$stage->stage_name:''); 
                //$this->table->add_row($cell2, 'Roading'); 
                $this->table->add_row('', ''); 
                $this->table->add_row($cell3, isset($stage->number_of_lots)?$stage->number_of_lots:'');                 
                $this->table->add_row($cell4, isset($stage->under_construction)?$stage->under_construction:'');                 
                $this->table->add_row($cell5, isset($stage->awaiting_construction)?$stage->awaiting_construction:'');                
                $this->table->add_row($cell6, isset($stage->total_homes_completed)?$stage->total_homes_completed:'');                
                $this->table->add_row('', '');
                if(isset($stage->construction_start_date)){
					if($stage->construction_start_date > '0000-00-00'){
						$date = $this->wbs_helper->to_report_date($stage->construction_start_date);
					}

				}else{
						$date='';
				}

				if(isset($stage->maintainence_bond_date)){
					if($stage->maintainence_bond_date > '0000-00-00'){
						$date1 = $this->wbs_helper->to_report_date($stage->maintainence_bond_date);
					}

				}else{
					$date1 ='';
				}

                $this->table->add_row($cell7, $date);  
                $this->table->add_row($cell8, $date1);  
                
				$this->table->add_row('', '<a style="" id="add-ailestone" href="#AddNewMilestone" data-toggle="modal" role="button">Add New Milestone</a>');

                $i = 1;
				foreach($stage_milestone as $st_milestone){
					$milestone = array('data' => "Milestone $i : ", 'class' => 'coloum-highlight');
					$this->table->add_row($milestone, $st_milestone->milestone_title.' - '.$this->wbs_helper->to_report_date($st_milestone->milestone_date).'<a style="float: right; margin-left:10px;" id="add-ailestone" href="#EditMilestone_'.$st_milestone->id.'" data-toggle="modal" role="button"><img alt="Edit Milestone" src="'.base_url().'icon/icon_edit.png" width="20" height="" /></a>');
					
					$i++;
				}


                //$this->table->add_row('Project Status',$project->project_status==1?'Open':'Closed'); 
               // $this->table->add_row('Project create Date',date('d/m/Y', strtotime($project->created)));       
                //$this->table->add_row('Created', date('d/m/Y', $project->created)); 
                //$this->table->add_row('Updated',date('d/m/Y',$project->updated)); 
               // $this->table->add_row('Created by',$project->created_by);                                       

		$data['table'] = $this->table->generate();
                $this->table->clear();
                
                
		       
		$data['stage_sub_sidebar']=$this->load->view('stage/stage_sub_sidebar',$data,true);  
                $data['stage_content']=$this->load->view('stage/stage_detail',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('stage/stage_sidebar',$data);
		  $this->load->view('stage/stage_home',$data);
		  $this->load->view('includes/footer', $data);
    }
    
	public function stage_documents($pid=0, $sid=0){
		$development = $this->stage_model->get_development_detail($pid)->row();
		$data['title'] = $development->development_name;

		$data['number_of_stages'] = $this->stage_model->get_development_number_of_stage($pid);
        $data['development_id']=$pid;
        $data['stage_id']=$sid;
        
        
        $data['documents'] = $this->stage_model->getStageDocuments($pid, $sid)->result();
              
        $data['stage_documents'] = $this->stage_model->get_stage_document_details($pid, $sid)->result();        
		       
		$data['stage_notes'] = $this->stage_model->get_stage_note_detail($pid, $sid)->result();

        $data['stage_sub_sidebar']=$this->load->view('stage/stage_sub_sidebar',$data,true);  
       	$data['stage_content']=$this->load->view('stage/stage_documents',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('stage/stage_sidebar',$data);
		  $this->load->view('stage/stage_home',$data);
		  $this->load->view('includes/footer', $data);
    }
    
	public function stage_documents_bycategory($pid=0, $sid=0, $cid=0){
        $data['title'] = 'Stage Documents'; 
        $data['development_id']=$pid;
        $data['stage_id']=$sid;
        $data['category_id']=$cid;
        $data['number_of_stages'] = $this->stage_model->get_development_number_of_stage($pid);
        //$search_notes= $this->input->post('search_notes');
        
        $data['documents'] = $this->stage_model->get_stage_documents_bycategory($pid, $sid, $cid)->result();
        $data['stage_documents'] = $this->stage_model->get_others_stage_documents_bycategory($pid, $sid, $cid)->result();

        $data['stage_notes'] = $this->stage_model->get_stage_note_detail($pid, $sid)->result();

        $data['stage_sub_sidebar']=$this->load->view('stage/stage_sub_sidebar',$data,true);  
        $data['stage_content']=$this->load->view('stage/stage_documents',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('stage/stage_sidebar',$data);
		  $this->load->view('stage/stage_home',$data);
		  $this->load->view('includes/footer', $data); 
    }

	public function save_stage_document($pid=0, $sid=0){
    	
    	$user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
         
        $post = $this->input->post();
       
        
        $config['upload_path'] = UPLOAD_FILE_PATH_STAGE_DOCUMENT;
        //$config['upload_path'] = UPLOAD_DEVELOPMENT_IMAGE_PATH;
        
        $config['allowed_types'] = '*';
		$config['max_size'] = '100000KB';
		$config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
       	
		    
                  
        if ($this->upload->do_upload('upload_document')){
	        $upload_data = $this->upload->data();	        
	        // insert data to file table
	        // get latest id from frim table and insert it to loan table
	        $document = array(
	            'development_id'=>$pid,
	            'stage_no'=>$sid,               
	        	'filename'=>$upload_data['file_name'],
	            'filetype'=>$upload_data['file_type'],
	            'filesize'=>$upload_data['file_size'],
	            'filepath'=>$upload_data['full_path'],
	            'filename_custom'=>$post['file_title'],
	            'created'=>time(),
	            'uid'=>$user_id,
				'notify_user'=>$post['notify_user']
	        );

            $this->stage_model->stage_document_insert($document);            
	          	                                     
		}else{
        	print 'Error in file uploading...'; 
           	print $this->upload->display_errors() ; 
        } 
        redirect('stage/stage_documents/'.$pid.'/'.$sid, 'refresh'); 
    }
    
	public function stage_document_detail($document_id){
        
        $stage_document_detail = $this->stage_model->get_document_detail($document_id)->row();
        
		echo '<object data="'.base_url().'uploads/stage/documents/'.$stage_document_detail->filename.'" type="application/pdf" width="100%" height="100%"><p>It appears you dont have a PDF plugin for this browser<br>You can <a href="'.base_url().'uploads/stage/documents/'.$stage_document_detail->filename.'">click here to download the PDF file.</a></p> </object>';
         
    }


 
    public function stage_photos($pid=0, $sid=0){
        
        $development = $this->stage_model->get_development_detail($pid)->row();
        $data['title'] = $development->development_name; 

        $data['development_id']=$pid;
        $data['stage_id']=$sid;
        $data['photos'] = $this->stage_model->getStagePhotos($pid, $sid)->result();
        $data['number_of_stages'] = $this->stage_model->get_development_number_of_stage($pid);
        
        $data['stage_sub_sidebar']=$this->load->view('stage/stage_sub_sidebar',$data,true); 
        $data['stage_content']=$this->load->view('stage/stage_photos',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('stage/stage_sidebar',$data);
		  $this->load->view('stage/stage_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }
    public function upload_stage_photo($pid=0, $sid=0){
        
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
        
         $config['upload_path'] = UPLOAD_DEVELOPMENT_IMAGE_PATH;
		 $config['max_size'] = '100000KB';
         $config['allowed_types'] = '*';
		 $config['overwrite'] = TRUE;
         $this->load->library('upload', $config);
         $this->upload->initialize($config);
         
         if ($this->upload->do_upload('photoimg')){
            $upload_data = $this->upload->data();
            echo '<img width="245" height="245" src="'.base_url().'uploads/development/'.$upload_data['file_name'].'"/>';
            //print_r($upload_data); 
            $document = array(
                'project_id'=>$pid,
            	'stage_no'=>$sid,
                'filename'=>$upload_data['file_name'],
                'filetype'=>$upload_data['file_type'],
                'filesize'=>$upload_data['file_size'],
                'filepath'=>$upload_data['full_path'],
                //'filename_custom'=>$post['note_image'],
                'created'=>strtotime(date("Y-m-d H:i:s")),                
                'uid'=>$user_id
            );
            $photo_insert_id = $this->stage_model->stage_photo_insert($document); 
             
            echo '<input type="hidden" id="development_photo_id" value="'.$photo_insert_id.'" />';
             

        }else{
            echo 'Error in file uploading...'; 
           print $this->upload->display_errors() ;  
        } 
        
    }
    public function save_stage_photo($pid=0, $sid=0){
        
        
        $data['title'] = 'Stage Photos'; 
        $data['development_id']=$pid;  
        $data['stage_id']=$sid;        
        $post = $this->input->post();     
        
        $photo_insert_id = $this->input->post('photo_insert_id');   
        $photo_info = array(                        
                       
                        'photo_caption' => $this->input->post('photo_caption'),
                        'photo_category' =>$this->input->post('photo_category')
                   );       
       
				
        $this->stage_model->save_stage_photo_info($photo_insert_id, $photo_info);       
              
        redirect('stage/stage_photos/'.$pid.'/'.$sid, 'refresh');          
        
    }
    
    public function stage_overview($pid=0, $sid=0){
        $development = $this->stage_model->get_development_detail($pid)->row();
        $data['title'] = $development->development_name; 
        $data['development_id']=$pid;
        $data['stage_id']=$sid;
        
        $data['number_of_stages'] = $this->stage_model->get_development_number_of_stage($pid);
        
        //get_development_stage_phase_task_info
        $data['task_info'] = $this->stage_model->get_development_stage_task_list($pid,$sid)->result();
        $data['phase_info'] = $this->stage_model->get_development_stage_phase_list($pid,$sid)->result();
         
        
        $data['stage_sub_sidebar']=$this->load->view('stage/stage_sub_sidebar',$data,true); 
        $data['stage_content']=$this->load->view('stage/stage_overview',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('stage/stage_sidebar',$data);
		  $this->load->view('stage/stage_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }
    public function project_plan($pid=0, $sid=0){
        
        $data['title'] = 'Project Plan'; 
        $data['development_id']=$pid;
        $data['stage_id']=$sid;
        
        $data['number_of_stages'] = $this->stage_model->get_development_number_of_stage($pid);
        $data['task_info'] = $this->stage_model->get_development_stage_task_list($pid,$sid)->result();
		
               
        
        $data['stage_sub_sidebar']=$this->load->view('stage/stage_sub_sidebar',$data,true); 
        $data['stage_content']=$this->load->view('stage/project_plan',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('stage/stage_sidebar',$data);
		  $this->load->view('stage/stage_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }
    
    public function plan_vs_actual($pid=0, $sid=0){
        
        $development = $this->stage_model->get_development_detail($pid)->row();
		$data['development_details'] = $development;
        $data['title'] = $development->development_name; 
 
        $data['development_id']=$pid;
        $data['stage_id']=$sid;
        
        $data['number_of_stages'] = $this->stage_model->get_development_number_of_stage($pid);
        $data['task_info'] = $this->stage_model->get_development_stage_task_list($pid,$sid)->result();
        $data['phase_info'] = $this->stage_model->get_development_stage_phase_list($pid,$sid)->result();
               
        
        $data['stage_sub_sidebar']=$this->load->view('stage/stage_sub_sidebar',$data,true); 
        $data['stage_content']=$this->load->view('stage/plan_vs_actual',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  //$this->load->view('stage/stage_sidebar',$data);
		  $this->load->view('stage/stage_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }
    
    

    public function phases_list($pid=0, $sid=0){
        
        $development = $this->stage_model->get_development_detail($pid)->row();
        $data['title'] = $development->development_name; 

        $data['development_id']=$pid;
        $data['stage_id']=$sid;
        $data['stages_no'] = $this->stage_model->get_stage_list($pid)->result();

		$data['phase_info'] = $this->stage_model->get_development_stage_phase_list($pid,$sid)->result();
 

         //$data['number_of_stages'] = $this->stage_model->get_stage_list($pid)->result();
        //$data['phase_info'] = $this->project->model->get_phase_info($pid)->result();
        $data['number_of_stages'] = $this->stage_model->get_development_number_of_stage($pid);
        
        $data['stage_sub_sidebar']=$this->load->view('stage/stage_sub_sidebar',$data,true); 
        $data['stage_content']=$this->load->view('stage/stage_phases',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('stage/stage_sidebar',$data);
		  $this->load->view('stage/stage_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }
public function print_stage($pid=0, $sid=0)
{
 

    $data['development_id']=$pid;
    $data['stage_id']=$sid;

    $stage_info = $this->stage_model->get_stage_detail($pid)->row();       

    $data['title'] = 'Stage'.$sid;  


  $this->load->view('stage/stage_print',$data);

 
 }
 
 public function email_stage($pid=0, $sid=0){

 
    
    
    $to= 'alimuls@gmail.com'; 
    $from= 'mamunjava@gmail.com'; 
    $cc= 'nurulku02@gmail.com'; 
    $subject = 'Developments Info';

    $headers = "From: " . $from . "\r\n";
    $headers .= "Reply-To: ". $from . "\r\n";
    $headers .= "CC:". $cc ."\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
        

    $data['development_id']=$pid;  
    $data['stage_id']=$sid;    
    //$development = $this->developments_infromation($pid);    
    
     
   
       
  
    $html = 'Stage '.$sid;
    $html .='<img width="270" height="250" src="'.base_url().'images/pms_home.png"/>';   
    
    
    $mail_status= mail($to, $subject, $html, $headers);
    
    
    if($mail_status){
        echo 'Mail Sent successfully.';
    }else{
        echo 'Mail did not Sent. Try again some later.';
    }
        
    //redirect('developments/development_detail/'.$pid);


 
 }

        
        
    public function stage_infromation($tid){
                
                
        $stage = $this->stage_model->get_stage_details($tid)->row();  

		$feature_photo_id= $stage->fid;
    	$feature_photo= $this->stage_model->get_stage_feature_photo($feature_photo_id)->row();
    	$photo='<img width="" height="" src="'.base_url().'uploads/stage/'.$feature_photo->filename.'"/>';     

        $this->load->library('table');
        $this->table->set_empty("");
		$this->table->set_caption('<h1>Stage Information</h1>');

        $this->table->add_row('Stage Name',$stage->stage_name); 
        $this->table->add_row('Number of Lots', $stage->number_of_lots);  
        $this->table->add_row('Under Construction', $stage->under_construction);  
        $this->table->add_row('Awaiting Construction', $stage->awaiting_construction);  
        $this->table->add_row('Total Homes Completed', $stage->total_homes_completed);  
        $this->table->add_row('Construction Start Date', $this->wbs_helper->to_report_date($stage->construction_start_date));  
        $this->table->add_row('Maintainence Bond Date', $this->wbs_helper->to_report_date($stage->maintainence_bond_date)); 

		$this->table->add_row('', '');
        $photo_title = array('data' => '<h3>Feature Photo</h3>', 'class' => '', 'colspan' => 2);
        $this->table->add_row($photo_title);
        $photo_row = array('data' => $photo, 'class' => '', 'colspan' => 2);
        $this->table->add_row($photo_row);

         $data = $this->table->generate();
         $this->table->clear();
         return $data;                      

  	
                
    }

	public function update_task_status($task_id,$status)
	{

		$this->stage_model->update_status($task_id,$status);
	}
	
   
	public function pdf_stage($sid){
             
         $a = define ('PDF_HEADER_STRING1', '');
         $b = define ('PDF_HEADER_TITLE1', 'Horncastle Developments');
         //$all_employees = $this->employee_model->employee_list_print();
         $data= $this->stage_infromation($sid);
         $this->wbs_helper->make_list_pdf($data, $a, $b);
         //print_r($data);
 
    }
         
    public function stage_print($sid)
	{
	
	    $stage = $this->stage_model->get_stage_details($sid)->row(); 
	
	    $feature_photo_id= $stage->fid;
	    $data['feature_photo'] = $this->stage_model->get_stage_feature_photo($feature_photo_id)->row();

	    $this->load->library('table');
	    $this->table->set_empty("");
	
	 	$this->table->add_row('Stage Name : ',$stage->stage_name); 
        $this->table->add_row('Number of Lots : ', $stage->number_of_lots);  
        $this->table->add_row('Under Construction : ', $stage->under_construction);  
        $this->table->add_row('Awaiting Construction : ', $stage->awaiting_construction);  
        $this->table->add_row('Total Homes Completed : ', $stage->total_homes_completed);  
        $this->table->add_row('Construction Start Date : ', $this->wbs_helper->to_report_date($stage->construction_start_date));  
        $this->table->add_row('Maintainence Bond Date : ', $this->wbs_helper->to_report_date($stage->maintainence_bond_date)); 
	                           

	  	$data['table'] = $this->table->generate();
	  	$this->table->clear();
	
	
	
	  	$this->load->view('stage/stage_print',$data);
	
	 
	}  
   
	public function send_stage_photo_message()
	{
		$photo_id = $this->input->post('photo_id');
		$photo_dev_id = $this->input->post('photo_dev_id');
		$stage_no = $this->input->post('stage_no');
		
		$user=  $this->session->userdata('user'); 
		///$user_id =$user->uid; 
		$user_name = $user->name; 
		$user_mail = $user->email; 
		
		$photo_detail= $this->stage_model->get_photo_author($photo_id)->row(); 
		$photo_author_email= $photo_detail->useremail;
		$image= $photo_detail->filename;

		$mail_body = 'Your Photo : <img src="'.base_url().'uploads/development/'.$image.'" /><br />';
		$mail_body .= 'Photo Caption : '.$photo_detail->photo_caption.'<br />';
		$mail_body .= 'User Reply <br />'.$this->input->post('photo_message');
		
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;		
		$this->email->initialize($config);


		$this->email->from($user_mail, $user_name);
		$this->email->to($photo_author_email); 
		
		$this->email->subject('Reply On '.$image);

		$this->email->message($mail_body);
		
		if ( ! $this->email->send())
		{
		    $email_send=0;
		}
		else{ $email_send=1; }

		//echo $this->email->print_debugger();
		redirect('stage/stage_photos/'.$photo_dev_id.'/'.$stage_no.'?sent_email='.$email_send);
		
	}    
	

	public function pdf_stage_photo($photo_id){
             
        $a = define ('PDF_HEADER_STRING1', '');
        $b = define ('PDF_HEADER_TITLE1', 'Horncastle Developments');
            
        $photo = $this->stage_model->getStagePhotoDetail($photo_id);   
        $photo_image='<img width="" height="" src="'.base_url().'uploads/development/'.$photo->filename.'"/>';  
	
	    $this->load->library('table');
	    $this->table->set_empty(""); 
	  
	    $this->table->add_row('Photo Name',$photo->filename); 
	    $this->table->add_row('Uploaded By', $photo->username);  
	    $this->table->add_row('Uploaded Date', date('d-m-Y', $photo->created));  
	    $this->table->add_row('Photo Caption', $photo->photo_caption); 
	    $this->table->add_row('', '');	
	    
	   
        $photo_title = array('data' => '<h3>Photo Image</h3>', 'class' => '', 'colspan' => 2);
        $this->table->add_row($photo_title);
        $photo_row = array('data' => $photo_image, 'class' => '', 'colspan' => 2);
        $this->table->add_row($photo_row);
	    
	    $data = $this->table->generate();
	    
       	$this->wbs_helper->make_list_pdf($data, $a, $b);
            
    }

	public function print_stage_photo($photo_id)
	{
	 
	    $data['photo_id']=$photo_id;
	    $photo = $this->stage_model->getStagePhotoDetail($photo_id);     
	
	    $data['title'] = $photo->filename;
	    $data['photo'] = $photo;
	    $this->load->library('table');
	    $this->table->set_empty(""); 
	  
	    $this->table->add_row('Photo Name',$photo->filename); 
	    $this->table->add_row('Uploaded By', $photo->username);  
	    $this->table->add_row('Uploaded Date', date('d-m-Y', $photo->created));  
	    $this->table->add_row('Photo Caption', $photo->photo_caption); 
	    $this->table->add_row('', '');	
	    $data['table'] = $this->table->generate();
	  	$this->table->clear();
	
	  	$this->load->view('stage/stage_photo_print',$data);
	
	 
	}

	public function send_stage_note_message($did)
	{
		$note_id = $this->input->post('note_id');
		$stage_no = $this->input->post('stage_no');

		$user=  $this->session->userdata('user'); 
		///$user_id =$user->uid; 
		$user_name = $user->name; 
		$user_mail =$user->email; 
		
		$dev_detail= $this->stage_model->get_development_detail($did)->row();

		$note_detail= $this->stage_model->get_note_author($note_id)->row(); 
		$note_author_email= $note_detail->useremail;
		$notes_title= $note_detail->notes_title;
		
		$note_message = 'Name : '.$user_name.'<br />';
		$note_message .= 'Development : '.$dev_detail->development_name.'<br />';
		$note_message .= 'Stage : Stage '.$stage_no.'<br />';
		$note_message .= 'Notes : '.$notes_title.'<br />';
		$note_message .= '<br /><br />';
		$note_message .= $user_name.' wrote the following message :<br />';
		$note_message .= $this->input->post('notes_message');
		
		$this->load->library('email');
		$config['mailtype'] = 'html';
		$config['charset'] = 'iso-8859-1';
		$config['wordwrap'] = TRUE;			
		$this->email->initialize($config);

		$this->email->from($user_mail, $user_name);
		$this->email->to($note_author_email); 
		
		$this->email->subject($user_name.' has written correspondance for '.$notes_title);
		$this->email->message($note_message);	
		//$this->email->send();
		if ( ! $this->email->send())
		{
		    $email_send=0;
		}
		else{ $email_send=1; }

		//echo $this->email->print_debugger();
		redirect('stage/stage_notes/'.$did.'/'.$stage_no.'?sent_email='.$email_send);
		
	}

	public function stage_task_start_date_update($task_id){
        
        $post = $this->input->post();    
        $dev_id = $post['development_id'];
		$stage_no = $post['stage_no'];
        $task_data = array(                        
        				'task_start_date'=>$this->wbs_helper->to_mysql_date($post['planned_start_date'])

                   );	
				
        $this->stage_model->stage_task_start_date_update($task_id,$task_data);        
        
        redirect('stage/phases_list/'.$dev_id.'/'.$stage_no);
        
    }


	public function stage_task_actual_date_update($task_id){
        
        $post = $this->input->post();    
        $dev_id = $post['development_id'];
		$stage_no = $post['stage_no'];
        $task_data = array(                        
        				'actual_completion_date'=>$this->wbs_helper->to_mysql_date($post['actual_completion_date'])

                   );	
				
        $this->stage_model->stage_task_actual_date_update($task_id,$task_data);        
        
        redirect('stage/phases_list/'.$dev_id.'/'.$stage_no);
        
    }

	public function add_new_milestone(){
    	
    	$user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
         
        $post = $this->input->post();
       	$development_id = $post['development_id'];    
		$stage_no = $post['stage_no'];  

		$phase_ids = $post['phase_ids'];

		$str_pid = '';
		
		if(isset($_POST['phase_ids'])){
			for($i = 0; $i < count($phase_ids); $i++){ 
				if($i<(count($phase_ids)-1))
				{
					$str_pid = $str_pid.$phase_ids[$i].',';
				}
				else
				{
					$str_pid = $str_pid.$phase_ids[$i];
				}
			}
		}else{
			$str_pid = '';
		}      
                  
        if ($this->input->post('submit')){

	        $add_new_milestone = array(
	            'development_id' => $development_id,   
				'stage_no' => $stage_no,           
	            'milestone_title'=> $post['milestone_title'],
				'milestone_date' => $this->wbs_helper->to_mysql_date($post['milestone_date']),
				'milestone_select_color' => $post['milestone_select_color'],
				'milestone_phases' => $str_pid,
	            'created' => date("Y-m-d"),
	            'created_by' => $user_id
	        );

            $this->stage_model->add_new_milestone($add_new_milestone);            
	          	                                     
		} 
        redirect('stage/stage_info/'.$development_id.'/'.$stage_no); 
    }

	public function update_milestone($id){
    	
    	$user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
         
        $post = $this->input->post();
       	$development_id = $post['development_id'];    
		$stage_no = $post['stage_no'];  

		$phase_ids = $post['phase_ids'];

		$str_pid = '';
		
		if(isset($_POST['phase_ids'])){
			for($i = 0; $i < count($phase_ids); $i++){ 
				if($i<(count($phase_ids)-1))
				{
					$str_pid = $str_pid.$phase_ids[$i].',';
				}
				else
				{
					$str_pid = $str_pid.$phase_ids[$i];
				}
			}
		}else{
			$str_pid = '';
		}      
                  
        if ($this->input->post('submit')){

	        $update_milestone = array(
	            'development_id' => $development_id,   
				'stage_no' => $stage_no,           
	            'milestone_title'=> $post['milestone_title'],
				'milestone_date' => $this->wbs_helper->to_mysql_date($post['milestone_date']),
				'milestone_select_color' => $post['milestone_select_color'],
				'milestone_phases' => $str_pid,
	            'updated_by' => $user_id
	        );

            $this->stage_model->update_milestone($id, $update_milestone);            
	          	                                     
		} 
        redirect('stage/stage_info/'.$development_id.'/'.$stage_no); 
    }

	
	public function update_all_task_status($development_id,$phase_id,$stage_no,$status)
	{
		$this->stage_model->update_all_phase_tasks($development_id, $stage_no, $phase_id,$status);
	}

	public function email_outlook_stage($photo_id)
	{
		$this->stage_model->email_outlook_stage($photo_id);
	}

	public function stage_photo_delete(){
		$post = $this->input->post();
       	$development_id = $post['dev_id']; 
		$stage_no = $post['stage_no']; 
		$stage_photo_id = $post['stage_photo_id'];
		$this->stage_model->stage_photo_delete($stage_photo_id);
		redirect('stage/stage_photos/'.$development_id.'/'.$stage_no);
	}

	public function stage_document_delete(){
		$post = $this->input->post();
       	$development_id = $post['dev_id']; 
		$stage_no = $post['stage_no']; 
		$stage_document_id = $post['stage_document_id'];
		$this->stage_model->stage_document_delete($stage_document_id);
		redirect('stage/stage_documents/'.$development_id.'/'.$stage_no);
	}
        
    
    public function search_stage_notes($pid=0, $sid=0){
        $data['title'] = 'Search Stage Notes'; 
        $data['development_id']=$pid;
        $data['stage_id']=$sid;
        $data['number_of_stages'] = $this->stage_model->get_development_number_of_stage($pid);
        $search_notes= $this->input->post('search_notes');
        
        $data['notes'] = $this->stage_model->get_search_stage_notes($pid, $sid, $search_notes)->result();

		$data['stage_notes'] = $this->stage_model->get_others_search_stage_notes($pid, $sid, $search_notes)->result();
        
        $data['stage_sub_sidebar']=$this->load->view('stage/stage_sub_sidebar',$data,true); 
        $data['stage_content']=$this->load->view('stage/stage_notes',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('stage/stage_sidebar',$data);
		  $this->load->view('stage/stage_home',$data);
		  $this->load->view('includes/footer', $data); 
    }
    public function stage_notes_details($nid){
        
        $note_detail = $this->stage_model->get_note_detail($nid)->row();
        //print_r($note_detail);
        echo '<p>Subject: '.$note_detail->notes_title.'</p>';
        echo '<p>';
        echo date('d-m-Y', strtotime($note_detail->created)); echo '&nbsp; &nbsp;&nbsp; ';
        echo date("h:i a", strtotime($note_detail->created));
        echo '<span style="float:right">Author :'.$note_detail->username.'</span>'; 
         echo '</p>';
         echo '<hr style="margin-top:0px;"/>';
        echo '<p>'.$note_detail->notes_body.'</p>';
         
    }
    
    
    public function save_stage_note($pid=0, $sid=0){
         $user=  $this->session->userdata('user');          
         $user_id =$user->uid; 
        
        $data['title'] = 'Stage Nonte'; 
        $data['development_id']=$pid;
        $data['stage_id']=$sid;
        //$data['notes'] = $this->stage_model->get_stage_notes($pid)->result();
        
        $post = $this->input->post();    
        
        $note_data = array(                        
                       'project_id'=>$pid,
        				'stage_no'=>$sid,
                        'notes_title' => $this->input->post('notes_title'),
                        'notes_body' =>$this->input->post('notes_body'),
                        'created'=>date("Y-m-d H:i:s"),
                        'notes_by'=>$user_id
                   );	
				
        $this->stage_model->insert_stage_note($note_data);        
        
        redirect('stage/stage_notes/'.$pid.'/'.$sid, 'refresh');
        
    }
    public function stage_notes($pid=0, $sid=0){
        
        $development = $this->stage_model->get_development_detail($pid)->row();
        $data['title'] = $development->development_name; 

        $data['development_id']=$pid;
        $data['stage_id']=$sid;
        $data['notes'] = $this->stage_model->get_stage_notes($pid, $sid)->result();
        $data['number_of_stages'] = $this->stage_model->get_development_number_of_stage($pid);
        $data['stage_notes'] = $this->stage_model->get_stage_note_detail($pid, $sid)->result();

        $data['stage_sub_sidebar']=$this->load->view('stage/stage_sub_sidebar',$data,true); 
        $data['stage_content']=$this->load->view('stage/stage_notes',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('stage/stage_sidebar',$data);
		  $this->load->view('stage/stage_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }
    public function notes($dev_id='', $stage_id='')
  {
     
       $development = $this->stage_model->get_development_detail($dev_id)->row();
       $data['title'] = $development->development_name; 

      $data['development_id']=$dev_id;
      $data['stage_id']=$stage_id;
      $data['number_of_stages'] = $this->stage_model->get_development_number_of_stage($dev_id);
      $data['request_info']= $this->stage_model->getStageInfo($dev_id, $stage_id);
      //print_r($data['request_info']); exit;
      $prev_notes = $this->stage_model->getPriviousStageNotes($dev_id, $stage_id);
      
      $data['prev_notes'] = $this->notes_image_tmpl($prev_notes);
      //print_r($prev_notes); exit;
      
       $data['stage_sub_sidebar']=$this->load->view('stage/stage_sub_sidebar',$data,true); 
       $data['stage_content']=$this->load->view('stage/stage_notes_view',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('stage/stage_sidebar',$data);
		  $this->load->view('stage/stage_home',$data);
		  $this->load->view('includes/footer', $data);
      
      
  
  	
  }
  public function show_notes_with_image($rid, $sid){ 
      
      
      $prev_notes= $this->stage_model->getPriviousStageNotes($rid, $sid);
      echo $this->notes_image_tmpl($prev_notes);
        
      
      //echo '<p>Me : '.urldecode($note).'<br /> '.  date('d/m/Y g:i a').'<p>';
      
  }
  public function notes_image_tmpl($prev_notes){
      
      $user=  $this->session->userdata('user');          
      $user_id =$user->uid; 
      
      $align_class='';
      $tmpl='';
       foreach ($prev_notes as $notes) {
           
           
         $note_id= $notes->nid;   
        if($notes->notes_by==$user_id)
        {
           $showuser= 'Me'; 
           $notified_user= $this->stage_model->getNotifiedUserName($notes->notify_user_id);
           $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
           $align_class = 'right';
           if(!$notes->notes_image_id == null){
               $show_file= $this->notes_model->getNotesImage($notes->notes_image_id);
               $file_name= $show_file->filename;
               $allowedExts = array("gif", "jpeg", "jpg", "png");
                $temp = explode(".", $file_name);
                $extension = end($temp);
                if(in_array($extension, $allowedExts)){
                    //this is image
                   $tmpl .= '<div class="'.$align_class.'"><span class="time-left">'.$creation_time.'</span><br/><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div> </div>'; 
                }
                else
                {
                    //this is file not image
                    $tmpl .= '<div class="'.$align_class.'"><span class="time-left">'.$creation_time.'</span><br/><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body"><a target="_blank" href="'.base_url().'uploads/notes/images/'.$show_file->filename.'">'.$file_name.'</a></div> </div>';
                }
               
               
           }else{
               $tmpl .= '<div class="'.$align_class.'"><span class="time-left">'.$creation_time.'</span><br/><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body">'.$notes->notes_body.'</div><div style="margin-left: 40px;float: left;">'.$notified_user.'</div><span class="del" onClick="notesDelete('.$note_id.')"> X </span> </div>';
           }
           
           
           $tmpl .= '<div class="clear"></div>';

        }else{
            $showuser= $notes->name; 
            $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
            $align_class ='left';
            $notified_user= $this->stage_model->getNotifiedUserName($notes->notify_user_id);
            if(!$notes->notes_image_id == null){
                $show_file= $this->notes_model->getNotesImage($notes->notes_image_id);
                $file_name= $show_file->filename;
               $allowedExts = array("gif", "jpeg", "jpg", "png");
                $temp = explode(".", $file_name);
                $extension = end($temp);
                if(in_array($extension, $allowedExts)){
                    //this is image
                    $tmpl .= '<div class="'.$align_class.'"><span class="time-right">'.$creation_time.'</span><br/><div class="useranother">'.$showuser.':</div> <div style="float: left;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div> </div>';
                }else{
                    //this is not image
                    $tmpl .= '<div class="'.$align_class.'"><span class="time-right">'.$creation_time.'</span><br/><div class="useranother">'.$showuser.':</div> <div style="float: left;" class="notes_body"><a target="_blank" href="'.base_url().'uploads/notes/images/'.$show_file->filename.'">'.$file_name.'</a></div> </div>';
                }
            }
            else{
                $tmpl .= '<div class="'.$align_class.'"><span class="time-right">'.$creation_time.'</span><br/><div class="useranother">'.$showuser.':</div>  <div style="float: left;" class="notes_body">'.$notes->notes_body.'</div><div style="margin-right: 30px;float: right;">'.$notified_user.'</div><span class="del" onClick="notesDelete('.$note_id.')"> X </span> </div>'; 
            }
            
            $tmpl .= '<div class="clear"></div>';
        }
       } 
       return $tmpl;
      
  }
  public function show_notes($rid, $sid, $note, $notify_user_id=''){
      
      $user=  $this->session->userdata('user');  
      //print_r($user);
      
      $user_id =$user->uid; 
      $user_email = $user->email;
      $user_name =$user->name;
      $user_role= $user->rid;  
      $note_body= urldecode($note);       
      $now = date('Y-m-d H:i:s');
      
      
      $dev_detail= $this->stage_model->get_development_detail($rid)->row();
      $development_name= $dev_detail->development_name;

      $request_info= $this->stage_model->getStageInfo($rid, $sid);      
      $stage_name= $request_info->stage_name;      
      $request_created_by =$request_info->created_by;

		$insert_note = $this->stage_model->insertNote($rid, $sid, $note_body, $user_id, $notify_user_id, $now);      
        $prev_notes= $this->stage_model->getPriviousStageNotes($rid, $sid);
        echo $this->notes_image_tmpl($prev_notes); 
        
      
    
        $notify_user_info=$this->stage_model->get_user_info($notify_user_id);   
        
        foreach ($notify_user_info as $user_info) {
                    //$user_name[]=$user->name;
                    $notify_user_email[]=$user_info->email;
                }
                //$assign_user_name= implode(", ", $user_name);
                $notify_user_to= implode(", ", $notify_user_email);
        
        
            $from2= $user_email;
            $notes_from2 = $user_name;
            $subject2 ='You have a notes from '.$notes_from2.' on Stage -'.$stage_name.' on Development -'.$development_name;


            $headers2 = "From: ".$from2 . "\r\n";
            $headers2 .= "Reply-To: ". $notify_user_to . "\r\n";
            //$headers .= "CC: ". $cc . "\r\n";
            $headers2 .= "MIME-Version: 1.0\r\n";
            $headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

            $message2= '';
            $message2 .= '<html><body>';
            
			$message2 .= "Hello, <strong>".$notes_from2."</strong> has added a new note on stage '".$stage_name.' on Development -'.$development_name."'<br />";
                        
            $message2 .= 'Development : '.$dev_detail->development_name.'<br />';
            $message2 .= 'Stage : '.$stage_name.'<br />'; 
            
			$message2 .= "Note Description: " . $note_body . " <br />";
            $message2 .= " To view this conversation, follow this link: http://horncastledevelopments.co.nz/stage/notes/".$rid."/".$sid;
            $message2 .= "</body></html>";	
            //$msg_body='message body';
            $msg_body2=$message2;
            mail($notify_user_to, $subject2, $msg_body2, $headers2);
        
        
        
          
      
  	}
	public function notes_delete($pid, $sid, $noteid){ 
      
      $this->stage_model->deleteStageNotes($noteid);
        $prev_notes= $this->stage_model->getPriviousStageNotes($pid, $sid);
      echo $this->notes_image_tmpl($prev_notes);
      
  }

	public function stage_phase_delete($phase_id){
		
		$post = $this->input->post();
		$development_id = $post['development_id'];
		$url = $post['url'];
		$stage_no = $post['stage_no'];
		
		$this->stage_model->stage_phase_delete($phase_id);
		// redirect to Employee list page
		redirect('stage/'.$url.'/'.$development_id.'/'.$stage_no);
	}	

	public function stage_task_delete($task_id){
		
		$post = $this->input->post();
		$development_id = $post['development_id'];
		$url = $post['url'];
		$stage_no = $post['stage_no'];
		$phase_id = $post['phase_id'];
		
		$this->stage_model->stage_task_delete($task_id);
		// redirect to Employee list page
		redirect('stage/'.$url.'/'.$development_id.'/'.$stage_no.'/'.$phase_id);
	}	

	public function stage_task_update($task_id) {        
        
        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$development_id = $post['development_id'];
			$stage_no = $post['stage_no'];
			$phase_id = $post['phase_id'];
			$url = $post['url'];

			if(!empty($post['task_start_date'])){
				$task_start_date = $this->wbs_helper->to_mysql_date($post['task_start_date']);
			}else{
				$task_start_date = '0000-00-00';
			}
			if(!empty($post['actual_completion_date'])){
				$actual_completion_date = $this->wbs_helper->to_mysql_date($post['actual_completion_date']);
			}else{
				$actual_completion_date = '0000-00-00';
			}

			$stage_task_update = array(
				'task_name' => $post['task_name'],
				'task_start_date' => $task_start_date,
				'actual_completion_date' => $actual_completion_date
		    );	
			
		    $this->stage_model->stage_task_update($task_id,$stage_task_update);

			redirect('stage/'.$url.'/'.$development_id.'/'.$stage_no.'/'.$phase_id);
			
		} 
		
	}

	public function stage_phase_update($phase_id) {        
        
        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$development_id = $post['development_id'];
			$stage_no = $post['stage_no'];
			$url = $post['url'];
			
			if(!empty($post['planned_start_date'])){
				$planned_start_date = $this->wbs_helper->to_mysql_date($post['planned_start_date']);
			}else{
				$planned_start_date = '0000-00-00';
			}
			if(!empty($post['planned_finished_date'])){
				$planned_finished_date = $this->wbs_helper->to_mysql_date($post['planned_finished_date']);
			}else{
				$planned_finished_date = '0000-00-00';
			}

			$stage_phase_update = array(
				'phase_name' => $post['phase_name'],		
				'planned_start_date' => $planned_start_date,
				'planned_finished_date' => $planned_finished_date,
				'phase_person_responsible' => $post['phase_person_responsible']

		    );	

		    $this->stage_model->stage_phase_update($phase_id,$stage_phase_update);
		    
			redirect('stage/'.$url.'/'.$development_id.'/'.$stage_no);
		} 
		
	}

	public function stage_phase_add() {        
        
        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$development_id = $post['development_id'];
			$stage_no = $post['stage_no'];
			$url = $post['url'];
			
			if(!empty($post['planned_start_date'])){
				$planned_start_date = $this->wbs_helper->to_mysql_date($post['planned_start_date']);
			}else{
				$planned_start_date = '0000-00-00';
			}
			if(!empty($post['planned_finished_date'])){
				$planned_finished_date = $this->wbs_helper->to_mysql_date($post['planned_finished_date']);
			}else{
				$planned_finished_date = '0000-00-00';
			}

			$stage_phase_add = array(
				'phase_name' => $post['phase_name'],		
				'planned_start_date' => $planned_start_date,
				'planned_finished_date' => $planned_finished_date,
				'phase_person_responsible' => $post['phase_person_responsible'],
				'stage_no' => $stage_no,
				'development_id' => $development_id
		    );	

		    $this->stage_model->stage_phase_add($stage_phase_add);
		    
			redirect('stage/'.$url.'/'.$development_id.'/'.$stage_no);
		} 
		
	}

	public function stage_task_add() {        
        
        if ( $this->input->post('submit')) {

            $post = $this->input->post();
			$development_id = $post['development_id'];
			$stage_no = $post['stage_no'];
			$phase_id = $post['phase_id'];
			$url = $post['url'];

			if(!empty($post['task_start_date'])){
				$task_start_date = $this->wbs_helper->to_mysql_date($post['task_start_date']);
			}else{
				$task_start_date = '0000-00-00';
			}
			if(!empty($post['actual_completion_date'])){
				$actual_completion_date = $this->wbs_helper->to_mysql_date($post['actual_completion_date']);
			}else{
				$actual_completion_date = '0000-00-00';
			}

			$stage_task_add = array(
				'task_name' => $post['task_name'],
				'task_start_date' => $task_start_date,
				'task_length' => $post['task_length'],
				'actual_completion_date' => $actual_completion_date,
				'phase_id' => $phase_id,
				'stage_no' => $stage_no,
				'development_id' => $development_id
		    );	
			
		    $this->stage_model->stage_task_add($stage_task_add);

			redirect('stage/'.$url.'/'.$development_id.'/'.$stage_no.'/'.$phase_id);
			
		} 
		
	}

}