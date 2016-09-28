<?php
class Report_model extends CI_Model {
	
	private $primary_key = 'id';	
	private $table_request='request';
        private $table_users='users';
	
	function __construct() {
		parent::__construct();
	}
	
	public function getUserName(){
            $this->db->select('users.uid, users.username as name, users_application.application_role_id as rid');
			$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
             $this->db->where('users_application.application_role_id !=', 1);
			$this->db->where('users_application.application_id', '4');
             $user_name = $this->db->get($this->table_users)->result();
             
            
             return $user_name;
        }
        
        public function getNewRequest(){
            
            $today=  date("Y-m-d");            
            $before = strtotime('-6 months');            
            $start_date= date("Y-m-d", $before);
            
             $this->db->select('users.uid, users.username as name, users_application.application_role_id as rid');
			$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
             $this->db->where('users_application.application_role_id !=', 1);
			$this->db->where('users_application.application_id', '4');
             $user_ids = $this->db->get($this->table_users)->result();
             $i=0;
             foreach ($user_ids as $user) {
                 $user_id =  $user->uid;
                 $user_name = $user->name;
                 $user_role = $user->rid;
                 
            $this->db->select('count(id) as request_quantity, week(request_date) as week');
            $this->db->where('request_date < ', $today);
            $this->db->where('request_date >', $start_date);
            $this->db->where('request_status', 1);            
            $this->db->where("FIND_IN_SET('$user_id',request_viewed_byuser) =", 0);
            
            //$this->db->where('created_by', 7);
            if($user_role==3){
                $this->db->where("FIND_IN_SET ($user_id, assign_developer_id)");
            }else{
                $this->db->where("FIND_IN_SET ($user_id, assign_manager_id)");
            }
            
            $this->db->group_by("week(request_date)"); 
            //$this->db->group_by("created_by");
            $result[] = $this->db->get($this->table_request)->result();
            //echo $this->db->last_query();
            //print_r($result);
            
                 
                
            }
            
            return $result;
            
        }
	
        public function getOpenRequest(){
            
            $today=  date("Y-m-d");            
            $before = strtotime('-6 months');            
            $start_date= date("Y-m-d", $before);
            
             $this->db->select('users.uid, users.username as name, users_application.application_role_id as rid');
			$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
             $this->db->where('users_application.application_role_id !=', 1);
			$this->db->where('users_application.application_id', '4');
             $user_ids = $this->db->get($this->table_users)->result();
             $i=0;
             foreach ($user_ids as $user) {
                 $user_id =  $user->uid;
                 $user_name = $user->name;
                 $user_role = $user->rid;
                 
            $this->db->select('count(id) as request_quantity, week(request_date) as week');
            $this->db->where('request_date < ', $today);
            $this->db->where('request_date >', $start_date);
            $this->db->where('request_status', 1);
            
            
            //$this->db->where('created_by', 7);
            if($user_role==3){
                $this->db->where("FIND_IN_SET ($user_id, assign_developer_id)");
            }else{
                $this->db->where("FIND_IN_SET ($user_id, assign_manager_id)");
            }
            
            $this->db->group_by("week(request_date)"); 
            //$this->db->group_by("created_by");
            $result[] = $this->db->get($this->table_request)->result();
            //echo $this->db->last_query();
            //print_r($result);
            
                 
                
            }
            
            return $result;
            
        }
        public function getCloseRequest(){
            
            $today=  date("Y-m-d");            
            $before = strtotime('-6 months');            
            $start_date= date("Y-m-d", $before);
            
             $this->db->select('users.uid, users.username as name, users_application.application_role_id as rid');
			$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
             $this->db->where('users_application.application_role_id !=', 1);
			$this->db->where('users_application.application_id', '4');
             $user_ids = $this->db->get($this->table_users)->result();
             $i=0;
             foreach ($user_ids as $user) {
                 $user_id =  $user->uid;
                 $user_name = $user->name;
                 $user_role = $user->rid;
                 
            $this->db->select('count(id) as request_quantity, week(request_date) as week');
            $this->db->where('request_date < ', $today);
            $this->db->where('request_date >', $start_date);
            $this->db->where('request_status', 2);
            
            
            //$this->db->where('created_by', 7);
            if($user_role==3){
                $this->db->where("FIND_IN_SET ($user_id, assign_developer_id)");
            }else{
                $this->db->where("FIND_IN_SET ($user_id, assign_manager_id)");
            }
            
            $this->db->group_by("week(request_date)"); 
            //$this->db->group_by("created_by");
            $result[] = $this->db->get($this->table_request)->result();
            //echo $this->db->last_query();
            //print_r($result);
            
                 
                
            }
            
            return $result;
            
        }
        public function getOverdueRequest(){
            
            $today=  date("Y-m-d");            
            $before = strtotime('-6 months');            
            $start_date= date("Y-m-d", $before);
            
             $this->db->select('users.uid, users.username as name, users_application.application_role_id as rid');
			$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
             $this->db->where('users_application.application_role_id !=', 1);
			$this->db->where('users_application.application_id', '4');
             $user_ids = $this->db->get($this->table_users)->result();
             $i=0;
             foreach ($user_ids as $user) {
                 $user_id =  $user->uid;
                 $user_name = $user->name;
                 $user_role = $user->rid;
                 
            $this->db->select('count(id) as request_quantity, week(request_date) as week');
            $this->db->where('request_date < ', $today);
            $this->db->where('request_date >', $start_date);
            $this->db->where('request_status', 1);
            $this->db->where('estimated_completion < ', $today);
            
            
            
            //$this->db->where('created_by', 7);
            if($user_role==3){
                $this->db->where("FIND_IN_SET ($user_id, assign_developer_id)");
            }else{
                $this->db->where("FIND_IN_SET ($user_id, assign_manager_id)");
            }
            
            $this->db->group_by("week(request_date)"); 
            //$this->db->group_by("created_by");
            $result[] = $this->db->get($this->table_request)->result();
            //echo $this->db->last_query();
            //print_r($result);
            
                 
                
            }
            
            return $result;
            
        }
        
}
?>