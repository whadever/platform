<?php if(isset($massage)) echo $message;  

//print_r($developments); 

$ci = &get_instance();
$ci->load->model('report_model');

for($i = 0; $i < count($developments); $i++)
{

	$dev_milestone_info = $ci->report_model->get_development_milestone_info($developments[$i]->id);

	//print_r($dev_milestone_info );

	for($j = 0; $j < count($dev_milestone_info); $j++ )
	{

		$dev_milestone_info[$j]->milestone_select_color;
		$milestone_id = $dev_milestone_info[$j]->id;
		$phases = $dev_milestone_info[$j]->milestone_phases;
		$milestone_select_color = $dev_milestone_info[$j]->milestone_select_color;

		$milestone_phase_data = $ci->report_model->get_development_milestone_phase_info($milestone_id,$phases);

		for($k = 0; $k < count($milestone_phase_data); $k++ )
		{
	
			$graph_start_date[] = $milestone_phase_data[$k]->start_date;
			$graph_end_date[] =  $milestone_phase_data[$k]->end_date;

		}
			

	}


}


if (in_array('0000-00-00', $graph_start_date)) 
{   
    $graph_start_date_without_zero = array_diff($graph_start_date, array('0000-00-00'));
}else{
    $graph_start_date_without_zero =$graph_start_date;
}


//$g_start_date = min($graph_start_date_without_zero);
$g_start_date='2006-01-01';
$g_end_date = max($graph_end_date);

$start_year = date("Y",strtotime($g_start_date));
$end_year = date("Y",strtotime($g_end_date));

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


//echo $category;





?>



<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1/jquery.min.js"></script>

<script type="text/javascript" src="fusioncharts/fusioncharts.gantt.js"></script>

<script type="text/javascript" src="fusioncharts/fusioncharts.js"></script>
<script type="text/javascript" src="fusioncharts/fusioncharts.charts.js"></script>
<script type="text/javascript" src="fusioncharts/fusioncharts.powercharts.js"></script>

<script type="text/javascript" src="fusioncharts/fusioncharts.maps.js"></script>
<script type="text/javascript" src="fusioncharts/fusioncharts.widgets.js"></script>
<script type="text/javascript" src="fusioncharts/themes/fusioncharts.theme.fint.js"></script>
<script type="text/javascript">
FusionCharts.ready(function () {
    var ganttChart = new FusionCharts({
        "type": "gantt",
        "renderAt": "chartContainer",
        "width": "1000",
        "height": "1000",
        "dataFormat": "json",
        "dataSource":  {
			
    "chart": {
        "canvasbgcolor": "F1F1FF, FFFFFF",
        "canvasbgangle": "90",
        "dateformat": "dd/mm/yyyy",
        "ganttlinecolor": "0372AB",
        "ganttlinealpha": "9",
        "gridbordercolor": "0372AB",
        "canvasbordercolor": "0372AB",
        "showshadow": "0",
        "taskbarfillmix": "{light-10}",
        "showborder": "0"
    },
    "categories": [
        {
            "bgcolor": "0372AB",
            "category": [
                {
                    "start": "<?php echo date("d/m/Y", strtotime($g_start_date)); ?>",
                    "end": "<?php echo date("d/m/Y", strtotime($g_end_date)); ?>",
                    "name": "",
                    "fontcolor": "FFFFFF"
                }
            ]
        },
        {
            "bgalpha": "0",
            "category": [
				<?php echo $category; ?>
            ]
        }
    ],
    "processes": {
        "isbold": "1",
        "headerbgcolor": "0372AB",
        "fontcolor": "0372AB",
        "bgcolor": "FFFFFF",
        "process": [

		<?php

			for($i = 0; $i<count($developments); $i++)
			{
		?>

            {
                "name": "<?php echo $developments[$i]->development_name; ?>",
                "id": "<?php echo $developments[$i]->id; ?>"
            },
		<?php } ?>


        ]
    },
    "tasks": {
        "task": [


			<?php

			for($i = 0; $i<count($developments); $i++)
			{

				$dev_milestone_info = $ci->report_model->get_development_milestone_info($developments[$i]->id);
				
				for($j = 0; $j < count($dev_milestone_info); $j++ )
				{

					$milestone_id = $dev_milestone_info[$j]->id;
					$phases = $dev_milestone_info[$j]->milestone_phases;
					$milestone_select_color = $dev_milestone_info[$j]->milestone_select_color;

					if($milestone_select_color == '#a1d49c'){$color_name = 'Urban Plan Concept';}
					else if($milestone_select_color == '#7bcbc8'){$color_name = 'Consultation';}
					else if($milestone_select_color == '#fff79a'){$color_name = 'Building Design';}
					else if($milestone_select_color == '#ff9899'){$color_name = 'Working Drawings';}
					else if($milestone_select_color == '#ffd799'){$color_name = 'Resource Consent';}
					else if($milestone_select_color == '#da99ff'){$color_name = 'Building Permits';}
					else if($milestone_select_color == '#c69c6c'){$color_name = 'Development Construction';}
					else if($milestone_select_color == '#cccccc'){$color_name = 'Construction';}
					else if($milestone_select_color == '#a0ff7f'){$color_name = 'Completion';}
					else if($milestone_select_color == '#464646'){$color_name = 'Titles Due';}
					else{$color_name = '';}
	
					
					$milestone_phase_data = $ci->report_model->get_development_milestone_phase_info($milestone_id,$phases);
				
					$phase_text = $ci->report_model->get_milestone_phase_data($phases,$developments[$i]->id);

					for($k = 0; $k < count($milestone_phase_data); $k++ )
					{
	
					?>
	
	
						{
		                "name": "Urban Plan Concept",
		                "processid": "<?php echo $developments[$i]->id; ?>",
		                "start": "<?php echo $milestone_phase_data[$k]->start_date; ?>",
		                "end": "<?php echo $milestone_phase_data[$k]->end_date; ?>",
		                "taskid": "B",
		                "bordercolor": "<?php echo $dev_milestone_info[$j]->milestone_select_color; ?>",
		                "color": "<?php echo $dev_milestone_info[$j]->milestone_select_color; ?>",
						"tooltext": "<?php echo $developments[$i]->development_name; ?> - <?php echo $color_name; ?> {br}<?php echo date("d F Y", strtotime($milestone_phase_data[$k]->start_date)); ?>  -<?php echo date("d F Y", strtotime($milestone_phase_data[$k]->end_date));  ?> {br} {br} Phases: {br} <?php echo $phase_text; ?> "
		            },

			
				<?php
					
					}
						
				
				}


			}

		?>

        ]
    },
    
    "legend": {
        "item": [
            {
                "label": "Urban Plan Concept",
                "color": "A1D49C"
            },
            {
                "label": "Consultation",
                "color": "7BCBC8"
            },
			{
                "label": "Building Design",
                "color": "FFF79A"
            },
			{
                "label": "Working Drawings",
                "color": "FF9899"
            },
			{
                "label": "Resource Consent",
                "color": "FFD799"
            },
			{
                "label": "Building Permits",
                "color": "DA99FF"
            },
			{
                "label": "Development Construction",
                "color": "C69C6C"
            },
			{
                "label": "Construction",
                "color": "CCCCCC"
            },
			{
                "label": "Completion",
                "color": "A0FF7F"
            },
            {
                "label": "Titles Due",
                "color": "000000"
            }
        ]
    }
}
    
    
    
    
    

    });

    ganttChart.render();
});
</script>

<div class="clear"></div>

<div class="all-title">
   <?php // echo $title; ?>
</div>
<div style="width:100%; text-align:center">
<div id="chartContainer">FusionCharts XT will load here!</div>
</div>


                  

			
			
			
	