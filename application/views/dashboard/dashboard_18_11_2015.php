<?php require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect; 

$user = $this->session->userdata('user'); 
//print_r($client);
$clientLogo = 'uploads/logo/'.$client->filename;
$backgroundWclp = 'uploads/background/'.$client_background->filename;  
$clientTitle = $client->client_name;
?>
<html>
    
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title> Williams Corporation Community - Home </title> 
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon"> 
	<link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	
	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-2.1.4.min.js"> </script>
	<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"> </script>
	<script>
	    window.BaseUrl = "<?php echo base_url(); ?>";
	</script>
<style type="text/css"> 
html{
	/*background-image: url(<?php echo base_url();?>images/WilliamsCorporation.png);*/
	background-image: url(<?php echo base_url().$backgroundWclp;?>);
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



body {
font-family: 'Roboto', sans-serif;
font-size: 14px;
}

#mainContainer{ width: 100%; text-align:center;}

.container{ width: 100%;}
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
.row{margin-top:20px;margin-bottom:20px;}
</style>
</head>
<body>

<div class="container">
	<div class="row">
		<div class="col-xs-6 text-center">
			<?php 
			if( $detect->isMobile() || $detect->isTablet() ){
			 	if($user->role==1){
					$ums = base_url().'user/user_list';
				}else if(is_object($users_create_access) && $users_create_access->application_role_id==1){
					$ums = base_url().'user/user_list';
				}else {
					$ums = '#';
				}
				echo '<a style="text-decoration:none; color:#000;" href="#"><img class="img-responsive" width="280"  src="'.base_url().$clientLogo.'"/></a>';
				
			}
			?>
		</div>
		<div class="col-xs-6">
			<div class="header-top-right" style="float:right;">
				<a href="<?php echo base_url(); ?>user/user_setting/<?php echo $user->uid; ?>"> <img src="<?php echo base_url();?>images/user_setting.png" height="55" title="Detail" alt="Detail" /></a>
			    <a href="<?php echo base_url();?>user/user_logout"> <img  src="<?php echo base_url();?>images/logout.png" height="55" title="Logout" alt="Logout" /></a>    
			</div>
		</div>
	</div>
</div>


<div class="clear"></div>
	
		<?php
			
			foreach($user_apps as $user_app){
				if($user_app->application_id==2){
					if($user_app->application_role_id==2 or $user_app->application_role_id==1){
						$mss = base_url().'mss/dashboard';
					}else{
						$mss = '#';	
					}
					$App2_mss = '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$mss.'"><img src="'.base_url().'images/icon_hums_home/Maintenance.png"><h3>Maintenance System</h3></a></div>';	
				}
				if($user_app->application_id==7){
					if($user_app->application_role_id==2){
						$timesheet = base_url().'timesheet';
					}else if($user_app->application_role_id==3){
						$timesheet = base_url().'timesheet';
					}else{
						$timesheet = '#';
					}
					$App7_timesheet = '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$timesheet.'"><img src="'.base_url().'images/icon_hums_home/Timesheet.png"><h3>Time Sheet System </h3></a></div>';
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
					$App3_tms = '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$tms.'"><img src="'.base_url().'images/icon_hums_home/TaskManagement.png"><h3>Task<br/>Management System</h3></a></div>';
				}
				if($user_app->application_id==1){
					if($user_app->application_role_id==1){
						$hds = base_url().'hds/welcome?uid='.$user->uid;
					}else if($user_app->application_role_id==2){
						$hds = base_url().'hds/welcome?uid='.$user->uid;
					}else if($user_app->application_role_id==3 || $user_app->application_role_id==4 || $user_app->application_role_id==5){
						$hds = base_url().'hds/welcome?uid='.$user->uid;
					}else{
						$hds = '#';
					}
					$App1_ds = '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$hds.'"><img src="'.base_url().'images/icon_hums_home/Development.png"><h3>Development System</h3></a></div>';
				}
				if($user_app->application_id==5){
					if(empty($construction_data->job_id)){
						$job_id = $construction_last_data->id;
					}else{
						$job_id = $construction_data->job_id;
					}
					if($user_app->application_role_id==2){
						$construction = base_url().'wpconstruction/constructions/construction_overview/'.$job_id.'/construction';
					}else if($user_app->application_role_id==3){
						$construction = base_url().'wpconstruction/constructions/construction_overview/'.$job_id.'/construction';
					}else{
						$construction = base_url().'wpconstruction/constructions/construction_overview/'.$job_id.'/construction';
					}
					$App5_construction = '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$construction.'"><img src="'.base_url().'images/icon_hums_home/ConstructionManagement.png"><h3>Construction<br/>Management System</h3></a></div>';
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
					$App4_contact = '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$contact.'"><img src="'.base_url().'images/icon_hums_home/ContactManagement.png" ><h3>Contact<br/>Management System</h3></a></div>';
				}
				if($user_app->application_id==6){
					if($user_app->application_role_id==1){
						$cms = base_url().'cms/welcome?uid='.$user->uid;
					}else if($user_app->application_role_id==2){
						$cms = base_url().'cms/welcome?uid='.$user->uid;
					}else{
						$cms = '#';
					}
					$App6_consent = '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$cms.'"><img src="'.base_url().'images/icon_hums_home/ConsentManagement.png"><h3>Consent<br/>Management System</h3></a></div>';
				}

				if($user_app->application_id==8){
					if($user_app->application_role_id==1){
						$rs = base_url().'rs';
					}else if($user_app->application_role_id==2){
						$rs = base_url().'rs';
					}else if($user_app->application_role_id==3){
						$rs = base_url().'rs';
					}else{
						$rs = '#';
					}
					$App8_rs = '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$rs.'"><img src="'.base_url().'images/icon_hums_home/Reporting_Icon.png"><h3>Reporting System</h3></a></div>';
				}
			}
			//echo '<div class="image-box1"><a style="text-decoration:none; color:black" href="http://williamscorporation.co.nz/dashboard/Select%20Company.html"><img src="'.base_url().'images/icon_hums_home/Timesheet.png" id="counseling-weight-loss"><h3>Time Sheet System</h3></a></div>';
		
		
		?>



<div class="container">
	<div class="row">
		<div class="col-xs-4 text-center"><?php echo $App5_construction; ?></div>
		<div class="col-xs-4 text-center"><?php echo $App8_rs; ?></div>
		<div class="col-xs-4 text-center"><?php echo $App4_contact; ?></div>
	</div>
	<div class="row">
		<?php if( $detect->isMobile() || $detect->isTablet() ){
			$colSize = 'col-md-6';
		}
		else $colSize = 'col-md-3';
		?>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 text-center"><?php echo $App1_ds; ?></div>
		<div class="col-md-6 col-lg-6 text-center hidden-xs hidden-sm">
			<?php
			if($user->role==1){
				$ums = base_url().'user/user_list';
			}else if(is_object($users_create_access) && $users_create_access->application_role_id==1){
				$ums = base_url().'user/user_list';
			}else {
				$ums = '#';
			}
			echo '<a style="text-decoration:none; color:#000;" href="#"><img  width="500" src="'.base_url().$clientLogo.'"></a>';
			?>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-3 col-lg-3 text-center"><?php echo $App2_mss; ?></div>
	</div>
	<div class="row">
		<div class="col-xs-4 text-center"><?php echo $App3_tms; ?></div>
		<div class="col-xs-4 text-center"><?php echo $App7_timesheet; ?></div>
		<div class="col-xs-4 text-center"><?php echo $App6_consent; ?></div>
	</div>
</div>
</body>
</html>