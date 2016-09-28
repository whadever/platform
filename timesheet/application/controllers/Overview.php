<?php 
class Overview extends CI_Controller {
	private $limit = 10;

	function __construct() {
		parent::__construct();
		$this->load->model('project_model','',TRUE);
		//$this->load->model('user_model','',TRUE);
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->library('Wbs_helper');
		$this->load->helper(array('form', 'url'));
        //$this->load->helper('email');
        date_default_timezone_set("NZ");

		$redirect_login_page = base_url().'user';

		$user = $this->session->userdata('user');
		$user_id = $user->uid;
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		}


		$sql = "select LOWER(ar.application_role_name) role
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 7 limit 0, 1";

		$this->user_app_role = ($this->db->query($sql)->row()) ? $this->db->query($sql)->row()->role : '';


		              
	}
        
    public function index(){

		$data['title'] = 'Overview';
		$user=  $this->session->userdata('user');
		$user_id = $user->uid;

		$data['user']=$user;
		$data['user_app_role'] = $this->user_app_role;

		$day = date('w')-1;
		$week_start = date('Y-m-d', strtotime('-'.$day.' days'));
		$last_week_start = date('Y-m-d', strtotime('-'.($day+7).' days'));
		$last_week_end = date('Y-m-d', strtotime('-'.($day+1).' days'));
		$last_month_start = date('Y-m-d', strtotime('first day of previous month'));
		$last_month_end = date('Y-m-d', strtotime('last day of previous month'));
		$today = date('Y-m-d');

		/*initializing the array with zero value*/
		$arr = array();
		$legend = array();
		$dt = date_create_from_format('Y-m-d',$week_start);
		while($dt->format('Y-m-d') <= $today){
			$arr[$dt->format('Y-m-d')] = array(
				//'label' => strtoupper($dt->format('l'))." 0 hour",
				'label' => "0 hour",
				'hour' =>  0
			);
			$legend[] = $dt->format('l');
			$dt->add(new DateInterval("P1D"));
		}
		$data['legend'] = json_encode($legend);
		/*retrieving this weeks hours*/
		$sql = "SELECT ts_timesheet_entries.day, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time FROM ts_timesheet_entries where user_id = {$user_id} AND ts_timesheet_entries.day BETWEEN '{$week_start}' AND '{$today}' GROUP BY ts_timesheet_entries.day";
		$week_entries = $this->db->query($sql)->result();
		foreach($week_entries as $entry){
			$dt = date_create_from_format('Y-m-d',$entry->day);
			$hour = floor($entry->total_time / 60)." Hours";
			if($entry->total_time % 60 != 0){
				$hour .= " ".($entry->total_time % 60). " Minutes ";
			}
			$arr[$dt->format('Y-m-d')] = array(
				//'label' => strtoupper($dt->format('l'))." ".$hour,
				'label' => $hour,
				'hour' =>  $entry->total_time
			);
		}
		$data['hours'] = $arr;


		/*admin getting hours of users for this week*/
		$sql = "SELECT project.project_name, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time, users.uid, users.username 
					FROM ts_timesheet_entries 
					LEFT JOIN users ON users.uid = ts_timesheet_entries.user_id  
					LEFT JOIN project ON project.id = ts_timesheet_entries.project_id  
					where project.wp_company_id = ".$user->company_id." AND ts_timesheet_entries.day BETWEEN '{$week_start}' AND '{$today}' GROUP BY ts_timesheet_entries.user_id";
		$user_entries = $this->db->query($sql)->result();
		$data['user_entries'] = $user_entries;
		
		/*admin getting hours of users for last week*/
		$sql = "SELECT project.project_name, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time, users.uid, users.username 
					FROM ts_timesheet_entries 
					LEFT JOIN users ON users.uid = ts_timesheet_entries.user_id  
					LEFT JOIN project ON project.id = ts_timesheet_entries.project_id  
					where project.wp_company_id = ".$user->company_id." AND ts_timesheet_entries.day BETWEEN '{$last_week_start}' AND '{$last_week_end}' GROUP BY ts_timesheet_entries.user_id";
		$user_last_week = $this->db->query($sql)->result();
		$data['user_last_week'] = $user_last_week;
		
		/*admin getting hours of users for last month*/
		$sql = "SELECT project.project_name, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time, users.uid, users.username 
					FROM ts_timesheet_entries 
					LEFT JOIN users ON users.uid = ts_timesheet_entries.user_id  
					LEFT JOIN project ON project.id = ts_timesheet_entries.project_id  
					where project.wp_company_id = ".$user->company_id." AND ts_timesheet_entries.day BETWEEN '{$last_month_start}' AND '{$last_month_end}' GROUP BY ts_timesheet_entries.user_id";
		$user_last_month = $this->db->query($sql)->result();
		$data['user_last_month'] = $user_last_month;


		$sql = "SELECT project.id AS pid, project.project_name, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time, users.uid, users.username 
					FROM ts_timesheet_entries 
					LEFT JOIN users ON users.uid = ts_timesheet_entries.user_id  
					LEFT JOIN project ON project.id = ts_timesheet_entries.project_id  
					where project.wp_company_id = ".$user->company_id." AND ts_timesheet_entries.day BETWEEN '{$week_start}' AND '{$today}' GROUP BY ts_timesheet_entries.project_id";
		$project_entries = $this->db->query($sql)->result();
		$data['project_entries'] = $project_entries;


		$sql = "SELECT project.id, project.project_name, SUM(TIME_TO_SEC(TIMEDIFF(ts_timesheet_entries.finish_time,ts_timesheet_entries.start_time))/60 - ts_timesheet_entries.break_time) total_time, users.uid, users.username 
					FROM ts_timesheet_entries 
					LEFT JOIN users ON users.uid = ts_timesheet_entries.user_id  
					LEFT JOIN project ON project.id = ts_timesheet_entries.project_id  
					where project.wp_company_id = ".$user->company_id." AND ts_timesheet_entries.day BETWEEN '{$week_start}' AND '{$today}' GROUP BY ts_timesheet_entries.project_id , ts_timesheet_entries.user_id ";
		$time_entries = $this->db->query($sql)->result();
		$data['time_entries'] = $time_entries;


		/*getting hours for this week*/
		$sql = "SELECT project.project_name, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time FROM ts_timesheet_entries, project where user_id = {$user_id} AND project.id = ts_timesheet_entries.project_id AND ts_timesheet_entries.day BETWEEN '{$week_start}' AND '{$today}' GROUP BY ts_timesheet_entries.project_id";
		$entries = $this->db->query($sql)->result();
		$cal_result = $this->_get_project_wise_hours($entries);
		$data['this_week_total'] = $cal_result[0];
		$data['this_week_hours'] = $cal_result[1];

		/*getting hours for last week*/
		$sql = "SELECT project.project_name, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time FROM ts_timesheet_entries, project where user_id = {$user_id} AND project.id = ts_timesheet_entries.project_id AND ts_timesheet_entries.day BETWEEN '{$last_week_start}' AND '{$last_week_end}' GROUP BY ts_timesheet_entries.project_id";
		$entries = $this->db->query($sql)->result();
		$cal_result = $this->_get_project_wise_hours($entries);
		$data['last_week_total'] = $cal_result[0];
		$data['last_week_hours'] = $cal_result[1];

		/*getting hours for last month*/
		$sql = "SELECT project.project_name, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time FROM ts_timesheet_entries, project where user_id = {$user_id} AND project.id = ts_timesheet_entries.project_id AND ts_timesheet_entries.day BETWEEN '{$last_month_start}' AND '{$last_month_end}' GROUP BY ts_timesheet_entries.project_id";
		$entries = $this->db->query($sql)->result();
		$cal_result = $this->_get_project_wise_hours($entries);
		$data['last_month_total'] = $cal_result[0];
		$data['last_month_hours'] = $cal_result[1];

		$data['maincontent'] = $this->load->view('overview',$data,true);

		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

	private function _get_project_wise_hours($sql_res){
		$total_hour = 0;
		$project_wise_hours = array();
		foreach($sql_res as $entry){
			$total_hour += $entry->total_time;
			$hour = floor($entry->total_time / 60)." Hours";
			if($entry->total_time % 60 != 0){
				$hour .= " ".($entry->total_time % 60). " Minutes ";
			}
			$project_wise_hours[] = array(
				'project' => $entry->project_name,
				'hours' => $hour
			);
		}
		$total_hour_text = "<b>".floor($total_hour / 60)."</b> Hours";
		if($total_hour % 60 != 0){
			$total_hour_text .= " <b>".($total_hour % 60). "</b> Minutes ";
		}

		return array($total_hour_text, $project_wise_hours);
	}
           	
}