<?php if (isset($message)) {echo $message;}  ?>
<!--<div class="button-wrapper">
    
            <?php echo anchor('user/user_add','Add User',array('class'=>'btn btn-default')); ?>
      
       
</div>

<div class="button">
            <?php //echo anchor('user/user_role_add','Add User Role',array('class'=>'add')); ?>
    </div>  


<div class="all-title">
   <?php if (isset($title)){ echo $title; } ?>
</div>-->
<style>
td, th {
    padding: 0 4px;
}

table tbody td {
    border-bottom: 1px solid #eeeeee;
	border-right: 1px solid #eeeeee;
}
</style>


<div class="development-header">
		<div class="development-title" style="width:100%; margin-bottom:10px;">
			<div class="all-title"><?php if (isset($title)){ echo $title; } ?></div>
		</div>
	</div>
<div class="clear"></div>
<?php
        $form_attributes = array('class' => 'search-form', 'id' => 'user-search-form','method'=>'get');
	$get = $_GET;       
        
	$uname = form_label('User Name :', 'uname');
	$uname .= form_input(array(
	              'name'        => 'uname',
	              'id'          => 'edit-uname',
	              'value'       => isset($get['uname']) ? $get['uname'] : '',
	              'class'       => 'form-text',
			//	  'required'    => TRUE
	));
        
	$submit = form_label('', 'submit');        
	$submit .= form_submit(array(
	              'name'        => 'submit',
	              'id'          => 'edit-search',
	              'value'       => 'Find User',
	              'class'       => 'form-submit btn btn-default',
	              'type'        => 'submit',
	));
	
    echo form_open($action, $form_attributes);
	//echo form_fieldset('Search User',array('class'=>"search-fieldset"));
	echo '<div id="uname-wrapper" class="field-wrapper">'. $uname . '</div>';
    echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
	
	//echo form_fieldset_close(); 
	echo form_close();
?>

<div class="clear"></div>

<?php if (isset($user_table)){ echo $user_table; } ?>

<div class="clear"></div>
<p>&nbsp;</p>
<div class="all-title">
    <?php if (isset($role_title)){ echo $role_title; } ?>
</div>

<?php if (isset($user_role_table)){ echo $user_role_table; } ?>


