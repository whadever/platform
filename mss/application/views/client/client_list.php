
<script>
$(document).ready(function() {
    
    $('.clickdiv').click(function(){
        //$(this).find('.hiders').toggle();
        $('.hiders').slideToggle();
        $('#minus').toggle();
        $('#plus').toggle();
    });

	$('#clear_search').click(function(){
        $.ajax({				
			url: window.BaseUrl + 'client/clear_search',
			type: 'POST',
			success: function(html) 
			{
				//console.log(data);
				newurl = window.BaseUrl + 'client/client_list';
				window.location = newurl;
			},
		        
		});
    });
               
 });

	function Archive(id,value)  
	{  		
		$.ajax({				
			url: window.BaseUrl + 'client/archive_update?id=' + id + '&value=' + value,
			type: 'POST',
			success: function(data) 
			{	
				newurl = window.BaseUrl + 'client/client_list';
				window.location = newurl;
			},
		        
		}); 			 
	} 
 
</script>

<div class="page-title" style="float: left;width: 80%;">
	<div class="row">
		<div class="col-xs-2 col-sm-2 col-md-1">
			<img width="" height="65" src="<?php echo base_url(); ?>/images/mss_client.png" title="Manage Properties" alt=""/>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-11">
			<h4>Manage your properties</h4>
			<p>Add a new property to begin the creation of their personalised maintenance schedule.</p>
		</div>
	</div>
</div>
<div class="page-archive" style="float: left;width: 20%;">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top: 20px;text-align: right;">
			<a href="<?php echo base_url(); ?>client/archive_list"><img style="border: 2px solid #0D446E;border-radius: 5px;" width="" height="65" src="<?php echo base_url(); ?>/images/archive_area.png" title="Archive" alt=""/></a>
		</div>
	</div>
</div>

<div style="clear:both;"></div>

<div id="client-page" class="content allpage">
<?php
$pro_search = $this->session->userdata('pro_search');
?>


	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 searchbox">
		    <div class="clickdiv" id="search-header">
		        <strong> <span> Search </span> 
		        <span id="plus" style="<?php if(!empty($pro_search)){ echo 'display:none;'; } ?>">+</span><span id="minus" style="<?php if(!empty($pro_search)){ echo 'display:inline;'; }else{ echo 'display:none;'; } ?>">-</span></strong>
		    </div> 
		    <div class="hiders" style="<?php if(!empty($pro_search)){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">
				<div class="row">
					<form action="<?php echo base_url(); ?>client/client_list" method="post">
				        <div class="col-xs-12 col-sm-8 col-md-8">
			                <label for="pro_search">Search</label>
							<input type="text" class="form-control" id="pro_search" value="<?php if(!empty($pro_search)){ echo $pro_search; } ?>" name="pro_search">
			            </div>
						<div class="col-xs-6 col-sm-2 col-md-2">
							<label for="company_name">&nbsp;</label>
							<input type="button" class="form-control" id="clear_search" value="Clear Search">
			            </div>
						<div class="col-xs-6 col-sm-2 col-md-2">
							<label for="company_name">&nbsp;</label>
							<input type="submit" class="form-control" id="submit" value="Search" name="submit">
			            </div>
					</form>
				</div>
		    </div>
		</div>
	</div>

<div class="row">
	<div class="content-header">
		<div class="col-xs-7 col-sm-10 col-md-10">		
			<div class="title"><?php echo $title; ?></div>
		</div>
		<div class="col-xs-5 col-sm-2 col-md-2">		
			<a data-toggle="modal" class="form-submit btn btn-info new-button" href="#AddNewClient"><img class="plus-icon" src="<?php echo base_url(); ?>images/plus_icon.png" />Add a Property</a>
		</div>
	</div>
</div>

	

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="content-body">
			<div class="table-responsive">
			<?php if (isset($user_table)){ echo $user_table; } ?>
			</div>
		</div>
	</div>
</div>



<div class="clear"></div>
<p>&nbsp;</p>

<script>
	window.Url = "<?php print base_url(); ?>";

	function checkJnumber()  
	{  
		var job_number = $('.in #job_number').val();
		
		$.ajax({				
			url: window.Url + 'client/check_job_number?job_number=' + job_number,
			type: 'POST',
			success: function(data) 
			{	
				//console.log(data);
				if(data == 1){
					$('.in #job_number').css('border', '1px solid #FF0000');
					$('.in .taken').empty();
					$('.in .taken').append('<span style="color:#FF0000;">J number already exists</span>');
	        		return false;
				}else{
			        $('.in #job_number').css('border', '1px solid #ccc');
			        $('.in .taken').empty();
			        return true;
			    }
			},
		        
		}); 		  
	} 

	function checkJobnumber()
	{
	    var job_number = $('.in #job_number').val();
	        
        var html = $.ajax({
	        async: false,
	        url: window.Url + 'client/check_job_number?job_number=' + job_number,
	        type: 'POST',
	        dataType: 'html',
	        //data: {'pnr': a},
	        timeout: 2000,
	    }).responseText;
	    if(html==1){
	        $('.in #job_number').css('border', '1px solid #FF0000');
			$('.in .taken').empty();
			$('.in .taken').append('<span style="color:#FF0000;">J number already exists</span>');
        	return false;
	    }else{
	        $('.in #job_number').css('border', '1px solid #ccc');
	        $('.in .taken').empty();
	        return true;
	    } 
	    
	}
</script>



<!-- MODAL Add Client -->
<div id="AddNewClient" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-header">
		<h3 id="myModalLabel">Create Property</h3>
	</div>
	<div class="modal-body">


	
   <div class="row">
		<form onsubmit="return checkJobnumber()" method="POST" action="<?php echo base_url(); ?>client/client_add">

      		      		
      		<div class="col-xs-12 col-sm-12 col-md-12">
      			Property Address
      		</div>

			<div class="col-xs-12 col-sm-4 col-md-4">
      			<div class="form-group">
      				<label for="number">Number:*</label>
      				<input required="" class="form-control" type="text" name="number" id="number" value="" />
      			</div>
      		</div>

			<div class="col-xs-12 col-sm-8 col-md-8">
      			<div class="form-group">
      				<label for="street">Street:*</label>
      				<input required="" class="form-control" type="text" name="street" id="street" value="" />
      			</div>
      		</div>

			<div class="col-xs-12 col-sm-6 col-md-6">
      			<div class="form-group">
      				<label for="suburb">Suburb:*</label>
      				<input required="" class="form-control" type="text" name="suburb" id="suburb" value="" />
      			</div>
      		</div>

			<div class="col-xs-12 col-sm-6 col-md-6">
      			<div class="form-group">
      				<label for="city">City:*</label>
      				<input required="" class="form-control" type="text" name="city" id="city" value="" />
      			</div>
      		</div>

			<div class="col-xs-12 col-sm-12 col-md-12">
      			<div class="form-group">
      				<label for="job_number">Job Number:*</label>
      				<input required="" class="form-control" type="text" name="job_number" id="job_number" value="" />
					<div class="taken"></div>
      			</div>
      		</div>  		     		
			<div class="col-xs-12 col-sm-12 col-md-12">
      			<div class="form-group">
      				<label for="legal_description">Legal Description:*</label>
      				<input required="" class="form-control" type="text" name="legal_description" id="legal_description" value="" />
					<div class="taken"></div>
      			</div>
      		</div>
			<div class="col-xs-12 col-sm-6 col-md-6">
      			<div class="form-group">
      				<label for="corrosion_zone">Corrosion Zone:</label>
      				<input class="form-control" type="text" name="corrosion_zone" id="corrosion_zone" value="" />
      			</div>
      		</div>

			<div class="col-xs-12 col-sm-6 col-md-6">
      			<div class="form-group">
      				<label for="wind_zone">Wind Zone:</label>
      				<input class="form-control" type="text" name="wind_zone" id="wind_zone" value="" />
      			</div>
      		</div>
			<div class="col-xs-12 col-sm-12 col-md-12">
      			<div class="form-group">
      				<label for="note">Notes:</label>
      				<input class="form-control" type="text" name="note" id="note" value="" />
      			</div>
      		</div>

				<div class="col-xs-12 col-sm-6 col-md-6">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn cancel" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<input class="btn create" type="submit" name="submit" value="Create" />
				</div>
      		
		</form>
	</div>
		
	
	</div>


</div>

<script>
	$(document).ready(function(){

		$('.modal form').ajaxForm({
			success:function() {
				newurl = window.BaseUrl + 'client/client_list';
				window.location = newurl;	  
			},			
			beforeSubmit:function(){
				var overlay = jQuery('<div id="overlay"><div class="overlay-text">It May Take Some Time</div></div>');
				overlay.appendTo(document.body);
			}
		});
	});
</script>

</div> <!-- end of page (class allpage) -->
