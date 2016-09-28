<script type="text/javascript">
// for notes tooltip 
$(document).ready(function() {
	$('.masterTooltip').hover(function(){
	        // Hover over code
	        var title = $(this).attr('title');
	        //$(this).data('tipText', title).removeAttr('title');
	        $('<p class="tooltip"></p>')
	        .text(title)
	        .appendTo('body')
	        .fadeIn('slow');
	}, function() {
	        // Hover out code
	        $(this).attr('title', $(this).data('tipText'));
	        $('.tooltip').remove();
	}).mousemove(function(e) {
	        var mousex = e.pageX - 10; //Get X coordinates
	        var mousey = e.pageY + 10; //Get Y coordinates
	        $('.tooltip')
	        .css({ top: mousey, left: mousex })
	});
});
</script>
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

			if($consent_fields=='job_no' || $consent_fields=='consent_name' || $consent_fields=='no_units' || $consent_fields=='notes'){
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

<script type="text/javascript">
// for calender focus and calender class
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

// double click function for consent field row(working this function now)
$(document).ready(function() {

	$('.consent_table tr td').dblclick(function(e){
		$('#cmemory').val(1);
		e.preventDefault();
		tdid = $(this).prop('id');

		j_id_arr = tdid.split('_'); 
		var job_id = j_id_arr[2];
		var cons_indx = j_id_arr[0];

		consent_by_value = '<?php echo $consent_by_value; ?>';
		project_manager_value = '<?php echo $project_manager_value; ?>';
		builder_value = '<?php echo $builder_value; ?>';

		if($("#"+tdid).hasClass("close")){

			// for notes condition
			if(cons_indx == 36){
				var tdval = $("#"+tdid+" p").attr('title');
			}else{
				var tdval = $("#"+tdid).text().replace(/\s+/g, " ");
			}
			
			$("#"+tdid).empty();
			if(cons_indx == 0){
				$("#"+tdid).append('<input type="text" name="consent_name" id="consent_id_'+job_id+'" onblur="update_child(this.id,this.name);" value="'+tdval+'"><select name="consent_color" id="consent_color_'+job_id+'" onblur="update_child(this.id,this.name);"><option value=""></option><option value="ffffff">White</option><option value="90ee90">Green</option><option value="FF3D3D">Red</option><option value="FFAC40">Orange</option></select>');
			}else if(cons_indx == 45){
				$("#"+tdid).append('<select name="pre_construction_sign" id="pre_construction_sign_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="REQ">REQ</option><option value="Date">Date</option><option value="N/A">N/A</option></select>');
			}else if(cons_indx == 1){
				$("#"+tdid).append('<select id="design_id_'+job_id+'" name="design" onblur="update_child(this.id,this.name);"><option value="REQ Brief">REQ Brief</option><option value="Brief">Brief</option><option value="Hold">Hold</option><option value="Sign">Sign</option><option value="Consent">Consent</option></select>');
			}else if(cons_indx == 46){
				$("#"+tdid).append('<select name="designer" id="designer_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_other_option(this.id,this.name,this.value);"><option value="">--Select--</option><option value="Tim">Tim</option><option value="Ashleigh">Ashleigh</option><option value="Rebecca">Rebecca</option><option value="Melissa">Melissa</option><option value="David">David</option><option value="Nathan">Nathan</option><option value="Jos">Jos</option><option value="Other">Other</option></select>');
			}else if(cons_indx == 2){
				$("#"+tdid).append('<input type="text" name="approval_date" id="approval_date_'+job_id+'" onchange="update_child(this.id,this.name);" class="live_datepicker" value="'+tdval+'">');
			}else if(cons_indx == 47){
				$("#"+tdid).append('<select name="ok_to_release_to_marketing" id="ok_to_release_to_marketing_'+job_id+'" onblur="update_child(this.id,this.name);"><option value="">--Select--</option><option value="Yes">Yes</option><option value="No">No</option></select>');
			}else if(cons_indx == 48){
				$("#"+tdid).append('<select name="pricing_requested" id="pricing_requested_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="Date">Date</option><option value="REQ">REQ</option><option value="OVERDUE">OVERDUE</option></select>');
			}else if(cons_indx == 49){
				$("#"+tdid).append('<select name="pricing_for_approval" id="pricing_for_approval_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="DUE">DUE</option><option value="Date">Date</option><option value="OVERDUE">OVERDUE</option></select>');
			}else if(cons_indx == 37){
				$("#"+tdid).append('<input type="text" name="price_approved_date" id="price_approved_date_'+job_id+'" onchange="update_child(this.id,this.name);" class="live_datepicker" value="'+tdval+'">');
			}else if(cons_indx == 50){
				$("#"+tdid).append('<input type="text" name="approved_for_sale_price" id="approved_for_sale_price_'+job_id+'" onblur="update_child(this.id,this.name);" value="'+tdval+'">');
			}else if(cons_indx == 51){
				$("#"+tdid).append('<select name="kitchen_disign_type" id="kitchen_disign_type_'+job_id+'" onblur="update_child(this.id,this.name);"><option value="">--Select--</option><option value="STD">STD</option><option value="DESIGNER">DESIGNER</option></select>');
			}else if(cons_indx == 52){
				$("#"+tdid).append('<select name="kitchen_disign_requested" id="kitchen_disign_requested_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="Date">Date</option><option value="REQ">REQ</option><option value="OVERDUE">OVERDUE</option></select>');
			}else if(cons_indx == 53){
				$("#"+tdid).append('<select name="colours_requested_and_loaded_on_gc" id="colours_requested_and_loaded_on_gc_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="Date">Date</option><option value="REQ">REQ</option></select>');
			}else if(cons_indx == 54){
				$("#"+tdid).append('<select name="kitchen_design_loaded_on_gc" id="kitchen_design_loaded_on_gc_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="Date">Date</option><option value="REQ">REQ</option></select>');
			}else if(cons_indx == 55){
				$("#"+tdid).append('<select name="developer_colour_sheet_created" id="developer_colour_sheet_created_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="Date">Date</option><option value="REQ">REQ</option></select>');
			}else if(cons_indx == 56){
				$("#"+tdid).append('<select name="spec_loaded_on_gc" id="spec_loaded_on_gc_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="Date">Date</option><option value="REQ">REQ</option><option value="OVERDUE">OVERDUE</option></select>');
			}else if(cons_indx == 57){
				$("#"+tdid).append('<select name="loaded_on_intranet" id="loaded_on_intranet_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="Date">Date</option><option value="N/A">N/A</option><option value="REQ">REQ</option></select>');
			}else if(cons_indx == 58){
				$("#"+tdid).append('<select name="website" id="website_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="Date">Date</option><option value="N/A">N/A</option><option value="REQ">REQ</option></select>');
			}else if(cons_indx == 59){
				$("#"+tdid).append('<select name="render_requested" id="render_requested_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="Date">Date</option><option value="N/A">N/A</option><option value="REQ">REQ</option></select>');
			}else if(cons_indx == 60){
				$("#"+tdid).append('<select name="render_received" id="render_received_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="Date">Date</option><option value="N/A">N/A</option><option value="REQ">REQ</option></select>');
			}else if(cons_indx == 61){
				$("#"+tdid).append('<select name="brochure" id="brochure_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">--Select--</option><option value="Date">Date</option><option value="N/A">N/A</option><option value="REQ">REQ</option></select>');
			}else if(cons_indx == 3){
				$("#"+tdid).append('<input type="text" name="pim_logged" id="pim_logged_'+job_id+'" class="live_datepicker" onchange="update_child(this.id,this.name);"  value="'+tdval+'">');
			}else if(cons_indx == 6){
				$("#"+tdid).append('<input type="text" name="drafting_issue_date" id="drafting_issue_date_'+job_id+'"  onchange="update_child(this.id,this.name);"  class="live_datepicker" value="'+tdval+'">');
			}else if(cons_indx == 7){
				$("#"+tdid).append('<select id="consent_by_'+job_id+'" name="consent_by" onblur="update_child(this.id,this.name);" ><option value="">-- Select --</option>'+consent_by_value+'</select>');
			}else if(cons_indx == 8){
				$("#"+tdid).append('<select id="action_required_'+job_id+'" name="action_required" onblur="update_child(this.id,this.name);" ><option value="">Select Action</option><option value="Urgent">Urgent</option></select>');
			}else if(cons_indx == 9){
				$("#"+tdid).append('<select id="council_'+job_id+'" name="council"  onblur="update_child(this.id,this.name);" ><option value=""></option><option value="Ashburton">Ashburton</option><option value="Auckland">Auckland</option><option value="Chch">Chch</option><option value="Hurunui">Hurunui</option><option value="Selwyn">Selwyn</option><option value="Waikato">Waikato</option><option value="Waimak">Waimak</option></select>');
			}else if(cons_indx == 29){
				$("#"+tdid).append('<select id="lbp_'+job_id+'" name="lbp"  onblur="update_child(this.id,this.name);" ><option value=""></option><option value="Susan G">Susan G</option><option value="Mark B">Mark B</option><option value="Nathan V">Nathan V</option><option value="Selina A">Selina A</option><option value="Chelsea K">Chelsea K</option><option value="Jos K">Jos K</option><option value="Andy D">Andy D</option></select>');
			}else if(cons_indx == 30){
				$("#"+tdid).append('<input type="text" name="date_job_checked" id="date_job_checked_'+job_id+'"  onchange="update_child(this.id,this.name);"  class="live_datepicker" value="'+tdval+'">');
			}else if(cons_indx == 10){
				$("#"+tdid).append('<select id="bc_number_'+job_id+'"  onblur="update_child(this.id,this.name);" onchange="set_other_option(this.id,this.name,this.value);"  name="bc_number"><option value=""></option><option value="Checking">Checking</option><option value="Checked">Checked</option><option value="Other">Other</option></select>');
			}else if(cons_indx == 11){
				$("#"+tdid).append('<input type="text" name="no_units" id="no_units_'+job_id+'"  onblur="update_child(this.id,this.name);"  value="'+tdval+'">');
			}else if(cons_indx == 12){
				$("#"+tdid).append('<select id="contract_type_'+job_id+'" name="contract_type"  onblur="update_child(this.id,this.name);" ><option value=""></option><option value="BC">BC</option><option value="EQ">EQ</option><option value="HL">HL</option></select>');
			}else if(cons_indx == 13){
				$("#"+tdid).append('<select id="type_of_build_'+job_id+'" name="type_of_build"  onblur="update_child(this.id,this.name);" ><option value=""></option><option value="SH">SH</option><option value="MU">MU</option></select>');
			}else if(cons_indx == 14){
				$("#"+tdid).append('<select name="variation_pending" id="variation_pending_'+job_id+'"  onblur="update_child(this.id,this.name);" ><option value=""></option><option value="Yes">Yes</option></select>');
			}else if(cons_indx == 15){
				$("#"+tdid).append('<input type="text" id="foundation_type1_'+job_id+'" name="foundation_type"  onblur="update_child(this.id,this.name);"  value=""><select id="foundation_type2_'+job_id+'" name="foundation_type" onblur="update_child(this.id,this.name);"><option value="">Select Foundation Type</option><option value="Standard Engineered">Standard Engineered</option><option value="Standard">Standard</option><option value="Rib &amp; Shingle">Rib &amp; Shingle</option><option value="Jackable Rib &amp; Shingle">Jackable Rib &amp; Shingle</option><option value="Superslab &amp; Shingle">Superslab &amp; Shingle</option><option value="TC3 type 2B">TC3 type 2B</option></select>');
			}else if(cons_indx == 62){
				$("#"+tdid).append('<input type="text" name="resource_consent" id="resource_consent_'+job_id+'"  onblur="update_child(this.id,this.name);"  value="'+tdval+'">');
			}else if(cons_indx == 63){
				$("#"+tdid).append('<input type="text" name="rc_number" id="rc_number_'+job_id+'"  onblur="update_child(this.id,this.name);"  value="'+tdval+'">');
			}else if(cons_indx == 64){
				$("#"+tdid).append('<input type="text" name="expected_date_to_lodge_bc" id="expected_date_to_lodge_bc_'+job_id+'"  onchange="update_child(this.id,this.name);"  class="live_datepicker" value="'+tdval+'">');
			}else if(cons_indx == 16){
				$("#"+tdid).append('<input type="text" name="date_logged" id="date_logged_'+job_id+'"  onchange="update_child(this.id,this.name);"  class="live_datepicker" value="'+tdval+'">');
			}else if(cons_indx == 17){
				$("#"+tdid).append('<input type="text" name="date_issued" id="date_issued_'+job_id+'"  onchange="update_child(this.id,this.name);"  class="live_datepicker" value="'+tdval+'">');
			}else if(cons_indx == 18){
				$("#"+tdid).append('<input type="text" name="actual_date_issued" id="actual_date_issued_'+job_id+'"  onchange="update_child(this.id,this.name);"  class="live_datepicker" value="'+tdval+'">');
			}else if(cons_indx == 65){
				$("#"+tdid).append('<input type="text" name="water_connection" id="water_connection_'+job_id+'"  onblur="update_child(this.id,this.name);"  value="'+tdval+'">');
			}else if(cons_indx == 66){
				$("#"+tdid).append('<input type="text" name="vehicle_crossing" id="vehicle_crossing_'+job_id+'"  onblur="update_child(this.id,this.name);"  value="'+tdval+'">');
			}else if(cons_indx == 19){
				$("#"+tdid).append('<select id="order_site_levels_'+job_id+'" name="order_site_levels"  onblur="update_child(this.id,this.name);" ><option value=""></option><option value="N/A">N/A</option><option value="Received">Received</option><option value="Sent">Sent</option></select>');
			}else if(cons_indx == 20){
				$("#"+tdid).append('<select id="order_soil_report_'+job_id+'" name="order_soil_report"  onblur="update_child(this.id,this.name);" ><option value=""></option><option value="N/A">N/A</option><option value="Received">Received</option><option value="Sent">Sent</option></select>');
			}else if(cons_indx == 21){
				$("#"+tdid).append('<select id="septic_tank_approval_'+job_id+'" name="septic_tank_approval"  onblur="update_child(this.id,this.name);" ><option value=""></option><option value="REQ">REQ</option><option value="N/A">N/A</option><option value="SENT">SENT</option><option value="RECEIVED">RECEIVED</option></select>');
			}else if(cons_indx == 22){
				$("#"+tdid).append('<select id="dev_approval_'+job_id+'" name="dev_approval"  onblur="update_child(this.id,this.name);" ><option value="" ></option><option value="REQ">REQ</option><option value="N/A">N/A</option><option value="PRE SENT">PRE SENT</option><option value="PRE REC">PRE REC</option><option value="FULL SENT">FULL SENT</option><option value="FULL REC">FULL REC</option></select>');
			}else if(cons_indx == 32){
				$("#"+tdid).append('<select id="landscape_'+job_id+'" name="landscape"  onblur="update_child(this.id,this.name);" ><option value=""></option><option value="REQ">REQ</option><option value="N/A">N/A</option><option value="SENT">SENT</option><option value="RECEIVED">RECEIVED</option></select>');
			}else if(cons_indx == 33){
				$("#"+tdid).append('<input type="text" name="mss" id="mss_'+job_id+'"  onblur="update_child(this.id,this.name);"  value="'+tdval+'">');
			}else if(cons_indx == 23){
				$("#"+tdid).append('<select id="project_manager_'+job_id+'" name="project_manager"  onblur="update_child(this.id,this.name);" ><option value="">-- Select --</option>'+project_manager_value+'</select>');
			}else if(cons_indx == 25){
				$("#"+tdid).append('<input type="text" name="unconditional_date" id="unconditional_date_'+job_id+'"  onchange="update_child(this.id,this.name);"  class="live_datepicker" value="'+tdval+'">');
			}else if(cons_indx == 26){
				$("#"+tdid).append('<input type="text" name="handover_date" id="handover_date_'+job_id+'"  onchange="update_child(this.id,this.name);"  class="live_datepicker" value="'+tdval+'">');
			}else if(cons_indx == 27){
				$("#"+tdid).append('<select id="builder_'+job_id+'" name="builder"  onblur="update_child(this.id,this.name);" ><option value="">-- Select --</option>'+builder_value+'</select>');
			}else if(cons_indx == 28){
				$("#"+tdid).append('<select id="consent_out_but_no_builder_'+job_id+'" name="consent_out_but_no_builder"  onblur="update_child(this.id,this.name);" ><option value=""></option><option value="Allocated">Allocated</option><option value="Due">Due</option><option value="Need Builder">Need Builder</option></select>');
				$("#"+tdid+ " select option[value="+tdval+"]").attr('selected','selected');
			}else if(cons_indx == 34){
				$("#"+tdid).append('<input type="text" name="title_date" id="title_date_'+job_id+'" onchange="update_child(this.id,this.name);"  class="live_datepicker" value="'+tdval+'">');
			}else if(cons_indx == 35){
				$("#"+tdid).append('<input type="text" name="settlement_date" id="settlement_date_'+job_id+'" onchange="update_child(this.id,this.name);" value="'+tdval+'">');
			}else if(cons_indx == 67){
				$("#"+tdid).append('<select name="for_sale_sign" id="for_sale_sign_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value=""> -- Select --</option><option value="REQ">REQ</option><option value="Date">Date</option><option value="N/A">N/A</option></select>');
			}else if(cons_indx == 68){
				$("#"+tdid).append('<input type="text" name="code_of_compliance" id="code_of_compliance_'+job_id+'" onblur="update_child(this.id,this.name);"  value="'+tdval+'">');
			}else if(cons_indx == 36){
				$("#"+tdid).append('<input type="text" name="notes" id="notes_'+job_id+'" onblur="update_child(this.id,this.name);"  value="'+tdval+'">');
			}else if(cons_indx == 69){
				$("#"+tdid).append('<select name="photos_taken" id="photos_taken_'+job_id+'" onblur="update_child(this.id,this.name);" onchange="set_date_box(this.value,this.id,this.name)"><option value="">-- Select --</option><option value="REQ">REQ</option><option value="Date">Date</option><option value="N/A">N/A</option></select>');
			}else if(cons_indx == 70){
				$("#"+tdid).append('<input type="text" name="drainage_testing" id="drainage_testing_'+job_id+'" onblur="update_child(this.id,this.name);"  value="'+tdval+'">');
			}

			$("#"+tdid).addClass("open");
			$("#"+tdid).removeClass("close");
			$("#onclick_img_0").empty();
			$("#onclick_img_0").append('<img onclick="update_child_consent(201511,0)" src="http://horncastle.wclp.co.nz/cms/images/icon/edit_pass.png"></span>');
		}
		else{
			if(cons_indx == 0){
				var valu = $("#consent_id_"+job_id).val();
				var color_code = $("#consent_color_"+job_id).val();
				$("#consent_id_"+job_id).remove();
				$("#consent_color_"+job_id).remove();
				var sc = '#'+color_code;
				$("#"+tdid).delay(3000).css("background-color",sc);	
			}else if(cons_indx == 45){
				var valu = $("#pre_construction_sign_"+job_id).val();
				$("#pre_construction_sign_"+job_id).remove();	
			}else if(cons_indx == 1){
				var valu = $("#design_id_"+job_id).val();
				$("#design_id_"+job_id).remove();
			}else if(cons_indx == 46){
				var valu = $("#designer_"+job_id).val();
				$("#designer_"+job_id).remove();
			}else if(cons_indx == 2){
				var valu = $("#approval_date_"+job_id).val();
				$("#approval_date_"+job_id).remove();
			}else if(cons_indx == 47){
				var valu = $("#ok_to_release_to_marketing_"+job_id).val();
				$("#ok_to_release_to_marketing_"+job_id).remove();
			}else if(cons_indx == 48){
				var valu = $("#pricing_requested_"+job_id).val();
				$("#pricing_requested_"+job_id).remove();
			}else if(cons_indx == 49){
				var valu = $("#pricing_requested_"+job_id).val();
				$("#pricing_requested_"+job_id).remove();
			}else if(cons_indx == 37){
				var valu = $("#price_approved_date_"+job_id).val();
				$("#price_approved_date_"+job_id).remove();
			}else if(cons_indx == 50){
				var valu = $("#approved_for_sale_price_"+job_id).val();
				$("#approved_for_sale_price_"+job_id).remove();
			}else if(cons_indx == 51){
				var valu = $("#kitchen_disign_type_"+job_id).val();
				$("#kitchen_disign_type_"+job_id).remove();
			}else if(cons_indx == 52){
				var valu = $("#kitchen_disign_requested_"+job_id).val();
				$("#kitchen_disign_requested_"+job_id).remove();
			}else if(cons_indx == 53){
				var valu = $("#colours_requested_and_loaded_on_gc_"+job_id).val();
				$("#colours_requested_and_loaded_on_gc_"+job_id).remove();
			}else if(cons_indx == 54){
				var valu = $("#kitchen_design_loaded_on_gc_"+job_id).val();
				$("#kitchen_design_loaded_on_gc_"+job_id).remove();
			}else if(cons_indx == 55){
				var valu = $("#developer_colour_sheet_created_"+job_id).val();
				$("#developer_colour_sheet_created_"+job_id).remove();
			}else if(cons_indx == 56){
				var valu = $("#spec_loaded_on_gc_"+job_id).val();
				$("#spec_loaded_on_gc_"+job_id).remove();
			}else if(cons_indx == 57){
				var valu = $("#loaded_on_intranet_"+job_id).val();
				$("#loaded_on_intranet_"+job_id).remove();
			}else if(cons_indx == 58){
				var valu = $("#website_"+job_id).val();
				$("#website_"+job_id).remove();
			}else if(cons_indx == 59){
				var valu = $("#render_requested_"+job_id).val();
				$("#render_requested_"+job_id).remove();
			}else if(cons_indx == 60){
				var valu = $("#render_received_"+job_id).val();
				$("#render_received_"+job_id).remove();
			}else if(cons_indx == 61){
				var valu = $("#brochure_"+job_id).val();
				$("#brochure_"+job_id).remove();
			}else if(cons_indx == 3){
				var valu = $("#pim_logged_"+job_id).val();
				$("#pim_logged_"+job_id).remove();
			}else if(cons_indx == 6){
				var valu = $("#drafting_issue_date_"+job_id).val();
				$("#drafting_issue_date_"+job_id).remove();
			}else if(cons_indx == 7){
				var valu = $("#consent_by_"+job_id).val();
				$("#consent_by_"+job_id).remove();
			}else if(cons_indx == 8){
				var valu = $("#action_required_"+job_id).val();
				$("#action_required_"+job_id).remove();
			}else if(cons_indx == 9){
				var valu = $("#council_"+job_id).val();
				$("#council_"+job_id).remove();
			}else if(cons_indx == 29){
				var valu = $("#lbp_"+job_id).val();
				$("#lbp_"+job_id).remove();
			}else if(cons_indx == 30){
				var valu = $("#date_job_checked_"+job_id).val();
				$("#date_job_checked_"+job_id).remove();
			}else if(cons_indx == 10){
				var valu = $("#bc_number_"+job_id).val();
				$("#bc_number_"+job_id).remove();
			}else if(cons_indx == 11){
				var valu = $("#no_units_"+job_id).val();
				$("#no_units_"+job_id).remove();
			}else if(cons_indx == 12){
				var valu = $("#contract_type_"+job_id).val();
				$("#contract_type_"+job_id).remove();
			}else if(cons_indx == 13){
				var valu = $("#type_of_build_"+job_id).val();
				$("#type_of_build_"+job_id).remove();
			}else if(cons_indx == 14){
				var valu = $("#variation_pending_"+job_id).val();
				$("#variation_pending_"+job_id).remove();
			}else if(cons_indx == 15){
				var valu = $("#foundation_type1_"+job_id).val();
				if(valu == null){valu = $("#foundation_type2_"+job_id).val();}
				$("#foundation_type1_"+job_id).remove();
				$("#foundation_type2_"+job_id).remove();
			}else if(cons_indx == 62){
				var valu = $("#foundation_type_"+job_id).val();
				$("#foundation_type_"+job_id).remove();
			}else if(cons_indx == 63){
				var valu = $("#rc_number_"+job_id).val();
				$("#rc_number_"+job_id).remove();
			}else if(cons_indx == 64){
				var valu = $("#expected_date_to_lodge_bc_"+job_id).val();
				$("#expected_date_to_lodge_bc_"+job_id).remove();
			}else if(cons_indx == 16){
				var valu = $("#date_logged_"+job_id).val();
				$("#date_logged_"+job_id).remove();
			}else if(cons_indx == 17){
				var valu = $("#date_issued_"+job_id).val();
				$("#date_issued_"+job_id).remove();
			}else if(cons_indx == 18){
				var valu = $("#actual_date_issued_"+job_id).val();
				$("#actual_date_issued_"+job_id).remove();
			}else if(cons_indx == 65){
				var valu = $("#water_connection_"+job_id).val();
				$("#water_connection_"+job_id).remove();
			}else if(cons_indx == 66){
				var valu = $("#vehicle_crossing_"+job_id).val();
				$("#vehicle_crossing_"+job_id).remove();
			}else if(cons_indx == 19){
				var valu = $("#order_site_levels_"+job_id).val();
				$("#order_site_levels_"+job_id).remove();
			}else if(cons_indx == 20){
				var valu = $("#order_soil_report_"+job_id).val();
				$("#order_soil_report_"+job_id).remove();
			}else if(cons_indx == 21){
				var valu = $("#septic_tank_approval_"+job_id).val();
				$("#septic_tank_approval_"+job_id).remove();
			}else if(cons_indx == 22){
				var valu = $("#dev_approval_"+job_id).val();
				$("#dev_approval_"+job_id).remove();
			}else if(cons_indx == 32){
				var valu = $("#landscape_"+job_id).val();
				$("#landscape_"+job_id).remove();
			}else if(cons_indx == 33){
				var valu = $("#mss_"+job_id).val();
				$("#mss_"+job_id).remove();
			}else if(cons_indx == 23){
				var valu = $("#project_manager_"+job_id).val();
				$("#project_manager_"+job_id).remove();
			}else if(cons_indx == 25){
				var valu = $("#unconditional_date_"+job_id).val();
				$("#unconditional_date_"+job_id).remove();
			}else if(cons_indx == 26){
				var valu = $("#handover_date_"+job_id).val();
				$("#handover_date_"+job_id).remove();
			}else if(cons_indx == 27){
				var valu = $("#builder_"+job_id).val();
				$("#builder_"+job_id).remove();
			}else if(cons_indx == 28){
				var valu = $("#consent_out_but_no_builder_"+job_id).val();
				$("#consent_out_but_no_builder_"+job_id).remove();
			}else if(cons_indx == 34){
				var valu = $("#title_date_"+job_id).val();
				$("#title_date_"+job_id).remove();
			}else if(cons_indx == 35){
				var valu = $("#settlement_date_"+job_id).val();
				$("#settlement_date_"+job_id).remove();
			}else if(cons_indx == 67){
				var valu = $("#for_sale_sign_"+job_id).val();
				$("#for_sale_sign_"+job_id).remove();
			}else if(cons_indx == 68){
				var valu = $("#code_of_compliance_"+job_id).val();
				$("#code_of_compliance_"+job_id).remove();
			}else if(cons_indx == 36){
				var valu = $("#notes_"+job_id).val();
				$("#notes_"+job_id).remove();
			}else if(cons_indx == 70){
				var valu = $("#drainage_testing_"+job_id).val();
				$("#drainage_testing_"+job_id).remove();
			}
			$("#"+tdid).append(valu);
			$("#"+tdid).addClass("close");
			$("#"+tdid).removeClass("open");
		}
		
		
		
	});

});

function set_other_option(item_id, item_name, item_value){
	if(item_value == "Other"){
		parent_id = $("#"+item_id).parent().attr("id");
		$("#"+parent_id).append('<input type="text" name="' + item_name + '" id="' + item_id + '1" onblur="update_child(this.id,this.name)">' );
		$("#"+item_id).remove();
	}
} 

function set_date_box(value,item_id,item_name){
	if(value == "Date"){
		parent_id = $("#"+item_id).parent().attr("id");
		$("#"+parent_id).append('<input type="text" name="'+item_name+'" id="'+item_id+'1" class="live_datepicker" onchange="update_child(this.id,this.name)">' );
		$("#"+item_id).remove();
	}
} 

// Update consent function (This function is active now)

function update_child(field_id,field_name){
	var parent_id = $("#"+field_id).parent().attr("id");
	var field_value = $("#"+field_id).val();
	var cons_indx_arr = parent_id.split('_');
	var cons_indx = cons_indx_arr[0];
	var job_id = cons_indx_arr[2];

	var row_id = $("#"+parent_id).parent().attr("id"); 
	row_id_arr = row_id.split('_');
	pid = row_id_arr[2];

	var has_child = 0;
	if( $("#"+parent_id).parent().hasClass("parent") == true){
		child_row_class = 'child_'+pid;
		$("tr."+child_row_class).each(function() {
       		tr_id = $(this).find(" td:first-child").next().attr('id');
			if(tr_id != null)
			{
				if( (cons_indx != 25) && (cons_indx != 26) && (cons_indx != 67) && (cons_indx !=68) && (cons_indx != 69) )
				{
					tr_arr = tr_id.split("_");
					child_job_id = tr_arr[2];
					child_td_id = cons_indx+'_col_'+child_job_id;
					$("#"+child_td_id).empty();
					$("#"+child_td_id).append(field_value);
				}
				has_child = 1;
			}
    	});

	}

	if(cons_indx!=10){
		$("#modal").show();
		$("#fade").show();
	}
	// for Approved For Sale Price field
	if(cons_indx == 50){
		if(field_value != ''){
			$("#"+parent_id).delay(3000).css("background-color","green");
			$("#"+parent_id).delay(3000).css("color","white");
		}else{
			$("#"+parent_id).delay(3000).css("background-color","white");
		}
	}

	$.ajax({
		type:'GET',
		dataType: 'html', 
		data: { val: field_value, pid: pid}, 
		url:window.Url + 'consent/consent_update/'+field_name+'/'+job_id+'/'+has_child,
		success: function(data){
			$("#modal").hide();
			$("#fade").hide();
			if(cons_indx == 0){
				field_value = $("#consent_id_"+job_id).val();
				$("#"+parent_id).append(field_value);
				var color_code = $("#consent_color_"+job_id).val();
				var sc = '#'+color_code;
				$("#"+parent_id).delay(3000).css("background-color",sc);
				$("#consent_id_"+job_id).remove();
				$("#consent_color_"+job_id).remove();	
			}else if(cons_indx == 15){
				$("#"+field_id).parent().append(field_value);
				$("#foundation_type1_"+job_id).remove();
				$("#foundation_type2_"+job_id).remove();
			}else{
				$("#"+field_id).parent().append(field_value);
				$("#"+field_id).remove();
			}
		}  

	}); 

}


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

	//$( ".accordion-content" ).resizable();	
	
	$(".multiselectbox").multiselect({
        selectedText: "# of # selected"
    });

});


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


// onclick row select function
function selectrow(tr_id,tr_class)
{
	
	if($("#" + tr_id).hasClass( 'checked' ) == false){
		document.getElementById('delete_consent_item').href= window.Url + 'consent/consent_delete/'+tr_id;
		$("tr").removeClass("checked");
		$("#"+tr_id).addClass('checked');
		//document.getElementById(tr_id).className = 'checked';
		$('#delete_consent').css("display","block");
	}else{

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
	//alert(restable);

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
				//$tableids = $tableids.'#table'.$p.',';
				$tableid = '#table'.$p;
				?>
			
				var oTable = $('<?php echo $tableid; ?>').DataTable( 
				{			
					"sScrollY": "300px",
					"sScrollX": "100%",
					"sScrollXInner": "150%",
					"bScrollCollapse": true,
					"bPaginate": false,
			        "bFilter": false,
					"bInfo": false,
			    	"bAutoWidth": true,
					"ordering": false
					//"sDom": "Rlfrtip" // this is for column movement			
				});
			
				new $.fn.dataTable.FixedColumns( oTable );
	
			<?php
			}
		}
	?>		
	/* var tblids = '<?php echo $tableids; ?>';
	var numoftblids = tblids.length;
	var restable = tblids.substring(0, numoftblids - 1);
	var oTable = $(restable).DataTable( 
	{			
		"sScrollY": "300px",
		"sScrollX": "100%",
		"sScrollXInner": "150%",
		"bScrollCollapse": true,
		"bPaginate": false,
        "bFilter": false,
		"bInfo": false,
    	"bAutoWidth": true
		//"sDom": "Rlfrtip" // this is for column movement			
	});

	new $.fn.dataTable.FixedColumns( oTable ); */

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
		
		// 
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

			
			
		});
	
	});	
});




function Delete(){ 
    var par = $(this).parent().parent(); //tr 
    par.remove(); 
}


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

		var job_no = $('#job_' + month_id + '_' + i +" #jobnumber").html();
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

				var job_no = $('#job_' + month_id + '_' + i +" #jobnumber").html();
			
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

<style>

#fade {
    display: none;
    position:absolute;
    top: 0%;
    left: 0%;
    width: 100%;
    height: 100%;
    background-color: #ababab;
    z-index: 1001;
    -moz-opacity: 0.8;
    opacity: .70;
    filter: alpha(opacity=80);
}

#modal {
    display: none;
    position: absolute;
    top: 45%;
    left: 45%;
    width: 94px;
    height: 94px;
    padding:30px 15px 0px;
    border: 3px solid #ababab;
    box-shadow:1px 1px 10px #ababab;
    border-radius:20px;
    background-color: white;
    z-index: 1002;
    text-align:center;
    overflow: auto;
}

</style>	


	<div id="fade"></div>
	<div id="modal">
            <img id="loader" src="<?php echo base_url(); ?>images/consent_save.gif" />
	</div>

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
				<a class="black_text" id="delete_consent_item" href="#" onclick="return confirm('Are you sure you want to delete this item?');">
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
									<option <?php if(in_array('job_no',$refine)){ echo 'selected'; } ?> value="job_no">Job Number</option>
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
						$("#unconditional_date_row").hide("slow");
					}else{
						document.getElementById('no_of_apartment').style.display = 'none';
						$("#unconditional_date_row").show("slow");
					}
					
				}
				function add_apartment_jobno(job_num){
					$("#apartment_child_row").empty();
					
					for(i=1; i<=job_num;i++){
						$("#apartment_child_row").append('<div>'+i+' # Apartment Job No : <input type="text" name="child_apt_jobno_'+i+'" onkeyup="checkJnumber();"> Consent Name: <input type="text" name="child_apt_consent_name_'+i+'" /></div>');
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
							  'style'		=> 'width:17%',
							  'onblur'		=> 'add_apartment_jobno(this.value)'
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
   
				$contract_type_list = array(
				  ''  => '',
                  'BC'  => 'BC',
                  'EQ'   => 'EQ',
                  'HL' => 'HL'
                );
				$contract_type = form_label('Contract Type', 'contract_type');
				$contract_type .= form_dropdown('contract_type', $contract_type_list,'',$style);

				$council_list = array(
				  ''  => '',
                  'Ashburton'  => 'Ashburton',
                  'Auckland'    => 'Auckland',
                  'Chch'   => 'Chch',
                  'Hurunui' => 'Hurunui',
				  'Selwyn' => 'Selwyn',
				  'Waikato' => 'Waikato',
				  'Waimak' => 'Waimak'
                );
				$council = form_label('Council', 'council');
				$council .= form_dropdown('council', $council_list,'',$style);

				$type_of_build_list = array(
                  ''  => '',
                  'SH'    => 'SH',
                  'MU'   => 'MU'
                );
				$type_of_build = form_label('Type of Build', 'type_of_build');
				$type_of_build .= form_dropdown('type_of_build', $type_of_build_list,'',$style);

				$unconditional_date  = form_label('Unconditional Date ', 'unconditional_date');
				$unconditional_date .= form_input(array(
							  'name'        => 'unconditional_date',
							  'id'          => 'unconditional_date',
							  'class'       => 'live_datepicker',
							  'style'		=> 'width:97%',
							  'required'    => TRUE
				));

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
				echo '<div id="apartment_row" class="field-wrapper">'. $apt . '<div id="no_of_apartment" style="display:none">'.$no_of_apartment.'</div></div>';
				echo '<div id="apartment_child_row" class="field-wrapper"></div>';
				echo '<div id="name-wrapper" class="field-wrapper">'. $consent_name . '<div id="nusername_alert"></div></div>';
				echo '<div id="email-wrapper" class="field-wrapper">'. $months. '<div id="nemail_alert"></div></div>';
				echo '<div id="email-wrapper" class="field-wrapper">'. $years. '<div id="nemail_alert"></div></div>';
				echo '<div id="access-wrapper" class="field-wrapper">'. $contract_type . '</div>';
				echo '<div id="access-wrapper" class="field-wrapper">'. $council . '</div>';
				echo '<div id="access-wrapper" class="field-wrapper">'. $type_of_build . '</div>';
				echo '<div id="unconditional_date_row" class="field-wrapper">'. $unconditional_date . '</div>';
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

			for($p = 0; $p<count($user_permission_type); $p++){

				$nuser_permission_type[$user_permission_type[$p]->permission_id]->read_type = $user_permission_type[$p]->read_type;
				$nuser_permission_type[$user_permission_type[$p]->permission_id]->display_type = $user_permission_type[$p]->display_type;
			}

			//print_r($nuser_permission_type);


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
					
					$total_consents = $ci->consent_model->get_total_consents($month_id)->total_consent;
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

			if($report_search_value_1){
				if($consent_info){
					$tab_display = 'accordion-active';
					$tab_display1 = 'style="display: block !IMPORTANT;"';
				}
				
			}

	
			?>
			<li id="<?php echo $p; ?>" class="accordion <?php echo $tab_display; ?>">
				<div class="accordion-header">
					<h3 style="margin: 0; padding:0;">
						<div style="float:left; width:17%; color:#181818;font-size: 16px;"><?php echo $month; ?></div>
						<div style="float:right; width:83%;font-size:13px">Total jobs scheduled this month: <b><span id="total_job_<?php echo $p; ?>"><?php echo $total_consents;  ?></span></b> <span id="in_auck_chch_<?php echo $p; ?>">&nbsp;&nbsp;| &nbsp;&nbsp; Total consents IN &nbsp;&nbsp; <b>AUCK: <?php echo $total_consents_in_auck; ?> &nbsp;&nbsp; CHCH: <?php echo $total_consents_in_chch; ?></b>&nbsp;&nbsp; | &nbsp;&nbsp;  Total consents OUT: <b>AUCK: <?php echo $total_consents_out_auck; ?> &nbsp;&nbsp; CHCH: <?php echo $total_consents_out_chch; ?></b>&nbsp;&nbsp;| &nbsp;&nbsp;Total handovers: <b><?php echo $total_consents_handover; ?></b></span><!-- <span style="margin-left:10px;" id="onclick_img_<?php echo $p; ?>"></span>--><div class="accordion-icon"></div></div>
					</h3>
				</div>
				<div style="clear:both;"></div>
				<div class="accordion-content" id="consent<?php echo $p; ?>" onscroll="divScroll(this.id);" <?php echo $tab_display1; ?>>
                                    
				

				<table id="table<?php echo $p; ?>" class="consent_table tablesorter" border="0" cellspacing="0" cellpadding="0">
				<thead>
				<th >Job&nbsp;No.&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<?php if($nuser_permission_type[1]->display_type == 1){ ?>
				<th >Consent&nbsp;Name&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[45]->display_type == 1){ ?>
				<th >Pre-construction sign</th>
				<?php } if($nuser_permission_type[1]->display_type == 1){ ?>
				<th >Design&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[46]->display_type == 1){ ?>
				<th >Designer&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[2]->display_type == 1){ ?>
				<th >Design Approval Date</th>
				<?php } if($nuser_permission_type[47]->display_type == 1){ ?>
				<th >Ok to release to Marketing</th>
				<?php } if($nuser_permission_type[48]->display_type == 1){ ?>
				<th >Pricing requested</th>
				<?php } if($nuser_permission_type[49]->display_type == 1){ ?>
				<th >Pricing for approval</th>
				<?php } if($nuser_permission_type[33]->display_type == 1){ ?>
				<th >Price Approved Date</th>
				<?php } if($nuser_permission_type[50]->display_type == 1){ ?>
				<th >Approved For Sale Price</th>
				<?php } if($nuser_permission_type[51]->display_type == 1){ ?>
				<th >Kitchen Design Type</th>
				<?php } if($nuser_permission_type[52]->display_type == 1){ ?>
				<th >Kitchen Design Requested</th>
				<?php } if($nuser_permission_type[53]->display_type == 1){ ?>
				<th >Colours Requested & Loaded on GC</th>
				<?php } if($nuser_permission_type[54]->display_type == 1){ ?>
				<th >Kitchen Design Loaded on GC</th>
				<?php } if($nuser_permission_type[55]->display_type == 1){ ?>
				<th >Developer Colour Sheet Created</th>
				<?php } if($nuser_permission_type[56]->display_type == 1){ ?>
				<th>Spec Loaded on GC</th>
				<?php } if($nuser_permission_type[57]->display_type == 1){ ?>
				<th >Loaded on Intranet</th>
				<?php } if($nuser_permission_type[58]->display_type == 1){ ?>
				<th >Website&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[59]->display_type == 1){ ?>
				<th >Render Requested</th>
				<?php } if($nuser_permission_type[60]->display_type == 1){ ?>
				<th >Render Received</th>
				<?php } if($nuser_permission_type[61]->display_type == 1){ ?>
				<th >Brochure&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[3]->display_type == 1){ ?>
				<th >Pim&nbsp;&nbsp; Lodged</th>
				<?php } if($nuser_permission_type[6]->display_type == 1){ ?>
				<th >Drafting&nbsp;&nbsp;Issue Date</th>
				<?php } if($nuser_permission_type[7]->display_type == 1){ ?>
				<th >Consent&nbsp;&nbsp;by</th>
				<?php } if($nuser_permission_type[8]->display_type == 1){ ?>
				<th >Action&nbsp;&nbsp;Required</th>
				<?php } if($nuser_permission_type[9]->display_type == 1){ ?>
				<th >Council&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[29]->display_type == 1){ ?>
				<th >LBP&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[30]->display_type == 1){ ?>
				<th >Date Job Checked</th>
				<?php } if($nuser_permission_type[10]->display_type == 1){ ?>
				<th >Bc Number&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[11]->display_type == 1){ ?>
				<th >No. Units&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[12]->display_type == 1){ ?>
				<th >Contract Type&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[13]->display_type == 1){ ?>
				<th >Type of Build&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[14]->display_type == 1){ ?>
				<th >Variation Pending&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[15]->display_type == 1){ ?>
				<th >FoundationType&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[62]->display_type == 1){ ?>
				<th >Resource Consent&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[63]->display_type == 1){ ?>
				<th >RC Number&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[64]->display_type == 1){ ?>
				<th >Expected Date to Lodge BC&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[16]->display_type == 1){ ?>
				<th >Consent Lodged&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[17]->display_type == 1){ ?>
				<th >Consent Issued&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[18]->display_type == 1){ ?>
				<th >Actual Date Issued&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[18]->display_type == 1){ ?>
				<th >Days in Council&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[65]->display_type == 1){ ?>
				<th >Water Connection&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[66]->display_type == 1){ ?>
				<th >Vehicle Crossing&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[19]->display_type == 1){ ?>
				<th >Order Site Levels&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[20]->display_type == 1){ ?>
				<th >Order Soil Report&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[21]->display_type == 1){ ?>
				<th >Septic Tank Approval&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[70]->display_type == 1){ ?>
				<th >Drainage Testing&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[22]->display_type == 1){ ?>
				<th >Dev Approval&nbsp;&nbsp;&nbsp;</th>	
				<?php } if($nuser_permission_type[32]->display_type == 1){ ?>
				<th >Landscape&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[33]->display_type == 1){ ?>
				<th >MSS&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[23]->display_type == 1){ ?>
				<th >Project Manager&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[25]->display_type == 1){ ?>
				<th >Unconditional Date&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[26]->display_type == 1){ ?>
				<th >Handover Date&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[27]->display_type == 1){ ?>
				<th >Builder&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[28]->display_type == 1){ ?>
				<th >Builder Status&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[32]->display_type == 1){ ?>
				<th >For Sale Sign&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[68]->display_type == 1){ ?>
				<th >Code of Compliance&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[69]->display_type == 1){ ?>
				<th >Title Date&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[35]->display_type == 1){ ?>
				<th >Settlement Date&nbsp;&nbsp;&nbsp;</th>		
				<?php } if($nuser_permission_type[67]->display_type == 1){ ?>
				<th >Photo's Taken&nbsp;&nbsp;&nbsp;</th>
				<?php } if($nuser_permission_type[36]->display_type == 1){ ?>
				<th >Notes&nbsp;&nbsp;&nbsp;
					<div id="month_id_<?php echo $p; ?>" class="dnone"><?php echo $month_id; ?></div>
				</th>
				<?php } ?>
				
			</thead>
			
			<tbody id="<?php echo $month_id; ?>" class="tbody<?php echo $p; ?> connectedSortable">
			
				<?php 
					$n = 0;
					
					for($t = 0; $t < count($consent_info) ; $t++)
					{
						$unit_number = 1;
						$consent_child = $ci->consent_model->get_consent_info_by_monthid($month_id,$consent_info[$t]->id,$keywords,$consent_fields,$report_search_value_1,$search_start_date,$search_end_date);			
						if($consent_child){$toggle = "showchild(".$consent_info[$t]->id.",this.id);"; $toggle_class="toggle_off"; $parent_class="parent"; }else{$toggle =''; $toggle_class="";$parent_class='';}
						if($consent_child){ $unit_number = count($consent_child);}
				?>

				<tr id="consent_<?php echo $consent_info[$t]->month_id.'_'.$consent_info[$t]->id; ?>" class="<?php echo $parent_class; ?>"  onclick="selectrow(this.id,this.className);  ">
				<td id="job_<?php echo $month_id; ?>_<?php echo $n; ?>" onclick="<?php echo $toggle; ?>" ><div class="<?php echo $toggle_class; ?>" style="float:left;margin-right:5px" ></div><div id="jobnumber" style="float:left"><?php echo $consent_info[$t]->job_no; ?></div></td>

				<?php 	
				if($nuser_permission_type[1]->display_type == 1)
				{ ?>
				<td class="close" id="0_col_<?php echo $consent_info[$t]->job_no; ?>" style="height:30px; background-color:#<?php if($consent_info[$t]->unconditional_date != '0000-00-00'){echo "white;color:red;";}else if($consent_info[$t]->consent_color=='72D660'){ echo '90ee90'; }else{ echo $consent_info[$t]->consent_color;}  ?>">
					
					<?php 	echo $consent_info[$t]->consent_name; ?>	
				</td>
				<?php 	} ?>

				<?php if($nuser_permission_type[45]->display_type == 1){ 
					if($consent_info[$t]->pre_construction_sign ==  'REQ'){ 
						$style = "background-color:red; color:white";
					}elseif($consent_info[$t]->pre_construction_sign == "N/A"){
						$style = "background-color:green; color:white";
					}elseif($consent_info[$t]->pre_construction_sign ==''){
						$style = "background-color:white;";
					}else{
						$style = "background-color:green; color:white";
					}
				?>
				<td class="close"  id="45_col_<?php echo $consent_info[$t]->job_no; ?>" style="<?php echo $style; ?>">	
					<?php 	echo $consent_info[$t]->pre_construction_sign; ?>		
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[1]->display_type == 1)
				{ 
					$design_bg = '';
					if($consent_info[$t]->design == 'REQ Brief'){$design_bg = 'yellow';}
					if($consent_info[$t]->design == 'Brief'){$design_bg = 'blue;color:white;';}
					if($consent_info[$t]->design == 'Hold'){$design_bg = 'red;color:white;';}
					if($consent_info[$t]->design == 'Sign'){$design_bg = 'orange';}
					if($consent_info[$t]->design == 'Consent'){$design_bg = '#90ee90';}
				?>
				<td class="close" id="1_col_<?php echo $consent_info[$t]->job_no; ?>" style="background-color:<?php echo $design_bg;?>">
					<?php 	echo $consent_info[$t]->design; ?>
				</td> 
				<?php } ?>

				<?php if($nuser_permission_type[46]->display_type == 1){ ?>
				<td class="close" id="46_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	echo $consent_info[$t]->designer; ?>		
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[2]->display_type == 1)
				{ 
				?>
				<td class="close" id="2_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php if($consent_info[$t]->approval_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->approval_date); } ?>
				</td>
				<?php 	} ?>

				<?php if($nuser_permission_type[47]->display_type == 1){ ?>
				<td class="close" id="47_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	echo $consent_info[$t]->ok_to_release_to_marketing; ?> 		
				</td>
				<?php 	} ?>

				<?php if($nuser_permission_type[48]->display_type == 1){ ?>
				<td class="close" id="48_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
				<?php 
					$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
					if($consent_info[$t]->pricing_requested == ''){
						echo "";
					}
					elseif($consent_info[$t]->approval_date!='0000-00-00' && $consent_info[$t]->contract_type == 'HL'){
						echo "REQ";
						$ci->consent_model->send_consent_mail($consent_info[$t]->id,'pricing_requested',$consent_info[$t]->job_no,$consent_info[$t]->consent_name);	
					}elseif($consent_info[$t]->approval_date < $twenty_days_before ){
						echo "OVERDUE";
					}else{
						echo $consent_info[$t]->pricing_requested; 
					}
				?>		
				</td>
				<?php 	} ?>

				<?php if($nuser_permission_type[49]->display_type == 1){ ?>
				<td class="close" id="49_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	
							$five_days_before = date('Y-m-d', strtotime('-5 days'));
							if($consent_info[$t]->pricing_for_approval == ''){
								echo "";
							}
							elseif($consent_info[$t]->approval_date!='0000-00-00' && $consent_info[$t]->contract_type == 'HL'){
								echo "DUE";
							}elseif($consent_info[$t]->pricing_requested < $five_days_before ){
								echo "OVERDUE";
							}
					?>	
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[33]->display_type == 1)
				{ 
				?>
				<td class="close" id="37_col_<?php echo $consent_info[$t]->job_no; ?>" style=" <?php if($consent_info[$t]->price_approved_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>" >
					<?php if($consent_info[$t]->price_approved_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->price_approved_date); } ?>
				</td>
				<?php 	} ?>

				<?php if($nuser_permission_type[50]->display_type == 1){ ?>
				<td class="close" id="50_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px; background-color:<?php if($consent_info[$t]->approved_for_sale_price!='') {?> green; color:white;<?php } ?>">	
					<?php 	echo $consent_info[$t]->approved_for_sale_price; ?>		
				</td>
				<?php 	} ?>

				<?php if($nuser_permission_type[51]->display_type == 1){ ?>
				<td class="close" id="51_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	echo $consent_info[$t]->kitchen_disign_type; ?>		
				</td>
				<?php 	} ?>

				<?php if($nuser_permission_type[52]->display_type == 1){ ?>
				<td class="close" id="52_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	
						$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
						$pricing_for_approval_date = $this->wbs_helper->change_cms_date($consent_info[$t]->pricing_for_approval);

						preg_match('/(\d{2})-(\d{2})-(\d{2})/',$consent_info[$t]->pricing_for_approval,$matches);
						if($consent_info[$t]->kitchen_disign_requested == ''){
							echo "";
						}elseif($matches){ 
							if( $consent_info[$t]->ok_to_release_to_marketing == 'Yes'){
								echo "REQ";
								$ci->consent_model->send_consent_mail($consent_info[$t]->id,'kitchen_disign_requested',$consent_info[$t]->job_no,$consent_info[$t]->consent_name);	
							}
						}elseif($twenty_days_before > $consent_info[$t]->approval_date){
							echo "OVERDUE"; 
						}else{
							echo $consent_info[$t]->kitchen_disign_requested;
						}
					?>	
				</td>
				<?php 	} ?>
				
				<?php if($nuser_permission_type[53]->display_type == 1){ ?>
				<td class="close" id="53_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	
						$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
						$pricing_for_approval_date = $this->wbs_helper->change_cms_date($consent_info[$t]->pricing_for_approval);

						preg_match('/(\d{2})-(\d{2})-(\d{2})/',$consent_info[$t]->pricing_for_approval,$matches);
						if($consent_info[$t]->colours_requested_and_loaded_on_gc == ''){
							echo "";
						}elseif($matches){ 
							if( $consent_info[$t]->ok_to_release_to_marketing == 'Yes'){
								echo "REQ";
								$ci->consent_model->send_consent_mail($consent_info[$t]->id,'colours_requested_and_loaded_on_gc',$consent_info[$t]->job_no,$consent_info[$t]->consent_name);	
							}
						}elseif($twenty_days_before > $consent_info[$t]->approval_date){
							echo "OVERDUE"; 
						}else{
							echo $consent_info[$t]->colours_requested_and_loaded_on_gc;
						}
					?>		
				</td>
				<?php 	} ?>
				
				<?php if($nuser_permission_type[54]->display_type == 1){ ?>
				<td class="close" id="54_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	
						if($consent_info[$t]->kitchen_disign_requested == 'REQ'){
							echo "REQ";
						}else{
							echo $consent_info[$t]->kitchen_design_loaded_on_gc; 
						}
					?>		
				</td>
				<?php 	} ?>
				
				<?php if($nuser_permission_type[55]->display_type == 1){ ?>
				<td class="close" id="55_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	
						$ten_days_before = date('Y-m-d', strtotime('-10 days'));
						$colours_requested_and_loaded_on_gc_date = $this->wbs_helper->change_cms_date($consent_info[$t]->colours_requested_and_loaded_on_gc);

						preg_match('/(\d{2})-(\d{2})-(\d{2})/',$consent_info[$t]->colours_requested_and_loaded_on_gc,$matches);
						if($matches){ 
							if( $ten_days_before > $colours_requested_and_loaded_on_gc_date){
								echo "OVERDUE";
							}else{
								echo "REQ";
							}
						}else{
							echo $consent_info[$t]->developer_colour_sheet_created; 
						}
					?> 		
				</td>
				<?php 	} ?>
				
				<?php if($nuser_permission_type[56]->display_type == 1){ ?>
				<td class="close" id="56_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 
						$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
						if($consent_info[$t]->price_approved_date!='0000-00-00'){
							if($consent_info[$t]->contract_type == 'HL' & $consent_info[$t]->price_approved_date > $twenty_days_before){
								echo "REQ";
							}
							elseif($consent_info[$t]->price_approved_date < $twenty_days_before ){
									echo "OVERDUE";
							}	
						}else{
							echo $consent_info[$t]->spec_loaded_on_gc; 
						}

						if($consent_info[$t]->council == 'Auckland' or $consent_info[$t]->council == 'Waikato'){
							$ci->consent_model->send_consent_mail($consent_info[$t]->id,'spec_loaded_on_gc',$consent_info[$t]->job_no,$consent_info[$t]->consent_name);
						}
					?>		
				</td>
				<?php 	} ?>
				
				<?php if($nuser_permission_type[57]->display_type == 1){ ?>
				<td class="close" id="57_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	
						$ten_days_before = date('Y-m-d', strtotime('-10 days'));
						if($consent_info[$t]->price_approved_date!='0000-00-00'){
							if( $ten_days_before > $consent_info[$t]->price_approved_date){
								echo "OVERDUE";
							}else{
								echo "REQ";
							}
						}else{
							echo $consent_info[$t]->loaded_on_intranet; 

						}
					?>		
				</td>
				<?php 	} ?>
				
				<?php if($nuser_permission_type[58]->display_type == 1){ ?>
				<td class="close" id="58_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 
						$ten_days_before = date('Y-m-d', strtotime('-10 days'));
						if($consent_info[$t]->price_approved_date!='0000-00-00'){
							if( $ten_days_before > $consent_info[$t]->price_approved_date){
								echo "OVERDUE";
							}else{
								echo "REQ";
							}
						}else{
							echo $consent_info[$t]->website; 

						}
					?>	
				</td>
				<?php 	} ?>
				
				<?php if($nuser_permission_type[59]->display_type == 1){ ?>
				<td class="close" id="59_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php
						preg_match('/(\d{2})-(\d{2})-(\d{2})/',$consent_info[$t]->colours_requested_and_loaded_on_gc,$matches);
						if($matches){ 
							echo "REQ";	
						}else{
							echo $consent_info[$t]->render_requested; 
						} 
					?>	
				</td>
				<?php 	} ?>
				
				<?php if($nuser_permission_type[60]->display_type == 1){ ?>
				<td class="close" id="60_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	
						$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
						$render_requested_date = $this->wbs_helper->change_cms_date($consent_info[$t]->render_requested);

						preg_match('/(\d{2})-(\d{2})-(\d{2})/',$consent_info[$t]->render_requested,$matches);
						if($matches){ 
							if( $twenty_days_before > $render_requested_date){
								echo "OVERDUE";
							}else{
								echo "REQ";
							}
						}else{
							echo $consent_info[$t]->render_received; 
						}
					?> 		
				</td>
				<?php 	} ?>

				<?php if($nuser_permission_type[61]->display_type == 1){ ?>
				<td class="close" id="61_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	
						$ten_days_before = date('Y-m-d', strtotime('-10 days'));
						$render_received_date = $this->wbs_helper->change_cms_date($consent_info[$t]->render_received);

						preg_match('/(\d{2})-(\d{2})-(\d{2})/',$consent_info[$t]->render_received,$matches);
						if($matches){ 
							if( $ten_days_before > $render_received_date){
								echo "OVERDUE";
							}else{
								echo "REQ";
							}
						}else{
							echo $consent_info[$t]->brochure; 
						}

					?> 		
				</td>
				<?php 	} ?>			
				<?php 	
					if($nuser_permission_type[3]->display_type == 1){ 
				?>
				<td class="close" id="3_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php if($consent_info[$t]->pim_logged != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->pim_logged); } ?>
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[6]->display_type == 1){ 
				?>
				<td class="close" id="6_col_<?php echo $consent_info[$t]->job_no; ?>" style=" <?php if($consent_info[$t]->drafting_issue_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
					<?php if($consent_info[$t]->drafting_issue_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->drafting_issue_date); } ?>
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[7]->display_type == 1)
				{ 
				?>
				<td class="close" id="7_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php 	echo $consent_info[$t]->consent_by; ?>
				</td>
				<?php 	} ?>

				<?php 	
					if($nuser_permission_type[8]->display_type == 1)
							{ $txt = '';
				?>
				<td class="close" id="8_col_<?php echo $consent_info[$t]->job_no; ?>" style=" background-color:
				<?php
					$fourteen_days_before = date('Y-m-d', strtotime('-14 days'));
					$thirty_days_before = date('Y-m-d', strtotime('-30 days'));
					$twenty_days_before = date('Y-m-d', strtotime('-20 days'));
					if( $consent_info[$t]->unconditional_date!='0000-00-00' && $consent_info[$t]->unconditional_date < $fourteen_days_before && $consent_info[$t]->drafting_issue_date == '0000-00-00'){echo "red; color:white;"; $txt = 'Issue For Consent'; }
					if( $consent_info[$t]->drafting_issue_date < $thirty_days_before && $consent_info[$t]->drafting_issue_date != '0000-00-00'){echo "red; color:white;"; $txt = 'Drawings Late'; }
					if( $consent_info[$t]->date_logged != '0000-00-00' && $consent_info[$t]->date_logged  < $twenty_days_before ){echo "red; color:white;"; $txt = 'Consent Late';}
				?>">	
				
					<?php echo $txt; ?>
				
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[9]->display_type == 1)
				{ 
				?>
				<td class="close" id="9_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php echo $consent_info[$t]->council; ?>
				</td>
				<?php 	} ?>
				
				
				<?php 	
				if($nuser_permission_type[29]->display_type == 1)
				{ ?>
				<td class="close" id="29_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php echo $consent_info[$t]->lbp; ?>				
				</td>
				<?php } ?>
				
				
				<?php 	
					if($nuser_permission_type[30]->display_type == 1)
					{ 
				?>
				<td class="close" id="30_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php if($consent_info[$t]->date_job_checked != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->date_job_checked); } ?>
				</td>
				<?php 	} ?>
				
				<?php 	
				if($nuser_permission_type[10]->display_type == 1)
				{ 	
				?>
				<td class="close" id="10_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php 	echo $consent_info[$t]->bc_number; ?>
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[11]->display_type == 1)
				{ 
				?>
				<td class="close" id="11_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php echo $unit_number; 	//echo $consent_info[$t]->no_units; ?> 					
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[12]->display_type == 1)
				{ 
				?>
				<td class="close" id="12_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php echo $consent_info[$t]->contract_type; ?> 
				</td>
				<?php 	} ?>
			
				<?php 	
				if($nuser_permission_type[13]->display_type == 1)
				{ 
				?>
				<td class="close" id="13_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php echo $consent_info[$t]->type_of_build; ?>		
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[14]->display_type == 1)
				{ 
				?>
				<td class="close" id="14_col_<?php echo $consent_info[$t]->job_no; ?>" style=" <?php if($consent_info[$t]->variation_pending=='Yes'){ ?>background-color:red; color:white;<?php } ?> ">
					<?php 	echo $consent_info[$t]->variation_pending; ?>
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[15]->display_type == 1)
				{ 
				?>
				<td class="close" id="15_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php echo $consent_info[$t]->foundation_type; ?>
				</td>
				<?php 	} ?>

				<?php if($nuser_permission_type[62]->display_type == 1){ ?>
				<td class="close" id="62_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	echo $consent_info[$t]->resource_consent; ?> 		
				</td>
				<?php 	} ?>
				
				<?php if($nuser_permission_type[63]->display_type == 1){ ?>
				<td class="close" id="63_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	echo $consent_info[$t]->rc_number; ?>
				</td>
				<?php 	} ?>
				
				<?php if($nuser_permission_type[64]->display_type == 1){ ?>
				<td class="close" id="64_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php if($consent_info[$t]->expected_date_to_lodge_bc != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->expected_date_to_lodge_bc); } ?>		
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[16]->display_type == 1)
				{ 
				?>
				<td class="close" id="16_col_<?php echo $consent_info[$t]->job_no; ?>" style=" <?php if($consent_info[$t]->date_logged != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
					<?php 	if($consent_info[$t]->date_logged != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->date_logged); } ?>
				</td>
				<?php 	} ?>
	
				<?php 	
				if($nuser_permission_type[17]->display_type == 1)
				{ 
				?>
				<td class="close" id="17_col_<?php echo $consent_info[$t]->job_no; ?>" style="<?php if($consent_info[$t]->date_issued != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
					<?php 	if($consent_info[$t]->date_issued != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->date_issued); } ?>		
				</td>
				<?php 	} ?>

				<?php 	
					if($nuser_permission_type[18]->display_type == 1)
					{ 
				?>
				<td class="close" id="18_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php 	if($consent_info[$t]->actual_date_issued != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->actual_date_issued); } ?>		
				</td>
				<?php 	} ?>
				
				<?php 
					if($nuser_permission_type[18]->display_type == 1)
					{ ?>
					<td class="close" >
						<?php
						if($consent_info[$t]->date_logged != '0000-00-00' && $consent_info[$t]->date_issued != '0000-00-00'){ echo $days_in_council = $ci->consent_model->get_working_days($consent_info[$t]->date_logged, $consent_info[$t]->date_issued) - 1; }else if($consent_info[$t]->date_logged != '0000-00-00' && $consent_info[$t]->date_issued == '0000-00-00'){ echo $days_in_council = $ci->consent_model->get_working_days($consent_info[$t]->date_logged, date('Y-m-d')) - 1; }else{ echo '0'; }

						?>					
					</td>
				<?php 	} ?>

				<?php if($nuser_permission_type[65]->display_type == 1){ ?>
				<td class="close" id="65_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	echo $consent_info[$t]->water_connection; ?>		
				</td>
				<?php 	} ?>
				
				<?php if($nuser_permission_type[66]->display_type == 1){ ?>
				<td class="close" id="66_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	echo $consent_info[$t]->vehicle_crossing; ?>		
				</td>
				<?php 	} ?>
					
				<?php 	
				if($nuser_permission_type[19]->display_type == 1)
				{ 
				?>
				<td class="close" id="19_col_<?php echo $consent_info[$t]->job_no; ?>" style=" background-color:
				<?php 	if($consent_info[$t]->order_site_levels == '') {echo "#FFFFFF";} 
						if($consent_info[$t]->order_site_levels == 'Received') {echo "#90ee90";}
						if($consent_info[$t]->order_site_levels == 'Sent') {echo "#70B5FF";}
				?>">
					<?php echo $consent_info[$t]->order_site_levels; ?>
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[20]->display_type == 1)
				{ 
				?>
				<td class="close" id="20_col_<?php echo $consent_info[$t]->job_no; ?>" style=" background-color:
				<?php 	if($consent_info[$t]->order_soil_report == 'N/A') {echo "#FFFFFF";} 
						if($consent_info[$t]->order_soil_report == 'Received') {echo "#90ee90";}
						if($consent_info[$t]->order_soil_report == 'Sent') {echo "#70B5FF";}
				?>">
					<?php echo $consent_info[$t]->order_soil_report; ?>
		
				</td>
				<?php 	} ?>
			
			
				<?php 	
				if($nuser_permission_type[21]->display_type == 1)
				{ 
				?>
				<td class="close" id="21_col_<?php echo $consent_info[$t]->job_no; ?>" style=" color:white; background-color:
				<?php 	
						if($consent_info[$t]->septic_tank_approval == 'REQ') {echo "red";}
						if($consent_info[$t]->septic_tank_approval == 'N/A') {echo "#90ee90";} 
						if($consent_info[$t]->septic_tank_approval == 'SENT') {echo "blue";}
						if($consent_info[$t]->septic_tank_approval == 'RECEIVED') {echo "#90ee90";}
				?>">
					
					<?php echo $consent_info[$t]->septic_tank_approval; ?>
				
				</td>
				<?php 	} ?>



				<?php 	
				if($nuser_permission_type[70]->display_type == 1)
				{ 
				?>
				<td class="close" id="70_col_<?php echo $consent_info[$t]->job_no; ?>">
					<?php echo $consent_info[$t]->drainage_testing; ?>
				</td>
				<?php 	} ?>
				
				<?php 	
				if($nuser_permission_type[22]->display_type == 1)
				{ 
				?>
				<td class="close" id="22_col_<?php echo $consent_info[$t]->job_no; ?>" style=" color:white; background-color:
				<?php 
						if($consent_info[$t]->dev_approval == 'REQ') {echo "red";} 
						if($consent_info[$t]->dev_approval == 'N/A') {echo "#90ee90";} 
						if($consent_info[$t]->dev_approval == 'PRE SENT') {echo "blue";}
						if($consent_info[$t]->dev_approval == 'PRE REC') {echo "yellow; color:black";}
						if($consent_info[$t]->dev_approval == 'FULL SENT') {echo "blue";}
						if($consent_info[$t]->dev_approval == 'FULL REC') {echo "#90ee90";}
				?>">
				
					<?php echo $consent_info[$t]->dev_approval; ?>
					
				</td>
				<?php 	} ?>
				
				
				<?php 	
				if($nuser_permission_type[32]->display_type == 1)
				{ 
				?>
				<td class="close" id="32_col_<?php echo $consent_info[$t]->job_no; ?>" style=" color:white; background-color:
				<?php 	
						if($consent_info[$t]->landscape == 'REQ') {echo "red";}
						if($consent_info[$t]->landscape == 'N/A') {echo "#90ee90";} 
						if($consent_info[$t]->landscape == 'SENT') {echo "blue";}
						if($consent_info[$t]->landscape == 'RECEIVED') {echo "#90ee90";}
				?>">
				
					<?php echo $consent_info[$t]->landscape; ?>
					
				</td>
				<?php 	} ?>
				
				
				<?php 	
				if($nuser_permission_type[33]->display_type == 1)
				{ 
				?>
				<td class="close" id="33_col_<?php echo $consent_info[$t]->job_no; ?>" style=" color:white; background-color:
				<?php 	if($consent_info[$t]->mss == 'REQ') {echo "red";} 
						if($consent_info[$t]->mss == 'DONE') {echo "#90ee90";}
				?>">
				
					<?php echo $consent_info[$t]->mss; ?>
					
				</td>
				<?php 	} ?>
				

				<?php 	
				if($nuser_permission_type[23]->display_type == 1)
				{ 
				?>
				<td class="close" id="23_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php echo $consent_info[$t]->project_manager; ?>	
				</td>
				<?php 	} ?>
				

				<?php 	
				if($nuser_permission_type[25]->display_type == 1)
				{ 
				?>
				<td class="close" id="25_col_<?php echo $consent_info[$t]->job_no; ?>" style=" <?php if($consent_info[$t]->unconditional_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
					<?php 	if($consent_info[$t]->unconditional_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->unconditional_date); } ?>	
				</td>
				<?php 	} ?>

				<?php 	
					if($nuser_permission_type[26]->display_type == 1)
					{ 
				?>
				<td class="close" id="26_col_<?php echo $consent_info[$t]->job_no; ?>" style=" <?php if($consent_info[$t]->handover_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
					<?php if($consent_info[$t]->handover_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->handover_date); } ?>
				</td>
				<?php 	} ?>

				<?php 	
				if($nuser_permission_type[27]->display_type == 1)
				{	
				?>

				<td class="close" id="27_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php 	echo $consent_info[$t]->builder; ?>	
				</td>
				<?php } ?>
		
		
				<?php 	
				if($nuser_permission_type[28]->display_type == 1)
				{ ?>

				<td class="close" id="28_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php if($consent_info[$t]->builder == ''){echo "Need Builder";}else{echo $consent_info[$t]->consent_out_but_no_builder;} ?>				
				</td>
				<?php } ?>
				
				

				<?php if($nuser_permission_type[67]->display_type == 1){ ?>
				<td class="close" id="67_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 
	
						$ten_days_before = date('Y-m-d', strtotime('-10 days'));
						$render_received_date = $this->wbs_helper->change_cms_date($consent_info[$t]->render_received);

						preg_match('/(\d{2})-(\d{2})-(\d{2})/',$consent_info[$t]->render_received,$matches);
						if($matches){ 
							if( $ten_days_before > $render_received_date){
								echo "OVERDUE";
							}else{
								echo "REQ";
							}
						}else{
							echo $consent_info[$t]->for_sale_sign; 
						}

					?>		
				</td>
				<?php 	} ?>

				<?php if($nuser_permission_type[68]->display_type == 1){ ?>
				<td class="close" id="68_col_<?php echo $consent_info[$t]->job_no; ?>" style=" height:30px;">	
					<?php 	echo $consent_info[$t]->code_of_compliance; ?>		
				</td>
				<?php 	} ?>


				<?php 	
					if($nuser_permission_type[32]->display_type == 1)
					{ 
				?>
				<td class="close" id="34_col_<?php echo $consent_info[$t]->job_no; ?>" >
					<?php if($consent_info[$t]->title_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->title_date); } ?>
				</td>
				<?php 	} ?>
				
				
				<?php 	
					if($nuser_permission_type[35]->display_type == 1)
					{ 
				?>
				<td class="close" id="35_col_<?php echo $consent_info[$t]->job_no; ?>" style=" <?php if($consent_info[$t]->settlement_date != '0000-00-00'){ ?> background-color:#90ee90; color:white; <?php } ?>">
					<?php if($consent_info[$t]->settlement_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_info[$t]->settlement_date); } ?>
				</td>
				<?php 	} ?>
				

				<?php if($nuser_permission_type[69]->display_type == 1){ ?>
				<td class="close" id="69_col_<?php echo $consent_info[$t]->job_no; ?>" class="child close" style=" height:30px;">	
					<?php if($consent_info[$t]->handover_date != '0000-00-00'){ echo "REQ"; }else{ echo $consent_info[$t]->photos_taken; } ?>		
				</td>
				<?php 	} ?>
				
				<?php 	
				if($nuser_permission_type[36]->display_type == 1)
				{ 
				?>
				<td class="close notes" id="36_col_<?php echo $consent_info[$t]->job_no; ?>" class="child close" style="color:blue;">
					<p title="<?php echo $consent_info[$t]->notes; ?>" class="masterTooltip"><?php if($consent_info[$t]->notes!=''){ echo substr($consent_info[$t]->notes,0,20)."...."; } ?></p>
				</td>
				<?php 	} ?>
				
			</tr>



				<?php 

					if($consent_child)
					{
						for($c=0; $c < count($consent_child); $c++)
						{  
							$n++; 
							$colstr = $consent_child[$c]->consent_color;
							if(strlen($colstr) == 6){$consent_color = "#".$colstr;}else{$consent_color = $consent_child[$c]->consent_color;}	
						  ?>
							<tr id="consent_<?php echo $consent_info[$t]->month_id.'_'.$consent_child[$c]->id; ?>" class="child_<?php echo $consent_info[$t]->id; ?>" style="display:none;" onclick="selectrow(this.id,this.className);  ">
								<td id="job_<?php echo $month_id; ?>_<?php echo $n; ?>" class="child_td"><span id="jobnumber"><?php echo $consent_child[$c]->job_no;  ?></span></td>
								<?php if($nuser_permission_type[1]->display_type == 1){ ?>
								<td id="0_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close" style="background-color:<?php echo $consent_color; ?>"> <?php echo $consent_child[$c]->consent_name;  ?></td>
								<?php } if($nuser_permission_type[45]->display_type == 1){ ?>
								<td id="45_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->pre_construction_sign;  ?></td>
								<?php } if($nuser_permission_type[1]->display_type == 1){ ?>
								<td id="1_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->design;  ?></td>
								<?php } if($nuser_permission_type[46]->display_type == 1){ ?>
								<td id="46_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->designer;  ?></td>
								<?php } if($nuser_permission_type[2]->display_type == 1){ ?>
								<td id="2_col_<?php echo $consent_child[$c]->job_no; ?>"  class="child close"><?php if($consent_child[$c]->approval_date!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->approval_date); }  ?></td>
								<?php } if($nuser_permission_type[47]->display_type == 1){ ?>
								<td id="47_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->ok_to_release_to_marketing;  ?></td>
								<?php } if($nuser_permission_type[48]->display_type == 1){ ?>
								<td id="48_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->pricing_requested;  ?></td>
								<?php } if($nuser_permission_type[49]->display_type == 1){ ?>
								<td id="49_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->pricing_for_approval;  ?></td>
								<?php } if($nuser_permission_type[33]->display_type == 1){ ?>
								<td id="37_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->price_approved_date!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->price_approved_date); } ?></td>
								<?php } if($nuser_permission_type[50]->display_type == 1){ ?>
								<td id="50_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->approved_for_sale_price;  ?></td>
								<?php } if($nuser_permission_type[51]->display_type == 1){ ?>
								<td id="51_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->kitchen_disign_type;  ?></td>
								<?php } if($nuser_permission_type[52]->display_type == 1){ ?>
								<td id="52_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->kitchen_disign_requested;  ?></td>
								<?php } if($nuser_permission_type[53]->display_type == 1){ ?>
								<td id="53_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->colours_requested_and_loaded_on_gc;  ?></td>
								<?php } if($nuser_permission_type[54]->display_type == 1){ ?>
								<td id="54_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->kitchen_design_loaded_on_gc;  ?></td>
								<?php } if($nuser_permission_type[55]->display_type == 1){ ?>
								<td id="55_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->developer_colour_sheet_created;  ?></td>
								<?php } if($nuser_permission_type[56]->display_type == 1){ ?>
								<td id="56_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->spec_loaded_on_gc;  ?></td>
								<?php } if($nuser_permission_type[57]->display_type == 1){ ?>
								<td id="57_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->loaded_on_intranet;  ?></td>
								<?php } if($nuser_permission_type[58]->display_type == 1){ ?>
								<td id="58_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->website;  ?></td>
								<?php } if($nuser_permission_type[59]->display_type == 1){ ?>
								<td id="59_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->render_requested;  ?></td>
								<?php } if($nuser_permission_type[60]->display_type == 1){ ?>
								<td id="60_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->render_received;  ?></td>
								<?php } if($nuser_permission_type[61]->display_type == 1){ ?>
								<td id="61_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->brochure;  ?></td>
								<?php } if($nuser_permission_type[3]->display_type == 1){ ?>
								<td id="3_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->pim_logged!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->pim_logged); } ?></td>
								<?php } if($nuser_permission_type[6]->display_type == 1){ ?>
								<td id="6_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->drafting_issue_date!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->drafting_issue_date); } ?></td>
								<?php } if($nuser_permission_type[7]->display_type == 1){ ?>
								<td id="7_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->consent_by;  ?></td>
								<?php } if($nuser_permission_type[8]->display_type == 1){ ?>
								<td id="8_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"></td>
								<?php } if($nuser_permission_type[9]->display_type == 1){ ?>
								<td id="9_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->council;  ?></td>
								<?php } if($nuser_permission_type[29]->display_type == 1){ ?>
								<td id="29_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->lbp;  ?></td>
								<?php } if($nuser_permission_type[30]->display_type == 1){ ?>
								<td id="30_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->date_job_checked!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->date_job_checked); }  ?></td>
								<?php } if($nuser_permission_type[10]->display_type == 1){ ?>
								<td id="10_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->bc_number;  ?></td>
								<?php } if($nuser_permission_type[11]->display_type == 1){ ?>
								<td id="11_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo '1';  //$consent_child[$c]->no_units;  ?></td>
								<?php } if($nuser_permission_type[12]->display_type == 1){ ?>
								<td id="12_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->contract_type;  ?></td>
								<?php } if($nuser_permission_type[13]->display_type == 1){ ?>
								<td id="13_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->type_of_build;  ?></td>
								<?php } if($nuser_permission_type[14]->display_type == 1){ ?>
								<td id="14_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->variation_pending;  ?></td>
								<?php } if($nuser_permission_type[15]->display_type == 1){ ?>
								<td id="15_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->foundation_type;  ?></td>
								<?php } if($nuser_permission_type[62]->display_type == 1){ ?>
								<td id="62_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->resource_consent;  ?></td>
								<?php } if($nuser_permission_type[63]->display_type == 1){ ?>
								<td id="63_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->rc_number;  ?></td>
								<?php } if($nuser_permission_type[64]->display_type == 1){ ?>
								<td id="64_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->expected_date_to_lodge_bc!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->expected_date_to_lodge_bc); } ?></td>
								<?php } if($nuser_permission_type[16]->display_type == 1){ ?>
								<td id="16_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->date_logged!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->date_logged); } ?></td>
								<?php } if($nuser_permission_type[17]->display_type == 1){ ?>
								<td id="17_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->date_issued!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->date_issued); } ?></td>
								<?php } if($nuser_permission_type[18]->display_type == 1){ ?>
								<td id="18_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->actual_date_issued!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->actual_date_issued); } ?></td>
								<?php } if($nuser_permission_type[18]->display_type == 1){ ?>
								<td class="child close"></td>
								<?php } if($nuser_permission_type[65]->display_type == 1){ ?>
								<td id="65_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->water_connection;  ?></td>
								<?php } if($nuser_permission_type[66]->display_type == 1){ ?>
								<td id="66_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->vehicle_crossing;  ?></td>
								<?php } if($nuser_permission_type[19]->display_type == 1){ ?>
								<td id="19_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->order_site_levels;  ?></td>
								<?php } if($nuser_permission_type[20]->display_type == 1){ ?>
								<td id="20_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->order_soil_report;  ?></td>
								<?php } if($nuser_permission_type[21]->display_type == 1){ ?>
								<td id="21_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->septic_tank_approval;  ?></td>
								<?php } if($nuser_permission_type[70]->display_type == 1){ ?>
								<td id="21_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->drainage_testing;  ?></td>
								<?php } if($nuser_permission_type[22]->display_type == 1){ ?>
								<td id="22_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->dev_approval;  ?></td>
								<?php } if($nuser_permission_type[32]->display_type == 1){ ?>
								<td id="32_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->landscape;  ?></td>
								<?php } if($nuser_permission_type[33]->display_type == 1){ ?>
								<td id="33_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->mss;  ?></td>
								<?php } if($nuser_permission_type[23]->display_type == 1){ ?>
								<td id="23_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->project_manager;  ?></td>
								<?php } if($nuser_permission_type[25]->display_type == 1){ ?>
								<td id="25_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->unconditional_date!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->unconditional_date); }  ?></td>
								<?php } if($nuser_permission_type[26]->display_type == 1){ ?>
								<td id="26_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->handover_date!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->handover_date); }  ?></td>
								<?php } if($nuser_permission_type[27]->display_type == 1){ ?>
								<td id="27_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->builder;  ?></td>
								<?php } if($nuser_permission_type[28]->display_type == 1){ ?>
								<td id="28_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->consent_out_but_no_builder;  ?></td>	
								<?php } if($nuser_permission_type[67]->display_type == 1){ ?>
								<td id="67_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->for_sale_sign;  ?></td>
								<?php } if($nuser_permission_type[32]->display_type == 1){ ?>
								<td id="34_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->title_date!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->title_date); } ?></td>
								<?php } if($nuser_permission_type[35]->display_type == 1){ ?>
								<td id="35_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php if($consent_child[$c]->settlement_date!='0000-00-00'){ echo $this->wbs_helper->to_report_date($consent_child[$c]->settlement_date); }  ?></td>
								<?php } if($nuser_permission_type[68]->display_type == 1){ ?>
								<td id="68_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->code_of_compliance;  ?></td>
								<?php } if($nuser_permission_type[69]->display_type == 1){ ?>
								<td id="69_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close"><?php echo $consent_child[$c]->photos_taken;  ?></td>
								<?php } if($nuser_permission_type[36]->display_type == 1){ ?>
								<td id="36_col_<?php echo $consent_child[$c]->job_no; ?>" class="child close" style="color:blue;"><?php echo $consent_child[$c]->notes;  ?></td>
								<?php } ?>
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
<!-- <script type="text/javascript" src="<?php echo base_url();?>js/jquery.tablesorter.js" /></script>
<script type="text/javascript" src="<?php echo base_url();?>js/colResizable-1.5.min.js"></script> -->
<script>
<?php 
			//$tableids = '';
			//for($p=$s_month; $p >= $e_month; $p--)
			//{
			//	$tableids = $tableids.'#table'.$p.', ';
			//}
		?>
	
		//var tblids = '<?php echo $tableids; ?>';
		//var numoftblids = tblids.length;
    	//var restable = tblids.substring(0, numoftblids - 2);

		//$(restable).colResizable({
			//liveDrag:true, 
			//});

	//$('table').tablesorter();
// using a flag that prevents recursion - repeatedly calling this same function, because it
// will trigger the "sortEnd" event after sorting the other tables.
/*var recursionFlag = false;
$("table").bind("sortEnd",function(e, table) {

    if (!recursionFlag) {
        recursionFlag = true;
        $('table').not(this).trigger("sorton", [ table.config.sortList ]);
        setTimeout(function(){ recursionFlag = false; }, 100);
    }
});*/

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

    // show parent child consents 
	function showchild(job_id,tdid){
		if($(".child_"+job_id).css("display") == 'table-row'){
			$(".child_"+job_id).css("display","none");
			$("#"+tdid+" div:first-child").removeClass("toggle_on");
			$("#"+tdid+" div:first-child").addClass("toggle_off");
		}else{
			$(".child_"+job_id).css("display","table-row");
			$("#"+tdid+" div:first-child").removeClass("toggle_off");
			$("#"+tdid+" div:first-child").addClass("toggle_on");
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

		if(consent_fields_id=='job_no' || consent_fields_id=='consent_name' || consent_fields_id=='no_units' || consent_fields_id=='notes'){
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
				$("#refine_colunm_name").append('<div class="colunm3" id="'+consent_fields_id+'"><span>Refine '+consent_fields_text+':</span><br><select id="search_value_select" name="'+consent_fields_id+'_search_value"><option value="">-- Select --</option><option value="BC">BC</option><option value="EQ">EQ</option><option value="HL">HL</option></select></div>');
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

		text_fields = new Array('job_no,Job Number', 'consent_name,Consent', 'no_units,No. Units', 'notes,Notes');
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
		$("#refine_colunm_name").append('<div class="colunm3" id="contract_type"><span>Refine Contract Type:</span><br><select id="search_value_select" name="contract_type_search_value"><option value="">-- Select --</option><option value="BC">BC</option><option value="EQ">EQ</option><option value="HL">HL</option></select></div>');
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