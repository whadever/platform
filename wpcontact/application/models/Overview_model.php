<?php 
class Overview_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_company = 'contact_company';
	private $table_contact = 'contact_contact_list';
	private $table_category = 'contact_category';
	private $table_users = 'users';
        
	function __construct() {
		parent::__construct();
	}
	
	public function get_overview_new_company_list()
	{
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		$seven_days_ago = date('Y-m-d', strtotime('-7 days'));

		$this->db->select('contact_company.*');
		$this->db->where("wp_company_id",$wp_company_id); 
		$this->db->where('DATE(created)>=', DATE($seven_days_ago));
		return $this->db->get($this->table_company);
	}
	public function get_overview_new_contact_list()
	{
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		$seven_days_ago = date('Y-m-d', strtotime('-7 days'));

		$this->db->select('contact_contact_list.*');
		$this->db->where("wp_company_id",$wp_company_id); 
		$this->db->where('DATE(contact_contact_list.created)>=', DATE($seven_days_ago));
		return $this->db->get($this->table_contact);
	}  
	public function get_overview_new_category_list()
	{
		$user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

		$seven_days_ago = date('Y-m-d', strtotime('-7 days'));

		$this->db->select('contact_category.*');
		$this->db->where("wp_company_id",$wp_company_id);   
		$this->db->where('DATE(created)>=', DATE($seven_days_ago));
		return $this->db->get($this->table_category);
	}     
        
}
?>