
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


		<div class="row data-dev">

			<div class="col-xs-12">

				<h3 class="title"><?php echo $title; ?></h3>
				
				<?php 

				$this->db->join('development', 'development.id = stage_task.development_id', 'left');
				$this->db->join('stage_phase', 'stage_phase.id = stage_task.phase_id', 'left');
				$this->db->join('users', 'users.uid = stage_task.task_person_responsible');
				$this->db->where('development.wp_company_id', $wp_company_id);
				$this->db->where('stage_task.stage_task_status', '0');
				$this->db->where('stage_task.task_start_date >', '0000-00-00');
				$this->db->where('stage_task.planned_completion_date >', '0000-00-00');
				$this->db->order_by('stage_task.planned_completion_date', 'DESC');
				$results = $this->db->get('stage_task')->result();

				foreach($results as $result){

					$start_date = strtotime($result->task_start_date);
					$completion_date = strtotime($result->planned_completion_date);
				    $now = time();
				    $datediff = $completion_date - $now;
				    $day = floor($datediff/(60*60*24));
	
					if(20 >= $day){
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
								<td>Completion Date</td><td><?php echo $result->planned_completion_date; ?></td>
							</tr>
							<tr>
								<td>Person Responsible</td><td><?php echo $result->username; ?></td>
							</tr>
						</tbody>
					</table>
				<?php 
					} // if condition end
				} // foreach loop end

				?>
				
				<?php 

				$this->db->join('development', 'development.id = development_task.development_id', 'left');
				$this->db->join('development_phase', 'development_phase.id = development_task.phase_id', 'left');
				$this->db->join('users', 'users.uid = development_task.task_person_responsible');
				$this->db->where('development.wp_company_id', $wp_company_id);
				$this->db->where('development_task.development_task_status', '0');
				$this->db->where('development_task.task_start_date >', '0000-00-00');
				$this->db->where('development_task.actual_completion_date >', '0000-00-00');
				$this->db->order_by('development_task.actual_completion_date', 'DESC');
				$results = $this->db->get('development_task')->result();

				foreach($results as $result){

					$start_date = strtotime($result->task_start_date);
					$completion_date = strtotime($result->actual_completion_date);
				    $now = time();
				    $datediff = $completion_date - $now;
				    $day = floor($datediff/(60*60*24));
	
					if(20 >= $day){
				?>		
					<table class="table">
						<tbody>
							
							<tr>
								<td class="first">Development</td><td class="last"><?php echo $result->development_name; ?></td>
							</tr>
							<tr>
								<td>Phase</td><td><?php echo $result->phase_name; ?></td>
							</tr>
							<tr>
								<td>Task</td><td><?php echo $result->task_name; ?></td>
							</tr>
							<tr>
								<td>Completion Date</td><td><?php echo $result->actual_completion_date; ?></td>
							</tr>
							<tr>
								<td>Person Responsible</td><td><?php echo $result->username; ?></td>
							</tr>
						</tbody>
					</table>
				<?php 
					} // if condition end
				} // foreach loop end

				?>
					
			</div>
		</div>		
	
	</div>
	<!----<div class="data-report-footer">
		<i>We call Canterbury home</i>
		<p>38 Lowe St, Addington, PO Box 8255, Riccarton, Christchurch, Neww Zealand. Ph: (03) 348 8905 0800 NEW HOME <br>info@horncastle.co.nz www.horncastle.co.nz Proud to be Naming Partner for Horncastle Arena</p>
	</div>---->
</div>
