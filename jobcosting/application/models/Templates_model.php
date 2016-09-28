<?php 
class Templates_model extends CI_Model {

	private $primary_key = 'id';
	private $table_items = 'jobcosting_items';
	private $table_templates = 'jobcosting_templates';
	private $table_templates_items = 'jobcosting_templates_items';
	private $table_templates_category = 'jobcosting_templates_category';
	
	function __construct() {
		parent::__construct();
	}
	
	public function get_template_category($custom_template_id)
    {
    	$this->db->select('jobcosting_templates_category.*');
    	$this->db->where('template_id', $custom_template_id);
    	$this->db->order_by('ordering', 'asc');
		return $this->db->get($this->table_templates_category);  
    }
	
	public function get_items()
    {
    	$user = $this->session->userdata('user');
    	
    	$this->db->where('company_id', $user->company_id);
    	$this->db->order_by('id', 'ASC');
		return $this->db->get($this->table_items);  
    }
    
    public function insert_template_category($data)
    {
        $this->db->insert($this->table_templates_category, $data);
		return $this->db->insert_id();
    }
    
    public function insert_template($data)
    {
        $this->db->insert($this->table_templates, $data);
		return $this->db->insert_id();
    }
    
    public function insert_template_item($data)
    {
        $this->db->insert($this->table_templates_items, $data);
		return $this->db->insert_id();
    }
	
	public function get_template() {
		$user = $this->session->userdata('user');
    	
    	$this->db->where('company_id', $user->company_id);
		return $this->db->get($this->table_templates);
	} 
	
	public function get_template_id($tem_id) {
		$this->db->where('id',$tem_id);
		return $this->db->get($this->table_templates);
	} 
	
	public function update_template($tem_id,$data) {
		$this->db->where('id',$tem_id);
		return $this->db->update($this->table_templates, $data);
	} 
	
	public function get_template_items($tem_id)
    {
    	$user = $this->session->userdata('user');
    	
    	$this->db->select("jobcosting_items.*");
		$this->db->join('jobcosting_templates_items', 'jobcosting_templates_items.item_id = jobcosting_items.id', 'left');  

    	$this->db->where('jobcosting_templates_items.template_id', $tem_id);
    	//$this->db->order_by('id', 'ASC');
		return $this->db->get($this->table_items);  
    }
    
    public function delete_template_item($tem_id)
	{
		$this->db->where('template_id',$tem_id);
		$this->db->delete($this->table_templates_items);
	}
	
}
?>