<!--<div class="button-wrapper">
    <div class="button">
            <?php echo anchor('user/user_list','User List',array('class'=>'list')); ?>
    </div>        
</div>
-->
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

    $user_default = isset($user->rid) ? $user->rid : 0;
	$access = form_label('User Role', 'rid');
	$access .= form_dropdown('rid', $user_options,$user_default);
	
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

	
	
	echo validation_errors();
	echo form_open($action, $form_attributes);
	echo form_fieldset(isset($user->uid) ? 'Update User' : 'User Add',array('class'=>"user-add-fieldset"));
	echo '<div id="uid-wrapper" class="field-wrapper">'. $uid . '</div>';
	echo '<div id="name-wrapper" class="field-wrapper">'. $name . '</div>';
	echo '<div id="pass-wrapper" class="field-wrapper">'. $pass . '</div>';
	echo '<div id="email-wrapper" class="field-wrapper">'. $email . '</div>';
                $user=  $this->session->userdata('user');
                $user_role_id =$user->rid; 
                if($user_role_id==1){
	echo '<div id="access-wrapper" class="field-wrapper">'. $access . '</div>';
	echo '<div id="access-wrapper" class="field-wrapper">'. $state . '</div>';
                }
	echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
	echo form_fieldset_close(); 
	echo form_close();
?>



