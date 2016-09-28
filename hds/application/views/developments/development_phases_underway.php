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

	<div class="task-phase-add" <?php echo $per1; ?> >
		<ul class="drag-phase-task">
			<li id="draggable-task"><a style="color:#000;" href="#AddSubTask_<?php if($this->uri->segment(4)){ echo $this->uri->segment(4); }else{ echo $development_phase_info[0]->id; } ?>" id="taskid" role="button" data-toggle="modal">Add Sub Task +</a></li>
			<li id="draggable-task"><a style="color:#000;" href="#AddTask_<?php if($this->uri->segment(4)){ echo $this->uri->segment(4); }else{ echo $development_phase_info[0]->id; } ?>" id="phaseid" role="button" data-toggle="modal">Add Task +</a></li>
			<li id="draggable-phase"><a style="color:#000;" href="#AddPhase" title="Phase Add" role="button" data-toggle="modal">Add Phase +</a></li>
		</ul>
		<div style="clear:both;"></div>
	</div>
	<div style="clear:both;"></div>

	<div id="underway_header">
		<div class="uhead" style="width:6%"></div>
		<div class="uhead" style="width:16%">Task Name</div>
		<div class="uhead" style="width:9%">Start Alert</div>
		<div class="uhead" style="width:15%">Planned Start Date</div>
		<div class="uhead" style="width:15%">Planned Completion Date</div>
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
function change_phase_status(development_id,stage_no,phase_id,checked)
{

	$.ajax({
			url: window.Url + 'developments/update_phase_status/' + phase_id + '/' + checked,
			type: 'GET',
			cache: false,
			success: function(data) 
			{
				//location.reload();
				newurl = window.Url + 'developments/phases_underway/' + development_id + '/' + stage_no;
				window.location = newurl;		        
			},
			        
		});

}

function change_development_phase_task_status(development_id,phase_id,task_id,checked)
{

	$.ajax({
			url: window.Url + 'developments/update_development_phase_task_status/' + task_id + '/' + checked,
			type: 'GET',
			cache: false,
			success: function(data) 
			{
				//alert(data);
				newurl = window.Url + 'developments/phases_underway/' + development_id + '/' + phase_id;
				window.location = newurl;	        
			},
			        
		});

}


function change_all_phase_task_status(development_id,phase_id,checked)
{

	$.ajax({
			url: window.Url + 'developments/update_all_phase_task_status/'+ development_id + '/' + phase_id + '/' + checked,
			type: 'GET',
			cache: false,
			success: function(data) 
			{

				//alert(data);
				newurl = window.Url + 'developments/phases_underway/' + development_id + '/' + phase_id;
				window.location = newurl;	 
     
			},
			        
		});
}

function change_all_stage_phase_status(development_id,stage_no,checked)
{

	$.ajax({
			url: window.Url + 'developments/update_all_stage_phase_status/'+ development_id + '/' + stage_no + '/' + checked,
			type: 'GET',
			cache: false,
			success: function(data) 
			{
				//location.reload();
				newurl = window.Url + 'developments/phases_underway/' + development_id + '/' + stage_no;
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
				url: window.Url + 'admindevelopment/development_phase_ordering',
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
	
		$stages = $stages_no[0]->number_of_stages;
		$development_id = $stages_no[0]->id;
		$now = date('Y-m-d');
		$today_time = strtotime($now);
	?>
	
	<ul id="accordions" class="accordions <?php echo $per; ?>sortable-phase">

		<?php

		//print_r($development_phase_info);
		$phase_number = count($development_phase_info);

		for($p=0; $p < $phase_number; $p++)
		{
			if( $development_phase_info[$p]->planned_finished_date != '0000-00-00' && $development_phase_info[$p]->planned_finished_date != '1970-01-01')
			{
				$planned_finished_date = date('d-m-Y', strtotime($development_phase_info[$p]->planned_finished_date));
			}
			else if( $development_phase_info[$p]->planned_start_date == '0000-00-00' || $development_phase_info[$p]->planned_start_date == '1970-01-01')
			{
				$planned_finished_date = '00-00-0000';
			}
			else
			{
				$created_date = date_create($development_phase_info[$p]->planned_start_date);
				$str = '21 days';
				$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
				$planned_finished_date =  date_format($pcdate, 'd-m-Y');
			}
			
			$ci =&get_instance();
			$ci->load->model('developments_model');
			$all_phase_task = $ci->developments_model->get_all_development_phase_status($development_id,$development_phase_info[$p]->id)->result();
		?>
		<li onclick="TransferPhaseId(<?php echo $development_phase_info[$p]->id; ?>);" id="listItemPhase_<?php echo $development_phase_info[$p]->id; ?>" class="accordion <?php $ph = $this->uri->segment(4); if($development_phase_info[$p]->id==$ph){ echo 'accordion-active'; } ?>">

		<div class="accordion-header">
				<div class="uncol1" style="width:6%;"><?php echo $per2; ?>
					<a <?php echo $per1; ?> href="#DevPhaseDelete_<?php echo $development_phase_info[$p]->id; ?>" title="Phase Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
					<a <?php echo $per1; ?> href="#DevPhaseEdit_<?php echo $development_phase_info[$p]->id; ?>" title="Phase Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php echo base_url();?>icon/icon_edit.png" /></a>
				</div>
				<div class="uncol1" style="width:16%;"><?php if(isset($all_phase_task[0]->all_task_status) && $all_phase_task[0]->all_task_status == 1 || $development_phase_info[$p]->phase_status == 1){ ?><img style="margin:0 4px 0 0px" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" /><?php } ?><?php echo $development_phase_info[$p]->phase_name; ?></div>
			    <div class="uncol1" style="width:9%;">&nbsp;&nbsp;</div>	
				<div class="uncol1" style="width:15%;"><?php if($development_phase_info[$p]->planned_start_date != '1970-01-01' && $development_phase_info[$p]->planned_start_date != '0000-00-00'){ echo date('d-m-Y', strtotime($development_phase_info[$p]->planned_start_date)); }else{ echo '00-00-0000'; } ?></div>
				<div class="uncol1" style="width:15%;"><?php echo $planned_finished_date; ?></div>
				<div class="uncol1" style="width:15%;"><?php if($development_phase_info[$p]->username){ echo $development_phase_info[$p]->username; }else{ echo '&nbsp;&nbsp;'; } ?></div>
				<div class="uncol1" style="width:10%;">&nbsp;&nbsp;</div>
				<div class="uncol1" style="width:6%;">&nbsp;&nbsp;</div>
				<div class="uncol1" style="width:8%;text-align: right;"><?php echo $per2; ?> 
					<input <?php echo $per1; ?> type="checkbox" name="all_phase_task" id="all_phase_task" <?php if(isset($all_phase_task[0]->all_task_status) && $all_phase_task[0]->all_task_status == 1 || $development_phase_info[$p]->phase_status == 1){ ?> checked="checked" <?php } ?> onclick="change_all_phase_task_status(<?php echo $development_id; ?>,<?php echo $development_phase_info[$p]->id; ?>,<?php if($all_phase_task[0]->all_task_status == 1 || $development_phase_info[$p]->phase_status == 1){ echo '0'; }else{ echo '1'; } ?>)" />
				</div>
				<!-- MODAL Phase Delete-->
					<div id="DevPhaseDelete_<?php echo $development_phase_info[$p]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url();?>developments/development_phase_delete/<?php echo $development_phase_info[$p]->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h3 id="myModalLabel">Delete Phase: <?php echo $development_phase_info[$p]->phase_name; ?></h3>
						</div>
						<div class="modal-body">
							<p>Are you sure want to delete this Phase?</p>
					    
						</div>
						<div class="modal-footer delete-task">
							<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
							<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
							<input type="submit" value="Ok" name="submit" class="btn" />
						</div>
					</form>
					</div>
					<!-- MODAL Phase Delete-->

					<script>
					$(function() {
					   	$('#fuzzOptionsList_Phase_<?php echo $development_phase_info[$p]->id; ?>').fuzzyDropdown({
					      mainContainer: '#fuzzSearch_Phase_<?php echo $development_phase_info[$p]->id; ?>',
					      arrowUpClass: 'fuzzArrowUp',
					      selectedClass: 'selected',
					      enableBrowserDefaultScroll: true
					    });
					});
					</script>

					<!-- MODAL Phase Edit -->
					<div id="DevPhaseEdit_<?php echo $development_phase_info[$p]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url(); ?>developments/development_phase_update/<?php echo $development_phase_info[$p]->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h3 id="myModalLabel">Edit Phase</h3>
						</div>
						<div class="modal-body">

							<div class="control-group">
								<label class="control-label" for="phase_name">Phase Name </label>
								<div class="controls">
									<input type="text" id="phase_name" name="phase_name" value="<?php echo $development_phase_info[$p]->phase_name; ?>">
								</div>
							</div>
							
							<div class="control-group">
								<label class="control-label" for="planned_start_date">Planned Start Date </label>
								<div class="controls">
									<input <?php echo $disabled; ?> required="" type="text" class="live_datepicker" name="planned_start_date" value="<?php if($development_phase_info[$p]->planned_start_date != '1970-01-01' && $development_phase_info[$p]->planned_start_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($development_phase_info[$p]->planned_start_date); } ?>">
								</div>
							</div>
							<div class="control-group">
								<label class="control-label" for="planned_finished_date">Planned Completion Date </label>
								<div class="controls">
									<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="planned_finished_date" value="<?php if($development_phase_info[$p]->planned_finished_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($development_phase_info[$p]->planned_finished_date); } ?>">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="phase_person_responsible">Person Responsible</label>
								<div class="controls" style="clear: both;">
									<select name="phase_person_responsible" class="form-control" id="fuzzOptionsList_Phase_<?php echo $development_phase_info[$p]->id; ?>">
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
										<option <?php if($development_phase_info[$p]->phase_person_responsible==$result->uid){ echo 'selected'; } ?> value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
										<?php
										}
										?>
									</select>
										<div id="fuzzSearch_Phase_<?php echo $development_phase_info[$p]->id; ?>">
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
									<input type="hidden" id="development_id" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
									
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

		<div class="accordion-content" style="<?php $ph = $this->uri->segment(4); if($development_phase_info[$p]->id==$ph){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">


		<script>
		$(function() {
		   	$('#fuzzOptionsList_<?php echo $development_phase_info[$p]->id; ?>').fuzzyDropdown({
		      mainContainer: '#fuzzSearch_<?php echo $development_phase_info[$p]->id; ?>',
		      arrowUpClass: 'fuzzArrowUp',
		      selectedClass: 'selected',
		      enableBrowserDefaultScroll: true
		    });
		});
		</script>	

<!-- MODAL Task Add -->
<div id="AddTask_<?php echo $development_phase_info[$p]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form class="form-horizontal" action="<?php echo base_url(); ?>developments/development_task_add" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3 id="myModalLabel">Add Task</h3>
	</div>
	<div class="modal-body">
			
		<div class="control-group">
			<label class="control-label" for="task_name">Task Name </label>
			<div class="controls">
				<input type="text" id="task_name" name="task_name" value="" required="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="task_start_date">Planned Start Date </label>
			<div class="controls">
				<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="task_start_date" value="" required="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="planned_completion_date">Planned Completion Date </label>
			<div class="controls">
				<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="actual_completion_date" value="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="task_person_responsible">Person Responsible</label>
			<div class="controls" style="clear: both;">
				<select name="task_person_responsible" class="form-control" id="fuzzOptionsList_<?php echo $development_phase_info[$p]->id; ?>">
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
					<option  value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
					<?php
					}
					?>
				</select>
					<div id="fuzzSearch_<?php echo $development_phase_info[$p]->id; ?>">
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
				<input type="hidden" id="development_id" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
				<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $development_phase_info[$p]->id; ?>">
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
		   	$('#fuzzOptionsList_Sub_<?php echo $development_phase_info[$p]->id; ?>').fuzzyDropdown({
		      mainContainer: '#fuzzSearch_Sub_<?php echo $development_phase_info[$p]->id; ?>',
		      arrowUpClass: 'fuzzArrowUp',
		      selectedClass: 'selected',
		      enableBrowserDefaultScroll: true
		    });
		});
		</script>

<!-- MODAL Sub Task Add -->
<div id="AddSubTask_<?php echo $development_phase_info[$p]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form class="form-horizontal" action="<?php echo base_url(); ?>developments/development_task_add" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3 id="myModalLabel">Add Sub Task</h3>
	</div>
	<div class="modal-body">
			
		<div class="control-group">
			<label class="control-label" for="task_name">Parent Task </label>
			<div class="controls">
				<?php
					$this->db->where('phase_id',$development_phase_info[$p]->id);
					$this->db->where('parent_task_id',0);
					$tasks = $this->db->get('development_task')->result();
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
			<label class="control-label" for="task_start_date">Sub Task Name</label>
			<div class="controls">
				<input type="text" id="task_name" name="task_name" value="" required="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="task_start_date">Planned Start Date </label>
			<div class="controls">
				<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="task_start_date" value="" required="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="planned_completion_date">Planned Completion Date </label>
			<div class="controls">
				<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="actual_completion_date" value="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="task_person_responsible">Person Responsible</label>
			<div class="controls" style="clear: both;">
				<select name="task_person_responsible" class="form-control" id="fuzzOptionsList_Sub_<?php echo $development_phase_info[$p]->id; ?>">
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
					<option  value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
					<?php
					}
					?>
				</select>
					<div id="fuzzSearch_Sub_<?php echo $development_phase_info[$p]->id; ?>">
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
				<input type="hidden" id="development_id" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
				<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $development_phase_info[$p]->id; ?>">
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
	if (jQuery("#sortable-task-<?php echo $development_phase_info[$p]->id; ?>").length){
		$( "#sortable-task-<?php echo $development_phase_info[$p]->id; ?>" ).sortable({
			update : function () { 
			var order = $('#sortable-task-<?php echo $development_phase_info[$p]->id; ?>').sortable('serialize');
			$.ajax({
				url: window.Url + 'admindevelopment/development_task_ordering',
				type: 'POST',
				data: order,
				success: function(data) 
				{
 
				},
			        
			});
			}
		});	
		$( "#sortable-task-<?php echo $development_phase_info[$p]->id; ?>" ).disableSelection();
	}
});
</script>

		<div class="dev-sub-tasks">
		
		<ul id="<?php echo $per; ?>sortable-task-<?php echo $development_phase_info[$p]->id; ?>" class="sub-tasks stage_content dropable-task-<?php echo $development_phase_info[$p]->id; ?>">
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

				if( $development_phase_task_info[$t]->task_start_date == '0000-00-00' || $development_phase_task_info[$t]->task_start_date == '1970-01-01')
				{

					$task_planned_finished_date = '00-00-0000' ;
				}
				else
				{
					$task_planned_finished_date = date('d-m-Y', strtotime($development_phase_task_info[$t]->task_start_date));
				}

				if( $development_phase_task_info[$t]->actual_completion_date!='0000-00-00' && $development_phase_task_info[$t]->actual_completion_date!='1970-01-01')
				{
					$task_planned_actual_date_1 = date('d-m-Y', strtotime($development_phase_task_info[$t]->actual_completion_date));
				}
				else if( $development_phase_task_info[$t]->task_start_date == '0000-00-00' || $development_phase_task_info[$t]->task_start_date == '1970-01-01')
				{
					$task_planned_actual_date_1 = '00-00-0000';
				}
				else
				{
					$created_date = date_create($development_phase_task_info[$t]->task_start_date);
					$str = '21 days';
					$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
					$task_planned_actual_date_1 =  date_format($pcdate, 'd-m-Y');
				}

				$pc_time = strtotime($task_planned_actual_date_1);
				$t20_time = strtotime($task_planned_actual_date_1 .' - 20 days');

				if ($development_phase_task_info[$t]->development_task_status == '1') 
				{
					$rem_days = '';
					$day_sign = ' ';
					$day_alert = "-";
					$phase_bg_color = '<div style="height:20px; width:20px; border-radius:15px; background-color:none;"></div>';
				}
				else if( $development_phase_task_info[$t]->task_start_date == '0000-00-00' || $development_phase_task_info[$t]->task_start_date == '1970-01-01')
				{
					$rem_days = '';
					$day_sign = ' ';
					$day_alert = "Dates Required";
					$phase_bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:grey;"></div><div class="hover">This task<br>has no dates<br>entered</div></div>';
				}
				elseif ($today_time < $pc_time && $today_time < $t20_time) 
				{
					$rem_days = date_diff(date_create($task_planned_actual_date_1),date_create($now))->format("%a");
					$day_sign = '';
					$day_alert = " Days Remaining";
					$phase_bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:grey;"></div><div class="hover">This task is over 21<br>Calendar days away<br>from completion</div></div>';
				}
				elseif ($today_time < $pc_time || $today_time == $pc_time) 
				{
					$rem_days = date_diff(date_create($task_planned_actual_date_1),date_create($now))->format("%a");
					$day_sign = '';
					$day_alert = " Days Remaining";
					$phase_bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:orange;"></div><div class="hover">This task is within<br>20 Calendar days<br>from completion</div></div>';
				}
				elseif($today_time > $pc_time)
				{
					$rem_days = date_diff(date_create($task_planned_actual_date_1),date_create($now))->format("%a");
					$day_sign = '';
					$day_alert = " Days Over";
					$phase_bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:red;"></div><div class="hover">This task is<br>overdue</div></div>';
				}

				$per_res = $development_phase_task_info[$t]->task_person_responsible;
						
				if ($per_res > 0 && $development_phase_task_info[$t]->task_start_date > '0000-00-00' && $development_phase_task_info[$t]->development_task_status == '0') 
				{
					$s_date = $development_phase_task_info[$t]->task_start_date;
					$c_s_date = date('Y-m-d', strtotime($s_date. ' + 10 days'));
					$now = date('Y-m-d');
					if($development_phase_task_info[$t]->start_alert=='1'){
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
   	$('#fuzzOptionsList_<?php echo $development_phase_task_info[$t]->id; ?>').fuzzyDropdown({
      mainContainer: '#fuzzSearch_<?php echo $development_phase_task_info[$t]->id; ?>',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });
});
</script>

			
				<?php
					$child_tasks = $ci->developments_model->get_development_phase_task_info($development_id,$development_phase_info[$p]->id,$development_phase_task_info[$t]->id)->result();
				?>

			


			<li id="listItemTask_<?php echo $development_phase_task_info[$t]->id; ?>" class="sub-task <?php if(($development_phase_task_info[$t]->id) == ($this->uri->segment(5))){ echo 'sub-task-active'; } ?>">
				<div class="sub-task-headers">
					<div class="uncol" style="width:6%;padding-left: 10px;"><?php echo $per2; ?>
						<a <?php echo $per1; ?> href="#DevTaskDelete_<?php echo $development_phase_task_info[$t]->id; ?>" title="Task Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
						<a <?php echo $per1; ?> href="#DevTaskEdit_<?php echo $development_phase_task_info[$t]->id; ?>" title="Task Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php echo base_url();?>icon/icon_edit.png" /></a>
					</div>
					<div class="uncol" style="width:16%">
					<?php if($child_tasks): ?><img width="16" height="16" src="<?php echo base_url();?>images/icon/has_child.png" /><?php endif; ?>
					<?php if( $development_phase_task_info[$t]->development_task_status == '1' ) { ?><img style="margin:0 4px 0 0px" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" /><?php } ?><?php if(isset($development_phase_task_info[$t]->task_name)) { echo $development_phase_task_info[$t]->task_name; } ?></div>				
					<div class="uncol" style="width:9%"><?php echo $start_alert; ?></div>
					<div class="uncol" style="width:15%;"><?php if(isset($task_planned_finished_date)) { echo $task_planned_finished_date; } ?></a></div>

					<div class="uncol" style="width:15%"><?php echo $task_planned_actual_date_1; ?></div>

					<div class="uncol" style="width:15%"><?php if($development_phase_task_info[$t]->username){ echo $development_phase_task_info[$t]->username; }else{ echo '&nbsp;'; } ?></div>
					<div class="uncol" style="width:10%"><?php //echo $day_sign.$rem_days.$day_alert; ?>&nbsp;&nbsp;</div>

					<div class="uncol" style="width:6%;"><?php echo $phase_bg_color; ?></div>
					<div class="uncol" style="width:8%;text-align:right;"><?php echo $per2; ?><input <?php echo $per1; ?> id="phase_status_<?php echo $development_phase_task_info[$t]->id; ?>" type="checkbox" name="phase_status" <?php if( $development_phase_task_info[$t]->development_task_status == '1' ) { ?> checked="checked" <?php } ?> onclick="change_development_phase_task_status(<?php echo $development_id; ?>,<?php echo $development_phase_info[$p]->id; ?>,<?php echo $development_phase_task_info[$t]->id; ?>,<?php if($development_phase_task_info[$t]->development_task_status==1){ echo '0'; }else{ echo '1'; } ?>)" /></div>
					<div style="clear:both;"></div>

					<!-- MODAL Task Edit -->
					<div id="DevTaskEdit_<?php echo $development_phase_task_info[$t]->id; ?>" class="modal hide fade stage-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url(); ?>developments/development_task_update/<?php echo $development_phase_task_info[$t]->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h3 id="myModalLabel">Edit Task</h3>
						</div>
						<div class="modal-body">
								
							<div class="control-group">
								<label class="control-label" for="task_name">Task Name </label>
								<div class="controls">
									<input required="" type="text" id="task_name" name="task_name" value="<?php echo $development_phase_task_info[$t]->task_name; ?>">
								</div>
							</div>
							
							<div class="control-group" style="width: 85%;float:left;">
								<label class="control-label" for="task_start_date">Planned Start Date </label>
								<div class="controls">
									<input <?php echo $disabled; ?> required="" type="text" class="live_datepicker" placeholder="" name="task_start_date" value="<?php if($development_phase_task_info[$t]->task_start_date != '1970-01-01' && $development_phase_task_info[$t]->task_start_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($development_phase_task_info[$t]->task_start_date); } ?>">
								</div>
							</div>
							<div class="control-group" style="width: 13%;float:right;margin: 0px 0 0px 2%;">
								<label class="control-label" for="task_start_date">Started </label>
								<div class="controls">
									<input <?php if($development_phase_task_info[$t]->start_alert=='1'){ echo 'checked'; } ?> type="checkbox" class="start_alert" name="start_alert" value="1">
								</div>
							</div><div style="clear:both;"></div>
							<div class="control-group">
								<label class="control-label" for="planned_completion_date">Planned Completion Date </label>
								<div class="controls">
									<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="actual_completion_date" value="<?php if($development_phase_task_info[$t]->actual_completion_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($development_phase_task_info[$t]->actual_completion_date); } ?>">
								</div>
							</div>

							<div class="control-group">
								<label class="control-label" for="task_person_responsible">Person Responsible</label>
								<div class="controls" style="clear: both;">
									<select name="task_person_responsible" class="form-control" id="fuzzOptionsList_<?php echo $development_phase_task_info[$t]->id; ?>">
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
										<option <?php if($development_phase_task_info[$t]->task_person_responsible==$result->uid){ echo 'selected'; } ?> value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
										<?php
										}
										?>
									</select>
										<div id="fuzzSearch_<?php echo $development_phase_task_info[$t]->id; ?>">
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
									<input type="hidden" id="development_id" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
									<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $development_phase_info[$p]->id; ?>">
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
					<div id="DevTaskDelete_<?php echo $development_phase_task_info[$t]->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url();?>developments/development_task_delete/<?php echo $development_phase_task_info[$t]->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h3 id="myModalLabel">Delete Task: <?php echo $development_phase_task_info[$t]->task_name; ?></h3>
						</div>
						<div class="modal-body">
							<p>Are you sure want to delete this Task?</p>
						
						</div>
						<div class="modal-footer delete-task">
							<input type="hidden" id="development_id" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
							
							<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $development_phase_info[$p]->id; ?>">
							<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
							<input type="submit" value="Ok" name="submit" class="btn" />
						</div>
					</form>
					</div>
				</div>
				<div class="sub-task-contents" <?php if(($development_phase_task_info[$t]->id) == ($this->uri->segment(5))){ echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
					<!-- MODAL Task Delete-->
					<?php if($child_tasks): ?>
						<ul id="sortable-sub-task-<?php echo $development_phase_task_info[$t]->id; ?>" class="">
						<?php 
						foreach($child_tasks as $ctask){ 
						
						$phase_bg_color = '';
						$day_sign = '';
						$day_alert = '';

						if( $ctask->task_start_date == '0000-00-00' || $ctask->task_start_date == '1970-01-01')
						{

							$task_planned_finished_date = '00-00-0000' ;
						}
						else
						{
							$task_planned_finished_date = date('d-m-Y', strtotime($ctask->task_start_date));
						}

						if( $ctask->actual_completion_date!='0000-00-00' && $ctask->actual_completion_date!='1970-01-01')
						{
							$task_planned_actual_date_1 = date('d-m-Y', strtotime($ctask->actual_completion_date));
						}
						else if( $ctask->task_start_date == '0000-00-00' || $ctask->task_start_date == '1970-01-01')
						{
							$task_planned_actual_date_1 = '00-00-0000';
						}
						else
						{
							$created_date = date_create($ctask->task_start_date);
							$str = '21 days';
							$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
							$task_planned_actual_date_1 =  date_format($pcdate, 'd-m-Y');
						}

						$pc_time = strtotime($task_planned_actual_date_1);
						$t20_time = strtotime($task_planned_actual_date_1 .' - 20 days');

						if ($ctask->development_task_status == '1') 
						{
							$rem_days = '';
							$day_sign = ' ';
							$day_alert = "-";
							$phase_bg_color = '<div style="height:20px; width:20px; border-radius:15px; background-color:none;"></div>';
						}
						else if( $ctask->task_start_date == '0000-00-00' || $ctask->task_start_date == '1970-01-01')
						{
							$rem_days = '';
							$day_sign = ' ';
							$day_alert = "Dates Required";
							$phase_bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:grey;"></div><div class="hover">This task<br>has no dates<br>entered</div></div>';
						}
						elseif ($today_time < $pc_time && $today_time < $t20_time) 
						{
							$rem_days = date_diff(date_create($task_planned_actual_date_1),date_create($now))->format("%a");
							$day_sign = '';
							$day_alert = " Days Remaining";
							$phase_bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:grey;"></div><div class="hover">This task is over 21<br>Calendar days away<br>from completion</div></div>';
						}
						elseif ($today_time < $pc_time || $today_time == $pc_time) 
						{
							$rem_days = date_diff(date_create($task_planned_actual_date_1),date_create($now))->format("%a");
							$day_sign = '';
							$day_alert = " Days Remaining";
							$phase_bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:orange;"></div><div class="hover">This task is within<br>20 Calendar days<br>from completion</div></div>';
						}
						elseif($today_time > $pc_time)
						{
							$rem_days = date_diff(date_create($task_planned_actual_date_1),date_create($now))->format("%a");
							$day_sign = '';
							$day_alert = " Days Over";
							$phase_bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:red;"></div><div class="hover">This task is<br>overdue</div></div>';
						}
						
						$per_res = $ctask->task_person_responsible;
					
						if ($per_res > 0 && $ctask->task_start_date > '0000-00-00' && $ctask->development_task_status == '0') 
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
									<a <?php echo $per1; ?> href="#DevTaskDelete_<?php echo $ctask->id; ?>" title="Task Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
									<a <?php echo $per1; ?> href="#DevSubTaskEdit_<?php echo $ctask->id; ?>" title="Task Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php echo base_url();?>icon/icon_edit.png" /></a>
								</div>
								<div class="uncol" style="width:16%;padding-left: 35px;"><?php if( $ctask->development_task_status == '1' ) { ?><img style="margin:0 4px 0 0px" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" /><?php } ?><?php if(isset($ctask->task_name)) { echo $ctask->task_name; } ?></div>				
								<div class="uncol" style="width:9%"><?php echo $start_alert; ?></div> 
								<div class="uncol" style="width:15%;"><?php if(isset($task_planned_finished_date)) { echo $task_planned_finished_date; } ?></a></div>
			
								<div class="uncol" style="width:15%"><?php echo $task_planned_actual_date_1; ?></div>
			
								<div class="uncol" style="width:15%"><?php if($ctask->username){ echo $ctask->username; }else{ echo '&nbsp;'; } ?></div>
								<div class="uncol" style="width:10%"><?php //echo $day_sign.$rem_days.$day_alert; ?>&nbsp;&nbsp;</div>
			
								<div class="uncol" style="width:6%;"><?php echo $phase_bg_color; ?></div>
								<div class="uncol" style="width:8%;text-align:right;"><?php echo $per2; ?><input <?php echo $per1; ?> id="phase_status_<?php echo $ctask->id; ?>" type="checkbox" name="phase_status" <?php if( $ctask->development_task_status == '1' ) { ?> checked="checked" <?php } ?> onclick="change_development_phase_task_status(<?php echo $development_id; ?>,<?php echo $development_phase_info[$p]->id; ?>,<?php echo $ctask->id; ?>,<?php if($ctask->development_task_status==1){ echo '0'; }else{ echo '1'; } ?>)" /></div>
								<div style="clear:both;"></div>
			
								<!-- MODAL Task Edit -->
								<div id="DevSubTaskEdit_<?php echo $ctask->id; ?>" class="modal hide fade stage-modal" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<form class="form-horizontal" action="<?php echo base_url(); ?>developments/development_task_update/<?php echo $ctask->id; ?>/<?php echo $development_phase_task_info[$t]->id; ?>" method="POST">
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
												<input <?php echo $disabled; ?> required="" type="text" class="live_datepicker" placeholder="" name="task_start_date" value="<?php if($ctask->task_start_date != '1970-01-01' && $ctask->task_start_date != '0000-00-00'){ echo $this->wbs_helper->to_report_date($ctask->task_start_date); } ?>">
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
												<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="actual_completion_date" value="<?php if($ctask->actual_completion_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($ctask->actual_completion_date); } ?>">
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
												<input type="hidden" id="development_id" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
												<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $development_phase_info[$p]->id; ?>">
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
								<div id="DevTaskDelete_<?php echo $ctask->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<form class="form-horizontal" action="<?php echo base_url();?>developments/development_task_delete/<?php echo $ctask->id; ?>/<?php echo $development_phase_task_info[$t]->id; ?>" method="POST">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
										<h3 id="myModalLabel">Delete Sub Task: <?php echo $ctask->task_name; ?></h3>
									</div>
									<div class="modal-body">
										<p>Are you sure want to delete this Sub Task?</p>
									
									</div>
									<div class="modal-footer delete-task">
										<input type="hidden" id="development_id" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
										
										<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $development_phase_info[$p]->id; ?>">
										<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
										<input type="submit" value="Ok" name="submit" class="btn" />
									</div>
								</form>
								</div>	

								<!-- end sub task start -->
							</li>
							<?php } ?>
						</ul>
					<?php endif; ?>
					</div>
				
				</li>

	    <?php } // end task for loop   ?>
		
			</ul>
			</div>
		</div>

		</li>
		<?php
		}// end phase for loop
		

		?>

		<!--stage info --->
		<?php 
		for($i=1; $i<=$stages; $i++)
		{ 
			$ci =&get_instance();
			$ci->load->model('developments_model');
			$phase_info = $ci->developments_model->get_phase_info($development_id,$i)->result(); 
			$all_phase_task = $ci->developments_model->get_all_phase_status($development_id,$i)->result(); 
		
		?>
		<li class="accordion <?php $ph = $this->uri->segment(4); if($i==$ph){ echo 'accordion-active'; } ?>">
			<div class="accordion-header">
				<div class="uncol1" style="width:6%;">
					&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
				<div class="uncol1" style="width:16%;"><?php if(isset($all_phase_task[0]->aphase_status) && $all_phase_task[0]->aphase_status == 1){ ?><img style="margin-right:5px;" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" /><?php } ?>Stage <?php echo $i ?> </div>
				<div class="uncol" style="width:9%">&nbsp;&nbsp;</div>
				<div class="uncol1" style="width:15%;">&nbsp;&nbsp;</div>
				<div class="uncol1" style="width:15%;">&nbsp;&nbsp;</div>
				<div class="uncol1" style="width:15%;">&nbsp;&nbsp;</div>
				<div class="uncol1" style="width:10%;">&nbsp;&nbsp;</div>
				<div class="uncol1" style="width:6%;">&nbsp;&nbsp;</div>
				<div class="uncol1" style="width:8%;text-align: right;"><?php echo $per2; ?> 
					<input <?php echo $per1; ?> type="checkbox" name="all_phase_task" id="all_phase_task" <?php if(isset($all_phase_task[0]->aphase_status) && $all_phase_task[0]->aphase_status == 1){ ?> checked="checked" <?php } ?> onclick="change_all_stage_phase_status(<?php echo $development_id; ?>,<?php echo $i; ?>,<?php if($all_phase_task[0]->aphase_status==1){ echo '0'; }else{ echo'1'; } ?>)" />
				</div>
			</div>

			<div class="accordion-content" style="<?php $ph = $this->uri->segment(4); if($i==$ph){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">

			<ul class="stage_content">

				<?php for($j = 0; $j < count($phase_info); $j++ ) { 

						$day_sign = '';
						$day_alert = '';
						$bg_color = 'yellow';

						if( $phase_info[$j]->planned_finished_date > '0000-00-00')
						{
							$planned_finished_date = date('d-m-Y', strtotime($phase_info[$j]->planned_finished_date));
						}
						else if($phase_info[$j]->planned_start_date == '0000-00-00')
						{
							$planned_finished_date =  '00-00-0000';
						}
						else
						{
							$created_date = date_create($phase_info[$j]->planned_start_date);
							$str = '21 days';
							$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
							$planned_finished_date =  date_format($pcdate, 'd-m-Y');
						}

						$pc_time = strtotime($planned_finished_date);
						$t20_time = strtotime($planned_finished_date .' - 20 days');

						if ($phase_info[$j]->phase_status == '1' ) 
						{
							$rem_days = ''; 
							$day_sign = ' ';
							$day_alert = "-";
							$bg_color = '<div style="height:20px; width:20px; border-radius:15px; background-color:none;"></div>';
						}
						elseif($phase_info[$j]->planned_start_date == '0000-00-00')
						{
							$rem_days = ''; 
							$day_sign = ' ';
							$day_alert = "Dates Required";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:grey;"></div><div class="hover">This task<br>has no dates<br>entered</div></div>';
						}
						elseif ($today_time < $pc_time && $today_time < $t20_time) 
						{
							$rem_days = date_diff(date_create($phase_info[$j]->planned_finished_date),date_create($now))->format("%a"); 
							$day_sign = '';
							$day_alert = " Days Remaining";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:grey;"></div><div class="hover">This task is over 21<br>working days away<br>from completion</div></div>';
						}
						elseif ($today_time < $pc_time || $today_time == $pc_time) 
						{
							$rem_days = date_diff(date_create($phase_info[$j]->planned_finished_date),date_create($now))->format("%a"); 
							$day_sign = '';
							$day_alert = " Days Remaining";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:orange;"></div><div class="hover">This task is within<br>20 working days<br>from completion</div></div>';
						}
						elseif($today_time > $pc_time)
						{
							$rem_days = date_diff(date_create($phase_info[$j]->planned_finished_date),date_create($now))->format("%a"); 
							$day_sign = '';
							$day_alert = " Days Over";
							$bg_color = '<div class="system-hover"><div style="height:20px; width:20px; border-radius:15px; background-color:red;"></div><div class="hover">This task is<br>overdue</div></div>';
						}

				?>
				<li id="listItemTask">
					<div class="uncol" style="width:6%;padding-left: 10px;">
						&nbsp;&nbsp;
					</div>
					<div class="uncol" style="width:16%;"><?php if( $phase_info[$j]->phase_status == '1' ) { ?><img style="margin-right:5px;" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" /><?php } ?><?php if(isset($phase_info[$j]->phase_name))echo $phase_info[$j]->phase_name; ?></div>
					<div class="uncol" style="width:9%">&nbsp;&nbsp;</div>
					<div class="uncol" style="width:15%;"><?php if($phase_info[$j]->planned_start_date>'0000-00-00'){ echo date('d-m-Y', strtotime($phase_info[$j]->planned_start_date)); }else{ echo '00-00-0000'; } ?></div>
					<div class="uncol" style="width:15%;"><?php echo $planned_finished_date; ?></div>
					<div class="uncol" style="width:15%"><?php if($phase_info[$j]->username){ echo $phase_info[$j]->username; }else{ echo '&nbsp;&nbsp;'; } ?></div>					
					<div class="uncol" style="width:10%;"><?php //echo $day_sign.$rem_days.$day_alert;  ?>&nbsp;&nbsp;</div>
					<div class="uncol" style="width:6%;"><?php echo $bg_color; ?></div>
					<div class="uncol" style="width:8%; text-align:right;"><?php echo $per2; ?><input <?php echo $per1; ?> id="phase_status_<?php echo $phase_info[$j]->id; ?>" type="checkbox" name="phase_status" <?php if( $phase_info[$j]->phase_status == '1' ) { ?> checked="checked" <?php } ?> onclick="change_phase_status(<?php echo $development_id; ?>,<?php echo $i; ?>,<?php echo $phase_info[$j]->id; ?>,<?php if($phase_info[$j]->phase_status==1){ echo '0'; }else{ echo '1'; } ?>)" /></div>
					<div style="clear:both;"></div>
				</li>

				
				<?php } ?>
			</ul>	
			</div>
		</li>	
		<?php
		}
		?>
		
	</ul>


<div style="text-align:right;font-weight: bold;">Note: All stages must be edited in their stage.</div>
</div>

<script>
$(function() {
   	$('#fuzzOptionsList_Phase').fuzzyDropdown({
      mainContainer: '#fuzzSearch_Phase',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });
});
</script>

<!-- MODAL Phase Add -->
<div id="AddPhase" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form class="form-horizontal" action="<?php echo base_url(); ?>developments/development_phase_add" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
		<h3 id="myModalLabel">Add Phase</h3>
	</div>
	<div class="modal-body">

		<div class="control-group">
			<label class="control-label" for="phase_name">Phase Name </label>
			<div class="controls">
				<input type="text" id="phase_name" name="phase_name" value="" required="">
			</div>
		</div>
		
		<div class="control-group">
			<label class="control-label" for="planned_start_date">Planned Start Date </label>
			<div class="controls">
				<input <?php echo $disabled; ?> type="text" class="live_datepicker" id="" name="planned_start_date" value="" required="">
			</div>
		</div>
		<div class="control-group">
			<label class="control-label" for="planned_finished_date">Planned Completion Date </label>
			<div class="controls">
				<input <?php echo $disabled; ?> type="text" class="live_datepicker" name="planned_finished_date" value="">
			</div>
		</div>

		<div class="control-group">
			<label class="control-label" for="phase_person_responsible">Person Responsible</label>
			<div class="controls" style="clear: both;">
				<select name="phase_person_responsible" class="form-control" id="fuzzOptionsList_Phase">
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
					<div id="fuzzSearch_Phase">
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
				<input type="hidden" id="development_id" name="development_id" value="<?php echo $this->uri->segment(3); ?>">
				
				<div class="save">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</div>
	
	</div>

</form>
</div>
<!-- MODAL Phase Add-->

<?php if($this->uri->segment(4)){ ?>		
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


		/*$("ul li.haschild").click(function(){

			var n = $(this).find("ul").css("display");
			if(n == 'none'){
				$(this).find("ul").show();
			}else{
				$(this).find("ul").hide();
			}

		});*/

		$('.modal form').ajaxForm({
			success:function(data) {
				newurl = window.Url + 'developments/'+data;
				window.location = newurl;	  
			},			
			beforeSubmit:function(){
				var overlay = jQuery('<div id="overlay"></div>');
				overlay.appendTo(document.body);
			}
		});
	});
</script>