<style>
	.ui-dialog-titlebar-close::after {
		color: black;
		content: "X";
		position: absolute;
		right: 22%;
		top: -2px;
	}
</style>
<div class="row">
	<div class="title col-xs-8 col-sm-8 col-md-8">
		<div class="title-inner" style="padding: 6px 20px 11px">
			<img src="<?php echo base_url(); ?>images/add_1.png" width="40" />
			<h4>Manage your Profile</h4>
		</div>
	</div>
</div>
<?php //print_r($user);?>
<div class="main-page">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="client-add">
				<div class="error"></div>
				<form method="post" action="<?php echo base_url(); ?>client/profile"  enctype="multipart/form-data">
	
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="client_name">Company name: <span class="required">*</span></label>
								<input value="<?php echo $user->client_name; ?>" type="text" class="form-control" id="client_name" name="client_name" required="" />
							</div>
						</div>
						<!--<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="url">URL ([client].wclp.co.nz): <span class="required">*</span></label>
								<input value="<?php /*echo $user->url; */?>" type="text" class="form-control" id="url" name="url" required="" />
							</div>
						</div>-->
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="client_name">Person in charge: <span class="required">*</span></label>
								<input value="<?php echo $user->person_in_charge; ?>" type="text" class="form-control" id="person_in_charge" name="person_in_charge" required="" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="client_name">Phone number: <span class="required">*</span></label>
								<input value="<?php echo $user->phone_number; ?>" type="text" class="form-control" id="phone_number" name="phone_number" required="" />
							</div>
						</div>
						<!--<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="username">Company Admin: <span class="required">*</span></label>
								<input value="<?php /*echo $user->username; */?>" readonly type="text" class="form-control" id="username" name="username" required="" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="email">Email: <span class="required">*</span></label>
								<input value="<?php /*echo $user->email; */?>" type="text" class="form-control" id="email" name="email" required="" />
							</div>
						</div>
						
						<div class="col-xs-6 col-sm-3 col-md-3">
							<div class="form-group">
								<label for="plan">Plan: <span class="required">*</span></label>
								<select class="form-control" name="plan_id" required="" >
									<option value="" >--Select Plan--</option>
									<?php
/*								$query = $this->db->query("SELECT * FROM wp_plans");
								$plans = $query->result();
								foreach($plans as $plan)
								{
								if($user->plan_id == $plan->id)
								{
									$client_plan_default = 'selected="selected"';
								}
								else $client_plan_default = '';
								
								*/?>
								<option value="<?php /*echo $plan->id; */?>" <?php /*echo $client_plan_default; */?>><?php /*echo $plan->name; */?></option>
								<?php
/*								}
								*/?>
								</select>
							</div>
						</div>
						<div class="col-xs-6 col-sm-3 col-md-3">
							<div class="form-group">
								<label for="pricing">Pricing: <span class="required">*</span></label>
								<select class="form-control" name="pricing" required="">
									<option value="">--Select Pricing--</option>
									<option value="0" <?php /*echo $user->pricing==0?'selected="selected"':''; */?>>Full Price</option>
									<option value="1" <?php /*echo $user->pricing==1?'selected="selected"':''; */?>>Discounted Price</option>
								
								</select>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="apps">Systems: <span class="required">*</span><span class="note"></span></label>
								<select multiple class="form-control selectpicker" name="application[]" required="" data-selected-text-format="count">
									<option value="" data-hidden="true"></option>
									<?php
/*
									$query = $this->db->query("SELECT * FROM application");
									$rows = $query->result();
									foreach($rows as $row)
									{
	
										//$client_query = $this->db->query("SELECT * FROM users_application where user_id=$user->uid");
										$client_query = $this->db->query("SELECT * FROM wp_company_applications where company_id = $user->company_id");
										$client_apps = $client_query->result();
							 			$client_app_default = '';
										foreach($client_apps as $client_app)
										{
											if($client_app->application_id == $row->id)
											{
												$client_app_default = 'selected="selected"';
												break;
											}
										}
	
									*/?>
									<option value="<?php /*echo $row->id; */?>" <?php /*echo $client_app_default; */?>><?php /*echo $row->application_full_name; */?></option>
									<?php
/*									}
									*/?>
								</select>
							</div>
						</div>-->
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="logo">Logo: <span class="required">*</span></label>
								<input type="file" class="filestyle" id="logo" name="logo" data-buttonText="BROWSE">
								<?php echo $user->logo; ?>							
							</div>
						</div>
	
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="client_name">Background: <span class="required">*</span></label>
								<input type="file" class="filestyle" id="backgroundWclp" name="backgroundWclp" data-buttonText="BROWSE">
								<?php echo $user->bg; ?>	
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<!--task #4276-->
							<div class="form-group">
								<label for="client_name">Website:</label>
								<input type="text" id="website" name="website" value="<?php echo $user->website; ?>" class="form-control">
							</div>
						</div>
						
						<?php if($user->payment_token): ?>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<a class="btn btn-info" href="<?php echo site_url('user/payment_info_update'); ?>">Update payment information</a>
							<br>
							<small style="color: #BBBBBB">(This will charge NZD 0.01)</small>
							<br><br>
							<a class="btn btn-danger" id="remSub">Remove payment information</a>
							<br>
							<small style="color: red">(This will remove your subscription in the next billing cycle.)</small>
							<!--the dialog-->
							<div id="remove-confirm" title="Warning" style="display: none">
								<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>
								<p>
									Are you sure you want to remove your payment information? <br>
									This will remove your subscription in the next billing cycle.
								</p>
							</div>
						</div>
						<?php else: ?>

						<?php endif; ?>

					</div>
					
					<div class="row">
						
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="colour_one">Primary Colour (#000000): <span class="required">*</span></label>
								<div id="insertcolor">
									<input type="file" onchange="loadImageFile();" name="myPhoto" id="uploadImage" style="float:left;">
									<input type="button" onclick="myFunction()" value="Show image" />
								</div>
								<div id="picker_1" class="image-picker" style="display: none;">
								
								</div>
								<input value="<?php echo base_url().'uploads/logo/'.$user->logo;?>" type="hidden" id="logo_primary" />
								<input value="<?php echo $user->colour_one; ?>" type="text" class="form-control" id="colour_one" name="colour_one" required="" />
							</div>
						</div>
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="colour_two">Secondary Two (#000000): <span class="required">*</span></label>
								<div id="insertcolor">
									<input type="file" onchange="loadImageFile1();" name="myPhoto" id="uploadImage1" style="float:left;">
									<input type="button" onclick="myFunction1()" value="Show image" />
								</div>
								<div id="picker_2" class="image-picker" style="display: none;">
								
								</div>
								<input value="<?php echo base_url().'uploads/logo/'.$user->logo;?>" type="hidden" id="logo_secondary" />
								<input value="<?php echo $user->colour_two; ?>" type="text" class="form-control" id="colour_two" name="colour_two" required="" />
							</div>
						</div>
					</div>
					<!--task #4525-->
					<div class="row">
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="address">Company Address: *</label>
								<textarea class="form-control" id="address" name="address"><?php echo $user->address; ?></textarea>
								
							</div>
						</div>

						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="customer_code">Access Code for Customer Support</label>
								<input value="" type="text" class="form-control" id="customer_code" name=""  />
								<input type="button" onclick="getCustomerCode()" value="Generate Code" />
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-8"></div>
						<div class="col-xs-12 col-sm-2 col-md-2">
							<div class="form-group">
								<a id="button" class="form-control btn btn-default" href="<?php echo base_url(); ?>user/user_list" role="button">CANCEL</a>
							</div>
						</div>
						<div class="col-xs-12 col-sm-2 col-md-2">
							<div class="form-group">
								<input value="<?php echo $user->file_id; ?>" type="hidden" name="file_id" />
								<input value="<?php echo $user->backgroundWclp_id; ?>" type="hidden" name="backgroundWclp_id" />
								<input value="<?php echo $user->company_id; ?>" type="hidden" name="company_id" />
								<input type="submit" id="submit" class="form-control btn btn-default" name="submit" value="SAVE" />
							</div>
						</div>
					</div>
					<!------------------------>

				
				</form>
	
			</div>
		</div>
	</div>
</div>
<style>
#insertcolor {
    background-color: #eee;
    font-size: 12px;
    margin: 0 0 5px;
    padding: 5px;
    position: relative;
    text-align: right;
}
.image-picker {
    height: 250px;
    margin-bottom: 5px;
    overflow: scroll;
    width: 100%;
}
</style>

<script type="text/javascript">

	function loadImageFile(){
		
		var file = document.getElementById("uploadImage");   
      	/* Create a FormData instance */
       	var formData = new FormData();
      	/* Add the file */ 
		formData.append("upload", file.files[0]);
      	jQuery.ajax({
			url: "<?php echo base_url(); ?>" + 'welcome/loadImageFile/',
			type: 'post',
			data: formData,
			processData: false,
    		contentType: false,
			success: function(data) 
			{
				$('#logo_primary').val(data);   
			},
		});
	}
	
	function loadImageFile1(){
		
		var file = document.getElementById("uploadImage1");   
      	/* Create a FormData instance */
       	var formData = new FormData();
      	/* Add the file */ 
		formData.append("upload", file.files[0]);
      	jQuery.ajax({
			url: "<?php echo base_url(); ?>" + 'welcome/loadImageFile/',
			type: 'post',
			data: formData,
			processData: false,
    		contentType: false,
			success: function(data) 
			{
				$('#logo_secondary').val(data);   
			},
		});
	}
	
	
	function myFunction(){
		logo = $('#logo_primary').val();
		$('#picker_1').empty();
		$('#picker_1').css('display','block');
		$('#picker_1').append('<img src="'+logo+'" id="test" />');
		$("#test").ImageColorPicker({
			afterColorSelected: function(event, color){ $("#colour_one").val(color); }
		});
	}
	
	function myFunction1(){
		logo = $('#logo_secondary').val();
		$('#picker_2').empty();
		$('#picker_2').css('display','block');
		$('#picker_2').append('<img src="'+logo+'" id="test2" />');
		$("#test2").ImageColorPicker({
			afterColorSelected: function(event, color){ $("#colour_two").val(color); }
		});
	}

	/*task #4525*/
	function getCustomerCode(){
		var url = "<?php echo base_url().'client/get_customer_support_code'; ?>";
		$.ajax(url,{
			success: function(data){
				$("#customer_code").val(data);
			}
		})
	}
	
</script>

<script>
	$(document).ready(function(){
		$("#remSub").click(function(){
			$("#remove-confirm").dialog({
				resizable: false,
				height:180,
				width: 500,
				modal: true,
				buttons: {
					"OK": function() {
						var frm = $("<form>",{
							method: 'post',
							action: '<?php echo site_url('user/payment_info_remove'); ?>',
							'target': '_top'
						}).appendTo("body");
						frm.submit();
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});
		})
	})
</script>