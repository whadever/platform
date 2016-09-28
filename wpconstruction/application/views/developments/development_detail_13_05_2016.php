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

	#frm_development_update input, #frm_development_update select{
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
							<td>Name</td>
							<td>
								<input type="text" name="development_name" class="form-control" value="<?php echo $development_details->development_name; ?>" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> readonly="readonly" <?php endif; ?> />
								<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
							</td>
						</tr>
						<tr>
							<td>Build Type</td>
							<td>
								<select name="build_type" class="form-control" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> disabled="disabled" <?php endif; ?>>
									<option value="">Select Build Type</option>
									<option value="Spec Build" <?php if($development_details->build_type == 'Spec%20Build'){ echo "selected"; } ?>>Spec Build</option>
									<option value="Client Build" <?php if($development_details->build_type == 'Client%20Build'){ echo "selected"; } ?>>Client Build</option>
									<option value="Renovation" <?php if($development_details->build_type == 'Renovation'){ echo "selected"; } ?>>Renovation</option>
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
								<select name="land_zone" class="form-control" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> disabled="disabled" <?php endif; ?>>
									<option value="">Select Land Zone</option>
									<option value="L1" <?php if($development_details->land_zone == 'L1'){ echo "selected"; } ?>>L1</option>
									<option value="L2" <?php if($development_details->land_zone == 'L2'){ echo "selected"; } ?>>L2</option>
									<option value="L3" <?php if($development_details->land_zone == 'L3'){ echo "selected"; } ?>>L3</option>
									<option value="L4" <?php if($development_details->land_zone == 'L4'){ echo "selected"; } ?>>L4</option>
								</select>
								<!-- <input type="text" name="land_zone" class="form-control" value="<?php echo $development_details->land_zone; ?>" <?php if($app_role_id == 5):?> readonly="readonly" <?php endif; ?> /> -->
							<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" /></td>
						</tr>
						<tr>
							<td>Ground Condition</td>
							<td>
								<select name="ground_condition" class="form-control" <?php if($app_role_id == 5 || $app_role_id == 4 || $app_role_id == 3):?> disabled="disabled" <?php endif; ?>>
									<option value="">Select Ground Condition</option>
									<option value="TC1" <?php if($development_details->ground_condition == 'TC1'){ echo "selected"; } ?>>TC1</option>
									<option value="TC2" <?php if($development_details->ground_condition == 'TC2'){ echo "selected"; } ?>>TC2</option>
									<option value="TC3" <?php if($development_details->ground_condition == 'TC3'){ echo "selected"; } ?>>TC3</option>
								</select>
								<!-- <input type="text" name="ground_condition" class="form-control" value="<?php echo $development_details->ground_condition; ?>" <?php if($app_role_id == 5):?> readonly="readonly" <?php endif; ?> /> -->
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
										<td>Settlement Date</td>
										<td>
											<input type="text" name="settlement_date" class="form-control datepicker" value="<?php echo ($development_details->settlement_date != '0000-00-00') ? date('d-m-Y',strtotime($development_details->settlement_date)) : ''; ?>" <?php if($app_role_id == 5):?> readonly="readonly" <?php endif; ?> />
											<img class="loading" src="<?php echo base_url().'images/ajax-saving.gif'; ?>" />
										</td>
									</tr>
									<tr>
										<td>Unconditional Date</td>
										<td>
											<input type="text" name="unconditional_date" class="form-control datepicker" value="<?php echo ($development_details->unconditional_date != '0000-00-00') ? date('d-m-Y',strtotime($development_details->unconditional_date)) : ''; ?>" <?php if($app_role_id == 5):?> readonly="readonly" <?php endif; ?> />
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
						
						<tr class="key" <?php if($app_role_id == 5 || $app_role_id == 3 || $app_role_id == 4): echo 'style="display:none;"'; endif; ?>>
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
											<select id="project_manager" name="project_manager" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->project_manager))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
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
											<select id="purchaser" name="purchaser" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->purchaser))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<?php if($_SERVER['SERVER_NAME'] != 'horncastle.wclp.co.nz'): ?>
									<tr>
										<td>Investor</td>
										<td>
											<select id="investor" name="investor" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->investor))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
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
											
											<select id="builder" name="builder" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->builder))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Engineer</td>
										<td>
											
											<select id="engineer" name="engineer" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->engineer))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Draughtsman</td>
										<td>
											
											<select id="draughtsman" name="draughtsman" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->draughtsman))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Drain Layer</td>
										<td>
											
											<select id="drain_layer" name="drain_layer" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->drain_layer))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Concrete Placer</td>
										<td>
											
											<select id="concrete_placer" name="concrete_placer" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->concrete_placer))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Roofing Contractor</td>
										<td>
											
											<select id="roofing_contractor" name="roofing_contractor" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->roofing_contractor))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Bricklayer</td>
										<td>
											
											<select id="bricklayer" name="bricklayer" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->bricklayer))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Plumber</td>
										<td>
											
											<select id="plumber" name="plumber" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->plumber))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Electrician</td>
										<td>
											
											<select id="electrician" name="electrician" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->electrician))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Gibstopper</td>
										<td>
											
											<select id="gibstopper" name="gibstopper" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->gibstopper))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Tiler</td>
										<td>
											
											<select id="tiler" name="tiler" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->tiler))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Painters</td>
										<td>
											
											<select id="painters" name="painters" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->painters))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Foundation Placement</td>
										<td>
											
											<select id="foundation_placement" name="foundation_placement" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->foundation_placement))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
										</td>
									</tr>
									<tr>
										<td>Exterior Cladding</td>
										<td>
											
											<select id="exterior_cladding" name="exterior_cladding" class="multiselectbox" multiple data-show-subtext="true" data-live-search="true" <?php if($app_role_id == 5):?> disabled="disabled" <?php endif; ?>>
												<?php foreach($contacts as $contact){ ?>
												<option <?php if(in_array($contact->id, explode(",", $development_details->exterior_cladding))){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
												<?php } ?>
											</select>
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

			$("#frm_development_update select[name=build_type]").change(function(){
				update_list(this);
			});
			
			$("#frm_development_update select[name=land_zone]").change(function(){
				update_list(this);
			});	
			$("#frm_development_update select[name=ground_condition]").change(function(){
				update_list(this);
			});
			$("#frm_development_update select[name=project_manager]").change(function(){
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
						}else if(field=='build_type'){
							$('#investor_report').css('display','block');
						}
					}
				}); 	

			}
		
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