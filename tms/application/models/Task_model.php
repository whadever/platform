<?php 
class Task_model extends CI_Model {

	private $primary_key = 'request.id';
	private $table_request = 'request';
    private $table_request_hours = 'task_hours';
	private $table_file = 'tms_file';
	private $table_project = 'project';
	private $table_users = 'users';
    
	function __construct(){
		parent::__construct();
	}
	public function request_save($request_data){
		$this->db->insert($this->table_request, $request_data);
		return $this->db->insert_id();
	}  

	public function get_pending_tasks(){
		$user = $this->session->userdata('user');
		$user_id = $user->uid;
		$company_id = $user->company_id;

		$this->db->select('id, request_no, estimated_completion');
		$this->db->where("FIND_IN_SET ($user_id, assign_manager_id)");
		$this->db->where('request_status', 1);
		$this->db->where('company_id', $company_id);
		return $cresult=  $this->db->get($this->table_request)->result();
		//echo $this->db->last_query();
            
	} 

	public function get_closed_tasks(){
		$user = $this->session->userdata('user');
		$user_id = $user->uid;
		$company_id = $user->company_id;

		$this->db->select('id, request_no, estimated_completion');
		$this->db->where('request_status', 2);
		$this->db->where("approved_by_admin",0);
		$this->db->where('company_id', $company_id);
		return $cresult=  $this->db->get($this->table_request)->result();
		//echo $this->db->last_query();
            
	} 

	
}