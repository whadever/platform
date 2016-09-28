<?php 
class Template_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_user = 'users';
	private $table_template = 'template';
	private $table_template_product = 'template_product';
	
	function __construct() {
		parent::__construct();
	}
	
	
	
	
	public function get_template_list() { 
	    $user = $this->session->userdata('user');
		$wp_company_id = $user->company_id;

	    $this->db->select("template.*");
		$this->db->where('wp_company_id', $wp_company_id);	   
        return $this->db->get($this->table_template);
	}
        
        public function template_save($template_data){
		$this->db->insert($this->table_template,$template_data);
		return $this->db->insert_id();
	}
         public function template_product_add($template_product_add){
		$this->db->insert($this->table_template_product, $template_product_add);
		//return $this->db->insert_id();
	}
        
        
        
        public function template_load($id){
		$this->db->where($this->primary_key, $id);
		return $this->db->get($this->table_template)->row();
	}
        
        
	public function template_update($id, $template_update){
            $this->db->where($this->primary_key, $id);
            return $this->db->update($this->table_template, $template_update);
	}
        
        public function template_product_delete($id){
            $this->db->where('template_id', $id);
            return $this->db->delete($this->table_template_product);
	}
        
        
    public function template_delete($id){
        $this->db->where($this->primary_key, $id);
		$this->db->delete($this->table_template);
		
		$this->db->where('template_id', $id);
		$this->db->delete($this->table_template_product);
	}
        
        
        
        
        
        
        
        public function user_save($person){
		$this->db->insert($this->table_user,$person);
		return $this->db->insert_id();
	}
	public function user_update($uid, $person){
        $this->db->where($this->primary_key, $uid);
		return $this->db->update($this->table_user,$person);
	}
	
	public function user_delete($uid){
        $this->db->where($this->primary_key, $uid);
		return $this->db->delete($this->table_user);
	}
	
	public function user_uid($uid){
		$this->db->where($this->primary_key, $uid);
		return $this->db->get($this->table_user);
	}

        
	public function user_load($uid){
		$this->db->where($this->primary_key, $uid);
		return $this->db->get($this->table_user)->row();
	} 
       
	public function user_details($uid)
	{
                $this->db->select("users.*, users_roles.rname");
                $this->db->where('users.'.$this->primary_key, $uid);
                $this->db->join('users_roles', 'users_roles.rid = users.rid', 'left');  
		
		return $this->db->get($this->table_user)->row();
	}

	public function user_role_rid($rid){
		$this->db->where('rid', $rid);
		return $this->db->get($this->table_user_role);
	}
	
	public function user_role_update($rid, $role){
        $this->db->where('rid', $rid);
		return $this->db->update($this->table_user_role,$role);
	}
	
	public function user_role_delete($rid){
        $this->db->where('rid', $rid);
		return $this->db->delete($this->table_user_role);
	}
	
	public function user_role_list() {    
        return $this->db->get($this->table_user_role);
	}
	
	public function user_group_list() {    
        return $this->db->get($this->table_user_group);
	}
	
	function user_role_load(){
            $query = $this->db->query("SELECT ur.`rid`, ur.`rname` FROM users_roles ur ORDER BY ur.`rname`");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->rid] = $row->rname; 
            } 
            return $rows;
    }
	
	
	function user_group_load()
	{
            $query = $this->db->query("SELECT g.`id`, g.`group_name` FROM groups AS g WHERE status = 1 ORDER BY g.`id`");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->id] = $row->group_name; 
            } 
            return $rows;
    }
	
	public function user_login($username,$password) {
        $this->db->where('name',$username);
        $this->db->where('pass',md5($password));
		$this->db->where('status','1');		
        return $this->db->get($this->table_user)->row();      
    }

	public function user_name_check($username)
	{
		$this->db->where('name',$username);
		$this->db->where('status','1');
		return $this->db->get($this->table_user)->row();

	}
	
	public function get_group_read_permissions($group_id, $permission_type)
	{
		$this->db->where('group_id',$group_id);
		$this->db->where('read_type',$permission_type);
		$this->db->where('published','1');	
		return $this->db->get($this->table_group_permissions);	
	}
	
	public function get_group_display_permissions($group_id, $permission_type)
	{
		$this->db->where('group_id',$group_id);
		$this->db->where('display_type',$permission_type);
		$this->db->where('published','1');	
		return $this->db->get($this->table_group_permissions);	
	}

	public function get_permission_name($permission_id)
	{
		$this->db->select('permission_name');
		$this->db->where('id',$permission_id);
		return $this->db->get($this->table_master_group_permissions)->row();
	}

	public function update_user_permission($permission_id,$permission_type,$permission_value)
	{
		if($permission_type == 1)
		{
			$permission_data = array('read_type' => $permission_value);
		}
		else
		{
			$permission_data = array('display_type' => $permission_value);
		}
		$this->db->where('id', $permission_id);
		return $this->db->update($this->table_group_permissions,$permission_data);
	}
	public function delete_permission_group($group_id)
	{
		$this->db->where('id', $group_id);
		return $this->db->delete($this->table_user_group);
	}

	public function add_permission_group($group_info)
	{
		$this->db->insert($this->table_user_group,$group_info);
		$group_id = $this->db->insert_id();
		$this->db->select('id, permission_name');
		$this->db->where('published',1);
		$master_data = $this->db->get('master_group_permissions')->result();
		
		
		
		for($i = 0; $i< count($master_data); $i++)
		{
			$gp_data = array(
				'group_id'=>$group_id,
				'permission_id' => $master_data[$i]->id,
				'read_type' => 1,
				'display_type' => 1,
				'published' => 1
			);
			
			$this->db->insert($this->table_group_permissions,$gp_data);
		}
		
		
		return $gp_id = $this->db->insert_id();
		
	}

	function search_user($username)
	{

		$this->db->select('uid');
		$this->db->where('name',$username);
		$uid = $this->db->get($this->table_user)->row();
		if($uid)
		{
			return $uid->uid;
		}	

	}

	function search_email($user_email)
	{

		$this->db->select('uid');
		$this->db->where('email',$user_email);
		$uid = $this->db->get($this->table_user)->row();
		if($uid)
		{
			return $uid->uid;
		}	

	}

	function search_edit_user($username, $user_id)
	{

		$this->db->select('uid');
		$this->db->where('name',$username);
		$this->db->where('uid !=',$user_id);
		$uid = $this->db->get($this->table_user)->row();
		if($uid)
		{
			return $uid->uid;
		}	

	}
	
	
}
	
	