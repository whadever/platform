<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.tooltip/jquery.tooltip.css">
<style>
	option {
		padding: 6px 12px;
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
		border: 2px solid #cc1618;
	}
	.day-number {
		color: #cc1618;
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
	}
	
	#cal-header select {
		margin: 6px 0;
	}
	.calendar-row li {
		border-bottom: 1px dashed;
		list-style: none;
		color: black;
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
		color:#fab800 !important;
		font-size: 24px
	}
	#my-tasks.active{
		color:white !important;
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
	#cal-header select, #cal-header .bootstrap-select, #cal-header .form-control {
		margin: 6px 0;
	}
	#overlay {
		background-color: #000;
		background-image: url("<?php echo base_url(); ?>images/ajax-loading.gif");
		background-position: 50% center;
		background-repeat: no-repeat;
		height: 100%;
		left: 0;
		opacity: 0.5;
		position: fixed;
		top: 0;
		width: 100%;
		z-index: 10000;
	}
	.ui-datepicker-calendar {
		display: none;
	}
	.calendar-row li.overdue{
		color: red;
	}
</style>
<div class="container maincontent">


<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<div id="cal-header" class="col-xs-12 col-sm-12 col-md-12 col-lg-12 tour tour_1">

			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<?php 
					$ci = & get_instance();
					$ci->load->model('request_model'); 	
					$managers = $ci->request_model->get_manager_list();   
				?>
				<div style="margin-top:8px">
					<select class="multiselectbox" id="select-manager" data-live-search="true">
						<option value="">---Select Manager---</option>
						<?php foreach($managers as $key => $manager ): ?>
							<option value="<?php echo $key ; ?>"><?php echo $manager; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
			</div>

			<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4">
				<?php if($contractors): ?>
				<div style="margin-top:8px">
					<select class="multiselectbox" id="select-contractor">
						<option value="">---Select Contractor---</option>
						<?php foreach($contractors as $c): ?>
							<option value="<?php echo $c->uid; ?>"><?php echo $c->username; ?></option>
						<?php endforeach; ?>
					</select>
				</div>
				<?php endif; ?>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3">
				<input class="month-picker form-control" value="<?php echo date('F Y'); ?>">

			</div>
			<!---<?php if($contractors): ?>
			<div class="col-xs-12 col-sm-12 col-md-3 col-lg-3 text-right">
				<a href="#" id="my-tasks" style=''">
					<img src="<?php echo base_url();?>images/my-tasks.png" />My Tasks
				</a>
			</div>
			<?php endif; ?>--->
		</div>
		<div id="cal-body"  class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<!--calendar will load here-->
		</div>

	</div>
</div>
<style>
#ui-datepicker-div{
	z-index: 999 !important;
}
.ui-multiselect-menu label {
    overflow: hidden;    
}
</style>
  <?php //echo $maincontent; ?>
</div>

<div class="clear"></div>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tooltip.min.js"></script>
<script>
	var dt = new Date();
	var month = dt.getMonth()+1, year = dt.getFullYear();
	var base_url = '<?php echo base_url(); ?>';
	var mytask = false;
	$(document).ready(function(){

		$(".multiselectbox").multiselect({
	        selectedText: "# of # selected"
	    });

		load_calendar();
		/*$("#select-job").change(function(){
			filter_contractor($(this).val());
			filter_my_tasks();
			return false;
		});*/
		$("#select-manager").change(function(){
			filter_manager($(this).val());
			//filter_my_tasks();
			return false;
		});
		$("#select-contractor").change(function(){
			filter_contractor($(this).val());
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
			filter_contractor($("#select-contractor").val());
			filter_my_tasks();
			$("#cal-body li").tooltip({
				'opacity' : 1,
			});
		});
	}

	function filter_manager(cid){
		if($("#select-manager").length == 0) return;
		if(cid == ""){
			$("#cal-body li").show();
		}else{
			$("#cal-body li").hide();
			$("#cal-body li.manager-"+cid).show();
		}
	}

	function filter_contractor(cid){
		if($("#select-contractor").length == 0) return;
		if(cid == ""){
			$("#cal-body li").show();
		}else{
			$("#cal-body li").hide();
			$("#cal-body li.contractor-"+cid).show();
		}
	}

	function filter_my_tasks(){
		if(mytask){
			$("#cal-body li:visible:not('.mytask')").hide();
		}else{
			$("#cal-body li").show();
			filter_contractor($("#select-contractor").val());
		}
	}

	/*tour. task #4421*/
	var config = [
			{
				"name" 		: "tour_1",
				"bgcolor"	: "black",
				"color"		: "white",
				"position"	: "T",
				"text"		: "Filter the calendar that you want to see from user, contractor, and month to month basis.",
				"time" 		: 5000,
				"buttons"	: ["<span class='btn btn-xs btn-default endtour'>close</span>"]
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

</script>
