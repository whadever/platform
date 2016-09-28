<script>
    window.mbsBaseUrl = "<?php print base_url(); ?>";
</script>
 <?php 
 $this->load->helper('url');
 
 $model = $this->load->model('permission_model');
 $redirect_login_page = base_url().'user';
if(!$this->session->userdata('user')){redirect($redirect_login_page); }

 ?>
   
<?php
if (isset($message) and $message !='')
{
?> 

<div class="notify">
	<?php echo $message; ?>
</div>

<?php } ?>

               
<div id="maincontent-print">
  <?php echo $maincontent; ?>   
</div>
<div class="clear"></div>