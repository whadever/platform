<div id="all-title">
	<div class="row">
    	<div class="col-xs-12 col-sm-12 col-md-12">
        	<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
        </div>
	</div>
</div>
<?php 
if(isset($items)){
	echo form_open('items/item_edit/'.$items->id); 
}else{
	echo form_open('items/item_create'); 
}
?>
	<div class="row">
	&nbsp;
	<?php if (validation_errors()) { ?>
		<div class="col-xs-12 col-sm-4 col-md-4 col-md-offset-4 col-lg-offset-4">
	        <div id="infoMessage">
                <div class="alert alert-warning" id="warning-alert" style="margin-top: 20px">
                    <button type="button" class="close" data-dismiss="alert">x</button>
                    <?php echo validation_errors(); ?>
                </div>
	        </div>
	    </div>
	<?php } ?>
	</div>
	
    <div class="row">
		<div class="col-xs-12 col-sm-4 col-md-4"></div>
		<div class="col-xs-12 col-sm-4 col-md-4">
		<?php 
            echo form_label('Name of the Item', 'item_names'); 
            $data = array(
					'name' 	=> 'item_name',
					'id'  	=> 'item_name',
					'class'	=> 'form-control input-sm',
					'value' => $items->item_name==''? '' : $items->item_name,
					'required' => '1'
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
              ''=>'-Select to Unit-',
              '1'=>'Days',
              '2'=>'Hours',
			  '3'=>'m2',
			  '4'=>'Units',
			  '5'=>'Dollars'
            );
            $de = $items->item_unit==''? '' : $items->item_unit;
            $js = 'id="item_unit" class="form-control input-sm"';
            echo form_dropdown('item_unit', $options, $de, $js);
        ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4"></div>
    </div>
    <div class="row">
		<div class="col-xs-12 col-sm-4 col-md-4"></div>
		<div class="col-xs-12 col-sm-4 col-md-4">
			<label for="item_price">Price</label>
			<div class="input-group">
				<span class="input-group-addon">$</span>
				<input value="<?php echo $items->item_price; ?>" type="text" name="item_price" id="item_price" class="form-control input-sm" /> 
			</div>
		<?php 
            /*echo form_label('Price', 'item_price'); 
            $data = array(
					'name' 	=> 'item_price',
					'id'  	=> 'item_price',
					'class'	=> 'form-control input-sm',
					'value' => $items->item_price==''? '' : $items->item_price,
					
			);
            echo form_input($data);*/
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
              'name'	=> 'submit',
			  'id'		=> 'save',
			  'value'	=> 'Save',
			  'class'	=> 'btn btn-danger pull-right"',
			  'type'	=> 'submit',
            );
            echo form_submit($attr_save);
			echo '<a class="btn btn-danger pull-right" href="'.base_url().'items/item_view">Back</a>';
			
        ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4"></div>
    </div>
    <div class="row">&nbsp;</div>
<?php echo form_close(); ?>
