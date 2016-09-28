<style>
    #chartdiv {
	width       : 100%;
        height      : 250px;
        font-size   : 11px;
}	
</style>

<?php
$student_info = $this->crud_model->get_student_info($param2);
foreach ($student_info as $row1):
    ?>
    <center>
        <div style="font-size: 20px;font-weight: 200;margin: 10px;"><?php echo $row1['name']; ?></div>

        <div class="panel-group joined" id="accordion-test-2">

            <?php
            /////SEMESTER WISE RESULT, RESULTSHEET FOR EACH SEMESTER SEPERATELY
            $toggle = true;
            $exams = $this->crud_model->get_exams();
            foreach ($exams as $row0):

                $total_grade_point = 0;
                $total_marks = 0;
                $total_subjects = 0;
            ?>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title" onclick="reload<?php echo $row0['exam_id']; ?>();">
                            <a data-toggle="collapse" data-parent="#accordion-test-2" href="#collapse<?php echo $row0['exam_id']; ?>">
                                <i class="entypo-rss"></i>  <?php echo $row0['name']; ?>
                            </a>
                        </h4>
                    </div>

                    <div id="collapse<?php echo $row0['exam_id']; ?>" class="panel-collapse collapse <?php
                    if ($toggle) {
                        echo 'in';
                        $toggle = false;
                    }
                    ?>" >
                        <div class="panel-body">
                            <center>
                                <table class="table table-bordered " >
                                    <thead>
                                        <tr>
                                            <th>Subject</th>
                                            <th>Obtained marks</th>
                                            <th>Highest mark</th>
                                            <th>Grade</th>
                                            <th>Comment</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $subjects = $this->crud_model->get_subjects_by_class($row1['class_id']);
                                        foreach ($subjects as $row2):
                                            $total_subjects++;
                                            ?>
                                            <tr>
                                                <td><?php echo $row2['name']; $subject_name[] = $row2['name']; ?></td>
                                                <td>
                                                    <?php
                                                    //obtained marks
                                                    $verify_data = array('exam_id' => $row0['exam_id'],
                                                        'class_id' => $row1['class_id'],
                                                        'subject_id' => $row2['subject_id'],
                                                        'student_id' => $row1['student_id']);

                                                    $query = $this->db->get_where('sms_mark', $verify_data);
                                                    $marks = $query->result_array();
                                                    foreach ($marks as $row3):
                                                        echo $row3['mark_obtained'];
                                                        $mark_obtained[] = $row3['mark_obtained'];
                                                        $total_marks += $row3['mark_obtained'];
                                                    endforeach;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    //highest marks
                                                    $verify_data = array('exam_id' => $row0['exam_id'],
                                                        'subject_id' => $row2['subject_id']);
                                                    $this->db->select_max('mark_obtained', 'mark_highest');
                                                    $query = $this->db->get_where('sms_mark', $verify_data);
                                                    $marks = $query->result_array();
                                                    foreach ($marks as $row4):
                                                        echo $row4['mark_highest'];
                                                        $mark_highest[] = $row4['mark_highest'];
                                                    endforeach;
                                                    ?>
                                                </td>
                                                <td>
                                                    <?php
                                                    $grade = $this->crud_model->get_grade($row3['mark_obtained']);
                                                    echo $row2['grade'];
                                                    $total_grade_point += $grade['grade_point'];
                                                    ?>
                                                </td>
                                                <td></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                <!---<hr />
                                Total Marks : <?php echo $total_marks; ?>
                                <hr />--->
                                GPA(grade point average) : <?php echo round($total_grade_point / $total_subjects, 2); ?>
                                <div id="chart<?php echo $row0['exam_id']; ?>div" style="width:100%;height:250px;font-size:11px;"></div>
                                <script>
                                    //setTimeout(function() {
                                        var chart<?php echo $row0['exam_id']; ?> = AmCharts.makeChart("chart<?php echo $row0['exam_id']; ?>div", {
                                            "theme": "none",
                                            "type": "serial",
                                            "dataProvider": [
                                                <?php for( $i = 0; $i < count($subjects); $i++ ) { ?>
                                                    {
                                                        "subject": "<?php echo $subject_name[$i]; ?>",
                                                        "mark_obtained": "<?php echo $mark_obtained[$i]; ?>",
                                                        "mark_highest": "<?php echo $mark_highest[$i]; ?>"
                                                    },
                                                <?php } ?>
                                            ],
                                            "valueAxes": [{
                                                "stackType": "3d",
                                                "unit": "%",
                                                "position": "left",
                                                "title": "Obtained Mark vs Highest Mark"
                                            }],
                                            "startDuration": 1,
                                            "graphs": [{
                                                "balloonText": "Obtained Mark in [[category]]: <b>[[value]]</b>",
                                                "fillAlphas": 0.9,
                                                "lineAlpha": 0.2,
                                                "title": "2004",
                                                "type": "column",
                                                "fillColors":"#7f8c8d",
                                                "valueField": "mark_obtained"
                                            }, {
                                                "balloonText": "Highest Mark in [[category]]: <b>[[value]]</b>",
                                                "fillAlphas": 0.9,
                                                "lineAlpha": 0.2,
                                                "title": "2005",
                                                "type": "column",
                                                "fillColors":"#34495e",
                                                "valueField": "mark_highest"
                                            }],
                                            "plotAreaFillAlphas": 0.1,
                                            "depth3D": 20,
                                            "angle": 45,
                                            "categoryField": "subject",
                                            "categoryAxis": {
                                                "gridPosition": "start"
                                            },
                                            "exportConfig":{
                                                "menuTop":"20px",
                                                "menuRight":"20px",
                                                "menuItems": [{
                                                    "format": 'png'	  
                                                }]  
                                            }
                                        });
                                    //}, 10);
                                </script>
                                
                                
                            <?php
                            $exam_id = $row0['exam_id'];
                            $exam_date = date('Y-m-d',strtotime($row0['date']));
                            $class_id = $row0['class_id'];
                            $student_id = $param2;
                            
                            $this->db->select('MIN(date) as min_date, MAX(date) as max_date');
					    	$this->db->join('sms_student', 'sms_student.student_id=sms_attendance.student_id');
					    	$this->db->where_in('sms_student.class_id', array($class_id));
					    	$this->db->where('sms_attendance.date !=', '0000-00-00');
					    	$this->db->where('sms_attendance.date <', $exam_date);
					    	$this->db->where('sms_attendance.student_id', $student_id);
					    	$date = $this->db->get('sms_attendance')->row();
					    	
					    	
							if($date->min_date!='' && $date->max_date!=''){
								$min_date = $date->min_date;
	    						$max_date = $date->max_date;
	    
							    $weeks = $this->crud_model->date_range($min_date,$max_date);
							    //print_r($weeks);
							}
					    	
                            ?>
                            <?php if($date->min_date!='' && $date->max_date!=''){ ?>
                            <div id="chartweek<?php echo $exam_id; ?>div" style="width:100%;height:250px;font-size:11px;"></div>
                            <script>
			                    //setTimeout(function() {
			                        var chartweek<?php echo $exam_id; ?> = AmCharts.makeChart("chartweek<?php echo $exam_id; ?>div", {
			                            "theme": "none",
			                            "type": "serial",
			                            "dataProvider": [
			                                <?php 
			                                $week_s = '';
			                                foreach($weeks as $i=>$week){
												if($i!='0'){
										    		$this->db->select('sms_student.student_id, sms_student.name as student_name, sms_attendance.status, sms_attendance.date');
											    	$this->db->join('sms_attendance', 'sms_attendance.student_id=sms_student.student_id');
											    	$this->db->where_in('sms_student.class_id', array($class_id));
											    	$this->db->where('sms_attendance.date >=', $week_s);
											    	$this->db->where('sms_attendance.date <=', $weeks[$i]);
											    	$this->db->where('sms_attendance.student_id', $student_id);
											    	$student = count($this->db->get('sms_student')->result());
											    	
											    	$this->db->select('sms_student.student_id, sms_student.name as student_name, sms_attendance.status, sms_attendance.date');
											    	$this->db->join('sms_attendance', 'sms_attendance.student_id=sms_student.student_id');
											    	$this->db->where_in('sms_student.class_id', array($class_id));
											    	$this->db->where('sms_attendance.date >=', $week_s);
											    	$this->db->where('sms_attendance.date <=', $weeks[$i]);
											    	$this->db->where('sms_attendance.student_id', $student_id);
											    	$this->db->where('sms_attendance.status', '1');
											    	$status_1 = count($this->db->get('sms_student')->result());
											    	//echo $this->db->last_query(); 
											    	$atteds = $status_1/$student*100;
											    	if($student!='0'){
											?>
			                                    {
			                                        "subject": "Week <?php echo $i; ?>",
			                                        "mark_obtained": "<?php echo $atteds; ?>",
			                                        "mark_highest": "<?php echo $atteds; ?>"
			                                    },
			                                <?php 
			                                		}
			                                		$week_s = $weeks[$i];
			                                	} 
			                                }
			                                ?>
			                            ],
			                            "valueAxes": [{
			                                "stackType": "3d",
			                                "unit": "%",
			                                "position": "left",
			                                "title": "Weekly Attendance"
			                            }],
			                            "startDuration": 1,
			                            "graphs": [{
			                                "balloonText": "Attendance [[category]]: <b>[[value]]%</b>",
			                                "fillAlphas": 0.9,
			                                "lineAlpha": 0.2,
			                                "title": "2004",
			                                "type": "column",
			                                "fillColors":"#7f8c8d",
			                                "valueField": "mark_obtained"
			                            }, {
			                                "balloonText": "Attendance [[category]]: <b>[[value]]%</b>",
			                                "fillAlphas": 0.9,
			                                "lineAlpha": 0.2,
			                                "title": "2005",
			                                "type": "column",
			                                "fillColors":"#34495e",
			                                "valueField": "mark_highest"
			                            }],
			                            "plotAreaFillAlphas": 0.1,
			                            "depth3D": 20,
			                            "angle": 45,
			                            "categoryField": "subject",
			                            "categoryAxis": {
			                                "gridPosition": "start"
			                            },
			                            "exportConfig":{
			                                "menuTop":"20px",
			                                "menuRight":"20px",
			                                "menuItems": [{
			                                    "format": 'png'	  
			                                }]  
			                            }
			                        });
			                    //}, 10);
			                    
			                    
			                </script>
			                <?php } ?>
<script>
	function reload<?php echo $exam_id; ?>(){
		chart<?php echo $exam_id; ?>.invalidateSize();
		chartweek<?php echo $exam_id; ?>.invalidateSize();
	}
</script>
                            </center>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </center>
<?php endforeach; ?>

