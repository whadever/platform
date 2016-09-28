<!--<div class="button-wrapper">
    <div class="button">
            <?php echo anchor('user/user_list','User List',array('class'=>'list')); ?>
    </div>        
</div>
-->

<!-- start: multiselect -->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.multiselect.js"></script>
<link href="<?php echo base_url();?>css/jquery.multiselect.css" rel="stylesheet" type="text/css"/>
<!-- end: multiselect -->

<style>
legend {
    border-bottom: 1px solid #fffccd;
    color: #000;
    font-size: 18px;
    font-weight: bold;
    line-height: 18px;
    margin-bottom: 15px;
    padding-bottom: 7px;
    padding-left: 10px;
}
.ui-multiselect {
    background: none repeat scroll 0 0 #fff;
    width: 285px !important;
}
.save-change > input#change {
    background: #fff;
    border: 1px solid #002855;
}
a.update-password {
    border: 1px solid #002855;
    color: #333;
    padding: 4px 10px;
}
a.update-password:hover {
    text-decoration:none;
}
.modal .controls input[type="password"] {
    border: 1px solid #000;
    border-radius: 10px;
    line-height: 12px;
    padding: 3px 8px;
    width: 56%;
}
</style>
<div class="clear"></div>

<?php
	$form_attributes = array('class' => 'user-add-form', 'id' => 'entry-form','method'=>'post');

	$uid = form_hidden('uid', isset($user->uid) ? $user->uid : '');
	
	// print_r($user);
	
	
	$name = form_label('User Name', 'name');
	$name .= form_input(array(
	              'name'        => 'name',
	              'id'          => 'edit-name',
	              'value'       => isset($user->name) ? $user->name : '',
	              'class'       => 'form-text',
                  'required'    => TRUE
	));
	
	$pass = form_label('User Password', 'pass');
	$pass .= form_password(array(
	              'name'        => 'pass',
	              'id'          => 'edit-pass',
	              'value'       => '',
	              'class'       => 'form-text',
		      'autocomplete'	=> 'off',
				  'required'    => TRUE

	));
	
	$email = form_label('User Email', 'email');
	$email .= form_input(array(
	              'name'        => 'email',
	              'id'          => 'edit-email',
	              'value'       => isset($user->email) ? $user->email : '',
	              'class'       => 'form-text',
				  'required'    => TRUE

	));
	
	$ci = & get_instance();
	$ci->load->model('user_model');
	$user_options = $ci->user_model->user_role_load();

	$js = 'id="rid"';
    $user_default = isset($user->rid) ? $user->rid : 0;
	$access = form_label('User Role', 'rid');
	$access .= form_dropdown('rid', $user_options,$user_default,$js);

	$ci = & get_instance();
	$ci->load->model('user_model');
	$user_permission_options = $ci->user_model->developments_load();
	$user_permission_arr = explode(",", $user->user_permission);
	
	$state_options = array(
	      '1' => 'Active',
		  '0' => 'Block',
	);
	$state_default = isset($user->statue) ? $user->statue : '';
	$state = form_label('User Status', 'status');
	$state .= form_dropdown('status', $state_options, $state_default);

	$submit = form_label('', 'submit');
        $submit .= form_submit(array(
	              'name'        => 'submit',
	              'id'          => 'edit-submit',
	              'value'       => isset($user->uid) ? 'Update' : 'Submit',
	              'class'       => 'form-submit',
	              'type'        => 'submit',
	));

	if($user->rid == 3)
	{
		$user_per_show = 'style="display:block;"';
	}
	else
	{
		$user_per_show = 'style="display:none;"';
	}

	echo validation_errors();
	echo form_open($action, $form_attributes);
	echo form_fieldset(isset($user->uid) ? 'Update User' : 'User Add',array('class'=>"user-add-fieldset"));
	echo '<div id="uid-wrapper" class="field-wrapper">'. $uid . '</div>';
	echo '<div id="name-wrapper" class="field-wrapper">'. $name . '</div>';
	if(isset($user->uid))
	{
		echo '<div id="pass-wrapper" class="field-wrapper">';
		echo '<label for="pass">User Password</label>';
		echo '<a href="#UpdatePassword" title="Update Password" role="button" data-toggle="modal" class="update-password">Update Password</a>';
		echo '</div>';
	}
	else
	{
		echo '<div id="pass-wrapper" class="field-wrapper">'. $pass . '</div>';
	}
	echo '<div id="email-wrapper" class="field-wrapper">'. $email . '</div>';
    $user1 =  $this->session->userdata('user');
    $user_role_id =$user1->rid; 
    if($user_role_id==1)
	{
	echo '<div id="access-wrapper" class="field-wrapper">'. $access . '</div>';
	echo '<div id="access-wrapper" class="field-wrapper">'. $state . '</div>';
	echo '<div id="access-wrapper" class="field-wrapper dev_user_permission" '.$user_per_show.'>';	
		echo '<label for="user_permission">User Permission</label>';
		echo '<select name="user_permission[]" id="user_permission" multiple="multiple">';
		
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
	    echo '</select>';   
	echo '</div>';
    }
	echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
	echo form_fieldset_close(); 
	echo form_close();
?>
<div id="UpdatePassword" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form onsubmit="return checkOldAndNewpassword()" class="form-horizontal" action="<?php echo base_url(); ?>user/all_update_password/<?php echo $user->uid; ?>" method="POST">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h3 id="myModalLabel">Update Password</h3>
		</div>
		<div class="modal-body">
				
			<div class="control-group">
				<label class="control-label" for="old_password">Enter Old Passowrd</label>
				<div class="controls">
					<input onkeyup="checkOldpassword();" type="password" id="old_password" name="old_password" value="" required="">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="new_password">Enter New Password</label>
				<div class="controls">
					<input type="password" id="new_password" name="new_password" value="" required="">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="retype_new_password">Retype New Password</label>
				<div class="controls">
					<input onkeyup="checkpassword();" type="password" id="retype_new_password" name="retype_new_password" value="" required="">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="submit"></label>
				<div class="controls">
					<input type="hidden" id="new_pass_access" name="new_pass_access" value="">
					<input type="hidden" id="uid" name="uid" value="<?php echo $user->uid; ?>">
					<div class="save-change">
						<input id="change" type="submit" value="Change" name="submit" />
					</div>
				</div>
			</div>
	    
		</div>

	</form>
</div>

<script>
	window.Url = "<?php print base_url(); ?>";
jQuery(document).ready(function() {
	
	$("#rid").change(function() {
		
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

	if ($('#user_permission').length)
	{
		$("#user_permission").multiselect({
        	selectedText: "# of # selected"
    	});
	}
	
});

</script>
<script>
	function checkpassword()
	{
	    var password = $('.in #new_password').val();
	    var retype_password = $('.in #retype_new_password').val();
		console.log(retype_password);
	    if(password == retype_password){
	        $('.in #retype_new_password').css('border', '1px solid #000');
	        return true;
	    }else{
	        $('.in #retype_new_password').css('border', '1px solid #FF0000');
	        return false;
	    }
	}
	
	function checkOldpassword()
	{
	    var enter_old_password = $('.in #old_password').val();
	    var uid = $('.in #uid').val();
	    
		$.ajax({				
			url: window.Url + 'user/user_check_password/' + uid + '/' + enter_old_password,
			type: 'POST',
			success: function(data) 
			{
			//console.log(data);
			if(data == 1){
					$('.in #old_password').css('border', '1px solid #000');
					$('.in #new_pass_access').val(data);
	        		return true;
				}else{
			        //alert("Passwords Do Not Match!");
			        $('.in #old_password').css('border', '1px solid #FF0000');
			        $('.in #new_pass_access').val(data);
			        return false;
			    }
			},
		        
		});
		
	}
	
	function checkOldAndNewpassword()
	{
	    var new_pass_access = $('.in #new_pass_access').val();
	    var password = $('.in #new_password').val();
	    var retype_password = $('.in #retype_new_password').val();
		
		if(new_pass_access == 1 && password == retype_password){
    		return true;
		}else{
			if(new_pass_access == 0){
				$('.in #old_password').css('border', '1px solid #FF0000');
			}else if(password != retype_password){
				$('.in #retype_new_password').css('border', '1px solid #FF0000');
			}else{
				$('.in #old_password').css('border', '1px solid #FF0000');
	        	$('.in #retype_new_password').css('border', '1px solid #FF0000');
			}
	        return false;
	    }			
	}
		
</script>

