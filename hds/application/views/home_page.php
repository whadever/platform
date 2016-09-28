<html>
    
<head>
    <title> Development System - Home </title> 
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon"> 
	
    <style type="text/css"> 
    html{background-image: url(<?php echo base_url();?>images/home_bg.jpg);
		background-repeat: no-repeat;
		background-size: 100% 100%;
		background-position: center center;
		height: 100%;}
    body{
        background: #fff;
        padding: 20px 0 120px;
        opacity: 0.8;
		margin: 0;
		
    }
    
    #front-page{
        margin: 0 auto;
        width: 1050px;
        border-radius: 10px;
        padding: 0 20px 20px;
        /*height: 410px;*/
		margin: 20px auto 0;
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
		margin-left:auto; 
		margin-right:auto; 
		width: 650px;
		height: 400px;	
		position: relative;
	}
	
	#divCircle div.image-box1{
		position: absolute;
		width: 27%;
		height: 27%;
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
		  top:115px;
		  /*left:160px;*/
		}
		
		
		
		.image-box1 h3{
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
<?php $user=  $this->session->userdata('user'); $user_role_id =$user->rid; $additional_system_access = $user->additional_system_access; ?>
		<div class="header-top-right" style="float:right;">
		


            <a href="<?php echo base_url();?>user/user_logout"> <img src="<?php echo base_url();?>images/btn_power.png" height="67" title="Logout" alt="Logout" /></a>
            <a href="<?php echo base_url(); ?>user/user_detail/<?php echo $user->uid; ?>"> <img src="<?php echo base_url();?>images/btn_cap.png" height="67" title="Home" alt="Home" /></a>

        </div>


<div class="clear"></div>


<div id="front-page" style="text-align:center;">
	<div id="divCircle">
		<div id="middleBubble"><div><a style="text-decoration:none; color:#0D446E" href="#"><img width="150" src="<?php echo base_url();?>ums/images/icon_hums_home/horncastle-arena.png"><h3>Horncastle Management Platform</h3></a></div></div>
		<div class="image-box1"><a style="text-decoration:none; color:black" href="<?php echo base_url();?>cms/welcome"><img src="<?php echo base_url();?>ums/images/icon_hums_home/ConsentManagement.png" ><h3>Consent Management System</h3></a></div>
		<div class="image-box1"><a style="text-decoration:none; color:black" href="<?php echo base_url();?><?php if($user_role_id==1){ echo 'admindevelopment/development_list'; }else{ echo 'welcome'; } ?>"><img src="<?php echo base_url();?>ums/images/icon_hums_home/Development.png"><h3>Development System</h3></a></div>
	</div>	
</div> 
<div class="clear"></div>
<script type="text/javascript">
	$(document).ready(function(){
		//Center the "info" bubble in the  "circle" div
		var divTop = ($("#divCircle").height() - $("#middleBubble").height())/2;
		var divLeft = ($("#divCircle").width() - $("#middleBubble").width())/2;
		//$("#middleBubble").css("top",divTop + "px");
		$("#middleBubble").css("left",divLeft + "px");
		
		//Arrange the icons in a circle centered in the div
		numItems = $( "#divCircle .image-box1" ).length; //How many items are in the circle?
		start = 0; //the angle to put the first image at. a number between 0 and 2pi
		step = (2*Math.PI)/numItems; //calculate the amount of space to put between the items.
		
		//Now loop through the buttons and position them in a circle
		$( "#divCircle div.image-box1" ).each(function( index ) {
			radius = ($("#divCircle").width() - $(this).width())/2; //The radius is the distance from the center of the div to the middle of an icon
			//the following lines are a standard formula for calculating points on a circle. x = cx + r * cos(a); y = cy + r * sin(a)
			//We have made adjustments because the center of the circle is not at (0,0), but rather the top/left coordinates for the center of the div
			//We also adjust for the fact that we need to know the coordinates for the top-left corner of the image, not for the center of the image.
			tmpTop = (($("#divCircle").height()/2) + radius * Math.sin(start)) - ($(this).height()/2);
			tmpLeft = (($("#divCircle").width()/2) + radius * Math.cos(start)) - ($(this).width()/2);
			start += step; //add the "step" number of radians to jump to the next icon
			
			//set the top/left settings for the image
			$(this).css("top",tmpTop);
			$(this).css("left",tmpLeft);
		});
		
		//set the highlight and bubble default based on the homepageGridDefault class
		/*currentGridSelector = $(".homepageGridDefault").attr("id");
		$("#" + currentGridSelector).attr("src", "images/home-" + currentGridSelector + "-icon-on.png");
		$("#middleBubble").html("&lt;p&gt;&lt;b&gt;" + $(".homepageGridDefault").data("bubble1") + "&lt;/b&gt;&lt;br /&gt;" + $(".homepageGridDefault").data("bubble2") + "&lt;/p&gt;");
		
		//Setup the grid to change the highlighted bubble on mouseover ans click
		$("#divCircle img").mouseover(function(){
			//if the selected option has changed, deactivate the current selection
			if(currentGridSelector != $(this).attr("id"))
			{
				$("#" + currentGridSelector).attr("src", "images/home-" + currentGridSelector + "-icon-off.png");
			}
			//turn on the new selection
			$(this).attr("src", "images/home-" + $(this).attr("id") + "-icon-on.png");
			//set the content of the center bubble
			$("#middleBubble").html("&lt;p&gt;&lt;b&gt;" + $(this).data("bubble1") + "&lt;/b&gt;&lt;br /&gt;" + $(this).data("bubble2") + "&lt;/p&gt;");
			currentGridSelector = $(this).attr("id");
		});*/
	});
</script>
</body>
</html>





