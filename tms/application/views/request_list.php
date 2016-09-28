<?php 
//if(isset($massage)) echo $message; 
$user=  $this->session->userdata('user');  
$user_id =$user->uid; 
$role_id = $user->rid;           
?>

<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
			<a href="<?php echo base_url();?>request/request_add" class="btn btn-default add-button tour tour_1"> Add Task</a>
		</div>
	</div>
</div>

<div class="content-inner right-scroll">
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
<?php
        $form_attributes = array('class' => 'search-form', 'id' => 'request-search-form','method'=>'post');
	//$action='';
        $action=base_url().'request/request_list';
        //$get = $_GET;  
        $get = $this->session->userdata('searchvalue'); 
        
        $selectbox_js='class="form-control selectpicker1"';
        
        $request_no = form_label('Task No', 'request_no');
	$request_no .= form_input(array(
	              'name'        => 'request_no',
	              'id'          => 'edit-request_no',
	              'value'       => isset($get['request_no']) ? $get['request_no'] : '',
	              'class'       => 'form-control',
				  'placeholder'    => 'Enter Task No'
	));
        
	$request_title = form_label('Task Title', 'request_title');
	$request_title .= form_input(array(
	              'name'        => 'request_title',
	              'id'          => 'edit-request_title',
	              'value'       => isset($get['request_title']) ? $get['request_title'] : '',
	              'class'       => 'form-control',
                      'placeholder'    => 'Enter Task Title'
	));
        
        //$request_default = isset($request->request_status) ? $request->request_status : 0;
        $request_default = isset($get['request_status']) ? $get['request_status'] : '0';
        $request_status_options = array(
                    '0'=>'Select Status',
                  '1'  => 'Open',
                  '2'    => 'Completed',                 
                 '3'    => 'On Time',
                '4'    => 'Overdue'
                );
        $request_status = form_label('Task Status', 'request_status');
        $request_status .= form_dropdown('request_status', $request_status_options, $request_default, $selectbox_js);
        
        
        $ci = & get_instance();
		$ci->load->model('request_model');  
        
        //$ci->load->model('company_model');

	
        if($role_id==3){
            $comp_options = $ci->request_model->get_developer_company_list($user_id);
        }else{
            
            $comp_options = $ci->request_model->get_company_list();
        }
        $company_options = array('0' => 'Select Company') + $comp_options;
        $cid =isset($company_id) ? $company_id : 0;        
        //$company_default= isset($project->company_id) ? $project->company_id : $cid;
        $company_default = isset($get['company_id']) ? $get['company_id'] : '0';
		$project_company = form_label('Company Name', 'company_id');
		$project_company .= form_dropdown('company_id', $company_options, $company_default, $selectbox_js);

        
		if($role_id==3){
            $project_option = $ci->request_model->get_developer_project_list($user_id);
        }else{
            
            $project_option = $ci->request_model->get_project_list();
        }
        $project_options = array('0' => 'Select Project') + $project_option;
        $pid =isset($project_id) ? $project_id : 0;
		//$project_default = isset($request->project_id) ? $request->project_id : $pid;
        $project_default = isset($get['project_id']) ? $get['project_id'] : '0';
		$project = form_label('Project Name', 'project_id');
		$project .= form_dropdown('project_id', $project_options, $project_default, $selectbox_js);     
              
        
        
        $manager_option = $ci->request_model->get_manager_list();
        $manager_options = array('0' => 'Select Manager') + $manager_option;	
        $manager_default = isset($get['assign_manager_id']) ? $get['assign_manager_id'] : '0';
	$assign_manager = form_label('Managers', 'assign_manager_id');
	$assign_manager .= form_dropdown('assign_manager_id', $manager_options, $manager_default, $selectbox_js);	
	
        
        
	if($role_id==3){
            $developer_default=$user_id;
            $select_js = 'disabled class="form-control"';
        }else{
            $developer_default = isset($get['assign_developer_id']) ? $get['assign_developer_id'] : '0';
            $select_js = 'class="form-control"';
            
        }
	$developer_option = $ci->request_model->get_developer_list();
        $developer_options = array('0' => 'Select Developer') + $developer_option;	
        
	$assign_developer = form_label('Contractor', 'assign_developer_id');
	$assign_developer .= form_dropdown('assign_developer_id', $developer_options, $developer_default, $select_js);
        
        $request_priority_default = isset($get['request_priority']) ? $get['request_priority'] : '0';
        $request_priority_options = array(
                    '0'=>'Select Priority',
                  '1'  => 'High',
                  '2'    => 'Normal',                 
                 '3'    => 'Low'
                );
        $request_priority = form_label('Task Priority', 'request_priority');
        $request_priority .= form_dropdown('request_priority', $request_priority_options, $request_priority_default, $selectbox_js);
        
        
	$start_date = form_label('Date From', 'start_date');
	$start_date .= form_input(array(
		'name'        => 'start_date',
		'id'          => 'edit-start_date',
		'value'       => isset($get['start_date']) ? $get['start_date'] : '',
		'class'       => 'tmsdatepicker form-control',
  		'placeholder'=>'Select Date'
	)); 
       

	$end_date = form_label('Date To', 'end_date');
	$end_date .= form_input(array(
		'name'        => 'end_date',
		'id'          => 'edit-end_date',
		'value'       => isset($get['end_date']) ? $get['end_date'] : '',
		'class'       => 'tmsdatepicker form-control',
  		'placeholder'=>'Select Date'
	));
	
        $submit = form_label('', '');
        $submit .= form_submit(array(
              'name'        => 'submit',
              'id'          => 'search-button',
              'value'       => 'Search',
              'class'       => 'btn btn-default',
              'type'        => 'submit',
        ));
        
?>
<div class="searchbox">
    <div class="clickdiv tour tour_2" style="background:#EBEBEB;padding: 5px;">
        <strong> 
            <span> Search </span>
            <span id="plus">+</span>
            <span id="minus" style="display:none;">-</span>
        </strong>
    </div> 
    <div class="hiders" style="display:none;"> 
        <div class="row">		
	<?php	echo form_open($action, $form_attributes); ?>

                <div class="col-md-3">
                        <?php	
                                echo '<div id="search-request_no-wrapper" class="field-wrapper">'. $request_no . '</div>'; 
                                echo '<div id="search_request-status-wrapper" class="field-wrapper">'. $request_status . '</div>';
								echo '<div id="search_request-status-wrapper" class="field-wrapper">'. $start_date . '</div>';
                        ?>
						
                </div>
                <div class="col-md-3">
                        <?php	
                                echo '<div id="search-request_title-wrapper" class="field-wrapper">'. $request_title . '</div>';
                                echo '<div id="search_request-status-wrapper" class="field-wrapper">'. $request_priority . '</div>';
								echo '<div id="search_request-status-wrapper" class="field-wrapper">'. $end_date . '</div>';
                        ?>
                </div>
                <div class="col-md-3">
                        <?php	
                                echo '<div id="search-project-id-wrapper" class="field-wrapper">'. $project . '</div>';
                                echo '<div id="search_request-developer-wrapper" class="field-wrapper">'. $assign_developer . '</div>';
                        ?>
						<div id="submit-wrapper" class="field-wrapper field-button" style="padding-top:22px; text-align:left">
							<?php echo $submit; ?>
						</div>
                </div>
                <div class="col-md-3">
                        <?php	
                                //echo '<div id="search-project-company-id-wrapper" class="field-wrapper">'. $project_company . '</div>';
                                echo '<div id="search_request-manager-wrapper" class="field-wrapper">'. $assign_manager . '</div>';
                        ?>
						<div id="submit-wrapper" class="field-wrapper field-button" style="padding-top:22px; text-align:left">
							<a href="<?php echo base_url().'request/clear_search'; ?>" class="btn btn-default">Clear Search </a>
						</div>
                </div>

                <div class="col-md-12">
                        
						
                </div>

            <?php	echo form_close(); ?>
            </div>

	</div>

</div>


<div class="row">
	<div class="col-md-12">
		<div class="line"></div>
	</div>
</div>

<div id="request_list_view" class="row table-list">
	<div class="col-md-12">
		<?php if(isset($table)) { echo $table;	} ?> 
		<div class="pagination"> 
			<?php if(isset($pagination)) { echo $pagination;	} ?>
		</div> 
	</div>
</div>

<script>
 $(document).ready(function() {
    $('[data-toggle=tooltip]').tooltip({'placement': 'top'});
}); 
</script>

<script type="text/javascript">    

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

/*tour. task #4421*/
var config = [
        {
            "name" 		: "tour_1",
            "bgcolor"	: "black",
            "color"		: "white",
            "position"	: "RT",
            "text"		: "Add a new task from here. You can also add a new task from 'Projects'",
            "time" 		: 5000,
            "buttons"	: ["<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>"]
        },
        {
            "name" 		: "tour_2",
            "bgcolor"	: "black",
            "color"		: "white",
            "text"		: "Search anything that you want. We offer a wide range of flexibility for you to search a specific task.",
            "position"	: "B",
            "time" 		: 5000,
            "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>", "<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
        },
        {
            "name" 		: "tour_3",
            "bgcolor"	: "black",
            "color"		: "white",
            "text"		: "This to show the important of the task and the status of task as well. From Low, Normal, High priority and whether the status is Overdue or Completed.",
            "position"	: "RT",
            "time" 		: 5000,
            "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>", "<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
        },
        {
            "name" 		: "tour_4",
            "bgcolor"	: "black",
            "color"		: "white",
            "text"		: "Duplicate your task information from here if you have a similar information you need to put.",
            "position"	: "RT",
            "time" 		: 5000,
            "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>",  "<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
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

</div>

<!---<div class="row" style="position: absolute; width: 99%;">
	<div class="col-md-12">
		<?php if(isset($color)) { echo $color;	} ?> 
	</div>
</div>--->
<style>
.footer {
    margin-top: 35px;
}
</style>