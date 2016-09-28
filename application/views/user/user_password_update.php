<script>
    window.Url = "<?php print base_url(); ?>";

	jQuery(document).ready(function() {	
		$('#button').click(function(){
			newurl = window.Url + 'user/user_setting/' + <?php echo $user_info->uid; ?>;
			window.location = newurl;
		});
		 
	});

</script>

<script>
	function checkpassword()
	{
	    var password = $('#password').val();
	    var retype_password = $('#retype-password').val();

	    if(password == retype_password){
	        $('#retype-password').css('border', '1px solid #eee');
	        return true;
			
	    }else{
	        $('#retype-password').css('border', '1px solid #FF0000');
	        return false;
	    }
	}
	
	function check_current_password()
	{
	    var current_password = $('#current_password').val();

		$.ajax({
			url: "<?php echo base_url(); ?>" + 'user/check_current_password/' + current_password,
			type: 'GET',
			success: function(data) 
			{
				if(data == 1){
			        $('#current_password').css('border', '1px solid #eee');
			        $('#current_password_access').val(data);
			        return true;
					
			    }else{
			        $('#current_password').css('border', '1px solid #FF0000');
			        $('#current_password_access').val(data);
			        return false;
			    }    
			},
		});
	    
	}
	
	function CheckpasswordValidate()
	{
	    var password = $('#password').val();
	    var retype_password = $('#retype-password').val();
	    var current_password_access = $('#current_password_access').val();

	    if(password == retype_password){

			if(current_password_access == 1){
		        return true;
		    }else{
		        $('#current_password').css('border', '1px solid #FF0000');
		        return false;
		    }
				    			
	    }else{
	        $('#retype-password').css('border', '1px solid #FF0000');
	        return false;        
	    }
	    
	}
		
</script>

<?php 
	$form_attributes = array('id' => 'user-add-form','method'=>'post','onsubmit'=>'return CheckpasswordValidate()');

	$username = form_input(array(
	              'name'        => 'username',
	              'id'          => 'username',
	              'value'       => isset($user_info->username) ? $user_info->username : '',
	              'class'       => 'form-control',
                      'placeholder'=>'Name',
                      'required'    => TRUE
	));
	
	$c_password = form_password(array(
	              'name'        => 'current_password',
	              'id'          => 'current_password',
	              'value'       => '',
	              'class'       => 'form-control',
		          'placeholder'=>'Current Password',
		      	  'onkeyup' => 'check_current_password();',
                  'required'    => TRUE 

	));
	
	$n_password = form_password(array(
	              'name'        => 'password',
	              'id'          => 'password',
	              'value'       => '',
	              'class'       => 'form-control',
		      'placeholder'=>'New Password',
                      'required'    => TRUE

	));

	
	$con_password = form_password(array(
	              'name'        => 'con_pass',
	              'id'          => 'retype-password',
	              'value'       => '',
	              'class'       => 'form-control',
		      	  'placeholder' => 'Confirm Password',
				  'onkeyup' => 'checkpassword();',
                  'required'    => TRUE

	));
	
	$email = form_input(array(
	              'name'        => 'email',
	              'id'          => 'email',
	              'value'       => isset($user_info->email) ? $user_info->email : '',
	              'class'       => 'form-control',
		          'required'    => TRUE

	));
	
    $submit = form_submit(array(
	              'name'        => 'submit',
	              'id'          => 'submit',
	              'value'       => isset($user_info->uid) ? 'Update' : 'Create',
	              'class'       => 'form-control btn btn-default',
	              'type'        => 'submit',
	));
    
	echo form_open($action, $form_attributes);
	
    ?>


<div id="project_add_edit_page" class="content-inner user-update"> 

	<div class="row">
		<div class="title col-xs-10 col-sm-10 col-md-10">
			<div class="title-inner">
				<img src="<?php echo base_url(); ?>images/add_user_1.png" width="40" />
				<p><strong>Update Details</strong><br>Use this Settings area to manage and update your details. Click on the asterisk to your right to change your details.</p>
			</div>
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
		</div>
	</div>

	<div class="main-page"> 
    	<label class="col-xs-12 control-label" style="padding-left:0px;">Update User Information</label>
	    <div class="row">
			<div class="col-xs-12 col-sm-6 col-md-6">
		    	<div class="form-group">
		    		<label for="username" class="control-label">Name</label>
		      	    <?php echo $username; ?>
		  		</div>    	 
		    	<div class="form-group">
		    		<label for="email" class="control-label">Email</label>
		      			<?php echo $email; ?>
		  		</div>    	  	 
	    	</div>
	
			<div class="col-xs-12 col-sm-6 col-md-6">  	 
		    	<div class="form-group">
		    		<label for="c_password" class="control-label">Current Password</label>
		      			<?php echo $c_password; ?>
		      			<input type="hidden" id="current_password_access" value="0"/>
		  		</div> 
		  		<div class="form-group">
		    		<label for="password" class="control-label">New Password</label>
		      			<?php echo $n_password; ?>
		  		</div>
				<div class="form-group">
		    		<label for="confirm_password" class="control-label">Confirm Password</label>
		      			<?php echo $con_password; ?>
		  		</div>   	 
	    	</div>
		</div>
	    
	   
	    <div class="row">
	    	<div class="form-group">
	    		
	    		<div class="col-xs-12 col-sm-8 col-md-8">
	      			
	      			<!--task #3797-->
					<?php
					if($user_info->google_calendar_token && $user_info->google_calendar_token != 'requested'){
						echo "TMS tasks are synced to Google calendar.".'<br/>';
						echo "<a href='".site_url('user/revoke_google_api_token')."'>Click here to unsync</a>";
					}else {
						foreach ($user_app_roles as $r) {
							if ($r->application_id == 3 && ($r->application_role_id == 2 || $r->application_role_id == 3)) {
								echo "<a href='".site_url('user/request_google_api_token')."'>Sync TMS tasks to Google calendar</a>";
								break;
							}
						}
					}
					?>
	    		</div>
	    		<div class="col-xs-6 col-sm-2 col-md-2">
	      			 <input type="button" id="button" class="form-control btn btn-default" value="Cancel" />
	    		</div>
	    		<div class="col-xs-6 col-sm-2 col-md-2">
	            	<?php echo $submit; ?>
	        	</div>
	  		</div>    	 
	    </div>
   
	</div>

</div>   
    
    
    
<?php
	echo form_close();
?>


