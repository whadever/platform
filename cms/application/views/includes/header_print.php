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
        
       
        
        
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
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
        
        <link rel="stylesheet" href="<?php echo base_url();?>css/flexslider.css" type="text/css" media="screen" />
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery.flexslider-min.js"></script>

		<!-- start: Date Picker -->
		<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-datepicker.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datepicker.css"/>
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
                
               
       
</head>
<body>
    <?php $user=  $this->session->userdata('user'); $user_role_id =$user->rid;  ?>
<div id="wrapper">

<div class="container main-body">       
<div class="content">
