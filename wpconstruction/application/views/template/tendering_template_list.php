<script>
var base_url = "<?php echo base_url(); ?>";
jQuery(document).ready(function() {
	
	$('tr.check').click(function(e){
	     e.preventDefault();
	     $('tr').removeClass('checked'); // removes all highlights from tr's
	     $(this).addClass('checked'); // adds the highlight to this row
	 });
	
});
function setprojectid(pid)
{ 
	 document.getElementById('edit-template').href='tendering_template_basic_info/'+pid;
	 document.getElementById('delete-tem').href='tendering_template_delete/'+pid;
	 document.getElementById('clone-template').href='clone_tendering_template/'+pid;
}
</script>

<div class="development-add-home admindevelopment-list template-list" style="background: #fff;">
	<div class="development-header">
		<div class="development-title">
			<div class="all-title"><?php echo $title; ?></div>
		</div>
	</div>

	<div class="development-content">
		<div class="development-table">
			<div class="development-table-inner">
			<form action="#" method="POST" class="template-list-form">	
			<table class="table-hover">
				<thead>
					<tr>
						<th>Name</th><th>Date Created</th><th>No. Items</th><th>No. Contacts</th><th>Created By</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($templates as $template){ ?>
					<tr class="check" onclick="<?php echo 'setprojectid('. $template->id . ');'; ?>">
						
						<td><?php echo $template->name; ?></td>
						<td><?php echo $this->wbs_helper->to_report_date($template->created); ?></td>
						<td>
						<?php 
						$phase_query = $this->db->query("SELECT * FROM construction_tendering_template_items where template_id=".$template->id);
						$phase_result = $phase_query->result();
						$phase_count = count($phase_result);
						echo $phase_count; 
						?>
						</td>
						<td>
						<?php 
						$task_query = $this->db->query("SELECT count(*) cnt FROM construction_tendering_item_contacts c JOIN construction_tendering_template_items i on c.item_id = i.id where template_id=".$template->id);
						echo $task_query->row()->cnt;
						?>
						</td>
						<td><?php echo $template->username; ?></td>
								
					</tr>
					
				<?php } ?>
				</tbody>
			</table>
			</form>	
			</div>
		</div>
		<div class="development-button">
			<ul class="development-button-inner">
				<li>
                	<a href="#" title="Edit Template" id="edit-template"><img src="../images/icon/edit-button.png" alt="Edit Template" title="Edit Template" /></a>
                </li>
                <li>
                	<a href="#DeleteTem" title="Delete Template" id="delete-template" class="delete-template" role="button" data-toggle="modal"><img src="../images/icon/btn_horncastle_trash.png" alt="Delete Template" title="Delete Template" /></a>
                </li>
				<li>
                	<a href="#" title="Clone Template" id="clone-template" class="clone-template" role="button" ><img src="../images/icon/clone.png" alt="Clone Template" title="Clone Template" /></a>
                </li>
                <!-- MODAL Template Delete-->
				<div id="DeleteTem" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-body">
						<p>Are you sure want to delete this Template?</p>
					</div>
					<div class="modal-footer delete-task">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
						<a href="#" title="Delete Template" id="delete-tem">Ok</a>
					</div>
				</div>
				<!-- MODAL Template Delete-->
                                  
			</ul>
		</div>
		<div class="clear"></div>
	</div>
	
</div>

