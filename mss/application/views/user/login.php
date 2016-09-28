<style type="text/css">
html{background-color:#fff;background-position: center center; background-repeat: no-repeat; background-size: 100% 100%; height: 100%; }
body {  padding-top: 40px; padding-bottom: 40px; }
.form-signin { max-width: 290px; margin: 20px auto; }
#login-image{ text-align: center; }
#login-box { padding: 15px; background-color: #004370; border: 1px solid #004370; margin-top: 20px; -webkit-border-radius: 5px; -moz-border-radius: 5px; border-radius: 5px; -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05); -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05); box-shadow: 0 1px 2px rgba(0,0,0,.05); }
.form-signin .form-signin-heading, .form-signin .checkbox { margin-bottom: 10px; }
.form-signin input[type="text"], .form-signin input[type="password"] , .form-signin input[type="submit"]{ font-size: 16px; height: 35px; border:0px solid #fff; padding: 2px 5px; width:258px; }
.form-signin input[type="submit"]{cursor:pointer;}
#submit-wrapper{margin-top: 5px;}
.form_row button.btn {
    background-color: #006DCC;
    background-image: linear-gradient(to bottom, #0088CC, #0044CC);
    background-repeat: repeat-x;
    border-color: rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.1) rgba(0, 0, 0, 0.25);
    border-radius: 4px;
    color: #FFFFFF;
    font-size: 14px;
    margin-left: 24%;
    padding: 4px 0;
    text-align: center;
    text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.25);
    width: 30%;
}
.user_name_error{
    color: #fdb813;
    right: 290px;
    margin-top: -25px;
    padding: 3px;
    position: absolute;
    width: 200px;
}
.password_error{
	color: #fdb813;
    right: 380px;
    margin-top: -25px;
    position: absolute;
}
.form-signin-heading{font-size:18px; text-align:center;}

</style>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Maintenance Schedule System - Login - <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
</head>
<body>

<?php
	$form_attributes = array('class' => 'form-signin', 'id' => 'login-form','method'=>'post');

	//$name = form_label('Username:', 'name');
	$name = form_input(array(
	              'name'        => 'name',
	              'id'          => 'edit-name',
	              'value'       => isset($user->name) ? $user->name : '',
	              'class'       => 'form-input',
                      'placeholder'       => 'User Name',
                  'required'    => TRUE
	));
	
	//$pass = form_label('Password:', 'pass');
	$pass = form_password(array(
	              'name'        => 'pass',
	              'id'          => 'edit-pass',
	              'value'       => isset($user->pass) ? $user->pass : '',
	              'class'       => 'form-input',
                       'placeholder'       => 'Password',
                    'required'    => TRUE

	));
	
	/* $password_error = $this->session->userdata('password_error');
    if(isset($password_error)) {
        $password_error1 = $password_error;
		$this->session->unset_userdata('password_error');
    }

	$username_error=$this->session->userdata('username_error');
    if(isset($username_error)) {
        $username_error1 = $username_error;
		$this->session->unset_userdata('username_error');
    } */

	$user_password_error = $this->session->userdata('user_password_error');
    if(isset($user_password_error)) {
        $user_password_error1 = $user_password_error;
		$this->session->unset_userdata('user_password_error');
    }


	$submit = form_submit(array(
	              'name'        => 'Login',
	              'id'          => 'edit-submit',
	              'value'       => 'Log In',
	              'class'       => 'form-input',
	              'type'        => 'submit',
	));


	echo form_open('user/user_login', $form_attributes);
	
	echo '<div id="login-image">' . '<img width="150" src="' . base_url().'images/williums_logo.png" />'. '</div>';
	echo '<h2 class="form-signin-heading">Maintenance Schedule System</h2>';
        echo '<div id="login-box" class="">';
	echo '<div id="name-wrapper" class="field-wrapper" style="margin-bottom:5px;">'. $name . '</div>';
	echo '<div id="pass-wrapper" class="field-wrapper">'. $pass . '</div> ';
	//echo '<div id="name-wrapper error" class="field-wrapper password_error" style="color: #fdb813;">'. $password_error1 . '</div></div>';
	echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';

	if(isset($user_password_error1)){
		echo '<div id="user_password_error-wrapper" class="field-wrapper" style="color: #fdb813;font-size: 13px;line-height: 12px;margin-top: 5px;">'. $user_password_error1 . '</div>';
	}
	echo '</div>';
	echo form_close();
?>     

</body>
</html>

