<?php 
//if (isset($message)) {echo $message;}  
//$user=  $this->session->userdata('user');  
//$user_role_id =$user->rid;
?>
<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php if (isset($title)){ echo $title; } ?></span>
			<input type="button" value="Back" class="btn btn-default add-button" onclick="history.go(-1);"/>
		</div>
	</div>
</div>
<div class="breadcrumb-box">
<?php
$this->breadcrumbs->push('Users', 'user/user_list'); 
$this->breadcrumbs->push($username, 'user/user_detail/'.$user_id);
echo $this->breadcrumbs->show();  
?>
</div>

<div id="user_list_view" class="content-inner">
	<?php if(isset($table)) { echo $table;	} ?>


<div class="row  button-wrapper">
		<div class="col-md-2"></div>
		<div class="col-md-2"></div>
		<div class="col-md-4">
                   
    <?php  $user=  $this->session->userdata('user');  //print_r($user); ?>
    <div style="margin:10px; text-align:center">
        <?php // echo $user->name; echo '<br />'; echo $user->email; ?>
        <p>&nbsp;</p>
        <div class="button-wrapper">
          <a class="btn btn-default" href="<?php echo base_url();?>user/user_update/<?php echo $user_id;?>">Update Preference</a>  
        </div>

    </div>
		</div>
		<div class="col-md-2"></div>
		<div class="col-md-2"></div>
	</div>

    
    
</div>