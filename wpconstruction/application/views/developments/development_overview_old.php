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

<?php

$phase_date = array();
$stage_date = array();

foreach($development_overview_info as $phase_info)
{ 

	if($phase_info->start_date != '0000-00-00')
	{
	
		$phase_date[] = $phase_info->start_date; 
	}

}

$phase_dates = array_filter($phase_date);


foreach($stage_overview_info as $stage_info)
{  

	if($stage_info->start_date != '0000-00-00')
	{
	
		$stage_date[] = $stage_info->start_date; 
	}
}


$stage_dates = array_filter($stage_date);




if( empty($phase_dates) or empty($stage_dates) )
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
 	
		$now = date('Y-m-d');
		$today_time = strtotime($now);
		$block_color_arr = array();
		$day = 1;
		$day_deduct_str = " - ".$day." month";

		foreach($development_overview_info as $phase_info){ 
 
		$phase_start_date_30 = $phase_info->start_date; 
		$start_deduct_result = date_add(date_create($phase_start_date_30), date_interval_create_from_date_string($day_deduct_str));
		$phase_start_date = date_format($start_deduct_result, 'Y, m, d');

		
		$phase_end_date_30 = $phase_info->end_date; 
		$end_deduct_result = date_add(date_create($phase_end_date_30), date_interval_create_from_date_string($day_deduct_str));
		$phase_end_date = date_format($end_deduct_result, 'Y, m, d');

		$end_time = strtotime($phase_end_date);

		$ci =&get_instance();
		$ci->load->model('developments_model');
		$phase_status_info = $ci->developments_model->get_all_development_phase_status($development_id,$phase_info->id)->result();

		if($phase_status_info)
		{
			$phase_status = $phase_status_info[0]->all_task_status;
		}

		if ($today_time > $end_time && $phase_status  == '1') 
		{
			$block_color_arr[] = '#008000';
		}
		elseif ($today_time < $end_time && $phase_status  == '1') 
		{
			$block_color_arr[] = '#008000';
		}
		elseif($today_time > $end_time && $phase_status  == '0')
		{
			$block_color_arr[] = '#FF0000';
		}
		elseif($today_time < $end_time && $phase_status  == '0')
		{
			$block_color_arr[] = '#FFFF00';
		}
		else
		{
			$block_color_arr[] = '';
		} 


	?>
    
	[ '<?php echo $phase_info->phase_name ?>',  	
	
		<?php if($phase_info->start_date != '0000-00-00'){ ?>new Date(<?php echo $phase_start_date;?>), <?php } else{ echo ','; }  ?>
		<?php if($phase_info->end_date != '0000-00-00'){ ?>new Date(<?php echo $phase_end_date;?>), <?php } else{ echo ','; }  ?>],
	
    <?php }  ?>


	<?php 

		$now = date('Y-m-d');
		$today_time = strtotime($now);

		foreach($stage_overview_info as $stage_info){  

		$day = 1;
		$day_deduct_str = " - ".$day." month";

		$stage_start_date_30 = $stage_info->start_date; 
		$start_deduct_result = date_add(date_create($stage_start_date_30), date_interval_create_from_date_string($day_deduct_str));
		$stage_start_date = date_format($start_deduct_result, 'Y, m, d');

		
		$stage_end_date_30 = $stage_info->end_date; 
		$end_deduct_result = date_add(date_create($stage_end_date_30), date_interval_create_from_date_string($day_deduct_str));
		$stage_end_date = date_format($end_deduct_result, 'Y, m, d');


		$ci =&get_instance();
		$ci->load->model('developments_model');
		$phase_status_info = $ci->developments_model->get_all_phase_status($development_id,$stage_info->stage_no)->result();

		
		if($phase_status_info)
		{
			$phase_status = $phase_status_info[0]->aphase_status;
		}

		
		$end_time = strtotime(date_format($end_deduct_result,'Y-m-d'));
	
		
		if ($today_time > $end_time && $phase_status  == '1') 
		{
			$block_color_arr[] = '#008000';
		}
		elseif ($today_time < $end_time && $phase_status  == '1') 
		{
			$block_color_arr[] = '#008000';
		}
		elseif($today_time > $end_time && $phase_status  == '0')
		{
			$block_color_arr[] = '#FF0000';
		}
		elseif($today_time < $end_time && $phase_status  == '0')
		{
			$block_color_arr[] = '#FFFF00';
		}
		else
		{
			$block_color_arr[]= '';
		}



		if($stage_info->start_date != '0000-00-00' && $stage_info->end_date != '0000-00-00' )
		{
	?>
    
	[ 'Stage <?php echo $stage_info->stage_no ?>',  	
	
		<?php if($stage_info->start_date != '0000-00-00' && $stage_info->end_date != '0000-00-00' ){ ?>new Date(<?php echo $stage_start_date;?>), <?php } else{ echo ','; }  ?>
		<?php if($stage_info->end_date != '0000-00-00' && $stage_info->start_date != '0000-00-00'){ ?>new Date(<?php echo $stage_end_date;?>), <?php } else{ echo ','; }  ?>],
	
    <?php }  } ?>


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

<div id="development-overview">

	<?php if($show_grap == 0) { ?>

	<div style="color:red; font-size:16px; padding-top:20px">The graph is not showing due to phase dates missing or incorrect</div>

	<?php } ?>
	<div id="extend_div" ></div>
	<div style="overflow-x:scroll">
	<div id="chart_div"></div>
	</div>
</div>

<?php //print_r($stage_overview_info); ?>

<div id="devlopment-overview-stage-status" class="stage-status-box" style="">
        <div class="box-title" style="margin-bottom: 10px;">Stage Status (<?php  echo $number_of_stages; ?>)</div> 
        <div>
        	
        		<?php for($i=0; $i< $number_of_stages; $i++){ 

					$ci =&get_instance();
					$ci->load->model('developments_model');
					$phase_status_info = $ci->developments_model->get_all_phase_status($development_id,$i+1)->result();

					
					if($phase_status_info)
					{
						$phase_status = $phase_status_info[0]->aphase_status;
					}

					$block_color = '';
					$now = date('Y-m-d');
					$today_time = strtotime($now);
					if(isset($stage_overview_info[$i]->end_date))
					{
						$end_time = strtotime($stage_overview_info[$i]->end_date);
					}
					
					if ($today_time > $end_time && $phase_status  == '1') 
					{
						$block_color = 'green';
						$block_status = 'Completed';
					}
					elseif ($today_time < $end_time && $phase_status  == '1') 
					{
						$block_color = 'green';
						$block_status = 'Completed';
					}
					elseif($today_time > $end_time && $phase_status  == '0')
					{
						$block_color = 'red';
						$block_status = 'Late';
					}
					elseif($today_time < $end_time && $phase_status  == '0')
					{
						$block_color = 'yellow';
						$block_status = 'Underway';
					}
					else
					{
						$block_color = '';
						$block_status = 'Underway';
					}


				?>
        		
        			<div class=stage-status-box>
			        	<div class="stage-status-box-top">
			        		<div style="float:left"> 
			        			<div style="height: 15px;width:15px; border-radius:10px; background:<?php echo $block_color; ?>;margin-left:5px;text-align:center;"></div> 
			        		</div>
			        		<div style="float:left"><u>Stage<?php echo $i+1;?> </u></div>
			        		<div style="float:right">
			        			
			        			#0000<?php echo $i+1; ?> <br /> <a href="<?php $s_i = $i+1; echo base_url().'stage/stage_overview/'.$development_id.'/'.$s_i; ?>">
			        			<img src="<?php echo base_url()?>images/icon/chart.png"/> </a>
			        		</div>
			        		<div class="clear"></div>
			        	</div>
			        	
			        	<div class="stage-status-box-top">
			        		<div style="float:left">ETC: <?php echo $block_status; ?></div>
			        		<div style="float:right">Days Left: <?php if(isset($stage_overview_info[$i]->end_date) && $block_status != 'Completed' ){echo $rem_days = date_diff(date_create($stage_overview_info[$i]->end_date),date_create($now))->format("%a"); } ?> </div>
			        		<div class="clear"></div>
			        	</div>
			        	<div class="clear"></div>
			        
			        </div>
        		
        		<?php } ?>
        	
        </div>
        
    </div>
   <script type="text/javascript">
    
 $(document).ready(function () {
       
 
        $("#extend_div").click(
            function () {
            	
            	$("#chart_div").addClass('fullscreen');
                
            }
        );

        
  
	
            
 });

 
</script>