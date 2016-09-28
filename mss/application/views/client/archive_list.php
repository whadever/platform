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
			url: window.BaseUrl + 'client/archive_clear_search',
			type: 'POST',
			success: function(html) 
			{
				//console.log(data);
				newurl = window.BaseUrl + 'client/archive_list';
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
				newurl = window.BaseUrl + 'client/archive_list';
				window.location = newurl;
			},
		        
		}); 			 
	} 
 
</script>

<div class="page-title">
	<div class="row">
		<div class="col-xs-2 col-sm-2 col-md-1">
			<img width="" height="65" src="<?php echo base_url(); ?>/images/mss_client.png" title="Manage Properties" alt=""/>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-11">
			<h4 style="margin-top: 18px;">Property Archive Area</h4>
		</div>
	</div>
</div>

<div id="client-page" class="content allpage">
<?php
$pro_search = $this->session->userdata('pro_search1');
?>


	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 searchbox">
		    <div class="clickdiv" id="search-header">
		        <strong> <span> Search </span> 
		        <span id="plus" style="<?php if(!empty($pro_search)){ echo 'display:none;'; } ?>">+</span><span id="minus" style="<?php if(!empty($pro_search)){ echo 'display:inline;'; }else{ echo 'display:none;'; } ?>">-</span></strong>
		    </div> 
		    <div class="hiders" style="<?php if(!empty($pro_search)){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">
				<div class="row">
					<form action="<?php echo base_url(); ?>client/archive_list" method="post">
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
		<div class="col-xs-12 col-sm-12 col-md-12">		
			<div class="title"><?php echo $title; ?></div>
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

</div> <!-- end of page (class allpage) -->
