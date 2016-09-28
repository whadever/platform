<?php

require('lib/gantti.php'); 

date_default_timezone_set('NZ');
setlocale(LC_ALL, 'en_US');

$data = array();






/* $gantti = new Gantti($data, array(
  'title'      => '',
  'cellwidth'  => 2,
  'cellheight' => 35,
    'today'      => false
)); */

//echo $gantti;

?>
<style>
#project-plan-overview .gantt aside .gantt-label strong{padding: 0 10px;}
#project-plan-overview .gantt figcaption {    
    left: 10px;    
    top: 15px;
}
#project-plan-overview .gantt aside{width:160px;}
#project-plan-overview .gantt-data{margin-left: 170px;}
#project-plan-overview header ul.gantt-days{display: none;}
#project-plan-overview ul.gantt-labels{margin-top: 34px;}
#project-plan-overview .gantt-day span{border-right: none;}
#project-plan-overview .gantt-day.weekend span{background: none;}
#project-plan-overview .gantt-data{overflow-x: auto;}


</style>
<?php 

foreach($task_info as $task){

	$bg_color = '';

	$now = date('Y-m-d');

	$day_required = $task->day_required;
	$task_start_dates = date_create($task->task_start_date);
	$day_required_str = $day_required.' days';
	$end_dates = date_add($task_start_dates, date_interval_create_from_date_string($day_required_str));
	$end_date =  date_format($end_dates, 'Y-m-d');

	$today_time = strtotime($now);
	$pc_time = strtotime($end_date);

	if ($today_time < $pc_time && $task->stage_task_status == '1' ) 
	{
		$day_sign = 'Finished ';
		$day_alert = " Days Before";
		$bg_color = 'green';
	}
	elseif ($today_time < $pc_time && $task->stage_task_status == '0' ) 
	{
		$day_sign = '';
		$day_alert = " Days Remaining";
		$bg_color = 'yellow';
	}
	elseif($today_time > $pc_time && $task->stage_task_status == '1')
	{
		$day_sign = '';
		$day_alert = " Days Over";
		$bg_color = 'green';
	}
	elseif($today_time > $pc_time && $task->stage_task_status == '0')
	{
		$day_sign = '';
		$day_alert = " Days Over";
		$bg_color = 'red';
	}
	elseif($today_time == $pc_time && $task->stage_task_status == '1')
	{
		$day_sign = '';
		$day_alert = '';
		$bg_color = 'green';
	}
	else
	{
		$day_sign = '';
		$day_alert = '';
		$bg_color = 'yellow';
	}

	

	$task_data[] = array(
  'label' => $task->task_name,
  'start' => date('Y-m-d', strtotime($task->task_start_date)), 
  'end'   => date('Y-m-d', strtotime($end_date)),
  'class' => $bg_color,
);
}

//print_r($task_data); exit;
?>

<div id="project-plan-overview">
		<?php
$gantti = new Gantti($task_data, array(
  'title'      => '',
  'cellwidth'  => 2,
  'cellheight' => 35,
    'today'      => false
));
	echo $gantti; 
	
?>
</div>
