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
		 document.getElementById('edit-development').href='development_update/'+pid;
		 document.getElementById('delete-dev').href='development_delete/'+pid;
	}
</script>


<div class="development-add-home admindevelopment-list" style="background: #fff;">
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
						<th>Name</th><th>Location</th><th>Size</th><th>Stages</th><th>Lots</th><th>Template</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($admindevelopments as $admindevelopment){ ?>
					<tr class="check" onclick="<?php echo 'setdevelopmentid('. $admindevelopment->id . ');'; ?>">
						
						<td><?php echo $admindevelopment->development_name; ?></td>
						<td><?php echo $admindevelopment->development_location; ?></td>
						<td><?php echo $admindevelopment->development_size; ?></td>
						<td><?php echo $admindevelopment->number_of_stages; ?></td>
						<td><?php echo $admindevelopment->number_of_lots; ?></td>
						<td><?php echo $admindevelopment->template_name; ?></td>		
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
                	<a href="#" title="Edit Development" id="edit-development"><img src="../images/icon/edit-button.png" alt="Edit Development" title="Edit Development" /></a>
                </li>
                <li>
                	<a href="#DeleteDev" title="Delete Development" id="delete-development" role="button" data-toggle="modal"><img src="../images/icon/btn_horncastle_trash.png" alt="Delete Development" title="Delete Development" /></a>
                </li>
                <!-- MODAL Development Delete-->
				<div id="DeleteDev" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<div class="modal-body">
						<p>Are you sure want to delete this Development?</p>
					</div>
					<div class="modal-footer delete-task">
						<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
						<a href="#" title="Delete Development" id="delete-dev">Ok</a>
					</div>
				</div>
				<!-- MODAL Development Delete-->
                                  
			</ul>
		</div>
		<div class="clear"></div>
	</div>
	
</div>

