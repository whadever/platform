<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-select.css" />
<script type="text/javascript" src="<?php echo base_url();?>/js/bootstrap-select.js"></script>

<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
		</div>
	</div>
</div>
<script>
 	
 	function JobChange(){
		job_id = $("#job_name").val();
        if(job_id!=''){
			newurl = "<?php echo base_url(); ?>" + 'templates/template_view/' + job_id;
			window.location = newurl;
		}else{
			newurl = "<?php echo base_url(); ?>" + 'templates/template_view/';
			window.location = newurl;
		}
	}
  
</script>

<script>
jQuery(document).ready(function() {
	if (jQuery('.sortable-category').length){
		$( ".sortable-category" ).sortable({
			update : function () { 
				var order = $('.sortable-category').sortable('serialize');
				$.ajax({
					url: "<?php echo base_url(); ?>" + 'templates/template_category_ordering',
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
<!--
<script>
jQuery(document).ready(function() {
	if (jQuery('.sortable-item').length){
		$( ".sortable-item" ).sortable({
			update : function () { 
				var order = $('.sortable-item').sortable('serialize');
				$.ajax({
					url: "<?php echo base_url(); ?>" + 'templates/template_item_ordering',
					type: 'POST',
					data: order,
					success: function(data) 
					{
	 
					},
				        
				});
			}
		});	
		$( ".sortable-item" ).disableSelection();
	}
});
</script>
-->
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
				url: "<?php echo base_url(); ?>" + 'templates/template_item_drag/'+category_id+'/'+item_id,
				type: 'POST',
				success: function(data) 
				{
					$.ajax({
						url: "<?php echo base_url(); ?>" + 'templates/template_item_ordering',
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


<!-- Check if Flash Data returns error -->	
	<?php if($this->session->flashdata('Failed')) { ?>			
	<div class="alert alert-danger">			
	        <?php echo $this->session->flashdata('Failed'); ?>			
	</div>			
	<?php } ?>


<?php echo form_open('templates/template_view/'.$this->uri->segment(3)); ?>

    <div class="template-add">
	    <div class="row">
	        <div class="col-md-2"></div>
	        <div class="col-xs-12 col-sm-6 col-md-4">
	        <label for="job_name">Template Name</label>
			<select onchange="JobChange();" id="job_name" class="form-control input-sm" required="1" name="job_name">
				<option value="">--Select a Template--</option>
				<?php 
				foreach($templates as $template){
					if($template->id==$this->uri->segment(3)){ $se = 'selected'; }else{ $se = ''; }
					echo '<option '.$se.' value="'.$template->id.'">'.$template->job_name.'</option>';
				}
				?>				
			</select>
	        </div>
<?php if($this->uri->segment(3)!=''): ?>
	        <div class="col-xs-12 col-sm-12 col-md-4">
                        <a href="<?php echo base_url(); ?>templates/template_delete/<?php echo $this->uri->segment(3); ?>" onclick="return confirm('Are you sure you want to delete this template?')" class="btn btn-danger pull-right">Delete Template</a>
	        	<a data-toggle="modal" href="#AddItem" class="btn btn-danger pull-right">Add Item</a>
	        	<a data-toggle="modal" href="#AddCategory" class="btn btn-danger pull-right">Add Category</a>            	
	        </div>
	    </div>
	    <div class="row">&nbsp;</div>
	    <div class="row item-list">
	    	<div class="col-xs-12 col-sm-12 col-md-2"></div>
	        <div class="col-xs-12 col-sm-12 col-md-8">
				<ul class="items-header">
					<li>
						<div class="items">
							<div class="item item_name">Items</div>
							<div class="item item_unit">Measurement</div>
							<div class="item unit_price">Price / Unit (exc. GST)</div>
							<div class="item delete_edit">&nbsp;</div>
						</div>
					</li>
				</ul>
				
				<div class="panel-group sortable-category" id="accordion">
				<?php foreach($categorys as $id => $category): ?>
					<div id="listItemCategory_<?php echo $category->id; ?>" class="panel panel-default">
						<div class="panel-heading">
							<h4 class="panel-title">
							<a class="accordion-toggle" data-category-id="<?php echo $category->id; ?>" data-toggle="collapse"  href="#collapse<?php echo $category->id; ?>">
							<?php echo $category->category_name; ?>
							</a>
							
							<a data-toggle="modal" href="#EditCategory_<?php echo $category->id; ?>" class="pull-right">
							<img width="18" height="18" src="<?php echo base_url(); ?>images/icon_edit.png">
							</a>
							<a class="pull-right" onclick="return confirm('Are you sure delete this category?')" href="<?php echo base_url(); ?>templates/template_category_delete/<?php echo $category->id; ?>/<?php echo $category->template_id; ?>/2">
							<img width="18" height="18" src="<?php echo base_url(); ?>images/delete-icon.png">
							</a>
							
							</h4>
						</div>
						<div id="collapse<?php echo $category->id; ?>" class="panel-collapse collapse 
						<?php if($id=='0'){ $category_id = $category->id; echo 'in'; } ?>">
							<div class="panel-body">
								<table>
								<tbody id="<?php echo $category->id; ?>" class="category_<?php echo $category->id; ?> connectedSortable">
								<?php 
								$item_unit = array( '1'=>'Days', '2'=>'Hours', '3'=>'m2', '4'=>'Units', '5'=>'Dollars');

								$this->db->where('template_category_id',$category->id);
								$this->db->order_by('ordering','asc');
								$items = $this->db->get('jobcosting_templates_items')->result(); 
								foreach($items as $item):
								?> 
									<tr id="listItem_<?php echo $item->id; ?>">
										<td>
										<div class="items">
											<div class="item item_name"><?php echo $item->item_name; ?></div>
											<div class="item item_unit"><?php echo $item_unit[$item->item_unit]; ?></div>
											<div class="item unit_price"><?php echo $item->item_price; ?></div>
											<div class="item delete_edit">
											<a class="pull-right" onclick="return confirm('Are you sure delete this item?')" href="<?php echo base_url(); ?>templates/template_item_delete/<?php echo $item->id; ?>/<?php echo $item->template_id; ?>/2">
											<img width="18" height="18" src="<?php echo base_url(); ?>images/delete-icon.png">
											</a>
											</div>
										</div>
										</td>
									</tr>
								<?php endforeach; ?>
								</tbody>
								</table>
							</div>
						</div>
					</div>
					
				<?php endforeach; ?>
				</div>
	        </div>
	    </div>
	    
	    <div class="row"> 
	    	<div class="col-xs-12 col-sm-12 col-md-2"></div>
	        <div class="col-xs-12 col-sm-12 col-md-8">
	        <?php 
	            $attr_next = array(
	              'name'	=> 'submit',
	              'id'		=> 'next',
	              'value'	=> 'Save',
	              'class'	=> 'btn btn-danger pull-right"',
	              'type'	=> 'submit',
	            );
	            echo form_submit($attr_next);
	            //echo '<a class="btn btn-danger pull-right" href="javascript:void(0)" onclick="NextBack(1);">Next</a>';
	            echo '<a class="btn btn-danger pull-right" href="'.base_url().'templates/template">Back</a>';
	        ?>
	        </div>
	    </div>
	</div>
	
<?php echo form_close(); ?>

<!-- MODAL Edit Category -->
<?php foreach($categorys as $id => $category): ?>
<div id="EditCategory_<?php echo $category->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="POST" action="<?php echo base_url(); ?>templates/edit_category/<?php echo $category->id; ?>">
		<div class="modal-header">
			<h3 id="myModalLabel">Edit Category</h3>
		</div>
	
		<div class="modal-body">	
			<div class="row">		
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Category Name:*</label>
                  		<input value="<?php echo $category->category_name; ?>" type="text" name="category_name" class="form-control" required="" />
					</div>
                </div>
                
				<label for="date_issued"></label>
				<div class="col-xs-6 col-sm-12 col-md-12">
					<input class="btn create width100 pull-right" type="submit" name="submit" value="Submit" />
					<input type="hidden" name="url" value="template_view/<?php echo $this->uri->segment(3); ?>" />
					<button style="margin-right: 10px;" type="button" class="btn cancel width100 pull-right" data-dismiss="modal" aria-hidden="true">Cancel</button>
					
				</div>
			</div>			
		</div>	    
	</form>
</div>
<?php endforeach; ?>

<!-- MODAL Add Category -->
<div id="AddCategory" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="POST" action="<?php echo base_url(); ?>templates/add_category/<?php echo $this->uri->segment(3); ?>">
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
					<input type="hidden" name="url" value="template_view/<?php echo $this->uri->segment(3); ?>" />
					<button style="margin-right: 10px;" type="button" class="btn cancel width100 pull-right" data-dismiss="modal" aria-hidden="true">Cancel</button>
					
				</div>
			</div>			
		</div>	    
	</form>
</div>
<?php endif; ?>
<!-- MODAL Load Template -->
<div id="AddItem" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="POST" action="<?php echo base_url(); ?>templates/add_item/<?php echo $this->uri->segment(3); ?>">
		<div class="modal-header">
			<h3 id="myModalLabel">Item List</h3>
		</div>
	
		<div class="modal-body">	
			<div class="row">	
                <div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Item:*</label>
                  		<select multiple="" required="" name="items[]" id="items" class="form-control multiselectbox">
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


				<label for="date_issued"></label>
				<div class="col-xs-6 col-sm-12 col-md-12">
					<input type="hidden" name="category_id" id="category_id" value="<?php echo $category_id; ?>" />
					<input type="hidden" name="url" value="template_view" />
					<input class="btn create width100 pull-right" type="submit" name="add_item" value="OK" />
					<button style="margin-right: 10px;" type="button" class="btn cancel width100 pull-right" data-dismiss="modal" aria-hidden="true">Cancel</button>
					
				</div>
			</div>			
		</div>	    
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function() {
		$('.multiselectbox').selectpicker();
		$(".draggable").draggable({
	            revert: "invalid",
	            helper: "clone",
	            stack: ".draggable",
	            scroll: false
	        });
	        $(".droppable").droppable({
	            drop: function (e, el) {
	                el.helper.remove();
			var id = el.draggable.attr('data-id');
	                var new_row =  el.draggable.clone();
	                new_row.find("td:last-child").append('<a href="'+base_url+'overview/remove_from_todays_task/'+id+'"><img class="close_image" src="' + base_url + 'images/close-icon.png" /></a>');
	                $($(this).find("tbody")[0]).append(new_row);
	                
	                //var overlay = $("<div id='overlay'><img src='" + base_url + "images/loader.gif'"+" /></div>").appendTo($("body"));
	                var added_el_list = el.draggable.attr('data-list');
	                el.draggable.remove();
	                $.ajax(base_url+"overview/add_to_todays_task/"+id,{
	                    success:function(){
	                        $("#"+added_el_list+"_count").text(parseInt($("#"+added_el_list+"_count").text()-1));
	                        $("#todays_task_count").text(parseInt($("#todays_task_count").text())+1);
	                        //overlay.remove();
	                    }
	                })
	
	            }
	        });
		
//		$('.accordion-toggle').click(function(){
//			category_id = $(this).attr('data-category-id');
//			$('#category_id').val();
//			$('#category_id').val(category_id);
//		});
	});
 </script>
