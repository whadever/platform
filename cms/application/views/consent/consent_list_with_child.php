<style>
select{width: 90px;}
input[type="text"]{ width: 90px;}
report-link{background: none repeat scroll 0 0 #eee;border-radius: 3px;color: #000;padding: 3px 7px;}
.report-link:hover{text-decoration:none;color: #000;}
input.form-text{width:80%}
@media screen and (max-width:1280px){
	.consent_table{width:250%; max-width:250% !IMPORTANT}
}
@media screen and (min-width:1300px){
	.consent_table{width:120%; max-width:120% !IMPORTANT}
}

#create_report select{width:70%}
#create_report input[type="text"]{ width: 96%;border-radius: 5px;height: 20px; border: 1px solid #eee}
#search-button {
    background: none repeat scroll 0 0 #fff;
    border: 2px solid #eee;
    line-height: 20px;
    width: 86px;
    font-weight: bold;
	display:inline-block;	
}
#clear-search-button{
    background: none repeat scroll 0 0 #fff;
    border: 2px solid #eee;
    line-height: 20px;
    width: 86px;
    font-weight: bold;
	padding: 3px 5px;
	font-size: 12px;
	margin-right:10px;
	display:inline-block;
	text-align:center;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc;
}
#search_box .ui-multiselect{
	padding: 2px 5px;
	background:#fff;
}
#search_box .ui-multiselect .ui-icon{
	height:15px;
	width:15px;
}
#search_box .ui-state-default .ui-icon{
	background-image:url("../images/ui-icons_222222_256x240.png");
}
.ui-multiselect-header ul li{
	padding:0 5px 0 0 !important;
}
.ui-multiselect-header ul{
	font-size:0.8em !important;
}
#consent_fields {
    border: 1px solid #d1d2d4;
    border-radius: 5px;
    padding: 5px;
    width: 100%;
}
#search_value {
    border: 1px solid #d1d2d4;
    border-radius: 5px;
    padding: 6px;
    width: 94%;
}
#search_value_select {
    border: 1px solid #d1d2d4;
    border-radius: 5px;
    padding: 5px;
    width: 97%;
}
#search_value_date {
    border: 1px solid #d1d2d4;
    border-radius: 5px;
    padding: 6px;
    width: 44%;
}
#search_value_date1 {
    border: 1px solid #d1d2d4;
    border-radius: 5px;
    margin-left: 2%;
    padding: 6px;
    width: 44%;
}
input.live_datepicker {
    border: 1px solid #d1d2d4;
    border-radius: 5px;
    margin-right: 1%;
    padding: 6px;
    width: 44%;
}
.colunm3 {
    margin-bottom: 8px;
}
</style>
<?php

	$ci = &get_instance();
	$ci->load->model('consent_model');
	
	$sflag = 0;
	$eflag = 0;

	if($active_tabs){
		$active_tab_ids = $active_tabs->tab_ids;
		$tab_ids = explode('_',$active_tab_ids);
	}
	else
	{
		$tab_ids = '';
	}

	$this->load->library('session');

	$f_m_t_m = $ci->consent_model->get_from_month_to_month();
	$get_from_month = $f_m_t_m->from_month;	
	$get_too_month = $f_m_t_m->to_month;	
	
	if(isset($_POST['from_month']))
	{
		$start_month = $_POST['from_month']; 
		$end_month = $_POST['to_month'];
		$smonths['consent_f_month'] = $start_month;
		$smonths['consent_l_month'] = $end_month;

		if(strlen($start_month)>2 && strlen($end_month)>2)
		{
			
			$search['start_date'] = $start_month;
			$now = date("Y-m-d");
			$start_month = str_replace("-","/",$start_month);
			$dates = explode("/",$start_month);
			$start_month = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
			$start_month = date("Y-m-d", strtotime($start_month)); 

			if($start_month > $now)
			{
			
				$diff = abs(strtotime($start_month) - strtotime($now));
				$years = floor($diff / (365*60*60*24));// for year difference
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));	
				$smonths['f_month'] = - $months;
			}
			else
			{
				$d1 = new DateTime(date("Y-m-d"));
				$d2 = new DateTime(date("Y-m-d", strtotime($start_month)));
	
				$interval = $d2->diff($d1);
				$em = $interval->format('%m');
				$smonths['f_month'] = (int) $em;
			}

			$sflag = 1;
		}
		else
		{
			$smonths['f_month'] = $start_month;
		}
		$this->session->set_userdata($smonths);
	}
	else if(!empty($get_from_month))
	{
		$smonths['f_month'] = $get_from_month;
		$this->session->set_userdata($smonths);

		$smonths['t_month'] = $get_too_month;
		$this->session->set_userdata($smonths);
	}
	else
	{
		$start_month = 0;
	}


	if(isset($_POST['to_month']))
	{
		
		if(strlen($start_month)>2 && strlen($end_month)>2)
		{

			$search['end_date'] = $end_month;

			$now = date("Y-m-d");
			$end_date = str_replace("-","/",$end_month);
			$dates = explode("/",$end_date);
			$end_date = $dates[1].'/'.$dates[0].'/'.'20'.$dates[2];
			$end_date = date("Y-m-d", strtotime($end_date)); 

			if($end_date > $now)
			{
			
				$diff = abs(strtotime($end_date) - strtotime($now));
				$years = floor($diff / (365*60*60*24));// for year difference
				$months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));	
				$smonths['t_month'] = - $months;

				
			}
			else
			{
				$d1 = new DateTime(date("Y-m-d"));
				$d2 = new DateTime(date("Y-m-d", strtotime($end_month)));
	
				
				$interval = $d2->diff($d1);
				$em = $interval->format('%m');
				$smonths['t_month'] = (int) $em;
			}

			$eflag = 1;
		}
		else
		{
			$smonths['t_month'] = $end_month;
		}
		
		$this->session->set_userdata($smonths);
	}
	else
	{
		$end_month = -12;
	}

	$total_months = $start_month + $end_month;

	$s_month = $this->session->userdata('f_month');
	$e_month = $this->session->userdata('t_month');


	if($s_month == '' && $sflag == 0)
	{
		$s_month = 0;
	}

	if($e_month == '' && $eflag == 0 )
	{
		$e_month = -12;
	}

	//print_r($user_permission_type); 
	if(isset($_POST['submit'])){
		$search_filter = $_POST['search_filter'];
		$search['keywords'] = $search_filter;			
		
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

		$search['consent_fields'] = $field_check;
		$search['search_value'] = $search_value;
		$search['search_consent_f_month'] = $from_month;
		$search['search_consent_l_month'] = $to_month;

		$this->session->set_userdata($search); 
	}

	$keywords = $this->session->userdata('keywords');
	$consent_fields_session = $this->session->userdata('consent_fields');
	$report_search_value_1 = $this->session->userdata('search_value');
	$refine = explode(',',$consent_fields_session);
	$search_start_date = $this->session->userdata('search_consent_f_month');
	$search_end_date = $this->session->userdata('search_consent_l_month');

	$ci = &get_instance();
	$ci->load->model('consent_model');
	$user_info = $ci->consent_model->user_option();

	$consent_by_list = $ci->consent_model->get_user_category_list(3);
	$project_manager_list = $ci->consent_model->get_user_category_list(4);
	$builder_list = $ci->consent_model->get_user_category_list(5);
                               
?>

<?php  
	$consent_by_value = '';
	foreach ($consent_by_list as $consent_by){ 
		$consent_by_value .= '<option value="'.$consent_by->uid.'">'.$consent_by->fullname.'</option>'; 
	} 

	$project_manager_value = '';
	foreach ($project_manager_list as $project_manager){ 
		$project_manager_value .= '<option value="'.$project_manager->uid.'">'.$project_manager->fullname.'</option>'; 
	}

	$builder_value = '';
	foreach ($builder_list as $builder){ 
		$builder_value .= '<option value="'.$builder->uid.'">'.$builder->fullname.'</option>'; 
	}
?>

<?php

	if(!empty($consent_fields_session)){

		$refine_colunm_name = '';
		for($i = 0; $i < count($refine); $i++){
			$consent_fields = $refine[$i];
			$report_search_value1 = explode(',',$report_search_value_1);

			$report_search_value = '';
			for($a = 0; $a < count($report_search_value1); $a++){
				$report_search_value2 = $report_search_value1[$a];
				$report_search_value3 = explode("=",$report_search_value2);
				if(in_array($consent_fields.'_search_value',$report_search_value3)){
					$report_search_value = $report_search_value3[1];
					break;
				}
			}

			$start_date = explode(",",$search_start_date);
			$start_date3 = '';
			for($j = 0; $j < count($start_date); $j++){
				$start_date1 = $start_date[$j];
				$start_date2 = explode("=",$start_date1);
				if($consent_fields.'_from_month'==$start_date2[0]){
					if($start_date2[1]==''){
						$start_date3 = '';
					}else{
						$start_date3 = date("d-m-Y", strtotime($start_date2[1]));
					}
					break;
				}
			}
			
			$end_date = explode(",",$search_end_date);
			$end_date3 = '';
			for($k = 0; $k < count($end_date); $k++){
				$end_date1 = $end_date[$k];
				$end_date2 = explode("=",$end_date1);
				if($consent_fields.'_to_month'==$end_date2[0]){
					if($end_date2[1]==''){
						$end_date3 = '';
					}else{
						$end_date3 = date("d-m-Y", strtotime($end_date2[1]));
					}					
					break;
				}
			}

			$consent_by_value1 = '';
			foreach ($consent_by_list as $consent_by){ 
				if($consent_by->uid==$report_search_value){ $selected = 'selected'; }else{ $selected = ''; }
				$consent_by_value1 .= '<option '.$selected.' value="'.$consent_by->uid.'">'.$consent_by->fullname.'</option>'; 
			} 
		
			$project_manager_value1 = '';
			foreach ($project_manager_list as $project_manager){ 
				if($project_manager->uid==$report_search_value){ $selected = 'selected'; }else{ $selected = ''; }
				$project_manager_value1 .= '<option '.$selected.' value="'.$project_manager->uid.'">'.$project_manager->fullname.'</option>'; 
			}
		
			$builder_value1 = '';
			foreach ($builder_list as $builder){ 
				if($builder->uid==$report_search_value){ $selected = 'selected'; }else{ $selected = ''; }
				$builder_value1 .= '<option '.$selected.' value="'.$builder->uid.'">'.$builder->fullname.'</option>'; 
			}

			if($consent_fields=='consent_name' || $consent_fields=='no_units' || $consent_fields=='notes'){
				$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><input type="text" id="search_value" name="'.$consent_fields.'_search_value" value="'.$report_search_value.'" /></div>';
			}else if($consent_fields=='design' || $consent_fields=='consent_by' || $consent_fields=='action_required' || $consent_fields=='council' || $consent_fields=='lbp' || $consent_fields=='bc_number' || $consent_fields=='contract_type' || $consent_fields=='type_of_build' || $consent_fields=='variation_pending' || $consent_fields=='foundation_type' || $consent_fields=='order_site_levels' || $consent_fields=='order_soil_report' || $consent_fields=='septic_tank_approval' || $consent_fields=='dev_approval' || $consent_fields=='landscape' || $consent_fields=='mss' || $consent_fields=='project_manager' || $consent_fields=='builder' || $consent_fields=='consent_out_but_no_builder'){
				if($consent_fields=='design'){
					if('REQ Brief'==$report_search_value){ $Brief = 'selected'; }else{ $Brief = ''; }
					if('Brief'==$report_search_value){ $Brief1 = 'selected'; }else{ $Brief1 = ''; }
					if('Hold'==$report_search_value){ $Hold = 'selected'; }else{ $Hold = ''; }
					if('Sign'==$report_search_value){ $Sign = 'selected'; }else{ $Sign = ''; }
					if('Consent'==$report_search_value){ $Consent = 'selected'; }else{ $Consent = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$Brief.' value="REQ Brief">REQ Brief</option><option '.$Brief1.' value="Brief">Brief</option>
					<option '.$Hold.' value="Hold">Hold</option><option '.$Sign.' value="Sign">Sign</option>
					<option '.$Consent.' value="Consent">Consent</option></select></div>';
				}else if($consent_fields=='consent_by'){
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>'.$consent_by_value1.'</select></div>';
				}else if($consent_fields=='action_required'){
					if('Urgent'==$report_search_value){ $Urgent = 'selected'; }else{ $Urgent = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option><option '.$Urgent.' value="Urgent">Urgent</option></select></div>';
				}else if($consent_fields=='council'){
					if('Ashburton'==$report_search_value){ $Ashburton = 'selected'; }else{ $Ashburton = ''; }
					if('Auckland'==$report_search_value){ $Auckland = 'selected'; }else{ $Auckland = ''; }
					if('Chch'==$report_search_value){ $Chch = 'selected'; }else{ $Chch = ''; }
					if('Hurunui'==$report_search_value){ $Hurunui = 'selected'; }else{ $Ashburton = ''; }
					if('Selwyn'==$report_search_value){ $Selwyn = 'selected'; }else{ $Selwyn = ''; }
					if('Waikato'==$report_search_value){ $Waikato = 'selected'; }else{ $Waikato = ''; }
					if('Waimak'==$report_search_value){ $Waimak = 'selected'; }else{ $Waimak = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$Ashburton.' value="Ashburton">Ashburton</option><option '.$Auckland.' value="Auckland">Auckland</option>
					<option '.$Chch.' value="Chch">Chch</option><option '.$Hurunui.' value="Hurunui">Hurunui</option>
					<option '.$Selwyn.' value="Selwyn">Selwyn</option><option '.$Waikato.' value="Waikato">Waikato</option><option '.$Waimak.' value="Waimak">Waimak</option></select></div>';
				}else if($consent_fields=='lbp'){
					if('Susan G'==$report_search_value){ $Susan = 'selected'; }else{ $Susan = ''; }
					if('Mark B'==$report_search_value){ $Mark = 'selected'; }else{ $Mark = ''; }
					if('Nathan V'==$report_search_value){ $Nathan = 'selected'; }else{ $Nathan = ''; }
					if('Selina A'==$report_search_value){ $Selina = 'selected'; }else{ $Selina = ''; }
					if('Chelsea K'==$report_search_value){ $Chelsea = 'selected'; }else{ $Chelsea = ''; }
					if('Jos K'==$report_search_value){ $Jos = 'selected'; }else{ $Jos = ''; }
					if('Andy D'==$report_search_value){ $Andy = 'selected'; }else{ $Andy = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$Susan.' value="Susan G">Susan G</option><option '.$Mark.' value="Mark B">Mark B</option>
					<option '.$Nathan.' value="Nathan V">Nathan V</option><option '.$Selina.' value="Selina A">Selina A</option>
					<option '.$Chelsea.' value="Chelsea K">Chelsea K</option><option '.$Jos.' value="Jos K">Jos K</option>
					<option '.$Andy.' value="Andy D">Andy D</option></select></div>';
				}else if($consent_fields=='bc_number'){
					if('Checking'==$report_search_value){ $Checking = 'selected'; }else{ $Checking = ''; }
					if('Checked'==$report_search_value){ $Checked = 'selected'; }else{ $Checked = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option><option '.$Checking.' value="Checking">Checking</option><option '.$Checked.' value="Checked">Checked</option></select></div>';
				}else if($consent_fields=='contract_type'){
					if('BC'==$report_search_value){ $BC = 'selected'; }else{ $BC = ''; }
					if('DU'==$report_search_value){ $DU = 'selected'; }else{ $DU = ''; }
					if('EQ'==$report_search_value){ $EQ = 'selected'; }else{ $EQ = ''; }
					if('HL'==$report_search_value){ $HL = 'selected'; }else{ $HL = ''; }
					if('MU'==$report_search_value){ $MU = 'selected'; }else{ $MU = ''; }
					if('TK'==$report_search_value){ $TK = 'selected'; }else{ $TK = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$BC.' value="BC">BC</option><option '.$DU.' value="DU">DU</option><option '.$EQ.' value="EQ">EQ</option><option '.$HL.' value="HL">HL</option><option '.$MU.' value="MU">MU</option><option '.$TK.' value="TK">TK</option></select></div>';
				}else if($consent_fields=='type_of_build'){
					if('SH'==$report_search_value){ $SH = 'selected'; }else{ $SH = ''; }
					if('MU'==$report_search_value){ $MU = 'selected'; }else{ $MU = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$SH.' value="SH">SH</option><option '.$MU.' value="MU">MU</option></select></div>';
				}else if($consent_fields=='variation_pending'){
					if('Yes'==$report_search_value){ $Yes = 'selected'; }else{ $Yes = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option><option '.$Yes.' value="Yes">Yes</option></select></div>';
				}else if($consent_fields=='foundation_type'){
					if('Standard Engineered'==$report_search_value){ $Engineered = 'selected'; }else{ $Engineered = ''; }
					if('Standard'==$report_search_value){ $Standard = 'selected'; }else{ $Standard = ''; }
					if('Rib &amp; Shingle'==$report_search_value){ $Rib = 'selected'; }else{ $Rib = ''; }
					if('Jackable Rib &amp; Shingle'==$report_search_value){ $Jackable = 'selected'; }else{ $Jackable = ''; }
					if('Superslab &amp; Shingle'==$report_search_value){ $Superslab = 'selected'; }else{ $Superslab = ''; }
					if('TC3 type 2B'==$report_search_value){ $TC3 = 'selected'; }else{ $TC3 = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$Engineered.' value="Standard Engineered">Standard Engineered</option><option '.$Standard.' value="Standard">Standard</option>
					<option '.$Rib.' value="Rib &amp; Shingle">Rib &amp; Shingle</option><option '.$Jackable.' value="Jackable Rib &amp; Shingle">Jackable Rib &amp; Shingle</option>
					<option '.$Superslab.' value="Superslab &amp; Shingle">Superslab &amp; Shingle</option><option '.$TC3.' value="TC3 type 2B">TC3 type 2B</option></select></div>';
				}else if($consent_fields=='order_site_levels'){
					if('N/A'==$report_search_value){ $N_A = 'selected'; }else{ $N_A = ''; }
					if('Received'==$report_search_value){ $Received = 'selected'; }else{ $Received = ''; }
					if('Sent'==$report_search_value){ $Sent = 'selected'; }else{ $Sent = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$N_A.' value="N/A">N/A</option><option '.$Received.' value="Received">Received</option><option '.$Sent.' value="Sent">Sent</option></select></div>';
				}else if($consent_fields=='order_soil_report'){
					if('N/A'==$report_search_value){ $N_A = 'selected'; }else{ $N_A = ''; }
					if('Received'==$report_search_value){ $Received = 'selected'; }else{ $Received = ''; }
					if('Sent'==$report_search_value){ $Sent = 'selected'; }else{ $Sent = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$N_A.' value="N/A">N/A</option><option '.$Received.' value="Received">Received</option><option '.$Sent.' value="Sent">Sent</option></select></div>';
				}else if($consent_fields=='septic_tank_approval'){
					if('N/A'==$report_search_value){ $N_A = 'selected'; }else{ $N_A = ''; }
					if('REQ'==$report_search_value){ $REQ = 'selected'; }else{ $REQ = ''; }
					if('SENT'==$report_search_value){ $SENT = 'selected'; }else{ $SENT = ''; }
					if('RECEIVED'==$report_search_value){ $RECEIVED = 'selected'; }else{ $RECEIVED = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$REQ.' value="REQ">REQ</option><option '.$N_A.' value="N/A">N/A</option>
					<option '.$SENT.' value="SENT">SENT</option><option '.$RECEIVED.' value="RECEIVED">RECEIVED</option></select></div>';
				}else if($consent_fields=='dev_approval'){
					if('N/A'==$report_search_value){ $N_A = 'selected'; }else{ $N_A = ''; }
					if('REQ'==$report_search_value){ $REQ = 'selected'; }else{ $REQ = ''; }
					if('PRE SENT'==$report_search_value){ $PRE_SENT = 'selected'; }else{ $PRE_SENT = ''; }
					if('PRE REC'==$report_search_value){ $PRE_REC = 'selected'; }else{ $PRE_REC = ''; }
					if('FULL SENT'==$report_search_value){ $FULL_SENT = 'selected'; }else{ $FULL_SENT = ''; }
					if('FULL REC'==$report_search_value){ $FULL_REC = 'selected'; }else{ $FULL_REC = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine [ '.$consent_fields.' ]:</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$REQ.' value="REQ">REQ</option><option '.$N_A.' value="N/A">N/A</option><option '.$PRE_SENT.' value="PRE SENT">PRE SENT</option>
					<option '.$PRE_REC.' value="PRE REC">PRE REC</option><option '.$FULL_SENT.' value="FULL SENT">FULL SENT</option>
					<option '.$FULL_REC.' value="FULL REC">FULL REC</option></select></div>';
				}else if($consent_fields=='landscape'){
					if('N/A'==$report_search_value){ $N_A = 'selected'; }else{ $N_A = ''; }
					if('REQ'==$report_search_value){ $REQ = 'selected'; }else{ $REQ = ''; }
					if('SENT'==$report_search_value){ $SENT = 'selected'; }else{ $SENT = ''; }
					if('RECEIVED'==$report_search_value){ $RECEIVED = 'selected'; }else{ $RECEIVED = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$REQ.' value="REQ">REQ</option><option '.$N_A.' value="N/A">N/A</option><option '.$SENT.' value="SENT">SENT</option>
					<option '.$RECEIVED.' value="RECEIVED">RECEIVED</option></select></div>';
				}else if($consent_fields=='mss'){
					if('DONE'==$report_search_value){ $DONE = 'selected'; }else{ $DONE = ''; }
					if('REQ'==$report_search_value){ $REQ = 'selected'; }else{ $REQ = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$REQ.' value="REQ">REQ</option><option '.$DONE.' value="DONE">DONE</option></select></div>';
				}else if($consent_fields=='project_manager'){
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>'.$project_manager_value1.'</select></div>';
				}else if($consent_fields=='builder'){
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>'.$builder_value1.'</select></div>';
				}else if($consent_fields=='consent_out_but_no_builder'){
					if('Allocated'==$report_search_value){ $Allocated = 'selected'; }else{ $Allocated = ''; }
					if('Due'==$report_search_value){ $Due = 'selected'; }else{ $Due = ''; }
					if('Need Builder'==$report_search_value){ $Need = 'selected'; }else{ $Need = ''; }
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
					<option '.$Allocated.' value="Allocated">Allocated</option><option '.$Due.' value="Due">Due</option>
					<option '.$Need.' value="Need Builder">Need Builder</option></select></div>';
				}
			}else if($consent_fields=='approval_date' || $consent_fields=='price_approved_date' || $consent_fields=='pim_logged' || $consent_fields=='drafting_issue_date' || $consent_fields=='date_job_checked' || $consent_fields=='date_logged' || $consent_fields=='	date_issued' || $consent_fields=='actual_date_issued' || $consent_fields=='unconditional_date' || $consent_fields=='handover_date' || $consent_fields=='title_date' || $consent_fields=='settlement_date'){
				$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><input type="text" placeholder="-- Select Date From --" class="live_datepicker" name="'.$consent_fields.'_from_month" value="'.$start_date3.'" /><input type="text" placeholder="-- Select Date To --" class="live_datepicker" name="'.$consent_fields.'_to_month" value="'.$end_date3.'"></div>';														
			}else{
				$refine_colunm_name .= '';
			}

		}

	echo '<style>#search_box .ui-multiselect {width: 100% !important;}</style>';
	}

?>


<script src="<?php echo base_url();?>js/jquery.dragtable.js"></script>
<script type="text/javascript">
$(document).ready(function() {
$('#table6').dragtable();
});
</script>
<script type="text/javascript">

window.Url = "<?php print base_url(); ?>";

$(function(){ 
    $(document).on('focus', ".live_datepicker1", function(){
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy',
			onClose: function(dateText, inst) 
			{
       			this.fixFocusIE = true;
       			this.focus();
   			}
        });
    });
});

	function checkJnumber()  
	{  
		var job_no = $('.in #job_no').val();
  
		$.ajax({				
			url: window.Url + 'consent/check_job_no?job_no=' + job_no,
			type: 'POST',
			success: function(data) 
			{	
				//console.log(data);
				if(data == 1){
					$('.in #job_no').css('border', '1px solid #FF0000');
					$('.in .taken').empty();
					$('.in .taken').append('<span style="color:#FF0000;font-style: italic;">This J number has already been entered into the system</span>');
	        		return false;
				}else{
			        //alert("Passwords Do Not Match!");
			        $('.in #email').css('border', '1px solid #01416f');
			        $('.in .taken').empty();
			        return true;
			    }
			},
		        
		});  
	} 

	function checkJobform()
	{
	    var job_no = $('.in #job_no').val();
	        
        var html = $.ajax({
	        async: false,
	        url: window.Url + 'consent/check_job_no?job_no=' + job_no,
	        type: 'POST',
	        dataType: 'html',
	        //data: {'pnr': a},
	        timeout: 2000,
	    }).responseText;
	    if(html==1){
	        $('.in #job_no').css('border', '1px solid #FF0000');
			$('.in .taken').empty();
			$('.in .taken').append('<span style="color:#FF0000;font-style: italic;">This J number has already been entered into the system</span>');
        	return false;
	    }else{
	        $('.in #job_no').css('border', '1px solid #01416f');
	        $('.in .taken').empty();
	        return true;
	    } 
	    
	}



// double click function
$(document).ready(function() {

	

	$('#clear_search').click(function(){
        $.ajax({				
			url: window.Url + 'consent/clear_search_consent',
			type: 'POST',
			success: function(html) 
			{
				//console.log(data);
				newurl = window.mbsBaseUrl + 'consent/consent_list';
				window.location = newurl;
			},
		        
		});
    });	

	

	$( ".accordion-content" ).resizable();	
	
	$(".multiselectbox").multiselect({
        selectedText: "# of # selected"
    });

	$('.consent_table tr td:not(:first-child)').dblclick(function(e){
		$('#cmemory').val(1);
		e.preventDefault();
		tdid = $(this).prop('id');
		divid = tdid.replace('col','box');
		display_box = tdid.replace('col','dis');

		display_img = tdid.replace('col','timg');
		display_tab = tdid.replace('col','tab');

		tra_img = $('#' + display_img ).html();
		tra_tab = $('#' + display_tab ).html();

		
		if($('#' + divid).hasClass('dnone') == true)
		{	
			$('#' + divid ).attr('class', 'dblock');
			$('#' + display_box ).attr('class', 'dnone');
			
			$('#onclick_img_' + tra_tab).empty();
			$('#onclick_img_' + tra_tab).append(tra_img);
		}
		else
		{
			$('#' + divid ).attr('class', 'dnone');
			$('#' + display_box ).attr('class', 'dblock');
			
			$('#onclick_img_' + tra_tab).empty();
		}
		
	});
});
// search function
$.fn.eqAnyOf = function (arrayOfIndexes) {
    return this.filter(function(i) {
        return $.inArray(i, arrayOfIndexes) > -1;
    });
};

$(document).ready(function() {	
$("#filter").bind("keyup",advance_search);
});

function advance_search()
{
	
        // Retrieve the input field text and reset the count to zero
        var filter = $("#filter").val(), count = 0;

		if(filter !='')
		{
			var parr = new Array(); 
			
			if($('#sjobno').is(":checked")){ pos = parr.length;	parr[pos] = 0 ;	}
			if($('#sconsent_name').is(":checked")){ pos = parr.length; parr[pos] = 1 ; } 
			if($('#sdesign').is(":checked")){ pos = parr.length; parr[pos] = 2 ; }
			if($('#sapproval_date').is(":checked")){ pos = parr.length;	parr[pos] = 3 ;	}
			if($('#spim_logged').is(":checked")){ pos = parr.length; parr[pos] = 4 ; } 
			if($('#sin_council').is(":checked")){ pos = parr.length; parr[pos] = 5 ; }
			if($('#sconsent_out').is(":checked")){ pos = parr.length;	parr[pos] = 6 ;	}
			if($('#sdrafting_issue_date').is(":checked")){ pos = parr.length; parr[pos] = 7 ; } 
			if($('#sconsent_by').is(":checked")){ pos = parr.length; parr[pos] = 8 ; }
			if($('#saction_required').is(":checked")){ pos = parr.length;	parr[pos] = 9 ;	}
			if($('#scouncil').is(":checked")){ pos = parr.length; parr[pos] = 10 ; } 
			if($('#sbc_number').is(":checked")){ pos = parr.length; parr[pos] = 11 ; }
			if($('#sno_units').is(":checked")){ pos = parr.length;	parr[pos] = 12 ;	}
			if($('#scontract_type').is(":checked")){ pos = parr.length; parr[pos] = 13 ; } 
			if($('#stype_of_build').is(":checked")){ pos = parr.length; parr[pos] = 14 ; }
			if($('#svariation_pending').is(":checked")){ pos = parr.length;	parr[pos] = 15 ;	}
			if($('#sfoundation_type').is(":checked")){ pos = parr.length; parr[pos] = 16 ; } 
			if($('#sdate_logged').is(":checked")){ pos = parr.length; parr[pos] = 17 ; }
			if($('#sdate_issued').is(":checked")){ pos = parr.length;	parr[pos] = 18 ;	}
			if($('#sdays_in_council').is(":checked")){ pos = parr.length; parr[pos] = 19 ; } 
			if($('#sorder_site_levels').is(":checked")){ pos = parr.length; parr[pos] = 20 ; }
			if($('#sorder_soil_report').is(":checked")){ pos = parr.length;	parr[pos] = 21 ;	}
			if($('#sseptic_tank_approval').is(":checked")){ pos = parr.length; parr[pos] = 22 ; } 
			if($('#sdev_approval').is(":checked")){ pos = parr.length; parr[pos] = 23 ; }
			if($('#sproject_manager').is(":checked")){ pos = parr.length; parr[pos] = 24 ; }
			if($('#sallocated_to_pm').is(":checked")){ pos = parr.length;	parr[pos] = 25 ;	}
			if($('#sunconditional_date').is(":checked")){ pos = parr.length; parr[pos] = 26 ; } 
			if($('#shandover_dat').is(":checked")){ pos = parr.length; parr[pos] = 27 ; }
			
			if(parr.length == 0)
			{
				for(j=0; j<28; j++)
				{
					parr[j] = j; 	
				}
			}

			var table_open_ids = [];
			var all_table_ids = [];
			// Loop through the consent row list
			$(".consent_table tr").each(function()
			{
	 
					// If the list item does not contain the text phrase fade it out
				if ($(this).find("td").eqAnyOf(parr).text().search(new RegExp(filter, "i")) < 0) 
				{
					$(this).fadeOut();
					// Show the list item if the phrase matches and increase the count by 1
				}
				else 
				{	
					$(this).show();
					count++;
					tid = $(this).closest('li').attr('id');
					
					if(table_open_ids.length == 0){
						table_open_ids[0] = tid;
					}
					
					for(i=0; i<table_open_ids.length; i++){
						if( jQuery.inArray( tid, table_open_ids ) == -1 ){
							var pos = table_open_ids.length;
							table_open_ids[pos] = tid;
						}
					}
				}

			});
			$("#msg").html( count + ' results were found for "' + filter + '"' );
			
			
			<?php 
				$i = 0;
				for($p = $s_month; $p >= $e_month; $p--)
				{
					
					?>
					all_table_ids[<?php echo $i; ?>] = <?php echo $p; ?>;
			<?php   $i++;	
				}	
				?>
		
			
			for(j=0; j<all_table_ids.length; j++)
			{
				$('#' + all_table_ids[j] ).removeClass('accordion-active');
				$('#' + all_table_ids[j] ).find('.accordion-content').slideUp(300);			
			}
			
			for(j=0; j<table_open_ids.length; j++)
			{
				$('#' + table_open_ids[j] ).addClass('accordion-active');
				$('#' + table_open_ids[j] ).find('.accordion-content').slideDown(300);			
			}
		
		}
		else
		{
			$("#msg").html( '' );
		}
}


function clear_search()
{
	
	$("#filter").val( '' );
	$(".consent_table tr").each(function()
	{
		$(this).show();	
	});
	$("#msg").html( '' );
	$('#search_option').find('input[type=checkbox]:checked').removeAttr('checked');
	var all_table_ids = [];
	<?php 
		$i = 0;
		for($p = $s_month; $p >= $e_month; $p--)
		{
			
			?>
			all_table_ids[<?php echo $i; ?>] = <?php echo $p; ?>;
	<?php   $i++;	
		}	
		?>

	for(j=0; j< all_table_ids.length - 1; j++)
	{
		$('#' + all_table_ids[j] ).removeClass('accordion-active');
		$('#' + all_table_ids[j] ).find('.accordion-content').slideUp(300);			
	}
}

// all scroll script
function divScroll(curid)
{

   <?php 
	//for($p = $s_month; $p >= $e_month; $p--)
	//{
		
		//$consent_id = 'consent'.$p;	
	?>
	
	//var consid = '<?php //echo $consent_id; ?>'; 
	//var leftobj = document.getElementById(consid);
	//var rightobj = document.getElementById(curid);
	//leftobj.scrollLeft = rightobj.scrollLeft;
	
	<?php
	//}
   
   ?>
}


// draggable function
jQuery(document).ready(function() {	
		$( "#draggable_add" ).draggable({
			helper: "clone",
			revert: "invalid"
		});


		<?php 
			$tableids = '';
			for($p = $s_month; $p >= $e_month; $p--)
			{

				$tableids = $tableids.'#table'.$p.',';
			}
		?>
		
		var tblids = '<?php echo $tableids; ?>';
		var numoftblids = tblids.length;
    	var restable = tblids.substring(0, numoftblids - 1);


		$(restable).droppable({
			drop: function( event, ui ) {
				if (ui.draggable.is('#draggable_add')) {
					$( this ).find( "tbody" )
					.prepend( "<tr id='new'>"+ 
            "<td><input onblur='checkjobno();' id='job-no' name='job_no' type='text'/></td>"+ 
            "<td><input name='consent_name' type='text'/><select name='color'><option value='ffffff'>White</option><option value='72D660'>Green</option><option value='FF3D3D'>Red</option><option value='FFAC40'>Orange</option></select></td>"+ 
            "<td><select name='design'><option value='Allo'>Allo</option><option value='Brief'>Brief</option><option value='Consent'>Consent</option><option value='Drawings'>Drawings</option><option value='Hold'>Hold</option><option value='Required'>Required</option><option value='Sign'>Sign</option></select></td>"+ 
            "<td><input name='approval_date' class='live_datepicker' type='text'/></td>"+ 
            "<td><input name='pim_logged' class='live_datepicker' type='text'/></td>"+
            "<td><input name='in_council' class='live_datepicker' type='text'/></td>"+
            "<td><input name='consent_out' class='live_datepicker' type='text'/></td>"+
            "<td><input name='drafting_issue_date' class='live_datepicker' type='text'/></td>"+           
            "<td><select name='consent_by' id='consent_by'><option value='0'>Select User</option><?php  foreach ($consent_by_list as $consent_by) { ?> <option value='<?php echo $consent_by->uid ?>'> <?php echo $consent_by->fullname ?></option> <?php }?></select></td>"+ 
            "<td><select name='action_required'><option value='Urgent'>Urgent</option></select></td>"+
            "<td><select name='council'><option value='Ashburton'>Ashburton</option><option value='Auckland'>Auckland</option><option value='Chch'>Chch</option><option value='Hurunui'>Hurunui</option><option value='Selwyn'>Selwyn</option><option value='Waikato'>Waikato</option><option value='Waimak'>Waimak</option></select></td>"+             
            "<td><input name='lbp' class='' type='text'/></td>"+
			"<td><input name='date_job_checked' class='live_datepicker' type='text'/></td>"+
			"<td><input name='bc_number' class='' type='text'/></td>"+             
            "<td><input name='no_units' class='' type='text'/></td>"+ 
            "<td><select name='contract_type'><option value='BC'>BC</option><option value='DU'>DU</option><option value='EQ'>EQ</option><option value='HL'>HL</option><option value='MU'>MU</option><option value='TK'>TK</option></select></td>"+ 
            "<td><select name='type_of_build'><option value='SH'>SH</option><option value='MU'>MU</option></select></td>"+ 
            "<td><select name='variation_pending'><option value='Yes'>Yes</option><option value='No'>No</option></select></td>"+
            "<td><input type='text' name='foundation_type1' /><select name='foundation_type'><option value=''>Select Foundation Type</option><option value='Standard Engineered'>Standard Engineered</option><option value='Standard'>Standard</option>\n\
                <option value='Rib & Shingle'>Rib & Shingle</option><option value='Jackable Rib & Shingle'>Jackable Rib & Shingle</option>\n\
                <option value='Superslab & Shingle'>Superslab & Shingle</option><option value='TC3 type 2B'>TC3 type 2B</option></select></td>"+ 
            "<td><input name='date_logged' class='live_datepicker' type='text'/></td>"+
            "<td><input name='date_issued' class='live_datepicker' type='text'/></td>"+
            "<td><input name='days_in_council' class='' type='text'/></td>"+ 
            "<td><select name='order_site_levels'><option value='N/A'>N/A</option><option value='Received'>Received</option><option value='Sent'>Sent</option></select></td>"+ 
            "<td><select name='order_soil_report'><option value='N/A'>N/A</option><option value='Received'>Received</option><option value='Sent'>Sent</option></select></td>"+ 
            "<td><select name='septic_tank_approval'><option value='N/A'>N/A</option><option value='Received'>Received</option><option value='Sent'>Sent</option></select></td>"+ 
            "<td><select name='dev_approval'><option value='Required'>Required</option><option value='Pre'>Pre</option><option value='Sent'>Sent</option><option value='Have'>Have</option></select></td>"+ 
            "<td><select name='project_manager' id='project_manager'><option value='0'>Select User</option><?php  foreach ($project_manager_list as $project_manager){?> <option value='<?php echo $project_manager->uid ?>'> <?php echo $project_manager->fullname ?></option> <?php }?></select></td>"+ 
            "<td><input name='unconditional_date' class='live_datepicker' type='text'/></td>"+
            "<td><input name='handover_date' class='live_datepicker' type='text'/></td>"+
            "<td><select name='builder' id='builder'><option value='0'>Select Builder</option><?php  foreach ($builder_list as $builder) { ?> <option value='<?php echo $builder->uid ?>'> <?php echo $builder->fullname ?></option> <?php }?></select></td>"+ 
            "<td><select name='consent_out_but_no_builder'><option value='Allocated'>Allocated</option><option value='Due'>Due</option><option value='Need Builder'>Need Builder</option></select></td>"+
            "<td></td>"+
                       
            "</tr>" );

					$('#btnAdd').css("display","none");
				    $('#cancel_cons').css("display","block");
				    $("#consentSave").one("click", consentSave);

				}
				
			}
		});
	
});

// row select function
function selectrow(tr_id,tr_class)
{
	
	if($("#" + tr_id).hasClass( 'checked' ) == false)
	{
		document.getElementById('delete_consent_item').href= window.Url + 'consent/consent_delete/'+tr_id;
		$("tr").removeClass("checked");
		document.getElementById(tr_id).className = 'checked';
		$('#delete_consent').css("display","block");
	}
	else
	{

		document.getElementById('delete_consent_item').href= window.Url + 'consent/consent_list/#';
		$("tr").removeClass("checked");
		$('#delete_consent').css("display","none");

	}

}

// sortable/moveable function
$(function()
{

	<?php 
			$tableids = '';
			for($p=$s_month; $p >= $e_month; $p--)
			{
				$tableids = $tableids.'#table'.$p." tbody.tbody".$p.', ';
			}
		?>
		
		var tblids = '<?php echo $tableids; ?>';
		var numoftblids = tblids.length;
    	var restable = tblids.substring(0, numoftblids - 2);


	$( restable ).sortable({
		connectWith: ".connectedSortable",
		items: ">*:not(.sort-disabled)",
		update : function () { 
			var table_body_id = $(this).attr('id');
			var order = $(this).sortable('serialize');
			$.ajax({
				url: window.Url + 'consent/consent_ordering/' + encodeURIComponent(order) + '/' + table_body_id,
				type: 'POST',
				data: order,
				success: function(data) 
				{
					$('#cancel_cons').css("display","none");
				},
			        
			});
			}
	});
	
});

// job number check function
function checkjobno()
{

	var job_no = $('input#job-no').val();
		
	var html = $.ajax({
			async: false,
			url: window.Url + 'consent/check_job_no?job_no=' + job_no,
			type: 'POST',
			dataType: 'html',
			//data: {'pnr': a},
			timeout: 2000,
		}).responseText;
		if(html==1){
			$('input#job-no').css('border', '1px solid #FF0000');
			$('#consentSave').css("display","none");
			return false;
		}else{
			$('input#job-no').css('border', '1px solid #01416f');
			$('#consentSave').css("display","block");
			return true;
		}			
}


$(document).ready( function () 
{
	var iStart = new Date().getTime();

		
	<?php 
			$tableids = '';
			for($p=$s_month; $p >= $e_month; $p--)
			{

				$month_id = date("Ym", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));
				if($ci->consent_model->check_available_month($month_id) && $ci->consent_model->get_consent_info_by_monthid($month_id))
				{
					$tableids = $tableids.'#table'.$p.',';
				}
			}
		?>
		
		var tblids = '<?php echo $tableids; ?>';
		var numoftblids = tblids.length;
    	var restable = tblids.substring(0, numoftblids - 1);

	var oTable = $(restable).dataTable( 
	{
		
		"sScrollY": "300px",
		"sScrollX": "100%",
		"sScrollXInner": "150%",
		"bScrollCollapse": true,
		"bPaginate": false,
        "bFilter": false,
		"bInfo": false,
		"sDom": "Rlfrtip"
		
	} );


	var pressed = false;
    var start = undefined;
    var startX, startWidth;
    
    $("table th").mousedown(function(e) {
        start = $(this);
        pressed = true;
        startX = e.pageX;
        startWidth = $(this).width();
        $(start).addClass("resizing");
    });
    
    $(document).mousemove(function(e) {
        if(pressed) {
            $(start).width(startWidth+(e.pageX-startX));
        }
    });
    
    $(document).mouseup(function() {
        if(pressed) {
            $(start).removeClass("resizing");
            pressed = false;
        }
    });



});

$(document).ready(function () {
	
	$(window).bind('beforeunload', function(){
		if( $('#cmemory').val() == 1)
		{
			return 'Are you sure you want to leave?';
		}
	});
	
	$(".live_datepicker").attr('maxlength','8');

	$('#cancel_cons').click(function(){

		$('#new').remove();
		$('#cancel_cons').css("display","none");
        $('#btnAdd').css("display","block");
	
	});

	// zoom in code
	$('#zoomin').click(function()
	{
		
		var fontsize = $('table td').css('font-size');

		var num = fontsize.length;
    	var res = fontsize.substring(0, num-2);

        newsize = parseInt(res) + 1;

		if(newsize < 18)
		{

			newsizepx = newsize + 'px';

        	$('table td').css('font-size',newsizepx);
			$('table th').css('font-size',newsizepx);
		}

		var width = $('.consent_table').width();
		var widthpx = $(".consent_table").offsetParent().width();
		var percent = 100*width/widthpx;
		var new_tbl_width_out = percent + 100;

		if(new_tbl_width_out < 800 )
		{

			new_tbl_width_out_per = new_tbl_width_out + '%';
			
			$('.consent_table').css("width", new_tbl_width_out_per );
			$('.consent_table').css("max-width", new_tbl_width_out_per );			

		}
		

	});


	// zoom out code
	$('#zoomout').click(function(){


		widthpx = 0;
		var fontsize = $('table td').css('font-size');

		var num = fontsize.length;
    	var res = fontsize.substring(0, num-2);
        var newsize = parseInt(res) - 1;

		if(newsize > 7)
		{

			newsizepx = newsize + 'px';

        	$('table td').css('font-size',newsizepx);
			$('table th').css('font-size',newsizepx);
		}

		var width = $('.consent_table').width();
		var widthpx = $(".consent_table").offsetParent().width();
		var percent = 100*width/widthpx;	
		new_tbl_width_out = percent - 100;

		if(new_tbl_width_out > 100 )
		{

			new_tbl_width_out_per = new_tbl_width_out + '%';
			
			$('.consent_table').css("width", new_tbl_width_out_per );
			$('.consent_table').css("max-width", new_tbl_width_out_per );

		}

		if(new_tbl_width_out < 100)
		{

			new_tbl_width_out_per = '100' + '%';			
			$('.consent_table').css("width", new_tbl_width_out_per );
			$('.consent_table').css("max-width", new_tbl_width_out_per );

		}

	});

	

	$('.accordions').each(function(){
		
		// Set First Accordion As Active
		$(this).find('.accordion-content').hide();
		if($(this).hasClass('toggles')){
			<?php if(!$tab_ids){ ?>
			$(this).find('.accordion:first-child').addClass('accordion-active');
			$(this).find('.accordion:first-child .accordion-content').show();
			
			<?php } for($a = 0; $a<count($tab_ids)-1; $a++) { ?>
		
			$(this).find('#<?php echo $tab_ids[$a]; ?>').addClass('accordion-active');
			$(this).find('#<?php echo $tab_ids[$a]; ?> .accordion-content').show();
		
			<?php } ?>
			
		}
		// session active accordion
		
		
		
		// Set Accordion Events
		$(this).find('.accordion-header').click(function(){
			
			if(!$(this).parent().hasClass('accordion-active')){
				
				// Close other accordions
				if(!$(this).parent().parent().hasClass('toggles')){
					$(this).parent().parent().find('.accordion-active').removeClass('accordion-active').find('.accordion-content').slideUp(300);
				}
				
				// Open Accordion
				$(this).parent().addClass('accordion-active');
				$(this).parent().find('.accordion-content').slideDown(300);
			
			}else{
				
				// Close Accordion
				$(this).parent().removeClass('accordion-active');
				$(this).parent().find('.accordion-content').slideUp(300);
				
			}
			
			k = 0;
			ids = '';
			<?php 
			for($p=$s_month; $p >= $e_month; $p--)
			{
		
			?>
				
				if( $('#' + <?php echo $p; ?>).hasClass('accordion-active') == true)
				{
					ids = ids + <?php echo $p; ?> + '_' ;
				}
				
			<?php } ?>
			
			if(ids == '')
			{
				document.getElementById('download_link').href = '#';
				document.getElementById('print_link').href = '#';
				document.getElementById('logout').href = '<?php echo base_url();?>user/user_logout';
				$('#download_link').attr('target', '_self');
				$('#print_link').attr('target', '_self');	
			}
			else
			{
				document.getElementById('download_link').href = 'consent_list_download/' + ids + '/' + <?php echo $s_month ?> + '/' + <?php echo $e_month ?>;
				document.getElementById('print_link').href = 'consent_list_print/' + ids + '/' + <?php echo $s_month ?> + '/' + <?php echo $e_month ?>;
				document.getElementById('logout').href = '<?php echo base_url();?>user/user_logout/' + ids;
				$('#download_link').attr('target', '_blank');
				$('#print_link').attr('target', '_blank');
			}

			
			document.getElementById('email_link_outlook').value = ids;
					
			var email_link_outlook = $('#email_link_outlook').val();
		
			$.ajax({
				url: window.Url + 'consent/consent_list_email/' + email_link_outlook,
				type: 'GET',
				success: function(data) 
				{
					//$("#print_link").val();
					document.getElementById('email_link').href = 'mailto:?subject=Consent%20List&body=' + data;
				},
					
			});
			
		});
	
	});	
});


function Add(){ 

		$('#cancel_cons').css("display","block");
		$('#btnAdd').css("display","none");

        $("#table<?php echo $s_month;  ?> tbody").prepend( 
            "<tr id='new'>"+ 
            "<td><input onblur='checkjobno();' id='job-no' name='job_no' type='text'/></td>"+ 
            "<td><input name='consent_name' type='text'/><select name='color'><option value='ffffff'>White</option><option value='72D660'>Green</option><option value='FF3D3D'>Red</option><option value='FFAC40'>Orange</option></select></td>"+ 
            "<td><select name='design'><option value='Allo'>Allo</option><option value='Brief'>Brief</option><option value='Consent'>Consent</option><option value='Drawings'>Drawings</option><option value='Hold'>Hold</option><option value='Required'>Required</option><option value='Sign'>Sign</option></select></td>"+ 
            "<td><input name='approval_date' class='live_datepicker' type='text'/></td>"+ 
            "<td><input name='pim_logged' class='live_datepicker' type='text'/></td>"+
            "<td><input name='in_council' class='live_datepicker' type='text'/></td>"+
            "<td><input name='consent_out' class='live_datepicker' type='text'/></td>"+
            "<td><input name='drafting_issue_date' class='live_datepicker' type='text'/></td>"+
            "<td><select name='consent_by' id='consent_by'><option value='0'>Select User</option><?php  foreach ($consent_by_list as $consent_by){ ?> <option value='<?php echo $consent_by->uid ?>'> <?php echo $consent_by->fullname ?></option> <?php } ?></select></td>"+ 
            "<td><select name='action_required'><option value='Urgent'>Urgent</option></select></td>"+
            "<td><select name='council'><option value='Ashburton'>Ashburton</option><option value='Auckland'>Auckland</option><option value='Chch'>Chch</option><option value='Hurunui'>Hurunui</option><option value='Selwyn'>Selwyn</option><option value='Waikato'>Waikato</option><option value='Waimak'>Waimak</option></select></td>"+   
            "<td><input name='lbp' class='' type='text'/></td>"+
			"<td><input name='date_job_checked' class='live_datepicker' type='text'/></td>"+
			"<td><input name='bc_number' class='' type='text'/></td>"+    
            "<td><input name='no_units' class='' type='text'/></td>"+ 
            "<td><select name='contract_type'><option value='BC'>BC</option><option value='DU'>DU</option><option value='EQ'>EQ</option><option value='HL'>HL</option><option value='MU'>MU</option><option value='TK'>TK</option></select></td>"+ 
            "<td><select name='type_of_build'><option value='SH'>SH</option><option value='MU'>MU</option></select></td>"+ 
            "<td><select name='variation_pending'><option value='Yes'>Yes</option><option value='No'>No</option></select></td>"+
            "<td><input type='text' name='foundation_type1' /><select name='foundation_type'><option value=''>Select Foundation Type</option><option value='Standard Engineered'>Standard Engineered</option><option value='Standard'>Standard</option>\n\
                <option value='Rib & Shingle'>Rib & Shingle</option><option value='Jackable Rib & Shingle'>Jackable Rib & Shingle</option>\n\
                <option value='Superslab & Shingle'>Superslab & Shingle</option><option value='TC3 type 2B'>TC3 type 2B</option></select></td>"+ 
            "<td><input name='date_logged' class='live_datepicker' type='text'/></td>"+
            "<td><input name='date_issued' class='live_datepicker' type='text'/></td>"+
            "<td><input name='days_in_council' class='' type='text'/></td>"+ 
            "<td><select name='order_site_levels'><option value='N/A'>N/A</option><option value='Received'>Received</option><option value='Sent'>Sent</option></select></td>"+ 
            "<td><select name='order_soil_report'><option value='N/A'>N/A</option><option value='Received'>Received</option><option value='Sent'>Sent</option></select></td>"+ 
            "<td><select name='septic_tank_approval'><option value='N/A'>N/A</option><option value='Received'>Received</option><option value='Sent'>Sent</option></select></td>"+ 
            "<td><select name='dev_approval'><option value='Required'>Required</option><option value='Pre'>Pre</option><option value='Sent'>Sent</option><option value='Have'>Have</option></select></td>"+ 
            "<td><select name='project_manager' id='project_manager'><option value='0'>Select User</option><?php  foreach ($project_manager_list as $project_manager){ ?> <option value='<?php echo $project_manager->uid ?>'> <?php echo $project_manager->fullname ?></option> <?php } ?></select></td>"+ 
            "<td><input name='unconditional_date' class='live_datepicker' type='text'/></td>"+
            "<td><input name='handover_date' class='live_datepicker' type='text'/></td>"+
            "<td><select name='builder' id='builder'><option value='0'>Select Builder</option><?php  foreach ($builder_list as $builder){ ?> <option value='<?php echo $builder->uid ?>'> <?php echo $builder->fullname ?></option> <?php } ?></select></td>"+ 
            "<td><select name='consent_out_but_no_builder'><option value='Allocated'>Allocated</option><option value='Due'>Due</option><option value='Need Builder'>Need Builder</option></select></td>"+
			"<td></td>"+
                       
            "</tr>"); 
        $("#consentSave").one("click", consentSave);
        
}

function consentSave(){  

	var month_id = $("#new").closest("tbody").attr("id");

	tr_id = document.getElementById('new');
	var ordering = $( "tr" ).index(tr_id);
	ordering = ordering - 1;
	var table_id = $("#new").closest("table").attr("id");

	var tid = table_id.slice(5);

	$('#btnAdd').css("display","block");  
	$('#cancel_cons').css("display","none");
    
	var jobNo = $("#job-no").val();
	var consentName = $('#table' + tid +' tbody #new').find("td").eq(1).children("input[type=text]").val(); 

	var consentName_en = encodeURIComponent(consentName);    
	var consentColor = $('#table' + tid +' tbody #new').find("td").eq(1).children("select[name=color]").val(); 
	var consentDesign = $('#table' + tid +' tbody #new').find("td").eq(2).children("select[name=design]").val();    
	var consentApprovalDate = $('#table' + tid +' tbody #new').find("td").eq(3).children("input[type=text]").val();
	var consentPimLogged = $('#table' + tid +' tbody #new').find("td").eq(4).children("input[type=text]").val();     
	var consentInCouncil = $('#table' + tid +' tbody #new').find("td").eq(5).children("input[type=text]").val();
	var consentConsentOut = $('#table' + tid +' tbody #new').find("td").eq(6).children("input[type=text]").val();
	var consentDraftingIssueDate = $('#table' + tid +' tbody #new').find("td").eq(7).children("input[type=text]").val();   
    var consentConsentBy = $('#table' + tid +' tbody #new').find("td").eq(8).children("select[name=consent_by]").val();
	var consent_by_obj = document.getElementById('consent_by');
	var consentConsentBytxt = consent_by_obj.options[consent_by_obj.selectedIndex].text;
    var consentActionRequired = $('#table' + tid +' tbody #new').find("td").eq(9).children("select[name=action_required]").val();
    var consentCouncil = $('#table' + tid +' tbody #new').find("td").eq(10).children("select[name=council]").val();  
    var consentBcNumber = $('#table' + tid +' tbody #new').find("td").eq(11).children("input[type=text]").val();    
    var consentNoUnits = $('#table' + tid +' tbody #new').find("td").eq(12).children("input[type=text]").val();
    var consentContractType = $('#table' + tid +' tbody #new').find("td").eq(13).children("select[name=contract_type]").val(); 
    var consentTypeofBuild = $('#table' + tid +' tbody #new').find("td").eq(14).children("select[name=type_of_build]").val(); 
    var consentVariationPending = $('#table' + tid +' tbody #new').find("td").eq(15).children("input[type=text]").val();
	var consentFoundationType1 = $('#table' + tid +' tbody #new').find("td").eq(16).children("input[type=text]").val(); 
	if(consentFoundationType1 != '')
	{
		consentFoundationType = consentFoundationType1;
	}
	else
	{
      consentFoundationType = $('#table' + tid +' tbody #new').find("td").eq(16).children("select[name=foundation_type]").val();
	}	
    var consentDateLogged = $('#table' + tid +' tbody #new').find("td").eq(17).children("input[type=text]").val();
    var consentDateIssued = $('#table' + tid +' tbody #new').find("td").eq(18).children("input[type=text]").val();
    var consentDaysInCouncil = $('#table' + tid +' tbody #new').find("td").eq(19).children("input[type=text]").val();       
    var consentOrderSiteLevels = $('#table' + tid +' tbody #new').find("td").eq(20).children("select[name=order_site_levels]").val(); 
    var consentOrderSoilReport = $('#table' + tid +' tbody #new').find("td").eq(21).children("select[name=order_soil_report]").val(); 
    var consentSepticTankApproval = $('#table' + tid +' tbody #new').find("td").eq(22).children("select[name=septic_tank_approval]").val(); 
    var consentDevApproval = $('#table' + tid +' tbody #new').find("td").eq(23).children("select[name=dev_approval]").val(); 
    var consentProjectManager = $('#table' + tid +' tbody #new').find("td").eq(24).children("select[name=project_manager]").val();
	var project_manager_obj = document.getElementById('project_manager');
	var consentProjectManagertxt = project_manager_obj.options[project_manager_obj.selectedIndex].text;
    var consentJobstobeAllocatedtoPm = $('#table' + tid +' tbody #new').find("td").eq(25).children("select[name=jobs_to_be_allocated_to_pm]").val();   
    var consentUnconditionalDate= $('#table' + tid +' tbody #new').find("td").eq(26).children("input[type=text]").val(); 
    var consentHandoverDate = $('#table' + tid +' tbody #new').find("td").eq(27).children("input[type=text]").val();    
    var consentBuilder =  $('#table' + tid +' tbody #new').find("td").eq(28).children("select[name=builder]").val();
	var builder_obj = document.getElementById('builder');
	var consentBuildertxt = builder_obj.options[builder_obj.selectedIndex].text;
    var consentConsentoutbutNobuilder =  $('#table' + tid +' tbody #new').find("td").eq(29).children("select[name=consent_out_but_no_builder]").val();
    var consentlbp = $('#table' + tid +' tbody #new').find("td").eq(30).children("input[type=text]").val(); 
	var consentDateJobChecked = $('#table' + tid +' tbody #new').find("td").eq(31).children("input[type=text]").val();
        $.ajax({  
                //url=("availabilitycheck.php?t="+value+"&hid="+hd1+"&chkin="+chkin);
                url: "<?php print base_url();?>consent/consent_add?month_id="+month_id+"&job_no="+jobNo+"&consent_name="+consentName_en+"&consent_color="+consentColor+
                    "&consent_design="+consentDesign+"&approval_date="+consentApprovalDate+"&pim_logged="+consentPimLogged+
                    "&in_council="+consentInCouncil+"&consent_out="+consentConsentOut+"&drafting_issue_date="+consentDraftingIssueDate+
                    "&consent_by="+consentConsentBy+"&action_required="+consentActionRequired+"&council="+consentCouncil+
                    "&bc_number="+consentBcNumber+"&no_units="+consentNoUnits+"&contract_type="+consentContractType+"&type_of_build="+consentTypeofBuild
                +"&variation_pending="+consentVariationPending+"&foundation_type="+consentFoundationType+
                "&date_logged="+consentDateLogged+"&date_issued="+consentDateIssued
            +"&days_in_council="+consentDaysInCouncil+"&order_site_levels="+consentOrderSiteLevels+"&order_soil_report="+consentOrderSoilReport+
            "&septic_tank_approval="+consentSepticTankApproval+"&dev_approval="+consentDevApproval+"&project_manager="+consentProjectManager+
            "&jobs_to_be_allocated_to_pm="+consentJobstobeAllocatedtoPm+"&unconditional_date="+consentUnconditionalDate
    +"&handover_date="+consentHandoverDate+"&builder="+consentBuilder+"&consent_out_but_no_builder="+consentConsentoutbutNobuilder+"&consentlbp="+consentlbp+"&consentDateJobChecked="+consentDateJobChecked+"&ordering="+ordering,  
                dataType: 'html',  
                type: 'GET',   
                success:function(data){  
                 if(data){ 
                    //console.log(data);
					location.reload();

                    //$('#table0 tbody tr:first').find("td").eq(0).html(data);
                    
                 }  
                }
        }); 
        
        $('#table' + tid +' tbody #new').find("td").eq(0).html(jobNo);
        $('#table' + tid +' tbody #new').find("td").eq(1).html(consentName);
		$('#table' + tid +' tbody #new').find("td").eq(1).css("background-color", "#" + consentColor );
        $('#table' + tid +' tbody #new').find("td").eq(2).html(consentDesign); 
        $('#table' + tid +' tbody #new').find("td").eq(3).html(consentApprovalDate); 
        $('#table' + tid +' tbody #new').find("td").eq(4).html(consentPimLogged);
        $('#table' + tid +' tbody #new').find("td").eq(5).html(consentInCouncil); 
        $('#table' + tid +' tbody #new').find("td").eq(6).html(consentConsentOut); 
        $('#table' + tid +' tbody #new').find("td").eq(7).html(consentDraftingIssueDate);
        $('#table' + tid +' tbody #new').find("td").eq(8).html(consentConsentBytxt);
        $('#table' + tid +' tbody #new').find("td").eq(9).html(consentActionRequired);
        $('#table' + tid +' tbody #new').find("td").eq(10).html(consentCouncil);        
        $('#table' + tid +' tbody #new').find("td").eq(11).html(consentBcNumber);     
        $('#table' + tid +' tbody #new').find("td").eq(12).html(consentNoUnits);
        $('#table' + tid +' tbody #new').find("td").eq(13).html(consentContractType);
        $('#table' + tid +' tbody #new').find("td").eq(14).html(consentTypeofBuild);
        $('#table' + tid +' tbody #new').find("td").eq(15).html(consentVariationPending);
        $('#table' + tid +' tbody #new').find("td").eq(16).html(consentFoundationType);
		$('#table' + tid +' tbody #new').find("td").eq(17).html(consentDateLogged);
        $('#table' + tid +' tbody #new').find("td").eq(18).html(consentDateIssued);
        $('#table' + tid +' tbody #new').find("td").eq(19).html(consentDaysInCouncil);
        $('#table' + tid +' tbody #new').find("td").eq(20).html(consentOrderSiteLevels);

		if(consentOrderSiteLevels == 'Sent' ){ site_bgcolor = '#70B5FF'; }
		if(consentOrderSiteLevels == 'Received' ){ site_bgcolor = '#A7FF70'; }
		if(consentOrderSiteLevels == 'N/A' ){ site_bgcolor = '#FFFFFF'; }

		$('#table' + tid +' tbody #new').find("td").eq(20).css("background-color", site_bgcolor );

        $('#table' + tid +' tbody #new').find("td").eq(21).html(consentOrderSoilReport); 
		
		if(consentOrderSoilReport == 'Sent' ){ soil_bgcolor = '#70B5FF'; }
		if(consentOrderSoilReport == 'Received' ){ soil_bgcolor = '#A7FF70'; }
		if(consentOrderSoilReport == 'N/A' ){ soil_bgcolor = '#FFFFFF'; }

		$('#table' + tid +' tbody #new').find("td").eq(21).css("background-color", soil_bgcolor );

        $('#table' + tid +' tbody #new').find("td").eq(22).html(consentSepticTankApproval);
	
		if(consentSepticTankApproval == 'Sent' ){ septic_bgcolor = '#70B5FF'; }
		if(consentSepticTankApproval == 'Received' ){ septic_bgcolor = '#A7FF70'; }
		if(consentSepticTankApproval == 'N/A' ){ septic_bgcolor = '#FFFFFF'; }

		$('#table' + tid +' tbody #new').find("td").eq(22).css("background-color", septic_bgcolor );


        $('#table' + tid +' tbody #new').find("td").eq(23).html(consentDevApproval);


		if(consentDevApproval == 'Sent' ){ dev_bgcolor = '#70B5FF'; }
		if(consentDevApproval == 'Received' ){ dev_bgcolor = '#A7FF70'; }
		if(consentDevApproval == 'N/A' ){ dev_bgcolor = '#FFFFFF'; }

		$('#table' + tid +' tbody #new').find("td").eq(23).css("background-color", dev_bgcolor );

        $('#table' + tid +' tbody #new').find("td").eq(24).html(consentProjectManagertxt);
        $('#table' + tid +' tbody #new').find("td").eq(25).html(consentJobstobeAllocatedtoPm);
        $('#table' + tid +' tbody #new').find("td").eq(26).html(consentUnconditionalDate);
        $('#table' + tid +' tbody #new').find("td").eq(27).html(consentHandoverDate);
        $('#table' + tid +' tbody #new').find("td").eq(28).html(consentBuildertxt);        
        $('#table' + tid +' tbody #new').find("td").eq(29).html(consentConsentoutbutNobuilder);
        
        //$("#consentSave").bind("click", consentSave);	
     
}

function Delete(){ 
    var par = $(this).parent().parent(); //tr 
    par.remove(); 
}


$(function(){ 
    $("#btnAdd").bind("click", Add); 
});

$(function(){ 
    $(document).on('focus', ".live_datepicker", function(){
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-y',
			onClose: function(dateText, inst) 
			{
       			this.fixFocusIE = true;
       			this.focus();
   			}
        });
    });
});



function show_input_fields(input_box,display_box)
{
	if($('#' + input_box).hasClass('dnone') == true)
	{	
		$('#' + input_box ).attr('class', 'dblock');
		$('#' + display_box ).attr('class', 'dnone');
	}
	else
	{
		$('#' + input_box ).attr('class', 'dnone');
		$('#' + display_box ).attr('class', 'dblock');
	}

}

function update_consent(month_id, tab_id)
{

	total_job = $('#total_job_' + tab_id).html(); 

	data_save = ''; 
	for (i = 0; i < total_job; i++) {

		var job_no = $('#job_' + month_id + '_' + i).html();
		
		var consent_id = $('#consent_id_' + job_no).val();
		var consent_color = $('#consent_color_' + job_no).val();
		var design_id = $('#design_id_' + job_no).val();
		var approval_date = $('#approval_date_' + job_no).val();
		var price_approved_date = $('#price_approved_date_' + job_no).val();
		var pim_logged = $('#pim_logged_' + job_no).val();
		var drafting_issue_date = $('#drafting_issue_date_' + job_no).val();
		var consent_by = $('#consent_by_' + job_no).val();
		var action_required = $('#action_required_' + job_no).val();
		var council = $('#council_' + job_no).val();
		var lbp = $('#lbp_' + job_no).val();
		var date_job_checked = $('#date_job_checked_' + job_no).val();
		
		if($('#bc_number_' + job_no ).val() == 'Other')
		{
			var bc_number = $('#other_reason_' + job_no).val();
		}
		else
		{
			var bc_number = $('#bc_number_' + job_no).val();
		}
	
		var no_units = $('#no_units_' + job_no).val();
		var contract_type = $('#contract_type_' + job_no).val();
		var type_of_build = $('#type_of_build_' + job_no).val();
		var variation_pending = $('#variation_pending_' + job_no).val();
	
		if($('#foundation_type2_' + job_no ).val() == '')
		{
			var foundation_type = $('#foundation_type1_' + job_no ).val();
		}
		else
		{
			var foundation_type = $('#foundation_type2_' + job_no ).val();
		}
	
		var date_logged = $('#date_logged_' + job_no).val();
		var date_issued = $('#date_issued_' + job_no).val();
		var actual_date_issued = $('#actual_date_issued_' + job_no).val();
		var order_site_levels = $('#order_site_levels_' + job_no).val();
		var order_soil_report = $('#order_soil_report_' + job_no).val();
		var septic_tank_approval = $('#septic_tank_approval_' + job_no).val();
		var dev_approval = $('#dev_approval_' + job_no).val();
		var landscape = $('#landscape_' + job_no).val();
		var mss = $('#mss_' + job_no).val();
		var project_manager = $('#project_manager_' + job_no).val();
		var unconditional_date = $('#unconditional_date_' + job_no).val();
		var handover_date = $('#handover_date_' + job_no).val();
		var builder = $('#builder_' + job_no).val();
		var consent_out_but_no_builder = $('#consent_out_but_no_builder_' + job_no).val();
		var title_date_issued = $('#title_date_issued_' + job_no).val();
		var title_date = $('#title_date_' + job_no).val();
		var settlement_date = $('#settlement_date_' + job_no).val();

		var pre_construction_sign = $('#pre_construction_sign_' + job_no).val();
		var designer = $('#designer_' + job_no).val();
		var ok_to_release_to_marketing = $('#ok_to_release_to_marketing_' + job_no).val();
		var pricing_requested = $('#pricing_requested_' + job_no).val();
		var pricing_for_approval = $('#pricing_for_approval_' + job_no).val();
		var approved_for_sale_price = $('#approved_for_sale_price_' + job_no).val();
		var kitchen_disign_type = $('#kitchen_disign_type_' + job_no).val();
		var kitchen_disign_requested = $('#kitchen_disign_requested_' + job_no).val();
		var colours_requested_and_loaded_on_gc = $('#colours_requested_and_loaded_on_gc_' + job_no).val();
		var kitchen_design_loaded_on_gc = $('#kitchen_design_loaded_on_gc_' + job_no).val();
		var developer_colour_sheet_created = $('#developer_colour_sheet_created_' + job_no).val();
		var spec_loaded_on_gc = $('#spec_loaded_on_gc_' + job_no).val();
		var loaded_on_intranet = $('#loaded_on_intranet_' + job_no).val();
		var website = $('#website_' + job_no).val();
		var render_requested = $('#render_requested_' + job_no).val();
		var render_received = $('#render_received_' + job_no).val();
		var brochure = $('#brochure_' + job_no).val();
		var resource_consent = $('#resource_consent_' + job_no).val();
		var rc_number = $('#rc_number_' + job_no).val();
		var expected_date_to_lodge_bc = $('#expected_date_to_lodge_bc_' + job_no).val();
		var water_connection = $('#water_connection_' + job_no).val();
		var vehicle_crossing = $('#vehicle_crossing_' + job_no).val();
		var for_sale_sign = $('#for_sale_sign_' + job_no).val();
		var code_of_compliance = $('#code_of_compliance_' + job_no).val();

		var notes = $('#notes_' + job_no).val();

		data_save += 'job_no' + i + '=' + encodeURIComponent(job_no) + '&consent_id' + i + '=' + encodeURIComponent(consent_id) + '&consent_color' + i + '=' + encodeURIComponent(consent_color)
				+ '&design_id' + i + '=' + encodeURIComponent(design_id) + '&approval_date' + i + '=' + encodeURIComponent(approval_date)
				+ '&price_approved_date' + i + '=' + encodeURIComponent(price_approved_date) + '&pim_logged' + i + '=' + encodeURIComponent(pim_logged)
				+ '&drafting_issue_date' + i + '=' + encodeURIComponent(drafting_issue_date) + '&consent_by' + i + '=' + encodeURIComponent(consent_by)
				+ '&action_required' + i + '=' + encodeURIComponent(action_required) + '&council' + i + '=' + encodeURIComponent(council)
				+ '&lbp' + i + '=' + encodeURIComponent(lbp) + '&date_job_checked' + i + '=' + encodeURIComponent(date_job_checked)
				+ '&bc_number' + i + '=' + encodeURIComponent(bc_number) + '&no_units' + i + '=' + encodeURIComponent(no_units)
				+ '&contract_type' + i + '=' + encodeURIComponent(contract_type) + '&type_of_build' + i + '=' + encodeURIComponent(type_of_build)
				+ '&variation_pending' + i + '=' + encodeURIComponent(variation_pending) + '&foundation_type' + i + '=' + encodeURIComponent(foundation_type)
				+ '&date_logged' + i + '=' + encodeURIComponent(date_logged) + '&date_issued' + i + '=' + encodeURIComponent(date_issued)
				+ '&actual_date_issued' + i + '=' + encodeURIComponent(actual_date_issued) + '&order_site_levels' + i + '=' + encodeURIComponent(order_site_levels)			
				+ '&order_soil_report' + i + '=' + encodeURIComponent(order_soil_report) + '&septic_tank_approval' + i + '=' + encodeURIComponent(septic_tank_approval)
				+ '&dev_approval' + i + '=' + encodeURIComponent(dev_approval) + '&landscape' + i + '=' + encodeURIComponent(landscape)
				+ '&mss' + i + '=' + encodeURIComponent(mss) + '&project_manager' + i + '=' + encodeURIComponent(project_manager)
				+ '&unconditional_date' + i + '=' + encodeURIComponent(unconditional_date) + '&handover_date' + i + '=' + encodeURIComponent(handover_date)
				+ '&builder' + i + '=' + encodeURIComponent(builder) + '&consent_out_but_no_builder' + i + '=' + encodeURIComponent(consent_out_but_no_builder)
				+ '&title_date_issued' + i + '=' + encodeURIComponent(title_date_issued) + '&title_date' + i + '=' + encodeURIComponent(title_date) + '&settlement_date' + i + '=' + encodeURIComponent(settlement_date)
				+ '&pre_construction_sign' + i + '=' + encodeURIComponent(pre_construction_sign) + '&designer' + i + '=' + encodeURIComponent(designer) + '&ok_to_release_to_marketing' + i + '=' + encodeURIComponent(ok_to_release_to_marketing)
				+ '&pricing_requested' + i + '=' + encodeURIComponent(pricing_requested) + '&pricing_for_approval' + i + '=' + encodeURIComponent(pricing_for_approval) + '&approved_for_sale_price' + i + '=' + encodeURIComponent(approved_for_sale_price)
				+ '&kitchen_disign_type' + i + '=' + encodeURIComponent(kitchen_disign_type) + '&kitchen_disign_requested' + i + '=' + encodeURIComponent(kitchen_disign_requested) + '&colours_requested_and_loaded_on_gc' + i + '=' + encodeURIComponent(colours_requested_and_loaded_on_gc)
				+ '&kitchen_design_loaded_on_gc' + i + '=' + encodeURIComponent(kitchen_design_loaded_on_gc) + '&developer_colour_sheet_created' + i + '=' + encodeURIComponent(developer_colour_sheet_created) + '&spec_loaded_on_gc' + i + '=' + encodeURIComponent(spec_loaded_on_gc)
				+ '&loaded_on_intranet' + i + '=' + encodeURIComponent(loaded_on_intranet) + '&website' + i + '=' + encodeURIComponent(website) + '&render_requested' + i + '=' + encodeURIComponent(render_requested) 
				+ '&render_received' + i + '=' + encodeURIComponent(render_received) + '&brochure' + i + '=' + encodeURIComponent(brochure) + '&resource_consent' + i + '=' + encodeURIComponent(resource_consent)
				+ '&rc_number' + i + '=' + encodeURIComponent(rc_number) + '&expected_date_to_lodge_bc' + i + '=' + encodeURIComponent(expected_date_to_lodge_bc) + '&water_connection' + i + '=' + encodeURIComponent(water_connection)
				+ '&vehicle_crossing' + i + '=' + encodeURIComponent(vehicle_crossing) + '&for_sale_sign' + i + '=' + encodeURIComponent(for_sale_sign) + '&code_of_compliance' + i + '=' + encodeURIComponent(code_of_compliance)
				+ '&notes' + i + '=' + encodeURIComponent(notes) + '&';
	}

	$.ajax({ 
		type: 'GET',   
		url: window.Url + 'consent/consent_update/' + month_id + '/' + total_job + '?' + data_save,
		success: function(data) 
		{

			for (i = 0; i < total_job; i++) {

				var job_no = $('#job_' + month_id + '_' + i).html();
			
				var consent_id = $('#consent_id_' + job_no).val();
				var consent_color = $('#consent_color_' + job_no).val();
				var design_id = $('#design_id_' + job_no).val();
				var approval_date = $('#approval_date_' + job_no).val();
				var price_approved_date = $('#price_approved_date_' + job_no).val();
				var pim_logged = $('#pim_logged_' + job_no).val();
				var drafting_issue_date = $('#drafting_issue_date_' + job_no).val();
				var consent_by = $('#consent_by_' + job_no).val();
				var action_required = $('#action_required_' + job_no).val();
				var council = $('#council_' + job_no).val();
				var lbp = $('#lbp_' + job_no).val();
				var date_job_checked = $('#date_job_checked_' + job_no).val();
				
				if($('#bc_number_' + job_no ).val() == 'Other')
				{
					var bc_number = $('#other_reason_' + job_no).val();
				}
				else
				{
					var bc_number = $('#bc_number_' + job_no).val();
				}
			
				var no_units = $('#no_units_' + job_no).val();
				var contract_type = $('#contract_type_' + job_no).val();
				var type_of_build = $('#type_of_build_' + job_no).val();
				var variation_pending = $('#variation_pending_' + job_no).val();
			
				if($('#foundation_type2_' + job_no ).val() == '')
				{
					var foundation_type = $('#foundation_type1_' + job_no ).val();
				}
				else
				{
					var foundation_type = $('#foundation_type2_' + job_no ).val();
				}
			
				var date_logged = $('#date_logged_' + job_no).val();
				var date_issued = $('#date_issued_' + job_no).val();
				var actual_date_issued = $('#actual_date_issued_' + job_no).val();
				var order_site_levels = $('#order_site_levels_' + job_no).val();
				var order_soil_report = $('#order_soil_report_' + job_no).val();
				var septic_tank_approval = $('#septic_tank_approval_' + job_no).val();
				var dev_approval = $('#dev_approval_' + job_no).val();
				var landscape = $('#landscape_' + job_no).val();
				var mss = $('#mss_' + job_no).val();
				var project_manager = $('#project_manager_' + job_no).val();
				var unconditional_date = $('#unconditional_date_' + job_no).val();
				var handover_date = $('#handover_date_' + job_no).val();
				var builder = $('#builder_' + job_no).val();
				var consent_out_but_no_builder = $('#consent_out_but_no_builder_' + job_no).val();
				var title_date = $('#title_date_' + job_no).val();
				var settlement_date = $('#settlement_date_' + job_no).val();
				var notes = $('#notes_' + job_no).val();
 

				$('#0' + '_box_' + job_no  ).attr('class', 'dnone'); $('#15' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#1' + '_box_' + job_no  ).attr('class', 'dnone'); $('#16' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#2' + '_box_' + job_no  ).attr('class', 'dnone'); $('#17' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#37' + '_box_' + job_no  ).attr('class', 'dnone'); $('#18' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#3' + '_box_' + job_no  ).attr('class', 'dnone'); $('#19' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#6' + '_box_' + job_no  ).attr('class', 'dnone'); $('#20' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#7' + '_box_' + job_no  ).attr('class', 'dnone'); $('#21' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#8' + '_box_' + job_no  ).attr('class', 'dnone'); $('#22' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#9' + '_box_' + job_no  ).attr('class', 'dnone'); $('#32' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#29' + '_box_' + job_no  ).attr('class', 'dnone'); $('#33' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#30' + '_box_' + job_no  ).attr('class', 'dnone'); $('#23' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#10' + '_box_' + job_no  ).attr('class', 'dnone'); $('#25' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#11' + '_box_' + job_no  ).attr('class', 'dnone'); $('#26' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#12' + '_box_' + job_no  ).attr('class', 'dnone'); $('#27' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#13' + '_box_' + job_no  ).attr('class', 'dnone'); $('#28' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#14' + '_box_' + job_no  ).attr('class', 'dnone'); $('#34' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#35' + '_box_' + job_no  ).attr('class', 'dnone'); $('#36' + '_box_' + job_no  ).attr('class', 'dnone');
	
				bgcolor_consent_color = '#' + consent_color;
				$('#0' + '_col_' + job_no  ).css('background-color',bgcolor_consent_color);
				$('#0' + '_dis_' + job_no  ).html(consent_id);
	
				if(design_id == 'REQ Brief'){
					bgcolor_design_id = 'yellow';
				}else if(design_id == 'Brief'){
					bgcolor_design_id = 'blue';
				}else if(design_id == 'Hold'){
					bgcolor_design_id = 'red';
				}else if(design_id == 'Sign'){
					bgcolor_design_id = 'orange';
				}else if(design_id == 'Consent'){
					bgcolor_design_id = '#90ee90';
				}else{
					bgcolor_design_id = 'white';
				}
				$('#1' + '_dis_' + job_no  ).html(design_id);			
				$('#1' + '_col_' + job_no  ).css('background-color',bgcolor_design_id);
	
				$('#2' + '_dis_' + job_no  ).html(approval_date);
	
				if(price_approved_date == ''){
					bgcolor_price_approved_date = '';
				}else{
					bgcolor_price_approved_date = '#90ee90';
				}
				$('#37' + '_dis_' + job_no  ).html(price_approved_date);
				$('#37' + '_col_' + job_no  ).css('background-color',bgcolor_price_approved_date);
	
				$('#3' + '_dis_' + job_no  ).html(pim_logged);
	
				if(drafting_issue_date == ''){
					bgcolor_drafting_issue_date = '';
				}else{
					bgcolor_drafting_issue_date = '#90ee90';
				}
				$('#6' + '_dis_' + job_no  ).html(drafting_issue_date);
				$('#6' + '_col_' + job_no  ).css('background-color',bgcolor_drafting_issue_date);
	
				var cb = $('#consent_by_'+job_no+' option:selected').text();
				if(cb=='Select User'){
					$('#7' + '_dis_' + job_no  ).html();
				}else{
					$('#7' + '_dis_' + job_no  ).html(cb);
				}
	
				//$('#8' + '_dis_' + job_no  ).html(action_required);
				$('#9' + '_dis_' + job_no  ).html(council);
				$('#29' + '_dis_' + job_no  ).html(lbp);
				$('#30' + '_dis_' + job_no  ).html(date_job_checked);
				$('#10' + '_dis_' + job_no  ).html(bc_number);
				$('#11' + '_dis_' + job_no  ).html(no_units);
				$('#12' + '_dis_' + job_no  ).html(contract_type);
				$('#13' + '_dis_' + job_no  ).html(type_of_build);
				$('#14' + '_dis_' + job_no  ).html(variation_pending);
				$('#15' + '_dis_' + job_no  ).html(foundation_type);
	
				if(date_logged == ''){
					bgcolor_date_logged = '';
				}else{
					bgcolor_date_logged = '#90ee90';
				}
				$('#16' + '_dis_' + job_no  ).html(date_logged);
				$('#16' + '_col_' + job_no  ).css('background-color',bgcolor_date_logged);
	
				if(date_issued == ''){
					bgcolor_date_issued = '';
				}else{
					bgcolor_date_issued = '#90ee90';
				}
				$('#17' + '_dis_' + job_no  ).html(date_issued);
				$('#17' + '_col_' + job_no  ).css('background-color',bgcolor_date_issued);
	
				$('#18' + '_dis_' + job_no  ).html(actual_date_issued);
	
				if(order_site_levels == 'N&#47;A'){
					bgcolor_order_site_levels = '#FFFFFF';
				}else if(order_site_levels == 'Received'){
					bgcolor_order_site_levels = '#90ee90';
				}else if(order_site_levels == 'Sent'){
					bgcolor_order_site_levels = '#70B5FF';
				}else{
					bgcolor_order_site_levels = '';
				}
				$('#19' + '_dis_' + job_no  ).html(order_site_levels);
				$('#19' + '_col_' + job_no  ).css('background-color',bgcolor_order_site_levels);
	
				if(order_soil_report == 'N&#47;A'){
					bgcolor_order_soil_report = '#FFFFFF';
				}else if(order_soil_report == 'Received'){
					bgcolor_order_soil_report = '#90ee90';
				}else if(order_soil_report == 'Sent'){
					bgcolor_order_soil_report = '#70B5FF';
				}else{
					bgcolor_order_soil_report = '';
				}
				$('#20' + '_dis_' + job_no  ).html(order_soil_report);
				$('#20' + '_col_' + job_no  ).css('background-color',bgcolor_order_soil_report);
	
				if(septic_tank_approval == 'N&#47;A'){
					bgcolor_septic_tank_approval = '#90ee90';
				}else if(septic_tank_approval == 'REQ'){
					bgcolor_septic_tank_approval = 'red';
				}else if(septic_tank_approval == 'SENT'){
					bgcolor_septic_tank_approval = 'blue';
				}else if(septic_tank_approval == 'RECEIVED'){
					bgcolor_septic_tank_approval = '#90ee90';
				}else {
					bgcolor_septic_tank_approval = '';
				}
				$('#21' + '_dis_' + job_no  ).html(septic_tank_approval);
				$('#21' + '_col_' + job_no  ).css('background-color',bgcolor_septic_tank_approval);
	
				if(dev_approval == 'N&#47;A'){
					bgcolor_dev_approval = '#90ee90';
				}else if(dev_approval == 'REQ'){
					bgcolor_dev_approval = 'red';
				}else if(dev_approval == 'PRE SENT'){
					bgcolor_dev_approval = 'blue';
				}else if(dev_approval == 'PRE REC'){
					bgcolor_dev_approval = 'yellow';
				}else if(dev_approval == 'FULL SENT'){
					bgcolor_dev_approval = 'blue';
				}else if(dev_approval == 'FULL REC'){
					bgcolor_dev_approval = '#90ee90';
				}else{
					bgcolor_dev_approval = '';
				}
				$('#22' + '_dis_' + job_no  ).html(dev_approval);
				$('#22' + '_col_' + job_no  ).css('background-color',bgcolor_dev_approval);
	
				if(landscape == 'N&#47;A'){
					bgcolor_landscape = '#90ee90';
				}else if(landscape == 'REQ'){
					bgcolor_landscape = 'red';
				}else if(landscape == 'SENT'){
					bgcolor_landscape = 'blue';
				}else if(landscape == 'RECEIVED'){
					bgcolor_landscape = '#90ee90';
				}else {
					bgcolor_landscape = '';
				}
				$('#32' + '_dis_' + job_no  ).html(landscape);
				$('#32' + '_col_' + job_no  ).css('background-color',bgcolor_landscape);
	
				if(mss == 'REQ'){
					bgcolor_mss = 'red';
				}else if(mss == 'DONE'){
					bgcolor_mss = '#90ee90';
				}else{
					bgcolor_mss = '';
				}
				$('#33' + '_dis_' + job_no  ).html(mss);
				$('#33' + '_col_' + job_no  ).css('background-color',bgcolor_mss);
	
				var pm = $('#project_manager_'+job_no+' option:selected').text();
				if(pm=='Select Project Manager'){
					$('#23' + '_dis_' + job_no  ).html();
				}else{
					$('#23' + '_dis_' + job_no  ).html(pm);
				}
	
				if(unconditional_date == ''){
					bgcolor_unconditional_date = '';
				}else{
					bgcolor_unconditional_date = '#90ee90';
				}
				$('#25' + '_dis_' + job_no  ).html(unconditional_date);
				$('#25' + '_col_' + job_no  ).css('background-color',bgcolor_unconditional_date);
	
				if(handover_date == ''){
					bgcolor_handover_date = '';
				}else{
					bgcolor_handover_date = '#90ee90';
				}
				$('#26' + '_dis_' + job_no  ).html(handover_date);
				$('#26' + '_col_' + job_no  ).css('background-color',bgcolor_handover_date);
	
				var bld = $('#builder_'+job_no+' option:selected').text();
				if(bld=='Select Builder'){
					$('#27' + '_dis_' + job_no  ).html();
				}else{
					$('#27' + '_dis_' + job_no  ).html(bld);
				}
	
				$('#28' + '_dis_' + job_no  ).html(consent_out_but_no_builder);
				$('#34' + '_dis_' + job_no  ).html(title_date);
	
				if(settlement_date == ''){
					bgcolor_settlement_date = '';
				}else{
					bgcolor_settlement_date = '#90ee90';
				}
				$('#35' + '_dis_' + job_no  ).html(settlement_date);
				$('#35' + '_col_' + job_no  ).css('background-color',bgcolor_settlement_date);
	
				$('#36' + '_dis_' + job_no  ).html(notes);
	
				$('#0' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#15' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#1' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#16' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#2' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#17' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#37' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#18' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#3' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#19' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#6' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#20' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#7' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#21' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#8' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#22' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#9' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#32' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#29' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#33' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#30' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#23' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#10' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#25' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#11' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#26' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#12' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#27' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#13' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#28' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#14' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#34' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#35' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#36' + '_dis_' + job_no  ).attr('class', 'dblock');	


				var pre_construction_sign = $('#pre_construction_sign_' + job_no).val();
				var designer = $('#designer_' + job_no).val();
				var ok_to_release_to_marketing = $('#ok_to_release_to_marketing_' + job_no).val();
				var pricing_requested = $('#pricing_requested_' + job_no).val();
				var pricing_for_approval = $('#pricing_for_approval_' + job_no).val();
				var approved_for_sale_price = $('#approved_for_sale_price_' + job_no).val();
				var kitchen_disign_type = $('#kitchen_disign_type_' + job_no).val();
				var kitchen_disign_requested = $('#kitchen_disign_requested_' + job_no).val();
				var colours_requested_and_loaded_on_gc = $('#colours_requested_and_loaded_on_gc_' + job_no).val();
				var kitchen_design_loaded_on_gc = $('#kitchen_design_loaded_on_gc_' + job_no).val();
				var developer_colour_sheet_created = $('#developer_colour_sheet_created_' + job_no).val();
				var spec_loaded_on_gc = $('#spec_loaded_on_gc_' + job_no).val();
				var loaded_on_intranet = $('#loaded_on_intranet_' + job_no).val();
				var website = $('#website_' + job_no).val();
				var render_requested = $('#render_requested_' + job_no).val();
				var render_received = $('#render_received_' + job_no).val();
				var brochure = $('#brochure_' + job_no).val();
				var resource_consent = $('#resource_consent_' + job_no).val();
				var rc_number = $('#rc_number_' + job_no).val();
				var expected_date_to_lodge_bc = $('#expected_date_to_lodge_bc_' + job_no).val();
				var water_connection = $('#water_connection_' + job_no).val();
				var vehicle_crossing = $('#vehicle_crossing_' + job_no).val();
				var for_sale_sign = $('#for_sale_sign_' + job_no).val();
				var code_of_compliance = $('#code_of_compliance_' + job_no).val();

				$('#45' + '_box_' + job_no  ).attr('class', 'dnone'); $('#55' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#46' + '_box_' + job_no  ).attr('class', 'dnone'); $('#56' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#47' + '_box_' + job_no  ).attr('class', 'dnone'); $('#57' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#48' + '_box_' + job_no  ).attr('class', 'dnone'); $('#58' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#49' + '_box_' + job_no  ).attr('class', 'dnone'); $('#59' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#50' + '_box_' + job_no  ).attr('class', 'dnone'); $('#60' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#51' + '_box_' + job_no  ).attr('class', 'dnone'); $('#61' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#52' + '_box_' + job_no  ).attr('class', 'dnone'); $('#62' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#53' + '_box_' + job_no  ).attr('class', 'dnone'); $('#63' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#54' + '_box_' + job_no  ).attr('class', 'dnone'); $('#64' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#65' + '_box_' + job_no  ).attr('class', 'dnone'); $('#67' + '_box_' + job_no  ).attr('class', 'dnone');
				$('#66' + '_box_' + job_no  ).attr('class', 'dnone'); $('#68' + '_box_' + job_no  ).attr('class', 'dnone');

				$('#45' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#55' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#46' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#56' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#47' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#57' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#48' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#58' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#49' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#59' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#50' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#60' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#51' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#61' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#52' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#62' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#53' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#63' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#54' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#64' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#65' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#67' + '_dis_' + job_no  ).attr('class', 'dblock');
				$('#66' + '_dis_' + job_no  ).attr('class', 'dblock'); $('#68' + '_dis_' + job_no  ).attr('class', 'dblock');

				$('#45' + '_dis_' + job_no  ).html(pre_construction_sign); $('#57' + '_dis_' + job_no  ).html(loaded_on_intranet);
				$('#46' + '_dis_' + job_no  ).html(designer); $('#58' + '_dis_' + job_no  ).html(website);
				$('#47' + '_dis_' + job_no  ).html(ok_to_release_to_marketing); $('#59' + '_dis_' + job_no  ).html(render_requested);
				$('#48' + '_dis_' + job_no  ).html(pricing_requested); $('#60' + '_dis_' + job_no  ).html(render_received);
				$('#49' + '_dis_' + job_no  ).html(pricing_for_approval); $('#61' + '_dis_' + job_no  ).html(brochure);
				$('#50' + '_dis_' + job_no  ).html(approved_for_sale_price); $('#62' + '_dis_' + job_no  ).html(resource_consent);
				$('#51' + '_dis_' + job_no  ).html(kitchen_disign_type); $('#63' + '_dis_' + job_no  ).html(rc_number);
				$('#52' + '_dis_' + job_no  ).html(kitchen_disign_requested); $('#64' + '_dis_' + job_no  ).html(expected_date_to_lodge_bc);
				$('#53' + '_dis_' + job_no  ).html(colours_requested_and_loaded_on_gc); $('#65' + '_dis_' + job_no  ).html(water_connection);
				$('#54' + '_dis_' + job_no  ).html(kitchen_design_loaded_on_gc); $('#66' + '_dis_' + job_no  ).html(vehicle_crossing);
				$('#55' + '_dis_' + job_no  ).html(developer_colour_sheet_created); $('#67' + '_dis_' + job_no  ).html(for_sale_sign);
				$('#56' + '_dis_' + job_no  ).html(spec_loaded_on_gc); $('#68' + '_dis_' + job_no  ).html(code_of_compliance);
			}

			$('#cmemory').val('');				
			$('#onclick_img_' + tab_id  ).empty();
		
			$('#in_auck_chch_' + tab_id ).empty();
			$('#in_auck_chch_' + tab_id ).append(data);

			$('#' + tab_id ).attr('class', 'accordion accordion-active');
			$('#consent' + tab_id ).css('display','block');
		},

	});
	
}

function isDate(txtDate)
{

    var currVal = txtDate;
    if(currVal == '')
        return false;
    
    var rxDatePattern = /^(\d{1,2})(\/|-)(\d{1,2})(\/|-)(\d{2})$/; //Declare Regex
    var dtArray = currVal.match(rxDatePattern); // is format OK?
    
	
	
    if (dtArray == null) 
        return false;
    
    //Checks for dd/mm/yy format.
    dtMonth = dtArray[3];
    dtDay= dtArray[1];
    dtYear = dtArray[5];        
    
    if (dtMonth < 1 || dtMonth > 12) 
        return false;
    else if (dtDay < 1 || dtDay> 31) 
        return false;
    else if ((dtMonth==4 || dtMonth==6 || dtMonth==9 || dtMonth==11) && dtDay ==31) 
        return false;
    else if (dtMonth == 2) 
    {
        var isleap = (dtYear % 4 == 0 && (dtYear % 100 != 0 || dtYear % 400 == 0));
        if (dtDay> 29 || (dtDay ==29 && !isleap)) 
                return false;
    }
    return true;
}
function check_available_month()
{
	var month = $('#month_list').val();
	var year = $('#year_list').val();
	var html = $.ajax(
		{
			async: false,
			url: window.Url + 'consent/check_year_month/'+year+'/'+month,
			type: 'POST',
			dataType: 'html',
			timeout: 2000,
		}).responseText;
		
		if(html==0)
		{
			alert('This month already exist!');
			return false;
		}
		else
		{
			return true;
		}
}

jQuery(document).ready(function()
{

	$('.notify').show().fadeOut(4000);
	
	 $('.clickdiv').click(function(){
        //$(this).find('.hiders').toggle();
        $('.hiders').slideToggle();
        $('#minus').toggle();
        $('#plus').toggle();
    });
	$('#refine_search').multiselect();

});



function bc_number_change(bcid,jobid)
{
	//$("#other_reason_"+jobid).hide();
	var bc_number = $("#bc_number_"+jobid).find('option:selected').val();
	if(bc_number == "Other")
	{
		$("#other_reason_"+jobid).show();
	}else{
		$("#other_reason_"+jobid).hide();
	}

	

	$("#other_reason_"+jobid).keyup(function(ev){

 		var othersOption = $('#'+bcid).find('option:selected');

		if(othersOption.val() == "Other")
		{
			ev.preventDefault();
			//change the selected drop down text
  			$(othersOption).html($("#other_reason_"+jobid).val()); 
		} 
	});

}

           
    

</script>	

	<!-- Start CMS Toolbar -->
	<div style="clear:both">
		<div class="consent_toolbar">
		
			<div class="add_month">
				<a class="black_text" id="btnAddMonth" href="#AddMonth" data-toggle="modal">
					<img id="" src="<?php echo base_url(); ?>images/icon/icon_month.png" /><br />
					<span>Add Month</span>
				</a>
			</div>
		
			<div class="add">
				<a class="black_text" id="btnAddjob" href="#AddNewJob" data-toggle="modal">
					<img id="" src="<?php echo base_url(); ?>images/icon/btn_horncastle_note.png" /><br />
					<span>Add</span>
				</a>
			</div>

			<div id="cancel_cons" class="dnone">
				<a class="black_text" id="consentCancel" href="#">
					<img src="<?php echo base_url(); ?>images/icon/cancel_consent.png"  /><br />
					<span>Cancel</span>
				</a>   
			</div>

			<!---<div class="save">
				<a class="black_text" id="consentSave" href="#">
					<img src="<?php echo base_url(); ?>images/icon/btn_horncastle_save_old.png" /><br />
					<span>Save</span>
				</a>	   
			</div>--->
			
			<div id="delete_consent" class="dnone">
				<a class="black_text" id="delete_consent_item" href="#">
					<img src="<?php echo base_url(); ?>images/icon/delete_consent.png" /><br />
					<span>Delete</span>
				</a>  
			</div>
			
			<div id="search_box">
				<div class="searchbox">
					<div class="clickdiv" style="background:#EBEBEB;padding: 5px;text-align:center;">
						<strong> 
							<span> Search </span>
							<span style="<?php if($keywords=='' && $consent_fields==''){ echo 'display:inline;'; }else{ echo 'display:none;'; } ?>" id="plus">+</span>
							<span id="minus" style="<?php if($keywords=='' && $consent_fields==''){ echo 'display:none;'; }else{ echo 'display:inline;'; } ?>">-</span>
						</strong>
					</div> 
					<form action="" method="post" name="searh_cms">
					<div class="hiders" style="<?php if($keywords=='' && $consent_fields==''){ echo 'display:none;'; }else{ echo 'display:block;'; } ?> border:1px solid #EBEBEB; overflow:hidden;"> 
						<div class="row">
							<div class="col-md-8" style="float:left; padding:0%;width:100%">
								<!-- <span>Search:</span> <br> -->
								<input type="hidden" style="border: 1px solid #d3d3d3;border-radius: 4px;padding: 6px;width: 96%;" id="search_filter" name="search_filter" value="<?php echo $keywords; ?>" >
							</div>

							<div class="col-md-4" style="float:left;padding:1%; width:40%">
								<span>Refine Search By:</span><br>
								<select name="consent_fields[]" id="consent_fields" class="multiselectbox" multiple>
									<option <?php if(in_array('consent_name',$refine)){ echo 'selected'; } ?> value="consent_name">Consent</option>
									<option <?php if(in_array('design',$refine)){ echo 'selected'; } ?> value="design">Design</option>
									<option <?php if(in_array('approval_date',$refine)){ echo 'selected'; } ?> value="approval_date">Design Approval Date</option>
									<option <?php if(in_array('price_approved_date',$refine)){ echo 'selected'; } ?> value="price_approved_date">Price Approval Date</option>
									<option <?php if(in_array('pim_logged',$refine)){ echo 'selected'; } ?> value="pim_logged">Pim Logged</option>
									<option <?php if(in_array('drafting_issue_date',$refine)){ echo 'selected'; } ?> value="drafting_issue_date">Drafting Issue </option>
									<option <?php if(in_array('consent_by',$refine)){ echo 'selected'; } ?> value="consent_by">Consent by</option>
									<option <?php if(in_array('action_required',$refine)){ echo 'selected'; } ?> value="action_required">Action Required</option>
									<option <?php if(in_array('council',$refine)){ echo 'selected'; } ?> value="council">Council</option>
									<option <?php if(in_array('lbp',$refine)){ echo 'selected'; } ?> value="lbp">LBP</option>
									<option <?php if(in_array('date_job_checked',$refine)){ echo 'selected'; } ?> value="date_job_checked">Date Job Checked</option>
									<option <?php if(in_array('bc_number',$refine)){ echo 'selected'; } ?> value="bc_number">Bc Number</option>
									<option <?php if(in_array('no_units',$refine)){ echo 'selected'; } ?> value="no_units">No. Units</option>
									<option <?php if(in_array('contract_type',$refine)){ echo 'selected'; } ?> value="contract_type">Contract Type</option>
									<option <?php if(in_array('type_of_build',$refine)){ echo 'selected'; } ?> value="type_of_build">Type of Build</option>
									<option <?php if(in_array('variation_pending',$refine)){ echo 'selected'; } ?> value="variation_pending">Variation Pending</option>
									<option <?php if(in_array('foundation_type',$refine)){ echo 'selected'; } ?> value="foundation_type">Foundation Type</option>
									<option <?php if(in_array('date_logged',$refine)){ echo 'selected'; } ?> value="date_logged">Consent Lodged</option>
									<option <?php if(in_array('date_issued',$refine)){ echo 'selected'; } ?> value="date_issued">Consent Issued</option>
									<option <?php if(in_array('actual_date_issued',$refine)){ echo 'selected'; } ?> value="actual_date_issued">Actual Date Issued</option>
									<option <?php if(in_array('order_site_levels',$refine)){ echo 'selected'; } ?> value="order_site_levels">Order Site Levels</option>
									<option <?php if(in_array('order_soil_report',$refine)){ echo 'selected'; } ?> value="order_soil_report">Order Soil Report</option>
									<option <?php if(in_array('septic_tank_approval',$refine)){ echo 'selected'; } ?> value="septic_tank_approval">Septic Tank Approval</option>
									<option <?php if(in_array('dev_approval',$refine)){ echo 'selected'; } ?> value="dev_approval">Dev Approval</option>
									<option <?php if(in_array('landscape',$refine)){ echo 'selected'; } ?> value="landscape">Landscape</option>
									<option <?php if(in_array('mss',$refine)){ echo 'selected'; } ?> value="mss">MSS</option>
									<option <?php if(in_array('project_manager',$refine)){ echo 'selected'; } ?> value="project_manager">Project Manager</option>
									<option <?php if(in_array('unconditional_date',$refine)){ echo 'selected'; } ?> value="unconditional_date">Unconditional Date</option>
									<option <?php if(in_array('handover_date',$refine)){ echo 'selected'; } ?> value="handover_date">Handover Date</option>
									<option <?php if(in_array('builder',$refine)){ echo 'selected'; } ?> value="builder">Builder</option>
									<option <?php if(in_array('consent_out_but_no_builder',$refine)){ echo 'selected'; } ?> value="consent_out_but_no_builder">Builder Status</option>
									<option <?php if(in_array('title_date',$refine)){ echo 'selected'; } ?> value="title_date">Title Date</option>
									<option <?php if(in_array('settlement_date',$refine)){ echo 'selected'; } ?> value="settlement_date">Settlement Date</option>
									<option <?php if(in_array('notes',$refine)){ echo 'selected'; } ?> value="notes">Notes</option>							
								</select>
							</div>

							<div id="refine_colunm_name" class="col-md-3" style="float:left; padding:1%;width:56%">
								<?php echo $refine_colunm_name; ?>
							</div>
						</div>
						<div style="clear:both;"></div>
						<div class="row">
							<div class="col-md-3" style="float:left; padding:1%;width:50%">
								
							</div>
							
							<div class="col-md-6" style="float:left;padding:1%; width:46%; text-align:right;margin-top:5px;">
								<input type="reset" id="clear_search" class="clear_search" value="Clear Search">
								<input type="submit" name="submit" value="Search" id="search_button" class="search_button">
							</div>
						</div>
						</form>
					</div>
				</div>
			</div>
			
			<div id="search_box2" style="display:none;">
				<div style="font-size:12px;">
					<div style="float:left">Search: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="clear_search()">Clear Search</a></div>
					<div style="float:right"><a onclick="javascript:$('#search_option').toggle('slow');"  href="#">Advanced</a></div>
				</div>
				<input type="text" class="text-input" id="filter" value="" style="width:100%" />
				<div id="msg" style="font-style:italic"></div>
				<div id="search_option" style="width:100%; clear:both; display:none">

					<div style="width:15%; float:left; font-size:11px;">
						<input type="checkbox" name="sjobno" id="sjobno" class="check_class" onclick="advance_search()" /> Job No.<br>
						<input type="checkbox" name="sdrafting_issue_date" id="sdrafting_issue_date"  onclick="advance_search()" /> Drafting Issue Date<br>
						<input type="checkbox" name="stype_of_build"  id="stype_of_build" onclick="advance_search()" /> Type of Build<br>
						<input type="checkbox" name="sorder_soil_report" id="sorder_soil_report" onclick="advance_search()" /> Order Soil Report <br>
					</div>
					
					<div style="width:16%; float:left; font-size:11px;">
						<input type="checkbox" name="sconsent_name" id="sconsent_name" onclick="advance_search()" /> Consent<br>
						<input type="checkbox" name="sconsent_by" id="sconsent_by" onclick="advance_search()" /> Consent by<br>
						<input type="checkbox" name="svariation_pending" id="svariation_pending" onclick="advance_search()" /> Variation Pending<br>
						<input type="checkbox" name="sseptic_tank_approval" id="sseptic_tank_approval" onclick="advance_search()" /> Septic Tank Approval<br>
					</div>

					<div style="width:14%; float:left; font-size:11px;">
						<input type="checkbox" name="sdesign" id="sdesign" id="sdesign" onclick="advance_search()" /> Design<br>
						<input type="checkbox" name="saction_required" id="saction_required"  onclick="advance_search()" /> Action Required<br>
						<input type="checkbox" name="sfoundation_type" id="sfoundation_type" onclick="advance_search()" /> Foundation Type<br>
						<input type="checkbox" name="sdev_approval" id="sdev_approval" onclick="advance_search()" /> Dev Approval<br>
					</div>

					<div style="width:14%; float:left; font-size:11px;">
						<input type="checkbox" name="sapproval_date" id="sapproval_date" onclick="advance_search()" /> Approval Date<br>
						<input type="checkbox" name="scouncil" id="scouncil" onclick="advance_search()" /> Council<br>
						<input type="checkbox" name="sdate_logged" id="sdate_logged" onclick="advance_search()" /> Date Logged<br>
						<input type="checkbox" name="sproject_manager" id="sproject_manager"  onclick="advance_search()"/> Project Manager<br>
					</div>

					<div style="width:13%; float:left; font-size:11px;">
						<input type="checkbox" name="spim_logged" id="spim_logged" onclick="advance_search()" /> Pim Logged<br>
						<input type="checkbox" name="sbc_number" id="sbc_number" onclick="advance_search()" /> Bc Number<br>
						<input type="checkbox" name="sdate_issued" id="sdate_issued" onclick="advance_search()" /> Date Issued<br>
						<input type="checkbox" name="sallocated_to_pm" id="sallocated_to_pm" onclick="advance_search()" /> Allocated to PM<br>
					</div>

					<div style="width:14%; float:left; font-size:11px;">
						<input type="checkbox" name="sin_council" id="sin_council" onclick="advance_search()" /> In Council<br>
						<input type="checkbox" name="sno_units" id="sno_units" onclick="advance_search()" /> No. Units<br>
						<input type="checkbox" name="sdays_in_council" id="sdays_in_council" onclick="advance_search()" /> Days in Council<br>
						<input type="checkbox" name="sunconditional_date" id="sunconditional_date" onclick="advance_search()" /> Unconditional Date<br>
					</div>

					<div style="width:14%; float:left; font-size:11px;">
						<input type="checkbox" name="sconsent_out" id="sconsent_out"  onclick="advance_search()"/> Consent Out<br>
						<input type="checkbox" name="scontract_type" id="scontract_type" onclick="advance_search()" /> Contract Type<br>
						<input type="checkbox" name="sorder_site_levels" id="sorder_site_levels" onclick="advance_search()" /> Order Site Levels<br>
						<input type="checkbox" name="shandover_dat" id="shandover_dat" onclick="advance_search()" /> Handover Date<br>
					</div>

					
				</div>

			</div>

			<div class="zoomout">
				<a href="#"><img id="zoomout" src="<?php echo base_url() ?>images/icon/icon_collapse.png" /></a>
			</div>

			<div class="zoomin">
				<a href="#"><img id="zoomin" src="<?php echo base_url() ?>images/icon/icon_expand.png" /></a>
			</div>

			<div class="download">
				<a class="black_text" target="_blank" id="download_link" href="<?php echo base_url(); ?>consent/consent_list_download/<?php echo $s_month; ?>_/<?php echo $s_month ?>/<?php echo $e_month ?>">
					<img src="<?php echo base_url(); ?>images/icon/icon_down_load.png" /><br />
					<span>Download</span>
				</a>
			</div>
			
			<div class="print">
				<a class="black_text" id="print_link" href="<?php echo base_url(); ?>consent/consent_list_print/<?php echo $s_month; ?>_/<?php echo $s_month ?>/<?php echo $e_month ?>" target="_blank">
					<img src="<?php echo base_url(); ?>images/icon/btn_horncastle_printer_old.png" /><br />
					<span>Print</span>
				</a>
			</div>
			
			<!----<div class="print">
				<a class="black_text" id="btnCreateReport" href="#create_report" data-toggle="modal">
					<img src="<?php echo base_url(); ?>images/icon/icon_report.png" /><br />
					<span>Report</span>
				</a>
			</div>---->
			<div class="print">
				<a class="black_text" id="btnShowArchive" href="<?php echo base_url(); ?>consent/archive" target="_blank">
					<img src="<?php echo base_url(); ?>images/icon/icon_archive.png" /><br />
					<span>Archive</span>
				</a>
			</div>
			
			<input type="hidden" name="email_link_outlook" id="email_link_outlook" value="0-">
			<div class="clear"></div>
		</div>
	</div>
	<!-- End CMS Toolbar -->
	
	

<div id="consent_list" style="clear:both">
	<input type="hidden" name="cmemory" id="cmemory" value="" >
	
	<!-- Add Month Modal -->
	<div id="AddMonth" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h3 id="myModalLabel">Add Month</h3>
		</div>
		<div class="modal-body">
		   <?php
				$action = 'consent/month_add';
				$form_attributes = array('class' => 'month-add-form', 'id' => 'month-add-form','method'=>'post', 'onsubmit' => 'return check_available_month()' );

				$month_list = array(
                  '01'  => 'January',
                  '02'    => 'February',
                  '03'   => 'March',
                  '04' => 'April',
				  '05' => 'May',
				  '06' => 'June',
				  '07'  => 'July',
                  '08'    => 'August',
                  '09'   => 'September',
                  '10' => 'October',
				  '11' => 'November',
				  '12' => 'December'
                );

				$months = form_label('Month', 'month');
				$style_month = " id='month_list' style='width:97%'";
				$months .= form_dropdown('month_list', $month_list,'',$style_month);
				
				$defaul = date('Y');
				$years = form_label('Year', 'year');
				$year_list = array();
				for($i=2010; $i<=2030; $i++ )
				{
					$year_list[$i] = $i;
				}
				$style_year = " id='year_list' style='width:97%'";
				$years .= form_dropdown('year_list', $year_list,$defaul,$style_year);
				
		
				$submit = form_label('', 'submit');
				$submit .= form_submit(array(
							  'name'        => 'submit',
							  'id'          => 'save_user',
							  'value'       => 'submit',
							  'class'       => 'form-submit cms_save',
							  'type'        => 'submit',
					
							  
				));

				echo validation_errors();
				echo form_open($action, $form_attributes);
				echo '<div id="name-wrapper" class="field-wrapper">'. $months . '</div>';
				echo '<div id="name-wrapper" class="field-wrapper">'. $years . '</div>';
				echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
				echo form_fieldset_close(); 
				echo form_close();
			?>
		</div>

	</div>

	<!-- Add Job Modal -->
	<div id="AddNewJob" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h3 id="myModalLabel">Add New Job</h3>
		</div>
		<div class="modal-body">

			<script>
				function show_number(val){
					if(val == 2){
						document.getElementById('no_of_apartment').style.display = 'block';
					}else{
						document.getElementById('no_of_apartment').style.display = 'none';
					}
				}

			</script>

			<?php
				$action = 'consent/job_add';
				$form_attributes = array('class' => 'user-add-form', 'id' => 'entry-form','method'=>'post', 'onsubmit' => 'return checkJobform()' );
				
				$job_number = form_label('Job Number', 'job_no');
				$job_number .= form_input(array(
							  'name'        => 'job_no',
							  'id'          => 'job_no',
							  'class'       => 'form-text',
							  'style'		=> 'width:97%',
							  'onkeyup'    	=> "checkJnumber();",
							  'required'    => TRUE
				));

				$apartment_options = array(
					'1' => 'No',
					'2' => 'Yes'
				);

				$js = 'style="float:left; width:25%; margin-right:20px" onchange="show_number(this.value)"';
				$apt = form_label('Apartment','apartment');
				$apt .= form_dropdown('apertment',$apartment_options,1,$js);
				
				$no_of_apartment = form_label('No Apartment','no_of_apartment');
				$no_of_apartment .= form_input(array(
							  'name'        => 'no_of_apartment',
							  'id'          => 'no_apartment',
							  'class'       => 'form-text',
							  'style'		=> 'width:17%'
				));

				$consent_name = form_label('Consent Name', 'consent_name');
				$consent_name .= form_input(array(
							  'name'        => 'consent_name',
							  'id'          => 'consent_name',
							  'class'       => 'form-text',
							  'style'		=> 'width:97%',
							  'required'    => TRUE
				));
				
				$month_list = array(
                  '01'  => 'January',
                  '02'    => 'February',
                  '03'   => 'March',
                  '04' => 'April',
				  '05' => 'May',
				  '06' => 'June',
				  '07'  => 'July',
                  '08'    => 'August',
                  '09'   => 'September',
                  '10' => 'October',
				  '11' => 'November',
				  '12' => 'December'
                );

				$months = form_label('Month', 'month');
				$style = "style='width:100%'";
				$months .= form_dropdown('month', $month_list,'',$style);
		
				$defaul = date('Y');
				$years = form_label('Year', 'year');
				$year_list = array();
				for($i=2010; $i<=2030; $i++ )
				{
					$year_list[$i] = $i;
				}
				$years .= form_dropdown('year', $year_list,$defaul,$style);
				
				$notes = form_label('Notes', 'notes');
				$notes .= form_input(array(
							  'name'        => 'notes',
							  'id'          => 'notes',
							  'class'       => 'form-text',
							  'style'		=> 'width:97%'
				));

				$submit = form_label('', 'submit');
				$submit .= form_submit(array(
							  'name'        => 'submit',
							  'id'          => 'save_user',
							  'value'       => 'submit',
							  'class'       => 'form-submit cms_save',
							  'type'        => 'submit',
							  
				));

				echo validation_errors();
				echo form_open($action, $form_attributes);
				echo '<div id="name-wrapper" class="field-wrapper">'. $job_number . '<div class="taken"></div></div>';
				echo '<div id="name-wrapper" class="field-wrapper">'. $apt . '<div id="no_of_apartment" style="display:none">'.$no_of_apartment.'</div></div>';
				echo '<div id="name-wrapper" class="field-wrapper">'. $consent_name . '<div id="nusername_alert"></div></div>';
				echo '<div id="email-wrapper" class="field-wrapper">'. $months. '<div id="nemail_alert"></div></div>';
				echo '<div id="email-wrapper" class="field-wrapper">'. $years. '<div id="nemail_alert"></div></div>';
				echo '<div id="access-wrapper" class="field-wrapper">'. $notes . '</div>';
				echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
				echo form_fieldset_close(); 
				echo form_close();
			?>
		</div>
	</div>

	<!-- Create Report Modal -->
	<div id="create_report" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="height:300px; z-index:2000; width:600px;">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h3 id="myModalLabel">Create Report</h3>
		</div>
		<div class="modal-body">
			<?php
		   
				$action = 'consent/show_report';
				$form_attributes = array('class' => 'user-add-form', 'id' => 'entry-form','method'=>'post', 'onsubmit' => 'return checkform()' );

				$submit = form_label('', 'submit');
				$submit .= form_submit(array(
							  'name'        => 'submit',
							  'id'          => 'save_user',
							  'value'       => 'submit',
							  'class'       => 'form-submit cms_save',
							  'type'        => 'submit',
							  
				));

				echo form_open($action, $form_attributes);
				?>
				<div class="row field-wrapper" >
					<div class="col-xm-12 col-sm-12 col-md-12 col-lg-12">
						<div class="">Field(s)*</div>
						<select name="consent_fields[]" class="multiselectbox" multiple>
							<option value="2">Job No</option>
							<option value="3">Consent</option>
							<option value="5">Design</option>
							<option value="6">Approval Date</option>
							<option value="7"> Pim Logged</option>
							<option value="8"> In Council</option>
							<option value="9">Consent Out</option>
							<option value="10"> Drafting Issue </option>
							<option value="11">Consent by</option>
							<option value="12">Action Required</option>
							<option value="13">Council</option>
							<option value="14">LBP</option>
							<option value="15">Date Job Checked</option>
							<option value="16">Bc Number</option>
							<option value="17">No. Units</option>
							<option value="18">Contract Type</option>
							<option value="19">Type of Build</option>
							<option value="20">Variation Pending</option>
							<option value="21">Foundation Type</option>
							<option value="22">Consent Lodged</option>
							<option value="23">Consent Issued</option>
							<option value="24">Actual Date Issued</option>
							<option value="25">Order Site Levels</option>
							<option value="26">Order Soil Report</option>
							<option value="27">Septic Tank Approval</option>
							<option value="28">Dev Approval</option>
							<option value="29">Landscape</option>
							<option value="30">MSS</option>
							<option value="31">Project Manager</option>
							<option value="33">Unconditional Date</option>
							<option value="34">Handover Date</option>
							<option value="35">Builder</option>
							<option value="37">Title Date</option>
							<option value="38">Settlement Date</option>
							<option value="39">Notes</option>
							
						</select>
					</div>
				</div>
				
				<div class="row field-wrapper">
					<div>Keyword</div>
					<input type="text" name="keywords" placeholder="Keyword">
				</div>

				<div class="row field-wrapper">
					<div style="clear:both">
						<div style="float:left; width:48%; margin-right:2%">
							<div>Date From: *</div>
							<input id="report_date_from" type="text" class="live_datepicker" name="report_date_from"> 
						</div>
						<div style="float:left; width:48%">
							<div>Date To:*</div>
							<input id="report_date_to" type="text" class="live_datepicker" name="report_date_to"> 
						</div>
					</div>
				</div>
				
				<div class="row field-wrapper">
					<div class="col-xm-12 col-sm-12 col-md-6 col-lg-6">
						
					</div>
					<div class="col-xm-12 col-sm-12 col-md-6 col-lg-6">
						<input type="button" class="btn btn-cancel" style="float:left;font-size:14px;text-shadow:none; opacity:0.8; margin-right:3px; color:white;" data-dismiss="modal" aria-hidden="true" value="Cancel" >
						<input id="create" class="btn btn-report" name="create" value="Create Report" type="submit"> 
					</div>
				</div>
				
			<?php
				echo form_fieldset_close(); 
				echo form_close();
			?>
		</div>
	</div>
 
	<ul class="accordions toggles">
		<?php
		
			$total_months = 15;
			$now = date('Y-m-d');
			$today_time = strtotime($now);
			$last_month = date("F Y", strtotime("-2 months"));

			$user =  $this->session->userdata('user');  
			$user_group_id =  $this->session->userdata('user_group_id'); 			
			
			$user_permission_type = $ci->consent_model->get_user_permission_type($user_group_id);

		?>
		
		

			<?php

			for($p = $s_month; $p >= $e_month; $p--)
			{

				
			
				$month = date("F Y", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));
				
				$month_start_date = date("Y-m-d", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));
				
				$month_last_day = date("Y-m-t", strtotime($month_start_date));
				
				
				//$consent_info = $ci->consent_model->get_consent_info($month_start_date,$month_last_day);


                $month_id = date("Ym", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));

				if($ci->consent_model->check_available_month($month_id))
				{

					$consent_info = $ci->consent_model->get_consent_info_by_monthid($month_id,0,$keywords,$consent_fields_session,$report_search_value_1,$search_start_date,$search_end_date);
					
					$total_consents_in_auck = $ci->consent_model->get_total_consents_in($month_id,'Auckland')->total_consent_in;
					$total_consents_in_chch = $ci->consent_model->get_total_consents_in($month_id,'Chch')->total_consent_in;
					$total_consents_out_auck = $ci->consent_model->get_total_consents_out($month_id,'Auckland')->total_consent_out;
					$total_consents_out_chch = $ci->consent_model->get_total_consents_out($month_id,'Chch')->total_consent_out;
					$total_consents_handover = $ci->consent_model->get_total_consents_handover($month_id)->total_consents_handover;
					//print_r($consent_info);

			       //if($consent_info){

			$tab_display = '';
			$tab_display1 = 'style="display: none;"';
			for($a = 0; $a < count($tab_ids); $a++)
			{
				if($tab_ids[$a] == $p)
				{
					$tab_display = 'accordion-active';
					$tab_display1 = 'style="display: block;"';
					break;
				}
			}	
			?>
			<li id="<?php echo $p; ?>" class="accordion <?php echo $tab_display; ?>">
				<div class="accordion-header">
					<h3 style="margin: 0; padding:0;">
						<div style="float:left; width:17%; color:#181818;font-size: 16px;"><?php echo $month; ?></div>
						<div style="float:right; width:83%;font-size:13px">Total jobs scheduled this month: <b><span id="total_job_<?php echo $p; ?>"><?php echo count($consent_info);  ?></span></b> <span id="in_auck_chch_<?php echo $p; ?>">&nbsp;&nbsp;| &nbsp;&nbsp; Total consents IN &nbsp;&nbsp; <b>AUCK: <?php echo $total_consents_in_auck; ?> &nbsp;&nbsp; CHCH: <?php echo $total_consents_in_chch; ?></b>&nbsp;&nbsp; | &nbsp;&nbsp;  Total consents OUT: <b>AUCK: <?php echo $total_consents_out_auck; ?> &nbsp;&nbsp; CHCH: <?php echo $total_consents_out_chch; ?></b>&nbsp;&nbsp;| &nbsp;&nbsp;Total handovers: <b><?php echo $total_consents_handover; ?></b></span><span style="margin-left:10px;" id="onclick_img_<?php echo $p; ?>"></span><div class="accordion-icon"></div></div>
					</h3>
				</div>
				<div style="clear:both;"></div>
				<div class="accordion-content" id="consent<?php echo $p; ?>" onscroll="divScroll(this.id);" <?php echo $tab_display1; ?>>
                                    

				<table id="table<?php echo $p; ?>" class="consent_table tablesorter" border="0" cellspacing="0" cellpadding="0">
				<thead>
				<th style="width:1%">Job No.</th>
				<?php if($user_permission_type[0]->display_type == 1){ ?>
				<th style="width:3%;">Consent</th>
				<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Pre-construction sign</th>
<?php } ?>
				<?php if($user_permission_type[1]->display_type == 1){ ?>
				<th style="width:1%">Design</th>
				<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Designer</th>
<?php } ?>
				<?php if($user_permission_type[2]->display_type == 1){ ?>
				<th style="width:2%">Design Approval Date</th>
				<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Ok to release to Marketing</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Pricing requested</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Pricing for approval</th>
<?php } ?>
				<?php if($user_permission_type[2]->display_type == 1){ ?>
				<th style="width:2%">Price Approved Date</th>
				<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Approved For Sale Price</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Kitchen Design Type</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Kitchen Design Requested</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Colours Requested & Loaded on GC</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Kitchen Design Loaded on GC</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Developer Colour Sheet Created</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Spec Loaded on GC</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Loaded on Intranet</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Website</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Render Requested</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Render Received</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Brochure</th>
<?php } ?>
				<?php if($user_permission_type[3]->display_type == 1){ ?>
				<th style="width:2%">Pim <br> Lodged</th>
				<?php } ?>
				<?php if($user_permission_type[6]->display_type == 1){ ?>
				<th style="width:2%">Drafting <br>Issue Date</th>
				<?php } ?>
				<?php if($user_permission_type[7]->display_type == 1){ ?>
				<th style="width:2%">Consent<br>by</th>
				<?php } ?>
				<?php if($user_permission_type[8]->display_type == 1){ ?>
				<th style="width:2%">Action<br>Required</th>
				<?php } ?>
				<?php if($user_permission_type[9]->display_type == 1){ ?>
				<th style="width:2%">Council</th>
				<?php } ?>		
				<?php if($user_permission_type[28]->display_type == 1){ ?>
				<th style="width:2%">LBP</th>
				<?php } ?>
				<?php if($user_permission_type[28]->display_type == 1){ ?>
				<th style="width:2%">Date Job Checked</th>
				<?php } ?>
				<?php if($user_permission_type[10]->display_type == 1){ ?>
				<th style="width:2%">Bc Number</th>
				<?php } ?>
				<?php if($user_permission_type[11]->display_type == 1){ ?>
				<th style="width:1%">No. Units</th>
				<?php } ?>
				<?php if($user_permission_type[12]->display_type == 1){ ?>
				<th style="width:1%">Contract Type</th>
				<?php } ?>
				<?php if($user_permission_type[13]->display_type == 1){ ?>
				<th style="width:1%">Type of <br>Build</th>
				<?php } ?>
				<?php if($user_permission_type[14]->display_type == 1){ ?>
				<th style="width:2%">Variation <br>Pending</th>
				<?php } ?>
				<?php if($user_permission_type[15]->display_type == 1){ ?>
				<th style="width:2%">Foundation<br>Type</th>
				<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Resource Consent</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">RC Number</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Expected Date to Lodge BC</th>
<?php } ?>
				<?php if($user_permission_type[16]->display_type == 1){ ?>
				<th style="width:2%">Consent<br>Lodged</th>
				<?php } ?>
				<?php if($user_permission_type[17]->display_type == 1){ ?>
				<th style="width:2%">Consent <br>Issued</th>
				<?php } ?>
				<?php if($user_permission_type[17]->display_type == 1){ ?>
				<th style="width:2%">Actual <br>Date Issued</th>
				<?php } ?>
				<?php if($user_permission_type[18]->display_type == 1){ ?>
				<th style="width:1%">Days in <br>Council</th>
				<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Water Connection</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Vehicle Crossing</th>
<?php } ?>
				<?php if($user_permission_type[19]->display_type == 1){ ?>
				<th style="width:2%">Order Site <br>Levels</th>
				<?php } ?>
				<?php if($user_permission_type[20]->display_type == 1){ ?>
				<th style="width:2%">Order Soil <br>Report</th>
				<?php } ?>
				<?php if($user_permission_type[21]->display_type == 1){ ?>
				<th style="width:2%">Septic Tank <br>Approval</th>
				<?php } ?>
				<?php if($user_permission_type[22]->display_type == 1){ ?>
				<th style="width:2%">Dev Approval</th>
				<?php } ?>
				<?php if($user_permission_type[22]->display_type == 1){ ?>
				<th style="width:2%">Landscape</th>
				<?php } ?>
				<?php if($user_permission_type[22]->display_type == 1){ ?>
				<th style="width:2%">MSS</th>
				<?php } ?>
				<?php if($user_permission_type[23]->display_type == 1){ ?>
				<th style="width:2%">Project Manager</th>
				<?php } ?>
				<?php if($user_permission_type[25]->display_type == 1){ ?>
				<th style="width:2%">Unconditional <br>Date</th>
				<?php } ?>
				<?php if($user_permission_type[26]->display_type == 1){ ?>
				<th style="width:2%">Handover Date</th>
				<?php } ?>
				<?php if($user_permission_type[27]->display_type == 1){ ?>
				<th style="width:2%">Builder</th>
				<?php } ?>
				<?php if($user_permission_type[28]->display_type == 1){ ?>
				<th style="width:2%">Builder Status</th>
				<?php } ?>
				<?php if($user_permission_type[28]->display_type == 1){ ?>
				<th style="width:2%">Title Date</th>
				<?php } ?>
				<?php if($user_permission_type[28]->display_type == 1){ ?>
				<th style="width:2%">Settlement Date</th>
				<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">For Sale Sign</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Code of Compliance</th>
<?php } ?>
<?php if($user_permission_type[31]->display_type == 1){ ?>
<th style="width:2%;">Photo's Taken</th>
<?php } ?>
				<?php if($user_permission_type[28]->display_type == 1){ ?>
				<th style="width:2%">Notes
					<div id="month_id_<?php echo $p; ?>" class="dnone"><?php echo $month_id; ?></div>
				</th>
				<?php } ?>
				
			</thead>
			
			<tbody id="<?php echo $month_id; ?>" class="tbody<?php echo $p; ?> connectedSortable">
			
				<?php 
					$n = 0;

					for($t = 0; $t < count($consent_info) ; $t++)
					{

						$consent_child = $ci->consent_model->get_consent_info_by_monthid($month_id,$consent_info[$t]->id,$keywords,$consent_fields,$report_search_value_1,$search_start_date,$search_end_date);			
						if($consent_child){$toggle = "showchild(".$consent_info[$t]->id.",this.id);"; $toggle_class="toggle_off"; }else{$toggle =''; $toggle_class="";}
				?>

				<tr id="consent_<?php echo $consent_info[$t]->month_id.'_'.$consent_info[$t]->id; ?>" class="" onclick="selectrow(this.id,this.className);  ">
				<td id="job_<?php echo $month_id; ?>_<?php echo $n; ?>" onclick="<?php echo $toggle; ?>" ><div class="<?php echo $toggle_class; ?>" ></div><?php echo $consent_info[$t]->job_no; ?></td>

				<?php 	
				if($user_permission_type[0]->display_type == 1)
				{ ?>
				<td id="0_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:3%; height:30px; background-color:#<?php if($consent_info[$t]->unconditional_date != '0000-00-00'){echo "white;color:red;";}else if($consent_info[$t]->consent_color=='72D660'){ echo '90ee90'; }else{ echo $consent_info[$t]->consent_color;}  ?>">
					
				<?php 	if($user_permission_type[0]->read_type == 2)
						{
					?>
					<div id="0_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input type='text' id="consent_id_<?php echo $consent_info[$t]->job_no ?>" name="consent_name" value="<?php echo $consent_info[$t]->consent_name; ?> " onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','consent_name','consent_id_<?php echo $consent_info[$t]->job_no ?>',0)" />
						&nbsp;
						
						&nbsp;
						<select id="consent_color_<?php echo $consent_info[$t]->job_no ?>" name="consent_color">
							<option value=""></option>
							<option <?php if($consent_info[$t]->consent_color=='ffffff'){ echo 'selected'; } ?> value="ffffff">White</option>
							<option <?php if($consent_info[$t]->consent_color=='90ee90'){ echo 'selected'; }else if($consent_info[$t]->consent_color=='72D660'){ echo 'selected'; } ?> value="90ee90">Green</option>
							<option <?php if($consent_info[$t]->consent_color=='FF3D3D'){ echo 'selected'; } ?> value="FF3D3D">Red</option>
							<option <?php if($consent_info[$t]->consent_color=='FFAC40'){ echo 'selected'; } ?> value="FFAC40">Orange</option>
						</select>
						
					</div>
					<div id="0_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="0_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
					<?php }	?>
					<div id="0_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->consent_name; ?></div> 		
				</td>
				<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="45_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="45_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="pre_construction_sign_<?php echo $consent_info[$t]->job_no ?>" name="pre_construction_sign" value="<?php echo $consent_info[$t]->pre_construction_sign; ?>" />		
	</div>
	<div id="45_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="45_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="45_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->pre_construction_sign; ?></div> 		
</td>
<?php 	} ?>

				<?php 	
				if($user_permission_type[1]->display_type == 1)
				{ 
					$design_bg = '';
					if($consent_info[$t]->design == 'REQ Brief'){$design_bg = 'yellow';}
					if($consent_info[$t]->design == 'Brief'){$design_bg = 'blue';}
					if($consent_info[$t]->design == 'Hold'){$design_bg = 'red';}
					if($consent_info[$t]->design == 'Sign'){$design_bg = 'orange';}
					if($consent_info[$t]->design == 'Consent'){$design_bg = '#90ee90';}
				?>
				<td id="1_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:1%; background-color:<?php echo $design_bg;?>">
					
				<?php
					if($user_permission_type[1]->read_type == 2)
					{
					?>
					<div id="1_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="design" id="design_id_<?php echo $consent_info[$t]->job_no ?>">
							<option value=""></option>
							<option <?php if($consent_info[$t]->design == 'REQ Brief'){ echo 'selected'; } ?> value='REQ Brief'>REQ Brief</option>
							<option <?php if($consent_info[$t]->design == 'Brief'){ echo 'selected'; } ?> value='Brief'>Brief</option>
							<option <?php if($consent_info[$t]->design == 'Hold'){ echo 'selected'; } ?> value='Hold'>Hold</option>
							<option <?php if($consent_info[$t]->design == 'Sign'){ echo 'selected'; } ?> value='Sign'>Sign</option>
							<option <?php if($consent_info[$t]->design == 'Consent'){ echo 'selected'; } ?> value='Consent'>Consent</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="1_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="1_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
					<?php }	?>
					<div id="1_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->design; ?></div> 
				</td>
				<?php } ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="46_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="46_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="designer_<?php echo $consent_info[$t]->job_no ?>" name="designer" value="<?php echo $consent_info[$t]->designer; ?>" />		
	</div>
	<div id="46_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="46_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="46_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->designer; ?></div> 		
</td>
<?php 	} ?>

				<?php 	
							if($user_permission_type[2]->display_type == 1)
							{ 
				?>
				<td id="2_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">

				<?php	
								if($user_permission_type[2]->read_type == 2)
								{
					?>
					<div id="2_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="approval_date_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="approval_date" value="<?php if($consent_info[$t]->approval_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->approval_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','approval_date','approval_date_<?php echo $consent_info[$t]->job_no ?>',2);" > 
						&nbsp;
						
					</div>
					<div id="2_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="2_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
					<?php		}	?>
					<div id="2_dis_<?php echo $consent_info[$t]->job_no ?>"><?php if($consent_info[$t]->approval_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->approval_date); } ?></div> 
					

				</td>
				<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="47_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="47_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="ok_to_release_to_marketing_<?php echo $consent_info[$t]->job_no ?>" name="ok_to_release_to_marketing" value="<?php echo $consent_info[$t]->ok_to_release_to_marketing; ?>" />		
	</div>
	<div id="47_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="47_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="47_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->ok_to_release_to_marketing; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="48_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="48_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="pricing_requested_<?php echo $consent_info[$t]->job_no ?>" name="pricing_requested" value="<?php echo $consent_info[$t]->pricing_requested; ?>" />		
	</div>
	<div id="48_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="48_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="48_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->pricing_requested; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="49_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="49_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="pricing_for_approval_<?php echo $consent_info[$t]->job_no ?>" name="pricing_for_approval" value="<?php echo $consent_info[$t]->pricing_for_approval; ?>" />		
	</div>
	<div id="49_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="49_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="49_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->pricing_for_approval; ?></div> 		
</td>
<?php 	} ?>

				<?php 	
							if($user_permission_type[2]->display_type == 1)
							{ 
				?>
				<td id="37_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; <?php if($consent_info[$t]->price_approved_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>" >

				<?php
								if($user_permission_type[2]->read_type == 2)
								{
					?>
					<div id="37_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="price_approved_date_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="price_approved_date" value="<?php if($consent_info[$t]->price_approved_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->price_approved_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','price_approved_date','price_approved_date_<?php echo $consent_info[$t]->job_no ?>',2);" > 
						&nbsp;
						
					</div>
					<div id="37_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="37_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
					<?php		}	?>
					<div id="37_dis_<?php echo $consent_info[$t]->job_no ?>"><?php if($consent_info[$t]->price_approved_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->price_approved_date); } ?></div> 
					

				</td>
				<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="50_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="50_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="approved_for_sale_price_<?php echo $consent_info[$t]->job_no ?>" name="approved_for_sale_price" value="<?php echo $consent_info[$t]->approved_for_sale_price; ?>" />		
	</div>
	<div id="50_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="50_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="50_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->approved_for_sale_price; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="51_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="51_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="kitchen_disign_type_<?php echo $consent_info[$t]->job_no ?>" name="kitchen_disign_type" value="<?php echo $consent_info[$t]->kitchen_disign_type; ?>" />		
	</div>
	<div id="51_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="51_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="51_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->kitchen_disign_type; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="52_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="52_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="kitchen_disign_requested_<?php echo $consent_info[$t]->job_no ?>" name="kitchen_disign_requested" value="<?php echo $consent_info[$t]->kitchen_disign_requested; ?>" />		
	</div>
	<div id="52_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="52_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="52_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->kitchen_disign_requested; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="53_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="53_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="colours_requested_and_loaded_on_gc_<?php echo $consent_info[$t]->job_no ?>" name="colours_requested_and_loaded_on_gc" value="<?php echo $consent_info[$t]->colours_requested_and_loaded_on_gc; ?>" />		
	</div>
	<div id="53_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="53_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="53_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->colours_requested_and_loaded_on_gc; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="54_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="54_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="kitchen_design_loaded_on_gc_<?php echo $consent_info[$t]->job_no ?>" name="kitchen_design_loaded_on_gc" value="<?php echo $consent_info[$t]->kitchen_design_loaded_on_gc; ?>" />		
	</div>
	<div id="54_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="54_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="54_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->kitchen_design_loaded_on_gc; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="55_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="55_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="developer_colour_sheet_created_<?php echo $consent_info[$t]->job_no ?>" name="developer_colour_sheet_created" value="<?php echo $consent_info[$t]->developer_colour_sheet_created; ?>" />		
	</div>
	<div id="55_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="55_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="55_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->developer_colour_sheet_created; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="56_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="56_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="spec_loaded_on_gc_<?php echo $consent_info[$t]->job_no ?>" name="spec_loaded_on_gc" value="<?php echo $consent_info[$t]->spec_loaded_on_gc; ?>" />		
	</div>
	<div id="56_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="56_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="56_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->spec_loaded_on_gc; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="57_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="57_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="loaded_on_intranet_<?php echo $consent_info[$t]->job_no ?>" name="loaded_on_intranet" value="<?php echo $consent_info[$t]->loaded_on_intranet; ?>" />		
	</div>
	<div id="57_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="57_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="57_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->loaded_on_intranet; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="58_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="58_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="website_<?php echo $consent_info[$t]->job_no ?>" name="website" value="<?php echo $consent_info[$t]->website; ?>" />		
	</div>
	<div id="58_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="58_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="58_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->website; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="59_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="59_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="render_requested_<?php echo $consent_info[$t]->job_no ?>" name="render_requested" value="<?php echo $consent_info[$t]->render_requested; ?>" />		
	</div>
	<div id="59_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="59_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="59_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->render_requested; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="60_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="60_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="render_received_<?php echo $consent_info[$t]->job_no ?>" name="render_received" value="<?php echo $consent_info[$t]->render_received; ?>" />		
	</div>
	<div id="60_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="60_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="60_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->render_received; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="61_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="61_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="brochure_<?php echo $consent_info[$t]->job_no ?>" name="brochure" value="<?php echo $consent_info[$t]->brochure; ?>" />		
	</div>
	<div id="61_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="61_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="61_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->brochure; ?></div> 		
</td>
<?php 	} ?>			
				<?php 	
					if($user_permission_type[3]->display_type == 1)
							{ 
				?>
				<td id="3_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">

				<?php	
						if($user_permission_type[3]->read_type == 2)
								{
					?>
					<div id="3_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="pim_logged_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="pim_logged" value="<?php if($consent_info[$t]->pim_logged != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->pim_logged); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','pim_logged','pim_logged_<?php echo $consent_info[$t]->job_no ?>',3)" src="<?php echo base_url(); ?>images/icon/edit_pass.png" > 
						&nbsp;
						
					</div>
					<div id="3_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="3_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
					<?php		}	?>
					<div id="3_dis_<?php echo $consent_info[$t]->job_no ?>"><?php if($consent_info[$t]->pim_logged != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->pim_logged); } ?></div> 
				</td>

				<?php 	} ?>

				


				<?php 	
							if($user_permission_type[6]->display_type == 1)
							{ 
				?>
				<td id="6_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; <?php if($consent_info[$t]->drafting_issue_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
				<?php				if($user_permission_type[6]->read_type == 2)
								{
					?>
					<div id="6_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="drafting_issue_date_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="drafting_issue_date" value="<?php if($consent_info[$t]->drafting_issue_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->drafting_issue_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','drafting_issue_date','drafting_issue_date_<?php echo $consent_info[$t]->job_no ?>',6)" src="<?php echo base_url(); ?>images/icon/edit_pass.png"  > 
						&nbsp;
						
					</div>
					<div id="6_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="6_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
					<?php		}	?>
					<div id="6_dis_<?php echo $consent_info[$t]->job_no ?>"><?php if($consent_info[$t]->drafting_issue_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->drafting_issue_date); } ?></div> 
				</td>
				<?php 	} ?>

				<?php 	
							if($user_permission_type[7]->display_type == 1)
							{ 
				?>
				<td id="7_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">
				<?php
					
								if($user_permission_type[7]->read_type == 2)
								{
					?>
					<div id="7_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name='consent_by' id="consent_by_<?php echo $consent_info[$t]->job_no ?>">
							<option value='0'>Select User</option>
								<?php  foreach ($consent_by_list as $consent_by){ ?> <option value='<?php echo $consent_by->uid ?>' <?php if( $consent_by->uid == $consent_info[$t]->consent_by){ ?> selected="selected" <?php } ?> > <?php echo $consent_by->fullname ?></option> <?php } ?>
						</select> 
						&nbsp;
						
					</div>
					<div id="7_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="7_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="7_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->consent_by; ?></div> 
				</td>
				<?php 	} ?>

				<?php 	
					if($user_permission_type[8]->display_type == 1)
							{ $txt = '';
				?>
				<td id="8_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; background-color:
				<?php
					$fourteen_days_before = date('Y-m-d', strtotime('-14 days'));
					$thirty_days_before = date('Y-m-d', strtotime('-30 days'));
					$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
					if( $consent_info[$t]->unconditional_date!='0000-00-00' && $consent_info[$t]->unconditional_date < $fourteen_days_before && $consent_info[$t]->drafting_issue_date == '0000-00-00'){echo "red; color:white;"; $txt = 'Issue For Consent'; }
					if( $consent_info[$t]->drafting_issue_date < $thirty_days_before && $consent_info[$t]->drafting_issue_date != '0000-00-00'){echo "red; color:white;"; $txt = 'Drawings Late'; }
					if( $consent_info[$t]->date_logged != '0000-00-00' && $consent_info[$t]->date_logged  < $twenty_days_before ){echo "red; color:white;"; $txt = 'Consent Late';}
				?>">	
				<?php	if($user_permission_type[8]->read_type == 2)
								{
					?>
					<div id="8_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="action_required" id="action_required_<?php echo $consent_info[$t]->job_no ?>">
							<option value="">Select Action</option>
							<option <?php if($consent_info[$t]->action_required == 'Urgent'){ echo 'selected'; } ?> value="Urgent">Urgent</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="8_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="8_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="8_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $txt; 	//if($consent_info[$t]->action_required != 0 ){ echo $consent_info[$t]->action_required; } ?></div> 
				
				</td>
				<?php 	} ?>

				<?php 	
							if($user_permission_type[9]->display_type == 1)
							{ 
				?>
				<td id="9_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">
				<?php	
								if($user_permission_type[9]->read_type == 2)
								{
					?>
					<div id="9_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="council" id="council_<?php echo $consent_info[$t]->job_no ?>">
							<option value=""></option>
							<option <?php if($consent_info[$t]->council == 'Ashburton'){ echo 'selected'; } ?> value='Ashburton'>Ashburton</option>
							<option <?php if($consent_info[$t]->council == 'Auckland'){ echo 'selected'; } ?> value='Auckland'>Auckland</option>
							<option <?php if($consent_info[$t]->council == 'Chch'){ echo 'selected'; } ?> value='Chch'>Chch</option>
							<option <?php if($consent_info[$t]->council == 'Hurunui'){ echo 'selected'; } ?> value='Hurunui'>Hurunui</option>
							<option <?php if($consent_info[$t]->council == 'Selwyn'){ echo 'selected'; } ?> value='Selwyn'>Selwyn</option>
							<option <?php if($consent_info[$t]->council == 'Waikato'){ echo 'selected'; } ?> value='Waikato'>Waikato</option>
							<option <?php if($consent_info[$t]->council == 'Waimak'){ echo 'selected'; } ?> value='Waimak'>Waimak</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="9_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="9_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="9_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->council; ?></div> 
				</td>
				<?php 	} ?>
				
				
				<?php 	
				if($user_permission_type[29]->display_type == 1)
				{ ?>
				<td id="29_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">
					<?php
					if($user_permission_type[29]->read_type == 2)
					{
					?>

					
					<div id="29_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">

						<select name="lbp" id="lbp_<?php echo $consent_info[$t]->job_no ?>">
							<option value=""></option>
							<option <?php if($consent_info[$t]->lbp == 'Susan G'){ echo 'selected'; } ?> value="Susan G">Susan G</option>
							<option <?php if($consent_info[$t]->lbp == 'Mark B'){ echo 'selected'; } ?> value="Mark B">Mark B</option>
							<option <?php if($consent_info[$t]->lbp == 'Nathan V'){ echo 'selected'; } ?> value="Nathan V">Nathan V</option>
							<option <?php if($consent_info[$t]->lbp == 'Selina A'){ echo 'selected'; } ?> value="Selina A">Selina A</option>
							<option <?php if($consent_info[$t]->lbp == 'Chelsea K'){ echo 'selected'; } ?> value="Chelsea K">Chelsea K</option>
							<option <?php if($consent_info[$t]->lbp == 'Jos K'){ echo 'selected'; } ?> value="Jos K">Jos K</option>
							<option <?php if($consent_info[$t]->lbp == 'Andy D'){ echo 'selected'; } ?> value="Andy D">Andy D</option>
						</select>
						<!-- <input type="text" value="<?php echo $consent_info[$t]->lbp;  ?>" name="lbp" id="lbp_<?php echo $consent_info[$t]->job_no ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','lbp','lbp_<?php echo $consent_info[$t]->job_no ?>',29)" /> -->
						&nbsp;&nbsp;
						
					</div>
					<div id="29_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="29_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php	}	?>
					<div id="29_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->lbp; ?></div> 				
				</td>
				<?php } ?>
				
				
				<?php 	
					if($user_permission_type[30]->display_type == 1)
					{ 
				?>
				<td id="30_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">
				<?php
						if($user_permission_type[30]->read_type == 2)
						{
					?>
					<div id="30_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="date_job_checked_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="date_job_checked" value="<?php if($consent_info[$t]->date_job_checked != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->date_job_checked); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','date_job_checked','date_job_checked_<?php echo $consent_info[$t]->job_no ?>',30)" src="<?php echo base_url(); ?>images/icon/edit_pass.png" > 
						&nbsp;
						
					</div>
					<div id="30_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="30_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="30_dis_<?php echo $consent_info[$t]->job_no ?>"><?php if($consent_info[$t]->date_job_checked != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->date_job_checked); } ?></div> 
					
				</td>
				<?php 	} ?>
				
				

				<?php 	
							if($user_permission_type[10]->display_type == 1)
							{ 	
				?>
				<td id="10_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">
				<?php	
								if($user_permission_type[10]->read_type == 2)
								{
					?>
					<div id="10_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<!-- <input type='text' id="bc_number_<?php echo $consent_info[$t]->job_no ?>" name="bc_number" value="<?php echo $consent_info[$t]->bc_number; ?> " onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','bc_number','bc_number_<?php echo $consent_info[$t]->job_no ?>',10)" />&nbsp;-->
						<select name="bc_number" id="bc_number_<?php echo $consent_info[$t]->job_no ?>" onchange="bc_number_change(this.id,'<?php echo $consent_info[$t]->job_no;?>')">
							<option value=""></option>
							<option <?php if($consent_info[$t]->bc_number == 'Checking'){ echo 'selected'; } ?> value="Checking">Checking</option>
							<option <?php if($consent_info[$t]->bc_number == 'Checked'){ echo 'selected'; } ?> value="Checked">Checked</option>
							<option <?php if($consent_info[$t]->bc_number != 'Checking' || $consent_info[$t]->bc_number != 'Checked'){ echo 'selected'; } ?> value="Other">Other</option>
						</select>
						<input value="<?php if($consent_info[$t]->bc_number != 'Checking' || $consent_info[$t]->bc_number != 'Checked'){ echo $consent_info[$t]->bc_number; } ?>" id="other_reason_<?php echo $consent_info[$t]->job_no ?>" name="other_reason" type="text" placeholder="Other Reason" style="display:none;" />
						
					</div>
					<div id="10_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="10_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="10_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->bc_number; ?></div> 
					
				</td>
				<?php 	} ?>

				<?php 	
				if($user_permission_type[11]->display_type == 1)
				{ 
				?>
				<td id="11_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:1%">
				<?php	
								if($user_permission_type[11]->read_type == 2)
								{
					?>
					<div id="11_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input type='text' id="no_units_<?php echo $consent_info[$t]->job_no ?>" name="no_units" value="<?php echo $consent_info[$t]->no_units; ?> " onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','no_units','no_units_<?php echo $consent_info[$t]->job_no ?>',11)" >&nbsp;
						
					</div>
					<div id="11_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="11_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="11_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->no_units; ?></div> 					
				</td>
				<?php 	} ?>

				<?php 	
							if($user_permission_type[12]->display_type == 1)
							{ 
				?>
				<td id="12_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:1%">
				<?php	
								if($user_permission_type[12]->read_type == 2)
								{
					?>
					<div id="12_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="contract_type" id="contract_type_<?php echo $consent_info[$t]->job_no ?>">
							<option value=""></option>
							<option <?php if($consent_info[$t]->contract_type == 'BC'){ echo 'selected'; } ?> value='BC'>BC</option>
							<option <?php if($consent_info[$t]->contract_type == 'DU'){ echo 'selected'; } ?> value='DU'>DU</option>
							<option <?php if($consent_info[$t]->contract_type == 'EQ'){ echo 'selected'; } ?> value='EQ'>EQ</option>
							<option <?php if($consent_info[$t]->contract_type == 'HL'){ echo 'selected'; } ?> value='HL'>HL</option>
							<option <?php if($consent_info[$t]->contract_type == 'MU'){ echo 'selected'; } ?> value='MU'>MU</option>
							<option <?php if($consent_info[$t]->contract_type == 'TK'){ echo 'selected'; } ?> value='TK'>TK</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="12_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="12_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="12_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->contract_type; ?></div> 
				</td>
				<?php 	} ?>
			
				<?php 	
							if($user_permission_type[13]->display_type == 1)
							{ 
				?>
				<td id="13_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:1%">
				<?php	
								if($user_permission_type[13]->read_type == 2)
								{
					?>
					<div id="13_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="type_of_build" id="type_of_build_<?php echo $consent_info[$t]->job_no ?>">
							<option value=""></option>
							<option <?php if($consent_info[$t]->type_of_build == 'SH'){ echo 'selected'; } ?> value="SH">SH</option>
							<option <?php if($consent_info[$t]->type_of_build == 'MU'){ echo 'selected'; } ?> value="MU">MU</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="13_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="13_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="13_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->type_of_build; ?></div> 
					
				</td>
				<?php 	} ?>

				<?php 	
							if($user_permission_type[14]->display_type == 1)
							{ 
				?>
				<td id="14_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; <?php if($consent_info[$t]->variation_pending=='Yes'){ ?>background-color:red; color:white;<?php } ?> ">
				<?php		
								if($user_permission_type[14]->read_type == 2)
								{
					?>
					<div id="14_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select id="variation_pending_<?php echo $consent_info[$t]->job_no ?>" name='variation_pending'>
							<option value=""></option>
							<option <?php if($consent_info[$t]->variation_pending == 'Yes'){ echo 'selected'; } ?> value='Yes'>Yes</option>
						</select>
						&nbsp;

						
					</div>
					<div id="14_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="14_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="14_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->variation_pending; ?></div> 
				</td>
				<?php 	} ?>

				<?php 	
							if($user_permission_type[15]->display_type == 1)
							{ 
				?>
				<td id="15_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">
				<?php	
								if($user_permission_type[15]->read_type == 2)
								{
					?>
					<div id="15_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input value="<?php if($consent_info[$t]->foundation_type != 'Standard Engineered' 
												|| $consent_info[$t]->foundation_type != 'Standard'
												|| $consent_info[$t]->foundation_type != 'Rib & Shingle'
												|| $consent_info[$t]->foundation_type != 'Jackable Rib & Shingle'
												|| $consent_info[$t]->foundation_type != 'Superslab & Shingle'
												|| $consent_info[$t]->foundation_type != 'TC3 type 2B'){ echo $consent_info[$t]->foundation_type; } ?>" type="text" name="foundation_type1" id="foundation_type1_<?php echo $consent_info[$t]->job_no ?>" />
						<select name="foundation_type2" id="foundation_type2_<?php echo $consent_info[$t]->job_no ?>">
							<option value="">Select Foundation Type</option>
							<option <?php if($consent_info[$t]->foundation_type == 'Standard Engineered'){ echo 'selected'; } ?> value="Standard Engineered">Standard Engineered</option>
							<option <?php if($consent_info[$t]->foundation_type == 'Standard'){ echo 'selected'; } ?> value="Standard">Standard</option>
							<option <?php if($consent_info[$t]->foundation_type == 'Rib & Shingle'){ echo 'selected'; } ?> value="Rib & Shingle">Rib & Shingle</option>
							<option <?php if($consent_info[$t]->foundation_type == 'Jackable Rib & Shingle'){ echo 'selected'; } ?> value="Jackable Rib & Shingle">Jackable Rib & Shingle</option>
							<option <?php if($consent_info[$t]->foundation_type == 'Superslab & Shingle'){ echo 'selected'; } ?> value="Superslab & Shingle">Superslab & Shingle</option>
							<option <?php if($consent_info[$t]->foundation_type == 'TC3 type 2B'){ echo 'selected'; } ?> value="TC3 type 2B">TC3 type 2B</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="15_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="15_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="15_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->foundation_type; ?></div> 
				</td>
				<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="62_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="62_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="resource_consent_<?php echo $consent_info[$t]->job_no ?>" name="resource_consent" value="<?php echo $consent_info[$t]->resource_consent; ?>" />		
	</div>
	<div id="62_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="62_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="62_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->resource_consent; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="63_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="63_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="rc_number_<?php echo $consent_info[$t]->job_no ?>" name="rc_number" value="<?php echo $consent_info[$t]->rc_number; ?>" />		
	</div>
	<div id="63_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="63_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="63_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->rc_number; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="64_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="64_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input class="live_datepicker" type='text' id="expected_date_to_lodge_bc_<?php echo $consent_info[$t]->job_no ?>" name="expected_date_to_lodge_bc" value="<?php if($consent_info[$t]->expected_date_to_lodge_bc != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->expected_date_to_lodge_bc); } ?>" />		
	</div>
	<div id="64_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="64_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="64_dis_<?php echo $consent_info[$t]->job_no ?>"><?php if($consent_info[$t]->expected_date_to_lodge_bc != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->expected_date_to_lodge_bc); } ?></div> 		
</td>
<?php 	} ?>

				<?php 	
							if($user_permission_type[16]->display_type == 1)
							{ 
				?>
				<td id="16_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; <?php if($consent_info[$t]->date_logged != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
				<?php	
								if($user_permission_type[16]->read_type == 2)
								{
					?>
					<div id="16_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="date_logged_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="date_logged" value="<?php if($consent_info[$t]->date_logged != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->date_logged); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','date_logged','date_logged_<?php echo $consent_info[$t]->job_no ?>',16)" src="<?php echo base_url(); ?>images/icon/edit_pass.png"   > 
						&nbsp;
						
					</div>
					<div id="16_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="16_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="16_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	if($consent_info[$t]->date_logged != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->date_logged); } ?></div> 
				</td>
				<?php 	} ?>
	
				<?php 	
							if($user_permission_type[17]->display_type == 1)
							{ 
				?>
				<td id="17_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%;<?php if($consent_info[$t]->date_issued != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
				<?php	
								if($user_permission_type[17]->read_type == 2)
								{
					?>
					<div id="17_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="date_issued_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="date_issued" value="<?php if($consent_info[$t]->date_issued != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->date_issued); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','date_issued','date_issued_<?php echo $consent_info[$t]->job_no ?>',17)" src="<?php echo base_url(); ?>images/icon/edit_pass.png" > 
						&nbsp;
						
					</div>
					<div id="17_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="17_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="17_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	if($consent_info[$t]->date_issued != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->date_issued); } ?></div> 			
				</td>
				<?php 	} ?>

				
				<?php 	
					if($user_permission_type[17]->display_type == 1)
					{ 
				?>
				<td id="18_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">
				<?php	
								if($user_permission_type[17]->read_type == 2)
								{
					?>
					<div id="18_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="actual_date_issued_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="actual_date_issued" value="<?php if($consent_info[$t]->actual_date_issued != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->actual_date_issued); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','actual_date_issued','actual_date_issued_<?php echo $consent_info[$t]->job_no ?>',18)" src="<?php echo base_url(); ?>images/icon/edit_pass.png" > 
						&nbsp;
						
					</div>
					<div id="18_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="18_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="18_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	if($consent_info[$t]->actual_date_issued != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->actual_date_issued); } ?></div> 			
				</td>
				<?php 	} ?>
				
				
				
				<?php 
					if($user_permission_type[18]->display_type == 1)
					{ ?>
					<td style="width:1%">
						<?php
						if($consent_info[$t]->date_logged != '0000-00-00' && $consent_info[$t]->date_issued != '0000-00-00'){ echo $days_in_council = $ci->consent_model->get_working_days($consent_info[$t]->date_logged, $consent_info[$t]->date_issued) - 1; }else if($consent_info[$t]->date_logged != '0000-00-00' && $consent_info[$t]->date_issued == '0000-00-00'){ echo $days_in_council = $ci->consent_model->get_working_days($consent_info[$t]->date_logged, date('Y-m-d')) - 1; }else{ echo '0'; }

						?>					
					</td>
				<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="65_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="65_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="water_connection_<?php echo $consent_info[$t]->job_no ?>" name="water_connection" value="<?php echo $consent_info[$t]->water_connection; ?>" />		
	</div>
	<div id="65_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="65_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="65_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->water_connection; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="66_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="66_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="vehicle_crossing_<?php echo $consent_info[$t]->job_no ?>" name="vehicle_crossing" value="<?php echo $consent_info[$t]->vehicle_crossing; ?>" />		
	</div>
	<div id="66_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="66_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="66_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->vehicle_crossing; ?></div> 		
</td>
<?php 	} ?>
					
				<?php 	
							if($user_permission_type[19]->display_type == 1)
							{ 
				?>
				<td id="19_col_<?php echo $consent_info[$t]->job_no; ?>" id="19_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; background-color:
				<?php 	if($consent_info[$t]->order_site_levels == '') {echo "#FFFFFF";} 
						if($consent_info[$t]->order_site_levels == 'Received') {echo "#90ee90";}
						if($consent_info[$t]->order_site_levels == 'Sent') {echo "#70B5FF";}
				?>">

				<?php	
								if($user_permission_type[19]->read_type == 2)
								{
					?>
					<div id="19_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="order_site_levels" id="order_site_levels_<?php echo $consent_info[$t]->job_no ?>">
							<option value=""></option>
							<option <?php if($consent_info[$t]->order_site_levels == 'N/A'){ echo 'selected'; } ?> value="N/A">N/A</option>
							<option <?php if($consent_info[$t]->order_site_levels == 'Received'){ echo 'selected'; } ?> value="Received">Received</option>
							<option <?php if($consent_info[$t]->order_site_levels == 'Sent'){ echo 'selected'; } ?> value="Sent">Sent</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="19_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="19_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="19_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->order_site_levels; ?></div> 
					
				</td>
				<?php 	} ?>



				<?php 	
							if($user_permission_type[20]->display_type == 1)
							{ 
				?>
				<td id="20_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; background-color:
				<?php 	if($consent_info[$t]->order_soil_report == 'N/A') {echo "#FFFFFF";} 
						if($consent_info[$t]->order_soil_report == 'Received') {echo "#90ee90";}
						if($consent_info[$t]->order_soil_report == 'Sent') {echo "#70B5FF";}
				?>">

				<?php	
								if($user_permission_type[20]->read_type == 2)
								{
					?>
					<div id="20_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="order_soil_report" id="order_soil_report_<?php echo $consent_info[$t]->job_no ?>">
							<option value=''></option>
							<option <?php if($consent_info[$t]->order_soil_report == 'N/A'){ echo 'selected'; } ?> value="N/A">N/A</option>
							<option <?php if($consent_info[$t]->order_soil_report == 'Received'){ echo 'selected'; } ?> value="Received">Received</option>
							<option <?php if($consent_info[$t]->order_soil_report == 'Sent'){ echo 'selected'; } ?> value="Sent">Sent</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="20_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="20_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="20_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->order_soil_report; ?></div> 
					
				</td>
				<?php 	} ?>
			
			
				<?php 	
							if($user_permission_type[21]->display_type == 1)
							{ 
				?>
				<td id="21_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; color:white; background-color:
				<?php 	
						if($consent_info[$t]->septic_tank_approval == 'REQ') {echo "red";}
						if($consent_info[$t]->septic_tank_approval == 'N/A') {echo "#90ee90";} 
						if($consent_info[$t]->septic_tank_approval == 'SENT') {echo "blue";}
						if($consent_info[$t]->septic_tank_approval == 'RECEIVED') {echo "#90ee90";}
				?>">
					
				<?php	if($user_permission_type[21]->read_type == 2)
						{
					?>
					<div id="21_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="septic_tank_approval" id="septic_tank_approval_<?php echo $consent_info[$t]->job_no ?>">
							<option value=''></option>
							<option <?php if($consent_info[$t]->septic_tank_approval == 'REQ'){ echo 'selected'; } ?> value='REQ'>REQ</option>
							<option <?php if($consent_info[$t]->septic_tank_approval == 'N/A'){ echo 'selected'; } ?> value='N/A'>N/A</option>
							<option <?php if($consent_info[$t]->septic_tank_approval == 'SENT'){ echo 'selected'; } ?> value='SENT'>SENT</option>
							<option <?php if($consent_info[$t]->septic_tank_approval == 'RECEIVED'){ echo 'selected'; } ?> value='RECEIVED'>RECEIVED</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="21_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="21_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="21_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->septic_tank_approval; ?></div> 
				
				</td>
				<?php 	} ?>

				<?php 	
							if($user_permission_type[22]->display_type == 1)
							{ 
				?>
				<td id="22_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; color:white; background-color:
				<?php 
						if($consent_info[$t]->dev_approval == 'REQ') {echo "red";} 
						if($consent_info[$t]->dev_approval == 'N/A') {echo "#90ee90";} 
						if($consent_info[$t]->dev_approval == 'PRE SENT') {echo "blue";}
						if($consent_info[$t]->dev_approval == 'PRE REC') {echo "yellow; color:black";}
						if($consent_info[$t]->dev_approval == 'FULL SENT') {echo "blue";}
						if($consent_info[$t]->dev_approval == 'FULL REC') {echo "#90ee90";}
				?>">
				
					<?php	
								if($user_permission_type[22]->read_type == 2)
								{
					?>
					<div id="22_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="dev_approval" id="dev_approval_<?php echo $consent_info[$t]->job_no ?>">
							<option value=''></option>
							<option <?php if($consent_info[$t]->dev_approval == 'REQ'){ echo 'selected'; } ?> value='REQ'>REQ</option>
							<option <?php if($consent_info[$t]->dev_approval == 'N/A'){ echo 'selected'; } ?> value='N/A'>N/A</option>
							<option <?php if($consent_info[$t]->dev_approval == 'PRE SENT'){ echo 'selected'; } ?> value='PRE SENT'>PRE SENT</option>
							<option <?php if($consent_info[$t]->dev_approval == 'PRE REC'){ echo 'selected'; } ?> value='PRE REC'>PRE REC</option>
							<option <?php if($consent_info[$t]->dev_approval == 'FULL SENT'){ echo 'selected'; } ?> value='FULL SENT'>FULL SENT</option>
							<option <?php if($consent_info[$t]->dev_approval == 'FULL REC'){ echo 'selected'; } ?> value='FULL REC'>FULL REC</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="22_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="22_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="22_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->dev_approval; ?></div> 
					
				</td>
				<?php 	} ?>
				
				
				<?php 	
							if($user_permission_type[22]->display_type == 1)
							{ 
				?>
				<td id="32_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; color:white; background-color:
				<?php 	
						if($consent_info[$t]->landscape == 'REQ') {echo "red";}
						if($consent_info[$t]->landscape == 'N/A') {echo "#90ee90";} 
						if($consent_info[$t]->landscape == 'SENT') {echo "blue";}
						if($consent_info[$t]->landscape == 'RECEIVED') {echo "#90ee90";}
				?>">
				
					<?php	
								if($user_permission_type[22]->read_type == 2)
								{
					?>
					<div id="32_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="landscape" id="landscape_<?php echo $consent_info[$t]->job_no ?>">
							<option value=''></option>
							<option <?php if($consent_info[$t]->landscape == 'REQ'){ echo 'selected'; } ?> value='REQ'>REQ</option>
							<option <?php if($consent_info[$t]->landscape == 'N/A'){ echo 'selected'; } ?> value='N/A'>N/A</option>
							<option <?php if($consent_info[$t]->landscape == 'SENT'){ echo 'selected'; } ?> value='SENT'>SENT</option>
							<option <?php if($consent_info[$t]->landscape == 'RECEIVED'){ echo 'selected'; } ?> value='RECEIVED'>RECEIVED</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="32_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="32_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="32_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->landscape; ?></div> 
					
				</td>
				<?php 	} ?>
				
				
				<?php 	
				if($user_permission_type[22]->display_type == 1)
				{ 
				?>
				<td id="33_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; color:white; background-color:
				<?php 	if($consent_info[$t]->mss == 'REQ') {echo "red";} 
						if($consent_info[$t]->mss == 'DONE') {echo "#90ee90";}
				?>">
				
					<?php	
					if($user_permission_type[22]->read_type == 2)
					{
					?>
					<div id="33_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="mss" id="mss_<?php echo $consent_info[$t]->job_no ?>">
							<option value=''></option>
							<option <?php if($consent_info[$t]->mss == 'REQ'){ echo 'selected'; } ?> value='REQ'>REQ</option>
							<option <?php if($consent_info[$t]->mss == 'DONE'){ echo 'selected'; } ?> value='DONE'>DONE</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="33_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="33_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="33_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->mss; ?></div> 
					
				</td>
				<?php 	} ?>
				
				
				
				
				

				<?php 	
							if($user_permission_type[23]->display_type == 1)
							{ 
				?>
				<td id="23_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">
				<?php	
								if($user_permission_type[23]->read_type == 2)
								{
					?>
					<div id="23_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name='project_manager' id="project_manager_<?php echo $consent_info[$t]->job_no ?>">
							<option value='0'>Select Project Manager</option>
							<?php  foreach ($project_manager_list as $project_manager){?> <option <?php if($consent_info[$t]->project_manager == $project_manager->uid){ echo 'selected'; } ?> value='<?php echo $project_manager->uid ?>' <?php if($project_manager->uid == $consent_info[$t]->project_manager){ ?> selected="selected" <?php } ?> > <?php echo $project_manager->fullname ?></option> <?php }?>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="23_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="23_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="23_dis_<?php echo $consent_info[$t]->job_no ?>"><?php echo $consent_info[$t]->project_manager; ?></div> 
					
				</td>
				<?php 	} ?>
				

				<?php 	
							if($user_permission_type[25]->display_type == 1)
							{ 
				?>
				<td id="25_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; <?php if($consent_info[$t]->unconditional_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
				<?php	
								if($user_permission_type[25]->read_type == 2)
								{

								

					?>
					<div id="25_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="unconditional_date_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="unconditional_date" value="<?php if($consent_info[$t]->unconditional_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->unconditional_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','unconditional_date','unconditional_date_<?php echo $consent_info[$t]->job_no ?>',25)" > 
						&nbsp;
						
					</div>
					<div id="25_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="25_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="25_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	if($consent_info[$t]->unconditional_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->unconditional_date); } ?></div> 
					
				</td>
				<?php 	} ?>

				<?php 	
					if($user_permission_type[26]->display_type == 1)
					{ 
				?>
				<td id="26_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; <?php if($consent_info[$t]->handover_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
				<?php
						if($user_permission_type[26]->read_type == 2)
						{
					?>
					<div id="26_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="handover_date_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="handover_date" value="<?php if($consent_info[$t]->handover_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->handover_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','handover_date','handover_date_<?php echo $consent_info[$t]->job_no ?>',26)"  > 
						&nbsp;
						
					</div>
					<div id="26_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="26_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="26_dis_<?php echo $consent_info[$t]->job_no ?>"><?php if($consent_info[$t]->handover_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->handover_date); } ?></div> 
					
				</td>
				<?php 	} ?>

				<?php 	
					if($user_permission_type[27]->display_type == 1)
					{	?>

				<td id="27_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">

					<?php	if($user_permission_type[27]->read_type == 2)
						{
					?>

			
					<div id="27_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name='builder' id="builder_<?php echo $consent_info[$t]->job_no ?>">
							<option value='0'>Select Builder</option>
							<?php  foreach ($builder_list as $builder){ ?> <option <?php if($consent_info[$t]->builder == $builder->uid){ echo 'selected'; } ?> value='<?php echo $builder->uid ?>' <?php if($builder->uid == $consent_info[$t]->builder){ ?> selected="selected" <?php }  ?> > <?php echo $builder->fullname ?></option> <?php } ?>
						</select> 
						&nbsp;
						
					</div>
					<div id="27_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="27_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php }	?>
					<div id="27_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->builder; ?></div> 
						
				</td>
				<?php } ?>
		
		
				<?php 	
				if($user_permission_type[28]->display_type == 1)
				{ ?>

				<td id="28_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">
					<?php
					if($user_permission_type[28]->read_type == 2)
					{
					?>

					
					<div id="28_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="consent_out_but_no_builder" id="consent_out_but_no_builder_<?php echo $consent_info[$t]->job_no ?>">
							<option value=""></option>
							<option <?php if($consent_info[$t]->consent_out_but_no_builder == 'Allocated'){ echo 'selected'; } ?> value="Allocated">Allocated</option>
							<option <?php if($consent_info[$t]->consent_out_but_no_builder == 'Due'){ echo 'selected'; } ?> value="Due">Due</option>
							<option <?php if($consent_info[$t]->consent_out_but_no_builder == 'Need Builder'){ echo 'selected'; } ?> value="Need Builder">Need Builder</option>
						</select>
						&nbsp;&nbsp;
						
					</div>
					<div id="28_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="28_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php	}	?>
					<div id="28_dis_<?php echo $consent_info[$t]->job_no ?>"><?php if($consent_info[$t]->builder == ''){echo "Need Builder";}else{echo $consent_info[$t]->consent_out_but_no_builder;} ?></div> 				
				</td>
				<?php } ?>
				
				
				<?php 	
					if($user_permission_type[26]->display_type == 1)
					{ 
				?>
				<td id="34_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%">
				<?php
						if($user_permission_type[26]->read_type == 2)
						{
					?>
					<div id="34_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<select name="title_date_issued" id="title_date_issued_<?php echo $consent_info[$t]->job_no ?>" onchange="titleDateIssued(<?php echo "'".$consent_info[$t]->job_no."'"; ?>)">
							<option value=""></option>
							<option <?php if($consent_info[$t]->title_date_issued == 'Issued'){ echo 'selected'; } ?> value="Issued">Issued</option>
							
						</select>
						&nbsp;&nbsp;
						<input <?php if($consent_info[$t]->title_date_issued == 'Issued'){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?> id="title_date_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="title_date" value="<?php if($consent_info[$t]->title_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->title_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','title_date','title_date_<?php echo $consent_info[$t]->job_no ?>',34)"  > 
						&nbsp;
						
					</div>
					<div id="34_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="34_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="34_dis_<?php echo $consent_info[$t]->job_no ?>"><?php if($consent_info[$t]->title_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->title_date); } ?></div> 
				</td>
				<?php 	} ?>
				
				
				<?php 	
					if($user_permission_type[26]->display_type == 1)
					{ 
				?>
				<td id="35_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; <?php if($consent_info[$t]->settlement_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
				<?php
						if($user_permission_type[26]->read_type == 2)
						{
					?>
					<div id="35_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="settlement_date_<?php echo $consent_info[$t]->job_no ?>" type="text" class="live_datepicker" name="settlement_date" value="<?php if($consent_info[$t]->settlement_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->settlement_date); } ?>"  onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','settlement_date','settlement_date_<?php echo $consent_info[$t]->job_no ?>',35)" > 
						&nbsp;
						
					</div>
					<div id="35_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="35_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="35_dis_<?php echo $consent_info[$t]->job_no ?>"><?php if($consent_info[$t]->settlement_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->settlement_date); } ?></div> 
					
				</td>
				<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="67_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="67_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="for_sale_sign_<?php echo $consent_info[$t]->job_no ?>" name="for_sale_sign" value="<?php echo $consent_info[$t]->for_sale_sign; ?>" />		
	</div>
	<div id="67_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="67_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="67_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->for_sale_sign; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="68_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="68_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='text' id="code_of_compliance_<?php echo $consent_info[$t]->job_no ?>" name="code_of_compliance" value="<?php echo $consent_info[$t]->code_of_compliance; ?>" />		
	</div>
	<div id="68_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="68_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="68_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->code_of_compliance; ?></div> 		
</td>
<?php 	} ?>

<?php if($user_permission_type[31]->display_type == 1){ ?>
<td id="69_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%; height:30px;">	
	<?php 	if($user_permission_type[31]->read_type == 2){?>
	<div id="69_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
		<input type='file' id="photos_taken_<?php echo $consent_info[$t]->job_no ?>" name="photos_taken" />		
	</div>
	<div id="69_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
	<div id="69_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
	<?php }	?>
	<div id="69_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->photos_taken; ?></div> 		
</td>
<?php 	} ?>
				
				<?php 	
							if($user_permission_type[17]->display_type == 1)
							{ 
				?>
				<td id="36_col_<?php echo $consent_info[$t]->job_no; ?>" style="width:2%;color:blue;">
				<?php	
								if($user_permission_type[17]->read_type == 2)
								{
					?>
					<div id="36_box_<?php echo $consent_info[$t]->job_no ?>" class="dnone">
						<input id="notes_<?php echo $consent_info[$t]->job_no ?>" type="text" name="notes" value="<?php echo $consent_info[$t]->notes;?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_info[$t]->job_no; ?>','notes','notes_<?php echo $consent_info[$t]->job_no ?>',36)" > 
						&nbsp;
						
					</div>
					<div id="36_tab_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
					<div id="36_timg_<?php echo $consent_info[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
					<?php		}	?>
					<div id="36_dis_<?php echo $consent_info[$t]->job_no ?>"><?php 	echo $consent_info[$t]->notes; ?></div> 			
				</td>
				<?php 	} ?>
				
				

			</tr>

				<?php 

					if($consent_child)
					{
						for($c=0; $c < count($consent_child); $c++)
						{
						  ?>
						<tr class="child_<?php echo $consent_child[$t]->id; ?>" style="display:none;">
							<td class="child_td"><?php echo $consent_child[$c]->job_no;  ?></td>

							<?php 	
							if($user_permission_type[0]->display_type == 1)
							{ ?>
							<td id="0_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:3%; height:30px; background-color:#<?php if($consent_child[$t]->unconditional_date != '0000-00-00'){echo "white;color:red;";}else if($consent_child[$t]->consent_color=='72D660'){ echo '90ee90'; }else{ echo $consent_child[$t]->consent_color;}  ?>">
								
							<?php 	if($user_permission_type[0]->read_type == 2)
									{
								?>
								<div id="0_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input type='text' id="consent_id_<?php echo $consent_child[$t]->job_no ?>" name="consent_name" value="<?php echo $consent_child[$t]->consent_name; ?> " onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','consent_name','consent_id_<?php echo $consent_child[$t]->job_no ?>',0)" />
									&nbsp;
									
									&nbsp;
									<select id="consent_color_<?php echo $consent_child[$t]->job_no ?>" name="consent_color">
										<option value=""></option>
										<option <?php if($consent_child[$t]->consent_color=='ffffff'){ echo 'selected'; } ?> value="ffffff">White</option>
										<option <?php if($consent_child[$t]->consent_color=='90ee90'){ echo 'selected'; }else if($consent_child[$t]->consent_color=='72D660'){ echo 'selected'; } ?> value="90ee90">Green</option>
										<option <?php if($consent_child[$t]->consent_color=='FF3D3D'){ echo 'selected'; } ?> value="FF3D3D">Red</option>
										<option <?php if($consent_child[$t]->consent_color=='FFAC40'){ echo 'selected'; } ?> value="FFAC40">Orange</option>
									</select>
									
								</div>
								<div id="0_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="0_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
								<?php }	?>
								<div id="0_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->consent_name; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="45_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="45_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="pre_construction_sign_<?php echo $consent_child[$t]->job_no ?>" name="pre_construction_sign" value="<?php echo $consent_child[$t]->pre_construction_sign; ?>" />		
							</div>
							<div id="45_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="45_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="45_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->pre_construction_sign; ?></div> 		
							</td>
							<?php 	} ?>

							<?php 	
							if($user_permission_type[1]->display_type == 1)
							{ 
								$design_bg = '';
								if($consent_child[$t]->design == 'REQ Brief'){$design_bg = 'yellow';}
								if($consent_child[$t]->design == 'Brief'){$design_bg = 'blue';}
								if($consent_child[$t]->design == 'Hold'){$design_bg = 'red';}
								if($consent_child[$t]->design == 'Sign'){$design_bg = 'orange';}
								if($consent_child[$t]->design == 'Consent'){$design_bg = '#90ee90';}
							?>
							<td id="1_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:1%; background-color:<?php echo $design_bg;?>">
								
							<?php
								if($user_permission_type[1]->read_type == 2)
								{
								?>
								<div id="1_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="design" id="design_id_<?php echo $consent_child[$t]->job_no ?>">
										<option value=""></option>
										<option <?php if($consent_child[$t]->design == 'REQ Brief'){ echo 'selected'; } ?> value='REQ Brief'>REQ Brief</option>
										<option <?php if($consent_child[$t]->design == 'Brief'){ echo 'selected'; } ?> value='Brief'>Brief</option>
										<option <?php if($consent_child[$t]->design == 'Hold'){ echo 'selected'; } ?> value='Hold'>Hold</option>
										<option <?php if($consent_child[$t]->design == 'Sign'){ echo 'selected'; } ?> value='Sign'>Sign</option>
										<option <?php if($consent_child[$t]->design == 'Consent'){ echo 'selected'; } ?> value='Consent'>Consent</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="1_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="1_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
								<?php }	?>
								<div id="1_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->design; ?></div> 
							</td>
							<?php } ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="46_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="46_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="designer_<?php echo $consent_child[$t]->job_no ?>" name="designer" value="<?php echo $consent_child[$t]->designer; ?>" />		
							</div>
							<div id="46_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="46_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="46_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->designer; ?></div> 		
							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[2]->display_type == 1)
										{ 
							?>
							<td id="2_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">

							<?php	
											if($user_permission_type[2]->read_type == 2)
											{
								?>
								<div id="2_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="approval_date_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="approval_date" value="<?php if($consent_child[$t]->approval_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->approval_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','approval_date','approval_date_<?php echo $consent_child[$t]->job_no ?>',2);" > 
									&nbsp;
									
								</div>
								<div id="2_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="2_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
								<?php		}	?>
								<div id="2_dis_<?php echo $consent_child[$t]->job_no ?>"><?php if($consent_child[$t]->approval_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->approval_date); } ?></div> 
								

							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="47_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="47_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="ok_to_release_to_marketing_<?php echo $consent_child[$t]->job_no ?>" name="ok_to_release_to_marketing" value="<?php echo $consent_child[$t]->ok_to_release_to_marketing; ?>" />		
							</div>
							<div id="47_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="47_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="47_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->ok_to_release_to_marketing; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="48_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="48_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="pricing_requested_<?php echo $consent_child[$t]->job_no ?>" name="pricing_requested" value="<?php echo $consent_child[$t]->pricing_requested; ?>" />		
							</div>
							<div id="48_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="48_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="48_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->pricing_requested; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="49_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="49_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="pricing_for_approval_<?php echo $consent_child[$t]->job_no ?>" name="pricing_for_approval" value="<?php echo $consent_child[$t]->pricing_for_approval; ?>" />		
							</div>
							<div id="49_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="49_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="49_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->pricing_for_approval; ?></div> 		
							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[2]->display_type == 1)
										{ 
							?>
							<td id="37_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; <?php if($consent_child[$t]->price_approved_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>" >

							<?php
											if($user_permission_type[2]->read_type == 2)
											{
								?>
								<div id="37_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="price_approved_date_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="price_approved_date" value="<?php if($consent_child[$t]->price_approved_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->price_approved_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','price_approved_date','price_approved_date_<?php echo $consent_child[$t]->job_no ?>',2);" > 
									&nbsp;
									
								</div>
								<div id="37_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="37_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
								<?php		}	?>
								<div id="37_dis_<?php echo $consent_child[$t]->job_no ?>"><?php if($consent_child[$t]->price_approved_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->price_approved_date); } ?></div> 
								

							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="50_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="50_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="approved_for_sale_price_<?php echo $consent_child[$t]->job_no ?>" name="approved_for_sale_price" value="<?php echo $consent_child[$t]->approved_for_sale_price; ?>" />		
							</div>
							<div id="50_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="50_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="50_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->approved_for_sale_price; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="51_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="51_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="kitchen_disign_type_<?php echo $consent_child[$t]->job_no ?>" name="kitchen_disign_type" value="<?php echo $consent_child[$t]->kitchen_disign_type; ?>" />		
							</div>
							<div id="51_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="51_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="51_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->kitchen_disign_type; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="52_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="52_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="kitchen_disign_requested_<?php echo $consent_child[$t]->job_no ?>" name="kitchen_disign_requested" value="<?php echo $consent_child[$t]->kitchen_disign_requested; ?>" />		
							</div>
							<div id="52_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="52_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="52_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->kitchen_disign_requested; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="53_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="53_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="colours_requested_and_loaded_on_gc_<?php echo $consent_child[$t]->job_no ?>" name="colours_requested_and_loaded_on_gc" value="<?php echo $consent_child[$t]->colours_requested_and_loaded_on_gc; ?>" />		
							</div>
							<div id="53_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="53_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="53_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->colours_requested_and_loaded_on_gc; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="54_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="54_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="kitchen_design_loaded_on_gc_<?php echo $consent_child[$t]->job_no ?>" name="kitchen_design_loaded_on_gc" value="<?php echo $consent_child[$t]->kitchen_design_loaded_on_gc; ?>" />		
							</div>
							<div id="54_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="54_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="54_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->kitchen_design_loaded_on_gc; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="55_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="55_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="developer_colour_sheet_created_<?php echo $consent_child[$t]->job_no ?>" name="developer_colour_sheet_created" value="<?php echo $consent_child[$t]->developer_colour_sheet_created; ?>" />		
							</div>
							<div id="55_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="55_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="55_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->developer_colour_sheet_created; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="56_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="56_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="spec_loaded_on_gc_<?php echo $consent_child[$t]->job_no ?>" name="spec_loaded_on_gc" value="<?php echo $consent_child[$t]->spec_loaded_on_gc; ?>" />		
							</div>
							<div id="56_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="56_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="56_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->spec_loaded_on_gc; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="57_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="57_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="loaded_on_intranet_<?php echo $consent_child[$t]->job_no ?>" name="loaded_on_intranet" value="<?php echo $consent_child[$t]->loaded_on_intranet; ?>" />		
							</div>
							<div id="57_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="57_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="57_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->loaded_on_intranet; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="58_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="58_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="website_<?php echo $consent_child[$t]->job_no ?>" name="website" value="<?php echo $consent_child[$t]->website; ?>" />		
							</div>
							<div id="58_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="58_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="58_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->website; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="59_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="59_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="render_requested_<?php echo $consent_child[$t]->job_no ?>" name="render_requested" value="<?php echo $consent_child[$t]->render_requested; ?>" />		
							</div>
							<div id="59_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="59_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="59_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->render_requested; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="60_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="60_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="render_received_<?php echo $consent_child[$t]->job_no ?>" name="render_received" value="<?php echo $consent_child[$t]->render_received; ?>" />		
							</div>
							<div id="60_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="60_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="60_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->render_received; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="61_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="61_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="brochure_<?php echo $consent_child[$t]->job_no ?>" name="brochure" value="<?php echo $consent_child[$t]->brochure; ?>" />		
							</div>
							<div id="61_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="61_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="61_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->brochure; ?></div> 		
							</td>
							<?php 	} ?>			
							<?php 	
								if($user_permission_type[3]->display_type == 1)
										{ 
							?>
							<td id="3_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">

							<?php	
									if($user_permission_type[3]->read_type == 2)
											{
								?>
								<div id="3_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="pim_logged_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="pim_logged" value="<?php if($consent_child[$t]->pim_logged != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->pim_logged); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','pim_logged','pim_logged_<?php echo $consent_child[$t]->job_no ?>',3)" src="<?php echo base_url(); ?>images/icon/edit_pass.png" > 
									&nbsp;
									
								</div>
								<div id="3_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="3_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
								<?php		}	?>
								<div id="3_dis_<?php echo $consent_child[$t]->job_no ?>"><?php if($consent_child[$t]->pim_logged != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->pim_logged); } ?></div> 
							</td>

							<?php 	} ?>




							<?php 	
										if($user_permission_type[6]->display_type == 1)
										{ 
							?>
							<td id="6_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; <?php if($consent_child[$t]->drafting_issue_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
							<?php				if($user_permission_type[6]->read_type == 2)
											{
								?>
								<div id="6_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="drafting_issue_date_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="drafting_issue_date" value="<?php if($consent_child[$t]->drafting_issue_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->drafting_issue_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','drafting_issue_date','drafting_issue_date_<?php echo $consent_child[$t]->job_no ?>',6)" src="<?php echo base_url(); ?>images/icon/edit_pass.png"  > 
									&nbsp;
									
								</div>
								<div id="6_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="6_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
								<?php		}	?>
								<div id="6_dis_<?php echo $consent_child[$t]->job_no ?>"><?php if($consent_child[$t]->drafting_issue_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->drafting_issue_date); } ?></div> 
							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[7]->display_type == 1)
										{ 
							?>
							<td id="7_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">
							<?php
								
											if($user_permission_type[7]->read_type == 2)
											{
								?>
								<div id="7_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name='consent_by' id="consent_by_<?php echo $consent_child[$t]->job_no ?>">
										<option value='0'>Select User</option>
											<?php  foreach ($consent_by_list as $consent_by){ ?> <option value='<?php echo $consent_by->uid ?>' <?php if( $consent_by->uid == $consent_child[$t]->consent_by){ ?> selected="selected" <?php } ?> > <?php echo $consent_by->fullname ?></option> <?php } ?>
									</select> 
									&nbsp;
									
								</div>
								<div id="7_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="7_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="7_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->consent_by; ?></div> 
							</td>
							<?php 	} ?>

							<?php 	
								if($user_permission_type[8]->display_type == 1)
										{ $txt = '';
							?>
							<td id="8_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; background-color:
							<?php
								$fourteen_days_before = date('Y-m-d', strtotime('-14 days'));
								$thirty_days_before = date('Y-m-d', strtotime('-30 days'));
								$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
								if( $consent_child[$t]->unconditional_date!='0000-00-00' && $consent_child[$t]->unconditional_date < $fourteen_days_before && $consent_child[$t]->drafting_issue_date == '0000-00-00'){echo "red; color:white;"; $txt = 'Issue For Consent'; }
								if( $consent_child[$t]->drafting_issue_date < $thirty_days_before && $consent_child[$t]->drafting_issue_date != '0000-00-00'){echo "red; color:white;"; $txt = 'Drawings Late'; }
								if( $consent_child[$t]->date_logged != '0000-00-00' && $consent_child[$t]->date_logged  < $twenty_days_before ){echo "red; color:white;"; $txt = 'Consent Late';}
							?>">	
							<?php	if($user_permission_type[8]->read_type == 2)
											{
								?>
								<div id="8_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="action_required" id="action_required_<?php echo $consent_child[$t]->job_no ?>">
										<option value="">Select Action</option>
										<option <?php if($consent_child[$t]->action_required == 'Urgent'){ echo 'selected'; } ?> value="Urgent">Urgent</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="8_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="8_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="8_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $txt; 	//if($consent_child[$t]->action_required != 0 ){ echo $consent_child[$t]->action_required; } ?></div> 

							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[9]->display_type == 1)
										{ 
							?>
							<td id="9_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">
							<?php	
											if($user_permission_type[9]->read_type == 2)
											{
								?>
								<div id="9_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="council" id="council_<?php echo $consent_child[$t]->job_no ?>">
										<option value=""></option>
										<option <?php if($consent_child[$t]->council == 'Ashburton'){ echo 'selected'; } ?> value='Ashburton'>Ashburton</option>
										<option <?php if($consent_child[$t]->council == 'Auckland'){ echo 'selected'; } ?> value='Auckland'>Auckland</option>
										<option <?php if($consent_child[$t]->council == 'Chch'){ echo 'selected'; } ?> value='Chch'>Chch</option>
										<option <?php if($consent_child[$t]->council == 'Hurunui'){ echo 'selected'; } ?> value='Hurunui'>Hurunui</option>
										<option <?php if($consent_child[$t]->council == 'Selwyn'){ echo 'selected'; } ?> value='Selwyn'>Selwyn</option>
										<option <?php if($consent_child[$t]->council == 'Waikato'){ echo 'selected'; } ?> value='Waikato'>Waikato</option>
										<option <?php if($consent_child[$t]->council == 'Waimak'){ echo 'selected'; } ?> value='Waimak'>Waimak</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="9_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="9_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="9_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->council; ?></div> 
							</td>
							<?php 	} ?>


							<?php 	
							if($user_permission_type[29]->display_type == 1)
							{ ?>
							<td id="29_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">
								<?php
								if($user_permission_type[29]->read_type == 2)
								{
								?>

								
								<div id="29_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">

									<select name="lbp" id="lbp_<?php echo $consent_child[$t]->job_no ?>">
										<option value=""></option>
										<option <?php if($consent_child[$t]->lbp == 'Susan G'){ echo 'selected'; } ?> value="Susan G">Susan G</option>
										<option <?php if($consent_child[$t]->lbp == 'Mark B'){ echo 'selected'; } ?> value="Mark B">Mark B</option>
										<option <?php if($consent_child[$t]->lbp == 'Nathan V'){ echo 'selected'; } ?> value="Nathan V">Nathan V</option>
										<option <?php if($consent_child[$t]->lbp == 'Selina A'){ echo 'selected'; } ?> value="Selina A">Selina A</option>
										<option <?php if($consent_child[$t]->lbp == 'Chelsea K'){ echo 'selected'; } ?> value="Chelsea K">Chelsea K</option>
										<option <?php if($consent_child[$t]->lbp == 'Jos K'){ echo 'selected'; } ?> value="Jos K">Jos K</option>
										<option <?php if($consent_child[$t]->lbp == 'Andy D'){ echo 'selected'; } ?> value="Andy D">Andy D</option>
									</select>
									<!-- <input type="text" value="<?php echo $consent_child[$t]->lbp;  ?>" name="lbp" id="lbp_<?php echo $consent_child[$t]->job_no ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','lbp','lbp_<?php echo $consent_child[$t]->job_no ?>',29)" /> -->
									&nbsp;&nbsp;
									
								</div>
								<div id="29_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="29_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php	}	?>
								<div id="29_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->lbp; ?></div> 				
							</td>
							<?php } ?>


							<?php 	
								if($user_permission_type[30]->display_type == 1)
								{ 
							?>
							<td id="30_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">
							<?php
									if($user_permission_type[30]->read_type == 2)
									{
								?>
								<div id="30_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="date_job_checked_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="date_job_checked" value="<?php if($consent_child[$t]->date_job_checked != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->date_job_checked); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','date_job_checked','date_job_checked_<?php echo $consent_child[$t]->job_no ?>',30)" src="<?php echo base_url(); ?>images/icon/edit_pass.png" > 
									&nbsp;
									
								</div>
								<div id="30_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="30_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="30_dis_<?php echo $consent_child[$t]->job_no ?>"><?php if($consent_child[$t]->date_job_checked != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->date_job_checked); } ?></div> 
								
							</td>
							<?php 	} ?>



							<?php 	
										if($user_permission_type[10]->display_type == 1)
										{ 	
							?>
							<td id="10_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">
							<?php	
											if($user_permission_type[10]->read_type == 2)
											{
								?>
								<div id="10_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<!-- <input type='text' id="bc_number_<?php echo $consent_child[$t]->job_no ?>" name="bc_number" value="<?php echo $consent_child[$t]->bc_number; ?> " onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','bc_number','bc_number_<?php echo $consent_child[$t]->job_no ?>',10)" />&nbsp;-->
									<select name="bc_number" id="bc_number_<?php echo $consent_child[$t]->job_no ?>" onchange="bc_number_change(this.id,'<?php echo $consent_child[$t]->job_no;?>')">
										<option value=""></option>
										<option <?php if($consent_child[$t]->bc_number == 'Checking'){ echo 'selected'; } ?> value="Checking">Checking</option>
										<option <?php if($consent_child[$t]->bc_number == 'Checked'){ echo 'selected'; } ?> value="Checked">Checked</option>
										<option <?php if($consent_child[$t]->bc_number != 'Checking' || $consent_child[$t]->bc_number != 'Checked'){ echo 'selected'; } ?> value="Other">Other</option>
									</select>
									<input value="<?php if($consent_child[$t]->bc_number != 'Checking' || $consent_child[$t]->bc_number != 'Checked'){ echo $consent_child[$t]->bc_number; } ?>" id="other_reason_<?php echo $consent_child[$t]->job_no ?>" name="other_reason" type="text" placeholder="Other Reason" style="display:none;" />
									
								</div>
								<div id="10_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="10_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="10_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->bc_number; ?></div> 
								
							</td>
							<?php 	} ?>

							<?php 	
							if($user_permission_type[11]->display_type == 1)
							{ 
							?>
							<td id="11_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:1%">
							<?php	
											if($user_permission_type[11]->read_type == 2)
											{
								?>
								<div id="11_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input type='text' id="no_units_<?php echo $consent_child[$t]->job_no ?>" name="no_units" value="<?php echo $consent_child[$t]->no_units; ?> " onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','no_units','no_units_<?php echo $consent_child[$t]->job_no ?>',11)" >&nbsp;
									
								</div>
								<div id="11_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="11_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="11_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->no_units; ?></div> 					
							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[12]->display_type == 1)
										{ 
							?>
							<td id="12_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:1%">
							<?php	
											if($user_permission_type[12]->read_type == 2)
											{
								?>
								<div id="12_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="contract_type" id="contract_type_<?php echo $consent_child[$t]->job_no ?>">
										<option value=""></option>
										<option <?php if($consent_child[$t]->contract_type == 'BC'){ echo 'selected'; } ?> value='BC'>BC</option>
										<option <?php if($consent_child[$t]->contract_type == 'DU'){ echo 'selected'; } ?> value='DU'>DU</option>
										<option <?php if($consent_child[$t]->contract_type == 'EQ'){ echo 'selected'; } ?> value='EQ'>EQ</option>
										<option <?php if($consent_child[$t]->contract_type == 'HL'){ echo 'selected'; } ?> value='HL'>HL</option>
										<option <?php if($consent_child[$t]->contract_type == 'MU'){ echo 'selected'; } ?> value='MU'>MU</option>
										<option <?php if($consent_child[$t]->contract_type == 'TK'){ echo 'selected'; } ?> value='TK'>TK</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="12_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="12_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="12_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->contract_type; ?></div> 
							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[13]->display_type == 1)
										{ 
							?>
							<td id="13_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:1%">
							<?php	
											if($user_permission_type[13]->read_type == 2)
											{
								?>
								<div id="13_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="type_of_build" id="type_of_build_<?php echo $consent_child[$t]->job_no ?>">
										<option value=""></option>
										<option <?php if($consent_child[$t]->type_of_build == 'SH'){ echo 'selected'; } ?> value="SH">SH</option>
										<option <?php if($consent_child[$t]->type_of_build == 'MU'){ echo 'selected'; } ?> value="MU">MU</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="13_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="13_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="13_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->type_of_build; ?></div> 
								
							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[14]->display_type == 1)
										{ 
							?>
							<td id="14_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; <?php if($consent_child[$t]->variation_pending=='Yes'){ ?>background-color:red; color:white;<?php } ?> ">
							<?php		
											if($user_permission_type[14]->read_type == 2)
											{
								?>
								<div id="14_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select id="variation_pending_<?php echo $consent_child[$t]->job_no ?>" name='variation_pending'>
										<option value=""></option>
										<option <?php if($consent_child[$t]->variation_pending == 'Yes'){ echo 'selected'; } ?> value='Yes'>Yes</option>
									</select>
									&nbsp;

									
								</div>
								<div id="14_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="14_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="14_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->variation_pending; ?></div> 
							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[15]->display_type == 1)
										{ 
							?>
							<td id="15_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">
							<?php	
											if($user_permission_type[15]->read_type == 2)
											{
								?>
								<div id="15_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input value="<?php if($consent_child[$t]->foundation_type != 'Standard Engineered' 
															|| $consent_child[$t]->foundation_type != 'Standard'
															|| $consent_child[$t]->foundation_type != 'Rib & Shingle'
															|| $consent_child[$t]->foundation_type != 'Jackable Rib & Shingle'
															|| $consent_child[$t]->foundation_type != 'Superslab & Shingle'
															|| $consent_child[$t]->foundation_type != 'TC3 type 2B'){ echo $consent_child[$t]->foundation_type; } ?>" type="text" name="foundation_type1" id="foundation_type1_<?php echo $consent_child[$t]->job_no ?>" />
									<select name="foundation_type2" id="foundation_type2_<?php echo $consent_child[$t]->job_no ?>">
										<option value="">Select Foundation Type</option>
										<option <?php if($consent_child[$t]->foundation_type == 'Standard Engineered'){ echo 'selected'; } ?> value="Standard Engineered">Standard Engineered</option>
										<option <?php if($consent_child[$t]->foundation_type == 'Standard'){ echo 'selected'; } ?> value="Standard">Standard</option>
										<option <?php if($consent_child[$t]->foundation_type == 'Rib & Shingle'){ echo 'selected'; } ?> value="Rib & Shingle">Rib & Shingle</option>
										<option <?php if($consent_child[$t]->foundation_type == 'Jackable Rib & Shingle'){ echo 'selected'; } ?> value="Jackable Rib & Shingle">Jackable Rib & Shingle</option>
										<option <?php if($consent_child[$t]->foundation_type == 'Superslab & Shingle'){ echo 'selected'; } ?> value="Superslab & Shingle">Superslab & Shingle</option>
										<option <?php if($consent_child[$t]->foundation_type == 'TC3 type 2B'){ echo 'selected'; } ?> value="TC3 type 2B">TC3 type 2B</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="15_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="15_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="15_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->foundation_type; ?></div> 
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="62_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="62_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="resource_consent_<?php echo $consent_child[$t]->job_no ?>" name="resource_consent" value="<?php echo $consent_child[$t]->resource_consent; ?>" />		
							</div>
							<div id="62_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="62_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="62_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->resource_consent; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="63_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="63_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="rc_number_<?php echo $consent_child[$t]->job_no ?>" name="rc_number" value="<?php echo $consent_child[$t]->rc_number; ?>" />		
							</div>
							<div id="63_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="63_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="63_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->rc_number; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="64_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="64_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input class="live_datepicker" type='text' id="expected_date_to_lodge_bc_<?php echo $consent_child[$t]->job_no ?>" name="expected_date_to_lodge_bc" value="<?php if($consent_child[$t]->expected_date_to_lodge_bc != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->expected_date_to_lodge_bc); } ?>" />		
							</div>
							<div id="64_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="64_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="64_dis_<?php echo $consent_child[$t]->job_no ?>"><?php if($consent_child[$t]->expected_date_to_lodge_bc != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->expected_date_to_lodge_bc); } ?></div> 		
							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[16]->display_type == 1)
										{ 
							?>
							<td id="16_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; <?php if($consent_child[$t]->date_logged != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
							<?php	
											if($user_permission_type[16]->read_type == 2)
											{
								?>
								<div id="16_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="date_logged_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="date_logged" value="<?php if($consent_child[$t]->date_logged != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->date_logged); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','date_logged','date_logged_<?php echo $consent_child[$t]->job_no ?>',16)" src="<?php echo base_url(); ?>images/icon/edit_pass.png"   > 
									&nbsp;
									
								</div>
								<div id="16_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="16_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="16_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	if($consent_child[$t]->date_logged != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->date_logged); } ?></div> 
							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[17]->display_type == 1)
										{ 
							?>
							<td id="17_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%;<?php if($consent_child[$t]->date_issued != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
							<?php	
											if($user_permission_type[17]->read_type == 2)
											{
								?>
								<div id="17_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="date_issued_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="date_issued" value="<?php if($consent_child[$t]->date_issued != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->date_issued); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','date_issued','date_issued_<?php echo $consent_child[$t]->job_no ?>',17)" src="<?php echo base_url(); ?>images/icon/edit_pass.png" > 
									&nbsp;
									
								</div>
								<div id="17_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="17_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="17_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	if($consent_child[$t]->date_issued != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->date_issued); } ?></div> 			
							</td>
							<?php 	} ?>


							<?php 	
								if($user_permission_type[17]->display_type == 1)
								{ 
							?>
							<td id="18_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">
							<?php	
											if($user_permission_type[17]->read_type == 2)
											{
								?>
								<div id="18_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="actual_date_issued_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="actual_date_issued" value="<?php if($consent_child[$t]->actual_date_issued != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->actual_date_issued); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','actual_date_issued','actual_date_issued_<?php echo $consent_child[$t]->job_no ?>',18)" src="<?php echo base_url(); ?>images/icon/edit_pass.png" > 
									&nbsp;
									
								</div>
								<div id="18_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="18_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="18_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	if($consent_child[$t]->actual_date_issued != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->actual_date_issued); } ?></div> 			
							</td>
							<?php 	} ?>



							<?php 
								if($user_permission_type[18]->display_type == 1)
								{ ?>
								<td style="width:1%">
									<?php
									if($consent_child[$t]->date_logged != '0000-00-00' && $consent_child[$t]->date_issued != '0000-00-00'){ echo $days_in_council = $ci->consent_model->get_working_days($consent_child[$t]->date_logged, $consent_child[$t]->date_issued) - 1; }else if($consent_child[$t]->date_logged != '0000-00-00' && $consent_child[$t]->date_issued == '0000-00-00'){ echo $days_in_council = $ci->consent_model->get_working_days($consent_child[$t]->date_logged, date('Y-m-d')) - 1; }else{ echo '0'; }

									?>					
								</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="65_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="65_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="water_connection_<?php echo $consent_child[$t]->job_no ?>" name="water_connection" value="<?php echo $consent_child[$t]->water_connection; ?>" />		
							</div>
							<div id="65_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="65_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="65_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->water_connection; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="66_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="66_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="vehicle_crossing_<?php echo $consent_child[$t]->job_no ?>" name="vehicle_crossing" value="<?php echo $consent_child[$t]->vehicle_crossing; ?>" />		
							</div>
							<div id="66_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="66_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="66_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->vehicle_crossing; ?></div> 		
							</td>
							<?php 	} ?>
								
							<?php 	
										if($user_permission_type[19]->display_type == 1)
										{ 
							?>
							<td id="19_col_<?php echo $consent_child[$t]->job_no; ?>" id="19_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; background-color:
							<?php 	if($consent_child[$t]->order_site_levels == '') {echo "#FFFFFF";} 
									if($consent_child[$t]->order_site_levels == 'Received') {echo "#90ee90";}
									if($consent_child[$t]->order_site_levels == 'Sent') {echo "#70B5FF";}
							?>">

							<?php	
											if($user_permission_type[19]->read_type == 2)
											{
								?>
								<div id="19_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="order_site_levels" id="order_site_levels_<?php echo $consent_child[$t]->job_no ?>">
										<option value=""></option>
										<option <?php if($consent_child[$t]->order_site_levels == 'N/A'){ echo 'selected'; } ?> value="N/A">N/A</option>
										<option <?php if($consent_child[$t]->order_site_levels == 'Received'){ echo 'selected'; } ?> value="Received">Received</option>
										<option <?php if($consent_child[$t]->order_site_levels == 'Sent'){ echo 'selected'; } ?> value="Sent">Sent</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="19_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="19_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="19_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->order_site_levels; ?></div> 
								
							</td>
							<?php 	} ?>



							<?php 	
										if($user_permission_type[20]->display_type == 1)
										{ 
							?>
							<td id="20_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; background-color:
							<?php 	if($consent_child[$t]->order_soil_report == 'N/A') {echo "#FFFFFF";} 
									if($consent_child[$t]->order_soil_report == 'Received') {echo "#90ee90";}
									if($consent_child[$t]->order_soil_report == 'Sent') {echo "#70B5FF";}
							?>">

							<?php	
											if($user_permission_type[20]->read_type == 2)
											{
								?>
								<div id="20_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="order_soil_report" id="order_soil_report_<?php echo $consent_child[$t]->job_no ?>">
										<option value=''></option>
										<option <?php if($consent_child[$t]->order_soil_report == 'N/A'){ echo 'selected'; } ?> value="N/A">N/A</option>
										<option <?php if($consent_child[$t]->order_soil_report == 'Received'){ echo 'selected'; } ?> value="Received">Received</option>
										<option <?php if($consent_child[$t]->order_soil_report == 'Sent'){ echo 'selected'; } ?> value="Sent">Sent</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="20_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="20_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="20_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->order_soil_report; ?></div> 
								
							</td>
							<?php 	} ?>


							<?php 	
										if($user_permission_type[21]->display_type == 1)
										{ 
							?>
							<td id="21_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; color:white; background-color:
							<?php 	
									if($consent_child[$t]->septic_tank_approval == 'REQ') {echo "red";}
									if($consent_child[$t]->septic_tank_approval == 'N/A') {echo "#90ee90";} 
									if($consent_child[$t]->septic_tank_approval == 'SENT') {echo "blue";}
									if($consent_child[$t]->septic_tank_approval == 'RECEIVED') {echo "#90ee90";}
							?>">
								
							<?php	if($user_permission_type[21]->read_type == 2)
									{
								?>
								<div id="21_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="septic_tank_approval" id="septic_tank_approval_<?php echo $consent_child[$t]->job_no ?>">
										<option value=''></option>
										<option <?php if($consent_child[$t]->septic_tank_approval == 'REQ'){ echo 'selected'; } ?> value='REQ'>REQ</option>
										<option <?php if($consent_child[$t]->septic_tank_approval == 'N/A'){ echo 'selected'; } ?> value='N/A'>N/A</option>
										<option <?php if($consent_child[$t]->septic_tank_approval == 'SENT'){ echo 'selected'; } ?> value='SENT'>SENT</option>
										<option <?php if($consent_child[$t]->septic_tank_approval == 'RECEIVED'){ echo 'selected'; } ?> value='RECEIVED'>RECEIVED</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="21_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="21_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="21_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->septic_tank_approval; ?></div> 

							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[22]->display_type == 1)
										{ 
							?>
							<td id="22_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; color:white; background-color:
							<?php 
									if($consent_child[$t]->dev_approval == 'REQ') {echo "red";} 
									if($consent_child[$t]->dev_approval == 'N/A') {echo "#90ee90";} 
									if($consent_child[$t]->dev_approval == 'PRE SENT') {echo "blue";}
									if($consent_child[$t]->dev_approval == 'PRE REC') {echo "yellow; color:black";}
									if($consent_child[$t]->dev_approval == 'FULL SENT') {echo "blue";}
									if($consent_child[$t]->dev_approval == 'FULL REC') {echo "#90ee90";}
							?>">

								<?php	
											if($user_permission_type[22]->read_type == 2)
											{
								?>
								<div id="22_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="dev_approval" id="dev_approval_<?php echo $consent_child[$t]->job_no ?>">
										<option value=''></option>
										<option <?php if($consent_child[$t]->dev_approval == 'REQ'){ echo 'selected'; } ?> value='REQ'>REQ</option>
										<option <?php if($consent_child[$t]->dev_approval == 'N/A'){ echo 'selected'; } ?> value='N/A'>N/A</option>
										<option <?php if($consent_child[$t]->dev_approval == 'PRE SENT'){ echo 'selected'; } ?> value='PRE SENT'>PRE SENT</option>
										<option <?php if($consent_child[$t]->dev_approval == 'PRE REC'){ echo 'selected'; } ?> value='PRE REC'>PRE REC</option>
										<option <?php if($consent_child[$t]->dev_approval == 'FULL SENT'){ echo 'selected'; } ?> value='FULL SENT'>FULL SENT</option>
										<option <?php if($consent_child[$t]->dev_approval == 'FULL REC'){ echo 'selected'; } ?> value='FULL REC'>FULL REC</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="22_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="22_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="22_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->dev_approval; ?></div> 
								
							</td>
							<?php 	} ?>


							<?php 	
										if($user_permission_type[22]->display_type == 1)
										{ 
							?>
							<td id="32_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; color:white; background-color:
							<?php 	
									if($consent_child[$t]->landscape == 'REQ') {echo "red";}
									if($consent_child[$t]->landscape == 'N/A') {echo "#90ee90";} 
									if($consent_child[$t]->landscape == 'SENT') {echo "blue";}
									if($consent_child[$t]->landscape == 'RECEIVED') {echo "#90ee90";}
							?>">

								<?php	
											if($user_permission_type[22]->read_type == 2)
											{
								?>
								<div id="32_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="landscape" id="landscape_<?php echo $consent_child[$t]->job_no ?>">
										<option value=''></option>
										<option <?php if($consent_child[$t]->landscape == 'REQ'){ echo 'selected'; } ?> value='REQ'>REQ</option>
										<option <?php if($consent_child[$t]->landscape == 'N/A'){ echo 'selected'; } ?> value='N/A'>N/A</option>
										<option <?php if($consent_child[$t]->landscape == 'SENT'){ echo 'selected'; } ?> value='SENT'>SENT</option>
										<option <?php if($consent_child[$t]->landscape == 'RECEIVED'){ echo 'selected'; } ?> value='RECEIVED'>RECEIVED</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="32_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="32_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="32_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->landscape; ?></div> 
								
							</td>
							<?php 	} ?>


							<?php 	
							if($user_permission_type[22]->display_type == 1)
							{ 
							?>
							<td id="33_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; color:white; background-color:
							<?php 	if($consent_child[$t]->mss == 'REQ') {echo "red";} 
									if($consent_child[$t]->mss == 'DONE') {echo "#90ee90";}
							?>">

								<?php	
								if($user_permission_type[22]->read_type == 2)
								{
								?>
								<div id="33_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="mss" id="mss_<?php echo $consent_child[$t]->job_no ?>">
										<option value=''></option>
										<option <?php if($consent_child[$t]->mss == 'REQ'){ echo 'selected'; } ?> value='REQ'>REQ</option>
										<option <?php if($consent_child[$t]->mss == 'DONE'){ echo 'selected'; } ?> value='DONE'>DONE</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="33_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="33_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="33_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->mss; ?></div> 
								
							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[23]->display_type == 1)
										{ 
							?>
							<td id="23_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">
							<?php	
											if($user_permission_type[23]->read_type == 2)
											{
								?>
								<div id="23_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name='project_manager' id="project_manager_<?php echo $consent_child[$t]->job_no ?>">
										<option value='0'>Select Project Manager</option>
										<?php  foreach ($project_manager_list as $project_manager){?> <option <?php if($consent_child[$t]->project_manager == $project_manager->uid){ echo 'selected'; } ?> value='<?php echo $project_manager->uid ?>' <?php if($project_manager->uid == $consent_child[$t]->project_manager){ ?> selected="selected" <?php } ?> > <?php echo $project_manager->fullname ?></option> <?php }?>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="23_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="23_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="23_dis_<?php echo $consent_child[$t]->job_no ?>"><?php echo $consent_child[$t]->project_manager; ?></div> 
								
							</td>
							<?php 	} ?>


							<?php 	
										if($user_permission_type[25]->display_type == 1)
										{ 
							?>
							<td id="25_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; <?php if($consent_child[$t]->unconditional_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
							<?php	
											if($user_permission_type[25]->read_type == 2)
											{

											

								?>
								<div id="25_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="unconditional_date_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="unconditional_date" value="<?php if($consent_child[$t]->unconditional_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->unconditional_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','unconditional_date','unconditional_date_<?php echo $consent_child[$t]->job_no ?>',25)" > 
									&nbsp;
									
								</div>
								<div id="25_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="25_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="25_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	if($consent_child[$t]->unconditional_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->unconditional_date); } ?></div> 
								
							</td>
							<?php 	} ?>

							<?php 	
								if($user_permission_type[26]->display_type == 1)
								{ 
							?>
							<td id="26_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; <?php if($consent_child[$t]->handover_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
							<?php
									if($user_permission_type[26]->read_type == 2)
									{
								?>
								<div id="26_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="handover_date_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="handover_date" value="<?php if($consent_child[$t]->handover_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->handover_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','handover_date','handover_date_<?php echo $consent_child[$t]->job_no ?>',26)"  > 
									&nbsp;
									
								</div>
								<div id="26_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="26_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="26_dis_<?php echo $consent_child[$t]->job_no ?>"><?php if($consent_child[$t]->handover_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->handover_date); } ?></div> 
								
							</td>
							<?php 	} ?>

							<?php 	
								if($user_permission_type[27]->display_type == 1)
								{	?>

							<td id="27_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">

								<?php	if($user_permission_type[27]->read_type == 2)
									{
								?>


								<div id="27_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name='builder' id="builder_<?php echo $consent_child[$t]->job_no ?>">
										<option value='0'>Select Builder</option>
										<?php  foreach ($builder_list as $builder){ ?> <option <?php if($consent_child[$t]->builder == $builder->uid){ echo 'selected'; } ?> value='<?php echo $builder->uid ?>' <?php if($builder->uid == $consent_child[$t]->builder){ ?> selected="selected" <?php }  ?> > <?php echo $builder->fullname ?></option> <?php } ?>
									</select> 
									&nbsp;
									
								</div>
								<div id="27_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="27_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php }	?>
								<div id="27_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->builder; ?></div> 
									
							</td>
							<?php } ?>


							<?php 	
							if($user_permission_type[28]->display_type == 1)
							{ ?>

							<td id="28_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">
								<?php
								if($user_permission_type[28]->read_type == 2)
								{
								?>

								
								<div id="28_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="consent_out_but_no_builder" id="consent_out_but_no_builder_<?php echo $consent_child[$t]->job_no ?>">
										<option value=""></option>
										<option <?php if($consent_child[$t]->consent_out_but_no_builder == 'Allocated'){ echo 'selected'; } ?> value="Allocated">Allocated</option>
										<option <?php if($consent_child[$t]->consent_out_but_no_builder == 'Due'){ echo 'selected'; } ?> value="Due">Due</option>
										<option <?php if($consent_child[$t]->consent_out_but_no_builder == 'Need Builder'){ echo 'selected'; } ?> value="Need Builder">Need Builder</option>
									</select>
									&nbsp;&nbsp;
									
								</div>
								<div id="28_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="28_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php	}	?>
								<div id="28_dis_<?php echo $consent_child[$t]->job_no ?>"><?php if($consent_child[$t]->builder == ''){echo "Need Builder";}else{echo $consent_child[$t]->consent_out_but_no_builder;} ?></div> 				
							</td>
							<?php } ?>


							<?php 	
								if($user_permission_type[26]->display_type == 1)
								{ 
							?>
							<td id="34_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%">
							<?php
									if($user_permission_type[26]->read_type == 2)
									{
								?>
								<div id="34_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<select name="title_date_issued" id="title_date_issued_<?php echo $consent_child[$t]->job_no ?>" onchange="titleDateIssued(<?php echo "'".$consent_child[$t]->job_no."'"; ?>)">
										<option value=""></option>
										<option <?php if($consent_child[$t]->title_date_issued == 'Issued'){ echo 'selected'; } ?> value="Issued">Issued</option>
										
									</select>
									&nbsp;&nbsp;
									<input <?php if($consent_child[$t]->title_date_issued == 'Issued'){ echo 'style="display:block;"'; }else{ echo 'style="display:none;"'; } ?> id="title_date_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="title_date" value="<?php if($consent_child[$t]->title_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->title_date); } ?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','title_date','title_date_<?php echo $consent_child[$t]->job_no ?>',34)"  > 
									&nbsp;
									
								</div>
								<div id="34_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="34_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="34_dis_<?php echo $consent_child[$t]->job_no ?>"><?php if($consent_child[$t]->title_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->title_date); } ?></div> 
							</td>
							<?php 	} ?>


							<?php 	
								if($user_permission_type[26]->display_type == 1)
								{ 
							?>
							<td id="35_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; <?php if($consent_child[$t]->settlement_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
							<?php
									if($user_permission_type[26]->read_type == 2)
									{
								?>
								<div id="35_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="settlement_date_<?php echo $consent_child[$t]->job_no ?>" type="text" class="live_datepicker" name="settlement_date" value="<?php if($consent_child[$t]->settlement_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->settlement_date); } ?>"  onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','settlement_date','settlement_date_<?php echo $consent_child[$t]->job_no ?>',35)" > 
									&nbsp;
									
								</div>
								<div id="35_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="35_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="35_dis_<?php echo $consent_child[$t]->job_no ?>"><?php if($consent_child[$t]->settlement_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$t]->settlement_date); } ?></div> 
								
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="67_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="67_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="for_sale_sign_<?php echo $consent_child[$t]->job_no ?>" name="for_sale_sign" value="<?php echo $consent_child[$t]->for_sale_sign; ?>" />		
							</div>
							<div id="67_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="67_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="67_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->for_sale_sign; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="68_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="68_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='text' id="code_of_compliance_<?php echo $consent_child[$t]->job_no ?>" name="code_of_compliance" value="<?php echo $consent_child[$t]->code_of_compliance; ?>" />		
							</div>
							<div id="68_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="68_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="68_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->code_of_compliance; ?></div> 		
							</td>
							<?php 	} ?>

							<?php if($user_permission_type[31]->display_type == 1){ ?>
							<td id="69_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%; height:30px;">	
							<?php 	if($user_permission_type[31]->read_type == 2){?>
							<div id="69_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
							<input type='file' id="photos_taken_<?php echo $consent_child[$t]->job_no ?>" name="photos_taken" />		
							</div>
							<div id="69_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
							<div id="69_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div> 
							<?php }	?>
							<div id="69_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->photos_taken; ?></div> 		
							</td>
							<?php 	} ?>

							<?php 	
										if($user_permission_type[17]->display_type == 1)
										{ 
							?>
							<td id="36_col_<?php echo $consent_child[$t]->job_no; ?>" style="width:2%;color:blue;">
							<?php	
											if($user_permission_type[17]->read_type == 2)
											{
								?>
								<div id="36_box_<?php echo $consent_child[$t]->job_no ?>" class="dnone">
									<input id="notes_<?php echo $consent_child[$t]->job_no ?>" type="text" name="notes" value="<?php echo $consent_child[$t]->notes;?>" onkeydown="Javascript: if (event.keyCode==13) update_consent('<?php echo $consent_child[$t]->job_no; ?>','notes','notes_<?php echo $consent_child[$t]->job_no ?>',36)" > 
									&nbsp;
									
								</div>
								<div id="36_tab_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><?php echo $p; ?></div>
								<div id="36_timg_<?php echo $consent_child[$t]->job_no ?>" class="dnone"><img src="<?php echo base_url(); ?>images/icon/edit_pass.png" onclick="update_consent(<?php echo $month_id; ?>,<?php echo $p; ?>)"  /></div>
								<?php		}	?>
								<div id="36_dis_<?php echo $consent_child[$t]->job_no ?>"><?php 	echo $consent_child[$t]->notes; ?></div> 			
							</td>
							<?php 	} ?>
									</tr>

						<?php
						}
					} 

					$n++; 
				} // end task for loop  

					if($n == 0)
					{
							
					 ?>
                      
						<tr id="odd" class="odd" style="">
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td><td style="border:0px solid #fff"></td>
						<td style="border:0px solid #fff"></td>
						</tr>
				<?php } ?>

					</tbody>
		
			</table>
                                    
		</div>
			</li>
			<?php
	
	        //} // in consent info			
	

			} // available month if

			}// end phase for loop
			
			?>

	</ul>
	
	
	<form action="" method="POST">
		<div id="month_numbers" style="margin:20px auto; text-align:center">Show consent from 
			<select name="from_month" style="width:200px">
				<option <?php if($s_month == 0){?> selected <?php } ?> value="0">From current month</option>
				<option <?php if($s_month == 6){?> selected <?php } ?> value="6">Last 6 months</option>
				<option <?php if($s_month == 12){?> selected <?php } ?> value="12">Last 1 Year</option>
				<option <?php if($s_month == 18){?> selected <?php } ?> value="18">Last 1 year 6 months</option>
				<option <?php if($s_month == 24){?> selected <?php } ?> value="24">Last 2 years </option>
				<option <?php if($s_month == 30){?> selected <?php } ?> value="30">Last 2 years 6 months </option>
				<option <?php if($s_month == 36){?> selected <?php } ?> value="36">Last 3 years </option>
			</select>
			to
			<select name="to_month" style="width:200px">
				<option <?php if($e_month == 0){?> selected <?php } ?> value="0">Up to current month</option>
				<option <?php if($e_month == -6){?> selected <?php } ?> value="-6">Next 6 months</option>
				<option <?php if($e_month == -12){?> selected <?php } ?> value="-12">Next 1 Year</option>
				<option <?php if($e_month == -18){?> selected <?php } ?> value="-18">Next 1 year 6 months</option>
				<option <?php if($e_month == -24){?> selected <?php } ?> value="-24">Next 2 years </option>
				<option <?php if($e_month == -30){?> selected <?php } ?> value="-30">Next 2 years 6 months </option>
				<option <?php if($e_month == -36){?> selected <?php } ?> value="-36">Next 3 years </option>
			</select>
			<input type="submit" class="btn" name="show_consent" value="Show Consent" />
		
		</div>
	</form>
	
       
</div>

<!-- for table header sorting   -->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js" /></script>
<script type="text/javascript" src="<?php echo base_url();?>js/colResizable-1.5.min.js"></script>
<script>
<?php 
			$tableids = '';
			for($p=$s_month; $p >= $e_month; $p--)
			{
				$tableids = $tableids.'#table'.$p.', ';
			}
		?>
	
		var tblids = '<?php echo $tableids; ?>';
		var numoftblids = tblids.length;
    	var restable = tblids.substring(0, numoftblids - 2);

		//$(restable).colResizable({
			//liveDrag:true, 
			//});

$('table').tablesorter();
// using a flag that prevents recursion - repeatedly calling this same function, because it
// will trigger the "sortEnd" event after sorting the other tables.
var recursionFlag = false;
$("table").bind("sortEnd",function(e, table) {

    if (!recursionFlag) {
        recursionFlag = true;
        $('table').not(this).trigger("sorton", [ table.config.sortList ]);
        setTimeout(function(){ recursionFlag = false; }, 100);
    }
});

</script>	
<!-- for table header sorting   -->

<script>
	function titleDateIssued(job_no){
		title_date_issued = $('#title_date_issued_'+job_no).val();
		if(title_date_issued=='Issued'){
			$('#title_date_'+job_no).css("display","block");
		}else{
			$('#title_date_'+job_no).css("display","none");
		}
	}

	function showchild(job_id,tdid){
		if($(".child_"+job_id).css("display") == 'table-row'){
			$(".child_"+job_id).css("display","none");
			$("#"+tdid+" div").removeClass("toggle_on");
			$("#"+tdid+" div").addClass("toggle_off");
		}else{
			$(".child_"+job_id).css("display","table-row");
			$("#"+tdid+" div").removeClass("toggle_off");
			$("#"+tdid+" div").addClass("toggle_on");
		}
	}


$(document).ready(function() {

	$('.ui-corner-all input').click(function(){

		consent_fields_id = $(".ui-corner-all.ui-state-hover input").val();
		consent_fields_text = $(".ui-corner-all.ui-state-hover span").text();
		consent_fields_t_f = $(".ui-corner-all.ui-state-hover input").attr('aria-selected');

		consent_by_value = '<?php echo $consent_by_value; ?>';
		project_manager_value = '<?php echo $project_manager_value; ?>';
		builder_value = '<?php echo $builder_value; ?>';

		if(consent_fields_id=='consent_name' || consent_fields_id=='no_units' || consent_fields_id=='notes'){
				if(consent_fields_t_f=='true'){
					$("#"+consent_fields_id).remove();
				}else{
					$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><input type="text" id="search_value" name="'+consent_fields_id+'_search_value" /></div>');
				}
		}else if(consent_fields_id=='design' || consent_fields_id=='consent_by' || consent_fields_id=='action_required' || consent_fields_id=='council' || consent_fields_id=='lbp' || consent_fields_id=='bc_number' || consent_fields_id=='contract_type' || consent_fields_id=='type_of_build' || consent_fields_id=='variation_pending' || consent_fields_id=='foundation_type' || consent_fields_id=='order_site_levels' || consent_fields_id=='order_soil_report' || consent_fields_id=='septic_tank_approval' || consent_fields_id=='dev_approval' || consent_fields_id=='landscape' || consent_fields_id=='mss' || consent_fields_id=='project_manager' || consent_fields_id=='builder' || consent_fields_id=='consent_out_but_no_builder'){
			if(consent_fields_t_f=='true'){
				$("#"+consent_fields_id).remove();
			}else if(consent_fields_id=='design'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="REQ Brief">REQ Brief</option><option value="Brief">Brief</option><option value="Hold">Hold</option><option value="Sign">Sign</option><option value="Consent">Consent</option></select></div>');
			}else if(consent_fields_id=='consent_by'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option>'+consent_by_value+'</select></div>');
			}else if(consent_fields_id=='action_required'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="Urgent">Urgent</option></select></div>');
			}else if(consent_fields_id=='council'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="Ashburton">Ashburton</option><option value="Auckland">Auckland</option><option value="Chch">Chch</option><option value="Hurunui">Hurunui</option><option value="Selwyn">Selwyn</option><option value="Waikato">Waikato</option><option value="Waimak">Waimak</option></select></div>');
			}else if(consent_fields_id=='lbp'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="Susan G">Susan G</option><option value="Mark B">Mark B</option><option value="Nathan V">Nathan V</option><option value="Selina A">Selina A</option><option value="Chelsea K">Chelsea K</option><option value="Jos K">Jos K</option><option value="Andy D">Andy D</option></select></div>');
			}else if(consent_fields_id=='bc_number'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="Checking">Checking</option><option value="Checked">Checked</option></select></div>');
			}else if(consent_fields_id=='contract_type'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="BC">BC</option><option value="DU">DU</option><option value="EQ">EQ</option><option value="HL">HL</option><option value="MU">MU</option><option value="TK">TK</option></select></div>');
			}else if(consent_fields_id=='type_of_build'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="SH">SH</option><option value="MU">MU</option></select></div>');
			}else if(consent_fields_id=='variation_pending'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="Yes">Yes</option></select></div>');
			}else if(consent_fields_id=='foundation_type'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="Standard Engineered">Standard Engineered</option><option value="Standard">Standard</option><option value="Rib &amp; Shingle">Rib &amp; Shingle</option><option value="Jackable Rib &amp; Shingle">Jackable Rib &amp; Shingle</option><option value="Superslab &amp; Shingle">Superslab &amp; Shingle</option><option value="TC3 type 2B">TC3 type 2B</option></select></div>');
			}else if(consent_fields_id=='order_site_levels'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="N/A">N/A</option><option value="Received">Received</option><option value="Sent">Sent</option></select></div>');
			}else if(consent_fields_id=='order_soil_report'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="N/A">N/A</option><option value="Received">Received</option><option value="Sent">Sent</option></select></div>');
			}else if(consent_fields_id=='septic_tank_approval'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="REQ">REQ</option><option value="N/A">N/A</option><option value="SENT">SENT</option><option value="RECEIVED">RECEIVED</option></select></div>');
			}else if(consent_fields_id=='dev_approval'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="REQ">REQ</option><option value="N/A">N/A</option><option value="PRE SENT">PRE SENT</option><option value="PRE REC">PRE REC</option><option value="FULL SENT">FULL SENT</option><option value="FULL REC">FULL REC</option></select></div>');
			}else if(consent_fields_id=='landscape'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="REQ">REQ</option><option value="N/A">N/A</option><option value="SENT">SENT</option><option value="RECEIVED">RECEIVED</option></select></div>');
			}else if(consent_fields_id=='mss'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="REQ">REQ</option><option value="DONE">DONE</option></select></div>');
			}else if(consent_fields_id=='project_manager'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option>'+project_manager_value+'</select></div>');
			}else if(consent_fields_id=='builder'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option>'+builder_value+'</select></div>');
			}else if(consent_fields_id=='consent_out_but_no_builder'){
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="Allocated">Allocated</option><option value="Due">Due</option><option value="Need Builder">Need Builder</option></select></div>');
			}
		}else if(consent_fields_id=='approval_date' || consent_fields_id=='price_approved_date' || consent_fields_id=='pim_logged' || consent_fields_id=='drafting_issue_date' || consent_fields_id=='date_job_checked' || consent_fields_id=='date_logged' || consent_fields_id=='date_issued' || consent_fields_id=='actual_date_issued' || consent_fields_id=='unconditional_date' || consent_fields_id=='handover_date' || consent_fields_id=='title_date' || consent_fields_id=='settlement_date'){
			if(consent_fields_t_f=='true'){
				$("#"+consent_fields_id).remove();
			}else{
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><input type="text" placeholder="-- Select Date From --"  class="live_datepicker" name="'+consent_fields_id+'_from_month" value="" /><input type="text" placeholder="-- Select Date To --" class="live_datepicker" name="'+consent_fields_id+'_to_month" value=""></div>');
			}														
		}else{
			$("#refine_colunm_name").empty();
		}
	});

	$('.ui-multiselect-none').click(function(){
		$("#refine_colunm_name").empty();
	});

	$('.ui-multiselect-all').click(function(){
		$("#refine_colunm_name").empty();

		consent_by_value = '<?php echo $consent_by_value; ?>';
		project_manager_value = '<?php echo $project_manager_value; ?>';
		builder_value = '<?php echo $builder_value; ?>';

		text_fields = new Array('consent_name,Consent', 'no_units,No. Units', 'notes,Notes');
		for(i = 0; i < text_fields.length; i++){
			var one_text_field = text_fields[i];
			var one_text_field = one_text_field.split(",");
			var consent_fields_id = one_text_field[0];
			var consent_fields_text = one_text_field[1];

			$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><input type="text" id="search_value" name="'+consent_fields_id+'_search_value" /></div>');
		}

		$("#refine_colunm_name").append('<div class="colunm3" id="design"><span>Refine Design:</span><br><select id="search_value_select" name="design_search_value"><option value="">-- Select --</option><option value="REQ Brief">REQ Brief</option><option value="Brief">Brief</option><option value="Hold">Hold</option><option value="Sign">Sign</option><option value="Consent">Consent</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="consent_by"><span>Refine Consent by:</span><br><select id="search_value_select" name="consent_by_search_value"><option value="">-- Select --</option>'+consent_by_value+'</select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="action_required"><span>Refine Action required:</span><br><select id="search_value_select" name="action_required_search_value"><option value="">-- Select --</option><option value="Urgent">Urgent</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="council"><span>Refine Council:</span><br><select id="search_value_select" name="council_search_value"><option value="">-- Select --</option><option value="Ashburton">Ashburton</option><option value="Auckland">Auckland</option><option value="Chch">Chch</option><option value="Hurunui">Hurunui</option><option value="Selwyn">Selwyn</option><option value="Waikato">Waikato</option><option value="Waimak">Waimak</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="lbp"><span>Refine LBP:</span><br><select id="search_value_select" name="lbp_search_value"><option value="">-- Select --</option><option value="Susan G">Susan G</option><option value="Mark B">Mark B</option><option value="Nathan V">Nathan V</option><option value="Selina A">Selina A</option><option value="Chelsea K">Chelsea K</option><option value="Jos K">Jos K</option><option value="Andy D">Andy D</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="bc_number"><span>Refine Bc Number:</span><br><select id="search_value_select" name="bc_number_search_value"><option value="">-- Select --</option><option value="Checking">Checking</option><option value="Checked">Checked</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="contract_type"><span>Refine Contract Type:</span><br><select id="search_value_select" name="contract_type_search_value"><option value="">-- Select --</option><option value="BC">BC</option><option value="DU">DU</option><option value="EQ">EQ</option><option value="HL">HL</option><option value="MU">MU</option><option value="TK">TK</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="type_of_build"><span>Refine Type of Build:</span><br><select id="search_value_select" name="type_of_build_search_value"><option value="">-- Select --</option><option value="SH">SH</option><option value="MU">MU</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="variation_pending"><span>Refine Variation Pending:</span><br><select id="search_value_select" name="variation_pending_search_value"><option value="">-- Select --</option><option value="Yes">Yes</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="foundation_type"><span>Refine Foundation Type:</span><br><select id="search_value_select" name="foundation_type_search_value"><option value="">-- Select --</option><option value="Standard Engineered">Standard Engineered</option><option value="Standard">Standard</option><option value="Rib &amp; Shingle">Rib &amp; Shingle</option><option value="Jackable Rib &amp; Shingle">Jackable Rib &amp; Shingle</option><option value="Superslab &amp; Shingle">Superslab &amp; Shingle</option><option value="TC3 type 2B">TC3 type 2B</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="order_site_levels"><span>Refine Order Site Levels:</span><br><select id="search_value_select" name="order_site_levels_search_value"><option value="">-- Select --</option><option value="N/A">N/A</option><option value="Received">Received</option><option value="Sent">Sent</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="order_soil_report"><span>Refine Order Soil Report:</span><br><select id="search_value_select" name="order_soil_report_search_value"><option value="">-- Select --</option><option value="N/A">N/A</option><option value="Received">Received</option><option value="Sent">Sent</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="septic_tank_approval"><span>Refine Septic Tank Approval:</span><br><select id="search_value_select" name="septic_tank_approval_search_value"><option value="">-- Select --</option><option value="REQ">REQ</option><option value="N/A">N/A</option><option value="SENT">SENT</option><option value="RECEIVED">RECEIVED</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="dev_approval"><span>Refine Dev Approval:</span><br><select id="search_value_select" name="dev_approval_search_value"><option value="">-- Select --</option><option value="REQ">REQ</option><option value="N/A">N/A</option><option value="PRE SENT">PRE SENT</option><option value="PRE REC">PRE REC</option><option value="FULL SENT">FULL SENT</option><option value="FULL REC">FULL REC</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="landscape"><span>Refine Landscape:</span><br><select id="search_value_select" name="landscape_search_value"><option value="">-- Select --</option><option value="REQ">REQ</option><option value="N/A">N/A</option><option value="SENT">SENT</option><option value="RECEIVED">RECEIVED</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="mss"><span>Refine MSS:</span><br><select id="search_value_select" name="mss_search_value"><option value="">-- Select --</option><option value="REQ">REQ</option><option value="DONE">DONE</option></select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="project_manager"><span>Refine Project Manager:</span><br><select id="search_value_select" name="project_manager_search_value"><option value="">-- Select --</option>'+project_manager_value+'</select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="builder"><span>Refine Builder:</span><br><select id="search_value_select" name="builder_search_value"><option value="">-- Select --</option>'+builder_value+'</select></div>');
		$("#refine_colunm_name").append('<div class="colunm3" id="consent_out_but_no_builder"><span>Refine Builder Status:</span><br><select id="search_value_select" name="consent_out_but_no_builder_search_value"><option value="">-- Select --</option><option value="Allocated">Allocated</option><option value="Due">Due</option><option value="Need Builder">Need Builder</option></select></div>');
	
		date_fields = new Array('approval_date,Approval Date', 'price_approved_date,Price Approved Date', 'pim_logged,Pim Logged', 'drafting_issue_date,Drafting Issue Date', 'date_job_checked,Date Job Checked', 'date_logged,Date Logged', 'date_issued,Date Issued', 'actual_date_issued,Actual Date Issued', 'unconditional_date,Unconditional Date', 'handover_date,Handover Date', 'title_date,Title Date', 'settlement_date,Settlement Date');
		for(i = 0; i < date_fields.length; i++){
			var one_date_field = date_fields[i];
			var one_date_field = one_date_field.split(",");
			var consent_fields_id = one_date_field[0];
			var consent_fields_text = one_date_field[1];

			$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><input type="text" placeholder="-- Select Date From --"  class="live_datepicker" name="'+consent_fields_id+'_from_month" value="" /><input type="text" placeholder="-- Select Date To --" class="live_datepicker" name="'+consent_fields_id+'_to_month" value=""></div>');
		}

	});
});
</script>