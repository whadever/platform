<div class="content">
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="content-header">
				<div class="title"><?php echo $title; ?></div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<form method="POST" action="<?php echo base_url(); ?>user/user_profile/<?php echo $users->uid; ?>">
			
			<div class="col-xs-12 col-sm-12 col-md-12">
      			<div class="form-group">
      				<label for="fullname">Username</label>
      				<input class="form-control" type="text" name="username" id="fullname" value="<?php echo $users->username; ?>" />
      			</div>
      		</div>
      		
      		<div class="col-xs-12 col-sm-12 col-md-12">
      			<div class="form-group">
      				<label for="address">Email</label>
      				<input class="form-control" type="text" name="email" id="address" value="<?php echo $users->email; ?>" />
      			</div>
      		</div>
      		
			<div class="col-xs-12 col-sm-12 col-md-10">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-2">
				<input class="btn btn-info" type="submit" name="submit" value="Update Profile" />
			</div>
      		
		</form>
	</div>
	
</div>