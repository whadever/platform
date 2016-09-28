<style>
    table th {
        padding: 12px;
        border: 1px solid #666;
        background-color: <?php echo $color_one; ?>;
        color: white;
        text-align: center;
    }
    table tbody td {
        border: 1px solid #666;
        font-size: 14px;
        padding: 12px;
    }
    table tbody, table thead {
        border: medium none;
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
<?php if(isset($staff_name)): ?>
<div class="content-inner row">
    <div class="col-md-12" style="">
       <h3>Staff: <?php echo $staff_name; ?></h3>
    </div>
</div>
<?php endif; ?>
<div class="content-inner row">

    <div class="col-md-12" style=" text-align: center">

        <?php if(empty($reports)): ?>

            <?php echo $staff_name; ?> did not submit this report.
            
        <?php else: ?>

            <table>
                <thead>
                <tr>
                    <th>Date</th>
                    <th>Name of the Report</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach($reports as $r): ?>
                    <?php
                    $late_submission_txt = '';
                    /*if($late_submissions[$r->id]){
                        $late_submission_txt = "(late submission for {$late_submissions[$r->id]})";
                    }*/
                    ?>
                    <tr>
                        <td><?php echo date_create_from_format('Y-m-d H:i:s',$r->date)->format('d F, Y H:i:s'); ?> (<?php echo date('d-m-Y',strtotime($r->from))." to ".date('d-m-Y',strtotime($r->to)); ?>)</td>
                        <td><a target="_blank" style="text-decoration: underline" href="<?php echo site_url('form/report/'.$r->id); ?>"> <?php echo $r->username."-".$r->name."-".$r->date.".pdf"; ?> </a> </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        <?php endif; ?>


    </div>
</div>

