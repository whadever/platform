<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
		</div>
	</div>
</div>
<?php echo form_open('items/item'); ?>
	<div class="row">&nbsp;</div>
    <div class="row">
		<div class="col-xs-12 col-sm-4 col-md-4"></div>
		<div class="col-xs-12 col-sm-4 col-md-4">
		<?php 
            echo form_label('Select the Action', 'items'); 
            $options = array(
              ''=>'-Select to Create/Edit-',
              '1'=>'Create',
              '2'=>'View/Edit',
            );
            $js = 'id="items" class="form-control input-sm" required="1"';
            echo form_dropdown('items', $options, '', $js);
        ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4"></div>
    </div>
    <div class="row">&nbsp;</div>
    <div class="row"> 
    <div class="col-xs-12 col-sm-4 col-md-4"></div>
		<div class="col-xs-12 col-sm-4 col-md-4">
		<?php 
            //echo form_label('', 'next'); 
            $attr_arr = array(
              'name'        => 'next',
			  'id'          => 'next',
			  'value'       => 'Next',
			  'class'       => 'btn btn-danger pull-right"',
			  'type'        => 'submit',
            );
            //echo form_submit($attr_arr);
        ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4"></div>
    </div>
    <div class="row">&nbsp;</div>
<?php echo form_close(); ?>
<script>
	window.Url = "<?php print base_url(); ?>";
	$(document).ready(function () {
        $('#items').change(function () {
            action = $(this).val();
            if(action=='2'){
				document.location.href = window.Url + "items/item_view";
			}else if(action=='1'){
				document.location.href = window.Url + "items/item_create";
			}
        });
    });
</script>