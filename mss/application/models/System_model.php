<?php
class System_model extends CI_Model
{
	private $table_system_update = 'system_update';

	public function __construct()
	{
		parent::__construct();
	}
	
	public function system_add($add)
	{
		$this->db->insert($this->table_system_update, $add);
		//return $this->db->insert_id();
	}
    
}