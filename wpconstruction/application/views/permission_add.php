<?php

/**
 * page variable
 * 
 * $title       : page title
 * $action      : form action comes from controller
 * $controllers : an array of controllers defined in mbs.php config file such as employee, company, loan etc
 * $operations  : an array of operations defined in mbs.php config file like add, update, delete etc
 * $rid         : user role id
 * 
 * $db_controllers: an array of controllers retrieve from database based on user role id
 * 
 * 
 */
 ?>

<?php if (isset($message)) {echo $message;}  ?>


<div class="all-title">
    <?php echo $title; ?>
</div>
<div class="clear"></div>

<?php


$output = '<table>';
$output .= '<tr>';
$output .= '<th>Operations</th><th>Manager</th>';
$output .='</tr>';


foreach ($controllers as $controller) {
    $output .='<th class="header" colspan="2"> SECTION - ' . strtoupper($controller) . '</th>';
    foreach ($operations as $op) {  
        $checked = !empty($db_controllers) && array_key_exists("$controller $controller" .'_'. $op, $db_controllers) ? TRUE: FALSE;
        $row ='<tr>';
        $row .= '<td>' . $controller . ' ' . $op . '</td>';
        $row .= '<td>' . form_checkbox(array('name'=> $controller . '['.$op.']','checked'=>$checked)) . '<td>';
        $row .= '</tr>';
        $output .= $row;        
    }
}


    $output .= '</table>';
    $form_attributes = array('class' => 'add-form', 'id' => 'permisson-add-form');
    $get = $_GET;
    // $ename = form_label('Employee Name :', 'ename');
    $role_id = form_hidden('rid',$rid);
    
    $submit = form_submit(array(
            'name'        => 'submit',
            'id'          => 'edit-search',
            'value'       => 'Add Permission',
            'class'       => 'form-submit',
            'type'        => 'submit',
    ));

    echo form_open($action, $form_attributes);
    echo form_fieldset('Manage Add',array('class'=>"search-fieldset"));
    echo $role_id;
    echo '<div id="perm-wrapper" class="field-wrapper">'. $output . '</div>';
    echo $submit;
    echo form_fieldset_close(); 
    echo form_close();
?>
