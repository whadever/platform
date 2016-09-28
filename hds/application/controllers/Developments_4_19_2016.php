<?php 
class Developments extends CI_Controller {
	
	private $limit = 10;
	
	function __construct() {
		
		parent::__construct();
		
		$this->load->helper(array('form', 'url', 'file', 'html', 'email'));
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->model('developments_model','',TRUE);
		$this->load->library('Wbs_helper');		
		date_default_timezone_set("NZ");
		
                //$this->load->library('gantti');
                //$this->load->library('calender');
                
                
               
                //$this->load->helper('html');
                //if (!$this->permission_model->permission_has_permission($this->uri->uri_string())) redirect ('permission/access_denied');
		//$this->ums = $this->load->database('ums', TRUE);
	}

	public function development_document_search($development_id, $search)
	{
		$this->developments_model->development_document_search($development_id, $search);
	}

	public function developments_list_contractor(){
                        
        $data['title'] = 'Developments';              
        $get = $_GET;
        $developments = $this->developments_model->get_developments_list_contractor();
        $data['developments']=  $developments;
        
        $this->load->view('developments/development_list_contractor',$data);

    }
    
    public function change_development_status_contractor($status){
        $get = $_GET;  
		$development_name = $get['selectedDevelopmentName2'];
		$development_city = $get['selectedLocationId2'];
        $developments = $this->developments_model->change_development_status_contractor($status,$development_city,$development_name);
        //echo $developments;

		$data = '<table><tbody>';

		if($status!=2)
		{

			for($i=1; $i<= count($developments); $i++ )
			{
				$j = 1;
	
				if($developments[$i][$j+1] == $status)
				{
					$data .= '<tr id="check_'.$developments[$i][$j].'" onclick="setdevelopmentid('.$developments[$i][$j].');"><td><span>'.$developments[$i][$j+2].'</span><a style="display: none;" href="development_detail/'.$developments[$i][$j].'">'.$developments[$i][$j+2].'</a></td></tr>';
					
				}

			}

		}
		elseif($status==2 && $development_city!='0')
		{

			for($i=1; $i<= count($developments); $i++ )
			{
				$j = 1;
				$data .= '<tr id="check_'.$developments[$i][$j].'" onclick="setdevelopmentid('.$developments[$i][$j].');"><td><span>'.$developments[$i][$j+2].'</span><a style="display: none;" href="development_detail/'.$developments[$i][$j].'">'.$developments[$i][$j+2].'</a></td></tr>';

			}

		}
		elseif($development_name=='ZiaurRahman123')
		{
			foreach($developments as $development)
			{
				$user = $this->session->userdata('user'); 
				$user_uid = $user->uid;
				$this->db->select('hds_dev_permission');
				$this->db->where('user_id',$user_uid);
				$this->db->where('application_id','1');
				$user = $this->db->get('users_application')->row();
				$user_permissions = $user->hds_dev_permission;
				$user_permission_arr = explode(",", $user_permissions);
				for($a = 0; $a < count($user_permission_arr); $a++)
				{
					if($user_permission_arr[$a] == $development->id)
					{
						$data .= '<tr id="check_'.$development->id.'" onclick="setdevelopmentid('.$development->id.');"><td><span>'.$development->development_name.'</span><a style="display: none;" href="development_detail/'.$development->id.'">'.$development->development_name.'</a></td></tr>';
					} // if condition end;
				} // for loop end;
			}
		}
		else
		{
			foreach($developments as $development)
			{
				$user = $this->session->userdata('user'); 
				$user_uid = $user->uid;
				$this->db->select('hds_dev_permission');
				$this->db->where('user_id',$user_uid);
				$this->db->where('application_id','1');
				$user = $this->db->get('users_application')->row();
				$user_permissions = $user->hds_dev_permission;
				$user_permission_arr = explode(",", $user_permissions);
				for($a = 0; $a < count($user_permission_arr); $a++)
				{
					if($user_permission_arr[$a] == $development->id)
					{
						$data .= '<tr id="check_'.$development->id.'" onclick="setdevelopmentid('.$development->id.');"><td><span>'.$development->development_name.'</span><a style="display: none;" href="development_detail/'.$development->id.'">'.$development->development_name.'</a></td></tr>';
					 } // if condition end;
				} // for loop end;
			}
		}


		$data .= '</tbody></table>';
		print_r($data);
    }
        
    public function index(){
            $data['title'] = 'Project';
            $data['maincontent'] = $this->load->view('developments/developments',$data,true);
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }
        
        public function developments_list($sort_by = 'cid', $order_by = 'desc', $offset = 0){
                   
           
            $data['title'] = 'Developments';              
            $get = $_GET;
            $this->limit = 50;
            $developments = $this->developments_model->get_developments_list($sort_by,$order_by,$offset,$this->limit,$get)->result();
            $data['developments']=  $developments;
            
            $data['maincontent'] = $this->load->view('developments/developments',$data,true);
            //$data['maincontent'] = $this->load->view('project_list',$data,true);
		
            $this->load->view('includes/header',$data);
            $this->load->view('includes/project_home_sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }

		public function developments_list_overview(){
                   
           
            $data['title'] = 'Developments';              
            $get = $_GET;
            $developments = $this->developments_model->get_developments_list_overview();
            $data['developments']=  $developments;
            
            $this->load->view('developments/development_list_overview',$data);
 
            //$this->load->view('home',$data);

        }

		public function development_overview_area(){
                   
           
            $data['title'] = 'Developments Overview';              
            $developments = $this->developments_model->get_development_overview_area()->result();
            $data['developments']=  $developments;
            
            $data['devlopment_content'] = $this->load->view('developments/development_overview_area',$data,true);
		
            $this->load->view('includes/header',$data);
			$this->load->view('developments/development_overview_home',$data);
            $this->load->view('developments/development_overview_footer',$data);
        }

		public function change_development_status($status){
            $get = $_GET;  
			$development_name = $get['selectedDevelopmentName2'];
			$development_city = $get['selectedLocationId2'];
            $developments = $this->developments_model->change_development_status($status,$development_city,$development_name);
            //echo $developments;

			$data = '<table><tbody>';

			foreach($developments as $development){
				$data .= '<tr id="check_'.$development->id.'" onclick="setdevelopmentid('.$development->id.');"><td><span>'.$development->development_name.'</span><a style="display: none;" href="development_detail/'.$development->id.'">'.$development->development_name.'</a></td></tr>';
			}

			$data .= '</tbody></table>';
			echo $data;
        }

		public function header_change_development_status($status,$development_city){
            $get = $_GET;  
			$development_name = $get['selectedDevelopmentName2'];
            $developments = $this->developments_model->change_development_status($status,$development_city,$development_name);
            //echo $developments;

			$sesData['location']=$development_city;
            $this->session->set_userdata($sesData);

			$data = '';

			foreach($developments as $development){
				$data .= '<li><a href="'.base_url().'developments/development_detail/'.$development->id.'">'.$development->development_name.'</a></li>';
			}

			$data .= '';
			echo $data;
        }
	
    
	
	public function project_add() {
            
            $user=  $this->session->userdata('user');          
            $user_id =$user->uid;             
            $data['title'] = 'Add Development';
            $data['action'] = site_url('project/project_add');            
            $set_project_no=  $this->project_model->get_project_no();
            
            
				  
            $this->_set_rules();
		
            if ( $this->form_validation->run() === FALSE ) {
                    // print_r('error 1'); 
                    $data['maincontent'] = $this->load->view('project_add',$data,true);		
                    $this->load->view('includes/header',$data);
                    $this->load->view('includes/project_sidebar',$data);
                    $this->load->view('home',$data);
                    $this->load->view('includes/footer',$data);
			
            }else {

                $post = $this->input->post();
                $config['upload_path'] = UPLOAD_FILE_PATH_PROJECT;
                $config['allowed_types'] = '*';
                $config['max_size'] = '100000KB';
				$config['overwrite'] = TRUE;
				

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
                    $file_insert_id = $this->project_model->file_insert($file);                        
                }else{
                    //print 'Error in file uploading...'; 
                    //print $this->upload->display_errors() ; 
                } 

                $profile = array(
                        'project_id' => $set_project_no+1,
                        'project_name' => $this->input->post('project_name'),
                        'project_description' => $this->input->post('project_description'),
                        'project_status' =>$this->input->post('project_status'),			

                        'created'=>date("Y-m-d H:i:s"),
                        'created_by' => $user_id
                   );	
				
                   $id = $this->project_model->project_save($profile);
                    // set form input name="id"
                    $this->validation->id = $id;
		//print 'success'; exit;	
		redirect('project/project_list');			
            } 
	}
	
	public function project_delete($cid){
		// delete project
		$this->project_model->delete($cid);
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
				'project_id' => $this->input->post('project_id'),
				'project_name' => $this->input->post('project_name'),
				'project_description' => $this->input->post('project_description'),
				'project_status' =>$this->input->post('project_status'),
				
				'updated' => date("Y-m-d H:i:s"),
				'updated_by' => $user_id		
			);
			//var_dump($Student);
			$this->project_model->update($pid,$project_update);
			//$data['project'] = (array)$this->project_profile_model->get_by_cid($cid)->row();
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
            $this->form_validation->set_rules('project_id', 'Project Id', 'callback_project_id');
            $this->form_validation->set_rules('project_name', 'Project Name');
           //$this->form_validation->set_rules('project_name', 'Project Name', 'required|min_length[5]|max_length[12]');
           // $this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[project_profile.email_addr_1]');
        }
		
	public function milestone_delete($did,$mid){
		$this->developments_model->milestone_delete($mid);
		// redirect to project list page
		redirect('developments/development_detail/'.$did);
	}

    function development_detail($pid=0){
		
		if ($pid <=0){
             redirect('developments/developments_list');
        }
        
        $data['development_id']=$pid;
        
        $development = $this->developments_model->get_development_detail($pid)->row();
        $data['development_details'] = $development;

        $feature_photo_id = $development->fid;
        //$data['feature_photo']= $this->developments_model->get_feature_photo($feature_photo_id);
		$data['feature_photos']= $this->developments_model->get_feature_photos($pid);
       
		$development_milestone = $this->developments_model->get_development_milestone_detail($development->id)->row();  
		$development_stage_milestone = $this->developments_model->get_development_stage_milestone_detail($development->id)->result();       
        $data['milestone_details'] = $development_milestone;
		$data['stage_milestone_details'] = $development_stage_milestone;

        //$emp = $this->employee_profile_model->emp_load($salary->eid);

		$data['title'] = $development->development_name;
        $data['number_of_stages'] = $development->number_of_stages;
		$data['development_details'] = $development;
		$this->load->library('table');
		$this->table->set_empty("");
		// $cell = array('data' => 'Blue', 'class' => 'highlight', 'colspan' => 2);
		$cell1 = array('data' => 'Development Name:', 'class' => 'coloum-highlight');                             
		$cell2 = array('data' => 'Development Status:', 'class' => 'coloum-highlight');              
		$cell3 = array('data' => 'Location:', 'class' => 'coloum-highlight');  
		$cell4 = array('data' => 'Size:', 'class' => 'coloum-highlight');
		$cell5 = array('data' => 'Land Zone:', 'class' => 'coloum-highlight');
		$cell6 = array('data' => 'Ground Condition:', 'class' => 'coloum-highlight');
		$cell7 = array('data' => 'Number of Stages:', 'class' => 'coloum-highlight');
		$cell8 = array('data' => 'Number of Lots:', 'class' => 'coloum-highlight');
		
		$cell9 = array('data' => 'Project Manager:', 'class' => 'coloum-highlight');
		$cell10 = array('data' => 'Civil Manager:', 'class' => 'coloum-highlight');
		$cell11 = array('data' => 'Civil Engineer:', 'class' => 'coloum-highlight');
		$cell12 = array('data' => 'Geo Tech Engineer:', 'class' => 'coloum-highlight');
		
		
		$this->table->add_row($cell1, $development->development_name);                                
		$this->table->add_row($cell2, 'Under development'); 
		$this->table->add_row($cell3, $development->development_location);                
		$this->table->add_row($cell4, $development->development_size);   
		$this->table->add_row($cell5, $development->land_zone);  
		$this->table->add_row($cell6, $development->ground_condition);  
		$this->table->add_row($cell7, $development->number_of_stages);  
		$this->table->add_row($cell8, $development->number_of_lots); 
		
		$this->table->add_row('', '');
		$this->table->add_row($cell9, $development->project_manager);
		$this->table->add_row($cell10, $development->civil_engineer);
		$this->table->add_row($cell11, $development->civil_manager);
		$this->table->add_row($cell12, $development->geo_tech_engineer);
		
		
		
		
		if(empty($development_milestone)){
			//$this->table->add_row('', '<a style="" id="add-ailestone" href="#AddNewMilestone" data-toggle="modal" role="button">Set Milestone</a>');
		}else{

			//$this->table->add_row('', '<a style="" id="add-ailestone" href="#EditMilestone" data-toggle="modal" role="button">Update Milestone <img alt="Edit Milestone" src="'.base_url().'icon/icon_edit.png" width="20" height="" /></a>');

		}
		
		                                   

		$data['table'] = $this->table->generate();
		$this->table->clear();    
		       
		$data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true);  
        $data['devlopment_content']=$this->load->view('developments/development_detail',$data,true);
		  
		$this->load->view('includes/header', $data);
		$this->load->view('developments/development_sidebar',$data);
		$this->load->view('developments/development_home',$data);
		$this->load->view('includes/footer', $data);
    }
    public function development_photos($pid=0){
		//phpinfo();
        $user = $this->session->userdata('user');
		$this->load->model('user_model','',TRUE);
		$user_role = $this->user_model->user_app_role_load($user->uid);
		$user_role = $user_role->application_role_id;

        $development = $this->developments_model->get_development_detail($pid)->row();

        $data['title'] = $development->development_name;
 
        $data['development_id']=$pid;

		$data['photos'] = $this->developments_model->getDevelopmentPhotos($pid)->result();

        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);
        
        $data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
        $data['devlopment_content']=$this->load->view('developments/development_photos',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('developments/development_sidebar',$data);
		  $this->load->view('developments/development_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }

	public function development_archive_photos($pid=0){
		//phpinfo();
        $user = $this->session->userdata('user');
		$this->load->model('user_model','',TRUE);
		$user_role = $this->user_model->user_app_role_load($user->uid);
		$user_role = $user_role->application_role_id;

        $development = $this->developments_model->get_development_detail($pid)->row();

        $data['title'] = $development->development_name;
 
        $data['development_id']=$pid;
		$data['photos'] = $this->developments_model->getDevelopmentArchivePhotos($pid)->result();

        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);
        
        $data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
        $data['devlopment_content']=$this->load->view('developments/development_archive_photos',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('developments/development_sidebar',$data);
		  $this->load->view('developments/development_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }

    public function upload_development_photo($pid=0){
        
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
        
         $config['upload_path'] = UPLOAD_DEVELOPMENT_IMAGE_PATH;
         $config['allowed_types'] = '*';
		 $config['max_size'] = '100000KB';
		$config['overwrite'] = TRUE;
         $this->load->library('upload', $config);
         $this->upload->initialize($config);
         
         if ($this->upload->do_upload('photoimg')){
            $upload_data = $this->upload->data();
            echo '<img width="245" height="245" src="'.base_url().'uploads/development/'.$upload_data['file_name'].'"/>';
            //print_r($upload_data); 
            $document = array(
                'project_id'=>$pid,
                'filename'=>$upload_data['file_name'],
                'filetype'=>$upload_data['file_type'],
                'filesize'=>$upload_data['file_size'],
                'filepath'=>$upload_data['full_path'],
                //'filename_custom'=>$post['note_image'],
                'created'=>strtotime(date("Y-m-d H:i:s")),
                'uid'=>$user_id
            );
            $photo_insert_id = $this->developments_model->project_photo_insert($document); 
             
            echo '<input type="hidden" id="development_photo_id" value="'.$photo_insert_id.'" />';
             

        }else{
            echo 'Error in file uploading...'; 
           print $this->upload->display_errors() ;  
        } 
        
    }
    public function save_development_photo($pid=0){
        
        
        $data['title'] = 'Development Photos'; 
        $data['development_id']=$pid;        
        $post = $this->input->post();     
        
        $photo_insert_id = $this->input->post('photo_insert_id');   
        $photo_info = array(                        
                       
                        'photo_caption' => $this->input->post('photo_caption'),
                        'photo_category' =>$this->input->post('photo_category')
                   );       
       
				
        $this->developments_model->save_project_photo_info($photo_insert_id, $photo_info);       
              
        redirect('developments/development_photos/'.$pid, 'refresh');          
        
    }
	
	public function print_developments_photo($photo_id=0)
	{
	 
	    $data['photo_id']=$photo_id;
	    $photo = $this->developments_model->getDevelopmentPhotoDetail($photo_id);     
	
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
	
	  	$this->load->view('developments/development_photo_print',$data);
	
	 
	}
	
	public function pdf_developments_photo($photo_id){
             
        $a = define ('PDF_HEADER_STRING1', '');
        $b = define ('PDF_HEADER_TITLE1', 'Horncastle Developments');
            
        $photo = $this->developments_model->getDevelopmentPhotoDetail($photo_id);   
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
    
    public function development_overview_old($pid=0){
        
		$development = $this->developments_model->get_development_detail($pid)->row();

        $data['title'] = $development->development_name; 
        $data['development_id']=$pid;
        
        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);
        $data['development_overview_info'] = $this->developments_model->get_development_phase_info($pid)->result(); 
        $data['stage_overview_info'] = $this->developments_model->get_development_stage_info($pid)->result();       
        
        $data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
        $data['devlopment_content']=$this->load->view('developments/development_overview',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('developments/development_sidebar',$data);
		  $this->load->view('developments/development_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }

	public function development_overview($pid=0){
        
		

        $development = $this->developments_model->get_development_detail($pid)->row();

        $data['title'] = $development->development_name;
 
        $data['development_id']=$pid;

		$data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);
        $data['development_overview_info'] = $this->developments_model->get_development_phase_info($pid)->result(); 
        $data['stage_overview_info'] = $this->developments_model->get_development_stage_info($pid)->result();
        
        $data['development_milestone'] = $this->developments_model->get_development_milestone_detail($pid)->result();

        $data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
        $data['devlopment_content']=$this->load->view('developments/developments_overview',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('developments/development_sidebar',$data);
		  $this->load->view('developments/development_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }


    public function development_notes($pid=0){
        
        $development = $this->developments_model->get_development_detail($pid)->row();

        $data['title'] = $development->development_name;
 
        $data['development_id']=$pid;
        $data['notes'] = $this->developments_model->get_project_notes($pid)->result();
		$data['developments_notes'] = $this->developments_model->get_developments_notes($pid)->result();
        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);
        
        $data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
        $data['devlopment_content']=$this->load->view('developments/development_notes',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('developments/development_sidebar',$data);
		  $this->load->view('developments/development_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }
    
    public function search_development_notes($pid=0){
        $data['title'] = 'Development Search Notes'; 
        $data['development_id']=$pid;
        $search_notes= $this->input->post('search_notes');
        
        $data['notes'] = $this->developments_model->get_project_search_notes($pid, $search_notes)->result();

		$data['developments_notes'] = $this->developments_model->get_others_project_search_notes($pid, $search_notes)->result();
		$data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);
        
        $data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
        $data['devlopment_content']=$this->load->view('developments/development_notes',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('developments/development_sidebar',$data);
		  $this->load->view('developments/development_home',$data);
		  $this->load->view('includes/footer', $data); 
    }
    public function development_notes_details($nid){
        
        $note_detail = $this->developments_model->get_note_detail($nid)->row();
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
    
	public function email_development_notes($nid){
        
        $note_detail = $this->developments_model->get_note_detail($nid)->row();        
        $note = '<p>Subject: '.$note_detail->notes_title.'</p>';
        $note .=  '<p>';
        $note .=  date('d-m-Y', strtotime($note_detail->created)); echo '&nbsp; &nbsp;&nbsp; ';
        $note .=  date("h:i a", strtotime($note_detail->created));
        $note .=  '<span style="float:right">Author :'.$note_detail->username.'</span>'; 
        $note .=  '</p>';
        $note .=  '<hr style="margin-top:0px;"/>';
        $note .=  '<p>'.$note_detail->notes_body.'</p>';
        
        $html=$note;
        
        $to= 'alimuls@gmail.com'; 
	    $from= 'mamunjava@gmail.com'; 
	    $cc= 'nurulku02@gmail.com'; 
	    $subject = 'Developments Info';
	
	    $headers = "From: " . $from . "\r\n";
	    $headers .= "Reply-To: ". $from . "\r\n";
	    $headers .= "CC:". $cc ."\r\n";
	    $headers .= "MIME-Version: 1.0\r\n";
	    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	    
		$mail_status= mail($to, $subject, $html, $headers);
    
    
	    if($mail_status){
	        echo 'Mail Sent successfully.';
	    }else{
	        echo 'Mail did not Sent. Try again some later.';
	    }
        
	    
         
    }
    
    public function save_development_note($pid=0){
         $user=  $this->session->userdata('user');          
         $user_id =$user->uid; 
        
        $data['title'] = 'Development Photos'; 
        $data['development_id']=$pid;
        $data['notes'] = $this->developments_model->get_project_notes($pid)->result();
        
        $post = $this->input->post();    
        
        $note_data = array(                        
                       'project_id'=>$pid,
                        'notes_title' => $this->input->post('notes_title'),
                        'notes_body' =>$this->input->post('notes_body'),
                        'created'=>date("Y-m-d H:i:s"),
                        'notes_by'=>$user_id
                   );	
				
        $this->developments_model->insert_development_note($note_data);        
        
        redirect('developments/development_notes/'.$pid, 'refresh');
        
    }
    public function phases_underway($pid=0){
        
        $development = $this->developments_model->get_development_detail($pid)->row();

        $data['title'] = $development->development_name;
 
        $data['development_id']=$pid;
        $data['stages_no'] = $this->developments_model->get_stage_list($pid)->result();
         //$data['number_of_stages'] = $this->developments_model->get_stage_list($pid)->result();
        //$data['phase_info'] = $this->project->model->get_phase_info($pid)->result();

		$data['development_phase_info'] = $this->developments_model->get_development_phase_info($pid)->result(); 
        $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($pid);

		
        
        $data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
        $data['devlopment_content']=$this->load->view('developments/development_phases_underway',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('developments/development_sidebar',$data);
		  $this->load->view('developments/development_home',$data);
		  $this->load->view('includes/footer', $data);
        
    }
public function print_development($pid=0)
{
 
    if ($pid <=0){
     redirect('developments/developments_list');
    }


    $data['development_id']=$pid;

    $development = $this->developments_model->get_development_detail($pid)->row(); 

    $feature_photo_id= $development->fid;
    $data['feature_photo']= $this->developments_model->get_defelopment_feature_photo($feature_photo_id)->row();
    

    $data['title'] = $development->development_name;
    $data['development_details'] = $development;
    $this->load->library('table');
    $this->table->set_empty("");

 
  
    $this->table->add_row('Development Name',$development->development_name);        
                
    $this->table->add_row('Development Status', 'Under development'); 
    $this->table->add_row('', ''); 

    $this->table->add_row('Development Location', $development->development_location);  
    $this->table->add_row('Development Size', $development->development_size);  
    $this->table->add_row('Development Land Zone', $development->land_zone);  
    $this->table->add_row('Ground Condition', $development->ground_condition);  
    $this->table->add_row('Number of Stages', $development->number_of_stages);  
    $this->table->add_row('Number of Lots', $development->number_of_lots); 

    $this->table->add_row('', '');
    $this->table->add_row('Project Manager', $development->project_manager);
    $this->table->add_row('Civil Manager', $development->civil_engineer);
    $this->table->add_row('Civil Engineer', $development->civil_manager);
    $this->table->add_row('Geo Tech Engineer', $development->geo_tech_engineer);

                           

  	$data['table'] = $this->table->generate();
  	$this->table->clear();



  	$this->load->view('developments/development_print',$data);

 
 }
 
 public function email_development($pid=0){

 
    if ($pid <=0){
     redirect('developments/developments_list');
    }
    
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
    $development = $this->developments_infromation($pid);  
  
    $html = $development;
   
    $mail_status= mail($to, $subject, $html, $headers);
    
    
    if($mail_status){
        echo 'Mail Sent successfully.';
    }else{
        echo 'Mail did not Sent. Try again some later.';
    }
        
    //redirect('developments/development_detail/'.$pid);


 
	}
	public function pdf_developments($pid){
            
            $a = define ('PDF_HEADER_STRING1', '');
            $b = define ('PDF_HEADER_TITLE1', 'Horncastle Developments');
            //$all_employees = $this->employee_model->employee_list_print();
            $data= $this->developments_infromation($pid);			
            $this->wbs_helper->make_list_pdf($data, $a, $b);            
            //redirect('employee/employee_list');
    }
        
	public function email_dev_photo($photo_id){

 		$photo = $this->developments_model->getDevelopmentPhotoDetail($photo_id);
 		$photo_image= '<img width="270" height="250" src="'.base_url().'uploads/development/'.$photo->filename.'"/>';  
  
 		$this->load->library('table');
        $this->table->set_empty("");

        $this->table->add_row('Photo File Name',$photo->filename); 
        $this->table->add_row('Photo Cattion',$photo->photo_caption);         
        $this->table->add_row('', ''); 

        $this->table->add_row('Photo Image', $photo_image);  
        $this->table->add_row('Phote  Category', $photo->photo_category);  
        
        
         $photo_data = $this->table->generate();
         $this->table->clear();
 	
    
    
	    $to= 'mamunjava@gmail.com'; 
	    $from= 'nurulku02@gmail.com'; 
	    $cc= 'alimuls@gmail.com'; 
	    $subject = 'Developments Photo';
	
	    $headers = "From: " . $from . "\r\n";
	    $headers .= "Reply-To: ". $from . "\r\n";
	    $headers .= "CC:". $cc ."\r\n";
	    $headers .= "MIME-Version: 1.0\r\n";
	    $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		 
    	$html= '<h1>Developments Photos </h1>';
    	$html .= $photo_data;    
    
   	 	$mail_status= mail($to, $subject, $html, $headers);
    
    
	    if($mail_status){
	        echo 'Mail Sent successfully.';
	    }else{
	        echo 'Mail did not Sent. Try again some later.';
	    }
        
    	//redirect('developments/development_detail/'.$pid);
 
 } 
   
    public function developments_infromation($pid){
                
                
        $development = $this->developments_model->get_development_detail($pid)->row();  
        
        $feature_photo_id= $development->fid;
    	$feature_photo= $this->developments_model->get_defelopment_feature_photo($feature_photo_id)->row();
		
		if (!empty($feature_photo)) {
			$photo='<img width="" height="" src="'.base_url().'uploads/development/'.$feature_photo->filename.'"/>';
		}else{
			$photo='<img width="" height="" src="'.base_url().'images/pms_home.png"/>';		
		}
        

        $this->load->library('table');
        $this->table->set_empty("");
		$this->table->set_caption('<h1>Development Information</h1>');
		
        $this->table->add_row('Development Name',$development->development_name); 
        $this->table->add_row('Development Status', 'Under development'); 
        $this->table->add_row('', ''); 

        $this->table->add_row('Development Location', $development->development_location);  
        $this->table->add_row('Development Size', $development->development_size);  
        $this->table->add_row('Development Land Zone', $development->land_zone);  
        $this->table->add_row('Ground Condition', $development->ground_condition);  
        $this->table->add_row('Number of Stages', $development->number_of_stages);  
        $this->table->add_row('Number of Lots', $development->number_of_lots); 

        $this->table->add_row('', '');
        $this->table->add_row('Project Manager', $development->project_manager);
        $this->table->add_row('Civil Manager', $development->civil_engineer);
        $this->table->add_row('Civil Engineer', $development->civil_manager);
        $this->table->add_row('Geo Tech Engineer', $development->geo_tech_engineer);

        $this->table->add_row('', '');
        $photo_title = array('data' => '<h3>Feature Photo</h3>', 'class' => '', 'colspan' => 2);
        $this->table->add_row($photo_title);
        $photo_row = array('data' => $photo, 'class' => '', 'colspan' => 2);
        $this->table->add_row($photo_row);
        
        
        $data = $this->table->generate();
         $this->table->clear();
         
         return $data;                      

  	
                
    }

	public function update_phase_status($phase_id,$status)
	{

		$this->developments_model->update_status($phase_id,$status);
	}

	public function	update_development_phase_task_status($task_id,$status)
	{
			$this->developments_model->update_development_phase_task_status($task_id,$status);
	}

	public function send_development_note_message($pid)
	{
		$note_id = $this->input->post('note_id');
		
		
		
		$user=  $this->session->userdata('user'); 
		///$user_id =$user->uid; 
		$user_name = $user->name; 
		$user_mail =$user->email; 
		
		$dev_detail= $this->developments_model->get_development_detail($pid)->row();

		$note_detail= $this->developments_model->get_note_author($note_id)->row(); 
		$note_author_email= $note_detail->useremail;
		$notes_title= $note_detail->notes_title;
		
		$note_message = 'Name : '.$user_name.'<br />';
		$note_message .= 'Development : '.$dev_detail->development_name.'<br />';
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
		redirect('developments/development_notes/'.$pid.'?sent_email='.$email_send);
		
	}
	
    public function send_development_photo_message()
	{
		$photo_id = $this->input->post('photo_id');
		$photo_dev_id = $this->input->post('photo_dev_id');
		
		
		$user=  $this->session->userdata('user'); 
		///$user_id =$user->uid; 
		$user_name = $user->username; 
		$user_mail = $user->email; 
		
		$photo_detail= $this->developments_model->get_photo_author($photo_id)->row(); 
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
		redirect('developments/development_photos/'.$photo_dev_id.'?sent_email='.$email_send);
		
	}

	public function phase_task_start_date_update($task_id)
	{

		$post = $this->input->post();
		$dev_id = $post['development_id'];

		$task_data = array(                        
        				'task_start_date'=>$this->wbs_helper->to_mysql_date($post['planned_start_date'])
                   );

		$this->developments_model->development_phase_task_start_date_update($task_id,$task_data); 
		redirect('developments/phases_underway/'.$dev_id);

	}

	public function development_stage_task_start_date_update($task_id)
	{
		$post = $this->input->post();
		$dev_id = $post['development_id'];

		$task_data = array(                        
        				'planned_start_date'=>$this->wbs_helper->to_mysql_date($post['planned_start_date'])
                   );

		$this->developments_model->development_stage_task_start_date_update($task_id,$task_data); 
		redirect('developments/phases_underway/'.$dev_id);

	}


	public function phase_task_actual_date_update($task_id)
	{

		$post = $this->input->post();
		$dev_id = $post['development_id'];

		$task_data = array(                        
        				'actual_completion_date'=>$this->wbs_helper->to_mysql_date($post['actual_completion_date'])
                   );

		$this->developments_model->development_phase_task_actual_date_update($task_id,$task_data); 
		redirect('developments/phases_underway/'.$dev_id);

	}


	public function development_stage_task_actual_date_update($task_id)
	{
		$post = $this->input->post();
		$dev_id = $post['development_id'];

		$task_data = array(                        
        				'actual_finished_date'=>$this->wbs_helper->to_mysql_date($post['actual_finished_date'])
                   );

		$this->developments_model->development_stage_task_actual_date_update($task_id,$task_data); 
		redirect('developments/phases_underway/'.$dev_id);

	}

	public function development_documents($did){

		$development = $this->developments_model->get_development_detail($did)->row();
		$data['title'] = $development->development_name;
		$data['number_of_stages'] = $development->number_of_stages;
		$data['development_id']=$did;

        $data['documents'] = $this->developments_model->getDevelopmentDocuments($did)->result();
        $data['developments_documents'] = $this->developments_model->getOthersDevelopmentDocuments($did)->result();      
                
		$data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($did);

        $data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true);        
		$data['devlopment_content']=$this->load->view('developments/development_documents',$data,true);
		  
		$this->load->view('includes/header', $data);
		$this->load->view('developments/development_sidebar',$data);
		$this->load->view('developments/development_documents_home',$data);
		$this->load->view('includes/footer', $data);
    }
	
	public function development_documents_bycategory($did,$cid){

        $development = $this->developments_model->get_development_detail($did)->row();
		$data['title'] = $development->development_name;
		$data['number_of_stages'] = $development->number_of_stages;
		$data['development_id']=$did;
		$data['category_id']=$cid;
        $data['documents'] = $this->developments_model->get_development_documents_bycategory($did,$cid)->result();
              
        $data['developments_documents'] = $this->developments_model->getOthersDevelopmentDocumentsBycategory($did,$cid)->result();       
		
		$data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($did);
        
        $data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true);       
		$data['devlopment_content']=$this->load->view('developments/development_documents',$data,true);
		  
		$this->load->view('includes/header', $data);
		$this->load->view('developments/development_sidebar',$data);
		$this->load->view('developments/development_documents_home',$data);
		$this->load->view('includes/footer', $data);

    }

	public function development_document_detail($document_id){
        
        $document_detail = $this->developments_model->get_document_detail($document_id)->row();
        //print_r($note_detail);
        //echo '<p>Download Document : <a href="'.base_url().'uploads/development/documents/'.$document_detail->filename.'">'.$document_detail->filename_custom.'</a></p>';
        //echo '<p>';
        //echo date('d-m-Y', strtotime($note_detail->created)); 
        //echo '&nbsp; &nbsp;&nbsp; ';
        //echo date("h:i a", strtotime($note_detail->created));
         
        // echo '</p>';
        // echo '<hr style="margin-top:0px;"/>';
        //echo '<p> Download '.base_url().'uploads/stage/documents/'.$note_detail->filename.'</p>';


		echo '<object data="'.base_url().'uploads/development/documents/'.$document_detail->filename.'" type="application/pdf" width="100%" height="100%"><p>It appears you dont have a PDF plugin for this browser<br>You can <a href="'.base_url().'uploads/development/documents/'.$document_detail->filename.'">click here to download the PDF file.</a></p> </object>';
         
    }

    public function save_development_document($did){
    	
    	$user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
         
        $post = $this->input->post();
       	$url = $post['url'];
		if(isset($post['file_category'])){
			$file_category = '/'.$post['file_category'];
        }
        $config['upload_path'] = UPLOAD_FILE_PATH_DEVELOPMENT_DOCUMENT;
        
        $config['allowed_types'] = 'pdf';
		//$config['max_size'] = '5000';
		$config['overwrite'] = TRUE;
        $this->load->library('upload', $config);
        $this->upload->initialize($config);
                         
        if ($this->upload->do_upload('upload_document')){
	         
			$upload_data = $this->upload->data();	        
	        // insert data to file table
	        // get latest id from frim table and insert it to loan table
	        $document = array(
	            'development_id'=>$did,              
	        	'filename'=>$upload_data['file_name'],
	            'filetype'=>$upload_data['file_type'],
	            'filesize'=>$upload_data['file_size'],
	            'filepath'=>$upload_data['full_path'],
	            'filename_custom'=>$post['file_title'],
	            'created'=>time(),
	            'uid'=>$user_id,
				'notify_user'=>$post['notify_user']
	        );

            $this->developments_model->development_document_insert($document);

			$filename_custom = $post['file_title'];
			$notify_user = $post['notify_user'];
			if(!empty($notify_user))
			{
 
		      	$user_email = $user->email;
		      	$user_name =$user->username;

				$notify_user_email = $this->developments_model->notify_one_user_info($notify_user);
				$notify_user_to = $notify_user_email->email;
				$notify_user_name = $notify_user_email->username;

				$dev_info = $this->developments_model->getDevelopmentsInfo($did);
				$dev_name = $dev_info->development_name;

				$subject ='You have a document from '.$user_name;
			
				$headers = "From: ".$user_email . "\r\n";
				$headers .= "Reply-To: ". $user_email . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
				$message= '';
		        $message .= '<html><body>';	
				$message .= "Hello, <strong>".$user_name."</strong> has added a new document. <br />";
		        $message .= 'Development : '.$dev_name.'<br />';
				$message .= "Document Title: " . $filename_custom . " <br />";
		        $message .= " To view this conversation, follow this link: ".base_url()."developments/development_documents/".$did;
				$message .= "</body></html>";	

				$msg_body=$message;
		        mail($notify_user_to, $subject, $msg_body, $headers);
			}
	                                     
		}else{
			//echo '2'; 
        	//echo 'Error in file uploading...'; 
           	//echo $this->upload->display_errors() ; 
			redirect('developments/'.$url.'/'.$did.'?error=1');
        } 
        redirect('developments/'.$url.'/'.$did.''.$file_category); 
    }    
        
    public function add_new_milestone(){
    	
    	$user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
         
        $post = $this->input->post();

       	$development_id = $post['development_id']; 
   
		$urban_plan_concept= $post['urban_plan_concept']==''? '':$this->wbs_helper->to_mysql_date($post['urban_plan_concept']);
		$consultation= $post['consultation']==''? '':$this->wbs_helper->to_mysql_date($post['consultation']);
        $building_design= $post['building_design']==''? '':$this->wbs_helper->to_mysql_date($post['building_design']);
        $working_drawings= $post['working_drawings']==''? '':$this->wbs_helper->to_mysql_date($post['working_drawings']);
        $working_drawings_contractor= $post['working_drawings_contractor']==''? '':$this->wbs_helper->to_mysql_date($post['working_drawings_contractor']);

        $resource_consent= $post['resource_consent']==''? '':$this->wbs_helper->to_mysql_date($post['resource_consent']);
        $building_permits= $post['building_permits']==''? '':$this->wbs_helper->to_mysql_date($post['building_permits']);
        $construction_general= $post['construction_general']==''? '':$this->wbs_helper->to_mysql_date($post['construction_general']);
        $construction_earthworks= $post['construction_earthworks']==''? '':$this->wbs_helper->to_mysql_date($post['construction_earthworks']);
        $construction_civil= $post['construction_civil']==''? '':$this->wbs_helper->to_mysql_date($post['construction_civil']);
        $construction_roading= $post['construction_roading']==''? '':$this->wbs_helper->to_mysql_date($post['construction_roading']);
        $completion= $post['completion']==''? '':$this->wbs_helper->to_mysql_date($post['completion']);
        $titles_due_out= $post['titles_due_out']==''? '':$this->wbs_helper->to_mysql_date($post['titles_due_out']);
                  
		

        if ($this->input->post('submit')){
			
			
	        $add_new_milestone = array(
	            'development_id' => $development_id,              
	            
				'urban_plan_concept' => $urban_plan_concept,
				'consultation' => $consultation,
				'building_design' => $building_design,
				'working_drawings' => $working_drawings,
				'working_drawings_contractor' => $working_drawings_contractor,
				'resource_consent' => $resource_consent,
				'building_permits' => $building_permits,
				'construction_general' => $construction_general,
				'construction_earthworks' => $construction_earthworks,
				'construction_civil' => $construction_civil,
				'construction_roading' => $construction_roading,
				'completion' => $completion,
				'titles_due_out' => $titles_due_out,				
	            'created' => date("Y-m-d"),
	            'created_by' => $user_id
	        );

            $this->developments_model->add_new_milestone($add_new_milestone);            
	          	                                     
		} 
        redirect('developments/development_detail/'.$development_id); 
    }

	public function update_milestone($development_id){
    	
    	$user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
         
        $post = $this->input->post();
		

       	//$development_id = $post['development_id']; 
   
		$urban_plan_concept= $post['urban_plan_concept']==''? '':$this->wbs_helper->to_mysql_date($post['urban_plan_concept']);
		$consultation= $post['consultation']==''? '':$this->wbs_helper->to_mysql_date($post['consultation']);
        $building_design= $post['building_design']==''? '':$this->wbs_helper->to_mysql_date($post['building_design']);
        $working_drawings= $post['working_drawings']==''? '':$this->wbs_helper->to_mysql_date($post['working_drawings']);
        $working_drawings_contractor= $post['working_drawings_contractor']==''? '':$this->wbs_helper->to_mysql_date($post['working_drawings_contractor']);

        $resource_consent= $post['resource_consent']==''? '':$this->wbs_helper->to_mysql_date($post['resource_consent']);
        $building_permits= $post['building_permits']==''? '':$this->wbs_helper->to_mysql_date($post['building_permits']);
        $construction_general= $post['construction_general']==''? '':$this->wbs_helper->to_mysql_date($post['construction_general']);
        $construction_earthworks= $post['construction_earthworks']==''? '':$this->wbs_helper->to_mysql_date($post['construction_earthworks']);
        $construction_civil= $post['construction_civil']==''? '':$this->wbs_helper->to_mysql_date($post['construction_civil']);
        $construction_roading= $post['construction_roading']==''? '':$this->wbs_helper->to_mysql_date($post['construction_roading']);
        $completion= $post['completion']==''? '':$this->wbs_helper->to_mysql_date($post['completion']);
        $titles_due_out= $post['titles_due_out']==''? '':$this->wbs_helper->to_mysql_date($post['titles_due_out']);
		
		
                  
        if ($this->input->post('submit')){
			
			
	        $update_milestone = array(
	            //'development_id' => $development_id,              
	            'urban_plan_concept' => $urban_plan_concept,
				'consultation' => $consultation,
				'building_design' => $building_design,
				'working_drawings' => $working_drawings,
				'working_drawings_contractor' => $working_drawings_contractor,
				'resource_consent' => $resource_consent,
				'building_permits' => $building_permits,
				'construction_general' => $construction_general,
				'construction_earthworks' => $construction_earthworks,
				'construction_civil' => $construction_civil,
				'construction_roading' => $construction_roading,
				'completion' => $completion,
				'titles_due_out' => $titles_due_out,
	            'updated_by' => $user_id
	        );
			

            $this->developments_model->update_milestone($development_id, $update_milestone);            
	          	                                     
		} 
        redirect('developments/development_detail/'.$development_id); 
    }


	public function update_all_phase_task_status($development_id,$phase_id,$status)
	{
		$this->developments_model->update_all_phase_tasks($development_id,$phase_id,$status);
	}
    
	public function update_all_stage_phase_status($development_id,$stage_no,$status)
	{
		$this->developments_model->update_all_satge_phase($development_id, $stage_no,$status);
	}

	public function email_outlook_development($photo_id)
	{
		$this->developments_model->email_outlook_development($photo_id);
	}

	public function development_photo_delete(){
		$post = $this->input->post();
       	$development_id = $post['dev_id']; 
		$development_photo_id = $post['dev_photo_id'];
		$this->developments_model->development_photo_delete($development_photo_id);
		redirect('developments/development_photos/'.$development_id);
	}

	public function development_archive_photo_delete(){
		$post = $this->input->post();
       	$development_id = $post['dev_id']; 
		$development_photo_id = $post['dev_photo_id'];
		$this->developments_model->development_photo_delete($development_photo_id);
		redirect('developments/development_archive_photos/'.$development_id);
	}

	public function development_document_delete(){
		$post = $this->input->post();
       	$development_id = $post['dev_id']; 
		$dev_document_id = $post['dev_document_id'];
		$this->developments_model->development_document_delete($dev_document_id);
		redirect('developments/development_documents/'.$development_id, 'refresh');
	}

	public function development_update($development_id) {   
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid;         
        
        if ( $this->input->post('submit')) {

            $post = $this->input->post();

			$config['upload_path'] = UPLOAD_DEVELOPMENT_IMAGE_PATH;
			$config['allowed_types'] = '*';
			$this->load->library('upload', $config);
			$this->upload->initialize($config);

			if ($this->upload->do_upload('feature_photo')){
	            $upload_data = $this->upload->data();

	            $file = array(
	                
	                'filename'=>$upload_data['file_name'],
	                'filetype'=>$upload_data['file_type'],
	                'filesize'=>$upload_data['file_size'],
	                'filepath'=>$upload_data['full_path'],                
	                'created'=>date("Y-m-d H:i:s"),
	                'uid'=>$user_id
	            );
	            $photo_insert_id = $this->developments_model->development_feature_photo_insert($file); 
	             	
	        }else{
				$photo_insert_id = $post['fid'];
			}

			$development_update = array(
				'development_name' => $post['development_name'],
				'development_location' => $post['development_location'],
				'development_city' => $post['development_city'],
				'development_size' => $post['development_size'],
				'number_of_stages' => $post['number_of_stages'],
				'number_of_lots' => $post['number_of_lots'],
				'land_zone' => $post['land_zone'],
				'ground_condition' => $post['ground_condition'],
				'project_manager' => $post['project_manager'],
				'civil_engineer' => $post['civil_engineer'],
				'civil_manager' => $post['civil_manager'],
				'geo_tech_engineer' => $post['geo_tech_engineer'],
				'status' => $post['status'],
			
			    'fid' => $photo_insert_id,
				'updated_by' => $user_id
		    );	
			
		    $id = $this->developments_model->development_update($development_id,$development_update);
			
		} 
		
		redirect('developments/development_detail/'.$development_id, 'refresh');
	}
  public function notes($dev_id='')
  {
      //echo $req_id;
      
      $development = $this->developments_model->get_development_detail($dev_id)->row();
	  $data['title'] = $development->development_name;
 
      $data['development_id']=$dev_id;
      $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($dev_id);
      $data['request_info']= $this->developments_model->getDevelopmentsInfo($dev_id);
      //print_r($data['request_info']); exit;

		$prev_notes = $this->developments_model->getPriviousDevelopmentsNotes($dev_id);
		$prev_notes_stage = $this->developments_model->getPriviousStageNotesInDev($dev_id);

		//print_r($prev_notes_stage);exit;
		$data['feature_photos']= $this->developments_model->get_feature_photos($dev_id);

		//print_r($prev_notes);
      $data['prev_notes'] = $this->notes_image_tmpl($prev_notes,$prev_notes_stage);
      //print_r($prev_notes);
      
       $data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
       $data['devlopment_content']=$this->load->view('developments/development_notes_view',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('developments/development_sidebar',$data);
		  $this->load->view('developments/development_home',$data);
		  $this->load->view('includes/footer', $data);
      	
  	}

	public function photo_notes($photo_id='', $dev_id='')
 	{
     
      //echo $req_id;
      
      $development = $this->developments_model->get_development_detail($dev_id)->row();
	  $data['title'] = $development->development_name;
 
      $data['development_id']=$dev_id;
		$data['photo'] = $this->developments_model->getDevelopmentPhoto($photo_id)->row();

      $data['number_of_stages'] = $this->developments_model->get_development_number_of_stage($dev_id);
      $data['request_info']= $this->developments_model->getDevelopmentsInfo($dev_id);
      //print_r($data['request_info']); exit;
      $prev_notes = $this->developments_model->getPriviousDevelopmentphotoNotes($dev_id);
      $data['prev_notes'] = $this->notes_image_tmpl($prev_notes);
      //print_r($prev_notes); exit;
      
       $data['devlopment_sub_sidebar']=$this->load->view('developments/devlopment_sub_sidebar',$data,true); 
       $data['devlopment_content']=$this->load->view('developments/development_photo_notes_view',$data,true);
		  
		  $this->load->view('includes/header', $data);
		  $this->load->view('developments/development_sidebar',$data);
		  $this->load->view('developments/development_home',$data);
		  $this->load->view('includes/footer', $data);
      
      
  
  	
  }	

	public function show_notes_with_image($rid){ 
      	$user = $this->session->userdata('user');

		$prev_notes= $this->developments_model->getPriviousDevelopmentsNotes($rid);
		$prev_notes_stage = $this->developments_model->getPriviousStageNotesInDev($rid);
      
      	echo $this->notes_image_tmpl($prev_notes,$prev_notes_stage);
        
      
      //echo '<p>Me : '.urldecode($note).'<br /> '.  date('d/m/Y g:i a').'<p>';
      
  }

  public function show_photo_notes_with_image($rid){ 
      
      
      $prev_notes= $this->developments_model->getPriviousDevelopmentphotoNotes($rid);
      echo $this->notes_image_tmpl($prev_notes);
        
      
      //echo '<p>Me : '.urldecode($note).'<br /> '.  date('d/m/Y g:i a').'<p>';
      
  }
  public function notes_image_tmpl($prev_notes,$prev_notes_stage){
      
      $user=  $this->session->userdata('user');          
      $user_id =$user->uid; 
      $this->load->model('user_model','',TRUE);
		$user_role = $this->user_model->user_app_role_load($user->uid);
		$user_role = $user_role->application_role_id;

      $align_class='';
      $tmpl='';
	  foreach ($prev_notes_stage as $notes) {
           
           
         $note_id= $notes->nid;   
        if($notes->notes_by==$user_id)
        {
           $showuser= 'Me'; 
           $notified_user= $this->developments_model->getNotifiedUserName($notes->notify_user_id);
           $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
           $align_class = 'right';
		   if($user_role!=3){
		   $delete = '';
		   }else{ 
		   $delete = '';
		   }
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
               $tmpl .= '<div class="'.$align_class.'"><span class="time-left">'.$creation_time.'</span><br/><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body">'.$notes->notes_body.'</div><div style="margin-left: 40px;float: left;">'.$notified_user.'</div>'.$delete.'</div>';
           }
           
           
           $tmpl .= '<div class="clear"></div>';

        }else{
            $showuser= $notes->username; 
            $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
            $align_class ='left';
            $notified_user= $this->developments_model->getNotifiedUserName($notes->notify_user_id);
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
                $tmpl .= '<div class="'.$align_class.'"><span class="time-right">'.$creation_time.'</span><br/><div class="useranother">'.$showuser.':</div>  <div style="float: left;" class="notes_body">'.$notes->notes_body.'</div><div style="margin-right: 30px;float: right;">'.$notified_user.'</div>'.$delete.'</div>'; 
            }
            
            $tmpl .= '<div class="clear"></div>';
        }
       } 

      foreach ($prev_notes as $notes) {
           
           
         $note_id= $notes->nid;   
        if($notes->notes_by==$user_id)
        {
           $showuser= 'Me'; 
           $notified_user= $this->developments_model->getNotifiedUserName($notes->notify_user_id);
           $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
           $align_class = 'right';
		   if($user_role!=3){
		   $delete = '<span class="del" onClick="notesDelete('.$note_id.')"> X </span>';
		   }else{ 
		   $delete = '';
		   }
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
               $tmpl .= '<div class="'.$align_class.'"><span class="time-left">'.$creation_time.'</span><br/><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body">'.$notes->notes_body.'</div><div style="margin-left: 40px;float: left;">'.$notified_user.'</div>'.$delete.'</div>';
           }
           
           
           $tmpl .= '<div class="clear"></div>';

        }else{
            $showuser= $notes->username; 
            $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
            $align_class ='left';
            $notified_user= $this->developments_model->getNotifiedUserName($notes->notify_user_id);
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
                $tmpl .= '<div class="'.$align_class.'"><span class="time-right">'.$creation_time.'</span><br/><div class="useranother">'.$showuser.':</div>  <div style="float: left;" class="notes_body">'.$notes->notes_body.'</div><div style="margin-right: 30px;float: right;">'.$notified_user.'</div>'.$delete.'</div>'; 
            }
            
            $tmpl .= '<div class="clear"></div>';
        }
       } 

	   
       return $tmpl;
      
  }
	public function show_notes($rid, $notify_user_id){
      	$user=  $this->session->userdata('user');  
      
		$note = $_GET['notes']; 

		$private = $_GET['private']; 
      
      	$user_id =$user->uid; 
      	$user_email = $user->email;
      	$user_name =$user->username;
      	$user_role= $user->rid;  
      	$note0= urldecode($note);       
      	$now = date('Y-m-d H:i:s');

		
		$note1 = str_replace("forward_slash", "/", $note0);
        $note2 = str_replace("sign_of_hash", "#", $note1);
        $note3 = str_replace("sign_of_intertogation", "?", $note2);
		$note4 = str_replace("sign_of_plus", "+", $note3);
		$note5 = str_replace("sign_of_exclamation", "!", $note4);
        $note6 = str_replace("percentage", "%", $note5);
		$note7 = str_replace("back_slash", "\\", $note6);
      
      	$note_body = $note7;
      
      	$request_info= $this->developments_model->getDevelopmentsInfo($rid);      
      	$request_title= $request_info->development_name;      
      	$request_created_by =$request_info->created_by;
      
          
      
      	$from= $user_email;
      	$notes_from = $user_name;
		$subject = 'You have a note from '.$notes_from;
	
	
        
        
        $notify_user_info=$this->developments_model->get_user_info($notify_user_id);        
        foreach ($notify_user_info as $user_info) {
                    //$user_name[]=$user->name;
                    $notify_user_email[]=$user_info->email;
                }
                //$assign_user_name= implode(", ", $user_name);
                $notify_user_to= implode(", ", $notify_user_email);
        
        
        $from2= $user_email;
        $notes_from2 = $user_name;
		$subject2 ='You have a note from '.$notes_from2;
	
		$headers2 = "From: ".$from2 . "\r\n";
		$headers2 .= "Reply-To: ". $notify_user_to . "\r\n";
		//$headers .= "CC: ". $cc . "\r\n";
		$headers2 .= "MIME-Version: 1.0\r\n";
		$headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
		$message2= '';
        $message2 .= '<html><body>';	
		$message2 .= "Hello, <strong>".$notes_from2."</strong> has added a new note. <br />";
        $message2 .= 'Development : '.$request_title.'<br />';
		$message2 .= "Note Description: " . $note_body . " <br />";
        $message2 .= " To view this conversation, follow this link: ".base_url()."developments/notes/".$rid."";
		$message2 .= "</body></html>";	
		//$msg_body='message body';
		$msg_body2=$message2;
        mail($notify_user_to, $subject2, $msg_body2, $headers2);

        $insert_note = $this->developments_model->insertNote($rid, $note0, $user_id, $notify_user_id, $now, $private);      
        $prev_notes= $this->developments_model->getPriviousDevelopmentsNotes($rid);
        echo $this->notes_image_tmpl($prev_notes); 
     
  	}
	public function notes_delete($pid, $noteid){ 
      
      $this->developments_model->deleteDevelopmentsNotes($noteid);
      $prev_notes= $this->developments_model->getPriviousDevelopmentsNotes($pid);
      echo $this->notes_image_tmpl($prev_notes);
      
  	}
	public function photo_action_featured($photo_id, $checked_val){
		
		$this->developments_model->update_photo_featured($photo_id, $checked_val);
	}
	public function photo_action_private($photo_id, $checked_val){
		
		$this->developments_model->update_photo_private($photo_id, $checked_val);
	}
	public function insert_photo_notes($rid, $notify_user_id){
      	$user=  $this->session->userdata('user');  
      
		$note = $_GET['notes']; 
      
      	$user_id =$user->uid; 
      	$user_email = $user->email;
      	$user_name =$user->username;
      	$user_role= $user->rid;  
      	$note0= urldecode($note);       
      	$now = date('Y-m-d H:i:s');

		
		
      
      	$note_body = $note;
		$insert_note = $this->developments_model->insertPhotoNote($rid, $note0, $user_id, $notify_user_id, $now);      
        $prev_notes= $this->developments_model->getPriviousDevelopmentphotoNotes($rid);
        echo $this->notes_image_tmpl($prev_notes); 
      

      	$request_info= $this->developments_model->getDevelopmentsInfo($rid);      
      	$request_title= $request_info->development_name;      
      	$request_created_by =$request_info->created_by;
      
          
      
      	$from= $user_email;
      	$notes_from = $user_name;
		$subject = 'You have a note from '.$notes_from;
	
	
        
        
        $notify_user_info=$this->developments_model->get_user_info($notify_user_id);        
        foreach ($notify_user_info as $user_info) {
                    //$user_name[]=$user->name;
                    $notify_user_email[]=$user_info->email;
                }
                //$assign_user_name= implode(", ", $user_name);
                $notify_user_to = implode(", ", $notify_user_email);
        
        
        $from2= $user_email;
        $notes_from2 = $user_name;
		$subject2 ='You have a note from '.$notes_from2;
	
		$headers2 = "From: ".$from2 . "\r\n";
		$headers2 .= "Reply-To: ". $notify_user_to . "\r\n";
		//$headers .= "CC: ". $cc . "\r\n";
		$headers2 .= "MIME-Version: 1.0\r\n";
		$headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
		$message2= '';
        $message2 .= '<html><body>';	
		$message2 .= "Hello, <strong>".$notes_from2."</strong> has added a new note. <br />";
        $message2 .= 'Development : '.$request_title.'<br />';
		$message2 .= "Note Description: " . $note_body . " <br />";
        $message2 .= " To view this conversation, follow this link: ".base_url()."developments/notes/".$rid."";
		$message2 .= "</body></html>";	
		//$msg_body='message body';
		$msg_body2=$message2;
        mail($notify_user_to, $subject2, $msg_body2, $headers2);
                     
  	}

	public function photo_notes_delete($pid, $noteid){ 
      
      $this->developments_model->deleteDevelopmentphotoNotes($noteid);
      $prev_notes= $this->developments_model->getPriviousDevelopmentphotoNotes($pid);
      echo $this->notes_image_tmpl($prev_notes);
      
  	}

	public function development_phase_delete($phase_id){      
      	$post = $this->input->post();
		$development_id = $post['development_id'];  
		$this->developments_model->development_phase_delete($phase_id);  
		redirect('developments/phases_underway/'.$development_id.'/'.$phase_id, 'refresh'); 
  	}

	public function development_phase_update($phase_id){

		$user = $this->session->userdata('user');
        
        if ($this->input->post('submit')){
			$post = $this->input->post();
			$development_id = $post['development_id'];
			if($post['planned_finished_date']){ $planned_finished_date = date("Y-m-d", strtotime($post['planned_finished_date'])); }else{ $planned_finished_date = '0000-00-00'; }
	        $update_phase = array(              	            
				'phase_name' => $post['phase_name'],
				'planned_start_date' => date("Y-m-d", strtotime($post['planned_start_date'])),
				'planned_finished_date' => $planned_finished_date,
				'phase_person_responsible' => $post['phase_person_responsible']
	        );

            $this->developments_model->development_phase_update($phase_id,$update_phase); 

			$phase = $post['phase_name'];
			$notify_user = $post['phase_person_responsible'];
			if(!empty($notify_user))
			{
 
		      	$user_email = $user->email;
		      	$user_name =$user->username;

				$notify_user_email = $this->developments_model->notify_one_user_info($notify_user);
				$notify_user_to = $notify_user_email->email;
				$notify_user_name = $notify_user_email->username;

				$dev_info = $this->developments_model->getDevelopmentsInfo($development_id);
				$dev_name = $dev_info->development_name;

				$subject ='New Responsibility in Development System';
			
				$headers = "From: ".$user_email . "\r\n";
				$headers .= "Reply-To: ". $user_email . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
				$message= '';
		        $message .= '<html><body>';	
				$message .= "Hello <strong>".$notify_user_name."</strong>,<br />";
		        $message .= 'You have been allocated '.$phase.' in '.$dev_name.'.<br />';
				$message .= "Please ensure you are monitoring your task(s) correctly.<br />";
		        $message .= "To view this allocation, follow this link: ".base_url()."developments/phases_underway/".$development_id."/".$phase_id;
				$message .= "</body></html>";	

				$msg_body=$message;
		        mail($notify_user_to, $subject, $msg_body, $headers);
			}          
	          	                                     
		} 
		echo 'phases_underway/'.$development_id.'/'.$phase_id;
        //redirect('developments/phases_underway/'.$development_id.'/'.$phase_id, 'refresh'); 
    }

	public function development_phase_add(){

		$user = $this->session->userdata('user');
        
        if ($this->input->post('submit')){
			$post = $this->input->post();
			$development_id = $post['development_id'];
			if($post['planned_finished_date']){ $planned_finished_date = date("Y-m-d", strtotime($post['planned_finished_date'])); }else{ $planned_finished_date = '0000-00-00'; }
	        $add_phase = array(   
				'development_id' => $development_id,           	            
				'phase_name' => $post['phase_name'],
				'planned_start_date' => date("Y-m-d", strtotime($post['planned_start_date'])),
				'planned_finished_date' => $planned_finished_date,
				'phase_person_responsible' => $post['phase_person_responsible']
	        );

            $id = $this->developments_model->development_phase_add($add_phase); 

			$phase = $post['phase_name'];
			$notify_user = $post['phase_person_responsible'];
			if(!empty($notify_user))
			{
 
		      	$user_email = $user->email;
		      	$user_name =$user->username;

				$notify_user_email = $this->developments_model->notify_one_user_info($notify_user);
				$notify_user_to = $notify_user_email->email;
				$notify_user_name = $notify_user_email->username;

				$dev_info = $this->developments_model->getDevelopmentsInfo($development_id);
				$dev_name = $dev_info->development_name;

				$subject ='New Responsibility in Development System';
			
				$headers = "From: ".$user_email . "\r\n";
				$headers .= "Reply-To: ". $user_email . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
				$message= '';
		        $message .= '<html><body>';	
				$message .= "Hello <strong>".$notify_user_name."</strong>,<br />";
		        $message .= 'You have been allocated '.$phase.' in '.$dev_name.'.<br />';
				$message .= "Please ensure you are monitoring your task(s) correctly.<br />";
		        $message .= "To view this allocation, follow this link: ".base_url()."developments/phases_underway/".$development_id."/".$id;
				$message .= "</body></html>";	

				$msg_body=$message;
		        mail($notify_user_to, $subject, $msg_body, $headers);
			} 
			echo 'phases_underway/'.$development_id.'/'.$id;
	        //redirect('developments/phases_underway/'.$development_id.'/'.$id, 'refresh');  	                                     
		} 
         
    }

	public function development_task_delete($task_id){      
      	$post = $this->input->post();
		$development_id = $post['development_id'];  
		$phase_id = $post['phase_id']; 
		$this->developments_model->development_task_delete($task_id);  
		redirect('developments/phases_underway/'.$development_id.'/'.$phase_id, 'refresh'); 
  	}

	public function development_task_update($task_id){
		
		$user = $this->session->userdata('user');
        
        if ($this->input->post('submit')){
			$post = $this->input->post();
			$development_id = $post['development_id'];
			$phase_id = $post['phase_id']; 
			if($post['actual_completion_date']){ $actual_completion_date = date("Y-m-d", strtotime($post['actual_completion_date'])); }else{ $actual_completion_date = '0000-00-00'; }
	        $update_task = array(              	            
				'task_name' => $post['task_name'],
				'task_start_date' => date("Y-m-d", strtotime($post['task_start_date'])),
				'actual_completion_date' => $actual_completion_date,
				'task_person_responsible' => $post['task_person_responsible'],
				'start_alert' => $post['start_alert']
	        );

            $this->developments_model->development_task_update($task_id,$update_task); 

			$task_name = $post['task_name'];
			$notify_user = $post['task_person_responsible'];
			if(!empty($notify_user))
			{
 
		      	$user_email = $user->email;
		      	$user_name =$user->username;

				$notify_user_email = $this->developments_model->notify_one_user_info($notify_user);
				$notify_user_to = $notify_user_email->email;
				$notify_user_name = $notify_user_email->username;

				$dev_info = $this->developments_model->getDevelopmentsInfo($development_id);
				$dev_name = $dev_info->development_name;

				$phase_info = $this->developments_model->getDevelopmentPhaseInfo($phase_id);
				$phase_name = $phase_info->phase_name;

				$subject ='New Responsibility in Development System';
			
				$headers = "From: ".$user_email . "\r\n";
				$headers .= "Reply-To: ". $user_email . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
				$message= '';
		        $message .= '<html><body>';	
				$message .= "Hello <strong>".$notify_user_name."</strong>,<br />";
		        $message .= 'You have been allocated '.$task_name.' under '.$phase_name.' in '.$dev_name.'.<br />';
				$message .= "Please ensure you are monitoring your task(s) correctly.<br />";
		        $message .= "To view this allocation, follow this link: ".base_url()."developments/phases_underway/".$development_id."/".$phase_id;
				$message .= "</body></html>";	

				$msg_body=$message;
		        mail($notify_user_to, $subject, $msg_body, $headers);
			}     
	          	                                     
		} 
		echo 'phases_underway/'.$development_id.'/'.$phase_id;
        //redirect('developments/phases_underway/'.$development_id.'/'.$phase_id, 'refresh'); 
    }

	public function development_task_add(){

		$user = $this->session->userdata('user');
        
        if ($this->input->post('submit')){
			$post = $this->input->post();
			$development_id = $post['development_id'];
			$phase_id = $post['phase_id']; 
			if($post['actual_completion_date']){ $actual_completion_date = date("Y-m-d", strtotime($post['actual_completion_date'])); }else{ $actual_completion_date = '0000-00-00'; }
	        $add_task = array(   
				'development_id' => $development_id,
				'phase_id' => $phase_id,           	            
				'task_name' => $post['task_name'],
				'task_person_responsible' => $post['task_person_responsible'],
				'task_start_date' => date("Y-m-d", strtotime($post['task_start_date'])),
				'actual_completion_date' => $actual_completion_date
	        );

            $this->developments_model->development_task_add($add_task);   

			$task_name = $post['task_name'];
			$notify_user = $post['task_person_responsible'];
			if(!empty($notify_user))
			{
 
		      	$user_email = $user->email;
		      	$user_name =$user->username;

				$notify_user_email = $this->developments_model->notify_one_user_info($notify_user);
				$notify_user_to = $notify_user_email->email;
				$notify_user_name = $notify_user_email->username;

				$dev_info = $this->developments_model->getDevelopmentsInfo($development_id);
				$dev_name = $dev_info->development_name;

				$phase_info = $this->developments_model->getDevelopmentPhaseInfo($phase_id);
				$phase_name = $phase_info->phase_name;

				$subject ='New Responsibility in Development System';
			
				$headers = "From: ".$user_email . "\r\n";
				$headers .= "Reply-To: ". $user_email . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
				$message= '';
		        $message .= '<html><body>';	
				$message .= "Hello <strong>".$notify_user_name."</strong>,<br />";
		        $message .= 'You have been allocated '.$task_name.' under '.$phase_name.' in '.$dev_name.'.<br />';
				$message .= "Please ensure you are monitoring your task(s) correctly.<br />";
		        $message .= "To view this allocation, follow this link: ".base_url()."developments/phases_underway/".$development_id."/".$phase_id;
				$message .= "</body></html>";	

				$msg_body=$message;
		        mail($notify_user_to, $subject, $msg_body, $headers);
			}   	                                     
		} 
		echo 'phases_underway/'.$development_id.'/'.$phase_id;
        //redirect('developments/phases_underway/'.$development_id.'/'.$phase_id, 'refresh'); 
    }

	public function show_developments_featured_photo($did){
		
		$photo = $this->developments_model->get_developments_featured_photo($did); 
		
		//echo $photo;
	}

	public function development_document_update($id){
        
        if ($this->input->post('submit')){
			$post = $this->input->post();
			$development_id = $post['development_id'];
			$update = array(   
				'filename_custom' => $post['filename_custom']
	        );

            $this->developments_model->development_document_update($id,$update);            
	          	                                     
		} 
        redirect('developments/development_documents/'.$development_id, 'refresh'); 
    }

	function allocation_email_notification($development_id,$stage_no,$person_responsible){

		$now = date('Y-m-d'); 
		$person_responsible = explode(',',$person_responsible);
		$users = $this->developments_model->allocation_email_notification_user($development_id,$stage_no)->result();	
	
		foreach($users as $user){
			$user_id = $user->uid;
			$user_name = $user->username;
			$user_email = $user->email;

			if(in_array($user_id, $person_responsible)){
				$sql_stage = "SELECT stage_task.*,development.development_name,stage_phase.phase_name 
								FROM stage_task 
								LEFT JOIN development ON stage_task.development_id=development.id
								LEFT JOIN stage_phase ON stage_task.phase_id=stage_phase.id
								LEFT JOIN users ON stage_task.task_person_responsible=users.uid
								WHERE stage_task.development_id = '$development_id' && stage_task.stage_no = '$stage_no' && stage_task.stage_task_status = '0' && stage_task.task_start_date > '0000-00-00' && stage_task.task_person_responsible = '$user_id'";

				$result_stage = $this->db->query($sql_stage)->result();
				if($result_stage){
					$j = 1;
					$alert = '';
					foreach($result_stage as $result){
						$task_start_date = date('Y-m-d', strtotime($result->task_start_date. ' - 10 days'));
						if($now >= $task_start_date){
							$alert .= $j.'. '.$result->development_name.' - Stage '.$result->stage_no.' - '.$result->phase_name.' - '.$result->task_name.' starting on '.date("d-m-Y",strtotime($result->task_start_date)).'<br>';
							$j++;
						}
					}
	
					$html = '<html><body>';
					$html .= 'Hi '.$user_name.',<br><br>';
					$html .= 'This is a friendly reminder for you that you will have:<br>';
					$html .= $alert;
					$html .= "</body></html>";

					$subject ='New Responsibility in Development System';
						
					$headers = "From: ".$user_email . "\r\n";
					$headers .= "Reply-To: ". $user_email . "\r\n";
					$headers .= "MIME-Version: 1.0\r\n";
					$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
			        mail($user_email, $subject, $html, $headers);
				}
			}	
		}             
	}


}