<div id="phase_underway">
	<div id="underway_header">
		<div class="uhead" style="width:25%">Task Name</div>
		<div class="uhead" style="width:5%">Complete</div>
		<div class="uhead" style="width:15%">Planned<br>Completion</div>
		<div class="uhead" style="width:15%">Actual<br>Completion</div>
		<div class="uhead" style="width:25%">Days<br> Remaining</div>
		<div class="uhead" style="width:10%">On Time</div>
	</div>
	
<script>

window.Url = "<?php print base_url(); ?>";
function change_phase_status(phase_id,checked)
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
			url: window.Url + 'developments/update_phase_status/' + phase_id + '/' + status,
			type: 'GET',
			success: function(data) 
			{
				location.reload();	        
			},
			        
		});

}


function change_development_phase_task_status(task_id,checked)
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
			url: window.Url + 'developments/update_development_phase_task_status/' + task_id + '/' + status,
			type: 'GET',
			success: function(data) 
			{
				location.reload();	        
			},
			        
		});

}


function change_all_phase_task_status(development_id,phase_id,checked)
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
			url: window.Url + 'developments/update_all_phase_task_status/'+ development_id + '/' + phase_id + '/' + status,
			type: 'GET',
			success: function(data) 
			{
				location.reload();	        
			},
			        
		});
}

function change_all_stage_phase_status(development_id,stage_no,checked)
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
			url: window.Url + 'developments/update_all_stage_phase_status/'+ development_id + '/' + stage_no + '/' + status,
			type: 'GET',
			success: function(data) 
			{
				location.reload();	        
			},
			        
		  });

}


$(function() {
$( "#accordion" ).accordion({heightStyle: "content"});
$('#accordion input[type="checkbox"]').click(function(e) {
    e.stopPropagation();
});
});


</script>
	
	<?php
	
		$stages = $stages_no[0]->number_of_stages;
		$development_id = $stages_no[0]->id;
		$now = date('Y-m-d');
		$today_time = strtotime($now);
	?>
	
	<div id="accordion">

		<?php

		//print_r($development_phase_info);
		$phase_number = count($development_phase_info);

		for($p=0; $p < $phase_number; $p++)
		{

			
			$ci =&get_instance();
			$ci->load->model('developments_model');
			$all_phase_task = $ci->developments_model->get_all_development_phase_status($development_id,$development_phase_info[$p]->id)->result();
		?>

		<h3 style="height:35px; clear:both;">
				<div style="float: left; height: 30px; margin-left: 17px; width: 24%;"><?php echo $development_phase_info[$p]->phase_name; ?></div>
				<div style="float:left; width:74%;height:30px"> 
					<input type="checkbox" name="all_phase_task" id="all_phase_task" <?php if(isset($all_phase_task[0]->all_task_status) && $all_phase_task[0]->all_task_status == 1){ ?> checked="checked" <?php } ?> onclick="change_all_phase_task_status(<?php echo $development_id; ?>,<?php echo $development_phase_info[$p]->id; ?>,this.checked)" >
				</div>
			</h3>

		<div class="stage_content">

		<?php 

			$phase_planned_finished_date = $development_phase_info[$p]->planned_finished_date;

			$ci =&get_instance();
			$ci->load->model('developments_model');
			$development_phase_task_info = $ci->developments_model->get_development_phase_task_info($development_id,$development_phase_info[$p]->id)->result();
			//print_r($development_phase_task_info);

			for($t = 0; $t < count($development_phase_task_info); $t++)
			{

				$phase_bg_color = '';
				$day_sign = '';
				$day_alert = '';

				if( $development_phase_task_info[$t]->task_start_date == '0000-00-00')
				{

					$task_planned_finished_date = $phase_planned_finished_date ;
				}
				else
				{
					$task_start_date = $development_phase_task_info[$t]->task_start_date;
					$day_required = $development_phase_task_info[$t]->task_length;
					$task_planned_finished_date = date('Y-m-d', strtotime($task_start_date. ' + '.$day_required.' days'));
				}

				$pc_time = strtotime($task_planned_finished_date);

				if ($today_time < $pc_time && $development_phase_task_info[$t]->development_task_status == '1' ) 
				{
					$day_sign = ' ';
					$day_alert = "Completed";
					$phase_bg_color = 'green';
				}
				elseif ($today_time < $pc_time && $development_phase_task_info[$t]->development_task_status == '0' ) 
				{
					$day_sign = '';
					$day_alert = " Days Remaining";
					$phase_bg_color = 'yellow';
				}
				elseif($today_time > $pc_time && $development_phase_task_info[$t]->development_task_status == '1')
				{
					$day_sign = '';
					$day_alert = "Completed";
					$phase_bg_color = 'green';
				}
				elseif($today_time > $pc_time && $development_phase_task_info[$t]->development_task_status == '0')
				{
					$day_sign = '';
					$day_alert = " Days Over";
					$phase_bg_color = 'red';
				}
				elseif($today_time == $pc_time && $development_phase_task_info[$t]->development_task_status == '1')
				{
					$day_sign = '';
					$day_alert = 'Completed';
					$phase_bg_color = 'green';
				}
				else
				{
					$day_sign = '';
					$day_alert = '';
					$phase_bg_color = 'yellow';
				}
				

		?>

			<div class="unrow">
				
				<div class="uncol" style="width:27%;"><?php if(isset($development_phase_task_info[$t]->task_name)) { echo $development_phase_task_info[$t]->task_name; } ?></div>
				<div class="uncol" style="width:10%;"><input id="phase_status_<?php echo $development_phase_task_info[$t]->id; ?>" type="checkbox" name="phase_status" <?php if( $development_phase_task_info[$t]->development_task_status == '1' ) { ?> checked="checked" <?php } ?> onclick="change_development_phase_task_status(<?php echo $development_phase_task_info[$t]->id; ?>,this.checked)"   /></div>
				<div class="uncol" style="width:15%;"><a href="#EditDate_<?php echo $development_phase_task_info[$t]->id; ?>" role="button" data-toggle="modal"><?php if(isset($task_planned_finished_date)) { echo $task_planned_finished_date; } ?></a></div>
				<div class="uncol" style="width:15%;"><a href="#EditActualDate_<?php echo $development_phase_task_info[$t]->id; ?>" role="button" data-toggle="modal"><?php if(isset($development_phase_task_info[$t]->actual_completion_date) && $development_phase_task_info[$t]->actual_completion_date != '0000-00-00') { echo $development_phase_task_info[$t]->actual_completion_date; } else{ echo $task_planned_finished_date;} ?></a></div>
				<div class="uncol" style="width:23%;"><?php $rem_days = date_diff(date_create($task_planned_finished_date),date_create($now))->format("%a"); if($day_alert !='Completed'){ echo $day_sign.$rem_days.$day_alert;}else{ echo "Completed";}  ?></div>
				<div class="uncol" style="width:10%;"><div style="height:20px; width:20px; border-radius:15px; background-color:<?php echo $phase_bg_color; ?>"></div></div>

			</div>

			<div id="EditDate_<?php echo $development_phase_task_info[$t]->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<form class="form-horizontal" action="<?php echo base_url(); ?>developments/phase_task_start_date_update/<?php echo $development_phase_task_info[$t]->id; ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit Planned Completion Date</h3>
								</div>
								<div class="modal-body">
									
										
									<div class="control-group">
										<label class="control-label" for="task_name">Planned Start Date </label>
										<div class="controls">
											<input type="text" id="planned_start_date" placeholder="" name="planned_start_date" value="<?php if($development_phase_task_info[$t]->task_start_date !='0000-00-00'){ echo $this->wbs_helper->to_report_date($development_phase_task_info[$t]->task_start_date); } else{ echo date('d-m-Y'); } ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="inputPassword"></label>
										<div class="controls">
										<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
											<div class="save">
												<input type="submit" value="Submit" name="submit" />
											</div>
										</div>
									</div>
							    
								</div>

							</form>
				</div>

				<!-- edit actual finished date -->
				<div id="EditActualDate_<?php echo $development_phase_task_info[$t]->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<form class="form-horizontal" action="<?php echo base_url(); ?>developments/phase_task_actual_date_update/<?php echo $development_phase_task_info[$t]->id; ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit Actual Completion Date</h3>
								</div>
								<div class="modal-body">
									
										
									<div class="control-group">
										<label class="control-label" for="task_name">Actual Completion Date </label>
										<div class="controls">
											<input type="text" id="planned_start_date" placeholder="" name="actual_completion_date" value="<?php if($development_phase_task_info[$t]->actual_completion_date !='0000-00-00'){ echo $this->wbs_helper->to_report_date($development_phase_task_info[$t]->actual_completion_date); } else{ echo date('d-m-Y'); } ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="inputPassword"></label>
										<div class="controls">
										<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
											<div class="save">
												<input type="submit" value="Submit" name="submit" />
											</div>
										</div>
									</div>
							    
								</div>

							</form>
				</div>
	
		

	    <?php } // end task for loop   ?>

		</div>

		<?php
		}// end phase for loop
		

		?>


		<?php 
		for($i=1; $i<=$stages; $i++)
		{ 
			$ci =&get_instance();
			$ci->load->model('developments_model');
			$phase_info = $ci->developments_model->get_phase_info($development_id,$i)->result(); 
			$all_phase_task = $ci->developments_model->get_all_phase_status($development_id,$i)->result(); 
		
		?>
		
			<h3 style="height:35px; clear:both;">
				<div style="float: left; height: 30px; margin-left: 17px; width: 24%;">Stage <?php echo $i ?> </div>
				<div style="float:left; width:74%;height:30px"> 
					<input type="checkbox" name="all_phase_task" id="all_phase_task" <?php if(isset($all_phase_task[0]->aphase_status) && $all_phase_task[0]->aphase_status == 1){ ?> checked="checked" <?php } ?> onclick="change_all_stage_phase_status(<?php echo $development_id; ?>,<?php echo $i; ?>,this.checked)" >
				</div>
			</h3>
			<div class="stage_content">

				<?php for($j = 0; $j < count($phase_info); $j++ ) { 

						$day_sign = '';
						$day_alert = '';
						$bg_color = 'yellow';
						
						$pc_time = strtotime($phase_info[$j]->planned_finished_date);

						if ($today_time < $pc_time && $phase_info[$j]->phase_status == '1' ) 
						{
							$day_sign = ' ';
							$day_alert = "Completed";
							$bg_color = 'green';
						}
						elseif ($today_time < $pc_time && $phase_info[$j]->phase_status == '0' ) 
						{
							$day_sign = '';
							$day_alert = " Days Remaining";
							$bg_color = 'yellow';
						}
						elseif($today_time > $pc_time && $phase_info[$j]->phase_status == '1')
						{
							$day_sign = '';
							$day_alert = "Completed";
							$bg_color = 'green';
						}
						elseif($today_time > $pc_time && $phase_info[$j]->phase_status == '0')
						{
							$day_sign = '';
							$day_alert = " Days Over";
							$bg_color = 'red';
						}
						elseif($today_time == $pc_time && $phase_info[$j]->phase_status == '1')
						{
							$day_sign = '';
							$day_alert = 'Completed';
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
					<div class="uncol" style="width:27%;"><?php if(isset($phase_info[$j]->phase_name))echo $phase_info[$j]->phase_name; ?></div>
					<div class="uncol" style="width:10%;"><input id="phase_status_<?php echo $phase_info[$j]->id; ?>" type="checkbox" name="phase_status" <?php if( $phase_info[$j]->phase_status == '1' ) { ?> checked="checked" <?php } ?> onclick="change_phase_status(<?php echo $phase_info[$j]->id; ?>,this.checked)"   /></div>
					<div class="uncol" style="width:15%;"><a href="#EditDate_<?php echo $phase_info[$j]->id; ?>" role="button" data-toggle="modal"><?php if(isset($phase_info[$j]->planned_finished_date))echo $phase_info[$j]->planned_finished_date; ?></a></div>
					<div class="uncol" style="width:15%;"><a href="#EditActualDate_<?php echo $phase_info[$j]->id; ?>" role="button" data-toggle="modal"><?php if(isset($phase_info[$j]->actual_finished_date))echo $phase_info[$j]->actual_finished_date; ?></a></div>
					<div class="uncol" style="width:23%;"><?php $rem_days = date_diff(date_create($phase_info[$j]->planned_finished_date),date_create($now))->format("%a"); if($day_alert !='Completed'){ echo $day_sign.$rem_days.$day_alert;}else{ echo "Completed";}  ?></div>
					<div class="uncol" style="width:10%;"><?php if(isset($phase_info[$j]->phase_length)){ ?><div style="height:20px; width:20px; border-radius:15px; background-color:<?php echo $bg_color; ?>"></div><?php }?></div>
				</div>

				<div id="EditDate_<?php echo $phase_info[$j]->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<form class="form-horizontal" action="<?php echo base_url(); ?>developments/development_stage_task_start_date_update/<?php echo $phase_info[$j]->id; ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit Planned Completion Date</h3>
								</div>
								<div class="modal-body">
									
										
									<div class="control-group">
										<label class="control-label" for="task_name">Planned Start Date </label>
										<div class="controls">
											<input type="text" id="planned_start_date" placeholder="" name="planned_start_date" value="<?php if($phase_info[$j]->planned_start_date !='0000-00-00'){ echo $this->wbs_helper->to_report_date($phase_info[$j]->planned_start_date); } else{ echo date('d-m-Y'); } ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="inputPassword"></label>
										<div class="controls">
											<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
											<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $i; ?>">
											<div class="save">
												<input type="submit" value="Submit" name="submit" />
											</div>
										</div>
									</div>
							    
								</div>

							</form>
				</div>



				<div id="EditActualDate_<?php echo $phase_info[$j]->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<form class="form-horizontal" action="<?php echo base_url(); ?>developments/development_stage_task_actual_date_update/<?php echo $phase_info[$j]->id; ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit Actual Completion Date</h3>
								</div>
								<div class="modal-body">
									
										
									<div class="control-group">
										<label class="control-label" for="task_name">Actual Completion Date </label>
										<div class="controls">
											<input type="text" id="planned_start_date" placeholder="" name="actual_finished_date" value="<?php  if($phase_info[$j]->actual_finished_date !='0000-00-00'){ echo $this->wbs_helper->to_report_date($phase_info[$j]->actual_finished_date); } else{ echo date('d-m-Y'); } ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="inputPassword"></label>
										<div class="controls">
											<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
											<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $i; ?>">
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