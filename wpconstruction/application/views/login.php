  <style type="text/css">
      html {
        background-image: url("https://www.williamscorporation.co.nz/wp-content/uploads/2015/02/wc-bg2.jpg");
        background-position: center center;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        height: 100%;
      
      }
       body {

        padding-top: 0px;
        padding-bottom: 40px;
      }

      .form-signin {
        max-width: 290px;  
      }
      #login-image{
          text-align: center;
      }
      #login-box{
          padding: 15px 15px 10px;
          background-color: #ce1719;
        border: 1px solid #ce1719;
        margin-top: 20px;
        -webkit-border-radius: 5px;
           -moz-border-radius: 5px;
                border-radius: 5px;
        -webkit-box-shadow: 0 1px 2px rgba(0,0,0,.05);
           -moz-box-shadow: 0 1px 2px rgba(0,0,0,.05);
                box-shadow: 0 1px 2px rgba(0,0,0,.05);
      }

      .form-signin .form-signin-heading,

      .form-signin .checkbox {

        margin-bottom: 10px;

      }
.form-signin input[type="text"], .form-signin input[type="password"] , .form-signin input[type="submit"]{
    font-size: 16px;
    height: 35px;
    border:0px solid #fff;
    padding: 2px 5px;
    width:258px;
	
}

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

    </style>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Task Management System - <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/gif">
</head>
<body>

<?php
	$form_attributes = array('class' => 'form-signin', 'id' => 'login-form','method'=>'post');

	//$name = form_label('Username:', 'username');
	$name = form_input(array(
	              'name'        => 'username',
	              'id'          => 'edit-username',
	              'value'       => isset($user->username) ? $user->username : '',
	              'class'       => 'form-input',
                      'placeholder'       => 'User Name',
                  'required'    => TRUE
	));
	
	
	//$pass = form_label('Password:', 'password');
	$pass = form_password(array(
	              'name'        => 'password',
	              'id'          => 'edit-password',
	              'value'       => isset($user->password) ? $user->password : '',
	              'class'       => 'form-input',
                       'placeholder'       => 'Password',
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
	              'value'       => 'Log In',
	              'class'       => 'form-submit',
	              'type'        => 'submit',
	));

	
?>

<table style="width:100%; height:100%">
<tr>
<td valign="middle" align="center">
<?php
	echo form_open('user/user_auto_login', $form_attributes);
	
	echo '<div id="login-image">' . '<img width="290" src="' . base_url().'images/wbs-logo-transparent.png" />'. '</div>';
	//echo '<h2 class="form-signin-heading"></h2>';
	echo '<div id="login-box" class="">';
	echo '<div id="name-wrapper" class="field-wrapper" style="margin-bottom:5px;">'. $name . '</div>';
	echo '<div id="pass-wrapper" class="field-wrapper">'. $pass . '</div>';	
	echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
	if(isset($exception1)){
		echo '<div id="user_password_error-wrapper" class="field-wrapper" style="color: #fdb813;font-size: 13px;line-height: 12px;margin-top: 5px;">'. $exception1 . '</div>';
	}



      echo '</div>';
	echo form_close();
?>
</td></tr></table>
<?php

?>     
<script>
	var base_url = "<?php echo base_url(); ?>";
	$(document).ready(function(){

		$('#login-form').ajaxForm({
			success:function() {
				window.location.reload();
			}
		});

		$( "#edit-submit" ).click({
			$.fancybox.close();
		});
	});
</script>
</body>
</html>

