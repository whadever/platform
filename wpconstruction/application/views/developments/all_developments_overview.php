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

.modal-scrollable .modal {
	border: 1px solid #000;
	width: auto;
}
.modal-scrollable .modal.fade.in{
	border: none;
}
.shake {
	animation-name: none;
}
ul.ui-autocomplete {
	z-index: 1100;
}
.ui-dialog
{
	z-index: 1060;
}
select {
	display: inline-block;
	vertical-align: middle;
	width: auto;
	background-color: #fff;
	background-image: none;
	border: 1px solid #ccc;
	border-radius: 4px;
	box-shadow: 0 1px 1px rgba(0, 0, 0, 0.075) inset;
	color: #555;
	font-size: 14px;
	height: 34px;
	line-height: 1.42857;
	padding: 6px 12px;
	transition: border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s;
}
#overlay {
	background-color: #000;
	/*background-image: url("<?php echo base_url(); ?>images/ajax-loading.gif");*/
	background-position: 50% center;
	background-repeat: no-repeat;
	height: 100%;
	left: 0;
	opacity: 0.5;
	position: fixed;
	top: 0;
	width: 100%;
	z-index: 10000;
}
#overlay_text {
	background-color: white;
	border-radius: 10px;
	color: black;
	left: 50%;
	margin-left: -100px;
	opacity: 1;
	padding: 20px;
	position: absolute;
	text-align: center;
	top: 50%;
	width: 200px;
	z-index: 10000;
}
</style>


<div class="task-phase-add">
	<ul class="drag-phase-task">
		<li style="float:right">
			<span style="height:20px; width:20px; border-radius:15px; background-color:red">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Overdue, 
			<span style="height:20px; width:20px; border-radius:15px; background-color:grey">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Pending, 
			<span style="height:20px; width:20px; border-radius:15px; background-color:yellow">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Underway, 
			<span style="height:20px; width:20px; border-radius:15px; background-color:green">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Complete. &nbsp;&nbsp;&nbsp;&nbsp;</li>
		<!--<li style="float: right">
			<button class="btn btn-default" id="download" style="float: right; margin: -8px 14px 7px 0">Export to PDF</button>
		</li>-->
	</ul>
	<div style="clear:both;"></div>
</div>

<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.js"></script>

<!--task #4432-->
<script src="<?php echo base_url();?>js/saveSvgAsPng.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/svg_to_pdf.js"></script>

<?php
function getDateForSpecificDayBetweenDates($startDate, $endDate, $weekdayNumber)
{
    $startDate = strtotime($startDate.' - 7 days');
    $endDate = strtotime($endDate.' + 7 days');

    $dateArr = array();

    do
    {
        if(date("w", $startDate) != $weekdayNumber)
        {
            $startDate += (24 * 3600); // add 1 day
        }
    } while(date("w", $startDate) != $weekdayNumber);


    while($startDate <= $endDate)
    {
        $dateArr[] = date('d-m-Y', $startDate);
        $startDate += (7 * 24 * 3600); // add 7 days
    }

    return($dateArr);
}

?>

<!--task #4438-->
<?php if(count($development_overview_info_all_jobs) > 2): ?>
	<script>
		$(document).ready(function(){
			var overlay = jQuery('<div id="overlay"></div>');
			var overlay_text = jQuery('<div id="overlay_text">Loading, this may take a while</div>');
			overlay.appendTo(document.body);
			overlay_text.appendTo(document.body);
		});
		$(window).load(function() {
			// executes when complete page is fully loaded, including all frames, objects and images
			$("#overlay").remove();
			$("#overlay_text").remove();
		});
	</script>
<?php endif; ?>

<?php

foreach ($development_overview_info_all_jobs as $development_overview_info_all) {

	if (!$development_overview_info_all->parent_unit) {
		print_chart($development_overview_info_all, $this);
	}

	/*now printing the child units (if any)*/
	foreach ($development_overview_info_all_jobs as $development_overview_info_all2) {

		if($development_overview_info_all2->parent_unit == $development_overview_info_all->id){
			print_chart($development_overview_info_all2, $this);
		}
	}


}

function print_chart($development_overview_info_all, $obj, $show_construction_phase_column = ''){
	$development_id = $development_overview_info_all->id;
	$development_name = $development_overview_info_all->development_name;

	if($development_overview_info_all->parent_unit){
		$parent = $obj->db->get_where('construction_development',array('id' => $development_overview_info_all->parent_unit),1,0)->row()->development_name;
		$development_name .=" ({$parent})";
	}

	$obj->db->select("phase.*, job.development_name");
	$obj->db->join('construction_development job', "job.id = phase.development_id");
	//$this->db->where('development_id', $development_id);
	$where = "development_id = {$development_id} ";

	if($development_overview_info_all->parent_unit){
		$where = "(development_id = {$development_id} AND construction_phase <> 'pre_construction') ";
		$where .= "OR (development_id = {$development_overview_info_all->parent_unit} AND construction_phase = 'pre_construction') ";

	}
	$obj->db->where($where);

	$obj->db->order_by("FIELD(construction_phase, 'pre_construction', 'construction', 'post_construction')");
	$development_overview_info = $obj->db->get('construction_development_phase phase')->result();

	$obj->db->select("templates.name, milestone.*");
	$obj->db->join("construction_milestone_templates templates", "templates.id = milestone.milestone_template_id");
	$milestones = $obj->db->get_where('construction_development_milestones milestone', array('job_id' => $development_id))->result();
	?>

	<?php

	foreach ($development_overview_info as $phase_info) {

		$graph_start_date[] = $phase_info->planned_start_date;
		$graph_end_date[] = $phase_info->planned_finished_date;
	}


	if (in_array('0000-00-00', $graph_start_date)) {
		$graph_start_date_without_zero = array_diff($graph_start_date, array('0000-00-00'));
	} else {
		$graph_start_date_without_zero = $graph_start_date;
	}


	$g_start_date = min($graph_start_date_without_zero);
	$g_end_date = max($graph_end_date);


	$dateArr = getDateForSpecificDayBetweenDates($g_start_date, $g_end_date, 1);


	$start_year = date("Y", strtotime($g_start_date));
	$end_year = date("Y", strtotime($g_end_date));

	if ($start_year != '-0001' or $end_year != '-0001') {

		if ($start_year == $end_year || 1) {

			$yearArr = array();
			$monthArr = array();
			$category_year = "";
			$category_month = "";
			$years = array();
			$months = array();

			for ($i = 0; $i < count($dateArr); $i++) {
				/*formatting the label*/
				$dt = date_create_from_format('d-m-Y', $dateArr[$i]);

				/*label for year*/
				if (!in_array($dt->format('Y'), $yearArr)) {
					$yearArr[] = $dt->format('Y');
					$years[] = $dt;
				}

				/*label for month*/
				if (!in_array($dt->format('m'), $monthArr)) {
					$monthArr[] = $dt->format('m');
					$months[] = $dt;
				}

				/*$label = $dt->format("Y-M-d");
                $label = str_replace("-","<br>",$label);*/
				$label = $dt->format('j');

				$category = '{';
				$category .= '"start": "' . $dateArr[$i] . '",';
				$category .= '"end": "' . $dateArr[$i + 1] . '",';
				$category .= '"label": "' . $label . '"';
				/*$category .= 	'"title": "'."dddd".'",';*/
				$category .= '},';
			}

			for ($i = 0; $i < count($years); $i++) {
				$category_year .= '{';
				$category_year .= '"start": "' . $years[$i]->format('d-m-Y') . '",';
				if (isset($years[$i + 1])) {

					$category_year .= '"end": "' . $years[$i + 1]->format('d-m-Y') . '",';
				} else {
					$category_year .= '"end": "",';
				}
				$category_year .= '"label": "' . $years[$i]->format('Y') . '",';
				$category_year .= '},';
			}

			for ($i = 0; $i < count($months); $i++) {
				$category_month .= '{';
				$category_month .= '"start": "' . $months[$i]->format('d-m-Y') . '",';
				if (isset($months[$i + 1])) {

					$category_month .= '"end": "' . $months[$i + 1]->format('d-m-Y') . '",';
				} else {
					$category_month .= '"end": "",';
				}
				$category_month .= '"label": "' . $months[$i]->format('M') . '",';
				$category_month .= '},';
			}

		} else {
			for ($i = $start_year; $i <= $end_year; $i++) {

				$year_start_date = date("Y-m-d", mktime(0, 0, 0, 1, 1, $i));
				$year_end_date = date("Y-m-d", mktime(0, 0, 0, 12, 31, $i));

				$category = '{';
				$category .= '"start": "' . $year_start_date . '",';
				$category .= '"end": "' . $year_end_date . '",';
				$category .= '"label": "' . $i . '"';
				$category .= '},';
			}
		}
	} else {

		$category = '{';
		$category .= '"start": "' . date("Y-m-d", strtotime("first day of this year")) . '",';
		$category .= '"end": "' . date("Y-m-d", strtotime("last day of this year")) . '",';
		$category .= '"label": "' . date("Y") . '"';
		$category .= '},';
	}
	?>

	<script type="text/javascript">
		/*FusionCharts.ready(function () { */
		$(document).ready(function () {
			var ganttChart = new FusionCharts({
				"type": "gantt",
				"renderAt": "chartContainer<?php echo $development_id; ?>",
				"width": <?php if(count($dateArr) < 15){ ?>"100%", <?php } else{ ?>"100%", <?php } ?>
				"height": "550",
				"dataFormat": "json",
				"ganttWidthPercent": "60",
				"dataSource": {
					<?php  if($show_construction_phase_column): ?>
					"datatable": {
						"datacolumn": [


							/*{
							 "text": [
							<?php

                                foreach($development_overview_info as $phase_info)
                                {
                                    echo '{ "label": "'.$phase_info->development_name.'"},';
                                }

                            ?>
							 ]
							 },*/

							{
								"text": [
									<?php

                                        foreach($development_overview_info as $phase_info)
                                        {
                                            echo '{ "label": "'.$phase_info->phase_name.'"},';
                                        }

                                    ?>
								]
							}

						]
					},
					<?php endif; ?>
					"chart": {
						//"dateformat": "mm/dd/yyyy",
						"dateformat": "dd-mm-yyy",
						"caption": "",

						"showslackasfill": "0",
						"showpercentlabel": "1",
						"showborder": "0",
						"theme": "fint",
						"showToolTip": "1",
						"scrollShowButtons": "1",
						"canvasBorderAlpha": "30",

						"scrollColor": "#CCCCCC",
						"scrollPadding": "2",
						"scrollHeight": "20",
						"scrollBtnWidth": "25",
						"scrollBtnPadding": "5",
						"labelDisplay": "rotate",
						"showFullDataTable": 0,
						"ganttWidthPercent": "80"
					},
					"categories": [

						{
							"category": [
								<?php echo $category_year; ?>

							]
						},

						{
							"category": [
								<?php echo $category_month; ?>

							]
						},
						{
							"category": [
								<?php echo $category; ?>

							]
						}
					],
					"processes": {
						"fontsize": "10",
						"isbold": "1",
						"align": "left",
						"headertext": "<?php echo $development_name; ?>",
						"headerfontsize": "11",
						"headervalign": "middle",
						"headeralign": "center",
						"process": [
							<?php

                            foreach($development_overview_info as $phase_info)
                            {

                                $lbl = "";
                                switch($phase_info->construction_phase){
                                    case "pre_construction": $lbl = "Pre Construction"; break;
                                    case "construction": $lbl = "Construction"; break;
                                    case "post_construction": $lbl = "Post Construction"; break;
                                }
                                echo '{ "label": "'.$lbl.'"},';
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

                                $start_time = strtotime($phase_info->planned_start_date);
                                $end_time = strtotime($phase_info->planned_finished_date);

                                $ci =&get_instance();
                                $ci->load->model('developments_model');
                                $phase_status_info = $ci->developments_model->get_all_development_phase_status($development_id,$phase_info->id)->result();

                                if($phase_status_info)
                                {
                                    $phase_status = $phase_status_info[0]->all_task_status;
                                }

                                if ($phase_info->phase_status  == '1')
                                {
                                    $block_color_arr = '008000';
                                }
                                else if ($today_time > $end_time && $phase_status  == '1')
                                {
                                    $block_color_arr = '008000';
                                }
                                else if ($today_time <= $end_time && $phase_status  == '1')
                                {
                                    $block_color_arr = '008000';
                                }
                                else if($today_time > $start_time && $today_time > $end_time && $phase_status  == '0')
                                {
                                    $block_color_arr = 'FF0000';
                                }
                                else if($today_time < $start_time  && $today_time < $end_time && $phase_status  == '0')
                                {
                                    $block_color_arr = '808080';
                                }
                                else if($today_time >= $start_time && $today_time <= $end_time && $phase_status  == '0')
                                {
                                    $block_color_arr = 'FFFF00';
                                }
                                else
                                {
                                    $block_color_arr = '';
                                }


                                echo '{ "start": "'.date("d-m-Y", strtotime($phase_info->planned_start_date)).'",';
                                echo ' "end": "'.date("d-m-Y", strtotime($phase_info->planned_finished_date)).'",';
                                echo ' "hovertext": "'.date("d M, Y", strtotime($phase_info->planned_start_date)).' - '.date("d M, Y", strtotime($phase_info->planned_finished_date)).'",';
                                echo ' "color": "'.$block_color_arr.'" },';
                            }
                            ?>
						]
					},
					"trendlines": [
						{
							"line": [

								<?php
                                foreach($milestones as $milestone)
                                {

                                    echo '{ "start": "'.date("d-m-Y", strtotime($milestone->date)).'",';
                                    echo ' "displayvalue": "'.$milestone->name.'<br>'.date("d F, Y", strtotime($milestone->date)).'<br>",';
                                    echo ' "thickness": "2",';
                                    echo ' "color": "CC0000"';
                                    //echo ' "tooltext": "ppppp" },';
                                    echo "},";
                                }

                                ?>
							]
						}
					]
				},
				events: {
					renderComplete: function () {
						//$('g a').attr('title','')
						$("#chartContainer > span >svg").css("height", '560px');
					}
				}
			});

			ganttChart.render();
		});
	</script>

	<div id="chartContainer<?php echo $development_id; ?>" style="overflow:scroll; height: 560px">Gantt will load here!</div>

	<?php
}
?>


