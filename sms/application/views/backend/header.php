<?php 
	$user = $this->session->userdata('user');
	$wp_company_id = $user->company_id;

	$this->db->select("wp_company.*,wp_file.*");
	$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->db->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->db->get('wp_company')->row();

	$logo = 'https://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

?>
<div class="container">
<div class="row">
	<div class="col-xm-3 col-sm-3 col-md-3 col-lg-3">
		<div class="logo" style="">
			<a href="<?php echo base_url(); ?>">
				<img height="67" src="<?php echo $logo; ?>"/>
			</a>
		</div>
	</div>
	
	<div class="col-xm-9 col-sm-9 col-md-9 col-lg-9">
		
		<div class="logout text-right">
			<a onclick="window.history.go(-1)" class="brand">
				<img width="55" title="Back" src="<?php echo base_url(); ?>assets/images/back.png">
			</a>
			<?php //if ($account_type != 'parent'):?> 
			<a href="<?php echo base_url();?>admin/manage_profile"> 
				<img width="55" alt="Profile" title="Profile" src="<?php echo base_url(); ?>assets/images/user_setting.png">
			</a>
			<?php //endif;?>
			<?php if ($account_type == 'parent'):?> 
			<a href="<?php echo base_url();?>parents/manage_profile"> 
				<img width="55" alt="Settings" title="Settings" src="<?php echo base_url(); ?>assets/images/user_setting.png">
			</a>
			<?php endif;?>
			
			<a href="<?php echo 'http://'.$_SERVER['SERVER_NAME']; ?>" class="brand">
				<img width="55" title="Home" src="<?php echo base_url(); ?>assets/images/home-pdf.png">
			</a>
			<a href="<?php echo base_url();?>login/logout" class="brand">
				<img width="55" title="Logout" src="<?php echo base_url(); ?>assets/images/logout.png">
			</a>
			
		</div>
	</div>
</div>
</div>

<hr style="margin-top:0px;" />