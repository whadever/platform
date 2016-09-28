<script src="<?php echo base_url(); ?>js/fuse.min.js"></script>
<script src="<?php echo base_url(); ?>js/jquery.chained.js"></script>
<style>

    #fuzzNameContainer{ margin-left:33%; width:55%;border-radius: 5px;}
    #fuzzDropdownContainer{margin-left:33%; width:55%;border-radius: 5px;}
    .fuzzMagicBox{width:99%!IMPORTANT;}

    .modal .controls select {
        height: 31px;
    }
    .modal .controls .bs-searchbox input[type="text"] {
        width: 100%;
    }

</style>


<script>

    window.Url = "<?php print base_url(); ?>";
    jQuery(document).ready(function() {

        $('input.submit').change(function(e){
            e.preventDefault();
            $('li.accordion').removeClass('accordion-active'); // removes all highlights from tr's
            $('li.accordion').addClass('accordion-active'); // adds the highlight to this row
        });

        /*task #4566*/
        $('form').each(function () {
            var contact = $(this).find('select.contact').get(0);
            var company = $(this).find('select.company').get(0); // task #4563
            var category = $(this).find('select.category').get(0);
            var form_id = $(this).prop('id');
            var id1 = $(category).prop('id');
            var id2 = $(company).prop('id');
            var id3 = $(contact).prop('id');
            if (id1 != undefined && id2 != undefined && id3 != undefined) {
                $("#"+form_id+" #"+id2).chained("#"+form_id+" #"+id1,{
                    onUpdate:function(){
                        $("#"+form_id+" #"+id2).selectpicker('refresh');
                    }
                });
                $("#"+form_id+" #"+id3).chained("#"+form_id+" #"+id2,{
                    onUpdate:function(){
                        $("#"+form_id+" #"+id3).selectpicker('refresh');
                    }
                });
            }
        });

    });

</script>

<script>
    jQuery(document).ready(function() {
        if (jQuery('#sortable-phase').length){
            $( "#sortable-phase" ).sortable({
                update : function () {
                    var order = $('#sortable-phase').sortable('serialize');
                    $.ajax({
                        url: window.Url + 'template/template_phase_ordering',
                        type: 'POST',
                        data: order,
                        success: function(data)
                        {

                        },

                    });
                }
            });
            $( "#sortable-phase" ).disableSelection();
        }
    });
</script>

<script>
    jQuery(document).ready(function() {

        $( "#draggable-phase" ).draggable({
            //connectToSortable: "#sortable-phase",
            helper: "clone",
            revert: "invalid"
        });
        $( ".accordions-body" ).droppable({

            drop: function( event, ui ) {
                if (ui.draggable.is('#draggable-phase')) {
                    $( this )
                    .addClass( "droppable-add" )
                    .find( "li#droppable-phase" )
                    .html( '<a href="#PhaseModal" role="button" data-toggle="modal" class="edit">+Add New Phase</a>' );
                }
            }
        });


    });
</script>

<?php

$user = $this->session->userdata('user');
$this->db->where('wp_company_id', $user->company_id);
$cats = $this->db->get('contact_category')->result();

$this->db->where('wp_company_id', $user->company_id);
$companies = $this->db->get('contact_company')->result();

$this->db->where('wp_company_id', $user->company_id);
$this->db->where('status', '1');
$contacts = $this->db->get('contact_contact_list')->result();

?>

<div class="clear"></div>
<?php $form_id = 1; ?>
<div class="template-design" style="background: #fff;">
    <div class="template-header">
        <div class="template-title">
            <div class="all-title"><?php echo $title; ?></div>
        </div>
        <div class="start-page"><p>Template Design</p></div>
        <?php if (!isset($template_design_update->id)) : ?>
            <div class="start-over">
                <a href="<?php echo base_url(); ?>template/template_start">Start Over</a>
            </div>
        <?php endif; ?>
        <div class="clear"></div>
    </div>
    <div class="title-inner">Start Page > Basic Info > Template Design</div>
    <div class="clear"></div>

    <div class="template-body">

        <div class="task-phase-inner">
            <div class="task-phase-header">
                <ul class="drag-phase-task">
                    <li id="draggable-task"><a href="#AddTask" role="button" data-toggle="modal" class="plus-icon" style="margin-left: 5px; color: white">Add Task <img src="<?php print base_url(); ?>images/plus-icon1.png" /></a></li>
                    <li id="draggable-phase"><a href="#PhaseModal" role="button" data-toggle="modal" class="plus-icon" style="color: white">Add Phase <img src="<?php print base_url(); ?>images/plus-icon1.png" /></a></li>
                </ul>
            </div>
            <div class="clear"></div>
            <!-- Accordions -->
            <div class="accordions-body">
                <ul id="sortable-phase" class="accordions">

                    <!-- Accordion -->
                    <?php
                    $url_phase_id = $this->uri->segment(4);
                    $query = "SELECT construction_template_phase.*, contact_company.category_id company_category, contact_company.id phase_person_responsible_company
                              FROM construction_template_phase
                                   LEFT JOIN contact_contact_list ON construction_template_phase.phase_person_responsible = contact_contact_list.id
                                   LEFT JOIN contact_company ON contact_company.id = contact_contact_list.company_id
                              WHERE template_id=$template_id ORDER BY ordering ASC";
                    $phase_query = $this->db->query($query);
                    $phase_result = $phase_query->result();
                    $phase_row_count = count($phase_result);
                    $first_phase = "";
                    //task #4200
                    $tasks = $this->db->query("SELECT * FROM construction_template_task where template_id=$template_id ORDER BY ordering ASC")->result();

                    foreach ($phase_result as $phase_row) {
                        if ($first_phase == "") {
                            $first_phase = $phase_row->id;
                        }
                        ?>

                        <script>
                            jQuery(document).ready(function() {

                                $( "#draggable-task" ).draggable({
                                    //connectToSortable: "#sortable-phase",
                                    helper: "clone",
                                    revert: "invalid"
                                });

                                $( "#listItemPhase_<?php echo $phase_row->id; ?>" ).droppable({

                                    drop: function( event, ui ) {
                                        if (ui.draggable.is('#draggable-task')) {
                                            $("#listItemPhase_<?php echo $phase_row->id; ?> .accordion-content").css("display","block");
                                            $( this )
                                            .addClass( "accordion-active" )
                                            .find( "li#droppable-task-<?php echo $phase_row->id; ?>" )
                                            .html( '<a href="#AddTask_<?php echo $phase_row->id; ?>" role="button" data-toggle="modal" class="edit">+ Add New Task</a>' );
                                        }
                                    }
                                });

                            });
                        </script>

                        <li id="listItemPhase_<?php echo $phase_row->id; ?>" class="accordion <?php if (($phase_row->id) == ($url_phase_id)) {
                        echo 'accordion-active';
                    } ?>">

                            <div class="accordion-header">
                                <div class="accordion-icon"></div>
                                <h6>
                                    <?php echo $phase_row->phase_name; ?>
                                    <a href="#EditPhase_<?php echo $phase_row->id; ?>" title="Phase Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php print base_url(); ?>images/icon/edit_pen.png" /></a>
                                    <a href="#DeletePhase_<?php echo $phase_row->id; ?>" title="Phase Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php print base_url(); ?>images/icon/btn_horncastle_trash.png" /></a>

                                    <!-- MODAL Phase Edit -->
                                    <div id="EditPhase_<?php echo $phase_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <form class="form-horizontal" id="<?php echo "form_".$form_id++; ?>" action="<?php echo base_url(); ?>template/template_phase_update/<?php echo $phase_row->id; ?>" method="POST">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h3 id="myModalLabel">Edit Phase Details</h3>
                                            </div>
                                            <div class="modal-body">

                                                <input type="hidden" id="phase_id" name="phase_id" value="<?php echo $phase_row->id; ?>" readonly="">
                                                <div class="control-group">
                                                    <label class="control-label" for="phase_name">Phase Name </label>
                                                    <div class="controls">
                                                        <input type="text" id="phase_name" placeholder="" name="phase_name" value="<?php echo $phase_row->phase_name; ?>">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="phase_length">Phase Length </label>
                                                    <div class="controls">
                                                        <input type="text" id="phase_length" placeholder="" name="phase_length" value="<?php echo $phase_row->phase_length; ?>">
                                                    </div>
                                                </div>
                                                <?php //if ($phase_row->id != $first_phase): ?>
                                                <div class="control-group">
                                                    <label class="control-label" for="dependency">Phase Dependency </label>
                                                    <div class="controls">
                                                    <select name="dependency" style="width:55%" id="phase-dep-<?php echo $phase_row->id; ?>" class="phase-dep">
                                                        <option value=''>Select Dependency</option>
                                                        <?php
                                                        foreach ($phase_result as $phase_row2) {
                                                            $is_selected = "";
                                                            if($phase_row2->id != $phase_row->id){
                                                                if($phase_row->dependency == $phase_row2->id){
                                                                    $is_selected = "selected = 'selected' ";
                                                                }
                                                                echo "<option {$is_selected} value='{$phase_row2->id}'>{$phase_row2->phase_name}</option>";
                                                            }

                                                        }
                                                        ?>
                                                    </select>
                                                    </div>
                                                </div>
                                                <!-- task #4630. removed other fields-->
                                                <!-------------->
                                                <div class="control-group">
                                                    <label class="control-label" for="inputPassword"></label>
                                                    <div class="controls">
                                                        <input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
                                                        <input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">

                                                        <div class="save">
                                                            <input type="submit" value="Submit" name="submit" />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                    <!-- MODAL Phase Edit-->
                                    <!-- MODAL Phase Delete-->
                                    <div id="DeletePhase_<?php echo $phase_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <form id="<?php echo "form_".$form_id++; ?>" class="form-horizontal" action="<?php echo base_url(); ?>template/template_phase_delete/<?php if (isset($phase_row->id)) {
                                                                echo $phase_row->id;
                                                            } ?>" method="POST">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h3 id="myModalLabel">Delete Phase: <?php if (isset($phase_row->phase_name)) {
                                                                echo $phase_row->phase_name;
                                                            } ?></h3>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure want to delete this Phase?</p>

                                            </div>
                                            <div class="modal-footer delete-task">
                                                <input type="hidden" id="template_id" name="template_id" value="<?php echo $template_id; ?>">
                                                <input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
                                                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                                                <input type="submit" value="Ok" name="submit" class="btn" />
                                            </div>
                                        </form>
                                    </div>
                                    <!-- MODAL Phase Delete-->

                                </h6>



                            </div>

                            <div class="accordion-content"  <?php if (($phase_row->id) == ($url_phase_id)) {
                                                                echo 'style="display: block;"';
                                                            } else {
                                                                echo 'style="display: none;"';
                                                            } ?>>

                                <script>
                                    jQuery(document).ready(function() {
                                        if (jQuery("#sortable-task-<?php echo $phase_row->id; ?>").length){
                                            $( "#sortable-task-<?php echo $phase_row->id; ?>" ).sortable({
                                                update : function () {
                                                    var order = $('#sortable-task-<?php echo $phase_row->id; ?>').sortable('serialize');
                                                    $.ajax({
                                                        url: window.Url + 'template/template_task_ordering',
                                                        type: 'POST',
                                                        data: order,
                                                        success: function(data)
                                                        {

                                                        },

                                                    });
                                                }
                                            });
                                            $( "#sortable-task-<?php echo $phase_row->id; ?>" ).disableSelection();
                                        }
                                    });
                                </script>

                                <ul id="sortable-task-<?php echo $phase_row->id; ?>" class="">
    <?php
    $phase_id = $phase_row->id;
    $query = "SELECT construction_template_task.*, contact_company.category_id company_category, contact_company.id task_person_responsible_company
              FROM construction_template_task
                  LEFT JOIN contact_contact_list ON construction_template_task.task_person_responsible = contact_contact_list.id
                  LEFT JOIN contact_company ON contact_company.id = contact_contact_list.company_id
              WHERE template_id=$template_id and phase_id=$phase_id ORDER BY ordering ASC";
    $task_query = $this->db->query($query);
    $task_result = $task_query->result();
    $task_row_count = count($task_result);
    foreach ($task_result as $task_row) {
        ?>
                                        <li id="listItemTask_<?php echo $task_row->id; ?>">

        <?php echo $task_row->task_name; ?>
                                            <a href="#EditTask_<?php echo $task_row->id; ?>" title="Task Edit" role="button" data-toggle="modal" class="template-task-edit"><img width="16" height="16" src="<?php print base_url(); ?>images/icon/edit_pen.png" /></a>
                                            <a href="#DeleteTask_<?php echo $task_row->id; ?>" title="Task Delete" role="button" data-toggle="modal" class="template-task-delete"><img width="16" height="16" src="<?php print base_url(); ?>images/icon/btn_horncastle_trash.png" /></a>

                                            <!-- MODAL Task Edit -->
                                            <div id="EditTask_<?php echo $task_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <form id="<?php echo "form_".$form_id++; ?>" class="form-horizontal" action="<?php echo base_url(); ?>template/template_task_update/<?php echo $task_row->id; ?>" method="POST">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h3 id="myModalLabel">Edit Task Details</h3>
                                                    </div>
                                                    <div class="modal-body">

                                                        <input type="hidden" id="task_id" name="task_id" value="<?php echo $task_row->id; ?>" readonly="">
                                                        <div class="control-group">
                                                            <label class="control-label" for="task_name">Task Name </label>
                                                            <div class="controls">
                                                                <input type="text" id="task_name" placeholder="" name="task_name" value="<?php echo $task_row->task_name; ?>">
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label" for="start_day">Task Day </label>
                                                            <div class="controls">
                                                                <select name="start_day" style="width:55%">
                                                                    <option value="">Select Day</option>
                                                                    <?php for($i = 1; $i <= $phase_row->phase_length; $i++){
                                                                        $is_selected_day = "";
                                                                        if($task_row->start_day == $i){
                                                                            $is_selected_day = " selected = 'selected' ";
                                                                        }
                                                                        echo "<option {$is_selected_day} value='{$i}'>{$i}</option>";
                                                                    }
                                                                    ?>

                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="control-group">
                                                            <label class="control-label" for="task_length">Task Length </label>
                                                            <div class="controls">
                                                                <input type="text" id="task_length" placeholder="" name="task_length" value="<?php echo $task_row->task_length; ?>">
                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label" for="category_<?php echo $task_row->id; ?>">Task Category</label>
                                                            <div class="controls">
                                                                <select name="contact_category" class="selectpicker category"  data-live-search="true" id="category_<?php echo $task_row->id; ?>" style="width:55%">
                                                                    <option value="">Select Category</option>
                                                                    <?php
                                                                    foreach ($cats as $cat) {
                                                                        ?>
                                                                        <option  <?php if($cat->id == $task_row->task_category || in_array($cat->id, array_filter(explode('|',$task_row->company_category)))){ ?> selected="selected" <?php } ?> value="<?php echo $cat->id; ?>"><?php echo $cat->category_name; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label" for="company_<?php echo $task_row->id; ?>">Company</label>
                                                            <div class="controls">
                                                                <select name="contact_company" class="selectpicker company"  data-live-search="true" id="company_<?php echo $task_row->id; ?>" style="width:55%">
                                                                    <option value="">Select Company</option>
                                                                    <?php
                                                                    foreach ($companies as $comp) {
                                                                        $c_cat = implode(' ',array_filter(explode('|',$comp->category_id)));
                                                                        ?>
                                                                        <option class="<?php echo $c_cat; ?>"  <?php if ($comp->id == $task_row->task_company || $comp->id == $task_row->task_person_responsible_company) { ?> selected="selected" <?php } ?> value="<?php echo $comp->id; ?>"><?php echo $comp->company_name; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <div class="control-group">
                                                            <label class="control-label" for="task_person_responsible">Person Responsible</label>
                                                            <div class="controls">
                                                                <select name="task_person_responsible" class="selectpicker contact" data-live-search="true" id="fuzzOptionsList_Task_<?php echo $task_row->id; ?>" style="width:55%">
                                                                    <option value="">Select User</option>
                                                                    <?php
                                                                    foreach ($contacts as $contact) {
                                                                        ?>
                                                                        <option class="<?php echo $contact->company_id; ?>" <?php if ($task_row->task_person_responsible == $contact->id) { ?> selected="selected" <?php } ?> value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name . ' ' . $contact->contact_last_name; ?></option>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </select>

                                                            </div>
                                                        </div>

                                                        <!--task #4557-->
                                                        <div class="control-group">
                                                            <label class="control-label" for="task_person_responsible">Type of Task</label>
                                                            <div class="controls">
                                                                <input type="radio" name="type_of_task" value="key_task" <?php if($task_row->type_of_task == 'key_task'){echo "checked";} ?>> Key Task
                                                                <input type="radio" name="type_of_task" value="variation" <?php if($task_row->type_of_task == 'variation'){echo "checked";} ?>> Variation
                                                                <!--<input type="radio" name="type_of_task" value="maintenance_task"  <?php /*if($task_row->type_of_task == 'maintenance_task'){echo "checked";} */?>> Maintenance Task-->
                                                            </div>
                                                        </div>


                                                        <div class="control-group">
                                                            <label class="control-label" for="inputPassword"></label>
                                                            <div class="controls">
                                                                <input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
                                                                <input type="hidden" id="phase_id" placeholder="" name="phase_id" value="<?php echo $phase_id; ?>">
                                                                <input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
                                                                <div class="save">
                                                                    <input type="submit" value="Submit" name="submit" />
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>

                                                </form>
                                            </div>
                                            <!-- MODAL Task Edit-->

                                            <!-- MODAL Task Delete-->
                                            <div id="DeleteTask_<?php echo $task_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <form id="<?php echo "form_".$form_id++; ?>" class="form-horizontal" action="<?php echo base_url(); ?>template/template_task_delete/<?php if (isset($task_row->id)) {
            echo $task_row->id;
        } ?>" method="POST">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h3 id="myModalLabel">Delete Task: <?php if (isset($task_row->task_name)) {
            echo $task_row->task_name;
        } ?></h3>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure want to delete this Task?</p>

                                                    </div>
                                                    <div class="modal-footer delete-task">
                                                        <input type="hidden" id="template_id" name="template_id" value="<?php echo $template_id; ?>">
                                                        <input type="hidden" id="phase_id" placeholder="" name="phase_id" value="<?php echo $phase_id; ?>">
                                                        <input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
                                                        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                                                        <input type="submit" value="Ok" name="submit" class="btn" />
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- MODAL Task Delete-->

                                        </li>

        <?php
    }
    ?>
                                    <li id="droppable-task-<?php echo $phase_row->id; ?>">

                                    </li>
                                    <!---MODAL Task Add--->
                                    <div id="AddTask_<?php echo $phase_id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <form id="<?php echo "form_".$form_id++; ?>" class="form-horizontal" action="<?php echo base_url(); ?>template/template_task_add" method="POST">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h3 id="myModalLabel">New Task Details</h3>
                                            </div>
                                            <div class="modal-body">

                                                <input type="hidden" id="task_id" name="task_id" value="" readonly="">

                                                <div class="control-group">
                                                    <label class="control-label" for="task_name">Task Name </label>
                                                    <div class="controls">
                                                        <input type="text" id="task_name" placeholder="" name="task_name" value="">
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="start_day">Task Day </label>
                                                    <div class="controls">
                                                        <select name="start_day" style="width:55%">
                                                            <option value="">Select Day</option>
                                                            <?php for($i = 1; $i <= $phase_row->phase_length; $i++){
                                                                echo "<option value='{$i}'>{$i}</option>";
                                                            }
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="task_length">Task Length </label>
                                                    <div class="controls">
                                                        <input type="text" id="task_length" placeholder="" name="task_length" value="">
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label" for="phase_person_responsible">Task Category</label>
                                                    <div class="controls">
                                                        <select name="contact_category" class="selectpicker category"  data-live-search="true" id="category_<?php echo $task_row->id; ?>" style="width:55%">
                                                            <option value="">Select Category</option>
                                                            <?php
                                                            foreach ($cats as $cat) {
                                                                ?>
                                                                <option value="<?php echo $cat->id; ?>"><?php echo $cat->category_name; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="company_<?php echo $phase_row->id; ?>">Company</label>
                                                    <div class="controls">
                                                        <select name="contact_company" class="selectpicker company"  data-live-search="true" id="company_<?php echo $phase_row->id; ?>" style="width:55%">
                                                            <option value="">Select Company</option>
                                                            <?php
                                                            foreach ($companies as $comp) {
                                                                $c_cat = implode(' ',array_filter(explode('|',$comp->category_id)));
                                                                ?>
                                                                <option class="<?php echo $c_cat; ?>" value="<?php echo $comp->id; ?>"><?php echo $comp->company_name; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>

                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="task_person_responsible">Person Responsible</label>
                                                    <div class="controls">
                                                        <select name="task_person_responsible" class="selectpicker contact" data-live-search="true"  id="fuzzOptionsList_Task_Add_<?php echo $task_row->id; ?>" style="width:55%">
                                                            <option value="">Select User</option>
                                                            <?php
                                                            foreach ($contacts as $contact) {
                                                                ?>
                                                                <option class="<?php echo $contact->company_id; ?>" value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name . ' ' . $contact->contact_last_name; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>

                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label" for="inputPassword"></label>
                                                    <div class="controls">
                                                        <input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
                                                        <input type="hidden" id="phase_id" placeholder="" name="phase_id" value="<?php echo $phase_id; ?>">
                                                        <input type="hidden" id="phase_no" placeholder="" name="phase_no" value="<?php echo $phase_row->phase_no; ?>">
                                                        <input type="hidden" id="task_no" placeholder="" name="task_no" value="<?php echo $task_row_count + 1; ?>">
                                                        <input type="hidden" id="task_ordering" placeholder="" name="task_ordering" value="<?php echo $task_row_count; ?>">
                                                        <input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
                                                        <div class="save">
                                                            <input type="submit" value="Submit" name="submit" />
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>

                                        </form>
                                    </div>
                                    <!-- MODAL Task Add-->

                                </ul>
                            </div>
                        </li>

    <?php
}
?>
                    <li id="droppable-phase"></li>

                    <!-- MODAL Phase -->
                    <div id="PhaseModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <form id="<?php echo "form_".$form_id++; ?>" class="form-horizontal" action="<?php echo base_url(); ?>template/template_phase_add" method="POST">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel">New Phase Details</h3>
                            </div>
                            <div class="modal-body">

                                <input type="hidden" id="phase_id" name="phase_id" value="" readonly="">

                                <div class="control-group">
                                    <label class="control-label" for="phase_name">Phase Name </label>
                                    <div class="controls">
                                        <input type="text" id="phase_name" placeholder="" name="phase_name" value="">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="phase_length">Phase Length </label>
                                    <div class="controls">
                                        <input type="text" id="phase_length" placeholder="" name="phase_length" value="">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="dependency">Phase Dependency </label>

                                    <div class="controls">
                                        <select name="dependency" style="width:55%" id="phase-dep">
                                            <option value=''>Select Dependency</option>
                                            <?php
                                            foreach ($phase_result as $phase_row2) {

                                                echo "<option {$is_selected} value='{$phase_row2->id}'>{$phase_row2->phase_name}</option>";


                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <!--task #4630. removed other fields-->
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword"></label>
                                    <div class="controls">
                                        <input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
                                        <input type="hidden" id="phase_no" placeholder="" name="phase_no" value="<?php echo $phase_row_count + 1; ?>">
                                        <input type="hidden" id="phase_ordering" placeholder="" name="phase_ordering" value="<?php echo $phase_row_count; ?>">
                                        <input type="hidden" id="url" placeholder="" name="url" value="<?php echo $this->uri->segment(2); ?>">
                                        <div class="save">
                                            <input type="submit" value="Submit" name="submit" />
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </form>
                    </div>
                    <!-- MODAL Phase -->
                    <!-- Accordion -->

                </ul>

            </div>
            <!-- Accordions -->
        </div>

        <div class="clear"></div>
    </div>

    <div class="clear"></div>

    <div class="template-footer">
        <a class="back" onclick="window.history.go(-1)">Back</a>
<?php if (!isset($template_design_update->id)) : ?>
            <a class="next" href="<?php echo base_url(); ?>template/template_list">Finish</a>
            <!-- <a class="next" href="<?php echo base_url(); ?>template/template_review/<?php echo $template_id; ?>">Next</a> -->
<?php else : ?>
            <a class="next" href="<?php echo base_url(); ?>template/template_list">Finish</a>
            <!-- <a class="next" href="<?php echo base_url(); ?>template/template_review_update/<?php echo $template_id; ?>">Next</a> -->
<?php endif; ?>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>

<!--task add modal-->
<div id="AddTask" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <form id="<?php echo "form_".$form_id++; ?>" class="form-horizontal" action="<?php echo base_url(); ?>template/template_task_add_new" method="POST">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            <!--<h4 id="myModalLabel">New Task Details</h4>-->
        </div>
        <div class="modal-body">
            <div class="control-group">
                <label class="control-label" for="task_name">Task Name </label>
                <div class="controls">
                    <input type="text" id="task_name" placeholder="" name="task_name" value="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="task_length">Phase</label>
                <div class="controls">
                    <select  class="selectpicker" id="addtask_phase_id"  name="phase_id">
                        <?php foreach($phase_result as $phase): ?>
                            <option value="<?php echo $phase->id; ?>" data-phase-length="<?php echo $phase->phase_length; ?>"><?php echo $phase->phase_name; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="start_day">Task Day </label>
                <div class="controls">
                    <select id="addtask_start_day" name="start_day" style="width: 27%">

                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="task_length">Task Length </label>
                <div class="controls">
                    <input type="text" id="task_length" placeholder="" name="task_length" value="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="phase_person_responsible">Task Category</label>
                <div class="controls">
                    <select name="contact_category" class="selectpicker category"  data-live-search="true" id="category_<?php echo $task_row->id; ?>" style="width:55%">
                        <option value="">Select Category</option>
                        <?php
                        foreach ($cats as $cat) {
                            ?>
                            <option value="<?php echo $cat->id; ?>"><?php echo $cat->category_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>

                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="company_<?php echo $phase_row->id; ?>">Company</label>
                <div class="controls">
                    <select name="contact_company" class="selectpicker company"  data-live-search="true" id="company_<?php echo $phase_row->id; ?>" style="width:55%">
                        <option value="">Select Company</option>
                        <?php
                        foreach ($companies as $comp) {
                            $c_cat = implode(' ',array_filter(explode('|',$comp->category_id)));
                            ?>
                            <option class="<?php echo $c_cat; ?>" value="<?php echo $comp->id; ?>"><?php echo $comp->company_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>

                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="task_person_responsible">Person Responsible</label>
                <div class="controls">
                    <select name="task_person_responsible" class="selectpicker contact" data-live-search="true"  id="fuzzOptionsList_Task_Add_<?php echo $task_row->id; ?>" style="width:55%">
                        <option value="">Select User</option>
                        <?php
                        foreach ($contacts as $contact) {
                            ?>
                            <option class="<?php echo $contact->company_id; ?>" value="<?php echo $contact->id; ?>"><?php echo $contact->contact_first_name . ' ' . $contact->contact_last_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>

                </div>
            </div>

            <!--task #4557-->
            <div class="control-group">
                <label class="control-label" for="task_person_responsible">Type of Task</label>
                <div class="controls">
                    <input type="radio" name="type_of_task" value="key_task"> Key Task
                    <!--<input type="radio" name="type_of_task" value="maintenance_task"> Maintenance Task-->
                    <input type="radio" name="type_of_task" value="variation"> Variation

                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="inputPassword"></label>
                <div class="controls">
                    <div class="save">
                        <input type="submit" value="Submit" name="submit" />
                    </div>
                </div>
            </div>

        </div>

    </form>
</div>

<script src="<?php echo base_url(); ?>js/chosen.jquery.js" type="text/javascript"></script>
<script type="text/javascript">
    var config = {
        '.chosen-select'           : {},
        '.chosen-select-deselect'  : {allow_single_deselect:true},
        '.chosen-select-no-single' : {disable_search_threshold:10},
        '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
        '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
        $(selector).chosen(config[selector]);
    }
</script>
<script type="text/javascript">

    $(document).ready(function () {

        $('.accordions').each(function(){

            // Set First Accordion As Active
            //$(this).find('.accordion-content').hide();
            if(!$(this).hasClass('toggles')){
                //$(this).find('.accordion:first-child').addClass('accordion-active');
                //$(this).find('.accordion:first-child .accordion-content').show();
            }

            // Set Accordion Events
            $(this).find('.accordion-header').click(function(){

                if(!$(this).parent().hasClass('accordion-active')){

                    // Close other accordions
                    if(!$(this).parent().parent().hasClass('toggles')){
                        $(this).parent().parent().find('.accordion-active').removeClass('accordion-active').find('.accordion-content').slideUp(300);
                    }

                    // Open Accordion
                    $(this).parent().addClass('accordion-active');
                    $(this).parent().find('.accordion-content').slideDown(300);

                }else{

                    // Close Accordion
                    $(this).parent().removeClass('accordion-active');
                    $(this).parent().find('.accordion-content').slideUp(300);

                }

            });

        });
        var phase_length = $("#addtask_phase_id").find('option:first').attr('data-phase-length');
        $("#addtask_start_day").empty();
        for(var i = 1; i <= phase_length; i++){
            $("<option value='"+i+"'>"+i+"</option>").appendTo($("#addtask_start_day"));

        }
        $("#addtask_phase_id").change(function(){
            var phase_length = $(this).find('option:checked').attr('data-phase-length');
            $("#addtask_start_day").empty();
            for(var i = 1; i <= phase_length; i++){
                $("<option value='"+i+"'>"+i+"</option>").appendTo($("#addtask_start_day"));

            }
        });

        /*task #4200*/
        $(".task-dep").each(function(){
            var phase_id = $(this).attr('id').replace('task-dep','phase-dep');
            $(this).chained($("#"+phase_id));
        });

    });
</script>