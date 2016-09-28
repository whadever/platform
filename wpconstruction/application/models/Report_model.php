<?php
class Report_model extends CI_Model {
	
	private $primary_key = 'sid';
	private $table_development = 'development';
	private $table_development_phase = 'development_phase';
	private $table_development_milestone = 'development_milestone';
	private $table_development_task = 'development_task';

	
	function __construct() {
		parent::__construct();
	}
	
	
	public function get_devlopments() {

		$this->db->select('`id`,`development_name`');
		$this->db->order_by('development_name', 'ASC');
		$devs = $this->db->get($this->table_development)->result();
		return $devs;
            
	}
	
	public function get_development_milestone_info($development_id)
	{

		$this->db->select('`id`,`milestone_phases`,`milestone_select_color`');
		$this->db->where('development_id',$development_id);
		$milestone_info = $this->db->get($this->table_development_milestone)->result();

		return $milestone_info;
 	}

	public function get_development_milestone_phase_info($milestone_id,$phases)
	{

		$phases_arr = explode(',',$phases);

		$this->db->select('`id`, MIN(`planned_start_date`) as start_date, MAX(`planned_finished_date`) as end_date, phase_status');
		$this->db->where_in('id', $phases_arr);
		$data = $this->db->get($this->table_development_phase)->result(); 

		return $data;
	
	}
	
	public function get_milestone_phase_data($phases,$development_id)
	{

		$phases_arr = explode(',',$phases);
		$phase_data = '';

		for($i=0; $i<count($phases_arr); $i++)
		{
			$phase_id = $phases_arr[$i];

			$this->db->select('phase_name');
			$this->db->where_in('id', $phase_id);
			$data = $this->db->get($this->table_development_phase)->result();

			$stat = $this->get_all_development_phase_status($development_id,$phase_id)->result();

			$status = $stat[0]->all_task_status;

			if($status == 1){$phase_status = "Complete";}
			else{$phase_status = "Not complete yet";}
	
			$phase_data = $phase_data.$data[0]->phase_name.' -- '.$phase_status.'<br>';
			
		}

		return $phase_data;

	}



	public function get_all_development_phase_status($development_id,$phase_id)
	{
  		$this->db->select('`phase_id`, MIN(`development_task_status`) as all_task_status');
  		$this->db->where('development_id', $development_id);
		$this->db->where('phase_id', $phase_id);
  		$this->db->group_by('phase_id');
  		return $this->db->get($this->table_development_task);
  
 	}



	    
}
?>