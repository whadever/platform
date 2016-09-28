
<?php 

        
$stage_documents_img = array(
          'src' => base_url().'images/icon/documents_icon.png',
          'alt' => 'Stage Documents',
          'class' => 'save-developments',
          'width' => '60',          
          'title' => 'Stage Documents',
          'style'=>''
          
);

$save_stage_img = array(
          'src' => base_url().'images/icon/btn_horncastle_save.png',
          'alt' => 'Save Stage Info',
          'class' => 'save-stage',
          'width' => '60',          
          'title' => 'Save Stage Info',
          'style'=>''
          
);

$print_stage_img = array(
          'src' => base_url().'images/icon/btn_horncastle_printer.png',
          'alt' => 'Print Stage Info',
          'class' => 'print-stage',
          'width' => '60',          
          'title' => 'Print Stage Info',
          'style'=>''
          
);


$email_stage_img = array(
          'src' => base_url().'images/icon/btn_horncastle_mail.png',
          'alt' => 'Email Stage Info',
          'class' => 'email-stage',
          'width' => '60',          
          'title' => 'Email Stage Info',
          'style'=>''
          
);

$emaildata = array(
    'name' => 'development_email_btn',
    'id' => 'development_email_btn',
    'class' => 'btn_dev_info',
    'value' => 'true',
    'type' => 'button',
    'title' => 'Email Stage Info',
    'content' => img($email_stage_img),
    'onClick' => 'email_dev_info('.$development_id.')"',
    
    
);
 $atts = array(
                      'width'      => '800',
                      'height'     => '600',
                      'scrollbars' => 'yes',
                      'status'     => 'yes',
                      'resizable'  => 'yes',
                      'screenx'    => '0',
                      'screeny'    => '0',
                      'class'    => 'btn_dev_info'  
                    );


?>

<?php
	$development_id = $this->uri->segment(3);
	$stage_no = $this->uri->segment(4);
?>
<style>
.btn_dev_info {
    margin: 10px 5px 0 0;
}
</style>
<?php
$user = $this->session->userdata('user');
$user_app_role_id = $user->application_role_id; 

if($user_app_role_id==4){
	$disabled = 'disabled';
}else{
	$disabled = '';
}
?> 
    
        <div class="development-detail">
            <div id="development-detail-left">
                
            
                <div class="development-info">
                    <div class="box-title">Information<?php //echo $title; ?> </div>
                    <div class="development-info-table">
						<?php if($user_app_role_id==2 || $user_app_role_id==4){ ?><a href="#AddStage_<?php echo $stage_no; ?>" title="Update Stage" role="button" data-toggle="modal" class="add-stage">Update Stage</a><?php } ?>
						<?php if(isset($table)) { echo $table;	} ?> 
					</div>               
           			
                    
                </div>
				<div class="clear"></div>
                <div class="button-box" style="padding:0px; margin:0px;">
                
                	   <?php   
                	//echo anchor(base_url().'stage/stage_documents/'.$development_id.'/'.$stage_id, img($stage_documents_img), array('title' => 'Stage Documents', 'class'=> 'btn_dev_info'));
                                
                   

                  	if(isset($stage_detail->id)){
						$stage_id_new = $stage_detail->id;
						echo anchor_popup(base_url().'stage/stage_print/'.$stage_id_new, img($print_stage_img), $atts);                    
                    	echo anchor(base_url().'stage/pdf_stage/'.$stage_id_new, img($save_stage_img), array('title' => 'Save Stage Info', 'class'=> 'btn_dev_info'));
					}else{
					?>
					
					<a class="btn_dev_info" screeny="0" screenx="0" resizable="yes" status="yes" scrollbars="yes" height="600" width="800" href="#"><img width="60" style="" title="Print Stage Info" class="print-stage" alt="Print Stage Info" src="<?php echo base_url();?>images/icon/btn_horncastle_printer.png"></a>                   
                    <a class="btn_dev_info" title="Save Stage Info" href="#"><img width="60" style="" title="Save Stage Info" class="save-stage" alt="Save Stage Info" src="<?php echo base_url();?>images/icon/btn_horncastle_save.png"></a>

					<?php }
                    //echo anchor_popup(base_url().'stage/print_stage/'.$stage_id_new, img($print_stage_img), $atts);                    
                    //echo anchor(base_url().'stage/pdf_stage/'.$stage_id_new, img($save_stage_img), array('title' => 'Save Stage Info', 'class'=> 'btn_dev_info'));
                    //echo form_button($emaildata);
					$to='';
                    ?>

					<a class="btn_dev_info" href="mailto:<?php echo $to;?>?subject=Stage%20Information&amp;body=Stage Name: 
					<?php if(isset($stage_detail->stage_name)){ echo $stage_detail->stage_name; } ?>%0D%0ANumber of Lots: 
					<?php if(isset($stage_detail->number_of_lots)){ echo $stage_detail->number_of_lots; } ?>%0D%0AUnder Construction: 
					<?php if(isset($stage_detail->under_construction)){ echo $stage_detail->under_construction; } ?>%0D%0AAwaiting Construction: 
					<?php if(isset($stage_detail->awaiting_construction)){ echo $stage_detail->awaiting_construction; } ?>%0D%0ATotal Homes Completed: 
					<?php if(isset($stage_detail->total_homes_completed)){ echo $stage_detail->total_homes_completed; } ?>%0D%0AConstruction Start Date: 
					<?php if(isset($stage_detail->construction_start_date)){ echo $this->wbs_helper->to_report_date($stage_detail->construction_start_date); } ?>%0D%0AMaintainence Bond Date: 
					<?php if(isset($stage_detail->maintainence_bond_date)){ echo $this->wbs_helper->to_report_date($stage_detail->maintainence_bond_date); } ?>%0D%0AFeature Photo Link: 
					<?php if(isset($stage_feature_photo->filename)){echo base_url();?>uploads/stage/<?php echo $stage_feature_photo->filename;  }else{ echo 'No Feature Image';}?>">
                   
                   <?php echo img($email_stage_img);?></a>

					<div class="clear"></div>
					
                    </div>
            </div>
            
            <div class="development-photo all-feature-img">
                <div class="box-title">Feature Photo </div>
                <div style="min-height: 280px; text-align:center; width: 100%;">

					<div class="flexslider" style="border:0px solid #000 !important;">
         
          			<ul class="slides">
						<?php 
//print_r($stage_feature_photos);
						foreach($stage_feature_photos as  $stage_feature_photo){

							if(isset($stage_feature_photo->filename)){ 
								$image_link= base_url().'uploads/stage/'.$stage_feature_photo->filename;
							}else{
			            		 $image_link = base_url().'images/pms_home.png';
			            	}

							$imagedata = getimagesize($image_link);
							$image_width= $imagedata[0];
							$image_height= $imagedata[1];

							if($image_width <442 && $image_height<355){
								$width='';
								$height=$image_height.'px';
							}
							else{
								if($image_height>$image_width){
									$width='auto !important;';
									$height='355px !important';
								}
								else{
									$width='100% !important';
									$height='100%';
								}
							}
						?>
						<li>
	                    <a id="fancybox" rel="gallery1" href="<?php echo $image_link; ?>"><img style="width:<?php echo $width;?>;height:<?php echo $height;?>;" src="<?php echo $image_link; ?>"/></a>
						
						</li>
					<?php } ?>
					</ul>
					</div><!-- flexslider -->
                
				</div>


            </div>
        </div>
<div class="clear"></div>
<style>
#stage_milestone_overview{
	border: 4px solid #002855;
	border-radius:5px;
	margin-top:10px;
}
#stage_milestone_overview h6{
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
</style>
<script>
$(document).ready(function() { 
    
    $('.clickdiv').click(function(){
        $('.hiders').slideToggle();
        $('#minus').toggle();
        $('#plus').toggle();
    });
            
});
</script>
<div id="stage_milestone_overview"> 
<h6 align="center">Milestone for stage <?php echo $stage_no; ?></h6>

<div class="color-key">
	<div class="clickdiv" id="search-header-milestone">
        <span> Milestone Key </span> 
        <span id="plus">+</span><span id="minus" style="display:none">-</span>
    </div> 
    <div class="hiders" id="search-body-milestone">
		<div class="row" style="margin-bottom:10px;">
			<div id="milestone-color-block">
				<div class="block1"><span class="stone-block1" style="background:#FABF8F;"></span><span class="stone-block2">Scheme Concept</span></div>
				<div class="block1"><span class="stone-block1" style="background:#FFC000;"></span><span class="stone-block2">Consultation Vault</span></div>
				<div class="block1"><span class="stone-block1" style="background:#00B0F0;"></span><span class="stone-block2">Building Design</span></div>
				<div class="block1"><span class="stone-block1" style="background:#FF0000;"></span><span class="stone-block2">Architectural Drawings</span></div>
				<div class="block1"><span class="stone-block1" style="background:#948A54;"></span><span class="stone-block2">Resource Consenting</span></div>
				<div class="block1"><span class="stone-block1" style="background:#7030A0;"></span><span class="stone-block2">224 & Title Release</span></div>
			</div>
		</div>
		<div class="row">
			<div id="milestone-color-block">
				<div class="block1"><span class="stone-block1" style="background:#92D050;"></span><span class="stone-block2">Building Consent</span></div>
				<div class="block1"><span class="stone-block1" style="background:#FF66FF;"></span><span class="stone-block2">Earthworks</span></div>
				<div class="block1"><span class="stone-block1" style="background:#B1A0C7;"></span><span class="stone-block2">Civil Services</span></div>
				<div class="block1"><span class="stone-block1" style="background:#B7DEE8;"></span><span class="stone-block2">Roading</span></div>
				<div class="block1"><span class="stone-block1" style="background:#FFFF00;"></span><span class="stone-block2">Construction</span></div>
				<div class="block1"><span class="stone-block1" style="background:#000000;"></span><span class="stone-block2">Completion & Landscaping</span></div>
			</div>
		</div>
    </div>
</div>


<?php 


if(isset($stage_detail->construction_start_date)){ 
	$start_date= $stage_detail->construction_start_date; 
	
} 

//echo 'Start date '.$start_date.'<br/>';

$milestone_start_year =  date('Y', strtotime($start_date));
$milestone_start_month =  date('m', strtotime($start_date));


if(!empty($milestone_details)){

$date_arr[] = $milestone_details->urban_plan_concept;
$date_arr[] = $milestone_details->consultation;
$date_arr[] = $milestone_details->building_design;
$date_arr[] = $milestone_details->working_drawings;
//$date_arr[] = $milestone_details->working_drawings_contractor;
$date_arr[] = $milestone_details->resource_consent;
$date_arr[] = $milestone_details->titles_due_out;
$date_arr[] = $milestone_details->building_permits;
$date_arr[] = $milestone_details->construction_earthworks;
$date_arr[] = $milestone_details->construction_civil;
$date_arr[] = $milestone_details->construction_roading;
$date_arr[] = $milestone_details->construction_general;
$date_arr[] = $milestone_details->completion;


$max = max(array_map('strtotime', $date_arr));
$milestone_end_date =  date('Y-m-d', $max);


/***/

$milestone_end_year =  date('Y', $max);
$milestone_end_month =  date('m', $max);

$year_diff= $milestone_end_year - $milestone_start_year; 
 $month_diff = (($milestone_end_year - $milestone_start_year) * 12) + ($milestone_end_month - $milestone_start_month);
if($year_diff<=5){
	if($month_diff<=12){
		$width_per = (100)/($month_diff+1);
	}else{
		$width_per = (100*$year_diff)/($month_diff+$year_diff+$year_diff);
	}
	
	
}else{
	$width_per = 100/($year_diff*2+2);
}



/****/


//echo 'Start date '.$start_date.'<br/>';
//echo 'End date '.$milestone_end_date.'<br/>';

$difference = abs(strtotime($milestone_end_date) - strtotime($start_date));
$days = floor(($difference )/ (60*60*24));
//echo 'Total days '.$days.'<br/>';

$width = '';
if($date_arr[0])
{
	$difference = abs(strtotime($date_arr[0]) - strtotime($start_date));
	$width= $width.floor(($difference )/ (60*60*24));
	//echo $width; 
}

$now = date("Y-m-d");
$past_start_date = '';
	for($i=1; $i<count($date_arr); $i++)
	{
		
		if($date_arr[$i]!='0000-00-00')
		{
			if($past_start_date!='')
			{
				$difference = abs(strtotime($date_arr[$i]) - strtotime($past_start_date));
				$width = $width.'-'.floor(($difference )/ (60*60*24));
				$past_start_date = $date_arr[$j];
			}
			else
			{
				$difference = abs(strtotime($date_arr[$i]) - strtotime($date_arr[$i-1]));
				$width = $width.'-'.floor(($difference )/ (60*60*24));
			}
		}
		else
		{

			if($past_start_date=='')
			{
				$past_start_date = $date_arr[$i-1];
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

for($j=0; $j<count($pieces); $j++){
	$piece[]=  $pieces[$j];
}
//print_r($piece);

?>
<div>
	<div style="float:left;width:11%; padding:10px 2%;">Stage <?php echo $stage_no;?></div>
	<div style="float:left; width:89%; padding: 10px 0px;border-left:4px solid #002855;">
		<div style="width:100%" id="milestone_block">
		<?php $now = date("d-m-Y"); ?>
		<span title="Scheme Concept &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[0]));?>" class="stone-block" style="background:#FABF8F; width:<?php echo ($piece[0]/$days)*100;?>%"></span>
		<span title="Consultation Vault &#013; Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[1]));?>" class="stone-block" style="background:#FFC000; width:<?php echo ($piece[1]/$days)*100;?>%"></span>
		<span title="Building Design &#013;Completion Date:  <?php echo  date('d-m-Y', strtotime($date_arr[2]));?>" class="stone-block" style="background:#00B0F0; width:<?php echo ($piece[2]/$days)*100;?>%"></span>
		<span title="Architectural Drawings &#013; Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[3]));?>" class="stone-block" style="background:#FF0000; width:<?php echo ($piece[3]/$days)*100;?>%"></span>
		<span title="Resource Consenting &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[4]));?>" class="stone-block" style="background:#948A54; width:<?php echo ($piece[4]/$days)*100;?>%"></span>
		<span title="224 & Title Release &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[5]));?>" class="stone-block" style="background:#7030A0; width:<?php echo ($piece[5]/$days)*100;?>%"></span>
		<span title="Building Consent & EWA &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[6]));?>" class="stone-block" style="background:#92D050; width:<?php echo ($piece[6]/$days)*100;?>%"></span>
		<span title="Earthworks &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[7]));?>" class="stone-block" style="background:#FF66FF; width:<?php echo ($piece[7]/$days)*100;?>%"></span>		
		<span title="Civil Services &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[8]));?>" class="stone-block" style="background:#B1A0C7; width:<?php echo ($piece[8]/$days)*100;?>%"></span>		
		<span title="Roading &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[9]));?>" class="stone-block" style="background:#B7DEE8; width:<?php echo ($piece[9]/$days)*100;?>%"></span>
		<span title="Construction &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[10]));?>" class="stone-block" style="background:#FFFF00; width:<?php echo ($piece[10]/$days)*100;?>%"></span>
		<span title="Completion & Landscaping &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[11]));?>" class="stone-block" style="background:#000000; width:<?php echo ($piece[11]/$days)*100;?>%"></span>
		
		</div>
	</div>
</div>
<div class="clear"></div>
<?php } ?>
	<div class="bg-stage-gp" style="width:100%; padding-left:11%;padding-top:5px; background-color:#002855;overflow:hidden;color:#fff; text-align:center;">

	<?php 
		
		$month_array= array("JAN", "FEB", "MAR", "APRIL", "MAY", "JUN", "JULY", "AUG", "SEP", "OCT", "NOV", "DEC");
		//echo $year_diff.'-'.$milestone_start_year.'-'.$milestone_end_year; echo '<br>';
		//echo $month_diff.'-'.$milestone_start_month.'-'.$milestone_end_month;
		$last_d = date('d', strtotime($date_arr[11]));
		$last_m = date('m', strtotime($date_arr[11]));
		if($year_diff<=0){
		 
			$j= $milestone_start_year;
			$k = $milestone_start_month-1;
			for($i=0; $i<=$month_diff; $i++){
				if(date("m") == $k+1)
				{ 
					$days = date('d');
					$marg = (66*$days)/365;
					$hh =  '<div id="today_bar" style="margin-left:'.$marg.'%;"></div>';
				}else
				{
					$hh = '';
				}
				echo "<span style='float:left;width:$width_per%'>$month_array[$k] $hh <br> $j </span>";
				$k++;
				if($k>=12){$k=$k%12; $j=$j+1;}
			}
			
		}elseif($year_diff>0 && $year_diff<=5){
			$j= $milestone_start_year;
			$k = $milestone_start_month-1;
			for($i=0; $i<=$month_diff+$year_diff-1; $i+=$year_diff){

				$cd = date("d");
				$cm = date("m");
				if($last_m==$cm)
				{
					if($cd < $last_d){
						$d = 1;
						$m = $k+1;
						$sd = date("Y-$m-$d");
	
						$now = time();
					    $your_date = strtotime($sd);
					    $datediff = $now - $your_date;
					    $days = floor($datediff/(60*60*24));
	
						$marg = (66*$days)/(365*$year_diff);
						$hh =  '<div id="today_bar" style="margin-left:'.$marg.'%;"></div>';
					}else{
						$hh =  '';
					}

				}
				else
				{

					if( date("Y") == $j && ( date("m") > $k  ) && ( date("m") <= $k + $year_diff ) )
					{ 
						$d = 1;
						$m = $k+1;
						$sd = date("Y-$m-$d");
	
						$now = time();
					    $your_date = strtotime($sd);
					    $datediff = $now - $your_date;
					    $days = floor($datediff/(60*60*24));
	
						$marg = (66*$days)/(365*$year_diff);
						$hh =  '<div id="today_bar" style="margin-left:'.$marg.'%;"></div>';
					}
					else
					{
						$hh = '';
					}

				}
				echo "<span style='float:left;width:$width_per%'>$month_array[$k] $hh <br> $j </span>";
				$k+=$year_diff;
				if($k>=12){$k=$k%12; $j=$j+1;}
			}
			
		}else{

			for($i=$milestone_start_year; $i<=$milestone_end_year; $i++){

				if( date("Y") == $i && ( date("m") <= 6  ) ) 
				{ 
					$d = 1;
					$m = 1;
					$sd = date("Y-$m-$d");

					$now = time();
				    $your_date = strtotime($sd);
				    $datediff = $now - $your_date;
				    $days = floor($datediff/(60*60*24));

					$marg = (66*$days)/(365*$year_diff);
					$hh =  '<div id="today_bar" style="margin-left:'.$marg.'%;"></div>';
				}
				else 
				{
					$hh = '';
				}

				if( date("Y") == $i && ( date("m") >  6 ) ){
					$d = 1;
					$m = 7;
					$sd = date("Y-$m-$d");

					$now = time();
				    $your_date = strtotime($sd);
				    $datediff = $now - $your_date;
				    $days = floor($datediff/(60*60*24));

					$marg = (66*$days)/(365*$year_diff);
					$hh1 = '<div id="today_bar" style="margin-left:'.$marg.'%;"></div>';
				}
				else
				{		
					$hh1 = '';
				}
				echo "<span style='float:left;width:$width_per%'>JAN $hh <br> $i</span>";
				echo "<span style='float:left;width:$width_per%'>JULY $hh1 <br> $i</span>";
			}
		
		}
	
	
	?>
	</div>
</div>


<?php //foreach($milestone_details as $milestone_detail) { ?>

<!-- MODAL Edit Milestone -->
<div id="EditMilestone" class="milestone modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form class="form-horizontal" action="<?php echo base_url(); ?>stage/update_milestone/<?php echo $development_id.'/'.$stage_no; ?>" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Edit Milestone</h3>
	</div>
	<div class="modal-body">

		<div class="milestone-left1"></div>
		
		<div class="milestone-right1">

			<div class="control-group">
				<label class="control-label milestone-select-color-label" for="milestone_select_color">Milestone</label>
				<div class="controls milestone-select-color">
					<div class="urban-plan-concept">
						<?php if(!empty($milestone_details)){ ?>
						<span class="color"></span><span>Scheme Concept</span>
						<input <?php echo $disabled; ?> type="text" id="urban_plan_concept" class="live_datepicker" name="urban_plan_concept" value="<?php if($milestone_details->urban_plan_concept=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->urban_plan_concept); }?>" required="">
					</div>

					<div class="consultation">
						<span class="color"></span><span>Consultation Vault</span>
						<input <?php echo $disabled; ?> type="text" id="consultation" class="live_datepicker" name="consultation" value="<?php if($milestone_details->consultation=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->consultation); }?>">
					</div>

					<div class="building-design">
						<span class="color"></span><span>Building Design</span>
						<input <?php echo $disabled; ?> type="text" id="building_design" class="live_datepicker" name="building_design" value="<?php if($milestone_details->building_design=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->building_design); }?>">
					</div>

					<div class="working-drawings">
						<span class="color"></span><span>Architectural Drawings</span>
						<input <?php echo $disabled; ?> type="text" id="working_drawings" class="live_datepicker" name="working_drawings" value="<?php if($milestone_details->working_drawings=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->working_drawings); }?>">
					</div>

					<div class="working-drawings-contractor" style="display:none;">
						<span class="color"></span><span>Working Drawings (Contractor)</span>
						<input <?php echo $disabled; ?> type="text" id="working_drawings_contractor" class="live_datepicker" name="working_drawings_contractor" value="<?php if($milestone_details->working_drawings_contractor=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->working_drawings_contractor); }?>">
					</div>
					<div class="resource-consent">
						<span class="color"></span><span>Resource Consenting</span>
						<input <?php echo $disabled; ?> type="text" id="resource_consent" class="live_datepicker" name="resource_consent" value="<?php if($milestone_details->resource_consent=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->resource_consent); }?>">
					</div>
					<div class="titles-due">
						<span class="color"></span><span>224 & Title Release</span>
						<input <?php echo $disabled; ?> type="text" id="titles_due_out" class="live_datepicker" name="titles_due_out" value="<?php if($milestone_details->titles_due_out=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->titles_due_out); }?>">
					</div>
					<div class="building-permits">
						<span class="color"></span><span>Building Consent & EWA</span>
						<input <?php echo $disabled; ?> type="text" id="building_permits" class="live_datepicker" name="building_permits" value="<?php if($milestone_details->building_permits=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->building_permits); }?>">
					</div>
					
					<div class="construction-earthworks">
						<span class="color"></span><span>Earthworks</span>
						<input <?php echo $disabled; ?> type="text" id="construction_earthworks" class="live_datepicker" name="construction_earthworks" value="<?php if($milestone_details->construction_earthworks=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->construction_earthworks); }?>">
					</div>
					<div class="construction-civil">
						<span class="color"></span><span>Civil Services</span>
						<input <?php echo $disabled; ?> type="text" id="construction_civil" class="live_datepicker" name="construction_civil" value="<?php if($milestone_details->construction_civil=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->construction_civil); }?>">
					</div>
					<div class="construction-roading">
						<span class="color"></span><span>Roading</span>
						<input <?php echo $disabled; ?> type="text" id="construction_roading" class="live_datepicker" name="construction_roading" value="<?php if($milestone_details->construction_roading=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->construction_roading); }?>">
					</div>
					<div class="construction-general">
						<span class="color"></span><span>Construction</span>
						<input <?php echo $disabled; ?> type="text" id="construction_general" class="live_datepicker" name="construction_general" value="<?php if($milestone_details->construction_general=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->construction_general); }?>">
					</div>
					<div class="completion">
						<span class="color"></span><span>Completion & Landscaping</span>
						<input <?php echo $disabled; ?> type="text" id="completion" class="live_datepicker" name="completion" value="<?php if($milestone_details->completion=='0000-00-00'){ echo '';}else { echo $this->wbs_helper->to_report_date($milestone_details->completion); }?>">
					</div>
					<?php } ?>
					
				</div>
			</div>

			<div class="control-group control-milestone-submit">
				<div class="controls">
					<input type="hidden" id="development_id" name="development_id" value="<?php echo $development_id; ?>">	
					<input type="hidden" id="stage_no" name="stage_no" value="<?php echo $stage_no; ?>">			
					<div class="milestone-save">
						<input type="submit" value="Save" name="submit" />
					</div>
				</div>
			</div>
			<div style="clear:both;"></div>
		</div>
    	<div style="clear:both;"></div>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<!-- MODAL Edit Milestone-->


<?php //} ?>


<!-- MODAL Add New Milestone -->
<div id="AddNewMilestone" class="milestone modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form class="form-horizontal" action="<?php echo base_url(); ?>stage/add_new_milestone" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Set Milestone</h3>
	</div>
	<div class="modal-body">

		<div class="milestone-left1">

			

			
		

		</div>
		
		<div class="milestone-right1">

			<div class="control-group">
				<span style="float:left;">Milestone</span>
				<span  style="float:right;text-align:center;width:30%;">Date</span>
				<div class="controls milestone-select-color">
					<div class="urban-plan-concept">
						<span class="color"></span><span>Scheme Concept</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date1" class="live_datepicker" name="urban_plan_concept" value="" required="">
					</div>

					<div class="consultation">
						<span class="color"></span><span>Consultation Vault</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date2" class="live_datepicker" name="consultation" value="">
					</div>

					<div class="building-design">
						<span class="color"></span><span>Building Design</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date3" class="live_datepicker" name="building_design" value="">
					</div>

					<div class="working-drawings">
						<span class="color"></span><span>Architectural Drawings</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date4" class="live_datepicker" name="working_drawings" value="">
					</div>

					<div class="working-drawings-contractor" style="display:none;">
						<span class="color"></span><span>Working Drawings (Contractor)</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date5" class="live_datepicker" name="working_drawings_contractor" value="">
					</div>
					<div class="resource-consent">
						<span class="color"></span><span>Resource Consenting</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date6" class="live_datepicker" name="resource_consent" value="">
					</div>
					<div class="titles-due">
						<span class="color"></span><span>224 & Title Release</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date7" class="live_datepicker" name="titles_due_out" value="">
					</div>
					<div class="building-permits">
						<span class="color"></span><span>Building Consent & EWA</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date8" class="live_datepicker" name="building_permits" value="">
					</div>
					
					<div class="construction-earthworks">
						<span class="color"></span><span>Earthworks</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date9" class="live_datepicker" name="construction_earthworks" value="">
					</div>
					<div class="construction-civil">
						<span class="color"></span><span>Civil Services</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date10" class="live_datepicker" name="construction_civil" value="">
					</div>
					<div class="construction-roading">
						<span class="color"></span><span>Roading</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date11" class="live_datepicker" name="construction_roading" value="">
					</div>
					<div class="construction-general">
						<span class="color"></span><span>Construction</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date12" class="live_datepicker" name="construction_general" value="">
					</div>
					<div class="completion">
						<span class="color"></span><span>Completion & Landscaping</span>
						<input <?php echo $disabled; ?> type="text" id="milestone-date13" class="live_datepicker" name="completion" value="">
					</div>
					
				</div>
			</div>

			<div class="control-group control-milestone-submit">
				<div class="controls">
					<input type="hidden" id="development_id" name="development_id" value="<?php echo $development_id; ?>">	
					<input type="hidden" id="stage_no" name="stage_no" value="<?php echo $stage_no; ?>">			
					<div class="milestone-save">
						<input type="submit" value="Save" name="submit" />
					</div>
				</div>
			</div>
			<div style="clear:both;"></div>
    	</div>
    	<div style="clear:both;"></div>
	</div>
	<div style="clear:both;"></div>
</form>
</div>
<!-- MODAL Add New Milestone-->



<!-- MODAL Stage Add-->
<div id="AddStage_<?php echo $stage_no; ?>" class="modal stage hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url();?>stage/<?php if(isset($stage_detail->id)){ echo 'stage_update/'.$stage_detail->id;} else { echo 'stage_add';}?>" method="POST">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> X </button>
			<h3 id="myModalLabel">Update Stage Info</h3>
		</div>
		<div class="modal-body">
				
			<div class="control-group">
				<label class="control-label" for="stage_name">Stage Name </label>
				<div class="controls">
					<input type="text" id="stage_name" placeholder="" name="stage_name" value="<?php if(isset($stage_detail->stage_name)){ echo $stage_detail->stage_name; } ?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="number_of_lots">Number of Lots </label>
				<div class="controls">
					<input type="text" id="number_of_lots" placeholder="" name="number_of_lots" value="<?php if(isset($stage_detail->number_of_lots)){ echo $stage_detail->number_of_lots; } ?>">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="under_construction">Under Construction </label>
				<div class="controls">
					<input type="text" id="under_construction" placeholder="" name="under_construction" value="<?php if(isset($stage_detail->under_construction)){ echo $stage_detail->under_construction; } ?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="awaiting_construction">Awaiting Construction </label>
				<div class="controls">
					<input type="text" id="awaiting_construction" placeholder="" name="awaiting_construction" value="<?php if(isset($stage_detail->awaiting_construction)){ echo $stage_detail->awaiting_construction; } ?>">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="total_homes_completed">Total Homes Completed </label>
				<div class="controls">
					<input type="text" id="total_homes_completed" placeholder="" name="total_homes_completed" value="<?php if(isset($stage_detail->total_homes_completed)){ echo $stage_detail->total_homes_completed; } ?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="construction_start_date">Construction Start Date </label>
				<div class="controls">
					<input <?php echo $disabled; ?> type="text" class="live_datepicker" id="construction_start_date" name="construction_start_date" value="<?php if($stage_detail->construction_start_date != '1970-01-01'){ echo $stage_detail->construction_start_date; } ?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="maintainence_bond_date">Maintainence Bond Date </label>
				<div class="controls">
					<input <?php echo $disabled; ?> type="text" class="live_datepicker" id="maintainence_bond_date" placeholder="" name="maintainence_bond_date" value="<?php if($stage_detail->maintainence_bond_date != '1970-01-01'){ echo $stage_detail->maintainence_bond_date; }?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="feature_photo">Feature Photo </label>
				<div class="controls">
					<input type="file" id="feature_photo" name="feature_photo" value="">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="inputPassword"></label>
				<div class="controls">
					<input type="hidden" id="development_id" placeholder="" name="development_id" value="<?php echo $development_id; ?>">
					<input type="hidden" id="stage_no" name="stage_no" value="<?php echo $stage_no; ?>">
					<input type="hidden" id="fid" name="fid" value="<?php if(isset($stage_detail->fid)){ echo $stage_detail->fid; } ?>">

					<div class="save">
						<input type="submit" value="Submit" name="submit" />
					</div>
				</div>
			</div>
	    
		</div>

	</form>
</div>
<!-- MODAL Stage Add-->


<script>
    
    function email_dev_info(pid){
        
        $.ajax({
            url: "<?php print base_url(); ?>developments/email_development/"+pid,  
                dataType: 'html',  
                type: 'GET',  
                 
                success:     
                function(data){  
                 //console.log(data);
                 if(data){  
                     
                     
                     console.log(data);
                     alert(data);
                     
                   
                 }  
                }
        });
    }
    
   
    
</script>
<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo base_url();?>fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url();?>fancybox/jquery.fancybox.js"></script>



      <script type="text/javascript">
          
         
	$(document).ready(function() {
             $("#fancybox").fancybox();       
		
	});
        

    </script>

<script type="text/javascript">
// Can also be used with $(document).ready()
$(window).load(function() {
  $('.flexslider').flexslider({
       animation: "slide"
  });
});
</script>
 
<script>
  $(function() {
    $( document ).tooltip({

      position: {
        my: "center bottom-20",
        at: "center top",
        using: function( position, feedback ) {
          $( this ).css( position );
          $( "<div>" )
            .addClass( "arrow" )
            .addClass( feedback.vertical )
            .addClass( feedback.horizontal )
            .appendTo( this );
        }
      }
    });
  });
  </script>
  <style>
  .ui-tooltip, .arrow:after {
    background: #fff;
    border: 2px solid #002855;
  }
  .ui-tooltip {
    padding: 10px 10px;
    color: #000;
    border-radius: 5px;
    font: bold 12px "Helvetica Neue", Sans-Serif;
    text-align:center;
    
  }
.ui-tooltip {
 white-space: pre-line;
}
  .arrow {
    width: 70px;
    height: 16px;
    overflow: hidden;
    position: absolute;
    left: 50%;
    margin-left: -35px;
    bottom: -16px;
  }
  .arrow.top {
    top: -16px;
    bottom: auto;
  }
  .arrow.left {
    left: 20%;
  }
  .arrow:after {
    content: "";
    position: absolute;
    left: 20px;
    top: -20px;
    width: 25px;
    height: 25px;
    box-shadow: 6px 5px 9px -9px black;
    -webkit-transform: rotate(45deg);
    -ms-transform: rotate(45deg);
    transform: rotate(45deg);
  }
  .arrow.top:after {
    bottom: -20px;
    top: auto;
  }
  </style>