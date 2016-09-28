<?php
// main ajax back end
class Notes extends CI_Controller {
  
  function __construct(){
        parent::__construct();
        
        
        $this->load->model('notes_model','',TRUE);
        $this->load->model('request_model','',TRUE);
		$this->load->library(array('table','form_validation', 'session'));
        $this->load->library('breadcrumbs');
		$this->load->helper(array('form', 'url', 'file'));
        date_default_timezone_set("NZ");        
        

		$redirect_login_page = base_url().'user';
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		
		}
    }
	
	
  public function index($req_id='')
  {
     
      //echo $req_id;
      
     
      $data['title'] = 'Task Notes'; 
      $data['request_id']= $req_id;
      
      $data['request_info']= $this->notes_model->getRequestInfo($req_id);     
      $prev_notes = $this->notes_model->getPriviousNotes($req_id);
      $data['prev_notes'] = $this->notes_image_tmpl($prev_notes);
      //print_r($prev_notes);
      // load view
		$data['maincontent'] = $this->load->view('notes_view',$data,true);
		
		$this->load->view('includes/header',$data);
		//$this->load->view('includes/sidebar',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
  
  	
  } 
  
  public function show_notes($rid, $notify_user_id=''){
      $user=  $this->session->userdata('user');  
      
      
      $user_id =$user->uid; 
      $user_email = $user->email;
      $user_name =$user->username;
      $user_role= $user->rid;  
      $note_body= $_GET['notes'];    
      $now = date('Y-m-d H:i:s');

		$insert_note = $this->notes_model->insertNote($rid, $note_body, $user_id, $notify_user_id, $now);      
        $prev_notes= $this->notes_model->getPriviousNotes($rid);
        echo $this->notes_image_tmpl($prev_notes); 
      
      
      
      $request_info= $this->notes_model->get_request_user_info($rid);

      $request_no= $request_info->request_no;
      $request_title= $request_info->request_title;
      $request_project = $request_info->project_name;
      $request_created_by =$request_info->created_by;
      
        $assign_manager_id = $request_info->assign_manager_id;
        $assign_developer_id = $request_info->assign_developer_id;


        $assign_manager_info=$this->request_model->get_manager_info($assign_manager_id);              

        foreach ($assign_manager_info as $manager) {
            $manager_name[]=$manager->username;
            $manager_email[]=$manager->email;
        }
        $assign_manager_name= implode(", ", $manager_name);
        $assign_manager_email= implode(", ", $manager_email);
        

        $assign_developer_info=$this->request_model->get_developer_info($assign_developer_id);
        foreach($assign_developer_info as $developer){
            $developer_name[] =$developer->username;
            $developer_email[] = $developer->email;
        }
        $assign_developer_name= implode(", ", $developer_name);
        $assign_developer_email= implode(", ", $developer_email);

        $dev_name=$assign_developer_name;
        $dev_email=$assign_developer_email;          
        $staff_name= $assign_manager_name;
        $staff_email=$assign_manager_email;
      
     
      
      
      
      //user role 2 for staff 3 for developer
       if($user_role==2){         
         $to = $dev_email;         
         $cc='';          
      }
      elseif($user_role==3){          
          $to = $staff_email;          
          $cc='';          
      }else{
          $to = $staff_email;          
          $cc=$dev_email;
      }  
      
      
     
      
      
      
        $from= $user_email;
        $notes_from = $user_name;
	$subject = 'You have a notes from '.$notes_from.' on Request -'.$request_title;
	
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
        $message .= "<tr><td><strong>Project Name:</strong> </td><td>" . $request_project . "</td></tr>";
        
	$message .= "<tr><td><strong>Notes :</strong> </td><td>" . $note_body . "</td></tr>";
	$message .= "<tr><td><strong>Notes From :</strong> </td><td>" .$notes_from . "</td></tr>";	
	
	$message .= "</table>";
	$message .= "</body></html>";	
	//$msg_body='message body';
	$msg_body=$message;
	
	
	mail($to, $subject, $msg_body, $headers);
        
        
        $notify_user_info=$this->notes_model->get_user_info($notify_user_id); 
		$notify_user_email=array();       
        foreach ($notify_user_info as $user_info) {
                    //$user_name[]=$user->name;
                    $notify_user_email[]=$user_info->email;
                }
                //$assign_user_name= implode(", ", $user_name);
                $notify_user_to= implode(", ", $notify_user_email);
        
        
        $from2= $user_email;
        $notes_from2 = $user_name;
	$subject2 = 'Hi, You have a notification from '.$notes_from2.' on task -'.$request_title;
	
	$headers2 = "From: ".$from2 . "\r\n";
	$headers2 .= "Reply-To: ". $notify_user_to . "\r\n";
	//$headers .= "CC: ". $cc . "\r\n";
	$headers2 .= "MIME-Version: 1.0\r\n";
	$headers2 .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
	
	$message2= '';
        $message2 .= '<html><body>';	
	$message2 .= "Hello, <strong>".$notes_from2."</strong> has added a new note to task '". $request_title ."'  on project '".$request_project."'<br />";
        $message2 .= "Note Description: " . $note_body . " <br />";
        $message2 .= " To view this conversation, follow this link: ".base_url()."notes/index/".$rid."";
	$message2 .= "</body></html>";	
	//$msg_body='message body';
	$msg_body2=$message2;
        mail($notify_user_to, $subject2, $msg_body2, $headers2);
               
        
        
          
      
  }
  
  
  public function upload_note_image(){
                $req_id= $_POST['request_id'];  
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
                        $image_insert_id = $this->notes_model->notes_image_insert($document);                        
                    }else{
                        print 'error in file uploading...'; 
                        print $this->upload->display_errors() ;  
                    } 
                    
                    $notes_data = array(
			'request_id' => $req_id,
                        'notes_body' =>'',
                        'notes_image_id' =>$image_insert_id,
                        'notes_by' => $user_id,
                        'created' => date("Y-m-d H:i:s", time())
                        
                    ); 	
				
                    $id = $this->notes_model->notes_image_save($notes_data);
                    $this->validation->id = $id;
  }
  public function show_notes_with_image($rid){ 
      
      
      $prev_notes= $this->notes_model->getPriviousNotes($rid);
      echo $this->notes_image_tmpl($prev_notes);
        
      
      //echo '<p>Me : '.urldecode($note).'<br /> '.  date('d/m/Y g:i a').'<p>';
      
  }
  public function notes_image_tmpl2($prev_notes){
      
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
                   $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div><div class="show-time" style="float:right;"><span class="time-left1">'.$creation_time.'</span></div> </div>'; 
                }
                else
                {
                    //this is file not image
                    $tmpl .= '<div class="'.$align_class.'"><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body"><a target="_blank" href="'.base_url().'uploads/notes/images/'.$show_file->filename.'">'.$file_name.'</a></div><div class="show-time" style="float:right;"><span class="time-left1">'.$creation_time.'</span></div> </div>';
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
                    $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div> <div style="float: left;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div> <span class="time-right">'.$creation_time.'</span></div>';
                }else{
                    //this is not image
                    $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div> <div style="float: left;" class="notes_body"><a target="_blank" href="'.base_url().'uploads/notes/images/'.$show_file->filename.'">'.$file_name.'</a></div> <span class="time-right">'.$creation_time.'</span></div>';
                }
            }
            else{
                $tmpl .= '<div class="'.$align_class.'"><div class="useranother">'.$showuser.':</div>  <div style="float: left;" class="notes_body">'.$notes->notes_body.'</div><div style="float:left;"><span class="time-right1">'.$creation_time.'</span>'.$notified_user.'</div> </div>'; 
            }
            
            $tmpl .= '<div class="clear"></div>';
        }
       } 
       return $tmpl;
      
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
}
