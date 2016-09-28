
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

       
        <div class="development-detail">
            <div id="development-detail-left">
                
            
                <div class="development-info">
                    <div class="box-title">Information<?php //echo $title; ?> </div>
                    <div class="development-info-table">
						<a href="#AddStage_<?php echo $stage_no; ?>" title="Update Stage" role="button" data-toggle="modal" class="add-stage">Update Stage</a>
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
            
            <div class="development-photo">
                <div class="box-title">Feature Photo </div>
                <div style="min-height: 280px; text-align:center; width: 100%;">
					<?php if(isset($stage_feature_photo->filename)){ 
						$image_link= base_url().'uploads/stage/'.$stage_feature_photo->filename;
						$imagedata = getimagesize($image_link);
						$image_width= $imagedata[0];
						$image_height= $imagedata[1];
						if($image_width <442 && $image_height<365){
							$width='';
							$height=$image_height;
						}
						else{
							if($image_height>$image_width){
								$width='';
								$height='365';
							}
							else{
								$width='100%';
								$height='';
							}
						}
					?>
                    <a id="fancybox" href="<?php echo base_url();?>uploads/stage/<?php  echo $stage_feature_photo->filename; ?>"><img width="<?php echo $width;?>" height="<?php echo $height;?>" src="<?php echo base_url();?>uploads/stage/<?php  echo $stage_feature_photo->filename; ?>"/></a>
					<?php } ?>
                </div>


            </div>
        </div>


<?php foreach($milestone_details as $milestone_detail) { ?>

<!-- MODAL Edit Milestone -->
<div id="EditMilestone_<?php echo $milestone_detail->id; ?>" class="milestone modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form class="form-horizontal" action="<?php echo base_url(); ?>stage/update_milestone/<?php echo $milestone_detail->id; ?>" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Edit Milestone</h3>
	</div>
	<div class="modal-body">

		<div class="milestone-left">

			<div class="control-group">
				<label class="control-label milestone-title-label" for="milestone_title">Milestone Name </label>
				<div class="controls">
					<input type="text" id="milestone-title" name="milestone_title" value="<?php echo $milestone_detail->milestone_title; ?>" required="">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label milestone-phases-label" for="milestone_phases">Milestone Phases </label>
				<div class="controls milestone-phases">

					<?php 
						$i = 1;
						//$development_id = $development_details->id;
						$phase_query =$this->db->query("SELECT * FROM stage_phase where development_id=$development_id and stage_no=$stage_no");
						$phase_result = $phase_query->result();

						$milestone_phases = $milestone_detail->milestone_phases;
						$milestone_phases_arr = explode(",", $milestone_phases);

						foreach($phase_result as $phase_row)
						{
							$milestone_phase_id = '';
							for($a = 0; $a < count($milestone_phases_arr); $a++){
								if($milestone_phases_arr[$a] == $phase_row->id){
									$milestone_phase_id = 'checked';
									break;
								}else{
									$milestone_phase_id = '';
								}
							}
					?>
						
						<div class="milestone-phase-list">
						<?php echo $phase_row->phase_name; ?>
						<input <?php echo $milestone_phase_id; ?> id="<?php echo 'phase_'.$i; ?>" type="checkbox" name="phase_ids[]" value="<?php echo $phase_row->id; ?>">	
						</div>					
					<?php
						$i++;
						}
					?>
						
				</div>
			</div>
			
			<div class="control-group control-milestone-date">
				<label class="control-label milestone-date-label" for="milestone_date">Date </label>
				<div class="controls">
					<input type="text" id="milestone-date" name="milestone_date" value="<?php echo $this->wbs_helper->to_report_date($milestone_detail->milestone_date); ?>" required="">
					<a id="delete-milestone" style="margin-left:10px;" href="<?php echo base_url(); ?>stage/milestone_delete/<?php echo $milestone_detail->development_id; ?>/<?php echo $milestone_detail->stage_no; ?>/<?php echo $milestone_detail->id; ?>">
						<img alt="Delete Milestone" src="<?php echo base_url(); ?>icon/icon_delete.png" width="" height="" />
					</a>
				</div>
			</div>

		</div>
		
		<div class="milestone-right">

			<div class="control-group">
				<label class="control-label milestone-select-color-label" for="milestone_select_color">Select One </label>
				<div class="controls milestone-select-color">
					<div class="urban-plan-concept"><p class="color"></p>Urban Plan Concept<input <?php if($milestone_detail->milestone_select_color == '#a1d49c') { echo 'checked'; } ?> type="radio" name="milestone_select_color" value="#a1d49c" required=""></div>
					<div class="consultation"><p class="color"></p>Consultation<input <?php if($milestone_detail->milestone_select_color == '#7bcbc8') { echo 'checked'; } ?> type="radio" name="milestone_select_color" value="#7bcbc8"></div>
					<div class="building-design"><p class="color"></p>Building Design<input <?php if($milestone_detail->milestone_select_color == '#fff79a') { echo 'checked'; } ?> type="radio" name="milestone_select_color" value="#fff79a"></div>
					<div class="working-drawings"><p class="color"></p>Working Drawings<input <?php if($milestone_detail->milestone_select_color == '#ff9899') { echo 'checked'; } ?> type="radio" name="milestone_select_color" value="#ff9899"></div>
					<div class="resource-consent"><p class="color"></p>Resource Consent<input <?php if($milestone_detail->milestone_select_color == '#ffd799') { echo 'checked'; } ?> type="radio" name="milestone_select_color" value="#ffd799"></div>
					<div class="building-permits"><p class="color"></p>Building Permits<input <?php if($milestone_detail->milestone_select_color == '#da99ff') { echo 'checked'; } ?> type="radio" name="milestone_select_color" value="#da99ff"></div>
					<div class="development-construction"><p class="color"></p>Development Construction<input <?php if($milestone_detail->milestone_select_color == '#c69c6c') { echo 'checked'; } ?> type="radio" name="milestone_select_color" value="#c69c6c"></div>
					<div class="construction"><p class="color"></p>Construction<input <?php if($milestone_detail->milestone_select_color == '#cccccc') { echo 'checked'; } ?> type="radio" name="milestone_select_color" value="#cccccc"></div>
					<div class="completion"><p class="color"></p>Completion<input <?php if($milestone_detail->milestone_select_color == '#a0ff7f') { echo 'checked'; } ?> type="radio" name="milestone_select_color" value="#a0ff7f"></div>
					<div class="titles-due"><p class="color"></p>Titles Due<input <?php if($milestone_detail->milestone_select_color == '#464646') { echo 'checked'; } ?> type="radio" name="milestone_select_color" value="#464646"></div>
				</div>
			</div>

			<div class="control-group control-milestone-submit">
				<div class="controls">
					<input type="hidden" id="development_id" name="development_id" value="<?php echo $development_id; ?>">	
					<input type="hidden" id="stage_no" name="stage_no" value="<?php echo $stage_no; ?>">			
					<div class="milestone-save">
						<input type="submit" value="Update Milestone" name="submit" />
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


<?php } ?>


<!-- MODAL Add New Milestone -->
<div id="AddNewMilestone" class="milestone modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<form class="form-horizontal" action="<?php echo base_url(); ?>stage/add_new_milestone" method="POST">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
		<h3 id="myModalLabel">Add Milestone</h3>
	</div>
	<div class="modal-body">

		<div class="milestone-left">

			<div class="control-group">
				<label class="control-label milestone-title-label" for="milestone_title">Milestone Name </label>
				<div class="controls">
					<input type="text" id="milestone-title" name="milestone_title" value="" required="">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label milestone-phases-label" for="milestone_phases">Milestone Phases </label>
				<div class="controls milestone-phases">

					<?php 
						$i = 1;
						//$development_id = $development_details->id;
						$phase_query =$this->db->query("SELECT * FROM stage_phase where development_id=$development_id and stage_no=$stage_no");
						$phase_result = $phase_query->result();
						
						foreach($phase_result as $phase_row)
						{

					?>

						<div class="milestone-phase-list">
						<?php echo $phase_row->phase_name; ?>
						<input id="<?php echo 'phase_'.$i; ?>" type="checkbox" name="phase_ids[]" value="<?php echo $phase_row->id; ?>">	
						</div>					
					<?php
						$i++;
						}
					?>
						
				</div>
			</div>
			
			<div class="control-group control-milestone-date">
				<label class="control-label milestone-date-label" for="milestone_date">Date </label>
				<div class="controls">
					<input type="text" id="milestone-date" name="milestone_date" value="" required="">
				</div>
			</div>

		</div>
		
		<div class="milestone-right">

			<div class="control-group">
				<label class="control-label milestone-select-color-label" for="milestone_select_color">Select One </label>
				<div class="controls milestone-select-color">
					<div class="urban-plan-concept"><p class="color"></p>Urban Plan Concept<input type="radio" name="milestone_select_color" value="#a1d49c" required=""></div>
					<div class="consultation"><p class="color"></p>Consultation<input type="radio" name="milestone_select_color" value="#7bcbc8"></div>
					<div class="building-design"><p class="color"></p>Building Design<input type="radio" name="milestone_select_color" value="#fff79a"></div>
					<div class="working-drawings"><p class="color"></p>Working Drawings<input type="radio" name="milestone_select_color" value="#ff9899"></div>
					<div class="resource-consent"><p class="color"></p>Resource Consent<input type="radio" name="milestone_select_color" value="#ffd799"></div>
					<div class="building-permits"><p class="color"></p>Building Permits<input type="radio" name="milestone_select_color" value="#da99ff"></div>
					<div class="development-construction"><p class="color"></p>Development Construction<input type="radio" name="milestone_select_color" value="#c69c6c"></div>
					<div class="construction"><p class="color"></p>Construction<input type="radio" name="milestone_select_color" value="#cccccc"></div>
					<div class="completion"><p class="color"></p>Completion<input type="radio" name="milestone_select_color" value="#a0ff7f"></div>
					<div class="titles-due"><p class="color"></p>Titles Due<input type="radio" name="milestone_select_color" value="#464646"></div>
				</div>
			</div>

			<div class="control-group control-milestone-submit">
				<div class="controls">
					<input type="hidden" id="development_id" name="development_id" value="<?php echo $development_id; ?>">	
					<input type="hidden" id="stage_no" name="stage_no" value="<?php echo $stage_no; ?>">			
					<div class="milestone-save">
						<input type="submit" value="Add Milestone" name="submit" />
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
					<input type="text" id="construction_start_date" placeholder="" name="construction_start_date" value="<?php if(isset($stage_detail->construction_start_date)) {if($stage_detail->construction_start_date > '0000-00-00'){ echo $stage_detail->construction_start_date; } }?>">
				</div>
			</div>

			<div class="control-group">
				<label class="control-label" for="maintainence_bond_date">Maintainence Bond Date </label>
				<div class="controls">
					<input type="text" id="maintainence_bond_date" placeholder="" name="maintainence_bond_date" value="<?php if(isset($stage_detail->maintainence_bond_date)) {if($stage_detail->maintainence_bond_date > '0000-00-00'){ echo $stage_detail->maintainence_bond_date; } }?>">
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