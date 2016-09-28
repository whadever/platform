		</div>
	</div>
<?php 
	$user = $this->session->userdata('user'); 
	$wp_company_id = $user->company_id;

	$this->db->select("wp_company.*,wp_file.*");
	$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->db->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->db->get('wp_company')->row();

?>
	<div class="footer">
		<div class="container1">
			<p align="center" style="color:#000000;margin:0 0 10px;">User Management System</p>
			<p align="center" style="color:#000000;margin:0;">© Williams Platform 2015</p>
			<p align="center"><a target="_BLANK" href="http://www.williamsplatform.com/"><img border="0" width="163" src="<?php echo base_url();?>images/PoweredByLogo.png"/></a></p>
			<br/>
		</div>	 
	</div> 	 
</div>
</body>
</html>