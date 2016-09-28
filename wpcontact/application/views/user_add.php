<!--<div class="button-wrapper">
    <div class="button">
            <?php //echo anchor('user/user_list','User List',array('class'=>'list')); ?>
    </div>        
</div>
-->
<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php if (isset($title)){ echo $title; } ?></span>
			<input type="button" value="Back" class="btn btn-default add-button" onclick="history.go(-1);"/>
		</div>
	</div>
</div>
<div class="clear"></div>

<?php   
            
	$this->breadcrumbs->push('Users', 'user/user_list');
        if(isset($user_info->uid)){
            $this->breadcrumbs->push($user_info->username, 'user/user_detail/'.$user_info->uid);
            $this->breadcrumbs->push('Modify User', 'user/user_update/'.$user_info->uid);
        }else{
            $this->breadcrumbs->push('User Add', 'user/user_add/');
        }
        
        
    ?>
<div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div> 
<div id="project_add_edit_page" class="content-inner"> 

<?php
	$form_attributes = array('class' => 'user-add-form', 'id' => 'entry-form','method'=>'post');
        $userid= isset($user_info->uid) ? $user_info->uid : 0;

	$uid = form_hidden('uid', isset($user_info->uid) ? $user_info->uid : '');
	
	
        if($userid>0){$form_label = 'Update User';}else{ $form_label='Add User';}
	
	$name = form_label('User Name', 'name');
	$name .= form_input(array(
	              'name'        => 'name',
	              'id'          => 'edit-name',
	              'value'       => isset($user_info->username) ? $user_info->username : '',
	              'class'       => 'form-control',
                  'required'    => TRUE
	));
	
	$pass = form_label('User Password', 'pass');
	$pass .= form_password(array(
	              'name'        => 'pass',
	              'id'          => 'edit-pass',
	              'value'       => '',
	              'class'       => 'form-control',
		      'autocomplete'	=> 'off',
				  'required'    => TRUE

	));
	
	$email = form_label('User Email', 'email');
	$email .= form_input(array(
	              'name'        => 'email',
	              'id'          => 'edit-email',
	              'value'       => isset($user_info->email) ? $user_info->email : '',
	              'class'       => 'form-control',
				  'required'    => TRUE

	));
	
	$ci = & get_instance();
	$ci->load->model('user_model');
	//$user_options = $ci->user_model->user_role_load();

    //$user_default = isset($user_info->rid) ? $user_info->rid : 0;
    //$role_js= 'class="form-control selectpicker"';
	//$access = form_label('User Role', 'rid');
	//$access .= form_dropdown('rid', $user_options,$user_default, $role_js);
	
	$state_options = array(
	      '1' => 'Active',
		  '0' => 'Block',
	);
	$state_default = isset($user_info->statue) ? $user_info->statue : '';
        $state_js= 'class="form-control selectpicker"';
	$state = form_label('User Status', 'status');
        
	$state .= form_dropdown('status', $state_options, $state_default, $state_js);

	$submit = form_label('', 'submit');
        $submit .= form_submit(array(
	              'name'        => 'submit',
	              'id'          => 'edit-submit',
	              'value'       => 'Submit',
	              'class'       => 'btn btn-default',
	              'type'        => 'submit',
	));
        $user=  $this->session->userdata('user');
        
        $logged_user_id= $user->uid;
        $user_role_id =$user->rid; 
        if (isset($message)) {echo $message;}
	echo validation_errors();
	echo form_open($action, $form_attributes);
	//echo form_fieldset($form_label, array('class'=>"user-add-fieldset"));
	echo '<div id="uid-wrapper" class="field-wrapper">'. $uid . '</div>';
        ?>
    <div class="row"> 
        <div class="col-xs-12 col-sm-6 col-md-6">
            <?php echo '<div id="name-wrapper" class="field-wrapper">'. $name . '</div>'; ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            
        </div>
    </div>
    
    <!---<div class="row"> 
        <div class="col-xs-12 col-sm-6 col-md-6">
            <?php  if($userid == 0 || $userid == $logged_user_id){
            echo '<div id="pass-wrapper" class="field-wrapper">'. $pass . '</div>';
            } ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            
        </div>
    </div>--->
    
    <div class="row"> 
        <div class="col-xs-12 col-sm-6 col-md-6">
            <?php echo '<div id="email-wrapper" class="field-wrapper">'. $email . '</div>'; ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            
        </div>
    </div>
    
    <!---<div class="row">
        <?php if($user_role_id==1){ ?>  
        <div class="col-xs-12 col-sm-6 col-md-6">
         <?php echo '<div id="access-wrapper" class="field-wrapper">'. $access . '</div>'; ?>   
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
         
        </div>
        <?php } ?>  
    </div>
    
    <div class="row">
        <?php if($user_role_id==1){ ?>  
        <div class="col-xs-12 col-sm-6 col-md-6">
           <?php echo '<div id="access-wrapper" class="field-wrapper">'. $state . '</div>'; ?>  
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
          
        </div>
        <?php } ?>  
    </div>--->
    
    <div class="row"> 
        <div class="col-xs-12 col-sm-6 col-md-6" style="text-align: right;">
            <?php echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>'; ?>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            
        </div>
    </div>
    
    
    <?php
    
        
	
        
	echo form_fieldset_close(); 
	echo form_close();
?>

</div>



 