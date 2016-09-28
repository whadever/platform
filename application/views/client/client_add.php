<script>
    window.Url = "<?php print base_url(); ?>";

	jQuery(document).ready(function() {	
		$('#button').click(function(){
			newurl = window.Url + 'user/user_list';
			window.location = newurl;
		});
		 
	});

</script>

<script>
	function CheckUsername()
	{	 
		var username = $('#username').val();           
        var html = $.ajax({
	        async: false,
	        url: window.BaseUrl + 'client/check_username?username=' + username,
	        type: 'POST',
	        dataType: 'html',
	        //data: {'pnr': a},
	        timeout: 2000,
	    }).responseText;
	    if(html == 1){
			$('#username').css('border', '1px solid #01416f');
    		return true;
		}else{
			$('.error').empty();
	        $('.error').append('<div class="alert alert-warning" role="alert">Warning! Company Admin already taken</div>');
	        return false;
	    }	    
	}
</script>


<div class="row">
	<div class="title col-xs-8 col-sm-8 col-md-8">
		<div class="title-inner">
			<img src="<?php echo base_url(); ?>images/add_1.png" width="40" />
			<p><strong>Manage your Clients</strong><br>Create and manage your Clients, and allow them access to particular systems.</p>
		</div>
	</div>
	<div class="col-xs-4 col-sm-4 col-md-4">
	</div>
</div>

<div class="main-page">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="client-add">
				<div class="error"></div>
				<form onsubmit="return CheckUsername()" method="post" action="<?php echo base_url(); ?>client/client_add"  enctype="multipart/form-data">
	
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="client_name">Company Name: <span class="required">*</span></label>
								<input type="text" class="form-control" id="client_name" name="client_name" required="" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="url">URL ([client].wclp.co.nz): <span class="required">*</span></label>
								<input type="text" class="form-control" id="url" name="url" required="" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="client_name">Person in charge: <span class="required">*</span></label>
								<input type="text" class="form-control" id="person_in_charge" name="person_in_charge" required="" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="client_name">Phone number: <span class="required">*</span></label>
								<input type="text" class="form-control" id="phone_number" name="phone_number" required="" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="username">Company Admin: <span class="required">*</span></label>
								<input type="text" class="form-control" id="username" name="username" required="" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="email">Email: <span class="required">*</span></label>
								<input type="text" class="form-control" id="email" name="email" required="" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="password">Password: <span class="required">*</span></label>
								<input type="password" class="form-control" id="password" name="password" required="" />
							</div>
						</div>
						
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="password">Confirm Password: <span class="required">*</span></label>
								<input type="password" class="form-control" id="password" name="password" required="" />
							</div>
						</div>
						
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="plan">Plan: <span class="required">*</span></label>
								<select class="form-control" name="plan_id" required="" >
									<option value="" >--Select Plan--</option>
									<?php
									$query = $this->db->query("SELECT * FROM wp_plans");
									$rows = $query->result();
									foreach($rows as $row)
									{
									?>
									<option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
						<!--<div class="col-xs-6 col-sm-3 col-md-3">
							<div class="form-group">
								<label for="pricing">Pricing: <span class="required">*</span></label>
								<select class="form-control" name="pricing" required="">
									<option value="">--Select Pricing--</option>
									<option value="0">Full Price</option>
									<option value="1">Discounted Price</option>
								
								</select>
							</div>
						</div>--->
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="apps">Systems: <span class="required">*</span></label>
								<select multiple class="form-control selectpicker" name="application[]" required="" data-selected-text-format="count">
									<option value="" data-hidden="true"></option>
									<?php
								$query = $this->db->query("SELECT * FROM application");
								$rows = $query->result();
								foreach($rows as $row)
								{
								?>
								<option value="<?php echo $row->id; ?>"><?php echo $row->application_full_name; ?></option>
								<?php
								}
								?>
								</select>
							</div>
						</div>
						<div class="col-xs-6 col-sm-3 col-md-3">
							<div class="form-group">
								<label for="colour_one">Colour One (#000000): <span class="required">*</span></label>
								<input type="text" class="form-control" id="colour_one" name="colour_one" required="" />
							</div>
						</div>
						<div class="col-xs-6 col-sm-3 col-md-3">
							<div class="form-group">
								<label for="colour_two">Colour Two (#000000): <span class="required">*</span></label>
								<input type="text" class="form-control" id="colour_two" name="colour_two" required="" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="logo">Logo: <span class="required">*</span></label>
								<input type="file" class="filestyle" id="logo" name="logo" required="" data-buttonText="BROWSE">
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="client_name">Background: <span class="required">*</span></label>
								<input type="file" class="filestyle" id="backgroundWclp" name="backgroundWclp" required="" data-buttonText="BROWSE">
							</div>
						</div>
						
					
						<div class="col-xs-12 col-sm-8 col-md-8">
							<!--task #4276-->
							<div class="form-group">
								<label for="client_name">Website:</label>
								<input type="text" id="website" name="website" class="form-control">
							</div>
						</div>
						<div class="col-xs-12 col-sm-2 col-md-2">
							<div class="form-group">
								<a id="button" class="form-control btn btn-default" href="<?php echo base_url(); ?>client" role="button">CANCEL</a>
							</div>
						</div>
						<div class="col-xs-12 col-sm-2 col-md-2">
							<div class="form-group">
								<input type="submit" id="submit" class="form-control btn btn-default" name="submit" value="CREATE" />
							</div>
						</div>
					
					</div>
				
				</form>
	
			</div>
		</div>
	</div>
</div>