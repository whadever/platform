<script>
	
	function Remove(id){
		
		var b = window.confirm('Are you sure, you want to Delete This ?');
		if(b==true)
		{
			$.ajax({				
				url: window.BaseUrl + 'product/product_type_delete/' + id,
				type: 'POST',
				success: function(html) 
				{
					//console.log(data);
					newurl = window.BaseUrl + 'product/product_type';
					window.location = newurl;
				},
			        
			});
		}
	}	
	
</script>
<div class="page-title">
	<div class="row">
		<div class="col-xs-2 col-sm-2 col-md-1">
			<img width="" height="65" src="<?php echo base_url(); ?>/images/mss_prod_warr.png"  title="Manage Product" alt=""/>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-7">
			<h4>Product Type</h4>
			<p>Add a new product type for your product.</p>
		</div>
		
	</div>
</div>

<div class="content product-type">
	
	<div class="row">
		<div class="content-header">
			<div class="col-xs-6 col-sm-9 col-md-9">		
				<div class="title"><?php echo $title; ?></div>
			</div>

			<div class="col-xs-6 col-sm-3 col-md-3">		
				<a data-toggle="modal" class="form-submit btn btn-info new-button" href="#AddProductType"><img class="plus-icon" src="<?php echo base_url(); ?>images/plus_icon.png" />Add Product Type</a>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th width="80%">Type Name</th>
							<th>Edit</th>
							<th>Remove</th>
						</tr>
					</thead>
					<tbody>
					<?php
					foreach($product_type as $row)
					{
					?>
						<tr>
							<td><?php echo $row->product_type_name; ?></td>
							<td><a data-toggle="modal" href="#EditProductType_<?php echo $row->id; ?>">Edit</a></td>
							<td><a onclick="Remove('<?php echo $row->id; ?>');" href="#">Remove</a></td>
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

<!-- MODAL Edit Product/Warranties -->
<?php
foreach($product_type as $row)
{
?>
<div id="EditProductType_<?php echo $row->id;  ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form accept-charset="utf-8" method="post" action="<?php echo base_url(); ?>product/produt_type_update/<?php echo $row->id;  ?>">	
		<div class="first" style="display:block;">
			<div class="modal-header">
				<h3 id="myModalLabel">Edit Product Type</h3>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
		                <div class="form-group">
		                  	<label for="product_type_name">Product Type Name:*</label>
		                  	<input required="" class="form-control" type="text" name="product_type_name" id="product_type_name" value="<?php echo $row->product_type_name;  ?>" />
		                </div>
					</div>
	            </div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						
					</div>
					<div class="col-xs-6 col-sm-3 col-md-3">
						<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
					</div>
					<div class="col-xs-6 col-sm-3 col-md-3">
						<input class="btn create width100" type="submit" name="submit" value="Create" />
					</div>
				</div>	
			</div>
		</div>		
	</form>
</div>
<?php
}
?>

<!-- MODAL Create Product/Warranties -->
<div id="AddProductType" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form accept-charset="utf-8" method="post" action="<?php echo base_url(); ?>product/produt_type_add">	
		<div class="first" style="display:block;">
			<div class="modal-header">
				<h3 id="myModalLabel">Add Product Type</h3>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
		                <div class="form-group">
		                  	<label for="product_type_name">Product Type Name:*</label>
		                  	<input required="" class="form-control" type="text" name="product_type_name" id="product_type_name" value="" />
		                </div>
					</div>
	            </div>
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
						
					</div>
					<div class="col-xs-6 col-sm-3 col-md-3">
						<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
					</div>
					<div class="col-xs-6 col-sm-3 col-md-3">
						<input class="btn create width100" type="submit" name="submit" value="Create" />
					</div>
				</div>	
			</div>
		</div>		
	</form>
</div>