<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css" type="text/css" media="screen" /> 
<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
<style>
	select{
		width: auto;
	}
</style>
<a class="" style="margin-bottom: 5px; display: block" href="<?php echo site_url('job/show_popup_menu'); ?>"><< main menu</a>
<div class="popup_title">
	<h2 class="popup_title2">Add Job</h2>
</div>

<div class="Job_add row">
	<div class="col-lg-12">
		<div class="box-title">Information</div>
		<div class="development-info-table">
		<form id="frm_development_update" method="post" action="<?php echo base_url() ?>job/add_job">
				<table class="table table-bordered">
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
							<select name="project_manager" class="form-control selectpicker">
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
							<select name="status" class="form_control selectpicker">
								<option value="1">Open</option>
								<option value="0">Close</option>
								
							</select>
						</td>
					</tr>
					<tr>
						<?php if($_SERVER['SERVER_NAME'] != 'horncastle.wclp.co.nz'): ?>
							<td>Pre-Construction Template</td>
						<?php else: ?>
							<td>Design and Consenting Template</td>
						<?php endif; ?>
						<td>
							<select name="pre_construction_tid" class="form_control selectpicker">
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
							<select name="tid" class="form_control selectpicker">
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
							<select name="post_construction_tid" class="form_control selectpicker">
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
							<select name="tendering_template_id" id="tendering_template_id" class="form_control template_list selectpicker">
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
	var base_url = "<?php echo base_url(); ?>";
	$(document).ready(function(){
		$("#frm_development_update").submit(function(){
			$.ajax($(this).prop('action'),{
				type: 'POST',
				data: $(this).serialize(),
				success: function(){
					//parent.location.reload();
					window.top.location.href = base_url + "job";
					parent.$.fancybox.close();
				}
			});
			return false;
		});

		$(".datepicker").datepicker({
			dateFormat: 'yy-mm-dd'
		});
		//console.log(parent);
		//parent.location.reload();
	});
</script>