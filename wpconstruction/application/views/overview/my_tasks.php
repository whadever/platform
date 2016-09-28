<style>
	.date-header {
		color: white;
		padding: 8px;
	}
	.date-header, .date-body{
		width: 100%;
	}
	.row .task-container {
		border: 1px solid grey;
		margin-bottom: 8px;
		padding: 0;
	}
	.date-body th {
		color: white;
		font-size: 84%;
		text-align: center;
	}
	.date-body td {
		font-size: 84%;
		text-align: center;
	}
	#overlay {
		background-color: #000;
		background-image: url("<?php echo base_url(); ?>images/ajax-loading.gif");
		background-position: 50% center;
		background-repeat: no-repeat;
		height: 100%;
		left: 0;
		opacity: 0.5;
		position: fixed;
		top: 0;
		width: 100%;
		z-index: 10000;
	}
</style>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<div class="">
			<li style="float:right; list-style: none">
				<span style="height:20px; width:20px; border-radius:15px; background-color:red">&nbsp;&nbsp;&nbsp;&nbsp;</span> Overdue
				<span style="height:20px; width:20px; border-radius:15px; background-color:#fab800">&nbsp;&nbsp;&nbsp;&nbsp;</span> Underway
				<span style="height:20px; width:20px; border-radius:15px; background-color:grey">&nbsp;&nbsp;&nbsp;&nbsp;</span> Pending
			</li>
		</div>
		<div class="clear"></div>
		<?php $first_date = true; //will not show complete checkbox except the first date ?>
		<?php foreach($my_tasks as $date => $tasks): ?>
			<div class="task-container">
				<div class="date-header">
					Tasks for <?php echo date_create_from_format('Y-m-d',$date)->format('l - d F Y'); ?>
				</div>
				<div class="date-body">
					<table>
						<thead>
						<tr>
							<th>Job Name</th>
							<th>Task Name</th>
							<th>Start Date</th>
							<th>Finish Date</th>
							<th>Status</th>
							<?php if($first_date){?><th>Complete</th><?php } ?>
						</tr>
						</thead>
						<tbody>
						<?php foreach($tasks as $task): ?>
						<?php if($task['finish_date']=='00-00-0000' && $date==date('Y-m-d')){ ?>
							<tr>
								<td><?php echo $task['job_name']; ?></td>
								<td><?php echo $task['task_name']; ?></td>
								<td><?php echo $task['start_date']; ?></td>
								<td><?php echo $task['finish_date']; ?></td>
								<td>
									<?php
									$bg = "";
									switch($task['status']){
										case 'pending': $bg = "grey"; break;
										case 'underway': $bg = "#fab800"; break;
										case 'overdue': $bg = "red"; break;
									}
									?>
									<span style="margin:0 auto;display:block; height:16px; width:16px; border-radius:15px; background-color:<?php echo $bg; ?>">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
								<?php if($first_date){?><td><input type="checkbox" class="check-complete" value="<?php echo $task['task_id']; ?>"></td><?php } ?>
							</tr>
						<?php }elseif($task['finish_date']!='00-00-0000'){ ?>
							<tr>
								<td><?php echo $task['job_name']; ?></td>
								<td><?php echo $task['task_name']; ?></td>
								<td><?php echo $task['start_date']; ?></td>
								<td><?php echo $task['finish_date']; ?></td>
								<td>
									<?php
									$bg = "";
									switch($task['status']){
										case 'pending': $bg = "grey"; break;
										case 'underway': $bg = "#fab800"; break;
										case 'overdue': $bg = "red"; break;
									}
									?>
									<span style="margin:0 auto;display:block; height:16px; width:16px; border-radius:15px; background-color:<?php echo $bg; ?>">&nbsp;&nbsp;&nbsp;&nbsp;</span>
								</td>
								<?php if($first_date){?><td><input type="checkbox" class="check-complete" value="<?php echo $task['task_id']; ?>"></td><?php } ?>
							</tr>
						<?php } ?>	
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
				<div class="clear"></div>
			</div>
			<?php $first_date = false; ?>
		<?php endforeach; ?>

	</div>
</div>

<div class="clear"></div>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tooltip.min.js"></script>
<script>
	var base_url = "<?php echo base_url(); ?>";
	$(document).ready(function(){
		$(".check-complete").change(function(){
			var overlay = jQuery('<div id="overlay"> </div>');
			overlay.appendTo(document.body)
			$.ajax(base_url+"constructions/update_development_phase_task_status/"+$(this).val()+"/1",{
				success:function(){
					window.location.reload();
				}
			})
		});
	});


</script>
