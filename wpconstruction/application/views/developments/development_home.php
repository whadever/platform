<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 job-title">

	</div>
	<!--<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 job-title">
		<a href="<?php /*echo base_url('job/change_job'); */?>" data-fancybox-type="iframe" class="fancybox btn new-job changejobbtn" style="margin-bottom: 12px">
			Change Job
		</a>
	</div>-->
</div>
<div class="row">
	<?php if($development_details->is_unit || $development_details->parent_unit): ?>
	<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2">
		<ul class="nav nav-pills nav-stacked unit-nav">
			<?php if($development_details->is_unit): ?>
				<li class="active"><a href="#"><?php echo $development_details->development_name; ?></a></li>
				<?php
				$ci = & get_instance();
				$jobs = $ci->db->query('select id, development_name from construction_development where parent_unit = '.$development_details->id)->result();
				foreach($jobs as $job){
					?>
					<li><a href="<?php echo base_url()."constructions/construction_overview/{$job->id}"; ?>?cp=construction"><?php echo $job->development_name; ?></a></li>
					<?php
				}
				?>
			<?php else: ?>
				<?php
				$ci = & get_instance();
				$unit = $ci->db->query('select id, development_name from construction_development where id = '.$development_details->parent_unit)->row();
				?>
				<li class=""><a href="<?php echo base_url()."constructions/construction_overview/{$unit->id}"; ?>?cp=construction"><?php echo $unit->development_name; ?></a></li>
				<?php
				$jobs = $ci->db->query('select id, development_name from construction_development where parent_unit = '.$development_details->parent_unit)->result();
				foreach($jobs as $job){
					?>
					<li <?php if($job->id == $development_details->id) echo "class=' active'"; ?> ><a href="<?php echo base_url()."constructions/construction_overview/{$job->id}"; ?>?cp=construction"><?php echo $job->development_name; ?></a></li>
					<?php
				}
				?>
			<?php endif; ?>
		</ul>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10">

		<?php echo $development_content; ?>
	</div>
	<?php else: ?>
	<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">

		<?php echo $development_content; ?>
	</div>
	<?php endif; ?>
</div>

<div class="clear"></div>