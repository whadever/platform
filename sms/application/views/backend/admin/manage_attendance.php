<hr />
<script type="text/javascript">
    $(document).ready(function(){
	    $('.date').datepicker({
	        'format': 'dd/mm/yyyy',
	        'autoclose': true
	    });
	});
</script>

<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group  pull-right">
			<a href="<?php echo base_url().'admin/attendance_export_to_excel'; ?>" class="btn btn-default">
			<?php echo 'EXCEL';?></a>
			
			<a href="<?php echo base_url().'admin/view_all_attendance_pdf'; ?>" target="_blank" class="btn btn-default">
			<?php echo get_phrase('view_all_attendance');?></a>
		</div>
	</div>
</div>

	<table cellpadding="0" cellspacing="0" border="0" class="table table-bordered">
    	<thead>
        	<tr>
            	<th><?php echo get_phrase('date');?></th>
            	<!--<th><?php echo get_phrase('select_month');?></th>
            	<th><?php echo get_phrase('select_year');?></th>-->
            	<th><?php echo get_phrase('select_class');?></th>
            	<th><?php echo get_phrase('previous_attendance');?></th>
            	<th><?php echo get_phrase('add_/_update_attendance');?></th>
           </tr>
       </thead>
		<tbody>
        	<form method="post" action="<?php echo base_url();?>admin/attendance_selector" class="form">
            	<tr class="gradeA">
            		<td>
                    	<input required="" value="<?php if($date!='' && $month!='' && $year!=''){ echo $date.'/'.$month.'/'.$year; }?>" name="date"  class="form-control date" type="text" />
                    </td>
                    <td>
                    	<select name="class_id" class="form-control">
                        	<option value=""><?php echo get_phrase('select_class');?></option>
                        	<?php 
                        	$this->db->where('company_id', $this->session->userdata('user')->company_id);
							$classes	=	$this->db->get('sms_class')->result_array();
							foreach($classes as $row):?>
                        	<option value="<?php echo $row['class_id'];?>"
                            	<?php if(isset($class_id) && $class_id==$row['class_id'])echo 'selected="selected"';?>>
									<?php echo $row['name'];?>
                              			</option>
                            <?php endforeach;?>
                        </select>

                    </td>
                    <td align="center"><input name="previous_attendance" type="submit" value="<?php echo get_phrase('previous_attendance');?>" class="btn btn-info"/></td>
                    <td align="center"><input type="submit" value="<?php echo get_phrase('add_/_update_attendance');?>" class="btn btn-info"/></td>
                </tr>
            </form>
		</tbody>
	</table>
<hr />


<?php if($date!='' && $month!='' && $year!='' && $class_id!='' && $previous_attendance!=''):?>

<center>
    <div class="row">
        <div class="col-sm-offset-4 col-sm-4">
        
            <div class="tile-stats tile-white-gray">
                <div class="icon"><i class="entypo-suitcase"></i></div>
                <?php
                    $full_date	=	$year.'-'.$month.'-'.$date;
                    $timestamp  = strtotime($full_date);
                    $day        = strtolower(date('l', $timestamp));
                 ?>
                
                
                <h3>Attendance of POD <?php echo ($class_id);?></h3>
                <p><?php echo $month.'-'.$year;?></p>
            </div>
        </div>

    </div>
</center>
<hr />



<?php
$dayNumber = cal_days_in_month(CAL_GREGORIAN, $month, $year);
for($i=1; $i<=$dayNumber; $i++){
	$date = date('Y-m-d',strtotime("$i-$month-$year"));
?>
<div class="panel panel-default previous_attendance">
    <div class="panel-heading">
    		<h4 class="panel-title">
        <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapse<?php echo $i;?>">
            <i class="entypo-rss"></i> <?php echo $date;?>
        </a>
        </h4>
    </div>

    <div id="collapse<?php echo $i;?>" class="panel-collapse collapse <?php if($toggle){echo 'in';$toggle=false;}?>" style="padding-left: 10px;padding-top:10px;padding-right:10px;">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td><?php echo get_phrase('iD_number');?></td>
                    <td><?php echo get_phrase('name');?></td>
                    <td><?php echo get_phrase('status');?></td>
                </tr>
            </thead>
            <tbody>

                <?php 
                	$this->db->select('sms_attendance.*, sms_student.name as s_name, sms_student.id_number,');
    				$this->db->join('sms_student', 'sms_student.student_id = sms_attendance.student_id','left');
                	$this->db->where('sms_student.class_id',$class_id);
                	$this->db->like('sms_attendance.date',$date);
                    $students   =   $this->db->get('sms_attendance')->result_array();
                        foreach($students as $row):?>
                        <tr class="gradeA">
                            <td><?php echo $row['id_number'];?></td>
                            <td><?php echo $row['s_name'];?></td>
                            <?php 
                                $status     = $row['status'];
                            ?>
                        <?php if ($status == 1):?>
                            <td align="center">
                              <span class="badge badge-success"><?php echo get_phrase('present');?></span>  
                            </td>
                        <?php endif;?>
                        <?php if ($status == 2):?>
                            <td align="center">
                              <span class="badge badge-danger"><?php echo get_phrase('absent');?></span>  
                            </td>
                        <?php endif;?>
                        <?php if ($status == 0):?>
                            <td></td>
                        <?php endif;?>
                        </tr>
                    <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
<?php } ?>

<?php elseif($date!='' && $month!='' && $year!='' && $class_id!=''):?>

<center>
    <div class="row">
        <div class="col-sm-offset-4 col-sm-4">
        
            <div class="tile-stats tile-white-gray">
                <div class="icon"><i class="entypo-suitcase"></i></div>
                <?php
                    $full_date	=	$year.'-'.$month.'-'.$date;
                    $timestamp  = strtotime($full_date);
                    $day        = strtolower(date('l', $timestamp));
                 ?>
                <h2><?php echo ucwords($day);?></h2>
                
                <h3>Attendance of Pod <?php echo ($class_id);?></h3>
                <p><?php echo $date.'-'.$month.'-'.$year;?></p>
            </div>
            <!-- <a href="#" id="update_attendance_button" onclick="return update_attendance()" 
                class="btn btn-info">
                    Update Attendance
            </a> -->
        </div>

    </div>
</center>
<hr />

<div class="row" id="attendance_list">
    <div class="col-sm-offset-2 col-md-8">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <td><?php echo get_phrase('iD_number');?></td>
                    <td><?php echo get_phrase('name');?></td>
                    <td><?php echo get_phrase('status');?></td>
                    <td><?php echo get_phrase('today\'s_attendance');?></td>
                </tr>
            </thead>
            <tbody>

                <?php 
                    $students   =   $this->db->get_where('sms_student' , array('class_id'=>$class_id))->result_array();

                        foreach($students as $row):?>
                        <tr class="gradeA">
                            
                            <td><?php echo $row['id_number'];?></td>
                            <td><?php echo $row['name'];?></td>
                            <?php 
                                //inserting blank data for students attendance if unavailable
                                $verify_data    =   array(  'student_id' => $row['student_id'],
                                                            'date' => $full_date);
                                $query = $this->db->get_where('sms_attendance' , $verify_data);
                                if($query->num_rows() < 1)
                                $this->db->insert('sms_attendance' , $verify_data);
                                
                                //showing the attendance status editing option
                                $attendance = $this->db->get_where('sms_attendance' , $verify_data)->row();
                                $status     = $attendance->status;
                                $att_id     = $attendance->attendance_id;
                            ?>
                        <?php if ($status == 1):?>
                            <td align="center">
                              <span class="badge badge-success"><?php echo get_phrase('present');?></span>  
                            </td>
                        <?php endif;?>
                        <?php if ($status == 2):?>
                            <td align="center">
                              <span class="badge badge-danger"><?php echo get_phrase('absent');?></span>  

                            </td>
                        <?php endif;?>
                        <?php if ($status == 0):?>
                            <td>
                               
                                    
                                
                            </td>
                        <?php endif;?>
                        <td>
                            
                          
                            
                           <input   class="stats_<?php echo $att_id;?>" <?php if($status == 1)echo 'checked="checked"';?>  type="radio" name="status_<?php echo $att_id;?>" value="1" onclick="attUpdate(<?php echo $att_id;?>)" />Present&nbsp;&nbsp;
                            
                            <input  class="stats_<?php echo $att_id;?>" <?php if($status == 2)echo 'checked="checked"';?>  type="radio" name="status_<?php echo $att_id;?>" value="2" onclick="attUpdate(<?php echo $att_id;?>)" />Absent
                            
                        </td>
                        </tr>
                    <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>
    
</form>
</div>
<?php endif;?>


<script type="text/javascript">

    //$("#update_attendance_button").hide();

    // function update_attendance() {

    //     $("#attendance_list").hide();
    //     $("#update_attendance_button").hide();
    //     $("#update_attendance").show();

    // }

    function attUpdate(attendance_id){
	status = $('input[name="status_'+ attendance_id+'"]:checked').val();
   
        $.ajax({                        
            url: "<?php echo base_url(); ?>" + 'admin/att_update/' + attendance_id + '/' + status,
            type: 'GET',
            success: function(data) 
            {
                //location.reload();

            },
        });
	
	}
    
  
</script>