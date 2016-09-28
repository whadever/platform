<?php 
class User_model extends CI_Model {
	
	private $primary_key = 'uid';
	private $table_user = 'users';
	
	function __construct() {
		parent::__construct();
	}
	
	public function user_save($person){
		$this->db->insert($this->table_user,$person);
		return $this->db->insert_id();
	}
	
	public function user_list() { 
	  
		return $this->db->get($this->table_user);
	}
	
	public function user_update($uid, $person){
        $this->ums->where($this->primary_key, $uid);
		return $this->ums->update($this->table_user,$person);
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
		$this->ums->where($this->primary_key, $uid);
		return $this->ums->get($this->table_user)->row();
	} 
       
	public function user_login($username,$password) {
        $this->db->where('name',$username);
        $this->db->where('pass',md5($password));
		$this->db->where('status','1');		
        return $this->db->get($this->table_user)->row();      
    }
	
	public function user_email_check($get) { 
		$email = $get['email'];
		$this->db->select('email');
		$this->db->where('email', $email);
		$email_check = $this->db->get($this->table_user)->row();
		if($email_check)
		{
			$email_check1 = $email_check->email;
		}else{
			$email_check1 = '';
		}
		
		if($email_check1==$email)
		{
			print_r('1');	
		}
		else
		{
			print_r('0');
		}	
	}
	
	public function user_name_check($get) { 
		$username = $get['username'];
		$this->db->select('name');
		$this->db->where('name', $username);
		$name = $this->db->get($this->table_user)->row();
		if($name)
		{
			$uname = $name->name;
		}else{
			$uname = '';
		}
		
		if($uname==$username)
		{
			print_r('1');	
		}
		else
		{
			print_r('0');
		}	
	}
	
	public function update_email($uid, $user_email_data)
	{
		$this->ums->where('uid',$uid);
		return $this->ums->update($this->table_user,$user_email_data);
	}
	public function update_password($uid, $user_password_data)
	{
		$this->ums->where('uid',$uid);
		return $this->ums->update($this->table_user,$user_password_data);
	}
	
	public function user_details($uid)
	{
        $this->ums->select("users.*");	
		$this->ums->where('uid',$uid);	
		return $this->ums->get($this->table_user)->row();
	}

}
	
	