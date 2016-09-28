<?php 
class User_model extends CI_Model {
	
	private $primary_key = 'uid';
	private $table_user = 'users';
	private $table_users_application = 'users_application';
    private $table_application_roles = 'application_roles';
    private $table_application = 'application';
    private $table_wp_company = 'wp_company';
    private $table_wp_plans = 'wp_plans';
        	
    function __construct() {
		parent::__construct();
    }
    
    public function user_login_default() { 
    
    	$subdomain = str_replace(".wclp.co.nz", "", $_SERVER["HTTP_HOST"]);
    	//echo $subdomain;
    	if($subdomain =='wclp.co.nz' || $subdomain =='' || $subdomain =='www')
    	{
    		$query_user = "select wp_company.*, wp_file.filename from wp_company left join wp_file on wp_company.file_id=wp_file.id where url='".$_SERVER["HTTP_HOST"]."'";
    	
    	}
    	else
    	{
    		$query_user = "select wp_company.*, wp_file.filename from wp_company left join wp_file on wp_company.file_id=wp_file.id where url='".$_SERVER["HTTP_HOST"]."'";
    	}
    	$query = $this->db->query($query_user);
    	return $query->row();
    	
        //$this->db->where('username',$username);
        //$this->db->where('password',md5($password));
		//$this->db->where('status','1');		
		//echo '<pre>'; print_r($query->num_rows()); die();
        //return $this->db->get($this->table_user)->row();      
    }
    
    public function client_background($file_id) {
    	$this->db->select("wp_file.filename"); 
        $this->db->where('wp_file.id',$file_id);
        return $this->db->get('wp_file')->row();      
    }
    
    public function user_login($username,$password) { 
    
    	$subdomain = str_replace(".wclp.co.nz", "", $_SERVER["HTTP_HOST"]);
    	//echo $subdomain;
    	if($subdomain =='wclp.co.nz' || $subdomain =='' || $subdomain =='www')
    	{
    		$query_user = "select * from users where username='".$username."' and password ='".md5($password)."' and status=1 and role=3";
    	
    	}
    	else
    	{
    		$query_user = "select * from users inner join wp_company on users.company_id = wp_company.id where username='".$username."' and password ='".md5($password)."' and status=1 and url='".$_SERVER["HTTP_HOST"]."'";
    	}
    	$query = $this->db->query($query_user);
    	return $query->row();
    	
        //$this->db->where('username',$username);
        //$this->db->where('password',md5($password));
		//$this->db->where('status','1');		
		//echo '<pre>'; print_r($query->num_rows()); die();
        //return $this->db->get($this->table_user)->row();      
    }
    public function forgot_password($username,$email) { 
    
    	$subdomain = str_replace(".wclp.co.nz", "", $_SERVER["HTTP_HOST"]);
    	//echo $subdomain;
    	if($subdomain =='wclp.co.nz' || $subdomain =='' || $subdomain =='www')
    	{
    		$query_user = "select * from users where username='".$username."' and email ='".$email."' and status=1 and role=3";
    	
    	}
    	else
    	{
    		$query_user = "select * from users inner join wp_company on users.company_id = wp_company.id where username='".$username."' and email ='".$email."' and status=1 and url='".$_SERVER["HTTP_HOST"]."'";
    	}
    	$query = $this->db->query($query_user);
    	//print_r($query->row()); die();
    	return $query->row();     
    }
    public function user_reset_link($username,$email) { 
    
    	$subdomain = str_replace(".wclp.co.nz", "", $_SERVER["HTTP_HOST"]);
    	//echo $subdomain;
    	if($subdomain =='wclp.co.nz' || $subdomain =='' || $subdomain =='www')
    	{
    		$query_user = "select * from users where username='".$username."' and email ='".$email."' and status=1 and role=3";
    	
    	}
    	else
    	{
    		$query_user = "select * from users inner join wp_company on users.company_id = wp_company.id where username='".$username."' and email ='".$email."' and status=1 and url='".$_SERVER["HTTP_HOST"]."'";
    	}
    	$query = $this->db->query($query_user);
    	$reset_user = $query->row(); 
    	$query_reset_user = "update users set reset_password = '".md5(time())."' where uid=".$reset_user->uid;
    	$this->db->query($query_reset_user);
    	return  $reset_user;   
    }
    public function reset_password($email,$password, $reset_password) { 
    
    	$subdomain = str_replace(".wclp.co.nz", "", $_SERVER["HTTP_HOST"]);
    	//echo $subdomain;
    	if($subdomain =='wclp.co.nz' || $subdomain =='' || $subdomain =='www')
    	{
    		$query_user = "select * from users where reset_password ='".$reset_password."' and status=1 and role=3";
    	
    	}
    	else
    	{
    		$query_user = "select * from users inner join wp_company on users.company_id = wp_company.id where reset_password ='".$reset_password."' and status=1 and url='".$_SERVER["HTTP_HOST"]."'";
    	}
    	$query = $this->db->query($query_user);
    	$reset_user = $query->row(); 
    	$query_reset_user = "update users set password = '".md5($password)."', email='".$email."', reset_password='' where uid='".$reset_user->uid."'";
    	$this->db->query($query_reset_user);
    	return  $reset_user;   
    }
   
    public function user_save($person){
		$this->db->insert($this->table_user,$person);
		return $this->db->insert_id();
    }
        
    public function user_application_save($data){
		$this->db->insert_batch($this->table_users_application, $data);
		//return $this->db->insert_id();		
    }
	
    public function user_role_save($person){
		$this->db->insert($this->table_user_role,$person);
		return $this->db->insert_id();
    }
        
    public function application_user_list($uid) { 
	   $uname = '';
	   if (isset($get) && !empty($get['uname'])){
                $uname = $get['uname'];
            }
	   $this->db->select("users.username, users.email, application.application_name, application_roles.application_role_name");
	   if (!empty($uname)) $this->db->like('name', $uname);
	   $this->db->join('users', 'users.uid = users_application.user_id', 'left'); 
       $this->db->join('application', 'application.id = users_application.application_id', 'left');
       $this->db->join('application_roles', 'application_roles.id = users_application.application_role_id', 'left');
       $this->db->where('user_id',$uid);
       $this->db->where('users_application.application_id !=', 0);
       
       return $this->db->get($this->table_users_application);
    }
        
    public function get_application_checked($uid, $appid){
        $this->db->select("application_id");
		$this->db->where('user_id', $uid);
        $this->db->where('application_id', $appid);
		return $this->db->get($this->table_users_application)->row();
    }
    public function get_application_role($uid, $appid){
        $this->db->select("application_role_id");
		$this->db->where('user_id', $uid);
        $this->db->where('application_id', $appid);
		return $this->db->get($this->table_users_application)->row();
    }
    
    public function user_access_list() { 
    	$this->db->select("users.*"); 
    	$this->db->join('users_application', 'users_application.user_id = users.uid', 'left');
   		$app_ids = explode(" ",$get['app_id']);
   		for($i = 0; $i < count($app_ids); $i++){
			$this->db->where('application_id', $app_ids[$i]);
		}		
        return $this->db->get($this->table_user);
    }
    
    public function user_list($uid,$userrole,$company_id,$get) { 
		if(!empty($get)){
			$username = $get['username'];
			$system = $get['system'];

			$sesData['name']=$username;
			$sesData['system']=$system;
			$this->session->set_userdata($sesData);
		}
        if($userrole==1){
            $this->db->select("users.*"); 
			if (!empty($system)){
			    $this->db->join('users_application', 'users_application.user_id = users.uid', 'left'); 
		        $this->db->join('application', 'application.id = users_application.application_id', 'left');
				$this->db->where('users_application.application_id', $system);
			}
			if (!empty($username)) $this->db->like('username', $username);
			$this->db->where('users.company_id', $company_id);
            return $this->db->get($this->table_user);
        }else{
            //$user_app = $this->user_app_permission($uid)->result();
            $user_app = $this->show_user_list_for_admin($uid)->result();        
            
            foreach($user_app as $app){
                $appid[] = $app->application_id;
                //$approleid[]=$app->application_role_id;
            }
            if(!empty($appid)){
                $this->db->select("users.*"); 
                $this->db->join('users_application', 'users_application.user_id = users.uid', 'left');            
                $this->db->where_in('application_id', $appid);
                //$this->db->where_not_in('application_id', array('2', '3', '5'));
                //$this->db->where_in('application_role_id', $approleid);
                $this->db->where('role !=', 1);
				if (!empty($system)){ 
			        $this->db->join('application', 'application.id = users_application.application_id', 'left');
					$this->db->where('users_application.application_id', $system);
				}
				if (!empty($username)) $this->db->like('username', $username);
				$this->db->where('users.company_id', $company_id);
                $this->db->group_by("uid");
                $res = $this->db->get($this->table_user);
                //echo $this->db->last_query();
                return $res;
            }else{
                $object= new stdClass();
                return $object;
                
            }
            
        }
        
       
    }
    
    public function users_total($company_id, $plan_id) { 
		
        $this->db->select("count(*) as users_total");       
		$this->db->where('company_id',$company_id);
		$this->db->where('role != ', 1);       
		$users_count = $this->db->get($this->table_user)->result();
		$users_plan->users_total = $users_count[0]->users_total;
		
		$this->db->select("max_users");       
		$this->db->where('id',$plan_id);     
		$plans = $this->db->get($this->table_wp_plans)->result();
		$users_plan->users_max = $plans[0]->max_users;
		
		return $users_plan;
       
    }
    
    public function show_user_list_for_admin($uid) { 
	   
            $this->db->select("application_id");       
            $this->db->where('user_id',$uid);
            $this->db->where('application_role_id', 1);       
            return $this->db->get($this->table_users_application);
    }

    
    public function user_app_permission($uid) { 
    	$this->db->select("application.application_name,application.id"); 
    	$this->db->join('application', 'application.id = users_application.application_id', 'left');
		$this->db->where('user_id', $uid);		
        return $this->db->get($this->table_users_application);
    }

	public function user_app_role_permission_one($uid) {  
		$this->db->where('user_id', $uid);		
        return $this->db->get($this->table_users_application);
    }

	public function user_app_role_permission($application_id,$application_role_id) { 
		$this->db->where('application_id', $application_id);	
		$this->db->where('application_role_id', $application_role_id);		
        return $this->db->get('application_roles');
    }
    
    
	
    public function user_update($uid, $person){
        $this->db->where($this->primary_key, $uid);
		return $this->db->update($this->table_user,$person);
    }
    
    public function upgrade_plan($company_id, $plan_id){
    	//print_r($company_id); print_r($plan_id); die();
    	$query_upgrade = "update wp_company set plan_id = '".$plan_id."' where id='".$company_id."'"; //die();
    	return $this->db->query($query_upgrade);
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
       
    public function user_details($uid){
        $this->db->select("users.*");
        $this->db->where('users.'.$this->primary_key, $uid);
        //$this->db->join('users_roles', 'users_roles.rid = users.role', 'left');  		
		return $this->db->get($this->table_user)->row();
    }
    
    public function user_role_load($app_id){            
		$this->db->select("application_role_name, application_role_id");
		$this->db->where('application_id', $app_id);

		$query = $this->db->get('application_roles');

		$rows = array();
		foreach ($query->result() as $row){
		$rows[$row->application_role_id] = $row->application_role_name; 
		} 
		return $rows;
    }
    
    public function get_system_list(){
        $user=  $this->session->userdata('user');       
        $uid = $user->uid;
        $userrole=$user->role; 
		$company_id=$user->company_id;       
        
        $this->db->select("application.id, application_name");
		$this->db->join('users_application', 'users_application.application_id = application.id', 'left');
		$this->db->where('company_id', $company_id);
		$this->db->where('user_id', $uid);
		$this->db->order_by('application.id', 'ASC');
		$query = $this->db->get('application');        
		return $query->result();               
    }

	public function get_company_applications(){
		$user=  $this->session->userdata('user');
		$uid = $user->uid;
		$userrole=$user->role;
		$company_id=$user->company_id;

		$this->db->select("application.id, application_name");
		$this->db->join('wp_company_applications', 'wp_company_applications.application_id = application.id', 'left');
		$this->db->where('company_id', $company_id);
		$this->db->order_by('application.id', 'ASC');
		$query = $this->db->get('application');
		return $query->result();
	}


     public function get_system_list1(){
        $user=  $this->session->userdata('user');       
        $uid = $user->uid;
        $userrole=$user->role;
        if($userrole==1){
                $this->db->select("id, application_name");		
				$query = $this->db->get('application');
                return $query->result(); 
        }else{
            
            $admin_users= $this->user_admin_application_list($uid);           
            $app_id=array();
            foreach($admin_users as $admin){
                $app_id[] = $admin->id;
            }
            if(!empty($app_id)){
            $this->db->select("id, application_name");
                    $this->db->where_in('id', $app_id);
                    $query = $this->db->get('application');
                    return $query->result(); 
            }else{ echo 'You have no access.';}
       
        }
        
    }    
    public function user_admin_application_list($uid) { 
        
	  
       $this->db->select("application.id, application.application_name");
	  
       $this->db->join('users', 'users.uid = users_application.user_id', 'left'); 
       $this->db->join('application', 'application.id = users_application.application_id', 'left');
       $this->db->join('application_roles', 'application_roles.id = users_application.application_role_id', 'left');
       $this->db->where('user_id',$uid);
       $this->db->where('users_application.application_id !=', 0);
       $this->db->where('users_application.application_role_id ', 1);
       
       $result = $this->db->get($this->table_users_application)->result();
       //echo $this->db->last_query();
       return $result;
    }
        
    public function user_app_role_delete($uid){
        $this->db->where('user_id', $uid);
        return $this->db->delete($this->table_users_application);
    }
    

	public function developments_load(){  
		$user = $this->session->userdata('user');          
        $wp_company_id =$user->company_id; 
		
		$this->db->where('wp_company_id', $wp_company_id);         
		return $this->db->get('development');
    }

	public function cms_group(){            
		return $this->cms->get('groups');
    }

	public function hds_dev_permission($user_id){  
		$this->db->select("hds_dev_permission, application_role_id"); 
		$this->db->where('user_id', $user_id); 
		$this->db->where('application_id', '1'); 
		$this->db->where('application_role_id', '3');        
		return $this->db->get($this->table_users_application);
    }

	public function cms_group_id($user_id){  
		$this->db->select("cms_group_id, application_role_id"); 
		$this->db->where('user_id', $user_id); 
		$this->db->where('application_id', '6'); 
		$this->db->where_in('application_role_id', array('2','3'));        
		return $this->db->get($this->table_users_application);
    }

	public function user_email_check($get) { 
		$email = $get['email'];
		$company_id = $get['company_id'];
		$this->db->select('email');
		$this->db->where('company_id', $company_id);
		$this->db->where('role != ','1');
		$this->db->where('email', $email);
		$email_check = $this->db->get($this->table_user)->row();
		if($email_check)
		{
			$email_check1 = $email_check->email;
		}else{
			$email_check1 = '';
		}
		
		if($email_check1==$email){
			echo '1';	
		}else{
			echo '0';
		}	
	}

	public function username_check($get) { 
		$username = $get['username'];
		$company_id = $get['company_id'];
		$this->db->select('username');
		$this->db->where('company_id', $company_id);
		$this->db->where('username', $username);
		$username_check = $this->db->get($this->table_user)->row();
		if($username_check)
		{
			$username_check1 = $username_check->username;
		}else{
			$username_check1 = '';
		}
		
		if($username_check1==$username){
			echo '1';	
		}else{
			echo '0';
		}	
	}

	function get_system_access_list(){
		$user=  $this->session->userdata('user');       
        $uid = $user->uid;
		$role = $user->role;

		$this->db->select("application.id, application.application_name");
		$this->db->join('users_application', 'users_application.application_id = application.id', 'left');       	
       	$this->db->where('user_id',$uid);
		if($role==2){	         		
			$this->db->where('application_role_id !=','0');
		}
		$query = $this->db->get('application')->result();
            $rows = array();
            foreach ($query as $row){
                $rows[$row->id] = $row->application_name; 
            } 
            return $rows;
    }

	function insert_job_id($job_id,$user_id)
	{
		$this->db->where('user_id',$user_id);
		$this->db->delete('construction_user_job');
		
		$job_data = array(
					'user_id' => $user_id,
					'job_id' => $job_id
					);
		$this->db->insert('construction_user_job',$job_data);

	}
	
		
}	