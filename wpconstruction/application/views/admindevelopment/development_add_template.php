<script>
	window.Url = "<?php print base_url(); ?>";
jQuery(document).ready(function() {
	
	$("#template_list").change(function() {
		
		var url_dev = <?php echo '"'.$this->uri->segment(2).'"'; ?>;
		var dev_id = <?php echo $this->uri->segment(3); ?>;
		var selectedTemplateId = this.value;
		var r=confirm("Are you sure want to change this Template?");
		
		if (r==true) {
			$.ajax({
				url: window.Url + 'admindevelopment/set_development_template/' + dev_id + '/'+ selectedTemplateId,
				type: 'GET',
				success: function(data) 
				{
					newurl = window.Url + 'admindevelopment/' + url_dev + '/' + dev_id + '/' + selectedTemplateId;
					window.location = newurl; 	
				        
				},
			        
			});
		}
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
				url: window.Url + 'admindevelopment/development_phase_ordering',
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


<?php
	
	$development_id = $this->uri->segment(3);
	$template_id = $this->uri->segment(4);
	$url_phase_id = $this->uri->segment(5);

	$form_attributes = array('class' => 'form', 'id' => 'entry-form','method'=>'post');

	$action = 'admindevelopment/development_add_template/save_data';
	
	$ci = & get_instance();
	$ci->load->model('admindevelopment_model');
    $choice_template_option = $ci->admindevelopment_model->development_choice_template();

	$js = 'id="template_list"';
    $choice_template_options  = array('0' => '-- Choose Template --') + $choice_template_option;
	$choice_template = form_label('Job Template', 'choice_template');
	
	$choice_template .= form_dropdown('choice_template', $choice_template_options, $template_id, $js);


	//$submit = form_label(' ', 'submit');
	$submit = form_submit(array(
	          'name'        => 'submit',
	          'id'          => 'edit-submit',
	          'value'       => 'Next',
	          'class'       => 'form-submit',
	          'type'        => 'submit',
	          //'onclick'     => 'checkEmail();',
	));

?>

<div class="development-add-home" style="background: #fff;">
<div class="development-header">
	<div class="development-title">
		<!-- <div class="all-title"><?php echo $title; ?><span> >> <?php echo $page_title; ?></span></div> -->
		<div class="title-inner">Home > Job Info > <?php echo $page_title; ?></div>
	</div>
	<!-- <?php if(!isset($admindevelopment->id)) : ?>
	<div class="start-over">
		<a href="<?php echo base_url();?>admindevelopment/development_start">Start Over</a>
	</div>
	<?php endif; ?> -->
</div>
<div class="clear"></div>
<?php

	
echo '<div class="development_add_template-form" id="development_add_template-entry-form"><div class="development">';
echo form_open_multipart($action, $form_attributes);
echo '<div class="template">';	
echo '<div class="choice-template">'. $choice_template . '</div>';	
echo '</div>';
echo form_close();
echo '<div class="task-phase">';
?>
<div class="task-phase-inner">
<div class="task-phase-header">
	<a href="#PhaseModal" role="button" data-toggle="modal" class="plus-icon"><img src="<?php echo base_url();?>images/plus-icon1.png" /></a>
	<ul class="drag-phase-task">
		<li id="draggable-task">Add Task</li>
		<li id="draggable-phase">Add Phase</li>
	</ul>
	
<div class="clear"></div>	
</div>
<div class="clear"></div>
<!-- Accordions -->
<div class="accordions-body">
		<ul id="sortable-phase" class="accordions">
				
				<!-- Accordion -->
				<?php
					
					
					if($template_id)
					{
					
					$phase_query = $this->db->query("SELECT * FROM construction_development_phase where template_id=$template_id and development_id=$development_id ORDER BY ordering ASC");
					$result_phase = $phase_query->result();
					$phase_row_count = count($result_phase);
					foreach($result_phase as $phase_row){
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
							<a href="#EditPhase_<?php echo $phase_row->id; ?>" title="Phase Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php echo base_url();?>images/icon/edit_pen.png" /></a>
							<a href="#DeletePhase_<?php echo $phase_row->id; ?>" title="Phase Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
							
							<!-- MODAL Phase Edit -->
							<div id="EditPhase_<?php echo $phase_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form class="form-horizontal" action="<?php echo base_url(); ?>admindevelopment/development_phase_update/<?php echo $phase_row->id; ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit Phase: <?php if(isset($phase_row->phase_name)){ echo $phase_row->phase_name; } ?></h3>
								</div>
								<div class="modal-body">
									
											<input type="hidden" id="phase_id" name="phase_id" value="<?php if(isset($phase_row->id)){ echo $phase_row->id; } ?>" readonly="">
										
									<div class="control-group">
										<label class="control-label" for="phase_name">Phase Name </label>
										<div class="controls">
											<input type="text" id="phase_name" placeholder="" name="phase_name" value="<?php if(isset($phase_row->phase_name)){ echo $phase_row->phase_name; } ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="planned_start_date">Planned Start Date </label>
										<div class="controls">
											<input type="text" id="planned_start_date" placeholder="" name="planned_start_date" value="<?php if($phase_row->planned_start_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($phase_row->planned_start_date); } ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="planned_finished_date">Planned End date </label>
										<div class="controls">
											<input type="text" id="planned_finished_date" placeholder="" name="planned_finished_date" value="<?php if($phase_row->planned_finished_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($phase_row->planned_finished_date); } ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="phase_person_responsible">Person Responsible </label>
										<div class="controls">
											<input type="text" id="phase_person_responsible" placeholder="" name="phase_person_responsible" value="<?php if(isset($phase_row->phase_person_responsible)){ echo $phase_row->phase_person_responsible; } ?>">
										</div>
									</div>
									
									<div class="control-group">
										<label class="control-label" for="inputPassword"></label>
										<div class="controls">
											<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
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
							<div id="DeletePhase_<?php echo $phase_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form class="form-horizontal" action="<?php echo base_url();?>admindevelopment/development_phase_delete/<?php if(isset($phase_row->id)){ echo $phase_row->id; } ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Delete Phase: <?php if(isset($phase_row->phase_name)){ echo $phase_row->phase_name; } ?></h3>
								</div>
								<div class="modal-body">
									<p>Are you sure want to delete this Phase?</p>
							    
								</div>
								<div class="modal-footer delete-task">
									<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
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
					
					<div class="accordion-content" <?php if(($phase_row->id) == ($url_phase_id)){ echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
<script>
	var_phase = <?php echo $phase_row->id; ?>;
jQuery(document).ready(function() {
	if (jQuery("#sortable-task-<?php echo $phase_row->id; ?>").length){
		$( "#sortable-task-<?php echo $phase_row->id; ?>" ).sortable({
			update : function () { 
			var order = $('#sortable-task-<?php echo $phase_row->id; ?>').sortable('serialize');
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
		$( "#sortable-task-<?php echo $phase_row->id; ?>" ).disableSelection();
	}
});
</script>
						<ul id="sortable-task-<?php echo $phase_row->id; ?>" class="">
						<?php
							$phase_id = $phase_row->id;
							$task_query = $this->db->query("SELECT * FROM construction_development_task where template_id=$template_id and development_id=$development_id and phase_id=$phase_id ORDER BY ordering ASC");
							$result_task = $task_query->result();
							$task_row_count = count($result_task);
							foreach($result_task as $task_row){
						?>
							<li id="listItemTask_<?php echo $task_row->id; ?>">

							<?php echo $task_row->task_name; ?>
							<a href="#EditTask_<?php echo $task_row->id; ?>" title="Task Edit" role="button" data-toggle="modal" class="development-task-edit"><img width="16" height="16" src="<?php echo base_url();?>images/icon/edit_pen.png" /></a>
							<a href="#DeleteTask_<?php echo $task_row->id; ?>" title="Task Delete" role="button" data-toggle="modal" class="development-task-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
							
							<!-- MODAL Task Edit -->
							<div id="EditTask_<?php echo $task_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form class="form-horizontal" action="<?php echo base_url(); ?>admindevelopment/development_task_update/<?php echo $task_row->id; ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Edit Task: <?php if(isset($task_row->task_name)){ echo $task_row->task_name; } ?></h3>
								</div>
								<div class="modal-body">
									
											<input type="hidden" id="task_id" name="task_id" value="<?php if(isset($task_row->id)){ echo $task_row->id; } ?>" readonly="">
										
									<div class="control-group">
										<label class="control-label" for="task_name">Task Name </label>
										<div class="controls">
											<input type="text" id="task_name" placeholder="" name="task_name" value="<?php if(isset($task_row->task_name)){ echo $task_row->task_name; } ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="task_start_date">Task Start Date </label>
										<div class="controls">
											<input type="text" id="task_start_date" placeholder="" name="task_start_date" value="<?php if($task_row->task_start_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($task_row->task_start_date); }else if($phase_row->planned_start_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($phase_row->planned_start_date); } ?>">
										</div>
									</div>
									<div class="control-group">
										<label class="control-label" for="inputPassword"></label>
										<div class="controls">
											<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
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
							<div id="DeleteTask_<?php echo $task_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<form class="form-horizontal" action="<?php echo base_url();?>admindevelopment/development_task_delete/<?php if(isset($task_row->id)){ echo $task_row->id; } ?>" method="POST">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 id="myModalLabel">Delete Task: <?php if(isset($task_row->task_name)){ echo $task_row->task_name; } ?></h3>
								</div>
								<div class="modal-body">
									<p>Are you sure want to delete this Task?</p>
							    
								</div>
								<div class="modal-footer delete-task">
									<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
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

							<div id="AddTask_<?php echo $phase_id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
								<form class="form-horizontal" action="<?php echo base_url();?>admindevelopment/development_task_add" method="POST">
									<div class="modal-header">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h3 id="myModalLabel">Adding a Task</h3>
									</div>
									<div class="modal-body">
										
												<input type="hidden" id="task_id" name="task_id" value="" readonly="">
											
										<div class="control-group">
											<label class="control-label" for="task_name">Task Name </label>
											<div class="controls">
												<input type="text" id="task_name" placeholder="" name="task_name" value="">
											</div>
										</div>
										<div class="control-group">
											<label class="control-label" for="task_length">Task Length </label>
											<div class="controls">
												<input type="text" id="task_length" placeholder="" name="task_length" value="">
											</div>
										</div>
										
										
										<div class="control-group">
											<label class="control-label" for="task_start_date">Task Start Date </label>
											<div class="controls">
												<input type="text" id="task_start_date" placeholder="" name="task_start_date" value="">
											</div>
										</div>
										
										<div class="control-group">
											<label class="control-label" for="inputPassword"></label>
											<div class="controls">
												<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
												<input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
												<input type="hidden" id="phase_id" placeholder="" name="phase_id" value="<?php echo $phase_id; ?>">
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
				<li id="droppable-phase">
					
				</li>
				<!-- MODAL Phase -->
				<div id="PhaseModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<form class="form-horizontal" action="<?php echo base_url();?>admindevelopment/development_phase_add" method="POST">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
						<h3 id="myModalLabel">Adding a Phase</h3>
					</div>
					<div class="modal-body">
						
								<input type="hidden" id="phase_id" name="phase_id" value="" readonly="">
							
						<div class="control-group">
							<label class="control-label" for="phase_name">Phase Name </label>
							<div class="controls">
								<input type="text" id="phase_name" placeholder="" name="phase_name">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="phase_length">Phase Length </label>
							<div class="controls">
								<input type="text" id="phase_length" placeholder="" name="phase_length">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="planned_start_date">Planned Start Date </label>
							<div class="controls">
								<input type="text" id="planned_start_date" placeholder="" name="planned_start_date">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="planned_finished_date">Planned End date </label>
							<div class="controls">
								<input type="text" id="planned_finished_date" placeholder="" name="planned_finished_date">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="phase_person_responsible">Person Responsible </label>
							<div class="controls">
								<input type="text" id="phase_person_responsible" placeholder="" name="phase_person_responsible">
							</div>
						</div>
						<div class="control-group">
							<label class="control-label" for="inputPassword"></label>
							<div class="controls">
								<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
								<input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
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
				<?php
					} // if template_id condition close
				?>
				
				<!-- Accordion -->	
				
			</ul>
<!-- /Accordions -->
</div>

</div>
<?php
echo '</div>'; 

echo '</div>';
echo '<div class="clear"></div>'; 

echo '<div class="back-next">';
	echo '<a class="brand" onclick="window.history.go(-1)">Back</a>'; 
?>
	<?php if(isset($admindevelopment->id)) : ?>
	<a class="next" id="next" href="<?php echo base_url();?>admindevelopment/development_add_stage_update/<?php echo $development_id; ?>">Next</a>
	<?php else: ?>
	<a class="next" id="next" href="<?php echo base_url();?>admindevelopment/development_add_stage/<?php echo $development_id; ?>">Next</a>
	<?php endif; ?>
<?php
echo '<div class="clear"></div>'; 
echo '</div>';
	
echo '</div>';	
?>

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
