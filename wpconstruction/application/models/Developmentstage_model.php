<?php 
class Developments_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_development = 'development';
        private $table_request= 'request';
        private $table_notes='notes';
        private $table_development_photos= 'development_photos';
        private $table_users= 'users';
        
	
	
	function __construct() {
		parent::__construct();
	}
        
         function get_project_no(){
           $sql = "SELECT MAX(project_id) max_project_no FROM project";
            
            $query =  $this->db->query($sql)->row();                
            return $query->max_project_no;	 
        }
	
	public function project_save($person){

		$this->db->insert($this->table_project, $person);
		return $this->db->insert_id();
	}
	
	public function project_count_all() {
		return $this->db->count_all($this->table_project);
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
        
         public function getDevelopmentPhotos($pid) {
		
		
            $this->db->where('project_id',$pid);           
            return $this->db->get($this->table_development_photos);
	}
        
        function get_project_notes($pid){
            $this->db->select('notes.*, users.name as username');
            $this->db->from('notes');
            $this->db->where('project_id',$pid);
            $this->db->join('users', 'users.uid = notes.notes_by');
            $query = $this->db->get();
            return $query;
            //return $this->db->get($this->table_notes);	
            
                
	}
        function get_project_search_notes($pid, $search_notes){
            $this->db->select('notes.*, users.name as username');
            $this->db->from('notes');
            $this->db->where('project_id',$pid);
            $this->db->like('notes_title', $search_notes); 
            $this->db->join('users', 'users.uid = notes.notes_by');
            $query = $this->db->get();
            return $query;
            //return $this->db->get($this->table_notes);	
            
                
	}
        
        
        function get_note_detail($nid){
            $this->db->select('notes.*, users.name as username');
            $this->db->from('notes');
            $this->db->where('nid',$nid);
            $this->db->join('users', 'users.uid = notes.notes_by');

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
        
        public function project_photo_insert($file){
            $this->db->insert($this->table_development_photos,$file);
            return $this->db->insert_id();            
        }
         function save_project_photo_info($photo_insert_id, $photo_info){
                  
		$this->db->where('id', $photo_insert_id);
		$this->db->update($this->table_development_photos, $photo_info);
                
	}
        
        
         public function insert_development_note($file){
            $this->db->insert($this->table_notes, $file);
                     
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
}
?>