<?php
	$form_attributes = array('class' => 'development_add-form', 'id' => 'development_add-entry-form','method'=>'post');
	$upload_form_attributes = array('class' => 'development_add-form', 'id' => 'upload_feature_photo_form','method'=>'post');
	$upload_action = 'admindevelopment/upload_development_feature_photo';

	$id = form_hidden('id', isset($admindevelopment->id) ? $admindevelopment->id : '');

	$development_name = form_label('Development Name', 'development_name');
	$development_name .= form_input(array(
	          'name'        => 'development_name',
	          'id'          => 'edit-development_name',
	          'value'       => isset($admindevelopment->development_name) ? $admindevelopment->development_name : '',
	          'class'       => 'form-text',
	          'required'    => TRUE
	));

	$development_location = form_label('Development Location', 'development_location');
	$development_location .= form_input(array(
	          'name'        => 'development_location',
	          'id'          => 'edit-development_location',
	          'value'       => isset($admindevelopment->development_location) ? $admindevelopment->development_location : '',
	          'class'       => 'form-text',
	          'required'    => TRUE
	));

	$development_city_option = array(
                  'Christchurch'  => 'Christchurch',
                  'Auckland'    => 'Auckland'
                );
	$js = 'required=""';
	$development_city_default = isset($admindevelopment->development_city) ? $admindevelopment->development_city: '';
    $development_city_options  = array('' => '-- Choose City --') + $development_city_option;
	$development_city = form_label('Development City', 'development_city');	
	$development_city .= form_dropdown('development_city', $development_city_options, $development_city_default, $js);

	$development_size = form_label('Development Size', 'development_size');
	$development_size .= form_input(array(
	          'name'        => 'development_size',
	          'id'          => 'edit-development_size',
	          'value'       => isset($admindevelopment->development_size) ? $admindevelopment->development_size : '',
	          'class'       => 'form-text',
	          'required'    => TRUE
	));

	$number_of_stages = form_label('Number Of Stages', 'number_of_stages');
	$number_of_stages .= form_input(array(
	          'name'        => 'number_of_stages',
	          'id'          => 'edit-number-of-stages',
	          'value'       => isset($admindevelopment->number_of_stages) ? $admindevelopment->number_of_stages : '',
	          'class'       => 'form-text',
	          'required'    => TRUE
	));

	$number_of_lots = form_label('Number Of Lots', 'number_of_lots');
	$number_of_lots .= form_input(array(
	          'name'        => 'number_of_lots',
	          'id'          => 'edit-number-of-lots',
	          'value'       => isset($admindevelopment->number_of_lots) ? $admindevelopment->number_of_lots : '',
	          'class'       => 'form-text',
	          'required'    => TRUE
	));
	

	$land_zone = form_label('Land Zone', 'land_zone');
	$land_zone .= form_input(array(
	          'name'        => 'land_zone',
	          'id'          => 'edit-land_zone',
	          'value'       => isset($admindevelopment->land_zone) ? $admindevelopment->land_zone : '',
	          'class'       => 'form-text',
	          'required'    => TRUE
	));

	$ground_condition = form_label('Ground Condition', 'ground_condition');
	$ground_condition .= form_input(array(
	          'name'        => 'ground_condition',
	          'id'          => 'edit-ground_condition',
	          'value'       => isset($admindevelopment->ground_condition) ? $admindevelopment->ground_condition : '',
	          'class'       => 'form-text',
	          'required'    => TRUE
	));

	$status_option = array(
                  '0'  => 'Open',
                  '1'    => 'Close'
                );
	$status_default = isset($admindevelopment->status) ? $admindevelopment->status: '';
	$status = form_label('Status', 'status');	
	$status .= form_dropdown('status', $status_option, $status_default);

	$project_manager = form_label('Project Manager', 'project_manager');
	$project_manager .= form_input(array(
	          'name'        => 'project_manager',
	          'id'          => 'edit-project_manager',
	          'value'       => isset($admindevelopment->project_manager) ? $admindevelopment->project_manager : '',
	          'class'       => 'form-text',
	          'required'    => TRUE
	));

	$civil_engineer = form_label('Civil Engineer', 'civil_engineer');
	$civil_engineer .= form_input(array(
	          'name'        => 'civil_engineer',
	          'id'          => 'edit-civil-engineer',
	          'value'       => isset($admindevelopment->civil_engineer) ? $admindevelopment->civil_engineer : '',
	          'class'       => 'form-text',
	              'required'    => TRUE
	));

	$civil_manager = form_label('Civil Manager', 'civil_manager');
	$civil_manager .= form_input(array(
	          'name'        => 'civil_manager',
	          'id'          => 'edit-civil-manager',
	          'value'       => isset($admindevelopment->civil_manager) ? $admindevelopment->civil_manager : '',
	          'class'       => 'form-text',
	          'required'    => TRUE
	));

	$geo_tech_engineer = form_label('Geo Tech Engineer', 'geo_tech_engineer');
	$geo_tech_engineer .= form_input(array(
	          'name'        => 'geo_tech_engineer',
	          'id'          => 'edit-geo_tech_engineer',
	          'value'       => isset($admindevelopment->geo_tech_engineer) ? $admindevelopment->geo_tech_engineer : '',
	          'class'       => 'form-text',
	          'required'    => TRUE
	));
	
	$feature_photo = form_label('', 'feature_photo');
	$feature_photo .= form_upload(array(
	              'name'        => 'feature_photo',
	              'id'          => 'feature_photo',
	              'class'       => 'form-file',
	              'type'        => 'file',
	));

	//$submit = form_label(' ', 'submit');
	$submit = form_submit(array(
	          'name'        => 'submit',
	          'id'          => 'edit-submit',
	          'value'       => 'Next',
	          'class'       => 'form-submit',
	          'type'        => 'submit',
	          //'onclick'     => 'checkEmail();',
	));

?>

<div class="row">
	<div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-xs-12"><h4 class="jtitle"><?php echo $title; ?></h4></div>
</div>

<form id="add_job" action="<?php echo base_url(); ?>admindevelopment/development_add" method="post">
	<div class="row">
		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<label for="job_name" class="control-label">Name</label>
			<input type="text" name="development_name" class="form-control" required="required" />
			<label for="job_name" class="control-label">City</label>
			<select name="development_city" class="form-control">
				<option value="SelectaCity">---Select a City---</option>
				<option value="Christchurch">Christchurch</option>
				<option value="Auckland">Auckland</option>
			</select>
			<label for="job_name" class="control-label">Number of Stages</label>
			<input type="text" name="number_of_stages" class="form-control" />
			<label for="job_name" class="control-label">Land Zone</label>
			<input type="text" name="land_zone" class="form-control" />
			<label for="job_name" class="control-label">Project Manager</label>
			<input type="text" name="project_manager" class="form-control" />
			<label for="job_name" class="control-label">Civil Manager</label>
			<input type="text" name="civil_manager" class="form-control" />
		</div>
		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<label for="job_name" class="control-label">Location</label>
			<input type="text" name="development_location" class="form-control" />
			<label for="job_name" class="control-label">Job Size</label>
			<input type="text" name="development_size" class="form-control" />
			<label for="job_name" class="control-label">Number if Lots (if applicable)</label>
			<input type="text" name="number_of_lots" class="form-control" />
			<label for="job_name" class="control-label">Ground Condition</label>
			<input type="text" name="ground_condition" class="form-control" />
			<label for="job_name" class="control-label">Civil Engineer</label>
			<input type="text" name="civil_engineer" class="form-control" />
			<label for="job_name" class="control-label">Geo Tech Engineer</label>
			<input type="text" name="geo_tech_engineer" class="form-control" />
		</div>
	</div>

	<div class="row">
		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12"></div>
		<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-xs-12">
			<div class="btn_box">
				<a href="<?php echo base_url() ?>job"><input type="button" class="btn btn-default clear-button" name="cancel" value="Cancel"></a>
				<input type="submit" name="submit" value="Next" class="btn btn-default submit-button" >
			</div>
		</div>
	</div>
</form>



<script type="text/javascript">
    
	$(document).ready(function () {

		 $('#feature_photo').on('change', function(){             
            var options = { 
                target:     '#preview',     
                success:    function() { 
                    var photo_id= $('#development_photo_id').val();
                    $('#photo_insert_id').val(photo_id);         
                } 
            }; 

            $("#preview").html('');
            $("#preview").html('<img src="<?php echo base_url();?>images/loader.gif" alt="Uploading...."/>');
            $("#upload_feature_photo_form").ajaxForm(options).submit();
	            
	     });	   
		
		
	});
</script>