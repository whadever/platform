<?php 
class Overview_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_project = 'project';
	private $table_request= 'request';
	private $table_notes = 'notes';
	private $table_task_notes = 'task_notes';
	private $table_users = 'users';
        
	
	
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
	function get_overview_requests($uid, $role){
            $now= date('Y-m-d');
            if($role=='contractor'){
                //$this->db->where('assign_developer_id', $uid);
                $this->db->where("FIND_IN_SET ($uid, request.assign_developer_id)");
                $this->db->where('request_status', 1);
            }else{
                //$this->db->where('assign_manager_id', $uid);
                $this->db->where("FIND_IN_SET ($uid, request.assign_manager_id)");
                $this->db->where('request_status', 1);
                /*task #4102*/
                switch($this->input->post('view')){
                    case 1:
                        /*managers only*/
                        $this->db->where("(request.assign_developer_id = '0' OR request.assign_developer_id = '')");
                        break;
                    case 2:
                        /*contractor only*/
                        $this->db->where("(request.assign_developer_id != '0' AND request.assign_developer_id != '')");
                        break;
                }
            }
            $this->db->where("FIND_IN_SET ($uid, request_viewed_byuser)");
            $this->db->where('DATE(estimated_completion)>=', DATE($now));

			/*will not include tasks in today's task list*/
        	$this->db->where('is_in_todays_list !=',1);

            $this->db->order_by('id', "desc");
            $result = $this->db->get($this->table_request);
            return $result;
		
                
	} 
        
        function get_overview_corrosponds_requests($uid, $rid){
            $this->db->select('id, request_no, request_status');
            if($rid==3){
                
                $this->db->where("FIND_IN_SET ($uid, request.assign_developer_id)");
                $this->db->where('request_status', 1);
            }else{
                //$this->db->where('assign_manager_id', $uid);
                $this->db->where("FIND_IN_SET ($uid, request.assign_manager_id)");
                $this->db->where('request_status', 1);
            }  
            
             //WHERE FIND_IN_SET(1, field_name);
            //$this->db->where("FIND_IN_SET($assign_developer_id, assign_developer_id)"); 
            
            $cresult=  $this->db->get($this->table_request)->result();
            //print_r($cresult);
            $new_num_request=0;
            
            
            foreach ($cresult as $crow)
            {
                $request_id = $crow->id; 
                //$this->db->select('request_id');
                $this->db->where('request_id', $request_id);
                $has_notes = $this->db->get($this->table_notes)->result();
                //print_r($has_notes);
                if (empty($has_notes)) {
                    $new_num_request +=1;
                }
                
            }
            return $new_num_request; 
                
	} 
        
        function get_overview_new_requests_list($uid, $role){
            $now= date('Y-m-d');
            $this->db->select('request.*, project.project_name, users.username');
            $this->db->join('project', 'request.project_id = project.id', 'left');
            $this->db->join('users', 'request.assign_manager_id = users.uid', 'left'); 
            if($role=='contractor'){
                //$this->db->where('assign_developer_id', $uid);
                $this->db->where("FIND_IN_SET ($uid, request.assign_developer_id)");
                $this->db->where('request_status', 1);
            }else{
                //$this->db->where('assign_manager_id', $uid);
                $this->db->where("FIND_IN_SET ($uid, request.assign_manager_id)");
                $this->db->where('request_status', 1);
                /*task #4102*/
                switch($this->input->post('view')){
                    case 1:
                        /*managers only*/
                        $this->db->where("(request.assign_developer_id = '0' OR request.assign_developer_id = '')");
                        break;
                    case 2:
                        /*contractor only*/
                        $this->db->where("(request.assign_developer_id != '0'  AND request.assign_developer_id != '')");
                        break;
                }
            } 
            //$this->db->where("FIND_IN_SET ($uid, request_viewed_byuser)");
            $this->db->where("FIND_IN_SET('$uid',request_viewed_byuser) =", 0);
            $this->db->where('DATE(estimated_completion)>=', DATE($now));
            
			/*will not include tasks in today's task list*/
        	$this->db->where('is_in_todays_list !=',1);

            $this->db->order_by('id', "desc");
            $cresult=  $this->db->get($this->table_request);
            //echo $this->db->last_query();            
            //print_r($cresult->result());
            return $cresult;
            /*
            $new_request_id=array();
            
            
            foreach ($cresult as $crow)
            {
                $request_id = $crow->id; 
                //$this->db->select('request_id');
                $this->db->where('request_id', $request_id);
                $has_notes = $this->db->get($this->table_notes)->result();
                //print_r($has_notes);
                if (empty($has_notes)) {
                    $new_request_id[] = $request_id;
                }
                
            }
            //print_r($new_request_id);
            //$new_request_ids= implode(',', $new_request_id);
             if (!empty($new_request_id)) {
                //$this->db->select('request.*, project.project_name, users.name'); 
                //$this->db->join('users', 'request.assign_manager_id = users.uid', 'left'); 
                
                $this->db->select('request.*, project.project_name'); 
                $this->db->join('project', 'request.project_id = project.id', 'left'); 
                $this->db->where_in('request.id', $new_request_id);
                $this->db->order_by('request.id', 'desc');
                $new_result = $this->db->get($this->table_request)->result();
                //echo $this->db->last_query();
                return $new_result;
             }else{
                 
             }
             * 
             */
             
                
	} 
        public function get_assign_manager($assign_manager_id){
            $assign_manager= explode(",", $assign_manager_id);           
            foreach($assign_manager as $manager_id){
                $this->db->select('name');
                $this->db->where('uid', $manager_id);               
              $manager = $this->db->get($this->table_users)->row();
              $manager_name[]= $manager->name;      
              
            }
            return $manager_name;
        }
        
        function get_overview_overdue_requests($uid, $role){
            $this->db->select('id, request_no, estimated_completion');
            if($role=='contractor'){
                //$this->db->where('assign_developer_id', $uid);
                $this->db->where("FIND_IN_SET ($uid, assign_developer_id)");
                $this->db->where('request_status', 1);
            }else{
                $this->db->where("FIND_IN_SET ($uid, assign_manager_id)");
                //$this->db->where('assign_manager_id', $uid);
                $this->db->where('request_status', 1);
                /*task #4102*/
                switch($this->input->post('view')){
                    case 1:
                        /*managers only*/
                        $this->db->where("(request.assign_developer_id = '0' OR request.assign_developer_id = '')");
                        break;
                    case 2:
                        /*contractor only*/
                        $this->db->where("(request.assign_developer_id != '0'  AND request.assign_developer_id != '')");
                        break;
                }
            }          
            
            $cresult=  $this->db->get($this->table_request)->result();
            //print_r($cresult);
            $now= date('Y-m-d');
            $overdue_num_request=0;
            
            
            foreach ($cresult as $crow)
            {
                //$request_id = $crow->id; 
                $estimated_completion = $crow->estimated_completion;                
                
                
                if ($estimated_completion<$now){
                    $overdue_num_request +=1;
                }
                
            }
            
            return $overdue_num_request; 
                
	} 
        
        function get_overview_overdue_requests_list($uid, $role){
            $this->db->select('id, request_no, estimated_completion');
            if($role=='contractor'){
                //->db->where('assign_developer_id', $uid);
                $this->db->where("FIND_IN_SET ($uid, assign_developer_id)");
                $this->db->where('request_status', 1);
            }else{
                //$this->db->where('assign_manager_id', $uid);
                $this->db->where("FIND_IN_SET ($uid, assign_manager_id)");
                $this->db->where('request_status', 1);
                /*task #4102*/
                switch($this->input->post('view')){
                    case 1:
                        /*managers only*/
                        $this->db->where("(request.assign_developer_id = '0'  OR request.assign_developer_id = '')");
                        break;
                    case 2:
                        /*contractor only*/
                        $this->db->where("(request.assign_developer_id != '0' AND request.assign_developer_id != '')");
                        break;
                }
            }   

			/*will not include tasks in today's task list*/
        	$this->db->where('is_in_todays_list !=',1);       
            
            $cresult=  $this->db->get($this->table_request)->result();
            //print_r($cresult);
            $now= date('Y-m-d');
            $overdue_request_id=array();
            
            
            foreach ($cresult as $crow)
            {
                $request_id = $crow->id; 
                $estimated_completion = $crow->estimated_completion;                
                
                
                if ($estimated_completion<$now){
                    $overdue_request_id[]=$request_id;
                }
                
            }
            if (!empty($overdue_request_id)) {
                $this->db->where_in('id', $overdue_request_id);
                $new_result = $this->db->get($this->table_request)->result();
                //echo $this->db->last_query();
                return $new_result;
             }else{
                 
             }
            
                
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

	/* we are resetting all today's task which are older than 24 hours*/
    function reset_todays_tasks(){
        $data = array(
            'is_in_todays_list' => 0
        );

        $this->db->where('is_in_todays_list', 1)->where('added_to_todays_list_at <',time()-24*3600);
        $this->db->update($this->table_request, $data);
    }

	/*getting the today's tasks*/
	function get_overview_todays_task_list($uid, $role){

            $now = date('Y-m-d');
            $this->db->select('request.*, project.project_name, users.username');
            $this->db->join('project', 'request.project_id = project.id', 'left');
            $this->db->join('users', 'request.assign_manager_id = users.uid', 'left');
            if ($role == 'contractor') {
                $this->db->where("FIND_IN_SET ($uid, request.assign_developer_id)");
            } else {
                //task #4102
                $this->db->where("FIND_IN_SET ($uid, request.assign_manager_id)");
                switch($this->input->post('view')){
                    case 1:
                        /*managers only*/
                        $this->db->where("(request.assign_developer_id = '0' OR request.assign_developer_id = '')");
                        break;
                    case 2:
                        /*contractor only*/
                        $this->db->where("(request.assign_developer_id != '0' AND request.assign_developer_id != '')");
                        break;
                }
            }
            $this->db->where('request_status', 1);
            $this->db->where('is_in_todays_list',1);
            $this->db->order_by('id', "desc");
            $cresult = $this->db->get($this->table_request);
            return $cresult;
    }

	function check_new_notes($task_id){
		$user = $this->session->userdata('user');
		$uid = $user->uid;
		
		$this->db->select("nid,notify_user_id,new_note_notify_user_id,notes_viewed_byuser");
		$this->db->where('request_id',$task_id);
		$result = $this->db->get($this->table_task_notes)->result();
		
		for($i=0; $i<count($result); $i++){
			$notify_user_id = $result[$i]->notify_user_id;
			$notify_user_ids = explode(",",$notify_user_id);
			$notes_viewed_byuser = $result[$i]->notes_viewed_byuser;
			$notes_viewed_byusers = explode(",",$notes_viewed_byuser);
	
			if(in_array($uid,$notify_user_ids) && !in_array($uid,$notes_viewed_byusers)){
				return 1;
			}
		}
	}
        
}
?>