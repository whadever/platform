<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Williams Platform - <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link href="<?php echo base_url(); ?>css/login_styles.css" rel="stylesheet" type="text/css" >
</head>
<body>
<style>
html {
	background-image: url("<?php echo base_url().'uploads/background/'.$company->backgroundWclp;?>");
}
#login-box {
    /*background-color: <?php echo $company->colour_one; ?>;*/
    border: 1px solid <?php echo $company->colour_one; ?>;
    width: 400px;
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
	echo '<div id="login-image">' . '<img width="290" src="' . base_url().'uploads/logo/'.$company->filename.'" />'. '</div>';
	//echo '<h2 class="form-signin-heading"></h2>';
	echo '<div id="login-box" class="">';
	if(isset($exception1)){
		echo '<div id="user_password_error-wrapper" class="field-wrapper">'. $exception1 . '</div>';
	}
	echo '<div id="forgotPassword" class="field-wrapper">An email has been sent to your email with a link to reset your pasword.</div>';

      echo '</div>';
?>
</td></tr></table>
<?php

?>     
</body>
</html>

