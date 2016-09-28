<?php 
class Job_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_job = 'construction_development';
	private $table_file= 'construction_file';
	private $table_construction_check_list= 'construction_check_list';
	
	function __construct() {
		parent::__construct();
	}
        
	public function job_save($job_data){

		$this->db->insert($this->table_job, $job_data);
		return $this->db->insert_id();
	}
	public function job_update($id,$job_data){

		$this->db->where($this->primary_key,$id);
		$this->db->update($this->table_job,$job_data);
	}
	
	public function job_count_all() {
		return $this->db->count_all($this->table_job);
	}
	function get_latest_job(){
		$this->db->order_by('id', 'DESC');
		$result = $this->db->get($this->table_job)->row();
		return $result;
	}
	function get_job($id){
		$this->db->where('id',$id);
		$result = $this->db->get($this->table_job)->row();
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
		$query = $this->db->query("SELECT comp.`id`, comp.`company_name` FROM contact_company comp ORDER BY comp.`company_name`");
		$rows = array();
		foreach ($query->result() as $row){
                $rows[$row->id] = $row->company_name; 
		}
 
		return $rows;
	} 
    
    public function file_insert($file){
            $this->db->insert($this->table_file,$file);
            return $this->db->insert_id();            
    }

	public function file_delete($fid){
        $this->db->where('fid',$fid);
		return  $this->db->delete($this->table_file);         
    }

	public function get_job_list(){
		/*$query = "select d.id id,  d.development_name job_name, d.development_city city, min(t.development_task_status) status
				  from construction_development d LEFT JOIN construction_development_task t
				  on d.id = t.development_id
				  GROUP BY d.id";*/
		$user=  $this->session->userdata('user'); 
		$wp_company_id =$user->company_id;

		/*task #4055*/
		$query = "select d.id id, d.job_color job_color, d.development_name job_name, job_number, development_location location, d.investor, d.roofing_contractor, d.builder, d.development_city city, status, is_unit, parent_unit, max(planned_finished_date) finish_date, IF(MAX(planned_finished_date),MAX(planned_finished_date),'0000-00-00') finish_date2
				  from construction_development d LEFT JOIN construction_development_phase p ON d.id = p.development_id
				  where wp_company_id='$wp_company_id'
				  GROUP BY d.id
				  ORDER BY finish_date2 DESC, development_name
				  ";
		return $this->db->query($query)->result_array();

	}
	public function get_list_data($job_id){
		$query = "SELECT construction_check_list_status.*, construction_check_stage.stage_name, construction_check_list.task_name, construction_check_list.file_id, construction_file.filename
		 		  FROM construction_check_list_status, construction_check_stage, construction_check_list
				  LEFT JOIN construction_file on construction_check_list.file_id = construction_file.fid
		 		  WHERE construction_check_stage.id = construction_check_list_status.stage_id
		 		  AND construction_check_list.id = construction_check_list_status.check_list_id
		 		  AND construction_check_list_status.job_id = {$job_id}";
		/*$query = "select stage_id, stage_name, construction_check_list.id task_id, construction_check_list.status task_status, task_name, note
				  from construction_check_list_status JOIN construction_check_stage
				  on construction_check_list_status.stage_id = construction_check_stage.id
				  where 1"; */
		return $this->db->query($query)->result_array();
	}
	public function get_check_list_status($check_list_id, $stage_id, $job_id)
	{
		$check_query = "SELECT status FROM construction_check_list_status WHERE stage_id = $stage_id AND job_id = $job_id AND check_list_id = ".$check_list_id;
		$check = $this->db->query($check_query)->row();
		return $check->status;	
	}
	public function get_job_task_list($job_id){
		return $task_list = $this->db->query("SELECT *FROM construction_development_task WHERE development_id = '$job_id'")->result();

	}
	public function get_job_phase_list($job_id){
		return $task_list = $this->db->query("SELECT *FROM construction_development_phase WHERE development_id = '$job_id'")->result();

	}

	function update_check_list($task_id,$update){
		$this->db->where($this->primary_key,$task_id);
		$this->db->update($this->table_construction_check_list,$update);
	}
        
}
?>