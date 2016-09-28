<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap-select.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css" type="text/css" media="screen" />
<!-- start: Modal -->
<link href="<?php echo base_url();?>css/bootstrap-modal-bs3patch.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
<link href="<?php echo base_url();?>css/select2.min.css" rel="stylesheet" type="text/css"/>
<!-- end: Modal -->
<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.css">
<link rel="stylesheet" href="<?php echo base_url();?>css/flexslider.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/datepicker.css"/>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">


<!--<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.11.3.min.js"></script><script type="text/javascript" src="<?php echo base_url();?>js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.mtz.monthpicker.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/bootstrap-select.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.chained.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.dcaccordion.2.7.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/custom.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/new.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>
<script src="<?php echo base_url();?>js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.flexslider-min.js"></script>

<!-- start: Modal -->
<script src="<?php echo base_url();?>js/bootstrap-modal.js"></script>
<script src="<?php echo base_url();?>js/bootstrap-modalmanager.js"></script>
<script src="<?php echo base_url();?>js/ui-modals.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.fancybox.js?v=2.1.5"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.fancybox.css?v=2.1.5" media="screen" />
<script type="text/javascript">
    $(document).ready(function() {
        $(".fancybox").fancybox({
            maxWidth	: 1000,
            maxHeight	: 850,
            fitToView	: true,
            width		: '70%',
            height		: '70%'
        });
    });
</script>
<style>
    .admindevelopment-list .development-button a > img {
        height: auto;
        width: 85%;
    }
</style>
 <?php
 $this->load->helper('url');
 
 $model = $this->load->model('permission_model');
 $redirect_login_page = base_url().'user';
if(!$this->session->userdata('user')){redirect($redirect_login_page); }

 ?>
<div class="container-fluid">
    <div class="container maincontent" style="width:95%">
        <a class="" style="margin-bottom: 5px; display: block" href="<?php echo site_url('job/show_popup_menu'); ?>"><< main menu</a>
        <div id="devlopment-content">
            <?php echo $template_content; ?>
        </div>
        <div class="clear"></div>
    </div>
</div>