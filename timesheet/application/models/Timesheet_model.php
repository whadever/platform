<?php
class timesheet_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_timesheet_entries='ts_timesheet_entries';

	private $table_request_leave ='ts_request_leave';

    function __construct() {
		parent::__construct();
	}

	public function load_project_task_by_commercial($project_id){
		$this->db->select('request_no,request_title');
        $this->db->where("company_id",'24');
		$this->db->where("project_id",$project_id);
        return $this->db->get('request');
    }

	public function request_leave_send_manager_email($company_id){
		$this->db->select('users.email,users.username');
		$this->db->join('users','users.uid = users_application.user_id','LEFT');
        $this->db->where("users_application.company_id",$company_id);
		$this->db->where("users_application.application_id",'7');
		$this->db->where("users_application.application_role_id",'2');
        return $this->db->get('users_application');
    }

	public function request_leave_check($user_id,$date_form){
		$date_form = date('Y-m-d',strtotime($date_form));
        $this->db->where("created_by",$user_id);
		$this->db->where("date_form <=",$date_form);
		$this->db->where("date_to >=",$date_form);
        return $this->db->get($this->table_request_leave);
    }

	public function request_leave_add($add){
        $this->db->insert($this->table_request_leave, $add);
    }
	
	public function get_time_entries($day, $timer_status='', $user_id = '', $project_id = ''){
        if(!$user_id){
            $user=  $this->session->userdata('user');
            $user_id = $user->uid;
        }
        $this->db->where("user_id",$user_id);

        if($project_id){
            $this->db->where("project_id",$project_id);

        }
        if(!is_array($day)){
            $this->db->where("day", $day);
        }else{
            $this->db->where("day BETWEEN '{$day[0]}' AND '{$day[1]}'");
        }
        if($timer_status=='0'){
            $this->db->where("timer_status", $timer_status);
        }
        $this->db->order_by('start_time');
        return $this->db->get($this->table_timesheet_entries);
    }
    
    public function get_time_entries_timer($day, $timer_status='', $user_id = ''){
    	$this->db->select('ts_timesheet_entries.*, project.project_name');
    	$this->db->join('project','project.id = ts_timesheet_entries.project_id','LEFT');
        if(!$user_id){
            $user=  $this->session->userdata('user');
            $user_id = $user->uid;
        }
        $this->db->where("user_id",$user_id);

        if(!is_array($day)){
            $this->db->where("day", $day);
        }else{
            $this->db->where("day BETWEEN '{$day[0]}' AND '{$day[1]}'");
        }
        if($timer_status=='1'){
            $this->db->where("timer_status", $timer_status);
        }
        $this->db->order_by('start_time');
        return $this->db->get($this->table_timesheet_entries);
    }

    public function add_timesheet_entry($data){
        $this->db->insert($this->table_timesheet_entries, $data);
    }
    public function update_timesheet_entry($user_id, $entry_id, $data){
        $this->db->where($this->primary_key, $entry_id);
        $this->db->where('user_id', $user_id);
        $this->db->update($this->table_timesheet_entries, $data);
    }
    function delete_timesheet_entry($user_id, $entry_id){
        $this->db->where($this->primary_key, $entry_id);
        $this->db->where('user_id', $user_id);
        $this->db->delete($this->table_timesheet_entries);
    }

	function get_staff_list(){
		$user = $this->session->userdata('user');
		$this->db->select('users_application.user_id AS staff_id, users.username,');
		$this->db->join('users','users.uid = users_application.user_id','LEFT');
		$this->db->where('users_application.application_id',7);
		$this->db->where_in('users_application.application_role_id',array('2','3'));
		$this->db->where('users_application.company_id',$user->company_id);
		return $this->db->get('users_application');
	}
	function check_role($role_id,$user_id){
		$user = $this->session->userdata('user');
		$this->db->select('users_application.*');
		$this->db->where('users_application.application_id',7);
		$this->db->where('users_application.application_role_id',$role_id);
		$this->db->where('users_application.user_id',$user_id);
		$this->db->where('users_application.company_id',$user->company_id);
		$data = $this->db->get('users_application')->result();
		if($data) return true;

	}
}
?>