<!DOCTYPE html>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Development System - <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<!--<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">-->

		<link rel="stylesheet" href="<?php echo base_url();?>css/style.css" type="text/css" media="screen" />
        <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css"/>
        <link rel="stylesheet" href="<?php echo base_url();?>css/print.css" type="text/css" media="print"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/gantti.css"/>
		
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/fuzzyDropdown.css"/>
		
		<!-- Mmenu start-->
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>css/jquery.mmenu.css" />
		<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>css/dragopen/jquery.mmenu.dragopen.css" />

		
		<script type="text/javascript" src="https://hammerjs.github.io/dist/hammer.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.mmenu.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>css/dragopen/jquery.mmenu.dragopen.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>css/fixedelements/jquery.mmenu.fixedelements.min.js"></script>
		<script type="text/javascript">
			$(function() {
				var $menu = $('nav#menu'),
					$html = $('html, body');

				$menu.mmenu({
					dragOpen: true
				});

				var $anchor = false;
				$menu.find( 'li > a' ).on(
					'click',
					function( e )
					{
						$anchor = $(this);
					}
				);

				var api = $menu.data( 'mmenu' );
				api.bind( 'closed',
					function()
					{
						if ( $anchor )
						{
							var href = $anchor.attr( 'href' );
							$anchor = false;

							//	if the clicked link is linked to an anchor, scroll the page to that anchor 
							if ( href.slice( 0, 1 ) == '#' )
							{
								$html.animate({
									scrollTop: $( href ).offset().top
								});	
							}
						}
					}
				);
			});
		</script>
		<!--Mmenu End-->

        <script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
        
          <!-- start: Modal -->
		<link href="<?php echo base_url();?>css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
		<link href="<?php echo base_url();?>css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
		<!-- end: Modal -->
        
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery.mtz.monthpicker.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url();?>js/custom.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/new.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>
         <!--<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
         <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery-ui.css"/> 

	 	<link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
		<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>-->
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.3/themes/smoothness/jquery-ui.css">
  		<script src="//code.jquery.com/ui/1.11.3/jquery-ui.js"></script>
        
        <link rel="stylesheet" href="<?php echo base_url();?>css/flexslider.css" type="text/css" media="screen" />
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery.flexslider.js"></script>

		<!-- start: Date Picker 

		<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-datepicker.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datepicker.css"/>
		<script>
			jQuery(document).ready(function() {
		        $( "#milestone-date,#milestone_date,#planned_start_date,#planned_finished_date,#task_start_date,#construction_start_date,#maintainence_bond_date" ).datepicker({
				    changeMonth: true,//this option for allowing user to select month
				    changeYear: true, //this option for allowing user to select from year range
					format: 'dd-mm-yyyy'
			    });
		    });
		</script>-->
		
		
		
		
		<script>
		$(function(){ 
		    $(document).on('focus', ".live_datepicker", function(){
		        $(this).datepicker({
		            changeMonth: true,
		            changeYear: true,
		            dateFormat: 'dd-mm-yy',
					//beforeShowDay: $.datepicker.noWeekends,
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
        
		<script src="<?php echo base_url();?>js/fuse.min.js"></script>
		<script type="text/javascript" src="<?php echo base_url();?>js/fuzzyDropdown.min.js"></script>

		
 
            	 <!-- start: Modal -->
		<script src="<?php echo base_url();?>js/bootstrap-modal.js"></script>
		<script src="<?php echo base_url();?>js/bootstrap-modalmanager.js"></script>
		<script src="<?php echo base_url();?>js/ui-modals.js"></script>
		<script src="<?php echo base_url();?>js/jquery.ui.touch-punch.min.js"></script>
		
<?php
require_once 'Mobile_Detect.php';
$detect = new Mobile_Detect; 
if( $detect->isMobile() || $detect->isTablet() ){
	echo '';
}else{
?>
		<script>
			$(function(){ 
			    $(document).on('focus', ".in", function(){
			        $(this).draggable({
			            cursor: "move"
			        });
			    });
			});
			
		</script>
<?php } ?>
		<!-- end: Modal -->

		<!---- Start Menu --->
		<link href="<?php echo base_url();?>j-ver-menu/css/dcaccordion.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url();?>j-ver-menu/css/skins/blue.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url();?>j-ver-menu/css/skins/graphite.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo base_url();?>j-ver-menu/css/skins/grey.css" rel="stylesheet" type="text/css" />
		<script type='text/javascript' src="<?php echo base_url();?>j-ver-menu/js/jquery.cookie.js"></script>
		<script type='text/javascript' src="<?php echo base_url();?>j-ver-menu/js/jquery.hoverIntent.minified.js"></script>
		<script type='text/javascript' src="<?php echo base_url();?>j-ver-menu/js/jquery.dcjqaccordion.2.7.min.js"></script>
		<script type="text/javascript">
			$(document).ready(function($){
				$('#accordion-1').dcAccordion({
					eventType: 'click',
					autoClose: true,
					saveState: false,
					disableLink: true,
					showCount: false,
					speed: 'slow'
				});
			});
		</script>
		<!---- End Menu ----->

		<!---- Multi Select--->
		<link href="<?php echo base_url();?>css/fSelect.css" rel="stylesheet" type="text/css" />
		<script type='text/javascript' src="<?php echo base_url();?>js/fSelect.js"></script>

		<script type="text/javascript" src="<?php echo base_url();?>js/jquery.form.js"></script>
		
		<script type="text/javascript" src="<?php echo base_url(); ?>js/timeout.js"></script>

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
</style> 
                
<style>
.modal-header {
    cursor: move;
}
.developments-dropdown .btn-group.open {
    width: 100%;
}
.developments-dropdown .btn-group {
    width: 100%;
}
.developments-dropdown .btn.btn-default.display-none {
     background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    border: medium none;
    width: 91%;
}
.developments-dropdown .btn.btn-default.dropdown-toggle {
    background: none;
    border: none;
    box-shadow: 0 0 0 0;
    margin-top: -25px;
    color: #fff;
    padding: 0 10px 0 0;
	float: right;
}
.developments-dropdown .caret {
    border-left: 12px solid transparent;
    border-right: 12px solid transparent;
    border-top: 11px solid;
    display: inline-block;
    height: 0;
    margin-left: 2px;
    vertical-align: middle;
    width: 0;
}
.developments-dropdown .dropdown-menu {
    background-clip: padding-box;
    background-color: #fff;
    border: 5px solid #002855;
    border-radius: 0;
    box-shadow: 0 0 0 0;
    float: left;
    font-size: 15px;
    left: 0;
    list-style: outside none none;
    margin: 36px 2% 0;
    padding: 0;
    position: absolute;
    top: 100%;
    width: 96%;
    z-index: 1000;
	overflow-x: hidden;
    overflow-y: scroll;
	height: 350px;
}

.developments-dropdown .dropdown-menu > li {
    border-bottom: 3px solid #002855;
}
.developments-dropdown .dropdown-menu > li:last-child {
    border-bottom: none;
}
.developments-dropdown .dropdown-menu > li > a {
    clear: both;
    color: #333;
    display: block;
    font-weight: normal;
    line-height: 1.42857;
    padding: 5px;
    white-space: nowrap;
}
.developments-dropdown {
    margin-top: -20px;
}
.dev_search{
	display: none;
}
.dev_search {
	border-left: 5px solid #002855;
	border-top: 5px solid #002855;
	border-right: 5px solid #002855;
    background: #002855 none repeat scroll 0 0;
    clear: both;
    margin: 0 2%;
    position: absolute;
    top: 100%;
    width: 96%;
    z-index: 9999;
}
.inner_dev_search {
    background: #fff none repeat scroll 0 0;
    padding: 3px 5px;
}
.inner_dev_search input#development-name{
    border: 1px solid #ccc;
    line-height: 12px;
    width: 36%;
	padding: 4px;
}

.inner_dev_search input#report-search{
    border: 1px solid #ccc;
    line-height: 15px;
    width: 36%;
	padding: 4px;
}

@media (max-width : 1024px){
    .banar_title {
	    width: 100%;
	}
	.centerbury {
	    margin-top: -129px;
	    width: 60%;
	}
}
</style>   

<script>

window.Url = "<?php print base_url(); ?>";
jQuery(document).ready(function() {

	$("#development-name").keyup(function() {

		var selectedDevelopmentName1 = this.value;
		if(selectedDevelopmentName1)
		{
			var selectedDevelopmentName = selectedDevelopmentName1;
		}
		else
		{
			var selectedDevelopmentName = 'ZiaurRahman123';
		}
		var selectedStatusId = $("#all_open_close").val();
		var selectedLocationId = $("#location").val();

		$.ajax({
			url: window.Url + 'developments/header_change_development_status/' + selectedStatusId + '/' + selectedLocationId,
			type: 'GET',
			data: { selectedDevelopmentName2 : selectedDevelopmentName },
			success: function(data) 
			{
				//console.log(data); 	
				$('.dropdown-menu').empty();
				$('.dropdown-menu').append(data);			        
			},
		        
		});
	});

	$("#location").change(function() {

		var selectedLocationId = this.value;
		var selectedStatusId = $("#all_open_close").val();
		var selectedDevelopmentName1 = $("#development-name").val();
		if(selectedDevelopmentName1)
		{
			var selectedDevelopmentName = selectedDevelopmentName1;
		}
		else
		{
			var selectedDevelopmentName = 'ZiaurRahman123';
		}

		$.ajax({
			url: window.Url + 'developments/header_change_development_status/' + selectedStatusId + '/' + selectedLocationId,
			type: 'GET',
			data: { selectedDevelopmentName2 : selectedDevelopmentName },
			success: function(data) 
			{
				//console.log(data); 	
				$('.dropdown-menu').empty();
				$('.dropdown-menu').append(data);			        
			},
		        
		});
	});

	$("#all_open_close").change(function() {

		var selectedStatusId = this.value;
		var selectedLocationId = $("#location").val();
		var selectedDevelopmentName1 = $("#development-name").val();
		if(selectedDevelopmentName1)
		{
			var selectedDevelopmentName = selectedDevelopmentName1;
		}
		else
		{
			var selectedDevelopmentName = 'ZiaurRahman123';
		}

		$.ajax({
			url: window.Url + 'developments/header_change_development_status/' + selectedStatusId + '/' + selectedLocationId,
			type: 'GET',
			data: { selectedDevelopmentName2 : selectedDevelopmentName },
			success: function(data) 
			{
				//console.log(data); 	
				$('.dropdown-menu').empty();
				$('.dropdown-menu').append(data);			        
			},
		        
		});
	});


});
</script>

<script>

jQuery(document).ready(function() {

	$("#potential_development-name").keyup(function() {

		var selectedDevelopmentName1 = this.value;
		if(selectedDevelopmentName1)
		{
			var selectedDevelopmentName = selectedDevelopmentName1;
		}
		else
		{
			var selectedDevelopmentName = 'ZiaurRahman123';
		}
		var selectedStatusId = $("#potential_all_open_close").val();
		var selectedLocationId = $("#potential_location").val();

		$.ajax({
			url: window.Url + 'potential_developments/header_change_development_status/' + selectedStatusId + '/' + selectedLocationId,
			type: 'GET',
			data: { selectedDevelopmentName2 : selectedDevelopmentName },
			success: function(data) 
			{
				//console.log(data); 	
				$('.dropdown-menu').empty();
				$('.dropdown-menu').append(data);			        
			},
		        
		});
	});

	$("#potential_location").change(function() {

		var selectedLocationId = this.value;
		var selectedStatusId = $("#potential_all_open_close").val();
		var selectedDevelopmentName1 = $("#potential_development-name").val();
		if(selectedDevelopmentName1)
		{
			var selectedDevelopmentName = selectedDevelopmentName1;
		}
		else
		{
			var selectedDevelopmentName = 'ZiaurRahman123';
		}

		$.ajax({
			url: window.Url + 'potential_developments/header_change_development_status/' + selectedStatusId + '/' + selectedLocationId,
			type: 'GET',
			data: { selectedDevelopmentName2 : selectedDevelopmentName },
			success: function(data) 
			{
				//console.log(data); 	
				$('.dropdown-menu').empty();
				$('.dropdown-menu').append(data);			        
			},
		        
		});
	});

	$("#potential_all_open_close").change(function() {

		var selectedStatusId = this.value;
		var selectedLocationId = $("#potential_location").val();
		var selectedDevelopmentName1 = $("#potential_development-name").val();
		if(selectedDevelopmentName1)
		{
			var selectedDevelopmentName = selectedDevelopmentName1;
		}
		else
		{
			var selectedDevelopmentName = 'ZiaurRahman123';
		}

		$.ajax({
			url: window.Url + 'potential_developments/header_change_development_status/' + selectedStatusId + '/' + selectedLocationId,
			type: 'GET',
			data: { selectedDevelopmentName2 : selectedDevelopmentName },
			success: function(data) 
			{
				//console.log(data); 	
				$('.dropdown-menu').empty();
				$('.dropdown-menu').append(data);			        
			},
		        
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

	$colour_two = $wpdata->colour_one;
	$colour_one = $wpdata->colour_two;
	$logo = 'https://'.$_SERVER['SERVER_NAME'].'/uploads/logo/'.$wpdata->filename;
?>  

<style>
body {
    color: #000000 !important; /*<?php echo $colour_one; ?>*/
}
.banar_title {
    background-color: <?php echo $colour_two; ?> !important;
}
.developments-dropdown .dropdown-menu {
    border: 5px solid <?php echo $colour_two; ?> !important;
}
.dev_search {
    background: <?php echo $colour_two; ?> !important;
    border-left: 5px solid <?php echo $colour_two; ?> !important;
    border-right: 5px solid <?php echo $colour_two; ?> !important;
    border-top: 5px solid <?php echo $colour_two; ?> !important;
}
.sidebar {
    background: <?php echo $colour_two; ?> !important;
    border: 1px solid <?php echo $colour_two; ?> !important;
}
.development-home {
    border: 1px solid <?php echo $colour_two; ?> !important;
}
.development-info, .development-photo {
    border: 5px solid <?php echo $colour_two; ?> !important;
}
.box-title {
    background: <?php echo $colour_two; ?> !important;
}
#milestone_overview {
    border: 4px solid <?php echo $colour_two; ?> !important;
}
#milestone_overview h6 {
    background: <?php echo $colour_two; ?> !important;
}
.box-title1 {
    background: <?php echo $colour_two; ?> !important;
}
.flexslider .flex-control-thumbs {
    border: 10px solid <?php echo $colour_two; ?> !important;
}
#note_page {
    background: <?php echo $colour_two; ?> !important;
}
#notes_container {
    border: 1px solid <?php echo $colour_two; ?> !important;
}
#notify_user_select_box {
    border-top: 1px solid <?php echo $colour_two; ?> !important;
}
.modal .modal-header .close {
    background: <?php echo $colour_two; ?> !important;
}
#stage_milestone_overview {
    border: 4px solid <?php echo $colour_two; ?> !important;
}
#stage_milestone_overview h6 {
    background: <?php echo $colour_two; ?> !important;
}
.mile-stage-inner{
	background: <?php echo $colour_two; ?> !important;
}
.template-home .start-over a, 
.development-start a, 
.template-home .template-footer input.form-submit, 
.template-home .template-footer a.back,
.template-home .template-footer a.next,
.template-design .template-body .task-phase-inner,
.development-header .start-over > a,
.back-next .submit input,
.back-next .brand,
a.next,
.task-phase-inner,
a.back,
.photos_ga,
#note_page_header_photo,
.userme,
.bg-stage-gp
 {
    background: <?php echo $colour_two; ?> !important;
}
.color-key {
    border-bottom: 3px solid <?php echo $colour_two; ?> !important;
}
.developments-dropdown .dropdown-menu > li {
    border-bottom: 3px solid <?php echo $colour_two; ?> !important;
}
.flexslider {
    border: 5px solid <?php echo $colour_two; ?> !important;
}

#maincontent {
    border: 1px solid <?php echo $colour_two; ?> !important;
}
#date-block {
    background: <?php echo $colour_two; ?> !important;
}
.development-block {
    border-top: 1px solid <?php echo $colour_two; ?> !important;
}
.development-block .development-block-left {
    border-right: 4px solid <?php echo $colour_two; ?> !important;
}
.development-block .stage-block-left {
    border-right: 4px solid <?php echo $colour_two; ?> !important;
}
.footer {
    border-top: 2px solid <?php echo $colour_two; ?> !important;
    color: <?php echo $colour_two; ?> !important;
}
.development-info-table table {
    color: #000 !important;
}
.report-color {
    color: #000 !important;
}
</style>     
       
</head>
<body>
<?php 
$user = $this->session->userdata('user');
$ci = &get_instance();
$ci->load->model('user_model');
$user_role = $ci->user_model->user_app_role_load($user->uid);
$user_role = $user_role->application_role_id;
?>

<?php if($user_role == 2){ ?>
<style> 
.header-top-left h3 {
    margin: 8px 40px 8px 8px;
}
</style> 
<?php } ?>
<div id="wrapper">
<div class="header">
    <div class="container">
        <div class="logo">
			
            <a class="brand" href="<?php echo 'https://'.$_SERVER['SERVER_NAME']; ?>"> 
                <img src="<?php echo base_url();?>images/btn_home.png" height="67" title="Home" alt="Home" />
            </a>
			<?php if(isset($_GET['uid'])){ ?>
            <a class="brand" href="<?php echo base_url();?>welcome">
                <img src="<?php echo base_url();?>images/btn_up.png" height="67" title="Back" alt="Back" />
            </a>
			<?php }else{ ?>
			<a class="brand" onclick="window.history.go(-1)">
                <img src="<?php echo base_url();?>images/btn_up.png" height="67" title="Back" alt="Back" />
            </a>
			<?php } ?>
        </div>
        
       
            
        <div class="header-top-left" style="">
           
               <!-- <ul class="nav">
                <li class=<?php  if($this->uri->segment(1)=="overview") echo "active" ?>><?php echo anchor('overview', 'Overview',array('class'=>'overview')); ?></li>
                <li class=<?php  if($this->uri->segment(1)=="request") echo "active" ?>><?php echo anchor('request/request_list', 'Requests',array('class'=>'request')); ?></li>

                <li class=<?php  if($this->uri->segment(1)=="project") echo "active" ?>><?php echo anchor('project/project_list','Projects',array('class'=>'project_list')); ?></li>  				  
                <li class=<?php  if($this->uri->segment(1)=="report") echo "active" ?>><?php echo anchor('report/report_list','Reports',array('class'=>'report_list')); ?></li>
                <?php $user_role_id =$user->rid; 
                if($user_role_id==1){ ?>
                <li class=<?php  if($this->uri->segment(1)=="user") echo "active" ?>><?php echo anchor('user/user_list','User',array('class'=>'user_list')); ?></li>
                <?php } ?>

                </ul> -->
				<div class="hcd_logo">
					<img width="200" src="<?php echo $logo; ?>" />
				</div>
				<div class="banar_title">
					<h3 align="center"> 
						<?php if (isset($title)) {echo $title;} ?> 
						<?php $location = $this->session->userdata('location'); ?>	
					</h3>

					<?php if($user_role == 2 || $user_role == 4 || $user_role == 5){ ?>
					<div class="developments-dropdown">


					<div class="btn-group">
						<button type="button" class="btn btn-default display-none"></button>
				        <button onclick="toggle_visibility('hideMe')" type="button" class="btn btn-default dropdown-toggle"><span class="caret"></span></button>

						<?php if($this->uri->segment(1)=='report'){ ?>
						<div id="hideMe1" class="dev_search">
							<div class="inner_dev_search">
							<img height="27" width="30" alt="Home" title="Home" src="<?php echo base_url(); ?>images/search.jpg">
							<!----<input id="development-submit" type="submit" name="submit" />--->
							<input type="text" id="report-search" name="report_search" value="" placeholder="Search Report" />
							</div>
						</div>
						<ul id="hideMe" class="dropdown-menu report-section">
							<li><a href="<?php echo base_url(); ?>report">Milestone Report</a></li>
							<li><a href="<?php echo base_url(); ?>report/data_report">Data Report</a></li>
							<li><a href="<?php echo base_url(); ?>report/contractor_report">Contractor Report</a></li>
							<li><a href="<?php echo base_url(); ?>report/responsibility_report/<?php echo $user->uid; ?>">Responsibility Report</a></li>
							<li><a href="<?php echo base_url(); ?>report/under_caution_report">Under Caution Report</a></li>
						</ul>
						<?php }elseif($this->uri->segment(1)=='developments' || $this->uri->segment(1)=='stage'){ ?>
						<div id="hideMe1" class="dev_search">
							<div class="inner_dev_search">
							<img height="27" width="30" alt="Home" title="Home" src="<?php echo base_url(); ?>images/search.jpg">
							<!----<input id="development-submit" type="submit" name="submit" />--->
							<input type="text" id="development-name" name="development_name" value="<?php if(isset($get['development_name'])){ echo $get['development_name']; } ?>" placeholder="Search Development" />
							<select name="development_city" id="location">
								<option value="0">All Cities</option>
								<option <?php if($location == 'Christchurch'){echo 'selected'; } ?> value="Christchurch">Christchurch</option>
								<option <?php if($location == 'Auckland') {echo 'selected'; } ?> value="Auckland">Auckland</option>
							</select>
							<select name="all_open_close" id="all_open_close">
								<option value="0">Open</option>
								<option <?php if(isset($get['all_open_close'])){ $all_open_close = $get['all_open_close']; if($all_open_close == '2') {echo 'selected'; } } ?> value="2">All</option>
								<option <?php if(isset($get['all_open_close'])){ $all_open_close = $get['all_open_close']; if($all_open_close == '1') {echo 'selected'; } } ?> value="1">Closed</option>
							</select>
							</div>
						</div>

				        <ul id="hideMe" class="dropdown-menu">
						<?php
							$user=  $this->session->userdata('user');  
							$wp_company_id =$user->company_id;

							$dev_get = $this->uri->segment(3);
							//$query = $this->db->query("SELECT * FROM development order by development_name");
							if($location == 'Christchurch' || $location == 'Auckland'){
								$this->db->where('development_city', $location);
							}
							$this->db->where('wp_company_id',$wp_company_id);
							$this->db->where('status','0');
							$this->db->order_by('development_name', 'ASC');
							$query = $this->db->get('development');
							$rows = $query->result();
							foreach($rows as $row)
							{
						?>
							<li><a href="<?php echo base_url(); ?>developments/development_detail/<?php echo $row->id; ?>"><?php echo $row->development_name; ?></a></li>
						<?php
							}
						?>
						</ul>

					<?php }elseif($this->uri->segment(1)=='potential_developments' || $this->uri->segment(1)=='potential_stage'){ ?>

						<div id="hideMe1" class="dev_search">
							<div class="inner_dev_search">
							<img height="27" width="30" alt="Home" title="Home" src="<?php echo base_url(); ?>images/search.jpg">
							<!----<input id="development-submit" type="submit" name="submit" />--->
							<input style="padding: 4px;" type="text" id="potential_development-name" name="potential_development_name" value="<?php if(isset($get['potential_development_name'])){ echo $get['potential_development_name']; } ?>" placeholder="Search Development" />
							<select name="potential_development_city" id="potential_location">
								<option value="0">All Cities</option>
								<option <?php if($potential_location == 'Christchurch'){echo 'selected'; } ?> value="Christchurch">Christchurch</option>
								<option <?php if($potential_location == 'Auckland') {echo 'selected'; } ?> value="Auckland">Auckland</option>
							</select>
							<select name="potential_all_open_close" id="potential_all_open_close">
								<option value="0">Open</option>
								<option <?php if(isset($get['potential_all_open_close'])){ $potential_all_open_close = $get['potential_all_open_close']; if($potential_all_open_close == '2') {echo 'selected'; } } ?> value="2">All</option>
								<option <?php if(isset($get['potential_all_open_close'])){ $potential_all_open_close = $get['potential_all_open_close']; if($potential_all_open_close == '1') {echo 'selected'; } } ?> value="1">Closed</option>
							</select>
							</div>
						</div>

				        <ul id="hideMe" class="dropdown-menu">
						<?php
							$user=  $this->session->userdata('user');  
							$wp_company_id =$user->company_id;

							$dev_get = $this->uri->segment(3);
							//$query = $this->db->query("SELECT * FROM potential_development order by development_name");
							if($potential_location == 'Christchurch' || $potential_location == 'Auckland'){
								$this->db->where('development_city', $potential_location);
							}
							$this->db->where('wp_company_id',$wp_company_id);
							$this->db->where('status','0');
							$this->db->order_by('development_name', 'ASC');
							$query = $this->db->get('potential_development');
							$rows = $query->result();
							foreach($rows as $row)
							{
						?>
							<li><a href="<?php echo base_url(); ?>potential_developments/development_detail/<?php echo $row->id; ?>"><?php echo $row->development_name; ?></a></li>
						<?php
							}
						?>
						</ul>

					<?php } //end if else condition ?>

    				</div>
					</div>
					<?php
						}
					?>
				</div>
				<div class="centerbury">
					<i>We call Canterbury home</i>
				</div>

               

        </div>

        <div class="header-top-right" style="">
			<a href="<?php echo base_url();?>user/user_logout"> <img src="<?php echo base_url();?>images/btn_power.png" height="67" title="Logout" alt="Logout" /></a>
            <!---<a href="<?php  echo base_url().'user/user_detail/'.$user->uid; ?>"> <img src="<?php echo base_url();?>images/btn_cap.png" height="67" title="<?php echo $user->username; ?>" alt="<?php echo $user->username; ?>" /> </a> --->
        </div>
        <div class="clear"></div>
         
			
    </div>  
</div> 

<script>
function toggle_visibility(id) {
    var e = document.getElementById(id);
    if (e.style.display == 'none' || e.style.display=='') e.style.display = 'block';
    else e.style.display = 'none';

	var ee = document.getElementById(id+<?php echo '1'; ?>);
    if (ee.style.display == 'none' || ee.style.display=='') ee.style.display = 'block';
    else ee.style.display = 'none';
}

$(function(){

    $('#report-search').keyup(function(){
        
        var searchText = $(this).val();
        
        $('ul.report-section > li').each(function(){
            
            var currentLiText = $(this).text(),
                showCurrentLi = currentLiText.indexOf(searchText) !== -1;
            
            $(this).toggle(showCurrentLi);
            
        });     
    });

});
</script>

<div class="container main-body">       
<div class="content">
