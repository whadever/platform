<script>
	
	jQuery(document).ready(function() {
		
		
		$( "#product-existing-template .close" ).click(function(){
			$('#product-existing-template').css('display', 'none');
		});
		
		$( "#display-existing-product" ).click(function(){
			$('#product-existing-template').css('display', 'block');
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
		

		$('#product_table').droppable({
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
                $("#product_table tbody").sortable();
	
});
</script>
<style>
   .new-exit-button a {
    float: right;
    margin-bottom: 15px;
    margin-left: 10px;
}

</style>
<div class="content">
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="content-header">
				<div class="title"><?php echo $title; ?></div>
			</div>
		</div>
	</div>
	
    <div class="content-body template-add">
    <form method="POST" action="<?php echo base_url();?>template/template_update/<?php echo $template->id; ?>">





                    <div class="row tamplate">

                                    <div class="col-xs-12 col-sm-4 col-md-4">
                                        <div class="form-group">
                                            <label for="template_name">Template Name</label>
                                            <input type="text" name="template_name" id="template_name" value="<?php echo $template->template_name; ?>" />
                                        </div>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-4">
                                    </div>
                                    <div class="col-xs-12 col-sm-4 col-md-4 new-exit-button">
                                            <a id="display-existing-product" class="form-submit btn btn-info" href="#">Add Product</a>
                                            
                                    </div>          		



                    </div>
        
                        
        
       



       <div class="row pruduct-schedule">
			<div class="col-xs-12 col-sm-12 col-md-12">
                <div class="table-responsive">
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
							<?php
							$template_id = $template->id;
							
							$this->db->select('template_product.*');
							$this->db->where('template_id',$template_id);
							
							$this->db->order_by('ordering','ASC');
							$template_products = $this->db->get('template_product')->result();
							//print($templates);
							foreach($template_products as $template_product)
							{
								$product_id = $template_product->product_id;
								$pro_query = $this->db->query("SELECT * FROM product LEFT JOIN product_type ON product.product_type_id=product_type.id LEFT JOIN file ON product.file_id=file.id where product.id=$product_id");
								$products = $pro_query->result();
								//$products = $this->db->get($this->table_product)->result();
								$ppp = '';		
								foreach($products as $product)
								{	
									$ppp .= '<tr id="'.$product_id.'" class="'.$product_id.'">';
									$ppp .= '<td class="res-hidden"><img src="'.base_url().'images/drag_drop.png" /><input type="hidden" name="product_id[]" value="'.$product_id.'"></td>';
									$ppp .= '<td>'.$product->product_name.'</td>';
									$ppp .= '<td>'.$product->product_type_name.'</td>';
									$ppp .= '<td>'.$product->product_warranty_year.' Year<br>'.$product->product_warranty_month.' Month</td>';
									$ppp .= '<td class="res-hidden">'.$product->product_maintenance_year.' Year<br>'.$product->product_maintenance_month.' Month</td>';
									$ppp .= '<td class="res-hidden">'.$product->description_of_maintenance.'</td>';
									if($product->file_id != '0')
									{
										$ppp .= '<td class="res-hidden"><img src="'.base_url().'images/output_file.png" /></td>';
									}
									else
									{
										$ppp .= '<td class="res-hidden">No<br>Document</td>';
									}
									$ppp .= '</tr>';
								}
								echo $ppp;
							
							}
							
							?>
						</tbody>
                        </table>
                </div>
			</div>
        </div>
			
        <div class="row submit-button">
			<div class="col-xs-12 col-sm-12 col-md-10">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-2">
                <input type="submit" name="submit" class="btn btn-info" value="Edit Template" />
			</div>
        </div>

    </form>

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
	
</div>
</div>