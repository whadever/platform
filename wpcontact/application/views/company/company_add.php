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
	$category_options = $cat_options;
	$catid =isset($category_id) ? $category_id : 0;        
	//$companyid= isset($company->category_id) ? $company->category_id : $cid;
	$companyid= isset($company->category_id) ? $company->category_id : '';
	$companyid = explode('|',$companyid);
	$companyid = array_slice($companyid,1,count($companyid)-2);
	$company_js = 'id="company_id" onChange="" class="form-control selectpicker1" required="true" multiple';
	$category_list = form_label('Category', 'category_id');
	$category_list .= form_dropdown('category_id[]', $category_options, $companyid, $company_js);

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
$(document).ready(function(){
	$("#edit-submit").click(function () {
		cid = $('.category_id').val();
		if(cid==null){
			alert('Please fill out this field.');
		}	
    });
});
function contact_showmore(clobj)
{
	var index = $(".show_contact_more").index($(clobj));
	$('.contact_div_two:eq('+index+')').css('display','block');
	$(clobj).hide();
	return false;
}
function contact_showless(clobj)
{
	var index = $(".show_contact_less").index($(clobj));
	$('.contact_div_two:eq('+index+')').css('display','none');
	$('.show_contact_more:eq('+index+')').css('display','block');
	return false;
}
function addNewContact() {
	var html = $("#contactHtml").html();
	$("#add_contact_div").append(html);
}
function removeContactForm(el){
	$(el).parents('.new-contact-div').remove();
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
			<a id="showmore" class="less" href="#" onclick="showmore(this);return false;">Show More</a>
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
			<a id="showless" class="less" href="#" onclick="showless(this);return false;">Show Less</a>
		</div>
		<div class="col-xs-12 col-sm-4 col-md-3"></div>
    </div>

    <p>&nbsp;</p>
	<div class="row">
		<div class="col-xs-12 col-sm-4 col-md-3"></div>
		<div class="col-xs-12 col-sm-4 col-md-3">
			<span style=""><a href="<?php echo base_url(); ?>contact/contact_add" onclick="addNewContact();return false;">Add Contact <img src="<?php echo base_url() ?>images/icons/icon_add_contact.png" width="40" /></a> </span>
		</div>
	</div>
	<!--adding contact form here-->
	<div class="contact_add" id="add_contact_div">

	</div>
	<!---------------------------->
    <div class="row">
        <div class="col-xs-9 col-sm-10 col-md-9"></div>
        <div class="col-xs-3 col-sm-2 col-md-3"><?php echo $submit ; ?></div>        
    </div>
</div>


<?php
	echo form_close();
?>
</div>

<script>
	$(document).ready(function() {

		$("#company_id").selectpicker();
		$("#company_id").css('display','block');

	});
</script>

<div id="contactHtml" style="display: none">
	<div class="new-contact-div">
		<div class="row">
			<div class="col-xs-12 col-sm-4 col-md-3"></div>
			<div class="col-xs-12 col-sm-4 col-md-3" style="color: black; font-size: 24px; margin: 10px 0;">Add Contact:</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<img src="<?php echo base_url().'images/delete.png'; ?>" style="cursor: pointer; float: right; margin-top: 10px;" onclick="removeContactForm(this);">
			</div>
		</div>
		<div class="row contact_div_one">
			<div class="col-xs-12 col-sm-4 col-md-3"></div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<label for="contact_title">Position</label><input type="text" required="1" placeholder="Enter Contact Position" class="form-control" value="" name="contact_title[]">
				<label for="contact_first_name">First Name</label><input type="text" required="1" placeholder="Enter First Name" class="form-control" value="" name="contact_first_name[]">
				<label for="contact_last_name">Last Name</label><input type="text" required="1" placeholder="Enter Last Name" class="form-control" value="" name="contact_last_name[]">


			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<label for="contact_phone_number">Phone Number</label><input type="text" placeholder="Enter Phone Number" class="form-control" value="" name="contact_phone_number[]">
				<label for="contact_mobile_number">Mobile Number</label><input type="text" placeholder="Enter Mobile Number" class="form-control" value="" name="contact_mobile_number[]">
				<label for="contact_email">Email Address</label><input type="text" placeholder="Enter Email Address" class="form-control" value="" name="contact_email[]">
				<label for="category_id">Category</label>
				<select required="true" class="form-control selectpicker1" onchange="" name="contact_category_id[]">
					<option selected="selected" value="">--Select Category--</option>
					<?php foreach($cat_options as $id => $cat): ?>
						<option value="<?php echo $id; ?>"><?php echo $cat; ?></option>
					<?php endforeach; ?>
				</select>
				<a onclick="contact_showmore(this);return false;" href="" class="show_contact_more">Show More</a>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3"></div>
		</div>


		<div style="display:none" class="row contact_div_two">
			<div class="col-xs-12 col-sm-4 col-md-3"></div>
			<div class="col-xs-12 col-sm-4 col-md-3">

				<input type="hidden" value="" name="image_id">
				<div class="field-wrapper file"><label for="upload_image">Image</label><input type="file" class="form-file form-control" name="contact_upload_image[]">
					<div class="fakefile" >Upload Images....</div></div>
				<label for="contact_notes">Notes</label><textarea size="60" class="form-control" rows="5" cols="20" name="contact_notes[]"></textarea>

			</div>
			<div class="col-xs-12 col-sm-4 col-md-3">
				<label for="contact_address">Address</label><input type="text" placeholder="Enter Address" class="form-control"  value="" name="contact_address[]">
				<label for="contact_city">City</label><input type="text" placeholder="Enter City" class="form-control" value="" name="contact_city[]">
				<label for="contact_country">Country</label><input type="text" placeholder="Enter Country" class="form-control" value="" name="contact_country[]">
				<label for="contact_website">Website</label><input type="text" placeholder="Enter Website Address" class="form-control"  value="" name="contact_website[]">
				<a onclick="contact_showless(this);return false;" href="" class="show_contact_less" >Show Less</a>
			</div>
			<div class="col-xs-12 col-sm-4 col-md-3"></div>
		</div>

		<p>&nbsp;</p>
	</div>
</div>