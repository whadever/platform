<script>
window.Url = "<?php print base_url(); ?>";

$(function() {
$( "#accordion" ).accordion({heightStyle: "content",collapsible: true});
});

function selectrow( e, box_id, group_id, group_permission_id,permission_type)
{

	if(permission_type == 1)
	{
		check_line_id = 'read_' + group_id + '_' + group_permission_id;
	}
	else
	{
		check_line_id = 'display_' + group_id + '_' + group_permission_id;
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

function move_permission( group_id, from_permission, to_permission,permission_type,permission_value)
{
	
	//permission_id = $('#' + from_permission +  '_' + group_id + ' .checked_perm').attr('id');

	$('#' + from_permission +  '_' + group_id + ' .checked_perm').each(function(i, obj) {

		permission_id = obj.id;

    
	
	
	var arr = permission_id.split("_");
	
	pid = arr[2];
	
	permission_name = $('#' + from_permission + '_' + group_id + ' .checked_perm').html();
	$('#' + permission_id).remove();
	if(permission_id)
	{
		appendhtml = '<div id="' + permission_id + '" onclick="selectrow(event,&#39;' + to_permission + '_'+ group_id + '&#39;,' + group_id + ','+ pid + ',' + permission_type + ')">'+ permission_name + '</div>';
		$( '#' + to_permission + '_' + group_id ).prepend( appendhtml );
		
		$.ajax({
			url: window.Url + 'user/update_permission/'+ pid + '/' + permission_type + '/' + permission_value,
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

<div class="cms-button-wrapper">
	
	<div class="clr">
		<div class="cms_tabs">
			<table style="width:400px; margin:0px auto">
				<tr>
					<td style="border:2px solid #004278; "><a class="cmstabs" href="<?php echo base_url();?>user/user_list">Users</a></td>
					<td style="border:2px solid #004278;background-color:#ebebeb"><a class="cmstabs" href="<?php echo base_url();?>user/user_role_list">Permissions</a></td>
				</tr>
			</table>
		</div>
	</div>
	
	<div class="clr">
		
		<div class="cms_icons">
			<a class="icon_text" data-toggle="modal" href="#AddNewGroup" href="<?php echo base_url();?>user/user_add"><img src="<?php echo base_url();?>images/icon/add_user.png" height="" title="Add Group" alt="Add Group" /><br> 
			Add Group</a>
		</div>
		
	</div>
      
       
</div>

<!-- Add Group form -->
<div id="AddNewGroup" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<form class="form-horizontal" action="<?php echo base_url(); ?>user/add_permission_group" method="POST">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
					<h3 id="myModalLabel">Enter Group Name</h3>
				</div>
				<div class="modal-body">
					
						
					<div class="control-group">
						<div class="controls add_group_box">
							<input type="text" id="group_name" class="add_group" placeholder="" name="group_name" value="" required="" />
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
		
		$group_number = count($group_info);
		
		for($i=0; $i < $group_number; $i++)
		{ 
			
			$ci =&get_instance();
			$ci->load->model('user_model');

		?>
		
			
			<h3 style="height:40px; clear:both;">
				<div style="float:none; width:100%; text-align:center; height:40px; font-weight:bold"><?php echo $group_info[$i]->group_name; ?></div>
			</h3>

		
			<div class="group_content">
			
				<div class="left_permission_box">
					<h3 class="txtcntr">Read Only</h3>
					<div class="permission_box">
						<div id="read_only_list_<?php echo $group_info[$i]->id; ?>" class="read_only_list permission_box_wapper">
							<?php 
							$permission_type = 1;
							$read_only_group_permissions = $ci->user_model->get_group_read_permissions($group_info[$i]->id, $permission_type)->result();

							for($j = 0; $j < count( $read_only_group_permissions ); $j++)
							{	
							?>
								<div id="read_<?php echo $group_info[$i]->id.'_'.$read_only_group_permissions[$j]->id; ?>" class="read_perm permission" onclick="selectrow( event, 'read_only_list_<?php echo $group_info[$i]->id; ?>',<?php echo $group_info[$i]->id; ?>, <?php echo $read_only_group_permissions[$j]->id; ?>,1)"><?php $p = $ci->user_model->get_permission_name( $read_only_group_permissions[$j]->permission_id ); echo $p->permission_name; ?></div>
							<?php
							}
							?>
						</div>
					</div>
					
					<h3 class="txtcntr">Visible</h3>
					<div class="permission_box">
						<div id="visible_list_<?php echo $group_info[$i]->id; ?>" class="visible_list permission_box_wapper">
							<?php 
							$permission_type = 1;
							$read_only_group_permissions = $ci->user_model->get_group_display_permissions($group_info[$i]->id, $permission_type)->result();
							
							for($j = 0; $j < count( $read_only_group_permissions ); $j++)
							{	
							?>
								<div id="display_<?php echo $group_info[$i]->id.'_'.$read_only_group_permissions[$j]->id; ?>" class="read_perm permission" onclick="selectrow( event, 'visible_list_<?php echo $group_info[$i]->id; ?>',<?php echo $group_info[$i]->id; ?>,<?php echo $read_only_group_permissions[$j]->id; ?>,2)"><?php $p = $ci->user_model->get_permission_name( $read_only_group_permissions[$j]->permission_id ); echo $p->permission_name; ?></div>
							<?php
							}
							?>
						</div>
					</div>
				</div>
				
				<div class="middle_arrow_box">
					<div class="read_write_arrow clr">
						<div style="height:100px; width:100%"></div>
						<div class="permission_arrow" onclick="move_permission( <?php echo $group_info[$i]->id; ?>, 'read_only_list','read_write_list',1,2)"> &#8594; </div>
						<div class="permission_arrow" onclick="move_permission( <?php echo $group_info[$i]->id; ?>, 'read_write_list','read_only_list',1,1)"> &#8592;  </div>
						<div class="delete_group_box">
								<!-- <a class="icon_text" id="delete_user" data-toggle="modal" href="#DeleteGroup_<?php echo $group_info[$i]->id;  ?>"><img src="<?php echo base_url();?>images/icon/delete_user.png" height="" title="Delete Permission Group" alt="Delete Permission Group" /><br> 
							Delete Group</a> -->
								<br> <br> <br> 
								<div id="DeleteGroup_<?php echo $group_info[$i]->id;  ?>" class="modal hide fade delete_group" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
										<form class="form-horizontal" action="<?php echo base_url(); ?>user/delete_permission_group/<?php echo $group_info[$i]->id;  ?>" method="POST">
												<div class="modal-header">
													<button type="button" class="close" data-dismiss="modal" aria-hidden="true">X</button>
												</div>
												<div class="modal-body">
													
														
													<div class="control-group">
														<div class="controls" style="text-align:center">
															Are you sure you want to <br> delete this group "<?php echo $group_info[$i]->group_name; ?>"?
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
						<div class="permission_arrow" onclick="move_permission( <?php echo $group_info[$i]->id; ?>, 'visible_list','hidden_list',2,2)"> &#8594; </div>
						<div class="permission_arrow" onclick="move_permission( <?php echo $group_info[$i]->id; ?>, 'hidden_list','visible_list',2,1)"> &#8592;  </div>
						<div class="delete_group_box">
								<a class="icon_text" id="delete_user" data-toggle="modal" href="#DeleteGroup_<?php echo $group_info[$i]->id;  ?>"><img src="<?php echo base_url();?>images/icon/delete_user.png" height="" title="Delete Group" alt="Delete Group" /><br> 
							Delete Group</a>
						</div>
					</div>

				</div>
				
				
				<div class="right_permission_box">
					<h3 style="text-align:center">Read/Write</h3>
					
					<div class="permission_box">
					
						<div id="read_write_list_<?php echo $group_info[$i]->id; ?>" class="read_write_list permission_box_wapper">
							<?php 
							$permission_type = 2;
							$read_only_group_permissions = $ci->user_model->get_group_read_permissions($group_info[$i]->id, $permission_type)->result();
							
							for($j = 0; $j < count( $read_only_group_permissions ); $j++)
							{	
							?>
								<div id="read_<?php echo $group_info[$i]->id.'_'.$read_only_group_permissions[$j]->id; ?>" class="read_perm permission" onclick="selectrow(  event, 'read_write_list_<?php echo $group_info[$i]->id; ?>',<?php echo $group_info[$i]->id; ?>, <?php echo $read_only_group_permissions[$j]->id; ?>,1)"><?php $p = $ci->user_model->get_permission_name( $read_only_group_permissions[$j]->permission_id ); echo $p->permission_name; ?></div>
							<?php
							}
							?>
						</div>
					
					</div>
					
					<h3 style="text-align:center">Hidden</h3>
					<div class="permission_box">
					
						<div id="hidden_list_<?php echo $group_info[$i]->id; ?>" class="hidden_list permission_box_wapper">
							<?php 
							$permission_type = 2;
							$read_only_group_permissions = $ci->user_model->get_group_display_permissions($group_info[$i]->id, $permission_type)->result();
							
							for($j = 0; $j < count( $read_only_group_permissions ); $j++)
							{	
							?>
								<div id="display_<?php echo $group_info[$i]->id.'_'.$read_only_group_permissions[$j]->id; ?>" class="read_perm permission" onclick="selectrow(  event, 'hidden_list_<?php echo $group_info[$i]->id; ?>',<?php echo $group_info[$i]->id; ?>,<?php echo $read_only_group_permissions[$j]->id; ?>,2)"><?php $p = $ci->user_model->get_permission_name( $read_only_group_permissions[$j]->permission_id ); echo $p->permission_name; ?></div>
							<?php
							}
							?>
						</div>
					
					</div>
					
				</div>

				


			
			</div>
					

	<?php } ?>
			
		
</div>