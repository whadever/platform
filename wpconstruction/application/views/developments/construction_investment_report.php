<?php
$user = $this->session->userdata('user');
$user_id = $user->uid;
$this->db->select('application_role_id');
$this->db->where('user_id',$user_id);
$this->db->where('application_id',5);
$app_role_id = $this->db->get('users_application')->row()->application_role_id;

$total_draw_down = 0;
$total_predicted_interest_earned = 0;
$total_current_interest_earned = 0;
foreach($investor_draw_down as $draw){ 
	$total_draw_down = $total_draw_down + $draw->draw_down_amount;
	$daysf = date_diff(date_create($draw->draw_date),date_create($investor_data->estimation_settlement_date));
	$current_daysf = date_diff(date_create($draw->draw_date),date_create(date("y-m-d")));

	$days = (int)$daysf->format("%a");
	$current_days = (int)$current_daysf->format("%a");
	
	$predicted_interest_earned = (($draw->draw_down_amount * $investor_data->interest_rate)/(100 * 365)) * $days; 
	$current_interest_earned = (($draw->draw_down_amount * $investor_data->interest_rate)/(100 * 365)) * $current_days; 

	$total_predicted_interest_earned = $total_predicted_interest_earned + $predicted_interest_earned;
	$total_current_interest_earned = $total_current_interest_earned + $current_interest_earned;
}
	
				

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

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tooltip.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.tooltip/jquery.tooltip.css">
<style>
.loading{visibility: hidden;}
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
.draw_down_box{margin-bottom:8px; height:35px;}
.task-phase-add{margin-top:50px;}
</style>
<script type="text/javascript" src="<?php echo base_url();?>fusioncharts/fusioncharts.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/saveSvgAsPng.js"></script>
<script>
$(document).ready(function(){

  $("#investor_download").click(function(){
    var i = 0;
    var frm = $("<form>",{
      'action': document.location.origin+'/wpconstruction/report/investor_report_pdf',
      'method': 'post',
    });
    frm.append($("<input>",{
      'name': 'job_id',
      'value': job_id
    }));
    while(i < $("svg").length){

      svgAsDataUri($("svg").get(i), {}, function(svg_uri) {

        frm.append($("<input>",{
          'name': 'svg[]',
          'value': svg_uri
        }));

        if(i+1 == $("svg").length){
          //download_pdf('all_jobs_overview.pdf', doc.output('dataurlstring'));
          frm.appendTo('body').submit().remove();
        }

      });

      i++;

    }

    //download_pdf('all_jobs_overview.pdf', doc.output('dataurlstring'));
  })
});
</script>


<script type="text/javascript">
	var  h = 75 + <?php echo count($development_overview_info) * 28; ?>;
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

					"showslackasfill": "0",
					"showpercentlabel": "1",
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
			events:{
				renderComplete: function(){
					//$('g a').attr('title','')
					$("#chartContainer > span >svg").css("height",'560px');
				}
			}
		});

		ganttChart.render();
	});
</script>
<form id="form_investor_data" method="post">
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<a style="margin-bottom: 10px;" class="btn btn-default pull-right" id="investor_download" role="button">Export Investor Report</a>
	</div>

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
					Agreed Funding Facility
					<input type="text" name="agreed_funding_facility" value="<?php if($investor_data->agreed_funding_facility){ echo '$'.number_format($investor_data->agreed_funding_facility);} ?>" <?php if($app_role_id > 1){ ?> readonly <?php } ?> class="form-control" placeholder="Numeric value with $ as a fix one"  >
					<?php if($app_role_id == 1){ ?> <img src="<?php echo base_url(); ?>images/ajax-saving.gif" class="loading"> <?php }else{ ?><br><?php } ?>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
					Estimated Settlement Date
					<input type="text" value="<?php if($investor_data->estimation_settlement_date!='0000-00-00' && $investor_data->estimation_settlement_date !=''){ echo date("d-m-Y", strtotime($investor_data->estimation_settlement_date)); } ?>" <?php if($app_role_id > 1){ ?> readonly <?php } ?>  class="form-control <?php if($app_role_id == 1){ ?>  jq_datepicker <?php } ?>" name="estimation_settlement_date" placeholder="">
					<?php if($app_role_id == 1){ ?> <img src="<?php echo base_url(); ?>images/ajax-saving.gif" class="loading"><?php }else{ ?><br><?php } ?>
				</div>
			</div>
		
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
					Interest Rate %
					<input type="text" value="<?php if($investor_data->interest_rate){ echo $investor_data->interest_rate;} ?>" <?php if($app_role_id > 1){ ?> readonly <?php } ?> class="form-control" placeholder="" name="interest_rate">
					<?php if($app_role_id == 1){ ?> <img src="<?php echo base_url(); ?>images/ajax-saving.gif" class="loading"><?php }else{ ?><br><?php } ?>
				</div>
			</div>

			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
					Predicted Interest Earned (Based on Estimated Settlement Date)
					<input type="text" id="predicted_interest_earned" value="<?php if($total_predicted_interest_earned){ echo "$".round($total_predicted_interest_earned,2); } ?>" class="form-control" readonly>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
					Current Interest Earned to Date
					<input type="text" id="current_interest_earned" value="<?php if($total_current_interest_earned){ echo "$".round($total_current_interest_earned,2); } ?>" class="form-control" readonly>
				</div>
			</div>


		</div> <!-- end half -->

		<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">

			<div id="draw_down" class="row">

				<div id="draw_down_header" style="margin-bottom:10px; height:15px;">
					<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
						<div style="border-bottom:1px solid #ccc;font-weight:bold">Date</div>
					</div>
					<div class="col-xs-10 col-sm-10 col-md-5 col-lg-5 col-xl-5">
						<div style="border-bottom:1px solid #ccc;font-weight:bold">Draw Downs</div>
					</div>
				</div>

				<?php 
				$total_draw_down = 0;
				foreach($investor_draw_down as $draw){ 
					$total_draw_down = $total_draw_down + $draw->draw_down_amount;
	
				?>

					<div class="draw_down_box">
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
							<input type="text" name="draw_date" value="<?php echo date("d-m-Y", strtotime($draw->draw_date));?>" <?php if($app_role_id > 1){ ?> readonly <?php } ?> <?php if($app_role_id == 1){ ?> onchange="update_draw_amount(this)" <?php } ?> class="form-control <?php if($app_role_id == 1){ ?> jq_datepicker <?php } ?>" placeholder="">
							<?php if($app_role_id == 1){ ?><img src="<?php echo base_url(); ?>images/ajax-saving.gif" class="loading"><?php } ?>
						</div>
						<div class="col-xs-10 col-sm-10 col-md-5 col-lg-5 col-xl-5">
							<input type="text" name="draw_down_amount" value="<?php echo "$".number_format($draw->draw_down_amount); ?>" <?php if($app_role_id > 1){ ?> readonly <?php } ?> <?php if($app_role_id == 1){ ?> onblur="update_draw_amount(this)" <?php } ?> class="form-control draw" placeholder=""> 
							<?php if($app_role_id == 1){ ?><img src="<?php echo base_url(); ?>images/ajax-saving.gif" class="loading"><?php } ?>
						</div> 
		 				<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
							<?php if($app_role_id == 1){ ?><span id="delete_draw_down" onclick="delete_row(this)" style="font-size:30px">x</span> <?php } ?>
						</div> 
						<input type="hidden" class="draw_down_id"  name="draw_down_id" value="<?php echo $draw->id; ?>" />
					</div>
				<?php } ?>

				<?php if($app_role_id == 1): ?>
					<div class="draw_down_box">
						<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
							<input type="text" name="draw_date" value="" onchange="update_draw_amount(this)" <?php if($app_role_id > 1){ ?> readonly <?php } ?>  class="form-control <?php if($app_role_id == 1){ ?>  jq_datepicker <?php } ?>" placeholder="">
							<?php if($app_role_id == 1){ ?><img src="<?php echo base_url(); ?>images/ajax-saving.gif" class="loading"><?php } ?>
						</div>
						<div class="col-xs-10 col-sm-10 col-md-5 col-lg-5 col-xl-5">
							<input type="text" name="draw_down_amount" value="" onblur="update_draw_amount(this)" <?php if($app_role_id > 1){ ?> readonly <?php } ?>  class="form-control draw" placeholder=""> 
							<?php if($app_role_id == 1){ ?><img src="<?php echo base_url(); ?>images/ajax-saving.gif" class="loading"><?php } ?>
						</div> 
		 				<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2">
							<?php if($app_role_id == 1){ ?><span class="add_draw_down" style="font-size:30px">+</span><?php } ?>
						</div> 
						<input type="hidden" class="draw_down_id" name="draw_down_id" value="0" />
					</div>
				<?php endif; ?>
			</div> <!-- end #draw_down div -->



		</div> <!-- end half -->
		
	</div> <!-- end full -->


	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">

				<?php 
					$total_draw_down_percent = ($total_draw_down/$investor_data->agreed_funding_facility)*100;
					$rest_amount = $investor_data->agreed_funding_facility - $total_draw_down;
					$rest_draw = 100 - ( ($total_draw_down/$investor_data->agreed_funding_facility)*100); 
						
					$tooltip = "";
					$tooltip_class = "";
	
					$tooltip_title = "<span>Investor Report</span>";
					$tooltip = "<div class='tooltip_description' title='{$tooltip_title}' style='display:none;' ><br>Total Draw Downs = $".number_format($total_draw_down)."</div>";
					$tooltip_rest_amount = "<div class='tooltip_description' title='{$tooltip_title}' style='display:none;' ><br>Rest amount = $".number_format($rest_amount)."</div>";
	
				?>
				<br><br>
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
	 					<div id="total_agreed_fund" class="total_bar text-center " style="height:30px; width:100%; background-color:#197b30; border-radius:5px; <?php if($investor_data->agreed_funding_facility <= 0): ?> display:none; <?php endif; ?>">
						<div class="draw_down tool" style="float:left; width:<?php echo $total_draw_down_percent; ?>%;height:30px"><?php echo $tooltip; ?><div id="total_draw_down" style="color:white"><?php //if($total_draw_down_percent > 20){ ?>$<?php echo number_format($total_draw_down) ?><?php //}else{ ?>&nbsp;<?php //} ?></div></div>
						<div class="remaining tool text-center" style="height:30px; float:left; width:<?php echo $rest_draw; ?>%; background-color:#fbba00; border-radius:5px; float:right"><?php echo $tooltip_rest_amount; ?> <span id="rest_amount">$<?php echo number_format($investor_data->agreed_funding_facility - $total_draw_down); ?></span> </div>
					</div>
					<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
						<div id="total_agreed_fund_scale" class="row" <?php if($investor_data->agreed_funding_facility <=  0): ?> style="display:none"<?php endif; ?>>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-left">$0</div>
							<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 col-xl-6 text-right"><span id="agreed_fund_sacle">$<?php echo number_format($investor_data->agreed_funding_facility); ?></span></div>
						</div>
					</div>
					<input type="hidden" name="current_date" class="jq_datepicker" value="<?php echo date("y-m-d"); ?>">
					<br><br>
				</div>

			</div>
		</div>
	

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
		
		<div class="row">
			<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<textarea class="form-control" placeholder="Current Sales Plan" readonly ><?php echo $development_details->current_sales_plan ?></textarea>
				</div>
		
				<div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xl-6">
					<textarea class="form-control" placeholder="New Sales Plan (If Needed)" readonly ><?php echo $development_details->new_sales_plan ?></textarea>	
				</div>
			</div>
		</div>
			
	</div>

</div>
</form>

<div class="task-phase-add">
	<ul class="drag-phase-task">
		<li style="float:right">
			<span style="height:20px; width:20px; border-radius:15px; background-color:red">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Overdue, 
			<span style="height:20px; width:20px; border-radius:15px; background-color:grey">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Pending, 
			<span style="height:20px; width:20px; border-radius:15px; background-color:yellow">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Underway, 
			<span style="height:20px; width:20px; border-radius:15px; background-color:green">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Complete. &nbsp;&nbsp;&nbsp;&nbsp;</li>
	</ul>
	<div style="clear:both;"></div>
</div>

<div id="chartContainer" style="overflow:scroll; height: 560px">Gantt will load here!</div>

<script>
$(document).ready(function(){

	$("#chartContainer text").css("display", "none");

	$(document).on('focus', ".jq_datepicker", function(){
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
			//beforeShowDay: $.datepicker.noWeekends,
   		onClose: function(dateText, inst) 
   		{
          this.fixFocusIE = true;
          this.focus();
      	}
        });
    });


	$(".jq_datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy',
	});


});


      	window.Url = "<?php print base_url(); ?>";
      	var job_id = <?php echo "'{$development_details->id}'"; ?>;
      	var site_url = '<?php echo site_url(); ?>';
      	var update_uri = "report/update_investor_data";
		
		
      	function update(element){
			var field = $(element).prop('name');
			if(field == 'agreed_funding_facility'){
				var value = $(element).val().trim();
				value = value.replace("$",'');
				value = value.replace(/,/g,'');
			}else{
				var value = $(element).val().trim();
			}
			var total_draw_down = 0;
			var url = encodeURI(site_url+update_uri+"/"+job_id+"/"+field+"/"+value);
			$(element).siblings(".loading").css('visibility','visible');
			$.ajax(url,{
				success:function(data){
					if(data==1){
						if(field == 'agreed_funding_facility'){
							if(value>0){
								$("#total_agreed_fund").css('display','block');
								$("#total_agreed_fund_scale").css('display','block');
								$("#agreed_fund_sacle").html('$'+value);
								aff_value = Number(value).toLocaleString('en');
								$("input[name=agreed_funding_facility]").val("$"+aff_value);
							}else{
								$("#total_agreed_fund").css('display','none');
								$("#total_agreed_fund_scale").css('display','none');
								$("#agreed_fund_sacle").html('');
							}

							$('.draw').each(function(){
						   		val = $(this).val();
								if(val){
									val = val.replace("$",'');
									val = val.replace(/,/g, '');
									total_draw_down = parseInt(total_draw_down) + parseInt(val);
								}
							});
							
							var agreed_funding_facility = parseInt(value);
							rest_amount = agreed_funding_facility - total_draw_down;
	
							percent_draw_down = (total_draw_down / agreed_funding_facility) * 100;
							css_percent_draw_down = percent_draw_down + '%';
	
							parcent_rest_amount = 100 - percent_draw_down;
							css_parcent_rest_amount = parcent_rest_amount + '%';
	
							$(".tool").css('width',css_percent_draw_down);
							$(".remaining").css('width',css_parcent_rest_amount);
			
							total_draw_down = Number(total_draw_down).toLocaleString('en');
							$("#total_draw_down").html('$'+total_draw_down);
							$(".draw_down .tooltip_description").html("<br>Total Draw Downs = $"+total_draw_down);
	
							rest_amount = Number(rest_amount).toLocaleString('en');
							$("#rest_amount").html('$'+rest_amount);
							$(".remaining .tooltip_description").html("<br>Rest amount = $"+rest_amount);

						}
						$(element).siblings(".loading").css('visibility','hidden');
					}
				}
			});
		}

      	$("#form_investor_data input[type=text]").blur(function(){
          	update(this);
      	});

		$("#form_investor_data input[name=estimation_settlement_date].jq_datepicker").change(function(){
			update(this);
      	});

		/*$("#total_agreed_fund .tool").tooltip({
				'opacity' : 1,
		});*/

		var update_draw_downs = "report/update_investor_draw_down";
		function update_draw_amount(e){
			var field = $(e).prop('name');
			if(field == 'draw_down_amount'){
				var value = $(e).val().trim();
				value = value.replace("$",'');
				value = value.replace(/,/g,'');
			}else{
				var value = $(e).val().trim();
			}
			var total_draw_down = 0;
			var total_predicted_interest_earned = 0;
			var total_current_interest_earned = 0;

			draw_down_id = $(e).parent().parent().find('input[type=hidden]').val();
			var interest_rate = $('input[name=interest_rate]').val();
			var estimation_settlement_date = $('input[name=estimation_settlement_date]').val();
			$(e).siblings(".loading").css('visibility','visible');
			var url = encodeURI(site_url+update_draw_downs+"/"+job_id+"/"+field+"/"+value+'/'+draw_down_id);
			$.ajax(url,{
				success:function(data){
					if(data){
						$(e).parent().parent().find('input[type=hidden]').val(data);
						$(e).siblings(".loading").css('visibility','hidden');
						$('.draw').each(function(){
						   	val = $(this).val();
							if(val){
								val = val.replace("$",'');
								val = val.replace(/,/g, '');
								total_draw_down = parseInt(total_draw_down) + parseInt(val);
							}
						 });

						var agreed_funding_facility = $("input[name=agreed_funding_facility]").val();
						agreed_funding_facility = agreed_funding_facility.replace("$",'');
						agreed_funding_facility = agreed_funding_facility.replace(/,/g,'');
						agreed_funding_facility = parseInt(agreed_funding_facility);
						rest_amount = agreed_funding_facility - total_draw_down;

						percent_draw_down = (total_draw_down / agreed_funding_facility) * 100;
						css_percent_draw_down = percent_draw_down + '%';

						parcent_rest_amount = 100 - percent_draw_down;
						css_parcent_rest_amount = parcent_rest_amount + '%';

						$(".tool").css('width',css_percent_draw_down);
						$(".remaining").css('width',css_parcent_rest_amount);

						total_draw_down = Number(total_draw_down).toLocaleString('en');
						$("#total_draw_down").html('$'+total_draw_down);
						$(".draw_down .tooltip_description").html("<br>Total Draw Downs = $"+total_draw_down);

						rest_amount = Number(rest_amount).toLocaleString('en');
						$("#rest_amount").html('$'+rest_amount);
						$(".remaining .tooltip_description").html("<br>Rest amount = $"+rest_amount);

						// calculation of Predicted Interest Earned 	
						$('#draw_down .draw_down_box').each(function(){
						
								var start = $(this).find('input.jq_datepicker').datepicker("getDate");
					        	var end = $("input[name=estimation_settlement_date]").datepicker("getDate");
								var curr_end = $("input[name=current_date]").datepicker("getDate");
								var draw_amount = $(this).find('input[name=draw_down_amount]').val();
								
								if( start != null && draw_amount !='' ){
						        	var days = (end - start) / (1000 * 60 * 60 * 24);
									var curr_days = (curr_end - start) / (1000 * 60 * 60 * 24);

									draw_amount = draw_amount.replace("$",'');
									draw_amount = draw_amount.replace(/,/g, '');
									draw_amount = parseInt(draw_amount);
									
									predicted_interest_earned = ((draw_amount * interest_rate)/(100 * 365)) * days; 
									current_interest_earned =  ((draw_amount * interest_rate)/(100 * 365)) * curr_days;
	
									total_predicted_interest_earned = total_predicted_interest_earned + predicted_interest_earned;
									total_current_interest_earned = total_current_interest_earned + current_interest_earned;
								}
							
						 });
						$("#predicted_interest_earned").val("$"+total_predicted_interest_earned.toFixed(2));
						$("#current_interest_earned").val("$"+total_current_interest_earned.toFixed(2));

						if(field == 'draw_down_amount'){
							d_value = Number(value).toLocaleString('en');
							$(e).val("$"+d_value);
						}

					}
				}
			});
			
		}


	$(".add_draw_down").click(function(){
		$("#draw_down").append('<div class="draw_down_box"><div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5"><input type="text" name="draw_date" class="form-control jq_datepicker" placeholder="" onchange="update_draw_amount(this)"><img src="<?php echo base_url(); ?>images/ajax-saving.gif" class="loading"></div><div class="col-xs-10 col-sm-10 col-md-5 col-lg-5 col-xl-5"><input type="text" onblur="update_draw_amount(this)" name="draw_down_amount" class="form-control draw" placeholder=""><img src="<?php echo base_url(); ?>images/ajax-saving.gif" class="loading"></div><div class="col-xs-2 col-sm-2 col-md-2 col-lg-2 col-xl-2"><span id="delete_draw_down" onclick="delete_row(this)" style="font-size:30px">x</span></div><input type="hidden" class="draw_down_id" name="draw_down_id" value="0" /></div>');
	});

	function delete_row(e){
		draw_down_id = $(e).parent().parent().find('input[type=hidden]').val();
		var url = encodeURI(site_url+"report/delete_draw_down/"+draw_down_id+"/"+job_id);
		var total_draw_down = 0;
		var total_predicted_interest_earned = 0;
		var total_current_interest_earned = 0;
		var interest_rate = $('input[name=interest_rate]').val();
		var estimation_settlement_date = $('input[name=estimation_settlement_date]').val();
		$.ajax(url,{
				success:function(data){
					if(data){
						$(e).parent().parent().find('input[type=hidden]').val(data);
						$('.draw').each(function(){
						   	val = $(this).val();
							if(val){
								val = val.replace("$",'');
								val = val.replace(/,/g, '');
								total_draw_down = parseInt(total_draw_down) + parseInt(val);
							}
						 });
						var agreed_funding_facility = $("input[name=agreed_funding_facility]").val();
						agreed_funding_facility = agreed_funding_facility.replace("$",'');
						agreed_funding_facility = agreed_funding_facility.replace(/,/g,'');
						agreed_funding_facility = parseInt(agreed_funding_facility);

						rest_amount = agreed_funding_facility - parseInt(total_draw_down);

						percent_draw_down = (total_draw_down / agreed_funding_facility) * 100;
						css_percent_draw_down = percent_draw_down + '%';

						parcent_rest_amount = 100 - percent_draw_down;
						css_parcent_rest_amount = parcent_rest_amount + '%';

						$(".tool").css('width',css_percent_draw_down);
						$(".remaining").css('width',css_parcent_rest_amount);
						
						total_draw_down = Number(total_draw_down).toLocaleString('en');
						$("#total_draw_down").html('$'+total_draw_down);
						$(".draw_down .tooltip_description").html("<br>Total Draw Downs = $"+total_draw_down);

						rest_amount = Number(rest_amount).toLocaleString('en');
						$("#rest_amount").html('$'+rest_amount);
						$(".remaining .tooltip_description").html("<br>Rest amount = $"+rest_amount);

						// calculation of Predicted Interest Earned 	
						$('#draw_down .draw_down_box').each(function(){
						
								var start = $(this).find('input.jq_datepicker').datepicker("getDate");
					        	var end = $("input[name=estimation_settlement_date]").datepicker("getDate");
								var curr_end = $("input[name=current_date]").datepicker("getDate");
								var draw_amount = $(this).find('input[name=draw_down_amount]').val();
		
								if( start != null && draw_amount !='' ){
						        	var days = (end - start) / (1000 * 60 * 60 * 24);
									var curr_days = (curr_end - start) / (1000 * 60 * 60 * 24);
						
									
									draw_amount = draw_amount.replace("$",'');
									draw_amount = draw_amount.replace(/,/g, '');
									var predicted_interest_earned = ((parseInt(draw_amount) * interest_rate)/(100 * 365)) * days;
				
									var current_interest_earned =  ((parseInt(draw_amount) * interest_rate)/(100 * 365)) * curr_days;
		
									total_predicted_interest_earned = total_predicted_interest_earned + predicted_interest_earned;
									total_current_interest_earned = total_current_interest_earned + current_interest_earned;
								}							
						 });

						if(total_predicted_interest_earned>0){
							$("#predicted_interest_earned").val("$"+total_predicted_interest_earned.toFixed(2));
						}else{
							$("#predicted_interest_earned").val('');
						}
						if(total_current_interest_earned > 0){
							$("#current_interest_earned").val("$"+total_current_interest_earned.toFixed(2));
						}else{
							$("#current_interest_earned").val('');
						}

					}
				}
			});

		$(e).parent().parent().remove();
	}
</script>