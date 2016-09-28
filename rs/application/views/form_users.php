<?php
$this->db->select("wp_company.*,wp_file.*");
$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
$this->db->where('wp_company.id', $wp_company_id);
$wpdata = $this->db->get('wp_company')->row();

$color_one = $wpdata->colour_one;
$color_two = $wpdata->colour_two;
?>

<style>
    #maincontent{
        overflow: visible;
    }
    input[type=radio]{
        margin-right: 10px;

    }
</style>

<div id="all-title">
    <div class="row">
        <div class="col-md-12">
            <img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
            <span class="title-inner"><?php echo $title;  ?></span>
        </div>
    </div>
</div>
<form action="<?php echo site_url('form/staff_add'); ?>" method="post" class="tour tour_4">

    <div class="content-inner row">
        <div class="col-md-3 col-md-offset-2" style="text-align: right">
            <h4 style="color: #666">Name of the Report</h4>
        </div>
        <div class="col-md-3" style="">
            <input type="text" class="form-control" name="name" value="<?php echo $form->name; ?>" disabled>
        </div>

    </div>
    <div class="content-inner row">
        <div class="col-md-3 col-md-offset-2" style="text-align: right">
            <h4 style="color: #666">Assign User(s)</h4>

        </div>
        <div class="col-md-3" style="">
            <select name="staffs[]" class="multiselect" id="staffs" multiple style="visibility: hidden">
            <?php foreach($staffs as $staff): ?>
                <option value="<?php echo $staff->uid; ?>" <?php echo (in_array($staff->uid,$form_users)) ? "selected" : ""; ?>><?php echo $staff->username; ?></option>

            <?php endforeach; ?>
            </select>
        </div>

    </div>

    <div class="content-inner row">
        <div class="col-md-3 col-md-offset-2" style="text-align: right">
            <h4 style="color: #666">Frequency</h4>
        </div>
        <?php
        $frequency = (empty($frequency))? 'daily' : $frequency;

        ?>
        <div class="col-md-3" style="color: #666">
            <input type="radio" name="frequency" <?php if($frequency == 'daily'){echo "checked";} ?> value="daily">Daily<br>
            <input type="radio" name="frequency" <?php if($frequency == 'weekly'){echo "checked";} ?>  value="weekly">Weekly<br>
            <input type="radio" name="frequency" <?php if($frequency == 'fortnightly'){echo "checked";} ?> value="fortnightly">Fortnightly<br>
            <input type="radio" name="frequency" <?php if($frequency == 'monthly'){echo "checked";} ?>  value="monthly">Monthly<br>
            <input type="radio" name="frequency" <?php if($frequency == 'yearly'){echo "checked";} ?>  value="yearly">Yearly<br>
        </div>

    </div>
    <div class="content-inner row">
        <div class="col-md-3 col-md-offset-2" style="text-align: right">
            <h4 style="color: #666">Deadline</h4>
        </div>
        <div id="deadline-daily" class="col-md-3" style="color: #666; display: none">
            <?php  $time = ($frequency == 'daily')? $deadline : ""; ?>
            <input type="text" value="<?php echo $time; ?>" name="deadline-daily-time" class="form-control timepicker" placeholder="select time">
        </div>
        <div id="deadline-weekly" class="col-md-3" style="color: #666; display: none">
            <?php
            $days = array(
                'Sunday',
                'Monday',
                'Tuesday',
                'Wednesday',
                'Thursday',
                'Friday',
                'Saturday',
            );
            $day = ($frequency == 'weekly')? explode(' ',$deadline)[0] : 5;
            $time = ($frequency == 'weekly')? explode(' ',$deadline)[1].' '.explode(' ',$deadline)[2] : "";
            $selected = '';
            ?>
            <select class="form-control" name="deadline-weekly-day" style="margin: 5px 0">
                <?php for($i = 0; $i < 7; $i++): ?>
                    <?php $selected = ($i == $day) ? 'selected' : ''; ?>
                    <option value="<?php echo $i; ?>" <?php echo $selected; ?>><?php echo $days[$i]; ?> </option>
                <?php endfor; ?>
            </select>
            <input type="text" value="<?php echo $time; ?>" name="deadline-weekly-time" class="form-control timepicker" placeholder="select time">
        </div>
        <div id="deadline-fortnightly" class="col-md-3" style="color: #666; display: none">
            Day one: <br>
            <?php
            $day = ($frequency == 'fortnightly')? explode(' ',explode(',',$deadline)[0])[0] : "";
            $time = ($frequency == 'fortnightly')? explode(' ',explode(',',$deadline)[0])[1].' '.explode(' ',explode(',',$deadline)[0])[2] : "";
            $selected = '';
            ?>
            <select class="form-control" name="deadline-fortnightly-day1" style="margin: 5px 0">
                <?php for($i = 1; $i <= 15; $i++): ?>
                    <?php $selected = ($i == $day) ? 'selected' : ''; ?>
                    <option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo ordinal($i); ?> </option>
                <?php endfor; ?>
            </select>
            <input type="text" value="<?php echo $time; ?>" name="deadline-fortnightly-time1" class="form-control timepicker" placeholder="select time">
            <br>
            Day two: <br>
            <?php
            $day = ($frequency == 'fortnightly')? explode(' ',explode(',',$deadline)[1])[0] : "";
            $time = ($frequency == 'fortnightly')? explode(' ',explode(',',$deadline)[1])[1].' '.explode(' ',explode(',',$deadline)[1])[2]  : "";
            $selected = '';
            ?>
            <select class="form-control" name="deadline-fortnightly-day2" style="margin: 5px 0">
                <?php for($i = 16; $i <= 31; $i++): ?>
                    <?php $selected = ($i == $day) ? 'selected' : ''; ?>
                    <option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo ordinal($i); ?> </option>
                <?php endfor; ?>
            </select>
            <input type="text" value="<?php echo $time; ?>" name="deadline-fortnightly-time2" class="form-control timepicker" placeholder="select time">

        </div>
        <div id="deadline-monthly" class="col-md-3" style="color: #666; display: none">
            <?php
            $day = ($frequency == 'monthly')? explode(' ',$deadline)[0] : "";
            $time = ($frequency == 'monthly')? explode(' ',$deadline)[1].' '.explode(' ',$deadline)[2] : "";
            $selected = '';
            ?>
            <select class="form-control" name="deadline-monthly-day" style="margin: 5px 0">
                <?php for($i = 1; $i <= 31; $i++): ?>
                    <?php $selected = ($i == $day) ? 'selected' : ''; ?>
                    <?php $note = ($i == 31) ? " (selecting this will make the deadline equals to the end of each month)":""; ?>
                    <option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo ordinal($i).$note; ?> </option>
                <?php endfor; ?>
            </select>
            <input type="text" value="<?php echo $time; ?>" name="deadline-monthly-time" class="form-control timepicker" placeholder="select time">
            <br>
        </div>
        <div id="deadline-yearly" class="col-md-3" style="color: #666; display: none">
            <?php
            $month = ($frequency == 'yearly')? explode(' ',$deadline)[0] : "";
            $day = ($frequency == 'yearly')? explode(' ',$deadline)[1] : "";
            $time = ($frequency == 'yearly')? explode(' ',$deadline)[2].' '.explode(' ',$deadline)[3] : "";
            $selected = '';
            ?>
            <select class="form-control" name="deadline-yearly-day" style="margin: 5px 0">
                <?php for($i = 1; $i <= 31; $i++): ?>
                    <?php $selected = ($i == $day) ? 'selected' : ''; ?>
                    <option <?php echo $selected; ?> value="<?php echo $i; ?>"><?php echo ordinal($i); ?> </option>
                <?php endfor; ?>
            </select>
            <?php
            $months = array(
                'January',
                'February',
                'March',
                'April',
                'May',
                'June',
                'July ',
                'August',
                'September',
                'October',
                'November',
                'December',
            );
            ?>
            <select class="form-control" name="deadline-yearly-month" style="margin: 5px 0">
                <?php for($i = 0; $i <= 11; $i++): ?>
                    <?php $selected = ($i+1 == $month) ? 'selected' : ''; ?>
                    <option <?php echo $selected; ?> value="<?php echo $i+1; ?>"><?php echo $months[$i]; ?> </option>
                <?php endfor; ?>
            </select>
            <input type="text" value="<?php echo $time; ?>" name="deadline-yearly-time" class="form-control timepicker" placeholder="select time">
            <br>
        </div>


    </div>
    <div class="content-inner row">
        <div class="col-md-3 col-md-offset-2" style="text-align: right">
            <h4 style="color: #666">Notify Admin(s) and Manager(s)</h4>
        </div>
        <div class="col-md-3" style="">
            <select name="managers_to_notify[]" class="multiselect" id="" multiple style="visibility: hidden">
                <?php foreach($managers as $manager): ?>
                    <?php /*if($manager->uid == $form->manager_id) continue;*/ //task #4116 ?>
                    <option value="<?php echo $manager->uid; ?>" <?php echo (in_array($manager->uid,$notify_managers)) ? "selected" : ""; ?>><?php echo $manager->username; ?></option>

                <?php endforeach; ?>
            </select>
        </div>

    </div>
    <div class="row content-inner">
        <div class="col-md-2 col-md-offset-10" style="text-align: center">
            <input class="btn" type="submit" value="Save" style="background-color: <?php echo $color_one; ?>; color: white">
        </div>
    </div>
    <input type="hidden" name="form_id" value="<?php echo $form->id; ?>">
</form>
<?php
function ordinal($number) {
    $ends = array('th','st','nd','rd','th','th','th','th','th','th');
    if ((($number % 100) >= 11) && (($number%100) <= 13))
        return $number. 'th';
    else
        return $number. $ends[$number % 10];
}
?>
<script>
    var deadline = "<?php echo $deadline; ?>";
    $(document).ready(function(){
        $('.multiselect').selectpicker();

        $("form").submit(function(){
            //if($("#staffs").val() == null){
            //     alert('Please select a staff.');
            //     return false;
            // }
            var freq = $("input[name=frequency]:checked").val();
            var time = $("#deadline-"+freq).find('.timepicker');
            var time_is_empty = false;
            time.each(function(){
                if($(this).val() == ''){
                    time_is_empty = true;
                }
            });
            if(time_is_empty){
                alert('A time field is empty.');
                return false;
            }

        });

        $('.timepicker').timepicker({
            'timeFormat': 'h:i A', 'step': 60
        });
        var freq = $("input[name=frequency]:checked").val();

        $("#deadline-"+freq).show();
        $("input[name=frequency]").change(function(){
            $("div[id^=deadline]").hide();
            var freq = $("input[name=frequency]:checked").val();
            $("#deadline-"+freq).show();
        });
    });

    /*task #4429*/
    config.push({
        "name" 		: "tour_4",
        "bgcolor"	: "black",
        "color"		: "white",
        "position"	: "B",
        "text"		: " Easily assign your staff this report, set up the frequency of the report, and to whom you want this report to be sent.",
        "time" 		: 5000,
        "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
    });
    total_steps	= config.length;
</script>