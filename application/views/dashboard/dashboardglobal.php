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
	background-image: url(<?php echo base_url();?>images/WilliamsCorporation.png);
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
.container{ width: 100%;}
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
				echo '<a style="text-decoration:none; color:#000;" href="#"><img class="img-responsive" width="280"  src="'.base_url().'images/'.'"/></a>';
				
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

<div class="container">
	<div class="row">
		<div class="col-xs-12 text-center">
			<div style="width:300px;margin:auto;">
				<div class="image-box1" style="float:left;margin-right:30px;">
					<a style="text-decoration:none; color:black" href="client">
						<img width="120" src="<?php echo base_url();?>images/icon_hums_home/client.png">
						<p>Client Management</p>
					</a>
				</div>	
				<div class="image-box1">
					<a style="text-decoration:none; color:black" href="report">
						<img width="120" src="<?php echo base_url();?>images/icon_hums_home/report.png">
						<p>Report</p>
					</a>
				</div>
			</div>
		</div>
	</div>
</div>
</body>
</html>