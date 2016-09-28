<script>
window.Url = "<?php print base_url(); ?>";

function load_column(id)
{
	$.ajax({          
		url: window.Url + 'user/column_description_id/' + id,
		type: 'GET',
		success: function(data) 
		{
			$(".read_only_list div").removeClass();
			$("#column_"+id).addClass("active");
 			$('#description_load_list').empty(); 
			$('#description_load_list').html('<textarea onkeyup="description_update();" name="description" id="description_text">'+data+'</textarea><input value="'+id+'" type="hidden" id="column_id">'); 
		},
	});	
}

function description_update()
{
	des = $('#description_text').val(); 
	id = $('#column_id').val();  
	$.ajax({          
		url: window.Url + 'user/column_description_update/' + id,
		data: { description : encodeURIComponent(des) },
		type: 'GET',
		success: function(data) 
		{
		
		},
	});
}
</script>

<style>
.column-description textarea {
    border: 1px solid #eee;
    height: 188px;
    padding: 5px;
    width: 328px;
}
.column-description .active {
    background: #eee;
}
</style>

<?php
$ci =&get_instance();
$ci->load->model('user_model');
?>

<div class="cms-button-wrapper">
	
	<div class="clr">
		<div class="cms_tabs">
			<table style="width:80%; margin:0px auto; border: none;">
				<tr>
					<td align="center"><a class="cmstabs" href="<?php echo base_url();?>user/user_role_list">Permissions</a></td>
					<td align="center"><a class="cmstabs" href="<?php echo base_url();?>user/user_category_list">User Categories</a></td>
					<td class="active" align="center"><a class="cmstabs" href="<?php echo base_url();?>user/column_description">Description</a></td>
				</tr>
			</table>
		</div>
	</div>
    <br><br><br><br>   
</div>

		
	<div class="group_content column-description">
	
		<div class="left_permission_box">
			<h3 class="txtcntr">Column</h3>
			<div class="permission_box">
				<div class="read_only_list permission_box_wapper">
					<?php 
					$all_column = $ci->user_model->get_all_column()->result();

					foreach($all_column as $column)
					{	
					?>
						<div id="column_<?php echo $column->id; ?>" class="" onclick="load_column(<?php echo $column->id; ?>)"><?php echo $column->permission_name; ?></div>
					<?php
					}
					?>
				</div>
			</div>
		</div>
		
		<div class="left_permission_box">
			<h3 style="text-align:center">Description</h3>
			<div class="permission_box">
				<div id="description_load_list" class="keyword_load_list">
					
				</div>
			</div>	
		</div>
	
	</div>

