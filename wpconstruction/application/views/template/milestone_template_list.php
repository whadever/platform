<script>
var base_url = "<?php echo base_url(); ?>";
jQuery(document).ready(function() {
	
	$('tr.check').click(function(e){
	     e.preventDefault();
	     $('tr').removeClass('checked'); // removes all highlights from tr's
	     $(this).addClass('checked'); // adds the highlight to this row
	 });

	$("#edit-template").click(function(event){
		event.preventDefault();
		if($("#editModal").find("input[name='name']").val()){

			$("#editModal").dialog({
				dialog: true
			})
		}
	});

	$('#delete-template').click(function(event){
		event.preventDefault();
		if($("#delete-template").data('id') == undefined){
			return;
		}
		var url = "<?php echo site_url('template/milestone_template/delete'); ?>";
		$("<div title='Delete Template'>Are you sure you want to delete this template?</div>").dialog({
			resizable: false,
			modal: true,
			height: 200,
			buttons: {
				"YES": function () {
					$.ajax(url,{
						data:{id: $("#delete-template").data('id'), submit: 1},
						type: 'post',
						success: function () {
							location.reload();
						}
					})
				},
				CANCEL: function () {
					$(this).dialog("close");
				}
			},
			dialogClass: "dialog-phase-edit",
		});

	})
	
});
function setprojectid(pid)
{
	$("#editModal").find("input[name='name']").val($("#template_"+pid).text());
	$("#editModal").find("input[name='id']").val(pid);
	$('#delete-template').data('id',pid);
}
</script>

<div class="development-add-home admindevelopment-list template-list" style="background: #fff;">
	<div class="development-header">
		<div class="development-title">
			<div class="all-title"><?php echo $title; ?></div>
		</div>
	</div>

	<div class="development-content">
		<form action="<?php echo site_url('template/milestone_template/add'); ?>" class="form-inline" method="post" style="float: right; margin: 0px 79px 6px 0px;">
			<input type="text" name="name" class="form-control" placeholder="Add Milestone">
			<input type="submit" name="submit" value="add" class="btn btn-default">
		</form>
		<div class="development-table">
			<div class="development-table-inner">
			<form action="#" method="POST" class="template-list-form">	
			<table class="table-hover">
				<thead>
					<tr>
						<th>Name</th><th>Date Created</th><th>Created By</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach ($templates as $template){ ?>
					<tr class="check" onclick="<?php echo 'setprojectid('. $template->id . ');'; ?>">
						
						<td id="template_<?php echo $template->id; ?>"><?php echo $template->name; ?></td>
						<td><?php echo $this->wbs_helper->to_report_date($template->created); ?></td>
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
                	<a href="#" title="Edit Template" id="edit-template" role="button" data-toggle="modal"><img src="../images/icon/edit-button.png" alt="Edit Template" title="Edit Template" /></a>
                </li>
                <li>
                	<a href="#DeleteTem" title="Delete Template" id="delete-template" class="delete-template" role="button" data-toggle="modal"><img src="../images/icon/btn_horncastle_trash.png" alt="Delete Template" title="Delete Template" /></a>
                </li>

			</ul>
		</div>
		<div class="clear"></div>
	</div>
	
</div>
<!--template update modal-->
<div id="editModal" title="Edit Template" style="display: none">
		<div class="modal-body" style="text-align: center">
			<form action="<?php echo site_url('template/milestone_template/edit'); ?>" class="" method="post">
				<input type="text" name="name" class="form-control" placeholder="Add Milestone">
				<input type="hidden" name="id"><br>
				<input type="submit" name="submit" value="update" class="btn btn-default">
			</form>

		</div>
</div>

