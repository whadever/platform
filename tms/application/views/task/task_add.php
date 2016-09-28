<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-select.css">
<script type="text/javascript" src="<?php echo base_url();?>/js/bootstrap-select.js"></script>

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
            
	$this->breadcrumbs->push('Task', 'request/request_list');
        if(isset($request->id)){
            $this->breadcrumbs->push($request->request_title, 'request/request_detail/'.$request->request_no);

			if($this->uri->segment(2) != 'request_clone'){

				$this->breadcrumbs->push('Modify Task', 'request/request_update/'.$request->request_no);
			}else{

				$this->breadcrumbs->push('Clone Task','request/request_clone/'.$request->id);
			}
        }else{
            $this->breadcrumbs->push('Task Add', 'request/request_add/');
        }
	//echo $this->breadcrumbs->show();
    ?>
<div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div> 

<div class="content-inner task-add">

<?php
        //$pid = isset($project_id)?$project_id:0;

        $form_attributes = array('class' => 'request-add-form', 'id' => 'request-form','method'=>'post');
		$id = form_hidden('id', isset($request->id) ? $request->id : '');

		$request_title = form_label('Task Name', 'request_title');
		$request_title .= form_input(array(
	              'name'        => 'request_title',
	              'id'          => 'edit-request_title',
	              'value'       => isset($request->request_title) ? $request->request_title : set_value('request_title', ''),
	              'class'       => 'form-control',
					'placeholder'=>'Enter Task Name',
                      'required'    => TRUE

		));

		$request_description = form_label('Description', 'request_description');
		$request_description .= form_textarea(array(
	              'name'        => 'request_description',
	              'id'          => 'edit-request_description',
	              'value'       => isset($request->request_description) ? $request->request_description : set_value('request_description', ''),
	              'class'       => 'form-control',  
                        'size'        => '60',
                        'rows'=>5,
                        'cols'=>60,
                        'required'    => TRUE
		));

		$request_no = form_label('Task Id', 'request_no');
		$request_no .= form_input(array(
			'name'        => 'request_no',
			'id'          => 'edit-request_no',
			'value'       => (isset($request->id) && $this->uri->segment(2) != 'request_clone') ? $request->request_no : '',
			'class'       => 'form-control',
			'placeholder'=>'Task Number Will Auto Generate',
			'readonly'=>'true'
		));


		$request_date = form_label('Task Date', 'request_date');
		$request_date .= form_input(array(
	              'name'        => 'request_date',
	              'id'          => 'edit-request_date',
	              'value'       => isset($request->request_date) ? $this->wbs_helper->to_report_date($request->request_date) : date("d-m-Y"),
	              'class'       => 'form-control',   
                      'readonly'=>'true'

		));        

		$ci = & get_instance();
		$ci->load->model('request_model'); 
		$project_option = $ci->request_model->get_project_list();
		$project_options = array('0' => '-- Select Project --') + $project_option;
		$pid =isset($project_id) ? $project_id : set_select('project_id', 0, TRUE);
		$project_default = isset($request->project_id) ? $request->project_id : $pid;
		$project = form_label('Project', 'project_id');
                
		$project_js = 'id="project_id" onChange="" class="form-control selectpicker1"';
		$project .= form_dropdown('project_id', $project_options, $project_default, $project_js);
             
		
         /*$ci->load->model('company_model');
        $comp_options = $ci->company_model->get_company_list();
        $company_options = array('0' => '--Select Company--') + $comp_options;
        $cid =isset($company_id) ? $company_id : 0;        
        $companyid= isset($request->company_id) ? $request->company_id : $cid;        
        $company_js = 'id="company_id" onChange="" class="form-control selectpicker1" required="true"';
		$project_company = form_label('Company', 'company_id');
		$project_company .= form_dropdown('company_id', $company_options, $companyid, $company_js);*/

        $manager_option = $ci->request_model->get_manager_list();
        //$manager_options = array('0' => '-- Select Manager --') + $manager_option;
        
		$manager_default2 = isset($request->assign_manager_id) ? $request->assign_manager_id : 0;
        $manager_default= explode(",", $manager_default2);
        $manager_js = 'id="assign_manager_id" class="multiselectbox"';
		$assign_manager = form_label('Manager', 'assign_manager_id');        
		$assign_manager .= form_multiselect('assign_manager_id[]', $manager_option, $manager_default, $manager_js);

        $developer_option = $ci->request_model->get_developer_list();
        //$developer_options = array('0' => '-- Select Contractor --') + $developer_option;
		$developer_default2 = isset($request->assign_developer_id) ? $request->assign_developer_id : 0;
		$developer_default = explode(",", $developer_default2);
        $developer_js = 'id="assign_developer_id" class="multiselectbox"';
        $assign_developer = form_label('Contractor', 'assign_developer_id');
		$assign_developer .= form_multiselect('assign_developer_id[]', $developer_option, $developer_default, $developer_js);
	

		$priority_default = isset($request->priority) ? $request->priority : 2;
		$priority_options = array(
                  '1'  => 'High',
                  '2'    => 'Normal', 
                  '3' => 'Low',
         );        
        $priority_js = 'id="priority_select" class="form-control selectpicker1"';
        $priority = form_label('Priority', 'priority');
        $priority .= form_dropdown('priority', $priority_options, $priority_default, $priority_js);

        $document_id = form_hidden('document_id', isset($request->document_id) ? $request->document_id : '');  
		$document = form_label('Document', 'upload_document');
		if (isset($request->document)) {

			$document_file = $request->document;
			$document .= form_upload(array(
			  'name'        => 'upload_document',
			  'id'          => 'upload-document',
			  'class'       => 'form-file form-control',
			  'type'        => 'file',
			));

		}else {

			$document .= form_upload(array(

					  'name'        => 'upload_document',
					  'id'          => 'upload-document',
					  'class'       => 'form-file form-control',
					  'type'        => 'file',

			));
		}
        

        $image_id = form_hidden('image_id', isset($request->image_id) ? $request->image_id : '');
        $image = form_label('Image', 'upload_image');
		if (isset($request->image)) {
			$image_file = $request->image;
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

        $estimated_completion = form_label('Task Completion Date', 'estimated_completion');
        $estimated_completion .= form_input(array(
              'name'        => 'estimated_completion',
              'id'          => 'edit-estimated_completion',
              'value'       => isset($request->estimated_completion) ? $this->wbs_helper->to_report_date($request->estimated_completion) :set_value('estimated_completion', ''),
              'class'       => 'form-control',
				'placeholder'=>'Enter Task Estimated Completion Date',
                'required'    => TRUE
        ));        

        $request_default = isset($request->request_status) ? $request->request_status : set_value('request_status');
        $request_status_options = array(
                  '1'  => 'Open',
                  '2'    => 'Closed',                  

        );
        
        $status_js = 'id="status_select" class="form-control selectpicker1"';
        $request_status = form_label('Status', 'request_status');
        $request_status .= form_dropdown('request_status', $request_status_options, $request_default, $status_js);     
    
        $submit_button_value = isset($request->id) ? 'Update' : 'Save';
		if($this->uri->segment(2) == 'request_clone'){
			$submit_button_value = 'Clone Task';
		}
        //$submit = form_label(' ', 'submit');
		$submit = form_submit(array(

	              'name'        => 'submit',
	              'id'          => 'edit-submit',
	              'value'       => $submit_button_value,
	              'class'       => 'form-submit btn btn-default',
	              'type'        => 'submit',
                  'onclick'     => 'checkEmail();',
		));
        

		echo '<div id="" class="" style="color:red;">'.validation_errors(). '</div>';

		echo form_open_multipart($action, $form_attributes);

		echo '<div id="sid-wrapper" class="field-wrapper">'. $id . '</div>';

        ?>

<div class="row">
	<div class="col-md-8">
		<?php echo '<div id="requist-title-wrapper" class="field-wrapper">'. $request_title . '</div>'; ?>
	</div>
</div>

<div class="row">

	<div class="col-md-4">
		<?php echo '<div id="request-no-wrapper" class="field-wrapper">'. $request_no . '</div>'; ?>
		<?php /*echo '<div id="project-company-wrapper" class="field-wrapper">'. $project_company . '</div>'; */?>
		<?php   echo '<div id="project-wrapper" class="field-wrapper">'. $project . '</div>'; ?>	
		<?php echo '<div id="status-wrapper" class="field-wrapper">'. $request_status . '</div>'; ?>
	</div>
	<div class="col-md-4">
		<?php echo '<div id="request-date-wrapper" class="field-wrapper">'. $request_date . '</div>'; ?>
		<?php echo '<div id="assign-manager-wrapper" class="field-wrapper">'. $assign_manager . '</div>'; ?>
		<?php   echo '<div id="assign-developer-wrapper" class="field-wrapper">'. $assign_developer . '</div>'; ?>
		<?php    echo '<div id="sid-wrapper" class="field-wrapper">'. $priority . '</div>'; ?>
	</div>
	<div class="col-md-4">
		<?php echo '<div id="sid-wrapper" class="field-wrapper">'. $estimated_completion . '</div>'; ?>
		<?php echo  $image_id ;
			if(isset($image_file)){
				echo '<div id="sid-wrapper" class="field-wrapper file">'. $image . '<div id="fakefile1" class="fakefile">'.$image_file.'</div></div>';
			}else{
				echo '<div id="sid-wrapper" class="field-wrapper file">'. $image . '<div id="fakefile1" class="fakefile">Upload Images....</div></div>';
			}
        ?>
		<?php echo  $document_id;	
			if(isset($document_file)){
				echo '<div id="filename-custom-wrapper" class="field-wrapper file">'. $document . '<div id="fakefile2" class="fakefile">'.$document_file.'</div></div>';
			}else{
				echo '<div id="filename-custom-wrapper" class="field-wrapper file">'. $document . '<div id="fakefile2" class="fakefile">Upload Documents.....</div></div>';
			}
        ?>
	</div>

</div>

<div class="row">

	<div class="col-md-8">
		<?php echo '<div id="sid-wrapper" class="field-wrapper">'. $request_description . '</div>'; ?>
	</div>
	<div class="col-md-4">
		<?php //echo '<div id="sid-wrapper" class="field-wrapper">'. $submit . '</div>';?>
	</div>

</div>

<div class="control-group">
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-right">
			<div style="padding-top:8px; font-weight:bold">Request Status:</div>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-left">
			<a class="btn btn-default">Pending</a>
		</div>
	</div>
</div>
<div class="control-group">
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-right">
			<a class="btn btn-default" href="#Comment" data-toggle="modal">Decline</a>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-left">
			<a class="btn btn-default">Approved</a>
		</div>
	</div>
</div>

<?php
	echo form_close();
?>


<!-- Comment Modal Box -->
	<div id="Comment" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
			<h3 id="myModalLabel">Add Month</h3>
		</div>
		<div class="modal-body">
		   <?php
				$action = 'consent/month_add';
				$form_attributes = array('class' => 'month-add-form', 'id' => 'month-add-form','method'=>'post', 'onsubmit' => 'return check_available_month()' );

				$month_list = array(
                  '01'  => 'January',
                  '02'    => 'February',
                  '03'   => 'March',
                  '04' => 'April',
				  '05' => 'May',
				  '06' => 'June',
				  '07'  => 'July',
                  '08'    => 'August',
                  '09'   => 'September',
                  '10' => 'October',
				  '11' => 'November',
				  '12' => 'December'
                );

				$months = form_label('Month', 'month');
				$style_month = " id='month_list' style='width:97%'";
				$months .= form_dropdown('month_list', $month_list,'',$style_month);
				
				$defaul = date('Y');
				$years = form_label('Year', 'year');
				$year_list = array();
				for($i=2010; $i<=2030; $i++ )
				{
					$year_list[$i] = $i;
				}
				$style_year = " id='year_list' style='width:97%'";
				$years .= form_dropdown('year_list', $year_list,$defaul,$style_year);
				
		
				$submit = form_label('', 'submit');
				$submit .= form_submit(array(
							  'name'        => 'submit',
							  'id'          => 'save_user',
							  'value'       => 'submit',
							  'class'       => 'form-submit cms_save',
							  'type'        => 'submit',
					
							  
				));

				echo validation_errors();
				echo form_open($action, $form_attributes);
				echo '<div id="name-wrapper" class="field-wrapper">'. $months . '</div>';
				echo '<div id="name-wrapper" class="field-wrapper">'. $years . '</div>';
				echo '<div id="submit-wrapper" class="field-wrapper">'. $submit . '</div>';
				echo form_fieldset_close(); 
				echo form_close();
			?>
		</div>

	</div>






<style>
#ui-datepicker-div{
	z-index: 999 !important;
}
.ui-multiselect-menu label {
    overflow: hidden;    
}
</style>

<script>    
$(document).ready(function() {      
    
    $('#company_id').change(function(){
        company_id = $('#company_id option:selected').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('request/project_by_company');?>"+ "/" +company_id, 
            //data:company_id,
            dataType:"html",//return type expected as json
            success: function(data){                   
                $('#project_id').empty();
                $('#project_id').append(data);                   
            }
        });
    });
    
    $('#project_id').change(function(){
        project_id = $('#project_id option:selected').val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('request/company_by_project');?>"+ "/" +project_id, 
            //data:company_id,
            dataType:"html",
            //return type expected as json
            success: function(data){                   
                        //$('#company_id').empty();
                        //$('#company_id').val();
                        $('#company_id').val(data);
                        //$('#company_id').append(data);
                   
            }
        });
    });
    $(".multiselectbox").multiselect({
        selectedText: "# of # selected"
    });
    //$( "select" ).selectmenu();
    //$('.multiselectbox').selectpicker();

	$('#upload-image').change(function(){
		var filename = $('#upload-image').val();	
		$("#fakefile1").html(filename);
	});
	$('#upload-document').change(function(){
		var filename = $('#upload-document').val();	
		$("#fakefile2").html(filename);
	});
});

</script>

</div>