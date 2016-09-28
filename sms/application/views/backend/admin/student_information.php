<hr />

<div class="row">
	<div class="col-sm-12 col-md-12 col-lg-12">
		<div class="form-group  pull-right">
			<a href="<?php echo base_url().'admin/student_export_to_excel'; ?>" class="btn btn-default">EXCEL</a>
			<a href="<?php echo base_url().'admin/students_pdf/'; ?>" target="_blank" class="btn btn-default">PDF</a>
			<a href="<?php echo base_url().'admin/students_print/'; ?>" target="_blank" class="btn btn-default">Print</a>
			<a href="javascript:;" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/student_add/');" 
		    class="btn btn-primary">
		        <i class="entypo-plus-circled"></i>
		        <?php echo get_phrase('add_new_student');?>
		    </a>
		</div>
	</div>
</div>
               	
<div class="row">
    <div class="col-md-12">
        
        <ul class="nav nav-tabs bordered">
            <li class="active">
                <a href="#home" data-toggle="tab">
                    <span class="visible-xs"><i class="entypo-users"></i></span>
                    <span class="hidden-xs"><?php echo get_phrase('all_students');?></span>
                </a>
            </li>
        </ul>
        
        <div class="tab-content">
            <div class="tab-pane active" id="home">
                
                <table class="table table-bordered datatable" id="table_export">
                    <thead>
                        <tr>
                            <th width="100"><div><?php echo get_phrase('iD_number');?></div></th>
                            <th width="80"><div><?php echo get_phrase('photo');?></div></th>
                            <th><div><?php echo get_phrase('name');?></div></th>
                            <th class="span3"><div><?php echo get_phrase('address');?></div></th>
                            <th><div><?php echo get_phrase('email');?></div></th>
                            <th><div><?php echo get_phrase('options');?></div></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $students   =   $this->db->get_where('sms_student',array('company_id'=>$this->session->userdata('user')->company_id))->result_array();
                        foreach($students as $row):?>
                        <tr>
                            <td><?php echo $row['id_number'];?></td>
                            <td><img src="<?php echo $this->crud_model->get_image_url('student',$row['student_id']);?>" class="img-circle" width="30" /></td>
                            <td><a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_student_profile/<?php echo $row['student_id'];?>');"><?php echo $row['name'];?></a></td>
                            <td><?php echo $row['address'];?></td>
                            <td><?php echo $row['email'];?></td>
                            <td>
                                <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_student_edit/<?php echo $row['student_id'];?>');">
                                    <i class="entypo-pencil"></i>
                                        <?php //echo get_phrase('edit');?>
                                    </a>
                                <a href="#" onclick="confirm_modal('<?php echo base_url();?>admin/student/delete/<?php echo $row['student_id'];?>');">
                                    <i class="entypo-trash"></i>
                                        <?php //echo get_phrase('delete');?>
                                    </a>
                                <!--<div class="btn-group">
                                    <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                                        Action <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-default pull-right" role="menu">
                                        
                                        STUDENT PROFILE LINK 
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_student_profile/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-user"></i>
                                                    <?php echo get_phrase('profile');?>
                                                </a>
                                        </li>
                                        
                                         STUDENT EDITING LINK 
                                        <li>
                                            <a href="#" onclick="showAjaxModal('<?php echo base_url();?>modal/popup/modal_student_edit/<?php echo $row['student_id'];?>');">
                                                <i class="entypo-pencil"></i>
                                                    <?php echo get_phrase('edit');?>
                                                </a>
                                        </li>
                                        <li class="divider"></li>
                                        
                                        STUDENT DELETION LINK 
                                        <li>
                                            <a href="#" onclick="confirm_modal('<?php echo base_url();?>admin/student/delete/<?php echo $row['student_id'];?>');">
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
		
        </div>
        
        
    </div>
</div>
