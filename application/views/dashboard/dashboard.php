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
	/*height:100%;*/
 	min-height:100%;
	background-image: url(<?php echo base_url().$backgroundWclp;?>); 
  	-webkit-background-size: cover;
  	-moz-background-size: cover;
  	-o-background-size: cover;
  	background-size: cover;
  	background-repeat: no-repeat;
}
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
	font-size: 14px;
	margin-bottom:0px;
	margin-top: 10px;
	
}
.image-box1 span{
	font-size: 10px;
}
.image-box1 img{
	width:80px;
}
.row{margin-top:20px;margin-bottom:20px;}
.fixed-height {
    height: 135px;
}
.padding-top {
    padding-top: 38px;
}
.padding-top-1 {
    padding-top: 101px;
}
.padding-top-2 {
    padding-top: 63px;;
}
.padding-left {
    padding-left: 17%;
}
.padding-right {
    padding-right: 17%;
}
.custom .col-xs-2 {
    width: 12%;
}
.custom .col-xs-3 {
    width: 32%;
}
.text-margin-right h3 {
    margin-right: -4%;
}
.text-margin-left h3 {
    margin-left: -4%;
}
.mobile-tablet .image-box1{
	float: none !important;
	text-align: center !important;
	width: 100% !important;
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
	/*position: absolute;*/
	position: fixed;
	height: 100%;
	z-index: -1;
	top: 0;
	bottom: 0;
	left: 0;
	right: 0;
	/* background: url(<?php echo base_url().$backgroundWclp;?>) no-repeat center center fixed; */
	-webkit-background-size: cover;
  	-moz-background-size: cover;
  	-o-background-size: cover;
  	background-size: cover;
  	opacity: 0.5;
	background-color:#fff;
}

</style>
</head>
<body>
<?php free_trial_banner(); ?>
<div class="myDiv">
	<div class="bg"></div>

	<div class="container">
		<div class="row" style="margin-bottom:0px;margin-top:10px;">
			<div class="col-lg-9 col-md-9 col-sm-9 col-xs-6">
				<?php 
					echo '<a class="hidden-lg hidden-md" style="text-decoration:none; color:#000;" href="#"><img class="img-responsive" width="250"  src="'.base_url().$clientLogo.'"/></a>';
				?>
			</div>
			<div class="col-lg-3 col-md-3 col-sm-3 col-xs-6">
				<div class="header-top-right" style="float:right;">
					<a href="<?php echo base_url(); ?>user/user_setting/<?php echo $user->uid; ?>"> <img src="<?php echo base_url();?>images/user_setting.png" height="55" title="Detail" alt="Detail" /></a>
				    <a href="<?php echo base_url();?>user/user_logout"> <img  src="<?php echo base_url();?>images/logout.png" height="55" title="Logout" alt="Logout" /></a>    
				</div>
			</div>
		</div>
	</div>


	<div class="clear"></div>
		
			<?php
			
				$ci =&get_instance();
				$ci->load->model('dashboard_model');

				foreach($user_apps as $user_app){
					if($user_app->application_id==2){
						if($user_app->application_role_id==2 or $user_app->application_role_id==1){
							$mss = base_url().'mss/dashboard';
						}else{
							$mss = '#';	
						}
						$App2_mss = '<div class="image-box1" style="float: left;text-align: center;width: 100px;"><a style="text-decoration:none; color:black" href="'.$mss.'"><img src="'.base_url().'images/icon_hums_home/Maintenance.png"><h3>Maintenance System</h3></a></div>';	
					}
					if($user_app->application_id==7){
						if($user_app->application_role_id==2){
							$timesheet = base_url().'timesheet';
						}else if($user_app->application_role_id==3 || $user_app->application_role_id==4){
							$timesheet = base_url().'timesheet';
						}else{
							$timesheet = '#';
						}
						$App7_timesheet = '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$timesheet.'"><img src="'.base_url().'images/icon_hums_home/Timesheet.png"><h3>Time Sheet System </h3></a></div>';
					}
				
					if($user_app->application_id==3){
						if($user_app->application_role_id==1){
							$tms = base_url().'tms/overview';
						}else if($user_app->application_role_id==2){
							$tms = base_url().'tms/overview';
						}else if($user_app->application_role_id==3){
							$tms = base_url().'tms/overview';
						}else{
							$tms = '#';
						}
						$App3_tms = '<div class="image-box1"><a style="text-decoration:none; color:black" href="'.$tms.'"><img src="'.base_url().'images/icon_hums_home/TaskManagement.png"><h3 style="padding-right: 7px;">Task Management</h3></a></div>';
					}
					if($user_app->application_id==1){
						if($user_app->application_role_id==1){
							$hds = base_url().'hds/welcome';
						}else if($user_app->application_role_id==2){
							$hds = base_url().'hds/welcome';
						}else if($user_app->application_role_id==3 || $user_app->application_role_id==4 || $user_app->application_role_id==5){
							$hds = base_url().'hds/welcome';
						}else{
							$hds = '#';
						}
						$App1_ds = '<div class="image-box1" style="float: right;text-align: center;width: 100px;"><a style="text-decoration:none; color:black" href="'.$hds.'"><img src="'.base_url().'images/icon_hums_home/Development.png"><h3>Development System</h3></a></div>';
					}
					if($user_app->application_id==5){


						if($user_app->application_role_id !=1 && $user_app->application_role_id !=2){
							$construction_permitted_job = $ci->dashboard_model->get_construction_permitted_job($user->uid,$user_app->application_role_id);
							if($construction_permitted_job->job_id){
								if($user_app->application_role_id ==3){
									$construction = base_url().'wpconstruction/constructions/construction_detail/'.$construction_permitted_job->job_id.'?cp=construction';
								}else{
									$construction = base_url().'wpconstruction/constructions/construction_overview/'.$construction_permitted_job->job_id.'?cp=construction';
								}
							}else{
								$construction = '#';
							}
						}else{
							$job_id = $construction_last_data->id; 
							if(empty($job_id)){ 
								$construction = base_url().'wpconstruction/constructions/';
							}else{
								$construction = base_url().'wpconstruction/constructions/construction_overview/'.$job_id.'?cp=construction';
							}
						}

						/*if(empty($construction_data->job_id)){
							$job_id = $construction_last_data->id; 
							if(empty($job_id)){ 
								$construction = '#';
							}else{
								$construction = base_url().'wpconstruction/constructions/construction_overview/'.$job_id.'?cp=construction';
							}	
						}else{
							$job_id = $construction_data->job_id;
							$construction = base_url().'wpconstruction/constructions/construction_overview/'.$job_id.'?cp=construction';
						}
						if($user_app->application_role_id==2){
							$construction = base_url().'wpconstruction/constructions/construction_overview/'.$job_id.'?cp=construction';
						}else if($user_app->application_role_id==3){
							$construction = base_url().'wpconstruction/constructions/construction_overview/'.$job_id.'?cp=construction';
						}else{
							$construction = base_url().'wpconstruction/constructions/construction_overview/'.$job_id.'?cp=construction';
						}*/
						$App5_construction = '<div class="image-box1" style="float: left;text-align: center;width: 100px;"><a style="text-decoration:none; color:black" href="'.$construction.'"><img src="'.base_url().'images/icon_hums_home/ConstructionManagement.png"><h3>Construction<br/> System</h3></a></div>';
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
							$cms = base_url().'cms/welcome';
						}else if($user_app->application_role_id==2){
							$cms = base_url().'cms/welcome';
						}elseif($user_app->application_role_id==3){
							$cms = base_url().'cms/welcome';
						}else{
							$cms = '#';
						}
						$App6_consent = '<div class="image-box1" style="float: right;text-align: center;width: 100px;"><a style="text-decoration:none; color:black" href="'.$cms.'"><img src="'.base_url().'images/icon_hums_home/ConsentManagement.png"><h3>Consent System</h3></a></div>';
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
					
					if($user_app->application_id==9){
						if($user_app->application_role_id==1){
							$bighill = base_url().'guestbook';
						}else if($user_app->application_role_id==2){
							$bighill = base_url().'guestbook';
						}else{
							$bighill = '#';
						}

						$App9_bighill = '<div class="image-box1">
							<a style="text-decoration:none; color:black" href="'.$bighill.'">
								<img src="'.base_url().'images/bighill-logo.png">
								<h3>Guest Book System</h3>
							</a>
							<br>
						</div>';
					}
					
					if($user_app->application_id==10){
						if($user_app->application_role_id==1){
							$sms = base_url().'sms';
						}else{
							$sms = '#';
						}
						$App10_sms = '<div class="image-box1">
							<a style="text-decoration:none; color:black" href="'.$sms.'">
								<img src="'.base_url().'images/sms_icon.png">
								<h3>School Management System</h3>
							</a>
							<br>
						</div>';
					}
					
					if($user_app->application_id==11){
						if($user_app->application_role_id==1){
							$job_costing = base_url().'jobcosting';
						}else{
							$job_costing = '#';
						}
						$App11_job_costing = '<div class="image-box1">
							<a style="text-decoration:none; color:black" href="'.$job_costing.'">
								<img style="padding-top:10px;" src="'.base_url().'images/icon_hums_home/JobCosting_Icon.png">
								<h3>Job Costing System</h3>
							</a>
						</div>';
					}
					
					/*task #4654*/
					if(count($user_apps) == 1){
						if(isset($mss) && $mss != "#"){
							redirect($mss);
						}
						if(isset($timesheet) && $timesheet != "#"){
							redirect($timesheet);
						}
						if(isset($tms) && $tms != "#"){
							redirect($tms);
						}
						if(isset($hds) && $hds != "#"){
							redirect($hds);
						}
						if(isset($construction) && $construction != "#"){
							redirect($construction);
						}
						if(isset($contact) && $contact != "#"){
							redirect($contact);
						}
						if(isset($cms) && $cms != "#"){
							redirect($cms);
						}
						if(isset($rs) && $rs != "#"){
							redirect($rs);
						}
						if(isset($bighill) && $bighill != "#"){
							redirect($bighill);
						}
						if(isset($sms) && $sms != "#"){
							redirect($sms);
						}
					}
					
				}
				//echo '<div class="image-box1"><a style="text-decoration:none; color:black" href="http://williamscorporation.co.nz/dashboard/Select%20Company.html"><img src="'.base_url().'images/icon_hums_home/Timesheet.png" id="counseling-weight-loss"><h3>Time Sheet System</h3></a></div>';
			
			
			?>

	<!--task #4279-->
	<style>
		.rs .image-box1 img
		{
			margin-left: 13px;
		}
		.tms .image-box1 img,
		.consent .image-box1 img{
			margin-left: 9px;
		}
		.tms .image-box1 h3 {
			margin-left: 12px;
		}
	</style>

	<div class="container" style="min-height: 70%;">

	<?php //if( $detect->isMobile() || $detect->isTablet() ){ ?>

		<div class="row hidden-lg hidden-md mobile-tablet">
			<?php if($App8_rs){ ?><div class="col-sm-3 col-xs-6 text-center fixed-height rs"><?php echo $App8_rs; ?></div><?php } ?>
			<?php if($App5_construction){ ?><div class="col-sm-3 col-xs-6 text-center fixed-height"><?php echo $App5_construction; ?></div><?php } ?>
			
			<?php if($App11_job_costing){ ?><div class="col-sm-3 col-xs-6 text-center fixed-height"><?php echo $$App11_job_costing; ?></div><?php } ?>
			
			<?php if($App4_contact){ ?><div class="col-sm-3 col-xs-6 text-center fixed-height"><?php echo $App4_contact; ?></div><?php } ?>

			<?php if($App1_ds){ ?><div class="col-sm-3 col-xs-6 text-center fixed-height"><?php echo $App1_ds; ?></div><?php } ?>

			<?php if($App2_mss){ ?><div class="col-sm-3 col-xs-6 text-center fixed-height"><?php echo $App2_mss; ?></div><?php } ?>

			<?php if($App3_tms){ ?><div class="col-sm-3 col-xs-6 text-center fixed-height tms"><?php echo $App3_tms; ?></div><?php } ?>
			<?php if($App7_timesheet){ ?><div class="col-sm-3 col-xs-6 text-center fixed-height ts"><?php echo $App7_timesheet; ?></div><?php } ?>
			<?php if($App6_consent){ ?><div class="col-sm-3 col-xs-6 text-center fixed-height consent"><?php echo $App6_consent; ?></div><?php } ?>
			<?php if($App9_bighill){ ?><div class="col-sm-3 col-xs-6 text-center fixed-height"><?php echo $App9_bighill; ?></div><?php } ?>
		<?php if($App10_sms){ ?><div class="col-sm-3 col-xs-6 text-center fixed-height"><?php echo $App10_sms; ?></div><?php } ?>
		</div>


	<?php //}else{ ?>

		<div class="row custom hidden-sm hidden-xs" style="height:201px;margin-top:0px;">
			<div class="col-xs-3 text-right padding-top-1 text-margin-right"><?php echo $App1_ds; ?></div>
			<div class="col-xs-2 text-center padding-top"><?php echo $App3_tms; ?></div>
			<div class="col-xs-2 text-center"><?php	echo $App9_bighill;  ?></div>
			<div class="col-xs-2 text-center padding-top"><?php echo $App4_contact; ?></div>
			<div class="col-xs-3 text-left padding-top-1 text-margin-left"><?php echo $App5_construction; ?></div>
		</div>
		<div class="row hidden-sm hidden-xs" style="">
			<?php if( $detect->isMobile() || $detect->isTablet() ){
				$colSize = 'col-md-6';
			}
			else $colSize = 'col-md-3';
			?>
			<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 text-center padding-left"></div>
			<div class="col-md-4 col-lg-4 text-center hidden-xs hidden-sm">
				<?php
				if($user->role==1){
					$ums = base_url().'user/user_list';
				}else if(is_object($users_create_access) && $users_create_access->application_role_id==1){
					$ums = base_url().'user/user_list';
				}else {
					$ums = '#';
				}
				if($client->website){

					$website = preg_replace('#^https?://#', '', $client->website);

					echo '<a style="text-decoration:none; color:#000;" href="http://'.$website.'"><img style="width:400px;" class=""  src="'.base_url().$clientLogo.'"></a>';
				}else{
					echo '<img style="width:400px;" class=""  src="'.base_url().$clientLogo.'">';
				}
				?>
			</div>
			<div class="col-xs-6 col-sm-6 col-md-4 col-lg-4 text-center padding-right"><?php echo $App11_job_costing; ?></div>
		</div>
		<div class="row custom hidden-sm hidden-xs" style="height:210px">
			<div class="col-xs-3 text-right text-margin-right"><?php echo $App6_consent; ?></div>
			<div class="col-xs-2 text-center padding-top-2"><?php echo $App7_timesheet; ?></div>
			<div class="col-xs-2 text-center padding-top-1"><?php echo $App10_sms; ?></div>
			<div class="col-xs-2 text-center padding-top-2"><?php echo $App8_rs; ?></div>
			<div class="col-xs-3 text-left text-margin-left"><?php echo $App2_mss; ?></div>
		</div>

	<?php //} ?>

	</div>

	<div class="container">
		<div class="row" style="margin-top:0px;margin-bottom:10px;">
			<div class="col-xs-12 text-center">
				<!--<p align="center" style="margin:0px;color:#555;">Williams Platform<br/>Last Backed Up: <?php /*echo date('M d, Y'); */?><br/>Last Updated: <?php /*echo $start_date = date("M d, Y", strtotime('-1 Monday')); */?><br/><a target="_BLANK" href="https://www.williamsplatform.com/"><img border="0" width="163" src="<?php /*echo base_url();*/?>images/PoweredByLogo.png"/></a></p>-->
				<p align="center" style="margin:0px;color:#555;">Williams Platform<br/>Last Backed Up: <?php echo date('M d, Y'); ?><br/>Last Updated: <?php echo $start_date = date("M d, Y", strtotime('-1 Monday')); ?><br/><a target="_BLANK" href="http://www.williamsplatform.com/"><img border="0" width="163" src="<?php echo base_url();?>images/PoweredByLogo.png"/></a></p>
			</div>
		</div>	 
	</div> 

</div>

</body>
</html>