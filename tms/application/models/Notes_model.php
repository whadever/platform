<?php 
class Notes_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_project = 'project';
        private $table_request= 'request';
        private $table_notes= 'task_notes';
        private $table_file= 'tms_file';
        private $table_users='users';
        
	
	
	function __construct() {
		parent::__construct();
	}
	
	
        function getRequestInfo($company_id,$req_no){

             $this->db->select('id, request_no, request_title');                
            $this->db->where('request_no', $req_no);
			$this->db->where('company_id', $company_id);
            return $this->db->get($this->table_request)->row();
		
                
	}

	function getAllNotes(){

			$get = $_GET;
			$user = $this->session->userdata('user'); 
			$wp_company_id = $user->company_id;

			$yesterday = date('Y-m-d',strtotime("-1 days"));
			

             $this->db->select('task_notes.*,  request.request_title, request.assign_manager_id, project.project_name, users.username');  
              $this->db->join('users', 'task_notes.notes_by = users.uid', 'left');
			$this->db->join('request', 'request.id = task_notes.request_id', 'left');
			$this->db->join('project', 'request.project_id = project.id', 'left'); 
			//$this->db->join('company', 'request.company_id = company.id', 'left');  
		 
			if(!empty($get)){
				$from_date = date('Y-m-d',strtotime($get['from_date']));
				$to_date = date('Y-m-d',strtotime($get['to_date']));
				$this->db->where('task_notes.created >= ', $from_date);
				$this->db->where('task_notes.created <= ', $to_date);
			}else{
            	$this->db->where('date(task_notes.created)', $yesterday);
			}
			$this->db->where('project.wp_company_id', $wp_company_id);
			$this->db->order_by('nid', "desc");
			//$this->db->limit(50);
            $result= $this->db->get($this->table_notes)->result();
			//echo $this->db->last_query();
			return $result;
		
                
	} 
	
	function getPriviousNotes($rid){
             $this->db->select('task_notes.*, users.username');  
              $this->db->join('users', 'task_notes.notes_by = users.uid', 'left'); 
            $this->db->where('request_id', $rid);
			//$this->db->order_by('nid', "desc");
            return $this->db->get($this->table_notes)->result();
		
                
	} 
        
   function insertNote($rid, $note, $uid, $notify_user_id, $now){      
            
            
            $insert_data = array(
                'request_id' => $rid ,
                'notes_body' => $note,
                'notes_image_id'=>0,
                'notes_by' => $uid,
                'notify_user_id' => $notify_user_id,
                'created' => $now
            );
            $this->db->insert('task_notes', $insert_data);
       		return $this->db->insert_id();
                
	} 
        
        public function request_save($request_data){

		$this->db->insert($this->table_request, $request_data);
		return $this->db->insert_id();
	}
        
        public function notes_image_save($notes_data){

		$this->db->insert($this->table_notes, $notes_data);
		return $this->db->insert_id();
	}
        
        function get_request_user_info($id){
            
             //$this->db->select('request.*, c.name as created_by,  a.name as manager_name, a.email as manager_email, b.name as developer_name, b.email as developer_email');  
             $this->db->select('request.*, p.project_name, c.username as created_by'); 
             $this->db->join('project p', 'request.project_id = p.id', 'left'); 
             //$this->db->join('users a', 'request.assign_manager_id = a.uid', 'left'); 
             //$this->db->join('users b', 'request.assign_developer_id = b.uid', 'left'); 
             $this->db->join('users c', 'request.created_by = c.uid', 'left'); 
             $this->db->where('request.id', $id);               
             return $this->db->get($this->table_request)->row();
	}
        
        
        function get_request_detail($id){
            
             $this->db->select('request.*, project.project_name, a.username as manager_name, b.username as developer_name, f.filename as document, f.filepath as document_path, i.filename as image, i.filepath as image_path');  
             $this->db->join('project', 'request.project_id = project.id', 'left');
             $this->db->join('file f', 'request.document_id = f.fid', 'left');
             $this->db->join('file i', 'request.image_id = i.fid', 'left');
             $this->db->join('users a', 'request.assign_manager_id = a.uid', 'left'); 
             $this->db->join('users b', 'request.assign_developer_id = b.uid', 'left'); 
             $this->db->where('request.'.$this->primary_key, $id);               
             return $this->db->get($this->table_request)->row;
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
        function get_user_list(){
			$user=  $this->session->userdata('user');  
			$wp_company_id = $user->company_id;

            $query = $this->db->query("SELECT uid, username FROM users LEFT JOIN users_application ON users.uid = users_application.user_id AND users.company_id=users_application.company_id WHERE users_application.company_id=$wp_company_id and users_application.application_id=3 and users.role != 1 ORDER BY username");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->uid] = $row->username; 
            } 
            return $rows;
        }
        
        public function get_user_info($notify_user_id){
            $assign_manager= explode(",", $notify_user_id);         
         
            $this->db->select('username, email');
            $this->db->where_in('uid', $assign_manager);               
            $manager = $this->db->get($this->table_users)->result();             
            return $manager;
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
                $user_name =  implode(", ", $name);
                return 'Notified : '.$user_name;
            }else{
                return '';
            }
            
        }

		public function getNotifiedUser($notify_user_id){
            $notified_user= explode(",", $notify_user_id);         
         
            $this->db->select('username');
            $this->db->where_in('uid', $notified_user);               
            $notified_user_name = $this->db->get($this->table_users)->result();
            if(!empty($notified_user_name)){
                 $name=array();
                    foreach ($notified_user_name as $user_name) {
                     $name[] = $user_name->username;
                    }
                $user_name =  implode(", ", $name);
                return $user_name;
            }else{
                return '';
            }
            
        }
        
        
       
        
	

   
      
        
}
?>