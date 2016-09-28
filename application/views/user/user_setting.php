
<div id="user-page" class="content-inner"> 

	 <div class="row">
	    <div class="col-md-12"> 
	        <div id="infoMessage">
	
	        <?php if($this->session->flashdata('success-message')){ ?>
	
	        <div class="alert alert-success" id="success-alert">
	        <button type="button" class="close" data-dismiss="alert">x</button>
	        <strong>Success! </strong>
	        <?php echo $this->session->flashdata('success-message');?>
	        </div>    
	        <?php } ?>
	
	        </div>
	    </div>
	</div>   
	    
	   
	<div class="row">
		<div class="title col-xs-10 col-sm-10 col-md-10">
			<div class="title-inner">
				<img src="<?php echo base_url(); ?>images/add_user_1.png" width="40" />
				<p><strong>Hi <?php echo $user_info->username; ?></strong><br>Use this Settings area to manage and update your details. Click on the asterisk to your right to change your details.</p>
			</div>
		</div>
		<div class="col-xs-2 col-sm-2 col-md-2">
			<div class="add client-add-button">
				<a href="<?php echo base_url(); ?>user/user_password_update/<?php echo $user_info->uid; ?>"><img src="<?php echo base_url(); ?>images/user_update.png" title="Change Password" width="60" /></a>
			</div>
		</div>
	</div>
	    
	<div class="main-page"> 		

		<div class="row">
			<div class="col-sm-12 col-md-12">
				<div class="client-list">
		                    <div class="table-responsive">
		                        <?php echo $user_table; ?>
		                    </div>
		        </div>
		    </div>
		</div>
	
	</div>

</div>

