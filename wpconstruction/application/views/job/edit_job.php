<style>
	select{
		width: auto;
	}
</style>
<a class="" style="margin-bottom: 5px; display: block" href="<?php echo site_url('job/show_popup_menu'); ?>"><< main menu</a>
<div class="popup_title">
	<h2 class="popup_title2">Edit Job</h2>
</div>

<div class="Job_add row">
	<div class="col-lg-12">
		<div class="development-info-table">
			<form id="frm_development_update" method="post" action="<?php echo base_url() ?>job/edit_job">
				<table>
					<tr>
						<td>Select Job</td>
						<td>
							<select id="job_id" name="id" class="form_control">
								<option value="">Select Job</option>
								<?php foreach($jobs as $job): ?>
									<option value="<?php echo $job['id']; ?>"> <?php echo "#{$job['job_number']} - {$job['development_name']}" ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Job Number</td>
						<td>
							<input type="text" name="job_number" class="form-control" value="" />
						</td>
					</tr>
					<tr>
						<td>BCN Number</td>
						<td>
							<input type="text" name="bcn_number" class="form-control" value="" />
						</td>
					</tr>
					<tr>
						<td>Name</td>
						<td>
							<input type="text" name="development_name" class="form-control" value="" />
						</td>
					</tr>
					<tr>
						<td>Location</td>
						<td>
							<input type="text" name="development_location" class="form-control" value="" />
						</td>
					</tr>
					<tr>
						<td>City</td>
						<td>
							<input type="text" name="development_city" class="form-control" value="" />
						</td>
					</tr>
					<tr>
						<td>Job Size</td>
						<td>
							<input type="text" name="development_size" class="form-control" value="" />
						</td>
					</tr>
					<tr>
						<td>Land Zone</td>
						<td><input type="text" name="land_zone" class="form-control" value="" /></td>
					</tr>
					<tr>
						<td>Ground Condition</td>
						<td>
							<input type="text" name="ground_condition" class="form-control" value="" />
						</td>
					</tr>
					<tr>
						<td>Settlement Date</td>
						<td>
							<input type="text" name="settlement_date" class="form-control datepicker" value="" />
						</td>
					</tr>
					<tr>
						<td>Unconditional Date</td>
						<td>
							<input type="text" name="unconditional_date" class="form-control datepicker" value="" />
						</td>
					</tr>
					<tr>
						<td>Purchased Unconditional Date</td>
						<td>
							<input type="text" name="purchased_unconditional_date" class="form-control datepicker" value="" />
						</td>
					</tr>
					<tr>
						<td>Purchased Settlement Date</td>
						<td>
							<input type="text" name="purchased_settlement_date" class="form-control datepicker" value="" />
						</td>
					</tr>
					<tr>
						<td>Project Manager</td>
						<td>
							<select name="project_manager" class="form-control">
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
								<?php foreach($users as $user): ?>
									<option value="<?php echo $user->uid; ?>"><?php echo $user->username; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Draughtsman</td>
						<td>
							<input type="text" name="draughtsman" class="form-control" value="" />
						</td>
					</tr>
					<tr>
						<td>Engineer</td>
						<td>
							<input type="text" name="engineer" class="form-control" value="" />
						</td>
					</tr>
					<tr>
						<td>Council</td>
						<td>
							<input type="text" name="council" class="form-control" value="" />
						</td>
					</tr>

					<tr>
						<td>Status</td>
						<td>
						<select name="status" id="job_status">
							<option value="1">Open</option>
							<option value="0">Close</option>
						</select>
						</td>
					</tr>
					<tr>
						<td>Pre-Construction Template</td>
						<td>
							<select id="pre_const_template_list" name="pre_construction_tid" class="form_control template_list">
								<option value="">Select Template</option>
								<?php foreach($templates as $template): ?>
								<option value="<?php echo $template['id']; ?>"><?php echo $template['template_name']; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Construction Template</td>
						<td>
							<select id="template_list" name="tid" class="form_control template_list">
								<option value="">Select Template</option>
								<?php foreach($templates as $template): ?>
								<option value="<?php echo $template['id']; ?>"><?php echo $template['template_name']; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Post Construction Template</td>
						<td>
							<select id="post_const_template_list" name="post_construction_tid" class="form_control template_list">
								<option value="">Select Template</option>
								<?php foreach($templates as $template): ?>
									<option value="<?php echo $template['id']; ?>"><?php echo $template['template_name']; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Tendering Template</td>
						<td>
							<select name="tendering_template_id" id="tendering_template_id" class="form_control selectpicker">
								<option value="">Select Template</option>
								<?php foreach($tendering_templates as $template): ?>
									<option value="<?php echo $template->id; ?>" <?php echo ($template->id==$development_details->tendering_template_id)?" selected='selected'":""; ?> ><?php echo $template->name; ?></option>
								<?php endforeach; ?>
							</select>
						</td>
					</tr>
					<tr>
						<td></td>
						<td><input type="submit" class="btn" value="Save" style="background-color: #f9b800;color: white; float: right"></td>
					</tr>
				</table>
			</form>
		</div>
	</div>
</div>
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.css">
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
<script>
	$(document).ready(function(){
		$("#frm_development_update select[name=id]").change(function(){
			$.ajax('<?php echo base_url();?>job/get_job_details/'+$(this).val(),{
				success: function (data) {
					for(field in data){
						$("#frm_development_update [name="+field+"]").val(data[field]);
					}
				}

			})
		});
		$("#frm_development_update").submit(function(){
			$.ajax($(this).prop('action'),{
				type: 'POST',
				data: $(this).serialize(),
				success: function(){
					parent.location.reload();
					parent.$.fancybox.close();
				}
			});
			return false;
		})
	});
</script>

<script>
window.Url = "<?php print base_url(); ?>";
jQuery(document).ready(function() {
	
	$(".template_list").change(function() {
		
		//var url_dev = <?php echo '"'.$this->uri->segment(2).'"'; ?>;
		//var dev_id = <?php echo $this->uri->segment(3); ?>;
		var dev_id = $('#job_id').val();
		var selectedTemplateId = this.value;
                var pre_construction = "";
                if($(this).prop('name') == "pre_construction_tid"){
                   pre_construction = 1;
                }
                
		var r=confirm("Are you sure want to change this Template?");
		
		if (r==true) {
			$.ajax({
				url: window.Url + 'admindevelopment/set_development_template/' + dev_id + '/'+ selectedTemplateId + '/' +pre_construction,
				type: 'GET',
				success: function(data) 
				{
					alert('Successfully added template!');
					//newurl = window.Url + 'admindevelopment/' + url_dev + '/' + dev_id + '/' + selectedTemplateId;
					//window.location = newurl; 	
				        
				},
			        
			});
		}
	});

	/*changing tendering template*/
	$("#tendering_template_id").change(function() {

		var dev_id = $('#job_id').val();
		var selectedTemplateId = this.value;

		var r=confirm("Are you sure want to change this Template?");

		if(r == true){
			$.ajax({
				url: window.Url + 'admindevelopment/set_tendering_template/' + dev_id + '/'+ selectedTemplateId,
				type: 'GET',
				success: function(data)
				{
					alert('Successfully changed template!');


				}

			});
		}

	});

	$(".datepicker").datepicker({
		dateFormat: 'yy-mm-dd'
	});
	
});

</script>