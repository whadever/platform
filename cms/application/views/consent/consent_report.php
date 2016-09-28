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

.ui-dialog {
    padding: 0;
}
.ui-widget-header {
    display: none;
}
.ui-widget-content {
    border: 3px solid #d1d2d4;
}
.ui-dialog .ui-dialog-content {
    padding: 0;
}
.modal-body {
    border-bottom: 3px solid #d1d2d4;
	font-weight: bold;
    padding: 10px 20px;
}
.modal-footer {
    padding: 10px 20px;
}
#template_name {
    border: 2px solid #d1d2d4;
    border-radius: 5px;
    margin-bottom: 10px;
    padding: 5px;
    width: 97%;
}
#add_template .search_button {
    width: 110px;
}
.existing-template {
    margin-bottom: 10px;
}
select#existing_template {
    border: 1px solid #d1d2d4;
    width: 74%;
}
.ui-widget-header.ui-multiselect-header{
	display: block;
	font-size: 13px;
}
.ui-widget-header.ui-datepicker-header{
	display: block;
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
.consent_table {
    width: 100% !IMPORTANT; 
}
</style>

<?php
	$ci = &get_instance();
	$ci->load->model('consent_model');
	$keywords = $this->session->userdata('report_keywords');
	$consent_fields = $this->session->userdata('report_consent_fields');
	$report_search_value_1 = $this->session->userdata('report_search_value');
	$refine = explode(',',$consent_fields);
	$start_date_1 = $this->session->userdata('report_consent_f_month');
	$end_date_1 = $this->session->userdata('report_consent_l_month');

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

	if(!empty($consent_fields)){

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

			$start_date = explode(",",$start_date_1);
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
			
			$end_date = explode(",",$end_date_1);
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
					$refine_colunm_name .= '<div class="colunm3" id="'.$consent_fields.'"><span>Refine '.$consent_fields.':</span><br><select id="search_value_select" name="'.$consent_fields.'_search_value"><option value="">-- Select --</option>
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

	echo '<style>.refine .ui-multiselect {width: 100% !important;}</style>';
	}

?>

<script>
$(function(){ 
    $(document).on('focus', ".live_datepicker", function(){
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

	function TemplateDelete(id){
		var answer = confirm("Are you sure delete this Template?");
		if (answer){
			$.ajax({				
				url: window.mbsBaseUrl + 'consent/consent_template_delete/' + id,
				type: 'POST',
				success:function(data) {
					$('.tr_'+id).remove();	  
				}  
			});	
		}
	}

jQuery(document).ready(function()
{

	$('.notify').show().fadeOut(4000);
	
	 $('.clickdiv').click(function(){
        $('.hiders').slideToggle();
        $('#minus').toggle();
        $('#plus').toggle();
    });
	$('#refine_search').multiselect();

	$('#clear_search').click(function(){
        $.ajax({				
			url: window.mbsBaseUrl + 'consent/clear_search_consent_report',
			type: 'POST',
			success: function(html) 
			{
				//console.log(data);
				newurl = window.mbsBaseUrl + 'consent/show_report';
				window.location = newurl;
			},
		        
		});
    });

	
	$('#clear_template').click(function(){
        $( '#add_template' ).dialog( "close" );
    });
    
    $('#clear_template_all').click(function(){
        $( '#template_list' ).dialog( "close" );
    });

	$("#add_template").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 160,
            modal: true
    });
    
    $("#template_list").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 400,
            modal: true
    });

	$("#template_id").click(
        		
            function () {
               	$("#add_template").dialog('open');
            }
    );
    
    $("#all_template").click(
        		
            function () {
               	$("#template_list").dialog('open');
            }
    );
    
    

	$('#template_save').click(function(){

		template_name = $("#template_name").val();
		if(template_name==''){
			alert('Please fill out this field.');
			$("#template_name").css('border','2px solid red');
		}else{
			$.ajax({				
				url: window.mbsBaseUrl + 'consent/consent_template_add?template_name=' + template_name,
				type: 'POST',
				success:function(data) {
					$("#template_id").val(data);
					$('#add_template').dialog( "close" );	  
				}  
			});	
		}	
	});

	$('#existing_template').change(function(){
		template_id = $("#existing_template").val();
		newurl = window.mbsBaseUrl + 'consent/show_report?template_id=' + template_id;
		window.location = newurl;	
	});

	$(".multiselectbox").multiselect({
        selectedText: "# of # selected"
    });

	$('ul.ui-multiselect-checkboxes.ui-helper-reset li label.ui-corner-all input').click(function(){

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




<!-- Start CMS Toolbar -->
<div style="clear:both">
	<div class="consent_toolbar" id="report_section">
		
		<div id="search_box" style="margin: 0 22%;">
			<div class="existing-template">
				<span>Select an existing template:</span>
				<select name="existing_template" id="existing_template">
					<option value="null">--Select a Template--</option>
					<?php 
					$templates = $ci->consent_model->load_template();
					foreach($templates as $template){
					?>
					<option <?php if($_GET['template_id']==$template->id){ echo 'selected'; } ?> value="<?php echo $template->id; ?>"><?php echo $template->template_name; ?></option>
					<?php
					}
					?>
				</select>
			</div>
			<div style="clear:both;"></div>
			<div class="searchbox">
				<div class="clickdiv" style="background:#EBEBEB;padding: 5px;text-align:center;">
					<strong> 
						<span> Search </span>
						<span style="<?php if($keywords=='' && $consent_fields==''){ echo 'display:inline;'; }else{ echo 'display:none;'; } ?>" id="plus">+</span>
						<span id="minus" style="<?php if($keywords=='' && $consent_fields==''){ echo 'display:none;'; }else{ echo 'display:inline;'; } ?>">-</span>
					</strong>
				</div> 
				<form action="<?php echo base_url(); ?>consent/show_report" method="post" name="searh_cms">
				<div class="hiders" style="<?php if($keywords=='' && $consent_fields==''){ echo 'display:none;'; }else{ echo 'display:block;'; } ?> border:1px solid #EBEBEB; overflow:hidden;"> 
					<div class="row">
						<div class="col-md-8" style="float:left; padding:0%;width:98%">
							<!---<span>Search:</span> <br>--->
							<input type="hidden" style="border: 1px solid #d3d3d3;border-radius: 4px;padding: 6px;width: 95%;" id="search_filter" name="search_filter" value="<?php echo $keywords; ?>" >
						</div>
					</div>	
					<div style="clear:both;"></div>
					<div class="row">
						<div class="col-md-4 refine" style="float:left;padding:1%; width:35%">
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

						<div id="refine_colunm_name" class="col-md-3" style="float:left; padding:1%;width:61%">
							<?php echo $refine_colunm_name; ?>
						</div>
					</div>
					<div style="clear:both;"></div>
					<div class="row">
						<div class="col-md-4" style="float:left;padding:1%; width:50%">
							<input type="checkbox" style="margin-right:10px;" name="template_id" id="template_id" value="0" />Tick to save this search as a template
						</div>
						
						<div class="col-md-6" style="float:left;padding:1%; width:44%; text-align:right;margin-top:5px;">
							<input type="reset" id="clear_search" class="clear_search" value="Clear Search">
							<input type="submit" name="submit" value="Search" id="search_button" class="search_button">
						</div>
					</div>
				</div>
				</form>
			</div>
		</div>
		
		
		<div class="clear"></div>
	</div>
</div>
<!-- End CMS Toolbar -->





<div align="right" style="font-weight:bold;float:right;">
	<a target="_blank" href="<?php echo base_url(); ?>consent/consent_report_print"><img width="30" src="<?php echo base_url(); ?>images/icon/btn_horncastle_printer_old.png"></a>
</div>
<div align="right" style="float:right;margin-right:10px;background: #d1d2d4;padding: 5px 10px;border-radius: 5px;">
	<a style="color: #fff;" href="javascript:void(0)" id="all_template">All Template</a>
</div>
<div align="right" style="font-weight:bold; margin-right:10%;float:right;">Total Units:<?php echo $consent_info; ?></div>

<div id="consent_list" style="clear:both">
	<ul class="accordions toggles">

			<li class="accordion">
				
				<div class="accordion-content" id="consent<?php echo $p; ?>" onscroll="divScroll(this.id);" style="overflow-x:scroll">
					<table id="table<?php echo $p; ?>" class="consent_table tablesorter" border="0">
						<?php echo $report_message; ?>
					</table>          
				</div>
			</li>
			

	</ul>
	<div class="clear"></div>
</div>

<div class="clear"></div>

<div id="add_template">
	<form action="#" method="post">
		<div class="modal-body">
			<p>Save Template</p>
		</div>
		<div class="modal-footer">
			<p>Template Name:<br><input required="" type="text" id="template_name" name="template_name" value=""/></p>
			<p style="text-align: right;"><input type="reset" id="clear_template" class="clear_search" value="Cancel">
			<input type="reset" name="submit" value="Save Template" id="template_save" class="search_button"></p>
			
			<div class="clear"></div>
		</div>
	</form>	
</div>

<div id="template_list">
	<div class="modal-body">
		<p>All Template<input style="float: right;" type="reset" id="clear_template_all" class="clear_search" value="Close"></p>
	</div>
	<div class="modal-footer">
		<table width="100%" border="0" cellpadding="5" cellspacing="0">
			<thead>
				<tr>
					<th>Template Name</th>
					<th>Action</th>
				</tr>
			</thead>
			<tbody>
			<?php 
			$templates = $ci->consent_model->load_template();
			foreach($templates as $template){
			?>
				<tr class="tr_<?php echo $template->id; ?>">
					<td><?php echo $template->template_name; ?></td>
					<td><a onclick="TemplateDelete(<?php echo $template->id; ?>)" href="javascript:void(0)">Delete</a></td>
				</tr>
			<?php
			}
			?>
				
			</tbody>
		</table>
		
		<div class="clear"></div>
	</div>
</div>