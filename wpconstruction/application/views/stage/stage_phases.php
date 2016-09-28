<div id="stage_phase_task">

<script>
jQuery(document).ready(function() {
		
		$( "#draggable-phase" ).draggable({
			//connectToSortable: "#sortable-phase",
			helper: "clone",
			revert: "invalid"
		});
		$( "#stage_phase_task" ).droppable({
			
			drop: function( event, ui ) {
				if (ui.draggable.is('#draggable-phase')) {
					
					//$(".stage .stage-content").css("display","block");
					$( this )
					//.addClass( "stage-active" )
					.find( "#drop-phase" )
					.html( '<h3 style="margin-bottom: 0;margin-top: 10px;"><a style="color:#222;" href="#AddPhase" role="button" data-toggle="modal">+ New Phase Add</a></h3>' );
				}
			}
		});
	

});
</script>

<div class="task-phase-add">
	<ul class="drag-phase-task">
		<li id="draggable-task">Add Task +</li>
		<li id="draggable-phase">Add Phase +</li>
	</ul>
	<div style="clear:both;"></div>
</div>
<div style="clear:both;"></div>

	<div id="underway_header">
		<div class="uhead" style="width:6%"></div>
		<div class="uhead" style="width:18%">Task Name</div>	
		<div class="uhead" style="width:15%">Planned Start Date</div>
		<div class="uhead" style="width:17%">Planned Completion Date</div>
		<div class="uhead" style="width:17%">Actual Completion Date</div>
		<div class="uhead" style="width:15%">Days Remaining</div>
		<div class="uhead" style="width:6%">On Time</div>
		<div class="uhead" style="width:6%">Complete</div>
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


function change_all_task_status(development_id,phase_id,stage_no,checked)
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
			url: window.Url + 'stage/update_all_task_status/'+ development_id + '/' + phase_id + '/' + stage_no + '/' + status,
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

		//print_r($phase_info);
	
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
			$all_phase_task = $ci->stage_model->get_all_task_status($development_id,$stage_id,$phase_info[$i]->id)->result();
			
			$phase_planned_finished_date = $phase_info[$i]->planned_finished_date;

		?>
		
			
			<h3 style="height:35px; clear:both;">
				<div style="float:left; width:7%; height:30px">
					<a href="#DeletePhase_<?php echo $phase_info[$i]->id; ?>" title="Phase Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
					<a href="#EditPhase_<?php echo $phase_info[$i]->id; ?>" title="Phase Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php echo base_url();?>icon/icon_edit.png" /></a>
				</div>
				<div style="float:left; width:52%; height:30px"><?php if(isset($all_phase_task[0]->all_task_status) && $all_phase_task[0]->all_task_status == 1){ ?><img style="margin-right:5px;" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" /><?php } ?><?php echo $phase_info[$i]->phase_name; ?></div>
				<div style="float:left; width:40%;height:30px"> 
					<input style="float:right;" type="checkbox" name="all_phase_task" id="all_phase_task" <?php if(isset($all_phase_task[0]->all_task_status) && $all_phase_task[0]->all_task_status == 1){ ?> checked="checked" <?php } ?> onclick="change_all_task_status(<?php echo $development_id; ?>,<?php echo $phase_info[$i]->id; ?>,<?php echo $stage_id; ?>,this.checked)" >
				</div>

				<!-- MODAL Phase Delete-->
					<div id="DeletePhase_<?php echo $phase_info[$i]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url();?>stage/stage_phase_delete/<?php echo $phase_info[$i]->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel">Delete Phase: <?php echo $phase_info[$i]->phase_name; ?></h3>
						</div>
						<div class="modal-body">
							<p>Are you sure want to delete this Phase?</p>
					    
						</div>
						<div class="modal-footer delete-task">
							<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
							<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
							<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $this->uri->segment(4); ?>">
							<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
							<input type="submit" value="Ok" name="submit" class="btn" />
						</div>
					</form>
					</div>
					<!-- MODAL Phase Delete-->

					<!-- MODAL Phase Edit -->
					<div id="EditPhase_<?php echo $phase_info[$i]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_phase_update/<?php echo $phase_info[$i]->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel">Edit Phase</h3>
						</div>
						<div class="modal-body">

							<div class="control-group">
								<label class="control-label" for="phase_name">Phase Name </label>
								<div class="controls">
									<input type="text" id="phase_name" placeholder="" name="phase_name" value="<?php echo $phase_info[$i]->phase_name; ?>">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="planned_start_date">Planned Start Date </label>
								<div class="controls">
									<input type="text" id="planned_start_date" placeholder="" name="planned_start_date" value="<?php if($phase_info[$i]->planned_start_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($phase_info[$i]->planned_start_date); } ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="planned_finished_date">Planned Completion Date </label>
								<div class="controls">
									<input type="text" id="planned_start_date" placeholder="" name="planned_finished_date" value="<?php if($phase_info[$i]->planned_finished_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($phase_info[$i]->planned_finished_date); } ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="phase_person_responsible">Person Responsible </label>
								<div class="controls">
									<input type="text" id="phase_person_responsible" placeholder="" name="phase_person_responsible" value="<?php if(isset($phase_info[$i]->phase_person_responsible)){ echo $phase_info[$i]->phase_person_responsible; } ?>">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="inputPassword"></label>
								<div class="controls">
									<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
									<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $this->uri->segment(4); ?>">
									<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
									
									<div class="save">
										<input type="submit" value="Submit" name="submit" />
									</div>
								</div>
							</div>
					    
						</div>

					</form>
					</div>
					<!-- MODAL Phase Edit-->
			</h3>
		
			<div class="stage_content dropable-task-<?php echo $phase_info[$i]->id; ?>">

<script>
jQuery(document).ready(function() {
		
		$( "#draggable-task" ).draggable({
			//connectToSortable: "#sortable-phase",
			helper: "clone",
			revert: "invalid"
		});
		$( ".dropable-task-<?php echo $phase_info[$i]->id; ?>" ).droppable({
			
			drop: function( event, ui ) {
				if (ui.draggable.is('#draggable-task')) {
					
					//$(".stage .stage-content").css("display","block");
					$( this )
					//.addClass( "stage-active" )
					.find( "#drop-task-<?php echo $phase_info[$i]->id; ?>" )
					.html( '<h3 style="margin-bottom: 0;margin-top: 5px;"><a href="#AddTask_<?php echo $phase_info[$i]->id; ?>" role="button" data-toggle="modal">+ New Task Add</a></h3>' );
				}
			}
		});
	

});
</script>	

<!-- MODAL Task Add -->
<div id="AddTask_<?php echo $phase_info[$i]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_task_add" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Add Task</h3>
	</div>
	<div class="modal-body">
			
		<div class="control-group">
			<label class="control-label" for="task_name">Task Name </label>
			<div class="controls">
				<input type="text" id="task_name" placeholder="" name="task_name" value="" required="">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="planned_completion_date">Task Length </label>
			<div class="controls">
				<input type="text" id="task_length" placeholder="" name="task_length" value=""  required="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="task_start_date">Planned Start Date </label>
			<div class="controls">
				<input type="text" id="task_start_date" placeholder="" name="task_start_date" value="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="actual_completion_date">Actual Completion Date </label>
			<div class="controls">
				<input type="text" id="planned_start_date" placeholder="" name="actual_completion_date" value="">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="inputPassword"></label>
			<div class="controls">
				<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
				<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $this->uri->segment(4); ?>">
				<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
				<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $phase_info[$i]->id; ?>">
				<div class="save">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</div>
	
	</div>

</form>
</div>
<!-- MODAL Task Add-->

				<?php	for($j=0; $j< count($task_info); $j++)
					{ 
						$day_sign = '';
						$day_alert = '';
						$bg_color = 'yellow';
						$day_required = $task_info[$j]->task_length;

						if( $task_info[$j]->task_start_date == '0000-00-00')
						{

							$planned_completion_date = $phase_planned_finished_date;
						}
						else
						{

							$created_date = date_create($task_info[$j]->task_start_date);
							$str = $day_required.' days';
							$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
							$planned_completion_date =  date_format($pcdate, 'Y-m-d');
						}

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
					<div class="uncol" style="width:6%;padding-left: 12px;">
						<a href="#DeleteTask_<?php echo $task_info[$j]->id; ?>" title="Task Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
						<a href="#EditTask_<?php echo $task_info[$j]->id; ?>" title="Task Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php echo base_url();?>icon/icon_edit.png" /></a>
					</div>
					<div class="uncol" style="width:18%"><?php if( $task_info[$j]->stage_task_status == '1' ) { ?><img style="margin-right:5px;" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" /><?php } ?><?php if(isset($task_info[$j]->task_name)) echo $task_info[$j]->task_name; ?></div>				
					<div class="uncol" style="width:15%"><a href="#EditDate_<?php echo $task_info[$j]->id; ?>" role="button" data-toggle="modal"><?php if(isset($task_info[$j]->task_start_date)){ echo $task_info[$j]->task_start_date; } ?></a></div>
					<div class="uncol" style="width:17%"><?php if(isset($task_info[$j]->created)){ echo $planned_completion_date; } ?></div>
					<div class="uncol" style="width:17%"><a href="#EditActualDate_<?php echo $task_info[$j]->id; ?>" role="button" data-toggle="modal"><?php if(isset($task_info[$j]->actual_completion_date)) { echo $task_info[$j]->actual_completion_date; } ?></a></div>
					<div class="uncol" style="width:15%"><?php $rem_days = date_diff(date_create($planned_completion_date),date_create($now))->format("%a");  if( $task_info[$j]->stage_task_status == '1' ) { echo '-'; } else{ echo $day_sign.$rem_days.$day_alert; } ?></div>
					<div class="uncol" style="width:6%"><?php if(isset($task_info[$j]->task_name)){ ?><div style="height:20px; width:20px; border-radius:15px; background-color:<?php echo $bg_color; ?>"></div><?php }?></div>
					<div class="uncol" style="width:6%;padding-right: 16px;"><input id="task_status_<?php echo $task_info[$j]->id; ?>" class="task_status" type="checkbox" name="task_status" <?php if( $task_info[$j]->stage_task_status == '1' ) { ?> checked="checked" <?php } ?> onclick="change_task_status(<?php echo $task_info[$j]->id; ?>,this.checked)"  /></div>
				<div style="clear:both;"></div>
			</div>

					

					<!-- MODAL Task Edit -->
					<div id="EditTask_<?php echo $task_info[$j]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_task_update/<?php echo $task_info[$j]->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel">Edit Task</h3>
						</div>
						<div class="modal-body">
								
							<div class="control-group">
								<label class="control-label" for="task_name">Task Name </label>
								<div class="controls">
									<input type="text" id="task_name" placeholder="" name="task_name" value="<?php echo $task_info[$j]->task_name; ?>">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="task_start_date">Planned Start Date </label>
								<div class="controls">
									<input type="text" id="task_start_date" placeholder="" name="task_start_date" value="<?php if($task_info[$j]->task_start_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($task_info[$j]->task_start_date); } ?>">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="actual_completion_date">Actual Completion Date </label>
								<div class="controls">
									<input type="text" id="planned_start_date" placeholder="" name="actual_completion_date" value="<?php if($task_info[$j]->actual_completion_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($task_info[$j]->actual_completion_date); } ?>">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="inputPassword"></label>
								<div class="controls">
									<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
									<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $this->uri->segment(4); ?>">
									<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
									<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $phase_info[$i]->id; ?>">
									<div class="save">
										<input type="submit" value="Submit" name="submit" />
									</div>
								</div>
							</div>
						
						</div>

					</form>
					</div>
					<!-- MODAL Task Edit-->

					<!-- MODAL Task Delete-->
					<div id="DeleteTask_<?php echo $task_info[$j]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url();?>stage/stage_task_delete/<?php echo $task_info[$j]->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel">Delete Task: <?php echo $task_info[$j]->task_name; ?></h3>
						</div>
						<div class="modal-body">
							<p>Are you sure want to delete this Task?</p>
						
						</div>
						<div class="modal-footer delete-task">
							<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
							<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $this->uri->segment(4); ?>">
							<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
							<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $phase_info[$i]->id; ?>">
							<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
							<input type="submit" value="Ok" name="submit" class="btn" />
						</div>
					</form>
					</div>
					<!-- MODAL Task Delete-->

				<div id="EditDate_<?php echo $task_info[$j]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_task_start_date_update/<?php echo $task_info[$j]->id; ?>" method="POST">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h3 id="myModalLabel">Edit Planned Completion Date</h3>
							</div>
							<div class="modal-body">
								
									
								<div class="control-group">
									<label class="control-label" for="task_name">Planned Start Date </label>
									<div class="controls">
										<input type="text" id="planned_start_date" placeholder="" name="planned_start_date" value="<?php if($task_info[$j]->task_start_date !='0000-00-00'){ echo $this->wbs_helper->to_report_date($task_info[$j]->task_start_date); } else{ echo date('d-m-Y'); } ?>">
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


				<!-- Actual Completion Date form-->
				<div id="EditActualDate_<?php echo $task_info[$j]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_task_actual_date_update/<?php echo $task_info[$j]->id; ?>" method="POST">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h3 id="myModalLabel">Edit Actual Completion Date</h3>
							</div>
							<div class="modal-body">
								
									
								<div class="control-group">
									<label class="control-label" for="task_name">Actual Completion Date </label>
									<div class="controls">
										<input type="text" id="planned_start_date" placeholder="" name="actual_completion_date" value="<?php if($task_info[$j]->actual_completion_date !='0000-00-00'){ echo $this->wbs_helper->to_report_date($task_info[$j]->actual_completion_date); } else{ echo date('d-m-Y'); } ?>">
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
					<div id="drop-task-<?php echo $phase_info[$i]->id; ?>"></div>
			</div>

	  
			
		<?php
		}
		?>
		
	</div>
	<div id="drop-phase"></div>
</div>

		<!-- MODAL Phase Edit -->
		<div id="AddPhase" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_phase_add" method="POST">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h3 id="myModalLabel">Add Phase</h3>
			</div>
			<div class="modal-body">

				<div class="control-group">
					<label class="control-label" for="phase_name">Phase Name </label>
					<div class="controls">
						<input type="text" id="phase_name" placeholder="" name="phase_name" value="" required="">
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="planned_start_date">Planned Start Date </label>
					<div class="controls">
						<input type="text" id="planned_start_date" placeholder="" name="planned_start_date" value="">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="planned_finished_date">Planned Completion Date </label>
					<div class="controls">
						<input type="text" id="planned_start_date" placeholder="" name="planned_finished_date" value="">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="phase_person_responsible">Person Responsible </label>
					<div class="controls">
						<input type="text" id="phase_person_responsible" placeholder="" name="phase_person_responsible" value="">
					</div>
				</div>
				
				<div class="control-group">
					<label class="control-label" for="inputPassword"></label>
					<div class="controls">
						<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
						<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $this->uri->segment(4); ?>">
						<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
						
						<div class="save">
							<input type="submit" value="Submit" name="submit" />
						</div>
					</div>
				</div>
			
			</div>

		</form>
		</div>
		<!-- MODAL Phase Edit-->