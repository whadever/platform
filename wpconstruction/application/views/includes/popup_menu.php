<?php $domain = $_SERVER['SERVER_NAME']; ?>

<style>
    .submenu {
        margin: 0 0 0 20px;
    }
</style>

<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>

<div style="height: 297px">
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

    <div class="popup_title">
        <h2 class="popup_title2">Add/Edit</h2>
    </div>
    
    <div class="popup-body">
    
        <div class="control-group">
            <div id="job" class="black_bar">Job</div>
            <div id="job_submenu" style="display:none">
                <div class="black_bar"><a href="<?php echo base_url() ?>job/add_job">Add Job</a></div>
                <div class="black_bar"><a href="<?php echo base_url() ?>job/edit_job">Edit Job</a></div>
                <div class="black_bar"><a href="<?php echo base_url() ?>admindevelopment/development_list">Job List</a></div>
            </div>
        </div>
            
        <div class="control-group">
            <div id="template" class="black_bar">Program Template</div>
            <div id="template_submenu" style="display:none">
                <div class="black_bar"><a href="<?php echo base_url() ?>template/template_start">Add Template</a></div>
                <div class="black_bar"><a href="<?php echo base_url() ?>template/template_list">Template List</a></div>
            </div>
        </div>
        <?php if($_SERVER['SERVER_NAME'] != 'horncastle.wclp.co.nz'): ?>
        <div class="control-group">
            <div id="tendering_template" class="black_bar">Tendering Template</div>
            <div id="tendering_template_submenu" class="submenu" style="display:none">
                <div class="black_bar"><a href="<?php echo base_url() ?>template/tendering_template_start">Add Template</a></div>
                <div class="black_bar"><a href="<?php echo base_url() ?>template/tendering_template_list">Template List</a></div>
            </div>
        </div>
        <?php endif; ?>
       <!-- --><?php /*if($domain == 'horncastle.wclp.co.nz' || $domain == 'xprobuilders.wclp.co.nz'):  */?>
        <!--<div class="control-group">
                <div id="list" class="black_bar">List</div>
                <div id="list_submenu" style="display:none">
                        <div class="black_bar"><a href="<?php /*echo base_url(); */?>job/add_list">Add List</a></div>
                        <div class="black_bar"><a href="<?php /*echo base_url(); */?>job/edit_list">Edit List</a></div>
                </div>
        </div>-->
        <div class="control-group">
            <div class="black_bar"><a href="<?php echo base_url(); ?>template/milestone_template_list">Milestone Template</a></div>
        </div>
        <div class="control-group">
                <div id="list" class="black_bar">Form</div>
                <div id="list_submenu" style="display:none">
                        <div class="black_bar"><a href="<?php echo base_url(); ?>constructions/create_form">Add Form</a></div>
                        <div class="black_bar"><a href="<?php echo base_url(); ?>constructions/form_list">Form List</a></div>
                </div>
        </div>
        <?php if($_SERVER['SERVER_NAME'] != 'horncastle.wclp.co.nz'): ?>
        <div class="control-group">
            <div class="black_bar"><a href="<?php echo base_url(); ?>restore/">Restore</a></div>
        </div>
        <?php endif; ?>
            
    </div>
    <div style="clear:both;"></div>
</div>


<script>

jQuery(document).ready(function() {
	$( "#job" ).click(function() {
  		$( "#job_submenu" ).toggle( "slow" );
	});

	$( "#template" ).click(function() {
  		$( "#template_submenu" ).toggle( "slow" );
	});

    $( "#tendering_template" ).click(function() {
  		$( "#tendering_template_submenu" ).toggle( "slow" );
	});

	$( "#list" ).click(function() {
  		$( "#list_submenu" ).toggle( "slow" );
	});

    $("#infoMessage").fadeTo(3000, 500).slideUp(500, function(){
        $('#infoMessage').remove();
    });


});

</script>
