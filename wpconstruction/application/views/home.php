<div class="container maincontent">
<!--home page for company who doesn't have a job-->
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 job-title">
		<h4>No job created. Please create a new one through "+" button</h4>
	</div>

<!-- don't need this for awhile-->

<!-- <div class="row">
	<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 job-title">
		<h3>Job: #000<?php echo $latest_job->id."&nbsp;";  echo $latest_job->development_name; ?></h3>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 job-title">
		<a href="<?php echo base_url('job/change_job'); ?>" data-fancybox-type="iframe" class="fancybox btn new-job" style="background-color:#f9b800; color:white; width:100%">
			Change Job
		</a>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<!-- Start menu -->
		<!-- <div id="cons_menu">
		
			<div id="first_level" class="con_menu">
				<div id="pre_construction" class="files"><a class="btn btn-default btn-cons" href="#one">Pre Construction</a></div>
				<div id="construction" class="mail"><a class="btn btn-default btn-cons" href="#two">Construction</a></div>
			</div>

			<div id="second_level" style="display:none;clear:both">

				<div id="one_child">
					<div class="sub-menu">
						<div id="check_list"><a class="btn btn-default btn-cons" href="#one">Check List</a></div>
						<div id="job_info"><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>developments/development_detail/<?php echo $latest_job->id; ?>">Job Information</a></div>
					</div>
				</div>

				<div id="two_child">
					<div class="sub-menu">
						<div id="dashboard"><a href="<?php echo base_url(); ?>stage/plan_vs_actual/<?php echo $latest_job->id; ?>/1" class="btn btn-default btn-cons">Dashboard</a></div>
						<div id="program"><a href="<?php echo base_url(); ?>developments/phases_underway/<?php echo $latest_job->id; ?>" class="btn btn-default btn-cons">Program</a></div>
						<div id="photos"><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>developments/development_photos/<?php echo $latest_job->id; ?>">Photos</a></div>
						<div id="notes"><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>developments/notes/<?php echo $latest_job->id; ?>">Notes</a></div>
					</div>
				</div>

			</div>

		
		</div>  -->
		<!-- end menu -->

		<!-- <script>
		function mainmenu(){
			
			$("#pre_construction").click(function(){
				$("#second_level").css({display: "block"});
				$("#two_child").css({display: "none"});
				$("#one_child").css({"visibility": "visible"}).show(400);

				$("#cons_menu a").css("background-color","#5a5b5d");
				$("#pre_construction a").css("background-color","#f9b800");
			});

			$("#construction").click(function(){
				$("#second_level").css({display: "block"});
				$("#one_child").css({display: "none"});
				$("#two_child").css({"visibility": "visible"}).show(400);

				$("#cons_menu a").css("background-color","#5a5b5d");
				$("#construction a").css("background-color","#f9b800");
			});

			$("#check_list").click(function(){
				$("#check_list_items").css({display: "block"});
				$("#check_list_items").css({"visibility": "visible"}).show(400);

				$("#cons_menu a").css("background-color","#5a5b5d");
				$("#check_list a").css("background-color","#f9b800");
				$("#pre_construction a").css("background-color","#f9b800");
			});
			

		}
		
		$(document).ready(function(){					
			mainmenu();
		});
		</script> -->
	<!-- </div>
</div> -->
<!-- 
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
		<div id="check_list_items" style="display:none;">
			<select name="stage_list">
				<option value="1">Stage 1</option>
				<option value="2">Stage 2</option>
				<option value="3">Stage 3</option>
			</select>
		</div>
	</div>
</div>

  <?php echo $maincontent; ?>   
</div> -->


<div class="clear"></div>