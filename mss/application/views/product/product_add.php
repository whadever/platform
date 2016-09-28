<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>bootstrap/css/bootstrap-select.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/product.css">
<script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap-select.js"></script>
  
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="content-header">
			<div class="title"><?php echo $title; ?></div>
		</div>
	</div>
</div>
  
<?php	
        //$pid = isset($project_id)?$project_id:0;

        $form_attributes = array('class' => 'add-form', 'id' => 'product-add-form','method'=>'post');
        //$action='product/product_add/'.$pid;
	$id = form_hidden('id', isset($product->id) ? $product->id : '');



	$product_name = form_label('Product Name', 'product_name');
	$product_name .= form_input(array(
	              'name'        => 'product_name',
	              'id'          => 'add-product_name',
	              'value'       => isset($product->product_name) ? $product->product_name : set_value('product_name', ''),
	              'class'       => 'form-control',
                      'required'    => TRUE

	));
        
        
        $ci = & get_instance();
	$ci->load->model('product_model'); 
	$type_option = $ci->product_model->get_product_type();
        $type_options = array('' => '-- Select Product Type --') + $type_option;
        /*$pid =isset($project_id) ? $project_id : set_select('project_id', 0, TRUE); */
	$type_default = isset($product->product_type_id) ? $product->product_type_id : 0;
	$product_type = form_label('Product Type', 'product_type_id');
        $type_js = 'id="project_id" onChange="" class="form-control" required="true"';
	$product_type .= form_dropdown('product_type_id', $type_options, $type_default, $type_js);
        


	$product_warranty_year = form_label('Warranty Period', 'product_warranty_year');
	$product_warranty_year .= form_input(array(
	              'name'        => 'product_warranty_year',
	              'id'          => 'product_warranty_period_year',
	              'value'       => isset($product->product_warranty_year) ? $product->product_warranty_year : set_value('product_warranty_year', ''),
	              'placeholder' => 'Years',
				  'class'       => 'form-control',                         
                        'required'    => TRUE
	));
        
        $product_warranty_period_default = isset($product->product_warranty_month) ? $product->product_warranty_month : set_value('product_warranty_month');
        $product_warranty_period_options = array(
            ''=>'-- Select Month --', '1'=>1, '2'=> 2, '3'=>3, '4'=>4, 
            '5'=>5, '6' => 6, '7'=>7, '8'=>8, 
            '9'=>9, '10'=> 10, '11'=>11, '12'=>12);
        
        $att1 = 'id="product_warranty_period_month" class="form-control" required="true"';
        //$product_warranty_period = form_label('', 'product_warranty_period');
        $product_warranty_month = form_dropdown('product_warranty_month', $product_warranty_period_options, $product_warranty_period_default, $att1);         
         
        
       
       $category_option = $ci->product_model->get_product_category();
        $category_options = array('' => '-- Select Product Category --') + $category_option;
        /*$pid =isset($project_id) ? $project_id : set_select('project_id', 0, TRUE); */
	$product_category_default = isset($product->product_category_id) ? $product->product_category_id : 0;
	$product_category = form_label('Product Category', 'product_category_default');
        $category_js = 'id="select_product_category_id" onChange="" class="form-control" required="true"';
	$product_category .= form_dropdown('product_category_id', $category_options, $product_category_default, $category_js);

	    

        
        $product_maintenance_year = form_label('Maintenance Period', 'product_maintenance_year');
	$product_maintenance_year .= form_input(array(
	              'name'        => 'product_maintenance_year',
	              'id'          => 'product_maintenance_period_year',
	              'value'       => isset($product->product_maintenance_year) ? $product->product_maintenance_year : set_value('product_description', ''),
				  'placeholder' => 'Years',
	              'class'       => 'form-control',                         
                      'required'    => TRUE
	));
        
        $product_maintenance_period_default = isset($product->product_maintenance_month) ? $product->product_maintenance_month : set_value('product_maintenance_month');
        $product_maintenance_period_options = array(
            ''=>'-- Select Month --', '1'=>1, '2'=> 2, '3'=>3, '4'=>4, 
            '5'=>5, '6' => 6, '7'=>7, '8'=>8, 
            '9'=>9, '10'=> 10, '11'=>11, '12'=>12);
        
        $status_js = 'id="product_maintenance_period_month" class="form-control" required="true"';
        //$product_maintenance_month = form_label('', 'product_maintenance_month');
        $product_maintenance_month = form_dropdown('product_maintenance_month', $product_maintenance_period_options, $product_maintenance_period_default, $status_js);         
         
        
         
        $document_id = form_hidden('document_id', isset($product->document_id) ? $product->document_id : '0');  
	$document = form_label('Document', 'upload_document');
	if (isset($product->filename)) {

		$document .= $product->filename;
                $document .= form_upload(array(
	              'name'        => 'upload_document',
	              'id'          => 'edit-document',
	              'class'       => 'form-file form-control',
	              'type'        => 'file',
	));

	}else {

		$document .= form_upload(array(

	              'name'        => 'upload_document',
	              'id'          => 'edit-document',
	              'class'       => 'form-file',
	              'type'        => 'file',

	));

	}
        
        $product_specifications = form_label('Product Specifications', 'product_specifications');
	$product_specifications .= form_input(array(
	              'name'        => 'product_specifications',
	              'id'          => 'product_specifications',
	              'value'       => isset($product->product_specifications) ? $product->product_specifications : set_value('product_specifications', ''),
	              'class'       => 'form-control'

	));
        
        
        
        $look_while_maintaining = form_label('What to look for while maintaining', 'look_while_maintaining');
	$look_while_maintaining .= form_input(array(
	              'name'        => 'look_while_maintaining',
	              'id'          => 'look_while_maintaining',
	              'value'       => isset($product->look_while_maintaining) ? $product->look_while_maintaining : set_value('look_while_maintaining', ''),
	              'class'       => 'form-control'

	));
        
        $estimated_serviceable_life = form_label('Product Estimated serviceable life with reguler maintainence', 'estimated_serviceable_life');
	$estimated_serviceable_life .= form_input(array(
	              'name'        => 'estimated_serviceable_life',
	              'id'          => 'product_estimated_serviceable_life',
	              'value'       => isset($product->estimated_serviceable_life) ? $product->estimated_serviceable_life : set_value('estimated_serviceable_life', ''),
	              'class'       => 'form-control'

	));
        
        $description_of_maintenance = form_label('Description of maintenance', 'description_of_maintenance');
	$description_of_maintenance .= form_input(array(
	              'name'        => 'description_of_maintenance',
	              'id'          => 'product_description_of_maintenance',
	              'value'       => isset($product->description_of_maintenance) ? $product->description_of_maintenance : set_value('description_of_maintenance', ''),
	              'class'       => 'form-control'

	));
        
        $todo_end_of_serviceable_life = form_label('Product Estimated serviceable life with reguler maintainence', 'todo_end_of_serviceable_life');
	$todo_end_of_serviceable_life .= form_input(array(
	              'name'        => 'todo_end_of_serviceable_life',
	              'id'          => 'product_todo_end_of_serviceable_life',
	              'value'       => isset($product->todo_end_of_serviceable_life) ? $product->todo_end_of_serviceable_life : set_value('todo_end_of_serviceable_life', ''),
	              'class'       => 'form-control'

	));
        
        
        
       
        $submit_button_value = isset($product->id) ? 'Update Product' : 'Add Product';       
        $submit = form_label(' ', 'submit');
	$submit .= form_submit(array(

	              'name'        => 'submit',
	              'id'          => 'edit-submit',
	              'value'       => $submit_button_value,
	              'class'       => 'form-submit btn btn-info',
	              'type'        => 'submit',
                      'onclick'     => 'checkEmail();',

	));
        

	echo '<div id="" class="" style="color:red;">'.validation_errors(). '</div>';
	echo form_open_multipart($action, $form_attributes);
	echo form_fieldset('',array('class'=>"comp-add-fieldset"));
	echo '<div id="sid-wrapper" class="field-wrapper">'. $id . '</div>';

        ?>

  <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6">
            <?php echo '<div id="product-date-wrapper" class="form-group">'. $product_name . '</div>'; ?>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-6">
            <?php echo '<div id="project-company-wrapper" class="form-group">'.$product_type . '</div>'; ?>            
  
      </div>
  </div>
  
  <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6">
          <?php echo '<div id="warranty-period-wrapper" class="form-group">'. $product_warranty_year .$product_warranty_month. '</div>'; ?>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-6">
        <?php echo  $document_id;	
            
            echo '<div id="filename-custom-wrapper" class="form-group">'. $document . '</div>'; ?>
      </div>
  </div>

   <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-6"> 
        <?php echo '<div id="maintenance-period-wrapper" class="form-group">'. $product_maintenance_year.$product_maintenance_month . '</div>'; ?>
      </div>
      <div class="col-xs-12 col-sm-12 col-md-6"> 
            
        </div>        
    </div>
    
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12"> 
        <?php echo '<div id="product_specifications_wrapper" class="form-group">'. $product_specifications. '</div>'; ?>
      </div>
    </div>
  
     <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">       
        <?php echo '<div id="look_while_maintaining_wrapper" class="form-group">'.$look_while_maintaining. '</div>'; ?>
     </div>
    </div>
  
  
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">       
        <?php echo '<div id="estimated_serviceable_life_wrapper" class="form-group">'.$estimated_serviceable_life. '</div>'; ?>
     </div>
    </div>
  
  
  
  <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">       
        <?php echo '<div id="description_of_maintenance-wrapper" class="form-group">'.$description_of_maintenance. '</div>'; ?>
     </div>
    </div>
  
  <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-12">       
        <?php echo '<div id="to_do_wrapper" class="form-group">'.$todo_end_of_serviceable_life. '</div>'; ?>
     </div>
    </div>
    
 <div class="row">
      <div class="col-xs-12 col-sm-8 col-md-10"> 
        <!--<input type="button" value="Back" class="btn btn-default" onclick="history.go(-1);"/>  -->
      </div>
     <div class="col-xs-12 col-sm-4 col-md-2 submit"> 
        <?php echo '<div id="sid-wrapper" class="field-wrapper">'. $submit . '</div>'; echo form_close();?> 
     </div>
 </div>


<?php
	echo form_fieldset_close(); 
	echo form_close();
?>



