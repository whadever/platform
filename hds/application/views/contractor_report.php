
<script>
$(function(){

    $('#select-contractor').change(function(){
        
        var uid = $('#select-contractor').val();
        
        newurl = window.Url + 'report/contractor_report/' + uid;
		window.location = newurl;   
    });

});
</script>
<?php 
$user = $this->session->userdata('user');
$wp_company_id = $user->company_id;
$ci = &get_instance();
$ci->load->model('user_model');
$user_role = $ci->user_model->user_app_role_load($user->uid);
$user_role = $user_role->application_role_id;
?>
<div class="report-color data-report contractor">

	<div class="data-report-body">

		<div class="contractor-list" style="<?php if($user_role == 3){ echo 'display:none;'; } ?>">
			<form class="form-horizontal">
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-3 control-label">Contractor List</label>
			    <div class="col-sm-5">
					 <select class="form-control" id="select-contractor">
					    <option value="">--Select Contractor--</option>
						<?php
						
						$this->db->join('users', 'users.uid = users_application.user_id', 'left');
						$this->db->where('users.company_id',$wp_company_id);
						$this->db->where('application_id', '1');
						$this->db->where('application_role_id', '3');
						$this->db->order_by('username', 'ASC');
						$results = $this->db->get('users_application')->result();
						foreach($results as $result){
						?>
					    <option value="<?php echo $result->uid; ?>" <?php if($this->uri->segment(3)==$result->uid){ echo 'selected'; } ?>><?php echo $result->username; ?></option>
						<?php
						}
						?>
					 </select>
			    </div>
			  </div>
			</form>
		</div>
		
		<?php 
			if($this->uri->segment(3)){

			$c_uid = $this->uri->segment(3);
			$this->db->where('uid', $c_uid);
			$row = $this->db->get('users')->row();
		?>

		<div class="row data-dev">

			<div class="col-xs-12">

				<h3 class="title">Contractor Report<span style="float:right;"><?php echo $row->username; ?></span></h3>
				
				<?php 

				$this->db->join('development', 'development.id = stage_task.development_id', 'left');
				$this->db->join('stage_phase', 'stage_phase.id = stage_task.phase_id', 'left');
				$this->db->where('task_person_responsible', $c_uid);
				$this->db->order_by('planned_completion_date', 'ASC');
				$results = $this->db->get('stage_task')->result();
				foreach($results as $result){

				$day_required = $result->task_length;

				if( $result->planned_completion_date > '0000-00-00')
				{
					$planned_completion_date = date('d-m-Y', strtotime($result->planned_completion_date));						
				}
				else if( $result->task_start_date == '0000-00-00')
				{
					$planned_completion_date = '00-00-0000';
				}
				else
				{
					$created_date = date_create($result->task_start_date);
					$str = '21 days';
					$pcdate = date_add($created_date, date_interval_create_from_date_string($str));
					$planned_completion_date =  date_format($pcdate, 'd-m-Y');					
				}

				?>		
				<table class="table">
					<tbody>
						
						<tr>
							<td class="first">Development</td><td class="last"><?php echo $result->development_name; ?></td>
						</tr>
						<tr>
							<td>Stage</td><td><?php echo $result->stage_no; ?></td>
						</tr>
						<tr>
							<td>Phase</td><td><?php echo $result->phase_name; ?></td>
						</tr>
						<tr>
							<td>Task</td><td><?php echo $result->task_name; ?></td>
						</tr>
						<tr>
							<td>Completion Date</td><td><?php echo $planned_completion_date; ?></td>
						</tr>
					</tbody>
				</table>
				<?php } ?>
					
			</div>
		</div>		
	
	<?php } // end if condition ?>
	</div>
	<!----<div class="data-report-footer">
		<i>We call Canterbury home</i>
		<p>38 Lowe St, Addington, PO Box 8255, Riccarton, Christchurch, Neww Zealand. Ph: (03) 348 8905 0800 NEW HOME <br>info@horncastle.co.nz www.horncastle.co.nz Proud to be Naming Partner for Horncastle Arena</p>
	</div>---->
</div>
