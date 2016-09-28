<?php 
class Report_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_user = 'users';
    private $table_wp_company = 'wp_company';
        	
    function __construct() {
		parent::__construct();
    }
    
    public function at_risk_client() {
		$now = date("Y-m-d");
		$now7 = date('Y-m-d', strtotime($now. ' - 7 days'));
		//print_r($now7);exit;
		$this->db->select('wp_company.*, users.email, users.created, max(users2.last_login) last_login');
		$this->db->join('users', 'users.company_id = wp_company.id', 'left');
		$this->db->join('users users2','users2.company_id = wp_company.id'); //task #4477
		/*$this->db->where('users.last_login <', $now7);
		$this->db->where('users.last_login !=', '0000-00-00');*/
		/*task #4477*/
		$this->db->having('max(users2.last_login) <', $now7);
		$this->db->having('max(users2.last_login) !=', '0000-00-00');

		$this->db->where('users.role', '1');
		//$this->db->order_by('users.last_login', 'ASC');
		$this->db->order_by('wp_company.id', 'DESC');/*task #4477*/
		$this->db->group_by('wp_company.id');
		return $this->db->get($this->table_wp_company);
		//echo $this->db->last_query(); 
    }
    public function invoice() {
		$this->db->select('wp_company.id as company_id, wp_company.client_name, wp_company.url, wp_company.plan_id, wp_plans.name as plan_name, wp_company.pricing,  users.email, users.created, users.last_login'); 
		$this->db->join('users', 'users.company_id = wp_company.id', 'left');
		$this->db->join('wp_plans', 'wp_plans.id = wp_company.plan_id', 'left');
		//$this->db->where('users.last_login <', $now7);
		//$this->db->where('users.last_login !=', '0000-00-00');
		$this->db->where('users.role', '1');
		//$this->db->order_by('users.last_login', 'ASC');
		return $this->db->get($this->table_wp_company); 
		//echo $this->db->last_query(); 
    }
    public function applications($company_id) {
		$this->db->select('users_application.user_id, users_application.application_id, application.application_name'); 
		$this->db->join('users', 'users.uid = users_application.user_id', 'left');
		$this->db->join('application', 'application.id = users_application.application_id', 'left');
		//$this->db->where('users.last_login <', $now7);
		$this->db->where('users_application.company_id', $company_id);
		$this->db->where('users.role', '1');
		//$this->db->order_by('users.last_login', 'ASC');
		return $this->db->get('users_application'); 
		//echo $this->db->last_query(); 
    }
		
}	