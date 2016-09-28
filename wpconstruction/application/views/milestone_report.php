<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-select.css">
<script type="text/javascript" src="<?php echo base_url();?>/js/bootstrap-select.js"></script>

<div class="table-responsive report">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th>Job Number</th>
				<th>Job</th>
				<?php 
				$user = $this->session->userdata('user');
				$this->db->select("construction_milestone_templates.name");
		        $this->db->join('construction_development_milestones', 'construction_development_milestones.job_id=construction_development.id');
		        $this->db->join('construction_milestone_templates', 'construction_milestone_templates.id=construction_development_milestones.milestone_template_id');
		        $this->db->where('construction_development.wp_company_id', $this->wp_company_id);
				$this->db->where('construction_milestone_templates.deleted', 0);
		        $this->db->order_by("construction_development.id", 'DESC');
		        $this->db->group_by("construction_development_milestones.milestone_template_id");
		        $dates = $this->db->get('construction_development')->result();

				foreach($dates as $date):
				?>
				<th><?php echo $date->name; ?></th>
				<?php endforeach; ?>
			</tr>
		</thead>
		<tbody>
		<?php 
		foreach($milestone as $mile): 
		?>
			<tr>
				<td><?php echo $mile->job_number; ?></td>
				<td>
					<?php echo $mile->development_name; ?>
					<!--task #4593-->
					<?php
						if($mile->parent_unit){
							$parent = $this->db->get_where('construction_development',array('id' => $mile->parent_unit),1,0)->row()->development_name;
							echo " ({$parent})";
						}
					?>
				</td>
				<?php 
				$this->db->select("construction_development_milestones.milestone_template_id");
		        $this->db->join('construction_development_milestones', 'construction_development_milestones.job_id=construction_development.id');
		        $this->db->join('construction_milestone_templates', 'construction_milestone_templates.id=construction_development_milestones.milestone_template_id');
		        $this->db->where('construction_development.wp_company_id', $this->wp_company_id);
				$this->db->where('construction_milestone_templates.deleted', 0);
		        $this->db->order_by("construction_development.id", 'DESC');
		        $this->db->group_by("construction_development_milestones.milestone_template_id");
		        $tems = $this->db->get('construction_development')->result();

				//echo $this->db->last_query();
				foreach($tems as $tem):
				?>
				<td>
					<?php 
					$this->db->select("construction_development_milestones.*");
			        $this->db->where('job_id', $mile->id);
			        $dates = $this->db->get('construction_development_milestones')->result();
					foreach($dates as $date):
					?>
						<?php 
						if($date->milestone_template_id==$tem->milestone_template_id)
						{
							if($date->date < date('Y-m-d')){
								echo '<span style="color:green;font-weight: bold;">'.date('d-m-Y',strtotime($date->date)).'</span><br>'; 
							}else{
								echo '<span style="font-weight: bold;">'.date('d-m-Y',strtotime($date->date)).'</span><br>'; 
							}
						} 
						?>
					<?php endforeach; ?>
				</td>
				<?php endforeach; ?>
			</tr>
		<?php endforeach; ?>
		</tbody>
	</table>
</div>

<div class="pull-right">
	<a id="SendEmail" href="#" class="btn btn-default send-email">Send By Email</a>
</div>

<div id="send-email-modal" title="Send By Email">
	<form action="<?php echo base_url();?>report/milestone_report_send_email" method="post">
		<div class="modal-body">
			<div class="form-group">
				<label for="exampleInputEmail1">Contact List</label>
				<select name="contact_email[]" class="multiselectbox form-control" multiple="" required="">
					<?php
					$this->db->select("contact_email,contact_first_name,contact_last_name");
					$this->db->where('wp_company_id', $this->wp_company_id);
					$results = $this->db->get('contact_contact_list')->result(); 
					$row = '<option value="">--Select Contact</option>';
					foreach($results as $result){
						$row .= '<option value="'.$result->contact_email.'">'.$result->contact_first_name.' '.$result->contact_last_name.'</option>';
					}
					echo $row;
					?>
				</select>
			</div>
		</div>
		<div class="modal-footer">
			<input id="delete-document-dev" class="btn" type="submit" value="Ok"/>
		</div>	
	</form>
</div> 

<script type="text/javascript">
    
	$(document).ready(function () {
		$('.multiselectbox').selectpicker();
		$("#send-email-modal").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 250,
            modal: true
        });

		$("#SendEmail").click(
            function () {
            	$("#send-email-modal").dialog('open');  
            }
         );
	});
</script>

<style>
	.report .table-bordered > thead > tr > th, .table-bordered > tbody > tr > th, .table-bordered > tfoot > tr > th, .table-bordered > thead > tr > td, .table-bordered > tbody > tr > td, .table-bordered > tfoot > tr > td{
		border: 1px solid #ddd;
	}
#send-email-modal .modal-footer {
    padding: 0px 20px; 
    margin-top: 15px;
    text-align: right;
    border-top: 0px solid #e5e5e5; 
}
</style>