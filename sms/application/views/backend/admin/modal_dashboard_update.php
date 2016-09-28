
<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('update_dashboard');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open(base_url() . 'admin/dashboard_update/' , array('class' => 'form-horizontal form-groups-bordered', 'enctype' => 'multipart/form-data'));?>
                
					<div class="form-group">
						<label for="field-2" class="col-sm-3 control-label">&nbsp&nbsp</label>
                        
						<div class="col-sm-5">
							<select onchange="twoOption();" name="two_option" id="two_option" class="form-control" required="">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <option value="1" ><?php echo get_phrase('manage_class');?></option>
                              <option value="2"><?php echo get_phrase('choose_text_field');?></option>
                          </select>
						</div> 
					</div>
					
					
					<div class="form-group" id="classes" style="display: none;">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                        
						<div class="col-sm-5">
							<select name="class_id" class="form-control">
                              <option value=""><?php echo get_phrase('select');?></option>
                              <?php 
                              	$this->db->where('company_id', $this->session->userdata('user')->company_id);
								$classes = $this->db->get('sms_class')->result_array();
								foreach($classes as $row2):
									?>
	                        		<option value="<?php echo $row2['class_id'];?>">
												<?php echo $row2['name'];?>
	                                        </option>
	                            <?php
								endforeach;
								  ?>
                          </select>
						</div> 
					</div>
					
					<div class="form-group" id="day" style="display: none;">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('day');?></label>
                        <div class="col-sm-5">
                            <select name="day" class="form-control" style="width:100%;">
                                <option value="sunday">sunday</option>
                                <option value="monday">monday</option>
                                <option value="tuesday">tuesday</option>
                                <option value="wednesday">wednesday</option>
                                <option value="thursday">thursday</option>
                                <option value="friday">friday</option>
                                <option value="saturday">saturday</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="form-group" id="time_start" style="display: none;">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('starting_time');?></label>
                        <div class="col-sm-5">
                        	<input type="text" class="time start form-control" name="time_start" />
                            
                        </div>
                    </div>
                    <div class="form-group" id="time_end" style="display: none;">
                        <label class="col-sm-3 control-label"><?php echo get_phrase('ending_time');?></label>
                        <div class="col-sm-5">
                        	<input type="text" class="time end form-control" name="time_end" />
                        </div>
                    </div>
					
					<div class="form-group" id="text" style="display: none;">
						<label for="field-2" class="col-sm-3 control-label">&nbsp&nbsp</label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control" name="text" >
						</div> 
					</div>
					
					<div class="form-group" id="date" style="display: none;">
						<label for="field-2" class="col-sm-3 control-label"><?php echo get_phrase('date');?></label>
                        
						<div class="col-sm-5">
							<input type="text" class="form-control date" name="date" >
						</div> 
					</div>
                    
                    <div class="form-group" id="submit" style="display: none;">
						<div class="col-sm-offset-3 col-sm-5">
							<button type="submit" class="btn btn-info"><?php echo get_phrase('update');?></button>
						</div>
					</div>
                <?php echo form_close();?>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">

	$(document).ready(function () {
        $('.date').datepicker({
            format: "dd-mm-yyyy"
        }); 
        
    });
    $(document).ready(function(){
	    $('.time').timepicker({
            'showDuration': true,
            'timeFormat': 'H:i'
        });
	});

	function twoOption(){
		two_option = $('#two_option').val();
    	if(two_option==1){
    		$('#text').css('display','none');
    		$('#date').css('display','none');
			$('#classes').css('display','block');
			$('#day').css('display','block');
			$('#time_start').css('display','block');
			$('#time_end').css('display','block');
			
			$('#submit').css('display','block');
		}else if(two_option==2){
			$('#classes').css('display','none');
			$('#day').css('display','none');
			$('#time_start').css('display','none');
			$('#time_end').css('display','none');
			$('#text').css('display','block');
    		$('#date').css('display','block');
    		
    		$('#submit').css('display','block');
		}else{
			$('#classes').css('display','none');
			$('#day').css('display','none');
			$('#time_start').css('display','none');
			$('#time_end').css('display','none');
			$('#text').css('display','none');
    		$('#date').css('display','none');
    		
    		$('#submit').css('display','none');
		}		
    }

</script>