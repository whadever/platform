<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Job Costing - <?php if (isset($title)) {echo $title;} ?></title>
	
	<link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/gif">

	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css"/>	
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
	
	<!--start: Modal -->
	<link href="<?php echo base_url();?>css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
	<!-- end: Modal -->
		
	<link rel="stylesheet" href="<?php echo base_url();?>css/style.css" type="text/css" media="screen" />	
	<link rel="stylesheet" href="<?php echo base_url();?>css/responsive.css" type="text/css" media="screen" /> 

	
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.form.js"></script>       
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>      
	<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>      
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	
	
	<!-- start: Modal -->
	<script src="<?php echo base_url();?>js/bootstrap-modal.js"></script>
	<script src="<?php echo base_url();?>js/bootstrap-modalmanager.js"></script>
	<script src="<?php echo base_url();?>js/ui-modals.js"></script>
	<!-- end: Modal -->
	
	<script src="<?php echo base_url();?>js/timeout.js"></script>
	
	<script>
		$(function(){ 
		    $(document).on('focus', ".live_datepicker", function(){
		        $(this).datepicker({
		            changeMonth: true,
		            changeYear: true,
		            dateFormat: 'dd-mm-yy'
		        });
		    });  
		});
	</script>
	
	<?php 
		$user = $this->session->userdata('user'); 

		$wp_company_id = $user->company_id;

		$this->db->select("wp_company.*,wp_file.*");
		$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
	 	$this->db->where('wp_company.id', $wp_company_id);	
		$wpdata = $this->db->get('wp_company')->row();

		//print_r($wpdata);
		$main_url = 'http://'.$wpdata->url;
		$colour_one = $wpdata->colour_one;
		$colour_two = $wpdata->colour_two;
		$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;


	?>  
<style>
body {
    color: <?php echo $colour_one; ?> !important;
}
.header {
    border-bottom: 2px solid <?php echo $colour_one; ?> !important;
}
.header .nav > li.active > a {
    background-color: <?php echo $colour_two; ?> !important;
}

.header .nav{
    background-color: <?php echo $colour_one; ?> !important;
	height:35px;
}

.header .nav > li > a {
    background-color: <?php echo $colour_one; ?> !important;
}
.footer {
    border-top: 2px solid <?php echo $colour_one; ?> !important;
}
#maincontent {
    border: 2px solid <?php echo $colour_one; ?> !important;
}
#maincontent hr {
    background-color: <?php echo $colour_one; ?> !important;
}
a {
    color: <?php echo $colour_one; ?> !important;
}
.header .nav > li > a {
    color: <?php echo $colour_two; ?> !important;
}
.header .nav > li.active > a {
    color: <?php echo $colour_one; ?> !important;
}
.footer {
    color: <?php echo $colour_one; ?> !important;
}
.line {
    background: <?php echo $colour_two; ?> !important;
}
#all-title .title-inner {
    color: #000 !important;
}
.header-top-right {
    color: <?php echo $colour_two; ?> !important;
}
.dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus {
    background-color: <?php echo $colour_two; ?> !important;
}
.userme {
    background-color: <?php echo $colour_one; ?> !important;
}
.red {
    color: <?php echo $colour_one; ?> !important;
}
.app-name {
    font-size: 18px;
    margin-top: 0;
    color: <?php echo $colour_two; ?>;
}
.username {
    color: <?php echo $colour_one; ?> !important;
}
.header .nav {
    float: left;
    margin: 0;
    width: 100%;
    clear: both;
}
.navbar {
    border-radius: 0px;
}
.btn-danger {
    color: #fff !important;
    background-color: <?php echo $colour_one; ?>;
    border-color: <?php echo $colour_one; ?>;
}
.btn-danger:hover {
    color: #fff !important;
    background-color: <?php echo $colour_one; ?>;
    border-color: <?php echo $colour_one; ?>;
}
</style>  	        		
</head>


<body>
	<div id="wrapper">
		<div class="header">
    		<div class="container-fluid">
    		
        		<div class="logo">
	        		<a class="brand" href="<?php echo base_url();?>">
	            		<img src="<?php echo $logo;?>" height="67" />
	        		</a>
        		</div>

				<div class="top">
		            <div class="header-top">
		                <div class="header-top-right" style="float: right;">
			                <h3 style="margin-top: 15px;text-align: right;margin-bottom: 0px;">
			                    <a class="" href="<?php echo $main_url; ?>">
			                        <img style="margin-right: 5px" src="<?php echo base_url();?>images/btn_home.png" height="25" title="Williams Corporation Platform" alt="Williams Corporation Platform" />
			                    </a>

			                    <a class="" href="<?php echo site_url('user/user_logout'); ?>">
			                        <img style="margin-right: 5px" src="<?php echo base_url();?>images/btn_power.png" height="25" title="Williams Corporation Platform" alt="Williams Corporation Platform" />
			                    </a>
							</h3>
		                </div>
		                <div class="header-top-left" style="float: right;margin-right: 7px;">
		                	<h3 style="margin-top: 7px;text-align: right;margin-bottom: 0px;"><span class="username"><?php  echo $user->username; ?> </span></h3>
		               		<h5 class="app-name">Job Costing System</h5>
		                </div>
		                
		                <div style="clear:both;"></div>
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
				<div class="clear"></div>
			</div> 
			
			<div class="" align="center">
	            <nav class="navbar navbar-default header-bottom">
	    			<!-- Brand and toggle get grouped for better mobile display -->
					<!-- Collect the nav links, forms, and other content for toggling -->
		    		<div class="main-menu responsive-menu collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		                <ul class="nav">
	                        <li class=<?php if($this->uri->segment(1)=="job") echo "active" ?>><?php echo anchor('job', 'Job',array('class'=>'job')); ?></li>
	                        <li class=<?php  if($this->uri->segment(1)=="templates") echo "active" ?>><?php echo anchor('templates/template', 'Templates',array('class'=>'templates')); ?></li>	
	                        <li class=<?php  if($this->uri->segment(1)=="items") echo "active" ?>><?php echo anchor('items/item_view', 'Items',array('class'=>'item')); ?></li>	
		                </ul>
		            </div>
	            </nav>
			</div>
		</div> 
			
		<div class="container-fluid main-body">       
			<div class="content">