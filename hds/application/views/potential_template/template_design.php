<script>

window.Url = "<?php print base_url(); ?>";
jQuery(document).ready(function() {
	
	$('input.submit').change(function(e){
	     e.preventDefault();
	     $('li.accordion').removeClass('accordion-active'); // removes all highlights from tr's
	     $('li.accordion').addClass('accordion-active'); // adds the highlight to this row
	 });
	
});

</script>

<script>
jQuery(document).ready(function() {
	if (jQuery('#sortable-phase').length){
		$( "#sortable-phase" ).sortable({
			update : function () { 
			var order = $('#sortable-phase').sortable('serialize');
			$.ajax({
				url: window.Url + 'potential_template/template_phase_ordering',
				type: 'POST',
				data: order,
				success: function(data) 
				{
 
				},
			        
			});
			}
		});	
		$( "#sortable-phase" ).disableSelection();
	}
});
</script>

<script>
jQuery(document).ready(function() {
		
		$( "#draggable-phase" ).draggable({
			//connectToSortable: "#sortable-phase",
			helper: "clone",
			revert: "invalid"
		});
		$( ".accordions-body" ).droppable({
			
			drop: function( event, ui ) {
				if (ui.draggable.is('#draggable-phase')) {
					$( this )
					.addClass( "droppable-add" )
					.find( "li#droppable-phase" )
					.html( '<a href="#PhaseModal" role="button" data-toggle="modal" class="edit">+Add New Phase</a>' );
				}
			}
		});
	

});
</script>

<div class="clear"></div>

<div class="template-design" style="background: #fff;">
	<div class="template-header">
			<div class="template-title">
				<div class="all-title"><?php echo $title; ?></div>
			</div>	
			<div class="start-page"><p>Template Design</p></div>
			<?php if(!isset($template_design_update->id)) : ?>
			<div class="start-over">
				<a href="<?php echo base_url();?>potential_template/template_start">Start Over</a>
			</div>	
			<?php endif; ?>
			<div class="clear"></div>		
	</div>
	<div class="title-inner">Start Page > Basic Info > Template Design</div>
<div class="clear"></div>

	<div class="template-body">
	
		<div class="task-phase-inner">
			<div class="task-phase-header">
				<a href="#PhaseModal" role="button" data-toggle="modal" class="plus-icon"><img src="<?php print base_url(); ?>images/plus-icon1.png" /></a>
				<ul class="drag-phase-task">
					<li id="draggable-task">Add Task</li>
					<li id="draggable-phase">Add Phase</li>
				</ul>
			</div>
			<div class="clear"></div>
			<!-- Accordions -->
			<div class="accordions-body">
			<ul id="sortable-phase" class="accordions">

				<!-- Accordion -->
				<?php
					$template_id = $this->uri->segment(3);
					$url_phase_id = $this->uri->segment(4);
					$phase_query = $this->db->query("SELECT * FROM potential_template_phase where template_id=$template_id ORDER BY ordering ASC");
					$phase_result = $phase_query->result();
					$phase_row_count = count($phase_result);
					foreach($phase_result as $phase_row){
				?>

<script>
jQuery(document).ready(function() {
		
	$( "#draggable-task" ).draggable({
		//connectToSortable: "#sortable-phase",
		helper: "clone",
		revert: "invalid"
	});
	
	$( "#listItemPhase_<?php echo $phase_row->id; ?>" ).droppable({
		
		drop: function( event, ui ) {
			if (ui.draggable.is('#draggable-task')) {
				$("#listItemPhase_<?php echo $phase_row->id; ?> .accordion-content").css("display","block");
				$( this )
				.addClass( "accordion-active" )
				.find( "li#droppable-task-<?php echo $phase_row->id; ?>" )
				.html( '<a href="#AddTask_<?php echo $phase_row->id; ?>" role="button" data-toggle="modal" class="edit">+ Add New Task</a>' );
			}
		}
	});

});
</script>
			
				<li id="listItemPhase_<?php echo $phase_row->id; ?>" class="accordion <?php if(($phase_row->id) == ($url_phase_id)){ echo 'accordion-active'; } ?>">
					
					<div class="accordion-header">
						<div class="accordion-icon"></div>
						<h6>
						
							<?php echo $phase_row->phase_name; ?>
							<a href="#EditPhase_<?php echo $phase_row->id; ?>" title="Phase Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php print base_url(); ?>images/icon/edit_pen.png" /></a>
							<a href="#DeletePhase_<?php echo $phase_row->id; ?>" title="Phase Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php print base_url(); ?>images/icon/btn_horncastle_trash.png" /></a>

							<!-- MODAL Phase Edit -->
							<div id="EditPhase_<?php echo $phase_row->id; ?>" class="modal hide fade admindev" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form class="form-horizontal" action="<?php echo base_url(); ?>potential_template/template_phase_update/<?php echo $phase_row->id; ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit Phase Details</h3>
								</div>
								<div class="modal-body">
									
											<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $phase_row->id; ?>" readonly="">
									<div class="control-group">
										<label class="control-label" for="phase_name">Phase Name </label>
										<div class="controls">
											<input required type="text" id="phase_name" placeholder="" name="phase_name" value="<?php echo $phase_row->phase_name; ?>">
										</div>
									</div>
									<div class="control-group" style="display:none;">
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
							<!-- MODAL Phase Edit-->

							<!-- MODAL Phase Delete-->
							<div id="DeletePhase_<?php echo $phase_row->id; ?>" class="modal hide fade admindev" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form class="form-horizontal" action="<?php echo base_url();?>potential_template/template_phase_delete/<?php if(isset($phase_row->id)){ echo $phase_row->id; } ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Delete Phase: <?php if(isset($phase_row->phase_name)){ echo $phase_row->phase_name; } ?></h3>
								</div>
								<div class="modal-body">
									<p>Are you sure want to delete this Phase?</p>
							    
								</div>
								<div class="modal-footer delete-task">
									<input type="hidden" id="template_id" name="template_id" value="<?php echo $template_id; ?>">
									<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
									<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
									<input type="submit" value="Ok" name="submit" class="btn" />
								</div>
							</form>
							</div>
							<!-- MODAL Phase Delete-->
							
						</h6>
						
						
						
					</div>
					
					<div class="accordion-content"  <?php if(($phase_row->id) == ($url_phase_id)){ echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
					
<script>
jQuery(document).ready(function() {
	if (jQuery("#sortable-task-<?php echo $phase_row->id; ?>").length){
		$( "#sortable-task-<?php echo $phase_row->id; ?>" ).sortable({
			update : function () { 
			var order = $('#sortable-task-<?php echo $phase_row->id; ?>').sortable('serialize');
			$.ajax({
				url: window.Url + 'potential_template/template_task_ordering',
				type: 'POST',
				data: order,
				success: function(data) 
				{
 
				},
			        
			});
			}
		});	
		$( "#sortable-task-<?php echo $phase_row->id; ?>" ).disableSelection();
	}
});
</script>

						<ul id="sortable-task-<?php echo $phase_row->id; ?>" class="">
						<?php
							$phase_id = $phase_row->id;
							$task_query = $this->db->query("SELECT * FROM potential_template_task where template_id=$template_id and phase_id=$phase_id ORDER BY ordering ASC");
							$task_result = $task_query->result();
							$task_row_count = count($task_result);
							foreach($task_result as $task_row){
						?>
							<li id="listItemTask_<?php echo $task_row->id; ?>">
							
							<?php echo $task_row->task_name; ?>
							<a href="#EditTask_<?php echo $task_row->id; ?>" title="Task Edit" role="button" data-toggle="modal" class="template-task-edit"><img width="16" height="16" src="<?php print base_url(); ?>images/icon/edit_pen.png" /></a>
							<a href="#DeleteTask_<?php echo $task_row->id; ?>" title="Task Delete" role="button" data-toggle="modal" class="template-task-delete"><img width="16" height="16" src="<?php print base_url(); ?>images/icon/btn_horncastle_trash.png" /></a>
							
							<!-- MODAL Task Edit -->
							<div id="EditTask_<?php echo $task_row->id; ?>" class="modal hide fade admindev" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form class="form-horizontal" action="<?php echo base_url(); ?>potential_template/template_task_update/<?php echo $task_row->id; ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit Task Details</h3>
								</div>
								<div class="modal-body">
									
											<input type="hidden" id="task_id" name="task_id" value="<?php echo $task_row->id; ?>" readonly="">
									<div class="control-group">
										<label class="control-label" for="task_name">Task Name </label>
										<div class="controls">
											<input required type="text" id="task_name" placeholder="" name="task_name" value="<?php echo $task_row->task_name; ?>">
										</div>
									</div>
									<div class="control-group" style="display:none;">
										<label class="control-label" for="task_length">Task Length </label>
										<div class="controls">
											<input type="text" id="task_length" placeholder="" name="task_length" value="<?php echo $task_row->task_length; ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="inputPassword"></label>
										<div class="controls">
											<input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
											<input type="hidden" id="phase_id" placeholder="" name="phase_id" value="<?php echo $phase_id; ?>">
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
							
							<!-- MODAL Task Delete-->
							<div id="DeleteTask_<?php echo $task_row->id; ?>" class="modal hide fade admindev" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form class="form-horizontal" action="<?php echo base_url();?>potential_template/template_task_delete/<?php if(isset($task_row->id)){ echo $task_row->id; } ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Delete Task: <?php if(isset($task_row->task_name)){ echo $task_row->task_name; } ?></h3>
								</div>
								<div class="modal-body">
									<p>Are you sure want to delete this Task?</p>
							    
								</div>
								<div class="modal-footer delete-task">
									<input type="hidden" id="template_id" name="template_id" value="<?php echo $template_id; ?>">
									<input type="hidden" id="phase_id" placeholder="" name="phase_id" value="<?php echo $phase_id; ?>">
									<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
									<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
									<input type="submit" value="Ok" name="submit" class="btn" />
								</div>
							</form>
							</div>
							<!-- MODAL Task Delete-->
								
							</li>
							
						<?php
							}
						?>
						<li id="droppable-task-<?php echo $phase_row->id; ?>">
							 
						</li>
							<!---MODAL Task Add--->
							<div id="AddTask_<?php echo $phase_id; ?>" class="modal hide fade admindev" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<form class="form-horizontal" action="<?php echo base_url();?>potential_template/template_task_add" method="POST">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h3 id="myModalLabel">New Task Details</h3>
									</div>
									<div class="modal-body">
										
												<input type="hidden" id="task_id" name="task_id" value="" readonly="">
											
										<div class="control-group">
											<label class="control-label" for="task_name">Task Name </label>
											<div class="controls">
												<input required type="text" id="task_name" placeholder="" name="task_name" value="">
											</div>
										</div>
										<div class="control-group" style="display:none;">
											<label class="control-label" for="task_length">Task Length </label>
											<div class="controls">
												<input type="text" id="task_length" placeholder="" name="task_length" value="">
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label" for="inputPassword"></label>
											<div class="controls">
												<input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
												<input type="hidden" id="phase_id" placeholder="" name="phase_id" value="<?php echo $phase_id; ?>">
												<input type="hidden" id="phase_no" placeholder="" name="phase_no" value="<?php echo $phase_row->phase_no; ?>">
												<input type="hidden" id="task_no" placeholder="" name="task_no" value="<?php echo $task_row_count+1; ?>">
												<input type="hidden" id="task_ordering" placeholder="" name="task_ordering" value="<?php echo $task_row_count; ?>">
												<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
												<div class="save">
													<input type="submit" value="Submit" name="submit" />
												</div>
											</div>
										</div>
								    
									</div>

								</form>
								</div>
								<!-- MODAL Task Add-->
						
						</ul>
					</div>
				</li>
				
				<?php
					}
				?>
				<li id="droppable-phase"></li>
				
				<!-- MODAL Phase -->
				<div id="PhaseModal" class="modal hide fade admindev" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<form class="form-horizontal" action="<?php echo base_url();?>potential_template/template_phase_add" method="POST">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">New Phase Details</h3>
					</div>
					<div class="modal-body">
						
								<input type="hidden" id="phase_id" name="phase_id" value="" readonly="">
							
						<div class="control-group">
							<label class="control-label" for="phase_name">Phase Name </label>
							<div class="controls">
								<input required type="text" id="phase_name" placeholder="" name="phase_name" value="">
							</div>
						</div>
						<div class="control-group" style="display:none;">
							<label class="control-label" for="phase_length">Phase Length </label>
							<div class="controls">
								<input type="text" id="phase_length" placeholder="" name="phase_length" value="">
							</div>
						</div>
						
						<div class="control-group">
							<label class="control-label" for="inputPassword"></label>
							<div class="controls">
								<input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
								<input type="hidden" id="phase_no" placeholder="" name="phase_no" value="<?php echo $phase_row_count+1; ?>">
								<input type="hidden" id="phase_ordering" placeholder="" name="phase_ordering" value="<?php echo $phase_row_count; ?>">
								<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
								<div class="save">
									<input type="submit" value="Submit" name="submit" />
								</div>
							</div>
						</div>
				    
					</div>

				</form>
				</div>
				<!-- MODAL Phase -->
				<!-- Accordion -->	
				
			</ul>
			
			</div>
			<!-- Accordions -->
		</div>
			
	<div class="clear"></div>	
	</div>
	
<div class="clear"></div>

	<div class="template-footer">
			<a class="back" onclick="window.history.go(-1)">Back</a>
			<?php if(!isset($template_design_update->id)) : ?>
			<a class="next" href="<?php echo base_url();?>potential_template/template_review/<?php echo $template_id; ?>">Next</a>
			<?php else : ?>
			<a class="next" href="<?php echo base_url();?>potential_template/template_review_update/<?php echo $template_id; ?>">Next</a>
			<?php endif; ?>
		<div class="clear"></div>
	</div>
<div class="clear"></div>
</div>

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
		
		});	
});
</script>