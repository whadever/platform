
<script>
function sort_project(){
    var sort_by= $('#select_sort_by').val();
    alert(sort_by);
}
$(function() {
    var icons = {
      header: "ui-icon-circle-plus",
      activeHeader: "ui-icon-circle-minus"
    };
    $( "#accordion" ).accordion({
      icons: icons,
      active: false,
    collapsible: true,
        heightStyle: 'content'

   
    });
    $( "#toggle" ).button().click(function() {
      if ( $( "#accordion" ).accordion( "option", "icons" ) ) {
        $( "#accordion" ).accordion( "option", "icons", null );
      } else {
        $( "#accordion" ).accordion( "option", "icons", icons );
      }
    });
  });
  
  $(document).ready(function() {
    
    $("#infoMessage").fadeTo(5000, 500).slideUp(500, function(){
          $('#infoMessage').remove();
          //$("#success-alert").alert('close');
    }); 
    
    $('.clickdiv').click(function(){
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
            "text"		: "Add a new project from here.",
            "time" 		: 5000,
            "buttons"	: ["<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>"]
        },
        {
            "name" 		: "tour_2",
            "bgcolor"	: "black",
            "color"		: "white",
            "text"		: "Search anything you want from  the name of the project, the project manager, and the status of the project.",
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


$ci = & get_instance();
$ci->load->model('request_model'); 

$manager_option = $ci->request_model->get_manager_list();    
$manager_default2 = isset($assign_manager_id) ? $assign_manager_id : 0;
$manager_options = array('0' => '-- Select Project Manager --') + $manager_option;
$manager_default= explode(",", $manager_default2);
$manager_js = 'id="assign_manager_id" onChange="this.form.submit();" class="form-control"';
$assign_manager = form_label('Project Manager', 'assign_manager_id');        
$assign_manager .= form_dropdown('assign_manager_id', $manager_options, $manager_default, $manager_js);



$project_options = array(
                   'project_name'  => 'Project Name',
                  //'project_status'   => 'Project Status',
                  'id' => 'Latest',                  
                );

$project_sort = form_label('Sort By', 'project_sort');
$js = 'id="select_sort_by" onChange="this.form.submit();" class="form-control selectpicker1"';
$project_sort .= form_dropdown('project_sort', $project_options, $sort_by, $js);

$project_status_options = array(
					/*''  => 'Select Status',*/
                   '1'  => 'Open',
                  '3'   => 'All',
                  '2' => 'Completed',                  
                );

$project_status_default=isset($project_search_status)? $project_search_status : 1;
$project_status = form_label('Status', 'project_status');
$project_status_js = 'id="select_project_status" onChange="this.form.submit();" class="form-control selectpicker1"';
$project_status .= form_dropdown('project_status', $project_status_options, $project_status_default, $project_status_js);

?>
<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
                        <?php 
                        $user=  $this->session->userdata('user');  
                        $user_role_id =$user->rid; 
                        if($user_role_id!=3){ ?>                        
                        <span style="float:right;" class="tour tour_1"><a class="btn btn-default add-button" href="project_add">Add Project</a> </span>
                        <?php } ?>
			
		</div>
	</div>
</div>


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
            <div class="searchbox">
                <div class="clickdiv tour tour_2" style="background:#EBEBEB;padding: 5px;">
                    <strong> 
                        <span> Search </span>
                        <span id="plus">+</span>
                        <span id="minus" style="display:none;">-</span>
                    </strong>
                </div> 
                <div class="hiders" style="display:none;" > 
            
                    <?php
            echo form_open('project/project_list');
            
            //echo form_label('Project Name ', 'project_name');            
           // echo form_input($data);
            //echo form_label('Sort By ', 'project_sort');
            ?>
        <div class="row"> 
            <div class="col-xs-12 col-sm-6 col-md-3">
                <?php //echo '<div id="requist-title-wrapper" class="field-wrapper">'. $project_name . '</div>'; ?>
                <?php echo $project_name; ?>
            </div>

			<div class="col-xs-12 col-sm-6 col-md-3">
                <?php echo $assign_manager; ?>
            </div>

			
            <div class="col-xs-12 col-sm-6 col-md-3">
                <?php   //echo '<div id="project-wrapper" class="field-wrapper">'. $project_sort . '</div>'; ?>
                <?php   echo $project_sort; ?>
            </div>

			<div class="col-xs-12 col-sm-6 col-md-3">
                <?php   //echo '<div id="project-wrapper" class="field-wrapper">'. $project_status . '</div>'; ?>
                <?php   echo $project_status; ?>
            </div>


		

			

		
           
        </div>
   
<?php            //echo form_dropdown('project_sort', $options, $sort_by, $js);
            echo form_close();
            ?>
            </div>
            </div>
    </div>
</div>
     <hr/>       
    
 <div class="row">
    <div class="col-md-12">  
            <div id="project_list_view" class="">
<div id="accordion">	

    <?php foreach ($projects as $project){ ?>
    <?php // -----Detail Button Validation----- ?>
                <?php $valid = 0; ?>

            <?php if($project->privacy_type==0){
                
                $valid = 1;
                 }
                elseif($project->privacy_type == 1 && $user->role == 2)
                {
                    $valid = 1;
                }
                
                elseif($project->privacy_type == 2 && $user->uid == $project->created_by)
                {
                    $valid = 1;
                }
                elseif($project->privacy_type == 3)
                {
                    if($user->uid == $project->created_by)
                    {

                        $valid = 1;
                    }
                    else{
                         $user_default= explode(",", $project->privacy_user_ids);
                         
                         foreach ($user_default as $key) {
                             if($key == $user->uid){
                                $valid = 1;
                             }

                         }  
                    }
                    


                }

                elseif($project->privacy_type == 4){
                    $valid = 1;
                }
                else{
                    $valid = 0;
                }
            ?>
            <?php if($valid == 1){ ?>
   
            <h3 style="margin-top: 0px;">
                <?php echo $project->project_name; ?>
            </h3>

    <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
        <table class="company table table-bordered">
            <tr>
                <td>Project Name </td>
                <td><?php echo $project->project_name;?></td>
            </tr>
            <tr>
                <td>Project Description </td>
                <td> <?php echo $project->project_description; ?></td>
            </tr>
            <?php
            $wp_company_id = $this->session->userdata('user')->company_id;
            if($wp_company_id==111){
            ?>
            <tr>
                <td>Project Start Date </td>
                <td> <?php if($project->project_start_date!='0000-00-00'){ echo date("d-m-Y",strtotime($project->project_start_date)); } ?></td>
            </tr>
            <tr>
                <td>Project Completion Date </td>
                <td> <?php if($project->project_date!='0000-00-00 00:00:00'){ echo date("d-m-Y",strtotime($project->project_date)); } ?></td>
            </tr>
            <?php } ?>
            <tr>
                <td>Project Status </td>
                <td><?php if($project->project_status==1) echo 'Open'; else if($project->project_status==2) echo 'Completed' ; else echo 'Undefined'; ?> </td>
            </tr>
        </table>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <h4>Tasks</h4>
            <div style="margin:10px;padding:0px 20px;"> 
            <?php           
              

            $user=  $this->session->userdata('user');  
            $user_id =$user->uid; 
            $role_id = $user->rid;  
            
            if($role_id==3){
                $sql_open = 'SELECT count( id ) AS open_bug FROM `request` WHERE project_id ='.$project->id.' AND request_status =1 AND FIND_IN_SET('.$user_id.' , assign_developer_id)';
            }else{
                $sql_open = 'SELECT count( id ) AS open_bug FROM `request` WHERE project_id ='.$project->id.' AND request_status =1';
            }
            $open_count=  $this->db->query($sql_open)->row(); 
            $class = $open_count->open_bug >=1 ? 'red': 'normal';           
            echo '<div class="bug-count-box"><div class="pull-left">Open</div><div style="text-align:right" class="pull-right '.$class.'">'.$open_count->open_bug.'</div></div>';
            echo '<div class="clear"></div>';
            if($role_id==3){
                $sql_close = 'SELECT count( id ) AS close_bug FROM `request` WHERE project_id ='.$project->id.' AND request_status =2 AND FIND_IN_SET('.$user_id.' , assign_developer_id)';
            }else{
                $sql_close = 'SELECT count( id ) AS close_bug FROM `request` WHERE project_id ='.$project->id.' AND request_status =2';
            }
            $close_count=  $this->db->query($sql_close)->row(); 
            echo '<div class="bug-count-box"><div class="pull-left">Completed</div><div class="pull-right">'.$close_count->close_bug.'</div></div>';            
            echo '<div class="clear"></div>';
            ?> 
            </div>

            <div style="text-align:right; margin-right:10px;">
            
            
            
                 <a href="<?php echo base_url(); ?>project/project_detail/<?php echo $project->id; ?>"> 
                
                
                <img width="50" src="<?php echo base_url(); ?>images/btn_detail_plus.png"/>
                </a> 

                
           
            </div>
     </div>
       
    </div>
     <?php } ?>
    <?php	} ?>	
</div>
</div>
            
 </div>
 </div>           
            
</div>

 



			
			
			
	