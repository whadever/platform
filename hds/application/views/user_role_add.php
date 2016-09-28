<div class="button-wrapper">
    <div class="button">
            <?php echo anchor('user/user_list','User List',array('class'=>'list')); ?>
    </div>        
</div>

<div class="clear"></div>

<?php
	$form_attributes = array('class' => 'user-role-add-form', 'id' => 'entry-form','method'=>'post');

	$rid = form_hidden('rid', isset($urole->rid) ? $urole->rid : '');

	$rname = form_label('User Role Name', 'rname');
	$rname .= form_input(array(
	              'name'        => 'rname',
	              'id'          => 'edit-rname',
	              'value'       => isset($urole->rname) ? $urole->rname : '',
	              'class'       => 'form-text',
                  'required'    => TRUE
	));
	
	$rdesc = form_label('Role Description', 'rdesc');
	$rdesc .= form_input(array(
	              'name'        => 'rdesc',
	              'id'          => 'edit-rdesc',
	              'value'       => isset($urole->rdesc) ? $urole->rdesc : '',
	              'class'       => 'form-text',
				  'required'    => TRUE

	));

	$submit = form_submit(array(
	              'name'        => 'submit',
	              'id'          => 'edit-submit',
	              'value'       => 'Submit',
	              'class'       => 'form-submit',
	              'type'        => 'submit',
	));

	echo validation_errors();
	echo form_open($action, $form_attributes);
	echo form_fieldset('User Role Add',array('class'=>"user-role-add-fieldset"));
	echo '<div id="rid-wrapper" class="field-wrapper">'. $rid . '</div>';
	echo '<div id="rname-wrapper" class="field-wrapper">'. $rname . '</div>';
	echo '<div id="rdesc-wrapper" class="field-wrapper">'. $rdesc . '</div>';
	echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
	echo form_fieldset_close(); 
	echo form_close();
?>



