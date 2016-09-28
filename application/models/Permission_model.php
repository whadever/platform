<?php
class Permission_model extends CI_Model {
	
	private $primary_key    = 'pid';
	private $table_perm     ='permissions';
	private $table_role     ='users_roles';
	
	function __construct() {
		parent::__construct();
                $this->load->library('session');
	}
	
	function permission_insert_batch($data){            
            //  first delete existing permission and then insert new 
                $this->db->delete($this->table_perm, array('rid' => $data[0]['rid'])); 
                $this->db->insert_batch($this->table_perm,$data);
	}	
        
	function permission_load($rid){
		$this->db->where('rid',$rid);
		return $this->db->get($this->table_perm)->result();
	} 
        
	function permission_load_permission_only($rid){
		$this->db->where('rid',$rid);
		$perms = $this->db->get($this->table_perm)->result();
                //print_r($perms); 
                $rows = null;
                foreach ($perms as $perm) {
                    $rows[$perm->perm_url] = $perm->rid;
                }
                return $rows;
                
	}
        
	function permission_has_permission($uri){
		//global $user;
		//echo $uri; 
		$user = $this->session->userdata('user');
		if($user){
			$perms = $this->permission_load_permission_only($user->user_type); 
                        //print_r($perms); exit;
			list($controller,$op) = explode('/', $uri);                         
			$return = FALSE;
			if(is_array($perms)){
				if (array_key_exists("$controller/$op", $perms)) $return = TRUE;
			}                     
			return $return;
		}else{
			redirect('welcome');
		}
	}       
}