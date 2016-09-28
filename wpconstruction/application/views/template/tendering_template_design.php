<style>

    #fuzzNameContainer{ margin-left:33%; width:55%;border-radius: 5px;}
    #fuzzDropdownContainer{margin-left:33%; width:55%;border-radius: 5px;}
    .fuzzMagicBox{width:99%!IMPORTANT;}

    .modal .controls select {
        height: 31px;
    }
    .accordions{
        border-top: none;
    }
    .btn-default{
        border: 2px solid #eee;
        font-size: 11px;
    }
    .template-design .template-task-delete {
        float: right;
        margin-right: 0;
    }
    .modal .controls .bs-searchbox input[type="text"] {
        border: 2px solid #eee;
        border-radius: 5px;
        line-height: 18px;
        padding: 3px 8px;
        width: 100%;
    }

    .accordion .bootstrap-select.btn-group:not(.input-group-btn), .bootstrap-select.btn-group[class*="col-"]{
        float: right;
    }
    .accordion button.dropdown-toggle{
        z-index: 10;
    }
    .accordion .dropdown-menu.open{
        z-index: 11;
    }
    .accordion {
        border-bottom: 1px solid gray;
        border-collapse: collapse;
        clear: both;
        padding: 6px 0;
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

        $(".item_group").change(function(){
            $.ajax(window.Url+'template/update_item_group/'+$(this).attr('data-item')+'/'+$(this).val())
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
                        url: window.Url + 'template/tendering_template_item_ordering',
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
                    .html( '<a href="#PhaseModal" role="button" data-toggle="modal" class="edit">+Add New Item</a>' );
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
                    <li id="draggable-task">Add Contact</li>
                    <!--<li id="draggable-phase">Add Item</li>-->
                    <li id="draggable-phase">Load Key Tasks</li><!--task #4564-->
                </ul>
            </div>
            <div class="clear"></div>
            <!-- Accordions -->
            <div class="accordions-body">
                <ul id="sortable-phase" class="accordions">

                    <!-- Accordion -->
                    <?php
                    foreach ($items as $id => $item) {
                        ?>

                        <script>
                            jQuery(document).ready(function() {

                                $( "#draggable-task" ).draggable({
                                    //connectToSortable: "#sortable-phase",
                                    helper: "clone",
                                    revert: "invalid"
                                });

                                $( "#listItemPhase_<?php echo $id; ?>" ).droppable({

                                    drop: function( event, ui ) {
                                        if (ui.draggable.is('#draggable-task')) {
                                            $("#listItemPhase_<?php echo $id; ?> .accordion-content").css("display","block");
                                            $( this )
                                            .addClass( "accordion-active" )
                                            .find( "li#droppable-task-<?php echo $id; ?>" )
                                            .html( '<a href="#AddTask_<?php echo $id; ?>" role="button" data-toggle="modal" class="edit">+ Add New Contact</a>' );
                                        }
                                    }
                                });

                            });
                        </script>

                        <li id="listItemPhase_<?php echo $id; ?>" class="accordion <?php if ($_GET['iid'] && $id == $_GET['iid']) {
                        echo 'accordion-active';
                    } ?>" >
                            <!--task #4565-->
                            <select name="group" data-item="<?php echo $id; ?>" class="selectpicker item_group" style="float: right">
                                <option value="" style="">Select group</option>
                                <?php
                                foreach ($groups as $group) {
                                    ?>
                                    <option  <?php if ($group->id == $item['group_id']) { ?> selected="selected" <?php } ?> value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>
                                    <?php
                                }
                                ?>
                            </select>
                            <div class="accordion-header">

                                <div class="accordion-icon"></div>
                                <h6>

                                    <?php echo $item['name']; ?>
                                    <a href="#EditPhase_<?php echo $id; ?>" title="Item Edit" role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16" src="<?php print base_url(); ?>images/icon/edit_pen.png" /></a>
                                    <a href="#DeletePhase_<?php echo $id; ?>" title="Item Delete" role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16" src="<?php print base_url(); ?>images/icon/btn_horncastle_trash.png" /></a>

                                    <!-- MODAL Phase Edit -->
                                    <div id="EditPhase_<?php echo $id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <form class="form-horizontal" action="<?php echo base_url(); ?>template/tendering_template_item_update/<?php echo $id; ?>" method="POST">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h3 id="myModalLabel">Edit Item</h3>
                                            </div>
                                            <div class="modal-body">

                                                <input type="hidden" id="phase_id" name="template_id" value="<?php echo $template_id; ?>" readonly="">
                                                <!--<div class="control-group">
                                                    <label class="control-label" for="phase_name">Item Name </label>
                                                    <div class="controls">
                                                        <input type="text" id="name" placeholder="" name="name" value="<?php /*echo $item['name']; */?>">
                                                    </div>
                                                </div>-->
                                                <!--task #4558-->
                                                <div class="control-group">
                                                    <label class="control-label" for="start_day">Select Template</label>
                                                    <div class="controls">
                                                        <select class="selectpicker" name="program_template_id" data-live-search="true" >
                                                            <option value="" style="display: none"></option>
                                                            <?php foreach($templates as $template){
                                                                $selected = ($item['construction_template_id'] == $template['id']) ? " selected ":"";
                                                                echo "<option value='{$template['id']}' {$selected}>{$template['name']}</option>";
                                                            }
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="start_day">Select Task</label>
                                                    <div class="controls">
                                                        <select class="selectpicker key_task_id" name="key_task_id" data-live-search="false" >
                                                            <!--<option value="" style="display: none"></option>-->
                                                            <?php foreach($templates[$item['construction_template_id']]['tasks'] as $task){
                                                                $selected = ($item['construction_template_task_id'] == $task['id']) ? " selected ":"";
                                                                echo "<option value='{$task['id']}' {$selected}>{$task['name']}</option>";
                                                            }
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="phase_person_responsible">Select Group</label>
                                                    <div class="controls">
                                                        <select name="group_id" class="selectpicker" style="width:55%">
                                                            <option value="" style="display: none"></option>
                                                            <?php
                                                            foreach ($groups as $group) {
                                                                ?>
                                                                <option  <?php if ($group->id == $item['group_id']) { ?> selected="selected" <?php } ?> value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>
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
                                    <div id="DeletePhase_<?php echo $id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <form class="form-horizontal" action="<?php echo base_url(); ?>template/tendering_template_item_delete/<?php if (isset($id)) {
                                                                echo $id;
                                                            } ?>" method="POST">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h3 id="myModalLabel">Delete Item: <?php if (isset($item['name'])) {
                                                                echo $item['name'];
                                                            } ?></h3>
                                            </div>
                                            <div class="modal-body">
                                                <p>Are you sure want to delete this Item?</p>

                                            </div>
                                            <div class="modal-footer delete-task">
                                                <input type="hidden" id="template_id" name="template_id" value="<?php echo $template_id; ?>">
                                                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                                                <input type="submit" value="Ok" name="submit" class="btn" />
                                            </div>
                                        </form>
                                    </div>
                                    <!-- MODAL Phase Delete-->

                                </h6>

                            </div>

                            <div class="accordion-content"  <?php if ($_GET['iid'] && $_GET['iid'] == $id) {
                                                                echo 'style="display: block;"';
                                                            } else {
                                                                echo 'style="display: none;"';
                                                            } ?>>

                                <!--<script>
                                    jQuery(document).ready(function() {
                                        if (jQuery("#sortable-task-<?php /*echo $phase_row->id; */?>").length){
                                            $( "#sortable-task-<?php /*echo $phase_row->id; */?>" ).sortable({
                                                update : function () {
                                                    var order = $('#sortable-task-<?php /*echo $phase_row->id; */?>').sortable('serialize');
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
                                            $( "#sortable-task-<?php /*echo $phase_row->id; */?>" ).disableSelection();
                                        }
                                    });
                                </script>-->
                                <ul id="sortable-task-<?php echo $phase_row->id; ?>" class="">
                                <?php if($item['contacts']): ?>

                                <?php
                                foreach ($item['contacts'] as $cid => $contact):
                                    ?>
                                        <li id="listItemTask_<?php echo $cid; ?>">

                                            <?php echo $contact; ?>
                                            <a href="#DeleteTask_<?php echo $cid; ?>" title="Contact Delete" role="button" data-toggle="modal" class="template-task-delete"><img width="16" height="16" src="<?php print base_url(); ?>images/icon/btn_horncastle_trash.png" /></a>

                                            <!-- MODAL Contact Delete-->
                                            <div id="DeleteTask_<?php echo $cid; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                <form class="form-horizontal" action="<?php echo base_url(); ?>template/tendering_template_contact_delete/<?php if (isset($cid)) {
                                                        echo $cid;
                                                    } ?>" method="POST">
                                                    <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                        <h3 id="myModalLabel">Delete Contact: <?php echo $contact; ?></h3>
                                                    </div>
                                                    <div class="modal-body">
                                                        <p>Are you sure want to delete this Contact?</p>

                                                    </div>
                                                    <div class="modal-footer delete-task">
                                                        <input type="hidden" id="phase_id" placeholder="" name="id" value="<?php echo $cid; ?>">
                                                        <input type="hidden" id="phase_id" placeholder="" name="item_id" value="<?php echo $id; ?>">
                                                        <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                                                        <input type="submit" value="Ok" name="submit" class="btn" />
                                                    </div>
                                                </form>
                                            </div>
                                            <!-- MODAL Task Delete-->

                                        </li>

                                        <?php
                                    endforeach;
                                endif;
                                    ?>
                                    <li id="droppable-task-<?php echo $id; ?>">

                                    </li>
                                    <!---MODAL Task Add--->
                                    <div id="AddTask_<?php echo $id; ?>" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <form class="form-horizontal" action="<?php echo base_url(); ?>template/tendering_template_contact_add" method="POST">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <h3 id="myModalLabel">New Contact</h3>
                                            </div>
                                            <div class="modal-body">

                                                <input type="hidden" id="task_id" name="item_id" value="<?php echo $id; ?>" readonly="">
                                                <!--task #4556-->
                                                <div class="control-group">
                                                    <label class="control-label" for="start_day">Select Category</label>
                                                    <div class="controls">
                                                        <select class="selectpicker" name="contact_category_id" data-live-search="true" >
                                                            <option value="" style="display: none"></option>
                                                            <?php foreach($categories as $category){
                                                                echo "<option value='{$category['id']}'>{$category['name']}</option>";
                                                            }
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="start_day">Select Company</label>
                                                    <div class="controls">
                                                        <select class="selectpicker" name="contact_company_id" data-live-search="true" >
                                                            <!--<option value="" style="display: none"></option>-->
                                                            <?php /*foreach($companies as $company){
                                                                echo "<option value='{$company['id']}'>{$company['name']}</option>";
                                                            }*/
                                                            ?>

                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="start_day">Select Contact</label>
                                                    <div class="controls">
                                                        <select class="selectpicker" name="contact_contact_list_id">
                                                            <option value="" style="display: none"></option>

                                                        </select>
                                                    </div>
                                                </div>


                                                <div class="control-group">
                                                    <label class="control-label" for="inputPassword"></label>
                                                    <div class="controls">
                                                        <input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
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
                        <!--<form class="form-horizontal" action="<?php /*echo base_url(); */?>template/tendering_template_item_add" method="POST">-->
                        <!--task #4564-->
                        <form class="form-horizontal" action="<?php echo base_url(); ?>template/tendering_template_load_key_tasks" method="POST">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                <h3 id="myModalLabel">New Item</h3>
                            </div>
                            <div class="modal-body">

                                <input type="hidden" id="phase_id" name="template_id" value="<?php echo $template_id; ?>" readonly="">

                                <!--<div class="control-group">
                                    <label class="control-label" for="phase_name">Item Name </label>
                                    <div class="controls">
                                        <input type="text" id="phase_name" placeholder="" name="name" value="">
                                    </div>
                                </div>-->
                                <!--task #4558-->
                                <div class="control-group">
                                    <div class="controls">
                                        <label class="control-label" for="start_day" style="margin-top: 4px;">Select Template</label>
                                        <select class="selectpicker" name="program_template_id" data-live-search="true" id="select_template">
                                            <option value="" style="display: none"></option>
                                            <?php foreach($templates as $template){
                                                echo "<option value='{$template['id']}'>{$template['name']}</option>";
                                            }
                                            ?>

                                        </select>
                                    </div>
                                </div>
                                <div class="control-group" style="/*display: none*/ /*task #4564*/ /*task #4666*/">
                                    <label class="control-label" for="start_day">Select Task</label>
                                    <div class="controls">
                                        <select class="selectpicker key_task_id" name="key_task_id[]" data-live-search="false" multiple id="select_template_task">
                                            <!--<option value="" style="display: none"></option>-->
                                            <?php /*foreach($templates as $template){
                                                foreach($template['tasks'] as $task):
                                            */?><!--
                                                    <option value="<?php /*echo $task['id']; */?>" selected><?php /*echo $task['name']; */?></option>
                                            --><?php
/*                                                endforeach;
                                            }
                                            */?>

                                        </select>
                                    </div>
                                </div>
                                <div class="control-group" style="display: none /*task #4564*/">
                                    <label class="control-label" for="dependency">Group </label>
                                    <div class="controls">
                                    <select name="group_id" style="width:55%" class="selectpicker">
                                        <option value="" style="display: none"></option>
                                        <?php
                                        foreach ($groups as $group) {
                                            echo "<option  value='{$group->id}'>{$group->name}</option>";
                                        }
                                        ?>
                                    </select>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword"></label>
                                    <div class="controls">
                                        <input type="hidden" id="template_id" placeholder="" name="template_id" value="<?php echo $template_id; ?>">
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
            <a class="next" href="<?php echo base_url(); ?>template/tendering_template_list">Finish</a>
            <!-- <a class="next" href="<?php echo base_url(); ?>template/template_review/<?php echo $template_id; ?>">Next</a> -->
<?php else : ?>
            <a class="next" href="<?php echo base_url(); ?>template/tendering_template_list">Finish</a>
<?php endif; ?>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
<script type="text/javascript">

    var companies = <?php echo json_encode($companies); ?>;
    var categories = <?php echo json_encode($categories); ?>; //task #4556
    var templates = <?php echo json_encode($templates); ?>; //task #4558

    $(document).ready(function () {

        $('.accordions').each(function(){
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

        /*task #4556*/
        $("select[name='contact_category_id']").change(function(){
            var category = $(this).val();
            var company_select = $("select[name='contact_company_id']");
            company_select.empty();
            if(categories[category].companies.length == 0){

                company_select.append("<option value='' selected>No company in this category.</option>");
            }else{

                company_select.append("<option value='' selected>select a company</option>");
            }
            for(var i in categories[category].companies){
                company_select.append("<option value='"+categories[category].companies[i].id+"' >"+categories[category].companies[i].name+"</option>");
            }
            company_select.selectpicker('refresh');

        });

        /*task #4558*/
        $("select[name='program_template_id']").change(function(){
            var template = $(this).val();
            var task_select = $(this).parents("form").find("select.key_task_id");
            task_select.empty();
            if(templates[template].tasks.length == 0){

                task_select.append("<option value='' selected>No key task in this template.</option>");
            }else{

                for(var i in templates[template].tasks){
                    task_select.append("<option value='"+templates[template].tasks[i].id+"' selected>"+templates[template].tasks[i].name+"</option>");
                }
            }

            task_select.selectpicker('refresh');

        });

        $("select[name='contact_company_id']").change(function(){
            var company = $(this).val();
            var contact_select = $("select[name='contact_contact_list_id']");
            contact_select.empty();
            if(companies[company].contacts.length == 0){

                contact_select.append("<option value='' selected>No contact in this company.</option>");
            }else{

                contact_select.append("<option value='' selected></option>");
            }
            for(var i in companies[company].contacts){
                contact_select.append("<option value='"+companies[company].contacts[i].id+"' >"+companies[company].contacts[i].name+"</option>");
            }
            contact_select.selectpicker('refresh');

        })
    });
</script>