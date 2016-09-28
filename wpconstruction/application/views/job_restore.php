<style>
    ul{
        margin-left: 20px;
    }
    #overlay {
        background-color: #000;
        background-image: url("<?php echo base_url(); ?>images/ajax-loading.gif");
        background-position: 50% center;
        background-repeat: no-repeat;
        height: 100%;
        left: 0;
        opacity: 0.5;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 10000;
    }
</style>
<h1>Restore Backup</h1>
<?php if($job_name && $backup_time): ?>
    <h3><?php echo $job_name; ?> to <?php echo $backup_time; ?></h3>
<?php endif; ?>

<?php if($backup_times): ?>
    <h3>Select a date/time</h3>
    <div class="row">
        <div class="col-md-12">
            <form action="<?php echo site_url('restore'); ?>" method="post" style="text-align: center">
                <select name='backup_time' class="form-control">
                    <option value="">Select a backup</option>
                    <?php
                    foreach ($backup_times as $time):
                        $selected = ($time == $selected_backup_time) ? "selected" : "";
                        ?>
                        <option value="<?php echo $time->backup_time; ?>" <?php echo $selected; ?>><?php echo $time->backup_time; ?></option>
                        <?php
                    endforeach;
                    ?>
                </select>
                <br><br>
                <button class="btn btn-default">Next</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php if($jobs): ?>
    <h3>Select a Job</h3>
    <div class="row">
        <div class="col-md-12">
            <form action="<?php echo site_url('restore'); ?>" method="post" style="text-align: center">
                <input type="hidden" name="backup_time" value="<?php echo $backup_time; ?>">
                <select name='job' class="form-control">
                    <option value="">Select a Job</option>
                    <?php
                    foreach ($jobs as $job):
                        ?>
                        <option value="<?php echo $job->id; ?>"><?php echo $job->development_name; ?></option>
                        <?php
                    endforeach;
                    ?>
                </select>
                <br><br>
                <button class="btn btn-default">Next</button>
            </form>
        </div>
    </div>
<?php endif; ?>

<?php if($backups): ?>

    <?php foreach($backups as $construction_phase => $phases): ?>

        <ul style="margin: 0">

            <li>
                <input type="checkbox" data-type="cp" value="<?php echo $construction_phase; ?>" class="cp">

                    <?php echo str_replace("_"," ",$construction_phase); ?>

                <ul>
                <?php foreach($phases as $id => $phase): ?>

                    <li>
                        <input type="checkbox" data-type="phase" value="<?php echo $id; ?>"> <?php echo $phase['phase_name']; ?>
                        <ul>
                            <?php foreach($phase['tasks'] as $tid => $task): ?>

                                <li>
                                    <input type="checkbox" data-type="task" value="<?php echo $tid; ?>"> <?php echo $task; ?>
                                </li>

                            <?php endforeach; ?>
                        </ul>
                    </li>

                <?php endforeach; ?>
                </ul>

            </li>

        </ul>

    <?php endforeach; ?>

    <button class="btn btn-info" id="restore" style="float: right">Restore</button>

    <script>

        var restore_list = [];
        $(document).ready(function(){
            $(".cp").change(function(){
                $(this).parent().find("input[type='checkbox']").prop('checked',$(this).is(":checked"));
            });

            $("#restore").click(function(){

                $(this).remove();

               /*making the restore list*/
                $("input[type='checkbox']:checked").each(function(){

                    restore_list.push($(this).attr("data-type")+"-"+$(this).val());

                });

                $("input[type='checkbox']").prop('disabled',true);

                var overlay = jQuery('<div id="overlay"> </div>');
                overlay.appendTo(document.body);
                $.ajax({
                    url: '<?php echo site_url('restore/restore'); ?>',
                    type: 'post',
                    dataType: 'json',
                    data: {d:restore_list},
                    success: function (data) {
                        overlay.remove();
                        if(data.status == 1){
                            alert('Data restored.');
                        }
                    },
                    error: function(jqXHR,textStatus,errorThrown){
                        overlay.remove();
                        var e = '';
                        if(textStatus){
                            e += textStatus+"\n";
                        }
                        if(errorThrown){
                            e += errorThrown;
                        }
                        alert(e);
                    }
            });


            });

        })

    </script>

<?php endif; ?>
