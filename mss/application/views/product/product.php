
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
			url: window.BaseUrl + 'product/clear_search',
			type: 'POST',
			success: function(html) 
			{
				//console.log(data);
				newurl = window.BaseUrl + 'product/product_list';
				window.location = newurl;
			},
		        
		});
    });
               
 });
 
</script>

<div class="content">

<?php
$ci = & get_instance();
$ci->load->model('product_model');

$product_search = $this->session->userdata('product_search');
$product_type = $this->session->userdata('product_type');
$product_specifications = $this->session->userdata('product_specifications');
?>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 searchbox">
	    <div class="clickdiv" id="search-header">
	        <strong> <span> Search </span> 
	        <span id="plus" style="<?php if($this->session->userdata('product_search') || $this->session->userdata('product_type') || $product_specifications){ echo 'display:none;'; } ?>">+</span><span id="minus" style="<?php if($this->session->userdata('product_search') || $this->session->userdata('product_type') || $product_specifications){ echo 'display:inline;'; }else{ echo 'display:none;'; } ?>">-</span></strong>
	    </div> 
	    <div class="hiders" style="<?php if($this->session->userdata('product_search') || $this->session->userdata('product_type') || $product_specifications){ echo 'display:block;'; }else{ echo 'display:none;'; } ?>">
			<div class="row">
				<form action="<?php echo base_url(); ?>product/product_list" method="post">
			        <div class="col-xs-12 col-sm-3 col-md-3">
		                <label for="product_search">Search</label>
						<input type="text" class="form-control" id="product_search" value="<?php if(!empty($product_search)){ echo $product_search; } ?>" name="product_search">
		            </div>
					<div class="col-xs-12 col-sm-3 col-md-3">
		                <label for="product_type">Product Type</label>
						<?php
						$type_option = $ci->product_model->get_product_type();
						$type_options = array('' => '-- Select Product Type --') + $type_option;
						$type_default = isset($product_type) ? $product_type : '';
						$type_js = 'class="form-control"';
						$product_type = form_dropdown('product_type', $type_options, $type_default, $type_js);  
						echo $product_type; 
						?>
		            </div>
					<div class="col-xs-12 col-sm-2 col-md-2">
		                <label for="product_search">Masterspec Code</label>
						<input type="text" class="form-control" id="product_specifications" value="<?php if(!empty($product_specifications)){ echo $product_specifications; } ?>" name="product_specifications">
		            </div>
					<div class="col-xs-6 col-sm-2 col-md-2">
						<label for="company_name">&nbsp;</label>
						<input type="submit" class="form-control" id="submit" value="Search" name="submit">
		            </div>
					<div class="col-xs-6 col-sm-2 col-md-2">
						<label for="company_name">&nbsp;</label>
						<input type="button" class="form-control" id="clear_search" value="Clear Search">
		            </div>
					
				</form>
			</div>
	    </div>
	</div>
</div>

<div class="row">
	<div class="content-header">
		<div class="col-xs-12 col-sm-4 col-md-7">		
			<div class="title"><?php echo $title; ?></div>
		</div>
		<div class="col-xs-5 col-sm-3 col-md-2">		
			<a class="form-submit btn btn-info new-button" href="<?php echo base_url(); ?>product/product_type">Product Types</a>
		</div>
		<div class="col-xs-7 col-sm-5 col-md-3">		
			<a data-toggle="modal" class="form-submit btn btn-info new-button" href="#AddProduct"><img class="plus-icon" src="<?php echo base_url(); ?>images/plus_icon.png" />Add Product/Warranties</a>
		</div>
	</div>
</div>

<div class="content-body">
<div class="table-responsive">

	<table class="table">
		<thead>
			<tr>
				<th style="width: 17%;">Product Name</th>
				<th style="width: 5%;">Product Type</th>
				<th style="width: 5%;" class="res-hidden">Product Warranty</th>
				<th style="width: 30%;" class="res-hidden">Maintenance Period</th>
				<th style="width: 20%;" class="res-hidden">Document</th>
				<th style="width: 15%;" class="res-hidden">Manufacturers Info</th>
				<th style="width: 5%;" class="res-hidden">Masterspec Code</th>
				<th style="width: 3%;">Edit</th>
				<th style="width: 5%;">Remove</th>
			</tr>
		</thead>

		<tbody>
		<?php 	
		
		foreach($product_list as $product){

		$maintenance= array();
		$file1 = $product->file1==''? '': '<img style="margin-right:8px;width:16px;" onClick="deleteDocument('.$product->id.',1,'.$product->product_document_id.',`'.$product->file1.'`)" src="'.base_url().'images/doc_delete.png"><a target="_bank" href="'.base_url().'uploads/document/'.$product->file1.'">'.$product->file1.'</a>';
		$file2 = $product->file2==''? '': '<br><img style="margin-right:8px;width:16px;" onClick="deleteDocument('.$product->id.',2,'.$product->product_document_id_1.',`'.$product->file2.'`)" src="'.base_url().'images/doc_delete.png"><a target="_bank" href="'.base_url().'uploads/document/'.$product->file2.'">'.$product->file2.'</a>';
		$file3 = $product->file3==''? '': '<br><img style="margin-right:8px;width:16px;" onClick="deleteDocument('.$product->id.',3,'.$product->product_document_id_2.',`'.$product->file3.'`)" src="'.base_url().'images/doc_delete.png"><a target="_bank" href="'.base_url().'uploads/document/'.$product->file3.'">'.$product->file3.'</a>';
		
		$paint = $product->filepaint==''? '': '<br><img style="margin-right:8px;width:16px;" onClick="deleteDocument('.$product->id.',3,'.$product->product_document_paint.',`'.$product->filepaint.'`)" src="'.base_url().'images/doc_delete.png"><a target="_bank" href="'.base_url().'uploads/document/'.$product->filepaint.'">'.$product->filepaint.'</a>';
		
		$product_warranty_year = $product->product_warranty_year=='0'? '': $product->product_warranty_year.' Years';
		$product_warranty_month = $product->product_warranty_month=='0'? '': $product->product_warranty_month.' Months';
		
		if($product->change_color=='1'){
			$color = 'color:red;';
		}else{
			$color = '';
		}
		
		$product_maintenance_period = $ci->product_model->get_product_maintenance_period($product->id)->result();
		foreach($product_maintenance_period as $maintenance_period){
			if($maintenance_period->product_maintenance_year=='0'){
				$product_maintenance_year = '';
			}else{
				$product_maintenance_year = $maintenance_period->product_maintenance_year.' Year(s)' ;
			}
			if($maintenance_period->product_maintenance_month=='0'){
				$product_maintenance_month = '';
			}else{
				$product_maintenance_month = $maintenance_period->product_maintenance_month.' Month(s)';
			}
			if($maintenance_period->product_maintenance_week=='0'){
				$product_maintenance_week = '';
			}else{
				$product_maintenance_week = $maintenance_period->product_maintenance_week.' Week(s)';
			}
			$maintenance[] = $product_maintenance_year.'&nbsp;'.$product_maintenance_month.'&nbsp;'.$product_maintenance_week;
		}
		//$product_maintenance_year = $product->product_maintenance_year=='0'? '': $product->product_maintenance_year.' Years';
		//$product_maintenance_month = $product->product_maintenance_month=='0'? '': $product->product_maintenance_month.' Months';
		?>
			<tr>
				<td><span style="<?php echo $color; ?>"><?php echo $product->product_name; ?></span></td>
				<td><?php echo $product->product_type_name; ?></td>
				<td class="res-hidden"><?php echo $product_warranty_year; ?><br><?php echo $product_warranty_month; ?></td>
				<td class="res-hidden"><?php echo  implode('<br />', $maintenance); //array_map('strval', $maintenance); ?></td>
				<td class="res-hidden"><?php if($file1=='' && $file2=='' && $paint==''){ echo 'No<br>Document'; }else{ echo $file1.''.$file2.' '.$paint; } ?></td>	
				<td class="res-hidden"><?php if($file3==''){ echo 'No<br>Document'; }else{ echo $file3; } ?></td>
				<td class="res-hidden"><?php echo $product->product_specifications; ?></td>
				<td><a data-toggle="modal" href="#EditProduct_<?php echo $product->id; ?>">Edit</a></td>
				<td><a data-toggle="modal" href="#DeleteProduct_<?php echo $product->id; ?>">Remove</a></td>																								
			</tr>
		<?php } ?>
		</tbody>
	</table>

</div>
</div>



<div id="delete-document-modal">
	<div class="modal-header" id="modal-header">
		
	</div>
	<div class="modal-body" id="modal-body">
		
	</div>
	<div class="modal-footer">
		<form action="<?php echo base_url();?>product/product_document_delete" method="post">
			<input type="hidden" id="file_1_2_3" name="file_1_2_3" value=""/>
			<input type="hidden" id="file_id" name="file_id" value=""/>
			<input type="hidden" id="product_id" name="product_id" value=""/>
			<input id="delete-document-no" class="btn" type="button" value="No"/>
			<input id="delete-document-yes" class="btn" type="submit" value="Yes"/>
		</form>		
		<div class="clear"></div>
	</div>	
</div>  

<script type="text/javascript">

	function deleteDocument(product_id,file_1_2_3,file_id,filename)
	{
		$('#modal-header').empty();
		$('#modal-body').empty();
		var filename = filename;
		$('#file_1_2_3').val(file_1_2_3);
		$('#file_id').val(file_id);
		$('#product_id').val(product_id);
		$('#modal-header').append('Delete '+filename+'?');
		$('#modal-body').append('<p>Are you sure you want to delete '+filename+'?<br>Note: You cannot undo this action.</p>');

		$("#delete-document-modal").dialog('open');
	}
    
	$(document).ready(function () {

		$("#delete-document-modal").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 200,
            modal: true
        });

		$("#delete-document-no").click(
            function () {
                $('#delete-document-modal').dialog( "close" );
            }
        );
	});

</script>

<style>
.ui-draggable .ui-dialog-titlebar {
    display: none;
}
.ui-dialog {
    padding: 0;
}
.ui-dialog .ui-dialog-content {
    padding: 0;
	color: #000;
}
#delete-document-modal .modal-header {
    font-size: 14px;
}
#delete-document-modal .modal-body {   
    font-size: 12px;
	text-align: center;
}
#delete-document-modal .modal-footer {
    text-align: right;
}
#delete-document-modal #delete-document-no {
    background: #d1d2d4;
    width: 75px;
}
#delete-document-modal #delete-document-yes {
    background: #a7a9ac;
    width: 75px;
}
</style>

<!-- MODAL Edit Product/Warranties -->
<?php 

foreach($product_list as $product){
?>
<div id="EditProduct_<?php echo $product->id; ?>" class="modal hide fade product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form accept-charset="utf-8" enctype="multipart/form-data" method="post" action="<?php echo base_url(); ?>product/product_update/<?php echo $product->id; ?>">
	<div class="first" style="display:block;">		
		<div class="modal-header">
			<h3 id="myModalLabel">Edit Product/Warranties</h3>
		</div>
		<div class="modal-body">
			
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
			
					<div class="form-group">
						<label for="product_name">Product Name:*</label>
						<input type="text" required="1" class="form-control" id="product_name" value="<?php echo $product->product_name; ?>" name="product_name">
					</div>  
			
					<div class="form-group">
						<label for="product_warranty_year">Warranty Period:*</label>
						<div class="row">
							<div class="col-xs-12 col-sm-6 col-md-6">
								<input value="<?php echo $product->product_warranty_year; ?>" type="text" required="1" class="form-control" placeholder="Years" id="product_warranty_year" name="product_warranty_year">
							</div>
							<div class="col-xs-12 col-sm-6 col-md-6">
								<input value="<?php echo $product->product_warranty_month; ?>" type="text" class="form-control" placeholder="Months" id="product_warranty_month" name="product_warranty_month">
							</div>
						</div>			
					</div>

					<div class="form-group">
						<label for="look_while_maintaining">Change Color:</label>
						<input <?php if($product->change_color=='1'){ echo 'checked'; } ?> style="width: 20px;" name="change_color" type="checkbox" value="1">
					</div>
				
				</div>
			
				<div class="col-xs-12 col-sm-6 col-md-6">
			
					<div class="form-group">
						<?php
						//$ci = & get_instance();
						//$ci->load->model('product_model'); 
						$type_option = $ci->product_model->get_product_type();
						$type_options = array('' => '-- Select Product Type --') + $type_option;
						$product_type = form_label('Product Type:*', 'product_type_id');
						$type_default = isset($product->product_type_id) ? $product->product_type_id : '';
						$type_js = 'id="project_id" class="form-control" required="true"';
						$product_type .= form_dropdown('product_type_id', $type_options, $type_default, $type_js);  
						echo $product_type;    
						?>
					</div>  

					<div class="form-group">
						<label for="product_specifications">Masterspec Code:</label>
						<input value="<?php echo $product->product_specifications; ?>" type="text" class="form-control" id="product_specifications" name="product_specifications">
					</div>
					<div class="form-group">
						<label for="upload_document">Document 1 (Warranty):</label>
						<input type="file" class="filestyle" name="upload_document" data-buttonText="BROWSE">
						<?php echo $product->file1; ?>
						<input value="<?php echo $product->product_document_id; ?>" type="hidden" name="product_file_id">
					</div>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label for="look_while_maintaining">What to look for while maintaining:</label>
						<textarea class="form-control" name="look_while_maintaining" cols="100" rows="1"><?php echo $product->look_while_maintaining; ?></textarea>
					</div>								
				</div>

				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label for="upload_document">Document 2 (Maintenance):</label>
						<input type="file" class="filestyle" name="upload_document_1" data-buttonText="BROWSE">
						<?php echo $product->file2; ?>
						<input value="<?php echo $product->product_document_id_1; ?>" type="hidden" name="product_file_id_1">
					</div>						
				</div>
			</div>
			
			<div class="row">
				
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label for="upload_document">Manufacturers Info <small>(Note: This will not be displayed in the final schedule):</small></label>
						<input type="file" class="filestyle" name="upload_document_2" data-buttonText="BROWSE">
						<?php echo $product->file3; ?>
						<input value="<?php echo $product->product_document_id_2; ?>" type="hidden" name="product_file_id_2">
					</div>								
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label for="upload_document">Document 3 (Paint):</label>
						<input type="file" class="filestyle" name="upload_document_paint" data-buttonText="BROWSE">
						<?php echo $product->filepaint; ?>
						<input value="<?php echo $product->product_document_paint; ?>" type="hidden" name="upload_document_paint_id">
					</div>
				</div>
			</div>

			<?php 
			$product_id = $product->id;
			$product_maintenance_period = $ci->product_model->get_product_maintenance_period($product_id)->result(); ?>
			<div id="maintenance_period_div_<?php echo $product->id;?>">
			<?php 
			foreach($product_maintenance_period as $maintenance_period){
			?>
			<div class="row">
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label for="product_maintenance_year">Maintenance Period:*</label>
						<div class="row">
							<div class="col-xs-12 col-sm-4 col-md-4">
								<input value="<?php echo $maintenance_period->product_maintenance_year; ?>" type="text" required="1" class="form-control" placeholder="Years" id="product_maintenance_year" name="product_maintenance_year[]">
							</div>
							<div class="col-xs-12 col-sm-4 col-md-4">
								<input value="<?php echo $maintenance_period->product_maintenance_month; ?>" type="text" class="form-control" placeholder="Months" id="product_maintenance_month" name="product_maintenance_month[]">
							</div>
							<div class="col-xs-12 col-sm-4 col-md-4">
								<input value="<?php echo $maintenance_period->product_maintenance_week; ?>" type="text" class="form-control" placeholder="Weeks" id="product_maintenance_week" name="product_maintenance_week[]">
							</div>
						</div>
					</div>
				</div>
				<div class="col-xs-12 col-sm-6 col-md-6">
					<div class="form-group">
						<label for="description_of_maintenance">How to maintain:</label>
						<textarea class="form-control" id="product_description_of_maintenance" name="description_of_maintenance[]" cols="100" rows="1"><?php echo $maintenance_period->how_to_maintain; ?></textarea>
					</div> 
				</div>
			</div>
			<?php } ?></div>
			
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<a id="btn_add_row_<?php echo $product_id; ?>" class="pull-right">Add Another Maintenance Period</a>
				</div>
			</div>
			
			<div class="row">
			
				<div class="col-xs-12 col-sm-3 col-md-3">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<input class="btn create width100" type="submit" name="submit" value="Save" />
				</div>
				
			</div>
			
		</div>
	</div>
	
</form>

</div>

<div id="DeleteProduct_<?php echo $product->id; ?>" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide fade edit_modal">
	<div class="modal-header">
		<h3 id="myModalLabel">Delete Product/Warranties</h3>
	</div>
	<div class="modal-body">
		<form accept-charset="utf-8" method="post" action="<?php echo base_url(); ?>product/product_delete/<?php echo $product->id; ?>">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<p>Are you sure to delete this Product?</p>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button aria-hidden="true" data-dismiss="modal" class="btn cancel width100" type="button">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<input type="submit" class="btn create" value="Delete">
				</div>
			</div>
		</form>
	</div>
</div>
<script>
$(document).ready(function(){
    
    $("#btn_add_row_<?php echo $product->id; ?>").click(function(){
        $("#maintenance_period_div_<?php echo $product->id; ?>").append('<div class="row maintenance_row"> <div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group"><label for="product_maintenance_year">Maintenance Period</label><div class="row"><div class="col-xs-12 col-sm-4 col-md-4"><input type="text"  class="form-control" placeholder="Years" id="product_maintenance_year" name="product_maintenance_year[]"></div><div class="col-xs-12 col-sm-4 col-md-4"><input type="text" class="form-control" placeholder="Months" id="product_maintenance_month" name="product_maintenance_month[]"></div><div class="col-xs-12 col-sm-4 col-md-4"><input value="" type="text" class="form-control" placeholder="Weeks" id="product_maintenance_week" name="product_maintenance_week[]"></div></div></div></div><div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group"><label for="description_of_maintenance">How to maintain</label><textarea class="form-control" style="width:90%; float:left;" id="product_description_of_maintenance" name="description_of_maintenance[]" cols="100" rows="1"></textarea><span style="float:right;" class="remove_this_row">X</span></div></div></div>');
    });
	$('#maintenance_period_div_<?php echo $product->id; ?>').on('click', '.remove_this_row', function(e) {
		e.preventDefault();
		$(this).parent().parent().parent().remove();
		//$('.maintenance_row').remove();
	});
});
</script>
<script>
	jQuery(document).ready(function() {	
		$('#first_<?php echo $product->id; ?>').click(function(){
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "block");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			
		});
		
		$('#second_<?php echo $product->id; ?>').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "block");
			$('.in .fourth').css("display", "none");
			
		});

		$('#third_<?php echo $product->id; ?>').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "block");
			
		});
		
		$('#prev_second_<?php echo $product->id; ?>').click(function(){
			
			$('.in .first').css("display", "block");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			
		});

		$('#prev_third_<?php echo $product->id; ?>').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "block");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			
		});
		
		$('#prev_fourth_<?php echo $product->id; ?>').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "block");
			$('.in .fourth').css("display", "none");
			
		});
	});

$(function() {
   $('#fuzzOptionsListEdit_<?php echo $product->id; ?>').fuzzyDropdown({
      mainContainer: '#fuzzSearch_<?php echo $product->id; ?>',
      arrowUpClass: 'fuzzArrowUp',
      selectedClass: 'selected',
      enableBrowserDefaultScroll: true
    });

  
})
</script>

<?php } ?>





<!-- MODAL Create Product/Warranties -->
<div id="AddProduct" class="modal hide fade product" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form accept-charset="utf-8" enctype="multipart/form-data" method="post" action="<?php echo base_url(); ?>product/product_add">
	
		<div class="first" style="display:block;">
			<div class="modal-header">
				<h3 id="myModalLabel">Create Product/Warranties</h3>
			</div>
			<div class="modal-body">
				
				<div class="row">
					<div class="col-xs-12 col-sm-6 col-md-6">
				
						<div class="form-group">
							<label for="product_name">Product Name:*</label>
							<input type="text" required="1" class="form-control" id="product_name" value="" name="product_name">
						</div>  

						<div class="form-group">
							<label for="product_warranty_year">Warranty Period:*</label>
							<div class="row">
								<div class="col-xs-12 col-sm-6 col-md-6">
									<input type="text" required="1" class="form-control" placeholder="Years" id="product_warranty_year" name="product_warranty_year">
								</div>
								<div class="col-xs-12 col-sm-6 col-md-6">
									<input type="text" class="form-control" placeholder="Months" id="product_warranty_month" name="product_warranty_month">
								</div>
							</div>			
						</div>
				
						<div class="form-group">
							<label for="look_while_maintaining">Change Color:</label>
							<input style="width: 20px;" name="change_color" type="checkbox" value="1">
						</div>
				
					</div>
				
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<?php
							$ci = & get_instance();
							$ci->load->model('product_model'); 
							$type_option = $ci->product_model->get_product_type();
							$type_options = array('' => '-- Select Product Type --') + $type_option;
							$product_type = form_label('Product Type:*', 'product_type_id');
							$type_default = '';
							$type_js = 'id="project_id" class="form-control" required="true"';
							$product_type .= form_dropdown('product_type_id', $type_options, $type_default, $type_js);  
							echo $product_type;    
							?>
						</div> 

						<div class="form-group">
							<label for="product_specifications">Masterspec Code:</label>
							<input type="text" class="form-control" id="product_specifications" name="product_specifications">
						</div> 
						<div class="form-group">
							<label for="upload_document">Document 1 (Warranty):</label>
							<input type="file" class="filestyle" name="upload_document" data-buttonText="BROWSE">
						</div> 					
					</div>
				</div>

				<div class="row">
				
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<label for="upload_document">Manufacturers Info <small>(Note: This will not be displayed in the final schedule):</small></label>
							<input type="file" class="filestyle" name="upload_document_2" data-buttonText="BROWSE">
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<label for="look_while_maintaining">What to look for while maintaining:</label>
							<textarea class="form-control" name="look_while_maintaining" cols="100" rows="1"></textarea>
						</div>
					</div>
				</div>
				
				<div class="row">
				
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<label for="upload_document">Document 2 (Maintenance):</label>
							<input type="file" class="filestyle" name="upload_document_1" data-buttonText="BROWSE">
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<label for="upload_document">Document 3 (Paint):</label>
							<input type="file" class="filestyle" name="upload_document_paint" data-buttonText="BROWSE">
						</div>
					</div>
				</div>

				<div id="maintenance_period_div" class="">
				<div class="row row_count">
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<label for="product_maintenance_year">Maintenance Period:*</label>
							<div class="row">
								<div class="col-xs-12 col-sm-4 col-md-4">
									<input type="text" required="1" class="form-control" placeholder="Years" id="product_maintenance_year" name="product_maintenance_year[]">
								</div>
								<div class="col-xs-12 col-sm-4 col-md-4" id="load-month">
									<div style="padding:6px 0;" id="click-month">+ Month</div><input type="hidden" class="form-control" placeholder="Months" id="product_maintenance_month" name="product_maintenance_month[]">
								</div>
								<div class="col-xs-12 col-sm-4 col-md-4" id="load-week">
									<div style="padding:6px 0;" id="click-week">+ Week</div><input type="hidden" class="form-control" placeholder="Weeks" id="product_maintenance_week" name="product_maintenance_week[]">
								</div>
							</div>
						</div>
					</div>
					<div class="col-xs-12 col-sm-6 col-md-6">
						<div class="form-group">
							<label for="description_of_maintenance">How to maintain:</label>
							<textarea class="form-control" name="description_of_maintenance[]" cols="100" rows="1"></textarea>
						</div>
					</div>
				</div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
						<a id="btn_add_row" class="pull-right">Add Another Maintenance Period</a>
					</div>
				</div>

				<div class="row">
					<div class="col-xs-12 col-sm-3 col-md-3">
					</div>
					<div class="col-xs-12 col-sm-3 col-md-3">
						
					</div>
					<div class="col-xs-12 col-sm-3 col-md-3"> 
						<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
					</div>
					<div class="col-xs-12 col-sm-3 col-md-3"> 
						<input type="hidden" id="total_count" value="1">
						<input type="submit" class="btn create width100" value="Create" name="submit">
					</div>
					
				</div>

			</div>
			
		</div>
		
	</form>

</div>


<script>
$(document).ready(function(){
	
    $("#btn_add_row").click(function(){
		numItems = $('.row_count').length;
        $("#maintenance_period_div").append('<div class="row row_count"><div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group"><label for="product_maintenance_year">Maintenance Period</label><div class="row"><div class="col-xs-12 col-sm-4 col-md-4"><input type="text" class="form-control" placeholder="Years" id="product_maintenance_year" name="product_maintenance_year[]"></div><div class="col-xs-12 col-sm-4 col-md-4"><div style="padding:6px 0;" onclick="showMonth(' + numItems + ')" id="click_month_' + numItems + '">+ Month</div><input style="display:none;" type="text" class="form-control" placeholder="Months" id="product_maintenance_month_' + numItems + '" name="product_maintenance_month[]"></div><div class="col-xs-12 col-sm-4 col-md-4"><div style="padding:6px 0;" onclick="showWeek(' + numItems + ')" id="click_week_' + numItems + '">+ Week</div><input style="display:none;" type="text" class="form-control" placeholder="Weeks" id="product_maintenance_week_' + numItems + '" name="product_maintenance_week[]"></div></div></div></div><div class="col-xs-12 col-sm-6 col-md-6"><div class="form-group"><label for="description_of_maintenance">How to maintain</label><textarea class="form-control" name="description_of_maintenance[]" cols="100" rows="1" style="width:90%; float:left;"></textarea><span class="remove_this_row" style="float:right;">X</span></div></div></div>');
    	//var numItems = $('.row_count').length;
		//$("#total_count").val(numItems);
	});
	$('#maintenance_period_div').on('click', '.remove_this_row', function(e) {
		e.preventDefault();
		$(this).parent().parent().parent().remove();
		//var numItems = $("#total_count").val();
		//var numItems1 = numItems-1;	
		//$("#total_count").val(numItems1);	
	});
});
</script>
<script>
	jQuery(document).ready(function() {	
		$('#first').click(function(){
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "block");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			
		});
		
		$('#second').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "block");
			$('.in .fourth').css("display", "none");
			
		});

		$('#third').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "block");
			
		});
		
		$('#prev_second').click(function(){
			
			$('.in .first').css("display", "block");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			
		});

		$('#prev_third').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "block");
			$('.in .third').css("display", "none");
			$('.in .fourth').css("display", "none");
			
		});
		
		$('#prev_fourth').click(function(){
			
			$('.in .first').css("display", "none");
			$('.in .second').css("display", "none");
			$('.in .third').css("display", "block");
			$('.in .fourth').css("display", "none");
			
		});

		$('#click-month').click(function(){	
			$("#load-month").empty();	
			$("#load-month").append('<input type="text" required="1" class="form-control" placeholder="Months" id="product_maintenance_month" name="product_maintenance_month[]">');			
		});

		$('#click-week').click(function(){	
			$("#load-week").empty();	
			$("#load-week").append('<input type="text" required="1" class="form-control" placeholder="Weeks" id="product_maintenance_week" name="product_maintenance_week[]">');			
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

	function showMonth(id){
		$('#click_month_' + id).css("display", "none");
		$('#product_maintenance_month_' + id).css("display", "block");
	}

	function showWeek(id){
		$('#click_week_' + id).css("display", "none");
		$('#product_maintenance_week_' + id).css("display", "block");
	}
	
</script>
<script>
	jQuery(document).ready(function(){

		$('.modal form').ajaxForm({
			success:function() {
				newurl = window.BaseUrl + 'product/product_list';
				window.location = newurl;	  
			},			
			beforeSubmit:function(){
				var overlay = jQuery('<div id="overlay"><div class="overlay-text">It May Take Some Time</div></div>');
				overlay.appendTo(document.body);
			}
		});
	});
</script>

</div>