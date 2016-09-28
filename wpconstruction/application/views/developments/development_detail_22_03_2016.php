<?php
$user = $this->session->userdata('user');
$user_id = $user->uid;
$this->db->select('application_role_id');
$this->db->where('user_id',$user_id);
$this->db->where('application_id',5);
$app_role_id = $this->db->get('users_application')->row()->application_role_id;
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-select.css">
<script type="text/javascript" src="<?php echo base_url();?>/js/bootstrap-select.js"></script>

<style>
	.btn_dev_info {
		margin: 10px 5px 0 0;
	}

	.loading {
		margin-top: 9px;
		visibility: hidden;
	}

	#frm_development_update input {
		width: 95%;
		float: left;
	}

#frm_development_update .chosen-search input{width:100%}
.contractor .loading{
	display: none;
}
	.contractor .related_jobs{
		display: none;
	}
	.contractor.related_jobs tr{
		display: none;
	}
	.contractor.related_jobs tr.checked{
		display: table-row;
	}

</style>
<link href="<?php echo base_url(); ?>css/chosen.css" type="text/css" rel="stylesheet">
<div class="row">
	<div class="col-lg-12">
			<div class="development-info-table">
				<form id="frm_development_update">
					<table class="table table-bordered jobshow <?php echo $user_app_role; ?>">
						<tr>
							<td>Job Number</td>
							<td>
								<input type="text" name="job_number" class="form-control" value="<?php echo $development_details->job_number; ?>" />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>BCN Number</td>
							<td>
								<input type="text" name="bcn_number" class="form-control" value="<?php echo $development_details->bcn_number; ?>" />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Name</td>
							<td>
								<input type="text" name="development_name" class="form-control" value="<?php echo $development_details->development_name; ?>" />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Location</td>
							<td>
								<input type="text" name="development_location" class="form-control" value="<?php echo $development_details->development_location; ?>" />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>City</td>
							<td>
								<input type="text" name="development_city" class="form-control" value="<?php echo $development_details->development_city; ?>" />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Job Size</td>
							<td>
								<input type="text" name="development_size" class="form-control" value="<?php echo $development_details->development_size; ?>" />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Land Zone</td>
							<td><input type="text" name="land_zone" class="form-control" value="<?php echo $development_details->land_zone; ?>" /><img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" /></td>
						</tr>
						<tr>
							<td>Ground Condition</td>
							<td>
								<input type="text" name="ground_condition" class="form-control" value="<?php echo $development_details->ground_condition; ?>" />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Settlement Date</td>
							<td>
								<input type="text" name="settlement_date" class="form-control datepicker" value="<?php echo $development_details->settlement_date; ?>" />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Unconditional Date</td>
							<td>
								<input type="text" name="unconditional_date" class="form-control datepicker" value="<?php echo $development_details->unconditional_date; ?>" />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Purchased Unconditional Date</td>
							<td>
								<input type="text" name="purchased_unconditional_date" class="form-control datepicker" value="<?php echo $development_details->purchased_unconditional_date; ?>" />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Purchased Settlement Date</td>
							<td>
								<input type="text" name="purchased_settlement_date" class="form-control datepicker" value="<?php echo $development_details->purchased_settlement_date; ?>" />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Project Manager</td>
							<td>
								
								<select name="project_manager" data-placeholder="Select a user" class="chosen-select selectpicker" style="width:100%">
									<?php
									$user=  $this->session->userdata('user'); 
									$wp_company_id =$user->company_id;
				
									$this->db->where('users.company_id', $wp_company_id);
									$this->db->where('application_id', '5');
									$this->db->where('application_role_id', '2');
									$this->db->join('users', 'users.uid = users_application.user_id', 'left');
									$this->db->order_by('username', 'ASC');
									$users = $this->db->get('users_application')->result();
									?>
									<?php
									
										foreach($users as $result){
									?>
									<option <?php if($development_details->project_manager == $result->uid ){ ?> selected="selected" <?php } ?> value="<?php echo $result->uid; ?>"><?php echo $result->username; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>

						<tr>
							<td>Draughtsman</td>
							<td>
								<input type="text" name="draughtsman" class="form-control" value="<?php echo $development_details->draughtsman ?>" />
							</td>
						</tr>
				
						<tr>
							<td>Engineer</td>
							<td>
								<input type="text" name="engineer" class="form-control" value="<?php echo $development_details->engineer; ?>" />
							</td>
						</tr>
						<tr>
							<td>Council</td>
							<td>
								<input type="text" name="council" class="form-control" value="<?php echo $development_details->council; ?>" />
							</td>
						</tr>

						<tr>
							<td>Purchaser</td>
							<td>
								<select id="purchaser" name="purchaser" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true">
									<?php foreach($contacts as $contact){ ?>
									<option <?php if(in_array($contact->id, explode(",", $development_details->purchaser))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
									<?php } ?>
								</select>
							</td>
						</tr>

						<tr>
							<td>Client Notes</td>
							<td>
								<textarea class="form-control" id="client_notes" name="client_notes"><?php echo $development_details->client_notes; ?></textarea>
							</td>
						</tr>

						<tr>
							<td>Status</td>
							<td>
								<select name="status" id="job_status" class="selectpicker">
									<option value="1" <?php echo (1==$development_details->status)?" selected='selected'":""; ?> >Open</option>
									<option value="0" <?php echo (0==$development_details->status)?" selected='selected'":""; ?> >Close</option>
								</select>
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<!--will show this option only for jobs not under any unit as they dont have any pre construction-->
						<?php if(empty($development_details->parent_unit)): ?>
						<tr>
							<?php if($_SERVER['SERVER_NAME'] != 'horncastle.wclp.co.nz'): ?>
							<td>Pre-Construction Template</td>
							<?php else: ?>
							<td>Design and Consenting Template</td>
							<?php endif; ?>
							<td>
									<select id="pre_const_template_list" name="pre_construction_tid" class="form_control template_list selectpicker">
											<option value="">Select Template</option>
											<?php foreach($templates as $template): ?>
											<option value="<?php echo $template['id']; ?>" <?php echo ($template['id']==$development_details->pre_construction_tid)?" selected='selected'":""; ?> ><?php echo $template['template_name']; ?></option>
											<?php endforeach; ?>
									</select>
							</td>
						</tr>
						<?php endif; ?>
						<tr>
							<td>Construction Template</td>
							<td>
								<select name="tid" id="template_list" class="form_control template_list selectpicker">
									<option value="">Select Template</option>
									<?php foreach($templates as $template): ?>
									<option value="<?php echo $template['id']; ?>" <?php echo ($template['id']==$development_details->tid)?" selected='selected'":""; ?> ><?php echo $template['template_name']; ?></option>
									<?php endforeach; ?>
								</select>
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Post Construction Template</td>
							<td>
								<select name="post_construction_tid" id="post_const_template_list" class="form_control template_list selectpicker">
									<option value="">Select Template</option>
									<?php foreach($templates as $template): ?>
										<option value="<?php echo $template['id']; ?>" <?php echo ($template['id']==$development_details->post_construction_tid)?" selected='selected'":""; ?> ><?php echo $template['template_name']; ?></option>
									<?php endforeach; ?>
								</select>
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Tendering Template</td>
							<td>
								<select name="tendering_template_id" id="tendering_template_id" class="form_control template_list selectpicker">
									<option value="">Select Template</option>
									<?php foreach($tendering_templates as $template): ?>
										<option value="<?php echo $template->id; ?>" <?php echo ($template->id==$development_details->tendering_template_id)?" selected='selected'":""; ?> ><?php echo $template->name; ?></option>
									<?php endforeach; ?>
								</select>
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
					</table>
				</form>
			</div>            
		</div>
</div>
<?php if($is_unit): ?>
<div class="row">
	<div class="col-lg-12">
			<span class="related_units">Related Jobs:</span>
			<table class="related_jobs table-hover <?php echo $user_app_role; ?>">
				<thead>
				<tr>
					<th>Name</th><th>Location</th><th>Size</th><th>Template</th><th></th>
				</tr>
				</thead>
				<tbody>
				<?php foreach ($admindevelopments as $admindevelopment){ ?>
					<?php
					$checked = "";
					$class = "";
					if($admindevelopment->parent_unit == $development_id){
						$checked = "checked = 'checked'";
						$class = "checked";
					}
					?>
					<tr class="check <?php echo $class; ?>">

						<td><?php echo $admindevelopment->development_name; ?></td>
						<td><?php echo $admindevelopment->development_location; ?></td>
						<td><?php echo $admindevelopment->development_size; ?></td>
						<td><?php echo $admindevelopment->template_name; ?></td>

						<td><input <?php echo $checked; ?> type="checkbox" class="related_jobs" name="related_jobs[]" value="<?php echo $admindevelopment->id; ?>"></td>
					</tr>

				<?php } ?>
				</tbody>
			</table>
	</div>
	<div class="clear"></div>
</div>
<?php endif; ?>
      <!--change template confirmation dialog-->
<div id="confirmDialog" title="Change Template" style="display: none">
	<p>Are you sure you want to change this template?</p>
</div>
<div id="dialog-message" title="" style="display: none">
	<p>Successfully added template.</p>
</div>
<script>
    
    function email_dev_info(pid){
        
        $.ajax({
            url: "<?php print base_url(); ?>constructions/email_development/"+pid,  
                dataType: 'html',  
                type: 'GET',  
                 
                success:     
                function(data){  
                 //console.log(data);
                 if(data){  
                     
                     
                     console.log(data);
                     alert(data);
                     
                   
                 }  
                }
        });
    }
    
   
    
</script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo base_url();?>fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url();?>fancybox/jquery.fancybox.js"></script>


    <script type="text/javascript">
		window.Url = "<?php print base_url(); ?>";
		var development_id = <?php echo "'{$development_details->id}'"; ?>;
		var site_url = '<?php echo site_url(); ?>';
		var update_uri = "constructions/update";
		var development_values = <?php echo json_encode($development_details); ?>;

		function update(element){
			var field = $(element).prop('name');
			var value = $(element).val().trim();
			if(development_values[field] == value) return; //returning if value is not changed
			var url = encodeURI(site_url+update_uri+"/"+development_id+"/"+field+"/"+value);
			$(element).siblings(".loading").css('visibility','visible');
			$.ajax(url,{
				success:function(data){
					if(data==1){
						development_values[field] = value;
						$(element).siblings(".loading").css('visibility','hidden');
					}
				}
			});

		}
		$(document).ready(function() {

			var dev_id = <?php echo "'{$development_details->id}'"; ?>;

             $("#fancybox").fancybox();

			/*inline update of development fields*/
			<?php if($user_app_role != 'contractor'): ?>
			$("#frm_development_update input[type=text]").blur(function(){

				update(this);
			});
			$("#frm_development_update input.datepicker").change(function(){

				update(this);
			});
			$("#frm_development_update #job_status").change(function(){
				update(this);
			});

			$("#frm_development_update select[name=project_manager]").change(function(){
				update(this);
			});

			$("#frm_development_update #client_notes").blur(function(){
				update(this);
			});
			<?php endif; ?>

			$('.multiselectbox').selectpicker();

			$(".template_list").change(function() {
	

				var selectedTemplateId = this.value;
				var pre_construction = $(this).prop('name');

				//var r=confirm("Are you sure want to change this Template?");
				$( "#confirmDialog" ).dialog({
					resizable: false,
					modal: true,
					buttons: {
						"Yes": function() {
							$.ajax({
								url: window.Url + 'admindevelopment/set_development_template/' + dev_id + '/'+ selectedTemplateId + '/' +pre_construction,
								type: 'GET',
								success: function(data)
								{
									$( "#dialog-message" ).dialog({
										modal: true,
										buttons: {
											Ok: function() {
												$( this ).dialog( "close" );
											}
										}
									});

								},

							});
							$( this ).dialog( "close" );
						},
						Cancel: function() {
							$( this ).dialog( "close" );
						}
					}
				});

			});
			/*changing tendering template*/
			$("#tendering_template_id").change(function() {

				var selectedTemplateId = this.value;

				$( "#confirmDialog" ).dialog({
					resizable: false,
					modal: true,
					buttons: {
						"Yes": function() {
							$.ajax({
								url: window.Url + 'admindevelopment/set_tendering_template/' + dev_id + '/'+ selectedTemplateId,
								type: 'GET',
								success: function(data)
								{
									$( "#dialog-message" ).dialog({
										modal: true,
										buttons: {
											Ok: function() {
												$( this ).dialog( "close" );
											}
										}
									});

								},

							});
							$( this ).dialog( "close" );
						},
						Cancel: function() {
							$( this ).dialog( "close" );
						}
					}
				});

			});

			/*adding/removing jobs from unit*/
			$(".related_jobs").change(function(){
				var url = encodeURI(site_url+'constructions'+"/add_remove_job_to_unit/"+development_id+"/"+$(this).val()+"/"+$(this).prop('checked'));
				$.ajax(url,{
					success:function(data){

					}
				});
			});

			$(".datepicker").datepicker({
				dateFormat: 'yy-mm-dd'
			});

			$("#purchaser").change(function () {
				var cid = $(this).val();
				
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/update_purchaser?dev_id=" + development_id + "&cid=" + encodeURIComponent(cid),
					type: 'GET',
					success: function(data) 
					{
					},
				});
	        });
		
		});
    
    </script>

<script src="<?php echo base_url();?>js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
     /* $(selector).chosen(config[selector]);*/
    }
</script>