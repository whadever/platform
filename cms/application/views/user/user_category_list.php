<script>
window.Url = "<?php print base_url(); ?>";

$(function() {
$( "#accordion" ).accordion({heightStyle: "content",collapsible: true});
});

function selectrow( e, box_id, group_id, group_permission_id,box_type)
{

	if(box_type == 1)
	{
		check_line_id = 'read_' + group_id + '_' + group_permission_id;
	}
	else
	{
		check_line_id = 'category_' + group_id + '_' + group_permission_id;
	}
	
	e.preventDefault();
	var listItem = document.getElementById( check_line_id );

    if (e.ctrlKey || e.metaKey)
    {

		if($('#' + check_line_id).hasClass("checked_perm") == true)
		{
			document.getElementById(check_line_id).className = '';
		}
		else
		{
			document.getElementById(check_line_id).className = 'checked_perm';
		}

	}
	else if(e.shiftKey)
	{

		
		 
		// Get the first possible element that is selected.
        var currentSelectedIndex = $('#' + box_id + ' div.checked_perm').eq(0).index();

		// Get the shift+click element
        var selectedElementIndex = $('#' + box_id + ' div').index(listItem);


		if (currentSelectedIndex < selectedElementIndex)
        {
            for (var indexOfRows = currentSelectedIndex; indexOfRows <= selectedElementIndex; indexOfRows++) 
            {
                 $('#' + box_id + ' div').eq(indexOfRows).addClass('checked_perm');  
            }
        }
        else
        {
            for (var indexOfRows = selectedElementIndex; indexOfRows <= currentSelectedIndex; indexOfRows++)
            {
                 $('#' + box_id + ' div').eq(indexOfRows).addClass('checked_perm');  
            }
        }   


	}
	else
	{

		$('#' + box_id + ' div').removeClass("checked_perm");
		document.getElementById(check_line_id).className = 'checked_perm';
	} 


	
 

}

function move_permission( group_id, from_permission, to_permission,permission_type,move_type)
{
	
	//permission_id = $('#' + from_permission +  '_' + group_id + ' .checked_perm').attr('id');


	$('#' + from_permission +  '_' + group_id + ' .checked_perm').each(function(i, obj) {

		permission_id = obj.id;
	
	var arr = permission_id.split("_");
	
	uid = arr[2];

	permission_name = $('#' + from_permission + '_' + group_id + ' .checked_perm').html();
	$('#' + permission_id).remove();
	if(permission_id)
	{
		if(move_type == 1)
		{
			appendhtml = '<div id="' + permission_id + '" onclick="selectrow(event,&#39;' + to_permission + '_'+ group_id + '&#39;,' + group_id + ','+ uid + ',' + permission_type + ')">'+ permission_name + '</div>';
		}
		$( '#' + to_permission + '_' + group_id ).prepend( appendhtml );
		
		$.ajax({
			url: window.Url + 'user/update_category/'+ group_id + '/' + uid + '/' + move_type,
			type: 'GET',
			success: function(data) 
			{
				
			},
			        
		  });
		
		
	}
	else
	{
		alert('Please select permission');
	}


	});

	
}

</script>
<?php
$ci =&get_instance();
$ci->load->model('user_model');
?>


<div class="cms-button-wrapper">
	
	<div class="clr">
		<div class="cms_tabs">
			<table style="width:80%; margin:0px auto; border: none;">
				<tr>
					<!---<td style="border:2px solid #004278; " align="center"><a class="cmstabs" href="<?php echo base_url();?>user/user_list">Users</a></td>--->
					<td align="center"><a class="cmstabs" href="<?php echo base_url();?>user/user_role_list">Permissions</a></td>
					<td class="active" align="center"><a class="cmstabs" href="<?php echo base_url();?>user/user_category_list">User Categories</a></td>
					<td align="center"><a class="cmstabs" href="<?php echo base_url();?>user/column_description">Description</a></td>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="clr">
		
		<div class="cms_icons" style="width:100px">
			<a class="icon_text" data-toggle="modal" href="#AddNewGroup" href="<?php echo base_url();?>user/user_add"><img src="<?php echo base_url();?>images/icon/add_user.png" height="" title="Add Group" alt="Add Group" /><br> 
			Add Category</a>
		</div>
		
	</div>
      
       
</div>

<!-- Add Category form -->
<div id="AddNewGroup" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form class="form-horizontal" action="<?php echo base_url(); ?>user/add_user_category" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h3 id="myModalLabel">Enter Category Name</h3>
				</div>
				<div class="modal-body">
					
						
					<div class="control-group">
						<div class="controls add_group_box">
							<input type="text" id="category_name" class="add_group" placeholder="" name="category_name" value="" required="" />
						</div>
					</div>
					
					<div class="control-group">
						<div class="controls">
							<div class="save">
								<input type="submit" value="Submit" name="submit" />
							</div>
						</div>
					</div>
				
				</div>

			</form>
</div>


<div id="accordion">
		<?php 
		
		$category_number = count($category_info);
		
		for($i=0; $i < $category_number; $i++)
		{ 
			
			

		?>
		
			
			<h3 style="height:40px; clear:both;">
				<div style="float:none; width:100%; text-align:center; height:40px; font-weight:bold"><?php echo $category_info[$i]->category_name; ?></div>
			</h3>

		
			<div class="group_content">
			
				<div class="left_permission_box">
					<h3 class="txtcntr">System User</h3>
					<div class="permission_box">
						<div id="system_user_list_<?php echo $category_info[$i]->id; ?>" class="read_only_list permission_box_wapper">
							<?php 
							$permission_type = 1;
							$user_list = $ci->user_model->user_list()->result();

							for($j = 0; $j < count( $user_list ); $j++)
							{	
							?>
								<div id="read_<?php echo $category_info[$i]->id.'_'.$user_list[$j]->uid; ?>" class="read_perm permission" onclick="selectrow( event, 'system_user_list_<?php echo $category_info[$i]->id; ?>',<?php echo $category_info[$i]->id; ?>, <?php echo $user_list[$j]->uid; ?>,1)"><?php echo $user_list[$j]->username; ?></div>
							<?php
							}
							?>
						</div>
					</div>
					
					
				</div>
				
				<div class="middle_arrow_box">
					<div class="read_write_arrow clr">
						<div style="height:100px; width:100%"></div>
						<div class="permission_arrow" onclick="move_permission( <?php echo $category_info[$i]->id; ?>, 'system_user_list','user_category_list',1,1)"> &#8594; </div>
						<div class="permission_arrow" onclick="move_permission( <?php echo $category_info[$i]->id; ?>, 'user_category_list','system_user_list',1,2)"> &#8592;  </div>
						<div class="delete_group_box">
								<!-- <a class="icon_text" id="delete_user" data-toggle="modal" href="#DeleteGroup_<?php echo $group_info[$i]->id;  ?>"><img src="<?php echo base_url();?>images/icon/delete_user.png" height="" title="Delete Permission Group" alt="Delete Permission Group" /><br> 
							Delete Group</a> -->
								<br> <br> <br> 
								<div id="DeleteGroup_<?php echo $category_info[$i]->id;  ?>" class="modal hide fade delete_group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<form class="form-horizontal" action="<?php echo base_url(); ?>user/delete_user_category/<?php echo $category_info[$i]->id;  ?>" method="POST">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
												</div>
												<div class="modal-body">
													
														
													<div class="control-group">
														<div class="controls" style="text-align:center">
															Are you sure you want to <br> delete this group "<?php echo $category_info[$i]->category_name; ?>"?
														</div>
													</div>
													
													<div class="control-group">
														<div class="controls">
															<div class="delete_confirm">
																<div class="delete_yes"><input type="submit" value="Yes" name="submit" /></div>
																<div class="delete_cancel"><button aria-hidden="true" data-dismiss="modal" type="button">No</button></div>
															</div>
														</div>
													</div>
												
												</div>

											</form>
								</div>
							
							
						</div>
					</div>
					
					<div class="visible_hidden_arrow clr">
						<div class="delete_group_box">
								<a class="icon_text" id="delete_user" data-toggle="modal" href="#DeleteGroup_<?php echo $category_info[$i]->id;  ?>"><img src="<?php echo base_url();?>images/icon/delete_user.png" height="" title="Delete Group" alt="Delete Group" /><br> 
							Delete Category</a>
						</div>
					</div>

				</div>
				
				
				<div class="right_permission_box">
					<h3 style="text-align:center">User List</h3>
					
					<div class="permission_box">
					
						<div id="user_category_list_<?php echo $category_info[$i]->id; ?>" class="read_write_list permission_box_wapper">
							<?php 
							$permission_type = 2;
							$category_user_list = $ci->user_model->get_category_user_list($category_info[$i]->id)->result();
							
							for($j = 0; $j < count( $category_user_list ); $j++)
							{	
								$category_user_id = $category_user_list[$j]->user_id;
								$category_user_list_new = $ci->user_model->get_category_user_list_new($category_user_id)->result();
								for($k = 0; $k < count( $category_user_list_new ); $k++)
								{
							?>
								<div id="category_<?php echo $category_info[$i]->id.'_'.$category_user_list[$j]->user_id; ?>" class="read_perm permission" onclick="selectrow(  event, 'user_category_list_<?php echo $category_info[$i]->id; ?>',<?php echo $category_info[$i]->id; ?>, <?php echo $category_user_list[$j]->user_id; ?>,2)"><?php echo $category_user_list_new[$k]->username; ?></div>
							<?php
								}
							}
							?>
						</div>
					</div>
				</div>
			</div>
	<?php } ?>
</div>