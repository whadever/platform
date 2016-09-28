<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Williams Platform - <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link href="<?php echo base_url(); ?>css/login_styles.css" rel="stylesheet" type="text/css" >
</head>
<body>
<?php
	$form_attributes = array('class' => 'form-signin', 'id' => 'login-form','method'=>'post');
	$username = form_input(array(
	              'name'        => 'username',
	              'id'          => 'edit-username',
	              'value'       => isset($user->username) ? $user->username : '',
	              'class'       => 'form-input',
                  'placeholder'       => 'User Name',
                  'required'    => TRUE
	));
	
	$email = form_input(array(
	              'name'        => 'email',
	              'id'          => 'edit-email',
	              'value'       => isset($user->email) ? $user->email : '',
	              'class'       => 'form-input',
                  'placeholder'       => 'Email',
                  'required'    => TRUE

	));
	
	$exception=$this->session->userdata('exception');
    if(isset($exception)) {
       $exception1 = $exception;
		$this->session->unset_userdata('exception');
    }

	$submit = form_label('', 'submit');
	$submit .= form_submit(array(
	              'name'        => 'login',
	              'id'          => 'edit-submit',
	              'value'       => 'Forgot Password',
	              'class'       => 'form-submit',
	              'type'        => 'submit',
	));

?>
<style>
html {
	background-image: url("<?php echo base_url().'uploads/background/'.$company->backgroundWclp;?>");
}
#login-box {
    /*background-color: <?php echo $company->colour_one; ?>;*/
    border: 1px solid <?php echo $company->colour_one; ?>;
}
.form-signin input[type="text"], .form-signin input[type="password"], .form-signin input[type="submit"] {
    border: 1px solid <?php echo $company->colour_two; ?>;
    border-radius: 5px;
}
#forgotPassword a{color:<?php echo $company->colour_one; ?>;}
#user_password_error-wrapper{color:<?php echo $company->colour_two; ?>;}
.form-signin input[type="submit"] {
    background-color: <?php echo $company->colour_one; ?>;
    color:#FFFFFF;
}
</style>
<table style="width:100%; height:100%">
<tr>
<td valign="middle" align="center">
<?php
	echo form_open('user/forgot_password', $form_attributes);
	echo '<div id="login-image">' . '<img width="290" src="' . base_url().'uploads/logo/'.$company->filename.'" />'. '</div>';
	//echo '<h2 class="form-signin-heading"></h2>';
	echo '<div id="login-box" class="">';
	echo '<div id="name-wrapper" class="field-wrapper" style="margin-bottom:5px;">'. $username . '</div>';
	echo '<div id="pass-wrapper" class="field-wrapper">'. $email . '</div>';	
	if(isset($exception)){
		echo '<div id="user_password_error-wrapper" class="field-wrapper">'. $exception . '</div>';
	}
	echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';

      echo '</div>';
	echo form_close();
?>
</td></tr></table>
<?php

?>     
</body>
</html>

