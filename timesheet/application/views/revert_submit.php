<style>
    .table {
        margin: 46px auto;
        width: 50%;
    }
</style>


<div id="all-title">
    <div class="row">
        <div class="col-md-12">
            <div class="col-xs-12 col-sm-12 col-md-5 col-lg-5 col-xl-5">
                <img width="35" src="<?php echo base_url() ?>images/title-icon.png"/>
                <span class="title-inner"><?php echo $title; ?></span>
            </div>

        </div>
    </div>
</div>
<div class="content-inner">
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
            <form action="<?php echo site_url('timesheet/revert_submit'); ?>" method="post" style="margin: 0px auto; display: block; width: 50%;">
            <select name="staff_id" id="staff_list" class="form-control" onchange="this.form.submit()">
                <option value="">Select Staff</option>
                <?php foreach($staffs as $staff){ ?>
                    <?php $selected = ($staff_id && $staff_id == $staff->uid) ? "selected" : ""; ?>
                    <option value="<?php echo $staff->uid; ?>" <?php echo $selected; ?>><?php echo $staff->username; ?></option>
                <?php } ?>
            </select>
            </form>
        </div>
    </div>
    <?php if(isset($submitted_weeks)): ?>
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>From</th>
                    <th>To</th>
                    <th></th>
                </tr>
                </thead>
                <?php foreach($submitted_weeks as $week): ?>
                <tr>
                    <td> <?php echo $week->start_date; ?> </td>
                    <td> <?php echo $week->end_date; ?> </td>
                    <td>
                        <form action="<?php echo site_url('timesheet/revert_submit'); ?>" method="post">
                            <input type="hidden" name="staff_id" value="<?php echo $staff_id; ?>">
                            <input type="hidden" name="week_id"  value="<?php echo $week->id; ?>">
                            <input type="submit" class="btn btn-default" value="Revert">
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>


<script type="text/javascript">
    /*tour. task #4422*/
    var config = [
            {
                "name" 		: "tour_1",
                "bgcolor"	: "black",
                "color"		: "white",
                "position"	: "T",
                "text"		: "This feature is only available for manager. Revert the time sheet submission if someone accidentally pressed Submit button.",
                "time" 		: 5000,
                "buttons"	: ["<span class='btn btn-xs btn-default endtour'>close</span>"]
            }

        ],
//define if steps should change automatically
        autoplay	= false,
//timeout for the step
        showtime,
//current step of the tour
        step		= 0,
//total number of steps
        total_steps	= config.length;
    $(document).ready(function(){
        $("#maincontent").addClass('tour_1');
    })
</script>