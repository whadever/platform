<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Contact - <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/gif">
	<link rel="stylesheet" href="<?php echo base_url();?>css/newtms.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap-select.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap-modal.css" type="text/css" media="screen" />  
	<!-- Add fancyBox -->
	<link rel="stylesheet" href="<?php echo base_url();?>fancybox/jquery.fancybox.css" type="text/css" media="screen" />       
	<link rel="stylesheet" href="<?php echo base_url();?>css/fuelux.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/select2.css" type="text/css" media="screen" />        
	<link rel="stylesheet" href="<?php echo base_url();?>css/style.css" type="text/css" media="screen" />
	<!-- Responsive Style Sheets -->   
	<link rel="stylesheet" href="<?php echo base_url();?>css/responsive.css" type="text/css" media="screen" />  
 	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css"/>
 	<link rel="stylesheet" href="<?php echo base_url();?>css/print.css" type="text/css" media="print"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.multiselect.css" type="text/css" media="screen"/> 

	
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/timeout.js"></script>    
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.mtz.monthpicker.js"></script>
        
	<script type="text/javascript" src="<?php echo base_url();?>js/custom.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/new.js"></script>
        
	<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>
 	<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap-tooltip.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap-modal.js" ></script>
	<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap-modalmanager.js" ></script>
	<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap-select.js" ></script>
	<script type="text/javascript" src="<?php echo base_url();?>fancybox/jquery.fancybox.js"></script>
        
 	<script type="text/javascript" src="<?php echo base_url();?>js/fuelux.js" ></script>
 	<script type="text/javascript" src="<?php echo base_url();?>js/select2.js" ></script>
       
	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.multiselect.js"></script>

<?php 
	$user = $this->session->userdata('user'); 
	$wp_company_id = $user->company_id;

	$this->db->select("wp_company.*,wp_file.*");
	$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->db->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->db->get('wp_company')->row();

	//print_r($wpdata);
	$main_url = 'https://'.$wpdata->url;
	$colour_one = $wpdata->colour_one;
	$colour_two = $wpdata->colour_two;
	$logo = 'https://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

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
.request-count-color {
    color: <?php echo $colour_two; ?> !important;
}
.line {
    background: <?php echo $colour_two; ?> !important;
}
#all-title .title-inner {
    color: <?php echo $colour_one; ?> !important;
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
#project_list_view table tbody td{border-color: <?php echo $colour_two; ?> !important;}

.modal-header {
    background: <?php echo $colour_two; ?> !important;
}

.header .nav {
    background-color: <?php echo $colour_one; ?> !important;
}

.app-name {
    font-size: 18px;
    margin-top: 0;
    color: <?php echo $colour_two; ?>;
}
.username {
    color: <?php echo $colour_one; ?> !important;
}
</style>

</head>

<body>
<?php free_trial_banner(); ?>
	<div id="wrapper">
		<div class="header">
		
    		<div class="container-fluid">
        		<div class="logo">
	        		<a class="brand" href="<?php echo base_url();?>">
	            		<img src="<?php echo $logo;?>" height="67" title="WP TMS" alt="WP TMS" />
	        		</a>
        		</div>
        		<?php $user=  $this->session->userdata('user'); ?>
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
		               		<h5 class="app-name">Contact Management System</h5>
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
				                <li class=<?php  if($this->uri->segment(1)=="overview") echo "active" ?>><?php echo anchor('overview', 'Overview',array('class'=>'overview')); ?></li>
								<li class=<?php  if($this->uri->segment(1)=="contact") echo "active" ?>><?php echo anchor('contact/contact_list','Contact',array('class'=>'project_list')); ?></li>
								<li class=<?php  if($this->uri->segment(1)=="company") echo "active" ?>><?php echo anchor('company/company_list','Companies',array('class'=>'company_list')); ?></li>  						
				                <li class=<?php  if($this->uri->segment(1)=="category") echo "active" ?>><?php echo anchor('category/category_list', 'Categories',array('class'=>'request')); ?></li>			  
				                <!--<li><?php /*echo anchor('user/user_logout','Logout',array('class'=>'user')); */?></li>-->
			                </ul>
			            </div>
		            </nav>
    			</div>
			</div> 
			<div class="container-fluid main-body">       
				<div class="content">