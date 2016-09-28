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
					newurl = window.BaseUrl + 'schedule/schedule_list';
					window.location = newurl;
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
			url: window.BaseUrl + 'schedule/clear_search',
			type: 'POST',
			success: function(html) 
			{
				//console.log(data);
				newurl = window.BaseUrl + 'schedule/schedule_list';
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

	function Archive(id,value)  
	{  		
		$.ajax({				
			url: window.BaseUrl + 'schedule/archive_update?id=' + id + '&value=' + value,
			type: 'POST',
			success: function(data) 
			{	
				newurl = window.BaseUrl + 'schedule/schedule_list';
				window.location = newurl;
			},
		        
		}); 			 
	} 
 
</script>

<div class="page-title" style="float: left;width: 80%;">
	<div class="row">
		<div class="col-xs-2 col-sm-2 col-md-1">
			<img width="" height="65" src="<?php echo base_url(); ?>/images/mss_schedule.png"  title="Maintenance Schedule" alt="Schedule"/>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-11">
			<h4>Create a new Maintenance Schedule</h4>
			<p>Create beautiful and customisable Schedules for your properties.</p>
		</div>
	</div>
</div>
<div class="page-archive" style="float: left;width: 20%;">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12" style="margin-top: 20px;text-align: right;">
			<a href="<?php echo base_url(); ?>schedule/archive_list"><img style="border: 2px solid #0D446E;border-radius: 5px;" width="" height="65" src="<?php echo base_url(); ?>/images/archive_area.png" title="Archive" alt=""/></a>
		</div>
	</div>
</div>

<div style="clear:both;"></div>

<div class="content">
<?php
$schedule_search = $this->session->userdata('schedule_search');
?>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 searchbox">
		    <div class="clickdiv" id="search-header">
		        <strong> <span> Search </span> 
		        <span id="plus" style="<?php if(!empty($schedule_search)){ echo 'display:none;'; } ?>">+</span><span id="minus" style="<?php if(!empty($schedule_search)){ echo 'display:inline;'; }else{ echo 'display:none;'; } ?>">-</span></strong>
		    </div> 
		    <div class="hiders" style="<?php if(!empty($schedule_search)){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">
				<div class="row">
					<form action="<?php echo base_url(); ?>schedule/schedule_list" method="post">
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
			<div class="col-xs-6 col-sm-10 col-md-10">			
				<div class="title"><?php echo $title; ?></div>
			</div>
			<div class="col-xs-6 col-sm-2 col-md-2">		
				<a data-toggle="modal" class="form-submit btn btn-info new-button" href="#AddSchedule"><img class="plus-icon" src="<?php echo base_url(); ?>images/plus_icon.png" />Add Schedule</a>
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
								<th class="res-hidden">Job Number</th>
								<th>Schedule Name</th>
								
								<th class="res-hidden">Property Address</th>
								<th>Edit</th>
								<th>Remove</th>
								<th class="res-hidden">Generate Report</th>
								<th>Duplicate</th>
								<th>Archive</th>
							</tr>
						</thead>
						<tbody>
						<?php
						
						foreach($rows as $row)
						{
						?>
							<tr>
								<td class="res-hidden"><?php echo $row->job_number; ?></td>
								<td><?php echo $row->schedule_name; ?></td>
								
								<td class="res-hidden"><?php echo $row->number.' '.$row->street.' '.$row->suburb.' '.$row->city; ?></td>
								<td><a data-toggle="modal" href="#EditSchedule_<?php echo $row->id; ?>">Edit</a>
									
								</td>
								<td><a onclick="Remove('<?php echo $row->id; ?>');" href="#">Remove</a></td>
								<td class="res-hidden"><a target="_black" class="form-submit btn btn-info" href="<?php echo base_url(); ?>schedule/schedule_pdf/<?php echo $row->id; ?>">Generate</a></td>
								<td><a onclick="Duplicate(<?php echo $row->id; ?>)" class="form-submit btn btn-info" href="#">Duplicate</a></td>
								<td><input onclick="Archive(<?php echo $row->id; ?>,1)" type="checkbox" /></td>
							</tr>
						<?php
						}
						?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</div>
	
</div>

<!-- MODAL Duplicate Schedule -->
<div id="duplicate-modal">
	<div class="modal-body">
		<p>Duplicate Schedule</p>
	</div>
	<div class="modal-footer">
		<form action="<?php echo base_url();?>schedule/duplicate" method="post">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Property:*</label>
                  		<select required="" name="client_id" class="form-control" id="fuzz_duplicate">
							<option value="">--Select Property--</option>
							<?php
							$query1 = $this->db->query("SELECT * FROM clients order by id ASC");
							$rows1 = $query1->result();
							foreach($rows1 as $row1)
							{
							?>
							<option value="<?php echo $row1->id; ?>"><?php echo $row1->job_number; ?></option>
							<?php
							}
							?>
						</select>
						<div id="fuzzSearch_duplicate">
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
			</div>
			<input type="hidden" id="sch_id" name="sch_id" value=""/>
			<div class="row">
				<div class="col-xs-6 col-sm-6 col-md-6">
					
				</div>
				<div class="col-xs-3 col-sm-3 col-md-3">
					<input id="btnDone" class="btn" type="button" value="Close"/>
				</div>
				<div class="col-xs-3 col-sm-3 col-md-3">
					<input id="delete-document-dev" class="btn" type="submit" value="Save"/>
				</div>
			</div>
		</form>
		
		<div class="clear"></div>
	</div>	
</div> 
<!-- MODAL Duplicate Schedule --> 

<script type="text/javascript">
    
	$(document).ready(function () {
        $("#duplicate-modal").dialog({ 
            autoOpen: false,
            width : 460, 
            height: 220,
            modal: true
        });

		$("#btnDone").click(function () {
            $('#duplicate-modal').dialog('close');
        });
	});

	function Duplicate(id)
	{
		$("#duplicate-modal").dialog('open');
        $('#sch_id').val(id);
        return false;
	}

	$(function() {
	   $('#fuzz_duplicate').fuzzyDropdown({
	      mainContainer: '#fuzzSearch_duplicate',
	      arrowUpClass: 'fuzzArrowUp',
	      selectedClass: 'selected',
	      enableBrowserDefaultScroll: true
	    }); 
	})

</script>

<!-- MODAL Edit Schedule -->
<?php						
foreach($rows as $row)
{
?>
<div id="EditSchedule_<?php echo $row->id; ?>" class="modal hide fade schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form method="POST" action="<?php echo base_url(); ?>schedule/schedule_update/<?php echo $row->id; ?>">

	<div class="first" style="display:block;">
		<div class="modal-header">
			<h3 id="myModalLabel">Edit Schedule<span style="float:right;">Schedule Details</span></h3>
		</div>
	
		<div class="modal-body">	
		    <div class="row">				
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="schedule_name">Schedule Name:*</label>
                  		<input value="<?php echo $row->schedule_name; ?>" required="" class="form-control" type="text" name="schedule_name" id="schedule_name" value="" />
                  	</div>
                </div>		     		
			
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Property:*</label>
                  		<select required="" name="client_id" class="form-control" id="fuzzOptionsListEdit_<?php echo $row->id; ?>">
							<option value="">--Select Property--</option>
							<?php
							$query = $this->db->query("SELECT * FROM clients order by id ASC");
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

				<div class="col-xs-12 col-sm-12 col-md-12">
      				Address
	      		</div>
	
				<div class="col-xs-12 col-sm-4 col-md-4">
	      			<div class="form-group">
	      				<label for="number">Number:*</label>
	      				<input value="<?php echo $row->number; ?>" required="" class="form-control" type="text" name="number" id="number" />
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-8 col-md-8">
	      			<div class="form-group">
	      				<label for="street">Street:*</label>
	      				<input value="<?php echo $row->street; ?>" required="" class="form-control" type="text" name="street" id="street" />
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-6 col-md-6">
	      			<div class="form-group">
	      				<label for="suburb">Suburb:*</label>
	      				<input value="<?php echo $row->suburb; ?>" required="" class="form-control" type="text" name="suburb" id="suburb" />
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-6 col-md-6">
	      			<div class="form-group">
	      				<label for="city">City:*</label>
	      				<input value="<?php echo $row->city; ?>" required="" class="form-control" type="text" name="city" id="city" />
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-6 col-md-6">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="first_<?php echo $row->id; ?>">Next</button>
				</div>
		     	
			</div>				
		</div>
	</div>

	<div class="second" style="display:none;">
		<div class="modal-header">
			<h3 id="myModalLabel">Create Schedule<span style="float:right;">Zone Details</span></h3>
		</div>
	
		<div class="modal-body">	
		    <div class="row">					
				<div class="col-xs-12 col-sm-12 col-md-12">
	      			<div class="form-group">
	      				<label for="corrosion_zone">Corrosion Zone:</label>
	      				<input class="form-control" type="text" name="corrosion_zone" id="corrosion_zone" value="<?php echo $row->corrosion_zone; ?>" />
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-12 col-md-12">
	      			<div class="form-group">
	      				<label for="wind_zone">Wind Zone:</label>
	      				<input class="form-control" type="text" name="wind_zone" id="wind_zone" value="<?php echo $row->wind_zone; ?>" />
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-3 col-md-3">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="prev_second_<?php echo $row->id; ?>">Prev</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">  
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="second_<?php echo $row->id; ?>">Next</button>
				</div>
			</div>
		</div>
	</div>

	<div class="third" style="display:none;">
		<div class="modal-header">
			<h3 id="myModalLabel">Create Schedule<span style="float:right;">Products/Warranties Details</span></h3>
		</div>
	
		<div class="modal-body">	
		    <div class="row">					
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<label for="template_id">Template:</label>
						<select class="form-control" name="template_id" id="template_id">
							<option value="">--Select Template--</option>
							<?php
							$query = $this->db->query("SELECT * FROM template order by template_name ASC");
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
						<select name="product_id[]" multiple class="form-control SlectBox" placeholder="--Select Product(s)--">
	      				<option value="">--Select Product(s)--</option>
						<?php
						$query = $this->db->query("SELECT * FROM product");
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
						<option value="<?php echo $row3->id; ?>" <?php echo $default; ?>><?php echo $row3->product_name; ?></option>
						
						<?php
						}
						?>
						</select>
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-3 col-md-3">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="prev_third_<?php echo $row->id; ?>">Prev</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">  
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="third_<?php echo $row->id; ?>">Next</button>
				</div>
			</div>
		</div>
	</div>

	<div class="fourth" style="display:none;">
		<div class="modal-header">
			<h3 id="myModalLabel">Create Schedule<span style="float:right;">Architect/Builder Details</span></h3>
		</div>
	
		<div class="modal-body">	
		    <div class="row">					
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="designer_company_name">Company Name:</label>
                  		<input disabled value="<?php echo $row->designer_company_name; ?>" class="form-control" type="text" name="designer_company_name" id="designer_company_name" />
                  	</div>
                </div>	

				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="designer_phone_number">Phone:</label>
                  		<input value="<?php echo $row->designer_phone_number; ?>" class="form-control" type="text" name="designer_phone_number" id="designer_phone_number" />
                  	</div>
                </div>
	
				<div class="col-xs-12 col-sm-12 col-md-12">
	      			<div class="form-group">
	      				<label for="designer_email_address">Email:</label>
	      				<input disabled value="<?php echo $row->designer_email_address; ?>" class="form-control" type="text" name="designer_email_address" id="designer_email_address" />
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-3 col-md-3">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="prev_fourth_<?php echo $row->id; ?>">Prev</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="fourth_<?php echo $row->id; ?>">Next</button>
				</div>
			</div>
				
		</div>
	</div>

	<div class="five" style="display:none;">
		<div class="modal-header">
			<h3 id="myModalLabel">Create Schedule<span style="float:right;">Others Details</span></h3>
		</div>
	
		<div class="modal-body">	
		    <div class="row">					
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
	
				<div class="col-xs-12 col-sm-3 col-md-3">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="prev_five_<?php echo $row->id; ?>">Prev</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<input value="schedule_list" type="hidden" name="url" /><input class="btn create width100" type="submit" name="submit" value="Save" />
				</div>
			</div>
				
		</div>
	</div>

</form>
</div>


<script>
	jQuery(document).ready(function() {	
		$('#first_<?php echo $row->id; ?>').click(function(){
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "block");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "none");
		});
		
		$('#second_<?php echo $row->id; ?>').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "block");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "none");
		});

		$('#third_<?php echo $row->id; ?>').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "block");
			$('.in .five').css("display", "none");
		});

		$('#fourth_<?php echo $row->id; ?>').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "block");
		});
		
		$('#prev_second_<?php echo $row->id; ?>').click(function(){
			
			$('.in .first').css("display", "block");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "none");
		});

		$('#prev_third_<?php echo $row->id; ?>').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "block");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "none");
		});

		$('#prev_fourth_<?php echo $row->id; ?>').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "block");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "none");
		});

		$('#prev_five_<?php echo $row->id; ?>').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "block");
			$('.in .five').css("display", "none");
		});

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
<?php						
}
?>



<!-- MODAL Add Schedule -->
<div id="AddSchedule" class="modal hide fade schedule" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

<form method="POST" action="<?php echo base_url(); ?>schedule/schedule_add">
	<div class="first" style="display:block;">
		<div class="modal-header">
			<h3 id="myModalLabel">Create Schedule<span style="float:right;">Schedule Details</span></h3>
		</div>
	
		<div class="modal-body">	
		    <div class="row">					
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="schedule_name">Schedule Name:*</label>
                  		<input required="" class="form-control" type="text" name="schedule_name" id="schedule_name" value="" />
                  	</div>
                </div>		     		
			
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Property:*</label>
                  		<select required="" name="client_id" class="form-control" id="fuzzOptionsList">
							<option value="">--Select Property--</option>
							<?php
							$query = $this->db->query("SELECT * FROM clients order by id ASC");
							$rows = $query->result();
							foreach($rows as $row)
							{
							?>
							<option value="<?php echo $row->id; ?>"><?php echo $row->job_number; ?></option>
							<?php
							}
							?>
						</select>
						<div id="fuzzSearch">
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
                  		<input required="" class="form-control" type="text" name="legal_description" id="legal_description" value="" />
                  	</div>
                </div>

				<div class="col-xs-12 col-sm-12 col-md-12">
      				Address
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
	
				<div class="col-xs-12 col-sm-6 col-md-6">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="first">Next</button>
				</div>
			</div>
				
		</div>
	</div>

	<div class="second" style="display:none;">
		<div class="modal-header">
			<h3 id="myModalLabel">Create Schedule<span style="float:right;">Zone Details</span></h3>
		</div>
	
		<div class="modal-body">	
		    <div class="row">					
				<div class="col-xs-12 col-sm-12 col-md-12">
	      			<div class="form-group">
	      				<label for="corrosion_zone">Corrosion Zone:</label>
	      				<input class="form-control" type="text" name="corrosion_zone" id="corrosion_zone" value="" />
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-12 col-md-12">
	      			<div class="form-group">
	      				<label for="wind_zone">Wind Zone:</label>
	      				<input class="form-control" type="text" name="wind_zone" id="wind_zone" value="" />
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-3 col-md-3">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="prev_second">Prev</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">  
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="second">Next</button>
				</div>
			</div>
		</div>
	</div>

	<div class="third" style="display:none;">
		<div class="modal-header">
			<h3 id="myModalLabel">Create Schedule<span style="float:right;">Products/Warranties Details</span></h3>
		</div>
	
		<div class="modal-body">	
		    <div class="row">					
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="form-group">
						<label for="template_id">Template:</label>
						<select class="form-control" name="template_id" id="template_id">
							<option value="">--Select Template--</option>
							<?php
							$query = $this->db->query("SELECT * FROM template order by template_name ASC");
							$rows = $query->result();
							foreach($rows as $row)
							{
							?>
							<option value="<?php echo $row->id; ?>"><?php echo $row->template_name; ?></option>
							<?php
							}
							?>
						</select>
					</div>
				</div>
	
				<div class="col-xs-12 col-sm-12 col-md-12">
	      			<div class="form-group">
	      				<label for="note">Additional Product(s):</label>
						<select name="product_id[]" multiple="multiple" placeholder="--Select Product(s)--" class="form-control SlectBox">
	      				<option value="">--Select Product(s)--</option>
						<?php
						$query = $this->db->query("SELECT * FROM product");
						$rows = $query->result();
						foreach($rows as $row)
						{
						?>
						<option value="<?php echo $row->id; ?>"><?php echo $row->product_name; ?></option>
						
						<?php
						}
						?>
						</select>
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-3 col-md-3">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="prev_third">Prev</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">  
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="third">Next</button>
				</div>
			</div>
		</div>
	</div>

	<div class="fourth" style="display:none;">
		<div class="modal-header">
			<h3 id="myModalLabel">Create Schedule<span style="float:right;">Architect/Builder Details</span></h3>
		</div>
	
		<div class="modal-body">	
		    <div class="row">					
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="designer_company_name">Company Name:</label>
                  		<input disabled value="Horncastle Homes" class="form-control" type="text" name="designer_company_name" id="designer_company_name" />
                  	</div>
                </div>	

				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="designer_phone_number">Phone:</label>
                  		<input class="form-control" type="text" name="designer_phone_number" id="designer_phone_number" />
                  	</div>
                </div>
	
				<div class="col-xs-12 col-sm-12 col-md-12">
	      			<div class="form-group">
	      				<label for="designer_email_address">Email:</label>
	      				<input disabled value="info@horncastle.co.nz" class="form-control" type="text" name="designer_email_address" id="designer_email_address" />
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-3 col-md-3">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="prev_fourth">Prev</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="fourth">Next</button>
				</div>
			</div>
				
		</div>
	</div>

	<div class="five" style="display:none;">
		<div class="modal-header">
			<h3 id="myModalLabel">Create Schedule<span style="float:right;">Others Details</span></h3>
		</div>
	
		<div class="modal-body">	
		    <div class="row">					
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="code_compliance_certificate">Code of Compliance Certificate:</label>
                  		<select name="code_compliance_certificate" id="code_compliance_certificate" class="form-control">
							<option value="">--Select--</option>
							<option value="Required">Required</option>
							<option value="Issued">Issued</option>
						</select>
                  	</div>
                 </div>
                 <div class="col-xs-12 col-sm-12 col-md-12" id="date_issued" style="display:none">
                  	<div class="form-group">
                  		<label for="date_issued">Date Issued:</label>
                  		<input class="form-control live_datepicker" type="text" name="date_issued" id="" value="" />
                  	</div>
                 </div>
	
				<div class="col-xs-12 col-sm-3 col-md-3">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn create width100" id="prev_five">Prev</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<input class="btn create width100" type="submit" name="submit" value="Create" />
				</div>
			</div>
				
		</div>
	</div>

</form>
</div>


<script>
	jQuery(document).ready(function() {	
		$('#first').click(function(){
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "block");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "none");
		});
		
		$('#second').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "block");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "none");
		});

		$('#third').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "block");
			$('.in .five').css("display", "none");
		});

		$('#fourth').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "block");
		});
		
		$('#prev_second').click(function(){
			
			$('.in .first').css("display", "block");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "none");
		});

		$('#prev_third').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "block");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "none");
		});

		$('#prev_fourth').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "block");
			$('.in .fourth').css("display", "none");
			$('.in .five').css("display", "none");
		});

		$('#prev_five').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "block");
			$('.in .five').css("display", "none");
		});

	});

$(function() {
   $('#fuzzOptionsList').fuzzyDropdown({
      mainContainer: '#fuzzSearch',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });

  
})

$(document).ready(function () {
	$('.SlectBox').SumoSelect();
});
	
</script>
<script>
	jQuery(document).ready(function(){

		$('.modal form').ajaxForm({
			success:function() {
				newurl = window.BaseUrl + 'schedule/schedule_list';
				window.location = newurl;	  
			},			
			beforeSubmit:function(){
				var overlay = jQuery('<div id="overlay"> </div>');
				overlay.appendTo(document.body);
			}
		});
	});
</script>