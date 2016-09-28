<?php 
	if (isset($massage)) echo $message; 

	$ci = &get_instance();
	$ci->load->model('timesheet_model');
?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.tooltip/jquery.tooltip.css">
<style>
    .time-block {
        /*border-bottom-left-radius: 0.5em;
        border-top-left-radius: 0.5em;*/
        color: white;
        display: inline-block;
        float: left;
        padding: 5px 0;
        text-align: center;
        height: 46px;
    }
    .contractor_projects a:first-child .time-block{
        border-top-left-radius: 5px;
		border-bottom-left-radius: 5px;
    }
    .contractor_projects a:last-child .time-block {
        border-bottom-right-radius: 5px;
        border-top-right-radius: 5px; 
    }
    .contractor_name {
        color: gray;
        font-size: 20px;
        font-weight: normal;
        height: auto;
        padding: 9px 18px 0 0;
        text-align: right;
    }
    .contractor_row {
        margin-bottom: 19px;
    }
    .contractor_name, .contractor_projects {
        float: left;
    }
    .fancybox-inner{
        border-radius: 10px;
    }
    .fancybox-skin {
        border: none;
        background-color: transparent;
        box-shadow: 0 0 0 rgba(0, 0, 0, 0) !important;
    }
    @media (max-width: 766px) {
        .time-block{
            font-size: 80%;
        }
    }

	div.jquery-gdakram-tooltip div.content {
		background-color: white;
		border: 5px solid #671329;
		border-radius: 1em;
		float: left;
		min-height: 200px;
		padding:5px;
		width: 100px;
		color: black;
	}
	div.jquery-gdakram-tooltip div.content h1 {
		border-bottom: 1px solid #c4c4c4;
		font-size: 14px;
		margin-top: 8px;
		padding-bottom: 5px;
	}

</style>


<div id="all-title">
    <div class="row">
        <div class="col-md-12">
			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
            	<img width="35" src="<?php echo base_url() ?>images/title-icon.png"/>
            	<span class="title-inner"><?php echo $title; ?></span>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
            	<input type="text" value="Select Week" id="selWeek" class="btn btn-default" style="float: right;">
			</div>

			<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
				<div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
					<label style="font-size:13px; padding-top:10px">Generate Time Sheet</label>
				</div>
				<div class="col-xs-12 col-sm-12 col-md-7 col-lg-7 col-xl-7">
					<select name="staff_list" id="staff_list" class="form-control">
						<option value="">Select Staff</option>
						<option value="all">All Staff</option>
					<?php foreach($staff_list as $staff){ ?>
						<option value="<?php echo $staff->staff_id; ?>"><?php echo $staff->username; ?></option>
					<?php } ?>
					</select>
				</div>
			</div>
			
        </div>
    </div>
</div>
<div class="content-inner">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<h3>Managers Time Sheets:</h3>
		</div>
	</div>

    <?php $total_hour_manager = 0; $total_hour_staff = 0; ?>

<?php foreach($times as $uid => $time): 
	
	if($ci->timesheet_model->check_role(2,$time['user_id'])){
?>
    <div class="row contractor_row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			
            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 contractor_name"><?php echo $time['username']; ?></div>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 contractor_projects">
            <?php foreach($time['projects'] as $pid => $pinfo): ?>
                <?php
                /*getting */
				$project_minutes = $pinfo['project_time']%60;
				$project_hours = ($pinfo['project_time'] - $project_minutes)/60;
                $percent = round($pinfo['project_time'] * 100 / $time['total_time'], 2);
				$tooltip = "";
                $tooltip_class = "";

				$tooltip_title = "<span>{$pinfo['project_name']}</span>";
				$tooltip = "<div class='tooltip_description' title='{$tooltip_title}' style='display:none;' ><br>$percent % - ( $project_hours : $project_minutes hours ) </div>";


                ?>
                <a data-fancybox-type="iframe" class="fbox" href="<?php echo site_url('timesheet/staff_weekly_project_time/'.$uid.'/'.$pid.'/'.$this->uri->segment(2)); ?>">
                <div class="time-block <?php if($percent < 20){ ?> tool <?php } ?>" style="font-size:10px; width: <?php echo $percent;?>%;background-color: <?php echo $colors[$pid]; ?>; <?php if($percent < 18 && $percent > 8 ){ ?><?php } ?>"><?php echo $tooltip;  echo $pinfo['project_name']."<br>".$percent."% - ( ".$project_hours.":".$project_minutes." hours )"; ?></div>
				<?php ?>
                </a>
            <?php endforeach; ?>
            </div>
			<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2" style="padding-top:10px;"><b>Total Hours:
				<?php
                    $total_mins = $time['total_time']%60;
					$total_hrs = ($time['total_time'] - $total_mins)/60;
					echo $total_hrs."hr ".$total_mins." min";
                    $total_hour_manager += $time['total_time'];

				?> </b>
			</div>
        </div>

    </div>
<?php 
	}
	endforeach;

?>
    <?php if($total_hour_manager > 0): ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
                <?php
                $total_mins = $total_hour_manager%60;
                $total_hrs = ($total_hour_manager - $total_mins)/60;
                ?>
                <p class="text-right" style="color: black; font-weight: bold">
                Total hours (Manager): <?php echo $total_hrs."hr ".$total_mins." min"; ?>
                </p>
            </div>
        </div>
    <?php endif; ?>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<h3>Staff Time Sheets:</h3>
		</div>
	</div>

<?php foreach($times as $uid => $time): 
	
	if($ci->timesheet_model->check_role(3,$time['user_id'])){
?>
    <div class="row contractor_row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			
            <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2 contractor_name"><?php echo $time['username']; ?></div>
            <div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 col-xl-8 contractor_projects">
            <?php foreach($time['projects'] as $pid => $pinfo): ?>
                <?php
                /*getting */
				$project_minutes = $pinfo['project_time']%60;
				$project_hours = ($pinfo['project_time'] - $project_minutes)/60;
                $percent = round($pinfo['project_time'] * 100 / $time['total_time'], 2);
				$tooltip = "";
                $tooltip_class = "";

				$tooltip_title = "<span>{$pinfo['project_name']}</span>";
				$tooltip = "<div class='tooltip_description' title='{$tooltip_title}' style='display:none;' ><br>$percent % - ( $project_hours : $project_minutes hours ) </div>";


                ?>
                <a data-fancybox-type="iframe" class="fbox" href="<?php echo site_url('timesheet/staff_weekly_project_time/'.$uid.'/'.$pid.'/'.$this->uri->segment(2)); ?>">
                <div class="time-block <?php if($percent < 20){ ?> tool <?php } ?>" style="font-size:10px; width: <?php echo $percent;?>%;background-color: <?php echo $colors[$pid]; ?>; <?php if($percent < 18 && $percent > 8 ){ ?><?php } ?>"><?php echo $tooltip;  echo $pinfo['project_name']."<br>".$percent."% - ( ".$project_hours.":".$project_minutes." hours )"; ?></div>
				<?php ?>
                </a>
            <?php endforeach; ?>
            </div>
			<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2" style="padding-top:10px;"><b>Total Hours:
				<?php
                    $total_mins = $time['total_time']%60;
					$total_hrs = ($time['total_time'] - $total_mins)/60;
					echo $total_hrs."hr ".$total_mins." min";
                    $total_hour_staff +=  $time['total_time'];
				?> </b>
			</div>
        </div>

    </div>
<?php 
	}
	endforeach;
?>
    <?php if($total_hour_staff > 0): ?>
        <div class="row">
            <div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
                <?php
                $total_mins = $total_hour_staff%60;
                $total_hrs = ($total_hour_staff - $total_mins)/60;
                ?>
                <p class="text-right" style="color: black; font-weight: bold">
                    Total hours (Staff): <?php echo $total_hrs."hr ".$total_mins." min"; ?>
                </p>
            </div>
        </div>
    <?php endif; ?>
</div>

<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tooltip.min.js"></script>

<script type="text/javascript">
    $(document).ready(function() {
        var startDate;
        var endDate;
        var base_url = "<?php echo base_url(); ?>";

		$(".contractor_projects div.tool").tooltip({
				'opacity' : 1,
		});

       /* var selectCurrentWeek = function() {
            window.setTimeout(function () {
                $('#selWeek').find('.ui-datepicker-current-day a').addClass('ui-state-active')
            }, 1);
        }*/
        var dt = new Date();
        var diff = 7 - dt.getDay();
        var location = window.location;
        var first_time_hover = 0;
        $('#selWeek').datepicker( {
            firstDay: 1,
            showOtherMonths: true,
            selectOtherMonths: true,
            maxDate: '+'+diff,
            dateFormat: 'yy-mm-dd',
            onSelect: function(dateText, inst) {
                $("#selWeek").val('Select Week');
                var date = $(this).datepicker('getDate');
                startDate = new Date(date.getFullYear(), date.getMonth(), date.getDate() - date.getDay()+1);
                dt = startDate.getFullYear()+"-"+(startDate.getMonth()+1)+"-"+startDate.getDate();
                window.location = base_url+'staff-timesheets/'+dt;
                return false;
            },
            onClose: function(){
                first_time_hover = 0;
            }
            /*
            beforeShowDay: function(date) {
                var cssClass = '';
                if(date >= startDate && date <= endDate)
                    cssClass = 'ui-datepicker-current-day';
                return [true, cssClass];
            },
            onChangeMonthYear: function(year, month, inst) {
                selectCurrentWeek();
            }*/
        });

        $('body').delegate('#ui-datepicker-div  tr', 'mouseover', function() {
            if(first_time_hover){

                $(this).find('td a').addClass('ui-state-highlight');
            }else{
                first_time_hover = 1;
            }
        });
        $('body').delegate('#ui-datepicker-div  tr', 'mouseleave', function() {
            $(this).find('td a').removeClass('ui-state-highlight');
        });


		/* Staff Time Sheet */
		$('#staff_list').change( function(){
			userid = $('#staff_list').val();

			<?php 

				$week = $this->uri->segment(2);
				if(!$week){$week = date("Y-m-d",strtotime("last monday"));}
			?>


			newurl = base_url+'timesheet/download_timesheet/'+'<?php echo $week; ?>'+'/'+userid;
			window.location.href = newurl;	
		});

    });

    /*tour. task #4421*/
    var config = [
            {
                "name" 		: "tour_1",
                "bgcolor"	: "black",
                "color"		: "white",
                "position"	: "T",
                "text"		: "From here you can see your staff time sheet also with managers' time sheets. Click the bar to see the a brief detail for how many hours have done on each project from each staff / manager.",
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
    $(document).ready(function(){
        $("#maincontent").addClass('tour_1');
    })
</script>

</script>
<script>
    $(document).ready(function(){
        $("a.fbox").fancybox({
            padding: 0,
            margin: 0,
            iframe:{
                scrolling : 'no'
            }
        });

    })
</script>