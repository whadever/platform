<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group  pull-right">
			<a href="<?php echo base_url().'admin/mark_export_to_excel'; ?>" class="btn btn-default">
			<?php echo 'EXCEL';?></a>
			<a href="<?php echo base_url().'admin/marks_exam_pdf'; ?>" target="_blank" class="btn btn-default">
			<?php echo get_phrase('all_exams');?></a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('manage_marks');?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------>
        
	
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane  <?php if(!isset($edit_data) && !isset($personal_profile) && !isset($academic_result) )echo 'active';?>" id="list">
				<center>
                <?php echo form_open(base_url() . 'admin/marks');?>
                <table border="0" cellspacing="0" cellpadding="0" class="table table-bordered">
                	<tr>
                        <td><?php echo get_phrase('select_exam');?></td>
                        <td><?php echo get_phrase('select_class');?></td>
                        <!---<td><?php echo get_phrase('select_subject');?></td>--->
                        <td>&nbsp;</td>
                	</tr>
                	<tr>
                        <td>
                        	<select onchange="loadClass();" id="exam_id" name="exam_id" class="form-control"  style="float:left;">
                                <option value=""><?php echo get_phrase('select_an_exam');?></option>
                                <?php 
                                $this->db->where('company_id', $this->session->userdata('user')->company_id);
                                $exams = $this->db->get('sms_exam')->result_array();
                                foreach($exams as $row):
                                ?>
                                    <option value="<?php echo $row['exam_id'];?>"
                                        <?php if($exam_id == $row['exam_id'])echo 'selected';?>>
                                            <?php //echo get_phrase('class');?><?php echo $row['name'];?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </td>
                        <td>
                        	<select name="class_id" id="class_id" class="form-control" style="float:left;">
                                <option value=""><?php echo get_phrase('select_a_class');?></option>
                                <?php 
                                if($class_id):
                                	$this->db->where('class_id', $class_id);
                                	$classes = $this->db->get('sms_class')->result_array();
                                	foreach($classes as $row):
                                ?>
                                    <option value="<?php echo $row['class_id'];?>"
                                        <?php if($class_id == $row['class_id'])echo 'selected';?>>
                                            <?php echo $row['name'];?></option>
                                <?php
                                	endforeach;
                                endif;
                                ?>
                            </select>
                        </td>
                        <td>
                        	<input type="hidden" name="operation" value="selection" />
                    		<input type="submit" value="<?php echo get_phrase('manage_marks');?>" class="btn btn-info" />
                        </td>
                	</tr>
                </table>
                </form>
                </center>
                
                
                <br />
                
                
                <?php if($exam_id >0 && $class_id >0 ):?>
                <?php 		        
						////CREATE THE MARK ENTRY ONLY IF NOT EXISTS////
						$students	=	$this->crud_model->get_students($class_id);
						foreach($students as $row):
							$verify_data	=	array(	'exam_id' => $exam_id ,
														'class_id' => $class_id ,
														'student_id' => $row['student_id']);
							$query = $this->db->get_where('sms_mark' , $verify_data);
							
							if($query->num_rows() < 1)
								$this->db->insert('sms_mark' , $verify_data);
						 endforeach;
				?>
				
				<?php echo form_open(base_url() . 'admin/marks');?>
				<!--<button type="submit" style="margin-bottom: 10px;" class="btn btn-primary pull-right">Update All</button>-->
				<input type="hidden" name="operation" value="all_update" />
				<input type="hidden" name="exam_id" value="<?php echo $exam_id;?>" />
            	<input type="hidden" name="class_id" value="<?php echo $class_id;?>" />
            	<input type="hidden" name="subject_id" value="<?php echo $subject_id;?>" />
                <table class="table table-bordered" >
                    <thead>
                        <tr>
                            <td><?php echo get_phrase('student');?></td>
                            <td><?php echo get_phrase('mark_obtained');?>(out of 100)</td>
                            <td><?php echo get_phrase('grade');?></td>
                            <td><?php echo get_phrase('comment');?></td>
                            <!--<td></td>-->
                        </tr>
                    </thead>
                    <tbody>
                    	
                        <?php 
						$students	=	$this->crud_model->get_students($class_id);
						foreach($students as $row):
						
							$verify_data	=	array(	'exam_id' => $exam_id ,
														'class_id' => $class_id ,
														'student_id' => $row['student_id']);
														
							$query = $this->db->get_where('sms_mark' , $verify_data);							 
							$marks	=	$query->result_array();
							foreach($marks as $row2):
							?>
                            
							<tr>
								<td>
									<?php echo $row['name'];?>
									<input type="hidden" name="mark_id[]" value="<?php echo $row2['mark_id'];?>" />
								</td>
								<td>
									 <input onblur="markUpdate(<?php echo $row2['mark_id'];?>);" id="mark_obtained_<?php echo $row2['mark_id'];?>" type="text" value="<?php echo $row2['mark_obtained'];?>" name="mark_obtained[]" class="form-control"  />
												
								</td>
								<td>
									 <input onblur="markUpdate(<?php echo $row2['mark_id'];?>);" id="grade_<?php echo $row2['mark_id'];?>" type="text" value="<?php echo $row2['grade'];?>" name="grade[]" class="form-control"  />
												
								</td>
								<td>
									<textarea onblur="markUpdate(<?php echo $row2['mark_id'];?>);" id="comment_<?php echo $row2['mark_id'];?>" name="comment[]" class="form-control"><?php echo $row2['comment'];?></textarea>
								</td>
                                <!---<td>
                                	
                                	<input type="button" value="Update" onclick="markUpdate(<?php echo $row2['mark_id'];?>);" class="btn btn-primary">
                                </td>--->
							 </tr>
                         	<?php 
							endforeach;
						 endforeach;
						 ?>
						 
                     </tbody>
                  </table>
            	<?php echo form_close(); ?>
            <?php endif;?>
			</div>
            <!----TABLE LISTING ENDS-->
            
		</div>
	</div>
</div>

<script type="text/javascript">
  function show_subjects(class_id)
  {
      for(i=0;i<=100;i++)
      {

          try
          {
              document.getElementById('subject_id_'+i).style.display = 'none' ;
	  		  document.getElementById('subject_id_'+i).setAttribute("name" , "temp");
          }
          catch(err){}
      }
      document.getElementById('subject_id_'+class_id).style.display = 'block' ;
	  document.getElementById('subject_id_'+class_id).setAttribute("name" , "subject_id");
  }
  
  	function markUpdate(mark_id){
		mark_obtained = $('#mark_obtained_'+mark_id).val();
		grade = $('#grade_'+mark_id).val();
		comment = $('#comment_'+mark_id).val();
		$.ajax({		                
			url: "<?php echo base_url(); ?>" + 'admin/mark_update/' + mark_id + '/' + mark_obtained + '/' + grade + '/' + encodeURIComponent(comment),
			type: 'GET',
			success: function(data) 
			{
	 			//location.reload();
			},
		});
	}
  
	function loadClass(){
		exam_id = $('#exam_id').val();
		$.ajax({		                
			url: "<?php echo base_url(); ?>" + 'admin/load_class_by_exam/' + exam_id,
			type: 'GET',
			success: function(data) 
			{
	 			$('#class_id').empty();
	 			$('#class_id').append(data);
			},
		});
	}

</script> 