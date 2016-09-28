<style>
.dashboard-box{
	border: 2px solid #0D446E;
	border-radius:10px;
	margin:10px 0px;
	color: #000;
}
.dashboard-box img{
    margin: 10px 0px;
    max-height: 100%;
    max-width: 100%;
}
.dashboard-box h4 {
    font-size: 12px;
    font-weight: bold;
	margin:10px 0px 5px 0px;
}
.dashboard-box .box{
	min-height:100px;
	padding-left:0px;
	padding-right:10px;
}
</style>
<div class="content allpage">
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="content-header">
				<div class="title"><?php //echo $title; ?></div>
			</div>
		</div>
	</div>
	
	 <?php $user = $this->session->userdata('user'); ?>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4">
			<div class="row dashboard-box">
				<div class="col-xs-3 col-sm-3 col-md-3">
					<img width="" height="" src="<?php echo base_url(); ?>/images/mss_hello.png" />
				</div>
				<div class="col-xs-9 col-sm-9 col-md-9 box">
					<h4>Hello <?php echo $user->username; ?>, </h4>
					<p>Welcome to the Maintenance System. This system allows you to manage your properties and the products and maintenance that goes with them.</p>
				</div>
			</div>
		</div>
		
		<div class="col-xs-12 col-sm-12 col-md-4">
			<div class="row dashboard-box">
				<div class="col-xs-3 col-sm-3 col-md-3">
					<a class="" href="<?php echo base_url(); ?>client/client_list">
						<img width="" height="" src="<?php echo base_url(); ?>/images/mss_client.png" title="Manage Properties" alt=""/>
					</a>
				</div>
				<div class="col-xs-9 col-sm-9 col-md-9 box">
					<h4>Manage your properties </h4>
					<p>Add a new property to begin the creation of their personalised maintenance schedule. </p>
				</div>
			</div>
		</div>
		
		<div class="col-xs-12 col-sm-12 col-md-4">
			<div class="row dashboard-box">
				<div class="col-xs-3 col-sm-3 col-md-3">
					<a class="" href="<?php echo base_url(); ?>product/product_list">
					<img width="" height="" src="<?php echo base_url(); ?>/images/mss_prod_warr.png"  title="Manage Product" alt=""/>
					</a>
				</div>
				<div class="col-xs-9 col-sm-9 col-md-9 box">
					<h4>Manage your Products and Warranties</h4>
					<p>Create and manage the products and warranties you use in your homes. </p>
				</div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-3">
					
				</div>
				<div class="col-xs-12 col-sm-12 col-md-9">
					
				</div>
			</div>
		</div>
		
		<div class="col-xs-12 col-sm-12 col-md-4">
			<div class="row dashboard-box">
				<div class="col-xs-3 col-sm-3 col-md-3">
					<a class="" href="<?php echo base_url(); ?>template/template_list">
					<img width="" height="" src="<?php echo base_url(); ?>/images/mss_template.png"  title="Manage Template" alt="Template"/>
					</a>
				</div>
				<div class="col-xs-9 col-sm-9 col-md-9 box">
					<h4>Manage Template </h4>
					<p>Create customisable templates from your Products and Warranties.</p>
				</div>
			</div>
		</div>
		
		<div class="col-xs-12 col-sm-12 col-md-4">
			<div class="row dashboard-box">
				<div class="col-xs-3 col-sm-3 col-md-3">
					<a class="" href="<?php echo base_url(); ?>schedule/schedule_list">
					<img width="" height="" src="<?php echo base_url(); ?>/images/mss_schedule.png"  title="Maintenance Schedule" alt="Schedule"/>
					</a>
				</div>
				<div class="col-xs-9 col-sm-9 col-md-9 box">
					<h4>Create a new Maintenance Schedule</h4>
					<p>Create beautiful and customisable Schedules for your properties.</p>
				</div>
			</div>
		</div>
	</div>
	
	<!--- 
	
	<div class="row new-schedule">
		<div class="col-xs-12 col-sm-12 col-md-10 dashboard-left">
			<h2>Create a new maintenance schedule</h2>
			<p>Create beautiful and customisable schedules for your client and their new home.</p>
			<a class="form-submit btn btn-info" href="<?php //echo base_url(); ?>schedule/schedule_add">New Schedule</a>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-2 dashboard-right">
			<img width="80" height="80"  src="<?php //echo base_url(); ?>/images/icon_list.png" />
		</div>
	</div>
	
	<div class="row new-client">
		<div class="col-xs-12 col-sm-12 col-md-10 dashboard-left">
			<h2>Manage your client and their details</h2>
			<p>Add a new client account to let your client start managing their maintenance on their home.</p>
			<a class="form-submit btn btn-info" href="<?php //echo base_url(); ?>client/client_add">Add a Client</a>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-2 dashboard-right">
			<img width="80" height="80" src="<?php //echo base_url(); ?>/images/icon_client.png" />
		</div>
	</div>
	<div class="row  manage-profile">
		<div class="col-xs-12 col-sm-12 col-md-10 dashboard-left">
			<h2>Manage your user profile and settings</h2>
			<p>Manage general settings, your password and your notifications.</p>
			<a class="form-submit btn btn-info" href="<?php //echo base_url(); ?>user/user_setting">Manage Profile</a>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-2 dashboard-right">
			<img width="80" height="80" src="<?php //echo base_url(); ?>/images/icon_setting.png" />
		</div>
	</div>
		
	-->
</div>