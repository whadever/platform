<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
			<input type="button" value="Back" class="btn btn-default add-button" onclick="history.go(-1);"/>
		</div>
	</div>
</div>

<?php

	$this->breadcrumbs->push('Task', 'request/request_list');	 
	$this->breadcrumbs->push($request->request_title, 'request/request_detail/'.$request->id); 

	echo $this->breadcrumbs->show();
    $request_status_val = $request->request_status;
    
	$from = isset($_GET['from'])? $_GET['from'] : 'task';  
     
?>


<div class="content-inner task-add">
    <div class="row">
    <div class="col-md-12"> 
        <div id="infoMessage">

        <?php if($this->session->flashdata('success-message')){ ?>

        <div class="alert alert-success" id="success-alert">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Success! </strong>
        <?php echo $this->session->flashdata('success-message');?>
        </div>    
        <?php } ?>

        <?php if($this->session->flashdata('warning-message')){ ?>

        <div class="alert alert-warning" id="warning-alert">
        <button type="button" class="close" data-dismiss="alert">x</button>
        <strong>Success! </strong>
        <?php echo $this->session->flashdata('warning-message');?>
        </div>    
        <?php } ?>

        </div>
    </div>
</div>

	<div class="row  button-wrapper">
	
		<div class="col-md-4">
			<p><a class="btn btn-default" href="<?php echo base_url()?>notes/index/<?php echo $request->request_no?>">Task Notes</a></p>
		</div>

		<!-- <div class="col-md-3">
			<p> <a class="btn btn-default" href="<?php echo base_url()?>request/request_hour/<?php echo $request->id?>">Task Hours</a> </p>
		</div> -->
		
		<?php 
		$user=  $this->session->userdata('user');                
		$user_role_id =$user->rid; 
		if($user_role_id!=3){ ?>
		<div class="col-md-4"><p> <a class="btn btn-default" href="<?php echo base_url()?>request/request_update/<?php echo $request->request_no?>">Modify Task</a> </p></div>
		<?php } ?>

		<div class="col-md-4">
			<p>
				<?php if($request_status_val==2){ ?>
                                        <a class="btn btn-default" data-toggle="modal" data-target="#openModal" >Open Task</a>					       
				<?php }else{ ?>
                                        <a class="btn btn-default" data-toggle="modal" data-target="#closeModal" >Close Task</a>					
				<?php } ?>
			</p> 
		</div>

    </div>


	<div class="row table-border" id="request_detail_view">
		<div class="col-md-6 no-padding first">

		<?php
		if($request->priority==1){$priority = 'High';}
		elseif ($request->priority==2) { $priority = 'Normal'; }
		elseif ($request->priority==3) { $priority = 'Low'; }
		?>
		<table class="table" cellspacing="0" cellpadding="4" border="0">
		<tbody>
		<tr>
		<td>Task Id / #</td><td><?php echo $request->request_no; ?></td>
		</tr>
		<tr>
		<td>Task Name</td><td><?php echo $request->request_title; ?></td>
		</tr>
		<tr>
		<td>Task Date</td><td><?php echo date('d/m/Y', strtotime($request->request_date)); ?></td>
		</tr>
		<!-- <tr>
		<td>Company</td><td><?php echo $request->company_name; ?></td>
		</tr> -->
		<tr>
		<td>Project</td><td><?php echo $request->project_name; ?></td>
		</tr>
		<tr>
		<td>Status</td><td><?php echo $request->request_status==2 ? 'Closed':'Open'; ?></td>
		</tr>
		<tr>
		<td>Priority</td><td><?php echo $priority; ?></td>
		</tr>
		</tbody>
		</table>

		</div>

		<div class="col-md-6 no-padding second">

		<table class="table" cellspacing="0" cellpadding="4" border="0">
		<tbody>
		<tr>
		<td>Manager</td><td><?php echo implode(", ", $assign_manager); ?></td>
		</tr>
		<tr>
		<td>Contractor</td><td><?php echo implode(", ", $assign_developer); ?></td>
		</tr>
		<tr>
		<td>Created By</td><td><?php echo $request->created_by; ?></td>
		</tr>
		<tr>
		<td>Completion Date</td><td><?php echo date('d/m/Y', strtotime($request->estimated_completion)); ?></td>
		</tr>
		<tr>
		<td>Image</td><td><?php echo $request->image==''? 'No Image':'<a class="fancybox" href="'.base_url().'uploads/request/document/'.$request->image.'"><img width="30" height="30" src="'.base_url().'uploads/request/document/'.$request->image.'" title="'.$request->image.'" alt="'.$request->image.'"/></a>'; ?></td>
		</tr>
		<tr>
		<td>Document</td><td><?php echo $request->document==''? 'No Document':'<a  href="'.base_url().'document/download_file/'.$request->document.'">'.$request->document.'</a>'; ?></td>
		</tr>
		<tr>		
		<td colspan="2">&nbsp;</td>
		</tr>
		</tbody>
		</table>

		</div>
        </div>
    <div class="row"> 
		<div class="col-md-6 no-padding third">

			<table class="table" cellspacing="0" cellpadding="4" border="0">
				<tbody>
					<tr>
						<td align="center">Description</td>
					</tr>
					<tr>
						<td style="padding: 0px" class="no-border-bottom">
                                                    <div id="note_des" class="request-description"><?php echo $request->request_description; ?></div>
                                                </td>
					</tr>
				</tbody>
			</table>

		</div>

		<div class="col-md-6 no-padding fourth">

			<table class="table" cellspacing="0" cellpadding="4" border="0">
				<tbody>
					<tr>
						<td align="center">
<a class="" href="<?php echo base_url()?>notes/index/<?php echo $request->request_no?>">Notes</a>
						</td>
					</tr>
					<tr>
						<td style="padding:0px;" class="no-border-bottom">
                                                    <div id="request-notes-box"><?php echo $prev_notes; ?></div>
                                                </td>
					</tr>
				</tbody>
			</table>

		</div>

	</div>

	
	<div class="row  button-wrapper">
		<div class="col-md-2"></div>
		<div class="col-md-2"></div>
		<div class="col-md-4">
			<?php if($user_role_id==1){ ?>
				<p><a class="btn btn-default" data-toggle="modal" data-target="#myModal" >Delete Task</a></p>
			<?php } ?>
		</div>
		<div class="col-md-2"></div>
		<div class="col-md-2"></div>
	</div>

 
<!-- request delete modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Delete Task</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to delete this Task?</p>
      </div>
      <div class="modal-footer">
          <a  href="<?php echo base_url()?>request/request_delete/<?php echo $request->id; ?>" class="btn btn-default">Yes</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        
      </div>
    </div>
  </div>
</div>

<!-- request Close modal -->
<div class="modal fade" id="closeModal" tabindex="-1" role="dialog" aria-labelledby="CloseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="closeModalLabel">Close Task</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to close this Task?</p>
      </div>
      <div class="modal-footer">
          <a  href="<?php echo base_url()?>request/request_close/<?php echo $request->id.'/'.$request->project_id.'/'.$request->company_id; ?>?from=<?php echo $from;?>" class="btn btn-default">Yes</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        
      </div>
    </div>
  </div>
</div>  

<!-- request Open modal -->
<div class="modal fade" id="openModal" tabindex="-1" role="dialog" aria-labelledby="openModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="openModalLabel">Open Task</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to open this Task?</p>
      </div>
      <div class="modal-footer">
          <a  href="<?php echo base_url()?>request/request_open/<?php echo $request->id; ?>" class="btn btn-default">Yes</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        
      </div>
    </div>
  </div>
</div> 
    


     
</div>