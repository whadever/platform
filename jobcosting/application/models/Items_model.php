<?php 
class Items_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_items = 'jobcosting_items';
	
	function __construct() {
		parent::__construct();
	}
	
	public function insert_item($data)
    {
        $this->db->insert($this->table_items, $data);
		return $this->db->insert_id();
    }
	
	public function get_items()
    {
    	$user = $this->session->userdata('user');
    	
    	$this->db->where('company_id', $user->company_id);
    	$this->db->order_by('id', 'ASC');
		return $this->db->get($this->table_items);  
    }
    
    public function get_items_id($id){
		$this->db->where('id', $id);
		return $this->db->get($this->table_items); 
	}
	
	public function item_update($id,$data){
		$this->db->where('id', $id);
		return $this->db->update($this->table_items,$data);  
	}

	public function delete_item($id){
		$this->db->where($this->primary_key,$id);
		$this->db->delete($this->table_items);
	}
}
?>