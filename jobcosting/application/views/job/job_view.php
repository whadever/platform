<style>
.job-view table {
    font-weight: bold;
    text-align: center;
}
</style>

<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-4 col-md-4"></div>
	<div class="col-xs-12 col-sm-4 col-md-4">
	<?php
		echo form_label('Select The Template', 'template_id');
		echo '<select id="template_id" name="template_id" class="form-control input-sm">';
		echo '<option value="">--Select a Template--</option>'; 
		
		$wp_company_id = $this->session->userdata('user')->company_id;
		$this->db->where('company_id', $wp_company_id);
		$this->db->order_by('id', 'DESC');
        $jobs1 = $this->db->get('jobcosting_templates')->result();
        foreach($jobs1 as $job1)
		{
			$selected = '';
			if($job1->id==$tem_id){
				$selected = 'selected=""';
			}
			echo '<option '.$selected.' value="'.$job1->id.'">'.$job1->job_name.'</option>';
		}
        echo '</select>';
    ?>
    </div>
    <div class="col-xs-12 col-sm-4 col-md-4"></div>
</div>
<div class="row">&nbsp;</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-8 col-md-offset-2 text-right" style="padding-bottom: 5px;">
		<span class="text-right"><img height="16" src="<?php echo base_url(); ?>images/job_view.png" /> View</span>
		<span class="text-right"><img height="16" src="<?php echo base_url(); ?>images/job_edit.png" /> Edit</span>
	</div>
</div>
    
<div class="row job-view">
    <div class="col-xs-12 col-sm-12 col-md-2"></div>
    <div class="col-xs-12 col-sm-12 col-md-8">
		<table class="table table-striped table-bordered" style="text-align: center;">
		    <tbody>
		    <tr>
		        <td>Date</td>
		        <td>Name</td>
		        <td style="border-right: 0 solid #fff;">Planned</td>
		        <td style="border-right: 0 solid #fff;border-left: 0 solid #fff;">VS</td>
		        <td style="border-left: 0 solid #fff;">Actual</td>
		        <td>Delete</td>
		      </tr>
		    <?php foreach($jobs as $job){ ?>
		    	<tr>
		        	<td><?php echo date('d/m/Y', strtotime($job->job_costing_date)); ?></td>
		            <td><?php echo $job->job_number.'-'.$job->jobname.'-'.date('d-M-Y', strtotime($job->job_costing_date)); ?></td>
		            <td style="border-right: 0 solid #fff;">
		            <a href="<?php echo base_url(); ?>job/job_pdf/<?php echo $job->id; ?>/planned"><img height="22" src="<?php echo base_url(); ?>images/job_view.png" /></a>
		            <a href="<?php echo base_url(); ?>job/job_update/<?php echo $job->id; ?>/planned"><img height="22" src="<?php echo base_url(); ?>images/job_edit.png" /></a>
		            </td>
		            <td style="border-right: 0 solid #fff;border-left: 0 solid #fff;">&nbsp;</td>
		            <td style="border-left: 0 solid #fff;">
		            <a href="<?php echo base_url(); ?>job/job_pdf/<?php echo $job->id; ?>/actual"><img height="22" src="<?php echo base_url(); ?>images/job_view.png" /></a>
		            <a href="<?php echo base_url(); ?>job/job_costing_create/<?php echo $job->id; ?>/actual"><img height="22" src="<?php echo base_url(); ?>images/job_edit.png" /></a>
		            </td>
		            <td>
		            	<a onclick="return confirm('Are you sure delete this job?')" href="<?php echo base_url(); ?>job/job_delete/<?php echo $tem_id; ?>/<?php echo $job->id; ?>"><img height="22" src="<?php echo base_url(); ?>images/delete-icon.png" /></a>
		            </td>
		        </tr>
		    <?php } ?>
		    </tbody>
		  </table>
	</div>
	<div class="col-xs-12 col-sm-12 col-md-2"></div>
</div>

<div class="row"> 
	<div class="col-xs-12 col-sm-12 col-md-4"></div>
	<div class="col-xs-12 col-sm-12 col-md-6">
	<?php 
		echo '<a class="btn btn-danger pull-right" href="'.base_url().'job">Back</a>';
    ?>
    </div>
    <div class="col-xs-12 col-sm-12 col-md-2"></div>
</div>
    
<script>
	window.Url = "<?php print base_url(); ?>";
	$(document).ready(function () {
        $('#template_id').change(function () {
            template_id = $(this).val();
            if(template_id==''){
				document.location.href = window.Url + "job/job_view";
			}else{
				document.location.href = window.Url + "job/job_view/"+template_id;
			}
        });
    });
</script>
    
