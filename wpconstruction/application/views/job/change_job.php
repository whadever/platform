<!--Search Job-->
<style>
	#job_table_filter, #job_table_info{
		display: none;
	}
	#job_table tr td {
		border: 1px solid #eeeeee;
		/*font-size: 14px;
		font-weight: bold;*/
		cursor: pointer;
                padding-left: 8px;
	}
        #job_table tbody tr:hover {
		color: gold;
		
	}
        #job_table tr th {
		border: 1px solid #eeeeee;
                padding-left: 8px;
		
	}
	#job_table{
		border: none;
                margin-top: 10px;
	}
	.highlight { background-color: yellow }
        table thead {
            background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
        }
        .dataTables_empty {
            padding-top: 8px;
        }
        .dataTables_empty:hover {
            color: #666;
        }
</style>
<?php

	$user = $this->session->userdata('user');
    $user_id = $user->uid;

	$this->db->select('id');
	$this->db->where('system_user_id',$user_id);
	$contact_user_id = $this->db->get('contact_contact_list')->row()->id;


	$this->db->select('application_role_id');
	$this->db->where('user_id',$user_id);
	$this->db->where('application_id',5);
	$app_role_id = $this->db->get('users_application')->row()->application_role_id;

?>
<div class="popup-body">
	<div class="control-group">
		<div class="col-xs-9 col-sm-9 col-md-9 col-lg-9">
			<div id="search_box_name">
				<input type="text" name="search_job" id="search_name" class="form-control" placeholder="Search Job"  >
				<!--<h2>Name of the Job</h2>-->
			</div>
		</div>
		<!-- <div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
			<div id="search_box_city">
				<input type="text" name="search_city" id="search_city" class="form-control" >
			</div>
		</div> -->
		<div class="col-xs-3 col-sm-3 col-md-3 col-lg-3">
			<div id="status">
				<select name="job_status" class="form-control" id="search_status">
					<option value="">All</option>
					<option value="1" selected>Open</option>
					<option value="0">Close</option>
				</select>
				<!--<h2>Location</h2>-->
			</div>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<table id="job_table" style="width:100%" border="1">
                            <thead>
                                <tr>
                                    <th>Job No.</th>
                                    <th>Job Name</th>
                                    <th>Location</th>
                                    <th>City</th>
                                    <th style="display:none">Status</th>
                                    <th style="display:none"></th>
									<th>Finish Date</th>
                                </tr>
                            </thead>
                            <tbody>
				<?php foreach($jobs as $job): 
					
					if($job['job_color'] == 'Red'){
						$job_color = "color:red";
					}elseif($job['job_color'] == 'Green'){
						$job_color = "color:green";
					}else{
						$job_color = "color:black";
					}
				
				?>
         		<?php if(is_null($job['parent_unit'])): ?>
				<?php if($app_role_id != 3 && $app_role_id != 4 && $app_role_id != 5 or 
						($app_role_id == 5 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['investor']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['purchaser']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['engineer']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['draughtsman']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['drain_layer']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['concrete_placer']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['roofing_contractor']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['carpet_layer']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['bricklayer']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['plumber']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['electrician']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['gibstopper']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['tiler']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['painters']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['foundation_placement']))) or 
						($app_role_id == 3 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['exterior_cladding']))) or 
						($app_role_id == 4 && $contact_user_id !='' && in_array($contact_user_id, explode(",",$job['builder'])))) { ?>

				<tr  data-id="<?php echo "{$job['id']}"; ?>" style="<?php echo $job_color; ?>">
                                    <td><?php echo $job['job_number']; ?></td>
                                    <?php $type = ($job['is_unit']) ? "Unit" : "Job"; ?>
                                    <td class="job_name"><?php echo "{$type}: {$job['job_name']}"; ?></td>
                                    <td><?php echo "{$job['location']}"; ?></td>
                                    <td><?php echo "{$job['city']}"; ?></td>
                                    <td style="display:none;"><?php echo "{$job['status']}"; ?></td>
                                    <td style="display:none;">
                                        <?php echo $job['job_number']." ".
                                                   "{$type}: {$job['job_name']}"." ".
                                                   "{$job['location']}"." ".
                                                   "{$job['city']}"; ?>
                                    </td>
									<td><?php echo ($job['finish_date'] && $job['finish_date'] != '0000-00-00') ? date('d-m-Y', strtotime($job['finish_date'])) : ''; ?></td>
				</tr>
				<?php } ?>
                                <?php endif; ?>
				<?php endforeach; ?>
                            </tbody>
			</table>
		</div>
	</div>

</div>
<div style="clear:both;"></div>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.highlight.js"></script>
<script>
	var base_url = "<?php echo base_url(); ?>";
	$(document).ready(function(){
		var table = $('#job_table').DataTable( {
			"paging": false,
			"autoWidth": false,
			"bSort": false,
			"columnDefs": [
				/*{
					"targets": [ 0, 1, 2, 3 ],
					className: "search"
				}
				{
					"targets": [ 2 ],
					"visible": false
				}*/
			]
		} );
               
		/*display only the status 1 on load*/
		table
			.columns( 4 )
			.search( 1 )
			.draw();

		table.on( 'draw', function () {
			var body = $( "#job_table tr td" );
			body.unhighlight();
			body.highlight( $('#search_name').val() );
		} );

		$('#search_name').on( 'keyup', function () {

			table
				.columns(5)
				.search( this.value )
				.draw();

		} );


		$('#search_status').on( 'change', function () {
			table
				.columns( 4 )
				.search( this.value )
				.draw();
		} );

		$( "#job_table" ).delegate( "td", "click", function() {
			var id = $(this).parent("tr").attr('data-id');
			parent.location = base_url+'constructions/construction_overview/'+id+'/construction?cp=construction';
			parent.$.fancybox.close();
		});
	});
</script>

