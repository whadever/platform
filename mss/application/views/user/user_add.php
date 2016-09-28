<script>
	
	function ValidateEmail()  
	{  
		var email = $('#email').val();
		var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
		if(email.match(mailformat))  
		{  
			//$('.in #email').css('border', '1px solid #01416f'); 
			$.ajax({				
				url: window.BaseUrl + 'user/user_email_check?email=' + email,
				type: 'POST',
				success: function(data) 
				{	
					//console.log(data);
					if(data == 1){
						$('#email').css('border', '1px solid #FF0000');
		        		return false;
					}else{

				        $('#email').css('border', '1px solid #01416f');
				        return true;
				    }
				},
			        
			}); 
			//return true;  
		}  
		else  
		{  
			$('#email').css('border', '1px solid #FF0000');  
			return false;  
		}  
	} 
	
	function ValidateUser()  
	{  
		var username = $('#name').val(); 
		
		$.ajax({				
			url: window.BaseUrl + 'user/user_name_check?username=' + username,
			type: 'POST',
			success: function(data) 
			{	
				//console.log(data);
				if(data == 1){
					$('#name').css('border', '1px solid #FF0000');
	        		return false;
				}else{

			        $('#name').css('border', '1px solid #01416f');
			        return true;
			    }
			},
		        
		}); 
			
	} 
	
	function CheckUsenameEmail()
	{
		var username = $('#name').val(); 
	    var email = $('#email').val();
		var mailformat = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;  
		if(email.match(mailformat))  
		{
	        //alert("Passwords Match!");
	         $('#email').css('border', '1px solid #01416f');
	        
	        var html = $.ajax({
		        async: false,
		        url: window.BaseUrl + 'user/user_email_check?email=' + email,
		        type: 'POST',
		        dataType: 'html',
		        //data: {'pnr': a},
		        timeout: 2000,
		    }).responseText;
		    if(html==1){
		        $('#email').css('border', '1px solid #FF0000');
        		return false;
		    }else{
		        $('#email').css('border', '1px solid #01416f');
		        
		        
		        var html = $.ajax({
			        async: false,
			        url: window.BaseUrl + 'user/user_name_check?username=' + username,
			        type: 'POST',
			        dataType: 'html',
			        //data: {'pnr': a},
			        timeout: 2000,
			    }).responseText;
			    if(html==1){
			        $('#name').css('border', '1px solid #FF0000');
	        		return false;
			    }else{
			        $('#name').css('border', '1px solid #01416f');
					return true;
			    } 

		    } 
			
	    }
	    else
	    {
	        $('#email').css('border', '1px solid #FF0000');  
			return false;          
	    }
	    
	}
	
</script>

<div class="content">
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="content-header">
				<div class="title"><?php echo $title; ?></div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<form onsubmit="return CheckUsenameEmail()" method="POST" action="<?php echo base_url(); ?>user/user_add">
			
			<div class="col-xs-12 col-sm-12 col-md-6">
      			<div class="form-group">
      				<label for="fullname">Full Name</label>
      				<input required="" class="form-control" type="text" name="fullname" id="fullname" value="" />
      			</div>
      		</div>
      		
      		<div class="col-xs-12 col-sm-12 col-md-6">
      			<div class="form-group">
      				<label for="email">Email Address</label>
      				<input onkeyup="ValidateEmail();" required="" class="form-control" type="text" name="email" id="email" value="" />
      			</div>
      		</div>
      		
      		<div class="col-xs-12 col-sm-12 col-md-6">
      			<div class="form-group">
      				<label for="name">User Name</label>
      				<input onkeyup="ValidateUser();" required="" class="form-control" type="text" name="name" id="name" value="" />
      			</div>
      		</div>
      		
      		<div class="col-xs-12 col-sm-12 col-md-6">
      			<div class="form-group">
      				<label for="pass">Password</label>
      				<input required="" class="form-control" type="password" name="pass" id="pass" value="" />
      			</div>
      		</div>
 
			<div class="col-xs-12 col-sm-12 col-md-10">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-2">
				<input class="btn btn-info" type="submit" name="submit" value="Create User" />
			</div>
      		
		</form>
	</div>
	
</div>


