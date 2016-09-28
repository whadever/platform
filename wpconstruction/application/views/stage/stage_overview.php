<style>
#stage-overview{float:left; width:73%; margin-right:2%;}
#stage-overview-stage-status{
	float:left; 
	width:25%; 
	height:420px; 
	overflow-x:hidden; 
	text-align: center; 
	border:5px solid #004272; 
	border-radius:10px;
}
.stage-status-box{
	font-size:9px;
	border-bottom: 1px solid;
    margin-bottom: 10px;
    
}
.stage-status-box-top{margin-bottom:10px;}
</style>

<?php

$planned_start_date = array();
$planned_finished_date = array();

foreach($phase_info as $phase)
{

	if($phase->planned_start_date != '0000-00-00')
	{
	
		$planned_start_date[] = $phase->planned_start_date; 
	}

	if($phase->planned_finished_date != '0000-00-00')
	{
	
		$planned_finished_date[] = $phase->planned_finished_date; 
	}
		
}


$planned_start_dates = array_filter($planned_start_date);

$planned_finished_dates = array_filter($planned_finished_date);


if( empty($planned_start_dates) or empty($planned_finished_dates) )
{
	$show_grap = 0;
}
else
{
	$show_grap = 1;
}


if( $show_grap == 1)
{

?>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization',
       'version':'1','packages':['timeline']}]}"></script>
<script type="text/javascript">
google.setOnLoadCallback(drawChart);

function drawChart() {
  var container = document.getElementById('chart_div');

  var chart = new google.visualization.Timeline(container);

  var dataTable = new google.visualization.DataTable();

  dataTable.addColumn({ type: 'string', id: 'Lable' });
  dataTable.addColumn({ type: 'date', id: 'Start' });
  dataTable.addColumn({ type: 'date', id: 'End' });
  
  dataTable.addRows([
	<?php
		
		$block_color_arr = array();
		$now = date('Y-m-d');
		$today_time = strtotime($now);
		$day = 1;
		$day_deduct_str = " - ".$day." month";

		foreach($phase_info as $phase)
		{

			$phase_start_date_30 = $phase->planned_start_date; 
			$start_deduct_result = date_add(date_create($phase_start_date_30), date_interval_create_from_date_string($day_deduct_str));
			$phase_start_date = date_format($start_deduct_result, 'Y, m, d');

			$phase_end_date_30 = $phase->planned_finished_date; 
			$end_deduct_result = date_add(date_create($phase_end_date_30), date_interval_create_from_date_string($day_deduct_str));
			$phase_end_date = date_format($end_deduct_result, 'Y, m, d');


			$phase_start_date_time = strtotime($phase_start_date);
			$phase_end_date_time = strtotime($phase_end_date );


			$ci =&get_instance();
			$ci->load->model('stage_model');
			$phase_task_status_arr = $ci->stage_model->get_all_task_status($development_id,$stage_id,$phase->id)->result();

			$phase_task_status = $phase_task_status_arr[0]->all_task_status; 

			if ($today_time > $phase_end_date_time && $phase_task_status== '1' ) 
			{
				$block_color_arr[] = '#008000';
			}
			elseif ($today_time > $phase_end_date_time && $phase_task_status == '0' ) 
			{
				$block_color_arr[] = '#FF0000';
			}
			elseif ($today_time < $phase_end_date_time && $phase_task_status == '1' ) 
			{
				$block_color_arr[] = '#008000';
			}
			elseif($today_time < $phase_end_date_time && $phase_task_status == '0')
			{
				$block_color_arr[] = '#FFFF00';
			}
			else
			{
				$block_color_arr[] = '';
			}

		?>


		[ '<?php echo $phase->phase_name ?>',  	
	
		<?php if($phase->planned_start_date != '0000-00-00'){ ?>new Date(<?php echo $phase_start_date;?>), <?php } else{ echo ','; }  ?>
		<?php if($phase->planned_finished_date != '0000-00-00'){ ?>new Date(<?php echo $phase_end_date;?>), <?php } else{ echo ','; }  ?>],
	
  <?php }  ?>


]);

  var options = {
		  colors: [

					<?php foreach($block_color_arr as $block_color) { ?>

				'<?php echo $block_color; ?>',

				<?php } ?>],
		  
		    timeline: { 
			    groupByRowLabel: false , 
			    showRowLabels: true 
			},
		    backgroundColor: '#ECEBF0'
		  };
  chart.draw(dataTable, options);
}
</script>

<?php 

} // if $show_grap = 1 condition 

?>

<div id="stage-overview">

	<?php if($show_grap == 0) { ?>

	<div style="color:red; font-size:16px; padding-top:20px">The graph is not showing due to phase dates missing or incorrect</div>

	<?php } ?>

	<div id="extend_div" ></div>
	<div id="chart_div" style="height:420px"></div>
</div>



<div id="stage-overview-stage-status" class="stage-status-box" style="">
        <div class="box-title" style="margin-bottom: 10px;">Phase Status </div> 
        <div>
        	
        		<?php 	

						foreach($task_info as $task)
						{ 

							$etc = '';

							$days_left = 0;
							$task_start_date = $task->task_start_date;
							$day_required = $task->day_required;
							$task_end_date = date('Y-m-d', strtotime($task_start_date. ' + '.$day_required.' days'));

							$bg_color = '';
							$now = date('Y-m-d');
							$today_time = strtotime($now);
							$task_start_date_time = strtotime($task_start_date);
							$task_end_date_time = strtotime($task_end_date);

							$task_status = $task->stage_task_status;


							if($task_start_date == '0000-00-00' && $task_status == '0' ) 
							{
								$etc = 'Not start yet';
								$day_alert = " Days Before";
								$bg_color = 'yellow';
								$days_left = '';
							}
							elseif ($today_time > $task_end_date_time && $task_status == '1' ) 
							{
								$etc = 'Complete';
								$day_alert = " Days Before";
								$bg_color = 'green';
								$days_left = date_diff(date_create($now), date_create($task_end_date))->format("%R%a Days");
							}
							elseif ($today_time > $task_end_date_time && $task_status == '0' ) 
							{
								$etc = 'Late';
								$day_alert = " Days Overdue";
								$bg_color = 'red';
								$days_left = date_diff(date_create($now),date_create($task_end_date))->format("%R%a Days");
							}
							elseif ($today_time < $task_end_date_time && $task_status == '1' ) 
							{
								$etc = 'Complete';
								$day_alert = " Days Before";
								$bg_color = 'green';
								$days_left = date_diff(date_create($now),date_create($today_time))->format("%R%a Days");
							}
							elseif($today_time < $task_end_date_time && $task_status == '0')
							{
								$etc = 'Late';
								$day_alert = " Days Remaining";
								$bg_color = 'yellow';
								$days_left = date_diff(date_create($task_end_date), date_create($now))->format("%R%a Days");
							}
							else
							{
								$etc = 'Underway';
								$day_alert = '';
								$bg_color = 'yellow';
								$days_left = 0;
							}
							

				?>
        		
        			<div class=stage-status-box>
			        	<div class="stage-status-box-top">
			        		<div style="float:left"> 
			        			<div style="height: 15px;width:15px; border-radius:10px; background:<?php echo $bg_color; ?>;margin:5px;"></div> 
			        		</div>
			        		<div style="float:left"> <u><?php echo $task->task_name;?> </u></div>
			        		<div style="float:right;margin-right:5px;">
			        			<div>ETC: <?php echo $etc;?></div>
			        			<div>Days Left:<?php if($etc != 'Complete'){ echo $days_left; } ?></div>  			        			
			        		</div>
			        		<div class="clear"></div>
			        	</div>        	
			        	
			        </div>
        		
        		<?php } ?>
        	
        </div>
        
    </div>