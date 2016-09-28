<?php
class Dashboardglobal_model extends CI_Model{
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
		$this->db->where('user_id', $uid);
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

		$this->db->select('`id`');
		$this->db->where('wp_company_id', $wp_company_id);
		$this->db->order_by('id', 'DESC');
		$result = $this->db->get($this->table_construction_development)->row();
		return $result;
	}
}
