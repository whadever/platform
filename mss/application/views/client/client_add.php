

<div class="content">
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="content-header">
				<div class="title"><?php echo $title; ?></div>
			</div>
		</div>
	</div>

	<div class="row">
		<form method="POST" action="<?php echo base_url(); ?>client/client_add">
			
			<div class="col-xs-12 col-sm-6 col-md-6">
      			<div class="form-group">
      				<label for="client_name">Name</label>
      				<input required="" class="form-control" type="text" name="client_name" id="client_name" value="" />
      			</div>
      		</div>
      		
      		
      		
      		<div class="col-xs-12 col-sm-12 col-md-12">
      			<div class="form-group">
      				<label for="address">Address</label>
      				<input onkeyup="ValidateUser();" required="" class="form-control" type="text" name="address" id="address" value="" />
      			</div>
      		</div>

			<div class="col-xs-12 col-sm-6 col-md-6">
      			<div class="form-group">
      				<label for="email">Email Address</label>
      				<input onkeyup="ValidateEmail();" required="" class="form-control" type="text" name="email" id="email" value="" />
      			</div>
      		</div>
      		
      		<div class="col-xs-12 col-sm-12 col-md-6">
      			<div class="form-group">
      				<label for="phone">Phone Number</label>
      				<input required="" class="form-control" type="text" name="phone" id="phone" value="" />
      			</div>
      		</div>

				<div class="col-xs-12 col-sm-12 col-md-10">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-2">
					<input class="btn btn-info" type="submit" name="submit" value="Add Client" />
				</div>
      		
		</form>
	</div>
	
</div>


