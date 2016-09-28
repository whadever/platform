
<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
			<input type="button" value="Back" class="btn btn-default add-button" onclick="history.go(-1);"/>
		</div>
	</div>
</div>

<div id="company-detail" class="content-inner">


<?php
 $user=  $this->session->userdata('user');  
 $user_role_id =$user->rid; 
            
	$this->breadcrumbs->push('Company', 'company/company_list');	 
	$this->breadcrumbs->push($company_title, 'company/company_detail/'.$company_id);
	//echo $this->breadcrumbs->show();
?>
<div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div> 
<div class="row">
	<div id="infoMessage" class="col-md-12">
	    
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
  

<div id="company_button" class="button-wrapper">
          
            <div class="row">
                <div class="col-md-3">
                    <a class="btn btn-default" href="<?php echo base_url()?>request/request_add/0/<?php echo $company_id; ?>">Add Task to This Company</a> 
                </div>
                <div class="col-md-2">
                    <a class="btn btn-default" href="<?php echo base_url()?>company_notes/index/<?php echo $company_id; ?>"> Company Notes</a> 
                </div>
                <?php  if($user_role_id!=3){ ?>
                <div class="col-md-3">
                    <a class="btn btn-default" href="<?php echo base_url()?>project/project_add/<?php echo $company_id; ?>">Add Project to This Company</a> 
                </div>
                 
                 <div class="col-md-2">
                     <a class="btn btn-default" href="<?php echo base_url()?>company/company_update/<?php echo $company_id; ?>">Modify Company</a>
                </div>   
 
                                 
                <div class="col-md-2">
                    <a class="btn btn-default" data-toggle="modal" data-target="#companyCloseModal" >Close Company</a>
                </div>
                <?php } ?>
            </div>

</div>
    <p>&nbsp;</p>   
<div class="box">    
    <div class="row">
            <div class="col-xs-12 col-sm-6 col-md-6">
                <div id="table-company"><?php if(isset($table)) { echo $table;	} ?> </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <h4 align="center">Notes</h4>
                <div id="notes-box">
                   <?php echo $prev_notes; ?>
                </div>
            </div>
    </div>
</div>  
<div id="project_box" class="box">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <h4 align="center">Open Projects</h4>
            <?php if(isset($company_open_project_table)) { echo $company_open_project_table;} ?> 
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6">
            <h4 align="center">Closed Projects</h4>
            <?php if(isset($company_close_project_table)) { echo $company_close_project_table;} ?> 
        </div>
    </div>
</div>

<div id="task_box" class="box">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <h4 align="center">Open Tasks</h4>
            <div class="scroll">
                    <?php if(isset($open_bug_table)) { echo $open_bug_table;} ?> 
            </div>
             
        </div>

       <div class="col-xs-12 col-sm-6 col-md-6">
           <h4 align="center">Close Tasks</h4>
           <div class="scroll">
               <?php if(isset($close_request_table)) { echo $close_request_table;	} ?> 
           </div>
           
       </div>              

    </div>
</div>
    
<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="text-align:center;"> 
<?php     if($user_role_id==1){ ?>
               
  
                <a class="btn btn-default" data-toggle="modal" data-target="#myModal" >Delete Company</a>
<?php } ?>
        </div>
</div>

<!-- delete modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="myModalLabel">Delete Company</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to delete this company?</p>
      </div>
      <div class="modal-footer">
          <a  href="<?php echo base_url()?>company/company_delete/<?php echo $company_id; ?>" class="btn btn-default">Yes</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        
      </div>
    </div>
  </div>
</div>

<!-- company Close modal -->
<div class="modal fade" id="companyCloseModal" tabindex="-1" role="dialog" aria-labelledby="companyCloseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="companyCloseModalLabel">Close Company</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to close this company?</p>
      </div>
      <div class="modal-footer">
          <a  href="<?php echo base_url()?>company/company_close/<?php echo $company_id; ?>" class="btn btn-default">Yes</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        
      </div>
    </div>
  </div>
</div>

    
</div>  









