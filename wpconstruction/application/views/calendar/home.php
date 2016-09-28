<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.tooltip/jquery.tooltip.css">
<style>
	#cal-body th {
		color: white;
		font-size: 84%;
		text-align: center;
	}
	#cal-body td {
		border: 1px solid gray;
		height: 100px;
		margin: 2px;
		padding: 18px 8px 8px;
		position: relative;
		vertical-align: top;
		width: 14.28%;
	}
	#cal-body td.today{
	}
	.day-number {
		left: 2px;
		position: absolute;
		top: 0;
	}
	.status-complete{
		color: green;
	}
	.status-overdue{
		color: red;
	}
	.status-ontheway{
		color: #fab800;
	}
	#cal-body{
		padding: 0;
		position: relative;
	}
	#cal-header{
		padding: 0 15px;
	}
	#cal-header select,
	#cal-header .bootstrap-select,
	#cal-header .form-control{
		margin: 6px 0;
	}
	.calendar-row li {
		border-bottom: 1px dashed;
	}
	.calendar-row li:hover {
		cursor: pointer;
	}
	.calendar-row li:last-child {
		border: medium none;
	}
	table.calendar{
		border-collapse: separate;
	}
	#my-tasks:focus {
		outline: medium none;
	}
	#my-tasks{
		font-size: 20px
	}
	#my-tasks.active{
		color:white;
	}
	div.jquery-gdakram-tooltip div.content {
		background-color: white;
		border: 5px solid #671329;
		border-radius: 1em;
		float: left;
		min-height: 200px;
		padding: 10px;
		width: 280px;
		color: black;
	}
	div.jquery-gdakram-tooltip div.content h1 {
		border-bottom: 1px solid #c4c4c4;
		font-size: 14px;
		margin-top: 8px;
		padding-bottom: 5px;
	}
	.contractor #my-tasks{
		display: none;
	}
	.bootstrap-select .btn:focus {
		outline: none !important;
	}
	.button-wrapper .btn-default:hover, .btn-default:focus, .btn-default:active, .button-wrapper .btn-default.active, .button-wrapper .open .dropdown-toggle.btn-default {
		background-color: #fff;
		border-color: #afb0b3;
		color: #000;
	}
	#overlay {
		background-color: #000;
		background-image: url("<?php echo base_url(); ?>images/ajax-loading.gif");
		background-position: 50% center;
		background-repeat: no-repeat;
		height: 100%;
		left: 0;
		opacity: 0.5;
		position: absolute;
		top: 0;
		width: 100%;
		z-index: 10000;
	}
	.ui-datepicker-calendar {
		display: none;
	}
</style>
<div class="row <?php echo $user_app_role; ?>">

	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<li style="float:right; list-style: none">
			<span style="height:20px; width:20px; border-radius:15px; background-color:red">&nbsp;&nbsp;&nbsp;&nbsp;</span>:
			Overdue,
			<span style="height:20px; width:20px; border-radius:15px; background-color:grey">&nbsp;&nbsp;&nbsp;&nbsp;</span>:
			Pending,
			<span style="height:20px; width:20px; border-radius:15px; background-color:#fab800">&nbsp;&nbsp;&nbsp;&nbsp;</span>:
			Underway
			<!--<span style="height:20px; width:20px; border-radius:15px; background-color:green">&nbsp;&nbsp;&nbsp;&nbsp;</span>: Complete. &nbsp;&nbsp;&nbsp;&nbsp;-->
		</li>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div id="cal-header">
			<div class="row">
				<?php $col_width = ($contractors) ? '2' : '3'; ?>
				<?php if ($contractors): ?>
					<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
						<select class="form-control selectpicker" id="select-contractor" data-live-search="true">
							<option value="">Select Contractor</option>
							<?php foreach ($contractors as $contractor): ?>
								<option value="<?php echo $contractor->id; ?>"><?php echo $contractor->contact_first_name . " " . $contractor->contact_last_name; ?></option>
							<?php endforeach; ?>
						</select>
					</div>
				<?php endif; ?>
				<div class="col-xs-12 col-sm-12 col-md-<?php echo $col_width; ?> col-lg-<?php echo $col_width; ?>">
					<select class="form-control selectpicker" id="select-tasks">
						<option value="all_tasks">All Tasks</option>
						<option value="construction">Construction Tasks</option>
						<option value="pre_construction">Pre Construction Tasks</option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-<?php echo $col_width; ?> col-lg-<?php echo $col_width; ?>">
					<select class="form-control selectpicker" id="tasks-open-close">
						<option value="all">All</option>
						<option value="open">Open</option>
						<option value="complete">Closed</option>
					</select>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-<?php echo $col_width; ?> col-lg-<?php echo $col_width; ?>">
					<select class="form-control selectpicker" id="select-job">
						<option value="">Select Job</option>
						<?php foreach ($jobs as $id => $job): ?>
							<option value="<?php echo $id; ?>"><?php echo $job['job_info']->development_name; ?></option>
							<!--task #4433-->
							<?php if($job['children']): ?>
								<?php foreach($job['children'] as $child): ?>
									<option value="<?php echo $child->id; ?>">------ <?php echo $child->development_name; ?></option>
								<?php endforeach; ?>
							<?php endif; ?>
						<?php endforeach; ?>
					</select>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-<?php echo $col_width; ?> col-lg-<?php echo $col_width; ?>">
					<?php $y = date('Y');
					$m = date('n'); ?>
					<?php
					$months = array('JANUARY', 'FEBRUARY', 'MARCH', 'APRIL', 'MAY', 'JUNE', 'JULY', 'AUGUST', 'SEPTEMBER', 'OCTOBER', 'NOVEMBER', 'DECEMBER');
					?>
					<input class="month-picker form-control" value="<?php echo date('F Y'); ?>">

				</div>
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 text-right">
					<a href="#" id="my-tasks" style="">
						<img src="<?php echo base_url(); ?>images/my-tasks.png"/>My Tasks
					</a>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div id="cal-body" >
			<!--calendar will load here-->
		</div>
	</div>

</div>
<?php //echo $maincontent; ?>
<div class="clear"></div>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tooltip.min.js"></script>
<script>
	var dt = new Date();
	var month = dt.getMonth()+1, year = dt.getFullYear();
	var base_url = '<?php echo base_url(); ?>';
	var mytask = false;
	$(document).ready(function(){
		load_calendar();
		$("#select-job").change(function(){
			filter_job($(this).val(), $("#select-contractor").val(), $("#select-tasks").val(), $("#tasks-open-close").val());
			filter_my_tasks();
			return false;
		});
		
		$("#select-tasks").change(function(){
			filter_job($("#select-job").val(), $("#select-contractor").val(), $(this).val(), $("#tasks-open-close").val());
			filter_my_tasks();
			return false;
		});
		
		$("#tasks-open-close").change(function(){
			filter_job($("#select-job").val(), $("#select-contractor").val(), $("#select-tasks").val(), $(this).val());
			filter_my_tasks();
			return false;
		});
		
		$("#select-contractor").change(function(){
			filter_job($("#select-job").val(), $("#select-contractor").val(), $("#select-tasks").val(), $("#tasks-open-close").val());
			filter_my_tasks();
			return false;
		});
		$("#select-date").change(function(){
			year = $(this).val().split("-")[0];
			month = $(this).val().split("-")[1];
			load_calendar();
			return false;
		});
		$("#my-tasks").click(function(){
			mytask = !mytask;
			filter_my_tasks();
			$(this).toggleClass('active');
			return false;
		});

		$('.month-picker').datepicker( {
			changeMonth: true,
			changeYear: true,
			showButtonPanel: true,
			dateFormat: 'MM yy',
			onClose: function(dateText, inst) {
				month = $("#ui-datepicker-div .ui-datepicker-month :selected").val();
				year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
				$(this).datepicker('setDate', new Date(year, month, 1));
				month++;
				load_calendar();
			}
		});

	});

	$(document).ajaxStart(function(){
		var overlay = jQuery('<div id="overlay"> </div>');
		overlay.appendTo($("#cal-body"));
	});

	function load_calendar(){
		$("#cal-body").load(base_url + 'calendar/get_calendar/'+month+"/"+year,function(){
			filter_job($("#select-job").val(), $("#select-contractor").val(), $("#select-tasks").val(), $("#tasks-open-close").val());
			filter_my_tasks();
			$("#cal-body li").tooltip({
				'opacity' : 1,

			});
		});
	}

	function filter_job(jid, cid, p_c_task, o_c_task){

		$("#cal-body li").hide();

		if(jid == "" && (cid == undefined || cid == '') && p_c_task == 'all_tasks' && o_c_task== 'all'){

			$("#cal-body li").show();

		}else{

			if(jid != '' && (cid != undefined || cid != '') && (p_c_task == 'pre_construction' || p_c_task == 'construction') && o_c_task== 'complete'){

				$("#cal-body li.status-"+o_c_task+".job-"+jid+"."+p_c_task+".contractor-"+cid).show();

			}else if(jid != '' && (cid != undefined || cid != '') && (p_c_task == 'pre_construction' || p_c_task == 'construction') && o_c_task== 'open'){
				$("#cal-body li").show();
				$("#cal-body li.status-complete.job-"+jid+"."+p_c_task+".contractor-"+cid).hide();
			}else{

				if(jid != '' && (cid == undefined || cid == '') && p_c_task == 'all_tasks' && o_c_task == 'all'){
					$("#cal-body li.job-"+jid).show();
				}
				if(jid != '' && (cid != undefined || cid != '') && p_c_task == 'all_tasks' && o_c_task == 'all'){
					$("#cal-body li.job-"+jid+".contractor-"+cid).show();
				}
				if(jid != '' && (cid != undefined || cid != '') && p_c_task != 'all_tasks' && o_c_task == 'all'){
					$("#cal-body li.job-"+jid+"."+p_c_task+".contractor-"+cid).show();
				}
				if(jid != '' && (cid == undefined || cid == '') && p_c_task != 'all_tasks' && o_c_task == 'all'){
					$("#cal-body li.job-"+jid+"."+p_c_task).show();
				}
				if(jid != '' && (cid == undefined || cid == '') && p_c_task != 'all_tasks' && o_c_task != 'all'){
					if(jid != '' && (cid == undefined || cid == '') && p_c_task != 'all_tasks' && o_c_task == 'complete'){
						$("#cal-body li.status-"+o_c_task+".job-"+jid+"."+p_c_task).show();
					}
					if(jid != '' && (cid == undefined || cid == '') && p_c_task != 'all_tasks' && o_c_task == 'open'){
						$("#cal-body li.status-overdue.job-"+jid+"."+p_c_task).show();
						$("#cal-body li.status-ontheway.job-"+jid+"."+p_c_task).show();
						$("#cal-body li.status-pending.job-"+jid+"."+p_c_task).show();
					}
				}
				if(jid != '' && (cid == undefined || cid == '') && p_c_task == 'all_tasks' && o_c_task != 'all'){
					if(jid != '' && (cid == undefined || cid == '') && p_c_task == 'all_tasks' && o_c_task == 'complete'){
						$("#cal-body li.status-"+o_c_task+".job-"+jid).show();
					}
					if(jid != '' && (cid == undefined || cid == '') && p_c_task == 'all_tasks' && o_c_task == 'open'){
						$("#cal-body li.status-overdue.job-"+jid).show();
						$("#cal-body li.status-ontheway.job-"+jid).show();
						$("#cal-body li.status-pending.job-"+jid).show();
					}
				}
				if(jid != '' && (cid != undefined || cid != '') && p_c_task == 'all_tasks' && o_c_task != 'all'){
					if(jid != '' && (cid != undefined || cid != '') && p_c_task == 'all_tasks' && o_c_task == 'complete'){
						$("#cal-body li.status-"+o_c_task+".job-"+jid+".contractor-"+cid).show();
					}
					if(jid != '' && (cid != undefined || cid != '') && p_c_task == 'all_tasks' && o_c_task == 'open'){
						$("#cal-body li.status-overdue.job-"+jid+".contractor-"+cid).show();
						$("#cal-body li.status-ontheway.job-"+jid+".contractor-"+cid).show();
						$("#cal-body li.status-pending.job-"+jid+".contractor-"+cid).show();
					}
				}
				
				if(jid == '' && (cid != undefined || cid != '') && p_c_task == 'all_tasks' && o_c_task == 'all'){
					$("#cal-body li.contractor-"+cid).show();
				}
				if(jid == '' && (cid != undefined || cid != '') && p_c_task != 'all_tasks' && o_c_task == 'all'){
					$("#cal-body li."+p_c_task+".contractor-"+cid).show();
				}
				if(jid == '' && (cid != undefined || cid != '') && p_c_task != 'all_tasks' && o_c_task != 'all'){
					if(jid == '' && (cid != undefined || cid != '') && p_c_task != 'all_tasks' && o_c_task == 'complete'){
						$("#cal-body li.status-"+o_c_task+"."+p_c_task+".contractor-"+cid).show();
					}
					if(jid == '' && (cid != undefined || cid != '') && p_c_task != 'all_tasks' && o_c_task == 'open'){
						$("#cal-body li.status-overdue."+p_c_task+".contractor-"+cid).show();
						$("#cal-body li.status-ontheway."+p_c_task+".contractor-"+cid).show();
						$("#cal-body li.status-pending."+p_c_task+".contractor-"+cid).show();
					}
				}
				if(jid == '' && (cid != undefined || cid != '') && p_c_task == 'all_tasks' && o_c_task != 'all'){
					if(jid == '' && (cid != undefined || cid != '') && p_c_task == 'all_tasks' && o_c_task == 'complete'){
						$("#cal-body li.status-"+o_c_task+".contractor-"+cid).show();
					}
					if(jid == '' && (cid != undefined || cid != '') && p_c_task == 'all_tasks' && o_c_task == 'open'){
						$("#cal-body li.status-overdue.contractor-"+cid).show();
						$("#cal-body li.status-ontheway.contractor-"+cid).show();
						$("#cal-body li.status-pending.contractor-"+cid).show();
					}
				}
				
				
				if(jid == '' && (cid == undefined || cid == '') && p_c_task != 'all_tasks' && o_c_task == 'all'){
					$("#cal-body li.status-"+o_c_task).show();
				}
				if(jid == '' && (cid == undefined || cid == '') && p_c_task != 'all_tasks' && o_c_task != 'all'){
					if(jid == '' && (cid == undefined || cid == '') && p_c_task != 'all_tasks' && o_c_task == 'complete'){
						$("#cal-body li.status-"+o_c_task+"."+p_c_task).show();
					}
					if(jid == '' && (cid == undefined || cid == '') && p_c_task != 'all_tasks' && o_c_task == 'open'){
						$("#cal-body li.status-overdue."+p_c_task).show();
						$("#cal-body li.status-ontheway."+p_c_task).show();
						$("#cal-body li.status-pending."+p_c_task).show();
					}
				}
				
				if(jid == '' && (cid == undefined || cid == '') && p_c_task == 'all_tasks' && o_c_task != 'all'){
					if(jid == '' && (cid == undefined || cid == '') && p_c_task == 'all_tasks' && o_c_task == 'complete'){
						$("#cal-body li.status-"+o_c_task).show();
					}
					if(jid == '' && (cid == undefined || cid == '') && p_c_task == 'all_tasks' && o_c_task == 'open'){
						$("#cal-body li.status-overdue").show();
						$("#cal-body li.status-ontheway").show();
						$("#cal-body li.status-pending").show();
					}
				}			
				
			}
		}
	}

	function filter_my_tasks(){
		if(mytask){
			$("#cal-body li:visible:not('.mytask')").hide();
		}else{
			$("#cal-body li").show();
			filter_job($("#select-job").val(), $("#select-contractor").val(), $("#select-tasks").val(), $("#tasks-open-close").val());
		}
	}

	function statuschange(id){
		jQuery.ajax({
			url: base_url + 'calendar/statuschange/' + id,
			type: 'GET',
			success: function(data) 
			{
				load_calendar();   
			},
		});
	}
</script>