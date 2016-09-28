<?php
$task_responsible = array();
foreach ($job_task_list as $task) {
    if ($task->contact_id) {
        $task_responsible[$task->contact_id][] = $task;
    }
}
?>

<div class="content-inner">


    <div class="row">
        <div class="col-md-12">
            <div id="contact_list_view">
                <table class="contact">
                    <thead>
                    <tr id="header">
                        <th>Task</th>
                        <th>Company Name</th>
                        <th>Contact Name</th>
                        <th>Adress</th>
                        <th>Phone</th>
                        <th>Mobile Number</th>
                        <th>Email Address</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php
                    foreach ($task_responsible as $responsible) {
                        $list = '';
                        if (count($responsible) == 1) {
                            $list = $responsible[0]->task_name;
                        } else {
                            $list = "<select style='width:100%' name='task_list'><option>Select to See</option>";
                            for ($i = 0; $i < count($responsible); $i++) {
                                $list = $list . '<option>' . $responsible[$i]->task_name . '</option>';

                            }
                            $list .= "</select>";
                        }


                        ?>
                        <tr>
                            <!--<td><?php /*echo $phase_list; */
                            ?></td>-->
                            <td><?php echo $list; ?></td>
                            <td><?php echo $responsible[0]->company_name; ?></td>
                            <td><?php echo $responsible[0]->contact_first_name . ' ' . $responsible[0]->contact_last_name; ?></td>
                            <td><?php echo $responsible[0]->contact_address; ?></td>
                            <td><?php echo $responsible[0]->contact_phone_number; ?></td>
                            <td><?php echo $responsible[0]->contact_mobile_number; ?></td>
                            <td><?php echo $responsible[0]->contact_email; ?></td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>