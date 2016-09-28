<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
            		<?php 
					echo $this->db->get_where('sms_class' , array('class_id' => $param2))->row()->name;
					?>
            	</div>
            </div>
			<div class="panel-body">

                 <table class="table ">

                       <tr>
                          <td>
                              Class Attendance
                          </td>
                          <td>
                              <a class="btn btn-default pull-right" href="<?php echo base_url(); ?>admin/view_class_attendance_pdf/<?php echo $param2; ?>" target="_blank">PDF</a> 
<a class="btn btn-default pull-right" href="<?php echo base_url(); ?>admin/view_class_attendance_excel/<?php echo $param2; ?>" style="margin-right:1em">EXCEL</a>
                          </td>
                          
                              
                          
                       </tr>
                       <tr>
                          <td>
                              Student Details
                          </td>
                          <td>
                              <a class="btn btn-default pull-right" href="<?php echo base_url(); ?>admin/details_pod_pdf/<?php echo $param2; ?>" target="_blank">PDF</a>
<a class="btn btn-default pull-right" href="<?php echo base_url(); ?>admin/details_pod_excel/<?php echo $param2; ?>" style="margin-right:1em">EXCEL</a>
                          </td>
                          
                              
                          
                       </tr>
                       <tr>
                          <td>
                              Exam Marks 
                          </td>
                          <td>
                              <a class="btn btn-default pull-right" href="<?php echo base_url(); ?>admin/marks_exam_pod_pdf/<?php echo $param2; ?>" target="_blank">PDF</a>
<a class="btn btn-default pull-right" href="<?php echo base_url(); ?>admin/marks_exam_pod_excel/<?php echo $param2; ?>" style="margin-right:1em">EXCEL</a>
                          </td>
                          
                              
                          
                       </tr>

                    </table>
				
                <table class="table table-bordered datatable">
                	<thead>
                		<tr>
                    		<th><div>#</div></th>
                    		<th><div><?php echo 'Student Name';?></div></th>
                    		<th><div><?php echo 'Attendance';?></div></th>
                    		<th><div><?php echo 'Mark Sheet';?></div></th>
						</tr>
					</thead>
                    <tbody>
                    	<?php 
                    	$this->db->where('class_id', $param2);
				        $students = $this->db->get('sms_student')->result_array();
				        $count = 1;
				        foreach($students as $row):?>
                        <tr>
                            <td><?php echo $count++;?></td>
							<td><?php echo $row['name'];?></td>
							<td  class="text-center"><a class="btn btn-default" href="<?php echo base_url(); ?>admin/manage_attendance"><?php echo 'Attendance';?></a></td>
							<td  class="text-center"><a class="btn btn-default" href="<?php echo base_url(); ?>admin/student_marksheet"><?php echo 'Mark Sheet';?></a></td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



