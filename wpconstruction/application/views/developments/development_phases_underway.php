<?php
$user = $this->session->userdata('user');
$wp_company_id = $user->company_id;
$does_any_phase_have_dependency = false;
$this->db->select("wp_company.*,wp_file.*");
$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
$this->db->where('wp_company.id', $wp_company_id);
$wpdata = $this->db->get('wp_company')->row();

$main_url = 'http://' . $wpdata->url;
$colour_one = $wpdata->colour_one;
$colour_two = $wpdata->colour_two;

/*task #4556*/
$this->db->where('wp_company_id', $wp_company_id);
$cats = $this->db->get('contact_category')->result();

$this->db->where('wp_company_id', $wp_company_id);
$companies = $this->db->get('contact_company')->result();

$this->db->where('wp_company_id', $wp_company_id);
$this->db->where('status', '1');
$contacts = $this->db->get('contact_contact_list')->result();

$form_id = 1;
$select_id = 1;

?>
<script src="<?php echo base_url(); ?>js/jquery.chained.js"></script>
<script src="<?php echo base_url(); ?>js/fuse.min.js"></script>
<!--for tooltip and inline edit of task notes-->
<link rel="stylesheet" href="<?php echo base_url(); ?>css/jquery.tooltip/jquery.tooltip.css">
<link rel="stylesheet" href="<?php echo base_url(); ?>css/fuzzyDropdown.css">
<style>
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

    .contractor, .builder {
        visibility: hidden;
    }

    .statusCol.builder {
        visibility: visible;
    }

    .own_task {
        visibility: visible;
    }

    .drag-phase-task .contractor {
        display: none;
    }

    .dialog-phase-edit {
        border: 1px solid <?php echo $colour_one;?>;
        padding: 0;
    }

    .dialog-phase-edit .ui-dialog-titlebar {
        background-color: <?php echo $colour_one;?>;
        color: white;
        font-size: 20px;
    }

    .dialog-phase-edit .ui-dialog-titlebar {
        background-color: <?php echo $colour_one;?>;
        color: white;
        font-size: 20px;
    }

    .dialog-phase-edit .ui-dialog-titlebar-close {
        height: 20px;
        margin: -10px 0 0;
        padding: 1px;
        position: absolute;
        right: 0.3em;
        top: 42%;
        width: 20px;
    }

    .dialog-phase-edit .ui-dialog-buttonset button span {
        background-color: <?php echo $colour_one;?>;
        color: white;
    }

    #dialog-confirm-phase-update thead {
        background-color: unset;
    }

    #dialog-confirm-phase-update th {
        border: 1px solid #eee;
        text-align: center;
    }

    #dialog-confirm-phase-update td {
        border: 1px solid #eee;
        padding-left: 7px;
    }
    .select2-container{
        z-index: 1060;
        border: 2px solid #eee;
    }
    .bootstrap-select:not([class*="col-"]):not([class*="form-control"]):not(.input-group-btn) {
        width: 100%;
    }
</style>
<script type="text/javascript" src="<?php echo base_url(); ?>js/jquery.tooltip.min.js"></script>
<script>
    var pre_construction_url = "";
    <?php
        $uri_segments = $this->uri->segment_array();
        $is_pre_construction = $uri_segments[3];

    ?>
    pre_construction_url = "<?php echo $_GET['cp']; ?>";

    $(document).ready(function () {

        $(".phase_note").tooltip();

        /*updating phase task notes*/
        var base_url = "<?php echo base_url();?>";

        $(".phase_task_note").blur(function () {
            var el = $(this);
            $(this).nextAll(".loading:first").css('visibility', 'visible');
            el.prop('disabled', true);
            $("#task_form_" + el.attr('data-id') + " textarea[name=note]").val(el.val());
            var params = $("#task_form_" + el.attr('data-id')).serialize() + "&submit=1";
            $.ajax(base_url + 'constructions/development_task_update/' + el.attr('data-id'), {
                type: 'POST',
                data: params,
                success: function (data) {
                    el.nextAll(".loading:first").css('visibility', 'hidden');
                    el.prop('disabled', false)
                }
            })
        });
    })
</script>
<style>
    .loading {
        visibility: hidden;
    }

    .phase_task_note {
        background: transparent none repeat scroll 0 0;
        border: medium none;
    }

    .phase_task_note:focus {
        border: 1px solid darkslategray;
    }
</style>
<!--------------->
<script type="text/javascript" src="<?php echo base_url(); ?>js/fuzzy-dropdown.min.js"></script>
<?php
if ($user_app_role == 'manager'):
    ?>
    <div style="margin-bottom: 20px">
        <form id="frmRemoveDependency" action="<?php echo base_url() . 'constructions/remove_dependency'; ?>"
              method="post" style="display: none">
            <input type="hidden" name="development_id" value="<?php echo $development_id; ?>">
            <input type="hidden" name="construction_phase" value="<?php echo $_GET['cp']; ?>">
            <input type="submit" value="Remove All Dependency" class="btn" style="float: right">
        </form>
        <div style="clear:both;"></div>
    </div>
    <div id="dialog-confirm" title="Remove Dependency" style="display: none">
        <p style="text-align: justify"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>Are
            you sure you want to remove dependencies for all phases?</p>
    </div>
    <?php
endif;
?>
<div id="stage_phase_task">
    <div class="task-phase-add">
        <ul class="drag-phase-task">
            <!--<li id="draggable-task"><a style="color:#000;" href="#AddTask_<?php if ($this->uri->segment(4)) {
                echo $this->uri->segment(4);
            } else {
                echo $development_phase_info[0]->id;
            } ?>" id="phaseid" role="button" data-toggle="modal">Add Task +</a></li>--> 
            <li id="draggable-task" class=" <?php echo $user_app_role; ?>"><a style="color:#000;" href="#AddTask" id=""
                                                                              role="button" data-toggle="modal">Add Task
                    +</a></li>
            <li id="draggable-phase" class=" <?php echo $user_app_role; ?>"><a style="color:#000;" href="#AddPhase"
                                                                               title="Phase Add" role="button"
                                                                               data-toggle="modal">Add Phase +</a></li>
            <li style="float:right">
                <span
                    style="height:20px; width:20px; border-radius:15px; background-color:red">&nbsp;&nbsp;&nbsp;&nbsp;</span>:
                Overdue,
                <span style="height:20px; width:20px; border-radius:15px; background-color:grey">&nbsp;&nbsp;&nbsp;&nbsp;</span>:
                Pending,
                <span style="height:20px; width:20px; border-radius:15px; background-color:yellow">&nbsp;&nbsp;&nbsp;&nbsp;</span>:
                Underway,
                <span style="height:20px; width:20px; border-radius:15px; background-color:green">&nbsp;&nbsp;&nbsp;&nbsp;</span>:
                Complete. &nbsp;&nbsp;&nbsp;&nbsp;</li>
        </ul>
        <div style="clear:both;"></div>
    </div>
    <div style="clear:both;"></div>

    <div id="underway_header">
       
        <div class="uhead" style="width:18%">Phase Name</div>
        <div class="uhead" style="width:10%">Start Date</div>
        <div class="uhead" style="width:10%">Finish Date</div>
        <div class="uhead" style="width:15%">Notes</div>
        <div class="uhead" style="width:15%">Person Responsible</div>
        <div class="uhead" style="width:10%">Dependency</div>
        <div class="uhead" style="width:3%">Status</div>
        <div class="uhead " style="width:10%;text-align: right;">Complete</div>
        <div class="uhead <?php echo $user_app_role; ?>" style="width:7%">Edit/Delete</div>
    </div>

    <script>
        function TransferPhaseId(pid) {
            //document.getElementById('phaseid').href='#AddTask_'+pid;
        }
    </script>

    <script>

        window.Url = "<?php print base_url(); ?>";
        function change_phase_status(development_id, stage_no, phase_id, checked) {

            if (checked == true) {
                status = 1;
            }
            else {
                status = 0;
            }
            $.ajax({
                url: window.Url + 'constructions/update_phase_status/' + phase_id + '/' + status,
                type: 'GET',
                success: function (data) {
                    //location.reload();
                    newurl = window.Url + 'constructions/phases_underway/' + pre_construction_url + development_id + '/' + stage_no;
                    window.location = newurl;
                }

            });

        }


        function change_development_phase_task_status(development_id, phase_id, task_id, checked) {

            if (checked == true) {
                status = 1;
            }
            else {
                status = 0;
            }
            var checkbox = $("#phase_status_" + task_id);
            checkbox.prop('disabled', true);
            var loader = checkbox.next();
            var img = '<img style="margin:0 4px 0 0px" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" />';
            loader.css('visibility', 'visible');

            $.ajax({
                url: window.Url + 'constructions/update_development_phase_task_status/' + task_id + '/' + status + '/' + phase_id,
                type: 'GET',
                success: function (data) {
                    //location.reload();
                    newurl = window.Url + 'constructions/phases_underway/' + pre_construction_url + development_id + '/' + phase_id;
                    //window.location = newurl;
                    checkbox.parent().siblings('.status').find('div').css('background-color', data);
                    loader.css('visibility', 'hidden');
                    checkbox.prop('disabled', false);
                    if (data == 'green') {
                        checkbox.parent().siblings('.task_name').prepend(img);
                    } else {
                        checkbox.parent().siblings('.task_name').find('img').remove();
                    }
                    var phase_status_span = $("#listItemPhase_" + phase_id).find('.phase_status');
                    phase_status_span.load(phase_status_span.attr('data-src'));
                }

            });

        }


        function change_all_phase_task_status(development_id, phase_id, checked) {
            if (checked == true) {
                status = 1;
            }
            else {
                status = 0;
            }

            var checkbox = $("#listItemPhase_" + phase_id + " #all_phase_task");
            checkbox.prop('disabled', true);
            var loader = checkbox.next();
            var img = '<img style="margin:0 4px 0 0px" width="22" height="22" src="<?php echo base_url();?>images/icon/status_complate.png" />';
            loader.css('visibility', 'visible');

            $.ajax({
                url: window.Url + 'constructions/update_all_phase_task_status/' + development_id + '/' + phase_id + '/' + status,
                type: 'GET',
                success: function (data) {
                    //location.reload();
                    newurl = window.Url + 'constructions/phases_underway/' + pre_construction_url + development_id + '/' + phase_id;
                    //window.location = newurl;
                    loader.css('visibility', 'hidden');
                    checkbox.prop('disabled', false);
                    var phase_status_span = $("#listItemPhase_" + phase_id).find('.phase_status');
                    phase_status_span.load(phase_status_span.attr('data-src'));
                }

            });
        }

        function change_all_stage_phase_status(development_id, stage_no, checked) {

            if (checked == true) {
                status = 1;
            }
            else {
                status = 0;
            }

            $.ajax({
                url: window.Url + 'constructions/update_all_stage_phase_status/' + development_id + '/' + stage_no + '/' + status,
                type: 'GET',
                success: function (data) {
                    //location.reload();
                    newurl = window.Url + 'constructions/phases_underway/' + pre_construction_url + development_id + '/' + stage_no;
                    window.location = newurl;
                }

            });

        }

    </script>

    <script>
        jQuery(document).ready(function () {
            if (jQuery('.sortable-phase').length) {
                $(".sortable-phase").sortable({

                    update: function () {
                        var order = $('.sortable-phase').sortable('serialize');
                        $.ajax({
                            url: window.Url + 'admindevelopment/development_phase_ordering',
                            type: 'POST',
                            data: order,
                            success: function (data) {

                            }

                        });
                    }
                });
                $(".sortable-phase").disableSelection();
            }


        });
    </script>

    <script>

        jQuery(document).ready(function () {
            $('.dependency').change(function () {
                var id = $(this).val();
                var overlay = jQuery('<div id="overlay"> </div>');
                overlay.appendTo(document.body);
                $.ajax({
                    url: window.Url + 'constructions/development_phase_dependency_name_load/' + id,
                    dataType: 'json',
                    success: function (data) {
                        $('.in #dependency_name').empty();
                        $('.in #dependency_name').val(data.phase_name);
                        $('.in input[name=planned_start_date]').val(data.start_date);
                        overlay.remove();
                    },
                });
            });

        });

    </script>


    <?php

    $stages = $stages_no[0]->number_of_stages;
    $development_id = $stages_no[0]->id;
    $now = date('Y-m-d');
    $today_time = strtotime($now);
    ?>

    <ul id="accordions" class="accordions toggles sortable-phase">

        <?php

        //print_r($development_phase_info);
        $phase_number = count($development_phase_info);

        for ($p = 0; $p < $phase_number; $p++) {
            if ($development_phase_info[$p]->planned_finished_date > '0000-00-00') {
                $planned_finished_date = date('d-m-Y', strtotime($development_phase_info[$p]->planned_finished_date));
            } else if ($development_phase_info[$p]->planned_start_date == '0000-00-00') {
                $planned_finished_date = '00-00-0000';
            } else {
                $created_date = date_create($development_phase_info[$p]->planned_start_date);
                $str = '5 days';
                $pcdate = date_add($created_date, date_interval_create_from_date_string($str));
                $planned_finished_date = date_format($pcdate, 'd-m-Y');
            }

            $ci =& get_instance();
            $ci->load->model('developments_model');
            $all_phase_task = $ci->developments_model->get_all_development_phase_status($development_id, $development_phase_info[$p]->id)->result();
            ?>
            <li onclick="TransferPhaseId(<?php echo $development_phase_info[$p]->id; ?>);"
                id="listItemPhase_<?php echo $development_phase_info[$p]->id; ?>"
                class="accordion <?php $ph = $this->uri->segment(4);
                if ($development_phase_info[$p]->id == $ph) {
                    echo 'accordion-active';
                } ?>">

                <div class="accordion-header">
                    
                    <div class="uncol1" style="width:18%;">
					<span class="phase_status"
                          data-src="<?php echo site_url('constructions/phase_status_html/' . $development_id . '/' . $development_phase_info[$p]->id . '/'); ?>">
					<?php if ($all_phase_task[0]->all_task_status == 0 && $development_phase_info[$p]->phase_status == 1) { ?>
                        * <img style="margin:0 4px 0 0px" width="22" height="22"
                               src="<?php echo base_url(); ?>images/icon/status_complate.png"/>
                    <?php } ?>
                        <?php if (isset($all_phase_task[0]->all_task_status) && $all_phase_task[0]->all_task_status == 1) { ?>
                            <img style="margin:0 4px 0 0px" width="22" height="22"
                                 src="<?php echo base_url(); ?>images/icon/status_complate.png"/>
                        <?php } ?>
					</span>
                        <?php echo $development_phase_info[$p]->phase_name; ?>


                    </div>
                    <div class="uncol1"
                         style="width:10%;"><?php if ($development_phase_info[$p]->planned_start_date > '0000-00-00') {
                            echo date('d-m-Y', strtotime($development_phase_info[$p]->planned_start_date));
                        } else {
                            echo '00-00-0000';
                        } ?></div>
                    <div class="uncol1" style="width:10%;"><?php echo $planned_finished_date; ?></div>
                    <div class="uncol1" style="width:15%; font-size: 80%">
                        <?php
                        if ($development_phase_info[$p]->note) {
                            if (strlen($development_phase_info[$p]->note) > 32) {
                                echo
                                    "<span class='phase_note'>" . substr($development_phase_info[$p]->note, 0, 29) . "...
										<div class='tooltip_description'
										style='display:none;'
										title='Note'>" .
                                    nl2br($development_phase_info[$p]->note)
                                    . "</div>
									</span>";
                            } else {
                                echo
                                    "<span class='phase_note'>" . $development_phase_info[$p]->note . "...
										<div class='tooltip_description'
										style='display:none;'
										title='Note'>" .
                                    nl2br($development_phase_info[$p]->note)
                                    . "</div>
									</span>";
                            }
                        } else {
                            echo "&nbsp;&nbsp;&nbsp;";
                        }
                        ?>
                    </div>
                    <div class="uncol1" style="width:15%;">
                        <?php
                        if ($development_phase_info[$p]->contact_first_name) {
                            echo $development_phase_info[$p]->contact_first_name . ' ' . $development_phase_info[$p]->contact_last_name;
                        } else {
                            echo '&nbsp;&nbsp;';
                        }
                        ?>
                    </div>
                    <div class="uncol1" style="width:10%;">
                        <?php if ($development_phase_info[$p]->dependency_phase_name && $development_phase_info[$p]->dont_use_dependency != 1) {
                            echo ($development_phase_info[$p]->dependency_task) ? "{$development_phase_info[$p]->dependency_phase_name}, {$development_phase_info[$p]->dependency_task}" : $development_phase_info[$p]->dependency_phase_name;
                            $does_any_phase_have_dependency = true;
                        } else {
                            echo '&nbsp;&nbsp;&nbsp;';
                        } ?>
                    </div>
                    <div class="uncol1" style="width:3%;">&nbsp;&nbsp;</div>
                    <div class="uncol1 statusCol <?php echo $user_app_role;
                    if ($development_phase_info[$p]->system_user_id == $user_id) {
                        echo "own_task";
                    } ?>" style="width:10%;text-align: right;">
                        <input type="checkbox" name="all_phase_task"
                               id="all_phase_task" <?php if (isset($development_phase_info[$p]->phase_status) && $development_phase_info[$p]->phase_status == 1) { ?> checked="checked" <?php } ?>
                               onclick="change_all_phase_task_status(<?php echo $development_id; ?>,<?php echo $development_phase_info[$p]->id; ?>,this.checked)">
                        <img style="visibility: hidden" src="<?php echo site_url('images/ajax-saving.gif'); ?>">
                    
                    </div>
                    <div class="uncol1 <?php echo $user_app_role; ?> pull-right" style="width:7%;">

                        <a href="#DevPhaseEdit_<?php echo $development_phase_info[$p]->id; ?>" title="Phase Edit"
                           role="button" data-toggle="modal" class="template-phase-edit"><img width="16" height="16"
                                                                                              src="<?php echo base_url(); ?>icon/icon_edit.png"/></a>

                        <a href="#DevPhaseDelete_<?php echo $development_phase_info[$p]->id; ?>" title="Phase Delete"
                           role="button" data-toggle="modal" class="template-phase-delete"><img width="16" height="16"
                                                                                                src="<?php echo base_url(); ?>images/icon/btn_horncastle_trash.png"/></a>

                    </div>
                    <!-- MODAL Phase Delete-->
                    <div id="DevPhaseDelete_<?php echo $development_phase_info[$p]->id; ?>"
                         class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">
                        <form class="form-horizontal"
                              action="<?php echo base_url(); ?>constructions/development_phase_delete/<?php echo $development_phase_info[$p]->id; ?>"
                              method="POST">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                <h3 id="myModalLabel">Delete
                                    Phase: <?php echo $development_phase_info[$p]->phase_name; ?></h3>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure want to delete this Phase?</p>

                            </div>
                            <div class="modal-footer delete-task">
                                <input type="hidden" id="development_id" placeholder="" name="development_id"
                                       value="<?php echo $this->uri->segment(3); ?>">
                                <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                                <input type="submit" value="Ok" name="submit" class="btn"/>
                            </div>
                        </form>
                    </div>
                    <!-- MODAL Phase Delete-->

                    <script>
                        $(function () {
                            /*$('#fuzzOptionsList_Phase_<?php echo $development_phase_info[$p]->id; ?>').fuzzyDropdown({
                                mainContainer: '#fuzzSearch_Phase_<?php echo $development_phase_info[$p]->id; ?>',
                                arrowUpClass: 'fuzzArrowUp',
                                selectedClass: 'selected',
                                enableBrowserDefaultScroll: true
                            });*/
                        });
                    </script>

                    <!-- MODAL Phase Edit -->
                    <div id="DevPhaseEdit_<?php echo $development_phase_info[$p]->id; ?>"
                         class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                         aria-hidden="true">

                        <form class="form-horizontal frmPhaseUpdate" id="<?php echo "form_".$form_id++; ?>"
                              action="<?php echo base_url(); ?>constructions/development_phase_update/<?php echo $development_phase_info[$p]->id . "/{$is_pre_construction}?cp={$_GET['cp']}"; ?>"
                              method="POST">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                <h3 id="myModalLabel">Edit Phase</h3>
                            </div>
                            <div class="modal-body">

                                <div class="control-group">
                                    <label class="control-label" for="phase_name">Phase Name </label>

                                    <div class="controls">
                                        <input type="text" id="phase_name" name="phase_name"
                                               value="<?php echo $development_phase_info[$p]->phase_name; ?>">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="planned_start_date">Planned Start Date </label>

                                    <div class="controls">
                                        <input required="" type="text" class="jq_datepicker" name="planned_start_date"
                                               value="<?php if ($development_phase_info[$p]->planned_start_date > '0000-00-00') {
                                                   echo $this->wbs_helper->to_report_date($development_phase_info[$p]->planned_start_date);
                                               } ?>">
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="planned_finished_date">Planned Completion
                                        Date </label>

                                    <div class="controls">
                                        <input type="text" class="jq_datepicker" name="planned_finished_date"
                                               value="<?php if ($development_phase_info[$p]->planned_finished_date > '0000-00-00') {
                                                   echo $this->wbs_helper->to_report_date($development_phase_info[$p]->planned_finished_date);
                                               } ?>">
                                    </div>
                                </div>

                                <!--<div class="control-group">
								<label class="control-label" for="dependency">Dependency</label>
								<div class="controls">
                                                                        <select name="dependency" class="dependency">
										<option value="">--Select Dependency--</option>
										<?php
                                $this->db->where('development_id', $development_id);
                                $this->db->where('construction_phase', $_GET['cp']);
                                //$this->db->where('stage_no', $stage_id);
                                $this->db->order_by('ordering', 'ASC');
                                $results = $this->db->get('construction_development_phase')->result();
                                foreach ($results as $result) {
                                    ?>
										<option <?php if ($development_phase_info[$p]->dependency_phase_id == $result->id) {
                                        echo 'selected';
                                    } ?> value="<?php echo $result->id; ?>"><?php echo $result->phase_name; ?></option>
										<?php
                                }
                                ?>
									</select>
									<input type="hidden" id="dependency_name" name="dependency_name" value="<?php echo $development_phase_info[$p]->dependency_phase_name; ?>">
								</div>
							</div>-->

                                <!--task #4556-->
                                <?php
                                $this->db->select('contact_company.id company_id, contact_company.category_id');
                                $this->db->join('contact_contact_list','contact_contact_list.company_id = contact_company.id');
                                $c = $this->db->get_where('contact_company', array('contact_contact_list.id'=>$development_phase_info[$p]->phase_person_responsible), 1, 0)->row();

                                ?>
                                <div class="control-group">
                                    <label class="control-label" for="phase_person_responsible">Task Category</label>
                                    <div class="controls">
                                        <select name="contact_category" class="selectpicker category"  data-live-search="true" id="select_<?php echo $select_id++; ?>" style="">
                                            <option value="">Select Category</option>
                                            <?php
                                            foreach ($cats as $cat) {
                                                ?>
                                                <option value="<?php echo $cat->id; ?>" <?php if(in_array($cat->id, explode('|',$c->category_id))) echo "selected"; ?>><?php echo $cat->category_name; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="company">Company</label>
                                    <div class="controls">
                                        <select name="contact_company" class="selectpicker company"  data-live-search="true" id="select_<?php echo $select_id++; ?>" style="">
                                            <option value="">Select Company</option>
                                            <?php
                                            foreach ($companies as $comp) {
                                                $c_cat = implode(' ',array_filter(explode('|',$comp->category_id)));
                                                ?>
                                                <option class="<?php echo ($c_cat) ? $c_cat : ''; ?>" value="<?php echo $comp->id; ?>"  <?php if($comp->id == $c->company_id) echo "selected"; ?>><?php echo $comp->company_name; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>

                                    </div>
                                </div>
                                <!---------------------------->

                                <div class="control-group">
                                    <label class="control-label" for="phase_person_responsible">Person
                                        Responsible</label>

                                    <div class="controls" style="clear: both;">
                                        <select name="phase_person_responsible" class="form-control contact selectpicker"  data-live-search="true" id="select_<?php echo $select_id++; ?>">
                                            <option value="">--Select a User--</option>
                                            <?php
                                            $this->db->where('status', '1');
                                            $this->db->where('wp_company_id', $wp_company_id);
                                            $this->db->order_by('contact_first_name', 'ASC');
                                            $results = $this->db->get('contact_contact_list')->result();
                                            foreach ($results as $result) {
                                                ?>
                                                <option class="<?php echo $result->company_id; ?>" <?php if ($development_phase_info[$p]->phase_person_responsible == $result->id) {
                                                    echo 'selected';
                                                } ?>
                                                    value="<?php echo $result->id; ?>"><?php echo $result->contact_first_name . ' ' . $result->contact_last_name; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>

                                        <!--<div id="fuzzSearch_Phase_<?php /*echo $development_phase_info[$p]->id; */?>">
                                            <div id="fuzzNameContainer">
                                                <span class="fuzzName"></span>
                                                <span class="fuzzArrow"></span>
                                            </div>
                                            <div id="fuzzDropdownContainer">
                                                <input type="text" value="" class="fuzzMagicBox"
                                                       placeholder="search.."/>
                                                <span class="fuzzSearchIcon"></span>
                                                <ul id="fuzzResults">
                                                </ul>
                                            </div>
                                        </div>-->
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="note">Note</label>

                                    <div class="controls">
                                        <textarea name="note"
                                                  style="padding: 2px 5px; width: 100%;"><?php echo $development_phase_info[$p]->note; ?></textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <div class="controls" style="font-size: 12px; font-weight: bold">
                                        <input type="radio" name="update_task_dates" value="0" checked> All phases NOT including the tasks that has dependency on this phase will be changed.<br>
                                        <input type="radio" name="update_task_dates" value="1"> All Phases and Tasks that has dependency on this phase will be changed. <br>
                                        <input type="radio" name="update_task_dates" value="-1"> Only this phase will be changed.
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword"></label>

                                    <div class="controls">
                                        <input type="hidden" id="development_id" name="development_id"
                                               value="<?php echo $this->uri->segment(3); ?>">
                                        <input type="hidden" id="phase_id" name="phase_id"
                                               value="<?php echo $development_phase_info[$p]->id; ?>">

                                        <div class="save">
                                            <input type="submit" value="Submit" name="submit"/>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </form>
                    </div>
                    <!-- MODAL Phase Edit-->
                </div>

                <div class="accordion-content" style="<?php $ph = $this->uri->segment(4);
                if ($development_phase_info[$p]->id == $ph) {
                    echo 'display:block;';
                } else {
                    echo 'display:none;';
                } ?>">


                    <script>
                        $(function () {
                            /*$('#fuzzOptionsList_<?php echo $development_phase_info[$p]->id; ?>').fuzzyDropdown({
                                mainContainer: '#fuzzSearch_<?php echo $development_phase_info[$p]->id; ?>',
                                arrowUpClass: 'fuzzArrowUp',
                                selectedClass: 'selected',
                                enableBrowserDefaultScroll: true,
								highlightClass: 'highlight'
                            });*/
                        });
                    </script>

                    <!-- MODAL Task Add -->
                    <div id="AddTask_<?php echo $development_phase_info[$p]->id; ?>" class="modal hide fade stage-modal"
                         tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                        <form class="form-horizontal" id="<?php echo "form_".$form_id++; ?>"
                              action="<?php echo base_url(); ?>constructions/development_task_add" method="POST">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                <h3 id="myModalLabel">Add Task</h3>
                            </div>
                            <div class="modal-body">

                                <div class="control-group">
                                    <label class="control-label" for="task_name">Task Name </label>

                                    <div class="controls">
                                        <input type="text" id="task_name" name="task_name" value="" required="">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="task_start_date">Planned Start Date </label>

                                    <div class="controls">
                                        <input type="text" class="jq_datepicker" name="task_start_date" value=""
                                               required="">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="planned_completion_date">Planned Completion
                                        Date </label>

                                    <div class="controls">
                                        <input type="text" class="jq_datepicker" name="actual_completion_date" value="">
                                    </div>
                                </div>

                                <div class="control-group">
                                    <label class="control-label" for="task_person_responsible">Person
                                        Responsible</label>

                                    <div class="controls" style="clear: both;">
                                        <select name="task_person_responsible" class="form-control"
                                                id="fuzzOptionsList_<?php echo $development_phase_info[$p]->id; ?>">
                                            <option value="">--Select a User--</option>
                                            <?php
                                            $this->db->where('status', '1');
                                            $this->db->where('wp_company_id', $wp_company_id);
                                            $this->db->order_by('contact_first_name', 'ASC');
                                            $results = $this->db->get('contact_contact_list')->result();
                                            foreach ($results as $result) {
                                                ?>
                                                <option
                                                    value="<?php echo $result->id; ?>"><?php echo $result->contact_first_name . ' ' . $result->contact_last_name; ?></option>
                                                <?php
                                            }
                                            ?>
                                        </select>

                                        <div id="fuzzSearch_<?php echo $development_phase_info[$p]->id; ?>">
                                            <div id="fuzzNameContainer">
                                                <span class="fuzzName"></span>
                                                <span class="fuzzArrow"></span>
                                            </div>
                                            <div id="fuzzDropdownContainer">
                                                <input type="text" value="" class="fuzzMagicBox"
                                                       placeholder="search.."/>
                                                <span class="fuzzSearchIcon"></span>
                                                <ul id="fuzzResults">
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="note">Note</label>

                                    <div class="controls">
                                        <textarea name="note" style="padding: 2px 5px; width: 100%;"></textarea>
                                    </div>
                                </div>
                                <div class="control-group">
                                    <label class="control-label" for="inputPassword"></label>

                                    <div class="controls">
										<input type="hidden" id="task_id" name="task_id" value="">
                                        <input type="hidden" id="development_id" name="development_id"
                                               value="<?php echo $this->uri->segment(3); ?>">
                                        <input type="hidden" id="phase_id" name="phase_id"
                                               value="<?php echo $development_phase_info[$p]->id; ?>">

                                        <div class="save">
                                            <input type="submit" value="Submit" name="submit"/>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </form>
                    </div>
                    <!-- MODAL Task Add-->

                    <script>
                        jQuery(document).ready(function () {
                            if (jQuery("#sortable-task-<?php echo $development_phase_info[$p]->id; ?>").length) {
                                $("#sortable-task-<?php echo $development_phase_info[$p]->id; ?>").sortable({
                                    update: function () {
                                        var order = $('#sortable-task-<?php echo $development_phase_info[$p]->id; ?>').sortable('serialize');
                                        $.ajax({
                                            url: window.Url + 'admindevelopment/development_task_ordering',
                                            type: 'POST',
                                            data: order,
                                            success: function (data) {

                                            }

                                        });
                                    }
                                });
                                $("#sortable-task-<?php echo $development_phase_info[$p]->id; ?>").disableSelection();

                                $("#sortable-task-<?php echo $development_phase_info[$p]->id; ?>").find("textarea")
                                    .bind('mousedown.ui-disableSelection selectstart.ui-disableSelection', function (e) {
                                        e.stopImmediatePropagation();
                                    });


                            }
                        });


                    </script>

                    <ul id="sortable-task-<?php echo $development_phase_info[$p]->id; ?>"
                        class="stage_content dropable-task-<?php echo $development_phase_info[$p]->id; ?>">
                        <?php

                        $phase_planned_finished_date = $development_phase_info[$p]->planned_finished_date;

                        $ci =& get_instance();
                        $ci->load->model('developments_model');
                        $development_phase_task_info = $ci->developments_model->get_development_phase_task_info($development_id, $development_phase_info[$p]->id)->result();
                        //print_r($development_phase_task_info);

                        for ($t = 0; $t < count($development_phase_task_info); $t++) {

                            $phase_bg_color = '';
                            $day_sign = '';
                            $day_alert = '';

                            if ($development_phase_task_info[$t]->task_start_date == '0000-00-00' && $development_phase_task_info[$t]->actual_completion_date == '0000-00-00') {
                                $task_planned_finished_date = '00-00-0000';
                            } elseif ($development_phase_task_info[$t]->task_start_date != '0000-00-00' && $development_phase_task_info[$t]->actual_completion_date == '0000-00-00') {
                                $task_planned_finished_date = date('d-m-Y', strtotime($development_phase_task_info[$t]->task_start_date));
                            } elseif ($development_phase_task_info[$t]->task_start_date != '0000-00-00' && $development_phase_task_info[$t]->actual_completion_date != '0000-00-00') {
                                $task_planned_finished_date = date('d-m-Y', strtotime($development_phase_task_info[$t]->actual_completion_date));
                            } else {
                                $task_planned_finished_date = '00-00-0000';
                            }


                            $pc_time = strtotime($task_planned_finished_date);
                            $start_date_time = strtotime($development_phase_task_info[$t]->task_start_date);

                            if ($development_phase_task_info[$t]->development_task_status == '1') {
                                $rem_days = '';
                                $day_sign = ' ';
                                $day_alert = "-";
                                $phase_bg_color = 'green';
                            } else if ($development_phase_task_info[$t]->task_start_date == '0000-00-00') {
                                $rem_days = '';
                                $day_sign = ' ';
                                $day_alert = "Dates Required";
                                $phase_bg_color = 'grey';
                            } elseif ($today_time > $start_date_time && $today_time < $pc_time && $development_phase_task_info[$t]->development_task_status == 0) {
                                $rem_days = date_diff(date_create($task_planned_finished_date), date_create($now))->format("%a");
                                $day_sign = '';
                                $day_alert = " Days Remaining";
                                $phase_bg_color = 'yellow';
                            } elseif ($today_time < $start_date_time && $today_time < $pc_time && $development_phase_task_info[$t]->development_task_status == 0) {
                                $rem_days = date_diff(date_create($task_planned_finished_date), date_create($now))->format("%a");
                                $day_sign = '';
                                $day_alert = " Days Remaining";
                                $phase_bg_color = 'gray';
                            } elseif ($today_time > $pc_time && $development_phase_task_info[$t]->development_task_status == 0) {
                                $rem_days = date_diff(date_create($task_planned_finished_date), date_create($now))->format("%a");
                                $day_sign = '';
                                $day_alert = " Days Over";
                                $phase_bg_color = 'red';
                            } elseif ($today_time == $pc_time && $development_phase_task_info[$t]->development_task_status == 0) {
                                $rem_days = '';
                                $day_sign = '';
                                $day_alert = '-';
                                $phase_bg_color = 'yellow';
                            } else {
                                $rem_days = '';
                                $day_sign = '';
                                $day_alert = '-';
                                $phase_bg_color = 'yellow';
                            }


                            ?>

                            <script>
                                $(function () {
                                    /*$('#fuzzOptionsList_<?php echo $development_phase_task_info[$t]->id; ?>').fuzzyDropdown({
                                        mainContainer: '#fuzzSearch_<?php echo $development_phase_task_info[$t]->id; ?>',
                                        arrowUpClass: 'fuzzArrowUp',
                                        selectedClass: 'selected',
                                        enableBrowserDefaultScroll: true
                                    });*/
                                });
                            </script>
                            <li id="listItemTask_<?php echo $development_phase_task_info[$t]->id; ?>">
                                <div class="uncol  <?php echo $user_app_role; ?>" style="width:7%;padding-left: 10px;">
                                    <a href="#DevTaskDelete_<?php echo $development_phase_task_info[$t]->id; ?>"
                                       title="Task Delete" role="button" data-toggle="modal"
                                       class="template-phase-delete"><img width="16" height="16"
                                                                          src="<?php echo base_url(); ?>images/icon/btn_horncastle_trash.png"/></a>
                                    <a href="#DevTaskEdit_<?php echo $development_phase_task_info[$t]->id; ?>"
                                       title="Task Edit" role="button" data-toggle="modal"
                                       class="template-phase-edit"><img width="16" height="16"
                                                                        src="<?php echo base_url(); ?>icon/icon_edit.png"/></a>
                                </div>
                                <div class="uncol task_name"
                                     style="width:18%"><?php if ($development_phase_task_info[$t]->development_task_status == '1') { ?>
                                        <img style="margin:0 4px 0 0px" width="22" height="22"
                                             src="<?php echo base_url(); ?>images/icon/status_complate.png" /><?php } ?><?php if (isset($development_phase_task_info[$t]->task_name)) {
                                        echo $development_phase_task_info[$t]->task_name;
                                    } ?></div>
                                <div class="uncol"
                                     style="width:10%;"><?php if ($development_phase_task_info[$t]->task_start_date != '0000-00-00') {
                                        echo date('d-m-Y', strtotime($development_phase_task_info[$t]->task_start_date));
                                    } else {
                                        echo "0000-00-00";
                                    } ?></a></div>
                                <div class="uncol" style="width:10%"><?php echo $task_planned_finished_date; ?></div>
                                <div class="uncol" style="width:15%">
                                    <textarea class="phase_task_note"
                                              data-id="<?php echo $development_phase_task_info[$t]->id; ?>"><?php echo $development_phase_task_info[$t]->note; ?></textarea>
                                    <img class="loading" src="<?php echo base_url() . 'images/ajax-saving.gif'; ?>"/>
                                </div>
                                <div class="uncol"
                                     style="width:15%"><?php if ($development_phase_task_info[$t]->contact_first_name) {
                                        echo $development_phase_task_info[$t]->contact_first_name . ' ' . $development_phase_task_info[$t]->contact_last_name;
                                    } else {
                                        echo '&nbsp;&nbsp;&nbsp;';
                                    } ?></div>
                                <div class="uncol" style="width:11%">&nbsp;&nbsp;&nbsp;</div>
                                <div class="uncol status" style="width:3%;">
                                    <div
                                        style="height:20px; width:20px; border-radius:15px; background-color:<?php echo $phase_bg_color; ?>"></div>
                                </div>
                                <div class="uncol statusCol <?php echo $user_app_role;
                                if ($development_phase_task_info[$t]->system_user_id == $user_id) {
                                    echo " own_task";
                                } ?>" style="width:9%;text-align:right;">
                                    <input id="phase_status_<?php echo $development_phase_task_info[$t]->id; ?>"
                                           type="checkbox"
                                           name="phase_status" <?php if ($development_phase_task_info[$t]->development_task_status == '1') { ?> checked="checked" <?php } ?>
                                           onclick="change_development_phase_task_status(<?php echo $development_id; ?>,<?php echo $development_phase_info[$p]->id; ?>,<?php echo $development_phase_task_info[$t]->id; ?>,this.checked)"/>
                                    <img style="visibility: hidden"
                                         src="<?php echo site_url('images/ajax-saving.gif'); ?>">
                                </div>
                                <div style="clear:both;"></div>

                                <!-- MODAL Task Edit -->
                                <div id="DevTaskEdit_<?php echo $development_phase_task_info[$t]->id; ?>"
                                     class="modal hide fade stage-modal" tabindex="-1" role="dialog"
                                     aria-labelledby="myModalLabel" aria-hidden="true">
                                    <form class="task_form" id="task_form_<?php echo $development_phase_task_info[$t]->id; ?>"
                                          class="form-horizontal"
                                          action="<?php echo base_url(); ?>constructions/development_task_update/<?php echo $development_phase_task_info[$t]->id; ?>"
                                          method="POST">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                x
                                            </button>
                                            <h3 id="myModalLabel">Edit Task</h3>
                                        </div>
                                        <div class="modal-body">

                                            <div class="control-group">
                                                <label class="control-label" for="task_name">Task Name </label>

                                                <div class="controls">
                                                    <input required="" type="text" id="task_name" name="task_name"
                                                           value="<?php echo $development_phase_task_info[$t]->task_name; ?>">
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="task_start_date">Planned Start
                                                    Date </label>

                                                <div class="controls">
                                                    <input required="" type="text" class="jq_datepicker" placeholder=""
                                                           name="task_start_date"
                                                           value="<?php if ($development_phase_task_info[$t]->task_start_date > '0000-00-00') {
                                                               echo $this->wbs_helper->to_report_date($development_phase_task_info[$t]->task_start_date);
                                                           } ?>">
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="planned_completion_date">Planned
                                                    Completion Date </label>

                                                <div class="controls">
                                                    <input type="text" class="jq_datepicker"
                                                           name="actual_completion_date"
                                                           value="<?php if ($development_phase_task_info[$t]->actual_completion_date > '0000-00-00') {
                                                               echo $this->wbs_helper->to_report_date($development_phase_task_info[$t]->actual_completion_date);
                                                           } ?>">
                                                </div>
                                            </div>

                                            <!--task #4556-->
                                            <?php
                                            $this->db->select('contact_company.id company_id, contact_company.category_id');
                                            $this->db->join('contact_contact_list','contact_contact_list.company_id = contact_company.id');
                                            $c = $this->db->get_where('contact_company', array('contact_contact_list.id'=>$development_phase_task_info[$t]->task_person_responsible), 1, 0)->row();

                                            ?>
                                            <div class="control-group">
                                                <label class="control-label" for="phase_person_responsible">Task Category</label>
                                                <div class="controls">
                                                    <select name="contact_category" class="selectpicker category"  data-live-search="true" id="select_<?php echo $select_id++; ?>" style="">
                                                        <option value="">Select Category</option>
                                                        <?php
                                                        foreach ($cats as $cat) {
                                                            ?>
                                                            <option value="<?php echo $cat->id; ?>" <?php if($development_phase_task_info[$t]->task_category == $cat->id || in_array($cat->id, explode('|',$c->category_id))) echo "selected"; ?>><?php echo $cat->category_name; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="company">Company</label>
                                                <div class="controls">
                                                    <select name="contact_company" class="selectpicker company"  data-live-search="true" id="select_<?php echo $select_id++; ?>" style="">
                                                        <option value="">Select Company</option>
                                                        <?php
                                                        foreach ($companies as $comp) {
                                                            $c_cat = implode(' ',array_filter(explode('|',$comp->category_id)));
                                                            ?>
                                                            <option class="<?php echo ($c_cat) ? $c_cat : ''; ?>" value="<?php echo $comp->id; ?>"  <?php if($development_phase_task_info[$t]->task_company == $comp->id || $comp->id == $c->company_id) echo "selected"; ?>><?php echo $comp->company_name; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <!---------------------------->

                                            <div class="control-group">
                                                <label class="control-label" for="task_person_responsible">Person
                                                    Responsible</label>

                                                <div class="controls" style="clear: both;">
                                                    <select name="task_person_responsible" class="form-control contact selectpicker" data-live-search="true" id="select_<?php echo $select_id++; ?>">
                                                        <option value="">--Select a User--</option>
                                                        <?php
                                                        $this->db->where('status', '1');
                                                        $this->db->where('wp_company_id', $wp_company_id);
                                                        $this->db->order_by('contact_first_name', 'ASC');
                                                        $results = $this->db->get('contact_contact_list')->result();
                                                        foreach ($results as $result) {
                                                            ?>
                                                            <option class="<?php echo $result->company_id; ?>" <?php if ($development_phase_task_info[$t]->task_person_responsible == $result->id) {
                                                                echo 'selected';
                                                            } ?>
                                                                value="<?php echo $result->id; ?>"><?php echo $result->contact_first_name . ' ' . $result->contact_last_name; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="note">Note</label>

                                                <div class="controls">
                                                    <textarea name="note"
                                                              style="padding: 2px 5px; width: 100%;"><?php echo $development_phase_task_info[$t]->note; ?></textarea>
                                                </div>
                                            </div>

                                            <div class="control-group">
                                                <label class="control-label" for="phase_id_move">Phase</label>

                                                <div class="controls" style="clear: both;">
                                                    <select name="phase_id_move" id="phase_id_move">
                                                        <?php
                                                        $this->db->where('development_id', $development_id);
                                                        $this->db->where('construction_phase', $_GET['cp']);
                                                        $this->db->order_by('ordering', 'ASC');
                                                        $results = $this->db->get('construction_development_phase')->result();
                                                        foreach ($results as $result) {
                                                            ?>
                                                            <option <?php if ($development_phase_info[$p]->id == $result->id) {
                                                                echo 'selected';
                                                            } ?>
                                                                value="<?php echo $result->id; ?>"><?php echo $result->phase_name; ?></option>
                                                            <?php
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <div class="controls" style="font-size: 12px; font-weight: bold">
                                                    <input type="radio" name="update_task_dates" value="0" checked> All phases NOT including the tasks that has dependency on this phase will be changed.<br>
                                                    <input type="radio" name="update_task_dates" value="1"> All Phases and Tasks that has dependency on this phase will be changed. <br>
                                                    <input type="radio" name="update_task_dates" value="-1"> Only this phase will be changed.
                                                </div>
                                            </div>
                                            <div class="control-group">
                                                <label class="control-label" for="inputPassword"></label>

                                                <div class="controls">
                                                    <input type="hidden" id="development_id" name="development_id"
                                                           value="<?php echo $this->uri->segment(3); ?>">
                                                    <input type="hidden" id="phase_id" name="phase_id"
                                                           value="<?php echo $development_phase_info[$p]->id; ?>">
													<input type="hidden" id="task_id" name="task_id"
                                                           value="<?php echo $development_phase_task_info[$t]->id; ?>">
                                                    <div class="save">
                                                        <input type="submit" value="Submit" name="submit"/>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>

                                    </form>
                                </div>
                                <!-- MODAL Task Edit-->

                                <!-- MODAL Task Delete-->
                                <div id="DevTaskDelete_<?php echo $development_phase_task_info[$t]->id; ?>"
                                     class="modal hide fade stage-modal" tabindex="-1" role="dialog"
                                     aria-labelledby="myModalLabel" aria-hidden="true">
                                    <form class="form-horizontal"
                                          action="<?php echo base_url(); ?>constructions/development_task_delete/<?php echo $development_phase_task_info[$t]->id; ?>"
                                          method="POST">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
                                                x
                                            </button>
                                            <h3 id="myModalLabel">Delete
                                                Task: <?php echo $development_phase_task_info[$t]->task_name; ?></h3>
                                        </div>
                                        <div class="modal-body">
                                            <p>Are you sure want to delete this Task?</p>

                                        </div>
                                        <div class="modal-footer delete-task">
                                            <input type="hidden" id="development_id" name="development_id"
                                                   value="<?php echo $this->uri->segment(3); ?>">

                                            <input type="hidden" id="phase_id" name="phase_id"
                                                   value="<?php echo $development_phase_info[$p]->id; ?>">
                                            <button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
                                            <input type="submit" value="Ok" name="submit" class="btn"/>
                                        </div>
                                    </form>
                                </div>
                                <!-- MODAL Task Delete-->

                            </li>

                        <?php } // end task for loop
                        ?>

                    </ul>
                </div>

            </li>
            <?php
        }// end phase for loop


        ?>


        <?php
        for ($i = 1; $i <= $stages; $i++) {
            $ci =& get_instance();
            $ci->load->model('developments_model');
            $phase_info = $ci->developments_model->get_phase_info($development_id, $i)->result();
            $all_phase_task = $ci->developments_model->get_all_phase_status($development_id, $i)->result();

            ?>
            <li class="accordion <?php $ph = $this->uri->segment(4);
            if ($i == $ph) {
                echo 'accordion-active';
            } ?>">
                <div class="accordion-header">
                    <div class="uncol1" style="width:6%;">
                        &nbsp;&nbsp;&nbsp;&nbsp;
                    </div>
                    <div class="uncol1"
                         style="width:17%;"><?php if (isset($all_phase_task[0]->aphase_status) && $all_phase_task[0]->aphase_status == 1) { ?>
                            <img style="margin-right:5px;" width="22" height="22"
                                 src="<?php echo base_url(); ?>images/icon/status_complate.png" /><?php } ?>
                        Stage <?php echo $i ?> </div>
                    <div class="uncol1" style="width:15%;">&nbsp;&nbsp;</div>
                    <div class="uncol1" style="width:17%;">&nbsp;&nbsp;</div>
                    <div class="uncol1" style="width:17%;">&nbsp;&nbsp;</div>
                    <div class="uncol1" style="width:12%;">&nbsp;&nbsp;</div>
                    <div class="uncol1" style="width:6%;">&nbsp;&nbsp;</div>
                    <div class="uncol1  <?php echo $user_app_role; ?>" style="width:10%;text-align: right;">
                        <input type="checkbox" name="all_phase_task"
                               id="all_phase_task" <?php if (isset($all_phase_task[0]->aphase_status) && $all_phase_task[0]->aphase_status == 1) { ?> checked="checked" <?php } ?>
                               onclick="change_all_stage_phase_status(<?php echo $development_id; ?>,<?php echo $i; ?>,this.checked)">
                    </div>
                </div>

                <div class="accordion-content" style="<?php $ph = $this->uri->segment(4);
                if ($i == $ph) {
                    echo 'display:block;';
                } else {
                    echo 'display:none;';
                } ?>">

                    <ul class="stage_content">

                        <?php for ($j = 0; $j < count($phase_info); $j++) {

                            $day_sign = '';
                            $day_alert = '';
                            $bg_color = 'yellow';

                            if ($phase_info[$j]->planned_finished_date > '0000-00-00') {
                                $planned_finished_date = date('d-m-Y', strtotime($phase_info[$j]->planned_finished_date));
                            } else if ($phase_info[$j]->planned_start_date == '0000-00-00') {
                                $planned_finished_date = '00-00-0000';
                            } else {
                                $created_date = date_create($phase_info[$j]->planned_start_date);
                                $str = '5 days';
                                $pcdate = date_add($created_date, date_interval_create_from_date_string($str));
                                $planned_finished_date = date_format($pcdate, 'd-m-Y');
                            }

                            $pc_time = strtotime($planned_finished_date);

                            if ($phase_info[$j]->phase_status == '1') {
                                $rem_days = '';
                                $day_sign = ' ';
                                $day_alert = "-";
                                $bg_color = 'green';
                            } elseif ($phase_info[$j]->planned_start_date == '0000-00-00') {
                                $rem_days = '';
                                $day_sign = ' ';
                                $day_alert = "Dates Required";
                                $bg_color = 'grey';
                            } elseif ($today_time < $pc_time) {
                                $rem_days = date_diff(date_create($phase_info[$j]->planned_finished_date), date_create($now))->format("%a");
                                $day_sign = '';
                                $day_alert = " Days Remaining";
                                $bg_color = 'yellow';
                            } elseif ($today_time > $pc_time) {
                                $rem_days = date_diff(date_create($phase_info[$j]->planned_finished_date), date_create($now))->format("%a");
                                $day_sign = '';
                                $day_alert = " Days Over";
                                $bg_color = 'red';
                            } elseif ($today_time == $pc_time) {
                                $rem_days = '';
                                $day_sign = '';
                                $day_alert = '-';
                                $bg_color = 'red';
                            } else {
                                $rem_days = '';
                                $day_sign = '';
                                $day_alert = '-';
                                $bg_color = 'yellow';
                            }

                            ?>
                            <li id="listItemTask">
                                <div class="uncol" style="width:6%;padding-left: 10px;">
                                    &nbsp;&nbsp;
                                </div>
                                <div class="uncol"
                                     style="width:17%;"><?php if ($phase_info[$j]->phase_status == '1') { ?><img
                                        style="margin-right:5px;" width="22" height="22"
                                        src="<?php echo base_url(); ?>images/icon/status_complate.png" /><?php } ?><?php if (isset($phase_info[$j]->phase_name)) echo $phase_info[$j]->phase_name; ?>
                                </div>

                                <div class="uncol"
                                     style="width:15%;"><?php if ($phase_info[$j]->planned_start_date > '0000-00-00') {
                                        echo date('d-m-Y', strtotime($phase_info[$j]->planned_start_date));
                                    } else {
                                        echo '00-00-0000';
                                    } ?></div>
                                <div class="uncol" style="width:17%;"><?php echo $planned_finished_date; ?></div>
                                <div class="uncol" style="width:17%"><?php if ($phase_info[$j]->username) {
                                        echo $phase_info[$j]->username;
                                    } else {
                                        echo '&nbsp;&nbsp;';
                                    } ?></div>
                                <div class="uncol"
                                     style="width:12%;"><?php echo $day_sign . $rem_days . $day_alert; ?></div>
                                <div class="uncol" style="width:6%;">
                                    <div
                                        style="height:20px; width:20px; border-radius:15px; background-color:<?php echo $bg_color; ?>"></div>
                                </div>
                                <div class="uncol  <?php echo $user_app_role; ?>" style="width:10%; text-align:right;">
                                    <input id="phase_status_<?php echo $phase_info[$j]->id; ?>" type="checkbox"
                                           name="phase_status" <?php if ($phase_info[$j]->phase_status == '1') { ?> checked="checked" <?php } ?>
                                           onclick="change_phase_status(<?php echo $development_id; ?>,<?php echo $i; ?>,<?php echo $phase_info[$j]->id; ?>,this.checked)"/>
                                </div>
                                <div style="clear:both;"></div>
                            </li>


                        <?php } ?>
                    </ul>
                </div>
            </li>
            <?php
        }
        ?>

    </ul>


    <!--<div style="text-align:right;font-weight: bold;">Note: All stages must be edited in their stage.</div>-->
</div>

<script>
    $(function () {
        /*$('#fuzzOptionsList_Phase').fuzzyDropdown({
            mainContainer: '#fuzzSearch_Phase',
            arrowUpClass: 'fuzzArrowUp',
            selectedClass: 'selected',
            enableBrowserDefaultScroll: true
        });*/
       /* $('#fuzzOptionsList_Phase').select2({ width: 'resolve' });*/
    });
</script>

<!-- MODAL Phase Add -->
<div id="AddPhase" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form class="form-horizontal addPhase" id="<?php echo "form_".$form_id++; ?>" action="<?php echo base_url(); ?>constructions/development_phase_add" method="POST">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="myModalLabel">Add Phase</h3>
        </div>
        <div class="modal-body">

            <div class="control-group">
                <label class="control-label" for="phase_name">Phase Name </label>

                <div class="controls">
                    <input type="text" id="phase_name" name="phase_name" value="" required="">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="planned_start_date">Start Date </label>

                <div class="controls">
                    <input type="text" class="jq_datepicker" id="" name="planned_start_date" value="" required="">
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="planned_finished_date">Finished Date </label>

                <div class="controls">
                    <input type="text" class="jq_datepicker" name="planned_finished_date" value="">
                </div>
            </div>
            <!--task #4556-->
            <div class="control-group">
                <label class="control-label" for="phase_person_responsible">Task Category</label>
                <div class="controls">
                    <select name="contact_category" class="selectpicker category"  data-live-search="true" id="select_<?php echo $select_id++; ?>" style="">
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
                <label class="control-label" for="company">Company</label>
                <div class="controls">
                    <select name="contact_company" class="selectpicker company"  data-live-search="true" id="select_<?php echo $select_id++; ?>" style="">
                        <option value="">Select Company</option>
                        <?php
                        foreach ($companies as $comp) {
                            $c_cat = implode(' ',array_filter(explode('|',$comp->category_id)));
                            ?>
                            <option class="<?php echo ($c_cat) ? $c_cat : ''; ?>" value="<?php echo $comp->id; ?>"><?php echo $comp->company_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>

                </div>
            </div>
            <!---------------------------->
            <div class="control-group">
                <label class="control-label" for="phase_person_responsible">Person Responsible</label>

                <div class="controls" style="clear: both;">
                    <select name="phase_person_responsible" data-live-search="true" class="form-control contact selectpicker" id="contact_phase">
                        <option value="">--Select a User--</option>
                        <?php
                        $this->db->where('status', '1');
                        $this->db->where('wp_company_id', $wp_company_id);
                        $this->db->order_by('contact_first_name', 'ASC');
                        $results = $this->db->get('contact_contact_list')->result();
                        foreach ($results as $result) {
                            ?>
                            <option class="<?php echo $result->company_id; ?>"
                                value="<?php echo $result->id; ?>"><?php echo $result->contact_first_name . ' ' . $result->contact_last_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>

                    <!--<div id="fuzzSearch_Phase">
                        <div id="fuzzNameContainer">
                            <span class="fuzzName"></span>
                            <span class="fuzzArrow"></span>
                        </div>
                        <div id="fuzzDropdownContainer">
                            <input type="text" value="" class="fuzzMagicBox" placeholder="search.."/>
                            <span class="fuzzSearchIcon"></span>
                            <ul id="fuzzResults">
                            </ul>
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="note">Note</label>

                <div class="controls">
                    <textarea name="note" style="padding: 2px 5px; width: 100%;"></textarea>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="inputPassword"></label>

                <div class="controls">
                    <input type="hidden" id="development_id" name="development_id"
                           value="<?php echo $this->uri->segment(3); ?>">

                    <input type="hidden" id="" name="construction_phase"
                           value="<?php echo $_GET['cp']; ?>">

                    <div class="save">
                        <input type="submit" value="Submit" name="submit"/>
                    </div>
                </div>
            </div>

        </div>

    </form>
</div>
<!-- MODAL Phase Add-->

<!-- MODAL Task Add -->
<div id="AddTask" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true">
    <form class="form-horizontal task_form" id="<?php echo "form_".$form_id++; ?>" action="<?php echo base_url(); ?>constructions/development_task_add" method="POST">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="myModalLabel">Add Task</h3>
        </div>
        <div class="modal-body">

            <div class="control-group">
                <label class="control-label" for="task_name">Task Name </label>

                <div class="controls">
                    <input type="text" id="task_name" name="task_name" value="" required="">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="task_start_date">Planned Start Date </label>

                <div class="controls">
                    <input type="text" class="jq_datepicker" name="task_start_date" value="" required="">
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="planned_completion_date">Planned Completion Date </label>

                <div class="controls">
                    <input type="text" class="jq_datepicker" name="actual_completion_date" value="">
                </div>
            </div>

            <!--task #4556-->
            <div class="control-group">
                <label class="control-label" for="phase_person_responsible">Task Category</label>
                <div class="controls">
                    <select name="contact_category" class="selectpicker category"  data-live-search="true" id="select_<?php echo $select_id++; ?>" style="">
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
                <label class="control-label" for="company">Company</label>
                <div class="controls">
                    <select name="contact_company" class="selectpicker company"  data-live-search="true" id="select_<?php echo $select_id++; ?>" style="">
                        <option value="">Select Company</option>
                        <?php
                        foreach ($companies as $comp) {
                            $c_cat = implode(' ',array_filter(explode('|',$comp->category_id)));
                            ?>
                            <option class="<?php echo ($c_cat) ? $c_cat : ''; ?>" value="<?php echo $comp->id; ?>"><?php echo $comp->company_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>

                </div>
            </div>
            <!---------------------------->

            <div class="control-group">
                <label class="control-label" for="task_person_responsible">Person Responsible</label>

                <div class="controls" style="clear: both;">
                    <select name="task_person_responsible" data-live-search="true"  class="form-control contact selectpicker" id="select_<?php echo $select_id++; ?>">
                        <option value="">--Select a User--</option>
                        <?php
                        $this->db->where('status', '1');
                        $this->db->where('wp_company_id', $wp_company_id);
                        $this->db->order_by('contact_first_name', 'ASC');
                        $results = $this->db->get('contact_contact_list')->result();
                        foreach ($results as $result) {
                            ?>
                            <option class="<?php echo $result->company_id; ?>"
                                value="<?php echo $result->id; ?>"><?php echo $result->contact_first_name . ' ' . $result->contact_last_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>

                    <!--<div id="fuzzSearch">
                        <div id="fuzzNameContainer">
                            <span class="fuzzName"></span>
                            <span class="fuzzArrow"></span>
                        </div>
                        <div id="fuzzDropdownContainer">
                            <input type="text" value="" class="fuzzMagicBox" placeholder="search.."/>
                            <span class="fuzzSearchIcon"></span>
                            <ul id="fuzzResults">
                            </ul>
                        </div>
                    </div>-->
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="phase_id">Phase</label>

                <div class="" style="clear: both;">
                    <select name="phase_id" class="form-control" id="" required>
                        <option value="">--Select Phase--</option>
                        <?php

                        foreach ($development_phase_info as $phase) {
                            ?>
                            <option value="<?php echo $phase->id; ?>"><?php echo $phase->phase_name; ?></option>
                            <?php
                        }
                        ?>
                    </select>

                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="note">Note</label>

                <div class="controls">
                    <textarea name="note" style="padding: 2px 5px; width: 100%;"></textarea>
                </div>
            </div>

            <!-- task #4624 -->
            <div class="control-group">
                <label class="control-label" for="note">Type of Task</label>
                <div class="controls">
                    <input type="radio" name="type_of_task" value="variation"> Variation
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="inputPassword"></label>

                <div class="controls">
                    <input type="hidden" id="development_id" name="development_id"
                           value="<?php echo $this->uri->segment(3); ?>">
					<input type="hidden" id="task_id" name="task_id" value="">
                    <!--<input type="hidden" id="phase_id" name="phase_id" value="<?php echo $development_phase_info[$p]->id; ?>">-->
                    <div class="save">
                        <input type="submit" value="Submit" name="submit"/>
                    </div>
                </div>
            </div>

        </div>

    </form>
</div>
<!-- MODAL Task Add-->
<?php if ($this->uri->segment(4)) { ?>
    <script type="text/javascript">

        $(document).ready(function () {
            /*was any phase opened?*/
            /*we have to identify it from URL*/
            var opened_phase = "<?php echo $this->uri->segment(5); ?>";


            $('.accordions').each(function () {

                $(this).find('.accordion').each(function () {
                    var phase_id = $(this).attr('id').replace('listItemPhase_', '');
                    if (phase_id == opened_phase) {
                        $(this).addClass('accordion-active');
                        $(this).find('.accordion-content').slideDown(300)
                    }
                });


                // Set First Accordion As Active
                //$(this).find('.accordion-content').hide();
                if (!$(this).hasClass('toggles')) {
                    //$(this).find('.accordion:first-child').addClass('accordion-active');
                    //$(this).find('.accordion:first-child .accordion-content').show();
                }

                // Set Accordion Events
                $(this).find('.accordion-header').click(function () {

                    if (!$(this).parent().hasClass('accordion-active')) {

                        // Close other accordions
                        if (!$(this).parent().parent().hasClass('toggles')) {
                            $(this).parent().parent().find('.accordion-active').removeClass('accordion-active').find('.accordion-content').slideUp(300);
                        }

                        // Open Accordion
                        $(this).parent().addClass('accordion-active');
                        $(this).parent().find('.accordion-content').slideDown(300);

                    } else {

                        // Close Accordion
                        $(this).parent().removeClass('accordion-active');
                        $(this).parent().find('.accordion-content').slideUp(300);

                    }

                });

            });
        });
    </script>
<?php } else { ?>
    <script type="text/javascript">

        $(document).ready(function () {

            $('.accordions').each(function () {

                // Set First Accordion As Active
                $(this).find('.accordion-content').hide();
                if (!$(this).hasClass('toggles')) {
                    $(this).find('.accordion:first-child').addClass('accordion-active');
                    $(this).find('.accordion:first-child .accordion-content').show();
                }

                // Set Accordion Events
                $(this).find('.accordion-header').click(function () {

                    if (!$(this).parent().hasClass('accordion-active')) {

                        // Close other accordions
                        if (!$(this).parent().parent().hasClass('toggles')) {
                            $(this).parent().parent().find('.accordion-active').removeClass('accordion-active').find('.accordion-content').slideUp(300);
                        }

                        // Open Accordion
                        $(this).parent().addClass('accordion-active');
                        $(this).parent().find('.accordion-content').slideDown(300);

                    } else {

                        // Close Accordion
                        $(this).parent().removeClass('accordion-active');
                        $(this).parent().find('.accordion-content').slideUp(300);

                    }

                });

            });


        });
    </script>
<?php } ?>

<script>
    $(document).ready(function () {

        /*task #4556*/
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

        //$(".selectpicker").selectpicker();

        $('.modal form').not('.frmPhaseUpdate').not('.task_form').not('.addPhase').ajaxForm({
            data:{construction_phase: pre_construction_url},
            success: function (data, status, xhr, form) {
                var development_id = $(".in #development_id").val();
                var phase_id = $(".in #phase_id").val();
                if (phase_id == undefined) {
                    phase_id = $(form).find("select[name='phase_id']").val();
                }
                newurl = window.Url + 'constructions/phases_underway/' +  development_id + '/' + phase_id + '?cp=' + pre_construction_url;
                window.location = newurl;
            },
            beforeSubmit: function () {
                var overlay = jQuery('<div id="overlay"> </div>');
                overlay.appendTo(document.body);
            }
        });

        $('.modal form.frmPhaseUpdate').submit(function () {

            var url = $(this).prop('action');
            var params = $(this).serialize() + '&submit=1';
            var development_id = $(".in #development_id").val();
            var phase_id = $(".in #phase_id").val();

            var warning_url = "<?php echo base_url().'constructions/phase_update_warning/'; ?>" + development_id + "/" + phase_id + "/" + pre_construction_url;

            var old_start_date = $(this).find("input[name=planned_start_date]").prop('defaultValue');
            var old_finished_date = $(this).find("input[name=planned_finished_date]").prop('defaultValue');
            var new_start_date = $(this).find("input[name=planned_start_date]").val();
            var new_finished_date = $(this).find("input[name=planned_finished_date]").val();

            /*if any start date or end date is changed we will show the warning poopup.
             * otherwise we will submit the form*/
            if ((old_start_date != new_start_date && new_finished_date == '') || old_finished_date != new_finished_date && $(this).find("input[name='update_task_dates']:checked").val() != '-1') {

                var overlay = jQuery('<div id="overlay"> </div>');
                overlay.appendTo(document.body);

                show_warning_popup(warning_url, url, development_id, phase_id, params);

            } else {
                submit_add_update_form(url, development_id, phase_id, params);
            }

            return false;

        });

        /*task add/edit form submission*/
        $('.modal form.task_form').submit(function () {

            var url = $(this).prop('action');
            var params = $(this).serialize() + '&submit=1';
            var development_id = $(this).find("[name='development_id']").val();
            var phase_id = $(this).find("[name='phase_id']").val();
			var task_id = $(this).find("[name='task_id']").val();

            var warning_url = "<?php echo base_url().'constructions/task_update_warning/'; ?>" + development_id + "/" + phase_id + "/" + task_id + '/' + pre_construction_url;

            /*if any start date or end date is changed we will show the warning poopup.
             * otherwise we will submit the form*/

                var overlay = jQuery('<div id="overlay"> </div>');
                overlay.appendTo(document.body);
            if($(this).find("input[name='update_task_dates']:checked").val() != '-1' && task_id != '' ){
                show_warning_popup(warning_url, url, development_id, phase_id, params);
            }else{
                submit_add_update_form(url, development_id, phase_id, params);
            }

            return false;

        });
    });
    function submit_add_update_form(url, development_id, phase_id, params) {
        var overlay = jQuery('<div id="overlay"> </div>');
        overlay.appendTo(document.body);
        $.ajax(url, {
            type: 'POST',
            data: params,
            success: function (data) {
                newurl = window.Url + 'constructions/phases_underway/' +  development_id + '/' + phase_id + '?cp=<?php echo $_GET['cp']; ?>';
                window.location = newurl;
            }
        })
    }
    function show_warning_popup(warning_url, url, development_id, phase_id, params){

        $.ajax(warning_url, {
            type: 'POST',
            data: params,
            dataType: 'json',
            success: function (data) {

                if (data.html != '') {
                    $("#dialog-confirm-phase-update tbody").html(data.html);
                    $(".modal").modal('hide');

                    /*confirmation dialog*/
                    $("#dialog-confirm-phase-update").dialog({
                        resizable: false,
                        modal: true,
                        height: 400,
                        width: 500,
                        buttons: {
                            "DECLINE": function () {

                                $(this).dialog("close");
                            },
                            ACCEPT: function () {
                                $(this).dialog("close");
                                submit_add_update_form(url, development_id, phase_id, params);
                            }
                        },
                        dialogClass: "dialog-phase-edit",
                        open: function(event, ui){
                            $("#overlay").remove();
                        }
                    });
                } else if(data.msg != ''){
                    $(".modal").modal('hide');
                    $("<div title='WARNING' />").html(data.msg).dialog({
                        resizable: false,
                        modal: true,
                        height: 200,
                        width: 500,
                        buttons: {
                            "CANCEL": function () {

                                $(this).dialog("close");
                                overlay.remove();
                            },
                            "OK": function () {
                                submit_add_update_form(url, development_id, phase_id, params);
                            }
                        },
                        dialogClass: "dialog-phase-edit",
                        open: function(event, ui){
                            $("#overlay").remove();
                        }
                    });
                }else {
                    submit_add_update_form(url, development_id, phase_id, params);
                }
            }
        });
    }
</script>

<?php if ($user_app_role == 'manager'): ?>
    <script>
        var frmSubmit = 0;
        $(document).ready(function () {
            $("#frmRemoveDependency").submit(function (event, data) {
                if (frmSubmit == 0) {
                    event.preventDefault();
                    $("#dialog-confirm").dialog({
                        resizable: false,
                        height: 200,
                        modal: true,
                        buttons: {
                            "Yes": function () {
                                frmSubmit = 1;
                                $("#frmRemoveDependency").submit();
                            },
                            Cancel: function () {
                                $(this).dialog("close");
                            }
                        }
                    });
                }

            });

            <?php if(!$does_any_phase_have_dependency): ?>
            $("#frmRemoveDependency").remove();
            <?php else: ?>
            $("#frmRemoveDependency").show();
            <?php endif; ?>
        })
    </script>
<?php endif; ?>

<div id="dialog-confirm-phase-update" title="WARNING" style="display: none">
    <span style="font-size: 18px">These phases Start and Finish date will be changed.</span>
    <table style="margin-top: 10px">
        <thead style="font-size: 12px">
        <tr>
            <th rowspan="2">PHASE</th>
            <th colspan="2">FROM</th>
            <th colspan="2">TO</th>
        </tr>
        <tr style="font-size: 10px">
            <th>Start Date</th>
            <th>Finish Date</th>
            <th>Start Date</th>
            <th>Finish Date</th>
        </tr>
        </thead>
        <tbody>
        <!--warning table here-->
        </tbody>
    </table>
</div>

