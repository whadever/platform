
<div id="user_list_view" class="content-inner">

	<div class="row">
		<div class="title col-xs-8 col-sm-8 col-md-8">
			<div class="title-inner">
				<img src="<?php echo base_url(); ?>images/add_user_1.png" width="40" />
				<p><strong>Manage your Users</strong><br>Create and manage your Users, and allow them access to particular systems.</p>
			</div>
		</div>
	</div>

	<div class="main-page">
		<div class="row">
			<div class="col-xs-12">
				<?php if(isset($table)) { echo $table;	} ?>
			    <?php if(isset($user_table)) { echo $user_table;} ?>
			</div>
		</div>
	</div>

</div>