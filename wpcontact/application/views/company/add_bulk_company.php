<?php
	$form_attributes = array('method'=>'post', 'enctype'=>'multipart/form-data'); 
	
	$ci = & get_instance();  
	$ci->load->model('category_model');
	$cat_options = $ci->category_model->get_category_option_list();
	$category_options = $cat_options;
	$company_js = 'class="form-control selectpicker" required="true" multiple';
	$category_list = form_label('Category', 'category_id');
	$category_list .= form_dropdown('category_id[]', $category_options, '', $company_js);
	
	$submit = form_label(' ', 'submit');
	$submit .= form_submit(array(
	              'name'        => 'submit',
	              'id'          => 'edit-submit',
	              'value'       => 'Save',
	              'class'       => 'btn btn-default',
	              'type'        => 'submit'
	));
?>
<style>
	#maincontent {
		overflow: unset;
	}
	select#company_id {
		border: 0 none;
		height: 0;
		padding: 0;
		visibility: hidden;
	}
	a:focus {
		outline: medium none;
		outline-offset: -2px;
	}
</style>

<script>
	$(document).ready(function() {
		$(".selectpicker").selectpicker();
		//$("#company_id").css('display','block');
	});
</script>


<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
			<input type="button" value="Back" class="btn btn-default add-button" onclick="history.go(-1);"/>
		</div>
	</div>
</div>

<div class="clear"></div>

<?php          
	$this->breadcrumbs->push('Company', 'company/company_list');
	$this->breadcrumbs->push('Add Bulk Company', 'company/company_add/');
?>
<div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div> 

<div id="contact_add_edit_page" class="content-inner">
    
<?php

	echo '<div id="" class="" style="color:red;">'.validation_errors(). '</div>';
	echo form_open_multipart($action, $form_attributes);
        
?>

<div class="contact_add_bulk">
    <div class="row">
		<div class="col-xs-12 col-sm-3 col-md-3"></div>
		<div class="col-xs-12 col-sm-6 col-md-6">
	        <label for="upload_image">Select Excel File</label>
	        <input style="margin-bottom: 10px;" required="" type="file" name="upload_excel" id="upload-image" class="form-control">	
		</div>
    </div>
    
    <div class="row">
		<div class="col-xs-12 col-sm-4 col-md-3"></div>
		<div class="col-xs-12 col-sm-4 col-md-3">
	       	<a href="<?php echo base_url(); ?>uploads/excel/blank_excel_file.xlsx" target="_blank" class="btn btn-info btn-sm">
	       		<i class="entypo-download"></i>Download blank excel file</a>	
		</div>
    </div>
    
    <div class="row">
		<div class="col-xs-12 col-sm-3 col-md-3"></div>
		<div class="col-xs-12 col-sm-6 col-md-6">
	        <?php echo $category_list ; ?>	
		</div>
    </div>
	
    <div class="row">
    	<div class="col-xs-12 col-sm-3 col-md-3"></div>
        <div class="col-xs-12 col-sm-9 col-md-9">
        	<input style="margin-top: 10px;" name="submit" type="submit" value="Upload" class="btn btn-default">
        </div>      
    </div>
</div>


<?php
	echo form_close();
?>
</div>