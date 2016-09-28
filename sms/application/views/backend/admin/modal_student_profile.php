<style>
    #chartdiv {
	width       : 100%;
        height      : 250px;
        font-size   : 11px;
}
.chartContainer{
	float: left;
}	
.skills_test {
    width: 18px;
    height: 16px;
    background: #27e3ff;
    float: right;
}
.progress_reports {
    width: 18px;
    height: 16px;
    background: #1491a5;
    float: right;
}
.tuition {
    width: 18px;
    height: 16px;
    background: #067181;
    float: right;
}
.category{
	float: right;
	padding-right: 10px;
}
</style>


<?php
$student_info	=	$this->crud_model->get_student_info($param2);
foreach($student_info as $row):?>

<div class="profile-env">
	
	<header class="row">
		
		<div class="col-sm-3">
			
			<a href="#" class="profile-picture">
				<img src="<?php echo $this->crud_model->get_image_url('student' , $row['student_id']);?>" 
                	class="img-responsive img-circle" />
			</a>
			
		</div>
		
		<div class="col-sm-9">
			
			<ul class="profile-info-sections">
				<li style="padding:0px; margin:0px;">
					<div class="profile-name">
							<h3><?php echo $row['name'];?></h3>
					</div>
				</li>
			</ul>
			
		</div>
		
		
	</header>
	
	<section class="profile-info-tabs">
		
		<div class="row">
			
			<div class="">
            		<br>
                <table class="table table-bordered">
                
                    <?php if($row['class_id'] != ''):?>
                    <tr>
                        <td><?php echo get_phrase('class'); ?></td>
                        <td><b><?php echo $this->crud_model->get_class_name($row['class_id']);?></b></td>
                    </tr>
                    <?php endif;?>

                    <?php if($row['section_id'] != '' && $row['section_id'] != 0):?>
                    <tr>
                        <td>Section</td>
                        <td><b><?php echo $this->db->get_where('sms_section' , array('section_id' => $row['section_id']))->row()->name;?></b></td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['id_number'] != ''):?>
                    <tr>
                        <td>ID Number</td>
                        <td><b><?php echo $row['id_number'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['birthday'] != ''):?>
                    <tr>
                        <td>Birthday</td>
                        <td><b><?php echo $row['birthday'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['sex'] != ''):?>
                    <tr>
                        <td>Gender</td>
                        <td><b><?php echo $row['sex'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                
                    <?php if($row['phone'] != ''):?>
                    <tr>
                        <td>Phone</td>
                        <td><b><?php echo $row['phone'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['email'] != ''):?>
                    <tr>
                        <td>Email</td>
                        <td><b><?php echo $row['email'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['address'] != ''):?>
                    <tr>
                        <td>Address</td>
                        <td><b><?php echo $row['address'];?></b>
                        </td>
                    </tr>
                    <?php endif;?>

                    <?php if($row['father_name'] != ''):?>
                    <tr>
                        <td>Father's Name</td>
                        <td><b><?php echo $row['father_name'];?></b></td>
                    </tr>
                    <?php endif;?>

                    <?php if($row['mother_name'] != ''):?>
                    <tr>
                        <td>Mother's Name</td>
                        <td><b><?php echo $row['mother_name'];?></b></td>
                    </tr>
                    <?php endif;?>

                    <?php if($row['income'] != ''):?>
                    <tr>
                        <td>Source of Income</td>
                        <td><b><?php echo $row['income'];?></b></td>
                    </tr>
                    <?php endif;?>

                    <?php if($row['school'] != ''):?>
                    <tr>
                        <td>School</td>
                        <td><b><?php echo $row['school'];?></b></td>
                    </tr>
                    <?php endif;?>

                    <?php if($row['starting_grade'] != ''):?>
                    <tr>
                        <td>Starting Grade</td>
                        <td><b><?php echo $row['starting_grade'];?></b></td>
                    </tr>
                    <?php endif;?>

                    <!--student strike rate-->
                    <?php $student_attendance = $this->crud_model->get_student_attendance($row['student_id']);?>
                    <?php if(count($student_attendance)!=0):?>
                    <tr>
                        <td>Strike Rate</td>
                        <td><b><?php foreach($student_attendance as $att_status){
                             if($att_status['status'] == 1){
                                $total_present += 1;
                             }
                            }
                                $strike_rate = $total_present / count($student_attendance) * 100;
                             echo round($strike_rate,2);
                             echo '%';
                            ?></b></td>
                    </tr>
                    <?php endif;?>
                    
                    <!--student average mark-->
                    <?php $student_mark = $this->crud_model->get_student_mark($row['student_id']);?>
                    <?php if(count($student_mark)!=0):?>
                    <tr>
                    <!--get all student mark-->
                        
                        <td><?php echo get_phrase('average_mark'); ?></td>
                        <td><b><?php foreach ($student_mark as $mark) {
                            $avg_mark += $mark['mark_obtained'].' ';
                        } 
                            //counting average mark
                            $avg_mark = $avg_mark/count($student_mark); 
                            echo round($avg_mark,2);
                        ;?></b>
                        </td>
                    </tr>
                    <?php endif;?>
                    
                </table>
           
               	<div class="col-sm-12 col-md-8 col-md-offset-2 col-lg-6 col-lg-offset-3">
					<form method="GET" action="<?php echo base_url(); ?>">
						<div class="form-group">
							<input value="<?php if($param3!='' && $param4!=''){ echo date('Y/m/d',strtotime($param3)).' - '.date('Y/m/d',strtotime($param4)); } ?>" type="text" name="datefilter" class="form-control" placeholder="Select Range of Week">
							<input type="hidden" value="<?php echo $param2; ?>" id="student_id" />
						</div>
					</form>
				</div>
                
<script type="text/javascript" src="<?php echo base_url(); ?>fusioncharts/fusioncharts.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>fusioncharts/themes/fusioncharts.theme.zune.js"></script>

<?php 
if($param3!='' && $param4!=''){	
	$min_date = date('m/d/Y',strtotime($param3));
	$max_date = date('m/d/Y',strtotime($param4));
	function date_range($first, $last, $step = '+7 day', $output_format = 'm/d/Y') {
		$dates = array();
		$current = strtotime($first);
		$last = strtotime($last);
		while( $current <= $last ) {
		    $dates[] = date($output_format, $current);
		    $current = strtotime($step, $current);
		}
		return $dates;
	}

	$weeks = date_range($min_date,$max_date);

	for($i=0; $i<=count($weeks); $i++){
		$access = 0;
		if($i==count($weeks)){
			$first_date = $week_s;
			$last_date = $max_date;
			
			$date = new DateTime($first_date);
			$week = $date->format("W");
			$access = 1;
		}else if($i!='0'){
			$first_date = $week_s;
			$last_date = $weeks[$i];
			
			$date = new DateTime($first_date);
			$week = $date->format("W");
			$access = 1;
		}
		$week_s = $weeks[$i];
		
		if($access == 1){
			
			$this->db->select('sms_mark.*');
	        $this->db->join('sms_exam','sms_exam.exam_id=sms_mark.exam_id');
	        $this->db->where('student_id',$param2);
	        $this->db->where('sms_exam.category', 'Skills Test');
	        $this->db->where('sms_exam.date >=', $first_date);
	        $this->db->where('sms_exam.date <=', $last_date);
	        $skill_marks = $this->db->get('sms_mark')->result();
	        $skill = 0;
	        foreach($skill_marks as $skill_mark){
				$skill += $skill_mark->mark_obtained;
			}	
			$skill_total = $skill/count($skill_marks);
			
			$this->db->select('sms_mark.*');
	        $this->db->join('sms_exam','sms_exam.exam_id=sms_mark.exam_id');
	        $this->db->where('student_id',$param2);
	        $this->db->where('sms_exam.category', 'Progress Reports');
	        $this->db->where('sms_exam.date >=', $first_date);
	        $this->db->where('sms_exam.date <=', $last_date);
	        $progress_marks = $this->db->get('sms_mark')->result();
	        $progress = 0;
	        foreach($progress_marks as $progress_mark){
				$progress += $progress_mark->mark_obtained;
			}	
			$progress_total = $progress/count($progress_marks);
			
			$this->db->select('sms_mark.*');
	        $this->db->join('sms_exam','sms_exam.exam_id=sms_mark.exam_id');
	        $this->db->where('student_id',$param2);
	        $this->db->where('sms_exam.category', 'Tuition');
	        $this->db->where('sms_exam.date >=', $first_date);
	        $this->db->where('sms_exam.date <=', $last_date);
	        $tuition_marks = $this->db->get('sms_mark')->result();
	        $tuition = 0;
	        foreach($tuition_marks as $tuition_mark){
				$tuition += $tuition_mark->mark_obtained;
			}	
			$tuition_total = $tuition/count($tuition_marks);
?>
		
<script type="text/javascript">
  FusionCharts.ready(function(){
    var revenueChart = new FusionCharts({
        "type": "column2d",
        "renderAt": "chartContainer<?php echo $i; ?>",
        "width": "180",
        "height": "180",
        "dataFormat": "json",
        "dataSource":  {
	    "chart": {
	        "caption": "Week <?php echo $week; ?>",
	        "captionpadding": "20",
	        "captionOnTop": "0",
	        "yaxisname": "",
	        "showvalues": "0",
	        "plotgradientcolor": "",
	        //"rotatelabels": "1",
	        //"slantlabels": "1",
	        //"numdivlines": "3",
	        //"divlinedashed": "0",
	        //"divlinealpha": "40",
	        //"tooltipbgcolor": "138dd7",
	        //"tooltipfontbold": "1",
	        //"tooltipbgalpha": "80",
	        //"tooltipbordercolor": "138dd7",
	        //"showtooltipshadow": "0",
	        //"plottooltext": "$label Goals : $datavalue",
	        //"palettecolors": "4191cc",
	        "canvasBgAlpha": "0",
	        "bgColor": "#f1f1f1",
	        "bgAlpha": "50",
	        "theme": "zune"
	    },
	    "data": [
	        {
	            "label": "",
	            "value": "<?php echo $skill_total; ?>",
	            "color": "27e3ff"
	        },
	        {
	            "label": "",
	            "value": "<?php echo $progress_total; ?>",
	            "color": "1491a5"
	        },
	        {
	            "label": "",
	            "value": "<?php echo $tuition_total; ?>",
	            "color": "067181"
	        }
	    ]
	}
  });
revenueChart.render();
})
</script>
<div class="chartContainer" id="chartContainer<?php echo $i; ?>">FusionCharts XT will load here!</div> 

<?php		
		}
	}
}
?>

				<div class="col-sm-12 pull-right" style="padding-top: 20px">
					<div class="skills_test"></div><div class="category">Skills Test</div>
				</div>
				<div class="col-sm-12 pull-right" style="padding-top: 2px">
					<div class="progress_reports"></div><div class="category">Progress Reports</div>
				</div>
				<div class="col-sm-12 pull-right" style="padding-top: 2px">
					<div class="tuition"></div><div class="category">Tuition</div>
				</div>

   

           
                <?php 
                /*$this->db->select('sms_mark.*,sms_exam.name as exam_name');
                $this->db->join('sms_exam','sms_exam.exam_id=sms_mark.exam_id');
                $this->db->where('student_id',$param2);
                $this->db->order_by('mark_id','DESC');
                $this->db->limit(1);
                $latest_mark = $this->db->get('sms_mark')->row();*/
                ?>
                <!--
                <div id="chartdiv"></div>
                <script>
                    setTimeout(function() {
                        var chart = AmCharts.makeChart("chartdiv", {
                            "theme": "none",
                            "type": "serial",
                            "dataProvider": [
                                <?php //for( $i = 0; $i < count($subjects); $i++ ) { ?>
                                    {
                                        "subject": "<?php echo $latest_mark->exam_name; ?>",
                                        "mark_obtained": "<?php echo $latest_mark->mark_obtained; ?>",
                                        "mark_highest": "<?php echo $latest_mark->mark_obtained; ?>"
                                    },
                                <?php //} ?>
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
                    }, 500);
                </script>
                --->
			</div>
		</div>		
	</section>
	

<script type="text/javascript">
$(function() {

	$('input[name="datefilter"]').daterangepicker({
		showWeekNumbers: true,
		autoUpdateInput: false,
		locale: {
		  cancelLabel: 'Clear',
		  format: 'YYYY/MM/DD'
		},
		"showDropdowns": true,
	    "opens": "center",
	    "drops": "up"
	});

	$('input[name="datefilter"]').on('apply.daterangepicker', function(ev, picker) {
	  	//date_range = $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
	  	
	  	date_range = picker.startDate.format('YYYY-MM-DD') + '/' + picker.endDate.format('YYYY-MM-DD');
	  	
	  	student_id = $('#student_id').val();
	  	
	  	url = "<?php echo base_url(); ?>" + 'modal/popup/modal_student_profile/'+student_id+'/'+date_range;
	  
		base = "<?php echo base_url(); ?>";
		
		// SHOWING AJAX PRELOADER IMAGE
		jQuery('#modal_ajax .modal-body').html('<div style="text-align:center;margin-top:200px;"><img src="'+base+'assets/images/preloader.gif" /></div>');
		
		// LOADING THE AJAX MODAL
		jQuery('#modal_ajax').modal('show', {backdrop: 'true'});
		
		// SHOW AJAX RESPONSE ON REQUEST SUCCESS
		$.ajax({
			url: url,
			success: function(response)
			{
				jQuery('#modal_ajax .modal-body').html(response);
			}
		});
	});

	$('input[name="datefilter"]').on('cancel.daterangepicker', function(ev, picker) {
	  	$(this).val('');
	});

});
</script>
	
</div>


<?php endforeach;?>