<style>
	.highlight { background-color: yellow }
	.contractor{
		display: none;
	}
</style>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.highlight.js"></script>
<script>
$.fn.eqAnyOf = function (arrayOfIndexes) {
    return this.filter(function(i) {
        return $.inArray(i, arrayOfIndexes) > -1;
    });
};

$(document).ready(function() {	
	$("#search_contact").bind("keyup",advance_search);
});

function advance_search()
{   
 
	var filter = $("#search_contact").val(), count = 0;
	var parr = new Array(); 
	parr = [0,1,2,3,4,5,6,7,8,9,10,11,12,13];
	
	
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

		/*highlighting search term*/
		var body = $(this).find("td").eqAnyOf(parr);
		body.unhighlight();
		body.highlight( $('#search_contact').val() );

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
<?php
            
           
$data = array(
    'name'        => 'project_name',
    'id'          => 'project_name',
    'value'       => '', 
    'style'       => 'margin-right:10px',
    'placeholder' => 'Project Name'
);

$project_name = form_label('Project Name', 'project_name');
$project_name .= form_input(array(
          'name'        => 'project_name',
          'id'          => 'project_name',
          'value'       => isset($project_search_name)? $project_search_name:'',
          'class'       => 'form-control'

));

$project_options = array(
                   'project_name'  => 'Project Name',
                  'project_status'   => 'Project Status',
                  'id' => 'Latest',                  
                );

$project_sort = form_label('Sort By', 'project_sort');
$js = 'id="select_sort_by" onChange="this.form.submit();" class="form-control selectpicker1"';
$project_sort .= form_dropdown('project_sort', $project_options, $sort_by, $js);

?>



<div class="content-inner"> 
<div class="row">
    <div class="col-md-12"> 
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
    </div>
</div>
  


<div class="row">
	<div class="col-md-12">
		<div class="row"> 
			<div class="col-xs-12 col-sm-6 col-md-6">
            	<div class="contactsearchbox">
					<input type="text" class="search_contact form-control" id="search_contact" name="search" placeholder="Search" />
				</div>
     		</div>
			<div class="col-xs-12 col-sm-6 col-md-6">
				
			</div>
		</div>
	</div>
</div> 
          
<hr/>    

   
<div class="row">
	<div class="col-md-12">  
		<div id="contact_list_view">
			<table class="contact">
				<thead>
					<tr id="header">
						<th>ID</th>
						<th>NAME(S) PRIMARY CONTACT</th>
						<th>COMPANY NAME</th>
						<th>TITLE</th>
						<th>CONTACT NUMBER</th>
						<th>MOBILE NUMBER</th>
						<th>CITY</th>
						<!--<th>COUNTRY</th>-->
						<th>EMAIL</th>
					<th>ACTIONS</th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($contacts as $contact){ ?>
					<tr>
						<td><?php echo $contact->id; ?></td>
						<td><a href="<?php echo base_url() ?>contact/contact_details/<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></a></td>
						<td><a href="<?php echo base_url() ?>company/company_details/<?php echo $contact->company_id; ?>"><?php echo $contact->company_name; ?></a></td>
						<td><?php echo $contact->contact_title; ?></td>
						<td><?php echo $contact->contact_phone_number; ?></td>
						<td><?php echo $contact->contact_mobile_number; ?></td>
						<td><?php echo $contact->contact_city; ?></td>
						<!--<td><?php /*echo $contact->contact_country; */?></td>-->
						<td><?php echo $contact->contact_email; ?></td>
						<td><a href="<?php echo base_url() ?>contact/contact_add/<?php echo $contact->id; ?>" class="<?php echo $user_app_role; ?>"><img class="edit_icon" src="<?php echo base_url(); ?>images/icon/icon_edit.png" /></a></td>
						<!--<?php if($contact->status == 1){ echo "ACTIVE";}else{echo "INACTIVE";} ?><td style="display:none;"><?php /*echo $contact->contact_website; */?></td>
						<td style="display:none;"><?php /*echo $contact->contact_address; */?></td>
						<td style="display:none;"><?php /*echo $contact->contact_email; */?></td>
						<td style="display:none;"><?php /*echo $contact->category_name; */?></td>
						<td style="display:none;"><?php /*echo $contact->contact_notes; */?></td>
						<td style="display:none;"><?php /*echo $contact->company_website; */?></td>-->
					</tr>
					<?php	} ?>	
				</tbody>
			</table>
		</div>
	</div>
</div>  
              
</div>