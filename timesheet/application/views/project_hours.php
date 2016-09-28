<?php if (isset($massage)) echo $message; ?>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.tooltip/jquery.tooltip.css">
<style>
    .time-block {
        border-bottom-left-radius: 0.5em;
        border-top-left-radius: 0.5em;
        color: white;
        display: inline-block;
        float: left;
        margin-left: -10px;
        padding: 5px 0;
        text-align: center;
        height: 46px;
    }
    .contractor_projects a:first-child .time-block{
        border-radius: 0.6em;
    }
    .contractor_projects a:last-child .time-block {
        border-bottom-right-radius: 0.5em;
        border-top-right-radius: 0.5em;
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
			<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 col-xl-10">
            	<img width="35" src="<?php echo base_url() ?>images/title-icon.png"/>
            	<span class="title-inner"><?php echo $title; ?></span>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2">
            	<input type="text" value="Select Week" id="selWeek" class="btn btn-default" style="float: right;">
			</div>
			
        </div>
    </div>
</div>
<div class="content-inner">

	<?php

		$ci = & get_instance();       
		$ci->load->model('project_model');  
        
		$projects = $this->project_model->get_project_list()->result();

		$start_date = form_label('Date From', 'start_date');
		$start_date .= form_input(array(
			'name'        => 'start_date',
			'id'          => 'edit-start_date',
			'value'       => isset($get['start_date']) ? $get['start_date'] : '',
			'class'       => 'tmshdatepicker form-control',
	  		'placeholder'=>'Select Date'
		)); 
	       
	
		$end_date = form_label('Date To', 'end_date');
		$end_date .= form_input(array(
			'name'        => 'end_date',
			'id'          => 'edit-end_date',
			'value'       => isset($get['end_date']) ? $get['end_date'] : '',
			'class'       => 'tmshdatepicker form-control',
	  		'placeholder'=>'Select Date'
		));
		


	?>

	<form action="" name="search_timesheet" method="post">
    <div class="row contractor_row">
        <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <label>Select Project:</label>
			<select name="project_list" class="form-control">
				<option value="">Select Project</option>
				<?php foreach($projects as $project):?>
				<option value="<?php echo $project->id; ?>"><?php echo $project->project_name ?></option>
				<?php endforeach; ?>
			</select>
        </div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <?php echo $start_date; ?>
        </div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
            <?php echo $end_date; ?>
        </div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
		</div>
		<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
			<input type="submit" class="btn btn-default" value="Search" id="search-button" style="width:100%" >
		</div>
		
    </div>
	</form>

	<div class="row">
		<!---<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4">
		<?php if(isset($total_time)){ $minutes = $total_time % 60; $hours =  ( $total_time - $minutes ) /60;  ?>	Total Hour : <?php echo $hours." Hours ".$minutes." Minutes";  }?>
		<br>--->
		<?php if(isset($total_time_ma)){ $minutes = $total_time_ma % 60; $hours =  ( $total_time_ma - $minutes ) /60;  ?>	Total hour for Manager : <?php echo $hours." Hours ".$minutes." Minutes";  }?>
		<br>
		<?php if(isset($total_time_con)){ $minutes = $total_time_con % 60; $hours =  ( $total_time_con - $minutes ) /60;  ?>	Total hour for Contractor : <?php echo $hours." Hours ".$minutes." Minutes";  }?>

	</div>

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

    });
/*tour. task #4422*/
var config = [
        {
            "name" 		: "tour_1",
            "bgcolor"	: "black",
            "color"		: "white",
            "position"	: "T",
            "text"		: "Select the project that you want to back cost.",
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