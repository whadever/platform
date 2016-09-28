<?php
class Report_model extends CI_Model {
	
	private $primary_key = 'id';	
	private $table_request='request';
	private $table_users='users';
	private $table_project = 'project';
	
	function __construct() {
		parent::__construct();
	}
	
	public function getUserName(){
			$user=  $this->session->userdata('user');  
			$wp_company_id = $user->company_id;

            $this->db->select('users.uid, users.username as name, users_application.application_role_id as rid');
			$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
             $this->db->where_in('users_application.application_role_id', array('2','3'));
			$this->db->where('users_application.application_id', '3');
			$this->db->where('users_application.company_id', $wp_company_id);
             $user_name = $this->db->get($this->table_users)->result();
             
            
             return $user_name;
        }
        
        public function getNewRequest(){
			$user=  $this->session->userdata('user');  
			$wp_company_id = $user->company_id;
            
            $today=  date("Y-m-d");            
            $before = strtotime('-6 months');            
            $start_date= date("Y-m-d", $before);
            
             $this->db->select('users.uid, users.username as name, users_application.application_role_id as rid');
			$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
             $this->db->where_in('users_application.application_role_id', array('2','3'));
			$this->db->where('users_application.application_id', '3');
			$this->db->where('users_application.company_id', $wp_company_id);
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

			$user=  $this->session->userdata('user');  
			$wp_company_id = $user->company_id;
            
            $today=  date("Y-m-d");            
            $before = strtotime('-2 months');            
            $start_date= date("Y-m-d", $before);
            
             $this->db->select('users.uid, users.username as name, users_application.application_role_id as rid');
			$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
             $this->db->where_in('users_application.application_role_id', array('2','3'));
			$this->db->where('users_application.application_id', '3');
			$this->db->where('users_application.company_id', $wp_company_id);
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
            //$this->db->last_query();
            //print_r($result);
            
                 
                
            }
            
            return $result;
            
        }
        public function getCloseRequest(){
			$user=  $this->session->userdata('user');  
			$wp_company_id = $user->company_id;
            
            $today=  date("Y-m-d");            
            $before = strtotime('-6 months');            
            $start_date= date("Y-m-d", $before);
            
             $this->db->select('users.uid, users.username as name, users_application.application_role_id as rid');
			$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
             $this->db->where_in('users_application.application_role_id', array('2','3'));
			$this->db->where('users_application.application_id', '3');
			$this->db->where('users_application.company_id', $wp_company_id);
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
			$user=  $this->session->userdata('user');  
			$wp_company_id = $user->company_id;
            
            $today=  date("Y-m-d");            
            $before = strtotime('-6 months');            
            $start_date= date("Y-m-d", $before);
            
             $this->db->select('users.uid, users.username as name, users_application.application_role_id as rid');
			$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
             $this->db->where_in('users_application.application_role_id', array('2','3'));
			$this->db->where('users_application.application_id', '3');
			$this->db->where('users_application.company_id', $wp_company_id);
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

		public function getOpenRequestNew(){

			$user=  $this->session->userdata('user');  
			$wp_company_id = $user->company_id;
            
            $today=  date("Y-m-d");            
            $before = strtotime('-6 months');            
            $start_date= date("Y-m-d", $before);
            
             $this->db->select('users.uid, users.username as name, users_application.application_role_id as rid');
			$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
             $this->db->where_in('users_application.application_role_id', array('2','3'));
			$this->db->where('users_application.application_id', '3');
			$this->db->where('users_application.company_id', $wp_company_id);
             $user_ids = $this->db->get($this->table_users)->result();
             $i=0;
             foreach ($user_ids as $user) {
                 $user_id =  $user->uid;
                 $user_name = $user->name;
                 $user_role = $user->rid;
                 
            $this->db->select('request.request_date, users.username as name');
            $this->db->where('request_date < ', $today);
            $this->db->where('request_date >', $start_date);
            $this->db->where('request_status', 1);            
            $this->db->where("FIND_IN_SET('$user_id',request_viewed_byuser) =", 0);
            
            //$this->db->where('created_by', 7);
            if($user_role==3){
				$this->db->join('users', 'users.uid = request.assign_developer_id', 'left');
                $this->db->where("FIND_IN_SET ($user_id, assign_developer_id)");
            }else{
				$this->db->join('users', 'users.uid = request.assign_manager_id', 'left');
                $this->db->where("FIND_IN_SET ($user_id, assign_manager_id)");
            }
            
            //$this->db->group_by("week(request_date)"); 
            //$this->db->group_by("created_by");
            $result[] = $this->db->get($this->table_request)->result();
            //echo $this->db->last_query();
            //print_r($result);
                             
                
            }
            
            return $result;
            
        }


		public function get_tasks($start_date,$end_date,$status){

			$user=  $this->session->userdata('user');  
			$wp_company_id = $user->company_id;
            $today = date("Y-m-d");
			$this->db->select('count(id) as task_quantity');
            $this->db->where('request_date <= ', $end_date);
            $this->db->where('request_date >=', $start_date);
			if($status=="closed"){
            	$this->db->where('request_status', 2);
			}
			if($status=="new"){
				$this->db->where('request_status', 1);
			}
			if($status=="overdue"){
				$this->db->where('estimated_completion < ', $today);
			}
            $this->db->where('company_id', $wp_company_id);

			return $this->db->get($this->table_request)->row();

		}

		public function get_projects($start_date,$end_date,$status){

			$user=  $this->session->userdata('user');  
			$wp_company_id = $user->company_id;
            $today = date("Y-m-d");
			$this->db->select('count(id) as project_quantity');
            $this->db->where('created <= ', $end_date);
            $this->db->where('created >=', $start_date);
			if($status=="closed"){
            	$this->db->where('project_status', 2);
			}
			if($status=="new"){
				$this->db->where('project_status', 1);
			}
			if($status=="overdue"){
				$this->db->where('project_date < ', $today);
			}
            $this->db->where('wp_company_id', $wp_company_id);

			return $this->db->get($this->table_project)->row();

		}

        
}
?>