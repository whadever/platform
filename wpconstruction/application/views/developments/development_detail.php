<?php
$ci = & get_instance();
$ci->load->model('company_model');
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

	#frm_development_update input, #frm_development_update select {
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

/*--- Task 4464 ---*/

.key .key-open, .key .key-close{
	float: right;
	width: 25px;
}
.key{
	font-weight: bold;
	cursor: pointer;
}
.close{
	display: none;
}
.table #all_key .table{
	background-color: transparent;
	margin: 0px;
	border: 0px;
}
#all_key table tr td:first-child{
	text-align: right;
	width: 33%;
}

</style>

<script type="text/javascript">
    function key_change(key){
		if(key=='key_date'){
			$('#img_key_date.key-close').hide();
			$('#img_key_date.key-open').show();
			$('.key_date').show('slow');
		}
		if(key=='key_date_close'){
			$('#img_key_date.key-close').show();
			$('#img_key_date.key-open').hide();
			$('.key_date').hide('slow');
		}
		if(key=='key_people'){
			$('#img_key_people.key-close').hide();
			$('#img_key_people.key-open').show();
			$('.key_people').show('slow');
		}
		if(key=='key_people_close'){
			$('#img_key_people.key-close').show();
			$('#img_key_people.key-open').hide();
			$('.key_people').hide('slow');
		}
		if(key=='key_trades'){
			$('#img_key_trades.key-close').hide();
			$('#img_key_trades.key-open').show();
			$('.key_trades').show('slow');
		}
		if(key=='key_trades_close'){
			$('#img_key_trades.key-close').show();
			$('#img_key_trades.key-open').hide();
			$('.key_trades').hide('slow');
		}
		if(key=='key_templates'){
			$('#img_key_templates.key-close').hide();
			$('#img_key_templates.key-open').show();
			$('.key_templates').show('slow');
		}
		if(key=='key_templates_close'){
			$('#img_key_templates.key-close').show();
			$('#img_key_templates.key-open').hide();
			$('.key_templates').hide('slow');
		}
		
	}
</script>
<link href="<?php echo base_url(); ?>css/chosen.css" type="text/css" rel="stylesheet">
<div class="row">
	<div class="col-lg-12">
			<div class="development-info-table">
				<form id="frm_development_update">
					<table class="table table-bordered jobshow <?php echo $user_app_role; ?>">
						<tr <?php if($app_role_id == 5 || $app_role_id == 3): echo 'style="display:none;"'; endif; ?>>
							<td>Job Number</td>
							<td>
								<input type="text" name="job_number" class="form-control" value="<?php echo $development_details->job_number; ?>" <?php if($app_role_id == 5 || $app_role_id == 4):?> readonly="readonly" <?php endif; ?> />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr <?php if($app_role_id == 5 || $app_role_id == 3): echo 'style="display:none;"'; endif; ?>>
							<td>BCN Number</td>
							<td>
								<input type="text" name="bcn_number" class="form-control" value="<?php echo $development_details->bcn_number; ?>" <?php if($app_role_id == 5 || $app_role_id == 4):?> readonly="readonly" <?php endif; ?> />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Job Name</td>
							<td>
								<input type="text" name="development_name" class="form-control" value="<?php echo $development_details->development_name; ?>" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> readonly="readonly" <?php endif; ?> />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<!--
						<tr>
							<td>Job Color</td>
							<td>
								<select id="job_color" name="job_color" class="form-control" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> disabled="disabled" <?php endif; ?>>
									<option value="">Select Job Color</option>
									<option value="Red" <?php if($development_details->job_color == 'Red'){ echo "selected"; } ?>>Red</option>
									<option value="Green" <?php if($development_details->job_color == 'Green'){ echo "selected"; } ?>>Green</option>
									<option value="Black" <?php if($development_details->job_color == 'Black'){ echo "selected"; } ?>>Black</option>
								</select>
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>-->
						<tr>
							<td>Build Type</td>
							<td>
								<select name="build_type" class="form-control" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> disabled="disabled" <?php endif; ?>>
									<option value="">Select Build Type</option>
									<option value="Spec Build" <?php if($development_details->build_type == 'Spec%20Build'){ echo "selected"; } ?>>Spec Build</option>
									<option value="Client Build" <?php if($development_details->build_type == 'Client%20Build'){ echo "selected"; } ?>>Client Build</option>
									<option value="Renovation" <?php if($development_details->build_type == 'Renovation'){ echo "selected"; } ?>>Renovation</option>
									<option value="Section Only" <?php if($development_details->build_type == 'Section%20Only'){ echo "selected";} ?>>Section Only</option>
								</select>
								<!-- <input type="text" name="land_zone" class="form-control" value="<?php echo $development_details->land_zone; ?>" <?php if($app_role_id == 5):?> readonly="readonly" <?php endif; ?> /> -->
							<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" /></td>
						</tr>
						<tr>
							<td>Address</td>
							<td>
								<input type="text" name="development_location" class="form-control" value="<?php echo $development_details->development_location; ?>" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> readonly="readonly" <?php endif; ?> />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>City</td>
							<td>
								<input type="text" name="development_city" class="form-control" value="<?php echo $development_details->development_city; ?>" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> readonly="readonly" <?php endif; ?> />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Land Size</td>
							<td>
								<input type="text" name="development_size" class="form-control" value="<?php echo $development_details->development_size; ?>" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> readonly="readonly" <?php endif; ?> />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Land Zone</td>
							<td>
								<select id="land_zone" name="land_zone" class="form-control" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> disabled="disabled" <?php endif; ?>>
									<option value="">Select Land Zone</option>
									<option value="L1" <?php if($development_details->land_zone == 'L1'){ echo "selected"; } ?>>L1</option>
									<option value="L2" <?php if($development_details->land_zone == 'L2'){ echo "selected"; } ?>>L2</option>
									<option value="L3" <?php if($development_details->land_zone == 'L3'){ echo "selected"; } ?>>L3</option>
									<option value="L4" <?php if($development_details->land_zone == 'L4'){ echo "selected"; } ?>>L4</option>
								</select>
							<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" /></td>
						</tr>
						<tr>
							<td>Ground Condition</td>
							<td>
								<select id="ground_condition" name="ground_condition" class="form-control" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> disabled="disabled" <?php endif; ?>>
									<option value="">Select Ground Condition</option>
									<option value="TC1" <?php if($development_details->ground_condition == 'TC1'){ echo "selected"; } ?>>TC1</option>
									<option value="TC2" <?php if($development_details->ground_condition == 'TC2'){ echo "selected"; } ?>>TC2</option>
									<option value="TC3" <?php if($development_details->ground_condition == 'TC3'){ echo "selected"; } ?>>TC3</option>
								</select>
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr class="key" <?php if($app_role_id == 3 || $app_role_id == 4): echo 'style="display:none;"'; endif; ?>>
							<td colspan="2">Key Dates
							<div id="img_key_date" class="key-close"><img onclick="key_change('key_date');" width="20" height="15" src="<?php echo base_url(); ?>images/key_close.png" /></div>
							<div id="img_key_date" class="key-open" style="display: none;"><img onclick="key_change('key_date_close');" width="20" height="15" src="<?php echo base_url(); ?>images/key_open.png" /></div>
							</td>
						</tr>
						<tr id="all_key" class="key_date" style="display: none;">
							<td colspan="2" style="padding: 0px;">
								<table class="table table-bordered">

									<tr>
										<td>Unconditional Date</td>
										<td>
											<input type="text" name="unconditional_date" class="form-control datepicker" value="<?php echo ($development_details->unconditional_date != '0000-00-00') ? date('d-m-Y',strtotime($development_details->unconditional_date)) : ''; ?>" <?php if($app_role_id == 5):?> readonly="readonly" <?php endif; ?> />
											<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
										</td>
									</tr>
									<tr>
										<td>Settlement Date</td>
										<td>
											<input type="text" name="settlement_date" class="form-control datepicker" value="<?php echo ($development_details->settlement_date != '0000-00-00') ? date('d-m-Y',strtotime($development_details->settlement_date)) : ''; ?>" <?php if($app_role_id == 5):?> readonly="readonly" <?php endif; ?> />
											<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
										</td>
									</tr>
									<tr>
										<td>Purchased Unconditional Date</td>
										<td>
											<input type="text" name="purchased_unconditional_date" class="form-control datepicker" value="<?php echo ($development_details->purchased_unconditional_date != '0000-00-00') ?  date('d-m-Y',strtotime($development_details->purchased_unconditional_date)) : ''; ?>" <?php if($app_role_id == 5):?> readonly="readonly" <?php endif; ?> />
											<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
										</td>
									</tr>
									<tr>
										<td>Purchased Settlement Date</td>
										<td>
											<input type="text" name="purchased_settlement_date" class="form-control datepicker" value="<?php echo ($development_details->purchased_settlement_date != '0000-00-00') ?  date('d-m-Y',strtotime($development_details->purchased_settlement_date)) : ''; ?>" <?php if($app_role_id == 5):?> readonly="readonly" <?php endif; ?> />
											<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
						<tr class="key" <?php if($app_role_id == 5 || $app_role_id == 3 || $app_role_id == 4): echo 'style="display:none;"'; endif; ?> <?php if($app_role_id == 5): echo 'style="display:none;"'; endif; ?>>
							<td colspan="2">Key People
							<div id="img_key_people" class="key-close"><img onclick="key_change('key_people');" width="20" height="15" src="<?php echo base_url(); ?>images/key_close.png" /></div>
							<div id="img_key_people" class="key-open" style="display: none;"><img onclick="key_change('key_people_close');" width="20" height="15" src="<?php echo base_url(); ?>images/key_open.png" /></div>
							</td>
						</tr>
						<tr id="all_key" class="key_people" style="display: none;">
							<td colspan="2" style="padding: 0px;">
								<table class="table table-bordered">
									<tr>
										<td>Project Manager</td>
										<td>
											<span id="user_select_list"><?php echo $ci->company_model->get_company_select_list('project_manager_company','project_manager_company',$development_details->id); ?></span>
          									<span id="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('project_manager','project_manager',$development_details->project_manager,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Council</td>
										<td>
											<input type="text" name="council" class="form-control" value="<?php echo $development_details->council; ?>" <?php if($app_role_id == 5):?> readonly="readonly" <?php endif; ?> />
										</td>
									</tr>
									<tr>
										<td>Purchaser / Client</td>
										<td>
											<span id="user_select_list"><?php echo $ci->company_model->get_company_select_list('purchaser_company','purchaser_company',$development_details->id); ?></span>
          									<span id="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('purchaser','purchaser',$development_details->purchaser,$development_details->id); ?></span>
										</td>
									</tr>
									<?php if($_SERVER['SERVER_NAME'] != 'horncastle.wclp.co.nz'): ?>
									<tr>
										<td>Investor</td>
										<td>
											<span id="user_select_list"><?php echo $ci->company_model->get_company_select_list('investor_company','investor_company',$development_details->id); ?></span>
          									<span id="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('investor','investor',$development_details->investor,$development_details->id); ?></span>
										</td>
									</tr>
									<?php endif; ?>
									<tr>
										<td>Client Notes</td>
										<td>
											<textarea class="form-control" id="client_notes" name="client_notes" <?php if($app_role_id == 5):?> readonly="readonly" <?php endif; ?>><?php echo $development_details->client_notes; ?></textarea>
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
						<tr class="key" <?php if($app_role_id == 5 || $app_role_id == 3 || $app_role_id == 4): echo 'style="display:none;"'; endif; ?>>
							<td colspan="2">Key Trades People
							<div id="img_key_trades" class="key-close"><img onclick="key_change('key_trades');" width="20" height="15" src="<?php echo base_url(); ?>images/key_close.png" /></div>
							<div id="img_key_trades" class="key-open" style="display: none;"><img onclick="key_change('key_trades_close');" width="20" height="15" src="<?php echo base_url(); ?>images/key_open.png" /></div>
							</td>
						</tr>
						<tr id="all_key" class="key_trades" style="display: none;">
							<td colspan="2" style="padding: 0px;">
								<table class="table table-bordered">
									<tr>
										<td>Builder</td>
										<td>
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('builder_company','builder_company',$development_details->id); ?></span>
          									<span id="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('builder','builder',$development_details->builder,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Engineer</td>
										<td>
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('engineer_company','engineer_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('engineer','engineer',$development_details->engineer,$development_details->id); ?></span>

										</td>
									</tr>
									<tr>
										<td>Draughtsman</td>
										<td>
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('draughtsman_company','draughtsman_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('draughtsman','draughtsman',$development_details->draughtsman,$development_details->id); ?></span>	
										</td>
									</tr>
									<tr>
										<td>Drain Layer</td>
										<td>
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('drain_layer_company','drain_layer_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('drain_layer','drain_layer',$development_details->drain_layer,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Concrete Placer</td>
										<td>
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('concrete_placer_company','concrete_placer_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('concrete_placer','concrete_placer',$development_details->concrete_placer,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Roofing Contractor</td>
										<td>
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('roofing_contractor_company','roofing_contractor_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('roofing_contractor','roofing_contractor',$development_details->roofing_contractor,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Bricklayer</td>
										<td>
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('bricklayer_company','bricklayer_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('bricklayer','bricklayer',$development_details->bricklayer,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Plumber</td>
										<td>
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('plumber_company','plumber_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('plumber','plumber',$development_details->plumber,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Electrician</td>
										<td>
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('electrician_company','electrician_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('electrician','electrician',$development_details->electrician,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Gibstopper</td>
										<td>
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('gibstopper_company','gibstopper_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('gibstopper','gibstopper',$development_details->gibstopper,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Tiler</td>
										<td>											
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('tiler_company','tiler_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('tiler','tiler',$development_details->tiler,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Painters</td>
										<td>
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('painters_company','painters_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('painters','painters',$development_details->painters,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Foundation Placement</td>
										<td>
											
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('foundation_placement_company','foundation_placement_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('foundation_placement','foundation_placement',$development_details->foundation_placement,$development_details->id); ?></span>
										</td>
									</tr>
									<tr>
										<td>Exterior Cladding</td>
										<td>
											<span class="company_select_list"><?php echo $ci->company_model->get_company_select_list('exterior_cladding_company','exterior_cladding_company',$development_details->id); ?></span>
          									<span class="contact_select_list"><?php echo $ci->company_model->get_contact_select_list('exterior_cladding','exterior_cladding',$development_details->exterior_cladding,$development_details->id); ?></span>
										</td>
									</tr>
								</table>
							</td>
						</tr>

						<tr <?php if($app_role_id == 5 || $app_role_id == 3 || $app_role_id == 4): echo 'style="display:none;"'; endif; ?>>
							<td>Status</td>
							<td>
								<select name="status" id="job_status" class="selectpicker" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
									<option value="1" <?php echo (1==$development_details->status)?" selected='selected'":""; ?> >Open</option>
									<option value="0" <?php echo (0==$development_details->status)?" selected='selected'":""; ?> >Close</option>
								</select>
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>

						<tr class="key" <?php if($app_role_id == 5 || $app_role_id == 3 || $app_role_id == 4): echo 'style="display:none;"'; endif; ?>>
							<td colspan="2">Job Templates
							<div id="img_key_templates" class="key-close"><img onclick="key_change('key_templates');" width="20" height="15" src="<?php echo base_url(); ?>images/key_close.png" /></div>
							<div id="img_key_templates" class="key-open" style="display: none;"><img onclick="key_change('key_templates_close');" width="20" height="15" src="<?php echo base_url(); ?>images/key_open.png" /></div>
							</td>
						</tr>


						<!--will show this option only for jobs not under any unit as they dont have any pre construction-->
						<?php if(empty($development_details->parent_unit)): ?>
						<tr id="all_key" class="key_templates" style="display: none;">
							<td colspan="2" style="padding: 0px;">
								<table class="table table-bordered">
									<tr>
										<?php if($_SERVER['SERVER_NAME'] != 'horncastle.wclp.co.nz'): ?>
										<td>Pre-Construction Template</td>
										<?php else: ?>
										<td>Design and Consenting Template</td>
										<?php endif; ?>
										<td>
												<select id="pre_const_template_list" name="pre_construction_tid" class="form_control template_list selectpicker" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
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
											<select name="tid" id="template_list" class="form_control template_list selectpicker" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
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
											<select name="post_construction_tid" id="post_const_template_list" class="form_control template_list selectpicker" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
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
											<select name="tendering_template_id" id="tendering_template_id" class="form_control template_list selectpicker" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<option value="">Select Template</option>
												<?php foreach($tendering_templates as $template): ?>
													<option value="<?php echo $template->id; ?>" <?php echo ($template->id==$development_details->tendering_template_id)?" selected='selected'":""; ?> ><?php echo $template->name; ?></option>
												<?php endforeach; ?>
											</select>
											<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
				</form>
			</div>            
		</div>
</div>
<?php if($is_unit && $app_role_id != 5 ): ?>
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
			/* task #4591 */
			if($(element).hasClass('datepicker')){
				var arr = value.split('-');
				value = arr[2]+'-'+arr[1]+'-'+arr[0];
			}
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
				update_list(this);
			});

			$("#frm_development_update select[name=build_type]").change(function(){
				update_list(this);
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
				dateFormat: 'dd-mm-yy'
			});
			
			$("#purchaser").change(function () {
				update_list(this);
	        });

			$("#land_zone").change(function () {
				update_list(this);
	        });

			$("#ground_condition").change(function () {
				update_list(this);
	        });

			$("#job_color").change(function () {
				update_list(this);
	        });

			$("#investor").change(function () {
				update_list(this);
	        });

			$(".key_trades select").change(function(){
				update_list(this);
			});

			function update_list(element){
				var field = $(element).prop('name');
				var value = $(element).val();
				var url = encodeURI(site_url+"constructions/update_list/"+development_id+"/"+field+"/"+value);
				$(element).siblings(".loading").css('visibility','visible');
				$.ajax(url,{
					success:function(data){
						if(data==1){
							development_values[field] = value;
							$(element).siblings(".loading").css('visibility','hidden');
						}
						if(field=='build_type' && value=='Client Build'){
							$('#investor_report').css('display','none');
						}
						if(field=='build_type' && value=='Spec Build'){
							$('#investor_report').css('display','block');
						}
					}
				});
			}

			// Project Manager company wise contact select
	        $("#project_manager_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=project_manager&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#project_manager").empty();
						$("#project_manager").append("<option value='0'></option>");
						$("#project_manager").append(data);
						$("#project_manager").selectpicker('refresh');
					},
				});
	        });

			// Purchaser company wise contact select
	        $("#purchaser_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=purchaser&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#purchaser").empty();
						$("#purchaser").append("<option value='0'></option>");
						$("#purchaser").append(data);
						$("#purchaser").selectpicker('refresh');
					},
				});
	        });

			// Investor company wise contact select
	        $("#investor_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=investor&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#investor").empty();
						$("#investor").append("<option value='0'></option>");
						$("#investor").append(data);
						$("#investor").selectpicker('refresh');
					},
				});
	        });

			// Builder company wise contact select
	        $("#builder_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=builder&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#builder").empty();
						$("#builder").append("<option value='0'></option>");
						$("#builder").append(data);
						$("#builder").selectpicker('refresh');
					},
				});
	        });

			// engineer company wise contact select
	        $("#engineer_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=engineer&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#engineer").empty();
						$("#engineer").append("<option value='0'></option>");
						$("#engineer").append(data);
						$("#engineer").selectpicker('refresh');
					},
				});
	        });

			// draughtsman company wise contact select
	        $("#draughtsman_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=draughtsman&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#draughtsman").empty();
						$("#draughtsman").append("<option value='0'></option>");
						$("#draughtsman").append(data);
						$("#draughtsman").selectpicker('refresh');
					},
				});
	        });

			// drain_layer company wise contact select
	        $("#drain_layer_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=draughtsman&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#drain_layer").empty();
						$("#drain_layer").append("<option value='0'></option>");
						$("#drain_layer").append(data);
						$("#drain_layer").selectpicker('refresh');
					},
				});
	        });

			// concrete placer company wise contact select
	        $("#concrete_placer_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=concrete_placer&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#concrete_placer").empty();
						$("#concrete_placer").append("<option value='0'></option>");
						$("#concrete_placer").append(data);
						$("#concrete_placer").selectpicker('refresh');
					},
				});
	        });

			// roofing_contractor company wise contact select
	        $("#roofing_contractor_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=roofing_contractor&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#roofing_contractor").empty();
						$("#roofing_contractor").append("<option value='0'></option>");
						$("#roofing_contractor").append(data);
						$("#roofing_contractor").selectpicker('refresh');
					},
				});
	        });

			// bricklayer company wise contact select
	        $("#bricklayer_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=bricklayer&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#bricklayer").empty();
						$("#bricklayer").append("<option value='0'></option>");
						$("#bricklayer").append(data);
						$("#bricklayer").selectpicker('refresh');
					},
				});
	        });

			// plumber company wise contact select
	        $("#plumber_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=plumber&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#plumber").empty();
						$("#plumber").append("<option value='0'></option>");
						$("#plumber").append(data);
						$("#plumber").selectpicker('refresh');
					},
				});
	        });

			// electrician company wise contact select
	        $("#electrician_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=electrician&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#electrician").empty();
						$("#electrician").append("<option value='0'></option>");
						$("#electrician").append(data);
						$("#electrician").selectpicker('refresh');
					},
				});
	        });

			// Gibstopper company wise contact select
	        $("#gibstopper_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=gibstopper&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#gibstopper").empty();
						$("#gibstopper").append("<option value='0'></option>");
						$("#gibstopper").append(data);
						$("#gibstopper").selectpicker('refresh');
					},
				});
	        });

			// Tiler company wise contact select
	        $("#tiler_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=tiler&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#tiler").empty();
						$("#tiler").append("<option value='0'></option>");
						$("#tiler").append(data);
						$("#tiler").selectpicker('refresh');
					},
				});
	        });

			// Painters company wise contact select
	        $("#painters_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=painters&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#painters").empty();
						$("#painters").append("<option value='0'></option>");
						$("#painters").append(data);
						$("#painters").selectpicker('refresh');
					},
				});
	        });

			// Foundation placement company wise contact select
	        $("#foundation_placement_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=foundation_placement&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#foundation_placement").empty();
						$("#foundation_placement").append("<option value='0'></option>");
						$("#foundation_placement").append(data);
						$("#foundation_placement").selectpicker('refresh');
					},
				});
	        });

			// Exterior Cladding company wise contact select
	        $("#exterior_cladding_company").change(function () {
				var cid = $(this).val();
				jQuery.ajax({
					url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid)+"&field=exterior_cladding&job_id="+development_id,
					type: 'GET',
					success: function(data) 
					{
						$("#exterior_cladding").empty();
						$("#exterior_cladding").append("<option value='0'></option>");
						$("#exterior_cladding").append(data);
						$("#exterior_cladding").selectpicker('refresh');
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