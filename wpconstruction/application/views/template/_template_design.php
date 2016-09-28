<script src="<?php echo base_url(); ?>js/fuse.min.js"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>js/fuzzyDropdown.min.js"></script>
<link rel="stylesheet" href="<?php echo base_url(); ?>css/fuzzyDropdown.css">
<script type="text/javascript" src="<?php echo base_url(); ?>js/fuzzyDropdown.min.js"></script>
<style>

    #fuzzNameContainer{ margin-left:33%; width:55%;border-radius: 5px;}
    #fuzzDropdownContainer{margin-left:33%; width:55%;border-radius: 5px;}
    .fuzzMagicBox{width:99%!IMPORTANT;}

</style>


<script>

    window.Url = "<?php print base_url(); ?>";
    jQuery(document).ready(function() {

        $('input.submit').change(function(e){
            e.preventDefault();
            $('li.accordion').removeClass('accordion-active'); // removes all highlights from tr's
            $('li.accordion').addClass('accordion-active'); // adds the highlight to this row
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

<div class="clear"></div>

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
                <a href="#PhaseModal" role="button" data-toggle="modal" class="plus-icon"><img src="<?php print base_url(); ?>images/plus-icon1.png" /></a>
                <ul class="drag-phase-task">
                    <li id="draggable-task">Add Task</li>
                    <li id="draggable-phase">Add Phase</li>
                </ul>
            </div>
            <div class="clear"></div>
            <!-- Accordions -->
            <div class="accordions-body">
                <ul id="sortable-phase" class="accordions">

                    <!-- Accordion -->
                    <?php
                    $url_phase_id = $this->uri->segment(4);
                    $phase_query = $this->db->query("SELECT * FROM construction_template_phase where template_id=$template_id ORDER BY ordering ASC");
                    $phase_result = $phase_query->result();
                    $phase_row_count = count($phase_result);
                    $first_phase = "";
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
                                        <form class="form-horizontal" action="<?php echo base_url(); ?>template/template_phase_update/<?php echo $phase_row->id; ?>" method="POST">
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
                                                    <select name="dependency" style="width:55%">
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
                                                <?php //endif; ?>                
                                                <div class="control-group">
                                                    <label class="control-label" for="phase_person_responsible">Person Responsible</label>
                                                    <div class="controls">
                                                        <select name="phase_person_responsible" id="fuzzOptionsList_Phase_<?php echo $phase_row->id; ?>" style="width:55%">
                                                            <option value="">Select User</option>
                                                            <?php
                                                            $this->db->where('status', '1');
                                                            $results = $this->db->get('contact_contact_list')->result();
                                                            foreach ($results as $result) {
                                                                ?>
                                                                <option  <?php if ($result->id == $phase_row->phase_person_responsible) { ?> selected="selected" <?php } ?> value="<?php echo $result->id; ?>"><?php echo $result->contact_first_name . ' ' . $result->contact_last_name; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>

                                                        <div id="fuzzSearch_Phase_<?php echo $phase_row->id; ?>">
                                                            <div id="fuzzNameContainer">
                                                                <span class="fuzzName"></span>
                                                                <span class="fuzzArrow"></span>
                                                            </div>
                                                            <div id="fuzzDropdownContainer">
                                                                <input type="text" value="" class="fuzzMagicBox" placeholder="search.." />
                                                                <span class="fuzzSearchIcon"></span>
                                                                <ul id="fuzzResults">
                                                                </ul>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
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


                                    <script>
                                        $(function() {
                                            $('#fuzzOptionsList_Phase_<?php echo $phase_row->id; ?>').fuzzyDropdown({
                                                mainContainer: '#fuzzSearch_Phase_<?php echo $phase_row->id; ?>',
                                                arrowUpClass: 'fuzzArrowUp',
                                                selectedClass: 'selected',
                                                enableBrowserDefaultScroll: true
                                            });
                                        });
                                    </script>



                                    <!-- MODAL Phase Delete-->
                                    <div id="DeletePhase_<?php echo $phase_row->id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <form class="form-horizontal" action="<?php echo base_url(); ?>template/template_phase_delete/<?php if (isset($phase_row->id)) {
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
    $task_query = $this->db->query("SELECT * FROM construction_template_task where template_id=$template_id and phase_id=$phase_id ORDER BY ordering ASC");
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
                                                <form class="form-horizontal" action="<?php echo base_url(); ?>template/template_task_update/<?php echo $task_row->id; ?>" method="POST">
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
                                                            <label class="control-label" for="task_person_responsible">Person Responsible</label>
                                                            <div class="controls">
                                                                <select name="task_person_responsible" id="fuzzOptionsList_Task_<?php echo $task_row->id; ?>" style="width:55%">
                                                                    <option value="">Select User</option>
        <?php
        $this->db->where('status', '1');
        $results = $this->db->get('contact_contact_list')->result();
        foreach ($results as $result) {
            ?>
                                                                        <option <?php if ($task_row->task_person_responsible == $result->id) { ?> selected="selected" <?php } ?> value="<?php echo $result->id; ?>"><?php echo $result->contact_first_name . ' ' . $result->contact_last_name; ?></option>
            <?php
        }
        ?>
                                                                </select>

                                                                <div id="fuzzSearch_Task_<?php echo $task_row->id; ?>">
                                                                    <div id="fuzzNameContainer">
                                                                        <span class="fuzzName"></span>
                                                                        <span class="fuzzArrow"></span>
                                                                    </div>
                                                                    <div id="fuzzDropdownContainer">
                                                                        <input type="text" value="" class="fuzzMagicBox" placeholder="search.." />
                                                                        <span class="fuzzSearchIcon"></span>
                                                                        <ul id="fuzzResults">
                                                                        </ul>
                                                                    </div>
                                                                </div>	

                                                            </div>
                                                        </div>


                                                        <div class="control-group">
                                                            <label class="control-label" for="inputPassword"></label>
                                                            <div class="controls">
                                                                <input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
                                                                <input type="hidden" id="phase_id" placeholder="" name="phase_id" value="<?