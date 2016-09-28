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
		return $this->db->get($this->table_consent)->result();
	}



}



?>