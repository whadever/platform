<script>
  window.Url = "<?php print base_url(); ?>";
function changeStateTemplateLoadPhase(template_id,stage_no,development_id)
{
	
	var url_stage = <?php echo '"'.$this->uri->segment(2).'"'; ?>;
	var dev_id = <?php echo $this->uri->segment(3); ?>;
    var load_phase_content_id = '#pahse_content_' + stage_no;
	var r=confirm("Are you sure want to change this Template?");
	
	if (r==true) {
		$.ajax({
		                
			url: window.Url + 'admindevelopment/set_satge_template/' + template_id + '/'+ stage_no + '/'+ development_id,
			type: 'GET',
			success: function(data) 
			{
	 			newurl = window.Url + 'admindevelopment/' + url_stage + '/' + dev_id + '/' + stage_no;
				window.location = newurl;
				
			},
		});
	}
}

</script>

<?php
	$development_id = $this->uri->segment(3);
	$url_stage_no = $this->uri->segment(4);
	$url_phase_id = $this->uri->segment(5);
	$form_attributes = array('class' => 'form', 'id' => 'entry-form','method'=>'post');
	
	$ci = & get_instance();
	$ci->load->model('admindevelopment_model');
  
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

<div class="development-add-home development-add-stage" style="background: #fff;">
<div class="development-header">
	<div class="development-title">
		<div class="all-title"><?php echo $title; ?><span><?php echo $page_title; ?></span></div>
		<div class="title-inner">Home > Job Info > <?php echo $page_title_before; ?> > <?php echo $page_title; ?></div>
	</div>
	<?php if(!isset($admindevelopment->id)) : ?>
	<div class="start-over">
		<a href="<?php echo base_url();?>admindevelopment/development_start">Start Over</a>
	</div>
	<?php endif; ?>
</div>
<div class="clear"></div>
<?php

	
echo '<div class="development_add_template-form" id="development_add_template-entry-form"><div class="development">';

echo '<div class="task-phase" style="width:100%">';
?>
<div class="task-phase-inner">
<div class="task-phase-header">
	<a href="#PhaseModal" role="button" data-toggle="modal" class="plus-icon"><img src="<?php print base_url(); ?>images/plus-icon1.png" /></a>
	<ul class="drag-phase-task">
		<li id="draggable-task">Add Task</li>
		<li id="draggable-phase">Add Phase</li>
	</ul>
	<div class="clear"></div>
</div>
<div class="clear"></div>
<div class="stage-body">
<ul class="stages">
<?php 
	$ci = & get_instance();
	$ci->load->model('developments_model');
	$number_of_stages = $ci->developments_model->get_development_number_of_stage($development_id);	                 
	for($i = 1; $i <= $number_of_stages; $i++)
	{
?>	

<script>
jQuery(document).ready(function() {
	if (jQuery('#sortable-phase-<?php echo $i; ?>').length){
		$( "#sortable-phase-<?php echo $i; ?>" ).sortable({
			update : function () { 
			var order = $('#sortable-phase-<?php echo $i; ?>').sortable('serialize');
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
		$( "#sortable-phase-<?php echo $i; ?>" ).disableSelection();
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
		$( ".stage_<?php echo $i; ?>" ).droppable({
			
			drop: function( event, ui ) {
				if (ui.draggable.is('#draggable-phase')) {
					
					$(".stage_<?php echo $i; ?> .stage-content").css("display","block");
					$( this )
					.addClass( "stage-active" )
					.find( "li#droppable-phase-<?php echo $i; ?>" )
					.html( '<div class="accordion-header"><h6><a href="#AddPhase_<?php echo $i; ?>" role="button" data-toggle="modal">+ New Phase Add</a></h6></div>' );
				}
			}
		});
	

});
</script>


<li class="stage stage_<?php echo $i; ?> <?php if(($i) == ($url_stage_no)){ echo 'stage-active'; } ?>">
	<div class="stage-header">
		<div class="stage-icon"></div>
		<h6 style="height:20px">
			<div style="float:left; width:130px; margin-top:3px;">Stage <?php echo $i; ?></div>
			<div style="float:left; width:415px">
				<?php 
					$defual_query =$this->db->query("SELECT * FROM construction_stage_phase where development_id=$development_id and stage_no=$i");
					$defual_template = $defual_query->row();
					//echo $defual_template->template_id;
					if($defual_template){
						$defual_template_id = $defual_template->template_id;
					}else {
						$defual_template_id = '0';
					}

					$choice_template_option = $ci->admindevelopment_model->stage_choice_template();
					$choice_template_options  = array('0' => '-- Chose Template --') + $choice_template_option;

					$choice_template = form_label('What template you have chosen', 'choice_template');

					$js = 'id="template_list" onchange="changeStateTemplateLoadPhase(this.value,'.$i.','.$development_id.');"';

					$choice_template .= form_dropdown('choice_template', $choice_template_options, $defual_template_id, $js);

					echo $choice_template; 
				?>
			</div>
		</h6>
	</div>
	<div class="stage-content" <?php if(($i) == ($url_stage_no)){ echo 'style="display: block;"'; } else { echo 'style="display: none;"'; } ?>>
	<!-- Accordions -->
	<div class="accordions-body">
	<ul id="sortable-phase-<?php echo $i; ?>" class="accordions">
	<?php						
		$phase_query = $this->db->query("SELECT * FROM construction_stage_phase where development_id=$development_id and stage_no=$i ORDER BY ordering ASC");
		$result_phase = $phase_query->result();
		if($result_phase){
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
				.html( '<a href="#AddTask_<?php echo $phase_row->id; ?>" role="button" data-toggle="modal">+ New Task Add</a>' );
			}
		}
	});

});
</script>
			
		<!-- Accordion -->
		<li id="listItemPhase_<?php echo $phase_row->id; ?>" class="accordion <?php if(($phase_row->id) == ($url_phase_id)){ echo 'accordion-active'; } ?>">
			
			<div class="accordion-header">
				<div class="accordion-icon"></div>
				
				<h6>
					<?php echo $phase_row->phase_name; ?>
					<a href="#DeletePhase_<?php echo $phase_row->id; ?>" title="Phase Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
					<a href="#EditPhase_<?php echo $phase_row->id; ?>" title="Phase Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php echo base_url();?>images/icon/edit_pen.png" /></a>
					
					
					<!-- MODAL Phase Edit -->
					<div id="EditPhase_<?php echo $phase_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url(); ?>admindevelopment/stage_phase_update/<?php echo $phase_row->id; ?>" method="POST">
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
								<label class="control-label" for="planned_finished_date">Planned End Date </label>
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
									<input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php if(isset($phase_row->template_id)){ echo $phase_row->template_id; } ?>">
									<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $i; ?>">
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
					<form class="form-horizontal" action="<?php echo base_url();?>admindevelopment/stage_phase_delete/<?php if(isset($phase_row->id)){ echo $phase_row->id; } ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
							<h3 id="myModalLabel">Delete Phase: <?php if(isset($phase_row->phase_name)){ echo $phase_row->phase_name; } ?></h3>
						</div>
						<div class="modal-body">
							<p>Are you sure want to delete this Phase?</p>
					    
						</div>
						<div class="modal-footer delete-task">
							<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
							<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
							<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $i; ?>">
							<input type="hidden" id="phase_id" name="phase_id" value="<?php if(isset($phase_row->id)){ echo $phase_row->id; } ?>">
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
jQuery(document).ready(function() {
	if (jQuery("#sortable-task-<?php echo $phase_row->id; ?>").length){
		$( "#sortable-task-<?php echo $phase_row->id; ?>" ).sortable({
			update : function () { 
			var order = $('#sortable-task-<?php echo $phase_row->id; ?>').sortable('serialize');
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
		$( "#sortable-task-<?php echo $phase_row->id; ?>" ).disableSelection();
	}
});
</script>

				<ul id="sortable-task-<?php echo $phase_row->id; ?>" class=""> 
				 		  
				<?php
					$phase_id = $phase_row->id;
					$task_query = $this->db->query("SELECT * FROM construction_stage_task where development_id=$development_id and phase_id=$phase_id ORDER BY ordering ASC");
					$result_task = $task_query->result();
					$task_row_count = count($result_task);
					foreach($result_task as $task_row){
				?>
					<li id="listItemTask_<?php echo $task_row->id; ?>">
						<?php echo $task_row->task_name; ?>
						<a href="#DeleteTask_<?php echo $task_row->id; ?>" title="Task Delete" role="button" data-toggle="modal" class="template-task-delete"><img width="16" height="16" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png" /></a>
						<a href="#EditTask_<?php echo $task_row->id; ?>" title="Task Edit" role="button" data-toggle="modal" class="template-task-edit"><img width="16" height="16" src="<?php echo base_url();?>images/icon/edit_pen.png" /></a>
						
						
						<!-- MODAL Task Edit -->
						<div id="EditTask_<?php echo $task_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<form class="form-horizontal" action="<?php echo base_url(); ?>admindevelopment/stage_task_update/<?php echo $task_row->id; ?>" method="POST">
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
										<input type="text" id="task_start_date" placeholder="" name="task_start_date" value="<?php if($task_row->task_start_date > '0000-00-00'){ echo $this->wbs_helper->to_report_date($task_row->task_start_date); } ?>">
									</div>
								</div>
								
								<div class="control-group">
									<label class="control-label" for="inputPassword"></label>
									<div class="controls">
										<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
										<input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php if(isset($phase_row->template_id)){ echo $phase_row->template_id; } ?>">
										<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $i; ?>">
										<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
										<input type="hidden" id="phase_id" name="phase_id" value="<?php if(isset($phase_row->id)){ echo $phase_row->id; } ?>" readonly="">
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
						<form class="form-horizontal" action="<?php echo base_url();?>admindevelopment/stage_task_delete/<?php if(isset($task_row->id)){ echo $task_row->id; } ?>" method="POST">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h3 id="myModalLabel">Delete Task: <?php if(isset($task_row->task_name)){ echo $task_row->task_name; } ?></h3>
							</div>
							<div class="modal-body">
								<p>Are you sure want to delete this Task?</p>
						    
							</div>
							<div class="modal-footer delete-task">
								<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
								<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $i; ?>">
								<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
								<input type="hidden" id="phase_id" name="phase_id" value="<?php if(isset($phase_row->id)){ echo $phase_row->id; } ?>" readonly="">
								<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
								<input type="submit" value="Ok" name="submit" class="btn" />
							</div>
						</form>
						</div>
						<!-- MODAL Task Delete-->		
					</li>		
				<?php
					} //foreach end task
				?>
				<li id="droppable-task-<?php echo $phase_row->id; ?>">
				</li>	
					<div id="AddTask_<?php echo $phase_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
						<form class="form-horizontal" action="<?php echo base_url();?>admindevelopment/stage_task_add" method="POST">
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
										<input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php if(isset($phase_row->template_id)){ echo $phase_row->template_id; } ?>">
										<input type="hidden" id="phase_id" placeholder="" name="phase_id" value="<?php echo $phase_row->id; ?>">
										<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $i; ?>">
										<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
										<input type="hidden" id="task_ordering" placeholder="" name="task_ordering" value="<?php echo $task_row_count; ?>">
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
		<!-- /Accordion -->
		
		

	<?php 
		} //foreach phase loop close
	?>
	<li class="accordion droppable-phase" id="droppable-phase-<?php echo $i; ?>">
		
	</li>
	<!-- MODAL Phase -->
	<div id="AddPhase_<?php echo $i; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form class="form-horizontal" action="<?php echo base_url();?>admindevelopment/stage_phase_add" method="POST">
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
					<input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php if(isset($phase_row->template_id)){ echo $phase_row->template_id; } ?>">
					<input type="hidden" id="stage_no" placeholder="" name="stage_no" value="<?php echo $i; ?>">
					<input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
					<input type="hidden" id="phase_ordering" placeholder="" name="phase_ordering" value="<?php echo $phase_row_count; ?>">
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
		} //if condition phase close
	?>
	</ul>
	<!-- /Accordions -->
	</div>
	</div>
</li>
<?php 
	} //for loop close
?>
</ul>
<!-- /stage body close -->
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
	<a class="next" href="<?php echo base_url();?>admindevelopment/development_review_update/<?php echo $development_id; ?>">Next</a>
	<?php else: ?>
	<a class="next" href="<?php echo base_url();?>admindevelopment/development_review/<?php echo $development_id; ?>">Next</a>
	<?php endif; ?>
<?php
echo '<div class="clear"></div>'; 
echo '</div>';
	
echo '</div>';	
?>

</div>



<script type="text/javascript">
    
$(document).ready(function () {
		
	$('.stages').each(function(){
			
			//$(this).find('.stage-content').hide();
			if(!$(this).hasClass('toggles')){
				//$(this).find('.stage:first-child').addClass('stage-active');
				//$(this).find('.stage:first-child .stage-content').show();
			}
			// Set First Accordion As Active
			$(this).find('.stage-header').click(function(){
				
				if(!$(this).parent().hasClass('stage-active')){
					
					// Close other accordions
					if(!$(this).parent().parent().hasClass('toggles')){
						$(this).parent().parent().find('.stage-active').removeClass('stage-active').find('.stage-content').slideUp(300);
					}
					
					// Open Accordion
					$(this).parent().addClass('stage-active');
					$(this).parent().find('.stage-content').slideDown(300);
				
				}else{
					
					// Close Accordion
					$(this).parent().removeClass('stage-active');
					$(this).parent().find('.stage-content').slideUp(300);
					
				}
			
			});
			
			$('.accordions').each(function(){
				
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
});
</script>
