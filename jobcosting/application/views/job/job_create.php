<?php
$ci = & get_instance();
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-select.css" />
<script type="text/javascript" src="<?php echo base_url();?>/js/bootstrap-select.js"></script>

<script>
	function constructionJob(){
		job_id = $("#construction_job_list").val();
		
		$.ajax({
			url: "<?php echo base_url(); ?>" + 'job/check_construction_job_id/' + job_id,
			type: 'GET',
			success: function(data) 
			{
				if(data=='1'){
					alert('Job already added.');
				}else{
					$.ajax({
						url: "<?php echo base_url(); ?>" + 'job/construction_job/' + job_id,
						type: 'GET',
						dataType: "json",
						success: function(data) 
						{
							$('#jobname').empty();
							$('#jobname').val(data.development_name);   
							$('#job_number').empty();  
							$('#job_number').val(data.job_number);     
						},
					});
					
					/*$.ajax({
						url: "<?php echo base_url(); ?>" + 'job/construction_job_client/' + job_id,
						type: 'GET',
						success: function(data) 
						{
							$('#client-list').empty();
							$('#client-list').append(data);
							$('#client-list select').selectpicker('refresh');     
						},
					});*/
				}     
			},
		});
	}
</script>

<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
		</div>
	</div>
</div>
<?php 
if($job){
	echo form_open('job/job_update/'.$job->id); 
}else{
	echo form_open('job/job_create'); 
}
?>

	<?php if($type!='actual' && $type != 'planned'): ?>
	<div class="row">
		<div class="col-xs-12 col-sm-3 col-md-3"></div>
		<div class="col-xs-12 col-sm-6 col-md-6">
		<?php 
			$jobss = $ci->job_model->get_construction_job_list();
            echo form_label('Select Job From Construction System', 'constraction_job_list'); 
        ?>       
        	<select onchange="constructionJob();" id="construction_job_list" name="construction_job_id" class="multiselectbox form-control" data-live-search="true">
        		<option value="">Select a Job</option>
			<?php 
				foreach($jobss as $jobs){ 
					$check_job = $this->db->get_where('jobcosting_jobs',array('construction_job_id'=>$jobs->id))->row();
					if(!$check_job){
					$cons_parent_dev = $this->db->get_where('construction_development',array('id'=>$jobs->parent_unit))->row()->development_name;
			?>		
					<option <?php if($jobs->id==$job->construction_job_id){ echo 'selected'; } ?> value="<?php echo $jobs->id; ?>"><?php if($cons_parent_dev){ echo $cons_parent_dev.' - '; } ?><?php echo $jobs->development_name; ?></option>	
			<?php 
					}
				} 
			?>
			</select>
        </div>
        <div class="col-xs-12 col-sm-3 col-md-3"></div>
    </div>
    <?php endif; ?>
	<div class="row">&nbsp;</div>
	
    <div class="row">
		<div class="col-xs-12 col-sm-2 col-md-2"></div>
		


		<div class="col-xs-12 col-sm-8">
			<div class="col-xs-12 col-sm-6">
				<div class="row">
				<div class="col-xs-12 col-md-12">
					<?php 
			            echo form_label('Job Name', 'jobname'); 
			            $data = array(
								'name' 	=> 'jobname',
								'id'  	=> 'jobname',
								'class'	=> 'form-control input-sm',
								'required' => '1',
								'value' => $job->jobname
						);
			            echo form_input($data);
			        ?>
			        </div>
				</div>

				<div class="row">
				<div class="col-xs-12 col-md-12">
					<?php 
			            echo form_label('Date of the Job Costing', 'job_costing_date'); 
			             $data = array(
								'name' 	=> 'job_costing_date',
								'id'  	=> 'job_costing_date',
								'class'	=> 'form-control input-sm live_datepicker',
								'required' => '1',
								'value' => $job->job_costing_date? date('d-m-Y',strtotime($job->job_costing_date)) : ''
						);
			            echo form_input($data);
			        ?>
			        </div>
				</div>
				<div class="row">
				<div class="col-xs-12 col-md-12">
					<?php 
			            echo form_label('Job Number', 'job_number'); 
			            $data = array(
								'name' 	=> 'job_number',
								'id'  	=> 'job_number',
								'class'	=> 'form-control input-sm',
								'required' => '1',
								'value' => $job->job_number
						);
			            echo form_input($data);
			        ?>
			        </div>
				</div>
			</div>

			<div class="col-xs-12 col-sm-6 ">
				<div class="row">
					<div class="col-xs-12 col-md-12">
					<?php 
			            echo form_label('Select The Template', 'job'); 
			            $options[]='--Select a Template--'; 
			            
			            $wp_company_id = $this->session->userdata('user')->company_id;
						$this->db->where('company_id', $wp_company_id);
			            $this->db->order_by('id', 'DESC');
			            $jobs1 = $this->db->get('jobcosting_templates')->result();
			            foreach($jobs1 as $job1 )
						{
							$options[$job1->id]=$job1->job_name;
						}
						$de = $job->template_id? $job->template_id : '';
			            $js = 'id="job_select" class="form-control input-sm" required="1"';
			            echo form_dropdown('template_id', $options, $de, $js);
			        ?>
			        </div>
				</div>
				<div class="row">
					<div class="col-xs-12 col-md-12">
						<?php 
				            echo form_label('Information', 'information'); 
				            $data = array(
									'name' 	=> 'information',
									'id'  	=> 'information',
									'rows'	=> '3',
									'class'	=> 'form-control input-sm',
									'required' => '1',
									'value' => $job->information
							);
				            echo form_textarea($data);
				        ?>
			        </div>
				</div>
				<div class="row">
					
				</div>
			</div>
		</div>

		
        <div class="col-xs-12 col-sm-2 col-md-2"></div>
    </div>
    <div class="row">
		<div class="col-xs-12 col-sm-2 col-md-2"></div>
		<!--
		<div class="col-xs-12 col-sm-4 col-md-4" id="client-list">
		<?php 
			$contacts = $ci->job_model->get_contact_list();
            echo form_label('Client Name', 'client_name'); 
        ?>       
        	<select id="client_name" multiple="" name="client_name" class="multiselectbox form-control" data-live-search="true">
        		<option value="">Select a Client</option>
			<?php foreach($contacts as $contact){ ?>
				<option <?php if($contact->id == $job->client_name ){ echo 'selected'; } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name.' '.$contact->contact_last_name; ?></option>
			<?php } ?>
			</select>
        </div>
        -->
        
    <div class="row">&nbsp;</div>
    <div class="row"> 
    <div class="col-xs-12 col-sm-2 col-md-2"></div>
		<div class="col-xs-12 col-sm-8 col-md-8">
		<?php 
			$attr_back = array(
              'name'	=> 'back',
			  'id'		=> 'back',
			  'value'	=> 'Back',
			  'class'	=> 'btn btn-danger pull-right"',
			  'type'	=> 'submit',
            ); 
            $attr_save = array(
              'name'	=> 'submit',
			  'id'		=> 'next',
			  'value'	=> 'Next',
			  'class'	=> 'btn btn-danger pull-right"',
			  'type'	=> 'submit',
            );
			echo form_submit($attr_save);
			if($job){
				echo '<a class="btn btn-danger pull-right" href="'.base_url().'job/job_view/'.$job->template_id.'">Back</a>';
			}else{
				echo '<a class="btn btn-danger pull-right" href="'.base_url().'job">Back</a>';
			}
			
        ?>
        <input type="hidden" name="type" value="<?php if($type==''){ echo 'planned'; }else{ echo $type; } ?>" />
        </div>
        <div class="col-xs-12 col-sm-2 col-md-2"></div>
    </div>
    <div class="row">&nbsp;</div>
<?php echo form_close(); ?>

<script type="text/javascript">
	$(document).ready(function() {
		$('.multiselectbox').selectpicker();
	});
 </script>

