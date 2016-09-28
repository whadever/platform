<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group  pull-right">
			<a href="<?php echo base_url().'admin/exam_export_to_excel'; ?>" class="btn btn-default">
			<?php echo 'EXCEL';?></a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-md-12">
    
    	<!------CONTROL TABS START------>
		<ul class="nav nav-tabs bordered">
			<li class="active">
            	<a href="#list" data-toggle="tab"><i class="entypo-menu"></i> 
					<?php echo get_phrase('exam_list');?>
                    	</a></li>
			<li>
            	<a href="#add" data-toggle="tab"><i class="entypo-plus-circled"></i>
					<?php echo get_phrase('add_exam');?>
                    	</a></li>
		</ul>
    	<!------CONTROL TABS END------>
		<div class="tab-content">
            <!----TABLE LISTING STARTS-->
            <div class="tab-pane box active" id="list">
            	<!-- <div class="row">
            		<form action="<?php echo base_url(); ?>admin/exam/search" method="post">
	            		<div class="col-md-2 col-lg-2 col-sm-2 col-md-offset-1 col-lg-offset-1">

		            		<div class="form-group">
		            			<select name="year" class="form-control">
		            				<option value="">-- Year --</option>
		            				<option <?php if($this->session->userdata('exam_year')=='2015'){ echo 'selected'; } ?> value="2015">2015</option>
		            				<option <?php if($this->session->userdata('exam_year')=='2016'){ echo 'selected'; } ?> value="2016">2016</option>
		            			</select>
		            		</div>
	            		</div>
                       
	            		<div class="col-md-2 col-lg-2 col-sm-2">
	            			<div class="form-group">
                            
		            			<input value="<?php if($this->session->userdata('exam_name_search')!=''){ echo $this->session->userdata('exam_name_search'); }?>" type="text" name="name_search" class="form-control" placeholder="Search Name.." />
                              
		            		</div>
	            		</div>
                        <div class="col-md-2 col-lg-2 col-sm-2">
                            <div class="form-group">
                            
                                <input value="<?php if($this->session->userdata('exam_comment_search')!=''){ echo $this->session->userdata('exam_comment_search'); }?>" type="text" name="comment_search" class="form-control" placeholder="Search Comment.." />
                              
                            </div>
                        </div>

                        <div class="col-md-3 col-lg-3 col-sm-3">
                            <div class="form-group">
                            
                            
                                    <select name="category_search" class="form-control">
                                        <option value="">--Category--</option>
                                        <option <?php if($this->session->userdata('exam_category_search')=='Skills Test'){ echo 'selected'; } ?> value="Skills Test">Skills Test</option>
                                        <option <?php if($this->session->userdata('exam_category_search')=='Progress Reports'){ echo 'selected'; } ?> value="Progress Reports">Progress Reports</option>
                                        <option <?php if($this->session->userdata('exam_category_search')=='Tuition'){ echo 'selected'; } ?> value="Tuition">Tuition</option>
                                    </select>
                              
                            </div>
                        </div>
                       
	            		<div class="col-md-2 col-lg-2 col-sm-2">
	            			<div class="form-group">
		            			<input type="submit" name="search" class="form-control btn btn-primary" value="Search" />
		            		</div>
	            		</div>
                        
            		</form>

            	</div> -->
                <table  class="table table-bordered datatable" id="table_exam">
                	<thead>
                		<tr>
                    		<th><div><?php echo get_phrase('exam_name');?></div></th>
                    		<th><div><?php echo get_phrase('class');?></div></th>
                    		<th><div><?php echo get_phrase('date');?></div></th>
                            <th><div><?php echo get_phrase('category');?></div></th>

                    		<th><div><?php echo get_phrase('comment');?></div></th>
                    		<th><div><?php echo get_phrase('recurring');?></div></th>
                    		<th><div><?php echo get_phrase('document');?></div></th>
                    		<th><div><?php echo get_phrase('options');?></div></th>

						</tr>
					</thead>
                    <tbody>
                    	<?php foreach($exams as $row):?>
                        <tr>
							<td><a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_exam_info/<?php echo $row['exam_id'];?>');"><?php echo $row['name'];?></a></td>
							<td><?php echo $row['class_name'];?></td>
							<td><?php echo $row['date'];?></td>
                            <td><?php echo $row['category'];?></td>
							<td><?php echo $row['comment'];?></td>
							<td><?php echo $row['recurring_yes_no'];?><?php if($row['recurring']!=''){ echo ' | '.$row['recurring']; }?></td>
							<td><a href="<?php echo base_url(); ?>uploads/exam_document/<?php echo $row['document'];?>"><?php echo $row['document'];?></a></td>
							<td>
                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_exam/<?php echo $row['exam_id'];?>');">
                                    <i class="entypo-pencil"></i>
                                        <?php //echo get_phrase('edit');?>
                                    </a>
								<!--<a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_exam/<?php echo $row['exam_id'];?>');">
                                    <i class="entypo-pencil"></i>
                                        <?php //echo get_phrase('edit');?>
                                    </a>--->
                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>admin/exam/delete/<?php echo $row['exam_id'];?>');">
                                    <i class="entypo-trash"></i>
                                        <?php //echo get_phrase('delete');?>
                                    </a>
	                            <!---<div class="btn-group">
	                                <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
	                                    Action <span class="caret"></span>
	                                </button>
	                                <ul class="dropdown-menu dropdown-default pull-right" role="menu">
	                                    
	                                    EDITING LINK
	                                    <li>
	                                        <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_edit_exam/<?php echo $row['exam_id'];?>');">
	                                            <i class="entypo-pencil"></i>
	                                                <?php echo get_phrase('edit');?>
	                                            </a>
	                                                    </li>
	                                    <li class="divider"></li>
	                                    
	                                    DELETION LINK 
	                                    <li>
	                                        <a href="#" onclick="confirm_modal('<?php echo base_url();?>admin/exam/delete/<?php echo $row['exam_id'];?>');">
	                                            <i class="entypo-trash"></i>
	                                                <?php echo get_phrase('delete');?>
	                                            </a>
	                                                    </li>
	                                </ul>
	                            </div>--->
        					</td>
                        </tr>
                        <?php endforeach;?>
                    </tbody>
                </table>
			</div>
            <!----TABLE LISTING ENDS--->
            
            
			<!----CREATION FORM STARTS---->
			<div class="tab-pane box" id="add" style="padding: 5px">
                <div class="box-content">
                	<?php echo form_open_multipart(base_url() . 'admin/exam/create' , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
                        
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="name" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('date');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="datepicker form-control" name="date"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                                <div class="col-sm-5">
                                    <select name="class_id" class="form-control" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>">
                                    	<option value="">--Select Class--</option>
                                    	<?php 
                                    	$this->db->where('company_id', $this->session->userdata('user')->company_id);
                                    	$results = $this->db->get('sms_class')->result();
                                    	foreach($results as $row){
                                    	?>
                                    	<option value="<?php echo $row->class_id; ?>"><?php echo $row->name; ?></option>
                                    	<?php } ?>
                                    </select>
                                </div>
                            </div> 
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('category');?></label>
                                <div class="col-sm-5">
                                    <select name="category" class="form-control">
                                        <option value="">--Category--</option>
                                        <option value="Skills Test">Skills Test</option>
                                        <option value="Progress Reports">Progress Reports</option>
                                        <option value="Tuition">Tuition</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('comment');?></label>
                                <div class="col-sm-5">
                                    <input type="text" class="form-control" name="comment"/>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('recurring');?></label>
                                <div class="col-sm-1">
                                    <input type="checkbox" class="form-control" name="recurring_yes_no" value="Yes"/>
                                </div>
                                <div class="col-sm-4">
                                    <select name="recurring" class="form-control">
                                    	<option value="">--Select Frequency--</option>
                                    	<option value="Weekly">Weekly</option>
                                    	<option value="Fortnightly">Fortnightly</option>
                                    	<option value="Monthly">Monthly</option>
                                    	<option value="Yearly">Yearly</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group document-filestyle">
                                <label class="col-sm-3 control-label"><?php echo get_phrase('document');?></label>
                                <div class="col-sm-5">
                                    <input type="file" class="filestyle" name="document" data-icon="false" data-buttonText="Browse"/>
                                </div>
                            </div>
                            
                            
                        	<div class="form-group">
                              	<div class="col-sm-offset-3 col-sm-5">
                                  <button type="submit" class="btn btn-info"><?php echo get_phrase('add_exam');?></button>
                              	</div>
							</div>
                    </form>                
                </div>                
			</div>
			<!----CREATION FORM ENDS-->
            
		</div>
	</div>
</div>



<!-----  DATA TABLE EXPORT CONFIGURATIONS ---->                      
<script type="text/javascript">

	jQuery(document).ready(function($)
	{
		

		var datatable = $("#table_exam").dataTable();
		
		$(".dataTables_wrapper select").select2({
			minimumResultsForSearch: -1
		});
	});
		
</script>