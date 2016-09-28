<?php 
class Overview_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_project = 'project';
        private $table_request= 'request';
	
	
	function __construct() {
		parent::__construct();
	}
	
	public function project_save($person){

		$this->db->insert($this->table_project, $person);
		return $this->db->insert_id();
	}
	
	public function project_count_all() {
		return $this->db->count_all($this->table_project);
	}
	
	public function project_list_count() {
		
		
            $this->db->order_by('project_name', 'ASC');
            $this->db->limit(50);
            return $this->db->get($this->table_project);
	}
	
	function delete($cid){
		$this->db->where($this->primary_key,$cid);
		$this->db->delete($this->table_project);
	}
	
	function update($cid,$person){
		$this->db->where($this->primary_key,$cid);
		$this->db->update($this->table_project,$person);
	}
	
	function get_project_detail($pid){
            
            $this->db->where($this->primary_key,$pid);
            return $this->db->get($this->table_project);
		
                
	} 
        function get_overview_requests($uid, $rid){
            if($rid==3){
                $this->db->where('assign_developer_id', $uid);
                $this->db->where('request_status', 1);
            }else{
                $this->db->where('assign_manager_id', $uid);
                $this->db->where('request_status', 1);
            }
            
            
            return $this->db->get($this->table_request);
		
                
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
		$this->db->select('`request_no`, `request_title`, `request_status`'); 
                $this->db->where('project_id', $pid);
                $this->db->where('request_status', 1);
		return $this->db->get($this->table_request);
	}
        function get_project_close_request($pid){
		$this->db->select('`request_no`, `request_title`, `request_status`'); 
                $this->db->where('project_id', $pid);
                $this->db->where('request_status', 2);
		return $this->db->get($this->table_request);
	}
        
}
?>