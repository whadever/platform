<?php
$user = $this->session->userdata('user');
$user_app_role_id = $user->application_role_id; 

if($user_app_role_id==2 || $user_app_role_id==4){
	$per = '';
	$per1 = '';
	$per2 = '';
}else{
	$per = 'NotPermission';
	$per1 = 'style="display:none;"';
	$per2 = '&nbsp;';
}

if($user_app_role_id==4){
	$disabled = 'disabled';
}else{
	$disabled = '';
}
?>

<div id="stage_phase_task">
<?php
	//print_r($phase_info);

	$phase_number = count($phase_info);

?>

<div class="task-phase-add" <?php echo $per1; ?>>
	<ul class="drag-phase-task">
		<li id="draggable-task"><a style="color:#000;" href="#AddSubTask_<?php if($this->uri->segment(5)){ echo $this->uri->segment(5); }else{ echo $phase_info[0]->id; } ?>" id="taskid" role="button" data-toggle="modal">Add Sub Task +</a></li>
		<li id="draggable-task"><a style="color:#000;" href="#AddTask_<?php if($this->uri->segment(5)){ echo $this->uri->segment(5); }else{ echo $phase_info[0]->id; } ?>" id="phaseid" role="button" data-toggle="modal">Add Task +</a></li>
		<li id="draggable-phase"><a style="color:#000;" href="#AddPhase" title="Phase Add" role="button" data-toggle="modal">Add Phase +</a></li>
	</ul>
	<div style="clear:both;"></div>
</div>
<div style="clear:both;"></div>

	<div id="underway_header">
		<div class="uhead" style="width:6%"></div>
		<div class="uhead" style="width:16%">Task Name</div>
		<div class="uhead" style="width:9%">Start Alert</div>	
		<div class="uhead" style="width:15%;">Planned Start Date</div>
		<div class="uhead" style="width:15%">Planned Completion Date</div>
		<div class="uhead" style="width:17%;display: none;">Actual Completion Date</div>
		<div class="uhead" style="width:15%">Person Responsible</div>
		<div class="uhead" style="width:10%"><!---Days Remaining---></div>
		<div class="uhead" style="width:6%">Under Caution</div>
		<div class="uhead" style="width:8%;text-align: right;">Complete</div>
	</div>

<script>
	function TransferPhaseId(pid)
	{
	    document.getElementById('phaseid').href='#AddTask_'+pid;
	    document.getElementById('taskid').href='#AddSubTask_'+pid;
	}
</script>	

<script>
window.Url = "<?php print base_url(); ?>";
function change_task_status(dev_id,stage_id,phase_id,task_id,checked)
{
	$.ajax({
			url: window.Url + 'stage/update_task_status/' + task_id + '/' + checked,
			type: 'GET',
			cache: false,
			success: function(data) 
			{
				//location.reload();
				newurl = window.Url + 'stage/phases_list/' + dev_id + '/' + stage_id + '/' + phase_id;
				window.location = newurl;	        
			},
			        
		});

}


function change_all_task_status(development_id,phase_id,stage_no,checked)
{

	$.ajax({
			url: window.Url + 'stage/update_all_task_status/'+ development_id + '/' + phase_id + '/' + stage_no + '/' + checked,
			type: 'GET',
			cache: false,
			success: function(data) 
			{
				//location.reload();
				newurl = window.Url + 'stage/phases_list/' + development_id + '/' + stage_no + '/' + phase_id;
				window.location = newurl;	        
			},
			        
		});
}


</script>

<script>
jQuery(document).ready(function() {
	if (jQuery('.sortable-phase').length){
		$( ".sortable-phase" ).sortable({
			update : function () { 
			var order = $('.sortable-phase').sortable('serialize');
			$.ajax({
				url: window.Url + 'admindevelopment/stage_phase_ordering',
				type: 'POST',
				data: order,
				success: function(data) 
				{
 
				},
			        
			});
			}
		});	
		$( ".sortable-phase" ).disableSelection();
	}
});
</script>

	
	<?php

		$development_id = $stages_no[0]->id;
	?>
	
	<ul id="accordions" class="accordions <?php echo $per; ?>sortable-phase">
		<?php 
		for($i=0; $i < $phase_number; $i++)
		{ 
			$ci =&get_instance();
			$ci->load->model('stage_model');
			$task_info = $ci->stage_model->get_development_stage_phase_task_info($development_id,$stage_id,$phase_info[$i]->id)->result();
			$all_phase_task = $ci->stage_model->get_all_task_status($development_id,$stage_id,$phase_info[$i]->id)->result();
			
			$phase_planned_finished_date = $phase_info[$i]->planned_finished_date;
			
			if( $phase_info[$i]->planned_finished_date != '0000-00-00' && $phase_info[$i]->planned_finished_date > '1970-01-01')
			{
				$planned_finished_date = date('d-m-Y', strtotime($phase_info[$i]->planned_finished_date));
			}
			else if($phase_info[$i]->planned_start_date == '0000-00-00' || $phase_info[$i]->planned_start_date == '1970-01-01')
			{
				$planned_finished_date =  '00-00-0000';
			}
			else
			{
				$created_date = date_create($phase_info[$i]->planned_start_date);
				$str = '21 days';
				$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
				$planned_finished_date =  date_format($pcdate, 'd-m-Y');
			}

			$now_date = date("Y-m-d");
			$planned_start_date = $phase_info[$i]->planned_start_date;
			if($planned_start_date <= $now_date && $now_date < $phase_planned_finished_date){
				$ac_show = '1';
				$ac_show1 = '1';
			}else{
				$ac_show = '0';
			}
		?>
		
			
			<li onclick="TransferPhaseId(<?php echo $phase_info[$i]->id; ?>);" id="listItemPhase_<?php echo $phase_info[$i]->id; ?>" class="accordion <?php $ph = $this->uri->segment(5); if($phase_info[$i]->id==$ph){ echo 'accordion-active'; }else if(!$ph && $ac_show=='1'){ echo 'accordion-active'; } ?>">
			<div class="accordion-header">
				<div class="uncol1" style="width:6%;"><?php echo $per2; ?>
					<a <?php echo $per1; ?> href="#DeletePhase_<?php echo $phase_info[$i]->id; ?>" title="Phase Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
					<a <?php echo $per1; ?> href="#EditPhase_<?php echo $phase_info[$i]->id; ?>" title="Phase Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php echo base_url();?>icon/icon_edit.png" /></a>
				</div>
				<div class="uncol1" style="width:16%;"><?php if(isset($all_phase_task[0]->all_task_status) && $all_phase_task[0]->all_task_status == 1 || $phase_info[$i]->phase_status == 1){ ?><img style="margin:0 4px 0 0px" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" /><?php } ?><?php echo $phase_info[$i]->phase_name; ?></div>
				<div class="uncol1" style="width:9%;">&nbsp;&nbsp;</div>
				<div class="uncol1" style="width:15%;"><?php if($phase_info[$i]->planned_start_date != '1970-01-01' && $phase_info[$i]->planned_start_date != '0000-00-00'){ echo date('d-m-Y', strtotime($phase_info[$i]->planned_start_date)); }else{ echo '00-00-0000'; } ?></div>
				<div class="uncol1" style="width:15%;"><?php echo $planned_finished_date; ?></div>
				<div class="uncol1" style="width:15%;"><?php if($phase_info[$i]->username){ echo $phase_info[$i]->username; }else{ echo '&nbsp;&nbsp;&nbsp;'; } ?></div>
				<div class="uncol1" style="width:10%;">&nbsp;&nbsp;</div>
				<div class="uncol1" style="width:6%;">&nbsp;&nbsp;</div>
				<div class="uncol1" style="width:8%;text-align: right;"> <?php echo $per2; ?>
					<input <?php echo $per1; ?> type="checkbox" name="all_phase_task" id="all_phase_task" <?php if(isset($all_phase_task[0]->all_task_status) && $all_phase_task[0]->all_task_status == 1 || $phase_info[$i]->phase_status == 1){ ?> checked="checked" <?php } ?> onclick="change_all_task_status(<?php echo $development_id; ?>,<?php echo $phase_info[$i]->id; ?>,<?php echo $stage_id; ?>,<?php if($all_phase_task[0]->all_task_status == 1 || $phase_info[$i]->phase_status == 1){ echo '0'; }else{ echo'1'; } ?>)" >
				</div>

				<!-- MODAL Phase Delete-->
					<div id="DeletePhase_<?php echo $phase_info[$i]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url();?>stage/stage_phase_delete/<?php echo $phase_info[$i]->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
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
<script>
$(function() {
   	$('#fuzzOptionsListPhase_<?php echo $phase_info[$i]->id; ?>').fuzzyDropdown({
      mainContainer: '#fuzzSearchPhase_<?php echo $phase_info[$i]->id; ?>',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });
});
</script>
					<!-- MODAL Phase Edit -->
					<div id="EditPhase_<?php echo $phase_info[$i]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_phase_update/<?php echo $phase_info[$i]->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h3 id="myModalLabel">Edit Phase</h3>
						</div>
						<div class="modal-body">

							<div class="control-group">
								<label class="control-label" for="phase_name">Phase Name </label>
								<div class="controls">
									<input required="" type="text" id="phase_name" name="phase_name" value="<?php echo $phase_info[$i]->phase_name; ?>">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="planned_start_date">Planned Start Date </label>
								<div class="controls">
									<input <?php echo $disabled; ?> required="" type="text" class="live_datepicker" name="planned_start_date" value="<?php if($phase_info[$i]->planned_start_date != '1970-01-01' && $phase_info[$i]->planned_start_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($phase_info[$i]->planned_start_date); } ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="planned_finished_date">Planned Completion Date </label>
								<div class="controls">
									<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="planned_finished_date" value="<?php if($phase_info[$i]->planned_finished_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($phase_info[$i]->planned_finished_date); } ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="phase_person_responsible">Person Responsible</label>
								<div class="controls" style="clear: both;">
									<select name="phase_person_responsible" class="form-control" id="fuzzOptionsListPhase_<?php echo $phase_info[$i]->id; ?>">
										<option value="">--Select a User--</option>
										<?php
										$user=  $this->session->userdata('user'); 
										$wp_company_id =$user->company_id;
					
										$this->db->where('users.company_id', $wp_company_id);
										$this->db->where('application_id', '1');
										$this->db->where_in('application_role_id', array('2','3','4'));
										$this->db->join('users', 'users.uid = users_application.user_id', 'left');
										$this->db->order_by('username', 'ASC');
										$results = $this->db->get('users_application')->result();
										foreach($results as $result){
										?>
										<option <?php if($phase_info[$i]->phase_person_responsible==$result->uid){ echo 'selected'; } ?> value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
										<?php
										}
										?>
									</select>
									<div id="fuzzSearchPhase_<?php echo $phase_info[$i]->id; ?>">
									  <div id="fuzzNameContainer">
									    <span class="fuzzName"></span>
									    <span class="fuzzArrow"></span>
									  </div>
									  <div id="fuzzDropdownContainer">
									    <input type="text" value="" class="fuzzMagicBox" placeholder="search.." />
									    <span class="fuzzSearchIcon"></span>
									    <ul id="fuzzResults">
									    </ul>
									  </div>
									</div>
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
				</div>
				<div class="accordion-content" style="<?php $ph = $this->uri->segment(5); if($phase_info[$i]->id==$ph){ echo 'display:block;'; }else if(!$ph && $ac_show=='1'){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">
				
	

<script>
$(function() {
   	$('#fuzzOptionsList_<?php echo $phase_info[$i]->id; ?>').fuzzyDropdown({
      mainContainer: '#fuzzSearch_<?php echo $phase_info[$i]->id; ?>',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });
});
</script>

<!-- MODAL Task Add -->
<div id="AddTask_<?php echo $phase_info[$i]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_task_add" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
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
			<label class="control-label" for="task_start_date">Planned Start Date </label>
			<div class="controls">
				<input <?php echo $disabled; ?> required="" type="text" class="live_datepicker" name="task_start_date" value="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="planned_completion_date">Planned Completion Date </label>
			<div class="controls">
				<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="planned_completion_date" value="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="task_person_responsible">Person Responsible</label>
			<div class="controls" style="clear: both;">
				<select name="task_person_responsible" class="form-control" id="fuzzOptionsList_<?php echo $phase_info[$i]->id; ?>">
					<option value="">--Select a User--</option>
				<?php
				$user=  $this->session->userdata('user'); 
				$wp_company_id =$user->company_id;

				$this->db->where('users.company_id', $wp_company_id);
				$this->db->where('application_id', '1');
				$this->db->where_in('application_role_id', array('2','3','4'));
				$this->db->join('users', 'users.uid = users_application.user_id', 'left');
				$this->db->order_by('username', 'ASC');
				$results = $this->db->get('users_application')->result();
				foreach($results as $result){
				?>
				<option value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
				<?php
				}
				?>
				</select>
				<div id="fuzzSearch_<?php echo $phase_info[$i]->id; ?>">
				  <div id="fuzzNameContainer">
				    <span class="fuzzName"></span>
				    <span class="fuzzArrow"></span>
				  </div>
				  <div id="fuzzDropdownContainer">
				    <input type="text" value="" class="fuzzMagicBox" placeholder="search.." />
				    <span class="fuzzSearchIcon"></span>
				    <ul id="fuzzResults">
				    </ul>
				  </div>
				</div>
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

<script>
$(function() {
   	$('#fuzzOptionsList_Sub_<?php echo $phase_info[$i]->id; ?>').fuzzyDropdown({
      mainContainer: '#fuzzSearch_Sub_<?php echo $phase_info[$i]->id; ?>',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });
});
</script>

<!-- MODAL Sub Task Add -->
<div id="AddSubTask_<?php echo $phase_info[$i]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_task_add" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3 id="myModalLabel">Add Sub Task</h3>
	</div>
	<div class="modal-body">
		<div class="control-group">
			<label class="control-label" for="task_name">Parent Task </label>
			<div class="controls">
				<?php
					$this->db->where('phase_id',$phase_info[$i]->id);
					$this->db->where('parent_task_id',0);
					$tasks = $this->db->get('stage_task')->result();
				?>
				<select name="parent_task_id" class="form-control" required style="border-radius:4px">
					<option value="">Select Task</option>
					<?php 
						foreach($tasks as $task){
							echo "<option value=".$task->id.">".$task->task_name."</option>";
						}
					?>
				</select>
			</div>
		</div>
			
		<div class="control-group">
			<label class="control-label" for="task_name">Sub Task Name </label>
			<div class="controls">
				<input type="text" id="task_name" placeholder="" name="task_name" value="" required="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="task_start_date">Planned Start Date </label>
			<div class="controls">
				<input <?php echo $disabled; ?> required="" type="text" class="live_datepicker" name="task_start_date" value="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="planned_completion_date">Planned Completion Date </label>
			<div class="controls">
				<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="planned_completion_date" value="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="task_person_responsible">Person Responsible</label>
			<div class="controls" style="clear: both;">
				<select name="task_person_responsible" class="form-control" id="fuzzOptionsList_Sub_<?php echo $phase_info[$i]->id; ?>">
					<option value="">--Select a User--</option>
				<?php
				$user=  $this->session->userdata('user'); 
				$wp_company_id =$user->company_id;

				$this->db->where('users.company_id', $wp_company_id);
				$this->db->where('application_id', '1');
				$this->db->where_in('application_role_id', array('2','3','4'));
				$this->db->join('users', 'users.uid = users_application.user_id', 'left');
				$this->db->order_by('username', 'ASC');
				$results = $this->db->get('users_application')->result();
				foreach($results as $result){
				?>
				<option value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
				<?php
				}
				?>
				</select>
				<div id="fuzzSearch_Sub_<?php echo $phase_info[$i]->id; ?>">
				  <div id="fuzzNameContainer">
				    <span class="fuzzName"></span>
				    <span class="fuzzArrow"></span>
				  </div>
				  <div id="fuzzDropdownContainer">
				    <input type="text" value="" class="fuzzMagicBox" placeholder="search.." />
				    <span class="fuzzSearchIcon"></span>
				    <ul id="fuzzResults">
				    </ul>
				  </div>
				</div>
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
<!-- End MODAL Sub Task Add-->

<script>
jQuery(document).ready(function() {
	if (jQuery("#sortable-task-<?php echo $phase_info[$i]->id; ?>").length){
		$( "#sortable-task-<?php echo $phase_info[$i]->id; ?>" ).sortable({
			update : function () { 
			var order = $('#sortable-task-<?php echo $phase_info[$i]->id; ?>').sortable('serialize');
			$.ajax({
				url: window.Url + 'admindevelopment/stage_task_ordering',
				type: 'POST',
				data: order,
				success: function(data) 
				{
 
				},
			        
			});
			}
		});	
		$( "#sortable-task-<?php echo $phase_info[$i]->id; ?>" ).disableSelection();
	}
});
</script>

			<div class="dev-sub-tasks">

			<ul id="<?php echo $per; ?>sortable-task-<?php echo $phase_info[$i]->id; ?>" class="sub-tasks stage_content dropable-task-<?php echo $phase_info[$i]->id; ?>">
				<?php	for($j=0; $j< count($task_info); $j++)
					{ 
						$day_sign = '';
						$day_alert = '';
						$bg_color = 'yellow';
						$day_required = $task_info[$j]->task_length;

						if( $task_info[$j]->planned_completion_date != '0000-00-00' && $task_info[$j]->planned_completion_date != '1970-01-01')
						{
							$planned_completion_date = date('d-m-Y', strtotime($task_info[$j]->planned_completion_date));
						}
						else if( $task_info[$j]->task_start_date == '0000-00-00' || $task_info[$j]->task_start_date == '1970-01-01')
						{
							$planned_completion_date = '00-00-0000';
						}
						else
						{
							$created_date = date_create($task_info[$j]->task_start_date);
							$str = '21 days';
							$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
							$planned_completion_date =  date_format($pcdate, 'd-m-Y');
						}

						$now = date('Y-m-d');

						$today_time = strtotime($now);
						
						$pc_time = strtotime($planned_completion_date);
						$t20_time = strtotime($planned_completion_date .' - 20 days');
						
						if ($task_info[$j]->stage_task_status == '1' ) 
						{
							$rem_days = '';  
							$day_sign = '';
							$day_alert = "-";
							$bg_color = '<div style="height:20px; width:20px; border-radius:15px; background-color:none;"></div>';
						}
						elseif( $task_info[$j]->task_start_date == '0000-00-00' || $task_info[$j]->task_start_date == '1970-01-01')
						{
							$rem_days = '';  
							$day_sign = '';
							$day_alert = "Dates Required";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:grey;"></div><div class="hover">This task<br>has no dates<br>entered</div></div>';
						}
						elseif ($today_time < $pc_time && $today_time < $t20_time) 
						{
							$rem_days = date_diff(date_create($planned_completion_date),date_create($now))->format("%a");  
							$day_sign = '';
							$day_alert = " Days Remaining";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:grey;"></div><div class="hover">This task is over 21<br>Calendar days away<br>from completion</div></div>';
						}
						elseif ($today_time < $pc_time || $today_time == $pc_time) 
						{
							$rem_days = date_diff(date_create($planned_completion_date),date_create($now))->format("%a");  
							$day_sign = '';
							$day_alert = " Days Remaining";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:orange;"></div><div class="hover">This task is within<br>20 Calendar days<br>from completion</div></div>';
						}
						elseif($today_time > $pc_time)
						{
							$rem_days = date_diff(date_create($planned_completion_date),date_create($now))->format("%a");  
							$day_sign = '';
							$day_alert = " Days Over";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:red;"></div><div class="hover">This task is<br>overdue</div></div>';
						}

						$per_res = $task_info[$j]->task_person_responsible;
						
						if ($per_res > 0 && $task_info[$j]->task_start_date > '0000-00-00' && $task_info[$j]->stage_task_status == '0') 
						{
							$s_date = $task_info[$j]->task_start_date;
							$c_s_date = date('Y-m-d', strtotime($s_date. ' + 10 days'));
							$now = date('Y-m-d');
							if($task_info[$j]->start_alert=='1'){
								$start_alert = '<img width="22" height="22" src="'.base_url().'images/icon/start_alert_gray_color.png" />';
							}else if($now > $c_s_date){
								$start_alert = '<img width="22" height="22" src="'.base_url().'images/icon/start_alert.png" />';
							}else{
								$start_alert = '&nbsp;&nbsp;';
							}
						}
						else
						{
							$start_alert = '&nbsp;&nbsp;';
						}
						

				?>

<script>
$(function() {
   	$('#fuzzOptionsList_<?php echo $task_info[$j]->id; ?>').fuzzyDropdown({
      mainContainer: '#fuzzSearch_<?php echo $task_info[$j]->id; ?>',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });
});
</script>

				<?php
					$child_tasks = $ci->stage_model->get_development_stage_phase_task_info($development_id,$stage_id,$phase_info[$i]->id,$task_info[$j]->id)->result();
				?>

				<li id="listItemTask_<?php echo $task_info[$j]->id; ?>" class="sub-task <?php if(($task_info[$j]->id) == ($this->uri->segment(6))){ echo 'sub-task-active'; } ?>">
				<div class="sub-task-headers">
					<div class="uncol" style="width:6%;padding-left: 10px;"><?php echo $per2; ?>
						<a <?php echo $per1; ?> href="#DeleteTask_<?php echo $task_info[$j]->id; ?>" title="Task Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
						<a <?php echo $per1; ?> href="#EditTask_<?php echo $task_info[$j]->id; ?>" title="Task Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php echo base_url();?>icon/icon_edit.png" /></a>
					</div>
					<div class="uncol" style="width:16%">
					<?php if($child_tasks): ?><img width="16" height="16" src="<?php echo base_url();?>images/icon/has_child.png" /><?php endif; ?>
					<?php if( $task_info[$j]->stage_task_status == '1' ) { ?><img style="margin-right:5px;" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" /><?php } ?><?php if(isset($task_info[$j]->task_name)) echo $task_info[$j]->task_name; ?></div>				
					<div class="uncol" style="width:9%"><?php echo $start_alert; ?></div>	
					<div class="uncol" style="width:15%;"><?php if($task_info[$j]->task_start_date > '0000-00-00'){ echo date('d-m-Y', strtotime($task_info[$j]->task_start_date)); }else{ echo '00-00-0000'; } ?></div>
					<div class="uncol" style="width:15%"><?php echo $planned_completion_date; ?></div>
					<div class="uncol" style="width:15%;display: none;"><a href="#EditActualDate_<?php echo $task_info[$j]->id; ?>" role="button" data-toggle="modal"><?php if(isset($task_info[$j]->actual_completion_date)) { echo date('d-m-Y', strtotime($task_info[$j]->actual_completion_date)); } ?></a></div>
					<div class="uncol" style="width:15%"><?php if(isset($task_info[$j]->username)){ echo $task_info[$j]->username; }else{ echo '&nbsp;'; } ?></div>
					<div class="uncol" style="width:10%"><?php //echo $day_sign.$rem_days.$day_alert; ?>&nbsp;&nbsp;</div>
					<div class="uncol" style="width:6%"><?php echo $bg_color; ?></div>
					<div class="uncol" style="width:8%;padding-right: 11px;"><?php echo $per2; ?><input <?php echo $per1; ?> id="task_status_<?php echo $task_info[$j]->id; ?>" class="task_status" type="checkbox" name="task_status" <?php if( $task_info[$j]->stage_task_status == '1' ) { ?> checked="checked" <?php } ?> onclick="change_task_status(<?php echo $development_id; ?>,<?php echo $stage_id; ?>,<?php echo $phase_info[$i]->id; ?>,<?php echo $task_info[$j]->id; ?>,<?php if($task_info[$j]->stage_task_status == '1'){ echo '0'; }else{ echo '1'; } ?>)" /></div>
				<div style="clear:both;"></div>
			

					

					<!-- MODAL Task Edit -->
					<div id="EditTask_<?php echo $task_info[$j]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_task_update/<?php echo $task_info[$j]->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h3 id="myModalLabel">Edit Task</h3>
						</div>
						<div class="modal-body">
								
							<div class="control-group">
								<label class="control-label" for="task_name">Task Name </label>
								<div class="controls">
									<input required="" type="text" id="task_name" name="task_name" value="<?php echo $task_info[$j]->task_name; ?>">
								</div>
							</div>
							
							<div class="control-group" style="width: 85%;float:left;">
								<label class="control-label" for="task_start_date">Planned Start Date </label>
								<div class="controls">
									<input <?php echo $disabled; ?> required="" type="text" class="live_datepicker" name="task_start_date" value="<?php if($task_info[$j]->task_start_date != '1970-01-01' && $task_info[$j]->task_start_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($task_info[$j]->task_start_date); } ?>">
								</div>
							</div>
							<div class="control-group" style="width: 13%;float:right;margin: 0px 0 0px 2%;">
								<label class="control-label" for="task_start_date">Started </label>
								<div class="controls">
									<input <?php if($task_info[$j]->start_alert=='1'){ echo 'checked'; } ?> type="checkbox" class="start_alert" name="start_alert" value="1">
								</div>
							</div><div style="clear:both;"></div>

							<div class="control-group">
								<label class="control-label" for="planned_completion_date">Planned Completion Date </label>
								<div class="controls">
									<input <?php echo $disabled; ?> type="text" class="live_datepicker" id="" placeholder="" name="planned_completion_date" value="<?php if($task_info[$j]->planned_completion_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($task_info[$j]->planned_completion_date); } ?>">
								</div>
							</div>

							<div class="control-group" style="display: none;">
								<label class="control-label" for="actual_completion_date">Actual Completion Date </label>
								<div class="controls">
									<input type="text" class="live_datepicker" id="" placeholder="" name="actual_completion_date" value="<?php if($task_info[$j]->actual_completion_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($task_info[$j]->actual_completion_date); } ?>">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="task_person_responsible">Person Responsible</label>
								<div class="controls" style="clear: both;">
									<select name="task_person_responsible" class="form-control" id="fuzzOptionsList_<?php echo $task_info[$j]->id; ?>">
										<option value="">--Select a User--</option>
										<?php
										$user=  $this->session->userdata('user'); 
										$wp_company_id =$user->company_id;
					
										$this->db->where('users.company_id', $wp_company_id);
										$this->db->where('application_id', '1');
										$this->db->where_in('application_role_id', array('2','3','4'));
										$this->db->join('users', 'users.uid = users_application.user_id', 'left');
										$this->db->order_by('username', 'ASC');
										$results = $this->db->get('users_application')->result();
										foreach($results as $result){
										?>
										<option <?php if($task_info[$j]->task_person_responsible==$result->uid){ echo 'selected'; } ?> value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
										<?php
										}
										?>
									</select>
									<div id="fuzzSearch_<?php echo $task_info[$j]->id; ?>">
									  <div id="fuzzNameContainer">
									    <span class="fuzzName"></span>
									    <span class="fuzzArrow"></span>
									  </div>
									  <div id="fuzzDropdownContainer">
									    <input type="text" value="" class="fuzzMagicBox" placeholder="search.." />
									    <span class="fuzzSearchIcon"></span>
									    <ul id="fuzzResults">
									    </ul>
									  </div>
									</div>
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="task_phase_id">Phase</label>
								<div class="controls">
									<select name="task_phase_id" class="form-control" id="task_phase_id">
										<?php
										$this->db->where('development_id', $this->uri->segment(3));
										$this->db->where('stage_no', $this->uri->segment(4));
										$this->db->order_by('ordering', 'ASC');
										$results = $this->db->get('stage_phase')->result();
										foreach($results as $result){
										?>
										<option <?php if($phase_info[$i]->id==$result->id){ echo 'selected'; } ?> value="<?php echo $result->id; ?>"><?php echo $result->phase_name; ?></option>
										<?php
										}
										?>
									</select>
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
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
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
				</div>
				<div class="sub-task-contents" <?php if(($task_info[$j]->id) == ($this->uri->segment(6))){ echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
				<?php if($child_tasks): ?>
					<ul id="sortable-sub-task-<?php echo $task_info[$j]->id; ?>" class="">
					
					<?php 
					foreach($child_tasks as $ctask){ 
					
						$day_sign = '';
						$day_alert = '';
						$bg_color = 'yellow';
						$day_required = $ctask->task_length;

						if( $ctask->planned_completion_date != '0000-00-00' && $ctask->planned_completion_date != '1970-01-01')
						{
							$planned_completion_date = date('d-m-Y', strtotime($ctask->planned_completion_date));
						}
						else if( $ctask->task_start_date == '0000-00-00' || $ctask->task_start_date == '1970-01-01')
						{
							$planned_completion_date = '00-00-0000';
						}
						else
						{
							$created_date = date_create($ctask->task_start_date);
							$str = '21 days';
							$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
							$planned_completion_date =  date_format($pcdate, 'd-m-Y');
						}

						$now = date('Y-m-d');

						$today_time = strtotime($now);
						
						$pc_time = strtotime($planned_completion_date);
						$t20_time = strtotime($planned_completion_date .' - 20 days');
						
						if ($ctask->stage_task_status == '1' ) 
						{
							$rem_days = '';  
							$day_sign = '';
							$day_alert = "-";
							$bg_color = '<div style="height:20px; width:20px; border-radius:15px; background-color:none;"></div>';
						}
						elseif( $ctask->task_start_date == '0000-00-00' || $ctask->task_start_date == '1970-01-01')
						{
							$rem_days = '';  
							$day_sign = '';
							$day_alert = "Dates Required";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:grey;"></div><div class="hover">This task<br>has no dates<br>entered</div></div>';
						}
						elseif ($today_time < $pc_time && $today_time < $t20_time) 
						{
							$rem_days = date_diff(date_create($planned_completion_date),date_create($now))->format("%a");  
							$day_sign = '';
							$day_alert = " Days Remaining";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:grey;"></div><div class="hover">This task is over 21<br>Calendar days away<br>from completion</div></div>';
						}
						elseif ($today_time < $pc_time || $today_time == $pc_time) 
						{
							$rem_days = date_diff(date_create($planned_completion_date),date_create($now))->format("%a");  
							$day_sign = '';
							$day_alert = " Days Remaining";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:orange;"></div><div class="hover">This task is within<br>20 Calendar days<br>from completion</div></div>';
						}
						elseif($today_time > $pc_time)
						{
							$rem_days = date_diff(date_create($planned_completion_date),date_create($now))->format("%a");  
							$day_sign = '';
							$day_alert = " Days Over";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:red;"></div><div class="hover">This task is<br>overdue</div></div>';
						}

						$per_res = $ctask->task_person_responsible;
						
						if ($per_res > 0 && $ctask->task_start_date > '0000-00-00' && $ctask->stage_task_status == '0') 
						{
							$s_date = $ctask->task_start_date;
							$c_s_date = date('Y-m-d', strtotime($s_date. ' + 10 days'));
							$now = date('Y-m-d');
							if($ctask->start_alert=='1'){
								$start_alert = '<img width="22" height="22" src="'.base_url().'images/icon/start_alert_gray_color.png" />';
							}else if($now > $c_s_date){
								$start_alert = '<img width="22" height="22" src="'.base_url().'images/icon/start_alert.png" />';
							}else{
								$start_alert = '&nbsp;&nbsp;';
							}
						}
						else
						{
							$start_alert = '&nbsp;&nbsp;';
						}
					?>
<script>
$(function() {
   	$('#fuzzOptionsList_Sub_Edit_<?php echo $ctask->id; ?>').fuzzyDropdown({
      mainContainer: '#fuzzSearch_Sub_Edit_<?php echo $ctask->id; ?>',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });
});
</script>

						<li id="listItemSubTask_<?php echo $ctask->id; ?>">

						<!-- sub task start -->
						<div class="uncol" style="width:6%;padding-left: 10px;"><?php echo $per2; ?>
							<a <?php echo $per1; ?> href="#DeleteSubTask_<?php echo $ctask->id; ?>" title="Task Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
							<a <?php echo $per1; ?> href="#EditSubTask_<?php echo $ctask->id; ?>" title="Task Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php echo base_url();?>icon/icon_edit.png" /></a>
						</div>
						<div class="uncol" style="width:16%;padding-left: 35px;"><?php if( $ctask->stage_task_status == '1' ) { ?><img style="margin-right:5px;" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" /><?php } ?><?php if(isset($ctask->task_name)) echo $ctask->task_name; ?></div>				
						<div class="uncol" style="width:9%"><?php echo $start_alert; ?></div>	
						<div class="uncol" style="width:15%;"><?php if($ctask->task_start_date > '0000-00-00'){ echo date('d-m-Y', strtotime($ctask->task_start_date)); }else{ echo '00-00-0000'; } ?></div>
						<div class="uncol" style="width:15%"><?php echo $planned_completion_date; ?></div>
						<div class="uncol" style="width:15%;display: none;"><a href="#EditActualDate_<?php echo $ctask->id; ?>" role="button" data-toggle="modal"><?php if(isset($ctask->actual_completion_date)) { echo date('d-m-Y', strtotime($ctask->actual_completion_date)); } ?></a></div>
						<div class="uncol" style="width:15%"><?php if(isset($ctask->username)){ echo $ctask->username; }else{ echo '&nbsp;'; } ?></div>
						<div class="uncol" style="width:10%"><?php //echo $day_sign.$rem_days.$day_alert; ?>&nbsp;&nbsp;</div>
						<div class="uncol" style="width:6%"><?php echo $bg_color; ?></div>
						<div class="uncol" style="width:8%;padding-right: 11px;"><?php echo $per2; ?><input <?php echo $per1; ?> id="task_status_<?php echo $ctask->id; ?>" class="task_status" type="checkbox" name="task_status" <?php if( $ctask->stage_task_status == '1' ) { ?> checked="checked" <?php } ?> onclick="change_task_status(<?php echo $development_id; ?>,<?php echo $stage_id; ?>,<?php echo $phase_info[$i]->id; ?>,<?php echo $ctask->id; ?>,<?php if($ctask->stage_task_status == '1'){ echo '0'; }else{ echo '1'; } ?>)" /></div>
					<div style="clear:both;"></div>
				

						<!-- MODAL Task Edit -->
						<div id="EditSubTask_<?php echo $ctask->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_task_update/<?php echo $ctask->id; ?>/<?php echo $task_info[$j]->id; ?>" method="POST">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								<h3 id="myModalLabel">Edit Sub Task</h3>
							</div>
							<div class="modal-body">
									
								<div class="control-group">
									<label class="control-label" for="task_name">Sub Task Name </label>
									<div class="controls">
										<input required="" type="text" id="task_name" name="task_name" value="<?php echo $ctask->task_name; ?>">
									</div>
								</div>
								
								<div class="control-group" style="width: 85%;float:left;">
									<label class="control-label" for="task_start_date">Planned Start Date </label>
									<div class="controls">
										<input <?php echo $disabled; ?> required="" type="text" class="live_datepicker" name="task_start_date" value="<?php if($ctask->task_start_date != '1970-01-01' && $ctask->task_start_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($ctask->task_start_date); } ?>">
									</div>
								</div>
								<div class="control-group" style="width: 13%;float:right;margin: 0px 0 0px 2%;">
									<label class="control-label" for="task_start_date">Started </label>
									<div class="controls">
										<input <?php if($ctask->start_alert=='1'){ echo 'checked'; } ?> type="checkbox" class="start_alert" name="start_alert" value="1">
									</div>
								</div><div style="clear:both;"></div>

								<div class="control-group">
									<label class="control-label" for="planned_completion_date">Planned Completion Date </label>
									<div class="controls">
										<input <?php echo $disabled; ?> type="text" class="live_datepicker" id="" placeholder="" name="planned_completion_date" value="<?php if($ctask->planned_completion_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($ctask->planned_completion_date); } ?>">
									</div>
								</div>

								<div class="control-group" style="display: none;">
									<label class="control-label" for="actual_completion_date">Actual Completion Date </label>
									<div class="controls">
										<input type="text" class="live_datepicker" id="" placeholder="" name="actual_completion_date" value="<?php if($ctask->actual_completion_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($ctask->actual_completion_date); } ?>">
									</div>
								</div>

								<div class="control-group">
									<label class="control-label" for="task_person_responsible">Person Responsible</label>
									<div class="controls" style="clear: both;">
										<select name="task_person_responsible" class="form-control" id="fuzzOptionsList_Sub_Edit_<?php echo $ctask->id; ?>">
											<option value="">--Select a User--</option>
											<?php
											$user=  $this->session->userdata('user'); 
											$wp_company_id =$user->company_id;
						
											$this->db->where('users.company_id', $wp_company_id);
											$this->db->where('application_id', '1');
											$this->db->where_in('application_role_id', array('2','3','4'));
											$this->db->join('users', 'users.uid = users_application.user_id', 'left');
											$this->db->order_by('username', 'ASC');
											$results = $this->db->get('users_application')->result();
											foreach($results as $result){
											?>
											<option <?php if($ctask->task_person_responsible==$result->uid){ echo 'selected'; } ?> value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
											<?php
											}
											?>
										</select>
										<div id="fuzzSearch_Sub_Edit_<?php echo $ctask->id; ?>">
										  <div id="fuzzNameContainer">
										    <span class="fuzzName"></span>
										    <span class="fuzzArrow"></span>
										  </div>
										  <div id="fuzzDropdownContainer">
										    <input type="text" value="" class="fuzzMagicBox" placeholder="search.." />
										    <span class="fuzzSearchIcon"></span>
										    <ul id="fuzzResults">
										    </ul>
										  </div>
										</div>
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="inputPassword"></label>
									<div class="controls">
										<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
										<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $this->uri->segment(4); ?>">
										<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
										<input type="hidden" id="phase_id" name="task_phase_id" value="<?php echo $phase_info[$i]->id; ?>">
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
						<div id="DeleteSubTask_<?php echo $ctask->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<form class="form-horizontal" action="<?php echo base_url();?>stage/stage_task_delete/<?php echo $ctask->id; ?>/<?php echo $task_info[$j]->id; ?>" method="POST">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
								<h3 id="myModalLabel">Delete Sub Task: <?php echo $ctask->task_name; ?></h3>
							</div>
							<div class="modal-body">
								<p>Are you sure want to delete this Sub Task?</p>
							
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
						
						</li>
						<?php } //end foreach ?>
					</ul>
				<?php endif; //end if condition ?>
				</div>
				
				</li>
			<?php } ?>
				
			</ul>
			</div>
		</div>
	  	</li>
			
		<?php
		}
		?>
		
	</ul>
	<div id="drop-phase"></div>
</div>

<script>
$(function() {
   	$('#fuzzOptionsListPhase').fuzzyDropdown({
      mainContainer: '#fuzzSearchPhase',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });
});
</script>

		<!-- MODAL Phase Add -->
		<div id="AddPhase" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form class="form-horizontal" action="<?php echo base_url(); ?>stage/stage_phase_add" method="POST">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
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
						<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="planned_start_date" value="" required="">
					</div>
				</div>
				<div class="control-group">
					<label class="control-label" for="planned_finished_date">Planned Completion Date </label>
					<div class="controls">
						<input <?php echo $disabled; ?> type="text" class="live_datepicker" id="" placeholder="" name="planned_finished_date" value="">
					</div>
				</div>

				<div class="control-group">
					<label class="control-label" for="phase_person_responsible">Person Responsible</label>
					<div class="controls" style="clear: both;">
						<select name="phase_person_responsible" class="form-control" id="fuzzOptionsListPhase">
							<option value="">--Select a User--</option>
							<?php
							$user=  $this->session->userdata('user'); 
							$wp_company_id =$user->company_id;
		
							$this->db->where('users.company_id', $wp_company_id);
							$this->db->where('application_id', '1');
							$this->db->where_in('application_role_id', array('2','3','4'));
							$this->db->join('users', 'users.uid = users_application.user_id', 'left');
							$this->db->order_by('username', 'ASC');
							$results = $this->db->get('users_application')->result();
							foreach($results as $result){
							?>
							<option value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
							<?php
							}
							?>
						</select>
						<div id="fuzzSearchPhase">
						  <div id="fuzzNameContainer">
						    <span class="fuzzName"></span>
						    <span class="fuzzArrow"></span>
						  </div>
						  <div id="fuzzDropdownContainer">
						    <input type="text" value="" class="fuzzMagicBox" placeholder="search.." />
						    <span class="fuzzSearchIcon"></span>
						    <ul id="fuzzResults">
						    </ul>
						  </div>
						</div>
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



<?php echo max($ac_show); if($this->uri->segment(5) || $ac_show1=='1'){ ?>		
<script type="text/javascript">
    
$(document).ready(function () {
		
	$('.accordions').each(function(){
			
			// Set First Accordion As Active
			//$(this).find('.accordion-content').hide();
			if(!$(this).hasClass('toggles')){
				//$(this).find('.accordion:first-child').addClass('accordion-active');
				//$(this).find('.accordion:first-child .accordion-content').show();
			}
			
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
				
			});
			
			$('.sub-tasks').each(function(){
				
				//$(this).find('.sub-task-content').hide();
				if(!$(this).hasClass('toggles')){
					//$(this).find('.sub-task:first-child').addClass('sub-task-active');
					//$(this).find('.sub-task:first-child .sub-task-content').show();
				}
				// Set First Accordion As Active
				$(this).find('.sub-task-headers').click(function(){
					
					if(!$(this).parent().hasClass('sub-task-active')){
						
						// Close other accordions
						if(!$(this).parent().parent().hasClass('toggles')){
							$(this).parent().parent().find('.sub-task-active').removeClass('sub-task-active').find('.sub-task-contents').slideUp(300);
						}
						
						// Open Accordion
						$(this).parent().addClass('sub-task-active');
						$(this).parent().find('.sub-task-contents').slideDown(300);
					
					}else{
						
						// Close Accordion
						$(this).parent().removeClass('sub-task-active');
						$(this).parent().find('.sub-task-contents').slideUp(300);
						
					}
				
				});
			});
		
	});	
});
</script>
<?php }else{ ?>
<script type="text/javascript">
    
$(document).ready(function () {
		
	$('.accordions').each(function(){
			
			// Set First Accordion As Active
			$(this).find('.accordion-content').hide();
			if(!$(this).hasClass('toggles')){
				$(this).find('.accordion:first-child').addClass('accordion-active');
				$(this).find('.accordion:first-child .accordion-content').show();
			}
			
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
				
			});
			
			$('.sub-tasks').each(function(){
				
				//$(this).find('.sub-task-content').hide();
				if(!$(this).hasClass('toggles')){
					//$(this).find('.sub-task:first-child').addClass('sub-task-active');
					//$(this).find('.sub-task:first-child .sub-task-content').show();
				}
				// Set First Accordion As Active
				$(this).find('.sub-task-headers').click(function(){
					
					if(!$(this).parent().hasClass('sub-task-active')){
						
						// Close other accordions
						if(!$(this).parent().parent().hasClass('toggles')){
							$(this).parent().parent().find('.sub-task-active').removeClass('sub-task-active').find('.sub-task-contents').slideUp(300);
						}
						
						// Open Accordion
						$(this).parent().addClass('sub-task-active');
						$(this).parent().find('.sub-task-contents').slideDown(300);
					
					}else{
						
						// Close Accordion
						$(this).parent().removeClass('sub-task-active');
						$(this).parent().find('.sub-task-contents').slideUp(300);
						
					}
				
				});
			});
		
	});	
});
</script>
<?php } ?>

<script>
	jQuery(document).ready(function(){

		$('.modal form').ajaxForm({
			success:function(data) {
				newurl = window.Url + 'stage/'+data;
				window.location = newurl;	  
			},			
			beforeSubmit:function(){
				var overlay = jQuery('<div id="overlay"></div>');
				overlay.appendTo(document.body);
			}
		});
	});
</script>