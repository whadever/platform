<?php 
class Company_model extends CI_Model {
	
	private $primary_key = 'id';
        private $table_project = 'project';
	private $table_company = 'company';
        private $table_request= 'request';
	private $table_company_notes= 'company_notes';
	
	function __construct() {
		parent::__construct();
	}
        
         function get_company_no(){
           $sql = "SELECT MAX(company_no) max_company_no FROM company";
            
            $query =  $this->db->query($sql)->row();                
            return $query->max_company_no;	 
        }
	
	public function company_save($person){

		$this->db->insert($this->table_company, $person);
		return $this->db->insert_id();
	}
	
	public function company_count_all() {
		return $this->db->count_all($this->table_company);
	}
	
	public function company_list_count() {
		
		
            $this->db->order_by('company_name', 'ASC');
            $this->db->limit(50);
            return $this->db->get($this->table_company);
	}
        function get_company(){
                $this->db->order_by('company_name', 'ASC');
                $result = $this->db->get($this->table_company);
                //echo  $this->db->last_query();
                return $result;
        }
        function get_company_list_all($company_name, $sort_by = 'id', $order_by = 'desc',$offset=0,$limit=10,$get = NULL){
            $user=  $this->session->userdata('user');  
            $user_id =$user->uid; 
            $role_id = $user->rid;
			$wp_company_id = $user->company_id;
            
            if($role_id==3){
                $this->db->select("distinct(request.company_id) as id, company.company_name as company_name, company.company_description as company_description, company.company_status");
                $this->db->join('company', 'request.company_id = company.id', 'left');
                //$this->db->like("company.company_name", $company_name);
                $this->db->where('request.company_id >',0);
                if($role_id==3){
                    $this->db->where("FIND_IN_SET($user_id , assign_developer_id)");
                }
                if($sort_by == 'company_status'){
                $this->db->order_by($sort_by.' '.$order_by.' , company_name asc');                
                }else{
                    $this->db->order_by($sort_by, $order_by);
                
                    
                }
                $this->db->limit($limit,$offset);
            
                $result = $this->db->get($this->table_request);
                //echo  $this->db->last_query();
                return $result;
                
            }
            else{
                $this->db->like("company_name", $company_name);
				$this->db->where('wp_company_id',$wp_company_id);
                if($sort_by == 'company_status'){
                $this->db->order_by($sort_by.' '.$order_by.' , company_name asc');                
                }else{
                    $this->db->order_by($sort_by, $order_by);           
                    
                }
				
                $this->db->limit($limit,$offset);            
                $result = $this->db->get($this->table_company);
                //echo  $this->db->last_query();
                return $result;
            }
           
            
                
        }
        function get_company_list_all_backup($sort_by = 'id', $order_by = 'desc',$offset=0,$limit=10,$get = NULL){
            $user=  $this->session->userdata('user');  
            $user_id =$user->uid; 
            $role_id = $user->rid;     
            
            $this->db->select("distinct(request.company_id) as id, company.company_name as company_name, company.company_description as company_description, company.company_status");
                $this->db->join('company', 'request.company_id = company.id', 'left');
                //$this->db->like("company.company_name", $company_name);
                $this->db->where('request.company_id >',0);
                if($role_id==3){
                    $this->db->where("FIND_IN_SET($user_id , assign_developer_id)");
                }
                if($sort_by == 'company_status'){
                $this->db->order_by($sort_by.' '.$order_by.' , company_name asc');                
                }else{
                    $this->db->order_by($sort_by, $order_by);
                
                    
                }
                $this->db->limit($limit,$offset);
            
                $result = $this->db->get($this->table_request);
                //echo  $this->db->last_query();
                return $result;
        }
        public function company_list_search_count($sort_by = 'id', $order_by = 'desc',$offset=0,$limit=10,$get = NULL) {
             
                     
            if($sort_by == 'company_status'){
                $this->db->order_by($sort_by.' '.$order_by.' , company_name asc');                
            }else{
                $this->db->order_by($sort_by, $order_by);
            }
            
            $this->db->limit($limit,$offset);
            
            $result = $this->db->get($this->table_company);
            //echo  $this->db->last_query();
            return $result;
            
	} 
	
	function delete_company_with_requests_notes($pid){
            /* echo $pid;
            
            $request_sql = "select id from request where company_id=".$pid;
            $request_res= mysql_query($request_sql);
            while ($request_row = mysql_fetch_assoc($request_res)) {
                 $request_ids[]= $request_row['id'];
            }
            print_r($request_ids);      
                
                
             * 
             */
		$this->db->where($this->primary_key,$pid);
		$this->db->delete($this->table_company);
	}
	
	function update($cid,$person){
		$this->db->where($this->primary_key,$cid);
		$this->db->update($this->table_company,$person);
	}
        function close_company($pid){
		$data = array(
               'company_status' => 2
            );

            $this->db->where('id', $pid);
            $this->db->update('company', $data); 		
	}
	
	function get_company_detail($pid){
            $this->db->select('company.*, users.username');
            $this->db->from('company');
            $this->db->join('users', 'company.created_by = users.uid', 'left');
            $this->db->where($this->primary_key,$pid);
            $query = $this->db->get();
            return $query;
                
	}        
        
        
	function company_name(){
                $this->db->order_by('compname', "ASC");
		return $this->db->get($this->table_company);
	}
	function company_list_print(){
                $this->db->order_by('compname', "ASC");
		return $this->db->get($this->table_company);
	}
        
	function company_load($company_id){
		$this->db->where($this->primary_key,$company_id);
		return  $this->db->get($this->table_company)->row();
	}
       
        
	
	
        function get_company_list(){
			$user=  $this->session->userdata('user');  
            $wp_company_id =$user->company_id;
            $query = $this->db->query("SELECT comp.`id`, comp.`company_name` FROM company comp WHERE wp_company_id=$wp_company_id ORDER BY comp.`company_name`");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->id] = $row->company_name; 
            } 
            return $rows;
        }
		
       
    function check_company_name_exists($company_name){
		$this->db->where('compname',$company_name);
		$this->db->from($this->table_company);
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
	
        
	

	    public function file_insert($file){
            $this->db->insert($this->table_file,$file);
            return $this->db->insert_id();            
        }
        function get_company_open_bug($pid, $user_id, $role_id){
		$this->db->select('`id`, `request_no`, `request_title`, `request_status`'); 
                $this->db->where('company_id', $pid);
		//$this->db->where('project_id', 0);
                $this->db->where('request_status', 1);
                if($role_id==3){
                    $this->db->where("FIND_IN_SET($user_id , assign_developer_id)");
                }
				$this->db->order_by('id', 'DESC');
		return $this->db->get($this->table_request);
	}
        function get_company_close_request($pid, $user_id, $role_id){
		$this->db->select('`id`, `request_no`, `request_title`, `request_status`'); 
                $this->db->where('company_id', $pid);
		//$this->db->where('project_id', 0);
                $this->db->where('request_status', 2);
                if($role_id==3){
                    $this->db->where("FIND_IN_SET($user_id , assign_developer_id)");
                }
				$this->db->order_by('id', 'DESC');
		return $this->db->get($this->table_request);
	}
    public function get_company_project($cid){
		$this->db->select('`id`, `project_no`, `project_name`, `project_status`'); 
        $this->db->where('company_id', $cid); 
		$this->db->order_by('project_name', 'ASC');                
		return $this->db->get($this->table_project);
	}
    public function get_company_open_project($cid){
		$this->db->select('`id`, `project_no`, `project_name`, `project_status`'); 
        $this->db->where('company_id', $cid); 
        $this->db->where('project_status', 1); 
		$this->db->order_by('project_name', 'ASC');
		return $this->db->get($this->table_project);
	}
    public function get_company_close_project($cid){
		$this->db->select('`id`, `project_no`, `project_name`, `project_status`'); 
        $this->db->where('company_id', $cid); 
        $this->db->where('project_status', 2); 
		$this->db->order_by('project_name', 'ASC');
		return $this->db->get($this->table_project);
	}
        
        
}
?>