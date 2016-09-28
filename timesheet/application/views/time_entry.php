<body>
<style>
	table th {
		border-bottom: 1px solid #eeeeee;
		font-size: 12px;
	}
	table input, table select, table textarea {
		border: 1px solid oldlace;
		height: 29px;
	}
	table{
		margin: 10px;
		width: 97%;
	}
	.ui-widget-overlay {
		background: black none repeat scroll 0 0;
		opacity: 0.3;
	}
	tr#top_row {
		background-color: grey;
	}
	
#start-available-modal {
    background: #eee none repeat scroll 0 0;
    position: absolute;
    right: 5%;
    top: 40%;
    z-index: 9999;
}
#start-button, #alarm1 {
    float: left;
    margin: 10px 0 0;
}
#start-button a, #alarm1 a {
    float: left;
    color: #fff !important;
    margin: 0px 0 0;
}
#close-modal {
    float: right;
    margin: 10px 0 0;
}
#close-modal > a {
    background: #000;
    padding: 5px 10px;
    border-radius: 5px;
    color: #fff !important; 
    float: left;
}
.table-timer{
	display: none;
	float: left;
}
#start-available { 
	border-radius: 5px;
	background: #fff;
	cursor: pointer;
	padding: 7px 1px;
}
.start_update_button {
    border: 1px solid #eee;
    padding: 5px;
    border-radius: 5px;
}
</style>
<?php if(isset($massage)) echo $message;  ?>
<?php
$dt = date_create_from_format('Y-m-d', $day);

$user = $this->session->userdata('user');
$company_id = $user->company_id;
?>

	<div class="row modal-header">
		<div class="col-md-12">
			<div style="float: left;color: white; margin-top: 10px;">
				<span style="font-weight: bold; font-size: 130%;">TIME SHEET:</span><br>
				<span style="font-size: 258%; font-weight: bold;"><?php echo $dt->format('l'); ?></span>
			</div>
			<div class="cal <?php echo strtolower($dt->format('M')); ?>" >
				<span class="day"><?php echo $dt->format('d'); ?></span>
			</div>
		</div>
	</div>
<div class="content"  style="max-height: 400px; height: 400px; overflow: auto">

	<?php if($leave_check){ ?>	

	<p style="text-align:left;margin:20px 10%;">You have requested (what kind of leave) leave from <?php echo date('d/m/Y',strtotime($leave_check->date_form)); ?> to <?php echo date('d/m/Y',strtotime($leave_check->date_to)); ?>.<br>Comments: <?php echo $leave_check->note; ?>. <br><br>You are unable to modify this</p>
	<?php }else{ ?>
	<table style="margin-top: 5px" id="tbl">
		<thead>
		<tr>
			<th width="20%">Project</th>
			<?php if($company_id=='0'){ ?><th>Tasks</th><?php } ?>
			<th width="12%">Start Time</th>
			<th width="12%">Finish Time</th>
			<th width="18%">Unpaid Break Time</th>
			<th>Note</th>
			<th width="15%"></th>
		</tr>

		</thead>
		<tbody>
		<form id="frm1" action="<?php echo base_url()."timesheet/add_entry";?>" method="post">
			<tr id="top_row">
				<td>
					<select name="project_id" style="width: 100%" id="project_id">
						<option value="">--Select Project--</option>
						<?php foreach($projects as $project): ?>
						<option value="<?php echo $project->id; ?>"><?php echo $project->project_name; ?></option>
						<?php endforeach; ?>
					</select>
				</td>
				<?php if($company_id=='0'){ ?><td>
					<select name="task_id" style="width: 100%" id="task_id">
						<option value="">--Select Task--</option>
					</select>
				</td><?php } ?>
				<td><input id="start_time" class="timepicker" name="start_time" size="10" value="07:30" style="padding: 4px"></td>
				<td><input id="finish_time" class="timepicker" name="finish_time" size="10" placeholder="Finish Time"  style="padding: 4px"></td>
				<td>
					<select name="break_time" style="width: 100%">
						<option value="">--Select Break Time</option>
						<option value="15">15 minutes</option>
						<option value="30">30 minutes</option>
						<option value="45">45 minutes</option>
					</select>
					<input id="day" type="hidden" name="day" value="<?php echo $dt->format('Y-m-d'); ?>">
				</td>
				<td><textarea name="note" rows="1" style="width: 100%"></textarea></td>
				
				<td style="text-align: center">
				<div class="table-timer"><div id="start-available">Start Timer</div></div> <input type="image" src="<?php echo base_url();?>images/icons/add_btn.png" style="border: none"> 
				</td>
			</tr>
		</form>
		
		<?php foreach($entries_timer as $timer): ?>
		<form id="frm1" action="<?php echo base_url()."timesheet/add_entry/start_time";?>" method="post">
			<tr id="start_time_highlight">
				<td><?php echo $timer->project_name; ?></td>
				<td>Started <?php echo date('H.i',strtotime($timer->start_time)); ?></td>
				<td><h5><time>00:00:00</time></h5>		

<?php
$now_time = date('Y-m-d H:i:s');
$start_time = $timer->created;
$start_date = new DateTime($start_time);
$since_start = $start_date->diff(new DateTime($now_time));

$hours = $since_start->h;
$minutes = $since_start->i;
$seconds = $since_start->s;
?>
<script>
$(document).ready(function () {
	
var h1 = document.getElementsByTagName('h5')[0],
    seconds = "<?php echo $seconds; ?>", minutes = "<?php echo $minutes; ?>", hours = "<?php echo $hours; ?>",
    t;

function add() {
    seconds++;
    if (seconds >= 60) {
        seconds = 0;
        minutes++;
        if (minutes >= 60) {
            minutes = 0;
            hours++;
        }
    }
    
    h1.textContent = (hours ? (hours > 9 ? hours : "0" + hours) : "00") + ":" + (minutes ? (minutes > 9 ? minutes : "0" + minutes) : "00") + ":" + (seconds > 9 ? seconds : "0" + seconds);

    timer();
}
function timer() {
    t = setTimeout(add, 1000);
}
timer();


});

</script>					
				</td>
				<td></td>
				<td><?php echo $timer->note; ?></td>
				
				<td style="text-align: center">
				<a class="start_update_button" href="<?php echo base_url(); ?>timesheet/update_start_timer/<?php echo $timer->id; ?>/<?php echo $dt->format('Y-m-d'); ?>">Stop Timer</a>
				</td>
			</tr>
		</form>
		<?php endforeach; ?>
		
		<?php foreach($entries as $entry): ?>
			<form id="" action="<?php echo base_url()."timesheet/add_entry/{$entry->id}";?>" method="post">
				<tr>
					<td>
						<select name="project_id" style="width: 100%" <?php if($company_id=='24'){ ?>onchange="ProjectId(<?php echo $entry->id; ?>,<?php echo $entry->task_id; ?>);" id="project_id_<?php echo $entry->id; ?>" <?php } ?>>
							
							<?php foreach($projects as $project): ?>
								<?php
									$selected = "";
									if($project->id == $entry->project_id){
										$selected = "selected = 'selected'";
									}
								?>
								<option <?php echo $selected; ?> value="<?php echo $project->id; ?>"><?php echo $project->project_name; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<?php 
					if($company_id=='0'){ 
					$ci =&get_instance();
					$ci->load->model('timesheet_model');
					$tasks = $ci->timesheet_model->load_project_task_by_commercial($entry->project_id)->result(); 
					?>
					<td>
						<select name="task_id" style="width: 100%" id="task_id_<?php echo $entry->id; ?>">
							
							<?php foreach($tasks as $task): ?>
								<?php
									$selected = "";
									if($task->request_no == $entry->task_id){
										$selected = "selected = 'selected'";
									}
								?>
								<option <?php echo $selected; ?> value="<?php echo $task->request_no; ?>"><?php echo $task->request_title; ?></option>
							<?php endforeach; ?>
						</select>
					</td>
					<?php } ?>
					
					<td><input class="timepicker" value="<?php echo $entry->start_time; ?>" name="start_time" size="10"></td>
					<td><input class="timepicker" value="<?php echo $entry->finish_time; ?>" name="finish_time" size="10"></td>
					<td>
						<select name="break_time" style="width: 100%">
							<option value=""></option>
							<?php foreach(array(15, 30, 45) as $bt): ?>
							<?php
							$selected = "";
							if($bt == $entry->break_time){
								$selected = "selected = 'selected'";
							}
							?>
							<option value="<?php echo $bt; ?>" <?php echo $selected; ?>><?php echo $bt; ?> minutes</option>
							<?php endforeach; ?>
						</select>
						<input type="hidden" name="day" value="<?php echo $dt->format('Y-m-d'); ?>">
					</td>
					<td><textarea name="note" rows="1" style="width: 100%"><?php echo $entry->note; ?></textarea></td>
					<td style="text-align: center"> 
						<input style="border: none; float: left" type="image" src="<?php echo base_url();?>images/icons/btn_save.png">
						<a class="del" href="<?php echo base_url()."timesheet/delete_entry/{$entry->id}";?>">
							<img style="float: right;cursor: pointer" src="<?php echo base_url();?>images/icons/delete.png" height="29" width="29">
						</a>
					</td>
				</tr>
			</form>
		<?php endforeach; ?>
		</tbody>
	</table>
	
	<?php } //if leave_check ?>
</div>

<script>
	/*this is a patch*/
	var matched, browser;
	jQuery.uaMatch = function( ua ) {
		ua = ua.toLowerCase();

		var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
			/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
			/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
			/(msie) ([\w.]+)/.exec( ua ) ||
			ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
			[];

		return {
			browser: match[ 1 ] || "",
			version: match[ 2 ] || "0"
		};
	};

	matched = jQuery.uaMatch( navigator.userAgent );
	browser = {};

	if ( matched.browser ) {
		browser[ matched.browser ] = true;
		browser.version = matched.version;
	}

	// Chrome is Webkit, but Webkit is also Safari.
	if ( browser.chrome ) {
		browser.webkit = true;
	} else if ( browser.webkit ) {
		browser.safari = true;
	}

	jQuery.browser = browser;
	/********************************************/

	jQuery(document).ready(function(){
		jQuery('.content').mCustomScrollbar({
			theme:"dark"
		});

		$('.timepicker').timepicker({
			'timeFormat': 'H:i',
			step: 15
		});

		$("table form").ajaxForm({
			dataType: 'json',
			success: function(data){
				if(data.status == 'error'){
					$( "#error-message" ).html(data.message).dialog({
						modal: true,
						buttons: {
							Ok: function() {
								$( this ).dialog( "close" );
							}
						}
					});
				}else{
					/*will not show the dialog on monday - thursday*/
					var day = new Date().getDay();
					if(day == 5 || day == 6 || day == 0){
						$("#success-message").dialog({
							buttons: {
								Ok: function() {
									$( this ).dialog( "close" );

								}
							},
							close: function( event, ui ) {
								location.reload();
							}
						});
					}else{
						location.reload();
					}
				}
			}
		});
		$('a.del').click(function (event)
		{
			event.preventDefault();

			var url = $(this).attr('href');

			$( "#dialog-confirm" ).dialog({
				resizable: false,
				height:200,
				modal: true,
				buttons: {
					"Delete": function() {
						$.get(url, function(data) {
							location.reload();
						});
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});


		});

		$('#project_id').change(function()
		{
			var base_url = "<?php echo base_url(); ?>";
			var project_id = $(this).val();
			var day = $('#day').val();
			var currentDate = "<?php echo date('Y-m-d'); ?>";

			if(project_id!=''){
				
				if(currentDate==day){
					$('.table-timer').css('display','table-cell');	
					$('#start-available').empty();	
					$('#start-available').append('<a href="'+base_url+'timesheet/add_start_timer/'+project_id+'/'+day+'">Start Timer</a>');
				}
					
			}else{
				$('.table-timer').css('display','none');
			}
				
			/*$.ajax({
				url: "<?php echo base_url(); ?>" + 'timesheet/load_project_task_by_commercial/' + project_id + '/0',
				type: 'GET',
				success: function(data) 
				{
					//console.log(data); 	
					$('#task_id').empty();
					$('#task_id').append(data);			        
				},
			        
			});*/
		});

	});

function ProjectId(en_id,task_id)
{
	var project_id = $("#project_id_"+en_id).val();

	$.ajax({
		url: "<?php echo base_url(); ?>" + 'timesheet/load_project_task_by_commercial/' + project_id + '/' + task_id,
		type: 'GET',
		success: function(data) 
		{
			//console.log(data); 	
			$("#task_id_"+en_id).empty();
			$("#task_id_"+en_id).append(data);			        
		},
	        
	});
}

	/*$(document).ready(function () {

		$("#start-available").click(function(){
             $("#start-available-modal").css('display','block');
        });
        $("#close-modal").click(function(){
             $("#start-available-modal").css('display','none');
        });
	});*/

</script>

<!---
<div id="start-available-modal" style="display: none;">
	<div class="modal-body" id="modal-body" style="">
		<div id="myclock"></div>
		<input type="hidden" id="altime" placeholder="hh:mm"/>
		<div id="start-button"><a href="javascript:void(0)" id="set">Start Timer</a></div>
		<div id="alarm1" class="alarm"><a href="javascript:void(0)" id="turnOffAlarm">Stop Timer</a></div>
		<div id="close-modal"><a href="javascript:void(0)">Close</a></div>
		<div style="clear: both;"></div>
	</div>	
</div>  --->


 
<!---<script language="javascript">
	var intVal, myclock;

	$(window).resize(function(){
		//window.location.reload()
	});

	$(document).ready(function(){

		//var audioElement = new Audio("");

		//clock plugin constructor
		$('#myclock').thooClock({
			size:$(document).height()/3.5,
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

</script>--->

<div id="error-message" title="Error" style="display: none">
</div>
<div id="success-message"  title="Success"  style="display: none">
	Time Sheet Updated. <br>
	Please do not forget to press <b>SUBMIT</b> to be processed and get wages.
</div>
<div id="dialog-confirm" title="Delete Time Sheet Entry?" style="display: none">
	<p style="text-align: justify"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>This time sheet entry will be permanently deleted and cannot be recovered. Are you sure?</p>
</div>

</body>
</html>



