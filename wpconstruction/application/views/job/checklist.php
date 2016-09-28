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

    #check_list_items button {
        float: right;
    }

    #check_list_items table {
        clear: both;
    }

    #check_list_items .btn-default:focus {
        background-color: #fff;
        border-color: #ccc;
        color: #333;
    }

    #add_task_loading {
        visibility: hidden;
        float: right;
        margin-top: 9px;
        margin-right: 5px;
    }

    table.task_list th {
        padding-left: 7px;
    }

    #checklist-container .row {
        margin: auto;
    }

    .task-row:nth-child(2n+1) {
        /*background-color: #eee;*/
    }

    .task-row input {
        border: 1px solid #eee;
        margin: 2px 0;
    }
    .btn:focus, .btn:active:focus, .btn.active:focus {
        outline-offset: -2px;
    }
    #submit-btn{
        background-color: #afb0b3;
        border-color: #afb0b3;
        color: #fff;
    }
    #overlay {
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        right: 0;
        background: #000;
        opacity: 0.8;
        filter: alpha(opacity=80);
    }
    #loading {
        position: absolute;
        top: 50%;
        left: 50%;
        margin: -28px 0 0 -25px;
    }
</style>
<div id="checklist-container">
    <div class="row header_row">
        <div class="col-md-7">

        </div>
        <div class="col-md-2">

        </div>
        <div class="col-md-3" style="text-align: center">
        </div>
    </div>
    <form id="checklist-form" action="<?php echo site_url("job/checklist_submit/{$_SESSION[$_SERVER['SERVER_NAME']]['current_job']}"); ?>?cp=<?php echo $_GET['cp']; ?>" method="post">
        <?php $stages = array_keys($stage_info); ?>
        <?php foreach ($stages as $stage) : ?>
            <div class="row">
                <div class="col-md-7">
                    <h3><?php echo $stage; ?></h3>
                </div>
                <div class="col-md-2" style="padding-top: 20px; font-weight: bold; text-align: center">
                    <span style="width: 30%; display: inline-block">N/A</span>
                    <span style="width: 30%; display: inline-block">YES</span>
                    <span style="width: 30%; display: inline-block">NO</span>
                </div>
                <div class="col-md-3" style="text-align: center; padding-top: 20px; ; font-weight: bold">
                    Note
                </div>
            </div>
            <?php foreach ($stage_info[$stage] as $info): ?>
                <div class="row task-row" style="margin-bottom: 5px">
                    <div class="col-md-7" style="padding: 6px 0 0 35px;">
                        <?php echo $info['task_name']; ?>
                    </div>
                    <div class="col-md-2" style="text-align: center; padding-top: 8px">
                        <span style="width: 30%; display: inline-block"><input type="radio" <?php if($info['status'] == 2) echo "checked"; ?>
                                                                               name="task_status_<?php echo $info['task_id']; ?>"
                                                                               value="2"></span>
                        <span style="width: 30%; display: inline-block"><input type="radio"  <?php if($info['status'] == 1) echo "checked"; ?>
                                                                               name="task_status_<?php echo $info['task_id']; ?>"
                                                                               value="1" ></span>
                        <span style="width: 30%; display: inline-block"><input type="radio"  <?php if($info['status'] === '0') echo "checked"; ?>
                                                                               name="task_status_<?php echo $info['task_id']; ?>"
                                                                               value="0" ></span>
                    </div>
                    <div class="col-md-3" style="text-align: center">
                        <input type="text" name="task_note_<?php echo $info['task_id']; ?>"
                               value="<?php echo $info['note']; ?>"/>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
        <?php if(!$is_submitted && !empty($stage_info)): ?>
        <div class="row ">
            <div class="col-md-12" style="padding: 0">
                <input type="hidden" name="form_id" value="<?php echo $form_id; ?>">
                <input id="submit-btn" type="submit" class="btn btn-default" value="Finalize"style="float: right; width: 200px; padding: 10px; margin-top: 20px; font-weight: bold">
            </div>
        </div>
        <?php endif; ?>
    </form>
</div>

<div id="confirmDialog" title="Finalize Checklist" style="display: none">
    <p>Are you sure you want to submit this checklist?</p>
</div>

<script>
    $(document).ready(function(){
        $("#checklist-form").ajaxForm({
            beforeSubmit: function(arr, $form, options) {
                var over = '<div id="overlay">' +
                    '<img id="loading" src="<?php echo site_url('images/ajax-loading.gif'); ?>">' +
                    '</div>';
                $(over).appendTo('body');
            },
            success: function(data){
                if(data.status == 'error'){
                    alert(data.message);
                    $('#overlay').remove();
                }else{

                    //location.reload();
                    window.location = "<?php echo site_url("constructions/construction_documents/{$_SESSION[$_SERVER['SERVER_NAME']]['current_job']}/documents"); ?>?cp=<?php echo $_GET['cp']; ?>";
                }
                //$('#overlay').remove();
            },
            dataType: 'json'
        });
        $("#submit-btn").click(function(e){
            e.preventDefault();


            $( "#confirmDialog" ).dialog({
                resizable: false,
                modal: true,
                buttons: {
                    "Yes": function() {
                        $("#checklist-form").submit();
                        $( this ).dialog( "close" );
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        });
        <?php if($is_submitted): ?>
            $("#checklist-form input").prop('disabled','disabled');
        <?php endif; ?>
    })
</script>