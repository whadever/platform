
<?php 
	$user = $this->session->userdata('user'); 
	$wp_company_id = $user->company_id;

	$this->db->select("wp_company.*,wp_file.*");
	$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->db->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->db->get('wp_company')->row();

	//print_r($wpdata);
	$colour_one = $wpdata->colour_one;
	$colour_two = $wpdata->colour_two;
	$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

	 
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
    <title> Williams Corporation Community - Report </title> 
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
}
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
.container{ width: 75%; background: #fff;}
.main-body{
	background: url('http://wclp.co.nz/uploads/background/wc-bg.jpg') no-repeat center center fixed;
	-webkit-background-size: cover;
	-moz-background-size: cover;
	-o-background-size: cover;
	background-size: cover;
	/*height: 100%;*/
}
.dropdown {
    text-align: center;
}
#at_risk_client tr {
    background: #e7e7e8;
}
#at_risk_client tr td {
	border-bottom: 1px solid #fff;
}
#at_risk_client tr td:first-child {
    background: #d1d2d4;
	width: 35%;
    border-right: 1px solid #fff;
}
</style>
</head>
<body>

	<div class="header" style="border-bottom: 1px solid #000;">
	    <div class="container-fluid">
			<?php $user = $this->session->userdata('user'); ?>
			<div class="row">
				<div class="col-xm-3 col-sm-3 col-md-3 col-lg-3">
					<a class="brand" href="<?php echo base_url();?>dashboardglobal">
				            <img width="70" src="<?php echo base_url();?>images/home.png" title="Logout" />
				        </a>
		        </div>
				<div class="col-xm-6 col-sm-6 col-md-6 col-lg-6">
					<div class="logo">
				        <a class="brand" href="<?php echo base_url();?>">
				            <?php if($wp_company_id=='0'){ ?>
								<img width="220" src="<?php echo base_url(); ?>images/logo.png" />
							<?php }else{ ?>
								<img width="220" src="<?php echo $logo; ?>" />
							<?php } ?>
				        </a>
					</div>
		        </div>
		        <div class="col-xm-3 col-sm-3 col-md-3 col-lg-3">
					<div class="logout text-right">
						<a class="brand" onclick="window.history.go(-1)">
				            <img width="70" src="<?php echo base_url();?>images/back.png" title="Back" />
				        </a>
				        <a class="brand" href="<?php echo base_url();?>user/user_logout">
				            <img width="70" src="<?php echo base_url();?>images/logout.png" title="Logout" />
				        </a>
						
					</div>
		        </div>
	        </div>
	    </div>  
	</div> 


<div class="clear"></div>

<?php 
$CI =& get_instance();
$CI->load->model('report_model');
?>

<div class="main-body">
	<div class="container">	
		<div class="row">
			<div class="col-sm-12 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
				 <div class="dropdown">
					  <button style="width:100%;background: #fff;border: 2px solid #eee;" class="btn dropdown-toggle" type="button" data-toggle="dropdown">
						<?php 
						if($_GET['target']=='at_risk_client'){ 
							echo 'At Risk Client';
						}
						else if($_GET['target']=='invoice'){ 
							echo 'Invoice Report';
						}
						else{
							echo '--Select a Report--';
						}
						?>
					  <span class="caret"></span></button>
					  <ul class="dropdown-menu" style="width:100%;">
						<li><a href="<?php echo base_url(); ?>report">--Select a Report--</a></li>
						<li><a href="<?php echo base_url(); ?>report/index?target=at_risk_client">At Risk Client</a></li>
						<li><a href="<?php echo base_url(); ?>report/index?target=invoice">Invoice Report</a></li>
					  </ul>
				</div>
			</div>
		</div>

		<?php if($_GET['target']=='at_risk_client'){ ?>
		<div class="row">
			<div class="col-sm-12 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
				<div class="table-responsive" id="at_risk_client">
				<?php 
				$clients = $CI->report_model->at_risk_client()->result();
				foreach($clients as $client){
				?>
					<table class="table">
					    <tr>
							<td>Client Name:</td>
							<td><?php echo $client->client_name; ?></td>
						</tr>
						<tr>
							<td>Client URL:</td>
							<td><?php echo $client->url; ?></td>
						</tr>
						<tr>
							<td>Client Email:</td>
							<td><?php echo $client->email; ?></td>
						</tr>
						<tr>
							<td>Date Client Created:</td>
							<td><?php echo date('d/m/Y',strtotime($client->created)); ?></td>
						</tr>
						<tr>
							<td>Last Login:</td>
							<td><?php echo date('d/m/Y',strtotime($client->last_login)); ?></td>
						</tr>
					</table>
				<?php 
				}
				?>
				</div>
			</div>
		</div>
		<?php } ?>
		
		<?php if($_GET['target']=='invoice'){ ?>
		<div class="row">
			<div class="col-sm-12 col-md-8 col-lg-8 col-md-offset-2 col-lg-offset-2">
				<div class="table-responsive" id="at_risk_client">
				<?php 
				$clients = $CI->report_model->invoice()->result();
				foreach($clients as $client){
				?>
					<table class="table">
					    <tr>
							<td>Client Name In:</td>
							<td><?php echo $client->client_name; ?></td>
						</tr>
						<tr>
							<td>Client URL:</td>
							<td><?php echo $client->url; ?></td>
						</tr>
						<tr>
							<td>Client Email:</td>
							<td><?php echo $client->email; ?></td>
						</tr>
						<tr>
							<td>Date Client Created:</td>
							<td><?php echo date('d/m/Y',strtotime($client->created)); ?></td>
						</tr>
						<tr>
							<td>Last Login:</td>
							<td><?php echo date('d/m/Y',strtotime($client->last_login)); ?></td>
						</tr>
					</table>
				<?php 
				}
				?>
				</div>
			</div>
		</div>
		<?php } ?>

	</div>
</div>


</body>
</html>