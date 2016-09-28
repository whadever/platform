
<script type="text/javascript">
    
	$(document).ready(function () {	
		$('#addrow-button').click(function(){					
			$('#hour-form').css('display', '');	
			$('#addrow-button').css('display', 'none');		
			
			$('#hour-save').css('display', '');	
			$('#close-task').css('display', 'none');
		});	
		$('.close-button').click(function(){					
			$('#hour-form').css('display', 'none');	
			$('#addrow-button').css('display', '');	
			
			$('#hour-save').css('display', 'none');	
			$('#close-task').css('display', '');		
		});	
	});

	function hourSave(){
		var contractor = $('.contractor').val();
		var user = $('.user_id').val();
		var hour = $('.hour').val();
		var minute = $('.minute').val();
		var note = $('.note').val();
		var week_start_date = $('.week_start_date').val();
		var task_id = $('.task_id').val();
		
		if(week_start_date=="")
		{
			alert("Please fill out this field.");
			return false;
		}
		else{
			$.ajax({				
				url: window.mbsBaseUrl + 'request/request_hour_save?contractor=' + contractor + '&user=' + user + '&hour=' + hour + '&minute=' + minute + '&note=' + note + '&week_start_date=' + week_start_date + '&task_id=' + task_id,
				type: 'POST',
				success: function(html) 
				{
					//console.log(data);
					newurl = window.mbsBaseUrl + 'request/request_hour/' + task_id;
					window.location = newurl;
				},
			        
			});
		}
	}

</script>

<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
			<input type="button" value="Back" class="btn btn-default add-button" onclick="history.go(-1);"/>
		</div>
	</div>
</div>
<div class="breadcrumb-box">
<?php

  
$this->breadcrumbs->push('Task', 'request/request_list');
$this->breadcrumbs->push($request_name, 'request/request_detail/'.$request_id);
$this->breadcrumbs->push('Task Hours', 'request/request_hour/'.$request_id);
echo $this->breadcrumbs->show();  
?>
</div>
<div id="task-hour-page" class="content-inner task-hour">

	<div class="row">

		<div class="col-md-12 task-hour-list">
			<table class="table-hour">
				<thead>
					<tr>
						<th width="20%">Manager or<br>Contractor</th>
						<th width="20%">User</th>
						<th width="10%">Hours</th>
						<th width="10%">Minutes</th>
						<th width="20%">Week Starting</th>
						<th width="20%">Notes</th>
					</tr>
				</thead>
			</table>
			<?php
				foreach($task_hours as $task_hour){
			?>
			<table class="table-hour">
				<thead>
				
					<tr>
						<th width="20%"><?php echo $task_hour->contractor; ?></th>
						<th width="20%"><?php echo $task_hour->user; ?></th>
						<th width="10%"><?php if(!empty($task_hour->hour)){ echo $task_hour->hour.'h '; }  ?></th>
						<th width="10%"><?php if(!empty($task_hour->minute)){ echo $task_hour->minute.'m'; }  ?></th>
						
						<th width="20%"><?php echo $task_hour->week_start_date; ?></td>
						<th width="20%"><?php echo $task_hour->note; ?></th>
					</tr>	
				</thead>
			</table>
			<?php
				}
			?>
			<table class="table-hour" id="hour-form" style="display: none;">
				<tbody>
					<tr>
						<td width="20%">
						<select name="contractor" class="contractor form-control">
						<?php       
							$user=  $this->session->userdata('user');  
							$wp_company_id = $user->company_id;     
							$query = $this->db->query('SELECT * FROM users LEFT JOIN users_application ON users.uid=users_application.user_id where users_application.application_id=3 and users.uid='.$user->uid.' and users.company_id='.$wp_company_id);
							$results = $query->result();
							foreach($results as $result){
						?>
							<option value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
						<?php
							}
						?>
						</select>
						</td>
						<td width="20%">
						<select name="user" class="user_id form-control">
						<?php
							$query = $this->db->query('SELECT * FROM users LEFT JOIN users_application ON users.uid=users_application.user_id where users_application.application_id=3 and users.role !=1 and users.company_id='.$wp_company_id);
							$results = $query->result();
							foreach($results as $result){
						?>
							<option value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
						<?php
							}
						?>
						</select>
						</td>
						<td width="10%"><input type="text" name="hour" class="hour form-control" value="" /></td>
						<td width="10%"><input type="text" name="minute" class="minute form-control" value="" /></td>
						<td width="20%"><input type="text" name="week_start_date" class="week_start_date form-control" id="edit-estimated_completion" value="" /></td>
						<td width="20%"><input type="text" name="note" class="note form-control" value="" /><div class="close-button"><span>X</span></div></td>
						
					</tr>
				</tbody>
			</table>
			<table class="table-hour" id="addrow-button">
				<tbody>		
					<tr>
						<td colspan="5"><span>+</span></td>
					</tr>
				</tbody>
			</table>
			
		</div>
		
		<div class="col-md-12">
			<div class="total-hour">
				<?php 
					$time = $total_hours*60;

					$hours = floor($time / (60 * 60));
					$time -= $hours * (60 * 60);

					$minutes = floor($time / 60);
				 ?>
				<span>Total Hours: <?php echo "{$hours}h {$minutes}m"; ?> </span>
			</div>
			<div class="save-button">
				<input type="hidden" name="task_id" class="task_id" value="<?php echo $this->uri->segment(3)?>" />
				<input style="display: none;" id="hour-save" class="form-submit btn btn-default" type="button" onclick="hourSave();" value="Save">
				<a id="close-task" onclick="return confirm('Are you sure want to close this task?')" class="form-submit btn btn-default" href="<?php echo base_url()?>request/request_close/<?php echo $this->uri->segment(3)?>">Close Task</a>
			</div>
		</div>
		
	</div>

</div>