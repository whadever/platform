<?php 
class Company_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_project = 'project';
	private $table_company = 'contact_company';
	private $table_file= 'file';
	private $table_request= 'request';
	private $table_contact = 'contact_contact_list';
	
	function __construct() {
		parent::__construct();
	}
        
         function get_company_no(){
           $sql = "SELECT MAX(company_no) max_company_no FROM company";
            
            $query =  $this->db->query($sql)->row();                
            return $query->max_company_no;	 
        }
	
	public function company_save($person){

		$this->db->insert($this->table_company, $person);
		return $this->db->insert_id();
	}
	
	public function company_count_all() {
		return $this->db->count_all($this->table_company);
	}
	
	public function company_list_count() {
            $this->db->order_by('company_name', 'ASC');
            $this->db->limit(50);
            return $this->db->get($this->table_company);
	}
	function get_company(){
                $this->db->order_by('company_name', 'ASC');
                $result = $this->db->get($this->table_company);
                //echo  $this->db->last_query();
                return $result;
	}

	function get_company_list_all(){
		$this->db->select('contact_company.*');
		return $this->db->get($this->table_company);    
	}
        
	public function company_list_search_count($sort_by = 'id', $order_by = 'desc',$offset=0,$limit=10,$get = NULL) {
             
                     
            if($sort_by == 'company_status'){
                $this->db->order_by($sort_by.' '.$order_by.' , company_name asc');                
            }else{
                $this->db->order_by($sort_by, $order_by);
            }
            
            $this->db->limit($limit,$offset);
            
            $result = $this->db->get($this->table_company);
            //echo  $this->db->last_query();
            return $result;
            
	} 
	
	public function get_limited_company($start,$records_per_page)
	{

		$query = $this->db->query("SELECT * from contact_company LIMIT $start, $records_per_page");
		return $query->result();

	}
	
	function update($cid,$person){
		$this->db->where($this->primary_key,$cid);
		$this->db->update($this->table_company,$person);
	}
	function close_company($pid){
		$data = array(
               'company_status' => 2
            );

            $this->db->where('id', $pid);
            $this->db->update('company', $data); 		
	}
	
	function get_company_detail($company_id){
		$this->db->select('contact_company.*, file.filename');
		$this->db->join('file','file.fid=contact_company.company_image_id','left');
		$this->db->where($this->primary_key,$company_id);
		return $this->db->get($this->table_company);          
	}        

	function company_name(){
		$this->db->order_by('compname', "ASC");
		return $this->db->get($this->table_company);
	}
	function company_list_print(){
                $this->db->order_by('compname', "ASC");
		return $this->db->get($this->table_company);
	}
        
	function company_load($company_id){
		$this->db->where($this->primary_key,$company_id);
		return  $this->db->get($this->table_company)->row();
	}
       
	function get_company_list(){
		$user=  $this->session->userdata('user'); 
		$wp_company_id = $user->company_id;
		$query = $this->db->query("SELECT comp.`id`, comp.`company_name` FROM contact_company comp WHERE wp_company_id='$wp_company_id' ORDER BY comp.`company_name`");
		$rows = array();
		foreach ($query->result() as $row){
                $rows[$row->id] = $row->company_name; 
		}
 
		return $rows;
	} 
    function check_company_name_exists($company_name){
		$this->db->where('compname',$company_name);
		$this->db->from($this->table_company);
		return $this->db->count_all_results(); 
	}

    public function company_list_search_count_all($get=NULL) {
           $cname = '';
            $cond = array();
            if ($get){
                $cname = trim($get['cname']);
                if (!empty($cname)) $cond[] = 'compname LIKE "%'. $cname . '%"'; 
            }
            $sql = "SELECT COUNT(compname) total_rows FROM company_profile";
            if (!empty($cond)) $sql .= ' WHERE ' . implode(' AND ', $cond);           
            
            $query =  $this->db->query($sql)->row();                
            return $query->total_rows;			
	}
	
    public function file_insert($file){
            $this->db->insert($this->table_file,$file);
            return $this->db->insert_id();            
    }
	
	function get_company_project($cid){
		$this->db->select('`id`, `project_no`, `project_name`, `project_status`'); 
                $this->db->where('company_id', $cid);                 
		return $this->db->get($this->table_project);
	}
	function get_company_open_project($cid){
		$this->db->select('`id`, `project_no`, `project_name`, `project_status`'); 
		$this->db->where('company_id', $cid); 
 		$this->db->where('project_status', 1); 
		return $this->db->get($this->table_project);
	}
	function get_company_close_project($cid){
		$this->db->select('`id`, `project_no`, `project_name`, `project_status`'); 
		$this->db->where('company_id', $cid); 
		$this->db->where('project_status', 2); 
		return $this->db->get($this->table_project);
	}

	function get_contact_list($company_id){
		$this->db->select('contact_contact_list.*, file.filename');
		$this->db->join('file', 'file.fid = contact_contact_list.contact_image_id', 'left');
		$this->db->where('company_id',$company_id);
		return  $this->db->get($this->table_contact)->result();
	}
        
        
}
?>