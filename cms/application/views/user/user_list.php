<script>
window.Url = "<?php print base_url(); ?>";
function selectrow(userid,trclass)
{
	if(trclass == '')
	{
		document.getElementById('delete_user').href= window.Url + 'user/user_delete/'+userid;
		$("tr").removeClass("checked");
		document.getElementById('check_'+userid).className = 'checked';
	}
	else
	{
		document.getElementById('delete_user').href= window.Url + 'user/user_list#';
		$("tr").removeClass("checked");
	}
}

function check_edit_form(user_id)
{
	
	var password = $('#password' + user_id).val();
	var retype_password = $('#retype_password' + user_id).val();


	var username = $('#' + user_id).val();

	if(username.length < 2)
	{
		$('#' + user_id).focus();
		return false;
	}

	if($('#username_alert' + user_id).hasClass('usernameFail') == true)
	{
		$('#username_alert' + user_id).focus();
		return false;
	}



	/* if(password == retype_password)
	{
         //alert("Passwords Match!");
         $('.in #retype_password').css('border', '1px solid #01416f');
         return true;
    }
	else
	{
         //alert("Passwords Do Not Match!");
         $('.in #retype_password').css('border', '1px solid #FF0000');
         //$('.in #retype_password').focus('Password does not match');
         return false;
     } */


}

function check_username(user_name,user_id)
{
	
		var username = user_name;
		if (username.length >= 2)
		{
			$('#username_alert' + user_id).html('');
			

			$.ajax({
					type: 'POST',
					cache: 'false',
					url: window.Url + 'user/search_edit_user/' + username + '/' + user_id ,
					beforeSend: function() {
						$('#username_alert' + user_id).attr('class', 'usernameLoading');
					},
					success: function(result) 
					{
						if(result){
							$('#username_alert' + user_id).attr('class', 'usernameFail');
						}
						else{
							$('#username_alert' + user_id).attr('class', 'usernamePassed');
						}
					}

				});

		}
		else
		{
			$('#username_alert' + user_id).html('Must be at least 4 characters');

			$('#username_alert' + user_id).removeClass("usernameLoading");
			$('#username_alert' + user_id).removeClass("usernamePassed");
			$('#username_alert' + user_id).removeClass("usernameFail");
		}

}

function checkform()
{
	var password = $('.in #password').val();
	var retype_password = $('.in #retype_password').val();

	var username = $('#username').val();

	if(username.length < 2)
	{
		$('#nusername').focus();
		return false;
	}

	if($('#nusername_alert').hasClass('usernameFail') == true)
	{
		$('#nusername').focus();
		return false;
	}

	if($('#nemail_alert').hasClass('emailFail') == true)
	{
		$('#nuser-email').focus();
		return false;
	}

	/* if(password == retype_password)
	{
         //alert("Passwords Match!");
         $('.in #retype_password').css('border', '1px solid #01416f');
         return true;
    }
	else
	{
         //alert("Passwords Do Not Match!");
         $('.in #retype_password').css('border', '1px solid #FF0000');
         //$('.in #retype_password').focus('Password does not match');
         return false;
     } */


 }



jQuery(document).ready(function()
{

	$('.notify').show().fadeOut(4000);

	$('#nusername').on('keyup', function()
	{

		var username = $('#nusername').val();
		if (username.length >= 2)
		{
			$('#nusername_alert').html('');
			

			$.ajax({
					type: 'POST',
					cache: 'false',
					url: window.Url + 'user/search_user/' + username ,
					beforeSend: function() {
						$('#nusername_alert').attr('class', 'usernameLoading');
					},
					success: function(result) 
					{
						if(result){
							$('#nusername_alert').attr('class', 'usernameFail');
						}
						else{
							$('#nusername_alert').attr('class', 'usernamePassed');
						}
					}

				});

		}
		else
		{
			$('#nusername_alert').html('Must be at least 4 characters');

			$('#nusername_alert').removeClass("usernameLoading");
			$('#nusername_alert').removeClass("usernamePassed");
			$('#nusername_alert').removeClass("usernameFail");
		}

	});



	// email validation

	$("#nuser-email").blur(function()
	{
    
		var useremail = $('#nuser-email').val();
		
		var regex = /^([a-zA-Z0-9_.+-])+\@(([a-zA-Z0-9-])+\.)+([a-zA-Z0-9]{2,4})+$/;

  		res = regex.test(useremail);


		if(res == false)
		{
			$('#nemail_alert').html('Email is not valid');
		}
		else
		{

			$('#nemail_alert').html('');
			
			$.ajax({
					type: 'POST',
					cache: 'false',
					url: window.Url + 'user/search_email/' + encodeURIComponent(useremail),
					beforeSend: function() {
						$('#nemail_alert').attr('class', 'emailLoading');
					},
					success: function(result) 
					{
						if(result){
							$('#nemail_alert').attr('class', 'emailFail');
						}
						else{
							$('#nemail_alert').attr('class', 'emailPassed');
						}
					}

				});

		}

		
	
  	});



});


function checkpassword()
{
	var password = $('.in #password').val();
	var retype_password = $('.in #retype_password').val();

	if(password == retype_password)
	{
         //alert("Passwords Match!");
         $('.in #retype_password').css('border', '1px solid #01416f');
         return true;
    }
	else
	{
         //alert("Passwords Do Not Match!");
         $('.in #retype_password').css('border', '1px solid #FF0000');
         //$('.in #retype_password').focus('Password does not match');
         return false;
     }

}

function enable_password(id)
{
	var passid = 'password_' + id;
	document.getElementById(passid).disabled = false;

	var repassid = 're-pass-wrapper_' + id;
	document.getElementById(repassid).style.display = 'block';

}

 
</script>
<div class="cms-button-wrapper">
	
	<div class="clr">
		<div class="cms_tabs">
			<table style="width:60%; margin:0px auto">
				<tr>
					<td style="border:2px solid #004278; background-color:#ebebeb" align="center"><a class="cmstabs" href="<?php echo base_url();?>user/user_list">Users</a></td>
					<td style="border:2px solid #004278" align="center"><a class="cmstabs" href="<?php echo base_url();?>user/user_role_list">Permissions</a></td>
					<td style="border:2px solid #004278" align="center"><a class="cmstabs" href="<?php echo base_url();?>user/user_category_list">User Categories</a></td>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="clr">
	
		<!-- <div class="fltl notify"><?php // if (isset($message)) {echo $message;}  ?></div> -->
		
		<div class="cms_icons">
			<a class="icon_text" data-toggle="modal" href="#AddNewUser" href="<?php echo base_url();?>user/user_add"><img src="<?php echo base_url();?>images/icon/add_user.png" height="" title="Add User" alt="Add User" /><br> 
			<span style="color:#000">Add User</span></a>
		</div>
		
		<div class="cms_icons">
			<a class="icon_text" id="delete_user"><img src="<?php echo base_url();?>images/icon/delete_user.png" height="" title="Delete User" alt="Delete User" /><br> 
			<span style="color:#000">Delete User</span></a>
		</div>
	</div>
      
       
</div>

<div class="clear"></div>


<div class="clear"></div>

<?php if (isset($user_table)){ echo $user_table; } ?>

<div class="clear"></div>
<p>&nbsp;</p>


<!-- MODAL Add User -->
<div id="AddNewUser" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
		<h3 id="myModalLabel">Add New User</h3>
	</div>
	<div class="modal-body">



		
		
   <?php
   
	$action = 'user/user_add';
   
	$form_attributes = array('class' => 'user-add-form', 'id' => 'entry-form','method'=>'post', 'onsubmit' => 'return checkform()' );

	$uid = form_hidden('uid', isset($user->uid) ? $user->uid : '');
	
	// print_r($user);
	
	
	$fullname = form_label('Full Name', 'fullname');
	$fullname .= form_input(array(
	              'name'        => 'fullname',
	              'id'          => 'fullname',
	              'value'       => isset($user->fullname) ? $user->fullname : '',
	              'class'       => 'form-text',
                  'required'    => TRUE
	));

	$name = form_label('User Login', 'name');
	$name .= form_input(array(
	              'name'        => 'name',
	              'id'          => 'nusername',
	              'value'       => isset($user->name) ? $user->name : '',
	              'class'       => 'form-text',
                  'required'    => TRUE
	));
	
	$email = form_label('User Email', 'email');
	$email .= form_input(array(
	              'name'        => 'email',
	              'id'          => 'nuser-email',
	              'value'       => isset($user->email) ? $user->email : '',
	              'class'       => 'form-text',
				  'required'    => TRUE

	));
	
	$ci = & get_instance();
	$ci->load->model('user_model');
	$user_options = $ci->user_model->user_group_load();

    $user_default = isset($user->id) ? $user->id : 0;
	$permission_group = form_label('Permissions Group', 'id');
	$permission_group .= form_dropdown('group_id', $user_options,$user_default);
	
	
	$pass = form_label('User Password', 'pass');
	$pass .= form_password(array(
	              'name'        => 'pass',
	              'id'          => 'password',
	              'value'       => '',
	              'class'       => 'form-text',
		      'autocomplete'	=> 'off',
				  'required'    => TRUE

	));
	
	$retype_pass = form_label('Retype Password', 'repass');
	$retype_pass .= form_password(array(
	              'name'        => 'repass',
	              'id'          => 'retype_password',
	              'value'       => '',
	              'class'       => 'form-text',
		      'autocomplete'	=> 'off',
				  'required'    => TRUE,
				  'onkeyup'		=> 'checkpassword()'

	));
	
	
	
	$submit = form_label('', 'submit');
        $submit .= form_submit(array(
	              'name'        => 'submit',
	              'id'          => 'save_user',
	              'value'       => 'submit',
	              'class'       => 'form-submit cms_save',
	              'type'        => 'submit',
				  
	));

	echo validation_errors();
	echo form_open($action, $form_attributes);
	echo '<div id="uid-wrapper" class="field-wrapper">'. $uid . '</div>';
	echo '<div id="name-wrapper" class="field-wrapper">'. $fullname . '</div>';
	echo '<div id="name-wrapper" class="field-wrapper">'. $name . '<div id="nusername_alert"></div></div>';
	echo '<div id="email-wrapper" class="field-wrapper">'. $email . '<div id="nemail_alert"></div></div>';
	echo '<div id="access-wrapper" class="field-wrapper">'. $permission_group . '</div>';
	echo '<div id="pass-wrapper" class="field-wrapper">'. $pass . '</div>';
	//echo '<div id="pass-wrapper" class="field-wrapper">'. $retype_pass . '</div>';
	
	

	echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
	echo form_fieldset_close(); 
	echo form_close();
?>
		
	
	</div>


</div>
<!-- MODAL Add user end-->


<script>
$(document).ready(function(){

  $("#delete_user").click(function(){
  
	username = $("tr.checked .uname").html();
	
    if($("tr").hasClass("checked"))
	{
		return confirm('Are you sure you want to delete '+ username +' from the list?')
	}
	else
	{
		alert('Please select user!')
	}
  });

});
</script>