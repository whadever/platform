<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Reporting System - <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/gif">
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap-select.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery.timepicker.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.min.css" type="text/css" media="screen" />
	<!--<link rel="stylesheet" href="<?php /*echo base_url();*/?>bootstrap/css/bootstrap-modal.css" type="text/css" media="screen" />-->
	<!-- Add fancyBox -->
	<!--<link rel="stylesheet" href="<?php /*echo base_url();*/?>fancybox/jquery.fancybox.css" type="text/css" media="screen" />-->
	<link rel="stylesheet" href="<?php echo base_url();?>css/style.css" type="text/css" media="screen" />
	<!-- Responsive Style Sheets -->
	<!--<link rel="stylesheet" href="<?php /*echo base_url();*/?>css/responsive.css" type="text/css" media="screen" />
 	<link rel="stylesheet" type="text/css" href="<?php /*echo base_url();*/?>css/jquery.datetimepicker.css"/>
 	<link rel="stylesheet" href="<?php /*echo base_url();*/?>css/print.css" type="text/css" media="print"/>
	<link rel="stylesheet" href="<?php /*echo base_url();*/?>css/jquery.multiselect.css" type="text/css" media="screen"/>-->


	<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
	<!--<script type="text/javascript" src="<?php /*echo base_url();*/?>js/jquery.form.js"></script>-->


	<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap-select.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.timepicker.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/timeout.js"></script>
	<!--task #4429-->
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquerytour.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo base_url();?>js/tour.js"></script>

	<script src="<?php echo base_url();?>js/jquery-ui.min.js"></script>

<?php
	$user = $this->session->userdata('user');
	$wp_company_id = $user->company_id;

	$this->db->select("wp_company.*,wp_file.*");
	$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
 	$this->db->where('wp_company.id', $wp_company_id);
	$wpdata = $this->db->get('wp_company')->row();

	$main_url = 'http://'.$wpdata->url;
	$colour_one = $wpdata->colour_one;
	$colour_two = $wpdata->colour_two;
	$logo = 'https://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

	$sql = "select LOWER(ar.application_role_name) role
					from application a LEFT JOIN users_application ua ON a.id = ua.application_id
						 LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
					where ua.user_id = {$user->uid} and a.id = 8 limit 0, 1";

	$user_app_role = ($this->db->query($sql)->row()) ? $this->db->query($sql)->row()->role : '';

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
.template-menu .dropdown-menu {
	background-clip: padding-box;
	background-color: <?php echo $colour_one; ?>;
	border-radius: 0 0 4px 4px;
	border-width: 0;
	display: none;
	float: left;
	font-size: 14px;
	left: 0;
	list-style: outside none none;
	min-width: unset;
	position: absolute;
	top: 100%;
	width: 95px;
	z-index: 1000;
}
.open > .dropdown-menu {
	display: block;
}
.template-menu .dropdown-menu > li > a {
	color: #fbb900 !important;
}
.template-menu .dropdown-menu > li > a:hover, .template-menu .dropdown-menu > li > a:focus {
	background-color: unset;
	color: #262626 !important;
}
a:focus {
	outline: medium none;
	outline-offset: -2px;
}
.dropdown-menu {
	text-align: left;
}

	option{
		padding: 0 12px;
	}

.app-name {
    font-size: 18px;
    margin-top: 0;
    color: <?php echo $colour_two; ?>;
}
.username {
    color: <?php echo $colour_one; ?> !important;
}
.header .nav{
    background-color: <?php echo $colour_one; ?> !important;
}
</style>

	<script>
		/*tour. task #4429*/
		var config = [
				{
					"name" 		: "tour_1",
					"bgcolor"	: "black",
					"color"		: "white",
					"position"	: "T",
					"text"		: "See your own report and also your staff's report if you have Manager's permission.",
					"time" 		: 5000,
					"buttons"	: ["<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>"]
				},
				{
					"name" 		: "tour_2",
					"bgcolor"	: "black",
					"color"		: "white",
					"text"		: "Submit the report(s) you are assigned to. Your report will be sent to the people in charge.",
					"position"	: "T",
					"time" 		: 5000,
					"buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default nextstep'>next</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
				}

			];

			<?php if($user_app_role == 'admin'): ?>
				config.push({
					"name" 		: "tour_3",
					"bgcolor"	: "black",
					"color"		: "white",
					"text"		: "This tab is only visible to people that has Admin's permission. From here you can add/edit report, change the date line, assign to users, etc. You also able to clone the report if you have a similar ones.",
					"position"	: "T",
					"time" 		: 5000,
					"buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default nextstep'>next</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
				});
			<?php endif; ?>
		//define if steps should change automatically
		var autoplay	= false,
		//timeout for the step
			showtime,
		//current step of the tour
			step		= 0,
		//total number of steps
			total_steps	= config.length;
	</script>

</head>

<body>
<?php free_trial_banner(); ?>
	<div id="wrapper">
		<div class="header">
    		<div class="container-fluid">
        		<div class="logo">
	        		<a class="brand" href="<?php echo base_url();?>">
	            		<img src="<?php echo $logo;?>" height="67" title="WP RS" alt="WP RS" />
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
		                <div class="header-top-left" style="float: right;margin-right: 7px;text-align: right;">
		                	<h3 style="margin-top: 7px;text-align: right;margin-bottom: 0px;"><span class="username"><?php  echo $user->username; ?> </span></h3>
		               		<h5 class="app-name">Reporting System</h5>
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
			                <ul class="nav" style="float: right">
				                
								<li class="<?php  if($this->uri->segment(2)=="view") echo "active" ?> tour tour_1"><?php echo anchor('form/view','View',array('class'=>'project_list')); ?></li>
								<li class="<?php  if($this->uri->segment(2)=="submit") echo "active" ?> tour tour_2"><?php echo anchor('form/submit', 'Submit',array('class'=>'overview')); ?></li>
								<?php if($user_app_role == 'admin'): ?>
								<li class='template-menu <?php  if(in_array($this->uri->segment(2),array('add','show_list','staffs'))) echo "active"; ?> dropdown' >
									<li class="<?php  if(in_array($this->uri->segment(2),array('add','show_list','staffs'))) echo "active" ?>  tour tour_3"><?php echo anchor('form/show_list','Template',array('class'=>'project_list')); ?></li>
								</li>
								<?php endif; ?>

								<?php /*if($user_app_role == 'manager'): */?><!--
								<li class='template-menu <?php /* if(in_array($this->uri->segment(2),array('add','show_list','staffs'))) echo "active"; */?> dropdown' >
									<?php /*echo anchor('#','Template <span class="caret"></span>',array('class'=>'dropdown-toggle', 'data-toggle'=>'dropdown')); */?>
									<ul class="dropdown-menu">
										<li class=<?php /* if($this->uri->segment(1)=="report") echo "active" */?>><?php /*echo anchor('form/add','Add',array('class'=>'report_list')); */?></li>
										<li class=<?php /* if($this->uri->segment(1)=="notes") echo "active" */?>><?php /*echo anchor('form/show_list', 'Edit',array('class'=>'notes')); */?></li>
									</ul>
								</li>
								--><?php /*endif; */?>
								<li class="dropdown" style="float: right; margin-right: 23px; padding-top: 4px;cursor: pointer; height: 35px">
									<img style="height: 100%" src="<?php echo site_url('images/tour_dots.png'); ?>"  data-toggle="dropdown">
									<ul class="dropdown-menu" style="position: absolute; left: -119px; top: 45px; text-align: center; padding: 20px;">
										<li class="activatetour">Take a Tour</li>
									</ul>
								</li>
			                </ul>
			            </div>
		            </nav>
    			</div>
			</div>
			<div class="container-fluid main-body">
				<div class="content">