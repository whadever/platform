<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
		</div>
	</div>
</div>
<?php echo form_open('job/job_select'); ?>
    <div class="row">
		<div class="col-xs-12 col-sm-4 col-md-4"></div>
		<div class="col-xs-12 col-sm-4 col-md-4">
		<?php
			echo form_label('Select The Template', 'template_id');
			echo '<select name="template_id" class="form-control input-sm" required="1">';
			echo '<option value="">--Select a Template--</option>'; 
			
			$wp_company_id = $this->session->userdata('user')->company_id;
			$this->db->where('company_id', $wp_company_id);
			$this->db->order_by('id', 'DESC');
            $jobs = $this->db->get('jobcosting_templates')->result();
            foreach($jobs as $job )
			{
				echo '<option value="'.$job->id.'">'.$job->job_name.'</option>';
			}
            echo '</select>';
        ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4"></div>
    </div>
    <div class="row">&nbsp;</div>
    <div class="row"> 
    	<div class="col-xs-12 col-sm-4 col-md-4"></div>
		<div class="col-xs-12 col-sm-4 col-md-4">
		<?php 
           $attr_back = array(
              'name'	=> 'back',
			  'id'		=> 'back',
			  'value'	=> 'Back',
			  'class'	=> 'btn btn-danger pull-right',
			  'type'	=> 'submit',
            );  
			
			$attr_next = array(
              'name'        => 'next',
			  'id'          => 'next',
			  'value'       => 'Next',
			  'class'       => 'btn btn-danger pull-right',
			  'type'        => 'submit',
            );
            echo form_submit($attr_next);
			echo '<a class="btn btn-danger pull-right" href="'.base_url().'job">Back</a>';
        ?>
        </div>
        <div class="col-xs-12 col-sm-4 col-md-4"></div>
    </div>
    <div class="row">&nbsp;</div>
<?php echo form_close(); ?>
