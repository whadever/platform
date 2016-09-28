<html>
    
<head>
    <title> Williams Corporation Community - Home </title> 
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon"> 
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-2.1.4.min.js"> </script>
<style type="text/css"> 
html{background-image: url(<?php echo base_url();?>images/WilliamsCorporation.png);
	background-repeat: no-repeat;
	background-size: 100% 100%;
	background-position: center center;
	height: 100%;}
body{
    background: transparent;
    /*padding: 20px 0 120px;*/
	margin: 0;
	height: 100%;
}
.header-top-right {
    margin-top: 20px;
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

.clear{
	clear: both;
}

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

#middleBubble p {
    font-size: 18px;
    margin: 5px 0;
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

<?php 
$user = $this->session->userdata('user'); 
//print_r($client);
$clientLogo = 'uploads/logo/'.$client->filename; 
$clientTitle = $client->client_name;

?>
<div class="header-top-right" style="float:right;">
	<a href="<?php echo base_url(); ?>user/user_setting/<?php echo $user->uid; ?>"> <img src="<?php echo base_url();?>images/user_setting.png" height="67" title="Detail" alt="Detail" /></a>
    <a href="<?php echo base_url();?>user/user_logout"> <img src="<?php echo base_url();?>images/logout.png" height="67" title="Logout" alt="Logout" /></a>    
</div>

<div class="clear"></div>


<div id="front-page" style="text-align:center;">
	<div id="divCircle">
	
	<?php
		if($user->role==1){
			$ums = base_url().'user/user_list';
		}else if(is_object($users_create_access) && $users_create_access->application_role_id==1){
			$ums = base_url().'user/user_list';
		}else {
			$ums = '#';
		}
		echo '<div id="middleBubble"><div><a style="text-decoration:none; color:#000;" href="#"><img width="150" src="'.base_url().$clientLogo.'"><h3>'.$clientTitle.'</h3></a></div></div>';

		foreach($user_apps as $user_app){
			if($user_app->application_id==2){
				if($user_app->application_role_id==2 or $user_app->application_role_id==1){
					$mss = base_url().'mss/dashboard';
				}else{
					$mss = '#';	
				}
				echo '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$mss.'"><img src="'.base_url().'images/icon_hums_home/Maintenance.png" id="laboratory" data-bubble1="Maintenance System" data-bubble2="Maintenance System 2"><h3>Maintenance System</h3></a></div>';	
			}
			
			if($user_app->application_id==3){
				if($user_app->application_role_id==1){
					$tms = base_url().'tms/overview?uid='.$user->uid;
				}else if($user_app->application_role_id==2){
					$tms = base_url().'tms/overview?uid='.$user->uid;
				}else if($user_app->application_role_id==3){
					$tms = base_url().'tms/overview?uid='.$user->uid;
				}else{
					$tms = '#';
				}
				echo '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$tms.'"><img src="'.base_url().'images/icon_hums_home/TaskManagement.png" id="quick-care" data-bubble1="Task Management System" data-bubble2="Task Management System 2"><h3>Task Management System</h3></a></div>';
			}
			if($user_app->application_id==1){
				if($user_app->application_role_id==1){
					$hds = base_url().'hds/welcome?uid='.$user->uid;
				}else if($user_app->application_role_id==2){
					$hds = base_url().'hds/welcome?uid='.$user->uid;
				}else if($user_app->application_role_id==3){
					$hds = base_url().'hds/welcome?uid='.$user->uid;
				}else{
					$hds = '#';
				}
				echo '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$hds.'"><img src="'.base_url().'images/icon_hums_home/Development.png" id="specialist-coordination" data-bubble1="Development System" data-bubble2="Development System 2"><h3>Development System</h3></a></div>';
			}
			if($user_app->application_id==5){
				if($user_app->application_role_id==2){
					$construction = base_url().'wpconstruction/job';
				}else{
					$construction = '#';
				}
				echo '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$construction.'"><img src="'.base_url().'images/icon_hums_home/ConstructionManagement.png" id="school-physicals" data-bubble1="Construction Management System" data-bubble2="Contact Management System 2"><h3>Construction Management System</h3></a></div>';
			}
			if($user_app->application_id==4){
				if($user_app->application_role_id==1){
					$contact = base_url().'wpcontact';
				}else if($user_app->application_role_id==2){
					$contact = base_url().'wpcontact';
				}else if($user_app->application_role_id==3){
					$contact = base_url().'wpcontact';
				}else{
					$contact = '#';
				}
				echo '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$contact.'"><img src="'.base_url().'images/icon_hums_home/ContactManagement.png" id="school-physicals" data-bubble1="Contact Management System" data-bubble2="Contact Management System 2"><h3>Contact Management System</h3></a></div>';
			}
			if($user_app->application_id==6){
				if($user_app->application_role_id==1){
					$cms = base_url().'cms/welcome?uid='.$user->uid;
				}else if($user_app->application_role_id==2){
					$cms = base_url().'cms/welcome?uid='.$user->uid;
				}else{
					$cms = '#';
				}
				echo '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$cms.'"><img src="'.base_url().'images/icon_hums_home/ConsentManagement.png" id="counseling-weight-loss" data-bubble1="Consent Management System" data-bubble2="Consent Management System 2"><p>Consent Management System</p></a></div>';
			}
		}
		//echo '<div class="image-box1"><a style="text-decoration:none; color:black" href="http://williamscorporation.co.nz/dashboard/Select%20Company.html"><img src="'.base_url().'images/icon_hums_home/Timesheet.png" id="counseling-weight-loss"><h3>Time Sheet System</h3></a></div>';
		
		
	?>
		
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
		start = 0.25; //the angle to put the first image at. a number between 0 and 2pi
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
		
	});
</script>
</body>
</html>