<script>
	
	function Remove(id){
		
		var b = window.confirm('Are you sure, you want to Delete This ?');
		if(b==true)
		{
			$.ajax({				
				url: window.BaseUrl + 'user/user_delete/' + id,
				type: 'POST',
				success: function(html) 
				{
					//console.log(data);
					newurl = window.BaseUrl + 'user/user_list';
					window.location = newurl;
				},
			        
			});
		}
	}	
	
</script>

<div class="content">
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="content-header">
				<div class="title"><?php echo $title; ?></div>
			</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>Full Name</th>
							<th>User Name</th>
							<th>Email Address</th>
							<th class="res-hidden">Edit</th>
							<th class="res-hidden">Remove</th>
						</tr>
					</thead>
					<tbody>
					<?php
					$query = $this->db->query("SELECT * FROM users order by uid DESC");
					$rows = $query->result();
					foreach($rows as $row)
					{
					?>
						<tr>
							<td><?php echo $row->fullname; ?></td>
							<td><?php echo $row->name; ?></td>
							<td><?php echo $row->email; ?></td>
							<td class="res-hidden"><a href="<?php echo base_url(); ?>user/user_update/<?php echo $row->uid; ?>">Edit</a></td>
							<td class="res-hidden"><a onclick="Remove('<?php echo $row->uid; ?>');" href="#">Remove</a></td>
						</tr>
					<?php
					}
					?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	
</div>