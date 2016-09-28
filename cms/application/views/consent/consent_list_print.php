<script>
    window.print();
</script>

<style>

.report {
    border: 2px solid #0d446e;
    margin: 20px 0;
	padding-top: 10px;
}
.report-header {
    padding: 0 30px;
    text-align: right;
}
.report-logo {
    width: 240px;
}
.report-body {
    padding: 10px 30px;
}
.report-footer {
    background: #0d446e;
    color: #fff;
    padding: 10px 30px;
}
i {
    font-size: 15px;
    font-weight: bold;
}
.report-footer > p {
    font-size: 10px;
}
.report td {
    padding: 6px 10px;
	border: 1px solid #fff;
	background: #ebecec;
	width: 25%;
}
.report td.first {
	background: #d8d8da;
}
.report table tbody td:last-child {
    border-right: 1px solid #fff;
}
tr:first-child td{
	background: #85868a;
	color: #fff;
    font-weight: bold;
	border-right: 0px solid #fff;
}
</style>
<?php 

$ci = &get_instance();
$ci->load->model('consent_model');
$user_info = $ci->consent_model->user_option();
//print_r($user_info);
                               
?>

				
<div id="consent_list_print"> 
	<div class="report">
		<div class="report-header">
			<img class="report-logo" src="<?php echo base_url(); ?>images/report_logo.png" />
		</div> 
		<div class="report-body">
	<?php
	
		$total_months = 5;
		$now = date('Y-m-d');
		$today_time = strtotime($now);
		$last_month = date("F Y", strtotime("-2 months"));


		$user =  $this->session->userdata('user');   
		$user_group_id = $user->group_id;
		
		$ci = &get_instance();
		$ci->load->model('consent_model');
		$user_permission_type = $ci->consent_model->get_user_permission_type($user_group_id);

		//print_r($user_permission_type); 	

	?>
	
	

	<?php
		$segment_print = $this->uri->segment(3);

		$s_month = $this->uri->segment(4);
		$e_month = $this->uri->segment(5);

		$segment_print_arr = explode("_", $segment_print);
		
		//print_r($segment_print_arr);  
		
		for($p=$s_month; $p >= $e_month; $p--)
		{
			for($a = 0; $a < count($segment_print_arr) - 1; $a++)
			{
				if($segment_print_arr[$a] == $p )	
				{
			  	
					$month = date("F Y", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));				
					$month_start_date = date("Y-m-d", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));				
					$month_last_day = date("Y-m-t", strtotime($month_start_date));
					
					$ci = &get_instance();
					$ci->load->model('consent_model');
					//$consent_info = $ci->consent_model->get_consent_info($month_start_date,$month_last_day);

					$month_id = date("Ym", mktime(0, 0, 0, date("m")-$p, 1, date("Y")));
					$consent_info = $ci->consent_model->get_consent_info_by_monthid($month_id);

	?>
		
			
				<div style="width:100%;">
					<h3 style="height:30px; clear:both; margin: 0; padding: 0;">
						<div style="float:left; width:25%; height:25px; color:#181818;font-size: 21px;"><?php echo $month; ?></div>						
					</h3>
				</div>
				<div class="accordion-content-print">
                    <?php   
					
					for($t = 0; $t < count($consent_info) ; $t++)
					{										
						$message = '<table border="0" style="width:100%; margin-bottom:10px; ">';
										
						$message .= '<tbody>';
						
						$message .= "<tr>";
						$message .= '<td>Job Number</td><td>'.$consent_info[$t]->job_no.'</td>';
						$message .= '<td>Consent</td><td>'.$consent_info[$t]->consent_name.'</td>';
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[1]->display_type == 1)
						{
							$message .= '<td class="first">Design</td><td>'.$consent_info[$t]->design.'</td>';
						}
					
						if($user_permission_type[2]->display_type == 1)
						{
							if($consent_info[$t]->approval_date == '0000-00-00')
							{
								$message .= '<td class="first">Approval Date</td><td></td>';
							}
							else
							{
								$message .= '<td class="first">Approval Date</td><td>'.$this->wbs_helper->to_report_date($consent_info[$t]->approval_date).'</td>';
							}
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[3]->display_type == 1)
						{
							if($consent_info[$t]->pim_logged == '0000-00-00')
							{
								$message .= '<td class="first">Pim Logged</td><td></td>';
							}
							else
							{
								$message .= '<td class="first">Pim Logged</td><td>'.$this->wbs_helper->to_report_date($consent_info[$t]->pim_logged).'</td>';
							}
						}

						if($user_permission_type[4]->display_type == 1)
						{
							if($consent_info[$t]->in_council == '0000-00-00')
							{
								$message .= '<td class="first">In Council</td><td></td>';
							}
							else
							{
								$message .= '<td class="first">In Council</td><td>'.$this->wbs_helper->to_report_date($consent_info[$t]->in_council).'</td>';
							}
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[5]->display_type == 1)
						{
							if($consent_info[$t]->consent_out == '0000-00-00')
							{
								$message .= '<td class="first">Consent Out</td><td></td>';
							}
							else
							{
								$message .= '<td class="first">Consent Out</td><td>'.$this->wbs_helper->to_report_date($consent_info[$t]->consent_out).'</td>';
							}
						}

						if($user_permission_type[6]->display_type == 1)
						{
							if($consent_info[$t]->drafting_issue_date == '0000-00-00')
							{
								$message .= '<td class="first">Drafting Issue Date</td><td></td>';
							}
							else
							{
								$message .= '<td class="first">Drafting Issue Date</td><td>'.$this->wbs_helper->to_report_date($consent_info[$t]->drafting_issue_date).'</td>';
							}
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[7]->display_type == 1)
						{
							$message .= '<td class="first">Consent by</td><td>'.$consent_info[$t]->consent_by.'</td>';
						}

						if($user_permission_type[8]->display_type == 1)
						{
							$message .= '<td class="first">Action Required</td><td>'.$consent_info[$t]->action_required.'</td>';
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[9]->display_type == 1)
						{
							$message .= '<td class="first">Council</td><td>'.$consent_info[$t]->council.'</td>';
						}

						if($user_permission_type[10]->display_type == 1)
						{
							$message .= '<td class="first">Bc Number</td><td>'.$consent_info[$t]->bc_number.'</td>';
						}
						$message .= "</tr>";
					
						$message .= "<tr>";
						if($user_permission_type[11]->display_type == 1)
						{
							$message .= '<td class="first">No. Units</td><td>'.$consent_info[$t]->no_units.'</td>';
						}

						if($user_permission_type[12]->display_type == 1)
						{
							$message .= '<td class="first">Contract Type</td><td>'.$consent_info[$t]->contract_type.'</td>';
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[13]->display_type == 1)
						{
							$message .= '<td class="first">Type of Build</td><td>'.$consent_info[$t]->type_of_build.'</td>';
						}

						if($user_permission_type[14]->display_type == 1)
						{
							$message .= '<td class="first">Variation Pending</td><td>'.$consent_info[$t]->variation_pending.'</td>';
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[15]->display_type == 1)
						{
							$message .= '<td class="first">Foundation Type</td><td>'.$consent_info[$t]->foundation_type.'</td>';
						}

						if($user_permission_type[16]->display_type == 1)
						{
							if($consent_info[$t]->date_logged == '0000-00-00')
							{
								$message .= '<td class="first">Date Logged</td><td></td>';
							}
							else
							{
								$message .= '<td class="first">Date Logged</td><td>'.$this->wbs_helper->to_report_date($consent_info[$t]->date_logged).'</td>';
							}
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[17]->display_type == 1)
						{
							if($consent_info[$t]->date_issued == '0000-00-00')
							{
								$message .= '<td class="first">Date Issued</td><td></td>';
							}
							else
							{
								$message .= '<td class="first">Date Issued</td><td>'.$this->wbs_helper->to_report_date($consent_info[$t]->date_issued).'</td>';
							}
						}

						if($user_permission_type[18]->display_type == 1)
						{
							$difference = abs(strtotime($consent_info[$t]->date_issued) - strtotime($consent_info[$t]->date_logged));
							$days = floor(($difference )/ (60*60*24));
							$message .= '<td class="first">Days in Council</td><td>'.$days.'</td>';
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[19]->display_type == 1)
						{
							$message .= '<td class="first">Order Site Levels</td><td>'.$consent_info[$t]->order_site_levels.'</td>';
						}

						if($user_permission_type[20]->display_type == 1)
						{
							$message .= '<td class="first">Order Soil Report</td><td>'.$consent_info[$t]->order_soil_report.'</td>';
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[21]->display_type == 1)
						{
							$message .= '<td class="first">Septic Tank Approval</td><td>'.$consent_info[$t]->septic_tank_approval.'</td>';
						}

						if($user_permission_type[22]->display_type == 1)
						{
							$message .= '<td class="first">Dev Approval</td><td>'.$consent_info[$t]->dev_approval.'</td>';
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[23]->display_type == 1)
						{
							$message .= '<td class="first">Project Manager</td><td>'.$consent_info[$t]->project_manager.'</td>';
						}

						if($user_permission_type[24]->display_type == 1)
						{
							$message .= '<td class="first">Allocated to PM</td><td>'.$consent_info[$t]->jobs_to_be_allocated_to_PM.'</td>';
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[25]->display_type == 1)
						{
							if($consent_info[$t]->unconditional_date == '0000-00-00')
							{
								$message .= '<td class="first">Unconditional Date</td><td></td>';
							}
							else
							{
								$message .= '<td class="first">Unconditional Date</td><td>'.$this->wbs_helper->to_report_date($consent_info[$t]->unconditional_date).'</td>';
							}
						}

						if($user_permission_type[26]->display_type == 1)
						{
							if($consent_info[$t]->handover_date == '0000-00-00')
							{
								$message .= '<td class="first">Handover Date</td><td></td>';
							}
							else
							{
								$message .= '<td class="first">Handover Date</td><td>'.$this->wbs_helper->to_report_date($consent_info[$t]->handover_date).'</td>';
							}
						}
						$message .= "</tr>";
						
						$message .= "<tr>";
						if($user_permission_type[27]->display_type == 1)
						{
							$message .= '<td class="first">Builder</td><td>'.$consent_info[$t]->builder.'</td>';
						}

						if($user_permission_type[28]->display_type == 1)
						{
							$message .= '<td class="first">Consent out</td><td>'.$consent_info[$t]->consent_out_but_no_builder.'</td>';
						}
						$message .= "</tr>";
						
						$message .= "</tbody>";
					
						$message .= "</table>";	

						echo $message;
							
					} // end for loop $t
					
					?>
			</div>
			<?php
					}// if
				} // for loop $a
			}// end phase for loop
			
			?>
     	</div>  
		<div class="report-footer">
			<i>We call Canterbury home</i>
			<p>38 Lowe St, Addington, PO Box 8255, Riccarton, Christchurch, New Zealand. Ph: (03) 348 8905 0800 NEW HOME <br>info@horncastle.co.nz <strong>www.horncastle.co.nz</strong> Proud to be Naming Partner for <strong>Horncastle Arena</strong></p>
		</div>
	</div>
</div>
