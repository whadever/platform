<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 


	<title>Maintenance Schedule System  -  <?php if (isset($title)) {echo $title;} ?></title>
	
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>images/favicon.ico" type="image/x-icon">
	
	<!-- Style Sheets -->
	
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
	
	<link href="<?php echo base_url(); ?>bootstrap/css/bootstrap-select.min.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url(); ?>bootstrap/css/bootstrap-select.css" rel="stylesheet" type="text/css"/>

	<link rel="stylesheet" href="<?php echo base_url(); ?>css/fSelect.css">

	<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/schedule.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/client.css" type="text/css" media="screen" />

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/print.css" type="text/css" media="print"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/gantti.css"/>

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/sumoselect.css"/>
        
    <!-- Responsive Style Sheets -->   
    <link rel="stylesheet" href="<?php echo base_url();?>css/responsive.css" type="text/css" media="screen" />   
        
        
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery-2.1.3.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery.form.js"></script>
        <!--<script type="text/javascript" src="<?php echo base_url();?>js/ajaxfileupload.js"></script>
           start: Modal -->
		<link href="<?php echo base_url();?>css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url();?>css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
		<!-- end: Modal -->
		<link href="<?php echo base_url();?>css/fuzzyDropdown.css" rel="stylesheet" type="text/css"/>
 
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery.mtz.monthpicker.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url();?>js/custom.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/new.js"></script>
        
         <script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery-ui.css"/> 
        
        <link rel="stylesheet" href="<?php echo base_url();?>css/flexslider.css" type="text/css" media="screen" />
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery.flexslider-min.js"></script>
		
		<!-- bootstrap -->
        <script src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script> 
        <script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-filestyle.js"> </script> 
		<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-select.min.js"> </script>
		<script type="text/javascript" src="<?php echo base_url(); ?>bootstrap/js/bootstrap-select.js"> </script>
  
        <!-- start: Modal -->
		<script src="<?php echo base_url();?>js/bootstrap-modal.js"></script>
		<script src="<?php echo base_url();?>js/bootstrap-modalmanager.js"></script>
		<script src="<?php echo base_url();?>js/ui-modals.js"></script>
		<script src="<?php echo base_url();?>js/jquery.ui.touch-punch.min.js"></script>

		<script src="<?php echo base_url();?>js/fuse.min.js"></script>
		<script src="<?php echo base_url();?>js/fuzzyDropdown.min.js"></script>

		<script src="<?php echo base_url();?>js/jquery.sumoselect.min.js"></script>
		<script src="<?php echo base_url();?>js/jquery.sumoselect.js"></script>

		<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.tooltip.css">
		<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tooltip.min.js"></script>

 <!-- Jquery: Mobile 
<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>-->

		
		<script type="text/javascript" src="<?php echo base_url(); ?>js/fSelect.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>js/bootstrap-select.min.js"></script>

		<link rel="stylesheet" href="<?php echo base_url(); ?>css/bootstrap-select.min.css">
		
		<script type="text/javascript" src="<?php echo base_url(); ?>js/timeout.js"></script>

		<script>
			jQuery(document).ready(function() {
				UIModals.init();
			});
		</script>
		<!-- end: Modal -->

		<script>
		$(function(){ 
		    $(document).on('focus', ".live_datepicker", function(){
		        $(this).datepicker({
		            changeMonth: true,
		            changeYear: true,
		            dateFormat: 'dd-mm-yy',
					beforeShowDay: $.datepicker.noWeekends,
		   		onClose: function(dateText, inst) 
		   		{
		          this.fixFocusIE = true;
		          this.focus();
		      	}
		        });
		    });
		});
		</script>
		<!-- end: Date Picker -->
        <style>
			#overlay {
				background-color: #000;
				background-image: url("<?php echo base_url(); ?>images/ajax-loading.gif");
				background-position: 50% center;
				background-repeat: no-repeat;
				height: 100%;
				left: 0;
				opacity: 0.5;
				position: fixed;
				top: 0;
				width: 100%;
				z-index: 10000;
			}
			.overlay-text {
				text-align:center;
				width: 100%;
				height: 100%;
				position: fixed;
				top: 54%;
				opacity: 1;
				color:#fff;
				font-size:20px;
				z-index: 100000;
			}
		</style>        
               

<?php 
	$this->ums = $this->load->database('ums', TRUE);

	$user = $this->session->userdata('user'); 
	$wp_company_id = $user->company_id;

	$this->ums->select("wp_company.*,wp_file.*");
	$this->ums->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->ums->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->ums->get('wp_company')->row();

	//print_r($wpdata);
	$main_url = 'https://'.$wpdata->url;
	$colour_two = $wpdata->colour_one;
	$colour_one = $wpdata->colour_two;
	$logo = 'https://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

?>  

<style>
body {
    /*color: <?php echo $colour_one; ?> !important;*/
}
.header {
    border-bottom: 2px solid <?php echo $colour_two; ?> !important;
}
.navbar-default .main-menu .navbar-nav > li > a {
    background-color: <?php echo $colour_two; ?> !important;
}
.navbar-default .main-menu .navbar-nav > li.active > a, .navbar-default .main-menu .navbar-nav > li.active > a:hover {
    color: <?php echo $colour_one; ?> !important;
}
.main-menu ul li:hover a{
    color: <?php echo $colour_one; ?> !important;
}
.content {
    border: 2px solid <?php echo $colour_two; ?> !important;
}
.dashboard-box {
    border: 2px solid <?php echo $colour_two; ?> !important;
}
.page-title {
    border: 2px solid <?php echo $colour_two; ?> !important;
}
.btn-info {
    background-color: <?php echo $colour_two; ?> !important;
}
.page-archive img {
    border: 2px solid <?php echo $colour_two; ?> !important;
}
.table-responsive .table {
    color: #000;
}
.footer {
	color: <?php echo $colour_two; ?> !important;
    background: none !important;
}
.dashboard-box img, .page-title img {
    background: <?php echo $colour_two; ?>;
    border-radius: 5px;
}
.pagination>.active>a, .pagination>.active>a:focus, .pagination>.active>a:hover, .pagination>.active>span, .pagination>.active>span:focus, .pagination>.active>span:hover {
    background-color: <?php echo $colour_two; ?> !important;
    border-color: <?php echo $colour_two; ?> !important;
}
</style>         
</head>
<body>
    <?php $user = $this->session->userdata('user'); ?>
<div id="wrapper">

<div class="header">
    <div class="container-fluid">
     	
    	<div class="row header-top">
			<div class="col-xs-12 col-sm-2 col-md-2 header-top-left" style="padding-right:0px">
				<div style="margin-top:26px;">
					<a style="float:left;" class="brand" href="<?php echo $main_url;?>">
							<img style="margin-right: 10px" src="<?php echo base_url();?>images/btn_home.png" height="50" title="Home" alt="Home" />
					</a>
					
				</div>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-4 header-top-left" style="padding:0px">
				
				<div class="logo" style="margin-top:20px;">
					<a href="#"><img style="max-width: 230px;max-height: 70px;" src="<?php echo $logo; ?>" title="Maintenence Achedule System" alt="MSS LOGO" /></a>
				</div>
				<div class="clear"></div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-6 header-top-right">
			
				<div style="float:left;">
					<nav class="navbar navbar-default">
						<div class="navbar-header">
						<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
						<span class="sr-only">Toggle navigation</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						</button>
						</div>
					</nav>	
				</div>
				
				<div class="user-menu">
					<ul>
						<!---<li style="float:left;">
							<a href="#">
								<img src="<?php echo base_url();?>images/btn_cap.png" height="50" title="<?php echo $user->username; ?>" alt="<?php echo $user->username; ?>" />
							</a>
							<ul>
								<li><a href="<?php echo base_url(); ?>user/user_setting/<?php echo $user->uid; ?>">Setting</a></li>
								<li><a href="<?php echo base_url(); ?>user/user_profile/<?php echo $user->uid; ?>">Profile</a></li>
							</ul>
						</li>--->
						<?php
						$this->db->order_by('id', 'DESC');
						$row = $this->db->get('system_update')->row();
						?>
		           		<span style="float: left;font-size: 15px;margin-right:10px;margin-top: 14px;">
							<strong>System Updated <?php echo date("d/m/Y", strtotime($row->date)); ?></strong>
						</span>
						<a style="float:left;" class="brand" onclick="window.history.go(-1)">
							<img style="margin-right: 10px" src="<?php echo base_url();?>images/back.png" height="50" title="Back" alt="Back" />
						</a>
						<a style="float:right;" href="<?php echo base_url(); ?>user/user_logout"><img src="<?php echo base_url();?>images/btn_power.png" height="50" title="Logout" alt="Logout" /></a>
					</ul>
					
					
				</div>
				
				
				
				
				
			</div>
		</div>
		<div class="row header-bottom">
			
			<div class="col-md-12">
				
				<div id="mss-main-menu">
					<nav class="navbar navbar-default">
						<!-- Brand and toggle get grouped for better mobile display -->
						<!-- Collect the nav links, forms, and other content for toggling -->
						<div class="main-menu responsive-menu collapse navbar-collapse" id="bs-example-navbar-collapse-1">
							<ul class="nav navbar-nav">
								<li class="<?php if($this->uri->segment(1)=='dashboard'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
								<li class="<?php if($this->uri->segment(1)=='client'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>client/client_list">Properties</a></li>
								<li class="<?php if($this->uri->segment(1)=='product'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>product/product_list">Products and Warranties</a></li>
								<li class="<?php if($this->uri->segment(1)=='template'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>template/template_list">Templates</a></li>
								<li class="<?php if($this->uri->segment(1)=='schedule'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>schedule/schedule_list">Schedules</a></li>					
								
							</ul>
						</div><!-- /.navbar-collapse -->
					</nav> 
				</div>
			</div>
			
		
		</div>
		

		

    </div> 
	
	
	

	<div class="all-sub-menu">
    	<!---
	    <?php if($this->uri->segment(1)=='schedule'){ ?>
	    <div class="sub-menu">
	    	<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 responsive-menu">
					<ul>
						<li class="<?php if($this->uri->segment(2)=='schedule_list'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>schedule/schedule_list">Schedule</a></li>
						<li class="<?php if($this->uri->segment(2)=='schedule_add'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>schedule/schedule_add">New Schedule</a></li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>-->
		<!----
	 	<?php if($this->uri->segment(1)=='product'){ ?>
	    <div class="sub-menu">
	    	<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 responsive-menu">
					<ul>
						<li class="<?php if($this->uri->segment(2)=='product_list'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>product/product_list">Product & Warranties</a></li>
						<li class="<?php if($this->uri->segment(2)=='product_add'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>product/product_add">New Product & Warranties</a></li>
						<li class="<?php if($this->uri->segment(2)=='product_type'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>product/product_type">Product Types</a></li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>--->
	    
	    <!---<?php if($this->uri->segment(1)=='template'){ ?>
	    <div class="sub-menu">
	    	<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 responsive-menu">
					<ul>
						<li class="<?php if($this->uri->segment(2)=='template_list'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>template/template_list">Template</a></li>
						<li class="<?php if($this->uri->segment(2)=='template_add'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>template/template_add">New Template</a></li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	
		<?php if($this->uri->segment(1)=='client'){ ?>
	    <div class="sub-menu">
	    	<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 responsive-menu">
					<ul>
						<li class="<?php if($this->uri->segment(2)=='client_list'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>client/client_list">Clients</a></li>
						<li class="<?php if($this->uri->segment(2)=='client_add'){ echo 'active'; } ?>"><a href="<?php echo base_url();?>client/client_add">Add Client</a></li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>--->
	
		<!-----
		<?php if($this->uri->segment(2)=='user_setting' or $this->uri->segment(2)=='user_profile'){ ?>
	    <div class="sub-menu">
	    	<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 responsive-menu">
					<ul>
						<li class="<?php if($this->uri->segment(2)=='user_setting'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>user/user_setting/<?php echo $user->uid; ?>">Settings</a></li>
						<li class="<?php if($this->uri->segment(2)=='user_profile'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>user/user_profile/<?php echo $user->uid; ?>">Profile</a></li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>--->
	
		<!---<?php if($this->uri->segment(2)=='user_list' or $this->uri->segment(2)=='user_add' or $this->uri->segment(2)=='user_update'){ ?>
	    <div class="sub-menu">
	    	<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 responsive-menu">
					<ul>
						<li class="<?php if($this->uri->segment(2)=='user_list'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>user/user_list">User</a></li>
						<li class="<?php if($this->uri->segment(2)=='user_add'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>user/user_add">New User</a></li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>--->

	</div>
 
</div> 