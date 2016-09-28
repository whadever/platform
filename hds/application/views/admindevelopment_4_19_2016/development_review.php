
<div class="development-add-home development-review" style="background: #fff;">
	<div class="development-header">
		<div class="development-title">
			<div class="all-title"><?php echo $title; ?><span><?php echo $page_title; ?></span></div>
			<div class="title-inner">Home > development Info > <?php echo $page_title_before; ?> > <?php echo $page_title; ?> > Review</div>
		</div>
		<?php if(!isset($admindevelopment->id)) : ?>
		<div class="start-over">
			<a href="<?php echo base_url();?>admindevelopment/development_start">Start Over</a>
		</div>
		<?php endif; ?>
	</div>
	<div class="clear"></div>
	
	<div class="development-body">
		<?php 
			$dev_query = $this->db->query("SELECT dev.*, template.template_name FROM development dev LEFT JOIN template ON dev.tid = template.id where dev.id=$development_id"); 
			$dev_row = $dev_query->row();
		?>	
		<div class="dev-review">
			<table>
				<tbody>
					<tr>
						<td>Development Name</td>
						<td><?php if(isset($dev_row->development_name)){ echo $dev_row->development_name; } ?></td>
					</tr>
					<tr>
						<td>Development Location</td>
						<td><?php if(isset($dev_row->development_location)){ echo $dev_row->development_location; } ?></td>
					</tr>
					<tr>
						<td>Development City</td>
						<td><?php if(isset($dev_row->development_city)){ echo $dev_row->development_city; } ?></td>
					</tr>
					<tr>
						<td>Development Size</td>
						<td><?php if(isset($dev_row->development_size)){ echo $dev_row->development_size; } ?></td>
					</tr>
					<tr>
						<td>Number Of Stages</td>
						<td><?php if(isset($dev_row->number_of_stages)){ echo $dev_row->number_of_stages; } ?></td>
					</tr>
					<tr>
						<td>Number Of Lots</td>
						<td><?php if(isset($dev_row->number_of_lots)){ echo $dev_row->number_of_lots; } ?></td>
					</tr>
					<tr>
						<td>Land Zone</td>
						<td><?php if(isset($dev_row->land_zone)){ echo $dev_row->land_zone; } ?></td>
					</tr>
					<tr>
						<td>Ground Condition</td>
						<td><?php if(isset($dev_row->ground_condition)){ echo $dev_row->ground_condition; } ?></td>
					</tr>
					<tr>
						<td>Project Manager</td>
						<td><?php if(isset($dev_row->project_manager)){ echo $dev_row->project_manager; } ?></td>
					</tr>
					<tr>
						<td>Civil Engineer</td>
						<td><?php if(isset($dev_row->civil_engineer)){ echo $dev_row->civil_engineer; } ?></td>
					</tr>
					<tr>
						<td>Civil Manager</td>
						<td><?php if(isset($dev_row->civil_manager)){ echo $dev_row->civil_manager; } ?></td>
					</tr>
					<tr>
						<td>Geo Tech Engineer</td>
						<td><?php if(isset($dev_row->geo_tech_engineer)){ echo $dev_row->geo_tech_engineer; } ?></td>
					</tr>
					<tr>
						<td>Development Template</td>
						<td><?php if(isset($dev_row->template_name)){ echo $dev_row->template_name.'<a href="#DevView" role="button" data-toggle="modal" class="dev-tem-view">View Full</a>'; } ?></td>
					</tr>
					<tr>
						<td>Stage Template</td>
						<td class="stage-template-td">
						<?php 
							$number_of_stages = $dev_row->number_of_stages;
							for($i = 1; $i <= $number_of_stages; $i++){
								$template_id_query = $this->db->query("SELECT * FROM stage_phase where development_id=$development_id and stage_no=$i");
								$row_template_id = $template_id_query->row();
								if($row_template_id){
									$template_id = $row_template_id->template_id;
									$template_name_query = $this->db->query("SELECT sp.*, template.template_name FROM stage_phase sp LEFT JOIN template ON sp.template_id = template.id where template_id=$template_id and development_id=$development_id and stage_no=$i");
									$row_template_name = $template_name_query->row();
									if($row_template_name){
										echo $row_template_name->template_name.', ';
									}//if condition end;
								}//if condition end;
								
							} //for loop end;
						 ?>
						<a href="#DevViewStage" role="button" data-toggle="modal" class="dev-tem-view">View Full</a>	
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
	
	<div class="clear"></div>
	
	<div class="development-footer">
		<div class="next-back">
			<a class="back" onclick="window.history.go(-1)">Back</a>
			<a class="next" href="<?php echo base_url();?>admindevelopment/development_list">Finish</a>
			<div class="clear"></div>
		</div>
	</div>
	
	<div class="clear"></div>
	
</div>
<!-- MODAL Development Template View -->
<div id="DevViewStage" class="modal dev-stage-tem-review stage hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-body">
		<div class="dev-tem-review">
			<div class="reviews">
			<?php
				$number_of_stages = $dev_row->number_of_stages;
				for($j = 1; $j <= $number_of_stages; $j++){
			?>
				<div class="review">
					<div class="stage-no">
					<?php
						$stage_no_query = $this->db->query("SELECT * FROM stage_phase where development_id=$development_id and stage_no=$j");
						$stage_no_result = $stage_no_query->row();
						if($stage_no_result){
					?>
						<h6><?php echo 'Stage '.$j; ?></h6>
					<?php 
						}
					?>
					</div>
					<div class="stage-phase-task-inner">
					<?php
						$phase_query = $this->db->query("SELECT * FROM stage_phase where development_id=$development_id and stage_no=$j ORDER BY ordering ASC");
						$phase_result = $phase_query->result();
						foreach($phase_result as $phase_row){
					?>
						<div class="review-header">
							<h6><?php echo $phase_row->phase_name; ?></h6>
						</div>
						<div class="review-content">
						<?php
							$phase_id = $phase_row->id;
							$task_query = $this->db->query("SELECT * FROM stage_task where development_id=$development_id and phase_id=$phase_id ORDER BY ordering ASC");
							$task_result = $task_query->result();
							foreach($task_result as $task_row){
						?>
							<h6><?php echo $task_row->task_name; ?></h6>
						<?php
							}//foreach loop end;
						?>
						</div>
					<?php
						}//foreach loop end;
					?>	
					</div>
				</div>
			<?php
				}//for loop end;
			?>
			</div>
		</div>
		
		<button class="btn" data-dismiss="modal" aria-hidden="true">Done</button>
		<div class="clear"></div>
	</div>

</div>
<!-- MODAL Development Template View-->

<!-- MODAL Development Template View -->
<div id="DevView" class="modal dev-stage-tem-review hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-body">
		<div class="dev-tem-review">
			<div class="reviews">
			<?php
				$phase_query = $this->db->query("SELECT * FROM development_phase where development_id=$development_id ORDER BY ordering ASC");
				$phase_result = $phase_query->result();
				foreach($phase_result as $phase_row){
			?>
				<div class="review">
					<div class="review-header">
						<h6><?php echo $phase_row->phase_name; ?></h6>
					</div>
					<div class="review-content">
					<?php
						$phase_id = $phase_row->id;
						$task_query = $this->db->query("SELECT * FROM development_task where development_id=$development_id and phase_id=$phase_id ORDER BY ordering ASC");
						$task_result = $task_query->result();
						foreach($task_result as $task_row){
					?>
						<h6><?php echo $task_row->task_name; ?></h6>
					<?php
						}
					?>
					</div>
				</div>
			<?php
				}
			?>
			</div>
		</div>
		
		<button class="btn" data-dismiss="modal" aria-hidden="true">Done</button>
		<div class="clear"></div>
	</div>

</div>
<!-- MODAL Development Template View-->