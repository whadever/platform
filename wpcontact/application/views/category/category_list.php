<script>
$.fn.eqAnyOf = function (arrayOfIndexes) {
    return this.filter(function(i) {
        return $.inArray(i, arrayOfIndexes) > -1;
    });
};

$(document).ready(function() {	
	$("#search_category").bind("keyup",advance_search);
});

function advance_search()
{   
 
	var filter = $("#search_category").val(), count = 0;
	var parr = new Array(); 
	parr = [1,2,3];
	
	
	$("#contact_list_view table tr").each(function()
	{
	 			
		if ($(this).find("td").eqAnyOf(parr).text().search(new RegExp(filter, "i")) < 0) 
		{
			if(this.id != 'header')
			{
				$(this).fadeOut();
			}
		}
		else 
		{	
			$(this).show();
			count++;
		}

	});

	$("#msg").html( count + ' results were found for "' + filter + '"' );
		
}


$(document).ready(function() {
    
    $("#infoMessage").fadeTo(5000, 500).slideUp(500, function(){
          $('#infoMessage').remove();
          //$("#success-alert").alert('close');
    }); 
    
    $('.clickdiv').click(function(){
        //$(this).find('.hiders').toggle();
        $('.hiders').slideToggle();
        $('#minus').toggle();
        $('#plus').toggle();
    });
    
   
            
 });

 
</script>

<div id="all-title">
    <img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
    <span class="title-inner"><?php echo $title;  ?></span>
</div>
<div class="clear"></div>
<div class="content-inner"> 
<div id="infoMessage">
    
    <?php if($this->session->flashdata('success-message')){ ?>
    
    <div class="alert alert-success" id="success-alert">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Success! </strong>
    <?php echo $this->session->flashdata('success-message');?>
    </div>    
    <?php } ?>
    
    <?php if($this->session->flashdata('warning-message')){ ?>
    
    <div class="alert alert-warning" id="warning-alert">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Success! </strong>
    <?php echo $this->session->flashdata('warning-message');?>
    </div>    
    <?php } ?>
      
</div>


<div class="row">
	<div class="col-md-12">
		<div class="row"> 
			<div class="col-xs-12 col-sm-6 col-md-6">
            	<div class="contactsearchbox">
					<input type="text" id="search_category" class="search_contact" name="search" placeholder="Search" /><!--Advance Search-->
				</div>
     		</div>
			<div class="col-xs-12 col-sm-6 col-md-6">
				<span style="float:right;"><a data-toggle="modal" data-target="#AddCategory" >Add Category <img src="<?php echo base_url() ?>images/icons/icon_add_company.png" width="40" /></a> </span>     
			</div>
		</div>
	</div>
</div> 
          
<hr/>    


<!-- Add Category Modal -->
<div class="modal fade" id="AddCategory" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header" style="background: #d72530; color: white;font-weight:bold; font-size:18px;">
				Add Category
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
			<div class="modal-body">
			<?php
			$ci = & get_instance();
			$form_attributes = array('class' => 'category-add-form', 'id' => 'entry-form','method'=>'post');
			$action = 'category/category_add';
			$company_id = form_hidden('id', isset($company->id) ? $company->id : '');
		        
			$category_name = form_label('Add Category (*)', 'contact_title');
			$category_name .= form_input(array(
				'name'        => 'category_name',
				'id'          => 'edit-category_name',
				'value'       => isset($company->category_name) ? $company->company_name : '',
				'class'       => 'form-control',
				'placeholder'=>'Enter Category Name',
				'required'    => TRUE
			));
		
		
			$submit = form_label(' ', 'submit');
			$submit .= form_submit(array(
			              'name'        => 'submit',
			              'id'          => 'edit-submit',
			              'value'       => 'Save',
			              'class'       => 'btn btn-default',
			              'type'        => 'submit',
		                  'onclick'     => 'checkEmail();',
			));
	

			echo '<div id="" class="" style="color:red;">'.validation_errors(). '</div>';
			echo form_open_multipart($action, $form_attributes);
			echo '<div id="sid-wrapper" class="field-wrapper">'. $company_id . '</div>';
	        
			?>


			<div class="company_add">
		    	<div class="row">
					<div class="col-xs-12 col-sm-12 col-md-12">
			        	<?php echo $category_name ; ?>
					</div>
		    	</div>
				
				<p>&nbsp;</p>
		    	<div class="row">
					<div class="col-xs-9 col-sm-10 col-md-8">
					</div>
		        	<div class="col-xs-9 col-sm-10 col-md-2">
						<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
					</div>
		        	<div class="col-xs-3 col-sm-2 col-md-2"><?php echo $submit ; ?></div>        
		    	</div>
	

			</div>
			<?php
				echo form_close();
			?>
		</div>
    </div>
  </div>
</div>

   
<div class="row">
	<div class="col-md-12">  
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-4"></div>
			<div class="col-xs-12 col-sm-12 col-md-4">
				<div id="contact_list_view">
					<table>
						<thead>
							<tr id="header">
								<th>ID</th>
								<th>CATEGORIES</th>
								<th>STATUS</th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ($categories as $category){ ?>
							<tr>
								<td><?php echo $category->id; ?></td>
                                                                <td><a href="<?php echo base_url().'company/tag/'.$category->id; ?>"><?php echo $category->category_name; ?></a></td>
								<td><?php if($category->status == 1){ echo "ACTIVE";}else{echo "INACTIVE";} ?><a href="<?php echo base_url(); ?>category/category_update/<?php echo $category->id; ?>"><img class="edit_icon" src="<?php echo base_url(); ?>images/icons/icon_edit.png" /></a><a onclick="return confirm('Are you sure want to delete this Category?');" href="<?php echo base_url() ?>category/category_delete/<?php echo $category->id; ?>"><img class="edit_icon" style="margin-right:5px;" src="<?php echo base_url(); ?>images/delete_icon.png" /></a></td>
							</tr>
							<?php	} ?>	
						</tbody>
					</table>
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4"></div>
		</div>
	</div>
</div>  
              
</div>