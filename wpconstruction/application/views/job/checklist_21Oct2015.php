<style>
    #check_list_items option {
        padding-left: 7px;
    }
    #check_list_items select {
        float: left;
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 10px;
        width: 20%;
    }
    #check_list_items #new_task {
        float: right;
        width: 20%;
    }
    #check_list_items button{
        float: right;
    }
    #check_list_items table{
        clear: both;
    }
    #check_list_items .btn-default:focus{
        background-color: #fff;
        border-color: #ccc;
        color: #333;
    }
    #add_task_loading{
        visibility: hidden;
        float: right;
        margin-top: 9px;
        margin-right: 5px;
    }

	table.task_list th{
		padding-left:7px;
	}
</style>
<div class="row">
    <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
        <div id="check_list_items" style="">
            <select name="stage" style="font-size: 14px; font-weight: bold" id="list_stage" class="form-control">
                <option value="">Select Check List</option>
                <?php foreach ($stage_info as $id => $stg): ?>
                    <option value="<?php echo $id; ?>"><?php echo $stg['stage_name']; ?></option>
                <?php endforeach; ?>
            </select>
            <button class="btn btn-default" type="submit" id="btn_new_task">+ Add Task</button>
            <input class="form-control" type="text" name="new_task" id="new_task" placeholder="new task">
            <img class="loading" id="add_task_loading" src="<?php echo base_url() . 'images/ajax-saving.gif'; ?>"/>
            <?php foreach ($stage_info as $id => $stg):?>

			
                <table class="task_list" id="task_list_stage_<?php echo $id; ?>">
                    <thead>
                    <tr>
                        <th width="40%">Task</th>
                        <th width="30%">Notes</th>
                        <th width="15%">Completed</th>
                        <th width="10%">&nbsp;</th>
						<th width="5%">Delete</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($stg['lists'] as $list_id => $list): 
						/*$ci = &get_instance();
						$ci->load->model('job_model');
						$check_list_status = $ci->job_model->get_check_list_status($list_id,$id, $_SESSION['current_job']);*/

					?>
                        <tr>
                            <td width="40%"><?php echo $list['task_name']; ?></td>
                            <td width="30%">
                                <textarea class="task_note" data-id="<?php echo $list['id']; ?>"><?php echo $list['note']; ?></textarea>
                                <img class="loading" src="<?php echo base_url() . 'images/ajax-saving.gif'; ?>"/>
                            </td>
                            <td width="15%" align="center">
                                <input type="checkbox" class="task_status" data-id="<?php echo $list_id; ?>"
                                       value="0" <?php echo ($list['status'] == 1) ? "checked" : ""; ?>>
                                <img class="loading" src="<?php echo base_url() . 'images/ajax-saving.gif'; ?>"/>
                            </td>
							<td width="10%" align="center">
                                <?php if($list['file_id']=='0'){ ?>
									<a style="color:black" onclick="DocUpload(<?php echo $list_id; ?>);" title="Add Document">Upload</a>
								<?php }else{ ?>
									<a style="color:black" title="View Document" class="fancybox" data-fancybox-type="iframe" href="<?php echo base_url(); ?>uploads/development/documents/<?php echo $list['filename']; ?>" title="Add Document">View </a>
									|<a style="color:black" onclick="EditDocUpload(<?php echo $list_id; ?>,<?php echo $list['file_id']; ?>);" title="Edit Document"> Edit</a>
								<?php } ?>
                            </td>
                            <td width="5%" align="center">
                                <?php
                                $class = "";
                                if($list['status'] == 1){
                                    $class = "hidden";
                                }
                                ?>
                                <img style="cursor: pointer" class = "<?php echo $class; ?> btn_del" src="<?php echo base_url().'images/delete.png'; ?>" class="delete" id = "del_list_<?php echo $list_id; ?>" data-id="<?php echo $list['id']; ?>" width="12">
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endforeach; ?>

        </div>
    </div>
</div>

<style>
.ui-dialog-title{
	display: none;
}
#edit-note-dialog-box .form-control:focus, #add-note-dialog-box .form-control:focus{box-shadow: 0 0 0 0;}
#edit-note-dialog-box .btn.btn-default, #add-note-dialog-box .btn.btn-default {
    background: #ecebf0;
    border: 0 none;
    border-radius: 0;
}
#edit-note-dialog-box .btn.btn-default:hover, #add-note-dialog-box .btn.btn-default:hover {
    background: #ecebf0;
    border: 0 none;
    border-radius: 0;
}
.ui-widget-content {
    border: 2px solid #ecebf0;
    border-radius: 0 !important;
}
			
.fileinputs > input#upload_document {
    opacity: 0;
    position: relative;
    width: 60%;
    z-index: 2;
	text-align: left;
}
div.fileinputs {
	position: relative;
}
div.upload-file {
    left: 33.3333%;
    position: absolute;
    top: 0;
    width: 60%;
    z-index: 1;
	border: 2px solid #ecebf0;
}
.upload-file > input {
    border: medium none;
    width: 82%;
}

.file_upload {
    border: 2px solid #ecebf0;
    margin-left: 33.3333%;
    width: 60%;
	padding: 2px 0;
}
.file_upload input.file_input_textbox {
    border: medium none;
    width: 78%;
}
.file_input_textbox {height:25px;width:200px;float:left; }
.file_input_div     {position: relative;width:21%;height:26px;overflow: hidden; }
.file_input_button {
    background-image: url("<?php echo base_url(); ?>images/file_upload.png");
    border: 1px solid #f0f0ee;
    font-weight: bold;
    height: 25px;
    margin: 0;
    padding: 0;
    position: absolute;
    top: 0;
	width: 100%;
}
.file_input_button_hover{
	background-image: url("<?php echo base_url(); ?>images/file_upload.png");
    border: 1px solid #f0f0ee;
    font-weight: bold;
    height: 25px;
    margin: 0;
    padding: 0;
    position: absolute;
    top: 0;
	width: 100%;
 }
.file_input_hidden  {font-size:45px;position:absolute;right:0px;top:0px;cursor:pointer;
                     opacity:0;filter:alpha(opacity=0);-ms-filter:"alpha(opacity=0)";-khtml-opacity:0;-moz-opacity:0; }
</style>

<div id="add-note-dialog-box" title="">
	<div class="doc_add_title" style="margin: -19px 0 20px 10px;text-align: left;"><h4>Upload Document</h4></div>
    	<?php 
    	$form_attributes = array('class' => 'addform', 'id' => 'addnoteform');
    	$label_attributes = array(
    		'class' => 'col-sm-4 control-label',
    		'style' => 'color: #000;padding-top: 7px;text-align: right;',
		);

		$upload_document = array(
              'name'        => 'upload_document',
              'id'          => 'upload_document',
              'type'       => 'file',
  			'class'       => 'file',              
        );
		$dropdown_js = 'class="form-control" style="width:60%;border: 2px solid #ecebf0;border-radius: 0;"';
		$development_id = $this->uri->segment(3);
    	$action =  base_url().'job/save_task_document/'.$development_id;
    	echo form_open_multipart($action, $form_attributes);
    	
    	
    	echo '<div class="form-group fileinputs">';
    	echo form_label('Document File', 'upload_document', $label_attributes); 
		$onchange = "javascript: document.getElementById('fileName').value = this.value";
		$onmouseover = "document.getElementById('fileInputButton').className='file_input_button_hover';";
		$onmouseout = "document.getElementById('fileInputButton').className='file_input_button';";

		echo '<div class="file_upload">';
		echo '<input type="text" id="fileName" class="file_input_textbox" readonly="readonly">';
 		echo '<div class="file_input_div">';
  		echo '<input id="fileInputButton" type="button" value="" class="file_input_button" />';
  		echo '<input required type="file" name="upload_document" class="file_input_hidden" onchange="'.$onchange.'" onmouseover="'.$onmouseover.'" onmouseout="'.$onmouseout.'" />';
		echo '</div>';
		echo '</div>';

    	echo '</div>';
    	echo '<input id="check_list_id" name="check_list_id" type="hidden" value="" />';
		echo form_label('', 'document_submit', $label_attributes);
    	echo form_submit('document_submit', 'Add', "class='btn btn-default'"); 
    	
    	echo form_close();
    	
    	?>
</div>

<div id="edit-note-dialog-box">
	<div class="doc_add_title" style="margin: -19px 0 20px 10px;text-align: left;"><h4>Edit Document</h4></div>
    	<?php 
    	$form_attributes = array('class' => 'addform', 'id' => 'addnoteform');
    	$label_attributes = array(
    		'class' => 'col-sm-4 control-label',
    		'style' => 'color: #000;padding-top: 7px;text-align: right;',
		);

		$upload_document = array(
              'name'        => 'upload_document',
              'id'          => 'upload_document',
              'type'       => 'file',
  			'class'       => 'file',              
        );
		$dropdown_js = 'class="form-control" style="width:60%;border: 2px solid #ecebf0;border-radius: 0;"';
		$development_id = $this->uri->segment(3);
    	$action =  base_url().'job/save_task_document/'.$development_id;
    	echo form_open_multipart($action, $form_attributes);
    	
    	
    	echo '<div class="form-group fileinputs">';
    	echo form_label('Document File', 'upload_document', $label_attributes); 
		$onchange = "javascript: document.getElementById('fileName').value = this.value";
		$onmouseover = "document.getElementById('fileInputButton').className='file_input_button_hover';";
		$onmouseout = "document.getElementById('fileInputButton').className='file_input_button';";

		echo '<div class="file_upload">';
		echo '<input type="text" id="fileName" class="file_input_textbox" readonly="readonly">';
 		echo '<div class="file_input_div">';
  		echo '<input id="fileInputButton" type="button" value="" class="file_input_button" />';
  		echo '<input required type="file" name="upload_document" class="file_input_hidden" onchange="'.$onchange.'" onmouseover="'.$onmouseover.'" onmouseout="'.$onmouseout.'" />';
		echo '</div>';
		echo '</div>';

    	echo '</div>';
    	echo '<input id="check_list_id_edit" name="check_list_id" type="hidden" value="" />';
		echo form_label('', 'document_submit', $label_attributes);
    	echo form_submit('document_submit', 'Edit', "class='btn btn-default' style='display:none'"); 
    	echo '<a id="Delete_file" href="#" class="btn btn-default">Delete</a>';
    	echo form_close();
    	
    	?>
</div>


<script>
	development_id = "<?php print $this->uri->segment(3); ?>";
	window.Url = "<?php print base_url(); ?>";
	function DocUpload(id)
	{
	    $('#check_list_id').val(id);
		$("#add-note-dialog-box").dialog('open');
        return false;
	}

	function EditDocUpload(id,fid)
	{
		document.getElementById('Delete_file').href=window.Url+'job/check_list_file_delete/'+fid+'/'+id+'/'+development_id;
	    $('#check_list_id_edit').val(id);
		$("#edit-note-dialog-box").dialog('open');
        return false;
	}

    $(document).ready(function(){

		$("#add-note-dialog-box").dialog({ 
            autoOpen: false,
            width : 500, 
            height: 170,
            modal: true
        });
		$("#edit-note-dialog-box").dialog({ 
            autoOpen: false,
            width : 500, 
            height: 170,
            modal: true
        });

        $(".task_list").hide();
        //$(".task_list:first").show();
        $("select[name=stage]").change(function(){
            $('.task_list').hide();
            $('#task_list_stage_'+$(this).val()).show();

        });

        /*updating notes*/
        var base_url = "<?php echo base_url();?>";

        $("#check_list_items").delegate(".task_note","blur",function(){
            var el = $(this);
            $(this).nextAll(".loading:first").css('visibility','visible');
            el.prop('disabled',true);
            $.ajax(base_url+'job/update_note/'+$(this).attr('data-id'),{
                type: 'POST',
                data:{
                    note: $(this).val()
                },
                success:function(data){
                    if(data != '-1'){
                        el.nextAll(".loading:first").css('visibility','hidden');
                    }
                    el.prop('disabled',false)
                }
            })
        });
        $("#check_list_items").delegate(".task_status","change", function(){
			var stage_id = $('#list_stage').val();
            var el = $(this);
            var status = 0;
            if(el.is(":checked")){
                status = 1;
            }
            $(this).nextAll(".loading:first").css('visibility','visible');
            $(this).prop('disabled',true);
            $.ajax(base_url+'job/update_task_status/'+$(this).attr('data-id'),{
                type: 'POST',
                data:{
                    status: status,
					stage_id: stage_id
                },
                success:function(data){
                    el.prop('disabled',false);
                    el.nextAll(".loading:first").css('visibility','hidden');
                    $("#del_list_"+el.attr('data-id')).toggleClass('hidden');
                }
            })
        });
        $("#check_list_items").delegate(".btn_del","click",function(){
            var r = confirm("Are you sure you want to delete this task?");
            if (r == true) {
                var row = $(this).parents("tr").get(0);
                $(row).find("td").last().html('deleting...');
                $.ajax(base_url+'job/delete_check_list/'+$(this).attr('data-id'),{
                    success:function(data){
                        if(data==1){
                            $(row).remove();
                        }
                    }
                })

            }


        });

        $("#btn_new_task").click(function(){
            if($("#new_task").val()==''){
                alert('Please type a task name.');
                return;
            }
            if($("#list_stage").val()==''){
                alert('Please select a stage.');
                return;
            };
            $("#new_task").prop('disabled',true);
            $("#add_task_loading").css('visibility','visible');
            $.ajax(base_url+'job/add_task_to_job',{
                type: 'POST',
                data:{
                    name: $("#new_task").val(),
                    stage: $("#list_stage").val()
                },
                dataType:'json',
                success:function(data){
                    if(data != '-1'){
                        console.log(data); console.log(data.task_id);
                        var tbl_body =  $("#task_list_stage_"+$("#list_stage").val()+" tbody");
                        var new_row = tbl_body.find("tr").last().clone().appendTo(tbl_body);
                        $(new_row.find('td').get(0)).text($("#new_task").val());
                        $(new_row.find('td').get(1)).find('textarea').empty().attr('data-id',data.status_id);
                        $(new_row.find('td').get(2)).find('input').prop('checked',false).attr('data-id',data.task_id);
                        $(new_row.find('td').get(3)).find('img').prop('id','del_list_'+data.task_id).attr('data-id',data.status_id);

                    }
                    $("#new_task").prop('disabled',false).val('');
                    $("#add_task_loading").css('visibility','hidden');
                }
            })
        });
    });
</script>