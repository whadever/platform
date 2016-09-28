<?php 
class Job_model extends CI_Model {
	
	private $primary_key = 'id';
	private $table_jobs = 'jobcosting_jobs';
	private $table_items = 'jobcosting_items';
	private $table_templates = 'jobcosting_templates';
	private $table_templates_items = 'jobcosting_templates_items';
	private $table_jobs_costing = 'jobcosting_jobs_costing';
	
	function __construct() {
		parent::__construct();
	}
	
	public function get_contact_list()
    {
		$user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;

  		$this->db->select("id,contact_first_name,contact_last_name");
        $this->db->where('wp_company_id', $wp_company_id);
  		return $this->db->get('contact_contact_list')->result();
  	}
  	
  	public function get_construction_job_list()
    {
		$user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;

  		$this->db->select("parent_unit,id,development_name");
        $this->db->where('wp_company_id', $wp_company_id);
  		return $this->db->get('construction_development')->result();
  	}
	
	public function delete_jobs_costing($jid)
    {
        $this->db->where('job_id',$jid);
        $this->db->delete($this->table_jobs_costing);
    }
	
	public function insert_jobs($data)
    {
        $this->db->insert($this->table_jobs, $data);
		return $this->db->insert_id();
    }
    
    public function update_jobs($jid,$data)
    {
    	$this->db->where('id',$jid);
        $this->db->update($this->table_jobs, $data);
    }
    
    public function insert_jobs_costing($data)
    {
        $this->db->insert($this->table_jobs_costing, $data);
		return $this->db->insert_id();
    }
    
    public function insert_jobs_costing_update($id,$data)
    {
    	$this->db->where('id',$id);
        $this->db->update($this->table_jobs_costing, $data);
    }
    
    public function get_job_id($jid) {
    	$this->db->select("jobcosting_templates.job_name,jobcosting_jobs.*");
		$this->db->join('jobcosting_templates', 'jobcosting_templates.id = jobcosting_jobs.template_id', 'left');
		$this->db->where('jobcosting_jobs.id',$jid);
		return $this->db->get($this->table_jobs);
	} 
	
	public function get_jobs($tem_id) {
    	$this->db->select("jobcosting_jobs.*,jobcosting_templates.job_name");
    	$this->db->join('jobcosting_templates', 'jobcosting_templates.id = jobcosting_jobs.template_id', 'left');
		$this->db->where('template_id',$tem_id);
		return $this->db->get($this->table_jobs);
		 //echo $this->db->last_query(); exit;
	} 
	
	public function get_template_id($tem_id) {
    	$this->db->select("jobcosting_templates.*");
		$this->db->where('id',$tem_id);
		return $this->db->get($this->table_templates);
	} 
    	
}
?>