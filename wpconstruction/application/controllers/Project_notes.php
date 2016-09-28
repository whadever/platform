<?php
// main ajax back end
class Project_Notes extends CI_Controller {
  
  function __construct(){
        parent::__construct();
        
        
        $this->load->model('project_notes_model','',TRUE);
        $this->load->library('breadcrumbs');
		$this->load->helper(array('form', 'url', 'file'));
        date_default_timezone_set("NZ");

		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}
    }
	
	
  public function index($project_id='')
  {
      
      //echo $project_id;
      $data['title'] = 'Project Notes'; 
      $data['project_id']= $project_id;
      
      $data['project_info']= $this->project_notes_model->getProjectInfo($project_id);  
      
      $prev_notes = $this->project_notes_model->getPriviousProjectNotes($project_id);
      $data['prev_notes'] = $this->notes_image_tmpl($prev_notes);
      
      // load view
		$data['maincontent'] = $this->load->view('project_notes_view',$data,true);
		
		$this->load->view('includes/header',$data);
		//$this->load->view('includes/sidebar',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
  
  	
  } 
  
  
  public function show_notes($pid, $notify_user_id){
      $user=  $this->session->userdata('user');  
      //print_r($user);
      
      $user_id =$user->uid; 
      $user_email = $user->email;
      $user_name =$user->username;
      $user_role= $user->rid;  
      $note_body= $_GET['notes'];    
      $now = date('Y-m-d H:i:s');
      
      
      
      $project_info= $this->project_notes_model->get_project_user_info($pid);
      $project_no= $project_info->project_no;      
      $project_name = $project_info->project_name;
      $project_created_by =$project_info->created_by;
      
       
        
        
        $notify_user_info=$this->project_notes_model->get_user_info($notify_user_id); 
		$notify_user_email=array();       
        foreach ($notify_user_info as $user_info) {
                    //$user_name[]=$user->name;
                    $notify_user_email[]=$user_info->email;
                }
                //$assign_user_name= implode(", ", $user_name);
                $notify_user_to= implode(", ", $notify_user_email);
        
        
               
        $from= $user_email;
        $notes_from = $user_name;
	$subject = 'Hi, You have a notification from '.$notes_from.' on project -'.$project_name;
	
	$headers2 = "From: ".$from. "\r\n";
	$headers2 .= "Reply-To: ". $notify_user_to . "\r\n";
	//$headers .= "CC: ". $cc . "\r\n";
	$headers2 .= "MIME-Version: 1.0\r\n";
	$headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	$message2= '';
        $message2 .= '<html><body>';	
	$message2 .= "Hello, <strong>".$notes_from."</strong> has added a new note on project '".$project_name."'<br />";
        $message2 .= "Note Description: " . $note_body . " <br />";
        $message2 .= " To view this conversation, follow this link: http://williamscorporation.co.nz/tms/project_notes/index/".$pid."";
	$message2 .= "</body></html>";	
	//$msg_body='message body';
	$msg_body2=$message2;
        mail($notify_user_to, $subject, $msg_body2, $headers2);
               
        $insert_note = $this->project_notes_model->insertProjectNote($pid, $note_body, $user_id, $notify_user_id, $now);      
        $prev_notes= $this->project_notes_model->getPriviousProjectNotes($pid);
        echo $this->notes_image_tmpl($prev_notes);           
      
  }
  
  
  public function upload_note_image(){
        $project_id= $_POST['project_id'];  
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid; 

        // print_r($_FILES); //return; 
        //$post = $this->input->post();

        $config['upload_path'] = UPLOAD_NOTES_PATH_IMAGE_FILE;
        $config['allowed_types'] = '*';


        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        $document_insert_id = 0;
        if ($this->upload->do_upload('note_image')){
            $upload_data = $this->upload->data();
           // echo $upload_data->file_name;
            //print_r($upload_data); 
            // insert data to file table
            // get latest id from frim table and insert it to loan table
            $document = array(
                'filename'=>$upload_data['file_name'],
                'filetype'=>$upload_data['file_type'],
                'filesize'=>$upload_data['file_size'],
                'filepath'=>$upload_data['full_path'],
                //'filename_custom'=>$post['note_image'],
                'created'=>date("Y-m-d H:i:s"),
                'uid'=>$user_id
            );
            $image_insert_id = $this->project_notes_model->notes_image_insert($document);                        
        }else{
            print 'error in file uploading...'; 
            print $this->upload->display_errors() ;  
        } 

        $notes_data = array(
            'project_id' => $project_id,
            'notes_body' =>'',
            'notes_image_id' =>$image_insert_id,
            'notes_by' => $user_id,
            'created' => date("Y-m-d H:i:s", time())

        ); 	

        $id = $this->project_notes_model->notes_image_save($notes_data);
        $this->validation->id = $id;
  }
  public function show_notes_with_image($rid){ 
      
      
      $prev_notes= $this->project_notes_model->getPriviousProjectNotes($rid);
      echo $this->notes_image_tmpl($prev_notes);
        
      
      //echo '<p>Me : '.urldecode($note).'<br /> '.  date('d/m/Y g:i a').'<p>';
      
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
           $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
           $notified_user= $this->project_notes_model->getNotifiedUserName($notes->notify_user_id);
           $align_class = 'right';
           if(!$notes->notes_image_id == null){
               $show_file= $this->project_notes_model->getNotesImage($notes->notes_image_id);
               
               $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div><div style="float:right"><span class="time-left">'.$creation_time.'</span></div> </div>';
           }else{
               $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body">'.$notes->notes_body.'</div><div style="float: right;"><span class="time-left">'.$creation_time.'</span>'.$notified_user.'</div> </div>';
           }
           
           
           $tmpl .= '<div class="clear"></div>';

        }else{
            $showuser= $notes->username; 
            $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
            $notified_user= $this->project_notes_model->getNotifiedUserName($notes->notify_user_id);
            $align_class ='left';
            if(!$notes->notes_image_id == null){
                $show_file= $this->project_notes_model->getNotesImage($notes->notes_image_id);
                $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div> <div style="float: left;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div><div style="float:left;"><span class="time-right">'.$creation_time.'</span></div> </div>';
            }
            else{
                $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div>  <div style="float: left;" class="notes_body">'.$notes->notes_body.'</div><div style="float: left;"><span class="time-right">'.$creation_time.'</span>'.$notified_user.'</div></div>'; 
            }
            
            $tmpl .= '<div class="clear"></div>';
        }
       } 
       return $tmpl;
      
  }
}
