<?php
// main ajax back end
class Notes extends CI_Controller {
  
  function __construct(){
        parent::__construct();
        
        $this->load->helper(array('form', 'url', 'file'));
        $this->load->model('notes_model','',TRUE);
    }
	
	
  public function index($req_id='')
  {
      
      //echo $req_id;
      $data['title'] = 'Show Notes'; 
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
  
  public function show_notes($rid, $note){
      $user=  $this->session->userdata('user');          
      $user_id =$user->uid; 
      $note_body= urldecode($note);      
      
      $insert_note = $this->notes_model->insertNote($rid, $note_body, $user_id);
      
      $prev_notes= $this->notes_model->getPriviousNotes($rid);
      echo $this->notes_image_tmpl($prev_notes);  
      
      //echo '<p>Me : '.urldecode($note).'<br /> '.  date('d/m/Y g:i a').'<p>';
      
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
                        'notes_by' => $user_id
                        //'created' => date("Y-m-d H:i:s", time())
                        
                    ); 	
				
                    $id = $this->notes_model->notes_image_save($notes_data);
                    $this->validation->id = $id;
  }
  public function show_notes_with_image($rid){ 
      
      
      $prev_notes= $this->notes_model->getPriviousNotes($rid);
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
           $align_class = 'right';
           if(!$notes->notes_image_id == null){
               $show_file= $this->notes_model->getNotesImage($notes->notes_image_id);
               
               $tmpl .= '<div class="'.$align_class.'"><span class="time-left">'.$creation_time.'</span><br/><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div> </div>';
           }else{
               $tmpl .= '<div class="'.$align_class.'"><span class="time-left">'.$creation_time.'</span><br/><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body">'.$notes->notes_body.'</div> </div>';
           }
           
           
           $tmpl .= '<div class="clear"></div>';

        }else{
            $showuser= $notes->name; 
            $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
            $align_class ='left';
            if(!$notes->notes_image_id == null){
                $show_file= $this->notes_model->getNotesImage($notes->notes_image_id);
                $tmpl .= '<div class="'.$align_class.'"><span class="time-right">'.$creation_time.'</span><br/><div class="useranother">'.$showuser.':</div> <div style="float: left;" class="notes_body"><img height="100" width="100" src="'.base_url().'uploads/notes/images/'.$show_file->filename.'"/></div> </div>';
            }
            else{
                $tmpl .= '<div class="'.$align_class.'"><span class="time-right">'.$creation_time.'</span><br/><div class="useranother">'.$showuser.':</div>  <div style="float: left;" class="notes_body">'.$notes->notes_body.'</div></div>'; 
            }
            
            $tmpl .= '<div class="clear"></div>';
        }
       } 
       return $tmpl;
      
  }
}
