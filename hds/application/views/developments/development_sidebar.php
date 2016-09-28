<?php
/**
 *common variable
 *
 * $user	: full user object
 * $controllers	: array of controllers defined in config file where key is numeric and value is controller name like employee, company etc
 * $operations	: array of operations defined in config file where key is numeric and value is operation name like add, update, delete, print etc
 *
 *
 */
    //$controllers = $this->config->item('mbs_controllers');
    //$operations = $this->config->item('mbs_operations');

//echo $number_of_stages;
$ci = &get_instance();
$ci->load->model('developments_model');


?>



<div class="sidebar"> 
	<nav class="navbar navbar-default">
		<div class="container-fluid">
			<div class="navbar-header">
			  <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			  </button>
			  <!--<a class="navbar-brand" href="#">Menu</a>-->
			</div>
		</div>
	 </nav> 
    <div class="block grey collapse navbar-collapse" id="bs-example-navbar-collapse-1">   
         
        <ul class="accordion" id="accordion-1">
              <li class="dcjq-current-parent">
				<a <?php if($this->uri->segment(1)=="developments"){ echo'class="active"'; } ?> href="#"> Development </a>
			  	<ul style="<?php if($this->uri->segment(1)=="developments"){ echo 'display: block;'; }else{ echo 'display: none;'; } ?>">
	                <li>
	                    <a <?php if($this->uri->segment(1)=="developments" && $this->uri->segment(2)=="development_detail"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>developments/development_detail/<?php echo $development_id; ?>"> Development Info </a> 
	                </li>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="developments" && $this->uri->segment(2)=="development_overview"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>developments/development_overview/<?php echo $development_id; ?>">Development Overview </a> 
	                </li>
					<li>
	                    <a <?php if($this->uri->segment(1)=="developments" && $this->uri->segment(2)=="phases_underway"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>developments/phases_underway/<?php echo $development_id; ?>">Development Schedule </a> 
	                </li>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="developments" && $this->uri->segment(2)=="development_photos"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>developments/development_photos/<?php echo $development_id; ?>">Development Photos </a> 
	                </li>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="developments" && $this->uri->segment(2)=="notes"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>developments/notes/<?php echo $development_id; ?>">Development Notes </a> 
	                </li>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="developments" && $this->uri->segment(2)=="development_documents"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>developments/development_documents/<?php echo $development_id; ?>">Development Documents </a> 
	                </li>
	            </ul>
			  </li>	
              <?php 
              for($i=1; $i<=$number_of_stages; $i++){ ?>

<?php
	$query = $this->db->query("SELECT MIN(stage_task_status) as all_task_status FROM stage_task where development_id=$development_id and stage_no=$i");
	$all_stage_task = $query->result();
//print_r($all_stage_phase);
	if($all_stage_task[0]->all_task_status == 1)
	{ 
		$image= '<img width="18" height="17" style="float:right;" src="'.base_url().'images/icon/status_complate.png" />';
		$stage = 'complate';
	} 
	else
	{
		$image= '';
		$stage = '';
	}
?>
              <li class="dcjq-current-parent">
				<a <?php if($this->uri->segment(1)=="stage" && $stage_id==$i){ echo'class="active"'; } ?> href="#"> Stage <?php echo $i; ?>  <?php echo $image; ?></a>
				<ul style="<?php if($this->uri->segment(1)=="stage" && $stage_id==$i){ echo 'display: block;'; }else{ echo 'display: none;'; } ?>">
	                <li>
	                    <a <?php if($this->uri->segment(1)=="stage" && $stage_id==$i && $this->uri->segment(2)=="stage_info"){ echo'class="active_menu"'; } ?> href="<?php echo base_url().'stage/stage_info/'.$development_id.'/'.$i; ?>"> Stage Info </a> 
	                </li>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="stage" && $stage_id==$i && $this->uri->segment(2)=="stage_overview"){ echo'class="active_menu"'; } ?> href="<?php echo base_url().'stage/stage_overview/'.$development_id.'/'.$i; ?>">Stage Overview </a> 
	                </li>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="stage" && $stage_id==$i && $this->uri->segment(2)=="phases_list"){ echo'class="active_menu"'; } ?> href="<?php echo base_url().'stage/phases_list/'.$development_id.'/'.$i; ?>">Project Schedule </a> 
	                </li>
					<li>
	                    <a role="button" data-toggle="modal" href="<?php echo '#stage_'.$development_id.'_'.$i; ?>">Allocation Email</a> 
	                </li>
	                <!----<li>
	                    <a <?php if($this->uri->segment(1)=="stage" && $stage_id==$i && $this->uri->segment(2)=="plan_vs_actual"){ echo'class="active_menu"'; } ?> href="<?php echo base_url().'stage/plan_vs_actual/'.$development_id.'/'.$i; ?>">Plan Vs Actual</a> 
	                </li>---->
	                
	                <li>
	                    <a <?php if($this->uri->segment(1)=="stage" && $stage_id==$i && $this->uri->segment(2)=="stage_photos"){ echo'class="active_menu"'; } ?> href="<?php echo base_url().'stage/stage_photos/'.$development_id.'/'.$i; ?>">Stage Photos </a> 
	                </li>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="stage" && $stage_id==$i && $this->uri->segment(2)=="notes"){ echo'class="active_menu"'; } ?> href="<?php echo base_url().'stage/notes/'.$development_id.'/'.$i; ?>">Stage Notes </a> 
	                </li>
					<!---<li>
	                    <a <?php if($this->uri->segment(1)=="stage" && $stage_id==$i && $this->uri->segment(2)=="stage_documents"){ echo'class="active_menu"'; } ?> href="<?php echo base_url().'stage/stage_documents/'.$development_id.'/'.$i; ?>">Stage Documents </a> 
	                </li>--->
	                
	            </ul>
			  </li>
<?php
$user_dev = $ci->developments_model->allocation_email_notification_user($development_id,$i)->result();
//print_r($user_dev);
?>
				<!-- MODAL Phase Delete-->
					<div id="stage_<?php echo $development_id.'_'.$i; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">					
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h3 id="myModalLabel">Allocation Email</h3>
						</div>
						<div class="modal-body">
							<div class="control-group">
								<label class="control-label" for="phase_person_responsible">Person Responsible</label>
								<div class="controls" style="clear: both;">
									<select id="person_responsible" name="person_responsible[]" class="form-control fselect" multiple>
										<?php foreach($user_dev as $userd){ ?>
										<option value="<?php echo $userd->uid; ?>"><?php echo $userd->username; ?></option>
										<?php } ?>
									</select>
								</div>
							</div>					    
						</div>
						<div class="modal-footer delete-task">
							<input type="hidden" value="<?php echo $development_id; ?>" id="development_id" />
							<input type="hidden" value="<?php echo $i; ?>" id="stage_no" />
							<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
							<input onclick="allocation_email()" type="submit" value="Send" name="submit" id="submit" class="btn" />
						</div>
					</div>
				<!-- MODAL Phase Delete-->
              <?php  } ?>
              
        </ul> 
        
    </div>
    <div class="sidebar-block-bottom">
		<span><?php  date_default_timezone_set('NZ'); echo date("h:i a", time()).' | '; ?><?php echo date('d.m.Y', time()); echo ' | '; $today = getdate(); echo $today['weekday']; ?></span>      
    </div>
<script>
window.Url = "<?php print base_url(); ?>";
$(document).ready(function() { 
	$('.fselect').fSelect();	
});
	function allocation_email()
	{
		var stage_no = $('.in #stage_no').val();
		var development_id = $('.in #development_id').val();
	    var person_responsible = $('.in #person_responsible').val();
		var r=confirm("Are you sure want to send Email?");
		
		if (r==true) {
			$.ajax({
			                
				url: window.Url + 'developments/allocation_email_notification/' + development_id + '/'+ stage_no + '/' + person_responsible,
				type: 'GET',
				success: function(data) 
				{
					$('.modal.in').modal('hide');
				},
			});
		}
	}
</script>    
     
</div> 