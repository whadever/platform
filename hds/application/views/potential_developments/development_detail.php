<?php 

$save_developments_img = array(
          'src' => base_url().'images/icon/btn_horncastle_save.png',
          'alt' => 'Save Developments Info',
          'class' => 'save-developments',
          'width' => '60',          
          'title' => 'Save Developments Info',
          'style'=>''
          
);

$print_developments_img = array(
          'src' => base_url().'images/icon/btn_horncastle_printer.png',
          'alt' => 'Print Developments Info',
          'class' => 'print-developments',
          'width' => '60',          
          'title' => 'Print Developments Info',
          'style'=>''
          
);


$email_developments_img = array(
          'src' => base_url().'images/icon/btn_horncastle_mail.png',
          'alt' => 'Email Developments Info',
          'class' => 'email-developments',
          'width' => '60',          
          'title' => 'Email Developments Info',
          'style'=>''
          
);

$emaildata = array(
    'name' => 'development_email_btn',
    'id' => 'development_email_btn',
    'class' => 'btn_dev_info',
    'value' => 'true',
    'type' => 'button',
    'title' => 'Email Developments Info',
    'content' => img($email_developments_img),
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

<style>
.btn_dev_info {
    margin: 10px 5px 0 0;
}
</style>
<?php
$user = $this->session->userdata('user');
$user_app_role_id = $user->application_role_id; 
?>     
        <div class="development-detail">
            <div id="development-detail-left">
                
            
                <div class="development-info">
                    <div class="box-title">Information<?php //echo $title; ?> </div>
                    <div class="development-info-table">
						<a style="display:none;" href="#UpdateDevelopment" title="Update Development" role="button" data-toggle="modal" class="add-stage update-development">Update Development</a>
						<?php if(isset($table)) { echo $table;	} ?> 
					</div>               
           
                    
                </div>
				<div class="clear"></div>
                <div class="button-box" style="padding:0px; margin:0px;">
                    <?php                  
                   

                  
                    echo anchor_popup(base_url().'potential_developments/print_development/'.$development_id, img($print_developments_img), $atts);                    
                    echo anchor(base_url().'potential_developments/pdf_developments/'.$development_id, img($save_developments_img), array('title' => 'Save Developments Info', 'class'=> 'btn_dev_info'));
                    //echo form_button($emaildata);                    
                    //print_r($development_details); 
                    $to='';
                    ?>
					<a class="btn_dev_info" href='mailto:<?php echo $to; ?>?subject=Development%20Information&amp;body=Developments Name : 
					<?php echo $development_details->development_name; ?>%0D%0ADevelopment Location : 
					<?php echo $development_details->development_location; ?>%0D%0ADevelopment Size : 
					<?php echo $development_details->development_size; ?>%0D%0ALand Zone : 
					<?php echo $development_details->land_zone; ?>%0D%0AGround Condition : 
					<?php echo $development_details->ground_condition; ?>%0D%0ANumber of Stages : 
					<?php echo $development_details->number_of_stages; ?>%0D%0ANumber of Lots : 
					<?php echo $development_details->number_of_lots; ?>%0D%0AProject Manager : 
					<?php echo $development_details->project_manager; ?>%0D%0ACivil Engineer : 
					<?php echo $development_details->civil_engineer; ?>%0D%0ACivil Manager : 
					<?php echo $development_details->civil_manager; ?>%0D%0AGeo Tech Engineer : 
					<?php echo $development_details->geo_tech_engineer; ?>%0D%0AFeature Photo Link : 
					<?php if(isset($feature_photo->filename)){echo base_url();?>uploads/development/<?php echo $feature_photo->filename; }else{ echo 'No Feature Image';}?>.&attachment=""D:\daily_works_doc.docx""'>
                   	<?php echo img($email_developments_img);?></a>
					<div class="clear"></div>
                    </div>
            </div>
            <?php 
				//print_r($feature_photos);
            ?>
            
            <div class="development-photo all-feature-img">
                <div class="box-title">Feature Photo </div>
                <div style="min-height: 280px; text-align:center; width: 100%;">
				
        <div class="flexslider" style="border:0px solid #000 !important;">
         
          <ul class="slides">
					<?php
						foreach($feature_photos as  $feature_photo){

            	if(isset($feature_photo->filename)){
            	 $image_link =base_url().'uploads/development/'.$feature_photo->filename;
            	}
            	else{
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
                    <a class="fancybox" rel="gallery1" href="<?php echo $image_link; ?>"><img style="width:<?php echo $width;?>;height:<?php echo $height;?>;" src="<?php echo $image_link; ?>"/></a>
					</li>
<?php } ?>
					</ul></div><!-- flexslider -->
                </div>


            </div>
        </div>
<div class="clear"></div>
<style>
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
<div id="milestone_overview">
	<h6 align="center">Milestone-Overview</h6>

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

		 $ci = & get_instance();
		$ci->load->model('potential_stage_model'); 
		
		$stage_start_date_array = array();
		$stage_end_date_array = array();

		for($i=0; $i<count($stage_milestone_details); $i++){

			$date_arr = array();

			$stage = $ci->potential_stage_model->get_stage_detail($development_id, $stage_milestone_details[$i]->stage_no)->row();
			
			$stage_start_date_array[] = $stage->construction_start_date;
			
				
			$date_arr[] = $stage_milestone_details[$i]->urban_plan_concept;	

			$date_arr[] = $stage_milestone_details[$i]->consultation;
			$date_arr[] = $stage_milestone_details[$i]->building_design;
			$date_arr[] = $stage_milestone_details[$i]->working_drawings;
			//$date_arr[] = $stage_milestone_details[$i]->working_drawings_contractor;
			$date_arr[] = $stage_milestone_details[$i]->resource_consent;
			$date_arr[] = $stage_milestone_details[$i]->titles_due_out;
			$date_arr[] = $stage_milestone_details[$i]->building_permits;
			
			$date_arr[] = $stage_milestone_details[$i]->construction_earthworks;
			$date_arr[] = $stage_milestone_details[$i]->construction_civil;
			$date_arr[] = $stage_milestone_details[$i]->construction_roading;
			$date_arr[] = $stage_milestone_details[$i]->construction_general;
			$date_arr[] = $stage_milestone_details[$i]->completion;
			
			
			//$stage_phase_days_length=array();
			$stage_phase_days_length[$i][] = $date_arr;

			$max = max(array_map('strtotime', $date_arr));
			$end_date =  date('Y-m-d', $max);

			$stage_end_date_array[] = $end_date;
			
			$difference = abs(strtotime($end_date) - strtotime($stage->construction_start_date));
			$stage_days_difference[] = floor(($difference )/ (60*60*24));
	
			
			
		}//stage loop

		$min = min(array_map('strtotime', $stage_start_date_array));
		$milestone_start_date =  date('Y-m-d', $min);
		$milestone_start_year =  date('Y', $min);
		$milestone_start_month =  date('m', $min);
		//echo 'Start Date '.$milestone_start_date.'<br/>'; 


		$max = max(array_map('strtotime', $stage_end_date_array));
		$milestone_end_date =  date('Y-m-d', $max);
		$milestone_end_year =  date('Y', $max);
		$milestone_end_month =  date('m', $max);
		//echo 'End Date '.$milestone_end_date.'<br/>'; 
		
		
		$difference = abs(strtotime($milestone_end_date) - strtotime($milestone_start_date));
		$total_days = floor(($difference )/ (60*60*24));
		//echo 'Total days '.$total_days.'<br/>';

		$year_diff= $milestone_end_year - $milestone_start_year; 
		$month_diff = (($milestone_end_year - $milestone_start_year) * 12) + ($milestone_end_month - $milestone_start_month);
		if($year_diff<=5){
			if($month_diff<=12){
				$width_per = (100)/($month_diff+2);
			}else{
				$width_per = (100*$year_diff)/($month_diff+$year_diff+$year_diff);
			}
			
			
		}else{
			$width_per = 100/($year_diff*2+2);
		}
	

		for($i=0; $i<count($stage_milestone_details); $i++){

			echo "<div class='stage-block' style=''>";
			echo "<div style='width:11%;float:left;padding:5px 2%;'>Stage ".$stage_milestone_details[$i]->stage_no;		
			echo "</div>";

		
			
			$difference = abs(strtotime($stage_start_date_array[$i]) - strtotime($milestone_start_date));
			$total_days_margin_left = floor(($difference )/ (60*60*24));
			
			$margin_percent = ($total_days_margin_left/$total_days)*100; 

			$stage_width = ($stage_days_difference[$i]/$total_days)*100; 

			echo "<div style='background:rgba(0, 0, 0, 0.5) repeating-linear-gradient(to right, #cccccc, #cccccc 1px, #ffffff 1px, #ffffff 5%) repeat scroll 0 25%;float:left; width:89%;border-left:4px solid #002855;'>";
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
			<span title="Scheme Concept &#013;Completion Date: <?php echo date('d-m-Y', strtotime($date_arr[0]));?>" class="stone-block" style="background:#FABF8F; width:<?php echo ($piece[0]/$stage_days_difference[$i])*100;?>%"></span>
			<span title="Consultation Vault &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[1]));?>" class="stone-block" style="background:#FFC000; width:<?php echo ($piece[1]/$stage_days_difference[$i])*100;?>%"></span>
			<span title="Building Design &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[2]));?>" class="stone-block" style="background:#00B0F0; width:<?php echo ($piece[2]/$stage_days_difference[$i])*100;?>%"></span>
			<span title="Architectural Drawings &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[3]));?>" class="stone-block" style="background:#FF0000; width:<?php echo ($piece[3]/$stage_days_difference[$i])*100;?>%"></span>
			
			<span title="Resource Consenting &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[4]));?>" class="stone-block" style="background:#948A54; width:<?php echo ($piece[4]/$stage_days_difference[$i])*100;?>%"></span>
			<span title="224 & Title Release &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[5]));?>" class="stone-block" style="background:#7030A0; width:<?php echo ($piece[5]/$stage_days_difference[$i])*100;?>%"></span>
			<span title="Building Consent &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[6]));?>" class="stone-block" style="background:#92D050; width:<?php echo ($piece[6]/$stage_days_difference[$i])*100;?>%"></span>
			
			<span title="Earthworks &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[7]));?>" class="stone-block" style="background:#FF66FF; width:<?php echo ($piece[7]/$stage_days_difference[$i])*100;?>%"></span>
			<span title="Civil Services &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[8]));?>" class="stone-block" style="background:#B1A0C7; width:<?php echo ($piece[8]/$stage_days_difference[$i])*100;?>%"></span>
			
			<span title="Roading &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[9]));?>" class="stone-block" style="background:#B7DEE8; width:<?php echo ($piece[9]/$stage_days_difference[$i])*100;?>%"></span>
			<span title="Construction &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[10]));?>" class="stone-block" style="background:#FFFF00; width:<?php echo ($piece[10]/$stage_days_difference[$i])*100;?>%"></span>
			<span title="Completion & Landscaping &#013;Completion Date: <?php echo  date('d-m-Y', strtotime($date_arr[11]));?>" class="stone-block" style="background:#000000; width:<?php echo ($piece[11]/$stage_days_difference[$i])*100;?>%"></span>
			
			</div>


			<?php

			echo "</div></div>";
			echo "<div class='clear'></div>";
			echo "</div>";
			

		}
		

 	?>
	<?php if($month_diff!=0){ 
		
		
	?>
	
	
	<div class="bg-stage-gp" style="width:100%; padding-left:11%;padding-top:5px; background-color:#002855;overflow:hidden;color:#fff; text-align:center;">

	<?php 
		
		$month_array= array("JAN", "FEB", "MAR", "APRIL", "MAY", "JUN", "JULY", "AUG", "SEP", "OCT", "NOV", "DEC");
		//echo $year_diff.'-'.$milestone_start_year.'-'.$milestone_end_year; echo '<br>';
		//echo $month_diff.'-'.$milestone_start_month.'-'.$milestone_end_month;
		$mi_count = count($stage_milestone_details);
		$height = 30*$mi_count;
		$mar_top = 21+(30*$mi_count);
		if($year_diff<=0){
		
			$j= $milestone_start_year;
			$k = $milestone_start_month-1;
			for($i=0; $i<=$month_diff+1; $i++){
				if(date("m") == $k+1)
				{ 
					$days = date('d');
					$marg = (66*$days)/365;
					$hh =  '<div id="today_bar_dev" style="margin-top: -'.$mar_top.'px;height: '.$height.'px;margin-left:'.$marg.'%;"></div>';
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
				if( date("Y") == $j && ( date("m") > $k  ) && ( date("m") <= $k + $year_diff  ) )
				{ 
					$d = 1;
					$m = $k+1;
					$sd = date("Y-$m-$d");

					$now = time();
				    $your_date = strtotime($sd);
				    $datediff = $now - $your_date;
				    $days = floor($datediff/(60*60*24));

					$marg = (66*$days)/(365*$year_diff);
					$hh =  '<div id="today_bar_dev" style="margin-top: -'.$mar_top.'px;height: '.$height.'px;margin-left:'.$marg.'%;"></div>';
				}
				else
				{
					$hh = '';
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
					$hh =  '<div id="today_bar_dev" style="margin-top: -'.$mar_top.'px;height: '.$height.'px;margin-left:'.$marg.'%;"></div>';
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
					$hh1 = '<div id="today_bar_dev" style="margin-top: -'.$mar_top.'px;height: '.$height.'px;margin-left:'.$marg.'%;"></div>';
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
	<?php 	} ?>
</div> <!-- end main Stage div -->











<!-- MODAL Development Update-->
<div id="UpdateDevelopment" class="modal stage hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form class="form-horizontal" enctype="multipart/form-data" action="<?php echo base_url();?>potential_developments/development_update/<?php echo $development_details->id; ?>" method="POST">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true"> X </button>
			<h3 id="myModalLabel">Update Development Info</h3>
		</div>
		<div class="modal-body">
				
			<div class="control-group">
				<label class="control-label" for="development_name">Development Name </label>
				<div class="controls">
					<input type="text" id="development_name" placeholder="" name="development_name" value="<?php if(isset($development_details->development_name)){ echo $development_details->development_name; } ?>">
				</div>
			</div>
			<div class="control-group">
				<label class="control-label" for="development_location">Development Location </label>
				<div class="controls">
					<input type="text" id="development_location" placeholder="" name="development_location" value="<?php if(isset($development_details->development_location)){ echo $development_details->development_location; } ?>">
				</div>
			</div>

			
			<div class="control-group">
				<label class="control-label" for="development_city">Development City </label>
				<div class="controls">
					<select name="development_city" id="development_city">
						<option value="">-- Choose City --</option>
						<option <?php if($development_details->development_city == 'Christchurch'){ echo 'selected'; } ?> value="Christchurch">Christchurch</option>
						<option <?php if($development_details->development_city == 'Auckland'){ echo 'selected'; } ?> value="Auckland">Auckland</option>
					</select>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="development_size">Development Size </label>
				<div class="controls">
					<input type="text" id="development_size" placeholder="" name="development_size" value="<?php if(isset($development_details->development_size)){ echo $development_details->development_size; } ?>">
				</div>
			</div>
			
			<div class="control-group">
				<label class="control-label" for="number_of_stages">Number Of Stages </label>
				<div class="controls">
					<input type="text" id="number_of_stages" placeholder="" name="number_of_stages" value="<?php if(isset($development_details->number_of_stages)){ echo $development_details->number_of_stages; } ?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="number_of_lots">Number Of Lots </label>
				<div class="controls">
					<input type="text" id="number_of_lots" placeholder="" name="number_of_lots" value="<?php if(isset($development_details->number_of_lots)) { echo $development_details->number_of_lots; }?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="land_zone">Land Zone </label>
				<div class="controls">
					<input type="text" id="land_zone" placeholder="" name="land_zone" value="<?php if(isset($development_details->land_zone)) { echo $development_details->land_zone; }?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="ground_condition">Ground Condition </label>
				<div class="controls">
					<input type="text" id="ground_condition" placeholder="" name="ground_condition" value="<?php if(isset($development_details->ground_condition)) { echo $development_details->ground_condition; }?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="project_manager">Project Manager </label>
				<div class="controls">
					<input type="text" id="project_manager" placeholder="" name="project_manager" value="<?php if(isset($development_details->project_manager)) { echo $development_details->project_manager; }?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="civil_engineer">Civil Engineer </label>
				<div class="controls">
					<input type="text" id="civil_engineer" placeholder="" name="civil_engineer" value="<?php if(isset($development_details->civil_engineer)) { echo $development_details->civil_engineer; }?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="civil_manager">Civil Manager </label>
				<div class="controls">
					<input type="text" id="civil_manager" placeholder="" name="civil_manager" value="<?php if(isset($development_details->civil_manager)) { echo $development_details->civil_manager; }?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="geo_tech_engineer">Geo Tech Engineer </label>
				<div class="controls">
					<input type="text" id="geo_tech_engineer" placeholder="" name="geo_tech_engineer" value="<?php if(isset($development_details->geo_tech_engineer)) { echo $development_details->geo_tech_engineer; }?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="status">Status </label>
				<div class="controls">
					<select name="status" id="status">
						<option <?php if($development_details->status == '0'){ echo 'selected'; } ?> value="0">Open</option>
						<option <?php if($development_details->status == '1'){ echo 'selected'; } ?> value="1">Close</option>
					</select>
				</div>
			</div>

			<div class="control-group" style="display:none;">
				<label class="control-label" for="feature_photo">Feature Photo </label>
				<div class="controls">
					<input type="file" id="feature_photo" name="feature_photo">
					<?php if(isset($feature_photo->filename)){ echo '<span style="margin-left:34%;">'.$feature_photo->filename.'</span>'; } ?>
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="inputPassword"></label>
				<div class="controls">
					<div class="save">
						<input type="hidden" value="<?php echo $development_details->fid; ?>" name="fid" />
						<input type="submit" value="Submit" name="submit" />
					</div>
				</div>
			</div>
	    
		</div>

	</form>
</div>
<!-- MODAL Development Update-->

<script>
    
    function email_dev_info(pid){
        
        $.ajax({
            url: "<?php print base_url(); ?>potential_developments/email_development/"+pid,  
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

<script type="text/javascript">
// Can also be used with $(document).ready()
$(window).load(function() {
  $('.flexslider').flexslider({
       animation: "slide"
  });
});
</script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo base_url();?>fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url();?>fancybox/jquery.fancybox.js"></script>


    <script type="text/javascript">
		$(document).ready(function() {
             $(".fancybox").fancybox();       
		
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
    font: bold 14px "Helvetica Neue", Sans-Serif;
    text-align:center;
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