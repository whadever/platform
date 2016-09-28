<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('class_schedule_list');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_class_schedule');?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------>
        
	
		<div class="tab-content">
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane active" id="list">
				<div class="panel-group joined" id="accordion-test-2">
				
					<div class="table-responsive class-routine">
	                    <table cellpadding="0" cellspacing="0" border="0"  class="table">
	                    	<thead>
	                    		<tr>
	                    			<th><?php echo get_phrase('monday');?></th>
	                    			<th><?php echo get_phrase('tuesday');?></th>
	                    			<th><?php echo get_phrase('wednesday');?></th>
	                    			<th><?php echo get_phrase('thursday');?></th>
	                    			<th><?php echo get_phrase('friday');?></th>
	                    			<th><?php echo get_phrase('saturday');?></th>
	                    			<th><?php echo get_phrase('sunday');?></th>
	                    		</tr>
	                    	</thead>
	                        <tbody>
	                            <?php 
	                            $this->db->select('sms_class_routine.*, sms_class.name');
								$this->db->join('sms_class', 'sms_class.class_id = sms_class_routine.class_id','left');
								$this->db->where('company_id', $this->session->userdata('user')->company_id);
	                            $class_routine = $this->db->get('sms_class_routine')->result();
	                            foreach($class_routine as $row):
	                            ?>
	                            <tr>
	                                <td>
	                                	<?php if($row->day=='monday'){ echo '<div class="class">'.$row->name.'<br>'.$row->time_start.'-'.$row->time_end.'</div>'; } ?>
	                                </td>
	                                <td><?php if($row->day=='tuesday'){ echo '<div class="class">'.$row->name.'<br>'.$row->time_start.'-'.$row->time_end.'</div>'; } ?></td>
	                                <td><?php if($row->day=='wednesday'){ echo '<div class="class">'.$row->name.'<br>'.$row->time_start.'-'.$row->time_end.'</div>'; } ?></td>
	                                <td><?php if($row->day=='thursday'){ echo '<div class="class">'.$row->name.'<br>'.$row->time_start.'-'.$row->time_end.'</div>'; } ?></td>
	                                <td><?php if($row->day=='friday'){ echo '<div class="class">'.$row->name.'<br>'.$row->time_start.'-'.$row->time_end.'</div>'; } ?></td>
	                                <td><?php if($row->day=='saturday'){ echo '<div class="class">'.$row->name.'<br>'.$row->time_start.'-'.$row->time_end.'</div>'; } ?></td>
	                                <td><?php if($row->day=='sunday'){ echo '<div class="class">'.$row->name.'<br>'.$row->time_start.'-'.$row->time_end.'</div>'; } ?></td>
	                            </tr>
	                            <?php endforeach;?>
	                            
	                        </tbody>
	                    </table>
                    </div>
  				</div>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			<!----CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open(base_url() . 'admin/class_routine/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                                <div class="col-sm-5">
                                    <select required="" name="class_id" class="form-control" style="width:100%;"
                                        onchange="return get_class_subject(this.value)">
                                        <option value=""><?php echo get_phrase('select_class');?></option>
                                    	<?php 
                                    	$this->db->where('company_id', $this->session->userdata('user')->company_id);
										$classes = $this->db->get('sms_class')->result_array();
										foreach($classes as $row):
										?>
                                    		<option value="<?php echo $row['class_id'];?>"><?php echo $row['name'];?></option>
                                        <?php
										endforeach;
										?>
                                    </select>
                                </div>
                            </div>
                            <!---<div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('subject');?></label>
                                <div class="col-sm-5">
                                    <select name="subject_id" class="form-control" style="width:100%;" id="subject_selection_holder">
                                        <option value=""><?php echo get_phrase('select_class_first');?></option>
                                    	
                                    </select>
                                </div>
                            </div>--->
                            <div class="form-group">
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
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('starting_time');?></label>
                                <div class="col-sm-5">
                                	<input required="" type="text" class="time start form-control" name="time_start" />
                                    
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('ending_time');?></label>
                                <div class="col-sm-5">
                                	<input required="" type="text" class="time end form-control" name="time_end" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo 'frequency';?></label>
                                <div class="col-sm-5">
                                	<select required="" name="frequency" class="form-control" style="width:100%;">
                                		<option value="">Select Frequency</option>
                                        <option value="daily">Daily</option>
                                        <option value="weekly">Weekly</option>
                                    </select>
                                </div>
                            </div>
                        <div class="form-group">
                              <div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_class_routine');?></button>
                              </div>
							</div>
                    </form>                
                </div>                
			</div>
			<!----CREATION FORM ENDS-->
            
		</div>
	</div>
</div>

<script type="text/javascript">
    function get_class_subject(class_id) {
        $.ajax({
            url: '<?php echo base_url();?>admin/get_class_subject/' + class_id ,
            success: function(response)
            {
                jQuery('#subject_selection_holder').html(response);
            }
        });
    }
    $(document).ready(function(){
	    $('.time').timepicker({
            'showDuration': true,
            'timeFormat': 'H:i'
        });
	});
</script>

