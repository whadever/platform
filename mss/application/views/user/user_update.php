

<div class="content">
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="content-header">
				<div class="title"><?php echo $title; ?></div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<form method="POST" action="<?php echo base_url(); ?>user/user_update/<?php echo $user->uid; ?>">
			
			<div class="col-xs-12 col-sm-12 col-md-6">
      			<div class="form-group">
      				<label for="fullname">Full Name</label>
      				<input required="" class="form-control" type="text" name="fullname" id="fullname" value="<?php echo $user->fullname; ?>" />
      			</div>
      		</div>
      		
      		<div class="col-xs-12 col-sm-12 col-md-6">
      			<div class="form-group">
      				<label for="email">Email Address</label>
      				<input required="" class="form-control" type="text" name="email" id="email" value="<?php echo $user->email; ?>" />
      			</div>
      		</div>
      		
      		<div class="col-xs-12 col-sm-12 col-md-6">
      			<div class="form-group">
      				<label for="name">User Name</label>
      				<input required="" class="form-control" type="text" name="name" id="name" value="<?php echo $user->name; ?>" />
      			</div>
      		</div>
      		
      		<div class="col-xs-12 col-sm-12 col-md-6">
      			<div class="form-group">
      				<label for="pass">Password</label>
      				<input class="form-control" type="password" name="pass" id="pass" value="" />
      			</div>
      		</div>
      		
			<div class="col-xs-12 col-sm-12 col-md-10">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-2">
				<input class="btn btn-info" type="submit" name="submit" value="Edit User" />
			</div>
      		
		</form>
	</div>
	
</div>


