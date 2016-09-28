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
		$user=  $this->session->userdata('user');          
        $user_id =$user->uid; 

		if($this->uri->segment(3)=='month_add_success')
			$data['message'] = 'Month added successfully';
		else if ($this->uri->segment(3)=='month_add_unsuccess')
			$data['message'] = 'Month not added';
		else
			$data['message'] = '';

		if(isset($_POST['show_consent'])){
			$from_month = $_POST['from_month'];
			$to_month = $_POST['to_month'];
			
			$month = array(
				'user_id' => $user_id,
				'from_month' => $from_month,
				'to_month' => $to_month
			);
			$this->consent_model->from_month_to_month($month);
		}

		
		$data['title'] = 'Consent List';	
		$data['active_tabs'] = $this->consent_model->get_active_tabs($user_id);

		$data['maincontent'] = $this->load->view('consent/consent_list',$data,true);
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
	} 
	public function job_add()
	{
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$month_id = $_POST['year'].$_POST['month'];
		$no_of_apartment = $_POST['no_of_apartment'];
		$unconditional_date = ($_POST['unconditional_date']) ? $this->wbs_helper->change_cms_date($_POST['unconditional_date']) : '' ;

		// Parent Job add
		$job_data = array(
				'wp_company_id' => $wp_company_id,
				'month_id' => $month_id,
				'job_no' => $_POST['job_no'],
				'consent_name' => $_POST['consent_name'],
				'design' => '',
				'apartment' => $_POST['apertment'],
				'number_of_apartment' =>$no_of_apartment,
				'contract_type' =>$_POST['contract_type'],
				'council' =>$_POST['council'],
				'type_of_build' =>$_POST['type_of_build'],
				'unconditional_date' => $unconditional_date,
				'notes' =>$_POST['notes']
			);
		$parent_id = $this->consent_model->consent_save($job_data);

		// Child Job Add
		for($i=1;$i<= $no_of_apartment;$i++){
			$index_p = 'child_apt_jobno_'.$i;
			$child_job_number = $_POST[$index_p];

			$job_data = array(
				'wp_company_id' => $wp_company_id,
				'month_id' => $month_id,
				'job_no' => $child_job_number,
				'design' => 'REQ Brief',
				'parent_id' => $parent_id,
				'consent_name' => $_POST["child_apt_consent_name_$i"]
			);
			$this->consent_model->consent_save($job_data);
		}

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

	function consent_update($field_name,$job_no,$has_child){

		$field_value =  $_GET['val'] ;
		$parent_id = $_GET['pid'];
		$field_value = trim(urldecode($field_value));
		if( $field_name == 'approval_date' or 
			$field_name == 'price_approved_date' or
			$field_name == 'pricing_requested' or
			$field_name == 'pricing_for_approval' or
			$field_name == 'colours_requested_and_loaded_on_gc' or
			$field_name == 'developer_colour_sheet_created' or
			$field_name == 'spec_loaded_on_gc' or
			$field_name == 'render_requested' or
			$field_name == 'render_received' or
			$field_name == 'drafting_issue_date' or
			$field_name == 'date_job_checked' or
			$field_name == 'date_logged' or
			$field_name == 'date_issued' or
			$field_name == 'actual_date_issued' or
			$field_name == 'unconditional_date' or
			$field_name == 'handover_date' or
			$field_name == 'title_date' or
			$field_name == 'settlement_date' or
			$field_name == 'expected_date_to_lodge_bc' ){
			if($field_value!=''){
				$field_value = $this->wbs_helper->change_cms_date($field_value);
			}else{
				$field_value = '';
			}
		}
		$update_data = array(
			$field_name => $field_value
		);

		$consent_data = $this->consent_model->consent_update_child($update_data, $job_no,$has_child,$parent_id);
		//print_r($consent_data);
		
	}

	function consent_update_old($month_id, $total_job)
	{
		for ($i = 0; $i < $total_job; $i++) {

			$get = $_GET;
			$job_no = urldecode($get["job_no$i"]);
			$consent_id = urldecode($get["consent_id$i"]); 
			$consent_color = urldecode($get["consent_color$i"]);
			$design_id = urldecode($get["design_id$i"]);
	 
			$approval_date = $get["approval_date$i"];
			if($approval_date==''){
				$approval_date = '0000-00-00';
			}else{
				$filed_value = urldecode($approval_date);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$approval_date = date("Y-m-d", strtotime($filed_values));
			}
	
			$price_approved_date = $get["price_approved_date$i"]; 
			if($price_approved_date==''){
				$price_approved_date = '0000-00-00';
			}else{
				$filed_value = urldecode($price_approved_date);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$price_approved_date = date("Y-m-d", strtotime($filed_values));
			}
	
			$pim_logged = $get["pim_logged$i"];
			if($pim_logged==''){
				$pim_logged = '0000-00-00';
			}else{
				$filed_value = urldecode($pim_logged);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$pim_logged = date("Y-m-d", strtotime($filed_values));
			}
	
			$drafting_issue_date = $get["drafting_issue_date$i"]; 
			if($drafting_issue_date==''){
				$drafting_issue_date = '0000-00-00';
			}else{
				$filed_value = urldecode($drafting_issue_date);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$drafting_issue_date = date("Y-m-d", strtotime($filed_values));
			}
	
			$consent_by = urldecode($get["consent_by$i"]);
			$action_required = urldecode($get["action_required$i"]); 
			$council = urldecode($get["council$i"]);
			$lbp = urldecode($get["lbp$i"]); 
	
			$date_job_checked = $get["date_job_checked$i"];
			if($date_job_checked==''){
				$date_job_checked = '0000-00-00';
			}else{
				$filed_value = urldecode($date_job_checked);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$date_job_checked = date("Y-m-d", strtotime($filed_values));
			}
	
			$bc_number = urldecode($get["bc_number$i"]); 
			$no_units = urldecode($get["no_units$i"]);
			$contract_type = urldecode($get["contract_type$i"]); 
			$type_of_build = urldecode($get["type_of_build$i"]);
			$variation_pending = urldecode($get["variation_pending$i"]); 
			$foundation_type = urldecode($get["foundation_type$i"]);
	
			$date_logged = $get["date_logged$i"]; 
			if($date_logged==''){
				$date_logged = '0000-00-00';
			}else{
				$filed_value = urldecode($date_logged);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$date_logged = date("Y-m-d", strtotime($filed_values));
			}
	
			$date_issued = $get["date_issued$i"];
			if($date_issued==''){
				$date_issued = '0000-00-00';
			}else{
				$filed_value = urldecode($date_issued);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$date_issued = date("Y-m-d", strtotime($filed_values));
			}
	
			$actual_date_issued = $get["actual_date_issued$i"]; 
			if($actual_date_issued==''){
				$actual_date_issued = '0000-00-00';
			}else{
				$filed_value = urldecode($actual_date_issued);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$actual_date_issued = date("Y-m-d", strtotime($filed_values));
			}
	
			$order_site_levels = urldecode($get["order_site_levels$i"]);			
			$order_soil_report = urldecode($get["order_soil_report$i"]); 
			$septic_tank_approval = urldecode($get["septic_tank_approval$i"]);
			$dev_approval = urldecode($get["dev_approval$i"]); 
			$landscape = urldecode($get["landscape$i"]);
			$mss = urldecode($get["mss$i"]);
			$project_manager = urldecode($get["project_manager$i"]);
	
			$unconditional_date = $get["unconditional_date$i"];
			if($unconditional_date==''){
				$unconditional_date = '0000-00-00';
			}else{
				$filed_value = urldecode($unconditional_date);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$unconditional_date = date("Y-m-d", strtotime($filed_values));
			}
	
			$handover_date = $get["handover_date$i"];
			if($handover_date==''){
				$handover_date = '0000-00-00';
			}else{
				$filed_value = urldecode($handover_date);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$handover_date = date("Y-m-d", strtotime($filed_values));
			}
	
			$builder = urldecode($get["builder$i"]); 
			$consent_out_but_no_builder = urldecode($get["consent_out_but_no_builder$i"]);
	
			$title_date_issued = urldecode($get["title_date_issued$i"]);

			$title_date = $get["title_date$i"]; 
			if($title_date==''){
				$title_date = '0000-00-00';
			}else{
				$filed_value = urldecode($title_date);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$title_date = date("Y-m-d", strtotime($filed_values));
			}
	
			$settlement_date = $get["settlement_date$i"];
			if($settlement_date==''){
				$settlement_date = '0000-00-00';
			}else{
				$filed_value = urldecode($settlement_date);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$settlement_date = date("Y-m-d", strtotime($filed_values));
			}

			$pre_construction_sign = urldecode($get["pre_construction_sign$i"]);
			$designer = urldecode($get["designer$i"]);
			$ok_to_release_to_marketing = urldecode($get["ok_to_release_to_marketing$i"]);
			$pricing_requested = urldecode($get["pricing_requested$i"]);
			$pricing_for_approval = urldecode($get["pricing_for_approval$i"]);
			$approved_for_sale_price = urldecode($get["approved_for_sale_price$i"]);
			$kitchen_disign_type = urldecode($get["kitchen_disign_type$i"]);
			$kitchen_disign_requested = urldecode($get["kitchen_disign_requested$i"]);
			$colours_requested_and_loaded_on_gc = urldecode($get["colours_requested_and_loaded_on_gc$i"]);
			$kitchen_design_loaded_on_gc = urldecode($get["kitchen_design_loaded_on_gc$i"]);
			$developer_colour_sheet_created = urldecode($get["developer_colour_sheet_created$i"]);
			$spec_loaded_on_gc = urldecode($get["spec_loaded_on_gc$i"]);
			$loaded_on_intranet = urldecode($get["loaded_on_intranet$i"]);
			$website = urldecode($get["website$i"]);
			$render_requested = urldecode($get["render_requested$i"]);
			$render_received = urldecode($get["render_received$i"]);
			$brochure = urldecode($get["brochure$i"]);
			$resource_consent = urldecode($get["resource_consent$i"]);
			$rc_number = urldecode($get["rc_number$i"]);
			
			$expected_date_to_lodge_bc = $get["expected_date_to_lodge_bc$i"];
			if($expected_date_to_lodge_bc==''){
				$expected_date_to_lodge_bc = '0000-00-00';
			}else{
				$filed_value = urldecode($expected_date_to_lodge_bc);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$expected_date_to_lodge_bc = date("Y-m-d", strtotime($filed_values));
			}
			
			$water_connection = urldecode($get["water_connection$i"]);
			$vehicle_crossing = urldecode($get["vehicle_crossing$i"]);
			$for_sale_sign = urldecode($get["for_sale_sign$i"]);
			$code_of_compliance = urldecode($get["code_of_compliance$i"]);
	 
			$notes = urldecode($get["notes$i"]);
	
			$update_data = array(                   
                    'consent_name' => $consent_id,
                    'consent_color' => $consent_color,	
                    'design' => $design_id,
                    'approval_date' =>  $approval_date,
					'price_approved_date' =>  $price_approved_date,
                    'pim_logged' =>  $pim_logged,
                    'drafting_issue_date' =>  $drafting_issue_date,
                	'consent_by' => $consent_by,
                	'action_required' => $action_required,
                	'council' => $council,   
					'lbp' => $lbp,   
					'date_job_checked' => $date_job_checked,              
                 	'bc_number' => $bc_number, 
	                'no_units' => $no_units,
	                'contract_type' => $contract_type,
	                'type_of_build' => $type_of_build,
	                'variation_pending' => $variation_pending,
	                'foundation_type' => $foundation_type,
	                'date_logged' => $date_logged,
	                'date_issued' => $date_issued,
					'actual_date_issued' => $actual_date_issued,
	                'order_site_levels' => $order_site_levels, 
	                'order_soil_report' => $order_soil_report, 
	                'septic_tank_approval' => $septic_tank_approval, 
	                'dev_approval' => $dev_approval, 
					'landscape' => $landscape, 
					'mss' => $mss, 
	                'project_manager' => $project_manager,  
	                'unconditional_date' => $unconditional_date, 
	                'handover_date' => $handover_date, 
	                'builder' => $builder, 
	                'consent_out_but_no_builder' => $consent_out_but_no_builder,
					'title_date_issued' => $title_date_issued,
					'title_date' => $title_date,
					'settlement_date' =>$settlement_date,
	                'notes' => $notes,
					'pre_construction_sign' => $pre_construction_sign,
					'designer' => $designer,
					'ok_to_release_to_marketing' => $ok_to_release_to_marketing,
					'pricing_requested' => $pricing_requested,
					'pricing_for_approval' => $pricing_for_approval,
					'approved_for_sale_price' => $approved_for_sale_price,
					'kitchen_disign_type' => $kitchen_disign_type,
					'kitchen_disign_requested' => $kitchen_disign_requested,
					'colours_requested_and_loaded_on_gc' => $colours_requested_and_loaded_on_gc,
					'kitchen_design_loaded_on_gc' => $kitchen_design_loaded_on_gc,
					'developer_colour_sheet_created' => $developer_colour_sheet_created,
					'spec_loaded_on_gc' => $spec_loaded_on_gc,
					'loaded_on_intranet' => $loaded_on_intranet,
					'website' => $website,
					'render_requested' => $render_requested,
					'render_received' => $render_received,
					'brochure' => $brochure,
					'resource_consent' => $resource_consent,
					'rc_number' => $rc_number,
					'expected_date_to_lodge_bc' => $expected_date_to_lodge_bc,
					'water_connection' => $water_connection,
					'vehicle_crossing' => $vehicle_crossing,
					'for_sale_sign' => $for_sale_sign,
					'code_of_compliance' => $code_of_compliance
                ); 	
			
			$this->consent_model->update_consent($job_no, $update_data);

		}
		
		$auckland = $this->consent_model->get_total_consents_in($month_id,'Auckland')->total_consent_in;
		$chch = $this->consent_model->get_total_consents_in($month_id,'Chch')->total_consent_in;

		$auckland_1 = $this->consent_model->get_total_consents_out($month_id,'Auckland')->total_consent_out;
		$chch_1 = $this->consent_model->get_total_consents_out($month_id,'Chch')->total_consent_out;

		$handover = $this->consent_model->get_total_consents_handover($month_id)->total_consents_handover;

		$val = "&nbsp;&nbsp;| &nbsp;&nbsp; Total consents IN &nbsp;&nbsp; <b>AUCK: ".$auckland." &nbsp;&nbsp; CHCH: ".$chch."</b>
				&nbsp;&nbsp; | &nbsp;&nbsp;  Total consents OUT: <b>AUCK: ".$auckland_1." &nbsp;&nbsp; CHCH: ".$chch_1."</b>
				&nbsp;&nbsp;| &nbsp;&nbsp;Total handovers: <b>".$handover."</b>";
		echo $val;
	}

	function consent_update_tfield()
	{
		$get = $_GET;
		$job_no = $get['job_no'];
		$field_name_1 = $get['field_name_1'];
		$filed_value_1 = $get['filed_value_1'];
		$field_name_2 = $get['field_name_2'];
		$filed_value_2 = $get['filed_value_2'];
		$formate = $get['formate'];

		if($formate == 1)
		{
			if($filed_value=='')
			{
				$filed_value = '0000-00-00';
			}
			else
			{
				$filed_value = urldecode($filed_value);
				$filed_values = str_replace("-","/",$filed_value);
				$dates = explode("/",$filed_values);
				$filed_values = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
				$filed_value = date("Y-m-d", strtotime($filed_values));
			} 
		}
		else
		{
			$filed_value_1 = urldecode($filed_value_1);
			$filed_value_2 = urldecode($filed_value_2);
		}
		
		$update_data = array(
			$field_name_1 => 	$filed_value_1,
			$field_name_2 => 	$filed_value_2
		);

		$this->consent_model->update_consent($job_no, $update_data);
	}

	public function month_add()
	{
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$month_data = array(
			'wp_company_id' => $wp_company_id,
			'month' => $_POST['month_list'],
			'year' => $_POST['year_list']
		);
		$this->consent_model->add_month($month_data);
	}
	public function check_year_month($year,$month)
	{
		$this->consent_model->check_year_month($year,$month);
	}

	public function clear_search_consent()
    {
        $this->session->unset_userdata('keywords');
		$this->session->unset_userdata('consent_fields');
		$this->session->unset_userdata('search_value');
		$this->session->unset_userdata('consent_f_month');
		$this->session->unset_userdata('consent_l_month');
    }

	public function clear_search_consent_report()
    {
        $this->session->unset_userdata('report_keywords');
		$this->session->unset_userdata('report_consent_fields');
		$this->session->unset_userdata('report_search_value');
		$this->session->unset_userdata('report_consent_f_month');
		$this->session->unset_userdata('report_consent_l_month');
    }

	public function consent_template_add()
    {
		$user=  $this->session->userdata('user');
		$wp_company_id=  $user->company_id;

		$template_name = $_GET['template_name'];
        $add = array(
			'wp_company_id' => $wp_company_id,
			'template_name' => $template_name
		);
		echo $id = $this->consent_model->consent_template_add($add);
    }

	public function show_report()
	{

		if(isset($_GET['template_id'])){
			$template_id = $_GET['template_id'];
			if($template_id=='null'){
				$this->session->unset_userdata('report_keywords');
				$this->session->unset_userdata('report_consent_fields');
				$this->session->unset_userdata('report_search_value');
				$this->session->unset_userdata('report_consent_f_month');
				$this->session->unset_userdata('report_consent_l_month');
			}else{
				$row = $this->consent_model->template_id($template_id)->row();
				$search_filter = $row->search;
				$field_check = $row->refine;
				$refine_value = $row->refine_value;
				$from_month = $row->date_from;
				$to_month = $row->date_to;
				$search['report_keywords'] = $search_filter;
				$search['report_consent_fields'] = $field_check;
				$search['report_search_value'] = $refine_value;
				$search['report_consent_f_month'] = $from_month;
				$search['report_consent_l_month'] = $to_month;			
				$this->session->set_userdata($search); 
			}
		}

		if(isset($_POST['submit'])){
			$search_filter = $_POST['search_filter'];
			$search['report_keywords'] = $search_filter;			
			
			if(isset($_POST['consent_fields'])){							
				$field_check = implode(",", $_POST['consent_fields']);
			}else{
				$field_check = '';
			}

			$report_consent_fields = $_POST['consent_fields'];

			$search_value = '';
			$from_month = '';
			$to_month = '';
			for($i=0; $i < count($report_consent_fields); $i++){
				if($i==count($report_consent_fields)-1){
					if(isset($_POST[$report_consent_fields[$i].'_search_value'])){
						$search_value .= $report_consent_fields[$i].'_search_value='.$_POST[$report_consent_fields[$i].'_search_value']; 
					}
		
					if(isset($_POST[$report_consent_fields[$i].'_from_month'])){
						$from_month .= $report_consent_fields[$i].'_from_month='.$_POST[$report_consent_fields[$i].'_from_month']; 
					}
		
					if(isset($_POST[$report_consent_fields[$i].'_to_month'])){
						$to_month .= $report_consent_fields[$i].'_to_month='.$_POST[$report_consent_fields[$i].'_to_month']; 
					}
				}else{
					if(isset($_POST[$report_consent_fields[$i].'_search_value'])){
						$search_value .= $report_consent_fields[$i].'_search_value='.$_POST[$report_consent_fields[$i].'_search_value'].','; 
					}
		
					if(isset($_POST[$report_consent_fields[$i].'_from_month'])){
						$from_month .= $report_consent_fields[$i].'_from_month='.$_POST[$report_consent_fields[$i].'_from_month'].','; 
					}
		
					if(isset($_POST[$report_consent_fields[$i].'_to_month'])){
						$to_month .= $report_consent_fields[$i].'_to_month='.$_POST[$report_consent_fields[$i].'_to_month'].','; 
					}
				}
			}

			$search['report_consent_fields'] = $field_check;
			$search['report_search_value'] = $search_value;
			$search['report_consent_f_month'] = $from_month;
			$search['report_consent_l_month'] = $to_month;

			$this->session->set_userdata($search); 

			$template_id = $_POST['template_id'];
			$update = array(
				'search' => $search_filter,
				'refine' => $field_check,
				'refine_value' => $search_value,
				'date_from' => $from_month,
				'date_to' => $to_month
			);
		    $this->consent_model->consent_template_update($template_id,$update);
		}

		$keywords = $this->session->userdata('report_keywords');
		$ss = $this->session->userdata('report_consent_fields');
		$report_search_value = $this->session->userdata('report_search_value');

		$start_date = $this->session->userdata('report_consent_f_month');
		$end_date = $this->session->userdata('report_consent_l_month');

		$user_info = $this->consent_model->user_option();
		
		$user =  $this->session->userdata('user');
   
		$user_group_id = $this->session->userdata('user_group_id');
		
		$user_permission_type = $this->consent_model->get_user_permission_type($user_group_id);
					
		$consent_info = $this->consent_model->get_consent_report($start_date,$end_date,$keywords,$ss,$report_search_value);
		$data['consent_info'] = count($consent_info);
		//print_r($ss);

		if($ss!=''){
			$search_per = explode(',',$ss);
		}else{
			$cu = 'photos_taken,code_of_compliance,for_sale_sign,vehicle_crossing,water_connection,expected_date_to_lodge_bc,rc_number,resource_consent,brochure,render_received,render_requested,website,loaded_on_intranet,spec_loaded_on_gc,developer_colour_sheet_created,kitchen_design_loaded_on_gc,colours_requested_and_loaded_on_gc,kitchen_disign_requested,kitchen_disign_type,approved_for_sale_price,pricing_for_approval,pricing_requested,ok_to_release_to_marketing,designer,pre_construction_sign,job_no,consent_name,design,approval_date,price_approved_date,pim_logged,drafting_issue_date,consent_by,action_required,council,lbp,date_job_checked,bc_number,no_units,contract_type,type_of_build,variation_pending,foundation_type,date_logged,date_issued,actual_date_issued,order_site_levels,order_soil_report,septic_tank_approval,dev_approval,landscape,mss,project_manager,unconditional_date,handover_date,builder,consent_out_but_no_builder,title_date,settlement_date,notes';
			$search_per = explode(',',$cu);	
		}

		$message = '<table id="table" class="consent_table tablesorter" border="0" cellpadding="0" cellspacing="0">';
		$message .= '<thead>';
		$message .= '<th style="width:1%">Job No.</th>';

	 	if($user_permission_type[0]->display_type == 1){  
			if(in_array('consent_name',$search_per)){
				$message .='<th style="width:3%;">Consent Name</th>';
			}
		} if($user_permission_type[45]->display_type == 1){ 
			if(in_array('pre_construction_sign',$search_per)){ 
				$message .='<th style="width:1%">Pre-construction sign</th>';
			}
	  	} if($user_permission_type[1]->display_type == 1){ 
			if(in_array('design',$search_per)){ 
				$message .='<th style="width:1%">Design</th>';
			}
	  	} if($user_permission_type[46]->display_type == 1){ 
			if(in_array('designer',$search_per)){ 
				$message .='<th style="width:1%">Designer</th>';
			}
	  	} if($user_permission_type[2]->display_type == 1){  
			if(in_array('approval_date',$search_per)){
				$message .='<th style="width:2%">Design Approval Date</th>';
			}
	  	} if($user_permission_type[47]->display_type == 1){ 
			if(in_array('ok_to_release_to_marketing',$search_per)){ 
				$message .='<th style="width:2%">Ok to release to Marketing</th>';
			}
	  	} if($user_permission_type[48]->display_type == 1){  
			if(in_array('pricing_requested',$search_per)){
				$message .='<th style="width:2%">Pricing requested</th>';
			}
	  	} if($user_permission_type[49]->display_type == 1){  
			if(in_array('pricing_for_approval',$search_per)){
				$message .='<th style="width:2%">Pricing for approval</th>';
			}
	  	} if($user_permission_type[37]->display_type == 1){  
			if(in_array('price_approved_date',$search_per)){
				$message .='<th style="width:2%">Price Approved Date</th>';
			}
	  	} if($user_permission_type[50]->display_type == 1){  
			if(in_array('approved_for_sale_price',$search_per)){
				$message .='<th style="width:2%">Approved For Sale Price</th>';
			}
	  	} if($user_permission_type[51]->display_type == 1){ 
			if(in_array('kitchen_disign_type',$search_per)){ 
				$message .='<th style="width:2%">Kitchen Design Type</th>';
			}
	  	} if($user_permission_type[52]->display_type == 1){  
			if(in_array('kitchen_disign_requested',$search_per)){
				$message .='<th style="width:2%">Kitchen Design Requested</th>';
			}
	  	} if($user_permission_type[53]->display_type == 1){ 
			if(in_array('colours_requested_and_loaded_on_gc',$search_per)){ 
				$message .='<th style="width:2%">Colours Requested & Loaded on GC</th>';
			}
	  	} if($user_permission_type[54]->display_type == 1){ 
			if(in_array('kitchen_design_loaded_on_gc',$search_per)){ 
				$message .='<th style="width:2%">Kitchen Design Loaded on GC</th>';
			}
	  	} if($user_permission_type[55]->display_type == 1){  
			if(in_array('developer_colour_sheet_created',$search_per)){
				$message .='<th style="width:2%">Developer Colour Sheet Created</th>';
			}
	  	} if($user_permission_type[56]->display_type == 1){ 
			if(in_array('spec_loaded_on_gc',$search_per)){ 
				$message .='<th style="width:2%">Spec Loaded on GC</th>';
			}
	  	} if($user_permission_type[57]->display_type == 1){  
			if(in_array('loaded_on_intranet',$search_per)){
				$message .='<th style="width:2%">Loaded on Intranet</th>';
			}
	  	} if($user_permission_type[58]->display_type == 1){
			if(in_array('website',$search_per)){  
				$message .='<th style="width:2%">Website</th>';
			}
	  	} if($user_permission_type[59]->display_type == 1){ 
			if(in_array('render_requested',$search_per)){ 
				$message .='<th style="width:2%">Render Requested</th>';
			}
	  	} if($user_permission_type[60]->display_type == 1){ 
			if(in_array('render_received',$search_per)){ 
				$message .='<th style="width:2%">Render Received</th>';
			}
	  	} if($user_permission_type[61]->display_type == 1){ 
			if(in_array('brochure',$search_per)){ 
				$message .='<th style="width:2%">Brochure</th>';
			}
	  	} if($user_permission_type[3]->display_type == 1){  
			if(in_array('pim_logged',$search_per)){
				$message .='<th style="width:2%">Pim <br> Lodged</th>';
			}
	  	} if($user_permission_type[6]->display_type == 1){  
			if(in_array('drafting_issue_date',$search_per)){
				$message .='<th style="width:2%">Drafting <br>Issue Date</th>';
			}
	  	} if($user_permission_type[7]->display_type == 1){ 
			if(in_array('consent_by',$search_per)){ 
				$message .='<th style="width:2%">Consent<br>by</th>';
			}
	  	} if($user_permission_type[8]->display_type == 1){  
			if(in_array('action_required',$search_per)){
				$message .='<th style="width:2%">Action<br>Required</th>';
			}
	  	} if($user_permission_type[9]->display_type == 1){  
			if(in_array('council',$search_per)){
				$message .='<th style="width:2%">Council</th>';
			}
	  	} if($user_permission_type[29]->display_type == 1){ 
			if(in_array('lbp',$search_per)){ 
				$message .='<th style="width:2%">LBP</th>';
			}
	  	} if($user_permission_type[30]->display_type == 1){ 
			if(in_array('date_job_checked',$search_per)){ 
				$message .='<th style="width:2%">Date Job Checked</th>';
			}
	  	} if($user_permission_type[10]->display_type == 1){  
			if(in_array('bc_number',$search_per)){
				$message .='<th style="width:2%">Bc Number</th>';
			}
	  	} if($user_permission_type[11]->display_type == 1){  
			if(in_array('no_units',$search_per)){
				$message .='<th style="width:1%">No. Units</th>';
			}
	  	} if($user_permission_type[12]->display_type == 1){  
			if(in_array('contract_type',$search_per)){
				$message .='<th style="width:1%">Contract Type</th>';
			}
	  	} if($user_permission_type[13]->display_type == 1){
			if(in_array('type_of_build',$search_per)){  
				$message .='<th style="width:1%">Type of <br>Build</th>';
			}
	  	} if($user_permission_type[14]->display_type == 1){
			if(in_array('variation_pending',$search_per)){  
				$message .='<th style="width:2%">Variation <br>Pending</th>';
			}
	  	} if($user_permission_type[15]->display_type == 1){ 
			if(in_array('foundation_type',$search_per)){ 
				$message .='<th style="width:2%">Foundation<br>Type</th>';
			}
	  	} if($user_permission_type[62]->display_type == 1){ 
			if(in_array('resource_consent',$search_per)){ 
				$message .='<th style="width:2%">Resource Consent</th>';
			}
	  	} if($user_permission_type[63]->display_type == 1){ 
			if(in_array('rc_number',$search_per)){ 
				$message .='<th style="width:2%">RC Number</th>';
			}
	  	} if($user_permission_type[64]->display_type == 1){  
			if(in_array('expected_date_to_lodge_bc',$search_per)){
				$message .='<th style="width:2%">Expected Date to Lodge BC</th>';
			}
	  	} if($user_permission_type[16]->display_type == 1){ 
			if(in_array('date_logged',$search_per)){ 
				$message .='<th style="width:2%">Consent<br>Lodged</th>';
			}
	  	} if($user_permission_type[17]->display_type == 1){ 
			if(in_array('date_issued',$search_per)){ 
				$message .='<th style="width:2%">Consent <br>Issued</th>';
			}
	  	} if($user_permission_type[17]->display_type == 1){ 
			if(in_array('actual_date_issued',$search_per)){ 
				$message .='<th style="width:2%">Actual <br>Date Issued</th>';
			}
	  	} if($user_permission_type[18]->display_type == 1){ 
			if(in_array('day_in_council',$search_per)){ 
				$message .='<th style="width:1%">Days in Council</th>';
			}
	  	} if($user_permission_type[65]->display_type == 1){ 
			if(in_array('water_connection',$search_per)){ 
				$message .='<th style="width:1%">Water Connection</th>';
			}
	  	} if($user_permission_type[66]->display_type == 1){
			if(in_array('vehicle_crossing',$search_per)){  
				$message .='<th style="width:1%">Vehicle Crossing</th>';
			}
	  	} if($user_permission_type[19]->display_type == 1){ 
			if(in_array('order_site_levels',$search_per)){ 
				$message .='<th style="width:2%">Order Site <br>Levels</th>';
			}
	  	} if($user_permission_type[20]->display_type == 1){ 
			if(in_array('order_soil_report',$search_per)){ 
				$message .='<th style="width:2%">Order Soil <br>Report</th>';
			}
	  	} if($user_permission_type[21]->display_type == 1){ 
			if(in_array('septic_tank_approval',$search_per)){ 
				$message .='<th style="width:2%">Septic Tank <br>Approval</th>';
			}
	  	} if($user_permission_type[70]->display_type == 1){  
			if(in_array('drainage_testing',$search_per)){
				$message .='<th style="width:2%">Drainage Testing</th>';
			}
	  	} if($user_permission_type[22]->display_type == 1){  
			if(in_array('dev_approval',$search_per)){
				$message .='<th style="width:2%">Dev Approval</th>';
			}
	  	} if($user_permission_type[32]->display_type == 1){ 
			if(in_array('landscape',$search_per)){ 
				$message .='<th style="width:2%">Landscape</th>';
			}
	  	} if($user_permission_type[33]->display_type == 1){  
			if(in_array('mss',$search_per)){
				$message .='<th style="width:2%">MSS</th>';
			}
	  	} if($user_permission_type[23]->display_type == 1){ 
			if(in_array('project_manager',$search_per)){ 
				$message .='<th style="width:2%">Project Manager</th>';
			}
	  	} if($user_permission_type[25]->display_type == 1){  
			if(in_array('unconditional_date',$search_per)){
				$message .='<th style="width:2%">Unconditional <br>Date</th>';
			}
	  	} if($user_permission_type[26]->display_type == 1){  
			if(in_array('handover_date',$search_per)){
				$message .='<th style="width:2%">Handover Date</th>';
			}
	  	} if($user_permission_type[27]->display_type == 1){ 
			if(in_array('builder',$search_per)){ 
				$message .='<th style="width:2%">Builder</th>';
			}
	  	} 
		if($user_permission_type[28]->display_type == 1){ 
			if(in_array('consent_out_but_no_builder',$search_per)){ 
				//$message .='<th style="width:2%">Builder Status</th>';
			}
	  	}
		if($user_permission_type[34]->display_type == 1){  
			if(in_array('title_date',$search_per)){
				$message .='<th style="width:2%">Title Date</th>';
			}
	  	} if($user_permission_type[35]->display_type == 1){ 
			if(in_array('settlement_date',$search_per)){ 
				$message .='<th style="width:2%">Settlement Date</th>';
			}
	  	} if($user_permission_type[67]->display_type == 1){ 
			if(in_array('for_sale_sign',$search_per)){ 
				$message .='<th style="width:2%">For Sale Sign</th>';
			}
	  	} if($user_permission_type[68]->display_type == 1){ 
			if(in_array('code_of_compliance',$search_per)){ 
				$message .='<th style="width:2%">Code of Compliance</th>';
			}
	  	} if($user_permission_type[69]->display_type == 1){ 
			if(in_array('photos_taken',$search_per)){ 
				$message .='<th style="width:2%">Photos Taken</th>';
			}
	  	} if($user_permission_type[36]->display_type == 1){ 
			if(in_array('notes',$search_per)){ 
				$message .='<th style="width:2%">Notes</th>';
			}
	  	}  
				
		$message .='</thead>';
		$message .= "<tbody>";

		$total_units = 0;

		for($i=0;$i<count($consent_info);$i++)
		{
			$total_units = $total_units + $consent_info[$i]->no_units;
			$message .= '<tr><td>'.$consent_info[$i]->job_no.'</td>';

			if($user_permission_type[0]->display_type == 1){

				$consent_name_bg = '';
				if($consent_info[$i]->unconditional_date != '0000-00-00'){
					$consent_name_bg = 'style="background-color:white;color:red;"';
				}else if($consent_info[$i]->consent_color=='72D660'){ 
					$consent_name_bg = 'style="background-color:#90ee90"'; 
				}else{ 
					$consent_name_bg = 'style="background-color:#'.$consent_info[$i]->consent_color.'"';
				} 
				if(in_array('consent_name',$search_per)){
					$message .= '<td '.$consent_name_bg.'>'.$consent_info[$i]->consent_name.'</td>';
				}

			} 
			if($user_permission_type[45]->display_type == 1){
				$style = "";
				if($consent_info[$i]->pre_construction_sign ==  ''){ 
					$style = "";
				}elseif($consent_info[$i]->pre_construction_sign ==  'REQ'){ 
					$style = "background-color:red; color:white";
				}elseif($consent_info[$i]->pre_construction_sign == "N/A"){
					$style = "background-color:green; color:white";
				}elseif($consent_info[$i]->pre_construction_sign ==''){
					$style = "background-color:white;";
				}else{
					$style = "background-color:green; color:white";
				}

				if(in_array('pre_construction_sign',$search_per)){
					$message .= '<td style="'.$style.'">'.$consent_info[$i]->pre_construction_sign.'</td>';
				}
			} 
			if($user_permission_type[1]->display_type == 1){
				$text_design = '';
				$bg_design = '';
				
				if($consent_info[$i]->contract_type=='BC'){
					$text_design = 'Allocate';
					$bg_design = 'background-color:green;color:white';
				}elseif($consent_info[$i]->unconditional_date != '0000-00-00' && $consent_info[$i]->approval_date != '0000-00-00'){
					$text_design = 'REQ Brief';
					$bg_design = 'background-color:yellow;color:black';
				}elseif($consent_info[$i]->unconditional_date == '0000-00-00' && $consent_info[$i]->approval_date != '0000-00-00'){
					$text_design = 'Sign';
					$bg_design = 'background-color:orange';
				}elseif($consent_info[$i]->unconditional_date != '0000-00-00' && $consent_info[$i]->approval_date == '0000-00-00'){
					$text_design = 'Allo';
				}elseif($consent_info[$i]->drafting_issue_date != '0000-00-00'){
					$text_design = 'Consent';
					$bg_design = 'background-color:#90ee90';
				}elseif($consent_info[$i]->design =='REQ Brief'){
					$text_design = 'REQ Brief';
					$bg_design = 'background-color:yellow;color:black';
				}elseif($consent_info[$i]->design =='Hold'){
					$text_design = 'Hold';
					$bg_design = 'background-color:red;color:white;';
				}elseif($consent_info[$i]->design =='Brief'){
					$text_design = 'Brief';
					$bg_design = 'background-color:blue;color:white;';
				}elseif($consent_info[$i]->design =='Sign'){
					$text_design = 'Sign';
					$bg_design = 'background-color:orange;color:black';
				}elseif($consent_info[$i]->design =='Consent'){
					$text_design = 'Consent';
					$bg_design = 'background-color:#90ee90';
				}
				if(in_array('design',$search_per)){
					$message .= '<td style="'.$bg_design.'">'.$text_design.'</td>';
				}
			} 
			if($user_permission_type[46]->display_type == 1){
				if(in_array('designer',$search_per)){
					$message .= '<td>'.$consent_info[$i]->designer.'</td>';
				}
			} 
			if($user_permission_type[2]->display_type == 1){
				$bg_design_approval_date = '';
				if($consent_info[$i]->approval_date != '0000-00-00'){
					$bg_design_approval_date = 'background-color:green;color:white;';
				}

				if(in_array('approval_date',$search_per)){
					if($consent_info[$i]->approval_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td style="'.$bg_design_approval_date.'">'.$this->wbs_helper->to_report_date($consent_info[$i]->approval_date).'</td>';
					}
				}

			} 
			if($user_permission_type[47]->display_type == 1){
				$text_ok_to_release_to_marketing = '';
				$bg_ok_to_release_to_marketing = '';

				if($consent_info[$i]->ok_to_release_to_marketing == '' && ( $consent_info[$i]->contract_type == 'EQ' or $consent_info[$i]->contract_type == 'BC')){
					$text_ok_to_release_to_marketing = 'N/A';
					$bg_ok_to_release_to_marketing = '';
				}elseif($consent_info[$i]->ok_to_release_to_marketing == "Yes"){
					$text_ok_to_release_to_marketing = 'Yes';
					$bg_ok_to_release_to_marketing = 'background-color:green;color:white;';
				}elseif($consent_info[$i]->ok_to_release_to_marketing == "No"){
					$text_ok_to_release_to_marketing = 'No';
					$bg_ok_to_release_to_marketing = 'background-color:red;color:white;';
				}

				if(in_array('ok_to_release_to_marketing',$search_per)){
					$message .= '<td style="'.$bg_ok_to_release_to_marketing.'">'.$text_ok_to_release_to_marketing.'</td>';
				}
			} 
			if($user_permission_type[48]->display_type == 1){
				$text_pricing_requested = '';
				$bg_pricing_requested = '';
				$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
				if($consent_info[$i]->pricing_requested == '0000-00-00' && ( $consent_info[$i]->contract_type == 'EQ' or $consent_info[$i]->contract_type == 'BC')){
					$text_pricing_requested = 'N/A';
					$bg_pricing_requested = '';
				}elseif($consent_info[$i]->approval_date!='0000-00-00' && $consent_info[$i]->contract_type == 'HL' && $consent_info[$i]->approval_date > $twenty_days_before && $consent_info[$i]->pricing_requested =='0000-00-00'){
					$text_pricing_requested = "REQ";
					$bg_pricing_requested = 'background-color:red;color:white;';	
				}elseif($consent_info[$i]->approval_date < $twenty_days_before && $consent_info[$i]->approval_date!='0000-00-00' && $consent_info[$i]->pricing_requested =='0000-00-00'){
					$text_pricing_requested = "OVERDUE";
					$bg_pricing_requested = 'background-color:red;color:white;';
				}elseif($consent_info[$i]->pricing_requested == '0000-00-00'){
					$text_pricing_requested = "";
				}else{
					$bg_pricing_requested = 'background-color:green;color:white;';
					$text_pricing_requested = $this->wbs_helper->to_report_date($consent_info[$i]->pricing_requested); 
				}

				if(in_array('pricing_requested',$search_per)){
					$message .= '<td style="'.$bg_pricing_requested.'">'.$text_pricing_requested.'</td>';
				}
			} 
			if($user_permission_type[49]->display_type == 1){
				$text_pricing_for_approval = '';
				$bg_pricing_for_approval = '';
				$five_days_before = date('Y-m-d', strtotime('-5 days'));
				if($consent_info[$i]->pricing_for_approval == '0000-00-00' && ( $consent_info[$i]->contract_type == 'EQ' or $consent_info[$i]->contract_type == 'BC')){
					$text_pricing_for_approval = 'N/A';
					$bg_pricing_for_approval = '';
				}elseif($consent_info[$i]->approval_date!='0000-00-00' && $consent_info[$i]->contract_type == 'HL' && $consent_info[$i]->pricing_requested =='0000-00-00' && $consent_info[$i]->pricing_for_approval=='0000-00-00' ){
					$text_pricing_for_approval = "DUE";
					$bg_pricing_for_approval = "background-color:orange; color:black";
				}elseif($consent_info[$i]->pricing_requested < $five_days_before && $consent_info[$i]->pricing_requested !='0000-00-00' && $consent_info[$i]->pricing_for_approval=='0000-00-00'  ){
					$text_pricing_for_approval = "OVERDUE";
					$bg_pricing_for_approval = 'background-color:red;color:white;';
				}elseif($consent_info[$i]->pricing_for_approval == '0000-00-00'){
					$text_pricing_for_approval = "";
				}else{
					$text_pricing_for_approval = $this->wbs_helper->to_report_date($consent_info[$i]->pricing_for_approval);
					$bg_pricing_for_approval = 'background-color:green;color:white;';
				}

				if(in_array('pricing_for_approval',$search_per)){
					$message .= '<td style="'.$bg_pricing_for_approval.'">'.$text_pricing_for_approval.'</td>';
				}
			} 
			if($user_permission_type[37]->display_type == 1){
				$txt_price_approved_date = '';
				$bg_price_approved_date = '';
				
				if($consent_info[$i]->price_approved_date == '0000-00-00' && ( $consent_info[$i]->contract_type == 'EQ' or $consent_info[$i]->contract_type == 'BC')){
					$txt_price_approved_date = 'N/A';
					$bg_price_approved_date = '';
				}elseif($consent_info[$i]->price_approved_date != '0000-00-00'){
					$txt_price_approved_date = $this->wbs_helper->to_report_date($consent_info[$i]->price_approved_date);
					$bg_price_approved_date = 'background-color:green; color:white;';
				}

				if(in_array('price_approved_date',$search_per)){
					$message .= '<td style="'.$bg_price_approved_date.'">'.$txt_price_approved_date.'</td>';
				}
			} 
			if($user_permission_type[50]->display_type == 1){
				if($consent_info[$i]->approved_for_sale_price!='') {
					$bg_approved_for_sale_price = 'background-color: green; color:white';
					$text_approved_for_sale_price = '$'.$consent_info[$i]->approved_for_sale_price;
				}else{
					$bg_color = '';
					$text = '';
				}
				if(in_array('approved_for_sale_price',$search_per)){
					$message .= '<td style="'.$bg_approved_for_sale_price.'">'.$text_approved_for_sale_price.'</td>';
				}
			} 
			if($user_permission_type[51]->display_type == 1){
				$text_kitchen_disign_type = '';
				$bg_kitchen_disign_type = '';

				if($consent_info[$i]->kitchen_disign_type == '' && ( $consent_info[$i]->contract_type == 'EQ' or $consent_info[$i]->contract_type == 'BC')){		
					$text_kitchen_disign_type = 'N/A';						
					$bg_kitchen_disign_type = '';
				}elseif($consent_info[$i]->kitchen_disign_type != ''){	
					$text_kitchen_disign_type = $consent_info[$i]->kitchen_disign_type;
					$bg_kitchen_disign_type = 'background-color:green; color:white;';
				}

				if(in_array('kitchen_disign_type',$search_per)){
					$message .= '<td style="'.$bg_kitchen_disign_type.'">'.$text_kitchen_disign_type.'</td>';
				}
			} 
			if($user_permission_type[52]->display_type == 1){
				$text_kitchen_disign_requested = '';
				$bg_kitchen_disign_requested = '';
				$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
				$pricing_for_approval_date = $this->wbs_helper->change_cms_date($consent_info[$i]->pricing_for_approval);

				if($consent_info[$i]->kitchen_disign_requested == '' && ( $consent_info[$i]->contract_type == 'EQ' or $consent_info[$i]->contract_type == 'BC')){		
					$text_kitchen_disign_requested = 'N/A';						
					$bg_kitchen_disign_requested = '';
				}elseif( $consent_info[$i]->ok_to_release_to_marketing == 'Yes' && $consent_info[$i]->pricing_for_approval !='0000-00-00' && $twenty_days_before < $consent_info[$i]->approval_date){
					$text_kitchen_disign_requested = "REQ";
					$bg_kitchen_disign_requested = "background-color:red;color:white;";	
				}elseif($twenty_days_before > $consent_info[$i]->approval_date && $consent_info[$i]->approval_date !='0000-00-00' ){
					$text_kitchen_disign_requested = "OVERDUE"; 
					$bg_kitchen_disign_requested = "background-color:red;color:white;";
				}elseif($consent_info[$i]->kitchen_disign_requested == ''){
					$text_kitchen_disign_requested = '';
				}else{
					$text_kitchen_disign_requested = $consent_info[$i]->kitchen_disign_requested;
				}

				if(in_array('kitchen_disign_requested',$search_per)){
					$message .= '<td style="'.$bg_kitchen_disign_requested.'">'.$text_kitchen_disign_requested.'</td>';
				}
			} 
			if($user_permission_type[53]->display_type == 1){
				$text_colours_requested_and_loaded_on_gc = '';
				$bg_colours_requested_and_loaded_on_gc = '';

				$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
				$pricing_for_approval_date = $this->wbs_helper->change_cms_date($consent_info[$i]->pricing_for_approval);

				if( $consent_info[$i]->ok_to_release_to_marketing == 'Yes' && $consent_info[$i]->pricing_for_approval !='0000-00-00' && $consent_info[$i]->colours_requested_and_loaded_on_gc == '0000-00-00' && $twenty_days_before < $consent_info[$i]->approval_date){
					$text_colours_requested_and_loaded_on_gc = "REQ";
					$bg_colours_requested_and_loaded_on_gc = "background-color:red;color:white;";
				}elseif($twenty_days_before > $consent_info[$i]->approval_date && $consent_info[$i]->approval_date != '0000-00-00' ){
					$text_colours_requested_and_loaded_on_gc = "OVERDUE"; 
					$bg_colours_requested_and_loaded_on_gc = "background-color:red;color:white;";
				}elseif($consent_info[$i]->colours_requested_and_loaded_on_gc=='0000-00-00'){
					$text_colours_requested_and_loaded_on_gc = '';
				}else{
					$text_colours_requested_and_loaded_on_gc = $this->wbs_helper->to_report_date($consent_info[$i]->colours_requested_and_loaded_on_gc);
					$bg_colours_requested_and_loaded_on_gc = "background-color:green;color:white;";
				}

				if(in_array('colours_requested_and_loaded_on_gc',$search_per)){
					$message .= '<td style="'.$bg_colours_requested_and_loaded_on_gc.'">'.$text_colours_requested_and_loaded_on_gc.'</td>';
				}
			} 
			if($user_permission_type[54]->display_type == 1){
				$text_kitchen_design_loaded_on_gc = '';
				$bg_kitchen_design_loaded_on_gc = '';
				if($consent_info[$i]->kitchen_disign_requested == 'REQ' OR($consent_info[$i]->ok_to_release_to_marketing == 'Yes' && $twenty_days_before < $consent_info[$i]->approval_date)){
					$text_kitchen_design_loaded_on_gc = "REQ";
					$bg_kitchen_design_loaded_on_gc = "background-color:red;color:white;";
				}else{
					$text_kitchen_design_loaded_on_gc = $consent_info[$i]->kitchen_design_loaded_on_gc; 
				}
				if(in_array('kitchen_design_loaded_on_gc',$search_per)){
					$message .= '<td style="'.$bg_kitchen_design_loaded_on_gc.'">'.$text_kitchen_design_loaded_on_gc.'</td>';
				}
			} 
			if($user_permission_type[55]->display_type == 1){
				$text_developer_colour_sheet_created = '';
				$bg_developer_colour_sheet_created = '';

				$ten_days_before = date('Y-m-d', strtotime('-10 days'));
				
				if( $consent_info[$i]->colours_requested_and_loaded_on_gc != '0000-00-00' && $consent_info[$i]->colours_requested_and_loaded_on_gc > $ten_days_before  && $consent_info[$i]->developer_colour_sheet_created == '0000-00-00' ){
					$text_developer_colour_sheet_created = "REQ";
					$bg_developer_colour_sheet_created = "background-color:red;color:white;";
				}elseif($consent_info[$i]->colours_requested_and_loaded_on_gc < $ten_days_before && $consent_info[$i]->colours_requested_and_loaded_on_gc!='0000-00-00'  && $consent_info[$i]->developer_colour_sheet_created == '0000-00-00' ){
					$text_developer_colour_sheet_created = "OVERDUE";
					$bg_developer_colour_sheet_created = "background-color:red;color:white;";
				}elseif($consent_info[$i]->developer_colour_sheet_created == '0000-00-00'){
					$text_developer_colour_sheet_created = "";
				}else{
					$text_developer_colour_sheet_created = $this->wbs_helper->to_report_date($consent_info[$i]->developer_colour_sheet_created); 
					$bg_developer_colour_sheet_created = "background-color:green;color:white;";
				}
				if(in_array('developer_colour_sheet_created',$search_per)){
					$message .= '<td style="'.$bg_developer_colour_sheet_created.'">'.$text_developer_colour_sheet_created.'</td>';
				}
			} 
			if($user_permission_type[56]->display_type == 1){
				$text_spec_loaded_on_gc = '';
				$bg_spec_loaded_on_gc = '';

				$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
				if($consent_info[$i]->price_approved_date!='0000-00-00'){
					if($consent_info[$i]->contract_type == 'HL' & $consent_info[$i]->price_approved_date > $twenty_days_before){
						$text_spec_loaded_on_gc = "REQ";
						$bg_spec_loaded_on_gc = "background-color:red;color:white;";
					}
					elseif($consent_info[$i]->price_approved_date < $twenty_days_before ){
							$text_spec_loaded_on_gc = "OVERDUE";
							$bg_spec_loaded_on_gc = "background-color:red;color:white;";
					}	
				}else{
					$text_spec_loaded_on_gc = $consent_info[$i]->spec_loaded_on_gc; 
				}

				if(in_array('spec_loaded_on_gc',$search_per)){
					$message .= '<td style="'.$bg_spec_loaded_on_gc.'">'.$text_spec_loaded_on_gc.'</td>';
				}
			} 
			if($user_permission_type[57]->display_type == 1){
				$text_loaded_on_intranet = '';
				$bg_loaded_on_intranet = '';

				$ten_days_before = date('Y-m-d', strtotime('-10 days'));
				if($consent_info[$i]->price_approved_date!='0000-00-00'){
					if( $ten_days_before > $consent_info[$i]->price_approved_date){
						$text_loaded_on_intranet = "OVERDUE";
						$bg_loaded_on_intranet = "background-color:red;color:white;";
					}else{
						$text_loaded_on_intranet = "REQ";
						$bg_loaded_on_intranet = "background-color:red;color:white;";
					}
				}else{
					$text_loaded_on_intranet = $consent_info[$i]->loaded_on_intranet; 

				}

				if(in_array('loaded_on_intranet',$search_per)){
					$message .= '<td style="'.$bg_loaded_on_intranet.'">'.$text_loaded_on_intranet.'</td>';
				}
			} 
			if($user_permission_type[58]->display_type == 1){
				$text_website = '';
				$bg_website = '';

				$ten_days_before = date('Y-m-d', strtotime('-10 days'));
				if($consent_info[$i]->price_approved_date!='0000-00-00'){
					if( $ten_days_before > $consent_info[$i]->price_approved_date){
						$text_website = "OVERDUE";
						$bg_website = "background-color:red;color:white;";
					}else{
						$text_website = "REQ";
						$bg_website = "background-color:red;color:white;";
					}
				}else{
					$text_website = $consent_info[$i]->website; 
				}

				if(in_array('website',$search_per)){
					$message .= '<td style="'.$bg_website.'">'.$text_website.'</td>';
				}
			} 
			if($user_permission_type[59]->display_type == 1){
				$text_render_requested = '';
				$bg_render_requested = '';

				if($consent_info[$i]->colours_requested_and_loaded_on_gc != '0000-00-00'  && $consent_info[$i]->render_requested == '0000-00-00' ){ 
					$text_render_requested = "REQ";	
					$bg_render_requested = "background-color:red;color:white;";
				}elseif($consent_info[$i]->render_requested == '0000-00-00'){
					$text_render_requested = '';
				}else{
					$bg_render_requested = "background-color:green;color:white;";
					$text_render_requested = $this->wbs_helper->to_report_date($consent_info[$i]->render_requested); 
				}

				if(in_array('render_requested',$search_per)){
					$message .= '<td style="'.$bg_render_requested.'">'.$text_render_requested.'</td>';
				}
			} 
			if($user_permission_type[60]->display_type == 1){
				$text_render_received = '';
				$bg_render_received = '';

				$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
				 
				if( $twenty_days_before > $consent_info[$i]->render_requested && $consent_info[$i]->render_requested != '0000-00-00' && $consent_info[$i]->render_received == '0000-00-00'){
					$text_render_received = "OVERDUE";
					$bg_render_received = "background-color:red;color:white;";
				}elseif($twenty_days_before < $consent_info[$i]->render_requested && $consent_info[$i]->render_requested != '0000-00-00' && $consent_info[$i]->render_received == '0000-00-00'){
					$text_render_received =  "REQ";
					$bg_render_received = "background-color:red;color:white;";
				}elseif($consent_info[$i]->render_received == '0000-00-00'){
					$text_render_received =  "";
				}else{
					$text_render_received = $this->wbs_helper->to_report_date($consent_info[$i]->render_received); 
					$bg_render_received = "background-color:green;color:white;";
				}

				if(in_array('render_received',$search_per)){
					$message .= '<td style="'.$bg_render_received.'">'.$text_render_received.'</td>';
				}
			} 
			if($user_permission_type[61]->display_type == 1){
				$text_brochure = '';
				$bg_brochure = '';

				$ten_days_before = date('Y-m-d', strtotime('-10 days'));
				
				if( $ten_days_before > $consent_info[$i]->render_received && $consent_info[$i]->render_received != '0000-00-00'){
					$text_brochure = "OVERDUE";
					$bg_brochure = "background-color:red;color:white;";
				}elseif($ten_days_before < $consent_info[$i]->render_received && $consent_info[$i]->render_received != '0000-00-00'){
					$text_brochure = "REQ";
					$bg_brochure = "background-color:red;color:white;";
				}else{
					$text_brochure = $consent_info[$i]->brochure; 
				}

				if(in_array('brochure',$search_per)){
					$message .= '<td style="'.$bg_brochure.'">'.$text_brochure.'</td>';
				}
			} 
			if($user_permission_type[3]->display_type == 1){
				if(in_array('pim_logged',$search_per)){
					if($consent_info[$i]->pim_logged == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->pim_logged).'</td>';
					}
				}
			} 


			if($user_permission_type[6]->display_type == 1){
				if(in_array('drafting_issue_date',$search_per)){
					if($consent_info[$i]->drafting_issue_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td style="background-color:#90ee90; color:white;">'.$this->wbs_helper->to_report_date($consent_info[$i]->drafting_issue_date).'</td>';
					}
				}
			} 
			if($user_permission_type[7]->display_type == 1){
				if(in_array('consent_by',$search_per)){
					$message .= '<td>'.$consent_info[$i]->consent_by.'</td>';
				}
			}
			if($user_permission_type[8]->display_type == 1){

				$txt = '';
				$style = '';
				$fourteen_days_before = date('Y-m-d', strtotime('-14 days'));
				$thirty_days_before = date('Y-m-d', strtotime('-30 days'));
				$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
				if( $consent_info[$i]->unconditional_date!='0000-00-00' && $consent_info[$i]->unconditional_date < $fourteen_days_before && $consent_info[$i]->drafting_issue_date == '0000-00-00'){ $style = 'style="background-color:red; color:white;"'; $txt = 'Issue For Consent'; }
				if( $consent_info[$i]->drafting_issue_date < $thirty_days_before && $consent_info[$i]->drafting_issue_date != '0000-00-00'){$style = 'style="background-color:red; color:white;"'; $txt = 'Drawings Late'; }
				if( $consent_info[$i]->date_logged != '0000-00-00' && $consent_info[$i]->date_logged  < $twenty_days_before ){ $style = 'style="background-color:red; color:white;"'; $txt = 'Consent Late';}
			
				if(in_array('action_required',$search_per)){
					$message .= '<td '.$style.'>'.$txt.'</td>';
				}
			}
			if($user_permission_type[9]->display_type == 1){
				if(in_array('council',$search_per)){
					$message .= '<td>'.$consent_info[$i]->council.'</td>';
				}
			}
			if($user_permission_type[29]->display_type == 1){
				if(in_array('lbp',$search_per)){
					$message .= '<td>'.$consent_info[$i]->lbp.'</td>';
				}
			}
			if($user_permission_type[30]->display_type == 1){
				if(in_array('date_job_checked',$search_per)){
					if($consent_info[$i]->date_job_checked == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->date_job_checked).'</td>';
					}
				}
			} 
			if($user_permission_type[10]->display_type == 1){
				if(in_array('bc_number',$search_per)){
					$message .= '<td>'.$consent_info[$i]->bc_number.'</td>';
				}
			} 
			if($user_permission_type[11]->display_type == 1){
				if(in_array('no_units',$search_per)){
					$message .= '<td>1</td>';
				}
			} 
			if($user_permission_type[12]->display_type == 1){
				if(in_array('contract_type',$search_per)){
					$message .= '<td>'.$consent_info[$i]->contract_type.'</td>';
				}
			} 
			if($user_permission_type[13]->display_type == 1){
				if(in_array('type_of_build',$search_per)){
					$message .= '<td>'.$consent_info[$i]->type_of_build.'</td>';
				}
			} 
			if($user_permission_type[14]->display_type == 1){
				if(in_array('variation_pending',$search_per)){				
					$variation_pending_bg = '';
					if($consent_info[$i]->variation_pending=='Yes'){ $variation_pending_bg = 'style="background-color:red; color:white"';}
					$message .= '<td '.$variation_pending_bg.'>'.$consent_info[$i]->variation_pending.'</td>';
				}
			} 
			if($user_permission_type[15]->display_type == 1){
				if(in_array('foundation_type',$search_per)){
					$message .= '<td>'.$consent_info[$i]->foundation_type.'</td>';
				}
			} 
			if($user_permission_type[62]->display_type == 1){
				if(in_array('resource_consent',$search_per)){
					$message .= '<td>'.$consent_info[$i]->resource_consent.'</td>';
				}
			} 
			if($user_permission_type[63]->display_type == 1){
				if(in_array('rc_number',$search_per)){
					$message .= '<td>'.$consent_info[$i]->rc_number.'</td>';
				}
			} 
			if($user_permission_type[64]->display_type == 1){
				if(in_array('expected_date_to_lodge_bc',$search_per)){
					if($consent_info[$i]->expected_date_to_lodge_bc == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->expected_date_to_lodge_bc).'</td>';
					}
				}
			} 
			if($user_permission_type[16]->display_type == 1){
				if(in_array('date_logged',$search_per)){
					if($consent_info[$i]->date_logged == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td style="background-color:#90ee90; color:white;">'.$this->wbs_helper->to_report_date($consent_info[$i]->date_logged).'</td>';
					}
				}
			} 
			if($user_permission_type[17]->display_type == 1){
				if(in_array('date_issued',$search_per)){
					if($consent_info[$i]->date_issued == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td style="background-color:#90ee90; color:white;">'.$this->wbs_helper->to_report_date($consent_info[$i]->date_issued).'</td>';
					}
				}
			} 
			if($user_permission_type[18]->display_type == 1){
				if(in_array('actual_date_issued',$search_per)){
					if($consent_info[$i]->actual_date_issued == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->actual_date_issued).'</td>';
					}
				}
			} 
			if($user_permission_type[18]->display_type == 1){

				if($consent_info[$i]->date_logged != '0000-00-00' && $consent_info[$i]->date_issued != '0000-00-00'){ 
					$days_in_council = $this->consent_model->get_working_days($consent_info[$i]->date_logged, $consent_info[$i]->date_issued) - 1; 
				}else if($consent_info[$i]->date_logged != '0000-00-00' && $consent_info[$i]->date_issued == '0000-00-00'){ 
					$days_in_council = $this->consent_model->get_working_days($consent_info[$i]->date_logged, date('Y-m-d')) - 1; 
				}else{ 
					$days_in_council = '0'; 
				}

				if(in_array('day_in_council',$search_per)){				
					$message .= '<td>'.$days_in_council.'</td>';
				}
			} 
			if($user_permission_type[65]->display_type == 1){
				if(in_array('water_connection',$search_per)){
					$message .= '<td>'.$consent_info[$i]->water_connection.'</td>';
				}
			} 
			if($user_permission_type[66]->display_type == 1){
				if(in_array('vehicle_crossing',$search_per)){
					$message .= '<td>'.$consent_info[$i]->vehicle_crossing.'</td>';
				}
			} 
			if($user_permission_type[19]->display_type == 1){
				$text_order_site_levels = '';
				$bg_order_site_levels = '';

				if($consent_info[$i]->order_site_levels == '' && ($consent_info[$i]->contract_type == 'BC' or $consent_info[$i]->contract_type == 'EQ') ){
					$text_order_site_levels = "REQ";
					$bg_order_site_levels = 'background-color:red; color:white';
				}elseif($consent_info[$i]->order_site_levels == 'Received'){
					$text_order_site_levels = "Received";
					$bg_order_site_levels = 'background-color:green; color:white';
				}elseif($consent_info[$i]->order_site_levels == 'Sent'){
					$text_order_site_levels = "Sent";
					$bg_order_site_levels = 'background-color:blue; color:white';
				}elseif($consent_info[$i]->order_site_levels == 'N/A'){
					$text_order_site_levels = "N/A";
					$bg_order_site_levels = 'background-color:white; color:black';
				}

				if(in_array('order_site_levels',$search_per)){	
					$message .= '<td style="'.$bg_order_site_levels.'">'.$text_order_site_levels.'</td>';
				}
			} 
			if($user_permission_type[20]->display_type == 1){
				$text_order_soil_report = '';
				$bg_order_soil_report = '';

				if($consent_info[$i]->order_soil_report == '' && ($consent_info[$i]->contract_type == 'BC' or $consent_info[$i]->contract_type == 'EQ') ){
					$text_order_soil_report = "REQ";
					$bg_order_soil_report = 'background-color:red; color:white';
				}elseif($consent_info[$i]->order_soil_report == 'Received'){
					$text_order_soil_report = "Received";
					$bg_order_soil_report = 'background-color:green; color:white';
				}elseif($consent_info[$i]->order_soil_report == 'Sent'){
					$text_order_soil_report = "Sent";
					$bg_order_soil_report = 'background-color:blue; color:white';
				}elseif($consent_info[$i]->order_soil_report == 'N/A'){
					$text_order_soil_report = "N/A";
					$bg_order_soil_report = 'background-color:white; color:black';
				}

				if(in_array('order_soil_report',$search_per)){
					$message .= '<td style="'.$bg_order_soil_report.'">'.$text_order_soil_report.'</td>';
				}
			} 
			if($user_permission_type[21]->display_type == 1){
				$text_septic_tank_approval = '';
				$bg_septic_tank_approval = '';

				if($consent_info[$i]->septic_tank_approval == '' && ($consent_info[$i]->contract_type == 'BC' or $consent_info[$i]->contract_type == 'EQ') ){
					$text_septic_tank_approval = "REQ";
					$bg_septic_tank_approval = 'background-color:red; color:white';
				}elseif($consent_info[$i]->septic_tank_approval == 'RECEIVED'){
					$text_septic_tank_approval = "Received";
					$bg_septic_tank_approval = 'background-color:green; color:white';
				}elseif($consent_info[$i]->septic_tank_approval == 'SENT'){
					$text_septic_tank_approval = "SENT";
					$bg_septic_tank_approval = 'background-color:blue; color:white';
				}elseif($consent_info[$i]->septic_tank_approval == 'N/A'){
					$text_septic_tank_approval = "N/A";
					$bg_septic_tank_approval = 'background-color:white; color:black';
				}

				if(in_array('septic_tank_approval',$search_per)){
					$message .= '<td style="'.$bg_septic_tank_approval.'">'.$text_septic_tank_approval.'</td>';
				}
			} 
			if($user_permission_type[70]->display_type == 1){
				$text_drainage_testing = '';
				$bg_drainage_testing = '';

				if($consent_info[$i]->drainage_testing == '' && ($consent_info[$i]->contract_type == 'BC' or $consent_info[$i]->contract_type == 'EQ') ){
					$text_drainage_testing = "REQ";
					$bg_drainage_testing = 'background-color:red; color:white';
				}else{
					$text_drainage_testing = $consent_info[$i]->drainage_testing;
					$bg_drainage_testing = 'background-color:green; color:white';
				}

				if(in_array('drainage_testing',$search_per)){
					$message .= '<td style="'.$bg_drainage_testing.'">'.$text_drainage_testing.'</td>';
				}
			} 
			if($user_permission_type[22]->display_type == 1){
				$text_dev_approval = '';
				$bg_dev_approval = '';

				if(($consent_info[$i]->dev_approval == '' or $consent_info[$i]->dev_approval == 'REQ') && ($consent_info[$i]->contract_type == 'BC' or $consent_info[$i]->contract_type == 'EQ') ){
					$text_dev_approval = "REQ";
					$bg_dev_approval = 'background-color:red; color:white';
				}elseif($consent_info[$i]->dev_approval == 'FULL SENT'){
					$text_dev_approval = "FULL SENT";
					$bg_dev_approval = 'background-color:blue; color:white';
				}elseif($consent_info[$i]->dev_approval == 'FULL REC'){
					$text_dev_approval = "FULL REC";
					$bg_dev_approval = 'background-color:green; color:white';
				}elseif($consent_info[$i]->dev_approval == 'N/A'){
					$text_dev_approval = "N/A";
					$bg_dev_approval = 'background-color:white; color:black';
				}elseif($consent_info[$i]->dev_approval == 'PRE SENT'){
					$text_dev_approval = "PRE SENT";
					$bg_dev_approval = 'background-color:yellow; color:black';
				}elseif($consent_info[$i]->dev_approval == ''){
					$text_dev_approval = "";
					$bg_dev_approval = 'background-color:blue;';
				}elseif($consent_info[$i]->dev_approval == 'PRE REC'){
					$text_dev_approval = "PRE REC";
					$bg_dev_approval = 'background-color:yellow; color:black;';
				}

				if(in_array('dev_approval',$search_per)){
					$message .= '<td style="'.$bg_dev_approval.'">'.$text_dev_approval.'</td>';
				}
			} 
			if($user_permission_type[32]->display_type == 1){
				$text_landscape = '';
				$bg_landscape = '';

				if(($consent_info[$i]->landscape == '' or $consent_info[$i]->landscape == 'REQ') && ($consent_info[$i]->contract_type == 'BC' or $consent_info[$i]->contract_type == 'EQ') ){
					$text_landscape = "REQ";
					$bg_landscape = 'background-color:red; color:white';
				}elseif($consent_info[$i]->landscape == 'RECEIVED'){
					$text_landscape = "Received";
					$bg_landscape = 'background-color:green; color:white';
				}elseif($consent_info[$i]->landscape == 'SENT'){
					$text_landscape = "SENT";
					$bg_landscape = 'background-color:blue; color:white';
				}elseif($consent_info[$i]->landscape == 'N/A'){
					$text_landscape = "N/A";
					$bg_landscape = 'background-color:white; color:black';
				}

				if(in_array('landscape',$search_per)){
					$message .= '<td style="'.$bg_landscape.'">'.$text_landscape.'</td>';
				}
			} 
			if($user_permission_type[33]->display_type == 1){
				$text_mss = '';
				$bg_mss = '';

				if($consent_info[$i]->mss == '' && $consent_info[$i]->drafting_issue_date !='0000-00-00'){
					$text_mss = 'REQ';
					$bg_mss = 'background-color:red; color:white';
				}elseif($consent_info[$i]->mss == 'REQ'){
					$text_mss = 'REQ';
					$bg_mss = 'background-color:red; color:white';
				}elseif($consent_info[$i]->mss == 'DONE'){
					$text_mss = 'DONE';
					$bg_mss = 'background-color:#90ee90; color:white';
				}

				if(in_array('mss',$search_per)){
					$message .= '<td style="'.$bg_mss.'">'.$text_mss.'</td>';
				}
			} 
			if($user_permission_type[23]->display_type == 1){
				if(in_array('project_manager',$search_per)){
					$message .= '<td>'.$consent_info[$i]->project_manager.'</td>';
				}
			} 
			if($user_permission_type[25]->display_type == 1){
				if(in_array('unconditional_date',$search_per)){
					if($consent_info[$i]->unconditional_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}
					else{
						$message .= '<td style="background-color:green; color:white;">'.$this->wbs_helper->to_report_date($consent_info[$i]->unconditional_date).'</td>';
					}
				}
			} 
			if($user_permission_type[26]->display_type == 1){
				if(in_array('handover_date',$search_per)){
					if($consent_info[$i]->handover_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td style="background-color:green; color:white;">'.$this->wbs_helper->to_report_date($consent_info[$i]->handover_date).'</td>';
					}
				}
			} 
			if($user_permission_type[27]->display_type == 1){
				if(in_array('builder',$search_per)){
					$message .= '<td>'.$consent_info[$i]->builder.'</td>';
				}
			} 

			if($user_permission_type[28]->display_type == 1){

				$builder_txt = '';
				if($consent_info[$i]->builder == ''){
					$builder_txt = "Need Builder";
				}else{
					$builder_txt = $consent_info[$i]->consent_out_but_no_builder;
				}

				if(in_array('consent_out_but_no_builder',$search_per)){
					//$message .= '<td>'.$builder_txt.'</td>';
				}
			} 

			if($user_permission_type[34]->display_type == 1){
				$bg_title_date = '';
				if($consent_info[$i]->title_date !='0000-00-00'){
					$bg_title_date = 'background-color:#008000; color:white';
				}else{
					$bg_title_date = 'background-color:#ffffff; color:black';
				}

				if(in_array('title_date',$search_per)){
					if($consent_info[$i]->title_date == '0000-00-00'){
						$message .= '<td style="'.$bg_title_date.'">&nbsp;</td>';
					}else{
						$message .= '<td style="'.$bg_title_date.'">'.$this->wbs_helper->to_report_date($consent_info[$i]->title_date).'</td>';
					}
				}
			} 
			if($user_permission_type[35]->display_type == 1){
				if(in_array('settlement_date',$search_per)){
					if($consent_info[$i]->settlement_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$settlement_date = strtotime($consent_info[$i]->title_date) + ($consent_info[$i]->settlement_date * 24 * 60 * 60);
						
						$message .= '<td style="background-color:orange; color:black;">'.date("d-m-y",$settlement_date).'</td>';
					}
				}
			} 
			if($user_permission_type[67]->display_type == 1){
				$text_for_sale_sign = '';
				$bg_for_sale_sign = '';

				$ten_days_before = date('Y-m-d', strtotime('-10 days'));
				
				if( $ten_days_before > $consent_info[$i]->render_received && $consent_info[$i]->render_received !='0000-00-00' && $consent_info[$i]->for_sale_sign == ''){
					$text_for_sale_sign = "OVERDUE";
					$bg_for_sale_sign = "background-color:red; color:white";
				}elseif($ten_days_before < $consent_info[$i]->render_received && $consent_info[$i]->render_received !='0000-00-00' && $consent_info[$i]->for_sale_sign == ''){
					$text_for_sale_sign = "REQ";
					$bg_for_sale_sign = "background-color:red; color:white";
				}else{
					$text_for_sale_sign = $consent_info[$i]->for_sale_sign; 
				}
				if(in_array('for_sale_sign',$search_per)){
					$message .= '<td style="'.$bg_for_sale_sign.'">'.$text_for_sale_sign.'</td>';
				}
			} 
			if($user_permission_type[68]->display_type == 1){
				if(in_array('code_of_compliance',$search_per)){
					$message .= '<td>'.$consent_info[$i]->code_of_compliance.'</td>';
				}
			} 
			if($user_permission_type[69]->display_type == 1){
				$text_photos_taken = '';
				$bg_photos_taken = '';

				if($consent_info[$i]->handover_date != '0000-00-00'){ 
					$text_photos_taken = "REQ"; 
					$bg_photos_taken = "background-color:red; color:white";
				}else{ 
					$text_photos_taken = $consent_info[$i]->photos_taken; 
				} 

				if(in_array('handover_date',$search_per)){
					$message .= '<td style="'.$bg_photos_taken.'">'.$text_photos_taken.'</td>';
				}
			} 
			if($user_permission_type[36]->display_type == 1){
				if(in_array('notes',$search_per)){
					$message .= '<td>'.$consent_info[$i]->notes.'</td>';
				}
			}

			$message .= "</tr>";
		
		}
		
		$message .= "</tbody>";
		$data['report_message'] = $message;
		$data['total_units'] = $total_units;
		$data['maincontent'] = $this->load->view('consent/consent_report',$data,true);		
		$this->load->view('includes/header',$data);
		$this->load->view('home',$data);
		$this->load->view('includes/footer',$data);
		
	}

	public function consent_report_print()
	{

		$keywords = $this->session->userdata('report_keywords');
		$ss = $this->session->userdata('report_consent_fields');
		$report_search_value = $this->session->userdata('report_search_value');

		$start_date = $this->session->userdata('report_consent_f_month');
		$end_date = $this->session->userdata('report_consent_l_month');

		$user_info = $this->consent_model->user_option();
		
		$user =  $this->session->userdata('user');
   
		$user_group_id = $this->session->userdata('user_group_id');
		
		$data['user_permission_type'] = $this->consent_model->get_user_permission_type($user_group_id);
					
		$data['consent_info'] = $this->consent_model->get_consent_report($start_date,$end_date,$keywords,$ss,$report_search_value);
		//echo count($consent_info);
		//print_r($ss);

		if($ss!=''){
			$data['search_per'] = explode(',',$ss);
		}else{
			$cu = 'photos_taken,code_of_compliance,for_sale_sign,vehicle_crossing,water_connection,expected_date_to_lodge_bc,rc_number,resource_consent,brochure,render_received,render_requested,website,loaded_on_intranet,spec_loaded_on_gc,developer_colour_sheet_created,kitchen_design_loaded_on_gc,colours_requested_and_loaded_on_gc,kitchen_disign_requested,kitchen_disign_type,approved_for_sale_price,pricing_for_approval,pricing_requested,ok_to_release_to_marketing,designer,pre_construction_sign,job_no,consent_name,design,approval_date,price_approved_date,pim_logged,drafting_issue_date,consent_by,action_required,council,lbp,date_job_checked,bc_number,no_units,contract_type,type_of_build,variation_pending,foundation_type,date_logged,date_issued,actual_date_issued,order_site_levels,order_soil_report,septic_tank_approval,dev_approval,landscape,mss,project_manager,unconditional_date,handover_date,builder,consent_out_but_no_builder,title_date,settlement_date,notes';
			$data['search_per'] = explode(',',$cu);	
		}

		$this->load->view('consent/consent_report_print',$data);	

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
					if($consent_info[$i]->title_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->title_date).'</td>';
					}
				}
				if($user_permission_type[28]->display_type == 1){
					if($consent_info[$i]->settlement_date == '0000-00-00'){
						$message .= '<td>&nbsp;</td>';
					}else{
						$message .= '<td>'.$this->wbs_helper->to_report_date($consent_info[$i]->settlement_date).'</td>';
					}
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
	function consent_delete($tr_id,$tab_id){
		$c_ids = explode('_',$tr_id);
		$consent_id = $c_ids[2]; 

		$this->consent_model->consent_delete($consent_id);	
		redirect('consent/consent_list?tab_id='.$tab_id);
	}

	function send_consent_mail($job_id,$field_name,$job_no,$consent_name,$data=''){
		$this->consent_model->send_consent_mail($job_id,$field_name,$job_no,$consent_name,$data);
	}


}
?>