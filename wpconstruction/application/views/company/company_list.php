<script>
$.fn.eqAnyOf = function (arrayOfIndexes) {
    return this.filter(function(i) {
        return $.inArray(i, arrayOfIndexes) > -1;
    });
};

$(document).ready(function() {	
	$("#search_company").bind("keyup",advance_search);
});

function advance_search()
{   
 
	var filter = $("#search_company").val(), count = 0;
	var parr = new Array(); 
	parr = [1,2,3,4,5];
	
	
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
					<input type="text" id="search_company" class="search_contact form-control" name="search" placeholder="Search" /><!--Advance Search-->
				</div>
     		</div>
			<div class="col-xs-12 col-sm-6 col-md-6">
				   
			</div>
		</div>
	</div>
</div> 
          
<hr/>    

<!-- Add Company Modal -->
<div class="modal fade" id="AddCompany" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
    	<div class="modal-content">
      		<div class="modal-header" style="background: #d72530; color: white;font-weight:bold; font-size:18px;">
				Add Company
        		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
            		<span aria-hidden="true">&times;</span>
        		</button>
      		</div>
			<div class="modal-body">
			<?php
			$ci = & get_instance();
			$form_attributes = array('class' => 'company-add-form', 'id' => 'entry-form','method'=>'post');
			$action = 'company/company_add';
			$company_id = form_hidden('id', isset($company->id) ? $company->id : '');
		        
			$company_name = form_label('Company Name (*)', 'contact_title');
			$company_name .= form_input(array(
				'name'        => 'company_name',
				'id'          => 'edit-company_name',
				'value'       => isset($company->company_name) ? $company->company_name : '',
				'class'       => 'form-control',
				'placeholder'=>'Enter Company Name',
				'required'    => TRUE
			));
		
		
			$ci->load->model('category_model');
			$cat_options = $ci->category_model->get_category_option_list();
			$category_options = array('0' => '--Select Category--') + $cat_options;
			$catid =isset($category_id) ? $category_id : 0;        
			$companyid= isset($company->category_id) ? $company->category_id : $company_id;        
			$company_js = 'id="company_id" onChange="" class="form-control selectpicker1" required="true"';
			$category_list = form_label('Category', 'category_id');
			$category_list .= form_dropdown('category_id', $category_options, $companyid, $company_js);
	
	
			$image_id = form_hidden('image_id', isset($company->image_id) ? $company->image_id : '');
			$image = form_label('Image', 'upload_image');
			if (isset($contact->image)){
				$image_file = $contact->image;
				$image .= form_upload(array(
			              'name'        => 'upload_image',
			              'id'          => 'upload-image',
			              'class'       => 'form-file form-control',
			              'type'        => 'file',
			             ));
		
			}else {
				$image .= form_upload(array(
		              'name'        => 'upload_image',
		              'id'          => 'upload-image',
		              'class'       => 'form-file form-control',
		              'type'        => 'file',
		             ));
			}
			
			$company_notes = form_label('Notes', 'contact_notes');
			$company_notes .= form_textarea(array(
				'name'        => 'company_notes',
				'id'          => 'edit-contact_notes',
				'value'       => isset($company->company_notes) ? $company->company_notes : set_value('company_notes', ''),
				'class'       => 'form-control',  
				'size'        => '60',
				'rows'=>5,
				'cols'=>20,
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
			        	<?php echo $company_name ; ?>
			        	<?php echo $category_list ; ?>
						<?php echo $image ; ?>
						<?php echo $company_notes ; ?>	
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
		<div id="contact_list_view">
			<table id="messages-list" style="width:100%" class="contact">
				<thead>
					<tr id="header">
						<th width="5%">ID</th>
						<th width="20%">COMPANY NAME(S)</th>
						<th width="20%">ADDRESS(S)</th>
						<th width="15%">CONTACT NUMBER(S)</th>
						<th width="15%">CITY</th>
						<th width="15%">COUNTRY</th>
						<th width="10%">STATUS</th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$records_per_page	=	20;	
					$count				=	count($companies);
					$total_pages		=	ceil($count / $records_per_page);

					?>
					
					<script type="text/javascript">

						var total_pages	=	<?php echo $total_pages; ?>;
						var current_page	=	1;
						var loading			=	false;
						var oldscroll		=	0;
						$(document).ready(function(){
						
							$.ajax({
								'url':'<?php echo base_url(); ?>company/auto_load_company',
								'type':'post',
								'data': 'p='+current_page,
								success:function(data){
									var data	=	$.parseJSON(data);
									$(data.html).hide().appendTo('#messages-list').fadeIn(1000);
									current_page++;
								}
							});
							
							$(window).scroll(function() {
								if( $(window).scrollTop() > oldscroll ){ //if we are scrolling down
									if( ($(window).scrollTop() + $(window).height() >= $(document).height()  ) && (current_page <= total_pages) ) {
										   if( ! loading ){
												loading = true;
												$('#messages-list').addClass('loading');
												$.ajax({
													'url':'<?php echo base_url(); ?>company/auto_load_company',
													'type':'post',
													'data': 'p='+current_page,
													success:function(data){
														var data	=	$.parseJSON(data);
														$(data.html).hide().appendTo('#messages-list').fadeIn(1000);
														current_page++;
														$('#messages-list').removeClass('loading');
														loading = false;
													}
												});	
										   }
									}
								}
							});
							
						});
					</script>
				</tbody>
				


			</table>
		</div>
	</div>
</div>  
              
</div>