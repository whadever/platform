<script>
	$(document).ready(function(){
		$('.alert').show().fadeOut(12000);
	});
</script>

<!--task #4524-->
<style>
	.highlight { background-color: yellow }
</style>

<script type="text/javascript" src="<?php echo base_url();?>js/jquery.highlight.js"></script>

<script>

	$.fn.eqAnyOf = function (arrayOfIndexes) {
		return this.filter(function (i) {
			return $.inArray(i, arrayOfIndexes) > -1;
		});
	};

	$(document).ready(function () {
		$("#search").bind("keyup", advance_search);
	});

	function advance_search() {

		var filter = $("#search").val(), count = 0;
		var parr = [0, 1, 2, 3, 4, 5];

		$("#client_list table tbody tr").each(function () {

			if ($(this).find("td").eqAnyOf(parr).text().search(new RegExp(filter, "i")) < 0 ) {

				$(this).fadeOut();
			}
			else {
				$(this).show();
				count++;
			}

			/*highlighting search term*/
			var body = $(this).find("td").eqAnyOf(parr);
			body.unhighlight();
			body.highlight($('#search').val());

		});

	}
</script>

	  

	<div class="row">
		<div class="title col-xs-8 col-sm-8 col-md-8">
			<div class="title-inner">
				<img src="<?php echo base_url(); ?>images/add_1.png" width="40" />
				<p><strong>Manage your Clients</strong><br>Create and manage your Clients, and allow them access to particular systems.</p>
			</div>
		</div>
		<div class="col-xs-4 col-sm-4 col-md-4">
			<div class="add client-add-button">
				<a href="<?php echo base_url(); ?>client/client_add"><img src="<?php echo base_url(); ?>images/add.png" title="Client Add" width="60" /></a>
			</div>
		</div>
	</div>

<div class="main-page">
	<div class="row">
		<div class="col-xs-12 col-sm-4 col-md-4">
			<div class="contactsearchbox">
				<input type="text" placeholder="Search" name="search" id="search" class="search_contact form-control" style="margin-bottom: 15px">
			</div>
		</div>
		<div class="col-xs-12 col-sm-2 col-md-2">
			<a class="btn btn-default" data-toggle="modal" data-target="#discount">Generate Discount Code</a>
		</div>
		<div class="col-xs-12 col-sm-6 col-md-6 text-right">
			<form class="form-inline" action="<?php echo base_url().'client/login_using_customer_code'; ?>" method="post">
				<label for="code">Login using customer support code:</label>
				<input class="form-control" type="text" id="code" name="code" placeholder="enter code">
				<button class="btn btn-default">login</button>
			</form>
		</div>
	</div>
	<div class="row">
		<div class="col-sm-12 col-md-12">
			<div class="client-list">
				<?php 
					if(isset($_GET['success'])){
						if($_GET['success']==1){
							echo '<div class="alert alert-success" role="alert">Well done! Client has been save successfully.</div>'; 
						}else if($_GET['success']==2){
							echo '<div class="alert alert-success" role="alert">Well done! Client was deleted successfully.</div>'; 
						}else if($_GET['success']==3){
							echo '<div class="alert alert-success" role="alert">Well done! Client has been update successfully.</div>'; 
						}
					}
				?> 
				<div class="table-responsive" id="client_list">
					<table class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>CLIENT</th>
								<th>COMPANY ADMIN</th>
								<th>URL</th>
								<!--<th>PASSWORD</th>-->
								<th>EMAIL</th>
								<th>REGISTRATION DATE</th>
								<!--<th>FREE TRIAL EXPIRATION DATE</th>-->
								<th>STATUS</th> <!--task #4540-->
								<th>NEXT PAYMENT</th>
								<th>ACTION</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach($users as $user){ ?>
							<tr>
								<td>
									<!--<a href="<?php echo base_url(); ?>client/client_detail/<?php echo $user->uid; ?>"><?php echo $user->client_name; ?></a>-->
									<a class="modal-button" data-toggle="modal" data-target="#modal_detail_<?php echo $user->id; ?>"><?php echo $user->client_name; ?></a>
								</td>
								<td><?php echo $user->username; ?></td>
								<td><?php echo "<a href='https://{$user->url}'>{$user->url}</a>"; ?></td>
								<!--<td><?php /*echo '*****'; */?></td>-->
								<td><?php echo $user->email; ?></td>
								<td><?php echo date("d-m-Y H:i:s",strtotime($user->created)); ?></td>
								<td>
									<?php
										/*task #4537*/
										$williams_companies = array(24,28,29,31,34);
										if(($user->payment_token && $user->is_active) || in_array($user->company_id, $williams_companies)){
											echo "<span style='color: green'>paid</span>";
										}else{
											$trial_expire_date = date_create_from_format('Y-m-d H:i:s',$user->created)->add(new DateInterval('P30D'));
											$today = new DateTime();
											$color = "";
											if($today <= $trial_expire_date){
												$days_left = date_diff($trial_expire_date,$today)->days;
												if($days_left < 7){
													echo "<span style='color: red'>{$trial_expire_date->format('d-m-Y')}</span>";
												}else{
													echo $trial_expire_date->format('d-m-Y');
												}
											}else{

												echo "expired";
											}
										}
									?>
								</td>
								<td><?php echo date("d-m-Y",strtotime($user->next_payment_date)); ?></td>
								<td>
									<a href="<?php echo base_url(); ?>client/client_update/<?php echo $user->uid; ?>"><img width="25" src="<?php echo base_url(); ?>images/edit.png" title="Client Edit" /></a>
									
									<button class="modal-button" type="button" data-toggle="modal" data-target="#modal_<?php echo $user->id; ?>"><img src="<?php echo base_url(); ?>images/delete.png" title="Client delete" /></button>
								</td>
							</tr>
	
							<div id="modal_<?php echo $user->id; ?>" class="modal fade custom-modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
							  <div class="modal-dialog">
							    <div class="modal-content">
							      <div class="modal-body">
							        <p class="delete-modal">Are you sure you want to permanetly delete</p>
									<h4><?php echo $user->client_name.'?'; ?></h4>
									<p class="note-modal">Note: You will not be able to undo this action, all information will be cleaned</p>
							      </div>
							      <div class="modal-footer">
							        <button type="button" class="btn btn-default modal-submit" data-dismiss="modal">No</button>
									<a class="btn btn-default modal-submit" href="<?php echo base_url(); ?>client/client_delete/<?php echo $user->company_id; ?>">Yes</a>
							      </div>
							    </div><!-- /.modal-content -->
							  </div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
							
							<div id="modal_detail_<?php echo $user->id; ?>" class="modal fade">
							  <div class="modal-dialog">
								<div class="modal-content">
								  <div class="modal-header" style="background:#e5e5e5 none repeat scroll 0 0;">
									<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
									<h3 class="modal-title"><?php echo $user->client_name; ?></h3>
								  </div>
								  <div class="modal-body">
								  	<div class="row">
								  		<div class="col-xs-12"> <img class="center-block" style="width:150px;" src="<?php echo base_url().'uploads/logo/'.$user->filename;?>" /></div>
										<div class="col-xs-12"><label>Company Admin:</label> <?php echo $user->username; ?></div>
										<div class="col-xs-12"><label>Person in Charge:</label> <?php echo $user->person_in_charge; ?></div>
										<div class="col-xs-12"><label>Phone Number:</label> <?php echo $user->phone_number; ?></div>
										<div class="col-xs-12"><label>Email:</label> <?php echo $user->email; ?></div>
									
										<div class="col-xs-12 col-sm-6 col-md-6"><label>Plan:</label> 
										<?php 
											$plans_query = $this->db->query("SELECT * FROM wp_plans where id=".$user->plan_id);
											$plans = $plans_query->result();
											echo $plans[0]->name; ?>
										</div>
										<div class="col-xs-12 col-sm-6 col-md-6"><label>Pricing:</label> <?php echo $user->pricing==0?'Full Price':'Discounted Price'; ?></div>
									
										
										<div class="col-xs-12 col-sm-6 col-md-6"><label>System(s):</label></div>
										<div class="col-xs-12 col-sm-6 col-md-6">	 
											<?php
												$apps_query = $this->db->query("SELECT application.* FROM users_application left join application on application.id = users_application.application_id where user_id=".$user->uid." and company_id=".$user->company_id);
												$apps = $apps_query->result();
												//$app_client_results = $this->client_model->application_client_list($user->id,$user->company_id)->result();
												foreach ($apps as $app){			
													echo $app->application_name.'<br/>';
												}
											?>
											<br/>
										</div>
										<div class="clear"></div>
										<div class="col-xs-12 col-sm-6 col-md-6"><label>Primary Color:</label> <span style="height:20px;width:50px;background-color:<?php echo $user->colour_one; ?>;">&nbsp;&nbsp;&nbsp;</span></div>
										<div class="col-xs-12 col-sm-6 col-md-6"><label>Secondary Color:</label> <span   style="height:20px;width:50px;background-color:<?php echo $user->colour_two; ?>;">&nbsp;&nbsp;&nbsp;</span></div>
										
									</div>
								  </div>
								  <div class="modal-footer">
									<a class="btn btn-default modal-submit" href="<?php echo base_url(); ?>client/client_update/<?php echo $user->uid; ?>">Update Information</a>
								  </div>
								</div><!-- /.modal-content -->
							  </div><!-- /.modal-dialog -->
							</div><!-- /.modal -->
							
							<?php } ?>
						</tbody>
					</table>
				</div>
				
			</div>
		</div>
	</div>
</div>

<!-- modal for discount -->
<!--task #4670-->
<div id="discount" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title">Generate discount code</h4>
			</div>
			<div class="modal-body" id="discount_modal">
				<form id="discount_form" class="form-inline" action="<?php echo site_url('client/generate_discount_code'); ?>" method="post">
					<p style="margin: 10px">
						Discount amount <input name="amount" type="number" step="0.1" class="form-control" required min="0"> %
					</p>
					<p style="margin: 10px">
						Valid for <input type="number" name="months" value="0" class="form-control" required min="0"> months (0 for unlimited).
					</p>
					<p style="margin: 10px">
						Code expire after <input name="expire" type="number" value="72" class="form-control" required min="1"> hours.
						<button id="discount_btn" class="btn btn-info" style="float: right">Generate code</button>
						<span id="dis_code" style="width:100%; display: block; font-size: large; font-weight: bold; color: maroon; margin: 10px; text-align: center"></span>
					</p>

				</form>
			</div>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<script>
	$(document).ready(function(){
		$("#discount_form").submit(function(e){
			e.preventDefault();
			var url = $(this).prop('action');
			var data = $(this).serialize();
			$.ajax(url,{
				method: 'post',
				data: data,
				success: function(data){
					$("#dis_code").html(data);
				}
			})
		});
	});
	$(document).on('hidden.bs.modal', '#discount', function () {
		$("#dis_code").empty();
	});
</script>