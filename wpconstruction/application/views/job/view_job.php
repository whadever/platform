<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<!-- Start menu -->
		<div id="cons_menu">
		
			<div id="first_level" class="con_menu">
				<div id="one" class="files"><a href="#one">Job</a></div>
				<div id="two" class="mail"><a href="#two">Tempate</a></div>
			</div>

			<div id="second_level" style="display:none;clear:both">

				<div id="one_child">
					<div class="sub-menu">
						<div id="notes"><a href="<?php echo base_url() ?>admindevelopment/development_list">Job List</a></div>
						<div id="checklists"><a href="<?php echo base_url() ?>admindevelopment/development_add">Add Job</a></div>
					</div>
				</div>

				<div id="two_child">
					<div class="sub-menu">
						<div id="plan"><a href="<?php echo base_url() ?>template/template_list">Template List</a></div>
						<div id="manage"><a href="<?php echo base_url() ?>template/template_start">Add Template</a></div>
					</div>
				</div>

			</div>
	
		</div>
		<!-- end menu -->

		<script>
		function mainmenu(){
			

			$("#one").click(function(){
				$("#second_level").css({display: "block"});
				$("#two_child,#three_child").css({display: "none"});
				$("#third_level").css({display: "none"});
				$("#one_child").css({"visibility": "visible"}).show(400);

				$("#cons_menu a").css("background-color","#a7aaad");
				$("#one a").css("background-color","#f9b800");
			});

			$("#two").click(function(){
				$("#second_level").css({display: "block"});
				$("#one_child,#three_child").css({display: "none"});
				$("#third_level").css({display: "none"});
				$("#two_child").css({"visibility": "visible"}).show(400);

				$("#cons_menu a").css("background-color","#a7aaad");
				$("#two a").css("background-color","#f9b800");
			});

			

		}
		
		$(document).ready(function(){					
			mainmenu();
		});
		</script>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-8 col-lg-8 job-title">
		<h3>Job: #000<?php echo $latest_job->id."&nbsp;";  echo $latest_job->development_name; ?></h3>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
		<table class="table table-bordered jobshow">
			<tr><td width="40%">Name</td><td width="60%"><?php echo $latest_job->development_name; ?></td></tr>
			<tr><td>Location</td><td><?php echo $latest_job->development_location; ?></td></tr>
			<tr><td>City</td><td><?php echo $latest_job->development_city; ?></td></tr>
			<tr><td>Job Size</td><td><?php echo $latest_job->development_size; ?></td></tr>
			<tr><td>Number of Stages</td><td><?php echo $latest_job->number_of_stages; ?></td></tr>
			<tr><td>Number if Lots (if applicable)</td><td><?php echo $latest_job->number_of_lots; ?></td></tr>
			<tr><td>Land Zone</td><td><?php echo $latest_job->land_zone; ?></td></tr>
			<tr><td>Ground Condition</td><td><?php echo $latest_job->ground_condition; ?></td></tr>
			<tr><td>Project Manager</td><td><?php echo $latest_job->project_manager; ?></td></tr>
		</table>
	</div>
</div>