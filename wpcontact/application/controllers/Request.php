<?php 
class Request extends CI_Controller {
	
	private $limit = 20;
	
	function __construct() {
		
		parent::__construct();
		
		
		
		$this->load->model('request_model','',TRUE);
		$this->load->model('notes_model','',TRUE);
		$this->load->library('wbs_helper');
		$this->load->library('breadcrumbs');
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->helper(array('form', 'url','email'));
		
		
		date_default_timezone_set("NZ");
		
			$redirect_login_page = base_url().'user';
			if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
			}
	}
        
        public function index(){
            $data['title'] = 'Requests';
            $data['maincontent'] = $this->load->view('requests',$data,true);
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }
        
        public function request(){
            $data['title'] = 'Requests';
            $data['maincontent'] = $this->load->view('project',$data,true);
		
            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
        }
     public function clear_search(){

		$this->session->unset_userdata('searchvalue');
		redirect('request/request_list');
	}  
       
	//http://localhost/wbs/request/request_list?
        //request_no=&
        //request_title=&
        //request_status=0&
        //request_priority=0&
        //project_id=0&
        //assign_manager_id=0&
        //assign_developer_id=10
        //&submit=Find+Request
    public function request_list($sort_by = 'request_no', $order_by = 'desc', $offset = 0) {
		$user=  $this->session->userdata('user');       
            $user_id =$user->uid; 
            $role_id = $user->rid;
            
            $data['title'] = 'Tasks';
            $post = $_POST;
            if(!empty($post)){
                /*set search session*/
                $searchdata['searchvalue']=$post;
                $this->session->set_userdata($searchdata);
            }else{
                // echo 'empty';
            }
            
             $searchvalue=  $this->session->userdata('searchvalue'); 
             
            
            //if (count($_GET) > 0) $config['suffix'] = '?' . http_build_query($searchvalue, '', "&");
           if (!empty($searchvalue))$config['suffix'] = '?' . http_build_query($searchvalue, '', "&");
           
            
            
            //$requests = $this->request_model->request_list_search_count($sort_by,$order_by,$offset,$this->limit,$user_id, $role_id, $get)->result();
            $requests = $this->request_model->request_list_search_count($sort_by,$order_by,$offset,$this->limit,$user_id, $role_id, $searchvalue)->result();

            //$config['base_url'] = site_url("request/request_list/$sort_by/$order_by");
            $config['base_url'] = site_url("request/request_list/$sort_by/$order_by");
            //$config['first_url'] = $config['base_url'].'?'.http_build_query($_GET);
            if (!empty($searchvalue)){
                $config['first_url'] = $config['base_url'].'?'.http_build_query($searchvalue);
            }else{
                $config['first_url'] = $config['base_url'];
            }
            $data['action'] = $config['base_url'];
            //$config['total_rows'] = $this->request_model->request_list_search_count_all($user_id, $role_id, $get);
            $config['total_rows'] = $this->request_model->request_list_search_count_all($user_id, $role_id, $searchvalue);
            $config['per_page'] = $this->limit;
            $config['uri_segment'] = 5;
            $config['num_links'] = 10;
            $this->load->library('pagination');
            $this->pagination->initialize($config);
            $data['pagination'] = $this->pagination->create_links();

            $this->load->library('table');
            $this->table->set_empty("");
            $this->table->set_caption($config['total_rows'] . ' Records found.');                


            $order_by = ($order_by == 'desc' ?  'asc' : 'desc');               
            $sl = $offset + 1;
            $sort_url = 'request/request_list';


            $title_link = anchor("$sort_url/request_title/$order_by/$offset", 'Title');
            $title = array('data' => $title_link, 'class' => 'Title', 'width' =>'20%');
            
            $description = array('data' => 'Description', 'class' => 'description', 'width' =>'30%');
            $project_name = array('data' => 'Project', 'class' => 'hidden599', 'width' =>'20%');


            $this->table->set_heading(
            anchor("$sort_url/request_date/$order_by/$offset", 'Date'),
            array ( 'data'=>anchor("$sort_url/id/$order_by/$offset", 'Task Id'), 'class'=>'hidden599 hidden767'),
            $title,
            $project_name, 
            array ( 'data'=>'Company', 'class'=>'hidden599 hidden767'),
            array ( 'data'=>'Contact', 'class'=>'hidden599'),
            array ( 'data'=>'Priority', 'class'=>'hidden599 hidden767'),           
			'Status',
			''
            );
            foreach ($requests as $request){
                //$request_is_new = $this->request_model->request_list_is_new($request->id, $user_id, $role_id);
                   $priority = $request->priority;
                   if($priority==1){$show_priority='High';}
                   elseif($priority==2){$show_priority='Normal';}
                   elseif($priority==3){$show_priority='Low';}
                   $assign_manager= $this->request_model->get_assign_manager($request->assign_manager_id);

                    $this->table->add_row(
                        $request->request_date,
                        array ( 'data'=>$request->id, 'class'=>'hidden599 hidden767'),
			//$request->request_title,
			anchor(base_url().'request/request_detail/'.$request->id, $request->request_title ,array('title'=>$request->request_description, 'data-toggle'=>'tooltip', 'class'=>'mytooltip')),
			//$request->request_description,
			array ( 'data'=>$request->project_name, 'class'=>'hidden599'),
                        array ( 'data'=>$request->company_name, 'class'=>'hidden599 hidden767'),
						
			array ( 'data'=>implode(", ", $assign_manager), 'class'=>'hidden599'),
			array ( 'data'=>$show_priority, 'class'=>'hidden599 hidden767'),
                       
			$request->request_status==2? 'Closed': 'Open',
			//anchor('request/request_update/'.$request->id,'update',array('class'=>'update','title'=>'Update')).' '.
                        //anchor('request/request_delete/'.$request->id,'delete',array('title'=>'Delete','class'=>'delete','onclick'=>"return confirm('Are you sure you want to remove this Company?')"))
			anchor('request/request_detail/'.$request->id, ' <span class="request_button_plus">+</span> ',array('title'=>'Click To View Details'))
                    ); $sl++;
            }
           
            
            $tmpl = array (
                'table_open'          => '<table border="0" cellpadding="2" cellspacing="1" class="table table-hover">',

                'table_close'         => '</table>'
            );

            $this->table->set_template($tmpl);
            $data['table'] = $this->table->generate();

            $data['maincontent'] = $this->load->view('request_list',$data,true);

            $this->load->view('includes/header',$data);
            //$this->load->view('includes/sidebar',$data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer',$data);
    }
	
    public function request_add($pid=0, $cid=0) {
            
        if ($pid > 0){
                $data['project_id']=$pid;
        }
         if ($cid > 0){
                $data['company_id']=$cid;
        }
            
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
        $user_name= $user->username;
        $user_email= $user->email;

        $data['title'] = 'Add Task';
        $data['action'] = site_url('request/request_add/'.$pid);

        $set_request_no=  $this->request_model->get_request_no();  
        
        $this->_set_rules();

        if ( $this->form_validation->run() === FALSE ) {
                // print_r('error 1'); 
                $data['maincontent'] = $this->load->view('request_add',$data,true);		
                $this->load->view('includes/header',$data);
                //$this->load->view('includes/sidebar',$data);
                $this->load->view('home',$data);
                $this->load->view('includes/footer',$data);

        }else {

            $post = $this->input->post();
            $config['upload_path'] = UPLOAD_FILE_PATH_REQUEST_DOCUMENT;
            $config['allowed_types'] = '*';

            $this->load->library('upload', $config);
            $this->upload->initialize($config);

            $document_insert_id = 0;
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
                $document_insert_id = $this->request_model->file_insert($document);                        
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
                $image_insert_id = $this->request_model->file_insert($image);                        
            }else{
                // print 'error in image uploading...'; 
                // print $this->upload->display_errors() ;  
            } 
            
            $select_manager = $this->input->post('assign_manager_id');            
            if($select_manager == ''){$select_manager_id=0;}
            else{ $select_manager_id = implode(",", $select_manager);}

            $select_developer = $this->input->post('assign_developer_id');            
            if($select_developer == ''){$select_developer_id=0;}
            else{ $select_developer_id = implode(",", $select_developer);}
            
            $request_data = array(
                    'request_no' => $set_request_no+1,
                    'request_date' => $this->wbs_helper->to_mysql_date($this->input->post('request_date')),
                    'request_title' => $this->input->post('request_title'),
                    'request_description' =>$this->input->post('request_description'),	
                    'company_id' => $this->input->post('company_id'),

                    'project_id' => $this->input->post('project_id'),
                    'assign_manager_id' => $select_manager_id,
                    'assign_developer_id' => $select_developer_id,
                    'priority' => $this->input->post('priority'),                          
                    
                    'estimated_completion' => $this->wbs_helper->to_mysql_date($this->input->post('estimated_completion')),
                    'request_status' => $this->input->post('request_status'),  

                    'document_id' =>$document_insert_id,
                    'image_id' =>$image_insert_id,                    
                    'created' => date("Y-m-d H:i:s"),
                    'created_by' =>$user_id
                ); 
            

                $id = $this->request_model->request_save($request_data);
                // set form input name="id"
                
                    
                $request_user_info= $this->request_model->get_request_user_info($id);
                
                $project_name= $request_user_info->project_name;
                $request_no= $request_user_info->request_no;
                $request_title= $request_user_info->request_title;
                $request_description= $request_user_info->request_description;
                $request_created_by =$request_user_info->created_by;
                
                $assign_manager_id = $request_user_info->assign_manager_id;
                $assign_developer_id = $request_user_info->assign_developer_id;
                
                
                $assign_manager_info=$this->request_model->get_manager_info($assign_manager_id);              
                
                foreach ($assign_manager_info as $manager) {
                    $manager_name[]=$manager->username;
                    $manager_email[]=$manager->email;
                }
                $assign_manager_name= implode(", ", $manager_name);
                $assign_manager_email= implode(", ", $manager_email);
                //print_r($assign_manager);    print_r($assign_manager_email); exit;
                
                $assign_developer_info=$this->request_model->get_developer_info($assign_developer_id);
                foreach($assign_developer_info as $developer){
                    $developer_name[] =$developer->username;
                    $developer_email[] = $developer->email;
                }
                $assign_developer_name= implode(", ", $developer_name);
                $assign_developer_email= implode(", ", $developer_email);
                //print_r($assign_developer_name);    print_r($assign_developer_email); exit;
               
                $dev_name=$assign_developer_name;
                $dev_email=$assign_developer_email;          
                $staff_name= $assign_manager_name;
                $staff_email=$assign_manager_email;
                
                
                $to=$staff_email;
                $cc=$dev_email;
                $from= $user_email;
                
                $request_from = $user_name;
                $subject = 'You have a Request from '.$request_from.' on Project: '.$project_name;

                $headers = "From: ".$from . "\r\n";
                $headers .= "Reply-To: ". $to . "\r\n";
                $headers .= "CC: ". $cc . "\r\n";
                $headers .= "MIME-Version: 1.0\r\n";
                $headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

                $message= '';
                $message .= '<html><body>';	
                $message .= '<table border="0" rules="all" style="border-color: #666;" cellpadding="10">';
                /* Information */

                $message .= "<tr><td><strong>Request No :</strong> </td><td>" . $request_no . "</td></tr>";
                $message .= "<tr><td><strong>Request Title:</strong> </td><td>" . $request_title . "</td></tr>";
                $message .= "<tr><td><strong>Request Description :</strong> </td><td>" . $request_description . "</td></tr>";
                $message .= "<tr><td><strong>Assign Manager :</strong> </td><td>" .$staff_name . "</td></tr>";	
                $message .= "<tr><td><strong>Assign Contractor :</strong> </td><td>" .$dev_name . "</td></tr>";	
                $message .= "<tr><td><strong>Request By :</strong> </td><td>" .$request_created_by . "</td></tr>";
                $message .= "<tr><td><strong>Url :</strong> </td><td> ".base_url()."</td></tr>";

                $message .= "</table>";
                $message .= "</body></html>";	
                //$msg_body='message body';
                $msg_body=$message;      
                
                mail($to, $subject, $msg_body, $headers);
                $this->session->set_flashdata('success-message', 'Task Successfully Added.');       
		
                    if ($pid > 0){                             
                       redirect('project/project_detail/'.$pid);
                   }else{                           
                       redirect('request/request_list');
                   }

           } 
    }
	
	public function request_delete($rid){
		// delete request
		$this->request_model->delete_request($rid);
		// redirect to request list page
                $this->session->set_flashdata('warning-message', 'Task Successfully Removed.');
		redirect('request/request_list');
	}
        public function request_close($rid){
		// delete request
		$this->request_model->close_request($rid);
		// redirect to request list page
                $this->session->set_flashdata('warning-message', 'Task Successfully Closed.');
		redirect('request/request_detail/'.$rid);
	}
        public function request_open($rid){
		// delete request
		$this->request_model->open_request($rid);
		// redirect to request list page
                $this->session->set_flashdata('success-message', 'Task Successfully Opened.');
		redirect('request/request_detail/'.$rid);
	}
        
       
	
    function request_update($id){
		
        $data['title'] = 'Update Task';
	$data['action'] = site_url('request/request_update/'.$id);  
        
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
		
	$this->_set_rules();
        
	// run validation
	if ($this->form_validation->run() === FALSE){
			
        $data['request'] = $this->request_model->get_request_detail($id)->row();
		
	}else{  
            
                    $post = $this->input->post();
                    $config['upload_path'] = UPLOAD_FILE_PATH_REQUEST_DOCUMENT;
                    $config['allowed_types'] = '*';

                    $this->load->library('upload', $config);
                    $this->upload->initialize($config);
		    
                    //$file_insert_id = 0;
                    $document_id = $post['document_id'];
                    
                   
                    
                    if ($this->upload->do_upload('upload_document')){
                        $upload_data = $this->upload->data();
                        //print_r($upload_data); exit; 
                        // insert data to file table                        		
                        $file = array(
                            'filename'=>$upload_data['file_name'],
                            'filetype'=>$upload_data['file_type'],
                            'filesize'=>$upload_data['file_size'],
                            'filepath'=>$upload_data['full_path'],
                            //'filename_custom'=>$post['upload_document'],
                            'updated'=>date("Y-m-d H:i:s"),
                            'uid'=>$user_id
                        );
                        if($document_id > 0 ){
                            $file_update_id = $this->request_model->request_file_update($document_id, $file); 
			} else {
                            $document_insert_id = $this->request_model->file_insert($file);
			}    
						                     
                    }else{
                       // print 'error in file uploading...'; 
                       // print $this->upload->display_errors() ; 
                    }
                    
                    
                    
                    //$file_insert_id2 = 0;
                    $image_id = $post['image_id'];
                    if ($this->upload->do_upload('upload_image')){
                        $upload_data = $this->upload->data();
                        // print_r($upload_data); 
                        // insert data to file table                       
                        $file = array(
                            'filename'=>$upload_data['file_name'],
                            'filetype'=>$upload_data['file_type'],
                            'filesize'=>$upload_data['file_size'],
                            'filepath'=>$upload_data['full_path'],
                            //'filename_custom'=>$post['upload_filename'],
                            'updated'=>date("Y-m-d H:i:s"),
                            'uid'=>$user_id
                        );
			if($image_id > 0 ){
                            $file_update_id = $this->request_model->request_file_update($image_id, $file); 
			} else {
                            $image_insert_id = $this->request_model->file_insert($file);
			}
                                                
                    }else{
                        // print 'error in file uploading...'; 
                        // print $this->upload->display_errors() ; 
                    }
                    
                        
            // save data
            $request_update = array(
                'request_no' => $this->input->post('request_no'),
                'request_date' => $this->wbs_helper->to_mysql_date($this->input->post('request_date')),
                'request_title' => $this->input->post('request_title'),
                'request_description' =>$this->input->post('request_description'),
                'company_id' => $this->input->post('company_id'),
                'project_id' => $this->input->post('project_id'),
                'assign_manager_id' => implode(",", $this->input->post('assign_manager_id')),
                'assign_developer_id' => implode(",", $this->input->post('assign_developer_id')),
                'priority' => $this->input->post('priority'),                            
                //'request_doc' => $this->input->post('request_doc'),
                'estimated_completion' => $this->wbs_helper->to_mysql_date($this->input->post('estimated_completion')),
                'request_status' => $this->input->post('request_status'),
                'document_id' =>($document_id == 0)?$document_insert_id : $document_id, 
		'image_id' =>($image_id == 0)?$image_insert_id : $image_id,
                'updated' => date("Y-m-d H:i:s"),
                'updated_by' =>$user_id		
            );
            //var_dump($Student);
            $this->request_model->update($id,$request_update);
            //$data['request'] = (array)$this->request_profile_model->get_by_cid($cid)->row();
            $this->session->set_flashdata('success-message', 'Task Successfully Updated.');
            redirect('request/request_detail/'.$id);
	}		
        // load view
        $data['maincontent'] = $this->load->view('request_add',$data,true);
        

        $this->load->view('includes/header',$data);
        //$this->load->view('includes/sidebar',$data);
        $this->load->view('home',$data);
        $this->load->view('includes/footer',$data);
    }
	
    function _set_rules(){
        //$this->form_validation->set_rules('compname', 'compname', 'trim|required|is_unique[request_profile.compname]');
        $this->form_validation->set_rules('request_title', 'Title', 'required');
        $this->form_validation->set_rules('assign_manager_id[0]', 'Assign Manager', 'callback_assign_manager_id_check');
	$this->form_validation->set_rules('company_id', 'Project Company', 'callback_company_id_check');
	$this->form_validation->set_rules('project_id', 'Project', 'callback_project_id_check');

            //$this->form_validation->set_rules('request_no', 'Request No', 'required|min_length[5]|max_length[12]');
            //$this->form_validation->set_rules('email_addr_1', 'Email', 'required|valid_email|is_unique[request_profile.email_addr_1]');
    }
    public function assign_manager_id_check($str)
	{
            
		if ($str == '')
		{
			$this->form_validation->set_message('assign_manager_id_check', 'Task should be assigned at least One Manager');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function company_id_check($str)
	{
		if ($str == 0)
		{
			$this->form_validation->set_message('company_id_check', 'Select Project Company');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
	public function project_id_check($str)
	{
		if ($str == 0)
		{
			$this->form_validation->set_message('project_id_check', 'Select Project');
			return FALSE;
		}
		else
		{
			return TRUE;
		}
	}
		
   

   function request_detail($request_id=0){
		
		if ($request_id <=0){
             redirect('request/request_list');
        }
		 $user=  $this->session->userdata('user');          
		 $user_id =$user->uid;    
		 $request_is_new = $this->request_model->request_is_new($request_id, $user_id); 
         
        $request = $this->request_model->get_request_detail($request_id)->row(); 
        $assign_manager = $this->request_model->get_assign_manager($request->assign_manager_id);
        $assign_developer = $this->request_model->get_assign_developer($request->assign_developer_id);
       
        $data['title'] = 'Tasks';
        $data['request'] = $request;
		$data['assign_manager'] = $assign_manager;
		$data['assign_developer'] = $assign_developer;
        //$this->load->library('table');
        //$this->table->set_empty("");
        
        
        //if($request->priority==1){$priority = 'High';}
        //elseif ($request->priority==2) { $priority = 'Normal'; }
        //elseif ($request->priority==3) { $priority = 'Low'; }

		//$date_cell = array('data' => 'Date', 'class' => '', 'width' => "25%");

        //$this->table->add_row($date_cell, date('d/m/Y', strtotime($request->request_date)));  
        //$this->table->add_row('Request No',$request->request_no); 
        //$this->table->add_row('Priority', $priority);  
        //$this->table->add_row('Description',$request->request_description); 
       
        //$this->table->add_row('Documents', $request->document==''? 'No Document':'<a  href="'.base_url().'uploads/request/document/'.$request->document.'">'.$request->document.'</a>'); 
         
        //$this->table->add_row('Image', $request->image==''? 'No Image':'<a id="fancybox" href="'.base_url().'uploads/request/document/'.$request->image.'"><img width="30" height="30" src="'.base_url().'uploads/request/document/'.$request->image.'" title="'.$request->image.'" alt="'.$request->image.'"/></a>'); 
        //$this->table->add_row('Project', $request->project_name); 
        //$this->table->add_row('Company', $request->company_name);

        //$this->table->add_row('Assign Manager', implode(", ", $assign_manager)); 
        //$this->table->add_row('Assign Contractor', implode(", ", $assign_developer)); 
        //$this->table->add_row('Created By', $request->created_by); 				
        //$this->table->add_row('Estimated Completion', date('d/m/Y', strtotime($request->estimated_completion)));       

        //$this->table->add_row('Status', $request->request_status==2 ? 'Closed':'Open'); 
       // $this->table->add_row('Created by',$request->created_by);                                       

            //$data['table'] = $this->table->generate();  

		$prev_notes = $this->notes_model->getPriviousNotes($request_id);
		$data['prev_notes'] = $this->notes_image_tmpl($prev_notes);

			$data['maincontent']=$this->load->view('request_detail',$data,true);
		  
            $this->load->view('includes/header', $data);
            //$this->load->view('includes/sidebar', $data);
            $this->load->view('home',$data);
            $this->load->view('includes/footer', $data);
    }
    public function project_by_company($company_id){
        
        $projects = $this->request_model->get_project_by_company($company_id);
        $options = '<option value="0">-- Select Project --</option>';
        foreach ($projects as $project_id=>$project_name) {
                $options .= '<option value="'.$project_id.'"> ' . $project_name . ' </option>' ; 
        }
        print $options;
    }
    public function company_by_project($project_id){
        
        $company = $this->request_model->get_company_by_project($project_id);
        
        if(empty($company)){echo 0;}else{
        echo $company->company_id;}
    }
	

    public function notes_image_tmpl($prev_notes){
      
      $user=  $this->session->userdata('user');          
      $user_id =$user->uid; 
      
      $align_class='';
      $tmpl='';
       foreach ($prev_notes as $notes) {
           
           
           
        if($notes->notes_by==$user_id)
        {
           $showuser= 'Me'; 
           $notified_user= $this->notes_model->getNotifiedUserName($notes->notify_user_id);
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
                   $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body"><a class="fancybox" href="'.base_url().'uploads/notes/images/'.$show_file->filename.'"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></a></div><div style="float:right;"><span class="time-left">'.$creation_time.'</span></div> </div>'; 
                }
                else
                {
                    //this is file not image
                    $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body"><a href="'.base_url().'document/download_notefile/'.$show_file->filename.'">'.$file_name.'</a></div><div style="float:right"><span class="time-left1">'.$creation_time.'</span></div> </div>';
                }
               
               
           }else{
               $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body">'.$notes->notes_body.'</div><div style="float:right;">'.$notified_user.'<span class="time-left1">'.$creation_time.'</span></div> </div>';
           }
           
           
           $tmpl .= '<div class="clear"></div>';

        }else{
            $showuser= $notes->username; 
            $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
            $align_class ='left';
            $notified_user= $this->notes_model->getNotifiedUserName($notes->notify_user_id);
            if(!$notes->notes_image_id == null){
                $show_file= $this->notes_model->getNotesImage($notes->notes_image_id);
                $file_name= $show_file->filename;
               $allowedExts = array("gif", "jpeg", "jpg", "png");
                $temp = explode(".", $file_name);
                $extension = end($temp);
                if(in_array($extension, $allowedExts)){
                    //this is image
                    $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div> <div style="float: left;" class="notes_body"><a class="fancybox" href="'.base_url().'uploads/notes/images/'.$show_file->filename.'"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></a></div><div style="float:left"><span class="time-right1">'.$creation_time.'</span></div> </div>';
                }else{
                    //this is file, not image
                    $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div> <div style="float: left;" class="notes_body"><a href="'.base_url().'document/download_notefile/'.$show_file->filename.'">'.$file_name.'</a></div><div style="float:left"><span class="time-right1">'.$creation_time.'</span></div> </div>';
                }
            }
            else{
                $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div>  <div style="float: left;" class="notes_body">'.$notes->notes_body.'</div><div style="float: left;"><span class="time-right1">'.$creation_time.'</span>'.$notified_user.'</div> </div>'; 
            }
            
            $tmpl .= '<div class="clear"></div>';
        }
       } 
       return $tmpl;
      
	}
	
   function request_hour($id){
		
        $data['title'] = 'Task Hours';        
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid;
        
        $data['request_id'] = $id;
        $data['request_name'] = $this->request_model->get_request_name($id);
        
        $data['total_hours'] = $this->request_model->total_hour($id);	
	$data['task_hours'] = $this->request_model->request_hour($id)->result();
        $data['maincontent'] = $this->load->view('request_hour',$data,true);
        
        $this->load->view('includes/header',$data);
        $this->load->view('home',$data);
        $this->load->view('includes/footer',$data);
    }
    
    function request_hour_save(){
    	$user1 =  $this->session->userdata('user');          
        $user_id =$user1->uid; 
        
		if($_GET["contractor"]=='undefined'){ $contractor = ''; }else{ $contractor = $_GET["contractor"]; }
		if($_GET["user"]=='undefined'){ $user = ''; }else{ $user = $_GET["user"]; }
		if($_GET["hour"]=='undefined'){ $hour = ''; }else{ $hour = $_GET["hour"]; }
		if($_GET["minute"]=='undefined'){ $minute = ''; }else{ $minute = $_GET["minute"]; }
		if($_GET["note"]=='undefined'){ $note = ''; }else{ $note = $_GET["note"]; }
		if($_GET["week_start_date"]=='undefined'){ $week_start_date = ''; }else{ $week_start_date = $_GET["week_start_date"]; }
		
		$task_id = $_GET["task_id"];
		
		$add = array(
            'contractor_id' => $contractor,
    		'user_id' => $user,
    		'hour' => $hour,
    		'minute' => $minute,
    		'note' => $note,
    		'week_start_date' => date('Y-m-d', strtotime($week_start_date)),
    		'task_id' => $task_id,       
            'created' => date("Y-m-d H:i:s"),
            'created_by' =>$user_id		
        );
        $this->request_model->request_hour_save($add);
    }
	
         
        
   
    
	
}