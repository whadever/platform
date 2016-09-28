<script>
    window.wbsBaseUrl = "<?php print base_url(); ?>";
</script>
 <?php 
 $this->load->helper('url');
 
 $model = $this->load->model('permission_model');
 $redirect_login_page = base_url().'user';
if(!$this->session->userdata('user')){redirect($redirect_login_page); }

 ?>
<div class="development-overview-home">                 
    <div id="devlopment-overview-content">
      <?php echo $devlopment_content; ?>   
    </div>
    <div class="clear"></div>
</div>