<?php 
if(!isset($company->filename)) $company->filename='william_platform_logo.png'; 
if(!isset($company->backgroundWclp)) $company->backgroundWclp='wc-bg.jpg';

$query_user = "select * from users where company_id='".$company->id."' and role='1'";
$row = $this->db->query($query_user)->row();
$last_login = $row->last_login;
?>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Williams Platform - <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	
	<link href="<?php echo base_url(); ?>css/tipso/tipso.css" rel="stylesheet" type="text/css" >
	<link href="<?php echo base_url(); ?>css/login_styles.css" rel="stylesheet" type="text/css" >

	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-2.1.4.min.js"> </script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/south-street/jquery-ui.min.css">
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>css/tipso/tipso.js"> </script>

	<style>
		.ui-dialog .ui-dialog-buttonpane {
			background-image: none;
			border-width: 1px 0 0;
			margin-top: 0.5em;
			padding: 0;
			text-align: left;
		}
		.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset {
			text-align: center;
			float: none;
		}
	</style>

</head>
<body>
<?php echo free_trial_banner(); ?>
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
	
	$password = form_password(array(
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
<style>
/*html {
	background: url("<?php echo base_url().'uploads/background/'.$company->backgroundWclp;?>") no-repeat center center;
	-webkit-background-size: cover;
  	-moz-background-size: cover;
  	-o-background-size: cover;
  	background-size: cover;
  	opacity: 0.6;
}*/
body {
    background: transparent none repeat scroll 0 0;
    height: 100%;
    margin: 0;
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
.myDiv {
	position: relative;
	z-index: 5;
	/*height: 250px;
	width: 300px;
	color: #000;
	font-size: 400%;
	padding: 20px;*/
}

.myDiv .bg {
	position: absolute;
	z-index: -1;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	background: url("<?php echo base_url().'uploads/background/'.$company->backgroundWclp;?>") no-repeat center center;
	-webkit-background-size: cover;
  	-moz-background-size: cover;
  	-o-background-size: cover;
  	background-size: cover;
  	opacity: 0.6;
}
</style>
<!--display messate-->
<div class="myDiv">
	<div class="bg"></div>
	<div id="infoMessage">

		<?php if($this->session->flashdata('success-message')){ ?>

			<div class="alert alert-success" id="success-alert">
				<button type="button" class="close" data-dismiss="alert">x</button>
				<?php echo $this->session->flashdata('success-message');?>
			</div>
		<?php } ?>

		<?php if($this->session->flashdata('warning-message')){ ?>

			<div class="alert alert-warning" id="warning-alert">
				<button type="button" class="close" data-dismiss="alert">x</button>
				<strong>Success! </strong>
				<?php echo $this->session->flashdata('warning-message');?>
			</div>
		<?php } ?>

	</div>
	<!------------------->
	<table style="width:100%; height:100%">
	<tr>
	<td valign="middle" align="center">
	<?php
		if($last_login=='0000-00-00'){
			$trip = 'class="welcome" data-tipso="Welcome to Williams Platform<br><br>We believe that this is your first time.<br>Please enter your log in detail <br>here to customise your company."';
		}else{
			$trip = '';
		}
		echo form_open('user/user_login', $form_attributes);
		echo '<div id="login-image">' . '<img width="290" src="' . base_url().'uploads/logo/'.$company->filename.'" />'. '</div>';
		//echo '<h2 class="form-signin-heading"></h2>';
		echo '<div id="login-box" '.$trip.'>';
		echo '<div id="name-wrapper" class="field-wrapper" style="margin-bottom:5px;">'. $username . '</div>';
		echo '<div id="pass-wrapper" class="field-wrapper">'. $password . '</div>';	
		if(isset($exception1)){
			echo '<div id="user_password_error-wrapper" class="field-wrapper">'. $exception1 . '</div>';
		}
		echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
		echo '<div id="forgotPassword" class="field-wrapper"><a href="/user/forgot_password">Forgotten your password?</a></div>';

	      echo '</div>';
		echo form_close();
	?>
	</td></tr>

	<tr>
	<td valign="bottom" align="center">
	<p style="margin:20px 0 10px 0;"><a target="_BLANK" href="http://www.williamsplatform.com/"><img border="0" width="163" src="<?php echo base_url();?>images/PoweredByLogo.png"/></a></p>
	</td></tr>
	</table>

</div>
<script>
//jQuery(window).load(function(){
// Show Tipso on Load
//jQuery('.welcome').tipso('show');
</script>

<?php
if($this->session->userdata('successful_payment')):
	$this->session->unset_userdata('successful_payment');
	?>
	<div id="dialog-message" title="Payment Success">
		<p>
			Registration is successful, enjoy your free 30 days trial.
		</p>
	</div>
	<script>
		jQuery(document).ready(function(){
			jQuery( "#dialog-message" ).dialog({
				modal: true,
				buttons: {
					Ok: function() {
						$( this ).dialog( "close" );
					}
				}
			});
		});
	</script>

	<?php
endif;
?>

<?php if($last_login=='0000-00-00'){ ?>
<script>
	jQuery(document).ready(function(){
		
		jQuery('.welcome').tipso({
			position: 'top-right',
			background: 'rgba(0,0,0,0.8)',
			titleBackground: 'tomato',
			useTitle: false,
			width: 250,
			tooltipHover: true
		});
	});
	
	jQuery(window).load(function(){
		// Show Tipso on Load
		jQuery('.welcome').tipso('show');
	});
</script>

<?php } ?>
	   
</body>
</html>

