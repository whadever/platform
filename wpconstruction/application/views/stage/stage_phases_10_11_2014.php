<div id="stage_phase_task">
	<div id="underway_header">
		<div class="uhead" style="width:20%">Task Name</div>
		<div class="uhead" style="width:5%">Complete</div>
		<div class="uhead" style="width:15%">Planned Completion</div>
		<div class="uhead" style="width:15%">Actual Completion</div>
		<div class="uhead" style="width:18%">Days Remaining</div>
		<div class="uhead" style="width:8%">On Time</div>
	</div>
	
<script>
window.Url = "<?php print base_url(); ?>";
function change_task_status(task_id,checked)
{
	if(checked == true)
	{
		status = 1;
	}
	else
	{
		status = 0;
	}
	$.ajax({
			url: window.Url + 'stage/update_task_status/' + task_id + '/' + status,
			type: 'GET',
			success: function(data) 
			{
				location.reload();	        
			},
			        
		});

}



$(function() {
$( "#accordion" ).accordion();
});
</script>
	
	<?php
	
		$phase_number = count($phase_info);
		$development_id = $stages_no[0]->id;
	?>
	
	<div id="accordion">
		<?php 
		for($i=0; $i < $phase_number; $i++)
		{ 
			$ci =&get_instance();
			$ci->load->model('stage_model');
			$task_info = $ci->stage_model->get_development_stage_phase_task_info($development_id,$stage_id,$phase_info[$i]->id)->result();
		
			

		?>
		
			
			<h3><?php echo $phase_info[$i]->phase_name; ?> </h3>

		
			<div class="stage_content">
				<?php	for($j=0; $j< count($task_info); $j++)
					{ 
						$day_sign = '';
						$day_alert = '';
						$bg_color = 'yellow';
						$day_required = $task_info[$j]->task_length;
						$created_date = date_create($task_info[$j]->task_start_date);
						$str = $day_required.' days';
						$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
						$planned_completion_date =  date_format($pcdate, 'Y-m-d');

						$now = date('Y-m-d');

						$today_time = strtotime($now);
						$pc_time = strtotime($planned_completion_date);

						if ($today_time < $pc_time && $task_info[$j]->stage_task_status == '1' ) 
						{
							$day_sign = 'Finished ';
							$day_alert = " Days Before";
							$bg_color = 'green';
						}
						elseif ($today_time < $pc_time && $task_info[$j]->stage_task_status == '0' ) 
						{
							$day_sign = '';
							$day_alert = " Days Remaining";
							$bg_color = 'yellow';
						}
						elseif($today_time > $pc_time && $task_info[$j]->stage_task_status == '1')
						{
							$day_sign = '';
							$day_alert = " Days Over";
							$bg_color = 'green';
						}
						elseif($today_time > $pc_time && $task_info[$j]->stage_task_status == '0')
						{
							$day_sign = '';
							$day_alert = " Days Over";
							$bg_color = 'red';
						}
						elseif($today_time == $pc_time && $task_info[$j]->stage_task_status == '1')
						{
							$day_sign = '';
							$day_alert = '';
							$bg_color = 'green';
						}
						else
						{
							$day_sign = '';
							$day_alert = '';
							$bg_color = 'yellow';
						}
						

				?>
				<div class="unrow">
	
					<div class="uncol" style="width:26%"><?php if(isset($task_info[$j]->task_name)) echo $task_info[$j]->task_name; ?></div>
					<div class="uncol" style="width:8%"><input id="task_status_<?php echo $task_info[$j]->id; ?>" type="checkbox" name="task_status" <?php if( $task_info[$j]->stage_task_status == '1' ) { ?> checked="checked" <?php } ?> onclick="change_task_status(<?php echo $task_info[$j]->id; ?>,this.checked)"  /></div>
					<div class="uncol" style="width:19%"><a href="#EditDate_<?php echo $task_info[$j]->id; ?>" role="button" data-toggle="modal"><?php if(isset($task_info[$j]->created)){ echo $planned_completion_date; } ?></a></div>
					<div class="uncol" style="width:18%"><?php if(isset($task_info[$j]->task_name)) echo $task_info[$j]->actual_completion_date; ?></div>
					<div class="uncol" style="width:22%"><?php $rem_days = date_diff(date_create($planned_completion_date),date_create($now))->format("%a"); echo $day_sign.$rem_days.$day_alert;  ?></div>
					<div class="uncol" style="width:5%"><?php if(isset($task_info[$j]->task_name)){ ?><div style="height:20px; width:20px; border-radius:15px; background-color:<?php echo $bg_color; ?>"></div><?php }?></div>
					
				</div>

					<div id="EditDate_<?php echo $task_info[$j]->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_task_start_date_update/<?php echo $task_info[$j]->id; ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit Planned Start Date</h3>
								</div>
								<div class="modal-body">
									
										
									<div class="control-group">
										<label class="control-label" for="task_name">Planned Start Date </label>
										<div class="controls">
											<input type="text" id="planned_start_date" placeholder="" name="planned_start_date" value="<?php echo $this->wbs_helper->to_report_date($task_info[$j]->task_start_date); ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="inputPassword"></label>
										<div class="controls">
											<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
											<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $this->uri->segment(4); ?>">
											<div class="save">
												<input type="submit" value="Submit" name="submit" />
											</div>
										</div>
									</div>
							    
								</div>

							</form>
					</div>

					<?php } ?>
			</div>

	  
			
		<?php
		}
		?>
		
	</div>
</div>

</div>