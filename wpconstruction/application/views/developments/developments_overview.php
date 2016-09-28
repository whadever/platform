<?php
$user = $this->session->userdata('user');
$user_id = $user->uid;
$this->db->select('application_role_id');
$this->db->where('user_id',$user_id);
$this->db->where('application_id',5);
$app_role_id = $this->db->get('users_application')->row()->application_role_id;

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



foreach($development_overview_info as $phase_info)
{
	$graph_start_date[] = $phase_info->planned_start_date;
	$graph_end_date[] = $phase_info->planned_finished_date;
}


if (in_array('0000-00-00', $graph_start_date)) 
{   
    $graph_start_date_without_zero = array_diff($graph_start_date, array('0000-00-00'));
}else{
    $graph_start_date_without_zero = $graph_start_date;
}



$g_start_date = min($graph_start_date_without_zero);
$g_end_date = max($graph_end_date);



$dateArr = getDateForSpecificDayBetweenDates($g_start_date, $g_end_date, 1);



$start_year = date("Y",strtotime($g_start_date));
$end_year = date("Y",strtotime($g_end_date));

if($start_year != '-0001' or $end_year != '-0001' )
{

	if($start_year == $end_year || 1)
	{

		$yearArr = array();
		$monthArr = array();
		$category_year = "";
		$category_month = "";
		$years = array(); $months = array();

		for($i=0;$i<count($dateArr); $i++)
		{
			/*formatting the label*/
			$dt = date_create_from_format('d-m-Y',$dateArr[$i]);

			/*label for year*/
			if(!in_array($dt->format('Y'),$yearArr)){
				$yearArr[] = $dt->format('Y');
				$years[] = $dt;
			}

			/*label for month*/
			if(!in_array($dt->format('m'),$monthArr)){
				$monthArr[] = $dt->format('m');
				$months[] = $dt;
			}

			/*$label = $dt->format("Y-M-d");
			$label = str_replace("-","<br>",$label);*/
			$label = $dt->format('j');

			$category .=    '{';
			$category .=    '"start": "'.$dateArr[$i].'",';
			$category .=	'"end": "'.$dateArr[$i+1].'",';
			$category .= 	'"label": "'.$label.'"';
			/*$category .= 	'"title": "'."dddd".'",';*/
			$category .= 	'},';
		}

		for($i = 0; $i<count($years); $i++){
			$category_year .=    '{';
			$category_year .=    '"start": "'.$years[$i]->format('d-m-Y').'",';
			if(isset($years[$i+1])){

				$category_year .=	'"end": "'.$years[$i+1]->format('d-m-Y').'",';
			}else{
				$category_year .=	'"end": "",';
			}
			$category_year .= 	'"label": "'.$years[$i]->format('Y').'",';
			$category_year .= 	'},';
		}

		for($i = 0; $i<count($months); $i++){
			$category_month .=    '{';
			$category_month .=    '"start": "'.$months[$i]->format('d-m-Y').'",';
			if(isset($months[$i+1])){

				$category_month .=	'"end": "'.$months[$i+1]->format('d-m-Y').'",';
			}else{
				$category_month .=	'"end": "",';
			}
			$category_month .= 	'"label": "'.$months[$i]->format('M').'",';
			$category_month .= 	'},';
		}
	
	}
	else
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
</style>


<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.js"></script>
<!--
<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.gantt.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.charts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.powercharts.js"></script>

<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.maps.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.widgets.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/themes/fusioncharts.theme.fint.js"></script>
-->

<!--task #4432-->
<script src="<?php echo base_url();?>js/saveSvgAsPng.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/svg_to_pdf.js?a=<?php echo time(); ?>"></script>
<script>
	var job_id = <?php echo $this->uri->segment(3); ?>;
</script>

<!--task #4457-->
<script src="https://d3js.org/d3.v3.min.js" charset="utf-8"></script>

<script type="text/javascript">
	/*FusionCharts.ready(function () { */
	var  h = 550;
	var milestones = [];
	<?php


	foreach($milestones as $milestone)
	{
	?>
		milestones.push('<?php echo $milestone->name; ?>');
	<?php
	}

	?>
	<?php if($combined_overview): ?>
		var  h = 75 + <?php echo count($development_overview_info) * 28; ?>;
	<?php endif; ?>

	$(document).ready(function () {
		var ganttChart = new FusionCharts({
			"type": "gantt",
			"renderAt": "chartContainer",
			"width": <?php if(count($dateArr) < 15){ ?>"100%", <?php } else{ ?>"100%", <?php } ?>
			"height": h,
			"dataFormat": "json",
			"ganttWidthPercent": "60",
			"dataSource": {
				<?php  if($show_construction_phase_column): ?>
				"datatable": {
					"datacolumn": [

						<?php if($development_details->is_unit || $development_details->parent_unit): ?>
						{
							"text": [
								<?php

									foreach($development_overview_info as $phase_info)
									{
										if($phase_info->parent_unit && $phase_info->construction_phase == 'pre_construction') continue;
										echo '{ "label": "'.$phase_info->development_name.'"},';
									}

								?>
							]
						},
						<?php endif; ?>
						{
							"text": [
								<?php

									foreach($development_overview_info as $phase_info)
									{
										if($phase_info->parent_unit && $phase_info->construction_phase == 'pre_construction') continue;
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
						}

					]
				},
				<?php endif; ?>
				"chart": {
					//"dateformat": "mm/dd/yyyy",
					"dateformat": "dd-mm-yyy",
					"caption": "",
					/*"exportenabled": "1",
					 "exportatclient": "1",
					"exporthandler": "http://export.api3.fusioncharts.com",
					"html5exporthandler": "http://export.api3.fusioncharts.com",*/
					"showslackasfill": "0",
					"showpercentlabel": "1",
					"useVerticalScrolling": 0, // task #4432
					"showborder": "0",
					"theme": "fint",
					"showToolTip": "1",
					"scrollShowButtons": "1",
					"canvasBorderAlpha": "30",
					//"ganttPaneDuration": "3",
					//"ganttPaneDurationUnit": "m",
					"scrollColor": "#CCCCCC",
					"scrollPadding": "2",
					"scrollHeight": "20",
					"scrollBtnWidth": "25",
					"scrollBtnPadding": "5",
					"labelDisplay": "rotate",
					"showFullDataTable": 0,
					"ganttWidthPercent": 80
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
                        	if($phase_info->parent_unit && $phase_info->construction_phase == 'pre_construction') continue;

                            echo '{ "label": "'.$phase_info->phase_name.'"},';
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

                        	if($phase_info->parent_unit && $phase_info->construction_phase == 'pre_construction') continue;

                            $start_time = strtotime($phase_info->planned_start_date);
                            $end_time = strtotime($phase_info->planned_finished_date);

                            $ci =&get_instance();
                            $ci->load->model('developments_model');
                            if($phase_info->parent_unit && $phase_info->construction_phase != 'pre_construction'){

                            	$phase_status_info = $ci->developments_model->get_all_development_phase_status($phase_info->development_id,$phase_info->id)->result();
                            }else{

                            	$phase_status_info = $ci->developments_model->get_all_development_phase_status($development_id,$phase_info->id)->result();
                            }

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
                            elseif ($today_time <= $end_time && $phase_status  == '1')
                            {
                                $block_color_arr = '008000';
                            }
                            elseif($today_time > $start_time && $today_time > $end_time && $phase_status  == '0')
                            {
                                $block_color_arr = 'FF0000';
                            }
                            elseif($today_time < $start_time  && $today_time < $end_time && $phase_status  == '0')
                            {
                                $block_color_arr = '808080';
                            }
                            elseif($today_time >= $start_time && $today_time <= $end_time && $phase_status  == '0')
                            {
                                $block_color_arr = 'FFFF00';
                            }
                            else
                            {
                                $block_color_arr = '';
                            }


                            if($phase_info->planned_start_date == '0000-00-00'){
                            	echo "{},";
                            }else{
                            	echo '{ "start": "'.date("d-m-Y", strtotime($phase_info->planned_start_date)).'",';
                            	echo ' "end": "'.date("d-m-Y", strtotime($phase_info->planned_finished_date)).'",';
                            	echo ' "hovertext": "'.date("d M, Y", strtotime($phase_info->planned_start_date)).' - '.date("d M, Y", strtotime($phase_info->planned_finished_date)).'",';
                            	echo ' "color": "'.$block_color_arr.'" },';
                            }

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
                                echo ' "color": "CC0000" ';
                               	//echo ' "tooltext": "Quarterly sales target was"';
                                echo "},";
                            }

                            ?>
						]
					}
				]
			},
			events:{
				renderComplete: function(){
					//$('g a').attr('title','')
					//$("#chartContainer > span >svg").css("height",'560px');
					/*task #4457*/
					<?php if($_SERVER['SERVER_NAME'] != 'horncastle.wclp.co.nz'): ?>
					$("svg tspan").each(function(){

						var txt = $(this).text();

						if(milestones.indexOf(txt) != -1){

							var el = $(this).parent();

							var x = el.attr('x')-60;

							var y = el.attr('y')-50;

							el.attr('transform','rotate(270, '+x+', '+y+')');

						}

					})
					<?php endif; ?>

				}
			}
		});

		ganttChart.render();

	});
</script>
<div class="task-phase-add">
	<?php if($this->uri->segment(2) != 'construction_overview_all' && $app_role_id != 5 && $app_role_id != 3):?>
		<a href="#milestoneAdd" class="" style="float: left; margin-left: 14px;"   data-toggle="modal" data-backdrop="static">+ Add Milestone</a>
		<a href="#milestoneUpdate" class="" style="float: left; margin-left: 14px;"   data-toggle="modal" data-backdrop="static">- Remove Milestone</a>
	<?php endif; ?>
		<ul class="drag-phase-task">
			<li style="float:right">
				<span style="height:20px; width:20px; border-radius:15px; background-color:red">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Overdue, 
				<span style="height:20px; width:20px; border-radius:15px; background-color:grey">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Pending, 
				<span style="height:20px; width:20px; border-radius:15px; background-color:yellow">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Underway, 
				<span style="height:20px; width:20px; border-radius:15px; background-color:green">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Complete. &nbsp;&nbsp;&nbsp;&nbsp;</li>
			<?php if($combined_overview && $_SERVER['SERVER_NAME'] != 'horncastle.wclp.co.nz'): ?>
			<li style="float: right">
				<button class="btn btn-default" id="download" style="float: right; margin: -8px 14px 7px 0">Export to PDF</button>
			</li>
			<?php endif; ?>

		</ul>
		<div style="clear:both;"></div>
	</div>
<div id="chartContainer">Gantt will load here!</div>


<?php 
$domain = $_SERVER['SERVER_NAME'];
if($_GET['cp'] == 'construction' && $domain !='horncastle.wclp.co.nz' && $app_role_id != 3): ?>


<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
<?php 
$this->db->where('development_id', $development_id);
$this->db->where('permitted_user_group', '5');
$this->db->where('construction_phase', 'construction');
$result = $this->db->get('construction_development_documents')->result();
if($result):
?>
<p>
	<?php foreach($result as $row): ?>
	>><a target="_blank" href="<?php echo base_url(); ?>uploads/development/documents/<?php echo $row->filename; ?>">
		<?php echo $row->filename_custom; ?>
			</a>&nbsp;&nbsp;&nbsp;
	<?php endforeach; ?>
</p>
<?php 
endif;
?>
</div>


<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
	<textarea id="current_sales_plan" name="current_sales_plan" class="form-control" placeholder="Current Sales Plan" <?php if($app_role_id == 5){ ?> readonly <?php } ?> ><?php echo $development_details->current_sales_plan ?></textarea>
</div>

<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
	<textarea id="new_sales_plan" name="new_sales_plan" class="form-control" placeholder="New Sales Plan (If Needed)" <?php if($app_role_id == 5){ ?> readonly <?php } ?> ><?php echo $development_details->new_sales_plan ?></textarea>
</div>
<?php endif; ?>

<!--milestone modal-->
<div id="milestoneAdd" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
			<h3 id="myModalLabel">Add Milestone</h3>
		</div>
		<div class="modal-body">
			<form class="form-inline" action="<?php echo site_url('constructions/milestones/'.$development_id.'/add?cp='.$_GET['cp']); ?>" method="post">
				<select class="milestoneName" name="template_id" id="milestoneName">
					<?php foreach($milestone_templates as $ms): ?>
						<option value="<?php echo $ms->id; ?>"><?php echo $ms->name; ?></option>
					<?php endforeach; ?>
				</select>
				<input type="text" class="form-control datepicker" name="date"  placeholder="select date">
				<input class="form-control" type="submit" value="Add">
			</form>
		</div>
	</div>

</div>
<div id="milestoneUpdate" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
			<h3 id="myModalLabel">Remove Milestones</h3>
		</div>
		<div class="modal-body">
			<table style="min-width: 500px" class="table">
				<?php foreach($milestones as $milestone): ?>
					<?php if($milestone->job_id != $development_id || $milestone->construction_phase != $_GET['cp']) continue; ?>
					<tr>
						<td width="50%" style="vertical-align: middle"><h4><?php echo $milestone->name; ?></h4></td>
						<td width="45%" style="vertical-align: middle"><h4><?php echo $milestone->date; ?></h4></td>
						<td style="text-align: right; vertical-align: middle">
							<form class="form-inline" action="<?php echo site_url('constructions/milestones/'.$development_id.'/delete?cp='.$_GET['cp']); ?>" method="post">
								<input type="hidden" name="id" value="<?php echo $milestone->id; ?>">
								<button class="btn btn-danger delMilestone">delete</button>
							</form>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		</div>
	</div>

</div>
<script>
$(document).ready(function(){

	$("#chartContainer text").css("display", "none");

	$(".datepicker").datepicker({
		dateFormat: "yy-mm-dd"
	});

	$('.modal').on('shown.bs.modal', function () {
		setTimeout (function () {
			$("#milestoneName").focus();
		}, 1);
	});

	$(".delMilestone").click(function(event){
		var frm = $(this).parent();
		event.preventDefault();
		$("<div title='Delete Milestone'>Are you sure you want to delete this milestone?</div>").dialog({
			resizable: false,
			modal: true,
			width: 500,
			buttons: {
				"OK": function () {
					frm.submit();
				},
				"Cancel": function () {
					$(this).dialog("close");
				}
			}
		});
	})


	window.Url = "<?php print base_url(); ?>";
	var development_id = <?php echo "'{$development_details->id}'"; ?>;
	var site_url = '<?php echo site_url(); ?>';
	var update_uri = "constructions/update";
	

	function update(element){
		var field = $(element).prop('name');
		var value = $(element).val().trim();
		
		var url = encodeURI(site_url+update_uri+"/"+development_id+"/"+field+"/"+value);
		$(element).siblings(".loading").css('visibility','visible');
		$.ajax(url,{
			success:function(data){
				if(data==1){
					$(element).siblings(".loading").css('visibility','hidden');
				}
			}
		});

	}

      	$("#current_sales_plan").blur(function(){
          	update(this);
      	});

		$("#new_sales_plan").blur(function(){
          	update(this);
      	});

});
</script>

