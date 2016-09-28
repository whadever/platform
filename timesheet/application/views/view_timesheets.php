<style>
    #accordion .ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default {
        background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
    }
    #accordion h3 {
        color: gray;
        font-size: 20px;
        font-weight: normal;
        margin-top: 10px !important;
    }
    #accordion h3.active{
        border-bottom: none;
    }
    #maincontent .ui-accordion .ui-accordion-content {
        color: gray;
        font-size: 20px;
        padding: 8px 25px 0;
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
    #accordion a {
        color: inherit !important;
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
        <!--<div class="col-md-12" style="color: black;
										font-size: 300%;
										font-weight: bold;
										margin-bottom: 20px;
										text-align: center;">
            VIEW PREVIOUS TIME SHEETS
        </div>-->
    </div>
    <div class="row">
        <div class="col-md-12" style="text-align: center">
            <div id="accordion" style="margin: 0 auto; visibility:hidden; width: 50%;">
                <h3 style="text-align: left;">-- Select Time Sheet--</h3>
                <div>
                    <?php
					$user = $this->session->userdata('user');
                    for($i=0; $i < count($weeks); $i++){

						if($i==0){$com_date = $weeks[$i]->start_date;}else{$com_date = $weeks[$i-1]->start_date;}

						if($user->created < $weeks[$i]->start_date && $com_date > $first_entry)
						{

	                        $start_date = date_create_from_format('Y-m-d',$weeks[$i]->start_date)->format('d F Y');
	                        $end_date = date_create_from_format('Y-m-d',$weeks[$i]->end_date)->format('d F Y');
	                        ?>
	                        <div>
	                            <span style="float: left">
	                                <?php if($weeks[$i]->user_id){ ?>
	                                <a href="<?php echo base_url()."timesheet/download_timesheet/{$weeks[$i]->start_date}"; ?>">
	                                <?php echo $start_date." - ".$end_date; ?>
	                                </a>
	                                <?php } else {?>
									<a href="<?php echo base_url()."weekly-timesheet/{$weeks[$i]->start_date}"; ?>">
	                                <?php echo $start_date." - ".$end_date; ?>
									</a>
	                                <?php } ?>
	                            </span>
	                            <?php
	                            $span = '<span style="height:16px; width:16px; border-radius:15px; margin-right: 5px;  display: inline-block;float: right; background-color:green">&nbsp;&nbsp;&nbsp;&nbsp;</span>'; //<span style="float: right; color:green">YES</span>
	                            if(!$weeks[$i]->user_id){
	                                $span = '<span style="height:16px; width:16px; border-radius:15px; margin-right: 5px;  background-color:red; display: inline-block;float: right;">&nbsp;&nbsp;&nbsp;&nbsp;</span>'; //<span style="float: right; color:red">NO</span>
	                            }
	                            ?>
	                            <?php echo $span; ?>
	                            <div style="clear: both"></div>
	                        </div>
	                        <?php

						} // End If Statement
                    } // End For Loop
                    ?>
                </div>
        </div>
    </div>
</div>

<div class="row">

	<div class="col-sm-12 col-md-9 col-lg-9 col-xl-9"></div>
	<div class="col-sm-12 col-md-3 col-lg-3 col-xl-3">
		<span class="tour_1"><span style="height:16px; width:16px; border-radius:15px; margin-right: 5px;  background-color:red; display: inline-block;">&nbsp;&nbsp;&nbsp;&nbsp;</span>Unsubmitted</span>&nbsp;&nbsp;
		<span class="tour_2"><span style="height:16px; width:16px; border-radius:15px; margin-right: 5px;  background-color:green; display: inline-block;">&nbsp;&nbsp;&nbsp;&nbsp;</span>Submitted</span>
	</div>

</div>

<script>
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
    /*tour. task #4421*/
    var config = [
            {
                "name" 		: "tour_1",
                "bgcolor"	: "black",
                "color"		: "white",
                "position"	: "RT",
                "text"		: "If you have not submitted your time sheet, that week will be indicated as unsubmitted, and to submit your report simply just go to that unsubmitted week and press 'Submit'.",
                "time" 		: 5000,
                "buttons"	: ["<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>"]
            },
            {
                "name" 		: "tour_2",
                "bgcolor"	: "black",
                "color"		: "white",
                "text"		: "If you have submitted your time sheet, tat week will be indicated as submitted and you can see your own time sheet PDF.",
                "position"	: "RT",
                "time" 		: 5000,
                "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>"]
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