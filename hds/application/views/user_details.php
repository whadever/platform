<style>
.save-change > input#change {
    background: #fff;
    border: 1px solid #002855;
}
a.update-password {
    border: 1px solid #002855;
    color: #333;
    padding: 4px 10px;
}
a.update-password:hover {
    text-decoration:none;
}
.modal .controls input[type="password"] {
    border: 1px solid #000;
    border-radius: 10px;
    line-height: 12px;
    padding: 3px 8px;
    width: 56%;
}
</style>
<script>
	function checkpassword()
	{
	    var password = $('.in #new_password').val();
	    var retype_password = $('.in #retype_new_password').val();
		console.log(retype_password);
	    if(password == retype_password){
	        $('.in #retype_new_password').css('border', '1px solid #000');
	        return true;
	    }else{
	        $('.in #retype_new_password').css('border', '1px solid #FF0000');
	        return false;
	    }
	}
	
	function checkOldpassword()
	{
	    var enter_old_password = $('.in #old_password').val();
	    var uid = $('.in #uid').val();
	    
		$.ajax({				
			url: window.mbsBaseUrl + 'user/user_check_password/' + uid + '/' + enter_old_password,
			type: 'POST',
			success: function(data) 
			{
			//console.log(data);
			if(data == 1){
					$('.in #old_password').css('border', '1px solid #000');
					$('.in #new_pass_access').val(data);
	        		return true;
				}else{
			        //alert("Passwords Do Not Match!");
			        $('.in #old_password').css('border', '1px solid #FF0000');
			        $('.in #new_pass_access').val(data);
			        return false;
			    }
			},
		        
		});
		
	}
	
	function checkOldAndNewpassword()
	{
	    var new_pass_access = $('.in #new_pass_access').val();
	    var password = $('.in #new_password').val();
	    var retype_password = $('.in #retype_new_password').val();
		
		if(new_pass_access == 1 && password == retype_password){
    		return true;
		}else{
			if(new_pass_access == 0){
				$('.in #old_password').css('border', '1px solid #FF0000');
			}else if(password != retype_password){
				$('.in #retype_new_password').css('border', '1px solid #FF0000');
			}else{
				$('.in #old_password').css('border', '1px solid #FF0000');
	        	$('.in #retype_new_password').css('border', '1px solid #FF0000');
			}
	        return false;
	    }			
	}
		
</script>


<?php if (isset($message)) {echo $message;}  ?>
<div class="all-title">
<?php echo $title; ?>
</div>

<div id="user_list_view" class="table-border">
	<?php if(isset($table)) { echo $table;	} ?>
</div>
<!----<div><h4><a href="<?php echo base_url();?>user/user_update/<?php echo $user_id;?>">Update Your Details </a></h4></div>-->

<div id="UpdatePassword" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form onsubmit="return checkOldAndNewpassword()" class="form-horizontal" action="<?php echo base_url(); ?>user/update_password/<?php echo $user->uid; ?>" method="POST">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel">Update Password</h3>
		</div>
		<div class="modal-body">
				
			<div class="control-group">
				<label class="control-label" for="old_password">Enter Old Passowrd</label>
				<div class="controls">
					<input onkeyup="checkOldpassword();" type="password" id="old_password" name="old_password" value="" required="">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="new_password">Enter New Password</label>
				<div class="controls">
					<input type="password" id="new_password" name="new_password" value="" required="">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="retype_new_password">Retype New Password</label>
				<div class="controls">
					<input onkeyup="checkpassword();" type="password" id="retype_new_password" name="retype_new_password" value="" required="">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="submit"></label>
				<div class="controls">
					<input type="hidden" id="new_pass_access" name="new_pass_access" value="">
					<input type="hidden" id="uid" name="uid" value="<?php echo $user->uid; ?>">
					<div class="save-change">
						<input id="change" type="submit" value="Change" name="submit" />
					</div>
				</div>
			</div>
	    
		</div>

	</form>
</div>
