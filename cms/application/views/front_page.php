<html>
    
<head>
    <title> Consent Management System -Home </title> 
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon"> 
	
    <style type="text/css"> 
    
#front-page{margin:0 auto;width:850px;background:#fff;border-radius:10px;padding:20px;height:250px;opacity:0.9;margin:110px auto 0;}    
.image-box { 
    width: 300px; 
    height: 200px; 
    text-align: center;    
    margin: 30px auto;
 } 
   html {

        background-image: url(images/home_bg.jpg);
       background-position: center center;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        height: 100%;
      
      }
      #home_logo{
          float: right;          
          background: #004370;
          padding: 10px;
          border-radius: 10px;
         
      }
/*.imgBox:hover { width: 370px; height: 350px; background: url('images/home.png') no-repeat; } */
.clear{clear: both;}

#Land .modal-body {
    padding: 20px 30px 14px;
}
#Land p {
    color: #333;
    margin: 0;
}
#Land .modal-footer {
    padding: 0 30px 10px;
}
#Land button {
    border: 1px solid #eee;
    border-radius: 3px;
    float: right;
    padding: 5px 10px;
}
#Land.modal{
	margin-left: -200px;
    width: 330px;
	margin-top: -179px !important;
}
</style> 
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
	<!-- start: Modal -->
		<link href="<?php echo base_url();?>css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url();?>css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
	<!-- end: Modal -->
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<!-- start: Modal -->
		<script src="<?php echo base_url();?>js/bootstrap-modal.js"></script>
		<script src="<?php echo base_url();?>js/bootstrap-modalmanager.js"></script>
		<script src="<?php echo base_url();?>js/ui-modals.js"></script>
	<!-- end: Modal -->
	<script>
		jQuery(document).ready(function() {
			UIModals.init();
		});
	</script>
</head>
<body>

	<div class="header-top-right" style="float:right;">
        <a href="<?php echo base_url();?>user/user_logout"> <img src="<?php echo base_url();?>images/btn_power.png" height="67" title="Logout" alt="Logout" /></a>
    </div>
	
    <?php if(isset($message)) echo $message;  ?>

	<div class="clear"></div>
	
	<div class="all-title">
		<?php //echo $title; ?>
	</div>
	
	<div class="clear"></div>

	<div id="front-page" style="text-align:center;">
		<div class="image-box">
			<?php 
				$user = $this->session->userdata('user');  
				if( isset($user->cms_user_profile) )
				{ 
					$cms_user_profile = $user->cms_user_profile;
				}
				else
				{
					$cms_user_profile = '';
				}
			?>
			<?php if( $cms_user_profile == 1 ){ ?>
			<a class="htxt" style="text-decoration:none; color:black" href="<?php echo base_url();?>user/user_list">
			<?php }else if( $cms_user_profile == 2 ){ ?>
			<a class="htxt" style="text-decoration:none; color:black" href="<?php echo base_url();?>consent/consent_list">
			<?php }else if( $role_id == 1 ){ ?>
			<a class="htxt" style="text-decoration:none; color:black" href="<?php echo base_url();?>user/user_list">
			<?php } else{ ?>
			<a class="htxt" style="text-decoration:none; color:black" href="<?php echo base_url();?>consent/consent_list">
			<?php } ?>
				<img width="150" height="150" src="<?php echo base_url();?>/images/cms.png" /> 
				<h3 style="margin:7px; font-family:arial">Consent Management System</h3>
			</a>
		</div>
	</div>

	<div id="Land" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-body">
			<p>Currently under construction.</p>
		</div>
		<div class="modal-footer">		
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
			<div class="clear"></div>
		</div>
	</div> 

</body>
</html>

