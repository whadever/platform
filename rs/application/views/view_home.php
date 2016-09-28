<style>
    option {
        margin: 2px 0;
        padding: 0 13px;
        cursor: pointer;
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
<form method="post">
<div class="content-inner row">
    <div class="col-md-6 col-md-offset-3" style=" text-align: center">
        <select name="form" class="form-control" id="sel" style="margin-bottom: 30px">
            <option value="">---Select Report---</option>
            <?php foreach ($forms as $form): ?>
                <?php $deleted = ($form->active)?"":" (deleted)"; ?>
                <option class="" value="<?php echo $form->id; ?>"><?php echo $form->name; ?><?php echo $deleted; ?></option>

            <?php endforeach; ?>
        </select>
    </div>
</div>
<?php if(isset($staffs)): ?>

    <div class="content-inner row" id="staff_list" style="visibility:hidden">
        <div class="col-md-6 col-md-offset-3" style=" text-align: center">
            <select name="staff" class="form-control" id="sel" style="margin-bottom: 30px">
                <option value="">---Select Staff---</option>
                <?php /*foreach ($staffs as $staff): */?><!--
                    <option class="" value="<?php /*echo $staff->uid; */?>"><?php /*echo $staff->username; */?></option>

                --><?php /*endforeach; */?>
            </select>
        </div>
    </div>

<?php endif; ?>

<div class="col-md-12" style="text-align:center; margin-bottom: 20px">

    <input type="submit" name="submit" value="Submit" class="btn" style="background-color: <?php echo $color_one; ?>; color: white">

</div>
<div class="" style="clear: both"></div>
</form>

<script>
    <?php
    if(isset($staffs)){
        echo "var staffs = {$staffs};";
    }
    ?>

    $(document).ready(function(){

        $('select').selectpicker();

        $('select[name=form]').change(function(){
            if($(this).val() == ''){
                $("#staff_list").css('visibility','hidden');
            }else{
                var val = $(this).val();
                $("#staff_list").css('visibility','visible');
                if(staffs[val].length != 0){
                    $("#staff_list select").empty().append('<option value="">--Select Staff--</option>');
                    for(u in staffs[val]){
                        $("<option />",{
                            value: staffs[val][u].user_id
                        }).text(staffs[val][u].user_name).appendTo($("#staff_list select"));
                    }

                }else{
                    alert("No staff assigned for this report. Please select another report.");
                    $("#staff_list").css('visibility','hidden');
                    $("#staff_list select").empty();
                }

                $("#staff_list select").selectpicker('refresh');
            }
        })
    })
</script>

