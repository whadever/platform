
<div class="row">
	<div class="title col-xs-8 col-sm-8 col-md-8">
		<div class="title-inner">
			<img src="<?php echo base_url(); ?>images/add_1.png" width="40" />
			<p><strong>Manage your Clients</strong><br>Create and manage your Clients, and allow them access to particular systems.</p>
		</div>
	</div>
</div>
<div class="main-page">
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="client-detail ">
	
				<?php if(isset($client_table)) { echo $client_table;	} ?>
			    <?php if(isset($app_client_table)) { echo $app_client_table;} ?>
	
			</div>
		</div>
	</div>    
</div>