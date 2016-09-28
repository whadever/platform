
<form class="form-horizontal" action="<?php echo base_url(); ?>user/permission_field_add" method="POST">
	<div class="modal-header">
		<h3>Enter Permission Name</h3>
	</div>
	<div class="modal-body">					
		<div class="control-group">
			<div class="controls add_group_box">
				<input type="text" id="permission_name" class="add_group" name="permission_name" value="" required="" />
			</div>
		</div>
		
		<div class="control-group">
			<div class="controls">
				<div class="save">
					<input type="submit" value="Submit" name="submit" />
				</div>
			</div>
		</div>	
	</div>

</form>
