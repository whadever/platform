<?php 
class Category_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_category = 'contact_category';
	
	function __construct() {
		parent::__construct();
	}
        
	public function category_save($category_data){
		$this->db->insert($this->table_category, $category_data);
		return $this->db->insert_id();
	}
	public function category_update($category_data,$category_id){
		$this->db->where($this->primary_key,$category_id);
		return $this->db->update($this->table_category,$category_data);
	}
	public function category_count_all() {
		return $this->db->count_all($this->table_category);
	}
	public function category_list_count() {
		$this->db->order_by('id', 'ASC');
		$this->db->limit(50);
		return $this->db->get($this->table_category);
	}
	function get_category_list(){
		
		$this->db->select("contact_category.*");           
		$this->db->where("contact_category.status","1");                
		$result = $this->db->get($this->table_category);
		//echo  $this->db->last_query();
		return $result;
	}
	function get_category_option_list(){
		$user=  $this->session->userdata('user'); 
		$wp_company_id = $user->company_id;
		$query = $this->db->query("SELECT cat.`id`, cat.`category_name` FROM contact_category cat WHERE wp_company_id='$wp_company_id' ORDER BY cat.`category_name`");
		$rows = array();
		foreach ($query->result() as $row){
                $rows[$row->id] = $row->category_name; 
		}
 
		return $rows;
	} 
	function get_category_details($category_id){
		$this->db->select('contact_category.*');
		$this->db->where($this->primary_key,$category_id);
		return $this->db->get($this->table_category)->row();
	}
     
}
?>