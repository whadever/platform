<div class="row">
	<div class="title col-xs-8 col-sm-8 col-md-8">
		<div class="title-inner">
			<img src="<?php echo base_url(); ?>images/add_1.png" width="40" />
			<p><strong>Manage your Clients</strong><br>Create and manage your Clients, and allow them access to particular systems.</p>
		</div>
	</div>
</div>
<?php //print_r($user);?>
<div class="main-page">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="client-add">
				<div class="error"></div>
				<form method="post" action="<?php echo base_url(); ?>client/client_update/<?php echo $user->uid; ?>"  enctype="multipart/form-data">
	
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="client_name">Company name: <span class="required">*</span></label>
								<input value="<?php echo $user->client_name; ?>" type="text" class="form-control" id="client_name" name="client_name" required="" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="url">URL ([client].wclp.co.nz): <span class="required">*</span></label>
								<input value="<?php echo $user->url; ?>" type="text" class="form-control" id="url" name="url" required="" />
							</div>
						</div>
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
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="username">Company Admin: <span class="required">*</span></label>
								<input value="<?php echo $user->username; ?>" readonly type="text" class="form-control" id="username" name="username" required="" />
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="email">Email: <span class="required">*</span></label>
								<input value="<?php echo $user->email; ?>" type="text" class="form-control" id="email" name="email" required="" />
							</div>
						</div>

						<div class="col-md-6">
                                <?php
                                $countries = array("Afghanistan", "Albania", "Algeria", "American Samoa", "Andorra", "Angola", "Anguilla", "Antarctica", "Antigua and Barbuda", "Argentina", "Armenia", "Aruba", "Australia", "Austria", "Azerbaijan", "Bahamas", "Bahrain", "Bangladesh", "Barbados", "Belarus", "Belgium", "Belize", "Benin", "Bermuda", "Bhutan", "Bolivia", "Bosnia and Herzegowina", "Botswana", "Bouvet Island", "Brazil", "British Indian Ocean Territory", "Brunei Darussalam", "Bulgaria", "Burkina Faso", "Burundi", "Cambodia", "Cameroon", "Canada", "Cape Verde", "Cayman Islands", "Central African Republic", "Chad", "Chile", "China", "Christmas Island", "Cocos (Keeling) Islands", "Colombia", "Comoros", "Congo", "Congo, the Democratic Republic of the", "Cook Islands", "Costa Rica", "Cote d'Ivoire", "Croatia (Hrvatska)", "Cuba", "Cyprus", "Czech Republic", "Denmark", "Djibouti", "Dominica", "Dominican Republic", "East Timor", "Ecuador", "Egypt", "El Salvador", "Equatorial Guinea", "Eritrea", "Estonia", "Ethiopia", "Falkland Islands (Malvinas)", "Faroe Islands", "Fiji", "Finland", "France", "France Metropolitan", "French Guiana", "French Polynesia", "French Southern Territories", "Gabon", "Gambia", "Georgia", "Germany", "Ghana", "Gibraltar", "Greece", "Greenland", "Grenada", "Guadeloupe", "Guam", "Guatemala", "Guinea", "Guinea-Bissau", "Guyana", "Haiti", "Heard and Mc Donald Islands", "Holy See (Vatican City State)", "Honduras", "Hong Kong", "Hungary", "Iceland", "India", "Indonesia", "Iran (Islamic Republic of)", "Iraq", "Ireland", "Israel", "Italy", "Jamaica", "Japan", "Jordan", "Kazakhstan", "Kenya", "Kiribati", "Korea, Democratic People's Republic of", "Korea, Republic of", "Kuwait", "Kyrgyzstan", "Lao, People's Democratic Republic", "Latvia", "Lebanon", "Lesotho", "Liberia", "Libyan Arab Jamahiriya", "Liechtenstein", "Lithuania", "Luxembourg", "Macau", "Macedonia, The Former Yugoslav Republic of", "Madagascar", "Malawi", "Malaysia", "Maldives", "Mali", "Malta", "Marshall Islands", "Martinique", "Mauritania", "Mauritius", "Mayotte", "Mexico", "Micronesia, Federated States of", "Moldova, Republic of", "Monaco", "Mongolia", "Montserrat", "Morocco", "Mozambique", "Myanmar", "Namibia", "Nauru", "Nepal", "Netherlands", "Netherlands Antilles", "New Caledonia", "New Zealand", "Nicaragua", "Niger", "Nigeria", "Niue", "Norfolk Island", "Northern Mariana Islands", "Norway", "Oman", "Pakistan", "Palau", "Panama", "Papua New Guinea", "Paraguay", "Peru", "Philippines", "Pitcairn", "Poland", "Portugal", "Puerto Rico", "Qatar", "Reunion", "Romania", "Russian Federation", "Rwanda", "Saint Kitts and Nevis", "Saint Lucia", "Saint Vincent and the Grenadines", "Samoa", "San Marino", "Sao Tome and Principe", "Saudi Arabia", "Senegal", "Seychelles", "Sierra Leone", "Singapore", "Slovakia (Slovak Republic)", "Slovenia", "Solomon Islands", "Somalia", "South Africa", "South Georgia and the South Sandwich Islands", "Spain", "Sri Lanka", "St. Helena", "St. Pierre and Miquelon", "Sudan", "Suriname", "Svalbard and Jan Mayen Islands", "Swaziland", "Sweden", "Switzerland", "Syrian Arab Republic", "Taiwan, Province of China", "Tajikistan", "Tanzania, United Republic of", "Thailand", "Togo", "Tokelau", "Tonga", "Trinidad and Tobago", "Tunisia", "Turkey", "Turkmenistan", "Turks and Caicos Islands", "Tuvalu", "Uganda", "Ukraine", "United Arab Emirates", "United Kingdom", "United States", "United States Minor Outlying Islands", "Uruguay", "Uzbekistan", "Vanuatu", "Venezuela", "Vietnam", "Virgin Islands (British)", "Virgin Islands (U.S.)", "Wallis and Futuna Islands", "Western Sahara", "Yemen", "Yugoslavia", "Zambia", "Zimbabwe");
                                $country_array = array(''=>'select country');
                                foreach($countries as $country){
                                    $country_array[strtolower($country)] = $country;
                                }
                                ?>
                                <label for="country">Country *</label>
                                <?php echo form_dropdown('country', $country_array,set_value('country')? set_value('country'): $user->country,array('class'=>'form-control')); ?>
                            </div>

							<div class="col-md-6">
								<label for="country">Timezone *</label>
								<select name="time_zone" class="form-control">
                                <?php
                                $time_zones = $this->db->query("SELECT * FROM wp_timezones")->result();
                                foreach($time_zones as $time_zone){
									if($time_zone->timezone == $user->time_zone){$sl = 'selected';}else{$sl = '';}
                                    echo '<option value="'.$time_zone->timezone.'" '.$sl.'>'.$time_zone->name.'</option>';
                                }
                                ?>
                                </select>
                            </div>
						
						<div class="col-xs-6 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="plan">Plan: <span class="required">*</span></label>
								<select class="form-control" name="plan_id" required="" >
									<option value="" >--Select Plan--</option>
									<?php
								$query = $this->db->query("SELECT * FROM wp_plans");
								$plans = $query->result();
								foreach($plans as $plan)
								{
								if($user->plan_id == $plan->id)
								{
									$client_plan_default = 'selected="selected"';
								}
								else $client_plan_default = '';
								
								?>
								<option value="<?php echo $plan->id; ?>" <?php echo $client_plan_default; ?>><?php echo $plan->name; ?></option>
								<?php
								}
								?>
								</select>
							</div>
						</div>
						<!---<div class="col-xs-6 col-sm-3 col-md-3">
							<div class="form-group">
								<label for="pricing">Pricing: <span class="required">*</span></label>
								<select class="form-control" name="pricing" required="">
									<option value="">--Select Pricing--</option>
									<option value="0" <?php echo $user->pricing==0?'selected="selected"':''; ?>>Full Price</option>
									<option value="1" <?php echo $user->pricing==1?'selected="selected"':''; ?>>Discounted Price</option>
								
								</select>
							</div>
						</div>--->
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="apps">Systems: <span class="required">*</span><span class="note"></span></label>
								<select multiple class="form-control selectpicker" name="application[]" required="" data-selected-text-format="count">
									<option value="" data-hidden="true"></option>
									<?php
	
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
	
									?>
									<option value="<?php echo $row->id; ?>" <?php echo $client_app_default; ?>><?php echo $row->application_full_name; ?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
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
					
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<!--task #4276-->
							<div class="form-group">
								<label for="client_name">Website:</label>
								<input type="text" id="website" name="website" value="<?php echo $user->website; ?>" class="form-control">
							</div>
						</div>
					</div>
					
					<div class="row">
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="client_name">Background: <span class="required">*</span></label>
								<input type="file" class="filestyle" id="backgroundWclp" name="backgroundWclp" data-buttonText="BROWSE">
								<?php if($user->bg){ ?><img src="<?php echo base_url().'uploads/background/'.$user->bg;?>" width="100" height="75" /><?php } ?>
							</div>
						</div>
						<div class="col-xs-12 col-sm-6 col-md-6">
							<div class="form-group">
								<label for="logo">Logo: <span class="required">*</span></label>
								<input type="file" class="filestyle" id="logo" name="logo" data-buttonText="BROWSE">
								<?php if($user->logo){ ?><img src="<?php echo base_url().'uploads/logo/'.$user->logo;?>" width="100" height="50" /><?php } ?>						
							</div>
						</div>
						
					</div>
					<div class="row">
						<div class="col-xs-12 col-sm-2 col-md-2">
							<div class="form-group">
								<a id="button" class="form-control btn btn-default" href="<?php echo base_url(); ?>client" role="button">CANCEL</a>
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
	
</script>