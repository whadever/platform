<?php 
class Request_model extends CI_Model {
	
	private $primary_key = 'request.id';
	private $table_request = 'request';
    private $table_request_hours = 'task_hours';
        private $table_file = 'tms_file';
        private $table_project = 'project';
         private $table_users = 'users';
       
	
	
	function __construct() {
		parent::__construct();
	}
	
	public function request_save($request_data){

		$this->db->insert($this->table_request, $request_data);
		return $this->db->insert_id();
	}
	
	public function project_count_all() {
		return $this->db->count_all($this->table_comp);
	}
	
	public function request_list_count() {
		
		
            $this->db->order_by('request_title', 'ASC');
            $this->db->limit(50);
            return $this->db->get($this->table_request);
	}
	
	function delete_request($id){
		$this->db->where($this->primary_key,$id);
		$this->db->delete($this->table_request);
	}
        function close_request($id){
                $request_status = array(
                    'request_status' => 2
                );
		$this->db->where($this->primary_key,$id);                
		$this->db->update($this->table_request, $request_status);                
		
	}
        function open_request($id){
                $request_status = array(
                    'request_status' => 1
                );
		$this->db->where($this->primary_key,$id);                
		$this->db->update($this->table_request, $request_status);                
		
	}
	
	function update($id, $request){
		$this->db->where($this->primary_key,$id);
		$this->db->update($this->table_request, $request);
	}
        function get_request_name($id){
             $this->db->select('request_title');            
             $this->db->where($this->primary_key, $id);               
             return $this->db->get($this->table_request)->row()->request_title;
        }
	
	function get_request_detail($id){
             //$this->db->select('request.*, project.project_name, company.company_name, a.name as manager_name, b.name as developer_name, c.name as created_by, f.filename as document, f.filepath as document_path, i.filename as image, i.filepath as image_path'); 
              $this->db->select('request.*, project.project_name, company.company_name,  c.username as created_by, f.filename as document, f.filepath as document_path, i.filename as image, i.filepath as image_path');
             $this->db->join('project', 'request.project_id = project.id', 'left');
             $this->db->join('company', 'request.company_id = company.id', 'left');
             $this->db->join('tms_file f', 'request.document_id = f.fid', 'left');
             $this->db->join('tms_file i', 'request.image_id = i.fid', 'left');
             //$this->db->join('users a', 'request.assign_manager_id = a.uid', 'left'); 
             //$this->db->join('users b', 'request.assign_developer_id = b.uid', 'left'); 
             $this->db->join('users c', 'request.created_by = c.uid', 'left');
             $this->db->where($this->primary_key, $id);               
             return $this->db->get($this->table_request);
             //echo $this->db->last_query();
	}
        public function get_assign_manager($assign_manager_id){
            $assign_manager= explode(",", $assign_manager_id);
            //print_r($assign_manager);
            $manager_name=array();
            foreach($assign_manager as $manager_id){
                $this->db->select('username');
                $this->db->where('uid', $manager_id);               
              $manager = $this->db->get($this->table_users)->row();
              $manager_name[]= $manager->username;      
              
            }            
            return $manager_name;
        }
        public function get_assign_developer($assign_developer_id){
            $assign_developer= explode(",", $assign_developer_id);  
            $developer_name=array();
            foreach($assign_developer as $developer_id){
                $this->db->select('username');
                $this->db->where('uid', $developer_id);               
              $developer = $this->db->get($this->table_users)->row();
              $developer_name[]= $developer->username;      
              
            }
            return $developer_name;
        }
	function company_name(){
                $this->db->order_by('compname', "ASC");
		return $this->db->get($this->table_comp);
	}
	function company_list_print(){
                $this->db->order_by('compname', "ASC");
		return $this->db->get($this->table_request);
	}
        
	function company_load($company_id){
		$this->db->where($this->primary_key,$company_id);
		return  $this->db->get($this->table_comp)->row();
	}
       
        
	
	function get_project_list(){
            $query = $this->db->query("SELECT id, project_name FROM project ORDER BY project_name");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->id] = $row->project_name; 
            } 
            return $rows;
        }
	function get_developer_project_list($user_id){
                $this->db->select("distinct(request.project_id) as project_id, project.project_name as project_name");
                $this->db->join('project', 'request.project_id = project.id', 'left');
                $this->db->where("FIND_IN_SET($user_id , request.assign_developer_id)"); 
                $this->db->order_by('project_name', "ASC");
                $query=$this->db->get($this->table_request);
                //echo $this->db->last_query();
                $rows = array();
                foreach ($query->result() as $row){
                    $rows[$row->project_id] = $row->project_name; 
                } 
                return $rows;
        }
   function get_company_list(){
            $query = $this->db->query("SELECT comp.`id`, comp.`company_name` FROM company comp ORDER BY comp.`company_name`");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->id] = $row->company_name; 
            } 
            return $rows;
        }
   function get_developer_company_list($user_id){
                $this->db->select("distinct(request.company_id) as company_id, company.company_name as company_name");
                $this->db->join('company', 'request.company_id = company.id', 'left');
                $this->db->where("FIND_IN_SET($user_id , assign_developer_id)"); 
                $this->db->order_by('company_name', "ASC");
                $query=$this->db->get($this->table_request);
                //echo $this->db->last_query();
                $rows = array();
                foreach ($query->result() as $row){
                    $rows[$row->company_id] = $row->company_name; 
                } 
                return $rows;
        }

    function get_manager_list(){
            $query = $this->db->query("SELECT uid,username FROM users LEFT JOIN users_application ON users.uid=users_application.user_id where users_application.application_id=4 and users_application.application_role_id=2 ORDER BY username");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->uid] = $row->username; 
            } 
            return $rows;
        }
    function get_developer_list(){
            $query = $this->db->query("SELECT uid,username FROM users LEFT JOIN users_application ON users.uid=users_application.user_id where users_application.application_id=4 and users_application.application_role_id=3 ORDER BY username");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->uid] = $row->username; 
            } 
            return $rows;
        }
    
    function get_request_no(){
           $sql = "SELECT MAX(request_no) max_request_no FROM request";             
            
            $query =  $this->db->query($sql)->row();                
            return $query->max_request_no;	 
        }


    function check_company_name_exists($company_name){
		$this->db->where('compname',$company_name);
		$this->db->from($this->table_comp);
		return $this->db->count_all_results(); 
	}

    public function request_list_search_count_all($user_id, $role_id, $get=NULL) {
            $request_no = '';
            $request_title = '';
            $company_id='';
            $project_id='';
            $request_status='';
            $request_priority='';
            $assign_manager_id='';
            $assign_developer_id='';
            if($role_id==3){
                $assign_developer_id=$user_id;
            }
            $cond = array();
            if ($get){
                $request_no = trim($get['request_no']);
                $request_title = trim($get['request_title']);
                $company_id = trim($get['company_id']);
                $project_id = trim($get['project_id']);
                $request_status = trim($get['request_status']);
                $request_priority = trim($get['request_priority']);
                $assign_manager_id = trim($get['assign_manager_id']);
                if($role_id==3){
                    $assign_developer_id=$user_id;
                }else{
                    $assign_developer_id = trim($get['assign_developer_id']);
                }
                
                if (!empty($request_no)) $cond[] = 'request_no = '. intval($request_no).' ';
                if (!empty($request_title)) $cond[] = 'request_title LIKE "%'. $request_title . '%"';  
                if (!empty($company_id)) $cond[] = 'company_id = '. $company_id.' ';
                if (!empty($project_id)) $cond[] = 'project_id = '. $project_id.' ';
                if (!empty($request_status)) {
                     if($request_status==1) $cond[] = 'request_status = '. $request_status.' ';
                     if($request_status==2) $cond[] = 'request_status = '. $request_status.' ';
                     if($request_status==3){
                        //on time                   
                        $today = date('Y-m-d');
                        $cond[] = 'request_status = 1 ';
                        $cond[] = 'estimated_completion > "'. $today.'" ';                                      

                    }
                    if($request_status==4){
                        //overdue
                        $today = date('Y-m-d');
                        $cond[] = 'request_status = 1 ';
                        $cond[] = 'estimated_completion < "'. $today.'" ';                     
                    }
                     
                 }
                 if (!empty($request_priority)) $cond[] = 'priority = '. $request_priority.' ';
                 if (!empty($assign_manager_id)) $cond[] = 'FIND_IN_SET ('.$assign_manager_id.', assign_manager_id)';
                 if (!empty($assign_developer_id)) $cond[] = 'FIND_IN_SET('.$assign_developer_id.',assign_developer_id)';
                 
                  //WHERE FIND_IN_SET(1, field_name);
            }else{
                if (!empty($assign_developer_id)) $cond[] = 'FIND_IN_SET('.$assign_developer_id.',assign_developer_id)';
            }

            $sql = "SELECT COUNT(request_title) total_rows FROM request";
            if (!empty($cond)) $sql .= ' WHERE ' . implode(' AND ', $cond);           
            
            $query =  $this->db->query($sql)->row();   
            //echo $this->db->last_query();
            return $query->total_rows;	
            
            //return $res;
	}
	
        
	public function request_list_search_count($sort_by = 'request_no', $order_by = 'desc',$offset=0,$limit=10,$user_id, $role_id, $get = NULL) {
             $request_no = '';
             $request_title = '';
             $company_id='';
             $project_id='';
             $request_status='';
             $request_priority='';
             $assign_manager_id='';
             $assign_developer_id='';
            if (isset($get) && !empty($get['request_no']) ){
                $request_no = $get['request_no'];
                
            }  
            if (isset($get) && !empty($get['request_title']) ){
                $request_title = $get['request_title'];
            } 
            if (isset($get) && !empty($get['company_id']) ){
                $company_id = $get['company_id'];
            }  
            if (isset($get) && !empty($get['project_id']) ){
                $project_id = $get['project_id'];
            }  
            if (isset($get) && !empty($get['request_status']) ){
                $request_status = $get['request_status'];
            }
            if (isset($get) && !empty($get['request_priority']) ){
                $request_priority = $get['request_priority'];
            }
            if (isset($get) && !empty($get['assign_manager_id']) ){
                $assign_manager_id = $get['assign_manager_id'];
            } 
            if($role_id==3){$assign_developer_id=$user_id;}else{
                if (isset($get) && !empty($get['assign_developer_id']) ){
                    $assign_developer_id = $get['assign_developer_id'];
                } 
            }
            
            $this->db->select('request.*, project.project_name, company.company_name, users.username'); 
            $this->db->join('users', 'request.assign_manager_id = users.uid', 'left'); 
            $this->db->join('project', 'request.project_id = project.id', 'left'); 
			$this->db->join('company', 'request.company_id = company.id', 'left'); 
            
            if (!empty($request_no)) $this->db->where('request_no', intval($request_no)); 
            if (!empty($request_title)) $this->db->like('request_title', $request_title);
            if (!empty($company_id)) $this->db->where('request.company_id', $company_id);
            if (!empty($project_id)) $this->db->where('request.project_id', $project_id); 
            if (!empty($request_status)){ 
                if($request_status==1)$this->db->where('request_status', $request_status); 
                if($request_status==2)$this->db->where('request_status', $request_status);
                if($request_status==3){
                    //on time                   
                    $today = date('Y-m-d');
                    $this->db->where('request_status', 1);
                    $this->db->where('estimated_completion >', $today);                
                    
                }
                if($request_status==4){
                    //overdue
                    $today = date('Y-m-d');
                    $this->db->where('request_status', 1);
                    $this->db->where('estimated_completion <', $today);                    
                }
                
            }
            if (!empty($request_priority)) $this->db->where('priority', $request_priority); 
            if (!empty($assign_manager_id)) $this->db->where("FIND_IN_SET($assign_manager_id , request.assign_manager_id)"); 
            if (!empty($assign_developer_id)) $this->db->where("FIND_IN_SET($assign_developer_id, request.assign_developer_id)");           
        
            //WHERE FIND_IN_SET(1, field_name);
            
            
            $this->db->order_by('request_status asc, '.$sort_by.' '.$order_by);               
            //$this->db->order_by($sort_by, $order_by);
            $this->db->limit($limit,$offset);
            $result =  $this->db->get($this->table_request);
            //echo $this->db->last_query();
            return $result;
	} 

	    public function file_insert($file){
            $this->db->insert($this->table_file,$file);
            return $this->db->insert_id();            
        }
        public function request_file_load($fid){
		$this->db->where('fid', $fid);
		return $this->db->get($this->table_file);
	}
	public function request_file_update($fid, $file){
                    $this->db->where('fid', $fid);
		return $this->db->update($this->table_file,$file);
	}
        
         function get_request_user_info($id){
            
             $this->db->select('request.*, p.project_name as project_name, c.username as created_by,  a.username as manager_name, a.email as manager_email, b.username as developer_name, b.email as developer_email');               
             $this->db->join('project p', 'request.project_id = p.id', 'left');
             $this->db->join('users a', 'request.assign_manager_id = a.uid', 'left'); 
             $this->db->join('users b', 'request.assign_developer_id = b.uid', 'left'); 
             $this->db->join('users c', 'request.created_by = c.uid', 'left'); 
             $this->db->where('request.id', $id);               
             return $this->db->get($this->table_request)->row();
	}
        public function get_manager_info($assign_manager_id){
            $assign_manager= explode(",", $assign_manager_id);         
         
            $this->db->select('username, email');
            $this->db->where_in('uid', $assign_manager);               
            $manager = $this->db->get($this->table_users)->result();             
            return $manager;
        }
        public function get_developer_info($assign_developer_id){
            $assign_developer= explode(",", $assign_developer_id);    
           
            $this->db->select('username, email');
            $this->db->where_in('uid', $assign_developer);               
            $developer = $this->db->get($this->table_users)->result();              
            return $developer;
        }
        
        
        function get_project_by_company($company_id){
            //echo $company_id;
            $query = $this->db->query('SELECT id, project_name FROM project where company_id='.$company_id.' ORDER BY project_name');            
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->id] = $row->project_name; 
            } 
            return $rows;
        }
        function get_company_by_project($project_id){           
             $this->db->select('company_id');                     
             $this->db->where('id', $project_id);               
             return $this->db->get($this->table_project)->row();
        
        }
        function request_is_new($request_id, $user_id){
            
            //echo $request_id.' , '.$user_id;
            $this->db->select('request_viewed_byuser'); 
            $this->db->where('id', $request_id);
           //$this->db->where("FIND_IN_SET($user_id, request_viewed_byuser)"); 
            $result = $this->db->get($this->table_request)->row();           
            $res= $result->request_viewed_byuser;
            if($res==''){$update_ids=$user_id;}else{ $update_ids=$res.','.$user_id;}
            $list_of_ids = explode(',', $res);
            
            if (in_array($user_id, $list_of_ids))
            {
                return 1;
            }
            else
            {
                $update_data = array(
                'request_viewed_byuser' => $update_ids,     
                
                'updated' => date("Y-m-d H:i:s"),
                'updated_by' =>$user_id		
                );
                $this->db->where($this->primary_key,$request_id);
		$this->db->update($this->table_request, $update_data);
                return 0;
            }
       }
       function request_list_is_new($request_id, $user_id, $rid){
            
            //echo $request_id.' , '.$user_id;
            $this->db->select('id, request_viewed_byuser'); 
            $this->db->where('id', $request_id);
           //$this->db->where("FIND_IN_SET($user_id, request_viewed_byuser)"); 
            if($rid==3){
                //$this->db->where('assign_developer_id', $user_id);
                $this->db->where("FIND_IN_SET($user_id, assign_developer_id)");
                
            }else{
                //$this->db->where('assign_manager_id', $user_id);
                $this->db->where("FIND_IN_SET($user_id, assign_manager_id)");
               
            }
            $result = $this->db->get($this->table_request)->row(); 
            
            if(!empty($result)){
                
                if(!$result->request_viewed_byuser=='')
                {
                        $res= $result->request_viewed_byuser;
                        //if($res==''){$update_ids=$user_id;}else{ $update_ids=$res.','.$user_id;}
                        $list_of_ids = explode(',', $res);

                        if (in_array($user_id, $list_of_ids))
                        {
                            return 0;
                        }
                        return $res;
                }
                return 1;
            }
            return 0;
            
            /*if(!empty($result)){
                if(!$result->request_viewed_byuser=='')
                    {
                
                        $res= $result->request_viewed_byuser;
                        //if($res==''){$update_ids=$user_id;}else{ $update_ids=$res.','.$user_id;}
                        $list_of_ids = explode(',', $res);

                        if (in_array($user_id, $list_of_ids))
                        {
                            return 0;
                        }
                        return 1;
                    }
                    return 0;
            }
            return 0;*/
       }
       
	function request_hour($id){ 
		$this->db->select("task_hours.*, c.name as contractor, u.name as user");     
        $this->db->where("task_id",$id); 
        $this->db->join('users c', 'task_hours.contractor_id = c.uid', 'left');
        $this->db->join('users u', 'task_hours.user_id = u.uid', 'left');
        $this->db->order_by('id', "DESC");
        $query = $this->db->get($this->table_request_hours);
        return $query;
    }
    
    function total_hour($id){  
		$this->db->select("hour,minute");     
        $this->db->where("task_id",$id); 
        $query = $this->db->get($this->table_request_hours)->result();
        $total_minute = '';
        foreach($query as $row){
			$h = ($row->hour*60)+$row->minute;
			$total_minute = $h+$total_minute;
		}
        return $total_minute;
    }
    
    public function request_hour_save($add){
		$this->db->insert($this->table_request_hours, $add);
		return $this->db->insert_id();
	}
        
       
}