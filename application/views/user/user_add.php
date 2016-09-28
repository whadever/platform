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
	
	function ValidateEmail()  
	{  
		var email = $('#email').val();
		var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
		if(email.match(mailformat))  
		{  
			$.ajax({				
				url: window.Url + 'user/user_email_check?email=' + email+'&company_id='+'<?php echo $user->company_id; ?>',
				type: 'POST',
				success: function(data) 
				{	
					//console.log(data);
					if(data == 1){
						$('#email').css('border', '1px solid #FF0000');
						$('.taken').empty();
						$('.taken').append('<span style="color:#FF0000;">Taken</span>');
		        		return false;
					}else{
				        $('#email').css('border', '1px solid #eee');
				        $('.taken').empty();
				        $('.taken').append('<span style="color:#000;">Available</span>');
				        return true;
				    }
				},
			        
			}); 
			//return true;  
		}  
		else  
		{  
			$('#email').css('border', '1px solid #FF0000');  
			$('.taken').empty();
			$('.taken').append('<span style="color:#000;">Email format wrong!</span>');
			return false;  
		}  
	}

	function ValidateUsername()  
	{  
		var username = $('#username').val(); 
		//alert(window.Url + 'user/username_check?username=' + username+'&company_id='+'<?php echo $user->company_id; ?>');

		$.ajax({				
			url: window.Url + 'user/username_check?username=' + username+'&company_id='+'<?php echo $user->company_id?>',

			type: 'POST',
			success: function(data) 
			{	
				//console.log(data);
				//alert(data);
				if(data == 1){
					$('#username').css('border', '1px solid #FF0000');
					$('.usernametaken').empty();
					$('.usernametaken').append('<span style="color:#FF0000;">Taken</span>');
					$('#useraccess').val(data);
	        		return false;
				}else{
			        $('#username').css('border', '1px solid #eee');
			        $('.usernametaken').empty();
			        $('.usernametaken').append('<span style="color:#000;">Available</span>');
					$('#useraccess').val(data);
			        return true;
			    }
			},
		        
		}); 
  
	}
	
	function CheckpasswordValidateEmail()
	{
	    var password = $('#password').val();
	    var retype_password = $('#retype-password').val();
		var email = $('#email').val();

		var useraccess = $('#useraccess').val();

	    if(password == retype_password){

			if(useraccess==0){

				if(email.match(mailformat))  
				{
			        $('#retype-password').css('border', '1px solid #eee');
			        
			        var html = $.ajax({
				        async: false,
				        url: window.Url + 'user/user_email_check?email=' + email,
				        type: 'POST',
				        dataType: 'html',
				        timeout: 2000,
				    }).responseText;
				    if(html==1){
				        $('#email').css('border', '1px solid #FF0000');
						$('.taken').empty();
						$('.taken').append('<span style="color:#FF0000;">Taken</span>');
		        		return false;
				    }else{
				        $('#email').css('border', '1px solid #eee');
				        $('.taken').empty();
				        $('.taken').append('<span style="color:#000;">Available</span>');
				        return true;
				    } 
	
				}else{  
					$('#email').css('border', '1px solid #FF0000');  
					$('.taken').empty();
					$('.taken').append('<span style="color:#000;">Email format wrong!</span>');
					return false;  
				} 

			}else{
				$('#username').css('border', '1px solid #FF0000');
				$('.usernametaken').empty();
				$('.usernametaken').append('<span style="color:#FF0000;">Taken</span>');
        		return false;
			} 
			
	    }else{
	        $('#retype-password').css('border', '1px solid #FF0000');
	        return false;        
	    }
	    
	}
		
</script>


<div id="project_add_edit_page" class="content-inner"> 

	<div class="row">
		<div class="title col-xs-8 col-sm-8 col-md-8">
			<div class="title-inner">
				<img src="<?php echo base_url(); ?>images/add_user_1.png" width="40" />
				<p><strong>Manage your Users</strong><br>Create and manage your Users, and allow them access to particular systems.</p>
			</div>
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
		</div>
	</div>

<div class="main-page"> 
<?php 
	if(isset($user_info->uid)){
		$form_attributes = array('id' => 'user-add-form','method'=>'post');
 	}else{
		$form_attributes = array('id' => 'user-add-form','method'=>'post','onsubmit'=>'return CheckpasswordValidateEmail()');
	}
    $userid= isset($user_info->uid) ? $user_info->uid : 0;

	$uid = form_hidden('uid', isset($user_info->uid) ? $user_info->uid : '');
	
	
        if($userid>0){$form_label = 'Update User';}else{ $form_label='Add User';}
        
        
        $fullname = form_input(array(
        		'name'        => 'fullname',
        		'id'          => 'fullname',
        		'value'       => isset($user_info->fullname) ? $user_info->fullname : '',
        		'class'       => 'form-control',
        		'placeholder'=>'Full Name',
        		'required'    => TRUE
        ));
	
	
	$username = form_input(array(
	              'name'        => 'username',
	              'id'          => 'username',
	              'value'       => isset($user_info->username) ? $user_info->username : '',
	              'class'       => 'form-control',
                      'placeholder'=>'Name',
				  'onblur' => isset($user_info->username) ? '' : 'ValidateUsername();',
                      'required'    => TRUE
	));
	
	
	$password = form_password(array(
	              'name'        => 'pass',
	              'id'          => 'password',
	              'value'       => '',
	              'class'       => 'form-control',
		      'placeholder'=>'Password',
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
                  'placeholder'=>'Email',
				  'onblur' => isset($user_info->email) ? '' : 'ValidateEmail();',
		          'required'    => TRUE

	));
	
	$ci = & get_instance();
	$ci->load->model('user_model');
	
	
	$state_options = array(
	      '1' => 'Active',
		  '0' => 'Block',
	);
	$state_default = isset($user_info->statue) ? $user_info->statue : '';
    $state_js= 'class="form-control selectpicker"';
	
        
	$state = form_dropdown('status', $state_options, $state_default, $state_js);

	
    $submit = form_submit(array(
	              'name'        => 'submit',
	              'id'          => 'submit',
	              'value'       => isset($user_info->uid) ? 'Save' : 'Create',
	              'class'       => 'form-control btn btn-default',
	              'type'        => 'submit',
	));
    $user=  $this->session->userdata('user');
        
    $logged_user_id= $user->uid;
    $user_role_id =$user->role; 
    if (isset($message)) {echo $message;}
	echo validation_errors();
	echo form_open($action, $form_attributes);
	//echo form_fieldset($form_label, array('class'=>"user-add-fieldset"));
	echo '<div id="uid-wrapper" class="field-wrapper">'. $uid . '</div>';
	
    ?>
    
    <div class="row">
		<div class="col-xs-12 col-sm-5 col-md-5">
			<label class="col-xs-12 control-label" style="padding-left:0px;">User Information</label>
	    	<div class="form-group">
	    		<label for="username" class="control-label">Name</label>
	      	    <?php echo $username; ?><div class="usernametaken"></div><input type="hidden" id="useraccess" value="" />
	  		</div>    	 
	    	<div class="form-group">
	    		<label for="email" class="control-label">Email</label>
	      			<?php echo $email; ?><div class="taken"></div>
	  		</div>    	 
	    	<div class="form-group">
	    		 <?php  if($userid == 0 || $userid == $logged_user_id){ ?>
	    		<label for="password" class="control-label">Password</label>
	      			<?php echo $password; ?>
	        	<?php } ?>
	  		</div> 
			<div class="form-group">
	    		 <?php  if($userid == 0 || $userid == $logged_user_id){ ?>
	    		<label for="confirm_password" class="control-label">Confirm Password</label>
	      			<?php echo $con_password; ?>
	        	<?php } ?>
	  		</div>   	 
    	</div>
    
		<?php if($userid != $logged_user_id): ?>
    	<div class="col-xs-12 col-sm-7 col-md-7">
    		<label class="col-xs-6 col-sm-6 col-md-6 control-label" style="padding-left:0px;">Systems Required</label>
			<label class="col-xs-6 col-sm-6 col-md-6 control-label" style="padding-left:15px;">Permissions</label>
    		<div class="row">

	        <div class="form-group">       
	        
	        
	        <div class="col-xs-12 col-sm-12 col-md-12">	
	            <?php  
	             
	           	$i=1; 
	            $user= $this->session->userdata('user');               
	            $system_checkbox = $ci->user_model->get_company_applications();
				
				
	            $admin_users= $ci->user_model->user_admin_application_list($logged_user_id);           
	            $app_id = array();
	            foreach($admin_users as $admin){
	                $app_id[] = $admin->id;
	            }
	            
	            if(!empty($system_checkbox)){
	            foreach ($system_checkbox as $checkbox) { 
	
	
		            if($user->role==1){
						$display_row= "block";
					} else{
			            if(in_array($checkbox->id, $app_id))
			            {
			                $display_row= "block"; 
			            }else{
			                $display_row= "none"; 
			            }
				    }

if($checkbox->id==1){
	$hover = '<strong>'.$checkbox->application_name.':</strong><br>Manage your Developments from beginning to end.';
}else if($checkbox->id==2){
	$hover = '<strong>'.$checkbox->application_name.':</strong><br>Manage and create a Maintenance Schedule for a clients new home';
}else if($checkbox->id==3){
	$hover = '<strong>'.$checkbox->application_name.':</strong><br>Internally manage Tasks in your business.';
}else if($checkbox->id==4){
	$hover = '<strong>'.$checkbox->application_name.':</strong><br>Manage your businesses Contacts.';
}else if($checkbox->id==5){
	$hover = '<strong>'.$checkbox->application_name.':</strong><br>Manage your Construction from start to finish.';
}else if($checkbox->id==6){
	$hover = '<strong>'.$checkbox->application_name.':</strong><br>Manage the Consents of a new build.';
}
	            ?>
	            <div class="row" style="margin-left:-5px;display: <?php echo $display_row; ?>">
	                
	                <div class="col-xs-6 col-sm-6 col-md-6"> 
	                        <div class="checkbox">
	                            <input type="hidden" name="app<?php echo $i;?>" value="0">
	                         <?php 
	                         if(isset($user_info->uid)){
	                                $checked_status = $ci->user_model->get_application_checked($user_info->uid, $checkbox->id);
	                                if (!empty($checked_status)) {
	                                   //echo $checked->application_id;
	                                    $checked='checked';
	                                }else{
	                                    $checked='';
	                                }
	                             
	                         }else{ $checked='';}
	                         echo '<input class="appcheckbox" name="app'.$i.'" type="checkbox" '.$checked.' value="'.$checkbox->id.'"/> &nbsp; '.$checkbox->application_name;
	                         ?>
	                        </div>
							<div class="system-hover">
								<img src="<?php echo base_url(); ?>images/system_hover.png" width="25" />
								<div class="hover"><?php echo $hover; ?></div>
							</div>
	                </div>
	                
	                <div class="col-xs-6 col-sm-6 col-md-6"> 
						<div class="form-group showbox<?php echo $i;?>" style="display:block;">
							<?php 
							$user_option = $ci->user_model->user_role_load($checkbox->id);    
							$user_options = array('0' => '---Select Permission---') + $user_option;
							if(isset($user_info->uid)){
								$perm_status = $ci->user_model->get_application_role($user_info->uid, $checkbox->id);
								if (!empty($perm_status)){ 
									$perm_role= $perm_status->application_role_id;
								}else{
									$perm_role=0;
									
								}                         
							}
							
						   
							$user_default = isset($perm_role) ? $perm_role : 0;
							$role_js= 'id="approleid'.$checkbox->id.'" class="form-control selectpicker"';	
							echo form_dropdown('approle'.$i, $user_options,$user_default, $role_js);
							$i++;
							?>
						</div>
						<?php if($checkbox->id==1):?>
							<div class="row form-group dev_user_permission" style="<?php if($hds_dev_permission->application_role_id==3){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">   
							  <div class="col-xs-12 col-sm-12 col-md-12">
									 <label for="user_permission" class="control-label">User Development Permission</label>    
									<select name="hds_dev_permission[]"  multiple class="form-control selectpicker" data-selected-text-format="count">
										<option value="0" data-hidden="true">---Select Development---</option>
										<?php
				
									$user_permission_options = $ci->user_model->developments_load();

									$user_permission_arr = explode(",", $hds_dev_permission->hds_dev_permission);
				
									foreach ($user_permission_options->result() as $row)
									{	    	
										$user_permission_default = '';
										for($a = 0; $a < count($user_permission_arr); $a++)
										{
												if($user_permission_arr[$a] == $row->id)
												{
														$user_permission_default = 'selected="selected"';
														break;
												}
										}
										echo '<option value="'.$row->id.'" '.$user_permission_default.'>'.$row->development_name.'</option>';
									}  
				
									?>
									</select>
							 </div>
						</div>
						<?php endif ?>
						<?php if($checkbox->id==6):?>
							<div class="row form-group cms_group_id" style="<?php if($cms_group_id->application_role_id==2 || $cms_group_id->application_role_id==3 ){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>"> 
									<div class="col-xs-12 col-sm-12 col-md-12">
											 <label for="user_permission" class="control-label">User Group Permission</label>    
											<select name="cms_group_id[]" multiple class="form-control selectpicker" data-selected-text-format="count">
												<option value="">---Select Group---</option>
												<?php
											$user_group_permission_cms = explode(",", $cms_group_id->cms_group_id);

											$cms_group_options = $ci->user_model->cms_group();
					
											foreach ($cms_group_options->result() as $row)
											{	    	
												$cms_group_default = '';
												if(in_array($row->id,$user_group_permission_cms))
												{
													   $cms_group_default = 'selected="selected"';
												}
												echo '<option value="'.$row->id.'" '.$cms_group_default.'>'.$row->group_name.'</option>';
											}  
					
											?>
											</select>
									</div>
							</div>
						<?php endif ?>
	                </div>
	                </div>
	                    <?php }} else {echo 'You have no access';} ?>
	            </div>
	        </div>
	  		
	   </div>


<?php 
$user_id = $this->uri->segment(3); 
$hds_dev_permission = $ci->user_model->hds_dev_permission($user_id)->row(); 

$cms_group_id = $ci->user_model->cms_group_id($user_id)->row(); 
                               
?>
            
	
	
	
    
    
	</div>

</div>   

	
	<?php endif; ?>
    
	<div class="row">
    	<div class="form-group">
    		<div class="col-xs-3 col-sm-2 col-md-2">   
				<?php  if($userid != $logged_user_id){ ?><a role="button" data-toggle="modal" href="#DeleteUser" class="form-control btn btn-default" id="userdelete">Delete User</a>  <?php } ?> 			
    		</div>
    		<div class="col-xs-3 col-sm-6 col-md-6">      			
      			
    		</div>
    		<div class="col-xs-3 col-sm-2 col-md-2">
      			 <input type="button" id="button" class="form-control btn btn-default" value="Cancel" />
    		</div>
    		<div class="col-xs-3 col-sm-2 col-md-2">
            	<?php echo $submit; ?>
        	</div>
  		</div>    	 
    </div>
    
    
    <?php
    
        
	
        
	echo form_fieldset_close(); 
	echo form_close();
?>


</div>

</div>



<div class="modal small fade DeleteUser" id="DeleteUser" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <p>
                    <strong>Delete <?php echo $user_info->username; ?>?</strong> 
				</p>
            </div>
            <div class="modal-body">
                <p class="error-text">
                    <strong> Are you sure you want to delete <?php echo $user_info->username; ?>?</strong><br><span style="font-style: italic;">(Note: This is permanent and cannot be undone)</span>
				</p>
            </div>
            <div class="modal-footer">
               <button class="btn btn-default"data-dismiss="modal" aria-hidden="true">Cancel</button> 
               <a href="<?php echo base_url(); ?>user/user_delete/<?php echo $user_info->uid; ?>" class="btn btn-danger"  id="modalDelete" >Delete</a>
            </div>
        </div>
    </div>
</div>


<style>
    .checkbox{
        height:50px;
    }
</style>
<script>
$(document).ready(function() {    
    
    window.Url = "<?php print base_url(); ?>";
  /*  
    $('.appcheckbox').change(function(){
        app_id = $(this).val();
        boxclass = 'showbox'+app_id;
        console.log(boxclass);
        if(this.checked){
            $('.'+boxclass).show();
        }else{
            $('.'+boxclass).hide();
        }
        
        
    });
    */    
	
	$("#approleid1").change(function() {
		
		var selectedRoleId = this.value;
		if(selectedRoleId == 3)
		{
			$(".dev_user_permission").css("display", "block");
		}
		else
		{
			$(".dev_user_permission").css("display", "none");
		}

	});	

	$("#approleid6").change(function() {
		
		var selectedRoleId = this.value;
		if(selectedRoleId == 2 || selectedRoleId == 3 )
		{
			$(".cms_group_id").css("display", "block");
		}
		else
		{
			$(".cms_group_id").css("display", "none");
		}

	});


	if ($('#user_permission').length)
	{
		$("#user_permission").multiselect({
        	selectedText: "# of # selected"
    	});
	}
	
});

</script>