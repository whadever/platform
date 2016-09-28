
<div class="clear"></div>

<div class="template-review" style="background: #fff;">
	<div class="template-header">
			<div class="template-title">
				<div class="all-title"><?php echo $title; ?></div>
			</div>	
			<div class="start-page"><p>Template Design</p></div>
			<?php if(!isset($template_review_update->id)) : ?>
			<div class="start-over">
				<a href="<?php echo base_url();?>template/template_start">Start Over</a>
			</div>
			<?php endif; ?>	
			<div class="clear"></div>		
	</div>
	<div class="title-inner">Start Page > Basic Info > Template Design > Review</div>
<div class="clear"></div>

	<div class="template-body">
		<div class="review-body">
			<div class="reviews">
			<?php
				//$template_id = $this->uri->segment(3);
				$phase_query = $this->db->query("SELECT * FROM construction_template_phase where template_id=$template_id ORDER BY ordering ASC");
				$phase_result = $phase_query->result();
				foreach($phase_result as $phase_row){
			?>
			
				<div class="review">
					<div class="review-header">
						<h6>
						
						<a href="#EditPhase_<?php echo $phase_row->id; ?>" title="Phase Edit" role="button" data-toggle="modal">
						<?php echo $phase_row->phase_name ?>
						</a>
						
						<!-- MODAL Task Edit -->
							<div id="EditPhase_<?php echo $phase_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form class="form-horizontal" action="<?php echo base_url(); ?>template/template_phase_update/<?php echo $phase_row->id; ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit Phase Details</h3>
								</div>
								<div class="modal-body">
									
											<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $phase_row->id; ?>" readonly="">
									<div class="control-group">
										<label class="control-label" for="phase_name">Phase Name </label>
										<div class="controls">
											<input type="text" id="phase_name" placeholder="" name="phase_name" value="<?php echo $phase_row->phase_name; ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="phase_length">Phase Length </label>
										<div class="controls">
											<input type="text" id="phase_length" placeholder="" name="phase_length" value="<?php echo $phase_row->phase_length; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="inputPassword"></label>
										<div class="controls">
											<input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
											<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
											
											<div class="save">
												<input type="submit" value="Submit" name="submit" />
											</div>
										</div>
									</div>
							    
								</div>

							</form>
							</div>
							<!-- MODAL Task Edit-->
							
						</h6>
						
					</div>
					
					<div class="review-content">
						<?php
							$phase_id = $phase_row->id;
							$task_query = $this->db->query("SELECT * FROM construction_template_task where template_id=$template_id and phase_id=$phase_id ORDER BY ordering ASC");
							$task_result = $task_query->result();
							foreach($task_result as $task_row){
						?>
							<h6>
							
							<a href="#EditTask_<?php echo $task_row->id; ?>" title="Task Edit" role="button" data-toggle="modal">
							<?php echo $task_row->task_name ?>
							</a>
							
							<!-- MODAL Task Edit -->
							<div id="EditTask_<?php echo $task_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form class="form-horizontal" action="<?php echo base_url(); ?>template/template_task_update/<?php echo $task_row->id; ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit Task Details</h3>
								</div>
								<div class="modal-body">
									
											<input type="hidden" id="task_id" name="task_id" value="<?php echo $task_row->id; ?>" readonly="">
									<div class="control-group">
										<label class="control-label" for="task_name">Task Name </label>
										<div class="controls">
											<input type="text" id="task_name" placeholder="" name="task_name" value="<?php echo $task_row->task_name; ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="task_length">Task Length </label>
										<div class="controls">
											<input type="text" id="task_length" placeholder="" name="task_length" value="<?php echo $task_row->task_length; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="inputPassword"></label>
										<div class="controls">
											<input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
											<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
											<div class="save">
												<input type="submit" value="Submit" name="submit" />
											</div>
										</div>
									</div>
							    
								</div>

							</form>
							</div>
							<!-- MODAL Task Edit-->
							
							</h6>
							
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
	<div class="clear"></div>	
	</div>
	
<div class="clear"></div>

	<div class="template-footer">
			<a class="back" onclick="window.history.go(-1)">Back</a>

			<?php if(!isset($template_review_update->id)) : ?>
			<a class="next" href="<?php echo base_url();?>template/template_list">Finish</a>
			<?php else : ?>
			<a class="next" href="<?php echo base_url();?>template/template_detail/<?php echo $template_id; ?>">Finish</a>
			<?php endif; ?>
		<div class="clear"></div>
	</div>
<div class="clear"></div>
</div>
