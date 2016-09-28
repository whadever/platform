<script>

jQuery(document).ready(function() {
	
	$('tr.check').click(function(e){
	     e.preventDefault();
	     $('tr').removeClass('checked'); // removes all highlights from tr's
	     $(this).addClass('checked'); // adds the highlight to this row
	 });
	
});
	function setdevelopmentid(pid)
	{ 
		 //document.getElementById('edit-development').href='development_update/'+pid;
		 document.getElementById('delete-dev').href='development_delete/'+pid;
	}
</script>

<a class="" style="margin-bottom: 5px; display: block" href="<?php echo site_url('job/show_popup_menu'); ?>"><< main menu</a>
<div class="development-add-home admindevelopment-list" style="background: #fff;">
	<div class="development-header">
		<div class="popup_title">
			<h2 class="popup_title2"><?php echo $title; ?></h2>
		</div>
	</div>

	<div class="development-content">
		<div class="development-table">
			<div class="development-table-inner">
			
			<form action="#" method="POST" class="template-list-form">	
			<table class="table-hover">
				<thead>
					<tr>
						<th>Unit</th>
						<th>Job</th>
						<th>Related Unit</th>
						<th>Location</th>
						<th>Size</th>
						<!--<th>Stages</th>-->
						<th>Pre Construction Template</th>
						<th>Construction Template</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($admindevelopments as $admindevelopment){ ?>
					<tr class="check" onclick="<?php echo 'setdevelopmentid('. $admindevelopment->id . ');'; ?>">
						
						<td><?php echo ($admindevelopment->is_unit) ? $admindevelopment->development_name : ''; ?></td>
						<td><?php echo (!$admindevelopment->is_unit) ? $admindevelopment->development_name : ''; ?></td>
						<td><?php echo ($admindevelopment->parent_unit) ? $admindevelopment->parent_unit_name : ''; ?></td>
						<td><?php echo $admindevelopment->development_location; ?></td>
						<td><?php echo $admindevelopment->development_size; ?></td>
						<!--<td><?php /*echo $admindevelopment->number_of_stages; */?></td>-->
						
						<td><?php echo $admindevelopment->pre_construction_template; ?></td>
						<td><?php echo $admindevelopment->construction_template; ?></td>
					</tr>
					
				<?php } ?>
				</tbody>
			</table>
			</form>
			</div>
		</div>
		<div class="development-button">
			<div style="height:50px; width:85%"><a href="#DeleteDev" title="Delete Development" id="delete-development" role="button" data-toggle="modal"><img src="../images/icon/btn_horncastle_trash.png" alt="Delete Development" title="Delete Development" /></a></div>
			<!-- <ul class="development-button-inner">
				<li>
                	<a href="#" title="Edit Development" id="edit-development"><img src="../images/icon/edit-button.png" alt="Edit Development" title="Edit Development" /></a>
                </li> 
                <li>
                	
                </li>-->
                <!-- MODAL Development Delete-->
				<div id="DeleteDev" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-body">
						<p>Are you sure want to delete this job?</p>
					</div>
					<div class="modal-footer delete-task">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
						<a href="#" title="Delete Development" id="delete-dev">Ok</a>
					</div>
				</div>
				<!-- MODAL Development Delete-->
                                  
			<!-- </ul> -->
			<div style="height:50px; width:85%"><a href="<?php echo base_url(); ?>job/add_unit" title="Add Unit" id="addUnit" role="button" data-toggle=""><img src="../images/icon/icon_add_company.png" alt="Add Unit" title="Add Unit" /></a></div>
		</div>
		<div class="clear"></div>
	</div>
	
</div>

