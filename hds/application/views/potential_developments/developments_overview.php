<?php

foreach($development_overview_info as $phase_info)
{

	$graph_start_date[] = $phase_info->start_date;
	$graph_end_date[] = $phase_info->end_date;
	if( $phase_info->start_date > '0000-00-00')
	{
		if($phase_info->end_date > '0000-00-00')
		{
			$graph_end_date[] = $phase_info->end_date;
		}				
		else
		{
			$created_date = date_create($phase_info->start_date);
			$str = '21 days';
			$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
			$graph_end_date[] =  date_format($pcdate, 'd-m-Y');
		}
	}
}

foreach($stage_overview_info as $stage_info)
{ 

	$graph_start_date[] = $stage_info->start_date;
	$graph_end_date[] = $stage_info->end_date;
	if( $stage_info->start_date > '0000-00-00')
	{
		if($stage_info->end_date > '0000-00-00')
		{
			$graph_end_date[] = $stage_info->end_date;
		}				
		else
		{
			$created_date = date_create($phase_info->start_date);
			$str = '21 days';
			$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
			$graph_end_date[] =  date_format($pcdate, 'd-m-Y');
		}
	}

}

if (in_array('0000-00-00', $graph_start_date)) 
{   
    $graph_start_date_without_zero = array_diff($graph_start_date, array('0000-00-00'));
}else{
    $graph_start_date_without_zero =$graph_start_date;
}


$g_start_date = min($graph_start_date_without_zero);
$g_end_date = max($graph_end_date);

$start_year = date("Y",strtotime($g_start_date));
$end_year = date("Y",strtotime($g_end_date));


$category='';

if($start_year != '-0001' or $end_year != '-0001' )
{


for($i = $start_year ; $i<= $end_year; $i++ )
{
	
	$year_start_date = date("Y-m-d", mktime(0, 0, 0, 1, 1, $i));
	$year_end_date = date("Y-m-d", mktime(0, 0, 0, 12, 31, $i));
	
        $category .=    '{';
        $category .=    '"start": "'.$year_start_date.'",';
        $category .=	'"end": "'.$year_end_date.'",';
        $category .= 	'"label": "'.$i.'"';
        $category .= 	'},';
}

}

else
{

	$category .=    '{';
	$category .=    '"start": "'.date("Y-m-d",strtotime("first day of this year")).'",';
	$category .=	'"end": "'.date("Y-m-d",strtotime("last day of this year")).'",';
	$category .= 	'"label": "'.date("Y").'"';
	$category .= 	'},';
}



?>
<style>
#development-overview{float:left; width:73%; margin-right:2%;}
#devlopment-overview-stage-status{
	float:left; 
	width:25%; 
	height:420px; 
	overflow-x:hidden; 
	text-align: center; 
	border:5px solid #004272; 
	border-radius:10px;
}
.stage-status-box-top{margin-bottom:10px;}
.stage-status-box{
	font-size:9px;
	border-bottom: 1px solid;
    margin-bottom: 10px;
    
}
#development-overview .fullscreen {
  display: block;
  position: absolute;
  top: 0;
  left: 0;
  width: 1200px;
  height: 100%;
  z-index: 9999;
  margin: 0;
  padding: 0;
  background: inherit;
}
#chart_div{
	height:500px;
	width:1000px;
}

#chart_div div div div{
overflow-x:hidden !IMPORTANT;
overflow-y:hidden !IMPORTANT;
}


</style>

<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.gantt.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.charts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.powercharts.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.maps.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.widgets.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/themes/fusioncharts.theme.fint.js"></script>
<script type="text/javascript">
FusionCharts.ready(function () {
    var ganttChart = new FusionCharts({
        "type": "gantt",
        "renderAt": "chartContainer",
        "width": "1000px",
        "height": "450",
        "dataFormat": "json",
        "dataSource":  {
  
    "chart": {
        "dateformat": "mm/dd/yyyy",
        "caption": "",
        
        "showslackasfill": "0",
        "showpercentlabel": "1",
        "showborder": "0",
		"theme": "fint",
		"showToolTip": "1",
    },
    "categories": [
        
        {
            "category": [
                <?php echo $category; ?>
				
            ]
        }
    ],
    "processes": {
        "fontsize": "12",
        "isbold": "1",
        "align": "right",
        "headertext": "",
        "headerfontsize": "18",
        "headervalign": "bottom",
        "headeralign": "right",
        "process": [
			<?php

			foreach($development_overview_info as $phase_info)
			{ 
				echo '{ "label": "'.$phase_info->phase_name.'"},'; 
			}


			foreach($stage_overview_info as $stage_info)
			{
				echo '{ "label": " Stage '.$stage_info->stage_no.'"},'; 
			}

			?>
        ]
    },
    "tasks": {
        "task": [

			<?php

			$now = date('Y-m-d');
			$today_time = strtotime($now);

			foreach($development_overview_info as $phase_info)
			{ 
				$planned_finished_date = $phase_info->end_date;

				if( $phase_info->start_date > '0000-00-00')
				{
					if($phase_info->end_date > '0000-00-00')
					{
						$planned_finished_date = $phase_info->end_date;
					}				
					else
					{
						$created_date = date_create($phase_info->start_date);
						$str = '21 days';
						$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
						$planned_finished_date =  date_format($pcdate, 'd-m-Y');
					}
				}

				$planned_strat_date = $phase_info->start_date;

				$end_time = strtotime($planned_finished_date);

				$ci =&get_instance();
				$ci->load->model('potential_developments_model');
				$phase_status_info = $ci->potential_developments_model->get_all_development_phase_status($development_id,$phase_info->id)->result();
		
				if($phase_status_info)
				{
					$phase_status = $phase_status_info[0]->all_task_status;
				}	
				
				if ($today_time > $end_time && $phase_status  == '1') 
				{
					$block_color_arr = '008000';
				}
				elseif ($today_time < $end_time && $phase_status  == '1') 
				{
					$block_color_arr = '008000';
				}
				elseif($today_time > $end_time && $phase_status  == '0')
				{
					$block_color_arr = 'FF0000';
				}
				elseif($today_time < $end_time && $phase_status  == '0')
				{
					$block_color_arr = 'FFFF00';
				}
				else
				{
					$block_color_arr = '';
				} 


				echo '{ "start": "'.date("m/d/Y", strtotime($planned_strat_date)).'",'; 
				echo ' "end": "'.date("m/d/Y", strtotime($planned_finished_date)).'",';
				echo ' "color": "'.$block_color_arr.'" },'; 
			}

			foreach($stage_overview_info as $stage_info)
			{ 

				$end_time = strtotime($stage_info->end_date);

				$ci =&get_instance();
				$ci->load->model('potential_developments_model');
				$phase_status_info = $ci->potential_developments_model->get_all_phase_status($development_id,$stage_info->stage_no)->result();
		
				
				if($phase_status_info)
				{
					$phase_status = $phase_status_info[0]->aphase_status;
				}

				if ($today_time > $end_time && $phase_status  == '1') 
				{
					$block_color_arr = '008000';
				}
				elseif ($today_time < $end_time && $phase_status  == '1') 
				{
					$block_color_arr = '008000';
				}
				elseif($today_time > $end_time && $phase_status  == '0')
				{
					$block_color_arr = 'FF0000';
				}
				elseif($today_time < $end_time && $phase_status  == '0')
				{
					$block_color_arr = 'FFFF00';
				}
				else
				{
					$block_color_arr = '';
				}

				echo '{ "start": "'.date("m/d/Y", strtotime($stage_info->start_date)).'",'; 
				echo ' "end": "'.date("m/d/Y", strtotime($stage_info->end_date)).'",';
				echo ' "color": "'.$block_color_arr.'" },';

                 
			}

			?>
           
           
        ]
    },
    "trendlines": [
        {
            "line": [

			<?php 
			//foreach($development_milestone as $milestone_info)
			//{
			
				echo '{ "start": "'.date("m/d/Y", strtotime(date("Y-m-d"))).'",'; 
				echo ' "tooltext": "Today",';
				echo ' "displayvalue": " Today",';
				echo ' "thickness": "5",';
				echo ' "color": "FF0000", ';
				echo ' "tooltext": "Today" }';
			//}

			?>
            ]
        }
    ]
}
    });

    ganttChart.render();
});
</script>

  <div id="chartContainer" style=" overflow:scroll">Gantt will load here!</div>



