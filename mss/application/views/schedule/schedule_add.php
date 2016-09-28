<script>
	
	jQuery(document).ready(function() {
		
		$( "#template_id" ).change(function(){
			var template_id = $(this).val();
			$.ajax({				
				url: window.BaseUrl + 'schedule/product_load/' + template_id,
				type: 'POST',
				success: function(html) 
				{
					$('.pruduct-schedule tbody').empty();
					$('.pruduct-schedule tbody').append(html);
				},
			        
			});
		});
		
		$( "#product-add-template .close" ).click(function(){
			$('#product-add-template').css('display', 'none');
		});
		
		$( "#display-new-product" ).click(function(){
			$('#product-add-template').css('display', 'block');
			$('#product-existing-template').css('display', 'none');
		});
		
		$( "#product-existing-template .close" ).click(function(){
			$('#product-existing-template').css('display', 'none');
		});
		
		$( "#display-existing-product" ).click(function(){
			$('#product-existing-template').css('display', 'block');
			$('#product-add-template').css('display', 'none');
		});
		
	});	
	
</script>


<script>
	
// draggable function
jQuery(document).ready(function() {	

		$( ".product" ).draggable({
			
			helper: "clone",
			revert: "invalid"
		});
		

		$('.product_table_drop').droppable({
			drop: function( event, ui ) 
			{
				if (ui.draggable.is(".product")) 
				{
					var itemId = $(ui.draggable).attr("id");
					//alert($('#product_table tr').hasClass(itemId));
					//id = $('#product_table ' + itemId).val();
					if(!$('#product_table tr').hasClass(itemId))
					{
						$.ajax({				
							url: window.BaseUrl + 'schedule/product_load_drag/' + itemId,
							type: 'POST',
							success: function(html) 
							{
								$('#product_table tbody').append(html);

							},
						        
						});
					}
					else
					{
						alert('Product already added');
					}
				}
				
			}
		});
		
		$( "#product_table tbody" ).sortable();
	
});
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
		<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="content-body schedule-add">
		<form method="POST" action="<?php echo base_url(); ?>schedule/schedule_add">
			<div class="schedule-tab">
				<div class="inner">

	                <!-- Tabs Navigation Start -->
	                <div class="row">
						<div class="col-xs-12 col-sm-12 col-md-12">
		                <ul class="nav nav-tabs">
		                  <li class="col-xs-12 col-sm-12 col-md-2 active"><a href="#schedule" data-toggle="tab">Schedule Details</a></li>
		                  <li class="col-xs-12 col-sm-12 col-md-3"><a href="#designer" data-toggle="tab">Architect/Designer Details</a></li>
		                  <li class="col-xs-12 col-sm-12 col-md-2"><a href="#duilder" data-toggle="tab">Builder Details</a></li>
		                  <li class="col-xs-12 col-sm-12 col-md-2"><a href="#other" data-toggle="tab">Other Details</a></li>
		                </ul>
						</div>
	                </div>
	                <!-- Tabs Navigation End -->

	                <!-- Tab panes -->
	                <div class="tab-content">
	                  <div class="tab-pane active" id="schedule">
	                  
	                  	<div class="row">
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="schedule_name">Schedule Name</label>
	                  				<input required="" class="form-control" type="text" name="schedule_name" id="schedule_name" value="" />
	                  			</div>
	                  		</div>
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="client_id">Client Name</label>
	                  				<select required="" name="client_id" class="form-control" id="fuzzOptionsList">
										<option value="">Select Client</option>
										<?php
										$query = $this->db->query("SELECT * FROM clients order by client_name ASC");
										$rows = $query->result();
										foreach($rows as $row)
										{
										?>
										<option value="<?php echo $row->id; ?>"><?php echo $row->client_name; ?></option>
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
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="legal_description">Legal Description</label>
	                  				<input required="" class="form-control" type="text" name="legal_description" id="legal_description" value="" />
	                  			</div>
	                  		</div>
	                  	</div>
	                  	<div class="row">
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="property_address">Property Address</label>
	                  				<input required="" class="form-control" type="text" name="property_address" id="property_address" value="" />
	                  			</div>
	                  		</div>
	                  	</div>
	                  	
	                  </div>

	                  <div class="tab-pane" id="designer">
	                  	<div class="row">
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="designer_phone_number">Phone Number</label>
	                  				<input class="form-control" type="text" name="designer_phone_number" id="designer_phone_number" value="" />
	                  			</div>
	                  		</div>
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="designer_email_address">Email Address</label>
	                  				<input class="form-control" type="text" name="designer_email_address" id="designer_email_address" value="" />
	                  			</div>
	                  		</div>
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="designer_website">Website</label>
	                  				<input class="form-control" type="text" name="designer_website" id="designer_website" value="" />
	                  			</div>
	                  		</div>
	                  	</div>
	                  </div>

	                  <div class="tab-pane" id="duilder">
	                  	<div class="row">
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="duilder_name">Name</label>
	                  				<input class="form-control" type="text" name="duilder_name" id="duilder_name" value="" />
	                  			</div>
	                  		</div>
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="duilder_address">Address</label>
	                  				<input class="form-control" type="text" name="duilder_address" id="duilder_address" value="" />
	                  			</div>
	                  		</div>
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="duilder_phone_number">Phone Number</label>
	                  				<input class="form-control" type="text" name="duilder_phone_number" id="duilder_phone_number" value="" />
	                  			</div>
	                  		</div>
	                  	</div>
	                  	<div class="row">
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="duilder_email_address">Email Address</label>
	                  				<input class="form-control" type="text" name="duilder_email_address" id="duilder_email_address" value="" />
	                  			</div>
	                  		</div>
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="duilder_website">Website</label>
	                  				<input class="form-control" type="text" name="duilder_website" id="duilder_website" value="" />
	                  			</div>
	                  		</div>
	                  	</div>
	                  </div>

	                  <div class="tab-pane" id="other">
	                  	<div class="row">
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="licenced_building_practitioner">Licenced Building Practitioner</label>
	                  				<input class="form-control" type="text" name="licenced_building_practitioner" id="licenced_building_practitioner" value="" />
	                  			</div>
	                  		</div>
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="licence_class">Licence Class</label>
	                  				<input class="form-control" type="text" name="licence_class" id="licence_class" value="" />
	                  			</div>
	                  		</div>
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="licence_number">Licence Number</label>
	                  				<input class="form-control" type="text" name="licence_number" id="licence_number" value="" />
	                  			</div>
	                  		</div>
	                  	</div>
	                  	<div class="row">
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="licenced_building_practitioner1">Licenced Building Practitioner</label>
	                  				<input class="form-control" type="text" name="licenced_building_practitioner1" id="licenced_building_practitioner1" value="" />
	                  			</div>
	                  		</div>
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="licence_class1">Licence Class</label>
	                  				<input class="form-control" type="text" name="licence_class1" id="licence_class1" value="" />
	                  			</div>
	                  		</div>
	                  		<div class="col-xs-12 col-sm-12 col-md-4">
	                  			<div class="form-group">
	                  				<label for="licence_number1">Licence Number</label>
	                  				<input class="form-control" type="text" name="licence_number1" id="licence_number1" value="" />
	                  			</div>
	                  		</div>
	                  	</div>
	                  </div>
	                </div>
					<!-- Tab panes end -->

	              </div>
			</div>
			
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="border"></div>
				</div>
			</div>
			
			<div class="row load-template">
				<div class="col-xs-12 col-sm-12 col-md-4 template">
					<div class="form-group">
						<label for="template_id">Load a Template</label>
						<select class="form-control" name="template_id" id="template_id">
							<option value="0">Select Template</option>
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
				<div class="col-xs-12 col-sm-12 col-md-4">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-4 new-exit-button">
					<a id="display-existing-product" class="form-submit btn btn-info" href="#">Existing Product</a>
					<a id="display-new-product" class="form-submit btn btn-info" href="#">New Product</a>
				</div>
			</div>
			
			<div class="row pruduct-schedule">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<div class="table-responsive product_table_drop">
						<table id="product_table" class="table">
							<thead>
								<tr>
									<th class="res-hidden"></th>
									<th>Product</th>
									<th>Product Type</th>
									<th>Warranty Period</th>
									<th class="res-hidden">Maintenance Period</th>
									<th class="res-hidden">Description</th>
									<th class="res-hidden">Document</th>
								</tr>
							</thead>
							<tbody>
								
							</tbody>
						</table>
					</div>
				</div>
			</div>
			
			<div class="row submit-button">
				<div class="col-xs-12 col-sm-12 col-md-10">
				</div>
				<div class="col-xs-12 col-sm-12 col-md-2 submit">
					<input class="form-submit btn btn-info" type="submit" name="submit" value="Create Schedule" />
				</div>
			</div>
			
		</form>
		</div>
		</div>
	</div>
	
</div>

<script>
	
	jQuery(document).ready(function() {
		
		$( "#product-add-ajax" ).click(function(){

			var product_name = $("#product_name").val();
			var product_type_id = $("#product_type_id").val();
			var product_warranty_year = $("#product_warranty_year").val();
			var product_maintenance_period = $("#product_maintenance_period").val();
			var description = $("#description").val();
			var file_id = $("#file_id").val();

			
			if(product_name!='' && product_type_id!='' && product_warranty_year!='' && product_maintenance_period!='')
			{
				$.ajax({				
					url: window.BaseUrl + 'schedule/template_product_add?product_name=' + product_name + '&product_type_id=' + product_type_id + '&product_warranty_year=' + product_warranty_year + '&product_maintenance_period=' + product_maintenance_period + '&description=' + description + '&file_id=' + file_id,
					type: 'POST',
					success: function(html) 
					{
						$('.pruduct-schedule tbody').append(html);
						$('.error').empty();
						//$('.error').append('<span class="success-font-color">Product has been add successfully</span>');
						$("#product_name").val('');
						$("#product_type_id").val('');
						$("#product_warranty_year").val('');
						$("#product_maintenance_period").val('');
						$("#description").val('');
						$("#file_id").val('');
						$('#product-add-template').css('display', 'none');
						
					},
				        
				});
			}
			else
			{

				if(product_name=='')
				{
					$('.error').empty();
					$('.error').append('<span class="error-font-color">Please fill product name field</span>');
				}
				else if(product_type_id=='')
				{
					$('.error').empty();
					$('.error').append('<span class="error-font-color">Please fill product type field</span>');
				}
				else if(product_warranty_year=='')
				{
					$('.error').empty();
					$('.error').append('<span class="error-font-color">Please fill warranty period field</span>');
				}
				else if(product_maintenance_period=='')
				{
					$('.error').empty();
					$('.error').append('<span class="error-font-color">Please fill maintenance period field</span>');
				}
			}

		});
		
		
		
		
		
	});	
	
</script>

<div id="product-add-template" class="hover-product" style="display: none;">
	<form id="product-add-form" method="POST" action="#" enctype="multipart/form-data">
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 modal-header">
				<button type="button" class="close" aria-hidden="true">X</button>
				<h3 id="myModalLabel">New Product</h3>
				<div class="error"></div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-12"> 
				<div class="form-group">
					<label for="product_name">Product Name</label>
					<input required="" type="text" required="" class="form-control" value="" id="product_name" name="product_name" />		
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12"> 
				<div class="form-group">
					<label for="product_type_id">Product Type</label>
					<select required="" class="form-control" name="product_type_id" id="product_type_id">
						<option value="">Select Produce Type</option>
						<?php
						$query = $this->db->query("SELECT * FROM product_type");
						$rows = $query->result();
						foreach($rows as $row)
						{
						?>
						<option value="<?php echo $row->id; ?>"><?php echo $row->product_type_name; ?></option>
						<?php
						}
						?>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12"> 
				<div class="form-group">
					<label for="product_warranty_year">Warranty Period</label>
					<select required="" class="form-control" id="product_warranty_year" name="product_warranty_year">
						<option value="">Select Warranty Period</option>
						<option value="1">1 Years</option>
						<option value="2">2 Years</option>
						<option value="3">3 Years</option>
						<option value="4">4 Years</option>
						<option value="5">5 Years</option>
						<option value="6">6 Years</option>
						<option value="7">7 Years</option>
						<option value="8">8 Years</option>
						<option value="9">9 Years</option>
						<option value="10">10 Years</option>
						<option value="11">11 Years</option>
						<option value="12">12 Years</option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12"> 
				<div class="form-group">
					<label for="product_maintenance_period">Maintenance Period</label>
					<select required="" class="form-control" id="product_maintenance_period" name="product_maintenance_period">
						<option value="">Select Maintenance Period</option>
						<option value="1">1 Years</option>
						<option value="2">2 Years</option>
						<option value="3">3 Years</option>
						<option value="4">4 Years</option>
						<option value="5">5 Years</option>
						<option value="6">6 Years</option>
						<option value="7">7 Years</option>
						<option value="8">8 Years</option>
						<option value="9">9 Years</option>
						<option value="10">10 Years</option>
						<option value="11">11 Years</option>
						<option value="12">12 Years</option>
					</select>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12"> 
				<div class="form-group">
					<label for="description">Description</label>
					<textarea name="description" id="description" class="form-control"></textarea>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12"> 
				<div class="form-group">
					<label for="description">Attach Document</label>
					<div id="files"></div>
					<div class="fileinputs">
						<input type="file" name="userfile" id="userfile" class="file form-control" size="20" />
						<input type="hidden" name="file_id" id="file_id" value="" />
						<div class="fakefile">
							<input class="form-control"/>
							<img src="<?php echo base_url(); ?>images/input_file.png" />
						</div>
					</div>
						
						
						
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-12"> 
				<div class="form-group">
					<label for="description"></label>
					
					<input id="product-add-ajax" type="button" name="submit" class="form-submit btn btn-info" value="Add Product" />
				</div>
			</div>
		</div>
    </form>
</div>

<script>
	jQuery(document).ready(function() {
		
		$( "#search_product" ).keyup(function(){
			var search_product = $(this).val();
			$.ajax({				
				url: window.BaseUrl + 'schedule/ajax_existing_product_load?search_product=' + search_product,
				type: 'POST',
				success: function(html) 
				{
					$('.form-group .product_load').empty();
					$('.form-group .product_load').append(html);
				},
			        
			});
		});
		
	});	
</script>

<div id="product-existing-template" class="hover-product" style="display: none;">
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 modal-header">
			<button type="button" class="close" aria-hidden="true">X</button>
			<h3 id="myModalLabel">Existing Product</h3>
		</div>

		<div class="col-xs-12 col-sm-12 col-md-12"> 
			<div class="form-group">
				<label for="product_name">Product List</label>	
				<input id="search_product" class="form-control" type="text" value="" name="search_product">
				<div class="product_load">
				<ul>
					<?php
					$query_p_t = $this->db->query("SELECT * FROM product_type");
					$rows_p_t = $query_p_t->result();
					foreach($rows_p_t as $row_p_t)
					{
					?>
					<li>
						<?php $type_id = $row_p_t->id; echo '<p>'.$row_p_t->product_type_name.'</p>'; ?>
						<ul id="draggable">
						<?php
						$query_p = $this->db->query("SELECT * FROM product where product_type_id=$type_id");
						$rows_p = $query_p->result();
						foreach($rows_p as $row_p)
						{
						?>
						<li id="<?php echo $row_p->id; ?>" class="product"><?php echo '<img src="'.base_url().'images/drag_drop.png" />'.$row_p->product_name; ?></li>
						
						<?php
						}
						?>
						</ul>
					</li>
					<?php
					}
					?>
				</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<script>


$(function() {
   $('#fuzzOptionsList').fuzzyDropdown({
      mainContainer: '#fuzzSearch',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });

  
})
</script>

