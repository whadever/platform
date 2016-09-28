<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Construction Management System - <?php if (isset($title)) {echo $title;} ?></title>

	<link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/gif">	

	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap-select.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css" type="text/css" media="screen" />
	<!-- start: Modal -->
	<link href="<?php echo base_url();?>css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
	<link href="<?php echo base_url();?>css/select2.min.css" rel="stylesheet" type="text/css"/>
	<!-- end: Modal -->
	<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.css">
	<link rel="stylesheet" href="<?php echo base_url();?>css/flexslider.css" type="text/css" media="screen" />
	<!--<link rel="stylesheet" type="text/css" href="<?php /*echo base_url();*/?>css/datepicker.css"/>-->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">


	<!--<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>-->
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.11.3.min.js"></script><script type="text/javascript" src="<?php echo base_url();?>js/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.mtz.monthpicker.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-select.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.dcaccordion.2.7.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.chained.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/custom.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/new.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/timeout.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.flexslider-min.js"></script>
	<!-- start: Date Picker -->
	
	<script>
		jQuery(document).ready(function() {
	        $( "#milestone-date,#milestone_date,#planned_start_date,#planned_finished_date,#task_start_date,#construction_start_date,#maintainence_bond_date" ).datepicker({
			    changeMonth: true,//this option for allowing user to select month
			    changeYear: true, //this option for allowing user to select from year range
				format: 'dd-mm-yyyy'
		    });
	    });
	</script>
	<!-- end: Date Picker -->
        
	<!-- start: Modal -->
	<script src="<?php echo base_url();?>js/bootstrap-modal.js"></script>
	<script src="<?php echo base_url();?>js/bootstrap-modalmanager.js"></script>
	<script src="<?php echo base_url();?>js/ui-modals.js"></script>
	<script>
		jQuery(document).ready(function() {
			UIModals.init();

		});
		/*dropdown change job box*/
		function toggle_visibility(id) {
			$("#"+id).slideToggle();
			/*var e = document.getElementById(id);
			if (e.style.display == 'none' || e.style.display=='') e.style.display = 'block';
			else e.style.display = 'none';*/
		}

	</script>
	<!-- end: Modal -->
	<?php
	$user = $this->session->userdata('user');
	$wp_company_id = $user->company_id;
	$sql = "select ar.application_role_id, LOWER(ar.application_role_name) role
                from application a LEFT JOIN users_application ua ON a.id = ua.application_id
                     LEFT JOIN application_roles ar ON ar.application_id = a.id AND ar.application_role_id = ua.application_role_id
                where ua.user_id = {$user->uid} and a.id = 5 limit 0, 1";
	$user_app_role = $this->db->query($sql)->row()->role;
	$application_role_id = $this->db->query($sql)->row()->application_role_id;
	?>
	<script>
		var pre_construction_page= "<?php echo $_COOKIE['pre_construction_page']; ?>";
		var construction_page = "<?php echo array_key_exists('construction_page',$_COOKIE)? $_COOKIE['construction_page'] : ''; ?>";
		jQuery(document).ready(function() {
			$("#pre_construction").click(function(){
				if(pre_construction_page != ""){
					window.location = pre_construction_page;
				}
			});
			$("#construction").click(function(){
				if(construction_page != ""){
					window.location = construction_page;
				}
			})
		});
	</script>
        
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox.js?v=2.1.5"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.fancybox.css?v=2.1.5" media="screen" />
	<script>

		jQuery(document).ready(function() {
			$( "#job" ).click(function() {
				$( "#job_submenu" ).toggle( "slow" );
			});

			$( "#template" ).click(function() {
				$( "#template_submenu" ).toggle( "slow" );
			});

			$( "#list" ).click(function() {
				$( "#list_submenu" ).toggle( "slow" );
			});


		});

	</script>

	<script type="text/javascript">
		$(document).ready(function() {
			$(".fancybox").fancybox({
				maxWidth	: 1000,
				maxHeight	: 850,
				fitToView	: true,
				width		: '70%',
				height		: '70%'
				//autoSize	: true
				//closeClick	: false,
				//openEffect	: 'none',
				//closeEffect	: 'none'
			});

			/*showing clock*/
			startTime();
		});
	</script>
	<script>
		var days = ['Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday'];
		var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
		function startTime() {
			var today = new Date();
			var h = today.getHours();
			var m = today.getMinutes();
			var d = days[today.getDay()];
			var dat = today.getDate();
			var mon =  months[today.getMonth()];
			var y = today.getFullYear();

			m = checkTime(m);
			h = checkTime(h);
			dat = checkTime(dat);

			var clock = h + ":" + m + " | " + d + " | "+dat+" "+mon+" "+y;
			document.getElementById('clock').innerHTML = clock;

			var t = setTimeout(startTime, 500);
		}
		function checkTime(i) {
			if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
			return i;
		}
	</script>

</head>
<body>
    <?php 

	$this->db->select("wp_company.*,wp_file.*");
	$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->db->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->db->get('wp_company')->row();

	//print_r($wpdata);
	$main_url = 'https://'.$wpdata->url;
	$colour_one = $wpdata->colour_one;
	$colour_two = $wpdata->colour_two;
	$logo = 'https://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

	echo "<script> var color_one = '{$colour_one}', color_two = '{$colour_two}'; </script>";
?>
<style>

.report-button a{
	margin-top: 20px;
	background-color: <?php echo $colour_two;?>;
	border-color: <?php echo $colour_two;?>;
	color: #fff;
}
.report-button a:hover{
	background-color: <?php echo $colour_two;?>;
	border-color: <?php echo $colour_two;?>;
	color: #fff;
}
.send-email{
	background-color: <?php echo $colour_two;?>;
	border-color: <?php echo $colour_two;?>;
	color: #fff;
}
.send-email:hover{
	background-color: <?php echo $colour_two;?>;
	border-color: <?php echo $colour_two;?>;
	color: #fff;
}
#cons_menu .con_menu div a {
    background-color: <?php echo $colour_one;?>;
}
#second_level div div div a {
    background-color: <?php echo $colour_one;?>;
}
/*css for job menu*/
#job_menu a {
	background-color: <?php echo $colour_one;?>;
}
#job_menu .selected {
	background-color: whitesmoke;
}
/*******************/
.wpconicon {
	background: <?php echo $colour_one;?>;
	border-radius: 12px;
	float: right;
}

.changejobbtn{ background-color: <?php echo $colour_two;?>;}
.footer {
    border-top: 2px solid <?php echo $colour_two;?>;
	margin-top: 20px;
}
.modal-scrollable .modal.stage-modal {
    border: 4px solid <?php echo $colour_one;?>;
}
.modal.stage-modal .modal-header {
    background: <?php echo $colour_one;?> none repeat scroll 0 0;
}
.modal.stage-modal .modal-header .close {
    color: #FFF;
}
.devphotoLeft{border:5px solid  <?php echo $colour_one;?>;}
.flexslider .flex-control-thumbs {border: 10px solid <?php echo $colour_two;?>;}
.userme {background: <?php echo $colour_two;?> none repeat scroll 0 0;}
.useranother{background: <?php echo $colour_one;?>;}
.document-detail {border: 4px solid <?php echo $colour_two;?>;}

a {color: <?php echo $colour_two;?>;}
#cal-header {background-color: <?php echo $colour_one;?>;}
#cal-body th {background-color: <?php echo $colour_two;?>}
#cal-body td.today {border: 2px solid <?php echo $colour_one;?>;}
.day-number {color: <?php echo $colour_one;?>;}
.status-ontheway{color: <?php echo $colour_two;?>}
div.jquery-gdakram-tooltip div.content {border: 5px solid <?php echo $colour_one;?>;}
#my-tasks{color:<?php echo $colour_two;?>;}
.date-header {background-color: <?php echo $colour_one;?>;}
.date-body th {background-color: <?php echo $colour_two;?>;}
.popup_title {background-color: <?php echo $colour_two;?>;}
.black_bar {background-color: <?php echo $colour_two;?>;}
.start-over a {background: <?php echo $colour_two;?> none repeat scroll 0 0;}
.development-start a {background: <?php echo $colour_two;?> none repeat scroll 0 0;}
.nav-pills.unit-nav > li.active > a, .nav-pills.unit-nav > li.active > a:hover, .nav-pills.unit-nav > li.active > a:focus {
    background-color: <?php echo $colour_one;?>;}
    
.flexslider {
    -webkit-box-shadow: 0 1px 4px <?php echo $colour_one;?>;
    -moz-box-shadow: 0 1px 4px <?php echo $colour_one;?>;
    -o-box-shadow: 0 1px 4px <?php echo $colour_one;?>;
    box-shadow: 0 1px 4px <?php echo $colour_one;?>;
}
.flexslider .flex-control-thumbs {
    -webkit-box-shadow: 0 1px 4px <?php echo $colour_one;?>;
    -moz-box-shadow: 0 1px 4px <?php echo $colour_one;?>;
    -o-box-shadow: 0 1px 4px <?php echo $colour_one;?>;
    box-shadow: 0 1px 4px <?php echo $colour_one;?>;
}
.flex-control-thumbs11 {border: 10px solid <?php echo $colour_two;?>;}
.title {
	background-color: <?php echo $colour_one;?>;
	border-radius: 14px;
	color: white;
	padding: 10px;
	text-align: center;
	font-weight: bold;
}
/*for mobile and tablet task: 4193 */
.header-top {
	display: flex;
	color: white;
	/*height: 78px;*/
	margin-bottom: 26px;
}
.cons_logo img {
	width: 260px;
}
@media (max-width: 768px) {
	.header-top {
		display: block;
	}
	.header-top .col-xs-12:nth-child(2), .header-top .col-xs-12:nth-child(3) {
		margin-top: 10px;
	}

	#top_title{
		margin-top: 12px;
	}
}

@media (max-width: 980px) {
	.cons_logo img {
		width: 170px;
	}
}

/*--
@media (max-width: 1500px) {
	.menu ul li a img {
		height: 24px;
		width: 24px;
	}
	.menu ul li a p {
		font-size: 65%;
	}
}
@media (max-width: 1600px) {
	.menu ul li a img {
		height: 28px;
		width: 28px;
	}
	.menu ul li a p {
		font-size: 75%;
	}
}
@media (max-width: 1400px) {
	.menu ul li a img {
		height: 26px;
		width: 26px;
	}
	.menu ul li a p {
		font-size: 70%;
	}
}
@media (max-width: 1366px) {
	.menu ul li a img {
		height: 22px;
		width: 22px;
	}
	.menu ul li a p {
		font-size: 65%;
	}
}
@media (max-width: 1280px) {
	.menu ul li a img {
		height: 20px;
		width: 20px;
	}
	.menu ul li a p {
		font-size: 60%;
	}
}
@media (max-width: 1024px) {
	.menu ul li a img {
		height: 16px;
		width: 16px;
	}
	.menu ul li a p {
		font-size: 45%;
	}
}

@media (max-width: 800px) {
	.menu ul li a img {
		height: 18px;
		width: 18px;
	}
	.menu ul li a p {
		font-size: 50%;
	}
}

@media (max-width: 767px) {
	.menu ul li a img {
		height: 36px;
		width: 36px;
	}
	.menu ul li a p {
		font-size: 12px;
	}
	.menu ul li {
		width: 18.5%;
	}
}*/
.menu ul.img_manu li {
  	height: auto;
    /* height: 50px; */
    margin-bottom: 7px;
    width: 32%;
    margin-right: 1%;
    border-bottom: 2px solid #fff;
}
.menu ul.img_manu li a img {
    height: 36px;
    max-width: 100%;
}
.menu ul.img_manu li a p {
    margin: 7px 0 7px;
    font-size: 12px;
}
.menu ul.img_manu li.active, .menu ul.img_manu li:hover {
    border-bottom: 2px solid #0e3f6e;
}

@media (max-width: 1324px) {
	.wpconicon img {
		width: 36px;
		height: 36px;
	}
}
/* task #4193 end*/

.header-top > div {
	margin: auto;
}
#left-bar {
	background-color: <?php echo $colour_one;?>;
	border-radius: 20px;
	margin-top: 22px;
	padding: 12px;
}
#hideMe {
	border: 6px solid  <?php echo $colour_one;?>;
	display: none;
	height: auto;
	left: 0;
	position: absolute;
	top: 74%;
	width: 100%;
	z-index: 10;
}
</style>
<div class="container-fluid">
		<div class="row header-top">
	        <div class="col-xs-5 col-sm-3 col-md-3 header-top-left">
				<div class="cons_logo">
					<a href="<?php  echo base_url(); ?>constructions/construction_overview/<?php echo $_SESSION[$_SERVER['SERVER_NAME']]['current_job']; ?>?cp=construction">
					<img width="" src="<?php echo $logo;?>" />
					</a>
				</div>
	        </div>
			<div class="col-xs-7 col-sm-3 col-md-3 visible-xs">

				<a class="wpconicon"  href="<?php echo base_url();?>user/user_logout"  style="margin-left: 3px;" >
					<img src="<?php  echo base_url().'icon/Log Out.png';?>">
				</a>
				<?php if(isset($user_app_role) && $user_app_role == 'admin'): ?>
					<a class="wpconicon"  style="margin-left: 3px;" href="<?php echo site_url('report/user_log'); ?>">

						<img src="<?php  echo base_url().'icon/history.png';?>">
					</a>
				<?php endif; ?>
				<a class="wpconicon"  style="margin-left: 3px;" href="<?php echo "https://".$_SERVER['SERVER_NAME']; ?>">

					<img src="<?php  echo base_url().'icon/btn_home.png';?>">
				</a>
				<?php if($application_role_id != '5' && $application_role_id != '3'){ ?>
				<a class="fancybox wpconicon" data-fancybox-type="iframe" href="<?php echo base_url() ?>job/show_popup_menu">

					<img src="<?php  echo base_url().'icon/Plus.png';?>">
				</a><?php } ?>
				<!---<a class="profile" href="<?php  echo base_url().'user/user_detail/'.$user->uid; ?>"><span><?php echo $user->username;?></span><img src="<?php echo base_url();?>images/btn_cap.png" height="35" title="<?php echo $user->username; ?>" alt="<?php echo $user->username; ?>" /> </a>--->

			</div>
			<div class="col-xs-12 col-sm-6 col-md-6">
				<?php if(isset($development_details)): ?>
					<?php $type = ($development_details->is_unit) ? "Unit" : "Job"; ?>
					<div class="title" style="position: relative">
						<?php echo $type; ?> #<?php echo $development_details->job_number.": &nbsp;";  echo $development_details->development_name;  ?>
						<button style="position: absolute; right: 7px; top: 2px; background: transparent none repeat scroll 0% 0%; border: medium none;" class="btn btn-default dropdown-toggle" type="button" onclick="toggle_visibility('hideMe')"><span class="caret" style="color: white"></span></button>
						<div id="hideMe">
							<iframe src="<?php echo site_url('job/change_job'); ?>" style="border: none; width: 100%; height: 400px">

							</iframe>
						</div>
					</div>
				<?php endif; ?>
			</div>
	        <div class="col-xs-12 col-sm-3 col-md-3 hidden-xs">

				<a class="wpconicon"  href="<?php echo base_url();?>user/user_logout"  style="margin-left: 3px;" >
					<img src="<?php  echo base_url().'icon/Log Out.png';?>">
				</a>
				<?php if(isset($user_app_role) && $user_app_role == 'admin'): ?>
					<a class="wpconicon"  style="margin-left: 3px;" href="<?php echo site_url('report/user_log'); ?>">

						<img src="<?php  echo base_url().'icon/history.png';?>">
					</a>
				<?php endif; ?>
				<a class="wpconicon"  style="margin-left: 3px;" href="<?php echo "https://".$_SERVER['SERVER_NAME']; ?>">
	                
					<img src="<?php  echo base_url().'icon/btn_home.png';?>">
				</a>
				<?php if($application_role_id != '5' && $application_role_id != '3'){ ?>
				<a class="fancybox wpconicon" data-fancybox-type="iframe" href="<?php echo base_url() ?>job/show_popup_menu">

					<img src="<?php  echo base_url().'icon/Plus.png';?>">
				</a><?php } ?>
	            <!---<a class="profile" href="<?php  echo base_url().'user/user_detail/'.$user->uid; ?>"><span><?php echo $user->username;?></span><img src="<?php echo base_url();?>images/btn_cap.png" height="35" title="<?php echo $user->username; ?>" alt="<?php echo $user->username; ?>" /> </a>--->
	        	
			</div>
		</div>

<div class="main-body row">
	<div class="col-xs-12 col-sm-3 col-md-2 menu">
		<ul class="img_manu">
			<li class="<?php if($this->uri->segment(1)=="job" || $this->uri->segment(1)=="constructions" && $this->uri->segment(2)!="construction_overview_all_jobs") echo "active" ?>"><a href="<?php  echo base_url(); ?>constructions/construction_overview/<?php echo $_SESSION[$_SERVER['SERVER_NAME']]['current_job']; ?>?cp=construction"><img src="<?php echo base_url();?>images/job.png" /><p>Job</p></a></li>
			<?php if($application_role_id != '5' && $domain != 'horncastle.wclp.co.nz' && $application_role_id != '3' && $application_role_id != '4'): ?>
			<li class="<?php if($this->uri->segment(1)=="contact" || $this->uri->segment(1)=="company") echo "active" ?>"><a href="<?php  echo base_url(); ?>contact/"><img src="<?php echo base_url();?>images/contact.png" /><p>Contact</p></a></li>
			<?php endif; ?>
			<!-- <li class="<?php if($this->uri->segment(1)=="task") echo "active" ?>"><a href="#"><img src="<?php echo base_url();?>images/task.png" /><p>Task List</p></a></li> -->
			<?php if($application_role_id != '5' && $domain != 'horncastle.wclp.co.nz'): ?>
			<li class="<?php if($this->uri->segment(1)=="calendar") echo "active" ?>"><a href="<?php  echo base_url(); ?>calendar/"><img src="<?php echo base_url();?>images/calender.png" /><p>Calendar</p></a></li>
			<?php endif; ?>
			<?php if($application_role_id != '5' && $domain != 'horncastle.wclp.co.nz'): ?>
			<li class="<?php if($this->uri->segment(2)=="my_tasks") echo "active" ?>"><a href="<?php  echo base_url(); ?>overview/my_tasks/"><img src="<?php echo base_url();?>images/task.png" /><p>Task List</p></a></li>
			<?php endif; ?>
			<!--<li class="<?php if($this->uri->segment(1)=="info_lib") echo "active" ?>"><a href="#"><img src="<?php echo base_url();?>images/info_lib.png" /><p>Information<br>Library</p></a></li>
					<li class="<?php if($this->uri->segment(1)=="report") echo "active" ?>"><a href="<?php  echo base_url(); ?>#"><img src="<?php echo base_url();?>images/report.png" /><p>Reports</p></a></li>-->
					
		    <?php if($application_role_id != '5' && $application_role_id != '3' && $application_role_id != '4'): ?>
			<li class="<?php if($this->uri->segment(1)=="constructions" && $this->uri->segment(2)=="construction_overview_all_jobs") echo "active" ?>">
				<a href="<?php echo base_url(); ?>constructions/construction_overview_all_jobs"><img src="<?php echo base_url();?>images/job.png" /><p>All Jobs</p></a>
			</li>
			<?php endif; ?>
			
			<?php if($wp_company_id!="34"){ ?>
			<?php if($application_role_id != '5' && $application_role_id != '3' && $application_role_id != '4'): ?>
			<li class="<?php if($this->uri->segment(1)=="report") echo "active" ?>">
				<a target="_blank" href="<?php  echo base_url(); ?>report/all_job_report"><img src="<?php echo base_url();?>images/icons/Reporting_Icon.png" /><p>Reports</p></a>
			</li>
			<?php endif; ?>
			<?php } ?>
		</ul>
		<div class="clear"></div>
		<div id="left-bar">
			<?php if($this->router->fetch_class() == 'constructions' || $this->router->fetch_class() == 'job' || $this->router->fetch_class() == 'report'): ?>
				<?php $this->load->view('includes/job_menu', array('latest_job' => $_SESSION['current_job'])); ?>
			<?php endif; ?>
			<?php if($this->router->fetch_class() == 'contact' || $this->router->fetch_class() == 'company'): ?>
				<div style="text-align: center; color: white; min-height: 400px; padding-top: 100px;">
					<img src="<?php echo site_url('images/contact-big.png'); ?>">
					<h2>Contact</h2>
				</div>
			<?php endif; ?>
			<?php if($this->router->fetch_class() == 'calendar'): ?>
				<div style="text-align: center; color: white; min-height: 400px; padding-top: 100px;">
					<img style="width: 85%;" src="<?php echo site_url('images/calendar-big.png'); ?>">
					<h2>Calendar</h2>
				</div>
			<?php endif; ?>
			<?php if($this->router->fetch_class() == 'overview'): ?>
				<div style="text-align: center; color: white; min-height: 400px; padding-top: 100px;">
					<img src="<?php echo site_url('images/task-big.png'); ?>">
					<h2>Task List</h2>
				</div>
			<?php endif; ?>
			<?php if(isset($development_details) && $development_details->bcn_number): ?>
					<div style="color: white; margin-top: 40px">BCN: <?php echo $development_details->bcn_number; ?></div>
			<?php endif; ?>
			<?php if($this->uri->segment(1) == 'report' && $this->uri->segment(2)=='user_log'): ?>
				<div style="text-align: center; color: white; min-height: 400px; padding-top: 100px;">
					<img src="<?php echo site_url('images/history-big.png'); ?>">
					<h2>User Log</h2>
				</div>
			<?php endif; ?>
			<div id="clock">

			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-9 col-md-10">
		<?php if(isset($title)): ?>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
				<div class="title" style="margin-bottom: 12px">
					<?php echo $title; ?>
				</div>
			</div>
		</div>
		<?php endif; ?>
		<!--main content-->