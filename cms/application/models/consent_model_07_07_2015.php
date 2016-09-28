<?php
class Consent_model extends CI_Model
{
	private $table_consent = 'consents';
	private $table_group_permissions = 'group_permissions';
	private $table_consent_month = 'consent_months';
	public function __construct()
	{
		parent::__construct();
	}
	public function get_consent_info($month_start_date,$month_last_day)
	{
		$this->db->select('consents.*, a.name as consent_by , b.name as project_manager, c.name as builder');
		$this->db->join('users a', 'a.uid = consents.consent_by', 'left');
		$this->db->join('users b', 'b.uid = consents.project_manager', 'left');
		$this->db->join('users c', 'c.uid = consents.builder', 'left');
		$this->db->where('approval_date >= ', $month_start_date);
		$this->db->where('approval_date <= ', $month_last_day);
		$this->db->order_by('id', 'ASC');
		return $this->db->get($this->table_consent)->result();
	}
	public function get_consent_info_by_monthid($month_id)
	{
		$this->db->select('consents.*, a.name as consent_by , b.name as project_manager, c.name as builder');
		$this->db->join('users a', 'a.uid = consents.consent_by', 'left');
		$this->db->join('users b', 'b.uid = consents.project_manager', 'left');
		$this->db->join('users c', 'c.uid = consents.builder', 'left');
		$this->db->where('month_id', $month_id);
		$this->db->order_by('ordering', 'ASC');
		return $this->db->get($this->table_consent)->result();
	}
	public function get_total_consents_in($month_id)
	{
		$this->db->select('count(id) AS total_consent_in');
		$this->db->where('month_id', $month_id);
		$this->db->where("date_issued != '0000-00-00'");
		$this->db->order_by('ordering', 'ASC');
		return $this->db->get($this->table_consent)->row();
	}
	public function get_total_consents_out($month_id)
	{
		$this->db->select('count(id) AS total_consent_out');
		$this->db->where('month_id', $month_id);
		$this->db->where("date_logged != '0000-00-00'");
		$this->db->order_by('ordering', 'ASC');
		return $this->db->get($this->table_consent)->row();
	}
	public function get_total_consents_handover($month_id)
	{
		$this->db->select('count(id) AS total_consents_handover');
		$this->db->where('month_id', $month_id);
		$this->db->where("handover_date != '0000-00-00'");
		$this->db->order_by('ordering', 'ASC');
		return $this->db->get($this->table_consent)->row();
	}
	function get_active_tabs($user_id)
	{
		$this->db->select('tab_ids');
		$this->db->where('user_id',$user_id);
		return $this->db->get('user_session')->row();
	}
	function check_job_no($get)
	{
		$job_no = $get['job_no'];
		$this->db->select('job_no');
		$this->db->where('job_no', $job_no);
		$job_no_check = $this->db->get($this->table_consent)->row();
		if($job_no_check->job_no == $job_no){
			print_r('1');	
		}else{
			print_r('0');
		}
	}
	function get_consent_no()
	{
		$sql = "SELECT MAX(job_no) max_job_no FROM consents";             
		$query =  $this->db->query($sql)->row();                
		return $query->max_job_no;	 
	}
	public function consent_save($consent_data)
	{
		$this->db->insert($this->table_consent, $consent_data);
		//return $this->db->insert_id();
	}
    public function add_month($month_data)
	{
		try
		{	
			$this->db->insert($this->table_consent_month,$month_data);
			redirect('consent/consent_list/month_add_success');	
		}
		catch(Exception $e)
		{
			var_dump($e->getMessage());
			redirect('consent/consent_list/month_add_unsuccess');	
		}
	}
	public function check_year_month($year,$month)
	{
		$this->db->select('consent_months.*');
		$this->db->where('month',$month);
		$this->db->where('year',$year);
		$val = $this->db->get('consent_months')->row();
		if($val)
		{
			echo "0";
		}
		else
		{
			echo "1";
		}
		return;
	}
	public function user_option()
	{
		$sql = "SELECT uid, name, group_id from users";
		$query =  $this->db->query($sql)->result();              
		return $query;
	}
	public function get_user_category_list($cid)
	{
		$sql = "SELECT u.uid, u.fullname from users AS u LEFT JOIN user_to_category AS uc on uc.user_id = u.uid WHERE uc.category_id = ".$cid;
		$query =  $this->db->query($sql)->result();              
		return $query;	
	}
	public function get_user_permission_type($user_group_id)
	{
		$this->db->where('group_id', $user_group_id );
		return $this->db->get($this->table_group_permissions)->result();
	}
	public function update_consent($job_no, $update_data)
	{
		$this->db->where('job_no', $job_no);
		return $this->db->update($this->table_consent,$update_data);
	}
	public function get_consent_info_report($id)
	{
		$this->db->where('id', $id);
		return $this->db->get($this->table_consent)->row();
	}
    public function save_consent_ordering($ordering, $month_id)
	{

		$i = 0; 
		foreach ($_POST as $field => $value )
		{     
        	$fields[] =  $field; 
        	$values[] = $value;      
        	$i ++; 
		}
		for($j=0; $j< count($fields); $j++)
		{
		
			$consent_id_field = $fields[$j];
			foreach ($_POST[$consent_id_field] as $position => $item)
			{	
				$sql = "UPDATE consents SET month_id = $month_id WHERE id = $item";
            	$res = mysql_query($sql);
			}
			
		}
       // final ordering
		for($j=0; $j< count($ordering); $j++)
	 	{
			$order = $j;
			$consent_id = $ordering[$j];
			$sql_order = "UPDATE consents SET ordering = $order WHERE id = $consent_id";
			$res = mysql_query($sql_order);
		}
		return $res;
	}
	public function consent_delete($consent_id)
	{
		$this->db->where('id', $consent_id);
		$this->db->delete($this->table_consent);
	}
	public function get_working_days($start_date, $end_date)
	{
		$start = new DateTime($start_date);
		$end = new DateTime($end_date);
		$oneday = new DateInterval("P1D");
		$total_working_days = 0;
		foreach(new DatePeriod($start, $oneday, $end->add($oneday)) as $day) 
		{
    		$day_num = $day->format("N");
    		if($day_num < 6) 
			{ 
				$total_working_days = $total_working_days + 1;
    		} 
		}    
		return $total_working_days;
	}
	public function get_consent_report_by_date($start_date,$end_date)
	{
		$this->db->select('consents.*, a.name as consent_by , b.name as project_manager, c.name as builder');
		$this->db->join('users a', 'a.uid = consents.consent_by', 'left');
		$this->db->join('users b', 'b.uid = consents.project_manager', 'left');
		$this->db->join('users c', 'c.uid = consents.builder', 'left');
		$this->db->where('approval_date >= ', $start_date);
		$this->db->where('approval_date <= ', $end_date);
		$this->db->order_by('ordering', 'ASC');
		return $this->db->get($this->table_consent)->result();		
	}
	public function get_archive_date()
	{
		$this->db->select('month, year');
		$this->db->where('status',1);
		$this->db->order_by('year, month DESC');
		return $this->db->get('consent_months')->result();
	}
}
?>