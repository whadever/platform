<?php
	$system_name        =	$this->db->get_where('sms_settings' , array('type'=>'system_name'))->row()->description;
	$system_title       =	$this->db->get_where('sms_settings' , array('type'=>'system_title'))->row()->description;
	$text_align         =	$this->db->get_where('sms_settings' , array('type'=>'text_align'))->row()->description;
	$account_type       =	$this->session->userdata('login_type');
	$skin_colour        =   $this->db->get_where('sms_settings' , array('type'=>'skin_colour'))->row()->description;
	$active_sms_service =   $this->db->get_where('sms_settings' , array('type'=>'active_sms_service'))->row()->description;
	?>
<!DOCTYPE html>
<html lang="en" dir="<?php if ($text_align == 'right-to-left') echo 'rtl';?>">
<head>
	
	<title><?php echo $page_title;?> | <?php echo $system_title;?></title>
    
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Cricket Live Foundation School System - Williams Business Solution" />
	<meta name="author" content="Alimul Razi" />
	
	

	<?php include 'includes_top.php';?>
	
</head>
<body class="page-body <?php if ($skin_colour != '') echo 'skin-' . $skin_colour;?>" >
	<?php include 'header.php';?>
	<div class="container main-body">       
		<div class="content">
			<div class="sidebar"> 
				<div class="block grey">   
		 			<div class="page-container <?php if ($text_align == 'right-to-left') echo 'right-sidebar';?>" >
						<?php echo include 'admin/navigation.php';?>
					</div>
		
				</div>
				<div class="sidebar-block-bottom"> 
				
					<span><?php echo date("h:i a", time()).' | '; ?><?php echo date('d.m.Y', time()); echo ' | '; $today = getdate(); echo $today['weekday']; ?></span>      
				</div>  
			</div>
			<div class="development-home"> 
				<div id="devlopment-content">
					<h3 style="margin-top:0;">
					<i class="entypo-right-circled"></i> 
						<?php echo $page_title;?>
				   </h3>

					<?php include 'admin/'.$page_name.'.php';?>
				</div>
			</div>
		</div>
	</div>	
	<hr style="margin-top:10px;">
	<div class="container">
		
		<?php //include $account_type.'/navigation.php';?>	

			<?php  //include $account_type.'/'.$page_name.'.php';?>

			<?php include 'footer.php';?>

		<?php //include 'chat.php';?>
        	
	</div>
    <?php include 'modal.php';?>
    <?php include 'includes_bottom.php';?>
    
</body>
</html>