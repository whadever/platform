<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Consent Management System  -  <?php if (isset($title)) {echo $title;} ?></title>
	
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	
	<!-- Style Sheets -->
	<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css" type="text/css" media="screen" />
	
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css"/>
	<link rel="stylesheet" href="<?php echo base_url();?>css/print.css" type="text/css" media="print"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/gantti.css"/>
        
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.11.3.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.form.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" src="<?php echo base_url();?>js/dataTables.fixedHeader.js"></script>
	<script type="text/javascript" src="<?php echo base_url(); ?>js/dataTables.fixedColumns.min.js"></script>
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
	<script type="text/javascript" src="<?php echo base_url();?>js/timeout.js"></script>
		
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
                

<?php 
	$this->ums = $this->load->database('ums', TRUE);

	$user = $this->session->userdata('user'); 
	$wp_company_id = $user->company_id;

	$this->ums->select("wp_company.*,wp_file.*");
	$this->ums->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->ums->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->ums->get('wp_company')->row();

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
    border-bottom: 2px solid <?php echo $colour_two; ?> !important;
}
.permission-submenu {
    border: 2px solid <?php echo $colour_two; ?> !important;;
}
#maincontent {
    border: 2px solid <?php echo $colour_two; ?> !important;
	min-height: 450px;
}
.cms_tabs table tbody td {
    border: 0px solid <?php echo $colour_two; ?> !important;
    background: <?php echo $colour_one; ?> !important;
    border-radius: 15px;
}
.cms_tabs table tbody td.active {
    border: 0px solid <?php echo $colour_two; ?> !important;
    background: <?php echo $colour_two; ?> !important;
    border-radius: 15px;
}
.cmstabs {
    color: #fff;
}
.permission_box {
    border: 2px solid <?php echo $colour_two; ?> !important;
}
.permission_arrow {
    background-color: <?php echo $colour_two; ?> !important;
}

.footer {
    border-top: 2px solid <?php echo $colour_two; ?> !important;
	color: <?php echo $colour_one; ?> !important;
}
.select-permission {
    float: left;
    width: 100%;
    margin: 20px auto;
    text-align: center;
}
.select-permission select {
    width: 240px;
    border: 1px solid <?php echo $colour_one; ?>;
    border-radius: 6px;
	padding: 5px 5px;
	color:<?php echo $colour_one; ?>;
}
</style>                 
       
</head>
<body>
    <?php $user = $this->session->userdata('user'); $user_role_id =$user->rid; ?>
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
		
				<div class="brand" style="float:left; width:480px">
					<img src="<?php echo $logo; ?>" height="70" title="Consent Management System" alt="Consent Management System" /> 	
				</div>
				<!--<div style="color: #213445;float: right;font-size: 25px;padding-right: 20px;padding-top: 25px;text-align: right;width: 380px;">
					<i>We call Canterbury home</i>
				</div>-->
				<?php
				$this->db->order_by('id', 'DESC');
				$row = $this->db->get('system_update')->row();
				?>
           		<div style="color: #213445;float: right;font-size: 25px;padding-right: 20px;padding-top: 25px;text-align: right;width: 380px;">
					<i>System Updated <?php echo date("d/m/Y", strtotime($row->date)); ?></i>
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

        <div class="header-top-right" style="text-align:right;">
            <!---<a href="<?php  echo base_url().'user/user_detail/'.$user->uid; ?>"> <img src="<?php echo base_url();?>images/btn_cap.png" height="67" title="<?php echo $user->username; ?>" alt="<?php echo $user->username; ?>" /> </a> --->
			

			<?php if($this->uri->segment(2)=='consent_list'){ ?>
            <a id="logout" class="brand-right" onClick="if(($('#cmemory').val() == 1) && confirm('Are you sure you want to exit without saving your data?')){return true}
			else if(($('#cmemory').val() == '') && confirm('Are you sure you want to exit?')){return true}
			else {return false}" href="<?php echo base_url();?>user/user_logout"> <img src="<?php echo base_url();?>images/btn_power.png" height="67" title="Logout" alt="Logout" /></a>
			<?php }else{ ?>
			<a id="logout" class="brand-right" href="<?php echo base_url();?>user/user_logout"> <img src="<?php echo base_url();?>images/btn_power.png" height="67" title="Logout" alt="Logout" /></a>
			<?php } ?>

			<?php 
			if($user_role_id==2){ 
			$user_group_id =  explode(',',$this->session->userdata('user_group_id')); 
			$group = explode(',',$user->group_id);
			?>
			<ul class="permission-menu brand-right">
				<li>
					<a href="#"><img id="clickimg" src="<?php echo base_url();?>images/PermissionsIcon.png" height="63" /></a>
					<ul class="permission-submenu">
						<li>View:</li>
						<?php
						$this->db->where_in('id',$group);
						$rows = $this->db->get('groups')->result();
						foreach($rows as $row){
						?>
						<li onclick="userChangePermission(<?php echo $row->id.','.$user->uid; ?>)"><?php echo $row->group_name; ?><span><?php if(in_array($row->id,$user_group_id)){ echo '<img src="'.base_url().'images/CurrentPermissionIcon.png" height="13" />'; } ?></span></li>
						<?php
						}
						?>
					</ul>
				</li>
			</ul>
			<?php } ?>
			<script>
				window.Url = "<?php print base_url(); ?>";
				function userChangePermission(group_id,user_id){
					$.ajax({
						url: window.Url + 'welcome/userChangePermission/' + group_id,
						type: 'POST',
						success: function(data) 
						{
							window.location.reload();
						},		        
					});
				}
			
			jQuery(document).ready(function()
			{
				
				 $('#clickimg').click(function(){
			        $('.permission-submenu').slideToggle();
			    });
			
			});
			</script>

        </div>
        <div class="clear"></div>
         

    </div>  
</div> 
<div class="container main-body">       
<div class="content">
