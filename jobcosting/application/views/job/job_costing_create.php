<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-select.css" />
<script type="text/javascript" src="<?php echo base_url();?>/js/bootstrap-select.js"></script>
<script>
jQuery(document).ready(function() {
	if (jQuery('.sortable-category').length){
		$( ".sortable-category" ).sortable({
			update : function () { 
				var order = $('.sortable-category').sortable('serialize');
				$.ajax({
					url: "<?php echo base_url(); ?>" + 'job/job_category_ordering',
					type: 'POST',
					data: order,
					success: function(data) 
					{
	 
					},
				        
				});
			}
		});	
		$( ".sortable-category" ).disableSelection();
	}
});
</script>

<style>
.job_costing_create table {
    font-weight: bold;
}
</style>

<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
		</div>
	</div>
</div>

<?php 
if($this->session->userdata('send_email')): 
?>
	<div class="alert alert-success alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  <strong>Well done!</strong> E-mail sent
	</div>
<?php 
	$this->session->unset_userdata('send_email'); 
endif; 
?>

<?php 
if($this->session->userdata('send_xero')): 
?>
	<div class="alert alert-success alert-dismissible" role="alert">
	  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  <strong>Well done!</strong> Uploaded to XERO
	</div>
<?php 
	$this->session->unset_userdata('send_xero'); 
endif; 
?>

<div class="row" style="margin-bottom: 10px;">
    <div class="col-xs-12 col-sm-12 col-md-12">
    	<?php if($type=='actual'){?><a data-toggle="modal" href="#AddCompany" class="btn btn-danger pull-right">Upload Quote</a><?php } ?>
    	<a data-toggle="modal" href="#AddItem" class="btn btn-danger pull-right">Add Item</a>
    	<a data-toggle="modal" href="#AddCategory" class="btn btn-danger pull-right">Add Category</a>   		
    </div>
</div>
	    
<?php echo form_open('job/job_costing_create/'.$job->id); ?>

    <div class="row job_costing_create">
		<?php if($this->session->flashdata('failed')): ?>
			<div class="alert alert-danger">
				<?php echo $this->session->flashdata('failed'); ?>
			</div>
		<?php endif; ?>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 sortable-category">
		<?php 
    	$this->db->where('job_id', $job->id);
    	$this->db->order_by('ordering','asc');
		$categorys = $this->db->get('jobcosting_jobs_category')->result();
		
		$planned_sub_total = '';
		$actual_sub_total = '';
		// Start foreach category
		foreach($categorys as $category):
		
		$this->db->select("MAX(item_unit) AS max_unit");  
    	$this->db->where('job_id', $job->id);
    	$this->db->where('job_category_id', $category->id);
		$max_unit_row = $this->db->get('jobcosting_jobs_costing')->row();
		$max_unit = $max_unit_row->max_unit;
		?>

		<div id="listJobCategory_<?php echo $category->id; ?>"><h3 style="margin-top: 0px;">
			<?php echo $category->category_name; ?>
			<a data-toggle="modal" href="#EditCategory" onclick="ShowEditCategory(<?php echo $category->id; ?>);" id="ShowEditCategory_<?php echo $category->id; ?>" data-val="<?php echo $category->category_name; ?>" class="pull-right">
				<img height="22" src="<?php echo base_url(); ?>images/icon_edit.png" />
			</a>
			<a href="<?php echo base_url(); ?>job/job_category_item_delete/<?php echo $category->id; ?>/<?php echo $job->id; ?>/<?php echo $type; ?>" onclick="return confirm('Are you sure delete this Category?')" class="pull-right">
				<img height="22" src="<?php echo base_url(); ?>images/delete-icon.png" />
			</a>
		</h3>
		<table class="table table-striped table-bordered" id="listJobCategory_<?php echo $category->id; ?>">
            <thead>
              <tr>
                <th>Items</th>
                <?php //if($max_unit!='0'): ?>
                <!--<th>Mesurement</th>
                <th>Planned - Unit</th>
                <th>Actual - Unit</th>-->
                <?php //endif; ?>
                <th>Planned - Price/Unit (exc. GST)</th>
				<th>Actual - Price/Unit (exc. GST)</th>
				<?php// if($max_unit!='0'): ?>
                <!--<th>Planned - Total (exc. GST)</th>
				<th>Actual - Total (exc. GST)</th> -->
				<?php// endif; ?>
				<?php if($type=='actual'): ?>
				<th>Company</th>
				<th>Contractor</th>
				<th>File</th>
				<th>Status</th>
				<?php endif; ?>
              </tr>
            </thead>
            <tbody id="<?php echo $category->id; ?>" class="category_<?php echo $category->id; ?> connectedSortable">
              	<?php 
	            $this->db->select("jobcosting_jobs_costing.*");  
		    	$this->db->where('job_id', $job->id);
		    	$this->db->where('job_category_id', $category->id);
		    	$this->db->order_by('ordering','asc');
				$items = $this->db->get('jobcosting_jobs_costing')->result();  
				
				$planned_sub_total1 = '';
				$actual_sub_total1 = '';
				foreach($items as $id => $item)
				{
					
					if($type=='actual'):
						$contact_id = '';
						$company_name = '';
						$contact_name = '';
						$file = '';
						$cons_job_id = $item->construction_job_id;
						$key_task_id = $item->key_task_id;

						$cons_template_id = $this->db->get_where('construction_development',array('id'=>$cons_job_id))->row()->tendering_template_id;
						
						if($cons_template_id){
							
							if($key_task_id!='0'){
								//$cons_item_id = $this->db->get_where('construction_tendering_template_items',array('template_id'=>$cons_template_id, 'construction_template_task_id'=>$key_task_id))->row()->id;
								$cons_item_id = $key_task_id;
								$cons_contact_id = $this->db->get_where('construction_tendering_job_status',array('job_id'=>$cons_job_id, 'item_id'=>$cons_item_id, 'status'=>'1'))->row();
								if($cons_contact_id->contact_id>'0'){
									$this->db->select('contact_contact_list.id, contact_contact_list.contact_first_name, contact_contact_list.contact_last_name, contact_company.company_name');
									$this->db->join('contact_company', 'contact_company.id = contact_contact_list.company_id', 'left'); 
									$cons_contact = $this->db->get_where('contact_contact_list',array('contact_contact_list.id'=>$cons_contact_id->contact_id))->row();
									$company_name = $cons_contact->company_name;
									$contact_id = $cons_contact->id;
									$contact_name = $cons_contact->contact_first_name.' '.$cons_contact->contact_last_name;
								}elseif($cons_contact_id->company_id>'0'){
									$this->db->select('contact_company.company_name');
									$cons_contact = $this->db->get_where('contact_company',array('id'=>$cons_contact_id->company_id))->row();
									$company_name = $cons_contact->company_name;
								}
								
								if($cons_contact_id){
									//$cons_file_id = $this->db->get_where('construction_tendering_jobs',array('job_id'=>$cons_job_id, 'template_id'=>$cons_template_id, 'item_id'=>$cons_item_id))->row()->id;
									$item_contact_id = $this->db->get_where('construction_tendering_item_contacts',array('contact_contact_list_id'=>$cons_contact_id->contact_id, 'item_id'=>$cons_item_id))->row()->id;
									$cons_file_id = $this->db->get_where('construction_tendering_jobs',array('job_id'=>$cons_job_id, 'template_id'=>$cons_template_id, 'contact_id'=>$item_contact_id, 'item_id'=>$cons_item_id))->row()->id;
									$cons_file = $this->db->get_where('construction_tendering_received_files',array('construction_tendering_job_id'=>$cons_file_id))->result();

									foreach($cons_file as $cons_fl){
										$file .= '<a target="_blank" href="http://'.$_SERVER['SERVER_NAME'].'/wpconstruction/constructions/download_quote/'.$cons_fl->received_fid.'"><img src="'.base_url().'images/file.png"></a>';
									}
								}
								
							}
						}
					endif;
				?>
									
					<tr id="listItem_<?php echo $item->id; ?>"> 
	                   <td>

		                   <?php echo $item->item_name; ?>
		                   <input type="hidden" name="id[]" value="<?php echo $item->id; ?>" />
		                   
		                   <?php if($max_unit=='0'): ?>
		                   <input value="1" type="hidden" name="units[]" id="units_<?php echo $item->id; ?>" />
		                   <input value="1" type="hidden" name="units_actual[]" id="units_actual_<?php echo $item->id; ?>" />
		                   <input value="<?php echo number_format($item->total); ?>" type="hidden" name="total[]" id="units_price_unit_<?php echo $item->id; ?>" class="units_price_unit" />
		                   <input value="<?php echo number_format($item->total_actual); ?>" type="hidden" name="actual_total[]" id="actual_units_price_unit_<?php echo $item->id; ?>" class="units_price_unit" />
		                   <?php endif; ?>
	                   </td>
	                   <?php //if($max_unit!='0'): ?>
	                  <!-- <td>-->
	                   	   <?php 
		                    //$item_unit = array( '1'=>'Days', '2'=>'Hours', '3'=>'m2', '4'=>'Units', '5'=>'Dollars'); 
		                   // echo $item_unit[$item->item_unit];
		                   ?>
	                   <!--</td>
	                   <td>-->
		                   <?php //if($item->item_unit=='0'): ?>
		                  <!-- <input value="1" type="hidden" name="units[]" id="units_<?php// echo $item->id; ?>" />
		                   <?php// else: ?>
		                   <!--<input <?php //if($type=='actual'){ echo 'disabled=""'; } ?> value="<?php //echo $item->units; ?>" type="text" name="units[]" id="units_<?php //echo $item->id; ?>" onkeyup="unit(<?php //echo $item->id; ?>);" class="form-control input-sm" />
		                   <?php //endif; ?>
	                  <!-- </td>
	                   <td>-->
		                   <?php// if($item->item_unit=='0'): ?>
		                   <!--<input value="1" type="hidden" name="units_actual[]" id="units_actual_<?php //echo $item->id; ?>" />
		                   <?php// else: ?>
		                   <!--<input <?php //if($type=='planned'){ echo 'disabled=""'; } ?> value="<?php //echo $item->units_actual; ?>" type="text" name="units_actual[]" id="units_actual_<?php// echo $item->id; ?>" onkeyup="unit_actual(<?php //echo $item->id; ?>);" class="form-control input-sm" />
		                   <?php //endif; ?>
	                   <!--</td>-->
	                   <?php //endif; ?>
	                   <td>
							<div class="input-group">
							  <span class="input-group-addon">$</span>
							  <input value="<?php echo number_format($item->price_unit); ?>" type="text" name="price_unit[]" id="price_unit_<?php echo $item->id; ?>" onkeyup="price_unit1(<?php echo $item->id; ?>);" class="form-control input-sm" <?php if($type=='actual'){ echo 'disabled=""'; } ?> /> 
							</div>
	                   </td>
						<td>
							<div class="input-group">
							  <span class="input-group-addon">$</span>
							  <input value="<?php  echo number_format($item->price_unit_actual); ?>" type="text" name="actual_price_unit[]" id="actual_price_unit_<?php echo $item->id; ?>" onkeyup="price_unit_actual1(<?php echo $item->id; ?>);" class="form-control input-sm" <?php if($type=='planned'){ echo 'disabled=""'; } ?>  /> 
							</div>
						</td>
						
						<?php //if($max_unit!='0'): ?>
	                   <!--<td>
							<div class="input-group">
							  <span class="input-group-addon">$</span>-->
	                   			<input readonly="" value="<?php echo number_format($item->total); ?>"type="hidden" name="total[]" id="units_price_unit_<?php echo $item->id; ?>" class="form-control input-sm units_price_unit" />
							<!--</div>
	                   </td>-->

						<!--<td>
							<div class="input-group">
							  <span class="input-group-addon">$</span>-->
	                   			<input readonly="" value="<?php echo number_format($item->total_actual); ?>" type="hidden" name="actual_total[]" id="actual_units_price_unit_<?php echo $item->id; ?>" class="form-control input-sm units_price_unit" />
							<!--</div>
	                   </td>-->
	                   <?php //endif; ?>
	                   
	                   <?php if($type=='actual'): ?>
	                   <td>
	                   <?php echo $company_name; ?>
	                   <?php 
	                   $contact_company_id = $item->contact_company_id;
	                   if($contact_company_id!='0'){
					   		echo $company_name = $this->db->get_where('contact_company',array('id'=>$contact_company_id))->row()->company_name;
					   }
	                   
	                   ?>
	                   </td>
					   <td>
					   <?php echo $contact_name; ?>
					   <?php 
	                   $contact_contact_id = $item->contact_contact_id;

	                   if($contact_contact_id!='0'){
					   		$contact_contact = $this->db->get_where('contact_contact_list',array('id'=>$contact_contact_id))->row();
	                   echo $contact_name = $contact_contact->contact_first_name.' '.$contact_contact->contact_last_name;
					   }
	                   
	                   ?>
					   </td>
					   <td>
					   <?php echo $file; ?>
					   <?php 
					   if($item->filename!=''){
					   		echo '<a target="_blank" href="'.base_url().'uploads/'.$item->filename.'"><img src="'.base_url().'images/file.png"></a>';
					   }
	                   ?>
					   </td>
					   <td>
					   <?php
					   if($file!=''){
					   		if($item->xero_status=='1'){
								echo '<input style="margin-bottom:5px;" type="button" class="btn btn-success" value="Uploaded to XERO">';	
							}
					   		if($item->email_status=='1'){
								echo '<input style="margin-bottom:5px;" type="button" class="btn btn-success" value="E-mail sent">';	
							}
					   		echo '<a data-toggle="modal" href="#Send'.$item->id.'" class="btn btn-danger">Send P/O</a>';
					   }
					   ?>
					   <?php
					   if($item->filename!=''){
					   		if($item->xero_status=='1'){
								echo '<input style="margin-bottom:5px;" type="button" class="btn btn-success" value="Uploaded to XERO"><br>';	
							}
					   		if($item->email_status=='1'){
								echo '<input style="margin-bottom:5px;" type="button" class="btn btn-success" value="E-mail sent"><br>';	
							}
					   		echo '<a data-toggle="modal" href="#Send'.$item->id.'" class="btn btn-danger">Send P/O</a>';
					   }
					   ?>
					   
					   <!-- MODAL Send Email -->
						<div id="Send<?php echo $item->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-body">	
								<div class="row">		
									<div class="col-xs-12 col-sm-12 col-md-12">
					                  	<div class="form-group">
					                  		Purchase Order for <?php echo $item->item_name; ?> - <?php echo $item->item_id; ?> / <?php echo count($items); ?>
										</div>
					                </div>
					                <div class="col-xs-12 col-sm-12 col-md-12">
					                  	<div class="form-group">
                                                          		    
													<label for="date_issued"></label>
													<div class="col-xs-6 col-sm-12 col-md-12">
														<a data-toggle="modal" href="#Preview<?php echo $item->id; ?>" class="btn btn-danger pull-right"> <?php
														   if($item->email_status=='1'){
															echo 'Re-send e-mail';	
														   }else{
															echo 'Send e-mail';
														   }
														   ?> </a> 					
												
										<a onclick="xeroItemContact(<?php echo $item->id; ?>)" href="#" class="btn btn-danger pull-right">
										<?php
										if($item->xero_status=='1'){
											echo 'Re-upload to XERO';	
										}else{
											echo 'Upload to XERO';
										}
										?>
										</a>
										<!--<a data-toggle="modal" href="#Preview--><?php //echo $item->id; ?><!--" class="btn btn-danger pull-right">Preview E-mail</a>-->
									</div>
								</div>			
							</div>	
						</div>
						
						<!-- MODAL Xero VAlue -->
						<div id="XeroItemContact" style="display: none;">	
							<div id="collect-xero-item-<?php echo $item->id; ?>">
							<?php
							$this->db->select("id,item_name,construction_job_id,key_task_id,contact_contact_id");  
					    	$this->db->where('job_id', $job->id);
							$all_items = $this->db->get('jobcosting_jobs_costing')->result();
							foreach($all_items as $all_item):
								if($item->contact_contact_id=='0'){
									$cons_job_id1 = $all_item->construction_job_id;
									$key_task_id1 = $all_item->key_task_id;
									
									$cons_template_id1 = $this->db->get_where('construction_development',array('id'=>$cons_job_id1))->row()->tendering_template_id;
									//$cons_item_id1 = $this->db->get_where('construction_tendering_template_items',array('template_id'=>$cons_template_id1, 'construction_template_task_id'=>$key_task_id1))->row()->id;
									$cons_item_id1 = $key_task_id1;
									$cons_contact_id1 = $this->db->get_where('construction_tendering_job_status',array('job_id'=>$cons_job_id1, 'item_id'=>$cons_item_id1, 'status'=>'1'))->row();
									$cons_contact1 = $this->db->get_where('contact_contact_list',array('contact_contact_list.id'=>$cons_contact_id1->contact_id))->row();
									$contact_id1 = $cons_contact1->id;
									if($contact_id1==$contact_id):
										echo '<input type="checkbox" name="item_id[]" value="'.$all_item->id.'"> '.$all_item->item_name.'<br>';
									endif;
								}else{
									if($all_item->contact_contact_id==$item->contact_contact_id):
										echo '<input type="checkbox" name="item_id[]" value="'.$all_item->id.'"> '.$all_item->item_name.'<br>';
									endif;
								}							
								
							endforeach;
							?>
			                </div>
			                <input type="hidden" id="collect-xero-contact-<?php echo $item->id; ?>" value="<?php if($item->contact_contact_id!='0'){ echo $item->contact_contact_id; }else{ echo $contact_id; } ?>" />
						</div>
						
						<!-- MODAL Preview Email -->
						<div style="top:15%" id="Preview<?php echo $item->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
							<div class="modal-body">
<form action="<?php echo base_url(); ?>job/send_email_contact/<?php echo $item->id; ?>/<?php echo $job->id; ?>/<?php if($item->contact_contact_id!='0'){ echo $item->contact_contact_id; }else{ echo $contact_id; } ?>" method="POST">	
								<div class="row">		
									<div class="col-xs-12 col-sm-12 col-md-12">
					                  	
									<label for="">Messages:</label>
									<textarea style="margin-bottom: 5px;" rows="5" id="message" name="message" class="form-control">Hi <?php echo $company_name; ?> - <?php echo $contact_name; ?>,

Here is purchase order for <?php echo $job->job_number; ?> - <?php echo $job->jobname; ?> - <?php echo $item->item_name; ?> - <?php echo $item->item_id; ?> / <?php echo count($items); ?>
If you have any questions, please let us know.

Thank you,
<?php $wp_company_id = $this->session->userdata('user')->company_id;
echo $this->db->get_where('wp_company',array('id'=>$wp_company_id))->row()->client_name;?>	
										</textarea>
					                  	
										
					                </div>
				                </div>
				                <div class="row">
				                	<div class="col-xs-12">
				                		<label for="">GST Number:</label>
				                		<input type="text" pattern='\d{3}[\-]\d{3}[\-]\d{3}' title='Format GST xxx-xxx-xxx' name="gst" required="1" class="form-control" value="115-055-342">
				                	</div>
				                </div> 
				                <div class="row">
				                	<div class="col-xs-6">
				                		<label for="">Person In Charge:</label>
				                		<input type="text" name="pic" required="1" class="form-control" value="<?php echo $this->db->get_where('wp_company',array('id'=>$this->session->userdata('user')->company_id))->row()->person_in_charge;?>">
				                	</div>
				                	<div class="col-xs-6">
				                		<label for="">Telephone:</label>
				                		<input type="text" name="telephone" required="1" class="form-control" value="<?php echo $this->db->get_where('wp_company',array('id'=>$this->session->userdata('user')->company_id))->row()->phone_number;?>	">
				                	</div>
				                </div>
				                <div class="row" style="margin-bottom: 10px;">
				                	<div class="col-xs-6">
				                		<label for="">Delivery Address:</label>
				                		
				                		<textarea class="form-control" rows="4"  name="address" required="1"><?php echo $this->db->get_where('wp_company',array('id'=>$this->session->userdata('user')->company_id))->row()->address;?> </textarea>
				                	</div>
				                	<div class="col-xs-6">
				                		<label for="">Delivery Instructions:</label>
				                		
				                		<textarea class="form-control" rows="4"  name="instructions" required="1"><?php echo $job->information ?></textarea>

				                	</div>
				                </div>
				                <div class="row" >
									<label for="date_issued"></label>
									<div class="col-xs-6 col-sm-12 ">
										
										<input type="submit" class="btn btn-danger pull-right" name="submit" value="send">
										<button type="button" class="btn btn-danger pull-right" data-dismiss="modal" aria-hidden="true">Close</button>
									</div>
								</div>
							</div>	
						</div>
				
				</form>						
					   </td>
					   <?php endif; ?>
	               </tr>
				<?php
					$planned_sub_total1 += $item->total;
					$actual_sub_total1 += $item->total_actual;
				}
				?>
            </tbody>
         </table>  </div>
         
         
         
         <?php
         $planned_sub_total += $planned_sub_total1;
         $actual_sub_total += $actual_sub_total1;
         endforeach;
         // End foreach category
         ?>
        
         <table class="table table-striped table-bordered">
         	<tbody>
				<?php if($type=="planned"): ?><tr>
					<td style="border: 1px solid #fff;background-color: #fff;" colspan="9"><strong class="pull-right">Planned Total</strong></td>
					<td style="border: 1px solid #fff;background-color: #fff;" colspan="3">$ <strong id="sub-total"><?php echo number_format($planned_sub_total); ?></strong></td>
				</tr>

				<?php else : ?><tr>
					<td style="border: 1px solid #fff;background-color: #fff;" colspan="9"><strong class="pull-right">Actual Total</strong></td>
					<td style="border: 1px solid #fff;background-color: #fff;" colspan="3">$ <strong id="actual_sub-total"><?php echo number_format($actual_sub_total); ?></strong></td>
				</tr><?php endif; ?>

				<tr>
					<td style="border: 1px solid #fff;background-color: #fff;" colspan="9"><strong class="pull-right">Sale Price of Job</strong></td>
					<td style="border: 1px solid #fff;background-color: #fff; padding-top: 3px;" colspan="3">
					<div class="input-group">
					    
						<span class="input-group-addon">$</span>
						<input value="<?php if($job->sale_price!='0'){ echo $job->sale_price; } ?>" style="padding: 5px;width: 100%;" type="text" name="sale_price" id="sale_price" class="form-control input-sm" <?php if($type=='planned'){ echo 'onkeyup="sale();"'; } else{echo 'onkeyup="sale_actual();"';} ?> />
						</div>
					</td>
				</tr>

				<!--<tr>
					<td style="border: 1px solid #fff;background-color: #fff;" colspan="9"><strong class="pull-right">Actual - Margin</strong></td>
					<td style="border: 1px solid #fff;background-color: #fff;" colspan="3">
						<input value="<?php if($job->margin_actual!='0'){ echo $job->margin_actual; } ?>" style="padding: 5px;width: 80%;" type="text" name="margin_actual" id="actual_margin" onkeyup="actual_margin1();" <?php if($type=='planned'){ echo 'disabled=""'; } ?> /><strong style="font-size: 15px;">&nbsp%</strong>
					</td>
				</tr>-->

				<tr>
					<td style="border: 1px solid #fff;background-color: #fff;" colspan="9"><strong class="pull-right">Potential Profit</strong></td>
					<td style="border: 1px solid #fff;background-color: #fff;" colspan="3">
						$ <strong id="<?php if($type=="actual"){echo actual_total;}else{ echo total;}?>"><?php 
							if($type=="planned"){
								if($planned_sub_total){ 
									$total = $job->sale_price - $planned_sub_total; 
									
									echo number_format($total);
								}
							}
							else if($type=="actual"){
								
									$total = $job->sale_price - $actual_sub_total; 
									
									echo number_format($total);
								
							}
						?>
						</strong>
					</td>
				</tr>
				<!--<tr>
					<td style="border: 1px solid #fff;background-color: #fff;" colspan="9"><strong class="pull-right">Actual - Total + Margin (exc. GST)</strong></td>
					<td style="border: 1px solid #fff;background-color: #fff;" colspan="3">
						$<strong id="actual_total"><?php 
							if($actual_sub_total){ 
								$total = $actual_sub_total/100*$job->margin_actual; 
								$total = $total + $actual_sub_total;
								echo number_format($total);
							} 
						?>
						</strong>
					</td>
				</tr>-->
            </tbody>
         </table>   
              
        </div>
        
    </div>
    
    <div class="row"> 
		<div class="col-xs-12 col-sm-12 col-md-12">
		<?php 
			$attr_back = array(
              'name'	=> 'back',
			  'id'		=> 'back',
			  'value'	=> 'Back',
			  'class'	=> 'btn btn-danger pull-right"',
			  'type'	=> 'submit',
            ); 
            $attr_save = array(
              'name'	=> 'submit',
			  'id'		=> 'next',
			  'value'	=> 'Save',
			  'class'	=> 'btn btn-danger pull-right"',
			  'type'	=> 'submit',
            );
			echo form_submit($attr_save);
			echo '<a class="btn btn-danger pull-right" href="'.base_url().'job/job_view/'.$job->template_id.'">Back</a>';
        ?>
        <input type="hidden" name="type" value="<?php echo $type; ?>" />
        </div>
    </div>
    
<?php echo form_close(); ?>

<!-- MODAL Xero -->
<div id="xeroitem-modal">
	<form action="<?php echo base_url();?>job/upload_xero" method="post">
		<div class="modal-body">
			<p style="padding-bottom: 5px;border-bottom: 1px solid #eee;font-weight: bold;">Item List</p>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
	              	<div id="load-item-xero" class="form-group">

					</div>
	            </div>
			</div>
		</div>
		<div class="modal-footer">
			<input type="hidden" id="xero_contact" name="contact_id" value=""/>
			<input type="hidden" name="job_id" value="<?php echo $job->id; ?>"/>
			<div class="row">
				<div class="col-xs-6 col-sm-12 col-md-12">
					<button type="button" class="btn btn-danger pull-right" id="btnDone">Close</button>
					<input type="submit" class="btn btn-danger pull-right" name="submit" value="OK">
				</div>
			</div>
		</div>
	</form>		
</div> 

<!-- MODAL Add Item Company -->
<div id="AddCompany" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="POST" action="<?php echo base_url(); ?>job/add_item_company" enctype="multipart/form-data">
		<div class="modal-header">
			<h3 id="myModalLabel">Add Item Company</h3>
		</div>
	
		<div class="modal-body">	
			<div class="row">		
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="">Category Name:*</label>
                  		<select id="company_category_id" name="category_id" class="form-control" required="">
                  			<option value="">--Select a Category--</option>
                  			<?php

                  			$category = $this->db->get_where('jobcosting_jobs_category',array('job_id'=>$this->uri->segment(3)))->result();
                  			foreach($category as $row):
                  				if($row->category_name!='Tendering System'){
                  						echo '<option value="'.$row->id.'">'.$row->category_name.'</option>';
                  				}
                  			endforeach;
                  			?>
                  		</select>
					</div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="">Item Name:*</label>
                  		<select required="" name="item_id" id="company_item_id" class="form-control">
							<option value="">--Select a Item--</option>
							
						</select>
					</div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="">Comapny Name:*</label>
                  		<select id="company_company_id" name="company_id" class="form-control" required="">
                  			<option value="">--Select a Company--</option>
                  			<?php
                  			$wp_company_id = $this->session->userdata('user')->company_id;
                  			$company = $this->db->get_where('contact_company',array('wp_company_id'=>$wp_company_id))->result();
                  			foreach($company as $row):
                  				echo '<option value="'.$row->id.'">'.$row->company_name.'</option>';
                  			endforeach;
                  			?>
                  		</select>
					</div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="">Contact Name:*</label>
                  		<select id="company_contact_id" name="contact_id" class="form-control" required="">
                  			<option value="">--Select a Contact--</option>
                  			
                  		</select>
					</div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">File:*</label>
                  		<input type="file" name="file"/>
					</div>
                </div>
                
				<label for="date_issued"></label>
				<div class="col-xs-6 col-sm-12 col-md-12">
					<input class="btn create width100 pull-right" type="submit" name="submit" value="Submit" />
					<input type="hidden" name="url" value="job/job_costing_create/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>" />
					<button style="margin-right: 10px;" type="button" class="btn cancel width100 pull-right" data-dismiss="modal" aria-hidden="true">Cancel</button>
					
				</div>
			</div>			
		</div>	    
	</form>
</div>

<!-- MODAL Add Item -->
<div id="AddItem" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="POST" action="<?php echo base_url(); ?>job/add_item/<?php echo $this->uri->segment(3); ?>">
		<div class="modal-header">
			<h3 id="myModalLabel">Add Item</h3>
		</div>
	
		<div class="modal-body">	
			<div class="row">		
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Category Name:*</label>
                  		<select name="category_id" class="form-control " required="">
                  			<option value="">--Select a Category--</option>
                  			<?php
                  			$category = $this->db->get_where('jobcosting_jobs_category',array('job_id'=>$this->uri->segment(3)))->result();
                  			foreach($category as $row):
                  				echo '<option value="'.$row->id.'">'.$row->category_name.'</option>';
                  			endforeach;
                  			?>
                  		</select>
					</div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Item Name:*</label>
                  		<select class="form-control multiselectbox" multiple="" required="" name="items[]" id="items" >
							<option style="display: none;" value="">--Select a Item--</option>
							<?php
							$user = $this->session->userdata('user');
							$wp_company_id = $user->company_id;
							
							$this->db->where('company_id',$wp_company_id);
							$rows = $this->db->get('jobcosting_items')->result();
							foreach($rows as $row)
							{
							?>
							<option value="<?php echo $row->id; ?>"><?php echo $row->item_name; ?></option>
							<?php
							}
							?>
				</select>
                  	

			</div>
                </div>
                <!--
                <div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Select Unit:</label>
                  		<select name="item_unit" class="form-control">
                  			<option value="">--Select a Unit--</option>
                  			<option value="1">Days</option>
                  			<option value="2">Hours</option>
                  			<option value="3">m2</option>
                  			<option value="4">Units</option>
                  			<option value="5">Dollars</option>
                  		</select>
					</div>
                </div>
                -->
                <!--
                <div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Price:</label>
                  		<input type="text" name="item_price" class="form-control"/>
					</div>
                </div>-->
                
				<label for="date_issued"></label>
				<div class="col-xs-6 col-sm-12 col-md-12">
					<input class="btn create width100 pull-right" type="submit" name="submit" value="Submit" />
					<input type="hidden" name="url" value="job/job_costing_create/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>" />
					<button style="margin-right: 10px;" type="button" class="btn cancel width100 pull-right" data-dismiss="modal" aria-hidden="true">Cancel</button>
					
				</div>
			</div>			
		</div>	    
	</form>
</div>

<!-- MODAL Add Category -->
<div id="AddCategory" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="POST" action="<?php echo base_url(); ?>job/add_category/<?php echo $this->uri->segment(3); ?>">
		<div class="modal-header">
			<h3 id="myModalLabel">Add Category</h3>
		</div>
	
		<div class="modal-body">	
			<div class="row">		
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Category Name:*</label>
                  		<input type="text" name="category_name" class="form-control" required="" />
					</div>
                </div>
                
				<label for="date_issued"></label>
				<div class="col-xs-6 col-sm-12 col-md-12">
					<input class="btn create width100 pull-right" type="submit" name="submit" value="Submit" />
					<input type="hidden" name="url" value="job/job_costing_create/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>" />
					<button style="margin-right: 10px;" type="button" class="btn cancel width100 pull-right" data-dismiss="modal" aria-hidden="true">Cancel</button>
					
				</div>
			</div>			
		</div>	    
	</form>
</div>

<!-- MODAL Edit Category -->
<div id="EditCategory" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="POST" action="<?php echo base_url(); ?>job/edit_category">
		<div class="modal-header">
			<h3 id="myModalLabel">Edit Category</h3>
		</div>
	
		<div class="modal-body">	
			<div class="row">		
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Category Name:*</label>
                  		<input value="" type="text" id="category_name" name="category_name" class="form-control" required="" />
					</div>
                </div>
                
				<label for="date_issued"></label>
				<div class="col-xs-6 col-sm-12 col-md-12">
					<input class="btn create width100 pull-right" type="submit" name="submit" value="Submit" />
					<input type="hidden" id="category_id" name="category_id" value="" />
					<input type="hidden" name="url" value="job/job_costing_create/<?php echo $this->uri->segment(3); ?>/<?php echo $this->uri->segment(4); ?>" />
					<button id="close-button" style="margin-right: 10px;" type="button" class="btn cancel width100 pull-right" data-dismiss="modal" aria-hidden="true">Cancel</button>
					
				</div>
			</div>			
		</div>	    
	</form>
</div> 

<script type="text/javascript">
	$(document).ready(function() {
            $('.multiselectbox').selectpicker();
});
 </script>


<script>

$(function()
{
	<?php 
		$tableids = '';
		foreach($categorys as $cat)
		{
			$tableids = $tableids.'table tbody.category_'.$cat->id.', ';
		}
	?>		
		var tblids = '<?php echo $tableids; ?>';
		var numoftblids = tblids.length;
    	var restable = tblids.substring(0, numoftblids - 2);

	$( restable ).sortable({
		connectWith: ".connectedSortable",
		items: ">*:not(.sort-disabled)",
		update : function (event, ui) { 
			order = $(this).sortable('serialize');		
			item_id = ui.item.attr("id");			
			category_id = $('#'+item_id).parent().attr('id');	

			$.ajax({
				url: "<?php echo base_url(); ?>" + 'job/item_drag/'+category_id+'/'+item_id,
				type: 'POST',
				success: function(data) 
				{
					$.ajax({
						url: "<?php echo base_url(); ?>" + 'job/job_item_ordering',
						type: 'POST',
						data: order,
						success: function(data) 
						{
		 					//location.reload();
						},
					        
					});
				},
			});
		}
	});
	
});
</script>

<script type="text/javascript">

	$(document).ready(function () {
        $("#xeroitem-modal").dialog({ 
            autoOpen: false,
            width : 560, 
            height: 280,
            modal: true
        });

		$("#btnDone").click(function () {
            $('#xeroitem-modal').dialog('close');
        });
        
		
		$('#company_category_id').change(function(){
			category_id = $(this).val();
			$.ajax({
				url: "<?php echo base_url(); ?>" + 'job/ajax_load_category_item/'+category_id,
				type: 'POST',
				success: function(data) 
				{
					$('#company_item_id').empty();
					$('#company_item_id').append(data);
				},
			});
		});
		
		$('#company_company_id').change(function(){
			company_id = $(this).val();
			$.ajax({
				url: "<?php echo base_url(); ?>" + 'job/ajax_load_company_contact/'+company_id,
				type: 'POST',
				success: function(data) 
				{
					$('#company_contact_id').empty();
					$('#company_contact_id').append(data);
				},
			});
		});
		
	});
	
</script>


<script type="text/javascript">
	
	function ShowEditCategory(cat_id)
	{
		cat_name = $('#ShowEditCategory_'+cat_id).attr('data-val');
		$('#category_name').empty();
		$('#category_name').val(cat_name);
		$('#category_id').val(cat_id);
		//$('#EditCategory').addClass('in');
	}

	function xeroItemContact(item_id)
	{
		$("#xeroitem-modal").dialog('open');
        xero_item = $('#collect-xero-item-'+item_id).html();
        xero_contact = $('#collect-xero-contact-'+item_id).val();
        $('#load-item-xero').empty();
        $('#load-item-xero').append(xero_item);
        $('#xero_contact').empty();
        $('#xero_contact').val(xero_contact);
        return false;
	}

       




</script>

<script>
  	function unit(id){
  		
  		before_subtotal = $('#units_price_unit_'+id).val();
		before_subtotal = before_subtotal.replace(/,/g,'');

  		before_total = $('#sub-total').html();
		before_total = before_total.replace(/,/g,'');

  		total = before_total-before_subtotal;
  		
  		units = $('#units_'+id).val();

  		price_unit = $('#price_unit_'+id).val();
		price_unit = price_unit.replace(/,/g,'');

  		if(price_unit==''){
			price_unit = 1;
		}
  		subtotal_val = price_unit;
  		subtotal = Number(subtotal_val).toLocaleString('en');
  		$('#units_price_unit_'+id).val(subtotal);
  		
  		total_val = total+subtotal_val;
		total = Number(total_val).toLocaleString('en');

  		$('#sub-total').empty();
  		$('#sub-total').append(total);
  		
  		margin = $('#margin').val();		
  		alltotal = total_val/100;
  		alltotal = alltotal*margin;
  		alltotal = Number(alltotal) + Number(total_val);
		alltotal = Number(alltotal).toLocaleString('en');

  		$('#total').empty();
  		$('#total').append(alltotal);
  	}
  	function unit_actual(id){
  		
  		before_subtotal = $('#actual_units_price_unit_'+id).val();
		before_subtotal = before_subtotal.replace(/,/g,'');

  		before_total = $('#actual_sub-total').html();
		before_total = before_total.replace(/,/g,'');

  		total = before_total-before_subtotal;
  		
  		units = $('#units_actual_'+id).val();

  		price_unit = $('#actual_price_unit_'+id).val();
		price_unit = price_unit.replace(/,/g,'');

  		if(price_unit==''){
			price_unit = 1;
		}
  		subtotal_val = units*price_unit;
  		subtotal = Number(subtotal_val).toLocaleString('en');
  		$('#actual_units_price_unit_'+id).val(subtotal);
  		
  		total_val = total+subtotal_val;
		total = Number(total_val).toLocaleString('en');

  		$('#actual_sub-total').empty();
  		$('#actual_sub-total').append(total);
  		
  		margin = $('#actual_margin').val();		
  		alltotal = total_val/100;
  		alltotal = alltotal*margin;
  		alltotal = Number(alltotal) + Number(total_val);
		alltotal = Number(alltotal).toLocaleString('en');

  		$('#actual_total').empty();
  		$('#actual_total').append(alltotal);
  	}
  	function price_unit1(id){
  	
  		before_subtotal = $('#units_price_unit_'+id).val();
		before_subtotal = before_subtotal.replace(/,/g,'');

  		before_total = $('#sub-total').html();
		before_total = before_total.replace(/,/g,'');

  		total = before_total-before_subtotal;
  		
  		units = $('#units_'+id).val();

  		price_unit = $('#price_unit_'+id).val();
		price_unit = price_unit.replace(/,/g,'');

  		if(units!= 1){
			units = 1;
		}
  		subtotal_val = units*price_unit;
  		subtotal = Number(subtotal_val).toLocaleString('en');
  		$('#units_price_unit_'+id).val(subtotal);
  		
  		total_val = total+subtotal_val;
		total = Number(total_val).toLocaleString('en');

  		$('#sub-total').empty();
  		$('#sub-total').append(total);
  		
  		sale = $('#sale_price').val();		
  		alltotal = sale - total_val;
  		
		alltotal = Number(alltotal).toLocaleString('en');

  		$('#total').empty();
  		$('#total').append(alltotal);
  	}
  	function price_unit_actual1(id){
  	
  		before_subtotal = $('#actual_units_price_unit_'+id).val();
		before_subtotal = before_subtotal.replace(/,/g,'');

  		before_total = $('#actual_sub-total').html();
		before_total = before_total.replace(/,/g,'');

  		total = before_total-before_subtotal;
  		
  		units = $('#units_actual_'+id).val();

  		price_unit = $('#actual_price_unit_'+id).val();
		price_unit = price_unit.replace(/,/g,'');

  		if(units!=1){
			units = 1;
		}

  		subtotal_val = units*price_unit;
  		subtotal = Number(subtotal_val).toLocaleString('en');
  		$('#actual_units_price_unit_'+id).val(subtotal);
  		
  		total_val = total+subtotal_val;
		total = Number(total_val).toLocaleString('en');

  		$('#actual_sub-total').empty();
  		$('#actual_sub-total').append(total);
  		
  		sale = $('#sale_price').val(); 		
  		alltotal = sale - total_val;
  		
  		
		alltotal = Number(alltotal).toLocaleString('en');
  		$('#actual_total').empty();
  		$('#actual_total').append(alltotal);
  	}

  	function sale(){
  		total = $('#sub-total').html();
  		total = total.replace(/,/g,'');

  		sale_price = $('#sale_price').val();

  		alltotal = sale_price-total;
  		alltotal2 = Number(alltotal).toLocaleString('en');
  		$('#total').empty();
  		$('#total').append(alltotal2);
  	}
function sale_actual(){
  		total = $('#actual_sub-total').html();
  		total = total.replace(/,/g,'');

  		sale_price = $('#sale_price').val();

  		alltotal = sale_price - total;
  		alltotal2 = Number(alltotal).toLocaleString('en');
  		$('#actual_total').empty();
  		$('#actual_total').append(alltotal2);	
  	}

  	function margin1(){
  		
  		total = $('#sub-total').html();
  		total = total.replace(/,/g,'');

  		if(total==''){
			total = 1;
		}		
  		margin = $('#margin').val();
  		
  		alltotal = total/100;
  		//console.log(alltotal);
  		alltotal1 = alltotal*margin;
  		alltotal2 = Number(alltotal1) + Number(total);
		alltotal2 = Number(alltotal2).toLocaleString('en');
  		$('#total').empty();
  		$('#total').append(alltotal2);
  	}

	function actual_margin1(){
  		
  		total = $('#actual_sub-total').html();
  		total = total.replace(/,/g,'');

  		if(total==''){
			total = 1;
		}		
  		margin = $('#actual_margin').val();
  		
  		alltotal = total/100;
  		//console.log(alltotal);
  		alltotal1 = alltotal*margin;
  		alltotal2 = Number(alltotal1) + Number(total);
		alltotal2 = Number(alltotal2).toLocaleString('en');
  		$('#actual_total').empty();
  		$('#actual_total').append(alltotal2);
  	}

  	

</script>



