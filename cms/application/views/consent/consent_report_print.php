<script>
    window.print();
</script>

<html>
<head>

<style>
table tr th, table tr td {
    padding: 5px 5px;
    text-align: left;
}
</style>

</head>
<body>

<div id="consent_list" style="clear:both">
	<?php

	$message = '<table id="table" class="consent_table" border="1" bordercolor="#eee" cellpadding="0" cellspacing="0">';
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
	$message .= "</table>";

	echo $message;
?>

</div>
</body>
</html>