<?php 
class Contact_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_contact = 'contact_contact_list';
	private $table_request= 'request';
	private $table_task_hours='task_hours';
	private $table_users = 'users';
	private $table_file = 'file';
	
	function __construct() {
		parent::__construct();
	}   
	function get_contact_no(){
		$sql = "SELECT MAX(project_no) max_project_no FROM project";
		$query =  $this->db->query($sql)->row();                
		return $query->max_project_no;	 
	}
	public function contact_save($contact_data,$contact_id)
	{
		if($contact_id==0)
		{
			$this->db->insert($this->table_contact, $contact_data);
		}
		else
		{
			$this->db->where('id',$contact_id);
			$this->db->update($this->table_contact, $contact_data);
		}
		return $this->db->insert_id();
	}
	public function contact_count_all() {
		return $this->db->count_all($this->table_contact);
	}
	public function contact_list_count() {
		$this->db->order_by('contact_title', 'ASC');
		$this->db->limit(50);
		return $this->db->get($this->table_contact);
	} 
	function get_contact_list($limit=10, $offset=0, $order_by = 'id', $order = 'desc')
	{
		$user=  $this->session->userdata('user');  
		$user_id =$user->uid;
        $wp_company_id = $user->company_id;

		$this->db->select("contact_contact_list.*, contact_company.company_name,contact_company.company_website, contact_category.category_name");
		$this->db->join('contact_company', 'contact_contact_list.company_id = contact_company.id', 'left');  
		$this->db->join('contact_category', 'contact_contact_list.category_id = contact_category.id', 'left');             
		$this->db->where("contact_contact_list.status = 1");
        $this->db->where("contact_contact_list.wp_company_id = {$wp_company_id}");
        if($limit != 0){

            $this->db->limit($limit,$offset);
        }
        $this->db->order_by($order_by, $order);
        $result = $this->db->get($this->table_contact);
		//echo  $this->db->last_query();
		return $result;
	}
        
        
	public function project_list_search_count($project_name, $sort_by = 'id', $order_by = 'desc',$offset=0,$limit=10,$get = NULL) {
		$this->db->like("project_name", $project_name);
		if($sort_by == 'project_status'){
			$this->db->order_by($sort_by.' '.$order_by.' , project_name asc');                
		}else{
			$this->db->order_by($sort_by, $order_by);
		}
		
		$this->db->limit($limit,$offset);
		
		$result = $this->db->get($this->table_project);
		//echo  $this->db->last_query();
		return $result;
            
	} 
	
	function delete_project_with_requests_notes($pid){
            /* echo $pid;
            
            $request_sql = "select id from request where project_id=".$pid;
            $request_res= mysql_query($request_sql);
            while ($request_row = mysql_fetch_assoc($request_res)) {
                 $request_ids[]= $request_row['id'];
            }
            print_r($request_ids);      
                
                
             * 
             */
		$this->db->where($this->primary_key,$pid);
		$this->db->delete($this->table_project);
	}
        function close_project($pid){
		$data = array(
               'project_status' => 2
            );

        $this->db->where('id', $pid);
        $this->db->update('project', $data); 

		
	}
	
	function update_project($pid, $person){
		$this->db->where($this->primary_key, $pid);
		$this->db->update($this->table_project, $person);
	}

    function get_contact_details($contact_id){
        $this->db->select('contact_contact_list.*, file.filename,contact_company.company_name');
        $this->db->join('contact_company', 'contact_company.id = contact_contact_list.company_id', 'left');
        $this->db->join('file', 'file.fid = contact_contact_list.contact_image_id', 'left');
        $this->db->where('contact_contact_list.id',$contact_id);
        return $this->db->get($this->table_contact)->row();
    }

    public function get_assign_manager($assign_manager_id){
            $assign_manager= explode(",", $assign_manager_id);
            
            if($assign_manager[0]>0){
                $manager_name=array();
                foreach($assign_manager as $manager_id){
                    $this->db->select('username');
                    $this->db->where('uid', $manager_id);               
                  $manager = $this->db->get($this->table_users)->row();
                  $manager_name[]= $manager->username;      

                }
                $manager_name_string = implode(", ", $manager_name);
            
            }else{
                $manager_name_string = 'No Manager Assigned';
            }
            
            return $manager_name_string;
        }
        public function get_assign_developer($assign_developer_id){
            $assign_developer= explode(",", $assign_developer_id); 
            if($assign_developer[0]>0){
                $developer_name=array();
                foreach($assign_developer as $developer_id){
                    $this->db->select('username');
                    $this->db->where('uid', $developer_id);               
                  $developer = $this->db->get($this->table_users)->row();
                  $developer_name[]= $developer->username; 
                }
                $developer_name_string = implode(", ", $developer_name);
            }else{
                $developer_name_string = 'No contractor Assigned';
            }
            return $developer_name_string;
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
	
        
	public function file_insert($file){
            $this->db->insert($this->table_file,$file);
            return $this->db->insert_id();            
        }
        function get_project_open_bug($pid, $user_id, $role_id){
		$this->db->select('`id`, `request_no`, `request_title`, `request_status`'); 
                $this->db->where('project_id', $pid);
                $this->db->where('request_status', 1);
                if($role_id==3){
                    $this->db->where("FIND_IN_SET($user_id , assign_developer_id)");
                }
		return $this->db->get($this->table_request);
	}
        
        function get_project_close_request($pid, $user_id, $role_id){
		$this->db->select('`id`, `request_no`, `request_title`, `request_status`'); 
                $this->db->where('project_id', $pid);
                $this->db->where('request_status', 2);
                if($role_id==3){
                    $this->db->where("FIND_IN_SET($user_id , assign_developer_id)");
                }
		return $this->db->get($this->table_request);
	}
        public function get_project_hours($pid){
            //$result = $this->db->get($this->table_task_hours);             
            //return $result;
            $this->db->select('task_hours.*, request.request_title as task_title, users.username');
            $this->db->from('task_hours');
            $this->db->join('request', 'request.id = task_hours.task_id', 'left');
            $this->db->join('project', 'project.id = request.project_id', 'left');
            $this->db->join('users', 'task_hours.created_by = users.uid', 'left');
            $this->db->where('project.'.$this->primary_key, $pid);
            $query = $this->db->get();
            return $query;
            
        }
        public function get_project_total_hours($pid){
            //$result = $this->db->get($this->table_task_hours);             
            //return $result;
            $this->db->select('sum(task_hours.hour) as total_hours, sum(task_hours.minute) as total_minutes');
            $this->db->from('task_hours');
            $this->db->join('request', 'request.id = task_hours.task_id', 'left');
            $this->db->join('project', 'project.id = request.project_id', 'left');            
            $this->db->where('project.'.$this->primary_key, $pid);
            $query = $this->db->get();
            return $query;
            
        }
         
      
}
?>