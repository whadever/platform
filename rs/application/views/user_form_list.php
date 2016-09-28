<style>
    option {
        margin: 2px 0;
        padding: 0 13px;
        cursor: pointer;
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
    #sel {
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
<div class="content-inner row">
    <div class="col-md-12" style="text-align: center">
        <h4 style="color: #666">Select a report to submit</h4>
    </div>

</div>

<div class="content-inner row  tour tour_4">
    <div class="col-md-6 col-md-offset-3" style=" text-align: center">
        <select name="form" class="form-control" id="sel" style="margin-bottom: 30px">
            <option value="">---Select Report---</option>
            <?php foreach ($forms as $form): ?>
                <option class="" value="<?php echo $form->id; ?>"><?php echo $form->name; ?></option>

            <?php endforeach; ?>
        </select>
    </div>

</div>
<div class="content-inner row">
    <div class="col-md-12" style="text-align: center; margin-top: 10px">
        <a data-toggle="modal" role="button" id="" href="#stop_report" class="btn btn-default">Stop Report</a>
    </div>
</div>
<!--stop report modal task #4136-->
<div class="modal hide fade stage-modal" id="stop_report" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="<?php echo site_url('form/stop_report'); ?>" method="post">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h3 class="modal-title" id="myModalLabel">Stop Report</h3>
            </div>
            <div class="modal-body">
                <div class="control-group">
                    <label for="form" class="control-label">Select Report</label>
                    <select name="form[]" class="form-control" id="sel" style="margin-bottom: 30px" required multiple>
                        <?php foreach ($forms as $form): ?>
                            <option class="" value="<?php echo $form->id; ?>"><?php echo $form->name; ?></option>

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
<!--------------------->
<script>
    $(document).ready(function () {
        $('.multiselect').selectpicker();
        $("#sel").change(function(){
            if($(this).val() != ""){
                var form_id = $(this).val();
                var url = "<?php echo base_url(); ?>form/";
                var d = $("<div>please wait...</div>");
                $(this).parent('div').append(d);
                $.ajax(url+"get_unsubmitted_report_list/"+form_id,{
                   dataType: "json",
                   success: function(data){
                       var html = "<div title='Select Reporting Period'><table class='table' style='border: none'>";
                       if(data.length == 0){
                           var li = "You don't have any pending submission.";
                       }else{
							var li = "";
						}
                       for(i in data){
                           var from = data[i].from.split('-').reverse().join('-');
                           var to = data[i].to.split('-').reverse().join('-');
                           var link = "<a href='"+url+"submit/"+form_id+"/"+data[i].id+"' class='btn btn-default'>submit</a>";
                           li = li + "<tr>";
                           li = li + "<td style='font-size: 1em; vertical-align: middle'>"+from+"</td>";
                           if(from != to){
                               li = li + "<td style='font-size: 1em; vertical-align: middle'>To </td><td style='font-size: 1em; vertical-align: middle'>"+to+"</td>";
                           }
                           li = li + "<td style='font-size: 1em; vertical-align: middle'>"+link+"</td></tr>";

                       }
                       html = html + li + "</table></div>";
                       $(html).dialog({
                           modal: true, width: 400, maxHeight: 600
                       });
                       d.remove();
                   }
                });
                //window.location = "<?php echo base_url(); ?>form/submit/"+$(this).val();
            }
        });
        $('select').selectpicker();
        $('.datepicker').datepicker({
            dateFormat: "yy-mm-dd"
        });
    });

    /*task #4429*/
    config.push({
        "name" 		: "tour_4",
        "bgcolor"	: "black",
        "color"		: "white",
        "position"	: "B",
        "text"		: "Select the report you want to submit from here and press 'Submit' after that.",
        "time" 		: 5000,
        "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
    });
    total_steps	= config.length;
</script>