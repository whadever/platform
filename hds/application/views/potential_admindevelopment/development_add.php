<?php
	$form_attributes = array('class' => 'development_add-form', 'id' => 'development_add-entry-form','method'=>'post');
	$upload_form_attributes = array('class' => 'development_add-form', 'id' => 'upload_feature_photo_form','method'=>'post');
	$upload_action = 'potential_admindevelopment/upload_development_feature_photo';

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

<div class="development-add-home" style="background: #fff;">
<div class="development-header">
	<div class="development-title">
		<div class="all-title"><?php echo $title; ?><span>Development Info</span></div>
		<div class="title-inner">Home > development Info</div>
	</div>
	<?php if(!isset($admindevelopment->id)){ ?>
	<div class="start-over">
		<a href="<?php echo base_url();?>potential_admindevelopment/development_start">Start Over</a>
	</div>
	<?php } ?>
</div>
<?php

	echo '<div id="" class="" style="color:red;">'.validation_errors(). '</div>';
	
echo '<div class="development"><div class="development_add">';
	echo form_open_multipart($action, $form_attributes);	
	echo form_fieldset('',array('class'=>"development_add-info-fieldset"));
	echo '<div id="did-wrapper" class="field-wrapper">'. $id . '</div>';
	echo '<div id="development_name-wrapper" class="field-wrapper">'. $development_name . '</div>';    
    echo '<div id="development_location-wrapper" class="field-wrapper">'. $development_location . '</div>';
	echo '<div id="development_city-wrapper" class="field-wrapper">'. $development_city . '</div>';
    echo '<div id="development_size-wrapper" class="field-wrapper">'. $development_size . '</div>';        
    echo '<div id="number_of_stages-wrapper" class="field-wrapper">'. $number_of_stages . '</div>';
    echo '<div id="number_of_lots-wrapper" class="field-wrapper">'. $number_of_lots . '</div>';
    echo '<div id="land_zone-wrapper" class="field-wrapper">'. $land_zone . '</div>';
    echo '<div id="ground_condition-wrapper" class="field-wrapper">'. $ground_condition . '</div>';
    echo '<div id="status-wrapper" class="field-wrapper">'. $status . '</div>';
    echo form_fieldset_close(); 
        
    echo form_fieldset('',array('class'=>"development-add-manager-fieldset"));
    echo '<div id="project_manager-wrapper" class="field-wrapper">'. $project_manager . '</div>';
    echo '<div id="civil_engineer-wrapper" class="field-wrapper">'. $civil_engineer . '</div>';        
    echo '<div id="civil_manager-wrapper" class="field-wrapper">'. $civil_manager . '</div>';
    echo '<div id="geo_tech_engineer-wrapper" class="field-wrapper">'. $geo_tech_engineer . '</div>';
	echo form_fieldset_close();
?> 
	<input type="hidden" name="photo_insert_id" id="photo_insert_id" value="<?php if(isset($admindevelopment->fid)){echo $admindevelopment->fid;} ?>"/>
	<input type="hidden" name="tid" id="tid" value="<?php if(isset($admindevelopment->tid)){echo $admindevelopment->tid;} ?>"/>
<?php	
	echo '<div class="back-next">';
	echo '<a class="brand" onclick="window.history.go(-1)">Back</a>';
	echo '<div class="submit">'. $submit . '</div>';
	echo '<div class="clear"></div>'; 
echo '</div>';
echo '</div>';

echo form_close();	
	
echo '<div class="development_image">';
echo form_open_multipart($upload_action, $upload_form_attributes);
echo $feature_photo;
echo '<input type="hidden" name="dev_photo_id" id="dev_photo_id" value="'.$admindevelopment->fid.'"/>';
echo form_close();
echo '<div id="preview" style="height:306px;">';
	if(isset($file->filename)){
		 echo '<img width="100%" height="306" src="'.base_url().'uploads/development/'.$file->filename.'"/>';
	}
echo '</div>';
echo '</div></div>'; 
echo '<div class="clear"></div>'; 

//echo '<div class="back-next">';
	//echo '<a class="brand" onclick="window.history.go(-1)">Back</a>'; 
	//echo '<div class="submit">'. $submit . '</div>';
//echo '</div>';
	

?>

</div>
<script type="text/javascript">
    
	$(document).ready(function () {

		 $('#feature_photo').on('change', function(){             
            var options = { 
                target:     '#preview',     
                success:    function() { 
                    var photo_id= $('#development_photo_id').val();
                    $('#photo_insert_id').val(photo_id);  
					$('#dev_photo_id').val(photo_id);       
                } 
            }; 

            $("#preview").html('');
            $("#preview").html('<img src="<?php echo base_url();?>images/loader.gif" alt="Uploading...."/>');
            $("#upload_feature_photo_form").ajaxForm(options).submit();
	            
	     });	   
		
		
	});
</script>