
<?php if (isset($message)) {echo $message;}  ?>
<div class="all-title">
<?php echo $title; ?>
</div>

<div id="user_list_view" class="table-border">
	<?php if(isset($table)) { echo $table;	} ?>
</div>
<div><h4><a href="<?php echo base_url();?>user/user_update/<?php echo $user_id;?>">Update Your Details </a></h4></div>
