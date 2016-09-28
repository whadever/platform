
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
        $('.hiders').toggle();
    });
            
 });
</script>
<?php 
            $user=  $this->session->userdata('user');  
            $user_role_id =$user->rid; 
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





<div class="breadcrumb-box">
<?php

  
$this->breadcrumbs->push('Projects', 'project/project_list');
$this->breadcrumbs->push($project_title, 'project/project_detail/'.$project_id);
$this->breadcrumbs->push('Track Hours', 'project/project_hours/'.$project_id);
echo $this->breadcrumbs->show();  
?>
</div>

<div class="content-inner"> 
	<div class="row">
    	<div class="col-md-12">  
			<div id="project_list_view" class="">

				<!-- <div style="background: #F5F5F5;display: block; overflow: hidden; padding:0 2%;">
                    <div style="float: left; width:16%;"> <h4> Contarctor Name</h4>  </div>
                    <div style="float: left; width:16%;"> <h4> Date </h4>  </div>
					<div style="float: left; width:16%;"> <h4> Start Time </h4>  </div>
					<div style="float: left; width:16%;"> <h4> Finish Time </h4>  </div>
					<div style="float: left; width:16%;"> <h4> Break Time </h4>  </div>
					<div style="float: left; width:16%;"> <h4> Notes </h4>  </div>
                    <div class="clear"></div>
                </div> -->

				<div class="row">
            		<div class="col-xs-12 col-sm-12 col-md-12">
						<table class="table table-bordered">
							<tr style="border-bottom: 1px solid #000;">
				                <th>Contractor Name</th>
				                <th> Date </th>
				                <th> Start Time </th>
				                <th> Finish Time </th>
				                <th> Break Time </th> 
								<th> Total Time </th> 
								<th> Notes </th>                
				            </tr>
						<?php 
								$project_total_time = 0;
								foreach ($projects_hours as $project_hour){ 
						?>
							<tr>
				                <td><?php echo $project_hour->username;?> </td>
				                <td><?php echo $project_hour->day;?></td>
				                <td><?php echo $project_hour->start_time;?></td>
				                <td><?php echo $project_hour->finish_time;?></td>
				                <td><?php echo $project_hour->break_time;?> </td>
								<td><?php 
											$seconds = strtotime($project_hour->finish_time) - strtotime($project_hour->start_time); 
											$minutes = $seconds/60;
											$total_minutes = $minutes -$project_hour->break_time;
	
											$project_total_time = $project_total_time + $total_minutes; 
											$working_minutes = $total_minutes % 60;
											$working_hours = ($total_minutes - $working_minutes)/60;
											echo $working_hours." hr ".$working_minutes." min";
											
									?> 
								</td>
								<td><?php echo $project_hour->note;?> </td>
				            </tr>
						<?php } ?>
							<tr><td></td><td></td><td></td><td></td><td><b>Total Project Time</b></td><td><b>
								<?php 
										$total_project_mints = $project_total_time % 60;
										$total_project_hours = ($project_total_time - $total_project_mints)/60;
										echo $total_project_hours." Hours and ".$total_project_mints." Minutes";
								 ?>
								</b></td><td></td>
							</tr>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
						

<!-- <div id="accordion">	

    <?php
    //print_r($projects_hours);

    foreach ($projects_hours as $project_hour){ ?>
   
            <h3 style="margin-top: 0px;"> 
                    <span style="float: left; width:50%;"> <?php echo $project_hour->task_title; ?></span>
                    <span> <?php echo $project_hour->hour.' hour '.$project_hour->minute.' minute';?></span>
            </h3>
	
    <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-12">
        <table class="table table-responsive">
            <tr style="border-bottom: 1px solid #000;">
                <th>Contractor <br/>or Manager </th>
                <th> Users </th>
                <th> Hours </th>
                <th> Week Starting </th>
                <th> Notes </th>                
            </tr>
            <tr>
                <td><?php echo $project_hour->username;?> </td>
                <td><?php echo $project_hour->username;?></td>
                <td><?php echo $project_hour->hour.' hour '.$project_hour->minute.' minute';?></td>
                <td><?php echo $project_hour->week_start_date;?></td>
                <td><?php echo $project_hour->note;?> </td>
            </tr>
            
        </table>
        </div>
        
        
    </div> 
    <?php	}  ?>
    
</div>
                <div>
                    <?php
                    //print_r($projects_total_hours);
     //[total_hours] => 3 [total_minutes] => 60 
    $total_hours= $projects_total_hours->total_hours;
     $total_minutess= $projects_total_hours->total_minutes;
    $extra_hour= (int)($total_minutess/60);
    $minute_reminder = $total_minutess%60;
    $sprint_minute_reminder = sprintf("%02d", $minute_reminder);
    echo '<h3>Total Hours : '.($total_hours+$extra_hour).' h '.$sprint_minute_reminder.' m</h3>';
                    ?>
                </div>
</div>
            
 </div>
 </div>           
            
</div> -->

 



			
			
			
	