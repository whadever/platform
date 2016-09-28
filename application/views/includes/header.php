<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Williams Corporation Community - <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/gif">
	
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/ImageColorPicker.css">
	
	<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery-ui.css">
	
	<link href="<?php echo base_url(); ?>bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>bootstrap/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>bootstrap/css/bootstrap-select.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>bootstrap/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>css/wp_styles.css" rel="stylesheet" type="text/css"/>

	<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery-2.1.4.min.js"> </script>
		
	<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap.min.js"> </script>
	<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-filestyle.js"> </script>
	<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-select.min.js"> </script>
	<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-select.js"> </script> 
	<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-modal.js"> </script>
	<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-modalmanager.js"> </script>

	<script src="<?php echo base_url(); ?>js/jquery-ui.js"></script>
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.3/themes/humanity/jquery-ui.min.css">

	<script src="<?php echo base_url(); ?>js/jquery.ImageColorPicker.js"></script>

	
	<meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">


	<script>
	    window.BaseUrl = "<?php echo base_url(); ?>";
	</script>

<?php 
	$user = $this->session->userdata('user'); 
	//print_r($user);
	$wp_company_id = $user->company_id;

	$this->db->select("wp_company.*,wp_file.*");
	$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->db->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->db->get('wp_company')->row();

	//print_r($wpdata);
	$colour_one = $wpdata->colour_one;
	$colour_two = $wpdata->colour_two;
	$logo = 'https://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

if($wp_company_id!='0'){
?>  

<style>
body {
    color: <?php echo $colour_one; ?> !important;
}
.header {
    border-bottom: 2px solid <?php echo $colour_two; ?> !important;
}
.title-inner {
    border: 2px solid <?php echo $colour_two; ?> !important;
}
.main-page {
    border: 2px solid <?php echo $colour_two; ?> !important;
}
.client-list table thead {
    background: <?php echo $colour_two; ?> !important;
}
<?php if($colour_two){ ?>
.title-inner > img {
    background: <?php echo $colour_two; ?> !important;
	border-radius: 6px;
}
.add img {
    background: <?php echo $colour_two; ?> !important;
	border-radius: 6px;
}
<?php }else{ ?>
.title-inner > img {
    background: #818285 !important;
	border-radius: 6px;
}
.add img {
    background: #818285 !important;
	border-radius: 6px;
}
<?php } ?>
a {
    color: <?php echo $colour_two; ?> !important;
}
.footer {
    color: <?php echo $colour_two; ?> !important;
}
.title-inner strong {
    color: <?php echo $colour_two; ?> !important;
}
</style> 

<?php
}
?> 

</head>
<body>
<?php if($this->uri->segment(2) != 'select_plan') free_trial_banner();?>
<div id="wrapper">
	<div class="header">
	    <div class="container-fluid">
			<?php $user = $this->session->userdata('user'); ?>
			<div class="row">
				<div class="col-xm-3 col-sm-3 col-md-3 col-lg-3">
					<?php if($user->role==3) {?> <a class="brand" href="<?php echo base_url();?>dashboardglobal">
				            <img width="70" src="<?php echo base_url();?>images/home.png" title="Home" />
				        </a> <?php } ?>
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
	<div class="container-fluid main-body">       
		<div class="content">
			<div id="infoMessage" style="margin-top: 20px;">

				<?php if($this->session->flashdata('success-message')){ ?>

					<div class="alert alert-success" id="success-alert">
						<button type="button" class="close" data-dismiss="alert">x</button>
						<?php echo $this->session->flashdata('success-message');?>
					</div>
				<?php } ?>

				<?php if($this->session->flashdata('warning-message')){ ?>

					<div class="alert alert-warning" id="warning-alert">
						<button type="button" class="close" data-dismiss="alert">x</button>
						<?php echo $this->session->flashdata('warning-message');?>
					</div>
				<?php } ?>

			</div>