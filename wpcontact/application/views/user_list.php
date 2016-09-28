<script>
$(document).ready(function() {
    
    $("#infoMessage").fadeTo(5000, 500).slideUp(500, function(){
          $('#infoMessage').remove();
          //$("#success-alert").alert('close');
    }); 
    
    $('.clickdiv').click(function(){
        $('.hiders').slideToggle();
        $('#minus').toggle();
        $('#plus').toggle();
    });
    
    $('.delete').click(function(){
    var id=$(this).data('id');
    $('#modalDelete').attr('href','user_delete/'+id);
})
            
 });
</script>


<!--
<div class="button">
            <?php //echo anchor('user/user_role_add','Add User Role',array('class'=>'add')); ?>
    </div>  

-->
<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php if (isset($title)){ echo $title; } ?></span>
			<?php echo anchor('user/user_add','Add User',array('class'=>'btn btn-default add-button')); ?>
		</div>
	</div>
</div>

<div class="clear"></div>
<div id="user-page" class="content-inner"> 

 <div class="row">
    <div class="col-md-12"> 
        <div id="infoMessage">

        <?php if($this->session->flashdata('success-message')){ ?>

        <div class="alert alert-success" id="success-alert">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Success! </strong>
        <?php echo $this->session->flashdata('success-message');?>
        </div>    
        <?php } ?>

        <?php if($this->session->flashdata('warning-message')){ ?>

        <div class="alert alert-warning" id="warning-alert">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Success! </strong>
        <?php echo $this->session->flashdata('warning-message');?>
        </div>    
        <?php } ?>

        </div>
    </div>
</div>   
    
    
<div class="row">
    <div class="col-md-12">
            <div class="searchbox">
                <div class="clickdiv" style="background:#EBEBEB;padding: 5px;">
                    <strong> 
                        <span> Search </span>
                        <span id="plus">+</span>
                        <span id="minus" style="display:none;">-</span>
                    </strong>
                </div> 
    <div class="hiders" style="display:none;" > 
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
	//echo '<div id="uname-wrapper" class="field-wrapper">'. $uname . '</div>';
        //echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
	
	//echo form_fieldset_close(); 
    ?>
        <div class="row"> 
            <div class="col-xs-12 col-sm-6 col-md-6">               
                <?php echo '<div id="uname-wrapper" class="field-wrapper">'. $uname . '</div>'; ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">               
                <?php   echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>'; ?>
            </div>
           
        </div>
   
<?php   
	echo form_close();
?>
</div>
            </div>
    </div>
</div>
     <hr/>         
        
        
        

<div class="clear"></div>

<?php if (isset($user_table)){ echo $user_table; } ?>

<div class="clear"></div>
<p>&nbsp;</p>
<div class="all-title">
    <h3><?php if (isset($role_title)){ echo $role_title; } ?></h3>
</div>

<?php if (isset($user_role_table)){ echo $user_role_table; } ?>

</div>


<div class="modal small fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                 <h3 id="deleteModalLabel">Delete User</h3>

            </div>
            <div class="modal-body">
                <p class="error-text">
                    <i class="fa fa-warning modal-icon"></i>
                    <strong> Are you sure you want to delete this User? </strong></p>
            </div>
            <div class="modal-footer">
               <button class="btn btn-default"data-dismiss="modal" aria-hidden="true">Cancel</button> 
               <a href="#" class="btn btn-danger"  id="modalDelete" >Delete</a>

            </div>
        </div>
    </div>
</div>
