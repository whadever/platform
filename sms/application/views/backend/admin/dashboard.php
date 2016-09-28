<div class="row">
	<div class="col-md-12">
    	<div class="row">
            <!-- CALENDAR-->
            <div class="col-md-12 col-xs-12">    
                <div class="panel panel-primary " data-collapsed="0">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <i class="fa fa-calendar"></i>
                            <?php echo get_phrase('event_schedule');?>
                        </div>
                    </div>
                    <div class="panel-body" style="padding:0px;">
						
						
                        <div class="calendar-env">
                            <div class="calendar-body">
                                <div id="notice_calendar"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
	
</div>



    <script>
  $(document).ready(function() {
  	
  	
	  
	  var calendar = $('#notice_calendar');
				
				$('#notice_calendar').fullCalendar({
					
					header: {
						left: 'title',
						right: 'today prev,next'
					},
					
					//defaultView: 'basicWeek',
					
					editable: false,
					firstDay: 1,
					height: 530,
					droppable: false,
					
					
					
					events: [
						<?php 
						$this->db->where('company_id', $this->session->userdata('user')->company_id);
						$notices	=	$this->db->get('sms_noticeboard')->result_array();
						foreach($notices as $row):
						?>
						{
							title: "<?php echo $row['notice_title'];?>",
							start: new Date(<?php echo date('Y',$row['create_timestamp']);?>, <?php echo date('m',$row['create_timestamp'])-1;?>, <?php echo date('d',$row['create_timestamp']);?>),
							end:	new Date(<?php echo date('Y',$row['create_timestamp']);?>, <?php echo date('m',$row['create_timestamp'])-1;?>, <?php echo date('d',$row['create_timestamp']);?>) 
						},
						<?php 
						endforeach
						?>
						
						<?php 
                    	$this->db->select('sms_class_routine.*, sms_class.name as c_name, sms_teacher.name as t_name');
    					$this->db->join('sms_class', 'sms_class.class_id = sms_class_routine.class_id','left');
    					$this->db->join('sms_teacher', 'sms_teacher.teacher_id = sms_class.teacher_id','left');
    					$this->db->where('sms_class.company_id', $this->session->userdata('user')->company_id);
						$routines = $this->db->get('sms_class_routine')->result_array();
						foreach($routines as $row){
							$day_name = $row['day'];
							if($day_name=='saturday'){ $day_name = 'Saturday'; }
							elseif($day_name=='sunday'){ $day_name = 'Sunday'; }
							elseif($day_name=='monday'){ $day_name = 'Monday'; }
							elseif($day_name=='tuesday'){ $day_name = 'Tuesday'; }
							elseif($day_name=='wednesday'){ $day_name = 'Wednesday'; }
							elseif($day_name=='thursday'){ $day_name = 'Thursday'; }
							elseif($day_name=='friday'){ $day_name = 'Friday'; }
							
							$cur_day = strtotime('d');
							$cur_month = strtotime('m');
							$cur_year = strtotime('Y');
							$cur_month1 = date('m');
							$cur_year1 = date('Y');
							
							$count_days = date('t', mktime(0, 0, 0, $cur_month, 1, $cur_year));
							for($i=1; $i<=$count_days; $i++){
								$day = date('l',strtotime("$cur_year1-$cur_month1-$i"));
								$day_1 = date('d',strtotime("$cur_year1-$cur_month1-$i"));
								if($day_name==$day){
									$routine = $row['c_name'];
						?>
							{
								title: "<?php echo $routine; ?>",
								start: new Date(<?php echo date('Y',$cur_year);?>, <?php echo date('m',$cur_month)-1;?>, <?php echo $day_1;?>),
								end:	new Date(<?php echo date('Y',$cur_year);?>, <?php echo date('m',$cur_month)-1;?>, <?php echo $day_1;?>),
								tooltip: "<div id='tooltip'><strong><?php echo $routine; ?></strong><br/><?php echo $day_name.' '.$row['time_start']; ?><br/><?php echo $row['t_name']; ?></div>"
							},
						<?php
								}
							}
						}
						?>
						
					],
					
					eventMouseover: function(calEvent, jsEvent) {
		                //debugger;
		                xOffset = 0;
		                yOffset = 0;
		                $("body").append(calEvent.tooltip);
		                $("#tooltip")
		                .css('z-index', 91000)
		                .css('position','absolute')
		                .css("top",(jsEvent.clientY - xOffset) + "px")
		                .css("left",(jsEvent.clientX + yOffset) + "px")
							.fadeIn("fast");
		            },
		            eventMouseout: function(calEvent,jsEvent) {
						$("#tooltip").remove();	
					}
					
				});
		linka = "'<?php echo base_url();?>modal/popup/modal_dashboard_update'";		
		$('.fc-header-right').prepend('<button onclick="showAjaxModal('+linka+');" type="button" class="fc-button fc-state-default fc-corner-left fc-corner-right">Update Dashboard</button><span class="fc-header-space"></span>');
	});
  </script>

  
