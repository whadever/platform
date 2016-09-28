<?php

class Project_Notes_model extends CI_Model {	

	private $primary_key = 'id';
	private $table_project = 'project';
	private $table_request= 'request';
	private $table_project_notes= 'project_notes';   
	private $table_file= 'file';
	private $table_users= 'users';

        

	function __construct() {
		parent::__construct();
	}


	function getProjectInfo($pid){
		$this->db->select('project_no, project_name');        

		$this->db->where('id', $pid);
		return $this->db->get($this->table_project)->row();	 

	} 


	function getPriviousProjectNotes($pid){

		$this->db->select('project_notes.*, users.username');  
		$this->db->join('users', 'project_notes.notes_by = users.uid', 'left'); 
		$this->db->where('project_id', $pid);
		return $this->db->get($this->table_project_notes)->result();

	} 


        
    function insertProjectNote($pid, $note, $uid, $notify_user_id, $now){      

		
        $insert_data = array(
			'project_id' => $pid,
			'notes_body' => $note,
			'notes_image_id'=>0,
			'notes_by' => $uid,
			'notify_user_id' => $notify_user_id,
			'created' => $now
		);
		$this->db->insert('project_notes', $insert_data);                 

	}         

    public function request_save($request_data){

		$this->db->insert($this->table_request, $request_data);
		return $this->db->insert_id();

	}

        

    public function notes_image_save($notes_data){
		$this->db->insert($this->table_project_notes, $notes_data);
		return $this->db->insert_id();
	}


	function get_project_detail($id){            

		$this->db->select('request.*, project.project_name, a.username as manager_name, b.username as developer_name, f.filename as document, f.filepath as document_path, i.filename as image, i.filepath as image_path');  

		$this->db->join('project', 'request.project_id = project.id', 'left');
		$this->db->join('file f', 'request.document_id = f.fid', 'left');
		$this->db->join('file i', 'request.image_id = i.fid', 'left');
		$this->db->join('users a', 'request.assign_manager_id = a.uid', 'left'); 
		$this->db->join('users b', 'request.assign_developer_id = b.uid', 'left'); 
		$this->db->where($this->primary_key, $id);         

		return $this->db->get($this->table_request);

	}
        
	function get_project_user_info($id){
		
		//$this->db->select('request.*, c.name as created_by,  a.name as manager_name, a.email as manager_email, b.name as developer_name, b.email as developer_email');  
		$this->db->select('project.*, c.username as created_by'); 		 
		$this->db->join('users c', 'project.created_by = c.uid', 'left'); 
		$this->db->where('project.id', $id);               
		return $this->db->get($this->table_project)->row();
	}
        
	public function get_user_info($notify_user_id){
		$assign_manager= explode(",", $notify_user_id);       
	 
		$this->db->select('username, email');
		$this->db->where_in('uid', $assign_manager);               
		$manager = $this->db->get($this->table_users)->result();             
		return $manager;
	}

        

	function get_overview_requests($uid){            

		$this->db->where('assign_manager_id', $uid);
		return $this->db->get($this->table_request);
	
	}
        

	public function notes_image_insert($file){

		$this->db->insert($this->table_file,$file);
		return $this->db->insert_id();           

	}
        

	public function getNotesImage($file_id){
		$this->db->select('filename');  

		$this->db->where("fid", $file_id);   

		return $this->db->get($this->table_file)->row();        

	}
        
	public function getNotifiedUserName($notify_user_id){
		$notified_user= explode(",", $notify_user_id);         
	 
		$this->db->select('username');
		$this->db->where_in('uid', $notified_user);               
		$notified_user_name = $this->db->get($this->table_users)->result();
		if(!empty($notified_user_name)){
			 $name=array();
				foreach ($notified_user_name as $user_name) {
				 $name[] = $user_name->username;
				}
			$user_name =  implode(",", $name);
			return 'Notified : '.$user_name;
		}else{
			return '';
		}
		
	}

}

