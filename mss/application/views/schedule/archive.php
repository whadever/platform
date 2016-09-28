<script>
	
	function Remove(id){
		
		var b = window.confirm('Are you sure, you want to Delete This ?');
		if(b==true)
		{
			$.ajax({				
				url: window.BaseUrl + 'schedule/schedule_delete/' + id,
				type: 'POST',
				success: function(html) 
				{
					//console.log(data);
					newurl = window.BaseUrl + 'schedule/archive_list';
					window.location = newurl;
				},
			        
			});
		}
	}
	
	function DeletePdf(id,fid,field_name){
		
		var b = window.confirm('Are you sure, you want to Delete This ?');
		if(b==true)
		{

			$.ajax({				
				url: window.BaseUrl + 'schedule/schedule_pdf_delete/' + id + '/' + fid + '/' + field_name,
				type: 'POST',
				success: function(html) 
				{
					//console.log(data);
					$('.in #delete_pdf_'+id+'_'+fid+' a').remove();
					$('.in #delete_pdf_'+id+'_'+fid+' .bootstrap-filestyle input').attr("placeholder", "");
					$('.in #delete_pdf_'+id+'_'+fid+' input#pdf_id').val('');
				},
			        
			});
		}
	}	
	
</script>

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
			url: window.BaseUrl + 'schedule/archive_clear_search',
			type: 'POST',
			success: function(html) 
			{
				//console.log(data);
				newurl = window.BaseUrl + 'schedule/archive_list';
				window.location = newurl;
			},
		        
		});
    });

	$('#code_compliance_certificate').change(function(){
		var issued = $(this).val();
		if(issued=='Issued'){
        	$('#date_issued').css('display', 'block');
		}else{
        	$('#date_issued').css('display', 'none');
		}
    });

               
 });

	function Archive(job_id,id,value)  
	{  		
		$.ajax({				
			url: window.BaseUrl + 'schedule/archive_update?job_id=' + job_id + '&id=' + id + '&value=' + value,
			type: 'POST',
			success: function(data) 
			{	
				newurl = window.BaseUrl + 'schedule/archive_list';
				window.location = newurl;
			},
		        
		}); 			 
	} 
 
</script>

<?php
$ci = & get_instance();
$ci->load->model('schedule_model');
?>

<div class="page-title">
	<div class="row">
		<div class="col-xs-2 col-sm-2 col-md-1">
			<img width="" height="65" src="<?php echo base_url(); ?>/images/mss_schedule.png"  title="Maintenance Schedule" alt="Schedule"/>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-11">
			<h4 style="margin-top:20px;">Schedule Archive Area</h4>
		</div>
	</div>
</div>

<div class="content">
<?php
$schedule_search = $this->session->userdata('schedule_search1');
?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 searchbox">
		    <div class="clickdiv" id="search-header">
		        <strong> <span> Search </span> 
		        <span id="plus" style="<?php if(!empty($schedule_search)){ echo 'display:none;'; } ?>">+</span><span id="minus" style="<?php if(!empty($schedule_search)){ echo 'display:inline;'; }else{ echo 'display:none;'; } ?>">-</span></strong>
		    </div> 
		    <div class="hiders" style="<?php if(!empty($schedule_search)){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">
				<div class="row">
					<form action="<?php echo base_url(); ?>schedule/archive_list" method="post">
				        <div class="col-xs-12 col-sm-8 col-md-8">
			                <label for="company_name">Search</label>
							<input type="text" class="form-control" id="schedule_name" value="<?php if(!empty($schedule_search)){ echo $schedule_search; } ?>" name="schedule_name">
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
			<div class="content-body schedule">
				<div class="table-responsive">
					<table data-role="table" class="table ui-responsive">
						<thead>
							<tr>
								<th>Job Number</th>
								
								<th class="res-hidden">Property Address</th>
								<th>Edit</th>
								<th>Remove</th>
								<th class="res-hidden">Generate Report</th>
								<th class="res-hidden">Save</th>
								<th>Archive</th>
							</tr>
						</thead>
						<tbody>
						<?php
						
						foreach($rows as $row)
						{
						?>
							<tr>
								<td><?php echo $row->job_number; ?></td>
								
								<td class="res-hidden"><?php echo $row->address; ?></td>
								<td><a data-toggle="modal" href="#EditSchedule_<?php echo $row->id; ?>">Edit</a>
									
								</td>
								<td><a onclick="Remove('<?php echo $row->id; ?>');" href="#">Remove</a></td>
								<td class="res-hidden"><a class="form-submit btn btn-info" data-toggle="modal" href="#GenerateSchedule_<?php echo $row->id; ?>">Generate</a></td>
								<td class="res-hidden"><a class="form-submit btn btn-info" href="<?php echo base_url(); ?>schedule/schedule_pdf/<?php echo $row->id; ?>">Save</a></td>
								<td><input checked onclick="Archive(<?php echo "'".$row->job_number."'"; ?>,<?php echo $row->id; ?>,0)" type="checkbox" />
								
								
<div id="GenerateSchedule_<?php echo $row->id; ?>" class="modal hide fade generate_schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form accept-charset="utf-8" enctype="multipart/form-data" method="POST" action="<?php echo base_url(); ?>schedule/generate_schedule/<?php echo $row->id; ?>">
		<div class="modal-header">
			<h3 id="myModalLabel">Generate Schedule</h3>
		</div>

		<div class="modal-body">

			<div class="row">					
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="success-massage">
						
					</div> 
					<div class="form-group" id="delete_pdf_<?php echo $row->id.'_'.$row->code_compliance_certificate_pdf; ?>">
						<label for="upload_document">Code of Compliance: <?php if($row->code_compliance_certificate_pdf!='0'){ echo "<a onclick='DeletePdf(".$row->id.",".$row->code_compliance_certificate_pdf.",1)' href='#'>Delete</a>"; } ?></label>
						<input data-placeholder="<?php echo $row->code_compliance; ?>" type="file" class="filestyle" name="code_compliance_certificate_pdf" data-buttonText="BROWSE">
						<input id="pdf_id" value="<?php echo $row->code_compliance_certificate_pdf; ?>" type="hidden" name="code_compliance_certificate_pdf_id">
					</div> 

					<div class="form-group" id="delete_pdf_<?php echo $row->id.'_'.$row->internal_colours; ?>">
						<label for="upload_document">Internal Colours: <?php if($row->internal_colours!='0'){ echo "<a onclick='DeletePdf(".$row->id.",".$row->internal_colours.",2)' href='#'>Delete</a>"; } ?></label>
						<input data-placeholder="<?php echo $row->internal; ?>" type="file" class="filestyle" name="internal_colours" data-buttonText="BROWSE">
						<input id="pdf_id" value="<?php echo $row->internal_colours; ?>" type="hidden" name="internal_colours_id">
					</div>

					<div class="form-group" id="delete_pdf_<?php echo $row->id.'_'.$row->external_colours; ?>">
						<label for="upload_document">External Colours: <?php if($row->external_colours!='0'){ echo "<a onclick='DeletePdf(".$row->id.",".$row->external_colours.",3)' href='#'>Delete</a>"; } ?></label>
						<input data-placeholder="<?php echo $row->external; ?>" type="file" class="filestyle" name="external_colours" data-buttonText="BROWSE">
						<input id="pdf_id" value="<?php echo $row->external_colours; ?>" type="hidden" name="external_colours_id">
					</div>

					<div class="form-group" id="delete_pdf_<?php echo $row->id.'_'.$row->plans; ?>">
						<label for="upload_document">Plans (Site plan, Floor Plan, Elevations): <?php if($row->plans!='0'){ echo "<a onclick='DeletePdf(".$row->id.",".$row->plans.",4)' href='#'>Delete</a>"; } ?></label>
						<input data-placeholder="<?php echo $row->plan; ?>" type="file" class="filestyle" name="plans" data-buttonText="BROWSE">						
						<input id="pdf_id" value="<?php echo $row->plans; ?>" type="hidden" name="plans_id">
					</div>

					<div class="form-group" id="delete_pdf_<?php echo $row->id.'_'.$row->kitchen_plans; ?>">
						<label for="upload_document">Kitchen/Laundry Plans: <?php if($row->kitchen_plans!='0'){ echo "<a onclick='DeletePdf(".$row->id.",".$row->kitchen_plans.",5)' href='#'>Delete</a>"; } ?></label>
						<input data-placeholder="<?php echo $row->kitchen; ?>" type="file" class="filestyle" name="kitchen_plans" data-buttonText="BROWSE">			
						<input id="pdf_id" value="<?php echo $row->kitchen_plans; ?>" type="hidden" name="kitchen_plans_id">
					</div>
					<div class="form-group" id="delete_pdf_<?php echo $row->id.'_'.$row->factory_order; ?>">
						<label for="upload_document">Final Specification: <?php if($row->factory_order!='0'){ echo "<a onclick='DeletePdf(".$row->id.",".$row->factory_order.",6)' href='#'>Delete</a>"; } ?></label>
						<input data-placeholder="<?php echo $row->factory; ?>" type="file" class="filestyle" name="factory_order" data-buttonText="BROWSE">						
						<input id="pdf_id" value="<?php echo $row->factory_order; ?>" type="hidden" name="factory_order_id">
					</div>

					<div class="form-group" id="delete_pdf_<?php echo $row->id.'_'.$row->job_specific_warranties; ?>">
						<label for="upload_document">Job Specific Warranties: <?php if($row->job_specific_warranties!='0'){ echo "<a onclick='DeletePdf(".$row->id.",".$row->job_specific_warranties.",7)' href='#'>Delete</a>"; } ?></label>
						<input data-placeholder="<?php echo $row->job_specific; ?>" type="file" class="filestyle" name="job_specific_warranties" data-buttonText="BROWSE">						
						<input id="pdf_id" value="<?php echo $row->job_specific_warranties; ?>" type="hidden" name="job_specific_warranties_id">
					</div>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4"> 
						<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4"> 
						<input type="submit" class="btn create width100" value="Save" name="submit">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4"> 
					<a target="_blank" class="form-submit btn btn-info width100" href="<?php echo base_url(); ?>schedule/schedule_pdf/<?php echo $row->id; ?>">Generate</a>
				</div>
			</div>
			
		</div>
	</form>
</div>

<div id="EditSchedule_<?php echo $row->id; ?>" class="modal hide fade schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="POST" action="<?php echo base_url(); ?>schedule/schedule_update/<?php echo $row->id; ?>">
		<div class="modal-header">
			<h3 id="myModalLabel">Edit Schedule</h3>
		</div>

		<div class="modal-body">

			<div class="row">					
				<div class="col-xs-12 col-sm-6 col-md-6">
	                  	<div class="row">

						<div class="col-xs-12 col-sm-12 col-md-12">
		                  	<div class="form-group">
		                  		<label for="client_id">Property:*</label>
		                  		<select required="" name="client_id" class="form-control" id="fuzzOptionsListEdit_<?php echo $row->id; ?>">
									<option value="">--Select Property--</option>
									<?php
									$query = $ci->schedule_model->get_clients();
									$rows = $query->result();
									foreach($rows as $row1)
									{
									?>
									<option <?php if($row->client_id==$row1->id){ echo 'selected'; } ?> value="<?php echo $row1->id; ?>"><?php echo $row1->job_number; ?></option>
									<?php
									}
									?>
								</select>
								<div id="fuzzSearch_<?php echo $row->id; ?>">
								  <div id="fuzzNameContainer">
								    <span class="fuzzName"></span>
								    <span class="fuzzArrow"></span>
								  </div>
								  <div id="fuzzDropdownContainer">
								    <input type="text" value="" class="fuzzMagicBox" placeholder="search.." />
								    <span class="fuzzSearchIcon"></span>
								    <ul id="fuzzResults">
								    </ul>
								  </div>
								</div>
		
							</div>
		                </div>
		
						<div class="col-xs-12 col-sm-12 col-md-12">
		                  	<div class="form-group">
		                  		<label for="legal_description">Legal Description:*</label>
		                  		<input value="<?php echo $row->legal_description; ?>" required="" class="form-control" type="text" name="legal_description" id="legal_description" />
		                  	</div>
		                </div>

						<div class="col-xs-12 col-sm-6 col-md-6">
			      			<div class="form-group">
			      				<label for="corrosion_zone">Corrosion Zone:</label>
			      				<input class="form-control" type="text" name="corrosion_zone" id="corrosion_zone" value="<?php echo $row->corrosion_zone; ?>" />
			      			</div>
			      		</div>
			
						<div class="col-xs-12 col-sm-6 col-md-6">
			      			<div class="form-group">
			      				<label for="wind_zone">Wind Zone:</label>
			      				<input class="form-control" type="text" name="wind_zone" id="wind_zone" value="<?php echo $row->wind_zone; ?>" />
			      			</div>
			      		</div>
			
						<div class="col-xs-12 col-sm-6 col-md-6">
		                  	<div class="form-group">
		                  		<label for="designer_company_name">Company Name:</label>
		                  		<input disabled value="<?php echo $row->designer_company_name; ?>" class="form-control" type="text" name="designer_company_name" id="designer_company_name" />
		                  	</div>
		                </div>	
		
						<div class="col-xs-12 col-sm-6 col-md-6">
		                  	<div class="form-group">
		                  		<label for="designer_phone_number">Phone Number:</label>
		                  		<input disabled value="<?php echo $row->designer_phone_number; ?>" class="form-control" type="text" name="designer_phone_number" id="designer_phone_number" />
		                  	</div>
		                </div>
			
						<div class="col-xs-12 col-sm-12 col-md-12">
			      			<div class="form-group">
			      				<label for="designer_email_address">Email:</label>
			      				<input disabled value="<?php echo $row->designer_email_address; ?>" class="form-control" type="text" name="designer_email_address" id="designer_email_address" />
			      			</div>
			      		</div>

	                  	</div>
	                </div>	

				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="row">		
			
						<div class="col-xs-12 col-sm-12 col-md-12">
							<div class="form-group">
								<label for="template_id">Template:</label>
								<select class="form-control" name="template_id" id="template_id">
									<option value="">--Select Template--</option>
									<?php
									$query = $ci->schedule_model->get_templates();
									$rows = $query->result();
									foreach($rows as $row2)
									{
									?>
									<option <?php if($row->template_id==$row2->id){ echo 'selected'; } ?> value="<?php echo $row2->id; ?>"><?php echo $row2->template_name; ?></option>
									<?php
									}
									?>
								</select>
							</div>
						</div>
			
						<div class="col-xs-12 col-sm-12 col-md-12">
			      			<div class="form-group">
			      				<label for="note">Products:</label>
								<select name="product_id[]" multiple class="form-control fSelect" placeholder="--Select Product(s)--">
			      				<option value="">--Select Product(s)--</option>
								<?php
								$query = $ci->schedule_model->get_products();
								$rows = $query->result();
								foreach($rows as $row3)
								{
									$query_p =$this->db->query("SELECT product_id FROM schedule_product where schedule_id=$row->id");
									$rows_p = $query_p->result();
									$default = '';
									for($a = 0; $a < count($rows_p); $a++)
									{
										if($rows_p[$a]->product_id == $row3->id)
										{
											$default = 'selected="selected"';
											break;
										}
									}
								?>
								<option value="<?php echo '#'.$row3->product_type_id.'#'.$row3->id.'#'.$row3->product_specifications; ?>" <?php echo $default; ?>><?php echo $row3->product_name; ?></option>
								
								<?php
								}
								?>
								</select>
			      			</div>
			      		</div>

						<div class="col-xs-12 col-sm-12 col-md-12">
		                  	<div class="form-group">
		                  		<label for="code_compliance_certificate">Code of Compliance Certificate:</label>
		                  		<select name="code_compliance_certificate" id="code_compliance_certificate_<?php echo $row->id; ?>" class="form-control">
									<option value="">--Select--</option>
									<option <?php if($row->code_compliance_certificate=='Required'){ echo 'selected'; } ?> value="Required">Required</option>
									<option <?php if($row->code_compliance_certificate=='Issued'){ echo 'selected'; } ?> value="Issued">Issued</option>
								</select>
		                  	</div>
		                 </div>
		                 <div class="col-xs-12 col-sm-12 col-md-12" id="date_issued_<?php echo $row->id; ?>" style="<?php if($row->code_compliance_certificate=='Issued'){ echo 'display:block;'; }else{ echo 'display:none'; } ?>">
		                  	<div class="form-group">
		                  		<label for="date_issued">Date Issued:</label>
		                  		<input class="form-control live_datepicker" type="text" name="date_issued" id="" value="<?php if($row->date_issued>'0000-00-00'){ echo date("d-m-Y", strtotime($row->date_issued)); } ?>" />
		                  	</div>
		                 </div>

						<label for="date_issued"></label>
						<div class="col-xs-12 col-sm-4 col-md-4">
						</div>
						<div class="col-xs-6 col-sm-4 col-md-4">
							<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
						</div>
						<div class="col-xs-6 col-sm-4 col-md-4">
							<input value="archive_list" type="hidden" name="url" /><input class="btn create width100" type="submit" name="submit" value="Save" />
						</div>

					</div>
				</div>

			</div>
			
		</div>
	</form>
</div>


<script>
	jQuery(document).ready(function() {	
		
		$('#code_compliance_certificate_<?php echo $row->id; ?>').change(function(){
			var issued = $(this).val();
			
			if(issued=='Issued'){
	        	$('#date_issued_<?php echo $row->id; ?>').css('display', 'block');			
			}else{
	        	$('#date_issued_<?php echo $row->id; ?>').css('display', 'none');
			}
	    });
	});

$(function() {
   $('#fuzzOptionsListEdit_<?php echo $row->id; ?>').fuzzyDropdown({
      mainContainer: '#fuzzSearch_<?php echo $row->id; ?>',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });

  
})
</script>
								
								</td>
							</tr>

						<?php
						}
						?>
						</tbody>
					</table>
				</div>
				
				<?php 
				if($pagination){
					echo $pagination;
				}
				?>
			</div>
		</div>
	</div>
	
</div>



<script>

$(document).ready(function () {
	$('.SlectBox').SumoSelect();
});
	
</script>
<script>
	jQuery(document).ready(function(){

		$('.modal.schedule form').ajaxForm({
			success:function() {
				newurl = window.BaseUrl + 'schedule/archive_list';
				window.location = newurl;	  
			},			
			beforeSubmit:function(){
				var overlay = jQuery('<div id="overlay"><div class="overlay-text">It May Take Some Time</div></div>');
				overlay.appendTo(document.body);
			}
		});
	});
</script>

<script>
	jQuery(document).ready(function(){

		$('.modal.generate_schedule form').ajaxForm({
			success:function(data) {
				newurl = window.BaseUrl + 'schedule/schedule_pdf/' + data;
				window.location = newurl;	  
			},			
			beforeSubmit:function(){
				var overlay = jQuery('<div id="overlay"> </div>');
				overlay.appendTo(document.body);
			}
		});
	});
</script>

<?php
$p_type = '<option value="">--Refine by Category--</option>';
$query1 = $ci->schedule_model->get_product_type();
$rowss = $query1->result();
foreach($rowss as $rows)
{
$p_type .= '<option value="'.$rows->id.'">'.$rows->product_type_name.'</option>';
}
?>

<script>
	
	jQuery(document).ready(function(){
        $('.fSelect').fSelect({
			placeholder: '--Select Product(s)--',
			categories: '<?php echo $p_type; ?>'
		});
    });
</script>
