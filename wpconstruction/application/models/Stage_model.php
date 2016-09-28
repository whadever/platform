<?php 
class Stage_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_development = 'construction_development';
	private $table_request= 'construction_request';
	private $table_notes='construction_notes';
	private $table_development_photos= 'construction_development_photos';
	private $table_stage_photos= 'construction_stage_photos';
	private $table_stage_notes= 'construction_stage_notes';
	private $table_stage_documents= 'construction_stage_documents';
	private $table_users= 'users';
	private $table_satge_phase = 'construction_stage_phase';
	private $table_stage_task = 'construction_stage_task';
	private $table_stage = 'construction_stage';
	private $table_file = 'construction_file';
    private $table_stage_milestone = 'construction_stage_milestone';     
	
	
	function __construct() {
		parent::__construct();
	}

	public function stage_document_search($development_id, $stage_id, $search)
	{
		$this->db->select('`id`,`filename_custom`,`created`');
		//$this->db->where('filename_custom',$search);
		$this->db->where('stage_no',$stage_id);
		$this->db->where('development_id',$development_id);
		if(isset($search))
		{
			$search1 = urldecode($search);
			$this->db->where('filename_custom',$search1);
			$this->db->order_by('filename_custom', 'ASC');
		}
		else
		{
			$this->db->order_by('id', 'DESC');
		}

		$stage_documents = $this->db->get($this->table_stage_documents)->result();

		$ppp = '<table><thead><tr><th>Document</th><th>Date Added</th></tr></thead><tbody>';				
		foreach($stage_documents as $stage_document)
		{	
			$ppp .= '<tr id="document_'.$stage_document->id.'" onclick="loadDocument('.$stage_document->id.')">';
			$ppp .= '<td>'.$stage_document->filename_custom.'</td>';
			$ppp .= '<td>'.date('d-m-Y', $stage_document->created).'</td>';
			$ppp .= '<input type="hidden" value="'.$stage_document->id.'" />';
			$ppp .= '</tr>';
		}
		$ppp .= '</tbody></table>';
		print_r($ppp);
	}

	function stage_document_delete($stage_document_id){
		$this->db->where($this->primary_key,$stage_document_id);
		$this->db->delete($this->table_stage_documents);
	}

	function stage_photo_delete($stage_photo_id){
		$this->db->where($this->primary_key,$stage_photo_id);
		$this->db->delete($this->table_stage_photos);
	}

	public function add_new_milestone($add_new_milestone){

		$this->db->insert($this->table_stage_milestone, $add_new_milestone);
		return $this->db->insert_id();
	}

	public function update_milestone($id,$update_milestone){
		$this->db->where($this->primary_key,$id);
		$this->db->update($this->table_stage_milestone,$update_milestone);
	}

	function get_stage_milestone_detail($did,$sid){
            
            $this->db->where('development_id',$did);
			$this->db->where('stage_no',$sid);
            return $this->db->get($this->table_stage_milestone);	
                
	}
        
     function get_project_no(){
           $sql = "SELECT MAX(project_id) max_project_no FROM project";
            
            $query =  $this->db->query($sql)->row();                
            return $query->max_project_no;	 
     }
	
	public function get_stage_detail($did,$sid) {
		$this->db->where('development_id',$did);
		$this->db->where('stage_no',$sid);	
        return $this->db->get($this->table_stage);
	}
	
	public function get_stage_details($sid) {
		$this->db->where($this->primary_key,$sid);	
        return $this->db->get($this->table_stage);
	}

	public function stage_add($stage_add){
		$this->db->insert($this->table_stage, $stage_add);
		return $this->db->insert_id();
	}

	function stage_update($stage_id,$stage_update){
		$this->db->where($this->primary_key,$stage_id);
		$this->db->update($this->table_stage,$stage_update);
	}

	public function stage_feature_photo_insert($file){
        $this->db->insert($this->table_file,$file);
        return $this->db->insert_id();            
    }

	public function get_stage_feature_photo($fid){
        $this->db->where('fid',$fid);	
        return $this->db->get($this->table_file);           
    }
	
	public function project_save($person){

		$this->db->insert($this->table_project, $person);
		return $this->db->insert_id();
	}
	
	public function project_count_all() {
		return $this->db->count_all($this->table_project);
	}
	
	function milestone_delete($mid){
		$this->db->where($this->primary_key,$mid);
		$this->db->delete($this->table_stage_milestone);
	}
       
	public function get_developments_list() {
		
		
            $this->db->order_by('development_name', 'ASC');
            //$this->db->limit(50);
            return $this->db->get($this->table_development);
	}
	
	function delete($cid){
		$this->db->where($this->primary_key,$cid);
		$this->db->delete($this->table_project);
	}
	
	function update($cid,$person){
		$this->db->where($this->primary_key,$cid);
		$this->db->update($this->table_project,$person);
	}
        
       
	
	function get_development_detail($pid){
            
            $this->db->where($this->primary_key,$pid);
            return $this->db->get($this->table_development);	
                
	} 
        
        function get_development_number_of_stage($pid){
            $this->db->select('number_of_stages');
            $this->db->where($this->primary_key,$pid);
            return $this->db->get($this->table_development)->row()->number_of_stages;	
                
	}
	
 	public function getStageDocuments($pid, $sid) {
		 $this->db->select('stage_documents.*, users.name as username');
			//$this->db->from('stage_documents');
            $this->db->where('development_id',$pid);   
            $this->db->where('stage_no',$sid); 
			//$this->db->where('file_category','1');  
            $this->db->join('users', 'users.uid = stage_documents.uid'); 
			$this->db->order_by("id", "desc");       
            return $this->db->get($this->table_stage_documents);
            
	}
	
	
	public function get_stage_documents_bycategory($pid, $sid, $cid) {
		 $this->db->select('stage_documents.*, users.name as username');
			//$this->db->from('stage_documents');
            $this->db->where('development_id',$pid);   
            $this->db->where('stage_no',$sid); 
            $this->db->where('file_category',$cid); 
            $this->db->join('users', 'users.uid = stage_documents.uid'); 
			$this->db->order_by("id", "desc");       
            return $this->db->get($this->table_stage_documents);
            
	}

	public function get_others_stage_documents_bycategory($pid, $sid, $cid) {
		 $this->db->select('stage_documents.*, users.name as username');
			//$this->db->from('stage_documents');
            $this->db->where('development_id',$pid);   
            $this->db->where('stage_no',$sid); 
            $this->db->where('file_category',$cid); 
            $this->db->join('users', 'users.uid = stage_documents.uid');  
			$this->db->order_by("id", "desc");
			$this->db->limit(1);      
            return $this->db->get($this->table_stage_documents);
            
	}
        
         public function getStagePhotos($pid, $sid) {
		
			$this->db->select('stage_photos.*, users.name as username');
            $this->db->where('project_id',$pid);   
            $this->db->where('stage_no',$sid);  
            $this->db->join('users', 'users.uid = stage_photos.uid');  
			$this->db->order_by("id", "DESC");         
            return $this->db->get($this->table_stage_photos);
                
           
	}
        
    function get_stage_notes($pid, $sid){
            $this->db->select('stage_notes.*, users.name as username');
            $this->db->from('stage_notes');
            $this->db->where('project_id',$pid);
            $this->db->where('stage_no',$sid);
            $this->db->join('users', 'users.uid = stage_notes.notes_by');
			$this->db->order_by("nid", "desc");
            $query = $this->db->get();
            return $query;
            //return $this->db->get($this->table_notes);	
            
                
	}
    
	function get_search_stage_notes($pid, $sid, $search_notes){
            $this->db->select('stage_notes.*, users.name as username');
            $this->db->from('stage_notes');
            $this->db->where('project_id',$pid);
            $this->db->where('stage_no',$sid);
            $this->db->like('notes_title', $search_notes); 
            $this->db->join('users', 'users.uid = stage_notes.notes_by');
			$this->db->order_by("nid", "desc");
            $query = $this->db->get();
            return $query;            
	}
    
	function get_others_search_stage_notes($pid, $sid, $search_notes){
            $this->db->select('stage_notes.*, users.name as username');
            $this->db->from('stage_notes');
            $this->db->where('project_id',$pid);
            $this->db->where('stage_no',$sid);
            $this->db->like('notes_title', $search_notes); 
            $this->db->join('users', 'users.uid = stage_notes.notes_by');
			$this->db->order_by("nid", "desc");
			$this->db->limit(1);
            $query = $this->db->get();
            return $query;            
	}  
        
        function get_note_detail($nid){
            $this->db->select('stage_notes.*, users.name as username');
            $this->db->from('stage_notes');
            $this->db->where('nid',$nid);
            $this->db->join('users', 'users.uid = stage_notes.notes_by');
			
            $query = $this->db->get();
            return $query;             
	}  

	function get_stage_note_detail($did,$stage_no){
            $this->db->select('stage_notes.*, users.name as username');
            $this->db->from('stage_notes');
            $this->db->where('project_id',$did);
			$this->db->where('stage_no',$stage_no);
            $this->db->join('users', 'users.uid = stage_notes.notes_by');
			$this->db->order_by("nid", "desc");
			$this->db->limit(1);
            $query = $this->db->get();
            return $query;             
	}  
	
	 function get_document_detail($document_id){
            $this->db->select('stage_documents.*, users.name as username');
            $this->db->from('stage_documents');
            $this->db->where('id',$document_id);
            $this->db->join('users', 'users.uid = stage_documents.uid');
			$this->db->order_by("id", "desc");
            $query = $this->db->get();
            return $query;
                        
	} 

	function get_stage_document_details($pid, $sid){
            $this->db->select('stage_documents.*, users.name as username');
            $this->db->from('stage_documents');
            $this->db->where('development_id',$pid);
			$this->db->where('stage_no',$sid);
			//$this->db->where('file_category','1'); 
            $this->db->join('users', 'users.uid = stage_documents.uid');
			$this->db->order_by("id", "desc");
			$this->db->limit(1);
            $query = $this->db->get();
            return $query;
                        
	} 
        
        
        
	function company_name(){
                $this->db->order_by('compname', "ASC");
		return $this->db->get($this->table_project);
	}
	function company_list_print(){
                $this->db->order_by('compname', "ASC");
		return $this->db->get($this->table_project);
	}
        
	function company_load($company_id){
		$this->db->where($this->primary_key,$company_id);
		return  $this->db->get($this->table_project)->row();
	}
       
        
	
	function get_company_list(){
            $query = $this->db->query("SELECT comp.`cid`, comp.`compname` FROM company_profile comp ORDER BY comp.`compname`");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->cid] = $row->compname; 
            } 
            return $rows;
        }
		
       
    function check_company_name_exists($company_name){
		$this->db->where('compname',$company_name);
		$this->db->from($this->table_project);
		return $this->db->count_all_results(); 
	}

    public function company_list_search_count_all($get=NULL) {
           $cname = '';
            $cond = array();
            if ($get){
                $cname = trim($get['cname']);
                if (!empty($cname)) $cond[] = 'compname LIKE "%'. $cname . '%"'; 
            }
            $sql = "SELECT COUNT(compname) total_rows FROM company_profile";
            if (!empty($cond)) $sql .= ' WHERE ' . implode(' AND ', $cond);           
            
            $query =  $this->db->query($sql)->row();                
            return $query->total_rows;			
	}
	
        
	public function company_list_search_count($sort_by = 'cid', $order_by = 'desc',$offset=0,$limit=10,$get = NULL) {
             $cname = '';
            if (isset($get) && !empty($get['cname']) ){
                $cname = $get['cname'];
            }   
			$this->db->select('company_profile.*,file.*'); 
			$this->db->join('file', 'company_profile.fid = file.fid', 'left');        
            if (!empty($cname)) $this->db->like('compname', $cname); 
            $this->db->order_by($sort_by, $order_by);
            $this->db->limit($limit,$offset);
            return $this->db->get($this->table_project);
	} 

	    public function file_insert($file){
            $this->db->insert($this->table_file,$file);
            return $this->db->insert_id();            
        }
        function get_project_open_bug($pid){
		$this->db->select('`id`, `request_no`, `request_title`, `request_status`'); 
                $this->db->where('project_id', $pid);
                $this->db->where('request_status', 1);
		return $this->db->get($this->table_request);
	}
        function get_project_close_request($pid){
		$this->db->select('`id`, `request_no`, `request_title`, `request_status`'); 
                $this->db->where('project_id', $pid);
                $this->db->where('request_status', 2);
		return $this->db->get($this->table_request);
	}
        
        public function stage_photo_insert($file){
            $this->db->insert($this->table_stage_photos,$file);
            return $this->db->insert_id();            
        }
        
		public function stage_document_insert($file){
            $this->db->insert($this->table_stage_documents,$file);
                      
        }
        
        function save_stage_photo_info($photo_insert_id, $photo_info)
		{           
			$this->db->where('id', $photo_insert_id);
			$this->db->update($this->table_stage_photos, $photo_info);     
		}
        
        
         public function insert_stage_note($file){
            $this->db->insert($this->table_stage_notes, $file);
                     
        }
     
    
    public function get_stage_list($pid)
	{
		$this->db->select('`id`,`number_of_stages`');
		$this->db->where('id', $pid);
		return $this->db->get($this->table_development);
	}

	public function get_phase_info($pid,$sid)
	{
		$this->db->select('`phase_id`,`phase_name`,`phase_length`,`planned_finished_date`,`actual_finished_date`');
		$this->db->where('stage_id', $sid);
		return $this->db->get('phase');
	}

	public function get_development_stage_phase_list($development_id, $stage_no)
	{
		$this->db->select('`id`,`phase_no`,`phase_name`,`phase_length`, `planned_start_date`, `planned_finished_date`,`actual_finished_date`,phase_person_responsible');
		$this->db->where('development_id', $development_id);
		$this->db->where('stage_no', $stage_no);
		return $this->db->get($this->table_satge_phase);

	}

	public function get_development_stage_phase_task_info($development_id, $stage_no, $phase_id)
	{
		$this->db->select('`id`,`task_name`,`task_length`,`task_start_date`, `planned_completion_date`,`actual_completion_date`,`created`,`stage_task_status`');
		$this->db->where('development_id', $development_id);
		$this->db->where('stage_no', $stage_no);
		$this->db->where('phase_id', $phase_id);
		return $this->db->get($this->table_stage_task);

	}
	
	public function get_development_stage_task_list($development_id, $stage_no){
		$this->db->select('id, task_name, task_length, day_required, actual_completion_date, stage_task_status, task_start_date');
		$this->db->where('development_id', $development_id);
		$this->db->where('stage_no', $stage_no);
				
		return $this->db->get($this->table_stage_task);

	}

	public function update_status($task_id,$status)
	{
		if($status == 1)
		{
			$now = date('Y-m-d');
		}
		else
		{
			$now = '0000-00-00';
		}
		$task_data = array('stage_task_status' => $status, 'actual_completion_date' => $now );
		$this->db->where('id', $task_id);
		$this->db->update($this->table_stage_task, $task_data); 
	}

	public function get_all_task_status($development_id,$stage_no,$phase_id)
	{
  		$this->db->select('`stage_no`, MIN(`stage_task_status`) as all_task_status');
  		$this->db->where('development_id', $development_id);
		$this->db->where('stage_no', $stage_no);
		$this->db->where('phase_id', $phase_id);
  		$this->db->group_by('phase_id');
  		return $this->db->get($this->table_stage_task);
  
 	}

	public function get_photo_author($photo_id)
	{
  		$this->db->select('stage_photos.photo_caption, stage_photos.filename, users.email as useremail');
  		$this->db->from('stage_photos');
  		$this->db->where('id', $photo_id); 
  		$this->db->join('users', 'users.uid = stage_photos.uid'); 
  		$query = $this->db->get();
        return $query;  
 	}

	public function getStagePhotoDetail($photo_id) {		
		$this->db->select('stage_photos.*, users.name as username');
        $this->db->where('id',$photo_id);  
        $this->db->join('users', 'users.uid = stage_photos.uid');          
        return $this->db->get($this->table_stage_photos)->row();
	}

	public function get_note_author($note_id)
	{
  		$this->db->select('stage_notes.notes_title, stage_notes.notes_by, users.email as useremail');
  		$this->db->from('stage_notes');
  		$this->db->where('nid', $note_id); 
  		$this->db->join('users', 'users.uid = stage_notes.notes_by'); 
  		$query = $this->db->get();
        return $query;  
 	}

	function stage_task_start_date_update($task_id,$task_update){
		$this->db->where($this->primary_key,$task_id);
		$this->db->update($this->table_stage_task,$task_update);
	}

	function stage_task_actual_date_update($task_id,$task_update){
		$this->db->where($this->primary_key,$task_id);
		$this->db->update($this->table_stage_task,$task_update);
	}


	public function update_all_phase_tasks($development_id, $stage_no, $phase_id,$status)
	{
		if($status == 1)
		{
			$now = date('Y-m-d');
		}
		else
		{
			$now = '0000-00-00';
		}
		$task_data = array('stage_task_status' => $status, 'actual_completion_date' => $now );
		$this->db->where('phase_id', $phase_id);
		$this->db->update($this->table_stage_task, $task_data); 
	
	}

	function email_outlook_stage($photo_id){
        $this->db->select('stage_photos.*, users.name as username');
        $this->db->from('stage_photos');
		$this->db->join('users', 'users.uid = stage_photos.uid');
        $this->db->where('id',$photo_id);
 
        $photo_result = $this->db->get()->row();

		$date = date("d-m-Y", $photo_result->created);
		$filename = $photo_result->filename;
		$username = $photo_result->username;
		$photo_caption = $photo_result->photo_caption;
		$base_url = base_url();
		$to='';
		$query = 'mailto:?subject=Stage%20Photo&body=Photo Name: '.$filename.'%0D%0A Uploaded By: '.$username.'%0D%0A Uploaded Date: '.$date.'%0D%0A Photo Caption: '.$photo_caption.'%0D%0A Photo Link: '.$base_url.'uploads/development/'.$filename;
   		echo $query;     
		//return $query;         
	}
        /*stage notes function starts from here */
         function getStageInfo($devid, $stage_id){
             $this->db->select('stage.id, stage.stage_name, c.name as created_by');
              $this->db->join('users c', 'stage.created_by = c.uid', 'left'); 
            $this->db->where('development_id', $devid);
            $this->db->where('stage_no', $stage_id);
            return $this->db->get($this->table_stage)->row();
		
                
	} 
        
        
	
	function getPriviousStageNotes($devid, $stage_id){
             $this->db->select('stage_notes.*, users.name');  
              $this->db->join('users', 'stage_notes.notes_by = users.uid', 'left'); 
            $this->db->where('project_id', $devid);
            $this->db->where('stage_no', $stage_id);
            return $this->db->get($this->table_stage_notes)->result();
		
                
	} 
         public function getNotifiedUserName($notify_user_id){
            $notified_user= explode(",", $notify_user_id);         
         
            $this->db->select('name');
            $this->db->where_in('uid', $notified_user);               
            $notified_user_name = $this->db->get($this->table_users)->result();
            if(!empty($notified_user_name)){
                 $name=array();
                    foreach ($notified_user_name as $user_name) {
                     $name[] = $user_name->name;
                    }
                $user_name =  implode(",", $name);
                return 'Notified : '.$user_name;
            }else{
                return '';
            }
            
        }
        function get_user_list(){
            $query = $this->db->query("SELECT uid, name FROM users ORDER BY name");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->uid] = $row->name; 
            } 
            return $rows;
        }
        public function get_user_info($notify_user_id){
            $assign_manager= explode(",", $notify_user_id);         
         
            $this->db->select('name, email');
            $this->db->where_in('uid', $assign_manager);               
            $manager = $this->db->get($this->table_users)->result();             
            return $manager;
        }
        function insertNote($rid, $sid, $note, $uid, $notify_user_id, $now){      
            $note1 = str_replace("forward_slash", "/", $note);
            $note2 = str_replace("sign_of_hash", "#", $note1);
            $note3 = str_replace("sign_of_intertogation", "?", $note2);
            
            $insert_data = array(
                'project_id' => $rid ,
                'stage_no' => $sid ,
                'notes_body' => $note3,
                'notes_image_id'=>0,
                'notes_by' => $uid,
                'notify_user_id' => $notify_user_id,
                'created' => $now
            );
            $this->db->insert('stage_notes', $insert_data);           
                
	} 
	 function deleteStageNotes($noteid){
             
            $this->db->where('nid', $noteid);
            $this->db->delete($this->table_stage_notes);
		
                
	}

	function stage_phase_delete($phase_id){
		$this->db->where($this->primary_key,$phase_id);
		$this->db->delete($this->table_satge_phase);	
		
		$this->db->where('phase_id',$phase_id);
		$this->db->delete($this->table_stage_task);
	}

	function stage_task_delete($task_id){
		$this->db->where($this->primary_key,$task_id);
		$this->db->delete($this->table_stage_task);
	}

	function stage_task_update($task_id,$stage_task_update){
		$this->db->where($this->primary_key,$task_id);
		$this->db->update($this->table_stage_task,$stage_task_update);
	}

	function stage_phase_update($phase_id,$stage_phase_update){
		$this->db->where($this->primary_key,$phase_id);
		$this->db->update($this->table_satge_phase,$stage_phase_update);
	}

	public function stage_phase_add($stage_phase_add){
		$this->db->insert($this->table_satge_phase, $stage_phase_add);
		return $this->db->insert_id();
	}

	public function stage_task_add($stage_task_add){
		$this->db->insert($this->table_stage_task, $stage_task_add);
		return $this->db->insert_id();
	}

}