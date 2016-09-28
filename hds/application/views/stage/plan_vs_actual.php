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
        $actual_completion_array[]=$phase->actual_finished_date;
        $planned_finished_array[] =$phase->planned_finished_date;
		
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


$showing_grap='';
if (in_array("0000-00-00", $actual_completion_array)) {
    $actual_completion_array_count =  count($actual_completion_array);  
    $actual_completion_count_zero_value_array = array_count_values($actual_completion_array);
    $actual_completion_count_zero_value= $actual_completion_count_zero_value_array['0000-00-00'];
    if($actual_completion_count_zero_value >= $actual_completion_array_count){
        $showing_grap = 2;
    }
}
if (in_array("0000-00-00", $planned_finished_array)) {
    $planned_finished_array_count =  count($planned_finished_array);  
    $planned_finished_count_zero_value_array = array_count_values($planned_finished_array);
    $planned_finished_count_zero_value= $planned_finished_count_zero_value_array['0000-00-00'];
    if($planned_finished_count_zero_value >= $planned_finished_array_count){
        $showing_grap = 3;
    }
}     

if($showing_grap==2){
    $show_grap = 0;
    echo 'Enter Task actual completion date of phase of the stage to show the graph properly';
    
 }
if($showing_grap==3){
    $show_grap = 0;
    echo 'Enter Task Planned completion date of phase of the stage to show the graph properly';
    
    }





if( $show_grap == 1)
{

?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Task', 'Actual', 'Planned'],
          <?php foreach($phase_info as $phase){ 

			$day = 1;
			$day_add_str = " - ".$day." month";

			$actual_completion_date_30 = $phase->actual_finished_date; 
			$result = date_add(date_create($actual_completion_date_30), date_interval_create_from_date_string($day_add_str));
			$actual_phase_completion_date = date_format($result, 'Y, m, d');

			$phase_planned_finished_date_30 = $phase->planned_finished_date;
			$result_planne_finished_date = date_add(date_create($phase_planned_finished_date_30), date_interval_create_from_date_string($day_add_str));			
			$phase_planned_finished_date = date_format($result_planne_finished_date, 'Y, m, d');
			
		  ?>
          ['<?php echo $phase->phase_name?>', 
           <?php if($phase->actual_finished_date != '0000-00-00'){ ?>new Date(<?php echo $actual_phase_completion_date;?>), <?php } else{ echo ','; } ?>
           <?php if($phase->planned_finished_date != '0000-00-00'){ ?>new Date(<?php echo $phase_planned_finished_date;?>),<?php } else{ echo ','; } ?>],
          <?php
			
			} // end foreach ?>
         
        ]);

        var options = {
        		legend: {position: 'right'},
         // title: 'Company Performance',
		  pointSize: 10,
		  series: {
                0: { pointShape: 'circle'},
                1: { pointShape: 'triangle'}
               // 2: { pointShape: 'square'}
                
            },
            
        	//chartArea:{left:40,top:20,width:'80%', height:'90%'},
        	//orientation: 'vertical' 
        };
		

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));

        chart.draw(data, options);
      }
    </script>
<div id="stage_plan_vs_actual">

<div id="chart_div" style="width: 100%; height: 400px;"></div>

<?php 

} // if $show_grap = 1 condition 

?>

<?php if($show_grap == 0) { ?>

<div id="stage_plan_vs_actual" style="height:400px">	

	<div style="color:red; font-size:16px; padding-top:20px">The graph is not showing due to phase dates missing or incorrect</div>

<?php } ?>

</div>

