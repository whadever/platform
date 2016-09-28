
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="content-header">
			<div class="title"><?php echo $title; ?></div>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="table-responsive">
			<table class="table">
				<tbody>
					<tr><td style="border-top:none; text-align:right;">Email</td><td style="border-top:none"><div style="background-color:#e1e1e1;min-height:33px; padding:5px; font-size:13px"> <span style="float:left;">Your current email is: <?php echo $user_info->email; ?></span> <span style="float:right"><a href='#Edit_email' data-toggle ='modal'>Edit</a></span></div></td></tr>
					<tr><td style="border-top:none; text-align:right;">Password</td><td style="border-top:none"><div style="background-color:#e1e1e1;min-height:33px; padding:5px; font-size:13px"><span style="float:right"><a href="#Edit_password" data-toggle ="modal">Edit</a></div></td></tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<div id="user_list_view" class="table-border">


	<!-- MODAL edit user email -->
	<div id="Edit_email" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h3 id="myModalLabel">Edit User Email Address</h3>
		</div>

		<div class="modal-body">
	   <?php
	   
		$action = 'user/update_email/'.$user_info->uid;
	   
		$form_attributes = array('class' => 'user-email-edit-form', 'id' => 'edit-email-form','method'=>'post', 'onsubmit' => 'return checkform()' );
	
	
		$uid = form_hidden('uid', isset($user_info->uid) ? $user_info->uid: '');
	
		$email = form_label('User Email', 'email');
		$email .= form_input(array(
		              'name'        => 'email',
		              'id'          => 'nuser-email',
		              'value'       => isset($user_info->email) ? $user_info->email : '',
		              'class'       => 'form-text',
					  'required'    => TRUE
	
		));
		
		
		$submit = form_label('', 'submit');
		$submit .= form_submit(array(
		              'name'        => 'submit',
		              'id'          => 'save_user',
		              'value'       => 'Update',
		              'class'       => 'form-submit mss_save',
		              'type'        => 'submit',
					  
		));
	
		echo validation_errors();
		echo form_open($action, $form_attributes);
		echo '<div id="uid-wrapper" class="field-wrapper">'. $uid . '</div>';
		echo '<div id="email-wrapper" class="field-wrapper">'. $email . '<div id="nemail_alert"></div></div>';
		
		echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
		echo form_fieldset_close(); 
		echo form_close();


		?>
		</div>
	</div>


	<!-- MODAL edit user password -->
	<div id="Edit_password" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h3 id="myModalLabel">Edit User Password</h3>
		</div>

		<div class="modal-body">
	   <?php
	   
		$action = 'user/update_password/'.$user_info->uid;
	   
		$form_attributes = array('class' => 'user-password-edit-form', 'id' => 'edit-password-form','method'=>'post', 'onsubmit' => 'return checkform()' );
	
	
		$uid = form_hidden('uid', isset($user_info->uid) ? $user_info->uid: '');
	

		$old_pass = form_label('Old Password', 'old_pass');
		$old_pass .= form_password(array(
					  'name'        => 'old_pass',
					  'id'          => 'old_pass_'.$user_info->uid,
					  'value'       => '',
					  'class'       => 'form-text',
				  	  'autocomplete'	=> 'off'

		));


		$pass = form_label('New Password', 'pass');
		$pass .= form_password(array(
					  'name'        => 'pass',
					  'id'          => 'password_'.$user_info->uid,
					  'value'       => '',
					  'class'       => 'form-text',
				  	  'autocomplete'	=> 'off'

		));


		$retype_pass = form_label('Retype Password', 'repass');
		$retype_pass .= form_password(array(
					  'name'        => 'repass',
					  'id'          => 'retype_password_'.$user_info->uid,
					  'class'       => 'form-text',
				  	  'autocomplete'	=> 'off'

		));
		
		
		$submit = form_label('', 'submit');
		$submit .= form_submit(array(
		              'name'        => 'submit',
		              'id'          => 'save_user',
		              'value'       => 'Update',
		              'class'       => 'form-submit mss_save',
		              'type'        => 'submit',
					  
		));
	
		echo validation_errors();
		echo form_open($action, $form_attributes);
		echo '<div id="uid-wrapper" class="field-wrapper">'. $uid . '</div>';
		//echo '<div id="email-wrapper" class="field-wrapper">'. $old_pass . '<div id="nemail_alert"></div></div>';
		echo '<div id="email-wrapper" class="field-wrapper">'. $pass . '<div id="nemail_alert"></div></div>';
		//echo '<div id="email-wrapper" class="field-wrapper">'. $retype_pass . '<div id="nemail_alert"></div></div>';
		
		echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
		echo form_fieldset_close(); 
		echo form_close();


		?>
		</div>
	</div>


</div>