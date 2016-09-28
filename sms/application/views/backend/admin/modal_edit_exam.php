<?php 
$edit_data		=	$this->db->get_where('sms_exam' , array('exam_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-primary" data-collapsed="0">
        	<div class="panel-heading">
            	<div class="panel-title" >
            		<i class="entypo-plus-circled"></i>
					<?php echo get_phrase('edit_exam');?>
            	</div>
            </div>
			<div class="panel-body">
				
                <?php echo form_open_multipart(base_url() . 'admin/exam/edit/do_update/'.$row['exam_id'] , array('class' => 'form-horizontal form-groups-bordered validate','target'=>'_top'));?>
            <div class="padded">
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('name');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="name" value="<?php echo $row['name'];?>" data-validate="required" data-message-required="<?php echo get_phrase('value_required');?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('date');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="datepicker form-control" name="date" value="<?php echo $row['date'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('class');?></label>
                    <div class="col-sm-5">
                        <select name="class_id" class="form-control">
                        	<option value="">--Select Class--</option>
                        	<?php 
                        	$results = $this->db->get('sms_class')->result();
                        	foreach($results as $row1){
                        	?>
                        	<option <?php if($row['class_id']==$row1->class_id){ echo 'selected'; } ?> value="<?php echo $row1->class_id; ?>"><?php echo $row1->name; ?></option>
                        	<?php } ?>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('comment');?></label>
                    <div class="col-sm-5">
                        <input type="text" class="form-control" name="comment" value="<?php echo $row['comment'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('recurring');?></label>
                    <div class="col-sm-1">
                        <input <?php if($row['recurring_yes_no']=='Yes'){ echo 'checked=""'; } ?> type="checkbox" class="form-control" name="recurring_yes_no" value="Yes"/>
                    </div>
                    <div class="col-sm-4">
                        <select name="recurring" class="form-control">
                        	<option value="">--Select Frequency--</option>
                        	<option <?php if($row['recurring']=='Weekly'){ echo 'selected=""'; } ?> value="Weekly">Weekly</option>
                        	<option <?php if($row['recurring']=='Fortnightly'){ echo 'selected=""'; } ?> value="Fortnightly">Fortnightly</option>
                        	<option <?php if($row['recurring']=='Monthly'){ echo 'selected=""'; } ?> value="Monthly">Monthly</option>
                        	<option <?php if($row['recurring']=='Yearly'){ echo 'selected=""'; } ?> value="Yearly">Yearly</option>
                        </select>
                    </div>
                </div>
                <div class="form-group document-filestyle">
                    <label class="col-sm-3 control-label"><?php echo get_phrase('document');?></label>
                    <div class="col-sm-5">
                        <input data-placeholder="<?php echo $row['document'];?>" type="file" class="filestyle" name="document" data-icon="false" data-buttonText="Browse"/>
                        <input type="hidden" name="edit_document" value="<?php echo $row['document'];?>"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-5">
                      <button type="submit" class="btn btn-info"><?php echo get_phrase('edit_exam');?></button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>
    <script src="<?php echo base_url(); ?>assets/js/bootstrap-filestyle.js"></script>
</div>

<?php
endforeach;
?>





