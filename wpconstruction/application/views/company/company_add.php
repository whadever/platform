<?php

	$form_attributes = array('class' => 'contact-add-form', 'id' => 'entry-form','method'=>'post');
	$id = form_hidden('id', isset($company->id) ? $company->id : '');
    $ci = & get_instance();    
	$company_name = form_label('Company Name', 'company_name');
	$company_name .= form_input(array(
		'name'        => 'company_name',
		'id'          => 'edit-company_name',
		'value'       => isset($company->company_name) ? $company->company_name : '',
		'class'       => 'form-control',
		'placeholder'=>'Enter Company Name',
		'required'    => TRUE
	));

	$image_id = form_hidden('image_id', isset($company->company_image_id) ? $company->company_image_id : '');
	$image = form_label('Image', 'upload_image');
	if (isset($company->image)){
		$image_file = $company->image;
		$image .= form_upload(array(
	              'name'        => 'upload_image',
	              'id'          => 'upload-image',
	              'class'       => 'form-file form-control',
	              'type'        => 'file',
	             ));

	}else {
		$image .= form_upload(array(
              'name'        => 'upload_image',
              'id'          => 'upload-image',
              'class'       => 'form-file form-control',
              'type'        => 'file',
             ));
	}
	
	$company_notes = form_label('Notes', 'company_notes');
	$company_notes .= form_textarea(array(
		'name'        => 'company_notes',
		'id'          => 'edit-contact_notes',
		'value'       => isset($company->company_notes) ? $company->company_notes : set_value('company_notes', ''),
		'class'       => 'form-control',  
		'size'        => '60',
		'rows'=>5,
		'cols'=>20,
	));
	
	$contact_number = form_label('Phone Number', 'contact_number');
	$contact_number .= form_input(array(
		'name'        => 'contact_number',
		'id'          => 'edit-company_phone_number',
		'value'       => isset($company->contact_number) ? $company->contact_number : '',
		'class'       => 'form-control',
		'placeholder'=>'Enter Phone Number',
	));


	$company_email = form_label('Email Address', 'company_email');
	$company_email .= form_input(array(
		'name'        => 'company_email',
		'id'          => 'edit-contact_email',
		'value'       => isset($company->company_email) ? $company->company_email : '',
		'class'       => 'form-control',
		'placeholder'=>'Enter Email Address',
	));

	$ci->load->model('category_model');
	$cat_options = $ci->category_model->get_category_option_list();
	$category_options = array('0' => '--Select Category--') + $cat_options;
	$catid =isset($category_id) ? $category_id : 0;        
	$companyid= isset($company->category_id) ? $company->category_id : $cid;        
	$company_js = 'id="company_id" onChange="" class="form-control selectpicker1" required="true"';
	$category_list = form_label('Category', 'category_id');
	$category_list .= form_dropdown('category_id', $category_options, $companyid, $company_js);

	$company_address = form_label('Address', 'company_address');
	$company_address .= form_input(array(
		'name'        => 'company_address',
		'id'          => 'edit_company_address',
		'value'       => isset($company->company_address) ? $company->company_address : '',
		'class'       => 'form-control',
		'placeholder'=>'Enter Address',
	));

	$company_city = form_label('City', 'company_city');
	$company_city .= form_input(array(
		'name'        => 'company_city',
		'id'          => 'edit-contact_city',
		'value'       => isset($company->company_city) ? $company->company_city : '',
		'class'       => 'form-control',
		'placeholder'=>'Enter City',
	));

	$company_country = form_label('Country', 'company_country');
	$company_country .= form_input(array(
		'name'        => 'company_country',
		'id'          => 'edit-company_country',
		'value'       => isset($company->company_country) ? $company->company_country : '',
		'class'       => 'form-control',
		'placeholder'=>'Enter Country',
	));
	

	$company_website = form_label('Website', 'company_website');
	$company_website .= form_input(array(
		'name'        => 'company_website',
		'id'          => 'edit_company_website',
		'value'       => isset($company->company_website) ? $company->company_website : '',
		'class'       => 'form-control',
		'placeholder'=>'Enter Website Address',
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
	$this->breadcrumbs->push('Contact', 'contact/contact_list');
	if(isset($company->id))
	{
		$this->breadcrumbs->push($company->company_name, 'company/company_details/'.$company->id);
 		$this->breadcrumbs->push('Modify Company', 'company/company_add/'.$company->id);
	}
	else
	{
		$this->breadcrumbs->push('Company Add', 'company/company_add/');
	}
?>
<div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div> 

<div id="contact_add_edit_page" class="content-inner">
    
<?php

	echo '<div id="" class="" style="color:red;">'.validation_errors(). '</div>';
	echo form_open_multipart($action, $form_attributes);
	echo '<div id="sid-wrapper" class="field-wrapper">'. $id . '</div>';
        
?>

<script>

function showmore(clobj)
{		
	$('#contact_more').css('display','block');
	$('#showmore').css('display','none');	
}
function showless(clobj)
{		
	$('#contact_more').css('display','none');
	$('#showmore').css('display','block');	
}

</script>

<div class="contact_add">
    <div class="row">
		<div class="col-xs-12 col-sm-4 col-md-3"></div>
		<div class="col-xs-12 col-sm-4 col-md-3">
	        <?php echo $company_name ; ?>
	        <?php echo $company_email ; ?>
			<?php echo $company_address ; ?>	
		</div>
		<div class="col-xs-12 col-sm-4 col-md-3">
			<?php echo $contact_number ; ?>
			<?php echo $category_list ; ?>
			<?php echo $company_city ; ?>
			<a id="showmore" class="less" href="#" onclick="showmore(this);">Show More</a>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-3"></div>
    </div>


	<div class="row" id="contact_more" style="display:none">
		<div class="col-xs-12 col-sm-4 col-md-3"></div>
		<div class="col-xs-12 col-sm-4 col-md-3">
			<?php echo  $image_id ;
			if(isset($image_file)){
				echo '<div id="sid-wrapper" class="field-wrapper file">'. $image . '<div id="fakefile1" class="fakefile">'.$image_file.'</div></div>';
			}else{
				echo '<div id="sid-wrapper" class="field-wrapper file">'. $image . '<div id="fakefile1" class="fakefile">Upload Images....</div></div>';
			}?>
			<?php echo $company_notes; ?>	
		</div>
		<div class="col-xs-12 col-sm-4 col-md-3">
			<?php echo $company_country ; ?>
			<?php echo $company_website ; ?>
			<a id="showless" class="less" href="#" onclick="showless(this);">Show Less</a>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-3"></div>
    </div>

    <p>&nbsp;</p>

    <div class="row">
        <div class="col-xs-9 col-sm-10 col-md-9"></div>
        <div class="col-xs-3 col-sm-2 col-md-3"><?php echo $submit ; ?></div>        
    </div>
</div>


<?php
	echo form_close();
?>
</div>