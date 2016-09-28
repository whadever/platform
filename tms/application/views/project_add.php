<?php
	$user=  $this->session->userdata('user'); 
    $user_role_id =$user->rid; 
	$form_attributes_edit = array('class' => 'project-add-form', 'id' => 'entry-form','method'=>'post');
	$form_attributes_add = array('class' => 'project-add-form', 'id' => 'entry-form','method'=>'post', 'onsubmit' => 'return checkProjectName()');
	$id = form_hidden('id', isset($project->id) ? $project->id : '');       
       
       
        
        if(isset($project->project_no)){
            $project_no = form_label('Project Number', 'project_no');
            $project_no .= form_input(array(
              'name'        => 'project_no',
              'id'          => 'edit-project_no',
              'value'       => isset($project->project_no) ? $project->project_no : '',
              'class'       => 'form-control',
                      'placeholder'=>'00001',
                      'readonly'=>'true'  

                ));            

        }else{

            $project_no = form_label('Project Number', 'project_no');
            $project_no .= form_input(array(
              'name'        => 'project_no',
              'id'          => 'edit-project_no',
              'value'       => isset($project->project_no) ? $project->project_no : '',
              'class'       => 'form-control',
                      'placeholder'=>'00001',
                      'readonly'=>'true'  
                ));     

        }     
                
	$project_name = form_label('', 'project_name');
	$project_name .= form_input(array(
	              'name'        => 'project_name',
	              'id'          => 'edit-project_name',
	              'value'       => isset($project->project_name) ? $project->project_name : '',
	              'class'       => 'form-control input-lg',                       
                        'placeholder'=>'Enter Project Name',
                    'required'    => TRUE
	));
        
	$project_date = form_label('Project Completion Date', 'project_date');
	$project_date .= form_input(array(
	              'name'        => 'project_date',
	              'id'          => 'edit-project_date',
	              'value'       => isset($project->project_date) ? date("d-m-Y",strtotime($project->project_date)) : '',
	              'class'       => 'form-control',
                      'placeholder'=>'Enter Estimated Project Completion Date',
                      'required'    => TRUE
	));
	
	$project_start_date = form_label('Project Start Date', 'project_start_date');
	$project_start_date .= form_input(array(
	              'name'        => 'project_start_date',
	              'id'          => 'edit-project_start_date',
	              'value'       => $project->project_start_date && $project->project_start_date!='0000-00-00' ? date("d-m-Y",strtotime($project->project_start_date)) : '',
	              'class'       => 'form-control',
                  'placeholder'=>'Enter Project Start Date',
                  'required'    => TRUE
	));

	$project_description = form_label('Project Description', 'project_description');
	$project_description .= form_textarea(array(
	              'name'        => 'project_description',
	              'id'          => 'edit-project_description',
	              'value'       => isset($project->project_description) ? $project->project_description : '',
	              'class'       => 'form-control',
                      'rows'   => '8',
                      'cols'        => '100',
                      'style'       => 'width:100%',
                      'required'    => TRUE

	));      
	
	
	$ci = & get_instance();
	$ci->load->model('company_model');
	$comp_options = $ci->company_model->get_company_list();
    $company_options = array('0' => 'No Company') + $comp_options;
    $cid =isset($company_id) ? $company_id : 0;        
    $companyid= isset($project->company_id) ? $project->company_id : $cid;
	$project_company = form_label('Project Company', 'company_id');
    $project_company_js= 'class="form-control selectpicker"';
	$project_company .= form_dropdown('company_id', $company_options, $companyid, $project_company_js);
        
        
	$ci->load->model('request_model');
    $manager_option = $ci->request_model->get_manager_list();
    //$manager_options = array('0' => '-- Select Manager --') + $manager_option;
        
	$manager_default2 = isset($project->assign_manager_id) ? $project->assign_manager_id : 0;
    $manager_default= explode(",", $manager_default2);
    $manager_js = 'id="assign_manager_select" class="multiselectbox"';
	$assign_manager = form_label('Manager', 'assign_manager_id');        
	$assign_manager .= form_multiselect('assign_manager_id[]', $manager_option, $manager_default, $manager_js);

    $developer_option = $ci->request_model->get_developer_list();
    //$developer_options = array('0' => '-- Select Contractor --') + $developer_option;
	$developer_default2 = isset($project->assign_developer_id) ? $project->assign_developer_id : 0;
	$developer_default = explode(",", $developer_default2);
    $developer_js = 'id="assign_developer_select" class="multiselectbox"';
    $assign_developer = form_label('Contractor', 'assign_developer_id');
	$assign_developer .= form_multiselect('assign_developer_id[]', $developer_option, $developer_default, $developer_js);
	

	$selected = isset($project->project_status) ? $project->project_status : 1;	
	$status_options = array(
                  '1'  => 'Open',
                  '2'    => 'Closed',             
                );
	$project_status = form_label('Project Status', 'project_status');
	$project_status_js= 'class="form-control selectpicker"';
	$project_status .= form_dropdown('project_status', $status_options, $selected, $project_status_js);

	$client_name = form_label('Client Name', 'client_name');
	$client_name .= form_input(array(
	              	'name'        => 'client_name',
	              	'id'          => 'edit_client_name',
	              	'value'       => isset($notification->client_name) ? $notification->client_name : '',
	              	'class'       => 'form-control',                       
					'placeholder'=>'Enter Client Name'
	));


	$frequency_options = array('1' => 'Daily', '2' => 'Weekly', '3'=> 'Fortnightly', '4' => 'Monthly');
	$frequency_default2 = isset($notification->frequency) ? $notification->frequency : 0;
	$frequency_default = explode(",", $frequency_default2);
    $frequency_js = 'id="assign_frequency" class="multiselectbox"';
    $frequency = form_label('Frequency', 'assign_frequency');
	$frequency .= form_multiselect('frequency[]', $frequency_options, $frequency_default, $frequency_js);


	$clients_email = form_label("Client's Email", 'clients_email');
	$clients_email .= form_input(array(
	              	'name'        => 'clients_email',
	              	'id'          => 'edit_clients_email',
	              	'value'       => isset($notification->client_email) ? $notification->client_email : '',
	              	'class'       => 'form-control',                       
					'placeholder'=>'Enter Clients Email'

	));


	$submit = form_label(' ', 'submit');
	$submit .= form_submit(array(
	              'name'        => 'submit',
	              'id'          => 'edit-submit',
	              'value'       => 'Save',
	              'class'       => 'btn btn-default form-submit',
	              'type'        => 'submit',
                  'onclick'     => 'checkEmail();',
	));
?>

<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
			<input type="button" value="Back" class="btn btn-default add-button" onclick="history.go(-1);"/>
		</div>
	</div>
</div>

<?php

	
	
	/*echo '<div id="sid-wrapper" class="field-wrapper">'. $id . '</div>';
        echo '<div id="sid-wrapper" class="field-wrapper">'. $project_no . '</div>';
	echo '<div id="sid-wrapper" class="field-wrapper">'. $project_name . '</div>';
	echo '<div id="sid-wrapper" class="field-wrapper">'. $project_description . '</div>';
        //echo '<div id="sid-wrapper" class="field-wrapper">'. $project_company . '</div>';
        echo '<div id="sid-wrapper" class="field-wrapper">'. $project_status . '</div>';
   
	echo '<div id="sid-wrapper" class="field-wrapper">'. $submit . '</div>';
	echo form_fieldset_close(); 
	echo form_close(); */
?>
<?php   
            
	$this->breadcrumbs->push('Project', 'project/project_list');
        if(isset($project->id)){
            $this->breadcrumbs->push($project->project_name, 'project/project_detail/'.$project->id);
            $this->breadcrumbs->push('Modify Project', 'project/project_update/'.$project->id);
        }else{
            $this->breadcrumbs->push('Project Add', 'project/project_add/');
        }
	//echo $this->breadcrumbs->show();
    ?>
    <div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div> 
<div id="project_add_edit_page" class="content-inner">
    
  
<?php
	echo '<div id="" class="" style="color:red;">'.validation_errors(). '</div>';
	if(isset($project->id)){
		echo form_open_multipart($action, $form_attributes_edit);
	}else{
		echo form_open_multipart($action, $form_attributes_add);
	}
	//echo form_fieldset('',array('class'=>"comp-add-fieldset"));
	echo '<div id="sid-wrapper" class="field-wrapper">'. $id . '</div>';
    
    $wp_company_id = $this->session->userdata('user')->company_id;     
?>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9 tour tour_1" style="background-color: white">
			<div class="row">
		        <div class="col-xs-12 col-sm-4 col-md-4 col-lg-4 col-xl-4"><h3>Project Name : </h3></div>
		        <div class="col-xs-12 col-sm-8 col-md-8 col-lg-8 col-xl-8"><?php echo $project_name ; ?><div class="taken"></div></div>
		    </div>
		    <p>&nbsp;</p>
		    <div class="row">
				<?php if($wp_company_id==111){ ?>
				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"><?php echo $project_status ; ?></div>
		        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"><?php echo $project_start_date ; ?></div>
		        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4"><?php echo $project_date ; ?></div>
		        <!-- <div class="col-xs-12 col-sm-6 col-md-3"><?php //echo $project_company ; ?></div> -->   
		        <?php }else{ ?>
		        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><?php echo $project_status ; ?></div>
		        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6"><?php echo $project_date ; ?></div>
		        <?php } ?>       
		    </div>
			<p>&nbsp;</p>
		    <div class="row">
		        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
		            <div id="assign-manager-wrapper" class="field-wrapper"><?php echo $assign_manager ; ?></div>
		        </div>
		        <div class="col-xs-12 col-sm-6 col-md-6 col-lg-6 col-xl-6">
		            <div id="assign-developer-wrapper" class="field-wrapper"><?php echo $assign_developer ; ?></div>
		        </div>
		    </div>
		    <p>&nbsp;</p>
		    <div class="row">
		        <div class="col-xs-12 col-sm-12 col-md-12"><?php echo $project_description ; ?></div>        
		    </div>
		    <p>&nbsp;</p>
		</div>

		<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 tour tour_2" style="background-color: white">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
					<h2>Notification | Client</h2>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
					<?php echo $client_name; ?>
				</div>
			</div>
			<p>&nbsp;</p>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
					<div class="pull-left">
						<label>Notification</label>
					</div>	
					<div class="pull-right">
						<input type="checkbox" name="notification" value="1" <?php if($notification->notification==1){ ?> checked <?php } ?>  id="frequency_check">
					</div>
				</div>
			</div>
			<div id="frequency_list" class="row" <?php if($notification->notification!=1){ ?> style="display:none" <?php } ?> >
			<p>&nbsp;</p>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
					<?php echo $frequency; ?>
				</div>
			</div>
			<p>&nbsp;</p>
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
					<?php echo $clients_email; ?>
				</div>
			</div>

		</div>

	</div>

    <div class="row">
        	<div class="col-xs-12 col-sm-12 col-md-9 col-lg-9 col-xl-9"></div>
        	<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 col-xl-3 pull-right"><?php echo $submit ; ?></div>       
    </div>


<?php
	//echo form_fieldset_close(); 
	echo form_close();
?>
</div>
<style>
.ui-multiselect-menu label {
    overflow: hidden;    
}
</style>
<script>    
$(document).ready(function() {      
    
    $(".multiselectbox").multiselect({
        selectedText: "# of # selected"
    });

	$('#frequency_check').change(function(){
		if($(this).is(':checked')){
		  	$("#frequency_list").show("slow");  
		}else{
			$("#frequency_list").hide("slow"); 					
		}
	});
    
});

</script>
<script>
	window.Url = "<?php print base_url(); ?>";

	function checkJnumber()  
	{  
		var project_name = $('#edit-project_name').val();
		
		$.ajax({				
			url: window.Url + 'project/check_project_name?project_name=' + project_name,
			type: 'POST',
			success: function(data) 
			{	
				//console.log(data);
				if(data == 1){
					$('#edit-project_name').css('border', '1px solid #FF0000');
					$('.taken').empty();
					$('.taken').append('<span style="color:#FF0000;">Project Name already exists</span>');
	        		return false;
				}else{
			        $('#edit-project_name').css('border', '1px solid #ccc');
			        $('.taken').empty();
			        return true;
			    }
			},
		        
		}); 		  
	} 

	function checkProjectName()
	{
	    var project_name = $('#edit-project_name').val();
	        
        var html = $.ajax({
	        async: false,
	        url: window.Url + 'project/check_project_name?project_name=' + project_name,
	        type: 'POST',
	        dataType: 'html',
	        //data: {'pnr': a},
	        timeout: 2000,
	    }).responseText;
	    if(html==1){
	        $('#edit-project_name').css('border', '1px solid #FF0000');
			$('.taken').empty();
			$('.taken').append('<span style="color:#FF0000;">Project Name already exists</span>');
        	return false;
	    }else{
	        $('#edit-project_name').css('border', '1px solid #ccc');
			$('.taken').empty();
	        return true;
	    } 
	    
	}

	/*tour. task #4421*/
	var config = [
			{
				"name" 		: "tour_1",
				"bgcolor"	: "black",
				"color"		: "white",
				"position"	: "B",
				"text"		: "Enter your project name, status of the project, completion date of the project, manager(s) and contractor(s) in charge. Also put the description and featured photo for this project.",
				"time" 		: 5000,
				"buttons"	: ["<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>"]
			},
			{
				"name" 		: "tour_2",
				"bgcolor"	: "black",
				"color"		: "white",
				"text"		: "The purpose of this field is to remind you that you have to follow up your client. You can set up frequency notification to what you want.",
				"position"	: "B",
				"time" 		: 5000,
				"buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>"]
			}

		],
	//define if steps should change automatically
		autoplay	= false,
	//timeout for the step
		showtime,
	//current step of the tour
		step		= 0,
	//total number of steps
		total_steps	= config.length;
</script>
