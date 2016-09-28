<?php 
class Consent extends CI_Controller
{
	private $table_consent = 'consent';
	public function __construct()
	{
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library(array('table','form_validation', 'session'));
		$this->load->model('consent_model','',TRUE);
		$this->load->library('wbs_helper');
		$this->load->library('Pdf');
	}
	public function check_job_no()
	{
		$get = $_GET;	
		$this->consent_model->check_job_no($get);
	}
	public function consent_list()
	{

		if($this->uri->segment(3)=='month_add_success')
			$data['message'] = 'Month added successfully';
		else if ($this->uri->segment(3)=='month_add_unsuccess')
			$data['message'] = 'Month not added';
		else
			$data['message'] = '';

		$user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
		$data['title'] = 'Consent List';	
		$data['active_tabs'] = $this->consent_model->get_active_tabs($user_id);
		$data['maincontent'] = $this->load->view('consent/consent_list',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	} 
	public function job_add()
	{
		$month_id = $_POST['year'].$_POST['month'];
		$job_data = array(
			'month_id' => $month_id,
			'job_no' => $_POST['job_no'],
			'consent_name' => $_POST['consent_name'],
			'notes' =>$_POST['notes']
		);
		$this->consent_model->consent_save($job_data);
		redirect('consent/consent_list');
	}
	public function consent_add() 
	{ 
        $user=  $this->session->userdata('user');          
        $user_id =$user->uid; 
        $user_name= $user->name;
        $user_email= $user->email;

		$last_consent_no=  $this->consent_model->get_consent_no();
		$job_no = $last_consent_no+1; 

		$unconditional_date = ($_GET['unconditional_date']) ? $this->wbs_helper->to_mysql_date($_GET['unconditional_date']) : '' ;
      	$approval_date = ($_GET['approval_date']) ? $this->wbs_helper->to_mysql_date($_GET['approval_date']) : '' ; 
		$pim_logged = ($_GET['pim_logged']) ? $this->wbs_helper->to_mysql_date($_GET['pim_logged']) : '' ;  
		$in_council = ($_GET['in_council']) ? $this->wbs_helper->to_mysql_date($_GET['in_council']) : '' ; 
		$consent_out = ($_GET['consent_out']) ? $this->wbs_helper->to_mysql_date($_GET['consent_out']) : '' ; 
		$drafting_issue_date = ($_GET['drafting_issue_date']) ? $this->wbs_helper->to_mysql_date($_GET['drafting_issue_date']) : '' ;
		$date_logged = ($_GET['date_logged']) ? $this->wbs_helper->to_mysql_date($_GET['date_logged']) : '' ;
		$date_issued = ($_GET['date_issued']) ? $this->wbs_helper->to_mysql_date($_GET['date_issued']) : '' ;
		$handover_date = ($_GET['handover_date']) ? $this->wbs_helper->to_mysql_date($_GET['handover_date']) : '' ;
		$consentDateJobChecked = ($_GET['consentDateJobChecked']) ? $this->wbs_helper->to_mysql_date($_GET['consentDateJobChecked']) : '' ;

		// jobs allocation to PM condition
		if($unconditional_date && $_GET['project_manager'])
		{
			$jobs_to_be_allocated_to_pm = 'Allocated';
		}
		elseif($unconditional_date && !$_GET['project_manager'] )
		{
			$jobs_to_be_allocated_to_pm = 'PM Required';
		}
		else
		{
			$jobs_to_be_allocated_to_pm = '';
		}

		// In council formula
		if($date_logged != '')
		{
			$in_council = 1;
		}

		// consent out formula
		if($date_issued != '')
		{
			$consent_out = 1;
		}

		// Action Required Formula 
		$action_required = $_GET['action_required'];

		$now = date('Y-m-d');
 		$today_time = strtotime($now);

		if($unconditional_date != '')
		{
 			$unconditional_date_time = strtotime($cdate);
		}
		else
		{
			$unconditional_date_time = 0;
		}

		if($unconditional_date != '' && $today_time > $unconditional_date_time && $_GET['consent_by'] == 0 )
		{
			$action_required = "Urgent";
		}
		else
		{
			$action_required = "";
		}

		$consent_data = array(
                    'job_no ' => $_GET['job_no'],  
					'month_id ' => $_GET['month_id'],                   
                    'consent_name' =>urldecode($_GET['consent_name']),
                    'consent_color' =>$_GET['consent_color'],	
                    'design' =>$_GET['consent_design'],
                    'approval_date' =>  $approval_date,
                    'pim_logged' =>  $pim_logged,
                    'in_council' =>  $in_council,
                    'consent_out' =>  $consent_out,
                    'drafting_issue_date' =>  $drafting_issue_date,
                	'consent_by' =>$_GET['consent_by'],
                	'action_required' => $action_required,
                	'council' =>$_GET['council'],                
                 	'bc_number' => $_GET['bc_number'], 
	                'no_units' => $_GET['no_units'],
	                'contract_type' => $_GET['contract_type'],
	                'type_of_build' => $_GET['type_of_build'],
	                'variation_pending' => $_GET['variation_pending'],
	                'foundation_type' => $_GET['foundation_type'],
	                'date_logged' => $date_logged,
	                'date_issued' => $date_issued,
	                'order_site_levels' => $_GET['order_site_levels'], 
	                'order_soil_report' => $_GET['order_soil_report'], 
	                'septic_tank_approval' => $_GET['septic_tank_approval'], 
	                'dev_approval' => $_GET['dev_approval'], 
	                'project_manager' => $_GET['project_manager'], 
	                'jobs_to_be_allocated_to_pm' => $jobs_to_be_allocated_to_pm,  
	                'unconditional_date' => $unconditional_date, 
	                'handover_date' => $handover_date, 
	                'builder' => $_GET['builder'], 
	                'consent_out_but_no_builder' => $_GET['consent_out_but_no_builder'],
					'lbp' => $_GET['consentlbp'],
					'date_job_checked' =>$consentDateJobChecked,
	                'ordering' => $_GET['ordering']
                ); 	

                $this->consent_model->consent_save($consent_data);
                echo $job_no;
    }
	public function submit_consent()
	{
 
		$last_consent_no=  $this->consent_model->get_consent_no();
		$consent_data = array(
			'job_no ' => $last_consent_no+1,                    
			'consent_name' =>$_POST['consent_name'],
			'consent_color' =>$_POST['color'],	
			'design' =>$_POST['design'],
			'approval_date' =>  $this->wbs_helper->to_mysql_date($_POST['approval_date']),
			'bc_number' => $_POST['bc_number']                 
			); 	

			$this->consent_model->consent_save($consent_data);
			redirect('consent/consent_list', 'refresh');
	}
	function consent_update($job_no, $field_name, $filed_value, $formate)
	{

		if($formate == 1)
		{
			$filed_value = urldecode($filed_value);
			$filed_values = str_replace("-","/",$filed_value);
			$dates = explode("/",$filed_values);
			$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
			$filed_value = date("Y-m-d", strtotime($filed_values)); 
		}
		else
		{
			$filed_value = urldecode($filed_value);
		}
		
		$update_data = array(
							$field_name => 	$filed_value
						);

		$this->consent_model->update_consent($job_no, $update_data);
	}
	public function month_add()
	{
		$month_data = array(
			'month' => $_POST['month_list'],
			'year' => $_POST['year_list']
		);
		$this->consent_model->add_month($month_data);
	}
	public function check_year_month($year,$month)
	{
		$this->consent_model->check_year_month($year,$month);
	}
	public function show_report()
	{
		$data['title'] = 'Consent List';
		
		$start_date = $_POST['report_date_from'];

		$field_check = array();
		if(isset($_POST['consent_fields']))
		{
			$field_check[] = $_POST['consent_fields']; 
		}

		$ss = serialize($field_check); 
		

		if($start_date)
		{
			$start_date = str_replace("-","/",$start_date);
			$dates = explode("/",$start_date);
			$start_date = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
			$start_date = date("Y-m-d", strtotime($start_date));
		}
		
		$end_date = $_POST['report_date_to'];
		if($end_date)
		{
			$end_date = str_replace("-","/",$end_date);
			$dates = explode("/",$end_date);
			$end_date = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
			$end_date = date("Y-m-d", strtotime($end_date)); 
		}
		
		$keywords = $_POST['keywords'];
		
		$user_info = $this->consent_model->user_option();
		
		$user =  $this->session->userdata('user');   
		$user_group_id = $user->group_id;
		
		$user_permission_type = $this->consent_model->get_user_permission_type($user_group_id);
					
		$consent_info = $this->consent_model->get_consent_report_by_date($start_date,$end_date,$keywords,$ss);
		

		$message = '<table id="table" class="consent_table tablesorter" border="0" cellpadding="0" cellspacing="0">';
		$message .= '<thead>';
		$message .= '<th style="width:1%">Job No.</th>';
				 if($user_permission_type[0]->display_type == 1){  
						$message .='<th style="width:3%;">Consent</th>';
				  }  
				  if($user_permission_type[1]->display_type == 1){  
				$message .='<th style="width:1%">Design</th>';
				  }  
				  if($user_permission_type[2]->display_type == 1){  
				$message .='<th style="width:2%">Approval Date</th>';
				  }  
				  if($user_permission_type[3]->display_type == 1){  
				$message .='<th style="width:2%">Pim <br> Lodged</th>';
				  }  
				  if($user_permission_type[4]->display_type == 1){  
				$message .='<th style="width:1%">In Council</th>';
				  }  
				  if($user_permission_type[5]->display_type == 1){  
				$message .='<th style="width:1%">Consent<br>Out</th>';
				  }  
				  if($user_permission_type[6]->display_type == 1){  
				$message .='<th style="width:2%">Drafting <br>Issue Date</th>';
				  }  
				  if($user_permission_type[7]->display_type == 1){  
				$message .='<th style="width:2%">Consent<br>by</th>';
				  }  
				  if($user_permission_type[8]->display_type == 1){  
				$message .='<th style="width:2%">Action<br>Required</th>';
				  }  
				  if($user_permission_type[9]->display_type == 1){  
				$message .='<th style="width:2%">Council</th>';
				  }  		
				  if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">LBP</th>';
				  }  
				  if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">Date Job Checked</th>';
				  }  
				  if($user_permission_type[10]->display_type == 1){  
				$message .='<th style="width:2%">Bc Number</th>';
				  }  
				  if($user_permission_type[11]->display_type == 1){  
				$message .='<th style="width:1%">No. Units</th>';
				  }  
				  if($user_permission_type[12]->display_type == 1){  
				$message .='<th style="width:1%">Contract Type</th>';
				  }  
				  if($user_permission_type[13]->display_type == 1){  
				$message .='<th style="width:1%">Type of <br>Build</th>';
				  }  
				  if($user_permission_type[14]->display_type == 1){  
				$message .='<th style="width:2%">Variation <br>Pending</th>';
				  }  
				  if($user_permission_type[15]->display_type == 1){  
				$message .='<th style="width:2%">Foundation<br>Type</th>';
				  }  
				  if($user_permission_type[16]->display_type == 1){  
				$message .='<th style="width:2%">Consent<br>Lodged</th>';
				  }  
				  if($user_permission_type[17]->display_type == 1){  
				$message .='<th style="width:2%">Consent <br>Issued</th>';
				  }  
				  if($user_permission_type[17]->display_type == 1){  
				$message .='<th style="width:2%">Actual <br>Date Issued</th>';
				  }  
				  if($user_permission_type[18]->display_type == 1){  
				$message .='<th style="width:1%">Days in <br>Council</th>';
				  }  
				  if($user_permission_type[19]->display_type == 1){  
				$message .='<th style="width:2%">Order Site <br>Levels</th>';
				  }  
				  if($user_permission_type[20]->display_type == 1){  
				$message .='<th style="width:2%">Order Soil <br>Report</th>';
				  }  
				  if($user_permission_type[21]->display_type == 1){  
				$message .='<th style="width:2%">Septic Tank <br>Approval</th>';
				  }  
				  if($user_permission_type[22]->display_type == 1){  
				$message .='<th style="width:2%">Dev Approval</th>';
				  }  
				  if($user_permission_type[22]->display_type == 1){  
				$message .='<th style="width:2%">Landscape</th>';
				  }  
				  if($user_permission_type[22]->display_type == 1){  
				$message .='<th style="width:2%">MSS</th>';
				  }  
				  if($user_permission_type[23]->display_type == 1){  
				$message .='<th style="width:2%">Project Manager</th>';
				  }  
				  if($user_permission_type[25]->display_type == 1){  
				$message .='<th style="width:2%">Unconditional <br>Date</th>';
				  }  
				  if($user_permission_type[26]->display_type == 1){  
				$message .='<th style="width:2%">Handover Date</th>';
				  }  
				  if($user_permission_type[27]->display_type == 1){  
				$message .='<th style="width:2%">Builder</th>';
				  }  
				  if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">Builder Status</th>';
				  }  
				  if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">Title Date</th>';
				  }  
				  if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">Settlement Date</th>';
				  }  
				  if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">Notes</th>';
				  }  
				
		$message .='</thead>';
		
		$message .= "<tbody>";
	for($i=0;$i<count($consent_info);$i++)
	{
		$message .= '<tr><td>'.$consent_info[0]->job_no.'</td>';
		
		

		if($user_permission_type[0]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->consent_name.'</td>';
				}
				if($user_permission_type[1]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->design.'</td>';
				}
				if($user_permission_type[2]->display_type == 1){
					if($consent_info[$i]->approval_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->approval_date).'</td>';
					}
				}
				if($user_permission_type[3]->display_type == 1){
					if($consent_info[$i]->pim_logged == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->pim_logged).'</td>';
					}
				}
				if($user_permission_type[4]->display_type == 1){
					if($consent_info[$i]->in_council == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->in_council).'</td>';
					}
				}
				if($user_permission_type[5]->display_type == 1){
					if($consent_info[$i]->consent_out == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->consent_out).'</td>';
					}
				}
				if($user_permission_type[6]->display_type == 1){
					if($consent_info[$i]->drafting_issue_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->drafting_issue_date).'</td>';
					}
				}
				if($user_permission_type[7]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->consent_by.'</td>';
				}
				if($user_permission_type[8]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->action_required.'</td>';
				}
				if($user_permission_type[9]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->council.'</td>';
				}
				if($user_permission_type[9]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->lbp.'</td>';
				}
				if($user_permission_type[6]->display_type == 1){
					if($consent_info[$i]->date_job_checked == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->date_job_checked).'</td>';
					}
				}
				if($user_permission_type[10]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->bc_number.'</td>';
				}
				if($user_permission_type[11]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->no_units.'</td>';
				}
				if($user_permission_type[12]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->contract_type.'</td>';
				}
				if($user_permission_type[13]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->type_of_build.'</td>';
				}
				if($user_permission_type[14]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->variation_pending.'</td>';
				}
				if($user_permission_type[15]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->foundation_type.'</td>';
				}
				if($user_permission_type[16]->display_type == 1){
					if($consent_info[$i]->date_logged == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->date_logged).'</td>';
					}
				}
				if($user_permission_type[16]->display_type == 1){
					if($consent_info[$i]->date_issued == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->date_issued).'</td>';
					}
				}
				if($user_permission_type[17]->display_type == 1){
					if($consent_info[$i]->actual_date_issued == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->actual_date_issued).'</td>';
					}
				}
				if($user_permission_type[18]->display_type == 1){
					$difference = abs(strtotime($consent_info[$i]->date_issued) - strtotime($consent_info[$i]->date_logged));
					$days = floor(($difference )/ (60*60*24));
					$message .= '<td>'.$days.'</td>';
				}
				if($user_permission_type[19]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->order_site_levels.'</td>';
				}
				if($user_permission_type[20]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->order_soil_report.'</td>';
				}
				if($user_permission_type[21]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->septic_tank_approval.'</td>';
				}
				if($user_permission_type[22]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->dev_approval.'</td>';
				}
				if($user_permission_type[27]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->landscape.'</td>';
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->mss.'</td>';
				}
				if($user_permission_type[23]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->project_manager.'</td>';
				}
				if($user_permission_type[25]->display_type == 1){
					if($consent_info[$i]->unconditional_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->unconditional_date).'</td>';
					}
				}
				if($user_permission_type[26]->display_type == 1){
					if($consent_info[$i]->handover_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->handover_date).'</td>';
					}
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->builder.'</td>';
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->builder.'</td>';
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]-> 	title_date.'</td>';
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->settlement_date.'</td>';
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->notes.'</td>';
				}
			$message .= "</tr>";
		
		}
		
		$message .= "</tbody>";
	
		
		
		$data['report_message'] = $message;
		
		$data['maincontent'] = $this->load->view('consent/consent_report',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
		
	}
	
	public function archive()
	{
		$data['title'] = 'Consent Archive List';
		$arc_date = $this->consent_model->get_archive_date();
		$user_info = $this->consent_model->user_option();
		$user =  $this->session->userdata('user');   
		$user_group_id = $user->group_id;
		$user_permission_type = $this->consent_model->get_user_permission_type($user_group_id);			
		$message = '';
		for($k=0;$k<count($arc_date);$k++)
		{
			$month = $arc_date[$k]->month;
			if($month<10){$month = '0'.$month;}
			$year = $arc_date[$k]->year;
			$month_id = $year.$month;
			$consent_info = $this->consent_model->get_consent_info_by_monthid($month_id);
			
			$month_form = $year.'-'.$month.'-'.'00';
			$month_str = date("F Y", strtotime($month_form));
			$message .= '<li class="accordion"><div class="accordion-header"><h3 style="height:18px; clear:both; margin: 0; padding:0;"><div style="float:left; width:40%; height:18px; color:#181818;font-size: 16px;">'.$month_str.'</div><div class="accordion-icon"></div></h3></div><div class="accordion-content" id="consent"><table id="table" class="consent_table" border="0" cellpadding="0" cellspacing="0">';
			$message .= '<thead>';
			$message .= '<th style="width:1%">Job No.</th>';
			 if($user_permission_type[0]->display_type == 1){  
				$message .='<th style="width:3%;">Consent</th>';
			}  
			if($user_permission_type[1]->display_type == 1){  
				$message .='<th style="width:1%">Design</th>';
			}  
			if($user_permission_type[2]->display_type == 1){  
				$message .='<th style="width:2%">Approval Date</th>';
			}  
			if($user_permission_type[3]->display_type == 1){  
				$message .='<th style="width:2%">Pim <br> Lodged</th>';
			}  
			if($user_permission_type[4]->display_type == 1){  
				$message .='<th style="width:1%">In Council</th>';
			}  
			if($user_permission_type[5]->display_type == 1){  
				$message .='<th style="width:1%">Consent<br>Out</th>';
			}  
			if($user_permission_type[6]->display_type == 1){  
				$message .='<th style="width:2%">Drafting <br>Issue Date</th>';
			}  
			if($user_permission_type[7]->display_type == 1){  
				$message .='<th style="width:2%">Consent<br>by</th>';
			}  
			if($user_permission_type[8]->display_type == 1){  
				$message .='<th style="width:2%">Action<br>Required</th>';
			}  
			if($user_permission_type[9]->display_type == 1){  
				$message .='<th style="width:2%">Council</th>';
			}  		
			if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">LBP</th>';
			}  
			if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">Date Job Checked</th>';
			}  
			if($user_permission_type[10]->display_type == 1){  
				$message .='<th style="width:2%">Bc Number</th>';
			}  
			if($user_permission_type[11]->display_type == 1){  
				$message .='<th style="width:1%">No. Units</th>';
			}  
			if($user_permission_type[12]->display_type == 1){  
				$message .='<th style="width:1%">Contract Type</th>';
			}  
			if($user_permission_type[13]->display_type == 1){  
				$message .='<th style="width:1%">Type of <br>Build</th>';
			}  
			if($user_permission_type[14]->display_type == 1){  
				$message .='<th style="width:2%">Variation <br>Pending</th>';
			}  
			if($user_permission_type[15]->display_type == 1){  
				$message .='<th style="width:2%">Foundation<br>Type</th>';
			}  
			if($user_permission_type[16]->display_type == 1){  
				$message .='<th style="width:2%">Consent<br>Lodged</th>';
			}  
			if($user_permission_type[17]->display_type == 1){  
				$message .='<th style="width:2%">Consent <br>Issued</th>';
			}  
			if($user_permission_type[17]->display_type == 1){  
				$message .='<th style="width:2%">Actual <br>Date Issued</th>';
			}  
			if($user_permission_type[18]->display_type == 1){  
				$message .='<th style="width:1%">Days in <br>Council</th>';
			}  
			if($user_permission_type[19]->display_type == 1){  
				$message .='<th style="width:2%">Order Site <br>Levels</th>';
			}  
			if($user_permission_type[20]->display_type == 1){  
				$message .='<th style="width:2%">Order Soil <br>Report</th>';
			}  
			if($user_permission_type[21]->display_type == 1){  
				$message .='<th style="width:2%">Septic Tank <br>Approval</th>';
			}  
			if($user_permission_type[22]->display_type == 1){  
				$message .='<th style="width:2%">Dev Approval</th>';
			}  
			if($user_permission_type[22]->display_type == 1){  
				$message .='<th style="width:2%">Landscape</th>';
			}  
			if($user_permission_type[22]->display_type == 1){  
				$message .='<th style="width:2%">MSS</th>';
			}  
			if($user_permission_type[23]->display_type == 1){  
				$message .='<th style="width:2%">Project Manager</th>';
			}  
			if($user_permission_type[25]->display_type == 1){  
				$message .='<th style="width:2%">Unconditional <br>Date</th>';
			}  
			if($user_permission_type[26]->display_type == 1){  
				$message .='<th style="width:2%">Handover Date</th>';
			}  
			if($user_permission_type[27]->display_type == 1){  
				$message .='<th style="width:2%">Builder</th>';
			}  
			if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">Builder Status</th>';
			}  
			if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">Title Date</th>';
			}  
			if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">Settlement Date</th>';
			}  
			if($user_permission_type[28]->display_type == 1){  
				$message .='<th style="width:2%">Notes</th>';
			}  	
			$message .='</thead>';
			$message .= "<tbody>";
			for($i=0; $i<count($consent_info); $i++)
			{
				$message .= '<tr><td>'.$consent_info[0]->job_no.'</td>';
				if($user_permission_type[0]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->consent_name.'</td>';
				}
				if($user_permission_type[1]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->design.'</td>';
				}
				if($user_permission_type[2]->display_type == 1){
					if($consent_info[$i]->approval_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->approval_date).'</td>';
					}
				}
				if($user_permission_type[3]->display_type == 1){
					if($consent_info[$i]->pim_logged == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->pim_logged).'</td>';
					}
				}
				if($user_permission_type[4]->display_type == 1){
					if($consent_info[$i]->in_council == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->in_council).'</td>';
					}
				}
				if($user_permission_type[5]->display_type == 1){
					if($consent_info[$i]->consent_out == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->consent_out).'</td>';
					}
				}
				if($user_permission_type[6]->display_type == 1){
					if($consent_info[$i]->drafting_issue_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->drafting_issue_date).'</td>';
					}
				}
				if($user_permission_type[7]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->consent_by.'</td>';
				}
				if($user_permission_type[8]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->action_required.'</td>';
				}
				if($user_permission_type[9]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->council.'</td>';
				}
				if($user_permission_type[9]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->lbp.'</td>';
				}
				if($user_permission_type[6]->display_type == 1){
					if($consent_info[$i]->date_job_checked == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->date_job_checked).'</td>';
					}
				}
				if($user_permission_type[10]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->bc_number.'</td>';
				}
				if($user_permission_type[11]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->no_units.'</td>';
				}
				if($user_permission_type[12]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->contract_type.'</td>';
				}
				if($user_permission_type[13]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->type_of_build.'</td>';
				}
				if($user_permission_type[14]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->variation_pending.'</td>';
				}
				if($user_permission_type[15]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->foundation_type.'</td>';
				}
				if($user_permission_type[16]->display_type == 1){
					if($consent_info[$i]->date_logged == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->date_logged).'</td>';
					}
				}
				if($user_permission_type[16]->display_type == 1){
					if($consent_info[$i]->date_issued == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->date_issued).'</td>';
					}
				}
				if($user_permission_type[17]->display_type == 1){
					if($consent_info[$i]->actual_date_issued == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->actual_date_issued).'</td>';
					}
				}
				if($user_permission_type[18]->display_type == 1){
					$difference = abs(strtotime($consent_info[$i]->date_issued) - strtotime($consent_info[$i]->date_logged));
					$days = floor(($difference )/ (60*60*24));
					$message .= '<td>'.$days.'</td>';
				}
				if($user_permission_type[19]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->order_site_levels.'</td>';
				}
				if($user_permission_type[20]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->order_soil_report.'</td>';
				}
				if($user_permission_type[21]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->septic_tank_approval.'</td>';
				}
				if($user_permission_type[22]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->dev_approval.'</td>';
				}
				if($user_permission_type[27]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->landscape.'</td>';
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->mss.'</td>';
				}
				if($user_permission_type[23]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->project_manager.'</td>';
				}
				if($user_permission_type[25]->display_type == 1){
					if($consent_info[$i]->unconditional_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->unconditional_date).'</td>';
					}
				}
				if($user_permission_type[26]->display_type == 1){
					if($consent_info[$i]->handover_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->handover_date).'</td>';
					}
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->builder.'</td>';
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->builder.'</td>';
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]-> 	title_date.'</td>';
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->settlement_date.'</td>';
				}
				if($user_permission_type[28]->display_type == 1){
					$message .= '<td>'.$consent_info[$i]->notes.'</td>';
				}
				
				$message .= "</tr>";
		
			}
			$message .= "</tbody></table>";
			$message .= "</div></li>";
			
		}
		
		
		$data['report_message'] = $message;
		
		$data['maincontent'] = $this->load->view('consent/consent_archive',$data,true);
	
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
		
	}
	
	
	public function consent_list_print()
	{
		
		$data['title'] = 'Consent List';		
		$data['maincontent'] = $this->load->view('consent/consent_list_print',$data,true);
				
		$this->load->view('includes/header_print',$data);
		$this->load->view('includes/home_print',$data);
		$this->load->view('includes/footer_print',$data);
		
	}
	
	public function consent_list_email($ids)
	{
		//$to = 'connor@williamsbusiness.co.nz';
		//$from= 'alimuls@gmail.com';

		//$subject = 'Consent Management System';

		//$headers = "From: " . $from . "\r\n";
		//$headers .= "Reply-To: ". $from . "\r\n";
		//$headers .= "CC: mamunjava@gmail.com\r\n";
		//$headers .= "MIME-Version: 1.0\r\n";
		//$headers .= "Content-Type: text/html; charset=ISO-8859-1\r\n";
		
		//$message = '<html><body>';
					
		//$data['title'] = 'Consent List';
		
		$user_info = $this->consent_model->user_option();
		
		$total_months = 5;
		$now = date('Y-m-d');
		$today_time = strtotime($now);
		$last_month = date("F Y", strtotime("-2 months"));

		$user =  $this->session->userdata('user');   
		$user_group_id = $user->group_id;
		
		$user_permission_type = $this->consent_model->get_user_permission_type($user_group_id);
		
		$segment_print = $ids;
		$segment_print_arr = split ("-", $segment_print);		
		//print_r($segment_print_arr);  
		
		for($p=0; $p <= $total_months; $p++)
		{
			for($a = 0; $a < count($segment_print_arr); $a++)
			{
				if($segment_print_arr[$a] == $p )	
				{
			  	
					$month = date("F Y", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));
					$month_start_date = date("Y-m-d", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));	
					$month_last_day = date("Y-m-t", strtotime($month_start_date));;
					
					$consent_info = $this->consent_model->get_consent_info($month_start_date,$month_last_day);
					$message = $month.'%0D%0A';
					
					for($t = 0; $t < count($consent_info) ; $t++)
					{										

						$message .= 'Job Number : '.$consent_info[$t]->job_no.'%0D%0A';

						if($user_permission_type[0]->display_type == 1)
						{
							$message .= 'Consent : '.$consent_info[$t]->consent_name.'%0D%0A';
						}

						if($user_permission_type[1]->display_type == 1)
						{
							$message .= 'Design : '.$consent_info[$t]->design.'%0D%0A';
						}

						if($user_permission_type[2]->display_type == 1)
						{
							if($consent_info[$t]->approval_date == '0000-00-00')
							{
								$message .= 'Approval Date : %0D%0A';
							}
							else
							{
								$message .= 'Approval Date : '.$this->wbs_helper->to_report_date($consent_info[$t]->approval_date).'%0D%0A';
							}
						}

						if($user_permission_type[3]->display_type == 1)
						{
							if($consent_info[$t]->pim_logged == '0000-00-00')
							{
								$message .= 'Pim Logged : %0D%0A';
							}
							else
							{
								$message .= 'Pim Logged : '.$this->wbs_helper->to_report_date($consent_info[$t]->pim_logged).'%0D%0A';
							}
						}

						if($user_permission_type[4]->display_type == 1)
						{
							if($consent_info[$t]->in_council == '0000-00-00')
							{
								$message .= 'In Council : %0D%0A';
							}
							else
							{
								$message .= 'In Council : '.$this->wbs_helper->to_report_date($consent_info[$t]->in_council).'%0D%0A';
							}
						}	

						if($user_permission_type[5]->display_type == 1)
						{
							if($consent_info[$t]->consent_out == '0000-00-00')
							{
								$message .= 'Consent Out : %0D%0A';
							}
							else
							{
								$message .= 'Consent Out : '.$this->wbs_helper->to_report_date($consent_info[$t]->consent_out).'%0D%0A';
							}
						}

						if($user_permission_type[6]->display_type == 1)
						{
							if($consent_info[$t]->drafting_issue_date == '0000-00-00')
							{
								$message .= 'Drafting Issue Date : %0D%0A';
							}
							else
							{
								$message .= 'Drafting Issue Date : '.$this->wbs_helper->to_report_date($consent_info[$t]->drafting_issue_date).'%0D%0A';
							}
						}

						if($user_permission_type[7]->display_type == 1)
						{
							$message .= 'Consent by : '.$consent_info[$t]->consent_by.'%0D%0A';
						}

						if($user_permission_type[8]->display_type == 1)
						{
							$message .= 'Action Required : '.$consent_info[$t]->action_required.'%0D%0A';
						}

						if($user_permission_type[9]->display_type == 1)
						{
							$message .= 'Council : '.$consent_info[$t]->council.'%0D%0A';
						}

						if($user_permission_type[10]->display_type == 1)
						{
							$message .= 'Bc Number : '.$consent_info[$t]->bc_number.'%0D%0A';
						}

						if($user_permission_type[11]->display_type == 1)
						{
							$message .= 'No. Units : '.$consent_info[$t]->no_units.'%0D%0A';
						}

						if($user_permission_type[12]->display_type == 1)
						{
							$message .= 'Contract Type : '.$consent_info[$t]->contract_type.'%0D%0A';
						}

						if($user_permission_type[13]->display_type == 1)
						{
							$message .= 'Type of Build : '.$consent_info[$t]->type_of_build.'%0D%0A';
						}

						if($user_permission_type[14]->display_type == 1)
						{
							$message .= 'Variation Pending : '.$consent_info[$t]->variation_pending.'%0D%0A';
						}
	
						if($user_permission_type[15]->display_type == 1)
						{
							$message .= 'Foundation Type : '.$consent_info[$t]->foundation_type.'%0D%0A';
						}
						
						if($user_permission_type[16]->display_type == 1)
						{
							if($consent_info[$t]->date_logged == '0000-00-00')
							{
								$message .= 'Date Logged : %0D%0A';
							}
							else
							{
								$message .= 'Date Logged : '.$this->wbs_helper->to_report_date($consent_info[$t]->date_logged).'%0D%0A';
							}
						}
						
						if($user_permission_type[17]->display_type == 1)
						{
							if($consent_info[$t]->date_issued == '0000-00-00')
							{
								$message .= 'Date Issued : %0D%0A';
							}
							else
							{
								$message .= 'Date Issued : '.$this->wbs_helper->to_report_date($consent_info[$t]->date_issued).'%0D%0A';
							}
						}
						
						if($user_permission_type[18]->display_type == 1)
						{
							$difference = abs(strtotime($consent_info[$t]->date_issued) - strtotime($consent_info[$t]->date_logged));
							$days = floor(($difference )/ (60*60*24));
							$message .= 'Days in Council : '.$days.'%0D%0A';
						}
						
						if($user_permission_type[19]->display_type == 1)
						{
							$message .= 'Order Site Levels : '.$consent_info[$t]->order_site_levels.'%0D%0A';
						}
						
						if($user_permission_type[20]->display_type == 1)
						{
							$message .= 'Order Soil Report : '.$consent_info[$t]->order_soil_report.'%0D%0A';
						}
						
						if($user_permission_type[21]->display_type == 1)
						{
							$message .= 'Septic Tank Approval : '.$consent_info[$t]->septic_tank_approval.'%0D%0A';
						}
						
						if($user_permission_type[22]->display_type == 1)
						{
							$message .= 'Dev Approval : '.$consent_info[$t]->dev_approval.'%0D%0A';
						}
						
						if($user_permission_type[23]->display_type == 1)
						{
							$message .= 'Project Manager : '.$consent_info[$t]->project_manager.'%0D%0A';
						}
						
						if($user_permission_type[24]->display_type == 1)
						{
							$message .= 'Allocated to PM : '.$consent_info[$t]->jobs_to_be_allocated_to_PM.'%0D%0A';
						}
						
						if($user_permission_type[25]->display_type == 1)
						{
							if($consent_info[$t]->unconditional_date == '0000-00-00')
							{
								$message .= 'Unconditional Date : %0D%0A';
							}
							else
							{
								$message .= 'Unconditional Date : '.$this->wbs_helper->to_report_date($consent_info[$t]->unconditional_date).'%0D%0A';
							}
						}
						
						if($user_permission_type[26]->display_type == 1)
						{
							if($consent_info[$t]->handover_date == '0000-00-00')
							{
								$message .= 'Handover Date : %0D%0A';
							}
							else
							{
								$message .= 'Handover Date : '.$this->wbs_helper->to_report_date($consent_info[$t]->handover_date).'%0D%0A';
							}
						}
						
						if($user_permission_type[27]->display_type == 1)
						{
							$message .= 'Builder : '.$consent_info[$t]->builder.'%0D%0A';
						}
						
						if($user_permission_type[28]->display_type == 1)
						{
							$message .= 'Consent out : '.$consent_info[$t]->consent_out_but_no_builder.'%0D%0A';
						}
						//$message .= "%0D%0A";
											
							
					} // end for loop $t
					
					
				} // end if condition.
			} // end for loop $a
		} // end for loop $p
		
		print_r($message);
		
		//if(mail($to, $subject, $message, $headers))
		//{
			//redirect('consent/consent_list?success=1', 'refresh');
		//}else
		//{ 
			//redirect('consent/consent_list?not_success=1', 'refresh'); 
		//}
		
	}
	
	public function consent_list_download($ids,$s_month,$e_month)
	{
		

		$message = '<html><body>';
					
		$data['title'] = 'Consent List';
		
		$user_info = $this->consent_model->user_option();
		
		$total_months = 5;
		$now = date('Y-m-d');
		$today_time = strtotime($now);
		$last_month = date("F Y", strtotime("-2 months"));

		$user =  $this->session->userdata('user');   
		$user_group_id = $user->group_id;
		
		$user_permission_type = $this->consent_model->get_user_permission_type($user_group_id);

		
		
		$segment_print = $ids;
		$segment_print_arr = explode ("_", $segment_print);		
		//print_r($segment_print_arr);  
		//$message .= '<p style="text-align:right;width:240px;"><img width="240" align="right" src="images/report_logo.png" /></p> <br />';
		for($p=$s_month; $p >= $e_month; $p--)
		{
			for($a = 0; $a < count($segment_print_arr) - 1; $a++)
			{
				if($segment_print_arr[$a] == $p )	
				{
			  	
					$month = date("F Y", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));				
					$month_start_date = date("Y-m-d", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));				
					$month_last_day = date("Y-m-t", strtotime($month_start_date));


					
					//$consent_info = $this->consent_model->get_consent_info($month_start_date,$month_last_day);

					$month_id = date("Ym", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));
					$consent_info = $this->consent_model->get_consent_info_by_monthid($month_id);
					
					$message .= '<p style="background: #ebebeb; padding:5px 10px; margin-bottom:10px;">'.$month.'</p> <br />';
					
					for($t = 0; $t < count($consent_info) ; $t++)
					{
						$message .= '<table border="0" cellspacing="3" cellpadding="4" width="100%">';
										
						$message .= '<tbody>';
						
						$message .= '<tr bgcolor="#85868a" style="color:#fff;font-weight: bold;">';
						$message .= '<td style="width:25%">Job Number</td><td style="width:25%">'.$consent_info[$t]->job_no.'</td>';
						$message .= '<td style="width:25%">Consent</td><td style="width:25%">'.$consent_info[$t]->consent_name.'</td>';
						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da" color="#000">';
						if($user_permission_type[1]->display_type == 1)
						{
							$message .= '<td style="width:25%">Design</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->design.'</td>';
						}

						if($user_permission_type[2]->display_type == 1)
						{
							if($consent_info[$t]->approval_date == '0000-00-00')
							{
								$message .= '<td style="width:25%">Approval Date</td><td bgcolor="#ebecec" style="width:25%"></td>';
							}
							else
							{
								$message .= '<td style="width:25%">Approval Date</td><td bgcolor="#ebecec" style="width:25%">'.$this->wbs_helper->to_report_date($consent_info[$t]->approval_date).'</td>';
							}
						}
						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';
						if($user_permission_type[3]->display_type == 1)
						{
							if($consent_info[$t]->pim_logged == '0000-00-00')
							{
								$message .= '<td style="width:25%">Pim Logged</td><td bgcolor="#ebecec" style="width:25%"></td>';
							}
							else
							{
								$message .= '<td style="width:25%">Pim Logged</td><td bgcolor="#ebecec" style="width:25%">'.$this->wbs_helper->to_report_date($consent_info[$t]->pim_logged).'</td>';
							}
						}

						if($user_permission_type[4]->display_type == 1)
						{
							if($consent_info[$t]->in_council == '0000-00-00')
							{
								$message .= '<td style="width:25%">In Council</td><td bgcolor="#ebecec" style="width:25%"></td>';
							}
							else
							{
								$message .= '<td style="width:25%">In Council</td><td bgcolor="#ebecec" style="width:25%">'.$this->wbs_helper->to_report_date($consent_info[$t]->in_council).'</td>';
							}
						}
						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';
						if($user_permission_type[5]->display_type == 1)
						{
							if($consent_info[$t]->consent_out == '0000-00-00')
							{
								$message .= '<td style="width:25%">Consent Out</td><td bgcolor="#ebecec" style="width:25%"></td>';
							}
							else
							{
								$message .= '<td style="width:25%">Consent Out</td><td bgcolor="#ebecec" style="width:25%">'.$this->wbs_helper->to_report_date($consent_info[$t]->consent_out).'</td>';
							}
						}

						if($user_permission_type[6]->display_type == 1)
						{
							if($consent_info[$t]->drafting_issue_date == '0000-00-00')
							{
								$message .= '<td style="width:25%">Drafting Issue Date</td><td bgcolor="#ebecec" style="width:25%"></td>';
							}
							else
							{
								$message .= '<td style="width:25%">Drafting Issue Date</td><td bgcolor="#ebecec" style="width:25%">'.$this->wbs_helper->to_report_date($consent_info[$t]->drafting_issue_date).'</td>';
							}
						}
 						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';
						if($user_permission_type[7]->display_type == 1)
						{
							$message .= '<td style="width:25%">Consent by</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->consent_by.'</td>';
						}

						if($user_permission_type[8]->display_type == 1)
						{
							$message .= '<td style="width:25%">Action Required</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->action_required.'</td>';
						}
		 				$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';
						if($user_permission_type[9]->display_type == 1)
						{
							$message .= '<td style="width:25%">Council</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->council.'</td>';
						}

						if($user_permission_type[10]->display_type == 1)
						{
							$message .= '<td style="width:25%">Bc Number</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->bc_number.'</td>';
						}
		 				$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';
						if($user_permission_type[11]->display_type == 1)
						{
							$message .= '<td style="width:25%">No. Units</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->no_units.'</td>';
						}

						if($user_permission_type[12]->display_type == 1)
						{
							$message .= '<td style="width:25%">Contract Type</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->contract_type.'</td>';
						}
						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';
						if($user_permission_type[13]->display_type == 1)
						{
							$message .= '<td style="width:25%">Type of Build</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->type_of_build.'</td>';
						}

						if($user_permission_type[14]->display_type == 1)
						{
							$message .= '<td style="width:25%">Variation Pending</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->variation_pending.'</td>';
						}
						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';
						if($user_permission_type[15]->display_type == 1)
						{
							$message .= '<td style="width:25%">Foundation Type</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->foundation_type.'</td>';
						}

						if($user_permission_type[16]->display_type == 1)
						{
							if($consent_info[$t]->date_logged == '0000-00-00')
							{
								$message .= '<td style="width:25%">Date Logged</td><td bgcolor="#ebecec" style="width:25%"></td>';
							}
							else
							{
								$message .= '<td style="width:25%">Date Logged</td><td bgcolor="#ebecec" style="width:25%">'.$this->wbs_helper->to_report_date($consent_info[$t]->date_logged).'</td>';
							}
						}
						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';
						if($user_permission_type[17]->display_type == 1)
						{
							if($consent_info[$t]->date_issued == '0000-00-00')
							{
								$message .= '<td style="width:25%">Date Issued</td><td bgcolor="#ebecec" style="width:25%"></td>';
							}
							else
							{
								$message .= '<td style="width:25%">Date Issued</td><td bgcolor="#ebecec" style="width:25%">'.$this->wbs_helper->to_report_date($consent_info[$t]->date_issued).'</td>';
							}
						}

						if($user_permission_type[18]->display_type == 1)
						{
							$difference = abs(strtotime($consent_info[$t]->date_issued) - strtotime($consent_info[$t]->date_logged));
							$days = floor(($difference )/ (60*60*24));
							$message .= '<td style="width:25%">Days in Council</td><td bgcolor="#ebecec" style="width:25%">'.$days.'</td>';
						}
						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';						 
						if($user_permission_type[19]->display_type == 1)
						{
							$message .= '<td style="width:25%">Order Site Levels</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->order_site_levels.'</td>';
						}

						if($user_permission_type[20]->display_type == 1)
						{
							$message .= '<td style="width:25%">Order Soil Report</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->order_soil_report.'</td>';
						}
						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';
						if($user_permission_type[21]->display_type == 1)
						{
							$message .= '<td style="width:25%">Septic Tank Approval</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->septic_tank_approval.'</td>';
						}

						if($user_permission_type[22]->display_type == 1)
						{
							$message .= '<td style="width:25%">Dev Approval</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->dev_approval.'</td>';
						}
						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';	
						if($user_permission_type[23]->display_type == 1)
						{
							$message .= '<td style="width:25%">Project Manager</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->project_manager.'</td>';
						}

						if($user_permission_type[24]->display_type == 1)
						{
							$message .= '<td style="width:25%">Allocated to PM</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->jobs_to_be_allocated_to_PM.'</td>';
						}
						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';
						if($user_permission_type[25]->display_type == 1)
						{
							if($consent_info[$t]->unconditional_date == '0000-00-00')
							{
								$message .= '<td style="width:25%">Unconditional Date</td><td bgcolor="#ebecec" style="width:25%"></td>';
							}
							else
							{
								$message .= '<td style="width:25%">Unconditional Date</td><td bgcolor="#ebecec" style="width:25%">'.$this->wbs_helper->to_report_date($consent_info[$t]->unconditional_date).'</td>';
							}
						}

						if($user_permission_type[26]->display_type == 1)
						{
							if($consent_info[$t]->handover_date == '0000-00-00')
							{
								$message .= '<td style="width:25%">Handover Date</td><td bgcolor="#ebecec" style="width:25%"></td>';
							}
							else
							{
								$message .= '<td style="width:25%">Handover Date</td><td bgcolor="#ebecec" style="width:25%">'.$this->wbs_helper->to_report_date($consent_info[$t]->handover_date).'</td>';
							}
						}
						$message .= "</tr>";
						
						$message .= '<tr bgcolor="#d8d8da">';							 
						if($user_permission_type[27]->display_type == 1)
						{
							$message .= '<td style="width:25%">Builder</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->builder.'</td>';
						}

						if($user_permission_type[28]->display_type == 1)
						{
							$message .= '<td style="width:25%">Consent out</td><td bgcolor="#ebecec" style="width:25%">'.$consent_info[$t]->consent_out_but_no_builder.'</td>';
						}
						$message .= "</tr>";

						$message .= "</tbody>";
					
						$message .= "</table>";	
						
						$message .= "<table><tbody><tr><td></td></tr></tbody></table>";
						
							
					} // end for loop $t					
					
				} // end if condition.
			} // end for loop $a
		} // end for loop $p
		
		$message .= "</body></html>";



		$pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
		$pdf->SetTitle('Consent Report');
		$pdf->SetHeaderMargin(30);
		$pdf->SetTopMargin(20);
		$pdf->setFooterMargin(0);
		$pdf->SetAutoPageBreak(true);
		$pdf->SetAuthor('Author');
		
		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'', PDF_HEADER_STRING);

		// set header and footer fonts
		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		// set some language-dependent strings (optional)
		if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
			require_once(dirname(__FILE__).'/lang/eng.php');
			$pdf->setLanguageArray($l);
		}

	// ---------------------------------------------------------

	// set font
	$pdf->SetFont('helvetica', '', 10);

	// add a page
	$pdf->AddPage();

	// create some HTML content

	// output the HTML content
		$pdf->writeHTML($message, true, false, true, false, '');

		// reset pointer to the last page
		$pdf->lastPage();



		//Close and output PDF document
		$pdf->Output('consent_report.pdf', 'I');
		
	}
	
	public function consent_list_report($id)
	{
					
		$data['title'] = 'Consent List';
		$data['id'] = $id;
		
		$user_info = $this->consent_model->user_option();
		
		$total_months = 5;
		$now = date('Y-m-d');
		$today_time = strtotime($now);
		$last_month = date("F Y", strtotime("-2 months"));

		$user =  $this->session->userdata('user');   
		$user_group_id = $user->group_id;
		
		$user_permission_type = $this->consent_model->get_user_permission_type($user_group_id);
					
		$consent_info = $this->consent_model->get_consent_info_report($id);

		$message = '<html><body>';
		
		$message .= '<table style="width:100%;">';
						
		$message .= '<tbody>';
		
		$message .= "<tr>";
		$message .= '<td colspan="2" style="width:100%; text-align:center;">Job Number : '.$consent_info->job_no.'</td>';
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[0]->display_type == 1)
		{
			$message .= '<td style="width:40%">Consent</td><td style="width:60%">'.$consent_info->consent_name.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[1]->display_type == 1)
		{
			$message .= '<td style="width:40%">Design</td><td style="width:60%">'.$consent_info->design.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[2]->display_type == 1)
		{
			if($consent_info->approval_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Approval Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Approval Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->approval_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[3]->display_type == 1)
		{
			if($consent_info->pim_logged == '0000-00-00')
			{
				$message .= '<td style="width:40%">Pim Logged</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Pim Logged</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->pim_logged).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[4]->display_type == 1)
		{
			if($consent_info->in_council == '0000-00-00')
			{
				$message .= '<td style="width:40%">In Council</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">In Council</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->in_council).'</td>';
			}
		}	
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[5]->display_type == 1)
		{
			if($consent_info->consent_out == '0000-00-00')
			{
				$message .= '<td style="width:40%">Consent Out</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Consent Out</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->consent_out).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[6]->display_type == 1)
		{
			if($consent_info->drafting_issue_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Drafting Issue Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Drafting Issue Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->drafting_issue_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[7]->display_type == 1)
		{
			$message .= '<td style="width:40%">Consent by</td><td style="width:60%">'.$consent_info->consent_by.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[8]->display_type == 1)
		{
			$message .= '<td style="width:40%">Action Required</td><td style="width:60%">'.$consent_info->action_required.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[9]->display_type == 1)
		{
			$message .= '<td style="width:40%">Council</td><td style="width:60%">'.$consent_info->council.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[10]->display_type == 1)
		{
			$message .= '<td style="width:40%">Bc Number</td><td style="width:60%">'.$consent_info->bc_number.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[11]->display_type == 1)
		{
			$message .= '<td style="width:40%">No. Units</td><td style="width:60%">'.$consent_info->no_units.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[12]->display_type == 1)
		{
			$message .= '<td style="width:40%">Contract Type</td><td style="width:60%">'.$consent_info->contract_type.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[13]->display_type == 1)
		{
			$message .= '<td style="width:40%">Type of Build</td><td style="width:60%">'.$consent_info->type_of_build.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[14]->display_type == 1)
		{
			$message .= '<td style="width:40%">Variation Pending</td><td style="width:60%">'.$consent_info->variation_pending.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[15]->display_type == 1)
		{
			$message .= '<td style="width:40%">Foundation Type</td><td style="width:60%">'.$consent_info->foundation_type.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[16]->display_type == 1)
		{
			if($consent_info->date_logged == '0000-00-00')
			{
				$message .= '<td style="width:40%">Date Logged</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Date Logged</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->date_logged).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[17]->display_type == 1)
		{
			if($consent_info->date_issued == '0000-00-00')
			{
				$message .= '<td style="width:40%">Date Issued</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Date Issued</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->date_issued).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[18]->display_type == 1)
		{
			$difference = abs(strtotime($consent_info->date_issued) - strtotime($consent_info->date_logged));
			$days = floor(($difference )/ (60*60*24));
			$message .= '<td style="width:40%">Days in Council</td><td style="width:60%">'.$days.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[19]->display_type == 1)
		{
			$message .= '<td style="width:40%">Order Site Levels</td><td style="width:60%">'.$consent_info->order_site_levels.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[20]->display_type == 1)
		{
			$message .= '<td style="width:40%">Order Soil Report</td><td style="width:60%">'.$consent_info->order_soil_report.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[21]->display_type == 1)
		{
			$message .= '<td style="width:40%">Septic Tank Approval</td><td style="width:60%">'.$consent_info->septic_tank_approval.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[22]->display_type == 1)
		{
			$message .= '<td style="width:40%">Dev Approval</td><td style="width:60%">'.$consent_info->dev_approval.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[23]->display_type == 1)
		{
			$message .= '<td style="width:40%">Project Manager</td><td style="width:60%">'.$consent_info->project_manager.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[24]->display_type == 1)
		{
			$message .= '<td style="width:40%">Allocated to PM</td><td style="width:60%">'.$consent_info->jobs_to_be_allocated_to_PM.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[25]->display_type == 1)
		{
			if($consent_info->unconditional_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Unconditional Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Unconditional Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->unconditional_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[26]->display_type == 1)
		{
			if($consent_info->handover_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Handover Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Handover Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->handover_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[27]->display_type == 1)
		{
			$message .= '<td style="width:40%">Builder</td><td style="width:60%">'.$consent_info->builder.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[28]->display_type == 1)
		{
			$message .= '<td style="width:40%">Consent out</td><td style="width:60%">'.$consent_info->consent_out_but_no_builder.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "</tbody>";
	
		$message .= "</table>";	
				
		$message .= "</body></html>";
		
		$data['report_message'] = $message;
		
		$data['maincontent'] = $this->load->view('consent/consent_list_report',$data,true);
				
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
		
	}
	
	public function consent_list_report_print($id)
	{
					
		$data['title'] = 'Consent List';
		
		$user_info = $this->consent_model->user_option();
		
		$total_months = 5;
		$now = date('Y-m-d');
		$today_time = strtotime($now);
		$last_month = date("F Y", strtotime("-2 months"));

		$user =  $this->session->userdata('user');   
		$user_group_id = $user->group_id;
		
		$user_permission_type = $this->consent_model->get_user_permission_type($user_group_id);
					
		$consent_info = $this->consent_model->get_consent_info_report($id);

		$message = '<html><body>';
		
		$message .= '<table border="1" style="width:100%;">';
						
		$message .= '<tbody>';
		
		$message .= "<tr>";
		$message .= '<td colspan="2" style="width:100%; text-align:center;">Job Number : '.$consent_info->job_no.'</td>';
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[0]->display_type == 1)
		{
			$message .= '<td style="width:40%">Consent</td><td style="width:60%">'.$consent_info->consent_name.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[1]->display_type == 1)
		{
			$message .= '<td style="width:40%">Design</td><td style="width:60%">'.$consent_info->design.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[2]->display_type == 1)
		{
			if($consent_info->approval_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Approval Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Approval Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->approval_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[3]->display_type == 1)
		{
			if($consent_info->pim_logged == '0000-00-00')
			{
				$message .= '<td style="width:40%">Pim Logged</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Pim Logged</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->pim_logged).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[4]->display_type == 1)
		{
			if($consent_info->in_council == '0000-00-00')
			{
				$message .= '<td style="width:40%">In Council</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">In Council</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->in_council).'</td>';
			}
		}	
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[5]->display_type == 1)
		{
			if($consent_info->consent_out == '0000-00-00')
			{
				$message .= '<td style="width:40%">Consent Out</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Consent Out</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->consent_out).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[6]->display_type == 1)
		{
			if($consent_info->drafting_issue_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Drafting Issue Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Drafting Issue Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->drafting_issue_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[7]->display_type == 1)
		{
			$message .= '<td style="width:40%">Consent by</td><td style="width:60%">'.$consent_info->consent_by.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[8]->display_type == 1)
		{
			$message .= '<td style="width:40%">Action Required</td><td style="width:60%">'.$consent_info->action_required.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[9]->display_type == 1)
		{
			$message .= '<td style="width:40%">Council</td><td style="width:60%">'.$consent_info->council.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[10]->display_type == 1)
		{
			$message .= '<td style="width:40%">Bc Number</td><td style="width:60%">'.$consent_info->bc_number.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[11]->display_type == 1)
		{
			$message .= '<td style="width:40%">No. Units</td><td style="width:60%">'.$consent_info->no_units.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[12]->display_type == 1)
		{
			$message .= '<td style="width:40%">Contract Type</td><td style="width:60%">'.$consent_info->contract_type.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[13]->display_type == 1)
		{
			$message .= '<td style="width:40%">Type of Build</td><td style="width:60%">'.$consent_info->type_of_build.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[14]->display_type == 1)
		{
			$message .= '<td style="width:40%">Variation Pending</td><td style="width:60%">'.$consent_info->variation_pending.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[15]->display_type == 1)
		{
			$message .= '<td style="width:40%">Foundation Type</td><td style="width:60%">'.$consent_info->foundation_type.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[16]->display_type == 1)
		{
			if($consent_info->date_logged == '0000-00-00')
			{
				$message .= '<td style="width:40%">Date Logged</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Date Logged</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->date_logged).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[17]->display_type == 1)
		{
			if($consent_info->date_issued == '0000-00-00')
			{
				$message .= '<td style="width:40%">Date Issued</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Date Issued</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->date_issued).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[18]->display_type == 1)
		{
			$difference = abs(strtotime($consent_info->date_issued) - strtotime($consent_info->date_logged));
			$days = floor(($difference )/ (60*60*24));
			$message .= '<td style="width:40%">Days in Council</td><td style="width:60%">'.$days.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[19]->display_type == 1)
		{
			$message .= '<td style="width:40%">Order Site Levels</td><td style="width:60%">'.$consent_info->order_site_levels.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[20]->display_type == 1)
		{
			$message .= '<td style="width:40%">Order Soil Report</td><td style="width:60%">'.$consent_info->order_soil_report.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[21]->display_type == 1)
		{
			$message .= '<td style="width:40%">Septic Tank Approval</td><td style="width:60%">'.$consent_info->septic_tank_approval.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[22]->display_type == 1)
		{
			$message .= '<td style="width:40%">Dev Approval</td><td style="width:60%">'.$consent_info->dev_approval.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[23]->display_type == 1)
		{
			$message .= '<td style="width:40%">Project Manager</td><td style="width:60%">'.$consent_info->project_manager.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[24]->display_type == 1)
		{
			$message .= '<td style="width:40%">Allocated to PM</td><td style="width:60%">'.$consent_info->jobs_to_be_allocated_to_PM.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[25]->display_type == 1)
		{
			if($consent_info->unconditional_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Unconditional Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Unconditional Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->unconditional_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[26]->display_type == 1)
		{
			if($consent_info->handover_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Handover Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Handover Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->handover_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[27]->display_type == 1)
		{
			$message .= '<td style="width:40%">Builder</td><td style="width:60%">'.$consent_info->builder.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[28]->display_type == 1)
		{
			$message .= '<td style="width:40%">Consent out</td><td style="width:60%">'.$consent_info->consent_out_but_no_builder.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "</tbody>";
	
		$message .= "</table>";	
				
		$message .= "</body></html>";
		
		$data['report_message'] = $message;
		
		$data['maincontent'] = $this->load->view('consent/consent_list_report_print',$data,true);
				
		$this->load->view('includes/header_print',$data);
		$this->load->view('includes/home_print',$data);
		$this->load->view('includes/footer_print',$data);
		
	}
	public function consent_list_report_download($id)
	{
					
		$data['title'] = 'Consent List';
		
		$user_info = $this->consent_model->user_option();
		
		$total_months = 5;
		$now = date('Y-m-d');
		$today_time = strtotime($now);
		$last_month = date("F Y", strtotime("-2 months"));

		$user =  $this->session->userdata('user');   
		$user_group_id = $user->group_id;
		
		$user_permission_type = $this->consent_model->get_user_permission_type($user_group_id);
					
		$consent_info = $this->consent_model->get_consent_info_report($id);

		$message = '<html><body>';
		
		$message .= '<table border="1" style="width:100%;">';
						
		$message .= '<tbody>';
		
		$message .= "<tr>";
		$message .= '<td colspan="2" style="width:100%; text-align:center;">Job Number : '.$consent_info->job_no.'</td>';
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[0]->display_type == 1)
		{
			$message .= '<td style="width:40%">Consent</td><td style="width:60%">'.$consent_info->consent_name.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[1]->display_type == 1)
		{
			$message .= '<td style="width:40%">Design</td><td style="width:60%">'.$consent_info->design.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[2]->display_type == 1)
		{
			if($consent_info->approval_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Approval Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Approval Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->approval_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[3]->display_type == 1)
		{
			if($consent_info->pim_logged == '0000-00-00')
			{
				$message .= '<td style="width:40%">Pim Logged</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Pim Logged</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->pim_logged).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[4]->display_type == 1)
		{
			if($consent_info->in_council == '0000-00-00')
			{
				$message .= '<td style="width:40%">In Council</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">In Council</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->in_council).'</td>';
			}
		}	
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[5]->display_type == 1)
		{
			if($consent_info->consent_out == '0000-00-00')
			{
				$message .= '<td style="width:40%">Consent Out</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Consent Out</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->consent_out).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[6]->display_type == 1)
		{
			if($consent_info->drafting_issue_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Drafting Issue Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Drafting Issue Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->drafting_issue_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[7]->display_type == 1)
		{
			$message .= '<td style="width:40%">Consent by</td><td style="width:60%">'.$consent_info->consent_by.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[8]->display_type == 1)
		{
			$message .= '<td style="width:40%">Action Required</td><td style="width:60%">'.$consent_info->action_required.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[9]->display_type == 1)
		{
			$message .= '<td style="width:40%">Council</td><td style="width:60%">'.$consent_info->council.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[10]->display_type == 1)
		{
			$message .= '<td style="width:40%">Bc Number</td><td style="width:60%">'.$consent_info->bc_number.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[11]->display_type == 1)
		{
			$message .= '<td style="width:40%">No. Units</td><td style="width:60%">'.$consent_info->no_units.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[12]->display_type == 1)
		{
			$message .= '<td style="width:40%">Contract Type</td><td style="width:60%">'.$consent_info->contract_type.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[13]->display_type == 1)
		{
			$message .= '<td style="width:40%">Type of Build</td><td style="width:60%">'.$consent_info->type_of_build.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[14]->display_type == 1)
		{
			$message .= '<td style="width:40%">Variation Pending</td><td style="width:60%">'.$consent_info->variation_pending.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[15]->display_type == 1)
		{
			$message .= '<td style="width:40%">Foundation Type</td><td style="width:60%">'.$consent_info->foundation_type.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[16]->display_type == 1)
		{
			if($consent_info->date_logged == '0000-00-00')
			{
				$message .= '<td style="width:40%">Date Logged</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Date Logged</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->date_logged).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[17]->display_type == 1)
		{
			if($consent_info->date_issued == '0000-00-00')
			{
				$message .= '<td style="width:40%">Date Issued</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Date Issued</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->date_issued).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[18]->display_type == 1)
		{
			$difference = abs(strtotime($consent_info->date_issued) - strtotime($consent_info->date_logged));
			$days = floor(($difference )/ (60*60*24));
			$message .= '<td style="width:40%">Days in Council</td><td style="width:60%">'.$days.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[19]->display_type == 1)
		{
			$message .= '<td style="width:40%">Order Site Levels</td><td style="width:60%">'.$consent_info->order_site_levels.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[20]->display_type == 1)
		{
			$message .= '<td style="width:40%">Order Soil Report</td><td style="width:60%">'.$consent_info->order_soil_report.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[21]->display_type == 1)
		{
			$message .= '<td style="width:40%">Septic Tank Approval</td><td style="width:60%">'.$consent_info->septic_tank_approval.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[22]->display_type == 1)
		{
			$message .= '<td style="width:40%">Dev Approval</td><td style="width:60%">'.$consent_info->dev_approval.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[23]->display_type == 1)
		{
			$message .= '<td style="width:40%">Project Manager</td><td style="width:60%">'.$consent_info->project_manager.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[24]->display_type == 1)
		{
			$message .= '<td style="width:40%">Allocated to PM</td><td style="width:60%">'.$consent_info->jobs_to_be_allocated_to_PM.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[25]->display_type == 1)
		{
			if($consent_info->unconditional_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Unconditional Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Unconditional Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->unconditional_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[26]->display_type == 1)
		{
			if($consent_info->handover_date == '0000-00-00')
			{
				$message .= '<td style="width:40%">Handover Date</td><td style="width:60%"></td>';
			}
			else
			{
				$message .= '<td style="width:40%">Handover Date</td><td style="width:60%">'.$this->wbs_helper->to_report_date($consent_info->handover_date).'</td>';
			}
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[27]->display_type == 1)
		{
			$message .= '<td style="width:40%">Builder</td><td style="width:60%">'.$consent_info->builder.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "<tr>";
		if($user_permission_type[28]->display_type == 1)
		{
			$message .= '<td style="width:40%">Consent out</td><td style="width:60%">'.$consent_info->consent_out_but_no_builder.'</td>';
		}
		$message .= "</tr>";
		
		$message .= "</tbody>";
	
		$message .= "</table>";	
				
		$message .= "</body></html>";
		
		require_once('tcpdf/tcpdf.php');

		// create new PDF document
		$pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

		// set document information
		$pdf->SetCreator(PDF_CREATOR);
		$pdf->SetAuthor('Xprocoders');
		//$pdf->SetTitle($this->lang->line('Marketing and Branding Solutions'));
		//$pdf->SetSubject($this->lang->line('Employee Details'));
		$pdf->SetKeywords('TCPDF, PDF, example, test, guide');


		// set default header data
		$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' ', PDF_HEADER_STRING, array(0,64,255), array(0,64,128));

		$pdf->setFooterData(array(0,64,0), array(0,64,128));

		// set header and footer fonts
		//$pdf->SetFont('aealarabiya', '', 18);

		$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));

		$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

		// set default monospaced font
		$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		// set margins
		$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
		$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
		$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

		// set auto page breaks
		$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		// set image scale factor
		$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		$lg = Array();
		$lg['a_meta_charset'] = 'UTF-8';
		$lg['a_meta_dir'] = 'rtl';
		$lg['a_meta_language'] = 'fa';
		$lg['w_page'] = 'page';
		$pdf->setLanguageArray($lg);

		// set some language-dependent strings (optional)
		// if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
		    //require_once(dirname(__FILE__).'/lang/eng.php');
		   // $pdf->setLanguageArray($l);                        
		//}

		// ---------------------------------------------------------

		// set default font subsetting mode
		$pdf->setFontSubsetting(true);

		// Set font
		// dejavusans is a UTF-8 Unicode font, if you only need to
		// print standard ASCII chars, you can use core fonts like
		// helvetica or times to reduce file size.

		// Restore RTL direction

		$pdf->setRTL(false);
		$pdf->SetFont('dejavusans', '', 10, '', true);

		// Add a page
		// This method has several options, check the source code documentation for more information.
		$pdf->AddPage();

		// set text shadow effect
		$pdf->setTextShadow(array('enabled'=>true, 'depth_w'=>0.2, 'depth_h'=>0.2, 'color'=>array(196,196,196), 'opacity'=>1, 'blend_mode'=>'Normal'));

		// Set some content to print
		$html = $message;

		// Print text using writeHTMLCell()
		$pdf->writeHTMLCell(0, 0, '', '', $html, 0, 1, 0, true, '', true);

		// ---------------------------------------------------------

		// Close and output PDF document
		// This method has several options, check the source code documentation for more information.
		$pdf->Output('consent_report.pdf', 'I');
		
	}

	function consent_ordering($order_str, $month_id){
		$consent_value = urldecode($order_str);

		$exp1 = explode('&', $consent_value);
		for($i = 0; $i< count($exp1); $i++)
		{
			$str2 = $exp1[$i];
			$exp2 = explode('=', $str2);
			$ordering[] = $exp2[1];
		}

		$this->consent_model->save_consent_ordering($ordering,$month_id);
	}
	function consent_delete($tr_id){
		$c_ids = explode('_',$tr_id);
		$consent_id = $c_ids[2]; 

		$this->consent_model->consent_delete($consent_id);	
		redirect('consent/consent_list');
	}


}
?>