<?php 
class Timesheet extends CI_Controller {

	private $user_id;
	private $user_app_role;
	private $wp_company_id;

	function __construct() {
		parent::__construct();
		$this->load->model('project_model','',TRUE);
		$this->load->model('timesheet_model','',TRUE);
		$this->load->model('user_model','',TRUE);
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->library('Wbs_helper');
		$this->load->library('user_agent');
		$this->load->library('pdf');
		$this->load->helper(array('form', 'url'));
        //$this->load->helper('email');
        date_default_timezone_set("NZ");

		$redirect_login_page = base_url().'user';

		$user = $this->session->userdata('user');
		$company_id = $user->company_id;
		$user_id = $user->uid;
		if(!$this->session->userdata('user')){	
				redirect($redirect_login_page,'refresh'); 		 
		}

		$user=  $this->session->userdata('user');
		$this->user_id = $user->uid;
		$this->wp_company_id = $user->company_id;

		$this->email = $user->email;
		$this->username = $user->username;

		$sql = "select LOWER(ar.application_role_name) role
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 7 limit 0, 1";

		$this->user_app_role = ($this->db->query($sql)->row()) ? $this->db->query($sql)->row()->role : '';
		              
	}
        
    public function index($start_day=''){
		if($start_day == ''){
			$start_day = date('Y-m-d',strtotime('Monday this week'));
		}else{
			/* does the week exist in the system (in ts_weeks table)? */
			$d = $this->db->query("select * from ts_weeks where start_date = '{$start_day}' limit 0, 1")->row();
			if(is_null($d)){
				$this->load->view('errors/html/error_general',array(
					'heading' => 'Timesheet for this week does not exist.',
					'message' => null
				));
				return;
			}
			$start_day = $d->start_date;
		}
		/*is the week submitted?*/
		$sql = "select * from ts_submitted_weeks sw, ts_weeks w where sw.week_id = w.id and w.start_date = '{$start_day}' and sw.user_id = {$this->user_id} limit 0, 1";
		$is_submitted = false;
		if(!is_null($this->db->query($sql)->row())){
			$is_submitted = true;
		}
		$data['title'] = 'Time Sheet';
		$data['start_date'] = $start_day;
		$data['is_week_submitted'] = $is_submitted;

		$user=  $this->session->userdata('user');
		$data['user']=$user;
		
		$today = date('Y-m-d');
		$data['timers'] = $this->timesheet_model->get_time_entries_timer($today,1)->row();

		$data['maincontent'] = $this->load->view('timesheet',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

	public function time_entry($date=''){
		if($date == ''){
			return;
		}
		$data['day'] = $date;

		/*getting previous entries for this day*/
		$data['entries'] = $this->timesheet_model->get_time_entries($date,0)->result();
		
		$data['entries_timer'] = $this->timesheet_model->get_time_entries_timer($date,1)->result();
		
		//#task 4520; all projects visible for all Williams Companies 
		if($this->wp_company_id=='31' || $this->wp_company_id=='29' || $this->wp_company_id=='24' || $this->wp_company_id=='26' || $this->wp_company_id=='27' || $this->wp_company_id=='28' || $this->wp_company_id=='30' || $this->wp_company_id=='38'){
			$data['projects'] = $this->project_model->get_project_list()->result();
		}else{
			$data['projects'] = $this->project_model->get_project_list_by_user()->result();
		}

		$data['leave_check'] = $this->timesheet_model->request_leave_check($this->user_id,$date)->row(); 

		$this->load->view('includes/modal_header');
		$this->load->view('time_entry',$data);

	}

	public function add_entry($entry_id = ''){
		$post = $this->input->post();
		/*validation*/
		$status = "success";
		$error_msg = array();
		if(empty($post['project_id'])){
			$status = "error";
			$error_msg[] = "Please select a project.";
		}
		if(empty($post['start_time'])){
			$status = "error";
			$error_msg[] = "Please select a start time.";
		}
		if(empty($post['finish_time'])){
			$status = "error";
			$error_msg[] = "Please select a finish time.";
		}
		if($post['start_time'] && !preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $post['start_time'])){
			$status = "error";
			$error_msg[] = "Give a valid start time.";
		}
		if($post['finish_time'] && !preg_match("/(2[0-3]|[01][0-9]):([0-5][0-9])/", $post['finish_time'])){
			$status = "error";
			$error_msg[] = "Give a valid finish time.";
		}
		if($post['start_time'] && $post['finish_time'] && $post['start_time'] >= $post['finish_time']){
			$status = "error";
			$error_msg[] = "Start time cannot be greater than or equal to finish time.";
		}
		if($post['break_time'] && !is_numeric($post['break_time'])){
			$status = "error";
			$error_msg[] = "Please select a valid break time.";
		}
		/* give error msg when start / finish time falls in the range of another task*/
		if($post['start_time'] && $post['finish_time']){

			$this->db->select('*');
			$this->db->where(array(
				'user_id' => $this->user_id,
				'day' => $post['day'],
			));
			$this->db->where("(start_time between '{$post['start_time']}' and '{$post['finish_time']}' OR finish_time between '{$post['start_time']}' and '{$post['finish_time']}')");
			if($entry_id != ''){
				$this->db->where('id !=',$entry_id);
			}

			if($this->db->get('ts_timesheet_entries')->row()){

				//$status = "error";
				//$error_msg[] = "You have another entry within this start and finish time.";
			}
		}
		if($post['start_time'] && $post['finish_time'] && $post['break_time']){

			$to_time = strtotime("2008-12-13 {$post['finish_time']}:00");
			$from_time = strtotime("2008-12-13 {$post['start_time']}:00");
			if (round(abs($to_time - $from_time) / 60,2) <= $post['break_time']){
				$status = "error";
				$error_msg[] = "Work time must be greater than break time.";
			}
		}
		$projects = $this->project_model->get_project_list()->result();
		$pid = array();
		foreach($projects as $p){
			$pid[] = $p->id;
		}
		if($post['project_id'] && !in_array($post['project_id'], $pid)){
			$status = "error";
			$error_msg[] = "Not a valid project.";
		}
		$sql = "select count(*) c from ts_submitted_weeks sw, ts_weeks w
				where sw.week_id = w.id and sw.user_id = {$this->user_id} and w.start_date <= '{$post['day']}' and w.end_date >= '{$post['day']}'";
		$cnt = $this->db->query($sql)->row();
		if($cnt->c != 0){
			$status = "error";
			$error_msg[] = "You already submitted time sheet for this week.";
		}

		if($cnt->c != 0){
			$status = "error";
			$error_msg[] = "You already submitted time sheet for this week.";
		}

		if($this->wp_company_id == '0'){
			if(empty($post['task_id'])){
				$status = "error";
				$error_msg[] = "Please select a task.";
			}
		}else{
			$post['task_id'] = '0';
		}

		if($status == 'success'){
			if($entry_id == ''){
				$post['created'] = date('Y-m-d H:i:s');
				$post['user_id'] = $this->user_id;
				$this->timesheet_model->add_timesheet_entry($post);
			}else{
				$this->timesheet_model->update_timesheet_entry($this->user_id,$entry_id,$post);
			}
		}
		echo json_encode(array(
			'status' => $status, 'message'=>implode("<br>",$error_msg)
		));
		
	}
	
	public function add_start_timer($p_id,$day){
		
		$post['start_time'] = date('H:i');
		$post['timer_status'] = 1;
		$post['day'] = $day;
		$post['project_id'] = $p_id;
		$post['created'] = date('Y-m-d H:i:s');
		$post['user_id'] = $this->user_id;
		$this->timesheet_model->add_timesheet_entry($post);	
		
		redirect('time_entry/'.$day);	
	}
	
	public function update_start_timer($id,$day){
		
		$post['finish_time'] = date('H:i');
		$post['timer_status'] = 0;
		$post['break_time'] = 0;
		$this->timesheet_model->update_timesheet_entry($this->user_id,$id,$post);
		redirect('time_entry/'.$day);	
	}

	public function delete_entry($entry_id = ''){
		if($entry_id != ''){

			$this->timesheet_model->delete_timesheet_entry($this->user_id,$entry_id);
		}
	}

	public function submit_weekly_timesheet(){
		$start_date = $this->input->post('start_date');
		$valid = true;
		$status = array(
			'status' => 'success', 'message' => ''
		);
		$sql = "select * from ts_submitted_weeks sw, ts_weeks w where sw.week_id = w.id and w.start_date = '{$start_date}' and sw.user_id = {$this->user_id} limit 0, 1";
		if(!is_null($this->db->query($sql)->row())){
			$status = array(
				'status' => 'error', 'message' => 'You already submitted this week.'
			);
			$valid = false;
		}
		if($start_date >= date('Y-m-d',strtotime("monday this week")) && date('w')<=4){
			$status = array(
				'status' => 'error', 'message' => 'This week is not over yet.'
			);
			$valid = false;
		}
		if($valid){
			$sql = "select id from ts_weeks where start_date = '{$start_date}' limit 0, 1";
			$week_id = $this->db->query($sql)->row()->id;
			if(!is_null($week_id)){
				$sql = "insert into ts_submitted_weeks(week_id, user_id, created) values({$week_id},{$this->user_id},NOW())";
				echo $sql;
				$this->db->simple_query($sql);
			}else{
				$status = array(
					'status' => 'error', 'message' => 'Week does not exist.'
				);
			}
		}
		redirect($this->agent->referrer());

	}

	public function view_time_sheets(){

		//task #4124
		if($this->user_app_role == 'admin') exit;

		$data['title'] = 'View Time Sheets';
		$sql = "SELECT * FROM ts_weeks w LEFT JOIN (SELECT * FROM ts_submitted_weeks WHERE user_id = {$this->user_id}) sw ON w.id = sw.week_id ORDER BY w.id DESC ";
		$weeks = $this->db->query($sql)->result();

		$start_time_sheet_query = "SELECT MIN(day) AS first_entry FROM ts_timesheet_entries WHERE user_id = {$this->user_id}";
		$min_day = $this->db->query($start_time_sheet_query)->result();

		$data['weeks'] = $weeks;
		$data['first_entry'] = $min_day[0]->first_entry;
		$data['maincontent'] = $this->load->view('view_timesheets',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

	public function download_timesheet($start_date, $staff_id=''){

		if($staff_id == '')
		{
			$uid = $this->user_id;
			$uids_arr[] = $uid;
			$user_info = $this->user_model->user_details($uid);
			$pdf_name = $user_info->username;
		}
		elseif($staff_id == 'all')
		{
			$staffs = $this->timesheet_model->get_staff_list()->result();
			foreach($staffs as $staff){
				$uids_arr[] = $staff->staff_id;
			}
			$uids = implode(',',$uids_arr);
			$pdf_name = "All Staff Report";
		}
		else
		{
			$uid = $staff_id;
			$uids_arr[] = $uid;
			$user_info = $this->user_model->user_details($uid);
			$pdf_name = $user_info->username;
		}

		/*does the time sheet exist*/

		for($j = 0; $j<count($uids_arr); $j++)
		{
			$uid = $uids_arr[$j];
			$sql = "select * from ts_weeks, ts_submitted_weeks where ts_weeks.id = ts_submitted_weeks.week_id and ts_weeks.start_date = '{$start_date}' and ts_submitted_weeks.user_id = {$uid} limit 0,1";
			$row = $this->db->query($sql)->row();
			if(is_null($row)){ $total_sheet = 'null'; }
			if($row){ $total_sheet = 'not null'; break; }
		}

		

		if($total_sheet == 'null') { echo "There is no time sheet for this week of this user."; return;  }


		for($j = 0; $j<count($uids_arr); $j++)
		{
			$uid = $uids_arr[$j];
			$sql = "select * from ts_weeks, ts_submitted_weeks where ts_weeks.id = ts_submitted_weeks.week_id and ts_weeks.start_date = '{$start_date}' and ts_submitted_weeks.user_id = {$uid} limit 0,1";
			$row = $this->db->query($sql)->row();
			if(is_null($row)){ continue; }
			if($row){ $final_user_list[] = $uid; }
		}


		$tbl = "<style>
					td{
					border: black solid 1px;
					text-align: center;
					line-height: 15px;
					}
					th{
						text-align: center;
						background-color: #CC3300;
						padding-top: 2px;
						font-weight: bold;
						color: white;
						line-height: 15px;
					}
					div#total_time{
						text-align: right;
						font-weight: bold;
					}
				</style>";

		

		for($j = 0; $j<count($final_user_list); $j++)
		{

			$uid = $final_user_list[$j];
			$user_info = $this->user_model->user_details($uid);

			$sql = "select * from ts_weeks, ts_submitted_weeks where ts_weeks.id = ts_submitted_weeks.week_id and ts_weeks.start_date = '{$start_date}' and ts_submitted_weeks.user_id = {$uid} limit 0,1";
			$row = $this->db->query($sql)->row();


			$sql = "select ts_timesheet_entries.*, TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time total_time, project.project_name from ts_timesheet_entries, project where ts_timesheet_entries.project_id = project.id AND user_id = {$uid} AND day BETWEEN '{$row->start_date}' AND '{$row->end_date}' ORDER BY day, project_name";
			$entries = $this->db->query($sql)->result();
			$i = 1;


			if(!$entries) continue;
	
			
			$tbl .= "<div><h3>".$user_info->username."</h3></div><table>
						<thead>
							
							<tr>
									<th valign='middle'>Day / Date</th>
									<th valign='middle'>Project</th>
									<th valign='middle'>Start Time</th>
									<th valign='middle'>Finish Time</th>
									<th valign='middle'>Break Time (minutes)</th>
									<th valign='middle'>Note</th>
									<th valign='middle'>Total Time</th>
							</tr>
						</thead>
						<tbody>";

			/*task #4100*/
			$total = 0;
			foreach($entries as $entry){
				$style = "";
				if($i%2 == 0){
					$style="style='background-color:#EEE'";
				}
				$tbl .= "<tr {$style}>";
				$day = date_create_from_format('Y-m-d',$entry->day)->format('l');
				$day .= "/<br>".date_create_from_format('Y-m-d',$entry->day)->format('d F Y');
				$tbl .= "<td>{$day}</td>";
				$tbl .= "<td>{$entry->project_name}</td>";
				$start_time = date_create_from_format('H:i:s',$entry->start_time)->format('H:i');
				$finish_time = date_create_from_format('H:i:s',$entry->finish_time)->format('H:i');
				$tbl .= "<td>{$start_time}</td>";
				$tbl .= "<td>{$finish_time}</td>";
				$tbl .= "<td>{$entry->break_time}</td>";
				$tbl .= "<td>{$entry->note}</td>";
				$m = $entry->total_time % 60;
				if(strlen($m) == 1){
					$m = "0".$m;
				}
				$total_time = floor($entry->total_time / 60).":".$m;
				$tbl .= "<td>{$total_time}</td>";
				$tbl .= "</tr>";
				$i++;
				$total += $entry->total_time;
			}
			$tbl .= "</tbody></table>";

			/*task #4100*/
			$m = $total % 60;
			if(strlen($m) == 1){
				$m = "0".$m;
			}
			$total = floor($total / 60).":".$m;
			$tbl .= '<div id="total_time">Total: '.$total.' hrs</div>';


		}

		$s = date_create_from_format('Y-m-d',$row->start_date)->format('d F Y');
		$e = date_create_from_format('Y-m-d',$row->end_date)->format('d F Y');
		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('Time Sheet Report');
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);
		/*getting the logo*/
		//$user = $this->session->userdata('user');
		

		$wp_company_id = $user_info->company_id;

		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
		$this->db->where('wp_company.id', $wp_company_id);
		$wpdata = $this->db->get('wp_company')->row();

		$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;
		$header_html = '<table width="100%">
						<tr>
						<td><img src="'.$logo.'" width="223"></td>
						<td style="text-align:right">
						<span style="font-size:20px; font-weight:bold">TIME SHEET REPORT</span><br />
						<span style="font-size:16px;">'.$pdf_name.'</span><br/>
						<span style="font-size:12px;">'.$s.' - '.$e.'</span>
						</td>
						</tr>
						</table>';

		$pdf->headerHtml = $header_html;
		$pdf->SetHeaderMargin(5);
		$pdf->SetTopMargin(35);
		$pdf->AddPage();
		$pdf->setFontSize(9);
		$pdf->writeHTML($tbl);
		$pdf->Output('Time-Sheet-Report.pdf', 'I');
	}

	public function staff_time_sheets($start_day = ''){

		if($start_day == ''){
			$date_range_condition = "and (day between (select max(start_date) from ts_weeks) and (select max(end_date) from ts_weeks))";
		}else{
			$day = $this->db->query("select * from ts_weeks where start_date = '{$start_day}' limit 0, 1")->row();
			if(!$day){
				$this->load->view('errors/html/error_general',array(
					'heading' => 'No time sheet exist for this week.',
					'message' => null
				));
				return;
			}
			$date_range_condition = "and (day between '{$day->start_date}' and '{$day->end_date}')";
		}

		$user = $this->session->userdata('user');
		$data['title'] = 'Staff Time Sheets';
		$data['user'] = $user;

		
		if($this->user_app_role != "manager" AND $this->user_app_role != "admin") return;

		

		$colors = array('#BE2126', '#D82B38', '#F5803B', '#FBB83A', '#F9C573', '#FFDCA9', '#FFE9CC');
		$color_index = 0;
		$project_colors = array();

		/*getting all staff's this week's entry*/
		$sql = "select
					uid,
					username,
					project_name,
					project_id,
					SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time
				from ts_timesheet_entries te JOIN project on te.project_id = project.id JOIN users on user_id = uid
				where te.user_id in (select uid from users where company_id = {$user->company_id})
					  {$date_range_condition}
					  and users.company_id = {$this->wp_company_id}
				GROUP BY user_id,project_id
				ORDER BY username, project_name";

		$entries = $this->db->query($sql)->result();
		$arr = array();
		foreach($entries as $e){
			if(!array_key_exists($e->uid,$arr)){
				$arr[$e->uid]['total_time']=0;
			}
			$arr[$e->uid]['user_id'] = $e->uid;
			$arr[$e->uid]['total_time'] += $e->total_time;
			$arr[$e->uid]['username'] = $e->username;
			$arr[$e->uid]['projects'][$e->project_id] = array('project_name'=>$e->project_name, 'project_time'=>$e->total_time);

			/*project colors*/
			if($color_index == 7){
				$color_index = 0;
			}
			if(!array_key_exists($e->project_id,$project_colors)){
				$project_colors[$e->project_id] = $colors[$color_index++];
			}
		}

		$data['staff_list'] = $this->timesheet_model->get_staff_list()->result();
		$data['times'] = $arr;
		$data['colors'] = $project_colors;
		$data['maincontent'] = $this->load->view('staff_time_sheets',$data,true);

		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);


	}

	function staff_weekly_project_time($uid, $pid, $week_start_day = ''){

		if($this->user_app_role != "manager") return;

		if($week_start_day == ''){
			$date_range_condition = "and (day between (select max(start_date) from ts_weeks) and (select max(end_date) from ts_weeks))";
		}else{
			$day = $this->db->query("select * from ts_weeks where start_date = '{$week_start_day}' limit 0, 1")->row();
			if(!$day){
				$this->load->view('errors/html/error_general',array(
					'heading' => 'No time sheet exist for this week.',
					'message' => null
				));
				return;
			}
			$date_range_condition = "and (day between '{$day->start_date}' and '{$day->end_date}')";
		}

		$sql = "select day, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time, GROUP_CONCAT(note ORDER BY day SEPARATOR '<br>') notes
				from ts_timesheet_entries te, users
				where
					  	  te.project_id = $pid
					  and te.user_id = users.uid
					  {$date_range_condition}
					  and users.company_id = $this->wp_company_id
					  and te.user_id = $uid
			  	GROUP BY day
			  	ORDER BY day, te.id
					  ";
		$times = $this->db->query($sql)->result();
		$data['times'] = $times;
		$data['username'] = $this->db->query('select username from users where uid = '.$uid)->row()->username;
		$data['project_name'] = $this->db->query('select project_name from project where id = '.$pid)->row()->project_name;
		$this->load->view('includes/modal_header');
		$this->load->view('staff_weekly_project_time',$data);

	}

	function project_hours($start_day = ''){
		$post = $this->input->post();

		if($post)
		{
			$project_id = $post['project_list'];
			$start_date = date("Y-m-d",strtotime($post['start_date']));
			$end_date = date("Y-m-d",strtotime($post['end_date']));
			if($project_id != '')
			{
				/*$sql = "SELECT day, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time
					from ts_timesheet_entries te WHERE te.project_id = $project_id AND ( day BETWEEN '$start_date' and '$end_date')";
				$hours = $this->db->query($sql)->result();
				$data['total_time'] = $hours[0]->total_time;*/
				
			    $sql1 = "SELECT day, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time
					from ts_timesheet_entries te
					LEFT JOIN users_application uapp ON te.user_id=uapp.user_id 
					WHERE uapp.application_id=7 AND uapp.application_role_id=2 AND te.project_id = $project_id AND ( day BETWEEN '$start_date' and '$end_date')";
				$hours_ma = $this->db->query($sql1)->result();
				$data['total_time_ma'] = $hours_ma[0]->total_time;
				$sql2 = "SELECT day, SUM(TIME_TO_SEC(TIMEDIFF(finish_time,start_time))/60 - break_time) total_time
					from ts_timesheet_entries te
					LEFT JOIN users_application uapp ON te.user_id=uapp.user_id 
					WHERE uapp.application_id=7 AND uapp.application_role_id=3 AND te.project_id = $project_id AND ( day BETWEEN '$start_date' and '$end_date')";
				$hours_con = $this->db->query($sql2)->result();
				$data['total_time_con'] = $hours_con[0]->total_time;
			}
		}

		$data['title'] = "Project Hours";
		$data['staff_list'] = $this->timesheet_model->get_staff_list()->result();
		
		$data['maincontent'] = $this->load->view('project_hours',$data,true);

		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);

	}

	function request_leave_add(){
		$post = $this->input->post();

		if($this->input->post('submit'))
		{
			$date_form = date('Y-m-d',strtotime($post['date_form']));
			$date_to = date('Y-m-d',strtotime($post['date_to']));
			$note = $post['note'];
			$add = array(
				'company_id' => $this->wp_company_id,  
	            'request' => $post['request'],    
				'date_form' => $date_form,
				'date_to' => $date_to,
				'note' => $note,				
	            'created' => date("Y-m-d"),
	            'created_by' => $this->user_id
	        );
			$this->timesheet_model->request_leave_add($add); 

			//$row = $this->timesheet_model->request_leave_check($this->user_id,$date_form)->row(); 
			//if($row){				
            	//redirect("weekly-timesheet?error=1&date_form=$row->date_form&date_to=$row->date_to&note=$row->note");
			//}else{
				//$this->timesheet_model->request_leave_add($add); 
				//redirect("weekly-timesheet");
			//}

			$results = $this->timesheet_model->request_leave_send_manager_email($this->wp_company_id)->result();

			foreach($results as $result){

				$to = $result->email;
				$subject ='Request Leave from '.$this->username;
	
				$headers = "From: ".$this->email . "\r\n";
				$headers .= "Reply-To: ". $this->email . "\r\n";
				$headers .= "MIME-Version: 1.0\r\n";
				$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
			
				$message= '';
		        $message .= '<html><body>';	
				$message .= "Hello, <strong>".$result->username."</strong><br>";
		        $message .= "Reason : ".$post['request']."<br>";
				$message .= "Date form: " . $date_form. "<br>";
				$message .= "Date to: " . $date_to. "<br>";
		        $message .= "Comments: " . $note;
				$message .= "</body></html>";	
	
		        mail($to, $subject, $message, $headers);

			}
			redirect("weekly-timesheet");
		}
	}

	function load_project_task_by_commercial($project_id,$task_id){		
		$results = $this->timesheet_model->load_project_task_by_commercial($project_id)->result();
		
		$row = '<option value="">--Select Task--</option>';
		foreach($results as $result){
			if($task_id==$result->request_no){
				$selected = 'selected=""';
			}else{
				$selected = '';
			}
			$row .= '<option '.$selected.' value="'.$result->request_no.'">'.$result->request_title.'</option>';
		}
		echo $row;
	}

	//task #4139
	public function revert_submit(){
		if($this->user_app_role != 'manager' && $this->user_app_role != 'admin') exit;
		/*all staffs*/
		$data['title'] = "Revert Submit";
		$data['staffs'] = $this->db->get_where('users',array('company_id' => $this->wp_company_id))->result();

		if($this->input->post('week_id')){
			$user = $this->db->get_where('users',array('company_id' => $this->wp_company_id, 'uid'=> $this->input->post('staff_id')), 0, 1)->row();
			$this->db->where('user_id', $user->uid);
			$this->db->where('week_id', $this->input->post('week_id'));
			$this->db->delete('ts_submitted_weeks');
		}
		
		if($this->input->post('staff_id')){
			$data['staff_id'] = $this->input->post('staff_id');
			$this->db->select('ts_weeks.*');
			$this->db->join('ts_weeks','ts_weeks.id = ts_submitted_weeks.week_id');
			$data['submitted_weeks'] = $this->db->get_where('ts_submitted_weeks',array('user_id' => $this->input->post('staff_id')))->result();
		}

		$data['maincontent'] = $this->load->view('revert_submit',$data,true);

		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	}

}

