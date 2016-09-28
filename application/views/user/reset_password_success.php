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
	

	$submit = form_label('', 'submit');
	$submit .= form_submit(array(
	              'name'        => 'login',
	              'id'          => 'edit-submit',
	              'value'       => 'Login',
	              'class'       => 'form-submit',
	              'type'        => 'submit',
	));

?>
<style>
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
.form-signin input[type="submit"], #login-box a {
    background-color: <?php echo $company->colour_one; ?>;
    color:#FFFFFF;
}
</style>
<table style="width:100%; height:100%">
<tr>
<td valign="middle" align="center">
<?php
	echo form_open('user/user_login', $form_attributes);
	echo '<div id="login-image">' . '<img width="290" src="' . base_url().'uploads/logo/'.$company->filename.'" />'. '</div>';
	//echo '<h2 class="form-signin-heading"></h2>';
	echo '<div id="login-box" class="">';
	echo '<div id="user_password_error-wrapper" class="field-wrapper">You have successfully changed your password!<br/>
Please click the button below to log in.</div>';

	echo '<br/><a style="  border-radius: 5px;padding: 7px 98px;text-decoration: none;" href="/user/user_login" class="btn btn-info" role="button">Login</a>';

      echo '</div>';
	echo form_close();
?>
</td></tr></table>
<?php

?>     
</body>
</html>

