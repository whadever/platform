
<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
			<input type="button" value="Back" class="btn btn-default add-button" onclick="history.go(-1);"/>
		</div>
	</div>
</div>
<div class="breadcrumb-box">
<?php
$user=  $this->session->userdata('user');  
  $user_role_id =$user->rid; 
  
$this->breadcrumbs->push('Projects', 'project/project_list');
$this->breadcrumbs->push($project_title, 'project/project_detail/'.$project_id);

  echo $this->breadcrumbs->show();  
?>
</div>

<div id="project-detail" class="content-inner">

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

    <div id="button_wrapper" class="row button-wrapper">

        <div class="col-md-2"> 
            <p><a class="btn btn-default"  href="<?php echo base_url()?>project_notes/index/<?php echo $project_id; ?>">Project Notes</a></p>
        </div>
        <div class="col-md-2"> 
            <p><a class="btn btn-default" href="<?php echo base_url()?>project/project_hours/<?php echo $project_id; ?>"> Track Hours</a></p> 
        </div>
        <div class="col-md-3">    
            <p><a class="btn btn-default" href="<?php echo base_url()?>request/request_add/<?php echo $project_id.'/'.$company_id; ?>">
            Add Task to This Project</a> </p>
       </div>
        <?php if($user_role_id!=3){ ?> 
        <div class="col-md-2">
                   
            <p><a class="btn btn-default" href="<?php echo base_url()?>project/project_update/<?php echo $project_id; ?>">Modify project</a></p>
        </div>
        <div class="col-md-2">    
            <p><a class="btn btn-default" data-toggle="modal" data-target="#projectCloseModal" >Close Project</a></p>
        
        </div>
        <?php } ?>

    </div>



    <?php


    $ci = & get_instance();
    $ci->load->model('notes_model');
    $user_option = $ci->notes_model->get_user_list();

    $user_default= 0;
    $user_js = 'id="assign_user_select" class="multiselectbox"';
    $assign_user = form_multiselect('assign_user_id[]', $user_option, $user_default, $user_js);

    ?>
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
                <div class="clear"></div>
                <div id="notes_container_bottom" style="">
                    <div>
                        <?php
                        $action= base_url().'project_notes/upload_note_image';
                        $form_attributes = array('class' => 'notes-add-form', 'id' => 'notes-form', 'method'=>'post');
                        echo form_open_multipart($action, $form_attributes);
                        ?>
                        <input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id?>"/>
                        <table border="0" class="" width="100%" style="background:#fff;">
                            <tr>
                                <td><textarea id="mynote" cols="150" rows="3"> </textarea> </td>
                                <td> <input style="height:50px;width:65px;" type="submit" id="submitnote" value=""/></td>
                                <td> <input type="file" name="note_image" id="note_image"><img style="margin-right:0px;" width="20" border="0" src="<?php echo base_url();?>images/attachment.png"/> <div id='preview'> </div></td>

                            </tr>
                        </table>




                        <?php 	echo form_close(); ?>
                    </div>
                    <div id="notify_user_select_box" style="">
                        <input id="notify_user_checkbox" type="checkbox"/>
                        <span style="">Notify User</span>
                        <span id="user_select_list" style="display:none;"><?php echo $assign_user; ?></span>

                    </div>
                </div>
            </div>
    </div>
</div> 

<div id="task_box" class="box">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
            <h4 align="center">Open Tasks(<?php if(isset($count_open_bug)) { echo $count_open_bug;} ?>)</h4>
            <a href="#" id="pirnt_open_tasks" style="position: absolute; right: 20px; top: 11px;">
                <img src="<?php echo base_url()?>images/print.png"/>
            </a>
            <div class="scroll">
                    <?php if(isset($open_bug_table)) { echo $open_bug_table;} ?> 
            </div>
             
        </div>

       <div class="col-xs-12 col-sm-6 col-md-6">
           <h4 align="center">Close Tasks(<?php if(isset($count_close_bug)) { echo $count_close_bug;} ?>)</h4>
           <div class="scroll">
               <?php if(isset($close_request_table)) { echo $close_request_table;	} ?> 
           </div>
           
       </div>              

    </div>
</div>
    
  <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12" style="text-align:center;"> 
<?php 
          
            if($user_role_id==1){ ?>
               <a class="btn btn-default" data-toggle="modal" data-target="#myModal" >Delete Project</a>
<?php } ?>   
        </div>
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
        <h4 class="modal-title" id="myModalLabel">Delete Project</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to delete this Project?</p>
      </div>
      <div class="modal-footer">
          <a  href="<?php echo base_url()?>project/project_delete/<?php echo $project_id; ?>" class="btn btn-default">Yes</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        
      </div>
    </div>
  </div>
</div>

<!-- Project Close modal -->
<div class="modal fade" id="projectCloseModal" tabindex="-1" role="dialog" aria-labelledby="projectCloseModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <h4 class="modal-title" id="projectCloseModalLabel">Close Project</h4>
      </div>
      <div class="modal-body">
          <p>Are you sure you want to close this Project?</p>
      </div>
      <div class="modal-footer">
          <a  href="<?php echo base_url()?>project/project_close/<?php echo $project_id; ?>" class="btn btn-default">Yes</a>
        <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
        
      </div>
    </div>
  </div>
</div>

<div id='open_task_list' style='display: none'>
    <div>
    <?php
    /* the hidden open tasks table. it will be printed*/
    echo $open_tasks_table;
    ?>
    </div>
</div>

<style>
    .project_request tr th{
        padding-left: 5px;
    }  
    .project_request tr td{
        padding-left: 10px;
    }
    #notes-box {
        border: 1px solid #eee;
        height: 229px;
        overflow-y: scroll;
        padding: 10px;
    }
    #notes-box {
        border-bottom: 1px solid black;
    }
    
</style>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.printElement.js"></script>
<script>
    // using JQUERY's ready method to know when all dom elements are rendered
    $( document ).ready(function () {


        $("#submitnote").click(function (e) {

            e.preventDefault();
            var note = $('#mynote').val();
            var userid = $('#assign_user_select').val();

            var value=$.trim(note);
            var mynote = note.replace(/\n/g,"<br>")



            if(value.length == 0){
                alert('Please enter your note.');
            }else{
                var pid = $('#project_id').val();
                $('#mynote').val('');

                $('#notify_user_checkbox').removeAttr('checked');
                $('#assign_user_select').prop('defaultSelected');
                $('#user_select_list').css("display","none");


                $.ajax({
                    url:window.mbsBaseUrl + "project_notes/show_notes/"+pid+"/"+userid,
                    dataType: 'html',
                    data: { notes: mynote},
                    type: 'GET',
                    success:function(data){
                        if(data){
                            //console.log(data);
                            // $("#text").append("Me : " + data + "<br />");
                            $("#notes-box").html(data);
                        }
                    }
                });

            }
        });


        $('#note_image').on('change', function(e){
            e.preventDefault();
            $("#preview").html('');
            $("#preview").html('<img class="image-loader" src="'+window.mbsBaseUrl+'images/loader.gif" alt="Uploading...."/>');
            $("#notes-form").ajaxForm({
                target: '#preview',
                data: {
                    var1: $("#project_id").val()   //assuming #inputText is a text field
                },
                success: function() {
                    refresh_files();
                }

            }).submit();

        });


        String.prototype.nl2br = function()
        {
            return this.replace(/\n/g, "<br />");
        }

    });
    function refresh_files()
    {
        var rid = $('#project_id').val();
        $.ajax({
            url:window.mbsBaseUrl +"project_notes/show_notes_with_image/"+rid,
            dataType: 'html',
            type: 'GET',
            success:function(data){
                if(data){

                    $("#notes-box").html(data);

                }
            }
        });

    }
</script>
<script>
    var objDiv = document.getElementById("notes-box");
    objDiv.scrollTop = objDiv.scrollHeight;
    $(document).ready(function() {

        $('.multiselectbox').selectpicker();
    });

    $( document ).ready(function () {
        $('#notify_user_checkbox').click(function() {
            $("#user_select_list").toggle(this.checked);
        });

        /*printing open tasks*/
        $('#open_task_list div').prepend($(".brand img").get(0));
        $("#pirnt_open_tasks").click(function(){

            /*var mywindow = window.open('', 'my div', 'height=400,width=600');
            mywindow.document.write('<html><head><title>open tasks</title>');
            mywindow.document.write('<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" />');
            mywindow.document.write('</head><body style="padding: 20px">');
            mywindow.document.write($("#open_task_list").html());
            mywindow.document.write('</body></html>');

            mywindow.document.close(); // necessary for IE >= 10
            mywindow.focus(); // necessary for IE >= 10

            mywindow.print();*/
            //mywindow.close();

            $('#open_task_list div').printElement();

            return false;
        })
    });

</script>
