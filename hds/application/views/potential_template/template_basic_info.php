<?php
	$form_attributes = array('class' => 'template_basic_info-form', 'id' => 'template_basic_info-entry-form','method'=>'post');

	$id = form_hidden('id', isset($template->id) ? $template->id : '');
	
	$no_phases = form_hidden('no_phases', '3');
	$no_tasks = form_hidden('no_tasks', '3');

	$template_name = form_label('Template Name', 'template_name');
	$template_name .= form_input(array(
	          'name'        => 'template_name',
	          'id'          => 'edit-template_name',
	          'value'       => isset($template->template_name) ? $template->template_name : '',
	          'class'       => 'form-text',
	          'required'    => TRUE
	));

	$template_options = array(
	      '1' => 'Development',
		  '2' => 'Stage',
	);
    $template_default = isset($template->template_type) ? $template->template_type : 1;
	$template_type = form_label('Template Type', 'template_type');
	$template_type .= form_dropdown('template_type', $template_options,$template_default);
	
	$submit = form_submit(array(
	          'name'        => 'submit',
	          'id'          => 'edit-submit',
	          'value'       => 'Next',
	          'class'       => 'form-submit',
	          'type'        => 'submit',

	));
?>
<div class="clear"></div>
<?php echo form_open_multipart($action, $form_attributes); ?>

<div class="template-basic-info" style="background: #fff;">
	<div class="template-header">
			<div class="template-title">
				<div class="all-title"><?php echo $title; ?></div>
			</div>	
			<div class="start-page"><p>Basic Info</p></div>
			<?php if(!isset($template->id)) : ?>
			<div class="start-over">
				<a href="<?php echo base_url();?>potential_template/template_start">Start Over</a>
			</div>	
			<?php endif; ?>
			<div class="clear"></div>		
	</div>
	<div class="title-inner">Start Page > Basic Info</div>
<div class="clear"></div>

	<div class="template-body">
	
<?php
	echo '<div id="id-wrapper" class="field-wrapper">'. $id . '</div>';
	echo '<div id="template_name-wrapper" class="field-wrapper">'. $template_name . '</div>';    
    echo $no_phases;
    echo $no_tasks;        
    echo '<div id="template_type-wrapper" class="field-wrapper">'. $template_type . '</div>';
?>
	<div class="clear"></div>	
	</div>
	
<div class="clear"></div>

	<div class="template-footer">
			<a class="back" onclick="window.history.go(-1)">Back</a>
			<?php echo $submit; ?>
		<div class="clear"></div>
	</div>
<div class="clear"></div>
</div>

<?php echo form_close(); ?>

