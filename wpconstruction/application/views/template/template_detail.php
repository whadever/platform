
<div class="clear"></div>

<div class="template-review template-detail" style="background: #fff;">
	<div class="template-header">
			<div class="template-title">
				<div class="all-title"><?php echo $title; ?></div>
			</div>
			<?php
			//$template_id = $this->uri->segment(3);
			$template_row_query = $this->db->query("SELECT * FROM construction_template where id=$template_id");
			$template_row = $template_row_query->row();
			?>	
			<div class="start-page"><p><?php echo $template_row->template_name; ?></p></div>

			<div class="clear"></div>		
	</div>
	
<div class="clear"></div>

	<div class="template-body">
		<div class="review-body">
			<div class="reviews">
			<?php 
				
				$query = $this->db->query("SELECT * FROM construction_template_phase where template_id=$template_id ORDER BY ordering ASC");
				foreach ($query->result() as $phase)
				{
			?>
				<div class="review">
					<div class="review-header">
						<h6><?php echo $phase->phase_name ?></h6>
					</div>
					
					<div class="review-content">
						<?php 
							$phase_id = $phase->id;
							$query1 = $this->db->query("SELECT * FROM construction_template_task where template_id=$template_id and phase_id=$phase_id ORDER BY ordering ASC");
							foreach ($query1->result() as $task)
							{
						?>
							<h6><?php echo $task->task_name ?></h6>
						<?php
							}
						?>
					</div>
				</div>
			<?php 
				}
			?>	
			</div>
		</div>
	<div class="clear"></div>	
	</div>
	
<div class="clear"></div>

	<div class="template-footer">
			<a class="back" onclick="window.history.go(-1)">Back</a>
			
		<div class="clear"></div>
	</div>
<div class="clear"></div>
</div>
