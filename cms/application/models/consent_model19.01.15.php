<?php
class Consent_model extends CI_Model
{

	private $table_consent = 'consents';

	public function __construct()
	{
		parent::__construct();
	}

	public function get_consent_info($month_start_date,$month_last_day)
	{
		$this->db->where('approval_date >= ', $month_start_date);
		$this->db->where('approval_date <= ', $month_last_day);
                $this->db->order_by('id', 'desc');
		return $this->db->get($this->table_consent)->result();
	}
        
         function get_consent_no(){
            $sql = "SELECT MAX(job_no) max_job_no FROM consents";             
            $query =  $this->db->query($sql)->row();                
            return $query->max_job_no;	 
        }
        
        public function consent_save($consent_data){

		$this->db->insert($this->table_consent, $consent_data);
		//return $this->db->insert_id();
	}



}



?>