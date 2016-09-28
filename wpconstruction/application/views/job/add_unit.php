<style>
    #frm{
        padding: 0 10px;
    }
    #frm table td {
        border: medium none;
    }
    #frm table {
        border: medium none;
    }
    #frm input, #frm select {
        border: 1px solid;
        border-radius: 11px;
        line-height: 18px;
        width: 35%;
    }
    #frm table td, .related_units {
        font-size: 15px;
        font-weight: bold;
    }
    .development-content {
        margin-top: 16px;
        padding: 0 10px;
    }
</style>
<div class="development-add-home" style="background: #fff;">
    <div class="development-header">
        <a class="" style="margin-bottom: 5px; display: block" href="<?php echo site_url('job/show_popup_menu'); ?>"><< main menu</a>
        <div class="popup_title">
            <h2 class="popup_title2"><?php echo $title; ?></h2>
        </div>
    </div>
    <form method="post" action="<?php echo base_url(); ?>job/add_unit">
    <div id="frm">

            <table>
                <tr><td width="25%">Name:</td><td><input name="development_name"></td></tr>
                <tr><td>Job Number:</td><td><input name="job_number"></td></tr>
                <tr><td>No. of Units:</td><td><input name="no_of_units"></td></tr>
                <tr>
                    <td>Pre-Construction Template:</td>
                    <td>
                        <select name="pre_construction_tid" class="form_control">
                            <option value="">Select Template</option>
							
                            <?php foreach($templates as $template): ?>
                                <option value="<?php echo $template['id']; ?>"><?php echo $template['template_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>Construction Template:</td>
                    <td>
                        <select name="tid" class="form_control">
                            <option value="">Select Template</option>

                            <?php foreach($templates as $template): ?>
                                <option value="<?php echo $template['id']; ?>"><?php echo $template['template_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </td>
                </tr>
            </table>
            <input type="hidden" name="related_jobs" value="">

    </div>
    <div class="development-content">
        <div class="development-table">
            <div class="development-table-inner admindevelopment-list ">
                <span class="related_units">Related Units:</span>
                    <table class="table-hover">
                        <thead>
                        <tr>
                            <th>Name</th><th>Location</th><th>Size</th><th>Template</th><th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($admindevelopments as $admindevelopment){ ?>
                            <tr class="check">

                                <td><?php echo $admindevelopment->development_name; ?></td>
                                <td><?php echo $admindevelopment->development_location; ?></td>
                                <td><?php echo $admindevelopment->development_size; ?></td>
                                <td><?php echo $admindevelopment->template_name; ?></td>
                                <td><input type="checkbox" class="related_jobs" name="related_jobs[]" value="<?php echo $admindevelopment->id; ?>"></td>
                            </tr>

                        <?php } ?>
                        </tbody>
                    </table>
            </div>
        </div>
        <div class="clear"></div>
    </div>
        <input type="submit" class="btn" value="Save" style="background-color: #f9b800;color: white; margin: 10px; float: right">
    </form>

</div>
<script>
    $(document).ready(function(){
        $(".related_jobs").change(function(){
            $("input[name=no_of_units]").val($(".related_jobs:checked").length);
        })
    })
</script>

