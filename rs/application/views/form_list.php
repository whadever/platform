<style>
    option {
        margin: 2px 0;
        padding: 0 13px;
        cursor: pointer;
    }
    .dropdown-menu {
        text-align: left;
    }
    .table td, .table th {
        color: #888;
        font-size: 18px;
        vertical-align: middle;
    }

    .modal-scrollable .modal.fade.in {
        top: 40%;
    }
    .modal-scrollable .modal.stage-modal {
        border: 4px solid #cd1619;
    }
    .modal-scrollable .modal.stage-modal {
        border: 4px solid #eeeeee;
        border-radius: 0;
    }
    .modal.hide.in {
        display: block !important;
    }
    .modal.stage-modal .modal-header {
        padding: 7px 10px;
    }
    .modal .modal-header {
        border-bottom: 0 solid #e5e5e5;
    }

    #sel_staff {
        display: block !important;
        float: left;
        overflow: hidden;
        height: 34px;
        width: 0;
        border: 0;
        padding: 0;
        box-shadow: none;
        color: white;
    }

</style>
<div id="all-title">
    <div class="row">
        <div class="col-md-12">
            <img width="35" src="<?php echo base_url() ?>images/title-icon.png"/>
            <span class="title-inner"><?php echo $title; ?></span>
        </div>
    </div>
</div>
<!--<div class="content-inner row">
    <div class="col-md-12" style="text-align: center">
        <h4 style="color: #666">Select a form to edit</h4>
    </div>

</div>-->

<div class="content-inner row" style="padding-bottom: 50px">
    <div class="col-md-10 col-md-offset-1" style="">
        <!--<select name="form" class="form-control" id="sel" style="margin-bottom: 30px">
            <option value="">---Select Form---</option>
            <?php /*foreach ($forms as $form): */?>
                <option class="" value="<?php /*echo $form->id; */?>"><?php /*echo $form->name; */?></option>

            <?php /*endforeach; */?>
        </select>-->
        <a  class="btn btn-default" style="float: right; margin-left: 20px" data-toggle="modal" role="button" id="" href="#stop_report">Deactivate Report Temporary</a>
        <div class="tour tour_4" style="float: right;"><a href="<?php echo site_url('form/add'); ?>"><h4 style="color: grey">+ Add Template</h4></a></div>
        <table class="table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Created By</th>
                    <th style="text-align: left">Staff</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php $i = 0; ?>
                <?php foreach ($forms as $form): ?>
                    <tr>
                        <td width="40%"><?php echo $form->name; ?></td>
                        <td width="25%" style="font-size: 16px"><?php echo $form_creators[$form->id]; ?></td>
                        <td width="20%" style="font-size: 16px"><?php echo implode('<br>',$form_staffs[$form->id]); ?></td>
                        <td style="text-align: right">
                            <?php if($i++ == 0): //task #4429 ?>
                            <a href="<?php echo site_url('form/add/'.$form->id); ?>" title="edit"><img class="tour tour_5" src="<?php echo site_url('images/edit.png'); ?>"></a>
                            <a href="<?php echo site_url('form/duplicate/'.$form->id); ?>" title="clone"><img class="tour tour_6" src="<?php echo site_url('images/clone.png'); ?>"></a>
                            <a class="del" href="<?php echo site_url('form/delete/'.$form->id); ?>" title="delete"><img class="tour tour_7" src="<?php echo site_url('images/trash.png'); ?>"></a>
                            <?php else: ?>
                            <a href="<?php echo site_url('form/add/'.$form->id); ?>" title="edit"><img src="<?php echo site_url('images/edit.png'); ?>"></a>
                            <a href="<?php echo site_url('form/duplicate/'.$form->id); ?>" title="clone"><img src="<?php echo site_url('images/clone.png'); ?>"></a>
                            <a class="del" href="<?php echo site_url('form/delete/'.$form->id); ?>" title="delete"><img src="<?php echo site_url('images/trash.png'); ?>"></a>
                            <?php endif; ?>
                        </td>
                    </tr>


                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!--/*task #4617*/-->
<?php if($unsubmitted_reports): ?>
<div class="content-inner row">
    <div class="col-md-12" style="text-align: center">
        <h4 style="color: #666">Uncompleted Reports</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th style="text-align: center">Report</th>
                    <th style="text-align: center">User</th>
                    <th style="text-align: center">Report Period</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($unsubmitted_reports as $rpt): ?>
                <tr>
                    <td><?php echo $rpt->form_name; ?></td>
                    <td><?php echo $rpt->username; ?></td>
                    <td>
                        <?php echo $rpt->from; ?>
                        <?php if($rpt->from != $rpt->to): ?>
                            To <?php echo $rpt->to; ?>
                        <?php endif; ?>

                    </td>
                    <td>
                        <a class="btn btn-success complete-report" href="#" data-info='<?php echo json_encode($rpt); ?>'>Mark as Complete</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<!--stop report modal task #4452-->
<div class="modal hide fade stage-modal" id="stop_report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo site_url('form/deactivate_report_temporary'); ?>" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h3 class="modal-title" id="myModalLabel">Deactivate Report</h3>
                </div>
                <div class="modal-body">
                    <div class="control-group">
                        <label for="form" class="control-label">Select Report</label>
                        <select name="form" class="form-control" id="sel" style="margin-bottom: 30px" required>
                            <?php foreach ($forms as $form): ?>
                                <option class="" value="<?php echo $form->id; ?>"><?php echo $form->name; ?></option>

                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="control-group">
                        <label for="form" class="control-label">Select Staffs</label>
                        <select name="staffs[]" class="form-control" id="sel_staff" style="margin-bottom: 30px" required multiple>
                            <?php foreach ($form_staffs_with_id as $fid => $staffs): ?>
                                <?php foreach($staffs as $staff): ?>
                                    <option class="<?php echo $fid; ?>" value="<?php echo $staff['uid']; ?>"><?php echo $staff['username']; ?></option>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="control-group">
                        <label for="from_date" class="control-label">Date From</label>
                        <input type="text" class="form-control datepicker" name="from_date" required>
                    </div>
                    <div class="control-group">
                        <label for="to_date" class="control-label">Date To</label>
                        <input type="text" class="form-control datepicker" name="to_date" required>
                    </div>
                    <div class="control-group">
                        <label for="comment" class="control-label">Comment</label>
                        <textarea name="comment" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <input type="submit" name="submit" class="btn btn-default" value="Submit">
                </div>
            </form>
        </div>
    </div>
</div>
<script>
   $(document).ready(function () {
       /* $('.multiselect').selectpicker();
        $("#sel").change(function(){
            if($(this).val() != ""){
                window.location = "<?php echo base_url(); ?>form/add/"+$(this).val();
            }
        });

        $('select').selectpicker();*/
        $("a.del").click(function(event){
            event.preventDefault();
            var url = $(this).prop('href');
            $("<div />",{
                title: 'Delete Template'
            }).html('Are you sure you want to delete this template?').dialog({
                resizable: false,
                height:150,
                modal: true,
                buttons: {
                    "Delete": function() {
                        window.location = url;
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                }
            });
        });

       /*task #4617*/
       var mark_complete_url = "<?php echo site_url('form/mark_report_as_complete'); ?>";
       $(".complete-report").click(function (e) {
           e.preventDefault();
           var el = $(this);
           el.attr('disabled',true);
           var data = el.attr('data-info');
           $.ajax(mark_complete_url, {
               dataType : 'json',
               data : JSON.parse(data),
               type : 'POST',
               success : function(data){
                   if(data.status == 'success'){
                       el.parents('tr').remove();
                   }else{
                       alert(data.message);
                   }
               },
               error: function(){
                   alert('error');
               }
           });
       })

    });

   /*task #4429*/
   config.push({
       "name" 		: "tour_4",
       "bgcolor"	: "black",
       "color"		: "white",
       "position"	: "R",
       "text"		: "From here you can create your own report template.",
       "time" 		: 5000,
       "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default nextstep'>next</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
   });
   config.push({
       "name" 		: "tour_5",
       "bgcolor"	: "black",
       "color"		: "white",
       "position"	: "R",
       "text"		: "Alter your report template and reassign it again.",
       "time" 		: 5000,
       "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default nextstep'>next</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
   });
   config.push({
       "name" 		: "tour_6",
       "bgcolor"	: "black",
       "color"		: "white",
       "position"	: "R",
       "text"		: "Duplicate your template. Save your time.",
       "time" 		: 5000,
       "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default nextstep'>next</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
   });
   config.push({
       "name" 		: "tour_7",
       "bgcolor"	: "black",
       "color"		: "white",
       "position"	: "R",
       "text"		: "Deactivate your template. All previous reports submitted under this template will be available to see in View page.",
       "time" 		: 5000,
       "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
   });
   total_steps	= config.length;

    /*task #4452*/
   $(document).ready(function () {
       $('select').selectpicker();
       $('.datepicker').datepicker({
           dateFormat: "yy-mm-dd"
       });
       $("#sel_staff").chained("#sel",{
           onUpdate:function(){
               $('select').selectpicker('refresh');
           }
       });
   });
</script>
<script src="<?php echo site_url('js/jquery.chained.js'); ?>"></script>