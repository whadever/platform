
<div class="report-color data-report contractor">

	<div class="data-report-body">
		
		<?php 
			if($this->uri->segment(3)){

			$c_uid = $this->uri->segment(3);
			$this->db->where('uid', $c_uid);
			$row = $this->db->get('users')->row();
		?>

		<div class="row data-dev">

			<div class="col-xs-12">

				<h3 class="title">Responsibility Report<span style="float:right;"><?php echo $row->username; ?></span></h3>
				
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
