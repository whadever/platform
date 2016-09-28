<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
		</div>
	</div>
</div>
	<?php 
	$id=$items[0]['id'];
	$item_name=$items[0]['item_name'];
	$item_unit=$items[0]['item_unit'];
	echo form_open('items/item_update/'.$id); 
	?>
	<div class="row">&nbsp;</div>
    <div class="row">
		<div class="col-xs-12 col-sm-4 col-md-4"></div>
		<div class="col-xs-12 col-sm-4 col-md-4">
		<?php
            echo form_label('Name of the Item', 'item_names'); 
            $data = array(
					'name' 	=> 'item_name',
					'id'  	=> 'item_name',
					'value'	=> $item_name,
					'class'	=> 'form-control input-sm',
			);
            echo form_input($data);
        ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4"></div>
    </div>
    <div class="row">
		<div class="col-xs-12 col-sm-4 col-md-4"></div>
		<div class="col-xs-12 col-sm-4 col-md-4">
		<?php 
            echo form_label('Select Unit', 'item_units'); 
            $options = array(
              '0'=>'-Select to Create/Edit-',
              '1'=>'Days',
              '2'=>'Hours',
			  '3'=>'m2'
            );
            $js = 'id="item_unit" class="form-control input-sm"';
            echo form_dropdown('item_unit', $options, $item_unit, $js);
        ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4"></div>
    </div>
    <div class="row">&nbsp;</div>
    <div class="row"> 
    <div class="col-xs-12 col-sm-4 col-md-4"></div>
		<div class="col-xs-12 col-sm-4 col-md-4">
		<?php 
			$attr_back = array(
              'name'	=> 'back',
			  'id'		=> 'back',
			  'value'	=> 'Back',
			  'class'	=> 'btn btn-danger pull-right"',
			  'type'	=> 'submit',
            ); 
            $attr_save = array(
              'name'	=> 'save',
			  'id'		=> 'save',
			  'value'	=> 'Save',
			  'class'	=> 'btn btn-danger pull-right"',
			  'type'	=> 'submit',
            );
			echo form_submit($attr_save);
			echo form_submit($attr_back);
        ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4"></div>
    </div>
    <div class="row">&nbsp;</div>
<?php echo form_close(); ?>
