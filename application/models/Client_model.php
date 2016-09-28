<?php
class Client_model extends CI_Model{
	private $primary_key = 'id';
	private $table_users = 'users';
	private $table_company = 'wp_company';
	private $table_file = 'wp_file';
	private $table_users_application = 'users_application';
	private $table_company_application = 'wp_company_applications';
	private $table_tms_company = 'company';
	
	function __construct(){
		parent::__construct();	
	}
	public function get_client_info(){
		$this->db->where('role', '1');
		//$this->db->join('wp_company', 'wp_company.id = users.company_id', 'left');
		$this->db->join('wp_company', 'wp_company.id = users.company_id');
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
		$this->db->order_by('uid', 'DESC');
		return $this->db->get($this->table_users);
	} 
	
	public function file_add($add){
		$this->db->insert($this->table_file,$add);
		return $this->db->insert_id();
	}

	public function company_add($add){
		$this->db->insert($this->table_company,$add);
		return $this->db->insert_id();
	}

	public function client_add($add){
		$this->db->insert($this->table_users,$add);
		return $this->db->insert_id();
	}
	
	public function application_add($add){
		//$this->db->insert($this->table_users_application,$add);
		$this->db->insert($this->table_company_application,$add);
		return $this->db->insert_id();
	}
	public function tms_company_save($person){

		$this->db->insert($this->table_tms_company, $person);
		return $this->db->insert_id();
	}

	public function check_username($get) { 
		$username = $get['username'];
		$this->db->select('username');
		$this->db->where('username', $username);
		$user = $this->db->get($this->table_users)->row();
		if($user)
		{
			$username1 = $user->username;
		}else{
			$username1 = '';
		}
		if($username1 == $username){
			echo '0';	
		}else{
			echo '1';
		}	
	}

	public function client_delete($company_id){
        
        //die($company_id);
        
        $this->db->where($this->primary_key, $company_id);
		$this->db->delete($this->table_company);
		
		$this->db->where('company_id', $company_id);
		$this->db->delete($this->table_users);

		$this->db->where('company_id', $company_id);
		$this->db->delete($this->table_users_application);
	}

	public function client_uid($uid){
		$this->db->select('wp_company.*, users.*, a.filename as logo, b.filename as bg');
		$this->db->where('uid', $uid);
		$this->db->join('wp_company', 'wp_company.id = users.company_id', 'left');
		$this->db->join('wp_file a', 'a.id = wp_company.file_id', 'left');
		$this->db->join('wp_file b', 'b.id = wp_company.backgroundWclp_id', 'left');
		return $this->db->get($this->table_users);
	}

	public function client_application_delete($uid){
        $this->db->where('user_id', $uid);
		$this->db->delete($this->table_users_application);
	}

	public function company_application_delete($company_id){
        $this->db->where('company_id', $company_id);
		$this->db->delete($this->table_company_application);
	}

	public function company_update($company_id,$company_update){
        $this->db->where($this->primary_key, $company_id);
		return $this->db->update($this->table_company,$company_update);
	}

	public function client_update($uid,$client_update){
        $this->db->where('uid', $uid);
		return $this->db->update($this->table_users,$client_update);
	}

	public function client_details($uid){
        $this->db->select("users.*,wp_company.*,wp_file.*");
        $this->db->join('wp_company', 'wp_company.id = users.company_id', 'left'); 
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
	 	$this->db->where('users.uid', $uid);	
		return $this->db->get($this->table_users);
    }

	public function application_client_list($uid,$company_id) { 
	   $this->db->select("application.application_name");
       $this->db->join('application', 'application.id = users_application.application_id', 'left');    
       $this->db->where('user_id',$uid);  
	   $this->db->where('company_id',$company_id);     
       return $this->db->get($this->table_users_application);
    }

}
