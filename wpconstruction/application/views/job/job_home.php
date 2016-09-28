<style>
	select{
		font-size: 14px;
		font-weight: bold;
	}
	.task_list {
		background-color: white;
		font-size: 18px;
		margin-top: 12px;
	}
	.task_list tbody td {
		font-size: 16px;
	}
	.task_list td {
		padding: 7px;
	}
	.task_list tr:nth-child(2n) {
		background-color: #e5e4e2;
	}
	.task_note {
		background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
		border:1px solid #ccc;
		width: 95%;
	}
	.task_note:focus{
		border: 1px black solid;
	}
	img.loading{
		visibility: hidden;
	}


</style>


<div class="maincontent">

<div class="row">
	<?php $type = ($latest_job->is_unit) ? "Unit" : "Job"; ?>
	<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 job-title">
		<h3><?php echo $type; ?> #<?php echo $latest_job->job_number.": ";  echo $latest_job->development_name; ?></h3>
	</div>
	<!--<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 job-title">
		<a href="<?php /*echo base_url('job/change_job'); */?>" data-fancybox-type="iframe" class="fancybox btn new-job" style="background-color:#f9b800; color:white; width:100%">
			Change Job
		</a>
	</div>-->
</div>

<div class="row">
	<?php if($latest_job->is_unit || $latest_job->parent_unit): ?>
		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
			<ul class="nav nav-pills nav-stacked unit-nav">
				<?php if($latest_job->is_unit): ?>
					<li class="active"><a href="#"><?php echo $latest_job->development_name; ?></a></li>
					<?php
					$ci = & get_instance();
					$jobs = $ci->db->query('select id, development_name from construction_development where parent_unit = '.$latest_job->id)->result();
					foreach($jobs as $job){
						?>
						<li><a href="<?php echo base_url()."constructions/construction_overview/{$job->id}"; ?>"><?php echo $job->development_name; ?></a></li>
						<?php
					}
					?>
				<?php else: ?>
					<?php
					$ci = & get_instance();
					$unit = $ci->db->query('select id, development_name from construction_development where id = '.$latest_job->parent_unit)->row();
					?>
					<li class=""><a href="<?php echo base_url()."constructions/construction_overview/{$unit->id}"; ?>"><?php echo $unit->development_name; ?></a></li>
					<?php
					$jobs = $ci->db->query('select id, development_name from construction_development where parent_unit = '.$latest_job->parent_unit)->result();
					foreach($jobs as $job){
						?>
						<li <?php if($job->id == $latest_job->id) echo "class=' active'"; ?> ><a href="<?php echo base_url()."constructions/construction_overview/{$job->id}"; ?>"><?php echo $job->development_name; ?></a></li>
						<?php
					}
					?>
				<?php endif; ?>
			</ul>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">

			<?php echo $maincontent; ?>
		</div>
	<?php else: ?>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

			<?php echo $maincontent; ?>
		</div>
	<?php endif; ?>
</div>


</div>
<div class="clear"></div>