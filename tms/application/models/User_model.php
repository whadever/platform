<?php 
class User_model extends CI_Model {
	
	private $primary_key = 'uid';
	private $table_user = 'users';
	private $table_user_role = 'users_roles';
	
	function __construct() {
		parent::__construct();
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
	   if (!empty($uname)) $this->db->like('username', $uname);
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
		$this->db->where($this->primary_key, $uid);
		return $this->db->get($this->table_user)->row();
	} 

	public function user_new_load($uid){
		$this->db->select("users.*, users_application.application_id, users_application.application_role_id as rid");
		$this->db->where($this->primary_key, $uid);
		$this->db->where('users_application.application_id', '3');
		$this->db->join('users_application', 'users_application.user_id = users.uid', 'left'); 
		return $this->db->get($this->table_user)->row();
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
	
	public function user_login($username,$password) {
            $this->db->select("users.*, users_application.application_role_id as rid");
            $this->db->join('users_application', 'users_application.user_id = users.uid', 'left'); 
        $this->db->where('username',$username);
        $this->db->where('password',md5($password));
		$this->db->where('status','1');
                $this->db->where('users_application.application_id', '4');
        return $this->db->get($this->table_user)->row();      
    }
    public function user_new_load2($uid){
		$this->db->select("users.*, users_application.application_role_id as rid");
		$this->db->where($this->primary_key, $uid);
		$this->db->where('users_application.application_id', '4');
		$this->db->join('users_application', 'users_application.user_id = users.uid', 'left'); 
		return $this->db->get($this->table_user)->row();
	} 
        
	
}
	
	