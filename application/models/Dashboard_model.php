<?php
class Dashboard_model extends CI_Model{
	private $primary_key = 'uid';
	private $table_users = 'users';
	private $table_users_application = 'users_application';
	private $table_application = 'application';
	private $table_wp_company = 'wp_company';

	private $table_user_job = 'construction_user_job';
	private $table_construction_development = 'construction_development';
	
	function __construct(){
		parent::__construct();	
	}
	
	public function get_create_user_access($uid){		
		$this->db->where('user_id', $uid);
		$this->db->where('application_role_id', '1');
		return $this->db->get($this->table_users_application);
	}
	
	public function get_create_user_app_access($uid){	
		$this->db->where('user_id', $uid);
		$this->db->where('application_role_id', '1');
		return $this->db->get($this->table_users_application);
	} 
	
	public function get_user_app_info($uid){

		$user=  $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		$this->db->select('users_application.*');
		$this->db->join('wp_company_applications apps','apps.application_id = users_application.application_id');
		$this->db->where('user_id', $uid);
		$this->db->where('apps.company_id', $wp_company_id);

		return $this->db->get($this->table_users_application);
	}
	
	public function get_master_admin_access(){
		return $this->db->get($this->table_application);
	}	

	public function user_load($uid){
		$this->db->where($this->primary_key, $uid);
		return $this->db->get($this->table_users)->row();
	} 

	 public function user_client($client_id) {
    	$this->db->select("wp_company.*, wp_file.filename"); 
    	$this->db->join('wp_file', 'wp_company.file_id = wp_file.id', 'left');
        $this->db->where('wp_company.id',$client_id);
        return $this->db->get($this->table_wp_company)->row();      
    }
    public function client_background($client_id) {
    	$this->db->select("wp_file.filename"); 
    	$this->db->join('wp_file', 'wp_company.backgroundWclp_id = wp_file.id', 'left');
        $this->db->where('wp_company.id',$client_id);
        return $this->db->get($this->table_wp_company)->row();      
    }

	public function get_construction_info($uid)
	{
		$this->db->select('`job_id`');
		$this->db->where('user_id', $uid);
		$result = $this->db->get($this->table_user_job)->row();
		return $result;
	}

	public function get_construction_last_info()
	{		
		$user=  $this->session->userdata('user'); 
		$wp_company_id = $user->company_id;
		$user_id = $user->uid;

		$this->db->select('id');
		$this->db->where('system_user_id',$user_id);
		$contact_user_id = $this->db->get('contact_contact_list')->row()->id;
		if(!$contact_user_id){
			$contact_user_id = 0; 
		}

		$this->db->select('application_role_id');
		$this->db->where('user_id',$user_id);
		$this->db->where('application_id',5);
		$app_role_id = $this->db->get('users_application')->row()->application_role_id;

		
		$this->db->select('`id`');
		$this->db->where('wp_company_id', $wp_company_id);
		
		if($app_role_id=='5'){
			$this->db->where("FIND_IN_SET ($contact_user_id, investor)");
		}else if($app_role_id=='4'){
			$this->db->where("FIND_IN_SET ($contact_user_id, builder)");
		}else if($app_role_id=='3'){
			$this->db->where("FIND_IN_SET ($contact_user_id, roofing_contractor)");
		}
		
		$this->db->order_by('id', 'DESC');
		$result = $this->db->get($this->table_construction_development)->row();
		return $result;
	}
	public function get_construction_permitted_job($uid,$user_role_id){
		$this->db->select('`job_id`');
		$this->db->where('user_id', $uid);
		$this->db->where('user_role_id', $user_role_id);
		$result = $this->db->get('construction_user_permitted_job')->row();
		return $result;
	}
	
	function isWeekend($date) {
	    return (date('N', strtotime($date)) >= 6);
	}
}
