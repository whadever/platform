<?php 
class User_model extends CI_Model {
	
	private $primary_key = 'uid';
	private $table_user = 'users';
	private $table_user_role = 'users_roles';
	
	function __construct() {
		parent::__construct();
	}

	public function old_password_check($uid, $enter_old_pass) { 
		
		$this->db->select('password');
		$this->db->where('uid', $uid);
		$password = $this->db->get($this->table_user)->row();
		if($password->password == $enter_old_pass){
			print_r('1');	
		}else{
			print_r('0');
		}	
	}
	
	public function user_save($person){
		$this->db->insert($this->table_user,$person);
		return $this->db->insert_id();
	}
	
	public function user_role_save($person){
		$this->db->insert($this->table_user_role,$person);
		return $this->db->insert_id();
	}	
	
	public function user_list($get=NULL) { 
	   $uname = '';
	   if (isset($get) && !empty($get['uname'])){
           $uname = $get['uname'];
       }
	   $this->db->select("users.*, users_roles.rname");
	   if (!empty($uname)) $this->db->like('name', $uname);
	   $this->db->join('users_roles', 'users_roles.rid = users.rid', 'left');   
		return $this->db->get($this->table_user);
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
		$this->db->select('users.*,users_application.application_role_id');
		$this->db->join('users_application', 'users_application.user_id = users.uid', 'left'); 
		$this->db->where($this->primary_key, $uid);
		$this->db->where('users_application.application_id', '1');
		return $this->db->get($this->table_user)->row();
	} 

	public function user_app_role_load($uid){
		$this->db->where('user_id', $uid);
		$this->db->where('application_id', '1');
		return $this->db->get('users_application')->row();
	}
       
	public function user_details($uid){
                $this->db->select("users.*");
                $this->db->where($this->primary_key, $uid);
		
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
	
	function user_role_load(){
            $query = $this->db->query("SELECT ur.`rid`, ur.`rname` FROM users_roles ur ORDER BY ur.`rname`");
            $rows = array();
            foreach ($query->result() as $row){
                $rows[$row->rid] = $row->rname; 
            } 
            return $rows;
    }
	
	

	public function user_name_check($username)
	{
		$this->db->where('name',$username);
		$this->db->where('status','1');
		return $this->db->get($this->table_user)->row();

	}

	function developments_load(){
		$query = $this->db->query("SELECT dev.`id`, dev.`development_name` FROM development dev ORDER BY dev.`id` DESC");
 
        return $query;
    }
	
}
	
	