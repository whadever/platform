<!DOCTYPE html>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Horncastle Developments - <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="stylesheet" href="<?php echo base_url();?>css/style.css" type="text/css" media="screen" />
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
        
         <!--<script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.js"></script>
         <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery-ui.css"/> -->

	 <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
	<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
            
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
    <?php $user=  $this->session->userdata('user'); ?>
<div id="wrapper">
<div class="header">
    <div class="container">
        <div class="logo">
            <a class="brand" href="<?php echo base_url();?>developments/developments_list">
                <img src="<?php echo base_url();?>images/btn_home.png" height="67" title="Home" alt="Home" />
             </a>
            <a class="brand" onclick="window.history.go(-1)">
                <img src="<?php echo base_url();?>images/btn_up.png" height="67" title="Back" alt="Back" />
            </a>
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
               <h3 align="center"> <?php if (isset($title)) {echo $title;} ?> </h3>

        </div>

        <div class="header-top-right" style="">
            <a href="<?php  echo base_url().'user/user_detail/'.$user->uid; ?>"> <img src="<?php echo base_url();?>images/btn_cap.png" height="67" title="<?php echo $user->name; ?>" alt="<?php echo $user->name; ?>" /> </a> 


            <a href="<?php echo base_url();?>user/user_logout"> <img src="<?php echo base_url();?>images/btn_power.png" height="67" title="Logout" alt="Logout" /></a>

        </div>
        <div class="clear"></div>
         

    </div>  
</div> 
<div class="container main-body">       
<div class="content">
