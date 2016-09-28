<script>
$(function(){

    $('#dev-id').change(function(){
        
        var id = $('#dev-id').val();
        
        newurl = window.Url + 'report/data_report?id=' + id;
		window.location = newurl;   
    });

});
</script>

<?php
$ci = &get_instance();
$ci->load->model('report_model');
?>

<div class="report-color data-report">

	<div class="data-report-body">

		<div class="contractor-list">
			<form class="form-horizontal">
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-3 control-label">Development List</label>
			    <div class="col-sm-5">
					 <select class="form-control" id="dev-id">
					    <option value="">--All Selected--</option>
						<?php
						$results = $ci->report_model->get_devlopments();
						foreach($results as $result){
						?>
					    <option value="<?php echo $result->id; ?>" <?php if($_GET['id']==$result->id){ echo 'selected'; } ?>><?php echo $result->development_name; ?></option>
						<?php
						}
						?>
					 </select>
			    </div>
			  </div>
			</form>
		</div>
		
		<div class="row data-dev">
			<div class="col-xs-12">
				
				<?php 
				foreach($developments as $development){ 
		
					$dev_id = $development->id;
			
					$ci = &get_instance();
					$ci->load->model('report_model');
					$dev_photo = $ci->report_model->get_development_photo_status($dev_id)->row();
					$stage_photo = $ci->report_model->get_stage_photo_status($dev_id)->row();
					if($dev_photo or $stage_photo){ $photo_status = 'Yes'; }else{ $photo_status = 'No'; }
			
					$dev_doc = $ci->report_model->get_development_doc_status($dev_id)->row();
					$stage_doc = $ci->report_model->get_stage_doc_status($dev_id)->row();
					if($dev_doc or $stage_doc){ $doc_status = 'Yes'; }else{ $doc_status = 'No'; }

					$dev_mile = $ci->report_model->get_development_milestone_status($dev_id)->row();
					if($dev_mile){ $milestone_status = 'Yes'; }else{ $milestone_status = 'No'; }
					
			
					$ci1 = &get_instance();
					$ci1->load->model('developments_model');
			        $development_overview_info = $ci1->developments_model->get_development_phase_info($dev_id)->result(); 
			        $stage_overview_info = $ci1->developments_model->get_development_stage_info($dev_id)->result();
			
					foreach($development_overview_info as $phase_info)
					{
						$graph_start_date[] = $phase_info->start_date;
						$graph_end_date[] = $phase_info->end_date;
					}
					foreach($stage_overview_info as $stage_info)
					{ 
						$graph_start_date[] = $stage_info->start_date;
						$graph_end_date[] = $stage_info->end_date;
					}
					
					$max_start_date = max($graph_start_date);
					$min_start_date = max($graph_end_date);
					
					if($max_start_date > '0000-00-00'){ $sdate = 1; }else{ $sdate = ''; }
					if($min_start_date > '0000-00-00'){ $edate = 1; }else{ $edate = ''; }
					
					if($sdate == 1 or $edate == 1){ $date = 'Yes'; }else{ $date = 'No'; }
			
					?>		
					<table class="table">
						<tbody>
							<tr>
								<td class="first">Development</td><td class="last"><?php echo $development->development_name; ?></td>
							</tr>
							<tr>
								<td>Does the Development have the dates required to make the graph?</td><td><?php echo $date; ?></td>
							</tr>
							<tr>
								<td>Does the Development have any photographs?</td><td><?php echo $photo_status; ?></td>
							</tr>
							<tr>
								<td>Does the Development have any documents?</td><td><?php echo $doc_status; ?></td>
							</tr>
							<tr>
								<td>Does the development have any Milestones?</td><td><?php echo $milestone_status; ?></td>
							</tr>
						</tbody>
					</table>
				<?php } ?>
					
			</div>
		</div>
	</div>
	<!----<div class="data-report-footer">
		<i>We call Canterbury home</i>
		<p>38 Lowe St, Addington, PO Box 8255, Riccarton, Christchurch, Neww Zealand. Ph: (03) 348 8905 0800 NEW HOME <br>info@horncastle.co.nz www.horncastle.co.nz Proud to be Naming Partner for Horncastle Arena</p>
	</div>---->
</div>
