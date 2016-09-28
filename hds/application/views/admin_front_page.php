<html>
    
<head>
    <title> Development System - Home </title> 
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon"> 
	
    <style type="text/css"> 
    html{background-image: url(<?php echo base_url();?>images/home_bg.jpg);
		background-repeat: no-repeat;
		background-size: cover;
	}
    body{
        background: #fff;
        opacity: 0.8;
		margin: 0;
		height: 100%;
    }
    
    #front-page{
        border-radius: 10px;
	    margin: 8% auto;
    }

.image-box { 
    float: left;
    height: 200px;
    margin: 15px;
    width: 250px;
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
	//
	
	</script>
<style>
	body {
		font-family: 'Roboto', sans-serif;
		font-size: 14px;
	}
	
	#mainContainer{ width: 100%; text-align:center;}
	
	#divCircle {
		height: 140px;
	    margin-left: auto;
	    margin-right: auto;
	    position: relative;
	    width: 600px;
	}
	
	#divCircle div.image-box1{
		float: left;
    	width: 33%;
	}
	
	#middleBubble {
	  text-align:center;
	  vertical-align:top;
	  //background: url(images/home-blank-bubble.png);
	  background-repeat: no-repeat;
		  background-size: cover;
		  color: #252525; /*#6d6e71*/
		  font-size: 1em;
		  height:50%;		
		  width: 50%;	
		  margin: auto;
		  position: absolute;
		  text-align:center;
		  top:100px;
		  /*left:160px;*/
		}
		
		
		
		.image-box1 p{
			font-size: 12px;
			margin-bottom:2px;
		}
		.image-box1 span{
			font-size: 10px;
		}
		.image-box1 img{
			/*width:100px;*/
		}
	</style>
</head>
<body>

		<div class="header-top-left" style="float:left;padding-left: 20px;padding-top: 20px;">
			<a onclick="window.history.go(-1)" class="brand">
				<img height="67" alt="Back" title="Back" src="<?php echo base_url();?>images/btn_up.png">
			</a>
		</div>
		<div class="header-top-right" style="float:right;padding-right: 20px;padding-top: 20px;">
            <a href="<?php echo base_url();?>user/user_logout"> <img src="<?php echo base_url();?>images/btn_power.png" height="67" title="Logout" alt="Logout" /></a>
            <!---<a href="<?php echo base_url(); ?>user/user_detail/<?php echo $user->uid; ?>"> <img src="<?php echo base_url();?>images/btn_cap.png" height="67" title="Home" alt="Home" /></a>--->

        </div>


<div class="clear"></div>


<div id="front-page" style="text-align:center;">
	<div id="divCircle">
		<div class="image-box1"><a style="text-decoration:none; color:black" href="<?php echo base_url(); ?>admindevelopment/development_list"><img width="110px" src="<?php echo base_url();?>/images/pms_home.png" /> <p>Current Development</p></a></div>
		
		<div class="image-box1"><a style="text-decoration:none; color:black" href="<?php echo base_url(); ?>potential_admindevelopment/development_list"><img width="110px" src="<?php echo base_url();?>/images/home_potential_land.png" /><p>Potential Land</p></a></div>
	</div>	
</div> 
<div class="clear"></div>

</body>
</html>




