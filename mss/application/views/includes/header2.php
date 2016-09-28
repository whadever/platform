<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 


	<title>Maintenance Schedule System  -  <?php if (isset($title)) {echo $title;} ?></title>
	
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	
	<!-- Style Sheets -->
	
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
	
	<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/schedule.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/client.css" type="text/css" media="screen" />

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/print.css" type="text/css" media="print"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/gantti.css"/>
        
    <!-- Responsive Style Sheets -->   
    <link rel="stylesheet" href="<?php echo base_url();?>css/responsive.css" type="text/css" media="screen" />   
        
        
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery.form.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/ajaxfileupload.js"></script>
          <!-- start: Modal -->
		<link href="<?php echo base_url();?>css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url();?>css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
		<!-- end: Modal -->
        
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
           
        <!-- start: Modal -->
		<script src="<?php echo base_url();?>js/bootstrap-modal.js"></script>
		<script src="<?php echo base_url();?>js/bootstrap-modalmanager.js"></script>
		<script src="<?php echo base_url();?>js/ui-modals.js"></script>
		<script src="<?php echo base_url();?>js/jquery.ui.touch-punch.min.js"></script>

 <!-- Jquery: Mobile 
<link rel="stylesheet" href="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
<script src="http://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>-->

		<script>
			jQuery(document).ready(function() {
				UIModals.init();
			});
		</script>
		<!-- end: Modal -->

	
		<script>
			jQuery(document).ready(function() {
		        $( "#milestone_date,#planned_start_date,#planned_finished_date,#task_start_date,#construction_start_date,#maintainence_bond_date" ).datepicker({
				    changeMonth: true,//this option for allowing user to select month
				    changeYear: true, //this option for allowing user to select from year range
					format: 'dd-mm-yyyy'
			    });
		    });
			
		</script>
		<!-- end: Date Picker -->
                
               
       
</head>
<body>
    <?php $user = $this->session->userdata('user'); ?>
<div id="wrapper">

<div class="header">
    <div class="container-fluid">
     	
    	<div class="row header-top">
			<div class="col-xs-12 col-sm-6 col-md-6 header-top-left">
				<div class="logo">
					<a href="<?php echo base_url(); ?>dashboard"><img src="<?php echo base_url(); ?>images/maintenence_schedule_system.jpg" /></a>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 col-md-6 header-top-right">
				<div class="user-menu">
					<ul>
						<li>
							<a href="#"><?php echo $user->name; ?></a>
							<ul>
								<li><a href="<?php echo base_url(); ?>user/user_setting">Setting</a></li>
								<li><a href="<?php echo base_url(); ?>user/user_profile/<?php echo $user->uid; ?>">Profile</a></li>
							</ul>
						</li>
					</ul>
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
			</div>
		</div>

	

    </div>
<div id="mss-main-menu">
	<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
		<nav class="navbar navbar-default">
    		<!-- Brand and toggle get grouped for better mobile display -->
			<!-- Collect the nav links, forms, and other content for toggling -->
    		<div class="main-menu responsive-menu collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      			<ul class="nav navbar-nav">
        			<li class="<?php if($this->uri->segment(1)=='dashboard'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>dashboard">Dashboard</a></li>
					<li class="<?php if($this->uri->segment(1)=='schedule'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>schedule/schedule_list">Schedules</a></li>
					<li class="<?php if($this->uri->segment(1)=='template'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>template/template_list">Templates</a></li>
					<li class="<?php if($this->uri->segment(1)=='product'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>product/product_list">Products and Warranties</a></li>
					<li class="<?php if($this->uri->segment(1)=='client'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>client/client_list">Clients</a></li>
					<li><a href="<?php echo base_url(); ?>user/user_logout">Logout</a></li>
      			</ul>
    		</div><!-- /.navbar-collapse -->
		</nav> 
		</div>
		</div>
		</div>
</div>
	<div class="all-sub-menu">
    
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
		<?php } ?>
	
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
		<?php } ?>
	    
	    <?php if($this->uri->segment(1)=='template'){ ?>
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
		<?php } ?>
	
	
		<?php if($this->uri->segment(2)=='user_setting' or $this->uri->segment(2)=='user_profile'){ ?>
	    <div class="sub-menu">
	    	<div class="container-fluid">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12 responsive-menu">
					<ul>
						<li class="<?php if($this->uri->segment(2)=='user_setting'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>user/user_setting">Settings</a></li>
						<li class="<?php if($this->uri->segment(2)=='user_profile'){ echo 'active'; } ?>"><a href="<?php echo base_url(); ?>user/user_profile/<?php echo $user->uid; ?>">Profile</a></li>
					</ul>
					</div>
				</div>
			</div>
		</div>
		<?php } ?>
	
		<?php if($this->uri->segment(2)=='user_list' or $this->uri->segment(2)=='user_add' or $this->uri->segment(2)=='user_update'){ ?>
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
		<?php } ?>

	</div>
 
</div> 