<script>
    window.wbsBaseUrl = "<?php print base_url(); ?>";
</script>
 <?php 
 $this->load->helper('url');
 
 $model = $this->load->model('permission_model');
 $redirect_login_page = base_url().'user';
if(!$this->session->userdata('user')){redirect($redirect_login_page); }

 ?>



<div class="development-home">    
    <div id="devlopment-sub-sidebar">
      <?php echo $stage_sub_sidebar; ?>   
    </div>              
    <div id="devlopment-content">
      <?php echo $stage_content; ?>   
    </div>
    <div class="clear"></div>
</div>