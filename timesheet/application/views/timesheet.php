<style>
	.jan{
		background: url("<?php echo base_url()?>images/cal/jan.png");
	}
	.feb{
		background: url("<?php echo base_url()?>images/cal/feb.png");
	}
	.mar{
		background: url("<?php echo base_url()?>images/cal/mar.png");
	}
	.apr{
		background: url("<?php echo base_url()?>images/cal/apr.png");
	}
	.may{
		background: url("<?php echo base_url()?>images/cal/may.png");
	}
	.jun{
		background: url("<?php echo base_url()?>images/cal/jun.png");
	}
	.jul{
		background: url("<?php echo base_url()?>images/cal/jul.png");
	}
	.aug{
		background: url("<?php echo base_url()?>images/cal/aug.png");
	}
	.sep{
		background: url("<?php echo base_url()?>images/cal/sep.png");
	}
	.oct{
		background: url("<?php echo base_url()?>images/cal/oct.png");
	}
	.nov{
		background: url("<?php echo base_url()?>images/cal/nov.png");
	}
	.dec{
		background: url("<?php echo base_url()?>images/cal/dec.png");
	}
	.cal {
		background-position: right bottom;
		background-repeat: no-repeat;
		cursor: pointer;
		display: block;
		height: 156px;
		margin: 0 auto;
		width: 111px;
	}
	a.fbox {
		display: block;
		float: left;
		text-align: center;
		width: 14%;
	}
	.day{
		color: black;
		display: block;
		font-size: 46px;
		height: 100%;
		margin-top: 58px;
	}
	.weekday{
		color: black; font-size: 20px
	}
	.grey{
		cursor: default;
	}
	.grey *{
		color: grey;
		opacity: 0.7;
	}

	.fancybox-inner{
		border-radius: 10px;
	}
	.fancybox-skin {
		border: none;
		background-color: transparent;
		box-shadow: 0 0 0 rgba(0, 0, 0, 0) !important;
	}
	#title{
		color: black;
		font-size: 300%;
		font-weight: bold;
		margin-bottom: 20px;
		text-align: center;
	}
	@media screen and (max-width: 800px) {
		a.fbox {
			width: 20%;
		}
	}
	@media screen and (max-width: 360px) {
		#title{
			font-size: 150%;
		}
		a.fbox {
			width: 100%;
		}
	}

	#request_main label, #request_option label{
	    margin: -5px 0 0 0;
	}
	#request_main{
	    margin: 5px 0 ;
	}
</style>
<?php if(isset($massage)) echo $message;  ?>


<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
		</div>
	</div>
</div>


<div class="content-inner">

	<div class="row">
		<div class="col-md-12" id="title">
			SELECT THE DATE
		</div>
	</div>
	<div class="row">
		<div class="col-md-12" style="text-align: center">
		<?php
		
		$dt = date_create_from_format('Y-m-d', $start_date);
		$today = date('Y-m-d');

		for ($i = 1; $i <= 7; $i++):
			$is_inactive = ($dt->format('Y-m-d') > $today || $is_week_submitted) ? " grey":"";

			$tour_class = ($dt->format('l') == 'Monday') ? " tour tour_2 " : "";
			?>
				<a class="fbox <?php echo $is_inactive; ?>" data-fancybox-type="iframe" href="<?php echo base_url()."time_entry/".$dt->format('Y-m-d'); ?>">
				<span class="cal <?php echo $is_inactive; ?> <?php echo strtolower($dt->format('M')); ?>  <?php echo $tour_class; ?>">
					<span class="weekday"><?php echo $dt->format('l'); ?></span>
					<span class="day"><?php echo $dt->format('d'); ?></span>
				</span>
				
				<?php
				if($timers && $dt->format('d')==date('d',strtotime($timers->day))):
					echo '<div id="myclock"></div>';
				endif;
				?>
				</a>


			<?php
			$dt->add(new DateInterval("P1D"));
		endfor;
		?>
		</div>
		<div style="clear: both"></div>
		<?php
		if((!$is_week_submitted && $start_date < date('Y-m-d',strtotime("monday this week"))) || ($today >= date('Y-m-d',strtotime("monday this week")) && date('w')>4)):
		?>
		<form id="frmSubmit" action="<?php echo base_url().'timesheet/submit_weekly_timesheet'; ?>" method="post">
			<input type="hidden" name="start_date" value="<?php echo $start_date; ?>">
		</form>
		<div class="col-md-12" style="padding-top: 5%; text-align: center;">
			<a class="btn btn-default" id="btnSubmit">Submit</a>
		</div>
		<?php endif; ?>
	</div>

	<div class="row" id="request_leave">
		<form onsubmit="return ValidationDate();" action="<?php echo base_url(); ?>timesheet/request_leave_add" method="post">
			<div class="col-md-offset-8 col-lg-offset-8 col-md-4 col-lg-4 col-sm-12" style="text-align:right;">
				<div class="form-group tour tour_3" id="request_main">
				    <input type="checkbox" id="request">
					<label>Request Leave</label>
				</div>
				<div class="form-group" id="request_option" style="display:none;">
				    <input type="radio" id="request_sick" name="request" value="Sick">
					<label>Sick</label>
					<input type="radio" id="request_bereavement" name="request" value="Bereavement">
					<label>Bereavement</label>
					<input type="radio" id="request_unpaid" name="request" value="Unpaid">
					<label>Unpaid</label>
					<input type="radio" id="request_personal" name="request" value="Personal">
					<label>Personal</label>
					<input type="radio" id="request_marernity" name="request" value="Maternity">
					<label>Maternity</label>
				</div>
			</div>
			<div id="request_form" style="display:none;">
				<div class="col-md-offset-8 col-lg-offset-8 col-md-2 col-lg-2 col-sm-12">
					<div class="form-group">
						<label>Date Form</label>
					    <input type="text" class="form-control live_datepicker" id="date_form" name="date_form" required="">
					</div>
				</div>
				<div class="col-md-2 col-lg-2 col-sm-12">
					<div class="form-group">
						<label>Date To</label>
					    <input type="text" class="form-control live_datepicker" id="date_to" name="date_to" required="">
						<span class="datecurrect"></span>
					</div>
				</div>
				<div class="col-md-offset-8 col-lg-offset-8 col-md-4 col-lg-4 col-sm-12">
					<div class="form-group">
						<label>Note</label>
					    <textarea class="form-control" id="note" name="note" required=""></textarea>
					</div>
				</div>
				<div class="col-md-offset-8 col-lg-offset-8 col-md-2 col-lg-2 col-sm-12">
					<div class="form-group">
						<input type="submit" class="form-control btn btn-default" name="submit" value="Submit">
					</div>
				</div>
			</div>
		</form>
	</div>

</div>

<script>
	$(document).ready(function(){
		$("a.fbox").not(".grey").fancybox({
			padding: 0,
			margin: 0,
			iframe:{
				scrolling : 'no'
			}
		});
		$("a.grey").click(function(){
			return false;
		});
		$("#btnSubmit").click(function(){
			$( "#dialog-confirm" ).dialog({
				resizable: false,
				height:200,
				modal: true,
				buttons: {
					"Yes": function() {
						$("#frmSubmit").submit();
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});
		})

		$("#request_main input").click(function(){
			x = document.getElementById("request").checked;
			if(x==true){
				$('#request_option').css('display','block');
			}else{
				$('#request_option').css('display','none');
				$('#request_form').css('display','none');
			}
		})

		$("#request_option input").click(function(){
			$('#request_form').css('display','block');
		})

	})

	function ValidationDate(){
		date_form = $('#request_form #date_form').val();
		date_to = $('#request_form #date_to').val();

		if(date_form <= date_to){
			return true;
		}else{
			$('#request_form span.datecurrect').append('Correct date input.');
			return false;
		}
	}
	
</script>

<script>
$(function(){ 
    $(document).on('focus', ".live_datepicker", function(){
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy',
			//beforeShowDay: $.datepicker.noWeekends,
   		onClose: function(dateText, inst) 
   		{
          this.fixFocusIE = true;
          this.focus();
      	}
        });
    });
});
</script>
<!-- end: Date Picker -->
<div id="dialog-confirm" title="Time Sheet Submit" style="display: none">
	<p style="text-align: justify"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>Are you sure you want to submit this time sheet?</p>
</div>

<script language="javascript">

	/*tour. task #4422*/
	var config = [
			{
				"name" 		: "tour_1",
				"bgcolor"	: "black",
				"color"		: "white",
				"position"	: "T",
				"text"		: "From here you can submit our weekly time sheets.",
				"time" 		: 5000,
				"buttons"	: ["<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>"]
			},
			{
				"name" 		: "tour_2",
				"bgcolor"	: "black",
				"color"		: "white",
				"text"		: "Click here to update your time sheets. The projects are connected to Task Management System so no data entry needed. Submit button will appear on Friday to Sunday.",
				"position"	: "LB",
				"time" 		: 5000,
				"buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>", "<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
			},
			{
				"name" 		: "tour_3",
				"bgcolor"	: "black",
				"color"		: "white",
				"text"		: "Click here if you want Request Leave, your submission will be sent to the manager permission in the company and you will not be able to submit on the day you submit your report.",
				"position"	: "BR",
				"time" 		: 5000,
				"buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>",  "<span class='btn btn-xs btn-default endtour'>end tour</span>"]
			}

		],
	//define if steps should change automatically
		autoplay	= false,
	//timeout for the step
		showtime,
	//current step of the tour
		step		= 0,
	//total number of steps
		total_steps	= config.length;

	$(document).ready(function(){
		$(".active").addClass('tour tour_1');
	});

	var intVal, myclock;

	$(window).resize(function(){
		//window.location.reload()
	});

	$(document).ready(function(){

		//var audioElement = new Audio("");

		//clock plugin constructor
		$('#myclock').thooClock({
			size:$(document).height()/8.5,
			onAlarm:function(){
				var dt = new Date();
				var time = dt.getHours() + ":" + dt.getMinutes();
				//all that happens onAlarm
				$('#start_time').val(time);
				$('#alarm1').show();
				$('#start-button').hide();
				alarmBackground(0);
				//audio element just for alarm sound
				/*document.body.appendChild(audioElement);
				var canPlayType = audioElement.canPlayType("audio/ogg");
				if(canPlayType.match(/maybe|probably/i)) {
					audioElement.src = 'alarm.ogg';
				} else {
					audioElement.src = 'alarm.mp3';
				}
				// erst abspielen wenn genug vom mp3 geladen wurde
				audioElement.addEventListener('canplay', function() {
					audioElement.loop = true;
					audioElement.play();
				}, false);*/
			},
			showNumerals:false,
			brandText:'',
			brandText2:'',
			onEverySecond:function(){
				//callback that should be fired every second
			},
			//alarmTime:'15:10',
			offAlarm:function(){
				var dt = new Date();
				var time = dt.getHours() + ":" + dt.getMinutes();
				$('#finish_time').val(time);
				$('#alarm1').hide();
				$('#start-available-modal').hide();
				$('#start-button').show();
				//audioElement.pause();
				clearTimeout(intVal);
				$('#start-available-modal').css('background-color','#eeeeee');
			}
		});

	});



	$('#turnOffAlarm').click(function(){
		$.fn.thooClock.clearAlarm();
	});


	$('#set').click(function(){
		var inp = $('#altime').val();
		$.fn.thooClock.setAlarm(inp);
	});

	
	function alarmBackground(y){
			var color;
			if(y===1){
				color = '#eeeeee';
				y=0;
			}
			else{
				color = '#eeeeee';
				y+=1;
			}
			$('#start-available-modal').css('background-color',color);
			intVal = setTimeout(function(){alarmBackground(y);},100);
	}

</script>
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-36251023-1']);
  _gaq.push(['_setDomainName', 'jqueryscript.net']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>



