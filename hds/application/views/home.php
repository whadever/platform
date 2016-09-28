<script>
    window.mbsBaseUrl = "<?php print base_url(); ?>";
</script>
 <?php 
 $this->load->helper('url');
 
 $model = $this->load->model('permission_model');
 $redirect_login_page = base_url().'user';
if(!$this->session->userdata('user')){redirect($redirect_login_page); }

 ?>
    
               
<div id="maincontent">
  <?php echo $maincontent; ?>   
</div>
<div class="clear"></div>