<?php
$user = $this->session->userdata('user');
$uid = $user->uid;

$f_query = $this->db->query("SELECT first_login FROM users where uid=".$uid)->row();
$first_login = $f_query->first_login;
?>
<?php if($first_login=='1' || $first_login=='2' || $first_login=='3'){ ?>
<link href="<?php echo base_url(); ?>css/tipso/tipso.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url(); ?>css/tipso/tipso.js"> </script>
<?php } ?>

<script>
window.Url = "<?php print base_url(); ?>";
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
            
 });
</script>

<script>
$(document).ready(function() {

	$('#button').click(function(){
        $.ajax({				
			url: window.Url + 'user/clear_search',
			type: 'POST',
			success: function(html) 
			{
				//console.log(data);
				newurl = window.BaseUrl + 'user/user_list';
				window.location = newurl;
			},
		        
		});
    });
    
    $('#btnUpgradePlan').click(function(){
		
		$( "#formUpgradePlan" ).submit(); 
		
	});
	
	$("#user-add").tooltip({
		tooltipSourceID:'#exampleuseradd', 
		loader:1,  
		loaderHeight:16, 
		loaderWidth:17, 
		width:'220px', 
		height:'60px', 
		tooltipSource:'inline'
	});
	
	$("#user-profile").tooltip({
		tooltipSourceID:'#exampleuserprofile', 
		loader:1, 
		loaderHeight:16, 
		loaderWidth:17, 
		width:'290px', 
		height:'85px', 
		tooltipSource:'inline'
	});
	
	$('.user-edit-tooltip').tooltip({
		width:'270px', 
		height:'65px' 
	});
               
 });
 
</script>

<div id="user-page" class="content-inner"> 

	<!--<div class="row">
	    <div class="col-md-12"> 
	        <div id="infoMessage">
	
	        <?php /*if($this->session->flashdata('success-message')){ */?>
	
	        <div class="alert alert-success" id="success-alert">
	        <button type="button" class="close" data-dismiss="alert">x</button>
	        <strong>Success! </strong>
	        <?php /*echo $this->session->flashdata('success-message');*/?>
	        </div>    
	        <?php /*} */?>
	
	        <?php /*if($this->session->flashdata('warning-message')){ */?>
	
	        <div class="alert alert-warning" id="warning-alert">
	        <button type="button" class="close" data-dismiss="alert">x</button>
	        <?php /*echo $this->session->flashdata('warning-message');*/?>
	        </div>    
	        <?php /*} */?>
	
	        </div>
	    </div>
	</div>  -->
	 
	
<?php
	$system = $this->session->userdata('system'); 
	$name = $this->session->userdata('name');
	$ci = & get_instance();
	$ci->load->model('user_model');
		
	$system_option = $ci->user_model->get_system_access_list();
	$system_options  = array('' => '---Select Criteria---') + $system_option;

	$system_default = isset($system) ? $system : '';
    $system_js= 'class="form-control"';
	       
	$system = form_dropdown('system', $system_options, $system_default, $system_js);
	
	if($first_login=='1' || $first_login=='2'){
		$first = 'id="first" data-tipso="Click here to add new user ad customise their permission."';
		$second = 'id="second" data-tipso="Click here to customise your company. (Changing your primary and secondary color, put your logo, put your background)"';
	}else{
		$second = '';
		$first = '';
	}
	

?>	   
	   
	<div class="row">
		<div class="title col-xs-8 col-sm-8 col-md-8">
			<div class="title-inner">
				<img src="<?php echo base_url(); ?>images/add_user_1.png" width="40" />
				<p><strong>Manage your Users</strong><br>Create and manage your Users, and allow them access to particular systems.</p>
			</div>
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
			<div <?php echo $second; ?> class="add client-add-button <?php if($first_login=='2'){ echo 'second'; } ?>" style="margin-left: 8px;text-align: center;">
				<a class="modal-button" href="<?php echo base_url(); ?>client/profile"><img src="<?php echo base_url(); ?>images/profile.png" width="60" /> <br />Interface</a>
			</div>
			<?php if($users_plan->users_total < $users_plan->users_max) { ?>
			<div class="add client-add-button <?php if($first_login=='1'){ echo 'first'; } ?>" <?php echo $first; ?> style="text-align: center;">
				<a href="<?php echo base_url(); ?>user/user_add"><img src="<?php echo base_url(); ?>images/add_user_1.png" width="60" /> <br />Add/Edit Users</a>
			</div>
			<?php } else { ?>
			<div class="add client-add-button">
				<a class="modal-button" data-toggle="modal" data-target="#modal_upgrade_plan"><img src="<?php echo base_url(); ?>images/btn-upgrade.png" title="Upgrade Plan" width="60" /></a>
			</div>
			<?php } ?>
			<div class="add client-add-button" style="margin-right: 10px;text-align: center;">
				<a class="" href="<?php echo site_url('user/select_plan'); ?>"><img src="<?php echo base_url(); ?>images/currency.png" title="Upgrade Plan" width="60" /> <br />Upgrade</a>
			</div>
			
		</div>
	</div>
	
	<div id="modal_upgrade_plan" class="modal fade">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header" style="background:#e5e5e5 none repeat scroll 0 0;">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 class="modal-title">Upgrade User Plan of <?php echo $user->client_name; ?></h3>
		  </div>
		  <form method="post" id="formUpgradePlan" role="form" action="<?php echo base_url(); ?>user/upgrade_plan">
					
		  <div class="modal-body">
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6"><label>Your current plan is: </label> </div>
    			<div class="col-xs-12 col-sm-6 col-md-6" style="text-align:right;">
            				<?php 
							$plans_query = $this->db->query("SELECT * FROM wp_plans where id=".$user->plan_id);
							$plans = $plans_query->result();
							echo $plans[0]->name; 
							?>
    			</div>
				<div style="clear:both;"></div>
    			<div class="col-xs-12 col-sm-6 col-md-6">Would you like to upgrade your plan?</div>
    			<div class="col-xs-12 col-sm-6 col-md-6"  style="text-align:right;">
            			<input style="margin-left:0;" onchange="$('#target').toggle();" type="checkbox" name="upgrade_plan" id="upgrade_plan"> Yes, upgrade me!
    			</div>
				<!--<div class="col-xs-12 col-sm-6 col-md-6"> <div class="checkbox"><input style="margin-left:0;" type="checkbox" name="upgrade_plan_no" id="upgrade_plan_no"> <label>No,thank you</label></div></div>-->
				<div class="col-xs-12 col-sm-12 col-md-12">
					
					<div id="target" style="display:none;"> 
						<div class="form-group">
				    		<label for="plan_id">What plan would you like to upgrade to:</label>
				  			<select name="plan_id" class="form-control" id="plan_id" placeholder="Select a Plan">	 
								<?php
									$plans_query = $this->db->query("SELECT * FROM wp_plans where id > '".$user->plan_id."'");
									$plans = $plans_query->result();
									//$app_client_results = $this->client_model->application_client_list($user->id,$user->company_id)->result();
									foreach ($plans as $plan){		
										echo '<option value="'.$plan->id.'">'.$plan->name.'<br/>';
									}
								?>
					 		</select>
					 		<input type="hidden" name="company_id" value="<?php echo $user->company_id?>">
					 		<br/>
					 		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					 		<button type="submit" class="btn btn-default">Submit</button>
					 		<br/>
					 		<br/>
					 		<div class="note"><i>Note: For the upgrade to take effect, please log out and log back in after submit.</i></div>
						</div>
					</div>	
					
					<br/>
				</div>
				<div class="clear"></div>
			</div>
		  </div>
		  <!--<div class="modal-footer">
		  	 <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
			<button type="submit" id="btnUpgradePlan" class="btn btn-default">Submit</button>
			
		  </div>-->
		  </form>
		</div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->    
	    
	<div class="main-page">
		<!--task #4670-->
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12" style="margin-bottom: 5px">
				<form class="form-inline" action="<?php echo site_url('client/apply_discount_code'); ?>" method="post">
					<input type="text" class="form-control" name="code" required placeholder="discount code">
					<button class="btn btn-sm">Get Discount</button>
				</form>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 searchbox">
			    <div class="clickdiv" id="search-header">
			        <span> Search </span> 
			        <span id="plus" style="<?php if($this->session->userdata('system') || $this->session->userdata('name')){ echo 'display:none;'; } ?>">+</span><span id="minus" style="<?php if($this->session->userdata('system') || $this->session->userdata('name')){ echo 'display:inline;'; }else{ echo 'display:none;'; } ?>">-</span>
			    </div> 
			    <div class="hiders" style="<?php if($this->session->userdata('system') || $this->session->userdata('name')){ echo 'display:block;'; } ?>">
					<div class="row">
						<form action="<?php echo base_url(); ?>user/user_list" method="POST">
					        <div class="col-xs-12 col-sm-8 col-md-8">
				                <label for="username">Search</label>
								<input type="text" class="form-control" id="username" value="<?php if(isset($name)){ echo $name; } ?>" name="username">
				            </div>
							<div class="col-xs-12 col-sm-4 col-md-4">
								<label for="system">Refine Search</label>
							    <?php echo $system; ?>
				            </div>
							<div class="col-xs-12 col-sm-8 col-md-8">
				                
				            </div>
							<div class="col-xs-6 col-sm-2 col-md-2">
								<label for="">&nbsp;</label>
								<input type="button" class="form-control" id="button" value="Clear Search">
				            </div>
							<div class="col-xs-6 col-sm-2 col-md-2">
								<label for="">&nbsp;</label>
								<input type="submit" class="form-control" id="submit" value="Search" name="submit">
				            </div>
						</form>
					</div>
			    </div>
			</div>
		</div>
    
		<div class="row">
			<div class="col-sm-12 col-md-12">
				<div class="client-list">
		                    <div class="table-responsive">
		                        <?php if (isset($user_table)){ echo $user_table; } ?>
		                    </div>
		                </div>
		        </div>
		</div>
    </div>

</div>


<div class="all-tipso">
<?php if($first_login=='1' || $first_login=='2' || $first_login=='3'){ ?>
<script>
	jQuery(document).ready(function(){
		
		jQuery('.first').tipso({
			position: 'top-left',
			background: 'rgba(0,0,0,0.8)',
			titleBackground: 'tomato',
			useTitle: false,
			width: 250,
			tooltipHover: true,
			content: function(){
				return 'Click here to add new user ad customise their permission.<br><a onclick="UpdateFirstLogin(<?php echo $uid; ?>,0);" style="color:#fff !important;float:left;" href="javascript:;">Close</a><a onclick="UpdateFirstLogin(<?php echo $uid; ?>,2);" style="color:#fff !important;float:right;" href="javascript:;">Next</a><div style="clear:both;"><div>';
			}
		});
	});
	
	jQuery(window).load(function(){
		// Show Tipso on Load
		jQuery('.first').tipso('show');
	});
</script>

<script>
	jQuery(document).ready(function(){
		
		jQuery('.second').tipso({
			position: 'top-left',
			background: 'rgba(0,0,0,0.8)',
			titleBackground: 'tomato',
			useTitle: false,
			width: 280,
			tooltipHover: true,
			content: function(){
				return 'Click here to customise your company. (Changing your primary and secondary color, put your logo, put your background)<br><a onclick="UpdateFirstLogin(<?php echo $uid; ?>,0);" style="color:#fff !important;float:left;" href="javascript:;">Close</a><a onclick="UpdateFirstLogin(<?php echo $uid; ?>,3);" style="color:#fff !important;float:right;" href="javascript:;">Next</a><div style="clear:both;"><div>';
			}
		});
	});
	
	jQuery(window).load(function(){
		// Show Tipso on Load
		jQuery('.second').tipso('show');
	});
</script>

<script>
	jQuery(document).ready(function(){
		
		jQuery('.third').tipso({
			position: 'top-left',
			background: 'rgba(0,0,0,0.8)',
			titleBackground: 'tomato',
			useTitle: false,
			width: 280,
			tooltipHover: true,
			content: function(){
				return 'Click here to modify user`s permission and to assign user system(s).<br><a onclick="UpdateFirstLogin(<?php echo $uid; ?>,0);" style="color:#fff !important;float:left;" href="javascript:;">Close</a><div style="clear:both;"><div>';
			}
		});
	});
	
	jQuery(window).load(function(){
		// Show Tipso on Load
		jQuery('.third').tipso('show');
	});
</script>
<?php } ?>
</div>

<script>
	function UpdateFirstLogin(uid,up){
        $.ajax({				
			url: window.Url + 'user/UpdateFirstLogin/'+uid+'/'+up,
			type: 'POST',
			success: function(html) 
			{
				if(up==2){
					$('.first').tipso('hide');
					$( "#first" ).removeClass( "first tipso_style" );
					$( "#second" ).addClass( "second" );
					$('.second').tipso({
						position: 'top-left',
						background: 'rgba(0,0,0,0.8)',
						titleBackground: 'tomato',
						useTitle: false,
						width: 280,
						tooltipHover: true,
						content: function(){
							return 'Click here to customise your company. (Changing your primary and secondary color, put your logo, put your background)<br><a onclick="UpdateFirstLogin(<?php echo $uid; ?>,0);" style="color:#fff !important;float:left;" href="javascript:;">Close</a><a onclick="UpdateFirstLogin(<?php echo $uid; ?>,3);" style="color:#fff !important;float:right;" href="javascript:;">Next</a><div style="clear:both;"><div>';
						}
					});
					$('.second').tipso('show');
				}else if(up==3){
					$('.second').tipso('hide');
					$( "#second" ).removeClass( "second tipso_style" );
					$( "#third" ).addClass( "third" );
					$('.third').tipso({
						position: 'top-left',
						background: 'rgba(0,0,0,0.8)',
						titleBackground: 'tomato',
						useTitle: false,
						width: 280,
						tooltipHover: true,
						content: function(){
							return 'Click here to modify user`s permission and to assign user system(s).<br><a onclick="UpdateFirstLogin(<?php echo $uid; ?>,0);" style="color:#fff !important;float:left;" href="javascript:;">Close</a><div style="clear:both;"><div>';
						}
					});
					$('.third').tipso('show');
				}else if(up==0){
					
					$('.first').tipso('hide');
					$('.second').tipso('hide');
					$('.third').tipso('hide');
					$( "#first" ).removeClass( "first tipso_style" );
					$( "#second" ).removeClass( "second tipso_style" );
					$( "#third" ).removeClass( "third tipso_style" );
				}
				//console.log(data);
				//newurl = window.Url + 'user/user_list';
				//window.location = newurl;
				$( ".all-tipso" ).remove();
			},
		        
		});
    }
</script>

<!----
<?php foreach($users as $user){ ?>
<div class="modal small fade custom-modal" id="deleteModal_<?php echo $user->uid; ?>" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-body">
                <p class="error-text">
                    <strong> Are you sure you want to delete this <?php echo $user->username; ?> from the Users List? </strong></p>
            </div>
            <div class="modal-footer">
               <button class="btn btn-default"data-dismiss="modal" aria-hidden="true">Cancel</button> 
               <a href="<?php echo base_url(); ?>user/user_delete/<?php echo $user->uid; ?>" class="btn btn-danger"  id="modalDelete" >Delete</a>

            </div>
        </div>
    </div>
</div>
<?php } ?>
---->