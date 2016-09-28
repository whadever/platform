<style>
#maincontent{background:#fff;}
#milestone_overview{
	border: 4px solid #002855;
	border-radius:5px;
	margin-top:10px;
}
#milestone_overview h6{
	background: #002855;
	padding:5px;
	margin:0px;
	color:#fff;
	font-weight:bold;
}
.stone-block{
	height:20px; 
	float:left; 
	display:inline-block;
}
#accordion.ui-accordion .ui-accordion-icons{
	padding-left:1.8em;
}

</style>
<?php
if(isset($massage)) echo $message;  



$ci = &get_instance();
$ci->load->model('report_model');
//$ci->load->model('stage_model');
 ?>
<div class="all-title" style="text-align:center;">
   <img src="images/milestone-color.png" width="80%" title="Milestone Color Key" alt=""/>
</div>


<?php




$get = $this->input->get();
$get_date_to= $get['date_to'];
$get_date_from= $get['date_from'];


if($get_date_from==''){
	$date_from= date('Y-m-d');
	//$search_year_from='';
	$search_year_from=date('Y',  strtotime($date_from));
	$search_month_from=date('m',  strtotime($date_from));
	
}else{
	$date_from = date('Y-m-d',  strtotime($get_date_from));
	$search_year_from=date('Y',  strtotime($get_date_from));
	$search_month_from=date('m',  strtotime($get_date_from));
}
if($get_date_to==''){
	//$search_year_to='';
	$date_to = date('Y-m-d', strtotime("+13 months"));
	$search_year_to=date('Y',  strtotime($date_to));
	$search_month_to=date('m',  strtotime($date_to));
}else{
	$date_to = date('Y-m-d',  strtotime($get_date_to));
	$search_year_to=date('Y',  strtotime($get_date_to));
	$search_month_to=date('m',  strtotime($get_date_to));
}

?>

<div id="date-block" class="" style="background:#162E4D; text-align:center;padding:10px; width:100%;">
	<div class="" style="width:25%; float:left;"><h3>Select a Date Range</h3></div>
	<div class="" style="width:75%; float:left;">

	<form class="form-inline" name="date_select_from" method="get" action="" onsubmit="return checkForm(this);">

		<div class="form-group">
			<span>Date From </span>
			<input type="text" class="live_datepicker form-control" name="date_from" id="date_from" value="<?php if($get_date_from!=''){echo date('d-m-Y', strtotime($get_date_from));} ?>" placeholder="Milestone Start Date"/>
		</div>		
		<div class="form-group">
			<span>Date To </span>
			<input type="text" class="live_datepicker form-control" name="date_to" id="date_to" value="<?php if($get_date_to!=''){echo date('d-m-Y', strtotime($get_date_to));} ?>" placeholder="Milestone End Date"/>
		</div>
		
		<button type="submit" class="btn btn-default">Search</button>
		<a href="<?php echo base_url()?>report" class="btn btn-default">Clear </a>
		
	</form>
	</div>
	<div class="clear"></div>
</div>

<?php 

foreach($developments as $development){
		$stage_milestone_details = $ci->report_model->get_development_stage_milestone_detail($development->id)->result();
		$stage_start_date_array = array();
		$stage_end_date_array = array();
		$stage_days_difference = array();
	
		for($i=0; $i<count($stage_milestone_details); $i++){

			$date_arr = array();

			$stage = $ci->report_model->get_stage_detail($development->id, $stage_milestone_details[$i]->stage_no)->row();

			
			
			$stage_start_date_array[] = $stage->construction_start_date;

			
			if($stage_milestone_details[$i]->urban_plan_concept > $date_from && $stage_milestone_details[$i]->urban_plan_concept < $date_to)
			{
			
				$date_arr[] = $stage_milestone_details[$i]->urban_plan_concept;	
			}
			if($stage_milestone_details[$i]->consultation > $date_from && $stage_milestone_details[$i]->consultation < $date_to)
			{

				$date_arr[] = $stage_milestone_details[$i]->consultation;
			}

			if($stage_milestone_details[$i]->building_design > $date_from && $stage_milestone_details[$i]->building_design < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->building_design;
			}

			if($stage_milestone_details[$i]->working_drawings > $date_from && $stage_milestone_details[$i]->working_drawings < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->working_drawings;
			}

			if($stage_milestone_details[$i]->working_drawings_contractor > $date_from && $stage_milestone_details[$i]->working_drawings_contractor < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->working_drawings_contractor;
			}

			if($stage_milestone_details[$i]->resource_consent > $date_from && $stage_milestone_details[$i]->resource_consent < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->resource_consent;
			}

			if($stage_milestone_details[$i]->titles_due_out > $date_from && $stage_milestone_details[$i]->titles_due_out < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->titles_due_out;
			}

			if($building_permits[$i]->building_permits > $date_from && $stage_milestone_details[$i]->building_permits < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->building_permits;
			}

			if($building_permits[$i]->construction_earthworks > $date_from && $stage_milestone_details[$i]->construction_earthworks < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->construction_earthworks;
			}

			if($stage_milestone_details[$i]->construction_civil > $date_from && $stage_milestone_details[$i]->construction_civil < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->construction_civil;
			}
			if($stage_milestone_details[$i]->construction_roading > $date_from && $stage_milestone_details[$i]->construction_roading < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->construction_roading;
			}

			if($stage_milestone_details[$i]->construction_general > $date_from && $stage_milestone_details[$i]->construction_general < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->construction_general;
			}
			if($stage_milestone_details[$i]->completion > $date_from && $stage_milestone_details[$i]->completion < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->completion;
			}
			
				
			$stage_phase_days_length[$i]=array();
			$stage_phase_days_length[$i][] = $date_arr;

			$max = max(array_map('strtotime', $date_arr));
			$end_date =  date('Y-m-d', $max);

			$stage_end_date_array[] = $end_date;
			
			
			
		}
		if(!empty($stage_start_date_array)){

			$min = min(array_map('strtotime', $stage_start_date_array));
			$milestone_start_date =  date('Y-m-d', $min);

			$all_milestone_start_date[] = $milestone_start_date;
		}

		if(!empty($stage_end_date_array)){

			$max2 = max(array_map('strtotime', $stage_end_date_array));
			$milestone_end_date =  date('Y-m-d', $max2);
			$all_milestone_end_date[] = $milestone_end_date;
			
		}
		

		
		
	}
	$milestone_first_min = min(array_map('strtotime', $all_milestone_start_date));
	$milestone_first_start_date =  date('Y-m-d', $milestone_first_min);
	$milestone_first_start_year =  date('Y', $milestone_first_min);

	$milestone_end_max = max(array_map('strtotime', $all_milestone_end_date));
	$milestone_last_end_date =  date('Y-m-d', $milestone_end_max);
	$milestone_last_end_year =  date('Y', $milestone_end_max);

	




	
	if($search_year_from!= '' && $search_year_to!= '' ){
		$total_difference = abs(strtotime($date_to) - strtotime($date_from));
		$total_days_differecce = floor(($total_difference )/ (60*60*24));		
		$year_diff= $search_year_to - $search_year_from;	
		$month_diff = (($search_year_to - $search_year_from) * 12) + ($search_month_to - $search_month_from);

		if($month_diff<=12){
			$width_per = 100/($month_diff+1);
		}else{
			$width_per = 100/($month_diff);
		}
		

	}else{
		
		$total_difference = abs(strtotime($milestone_last_end_date) - strtotime($milestone_first_start_date));
		$total_days_differecce = floor(($total_difference )/ (60*60*24));		
		$year_diff= $milestone_last_end_year - $milestone_first_start_year;
		$width_per = 100/($year_diff*2+2);
	}

?>



<div id="accordion" class="report-color" style="background:rgba(0, 0, 0, 0.5) repeating-linear-gradient(to right, #cccccc, #cccccc 1px, #f2f2f2 1px, #f2f2f2 <?php echo $width_per;?>%) repeat scroll 0 25%;">
	<div style="width:100%; padding-left:25%;background-color:#e2e2e2;overflow:hidden;">

<?php 
	if($search_year_from!= '' && $search_year_to!= '' ){
		if($month_diff<=12){
			$month_array= array("JAN", "FEB", "MAR", "APRIL", "MAY", "JUN", "JULY", "AUG", "SEP", "OCT", "NOV", "DEC");
			$i=$search_year_from;
			$k= $search_month_from-1;
			for($j=0; $j<$month_diff+1; $j++)
			{	
				
				echo "<span style='float:left;width:$width_per%'>$month_array[$k] <br> $i</span>";
				$k++;
				if($k==12){$k=0;$i=$i+1;}
				//echo $k.'-';
			}
			
			
		}else{
			$month_array= array("JAN", "FEB", "MAR", "APRIL", "MAY", "JUN", "JULY", "AUG", "SEP", "OCT", "NOV", "DEC");
			$i=$search_year_from;
			$k= $search_month_from-1;
			for($j=0; $j<$month_diff; $j++)
			{	
				
				echo "<span style='float:left;width:$width_per%'>$month_array[$k] <br> $i</span>";
				$k++;
				if($k==12){$k=0;$i=$i+1;}
				//echo $k.'-';
			}
		}

	}else{
	
		for($i=$milestone_first_start_year; $i<=$milestone_last_end_year; $i++){
			echo "<span style='float:left;width:$width_per%'>JAN <br> $i</span>";
			//echo "<span style='float:left;width:$width_per%'>APRIL <br> $i</span>";
			echo "<span style='float:left;width:$width_per%'>JULY <br> $i</span>";
			//echo "<span style='float:left;width:$width_per%'>DEC <br> $i</span>";
		}
	}

?>
</div>
<?php 

	foreach($developments as $development){
		$stage_milestone_details = $ci->report_model->get_development_stage_milestone_detail($development->id)->result();
		$milestone_has= empty($stage_milestone_details)? 1 : 0;
		
		?> 
		
		
		<div class="development-block" style="border-top:1px solid #002855; ">
		<div class="development-block-left" style="width:25%; padding:5px 0px; border-right:4px solid #002855;background:#fff;" data-toggle="collapse" data-target="#stage-block<?php echo $development->id;?>">
			<?php if($milestone_has==1){?>  <span> </span><?php }else{?> 
			<span class="glyphicon glyphicon-chevron-down"></span><?php }?>
			<strong><?php echo $development->development_name; ?></strong>
		</div>
		
		<?php

		
			
		
		 
		
		$stage_start_date_array = array();
		$stage_end_date_array = array();
		$stage_days_difference = array();
	
		for($i=0; $i<count($stage_milestone_details); $i++){

			$date_arr = array();

			$stage = $ci->report_model->get_stage_detail($development->id, $stage_milestone_details[$i]->stage_no)->row();

			
			
			$stage_start_date_array[] = $stage->construction_start_date;
			
				
			if($stage_milestone_details[$i]->urban_plan_concept > $date_from && $stage_milestone_details[$i]->urban_plan_concept < $date_to)
			{
				
				$date_arr[] = $stage_milestone_details[$i]->urban_plan_concept;	
			}

			if($stage_milestone_details[$i]->consultation > $date_from && $stage_milestone_details[$i]->consultation < $date_to)
			{

				$date_arr[] = $stage_milestone_details[$i]->consultation;
			}

			if($stage_milestone_details[$i]->building_design > $date_from && $stage_milestone_details[$i]->building_design < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->building_design;
			}

			if($stage_milestone_details[$i]->working_drawings > $date_from && $stage_milestone_details[$i]->working_drawings < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->working_drawings;
			}

			if($stage_milestone_details[$i]->working_drawings_contractor > $date_from && $stage_milestone_details[$i]->working_drawings_contractor < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->working_drawings_contractor;
			}

			if($stage_milestone_details[$i]->resource_consent > $date_from && $stage_milestone_details[$i]->resource_consent < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->resource_consent;
			}

			if($stage_milestone_details[$i]->titles_due_out > $date_from && $stage_milestone_details[$i]->titles_due_out < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->titles_due_out;
			}

			if($building_permits[$i]->building_permits > $date_from && $stage_milestone_details[$i]->building_permits < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->building_permits;
			}

			if($building_permits[$i]->construction_earthworks > $date_from && $stage_milestone_details[$i]->construction_earthworks < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->construction_earthworks;
			}

			if($stage_milestone_details[$i]->construction_civil > $date_from && $stage_milestone_details[$i]->construction_civil < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->construction_civil;
			}
			if($stage_milestone_details[$i]->construction_roading > $date_from && $stage_milestone_details[$i]->construction_roading < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->construction_roading;
			}

			if($stage_milestone_details[$i]->construction_general > $date_from && $stage_milestone_details[$i]->construction_general < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->construction_general;
			}
			if($stage_milestone_details[$i]->completion > $date_from && $stage_milestone_details[$i]->completion < $date_to)
			{
				$date_arr[] = $stage_milestone_details[$i]->completion;
			}

		
			
			
			$stage_phase_days_length[$i]=array();
			$stage_phase_days_length[$i][] = $date_arr;

			$max = max(array_map('strtotime', $date_arr));
			$end_date =  date('Y-m-d', $max);

			$stage_end_date_array[] = $end_date;
			
			

			$difference = abs(strtotime($end_date) - strtotime($stage->construction_start_date));
			$stage_days_difference[] = floor(($difference )/ (60*60*24));
	
			
			
		}//stage loop

		if(!empty($stage_start_date_array)){

			$min = min(array_map('strtotime', $stage_start_date_array));
			$milestone_start_date =  date('Y-m-d', $min);		
		}

		if(!empty($stage_end_date_array)){
			$max = max(array_map('strtotime', $stage_end_date_array));
			$milestone_end_date =  date('Y-m-d', $max);
		}
		 
		$difference = abs(strtotime($milestone_end_date) - strtotime($milestone_start_date));
		$total_days = floor(($difference )/ (60*60*24));
		//echo 'Total days '.$total_days.'<br/>';


		//print_r($stage_days_difference);
		echo "<div id='stage-block".$development->id."' class='collapse in'>";

		for($i=0; $i<count($stage_milestone_details); $i++){
			echo "<div class='stage-block' style='height:30px;overflow:hidden;'>";

			echo "<div class='stage-block-left' style='width:25%;float:left;padding:10px 2%;border-right:4px solid #002855;background:#fff;'>Stage ".$stage_milestone_details[$i]->stage_no;
			echo "</div>";

		
			
			
			//$difference = abs(strtotime($milestone_first_start_date) - strtotime($milestone_start_date));
			

			if($search_year_from!= '' && $search_year_to!= '' ){
				$difference = strtotime($stage_start_date_array[$i]) - strtotime($date_from);
				//remove abs for margin-left minus value
				$total_days_margin_left = floor(($difference )/ (60*60*24));
				$margin_percent = ($total_days_margin_left/$total_days_differecce)*100; 
			}else{
				$difference = abs(strtotime($stage_start_date_array[$i]) - strtotime($milestone_first_start_date));
				$total_days_margin_left = floor(($difference )/ (60*60*24));
				$margin_percent = ($total_days_margin_left/$total_days_differecce)*100; 
			}

			$stage_width = ($stage_days_difference[$i]/$total_days)*100; 

			echo "<div style='float:left; width:75%;overflow:hidden;'>";
			echo "<div style='width:$stage_width%; height:30px; padding:5px 0px; margin-left:$margin_percent%'>";
			$date_arr = $stage_phase_days_length[$i][0];

			//print_r($date_arr);
			//echo $stage_start_date_array[$i];
			$width = '';
			if($date_arr[0])
			{
				$difference = abs(strtotime($date_arr[0]) - strtotime($stage_start_date_array[$i]));
				$width= $width.floor(($difference )/ (60*60*24));
			}


			
			
			
			$past_start_date = '';
			for($j=1; $j<count($date_arr); $j++)
			{
				
				if($date_arr[$j]!='0000-00-00')
				{

					//echo "past start date = ".$past_start_date."<br>";
					if($past_start_date!='')
					{
						$difference = abs(strtotime($date_arr[$j]) - strtotime($past_start_date));
						$width = $width.'-'.floor(($difference )/ (60*60*24));
						$past_start_date = $date_arr[$j];
					}
					else
					{
						$difference = abs(strtotime($date_arr[$j]) - strtotime($date_arr[$j-1]));
						$width = $width.'-'.floor(($difference )/ (60*60*24));
						//$past_start_date = $date_arr[$j];
					}
				}
				else
				{
		
					
					if($past_start_date=='')
					{
						$past_start_date = $date_arr[$j-1];
					}
					else
					{
						$past_start_date = $past_start_date;
					}
					$difference = abs(strtotime($past_start_date) - strtotime($past_start_date));
					$width = $width.'-'.floor(($difference )/ (60*60*24)); 
				}
			}
			//echo $width; 
			$pieces = explode("-", $width);
			$piece = array();
			for($k=0; $k<count($pieces); $k++){
				$piece[]=  $pieces[$k];
			}
			?>
			
			<div style="width:100%" id="milestone_block">
			<span title="Urban Plan Concept				&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[0]));?>" class="stone-block" style="background:#FABF8F; width:<?php echo ($piece[0]/$total_days_differecce)*100;?>%"></span>
			<span title="Consultation 				 	&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[1]));?>" class="stone-block" style="background:#FFC000; width:<?php echo ($piece[1]/$total_days_differecce)*100;?>%"></span>
			<span title="Building Design 			 	&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[2]));?>" class="stone-block" style="background:#00B0F0; width:<?php echo ($piece[2]/$total_days_differecce)*100;?>%"></span>
			<span title="Working Drawings (In House)  	&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[3]));?>" class="stone-block" style="background:#FF0000; width:<?php echo ($piece[3]/$total_days_differecce)*100;?>%"></span>
			<span title="Working Drawings (Contractor) 	&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[4]));?>" class="stone-block" style="background:#A6A6A6; width:<?php echo ($piece[4]/$total_days_differecce)*100;?>%"></span>
			
			<span title="Resource Consent 				&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[5]));?>" class="stone-block" style="background:#948A54; width:<?php echo ($piece[5]/$total_days_differecce)*100;?>%"></span>
			<span title="Titles Due Out 				&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[6]));?>" class="stone-block" style="background:#7030A0; width:<?php echo ($piece[6]/$total_days_differecce)*100;?>%"></span>
			<span title="Building Permits 				&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[7]));?>" class="stone-block" style="background:#92D050; width:<?php echo ($piece[7]/$total_days_differecce)*100;?>%"></span>
			
			<span title="Construction (Earthworks) 		&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[8]));?>" class="stone-block" style="background:#FF66FF; width:<?php echo ($piece[8]/$total_days_differecce)*100;?>%"></span>
			<span title="Construction (Civil) 			&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[9]));?>" class="stone-block" style="background:#B1A0C7; width:<?php echo ($piece[9]/$total_days_differecce)*100;?>%"></span>
			
			<span title="Construction (Roading) 		&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[10]));?>" class="stone-block" style="background:#B7DEE8; width:<?php echo ($piece[10]/$total_days_differecce)*100;?>%"></span>
			<span title="Construction (General) 		&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[11]));?>" class="stone-block" style="background:#FFFF00; width:<?php echo ($piece[11]/$total_days_differecce)*100;?>%"></span>
			<span title="Completion 					&#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[12]));?>" class="stone-block" style="background:#000000; width:<?php echo ($piece[12]/$total_days_differecce)*100;?>%"></span>
			
			</div>


			<?php

			echo "</div></div>";
			echo "<div class='clear'></div>";
			echo "</div>";
			

		}
		
		echo "</div>";
		echo "</div>";
 	
	}	





		/*	for($i = 0; $i<count($developments); $i++)
			{
		

          	 echo $developments[$i]->development_name;	echo '<br>';
                echo $developments[$i]->id; 
            }
		*/
?>


</div>
<div class="clear"></div>

<script>
  $(function() {
    //$( "#accordion1" ).accordion({heightStyle: "content"});
	//$('#accordion .ui-accordion-content').show();
  });
  </script>

                  
<script type="text/javascript">

function checkForm(form)
{
	
    return checkDate(form.date_from) && checkDate(form.date_to);
}

function checkDate(input){


	var validformat=/^\d{2}\-\d{2}\-\d{4}$/ //Basic check for format validity

	var returnval=false
	if (!validformat.test(input.value))
		alert("Invalid Date Format. Please correct and submit again.")
	else{ 
		//Detailed check for valid date ranges
		var dayfield=input.value.split("-")[0];
		var monthfield=input.value.split("-")[1];
		var yearfield=input.value.split("-")[2];
		

		var dayobj = new Date(yearfield, monthfield-1, dayfield);
		if ((dayobj.getMonth()+1!=monthfield)||(dayobj.getDate()!=dayfield)||(dayobj.getFullYear()!=yearfield))
		alert("Invalid Day, Month, or Year range detected. Please correct and submit again.")
		else
		returnval=true;
	}
	if (returnval==false) input.select();
	return returnval;
}

</script>
			
			
			
	