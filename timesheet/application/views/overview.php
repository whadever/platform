<?php if (isset($massage)) echo $message; ?>
<style>
    #legend ul {
        display: table;
        width: 100%;
        list-style: none;
    }
    #legend ul li {
        display: table-cell;
        text-align: center;
    }
    .legendMarker span {
        vertical-align: middle;
    }
    .legend_square {
        display: inline-block;
        height: 16px;
        width: 16px;
    }
    #accordion .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    }
    #accordion h3 {
        color: gray;
        font-size: 18px;
        font-weight: bold;
        margin-top: 10px !important;
    }
    #accordion h3.active{
        border-bottom: none;
    }
    #maincontent .ui-accordion .ui-accordion-content {
        color: gray;
        font-size: 17px;
        padding: 1px 30px 4px 24px;
    }
    #maincontent .ui-accordion .ui-accordion-content > div{
        margin: 0 0 5px;
    }
    .ui-state-default .ui-icon {
        background-image: url("<?php echo base_url().'images/icons/arrow.png'; ?>");
    }
    h3.ui-state-active .ui-icon {
        background-image: url("<?php echo base_url().'images/icons/arrow-b.png'; ?>");
    }
    #maincontent .ui-accordion .ui-accordion-header .ui-accordion-header-icon {
        left: 100%;
        margin-left: -35px;
        padding-left: 1em;
        position: absolute;
        top: 42%;
        width: 26px;
        height: 26px;
        background-position: 0 0;
    }
    #chartContainer{
        height: 600px
    }
    .hours{
        float: right;
    }
    @media screen and (max-width: 360px) {
        #accordion h3 {
            font-size: 14px;
        }
        #maincontent .ui-accordion .ui-accordion-content {
            font-size: 12px;
            padding: 1px 5px 4px 16px;
        }
        h3.ui-state-active .ui-icon {
            background-image: url("http://localhost/wclp/timesheet/images/icons/arrow-b-small.png");
        }
        .ui-state-default .ui-icon {
            background-image: url("http://localhost/wclp/timesheet/images/icons/arrow-small.png");
        }
        #maincontent .ui-accordion .ui-accordion-header .ui-accordion-header-icon {
            margin-left: -22px;
        }
        #chartContainer{
            height: 300px
        }
        .hours {
            clear: left;
            float: left;
            font-size: 10px;
        }
    }

</style>
<div id="all-title">
    <div class="row">
        <div class="col-md-12">
            <img width="35" src="<?php echo base_url() ?>images/title-icon.png"/>
            <span class="title-inner"><?php echo $title; ?></span>
        </div>
    </div>
</div>

<div class="content-inner">
    <div class="row">
        <div class="col-md-6">
            <div id="accordion" style="visibility: hidden">
				<?php 
					if( $user_app_role == 'admin'){
				?>
				<h3>Staff Hours This Week</h3>
				<div>
					<?php
					$lg = '';
                    for($i = 0; $i<count($user_entries); $i++){
                    ?>
                        <div>
                                <span style="float: left"><?php echo $user_entries[$i]->username; ?></span>
                                <span class="hours">
									<?php 
										$mints = $user_entries[$i]->total_time % 60;
										$hrs = ( $user_entries[$i]->total_time - $mints )  / 60;
										echo $hrs." Hours ".$mints." Minutes"; 
										$lg[] = $user_entries[$i]->username;
									?>
								</span>
                                <div style="clear: both"></div>
                        </div>
                    <?php
                    }

					$legend =  json_encode($lg);

                    ?>
				</div>
				<h3>Staff Hours Last Week</h3>
                <div>
					<?php
                    for($i = 0; $i<count($user_last_week); $i++){
                    ?>
                        <div>
                                <span style="float: left"><?php echo $user_last_week[$i]->username; ?></span>
                                <span class="hours">
									<?php 
										$mints = $user_last_week[$i]->total_time % 60;
										$hrs = ( $user_last_week[$i]->total_time - $mints )  / 60;
										echo $hrs." Hours ".$mints." Minutes"; 
									?>
								</span>
                                <div style="clear: both"></div>
                        </div>
                    <?php
                    }
                    ?>
				</div>
				<h3>Staff Hours Last Month</h3>
                <div>
					<?php
                    for($i = 0; $i<count($user_last_month); $i++){
                    ?>
                        <div>
                                <span style="float: left"><?php echo $user_last_month[$i]->username; ?></span>
                                <span class="hours">
									<?php 
										$mints = $user_last_month[$i]->total_time % 60;
										$hrs = ( $user_last_month[$i]->total_time - $mints )  / 60;
										echo $hrs." Hours ".$mints." Minutes"; 
									?>
								</span>
                                <div style="clear: both"></div>
                        </div>
                    <?php
                    }
                    ?>
				</div>
				
				<?php }else{ ?>
				
                <h3>You have done <?php echo $this_week_total; ?> this week</h3>
                <div>
                    <?php
                    foreach($this_week_hours as $h):
                    ?>
                        <div>
                                <span style="float: left"><?php echo $h['project']; ?></span>
                                <span class="hours"><?php echo $h['hours']; ?></span>
                                <div style="clear: both"></div>
                        </div>
                    <?php
                    endforeach;
                    ?>
                </div>
                <h3>You completed <?php echo $last_week_total; ?> last week</h3>
                <div>
                    <?php
                    foreach($last_week_hours as $h):
                        ?>
                        <div>
                                <span style="float: left"><?php echo $h['project']; ?></span>
                                <span class="hours"><?php echo $h['hours']; ?></span>
                                <div style="clear: both"></div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
                <h3>You completed <?php echo $last_month_total; ?> in <?php echo date('F',strtotime('last month')); ?></h3>
                <div>
                    <?php
                    foreach($last_month_hours as $h):
                        ?>
                        <div>
                                <span style="float: left"><?php echo $h['project']; ?></span>
                                <span class="hours"><?php echo $h['hours']; ?></span>
                                <div style="clear: both"></div>
                        </div>
                        <?php
                    endforeach;
                    ?>
                </div>
				<?php } ?>
            </div>
        </div>
        <div class="col-md-6">
            <div id="chartContainer" style="width: 100%;"></div>
            <div id="legend">
                <ul id="legend_inner">

                </ul>
            </div>
        </div>
    </div>
</div>


		<?php 

				for($i = 0; $i<count($time_entries); $i++){
					$time_arr[$time_entries[$i]->uid][$time_entries[$i]->id] = $time_entries[$i]->total_time;
				}

				if( $user_app_role == 'admin'){
		?>

		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
		    google.load("visualization", "1", {packages:["corechart"]});
		    google.setOnLoadCallback(drawChart);
		    var legend_texts = <?php echo $legend; ?>;
	
		function drawChart() {

			var data = google.visualization.arrayToDataTable([
	           ['Day', 'Hours',{type:'string', role:'tooltip', 'p': {'html': true}}],
	            <?php
	            for($i = 0; $i<count($user_entries); $i++){
					echo "['{$user_entries[$i]->username}', {$user_entries[$i]->total_time},";
					$htm = "<table><th><u>".$user_entries[$i]->username."</u></th>";
					for($j = 0; $j<count($project_entries); $j++){
						if($time_arr[$user_entries[$i]->uid][$project_entries[$j]->pid]){
							$total_mints = $time_arr[$user_entries[$i]->uid][$project_entries[$j]->pid];
							$mints = $total_mints % 60;
							$hrs = ($total_mints - $mints)/60;
							$total_time = $hrs.' hours';
							if($mints > 0){
								$total_time = $total_time.' '.$mints.' minutes';
							}
							$htm = $htm.'<tr><td>'.$project_entries[$j]->project_name.'&nbsp;:&nbsp;'.$total_time.'</td></tr>';
						}
					}
	                echo "'".$htm."</table>'],";
	            }
	            ?>
				
	        ]);

	
			var options = {
				title: 'Project User & Time Chart',
	            legend: 'none',
	            tooltip: { isHtml: true, trigger: 'focus' },
	            pieSliceText: 'label',
	            chartArea:{width: '80%', height: '80%'},
	            slices: [
	                {color: '#BE2126'},
	                {color: '#D82B38'},
	                {color: '#F5803B'},
	                {color: '#FBB83A'},
	                {color: '#F9C573'},
	                {color: '#FFDCA9'},
	                {color: '#FFE9CC'}
	            ],
				is3D: true,
	        };


	        var chart = new google.visualization.PieChart(document.getElementById('chartContainer'));
	        chart.draw(data, options);
	
	        /*legend*/
	        var legend = document.getElementById("legend_inner");
	        var legItem = [];
	        var colors = ['#BE2126', '#D82B38', '#F5803B', '#FBB83A', '#F9C573', '#FFDCA9', '#FFE9CC'];
	        for (var i = 0; i < data.getNumberOfRows(); i++) {
	            // This will create legend list for the display
	            legItem[i] = document.createElement('li');
	            legItem[i].innerHTML = '<div class="legendMarker"><span style="background-color:' + colors[i] + ';"'+ ' class="legend_square"></span> <span style="color:' + colors[i] + ';">' + legend_texts[i] + '</span></div>';
	            legend.appendChild(legItem[i]);
	        }

		}

		<?php }else{ ?>


		<script type="text/javascript" src="https://www.google.com/jsapi"></script>
		<script type="text/javascript">
		    google.load("visualization", "1", {packages:["corechart"]});
		    google.setOnLoadCallback(drawChart);
		    var legend_texts = <?php echo $legend; ?>;

		function drawChart() {

        var data = google.visualization.arrayToDataTable([
            ['Day', 'Hours',{type:'string', role:'pieSliceText'}],
            <?php
            foreach($hours as $h){
                echo "['{$h['label']}', {$h['hour']}, 'abc'],";
            }
            ?>
        ]);


		var options = {
            legend: 'none',
            tooltip: { trigger: 'none' },
            pieSliceText: 'label',
            chartArea:{width: '80%', height: '80%'},
            slices: [
                {color: '#BE2126'},
                {color: '#D82B38'},
                {color: '#F5803B'},
                {color: '#FBB83A'},
                {color: '#F9C573'},
                {color: '#FFDCA9'},
                {color: '#FFE9CC'}
            ]
        };

        var chart = new google.visualization.PieChart(document.getElementById('chartContainer'));
        chart.draw(data, options);

        /*legend*/
        var legend = document.getElementById("legend_inner");
        var legItem = [];
        var colors = ['#BE2126', '#D82B38', '#F5803B', '#FBB83A', '#F9C573', '#FFDCA9', '#FFE9CC'];
        for (var i = 0; i < data.getNumberOfRows(); i++) {
            // This will create legend list for the display
            legItem[i] = document.createElement('li');
            legItem[i].innerHTML = '<div class="legendMarker"><span style="background-color:' + colors[i] + ';"'+ ' class="legend_square"></span> <span style="color:' + colors[i] + ';">' + legend_texts[i] + '</span></div>';
            legend.appendChild(legItem[i]);
        }


	 }

		<?php } ?>

        
   
    $(document).ready(function(){
        $( "#accordion" ).accordion({
            active: false,
            collapsible: true,
            beforeActivate: function( event, ui ) {
                if(ui.newHeader[0] != undefined){
                    $(ui.newHeader).addClass('active');
                }
            },
            activate: function(event, ui) {
                if(ui.oldHeader[0] != undefined){
                    $(ui.oldHeader).removeClass('active');
                }
            }
        });
        $("#accordion").css('visibility','visible');
    });

    /*tour. task #4422*/
    var config = [
            {
                "name" 		: "tour_1",
                "bgcolor"	: "black",
                "color"		: "white",
                "position"	: "T",
                "text"		: "From this page you can see how many hours you have done this week, last week, and last month in a glance.",
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
        $(".active").addClass('tour_1');
    })
</script>