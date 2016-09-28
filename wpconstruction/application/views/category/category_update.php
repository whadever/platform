<?php

	$form_attributes = array('class' => 'category-update-form', 'id' => 'entry-form','method'=>'post');
	$id = form_hidden('id', isset($category->id) ? $category->id : '');
        
	$action = 'category/category_update/'.$category->id;
			
		        
	$category_name = form_label('Category Name (*)', 'category_name');
	$category_name .= form_input(array(
		'name'        => 'category_name',
		'id'          => 'edit-category_name',
		'value'       => isset($category->category_name) ? $category->category_name : '',
		'class'       => 'form-control',
		'placeholder'=>'Enter Category Name',
		'required'    => TRUE
	));
		

	
	$submit = form_label(' ', 'submit');
	$submit .= form_submit(array(
	              'name'        => 'submit',
	              'id'          => 'edit-submit',
	              'value'       => 'Save',
	              'class'       => 'btn btn-default',
	              'type'        => 'submit',
                  'onclick'     => 'checkEmail();',
	));

?>



<div class="clear"></div>

<?php          
	$this->breadcrumbs->push('Category', 'category/category_list');
	if(isset($company->id))
	{
		$this->breadcrumbs->push($category->category_name, 'category/category_details/'.$category->id);
 		//$this->breadcrumbs->push('Modify Company', 'company/company_add/'.$company->id);
	}
	else
	{
		//$this->breadcrumbs->push(' Add', 'company/company_add/');
	}
?>
<div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div> 

<div id="contact_add_edit_page" class="content-inner">
    
<?php

	echo '<div id="" class="" style="color:red;">'.validation_errors(). '</div>';
	echo form_open_multipart($action, $form_attributes);
	echo '<div id="sid-wrapper" class="field-wrapper">'. $id . '</div>';
        
?>



<div class="contact_add">
    <div class="row">
		<div class="col-xs-12 col-sm-4 col-md-3"></div>
		<div class="col-xs-12 col-sm-4 col-md-3">
	        <?php echo $category_name ; ?>
	        
		</div>
		<div class="col-xs-12 col-sm-4 col-md-3">
		</div>
		<div class="col-xs-12 col-sm-4 col-md-3"></div>
    </div>


    <p>&nbsp;</p>

    <div class="row">
        <div class="col-xs-6 col-sm-6 col-md-6"></div>
        <div class="col-xs-6 col-sm-6 col-md-6"><?php echo $submit ; ?></div>        
    </div>
</div>


<?php
	echo form_close();
?>
</div>