<?php
class Consent_model extends CI_Model
{
	private $table_consent = 'consents';
	private $table_group_permissions = 'group_permissions';
	private $table_consent_month = 'consent_months';
	private $table_from_m_to_m = 'user_from_month_to_month';
	private $table_consent_template = 'consent_template';
	private $table_permission_keyword = 'permission_keyword';

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

	public function get_all_keyword_group_id($group_id)
	{
		$this->db->where('group_id', $group_id);
		return $this->db->get($this->table_permission_keyword)->result();
	}

	public function get_consent_info_by_monthid($month_id, $parent_id=0,$keywords,$ss,$report_search_value,$start_date,$end_date)
	{
		$user = $this->session->userdata('user');
		$wp_company_id=  $user->company_id;
		$group_id=  $this->session->userdata('user_group_id'); 

		$all_columns = $this->get_all_column_name('consents');
		$all_keywords = $this->get_all_keyword_group_id($group_id);
		
		$this->db->select('consents.*, a.name as consent_by , b.name as project_manager, c.name as builder');
		$this->db->join('users a', 'a.uid = consents.consent_by', 'left');
		$this->db->join('users b', 'b.uid = consents.project_manager', 'left');
		$this->db->join('users c', 'c.uid = consents.builder', 'left');
		$this->db->where('wp_company_id', $wp_company_id);
		$this->db->where('month_id', $month_id);
		$this->db->where('parent_id', $parent_id);


		foreach($all_keywords as $all_keyword){
			$gro_keyword = $all_keyword->keyword;
			$this->db->like('consent_name', $gro_keyword);
		}
		

		if($keywords!='' || $ss!='')
		{
			if($ss!='')
			{
				$ss = explode(",",$ss);
				for($i = 0; $i < count($ss); $i++){

					$report_search_value_1 = explode(",",$report_search_value);
					$report_search_value3 = '';
					for($a = 0; $a < count($report_search_value_1); $a++){
						$report_search_value1 = $report_search_value_1[$a];
						$report_search_value2 = explode("=",$report_search_value1);
						if($ss[$i].'_search_value'==$report_search_value2[0]){
							$report_search_value3 = $report_search_value2[1];
							break;
						}
					}
					
					$start_date_1 = explode(",",$start_date);
					$start_date3 = '';
					for($j = 0; $j < count($start_date_1); $j++){
						$start_date1 = $start_date_1[$j];
						$start_date2 = explode("=",$start_date1);
						if($ss[$i].'_from_month'==$start_date2[0]){
							if($start_date2[1]==''){
								$start_date3 = '0000-00-00';
							}else{
								$start_date3 = date("Y-m-d", strtotime($start_date2[1]));
							}
							break;
						}
					}
					
					$end_date_1 = explode(",",$end_date);
					$end_date3 = '';
					for($k = 0; $k < count($end_date_1); $k++){
						$end_date1 = $end_date_1[$k];
						$end_date2 = explode("=",$end_date1);
						if($ss[$i].'_to_month'==$end_date2[0]){
							if($end_date2[1]==''){
								$end_date3 = '0000-00-00';
							}else{
								$end_date3 = date("Y-m-d", strtotime($end_date2[1]));
							}
							break;
						}
					}

					if($ss[$i]=='approval_date' || $ss[$i]=='price_approved_date' || $ss[$i]=='pim_logged' 
					|| $ss[$i]=='drafting_issue_date' || $ss[$i]=='date_job_checked' || $ss[$i]=='date_logged' 
					|| $ss[$i]=='date_issued' || $ss[$i]=='actual_date_issued' || $ss[$i]=='unconditional_date' 
					|| $ss[$i]=='handover_date' || $ss[$i]=='title_date' || $ss[$i]=='settlement_date'){

						$this->db->where($ss[$i].' >= ', $start_date3);
						$this->db->where($ss[$i].' <= ', $end_date3);	
	
					}else if($ss[$i]=='job_no' || $ss[$i]=='consent_name' || $ss[$i]=='no_units' || $ss[$i]=='notes'){
						$like_q = " $ss[$i]  LIKE '%$report_search_value3%' ";
						$this->db->where($like_q, NULL, FALSE);	
					}else if($ss[$i]=='consent_by'){
						$this->db->where('a.name', $report_search_value3);
					}else if($ss[$i]=='project_manager'){
						$this->db->where('b.name', $report_search_value3);
					}else if($ss[$i]=='builder'){
						$this->db->where('c.name', $report_search_value3);
					}else{
						$this->db->where($ss[$i], $report_search_value3);
					}
				}
			}
			else
			{
				//$like_q = '( ';
			
				//for($i=0; $i<count($all_columns); $i++ )
				//{
					//$fname = $all_columns[$i]->Field;
					//$like_q = $like_q." $fname  LIKE '%$keywords%' OR ";	
					//$this->db->or_like($all_columns[$i]->Field, $keywords);			
				//}

				//$like_q = $like_q." a.name  LIKE '%$keywords%' OR ";
				//$like_q = $like_q." b.name  LIKE '%$keywords%' OR ";
				//$like_q = $like_q." c.name  LIKE '%$keywords%' ";

				//$like_q = $like_q.' ) ';

				//$this->db->where($like_q, NULL, FALSE);
				
			}
						
		}
		
		$this->db->order_by('ordering', 'ASC');
		return $this->db->get($this->table_consent)->result();
		//echo $this->db->last_query();
	}

	public function consent_update_child($update_data,$job_no,$has_child,$parent_id){
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;
		$this->db->where('wp_company_id', $wp_company_id);
		$this->db->where('job_no', $job_no);
		$this->db->update($this->table_consent,$update_data);
		if($has_child == 1 && $parent_id >0 ){
			$this->db->where('wp_company_id', $wp_company_id);
			$this->db->where('parent_id', $parent_id);
			$this->db->update($this->table_consent,$update_data);
			
			//echo $this->db->last_query(); exit;	
		}

		return $this->db->insert_id();
	}

	public function get_total_consents($month_id)
	{
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$this->db->select('count(id) AS total_consent');
		$this->db->where('wp_company_id', $wp_company_id);
		$this->db->where('month_id', $month_id);
		return $this->db->get($this->table_consent)->row();
	}

	public function get_total_consents_in($month_id,$type)
	{
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$this->db->select('count(id) AS total_consent_in');
		$this->db->where('wp_company_id', $wp_company_id);
		$this->db->where('month_id', $month_id);
		if($type == 'Chch')
		{
			$this->db->where('council != ', 'Auckland');
		}
		else
		{
			$this->db->where('council', $type);
		}
		$this->db->where("date_logged != '0000-00-00'");
		$this->db->order_by('ordering', 'ASC');
		return $this->db->get($this->table_consent)->row();
	}

	public function get_total_consents_out($month_id,$type)
	{
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$this->db->select('count(id) AS total_consent_out');
		$this->db->where('month_id', $month_id);
		$this->db->where('wp_company_id', $wp_company_id);
		if($type == 'Chch')
		{
			$this->db->where('council != ', 'Auckland');
		}
		else
		{
			$this->db->where('council', $type);
		}
		$this->db->where("date_issued != '0000-00-00'");
		$this->db->order_by('ordering', 'ASC');
		return $this->db->get($this->table_consent)->row();
	}
	
	public function get_total_consents_out_old($month_id)
	{
		$this->db->select('count(id) AS total_consent_out');
		$this->db->where('month_id', $month_id);
		$this->db->where("date_logged != '0000-00-00'");
		$this->db->order_by('ordering', 'ASC');
		return $this->db->get($this->table_consent)->row();
	}
	public function get_total_consents_handover($month_id)
	{
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$this->db->select('count(id) AS total_consents_handover');
		$this->db->where('wp_company_id', $wp_company_id);
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
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$job_no = $get['job_no'];
		$this->db->select('job_no');
		$this->db->where('job_no', $job_no);
		$this->db->where('wp_company_id',$wp_company_id);
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
		return $this->db->insert_id();
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
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$this->db->select('consent_months.*');
		$this->db->where('month',$month);
		$this->db->where('year',$year);
		$this->db->where('wp_company_id',$wp_company_id);
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
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$this->db->where('wp_company_id', $wp_company_id);
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
	public function get_consent_report_by_date($start_date,$end_date,$keywords,$ss)
	{
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$all_columns = $this->get_all_column_name('consents');

		$start_date = str_replace('-','',substr($start_date,0,7)); 
		$end_date = str_replace('-','',substr($end_date,0,7)); 

		$this->db->select('consents.*, a.name as consent_by , b.name as project_manager, c.name as builder');
		$this->db->join('users a', 'a.uid = consents.consent_by', 'left');
		$this->db->join('users b', 'b.uid = consents.project_manager', 'left');
		$this->db->join('users c', 'c.uid = consents.builder', 'left');
		$this->db->where('wp_company_id', $wp_company_id);

		if($start_date && $end_date)
		{
			$this->db->where('month_id >= ', $start_date);
			$this->db->where('month_id <= ', $end_date);
		}

		$field_check = array();

		
		$un = unserialize($ss); 
		if(count($un)>0)
		{
			$field_check = $un[0];
		}
	

		if($keywords!='')
		{
			if(count($field_check) > 0)
			{
				for($i=0; $i<count($all_columns); $i++ )
				{
					if(in_array($i, $field_check))
					{
						if($i==11)
						{
							$this->db->or_like('a.name', $keywords);
						}
						else if($i==31)
						{
							$this->db->or_like('b.name', $keywords);
						}
						else if($i==35)
						{
							$this->db->or_like('c.name', $keywords);
						}
						else
						{
							$this->db->or_like($all_columns[$i]->Field, $keywords);	
						}
					}		
				}
			}
			else
			{
				for($i=0; $i<count($all_columns); $i++ )
				{
					$this->db->or_like($all_columns[$i]->Field, $keywords);			
				}
				
				$this->db->or_like('a.name', $keywords);
				$this->db->or_like('b.name', $keywords);
				$this->db->or_like('c.name', $keywords);
			}
				
			
		
		}

		$this->db->order_by('ordering', 'ASC');

		return $this->db->get($this->table_consent)->result();		
		//echo $this->db->last_query(); exit;
	}
	public function get_archive_date()
	{
		$this->db->select('month, year');
		$this->db->where('status',1);
		$this->db->order_by('year, month DESC');
		return $this->db->get('consent_months')->result();
	}
	public function check_available_month($month_id){
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$year = substr($month_id,0,4);
		$month = substr($month_id,4,6);
		$this->db->select('month');
		$this->db->where('month',$month);
		$this->db->where('year',$year);
		$this->db->where('wp_company_id',$wp_company_id);
		return $this->db->get('consent_months')->result();
	}

	public function get_all_column_name($table_name){
		$result = $this->db->query("SHOW COLUMNS FROM $table_name")->result();
		return $result;
	}

	public function from_month_to_month($month)
	{
		$user = $this->session->userdata('user');
			
		$this->db->where('user_id', $user->uid);
		$this->db->delete($this->table_from_m_to_m);

		$this->db->insert($this->table_from_m_to_m,$month);
		return $this->db->insert_id();
	}

	public function get_from_month_to_month()
	{
		$user = $this->session->userdata('user');
			
		$this->db->where('user_id', $user->uid);
		return $this->db->get($this->table_from_m_to_m)->row();
	}

	public function get_consent_report($start_date,$end_date,$keywords,$ss,$report_search_value)
	{
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$all_columns = $this->get_all_column_name('consents');

		$this->db->select('consents.*, a.name as consent_by , b.name as project_manager, c.name as builder');
		$this->db->join('users a', 'a.uid = consents.consent_by', 'left');
		$this->db->join('users b', 'b.uid = consents.project_manager', 'left');
		$this->db->join('users c', 'c.uid = consents.builder', 'left');
		$this->db->where('wp_company_id', $wp_company_id);

		if($keywords!='' || $ss!='')
		{
			if($ss!='')
			{
				$ss = explode(",",$ss);
				for($i = 0; $i < count($ss); $i++){

					$report_search_value_1 = explode(",",$report_search_value);
					$report_search_value3 = '';
					for($a = 0; $a < count($report_search_value_1); $a++){
						$report_search_value1 = $report_search_value_1[$a];
						$report_search_value2 = explode("=",$report_search_value1);
						if($ss[$i].'_search_value'==$report_search_value2[0]){
							$report_search_value3 = $report_search_value2[1];
							break;
						}
					}

					$start_date_1 = explode(",",$start_date);
					$start_date3 = '';
					for($j = 0; $j < count($start_date_1); $j++){
						$start_date1 = $start_date_1[$j];
						$start_date2 = explode("=",$start_date1);
						if($ss[$i].'_from_month'==$start_date2[0]){
							if($start_date2[1]==''){
								$start_date3 = '0000-00-00';
							}else{
								$start_date3 = date("Y-m-d", strtotime($start_date2[1]));
							}
							break;
						}
					}
					
					$end_date_1 = explode(",",$end_date);
					$end_date3 = '';
					for($k = 0; $k < count($end_date_1); $k++){
						$end_date1 = $end_date_1[$k];
						$end_date2 = explode("=",$end_date1);
						if($ss[$i].'_to_month'==$end_date2[0]){
							if($end_date2[1]==''){
								$end_date3 = '0000-00-00';
							}else{
								$end_date3 = date("Y-m-d", strtotime($end_date2[1]));
							}
							break;
						}
					}

					if($ss[$i]=='approval_date' || $ss[$i]=='price_approved_date' || $ss[$i]=='pim_logged' 
					|| $ss[$i]=='drafting_issue_date' || $ss[$i]=='date_job_checked' || $ss[$i]=='date_logged' 
					|| $ss[$i]=='date_issued' || $ss[$i]=='actual_date_issued' || $ss[$i]=='unconditional_date' 
					|| $ss[$i]=='handover_date' || $ss[$i]=='title_date' || $ss[$i]=='settlement_date'){

						$this->db->where($ss[$i].' >= ', $start_date3);
						$this->db->where($ss[$i].' <= ', $end_date3);	
	
					}else if($ss[$i]=='job_no' || $ss[$i]=='consent_name' || $ss[$i]=='no_units' || $ss[$i]=='notes'){
						$like_q = " $ss[$i]  LIKE '%$report_search_value3%' ";
						$this->db->where($like_q, NULL, FALSE);	
					}else if($ss[$i]=='consent_by'){
						$this->db->where('a.name', $report_search_value3);
					}else if($ss[$i]=='project_manager'){
						$this->db->where('b.name', $report_search_value3);
					}else if($ss[$i]=='builder'){
						$this->db->where('c.name', $report_search_value3);
					}else{
						$this->db->where($ss[$i], $report_search_value3);
					}
				}
			}
			else
			{
				$like_q = '( ';
			
				for($i=0; $i<count($all_columns); $i++ )
				{
					$fname = $all_columns[$i]->Field;
					$like_q = $like_q." $fname  LIKE '%$keywords%' OR ";	
					//$this->db->or_like($all_columns[$i]->Field, $keywords);			
				}

				$like_q = $like_q." a.name  LIKE '%$keywords%' OR ";
				$like_q = $like_q." b.name  LIKE '%$keywords%' OR ";
				$like_q = $like_q." c.name  LIKE '%$keywords%' ";

				$like_q = $like_q.' ) ';

				$this->db->where($like_q, NULL, FALSE);

			}		
		}

		$this->db->order_by('ordering', 'ASC');
		return $this->db->get($this->table_consent)->result();		
		//echo $this->db->last_query(); exit;
	}

	public function consent_template_add($add)
	{
		$this->db->insert($this->table_consent_template,$add);
		return $this->db->insert_id();
	}

	public function consent_template_update($id,$update)
	{
		$this->db->where('id', $id);
		return $this->db->update($this->table_consent_template,$update);
	}

	public function load_template()
	{
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$this->db->where('wp_company_id', $wp_company_id);
		$this->db->order_by('id', 'DESC');
		return $this->db->get($this->table_consent_template)->result();	
	}

	public function template_id($id)
	{
		$this->db->where('id', $id);
		return $this->db->get($this->table_consent_template);	
	}

}
?>