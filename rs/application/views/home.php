<script>
    window.mbsBaseUrl = "<?php print base_url(); ?>";
</script>
 <?php 
 //$this->load->helper('url');
 
 //$model = $this->load->model('permission_model');
 $redirect_login_page = base_url().'user';
if(!$this->session->userdata('user')){
	
		redirect($redirect_login_page,'refresh'); 
		 
		
	}
	

 ?>

<div class="row">
    <div class="col-md-12">
        <div id="infoMessage">

            <?php if($this->session->flashdata('success-message')){ ?>

                <div class="alert alert-success" id="success-alert">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <?php echo $this->session->flashdata('success-message');?>
                </div>
            <?php } ?>

            <?php if($this->session->flashdata('warning-message')){ ?>

                <div class="alert alert-warning" id="warning-alert">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <?php echo $this->session->flashdata('warning-message');?>
                </div>
            <?php } ?>

        </div>
    </div>

</div>
               
<div id="maincontent" style="overflow: visible; padding-bottom: 30px">
  <?php echo $maincontent; ?>   
</div>