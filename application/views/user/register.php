<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Williams Corporation Community</title>
    <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/gif">
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap-select.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>bootstrap/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
    <link href="<?php echo base_url(); ?>css/wp_styles.css" rel="stylesheet" type="text/css"/>

    <script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-2.1.4.min.js"></script>

    <script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-filestyle.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-select.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-select.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-modal.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-modalmanager.js"></script>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	
	<!-- task #4489 -->
	<script src="https://maps.googleapis.com/maps/api/js?libraries=places"></script>
	<script type="text/javascript" src="<?php echo base_url();?>/js/jquery.geocomplete.min.js"></script>

    <style>
        label {
            margin-top: 15px;
        }
        .title-inner {
            border: 2px solid #231f20;
            border-radius: 10px;
            padding: 15px 20px;
        }
        .title-inner > p {
            font-size: 20px;
            transform: translateY(21%);
        }
        .title-inner > img {
            background-color: gray;
            border-radius: 6px;
        }
    </style>
    
<script>
	jQuery(document).ready(function() {	
		$('#company_name').blur(function(){
			var url = $(this).val();
			url = url.replace(/\s+/g, '').toLowerCase();
			url = url.replace(/[^0-9a-zA-Z ]/g, "");
			$('#company_url').val(url);
		});	

		/*task #4489*/
		$("#location").geocomplete();		
	});
</script>

</head>
<body>

<div id="wrapper">
    <div class="header" style="border-bottom: 1px solid black; padding-bottom: 20px;">
        <div class="container1" style="text-align: center">
           <img src="<?php echo site_url('images/Williams-Platform-Logo.png'); ?>" width="200">
        </div>
    </div>
    <div class="container-fluid main-body">
        <div class="content">

            <div class="main-content">
                <div id="user-page" class="content-inner">
                    <div class="row">
                        <div class="title col-xs-8 col-sm-8 col-md-8">
                            <div class="title-inner">
                                <img width="40" src="<?php echo site_url('images/add_user_1.png'); ?>">
                                <p><strong>Add your company details</strong></p>
                                <div style="clear: both"></div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div id="infoMessage">

                                <?php if (validation_errors()) { ?>

                                    <div class="alert alert-warning" id="warning-alert" style="margin-top: 20px">
                                        <button type="button" class="close" data-dismiss="alert">x</button>
                                        <?php echo validation_errors(); ?>
                                    </div>
                                <?php } ?>


                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <form action="<?php echo site_url('user/register/submit'); ?>" method="post">
                            <div class="col-md-6">
                                    <label for="company_name">Company Name *</label>
                                    <input type="text" required class="form-control" name="company_name" id="company_name" value="<?php echo (set_value('company_name'))? set_value('company_name'): $company_info['company_name']; ?>">
                            </div>
                            <div class="col-md-6">
                                    <label for="company_url">URL *</label>
                                    <div class="input-group">
	                                    <div class="input-group-addon">https://</div>
	                                    <input type="text" required class="form-control" name="company_url" id="company_url" value="<?php echo set_value('company_url')? set_value('company_url'): str_replace('.wclp.co.nz','',$company_info['company_url']); ?>" placeholder="your-domain">
	                                    <div class="input-group-addon">.wclp.co.nz</div>
                                    </div>
                            </div>
                            <div class="col-md-6">
                                <label for="person_in_charge">Person in Charge *</label>
                                <input type="text" required class="form-control" name="person_in_charge" id="person_in_charge" value="<?php echo set_value('person_in_charge')? set_value('person_in_charge'): $company_info['person_in_charge']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="phone_number">Phone Number *</label>
                                <input type="text" required class="form-control" name="phone_number" id="phone_number" value="<?php echo set_value('phone_number')? set_value('phone_number'): $company_info['phone_number']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="username">Admin Username*</label>
                                <input type="text" required class="form-control" name="username" id="username" value="<?php echo set_value('username')? set_value('username'): $company_info['username']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="email">Email *</label>
                                <input type="text" required class="form-control" name="email" id="email" value="<?php echo set_value('email')? set_value('email'): $company_info['email']; ?>">
                            </div>
                            <div class="col-md-6">
                                <label for="password">Password *</label>
                                <input type="password" required class="form-control" name="password" id="password">
                            </div>
                            <div class="col-md-6">
                                <label for="re-password">Re Type Password *</label>
                                <input type="password" required class="form-control" name="re-password" id="re-password">
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
                                <?php echo form_dropdown('country', $country_array,set_value('country')? set_value('country'): $company_info['country'],array('class'=>'form-control')); ?>
                            </div>

							<div class="col-md-6">
								<label for="country">Timezone *</label>
								<select name="time_zone" class="form-control">
                                <?php
                                $time_zones = $this->db->query("SELECT * FROM wp_timezones")->result();
                                foreach($time_zones as $time_zone){
                                    echo '<option value="'.$time_zone->timezone.'">'.$time_zone->name.'</option>';
                                }
                                ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="address">Address</label>
								<input type="text" class="form-control" id="location" name="address" value="<?php echo set_value('address')? set_value('address'): $company_info['address']; ?>" />
                                <!--<textarea class="form-control" name="address"><?php echo set_value('address')? set_value('address'): $company_info['address']; ?></textarea>-->
                            </div>

                        </div>
                        <div class="row" style="margin-top: 10px">
                            <div class="col-md-6">

                            </div>
                            <div class="col-md-3">
                                <input type="button" value="Cancel" class="form-control btn btn-default" id="button">
                            </div>
                            <div class="col-md-3">
                                <input type="submit" class="form-control btn btn-default" id="submit" value="Create" name="submit">
                            </div>
                        </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
    <div class="footer" style="border-top: 1px solid black; padding-top: 20px;">
        <div class="container1">
            <p align="center" style="color:#000000;margin:0 0 10px;">User Management System</p>

            <p align="center" style="color:#000000;margin:0;">&copy; Williams Platform 2015</p>

            <p align="center">
                <a target="_BLANK" href="https://www.williamsbusiness.co.nz/"><img border="0" width="163" src="<?php echo base_url(); ?>images/PoweredByLogo.png"/></a>
            </p>
            <br/>
        </div>
    </div>
</div>
</body>
</html>


