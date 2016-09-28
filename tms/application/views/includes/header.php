<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 

		<title>TMS - <?php if (isset($title)) {echo $title;} ?></title>
        <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/gif">
	
        <link rel="stylesheet" href="<?php echo base_url();?>css/newtms.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap-select.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap-modal.css" type="text/css" media="screen" />  
        <!-- Add fancyBox -->
        <link rel="stylesheet" href="<?php echo base_url();?>fancybox/jquery.fancybox.css" type="text/css" media="screen" />

        <!--task #4421-->
        <link rel="stylesheet" href="<?php echo base_url();?>css/jquerytour.css" type="text/css" />

        
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
        
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery.mtz.monthpicker.js"></script>

        <!--task #4421-->
        <script type="text/javascript" src="<?php echo base_url();?>js/tour.js"></script>

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
		<script type="text/javascript" src="<?php echo base_url();?>js/timeout.js"></script>
<?php 
	$user = $this->session->userdata('user'); 

	//print_r($user);

	$this->db->select('application_role_id');
	$this->db->where('user_id',$user->uid);
	$this->db->where('application_id',3);
	$user_app_role = $this->db->get('users_application')->row();
	
	$app_role_id = $user_app_role->application_role_id; 

	$wp_company_id = $user->company_id;

	$this->db->select("wp_company.*,wp_file.*");
	$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->db->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->db->get('wp_company')->row();

	//print_r($wpdata);
	$main_url = 'http://'.$wpdata->url;
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
.request-count-color {
    color: <?php echo $colour_two; ?> !important;
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
#project_list_view table tbody td{border-color: <?php echo $colour_two; ?> !important;}

#cal-header{
		background-color: <?php echo $colour_one; ?>;
}
#cal-body th {
		background-color: <?php echo $colour_two; ?> !important;
		color: white;
		font-size: 84%;
		text-align: center;
	}
.notf_number{
 	background-color: <?php echo $colour_two; ?> !important;
    border-radius: 15px;
    float: left;
    left: -25px;
    line-height: 25px;
    position: relative;
    width: 25px;
	margin-top:1px;
	font-size:12px;
}
.notf_text{
	background-color: <?php echo $colour_two; ?> !important;
    float: left;
    font-size: 12px;
    line-height: 25px;
    margin-left: -25px;
    padding-left: 5px;
    padding-right: 5px;
	margin-top:1px;
}
#request_list_view th a, #request_list_view th, .searchbox .clickdiv, .add-button, .breadcrumb a, #project-detail #button_wrapper a, #table-company .table-bordered td:nth-child(1), #project-detail h4, #note_form_date, .note_email label, .note-email-date {
    color: #000 !important;
}
#table-company .description {
    color: <?php echo $colour_one; ?>;
}

.modal .modal-header .close, .modal .modal-header .close:hover{background-color:<?php echo $colour_one; ?>;}

.app-name {
    font-size: 18px;
    margin-top: 0;
    color: <?php echo $colour_two; ?>;
}
.username {
    color: <?php echo $colour_one; ?> !important;
}
#project_list_view table {
    border: 1px solid <?php echo $colour_one; ?>;
}
</style>  

</head>
<body>
<?php free_trial_banner(); ?>
<div id="wrapper">
<div class="header">
    <div class="container-fluid">
        <div class="logo">
	        <a class="" href="<?php echo base_url();?>">
	            <img src="<?php echo $logo;?>" height="67" title="TMS" alt="TMS" />
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
               		<h5 class="app-name">Task Management System</h5>
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

				<?php if($this->session->userdata('client')->id==11) { ?><li class=<?php  if($this->uri->segment(1)=="company") echo "active" ?>><?php echo anchor('company/company_list','Companies',array('class'=>'company_list')); ?></li> <?php } ?>

                <li class=<?php  if($this->uri->segment(1)=="project") echo "active" ?>><?php echo anchor('project/project_list','Projects',array('class'=>'project_list')); ?></li>  
	
                <li class=<?php  if($this->uri->segment(1)=="request") echo "active" ?>>
				<?php echo anchor('request/request_list', 'Tasks',array('class'=>'request')); ?>
				<?php if($app_role_id == 1){?>
					<!-- <div class="notf_number"><a href="<?php echo base_url() ?>task/pending_tasks">5</a></div>
					<div class="notf_text"><a href="<?php echo base_url() ?>task/pending_tasks">Pending Tasks</a></div> -->
				<?php } ?>
				</li>
				<li class=<?php  if($this->uri->segment(1)=="calendar") echo "active" ?>><?php echo anchor('calendar', 'Calendar',array('class'=>'request')); ?></li>               				  
                <li id="dropdown-right" class="dropdown <?php  if($this->uri->segment(1)=='report') echo 'active'; ?>">
				<?php echo anchor('report/report_list','Reports <span class="caret"></span>',array('class'=>'dropdown-toggle report_list', 'data-toggle'=>'dropdown')); ?>
                	<ul class="dropdown-menu">
                		<li class=<?php  if($this->uri->segment(1)=="report" && $this->uri->segment(2)!="report_project") echo "active" ?>><?php echo anchor('report/report_list','Task Overview',array('class'=>'report_list')); ?></li>
                		<li class=<?php  if($this->uri->segment(1)=="notes") echo "active" ?>><?php echo anchor('notes/notes_list', 'Notes Report',array('class'=>'notes')); ?></li>
						<li class=<?php  if($this->uri->segment(2)=="report_project") echo "active" ?>><?php echo anchor('report/report_project','Project Overview',array('class'=>'report_list')); ?></li>
					</ul>
                </li>
                
                <?php //$user_role_id =$user->rid; 
                //if($user_role_id==1){ ?>
                <!----<li class=<?php  if($this->uri->segment(1)=="user") echo "active" ?>><?php echo anchor('user/user_list','User',array('class'=>'user_list')); ?></li>--->
                <?php //} ?>
                <!--<li><?php /*echo anchor('user/user_logout','Logout',array('class'=>'user')); */?></li>-->
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