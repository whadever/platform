<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Consent Management System  -  <?php if (isset($title)) {echo $title;} ?></title>
	
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	
	<!-- Style Sheets -->
	<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/print.css" type="text/css" media="print"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/gantti.css"/>
        
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.11.3.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.form.js"></script>
	
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
	
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.multiselect.js"></script>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.multiselect.css"/> 
	
	<link rel="stylesheet" href="<?php echo base_url();?>css/flexslider.css" type="text/css" media="screen" />
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.flexslider-min.js"></script>

		
			 <!-- start: Modal -->
	<script src="<?php echo base_url();?>js/bootstrap-modal.js"></script>
	<script src="<?php echo base_url();?>js/bootstrap-modalmanager.js"></script>
	<script src="<?php echo base_url();?>js/ui-modals.js"></script>
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
    <?php $user = $this->session->userdata('user'); $user_role_id =$user->rid;  ?>
<div id="wrapper">
<div class="header">
    <div class="container">
        <div class="logo">
            <a class="brand" href="<?php echo base_url();?>welcome">
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
		
				<div style="float:left; width:480px">
					<img src="<?php echo base_url();?>images/cms_logo.png" height="" title="Consent Management System" alt="Consent Management System" /> 	
				</div>
				<div style="color: #213445;float: right;font-size: 25px;padding-right: 20px;padding-top: 25px;text-align: right;width: 380px;">
					<i>We call Canterbury home</i>
				</div>
           
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
               <!-- <h3 align="center"> <?php if (isset($title)) {echo $title;} ?> </h3> -->

        </div>

        <div class="header-top-right" style="">
            <a href="#"> <img src="<?php echo base_url();?>images/btn_cap.png" height="67" title="<?php echo $user->username; ?>" alt="<?php echo $user->username; ?>" /> </a> 


            <a href="<?php echo base_url();?>user/user_logout"> <img src="<?php echo base_url();?>images/btn_power.png" height="67" title="Logout" alt="Logout" /></a>

        </div>
        <div class="clear"></div>
         

    </div>  
</div> 
<div class="container main-body">       
<div class="content">
