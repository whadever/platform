<script src="<?php echo base_url(); ?>js/jquery.chained.js"></script>
<style>
    /*table { border-collapse: separate; }
    td, th { border: solid 1px #000; }
    tr:first-child td:first-child, tr:first-child th:first-child { border-top-left-radius: 10px; }
    tr:first-child td:last-child, tr:first-child th:last-child { border-top-right-radius: 10px; }
    tr:last-child td:first-child, tr:last-child th:first-child { border-bottom-left-radius: 10px; }
    tr:last-child td:last-child, tr:last-child th:last-child { border-bottom-right-radius: 10px; }*/
    #stage_phase_task .unrow {
       margin: 0;
    }
    .unrow:last-child{
        border: none;
    }
    #stage_phase_task .unrow:last-child {
        border: medium none;
    }
    #stage_phase_task .unrow {
        align-items: center;
        border-bottom: 1px solid #ddd;
        display: flex;
    }
    .uncol {
        display: inline-block;
        font-size: 14px;
        line-height: 100%;
        padding: 10px 5px;
        vertical-align: middle;
    }
    #stage_phase_task .uncol {
        font-size: 12px;
        font-weight: bold;
    }
    #stage_phase_task .accordion-header{
        padding: 6px 8px;
        margin-bottom: 3px;
    }
    .modal-scrollable .modal {
        border: medium none;
        width: 500px;
    }
    .shake {
        animation-name: none;
    }
    .modal.stage-modal label.control-label {
        font-size: 14px;
        margin-bottom: 0;
        text-align: left;
        width: 128px;
        padding-top: 4px;
    }
    .btn-file {
        position: relative;
        overflow: hidden;
    }
    .btn-file input[type=file] {
        position: absolute;
        top: 0;
        right: 0;
        min-width: 100%;
        min-height: 100%;
        font-size: 100px;
        text-align: right;
        filter: alpha(opacity=0);
        opacity: 0;
        outline: none;
        background: white;
        cursor: inherit;
        display: block;
    }
    .ui-front {
        z-index: 3000;
    }
    .modal .form-control{
        transition: none;
        width: 60%;
    }


</style>

<form action="<?php echo base_url();?>/constructions/tendering/<?php echo $development_id;?>" method="POST">
<div class="row">
	<div class="col-xs-12">
                <input type="submit" class="btn btn-danger pull-right" name="search" value="Search"/>
		<input type="text" class="form-control pull-right" name="item_name" placeholder="Search Item" style="width:20%; margin-right:10px;" />
                <input type="text" class="form-control pull-right" name="contractor_name" placeholder="Search Contractor" style="width:20%; margin-right:10px;" />
                <input type="text" class="form-control pull-right" name="company_name" placeholder="Search Company" style="width:20%; margin-right:10px;" />
                <!--<input type="text" class="form-control pull-right" name="date_received" id="datepicker" placeholder="Received" style="width:10%; margin-right:10px;" />-->
	</div>
	
</div>
</form>

<div id="stage_phase_task">
    <div class="task-phase-add">
        <ul class="drag-phase-task">

            <li class=" " id="draggable-task">
                <a data-toggle="modal" class="btn btn-default"  role="button" id="" href="#uploadQuote" style="">Upload Quote</a>
            </li>
            <li class=" " id="draggable-phase">
                <a data-toggle="modal" class="btn btn-default" role="button"  title="Send Tender" href="#sendTender" data-backdrop="static" style="">Send Tender</a>
            </li>
            <li style="float:right; clear: right; margin-top: 8px">
            <span style="height:20px; width:20px; border-radius:15px; background-color:red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
            More than 2 weeks
            <span style="height:20px; width:20px; border-radius:15px; background-color:yellow">&nbsp;&nbsp;&nbsp;&nbsp;</span>
            Less than 2 weeks
            <span style="height:20px; width:20px; border-radius:15px; background-color:green">&nbsp;&nbsp;&nbsp;&nbsp;</span>
            Complete
            <span style="height:20px; width:20px; border-radius:15px; background-color:grey">&nbsp;&nbsp;&nbsp;&nbsp;</span>
            Not submitted
            </li>
            <!--task #4580-->
            <li style="float:left">
                <a data-toggle="modal" class="" role="button" id="" href="#addItem" style="">+Add Item</a> &nbsp; &nbsp;
                <a data-toggle="modal" class="" role="button" id="" href="#addContact" style="">+Add Contact</a>
            </li>
            <!--<li id="draggable-task"><a style="color:#000;" href="#AddTask_3062" id="phaseid" role="button" data-toggle="modal">Add Task +</a></li>-->

        </ul>
        <div style="clear:both;"></div>
    </div>
    <div class="" id="underway_header" style="margin-bottom: 3px">
            <div class="uhead" style="width: 15%">Name</div>
            <div class="uhead" style="width: 12%">Contractor</div>
            <div class="uhead" style="width: 13%">Company</div>
            <div class="uhead" style="width: 25%">E-mail</div>
            <div class="uhead" style="width: 15%">Date Submitted</div>
            <div class="uhead" style="width: 15%">Date Received</div>
            <div class="uhead"></div>
            <div class="uhead"></div>
    </div>
    <?php  foreach($tendering_info as $item_id => $contacts):

		if($contacts[0]->group_id == 4){continue;}
	 ?>
        <div class="accordion-header">
                <?php echo $contacts[0]->item; ?>
                <?php if($contacts[0]->item_job_id): ?>
                    <img width="16" height="16" src="<?php echo site_url('images/icon/btn_horncastle_trash.png'); ?>" style="float: right" title="delete item" data-item-id="<?php echo $item_id; ?>" class="delete-item" />
                <?php endif; ?>
        </div>
        <div class="accordion-body">
        <?php foreach($contacts as $contact): if(!$contact->contact_id && !$contact->company_id)continue; ?>
            <div class="unrow" style="clear: both">
                <div class="uncol" style="width: 15%">
                    <?php if($contact->item_contact_job_id): ?>
                        <img width="16" height="16" src="<?php echo site_url('images/icon/btn_horncastle_trash.png'); ?>" style="float: left; cursor: pointer" title="delete contact" data-contact-id="<?php echo $contact->item_contact_id; ?>" class="delete-contact" />
                    <?php endif; ?>
                    &nbsp;
                </div>
                <div class="uncol" style="width: 12%"><?php echo ($contact->contact_name) ? $contact->contact_name : $contact->company_name; ?></div>
                <div class="uncol" style="width: 13%"><?php echo ($contact->contact_company_name) ? $contact->contact_company_name : ""; ?></div>
                <div class="uncol" style="width: 25%"><?php echo ($contact->contact_email) ? $contact->contact_email : $contact->company_email; ?></div>
                <div class="uncol" style="width: 15%"><?php echo $contact->date_submitted; ?></div>
                <div class="uncol" style="width: 15%">
                    <?php
                    $received_files = $this->db->get_where('construction_tendering_received_files',array(
                        'construction_tendering_job_id' => $contact->construction_tendering_job_id
                    ))->result();
                    ?>
                    <?php //echo $contact->date_received; ?><br>
                    <?php if ($received_files): ?>
                        <!--task #4637-->
                        <?php //$date_received = explode(',', $contact->date_received); ?>
                        <?php //$received_fid = explode(',', $contact->received_fid); ?>
                        <?php //$i = 0; ?>
                        <?php foreach ($received_files as $f): ?>

                            <ul>
                                <li>
                                    <a href="<?php echo site_url('constructions/download_quote/' . $f->received_fid); ?>/" target="_blank">
                                        <img src="<?php echo site_url('images/file.png'); ?>" style="/*float: right; position: relative; left: -60px; top: -18px;*/">
                                    </a>
                                </li>
                                <li><?php echo date('d-m-Y',strtotime($f->date_received)); ?></li>

                            </ul>

                            <!--<a target="_blank" href="<?php echo site_url('constructions/download_quote/' . $contact->received_fid); ?>/view">
                                &nbsp;&nbsp;View
                            </a>-->
                            <?php
                            $file = $this->db->get_where('construction_file', array(
                                'fid' => $contact->received_fid), 0, 1)->row();
                            ?>
                            <!---<a target="_blank" href="<?php echo base_url(); ?>uploads/development/tendering/quotes/<?php echo $file->filename; ?>">Open</a>--->

                            <a class="delete_quote" href="#" title="Delete Quote" data-quote-id="<?php echo $f->id; ?>">X</a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="uncol">
                    <?php if($received_files): ?>
                        <span style="height:16px; display: block; width:16px; border-radius:15px; background-color:green">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <?php elseif(!$contact->date_submitted): ?>
                        <span style="height:16px; display: block; width:16px; border-radius:15px; background-color:grey">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <?php else: ?>
                        <?php
                            $date_submitted = date_create_from_format('d-m-Y',$contact->date_submitted);
                            $diff = $date_submitted->diff(new DateTime(),true)->d;
                            if($diff > 14){
                        ?>
                                <span style="height:16px; display: block; width:16px; border-radius:15px; background-color:red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <?php
                            }else{
                        ?>
                                <span style="height:16px; display: block; width:16px; border-radius:15px; background-color:yellow">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <?php    }?>

                    <?php endif; ?>

                </div>
                <div class="uncol">
                    <!--task #4167-->
                    <?php
                    /*getting the status*/
                    $this->db->select('status');
                    $conditions = array(
                        'job_id' => $development_id,
                        'item_id'=> $contact->item_id,
                    );
                    if(($contact->contact_name)){
                        $conditions['contact_id'] = $contact->contact_id;
                    }else{
                        $conditions['company_id'] = $contact->company_id;
                    }
                    $status = $this->db->get_where('construction_tendering_job_status',$conditions,1,0)->row();
                    $checked = ($status && $status->status == 1)?"checked":"";
                    $params = json_encode(array(
                        'job_id'    =>  $development_id,
                        'item_id'   =>  $contact->item_id,
                        'contact_id' => $contact->contact_id,
                        'company_id' => $contact->company_id
                    ));
                    ?>
                    <input name="item_<?php echo $contact->item_id; ?>" type="radio" onchange='updateStatus(<?php echo $params; ?>,this)' <?php echo $checked; ?>>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endforeach; ?>



	<!-- variation task -->

	<div><h3>Variation Tasks</h3></div>
	<?php  foreach($tendering_info as $item_id => $contacts): 

		if($contacts[0]->group_id != 4){continue;}
	?>
        <div class="accordion-header">
                <?php echo $contacts[0]->item; ?>
                <?php if($contacts[0]->item_job_id): ?>
                    <img width="16" height="16" src="<?php echo site_url('images/icon/btn_horncastle_trash.png'); ?>" style="float: right" title="delete item" data-item-id="<?php echo $item_id; ?>" class="delete-item" />
                <?php endif; ?>
        </div>
        <div class="accordion-body">
        <?php foreach($contacts as $contact): if(!$contact->contact_id && !$contact->company_id)continue; ?>
            <div class="unrow" style="clear: both">
                <div class="uncol" style="width: 15%">
                    <?php if($contact->item_contact_job_id): ?>
                        <img width="16" height="16" src="<?php echo site_url('images/icon/btn_horncastle_trash.png'); ?>" style="float: left; cursor: pointer" title="delete contact" data-contact-id="<?php echo $contact->item_contact_id; ?>" class="delete-contact" />
                    <?php endif; ?>
                    &nbsp;
                </div>
                <div class="uncol" style="width: 12%"><?php echo ($contact->contact_name) ? $contact->contact_name : $contact->company_name; ?></div>
                <div class="uncol" style="width: 13%"><?php echo ($contact->contact_company_name) ? $contact->contact_company_name : ""; ?></div>
                <div class="uncol" style="width: 25%"><?php echo ($contact->contact_email) ? $contact->contact_email : $contact->company_email; ?></div>
                <div class="uncol" style="width: 15%"><?php echo $contact->date_submitted; ?></div>
                <div class="uncol" style="width: 15%">
                    <?php
                    $received_files = $this->db->get_where('construction_tendering_received_files',array(
                        'construction_tendering_job_id' => $contact->construction_tendering_job_id
                    ))->result();
                    ?>
                    <?php //echo $contact->date_received; ?><br>
                    <?php if ($received_files): ?>
                        <!--task #4637-->
                        <?php //$date_received = explode(',', $contact->date_received); ?>
                        <?php //$received_fid = explode(',', $contact->received_fid); ?>
                        <?php //$i = 0; ?>
                        <?php foreach ($received_files as $f): ?>

                            <ul>
                                <li>
                                    <a href="<?php echo site_url('constructions/download_quote/' . $f->received_fid); ?>/" target="_blank">
                                        <img src="<?php echo site_url('images/file.png'); ?>" style="/*float: right; position: relative; left: -60px; top: -18px;*/">
                                    </a>
                                </li>
                                <li><?php echo date('d-m-Y',strtotime($f->date_received)); ?></li>

                            </ul>

                            <!--<a target="_blank" href="<?php echo site_url('constructions/download_quote/' . $contact->received_fid); ?>/view">
                                &nbsp;&nbsp;View
                            </a>-->
                            <?php
                            $file = $this->db->get_where('construction_file', array(
                                'fid' => $contact->received_fid), 0, 1)->row();
                            ?>
                            <!---<a target="_blank" href="<?php echo base_url(); ?>uploads/development/tendering/quotes/<?php echo $file->filename; ?>">Open</a>--->

                            <a class="delete_quote" href="#" title="Delete Quote" data-quote-id="<?php echo $contact->received_fid; ?>">X</a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
                <div class="uncol">
                    <?php if($received_files): ?>
                        <span style="height:16px; display: block; width:16px; border-radius:15px; background-color:green">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <?php elseif(!$contact->date_submitted): ?>
                        <span style="height:16px; display: block; width:16px; border-radius:15px; background-color:grey">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                    <?php else: ?>
                        <?php
                            $date_submitted = date_create_from_format('d-m-Y',$contact->date_submitted);
                            $diff = $date_submitted->diff(new DateTime(),true)->d;
                            if($diff > 14){
                        ?>
                                <span style="height:16px; display: block; width:16px; border-radius:15px; background-color:red">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <?php
                            }else{
                        ?>
                                <span style="height:16px; display: block; width:16px; border-radius:15px; background-color:yellow">&nbsp;&nbsp;&nbsp;&nbsp;</span>
                        <?php    }?>

                    <?php endif; ?>

                </div>
                <div class="uncol">
                    <!--task #4167-->
                    <?php
                    /*getting the status*/
                    $this->db->select('status');
                    $conditions = array(
                        'job_id' => $development_id,
                        'item_id'=> $contact->item_id,
                    );
                    if(($contact->contact_name)){
                        $conditions['contact_id'] = $contact->contact_id;
                    }else{
                        $conditions['company_id'] = $contact->company_id;
                    }
                    $status = $this->db->get_where('construction_tendering_job_status',$conditions,1,0)->row();
                    $checked = ($status && $status->status == 1)?"checked":"";
                    $params = json_encode(array(
                        'job_id'    =>  $development_id,
                        'item_id'   =>  $contact->item_id,
                        'contact_id' => $contact->contact_id,
                        'company_id' => $contact->company_id
                    ));
                    ?>
                    <input name="item_<?php echo $contact->item_id; ?>" type="radio" onchange='updateStatus(<?php echo $params; ?>,this)' <?php echo $checked; ?>>
                </div>
            </div>
        <?php endforeach; ?>
        </div>
    <?php endforeach; ?>







</div>

<!--the modal window for send tender-->
<div id="sendTender" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
            <h3 id="myModalLabel">Send Tender</h3>
        </div>
        <form id="frmSendTender" action="<?php echo site_url('constructions/upload_tender'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="control-group">
                <label for="file" class="control-label">Select File</label>

                    <span class="btn btn-default btn-file">
                       <input type="file" name="tenderFile"> Browse
                    </span>
                    <span id="filename" class="file-name" style="overflow: hidden"></span>
            </div>
            <div class="control-group">
                <label for="file" class="control-label">Select Group</label>

                <div class="controls">
                    <select id="selGroup" class="selectpicker" name="group">
                        <option value="" selected disabled></option>
                    <?php foreach($groups as $group): ?>
                        <option value="<?php echo $group->name; ?>"><?php echo $group->name; ?></option>
                    <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <table class="table table-striped">
                    <thead>
                    <tr><th>Item</th><th>Contractor</th><th>E-mail Address</th><th></th><th></th></tr>
                    </thead>
                    <tbody id="tbl">

                    </tbody>
                </table>
            </div>
            <div class="control-group" style="text-align: center">
                <input style="display: none" id="btnSendTender" type="submit" value="Upload" class="btn btn-default">
            </div>
        </div>
        </form>

</div>
<div id="uploadQuote" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel">Upload Quote</h3>
    </div>
    <form id="frmUploadQuote" action="<?php echo site_url('constructions/upload_quote'); ?>" method="post" enctype="multipart/form-data">
        <div class="modal-body">
            <div class="control-group">
                <label for="file" class="control-label">Select File</label>

                    <span class="btn btn-default btn-file">
                       <input type="file" name="quoteFile"> Browse
                    </span>
                <span id="" class="file-name" style="overflow: hidden"></span>
            </div>
            <div class="control-group">
                <label for="file" class="control-label">Select Contractor</label>

                <div class="controls">
                    <select id="selContractor" class="selectpicker" name="contact" data-live-search="true">
                        <option value="" selected disabled></option>
                        <?php $cont_arr = array(); ?>
                        <?php foreach($group_contacts as $group => $items): ?>
                            <?php foreach($items as $item => $conts): ?>
                                <?php foreach($conts as $cont): ?>
                                    <?php if(!in_array($cont['contact_id'], $cont_arr)):?>
                                        <option value="<?php echo ($cont['contact_contact_list_id']) ? "contact_{$cont['contact_contact_list_id']}" : "company_{$cont['contact_company_id']}"; ?>"><?php echo ($cont['contact_name']) ? $cont['contact_name'] : $cont['company_name']; ?></option>
                                        <?php $cont_arr[] = $cont['contact_id']; ?>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="control-group" style="clear: both">
                <label for="" class="control-label">Select Items</label>

                <div class="controls" id="listItem" style="min-height: 100px; float: left; width: 70%;">
                    <!--item list here-->
                </div>
            </div>
            <div class="control-group" style="text-align: center">
                <input style="" id="btnUploadQuote" type="submit" value="Upload" class="btn btn-default">
            </div>
        </div>
    </form>

</div>

<!--task #4580-->
<div id="addItem" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel">Add Item</h3>
    </div>
    <form id="" action="<?php echo site_url('constructions/add_tendering_item'); ?>" method="post">
        <?php
        $this->db->select('construction_template_task.*');
        $this->db->join('construction_template','construction_template.id = construction_template_task.template_id');
        $this->db->where('construction_template.wp_company_id',$wp_company_id);
        $this->db->where('construction_template_task.type_of_task','key_task');
        $tasks = $this->db->get('construction_template_task')->result();

        $templates = array();
        if($tasks){
            $template_id_arr = array();
            foreach($tasks as $t){
                $template_id_arr[] = $t->template_id;
            }
            $in = "(".implode(',',array_unique($template_id_arr)).")";
            $this->db->where('id in '.$in);
            $templates = $this->db->get('construction_template')->result();
        }

        ?>
        <input type="hidden" name="job_id" value="<?php echo $development_id; ?>">
        <input type="hidden" name="cp" value="<?php echo $_GET['cp']; ?>">
        <div class="modal-body">
            <div class="control-group">
                <label for="" class="control-label">Select Template</label>
                <select name="template" class="form-control" style="" id="addItemTemplate">
                    <option value="">select template</option>
                <?php foreach($templates as $t): ?>
                    <option value="<?php echo $t->id; ?>"><?php echo $t->template_name; ?></option>
                <?php endforeach; ?>
                </select>

            </div>
            <div class="control-group">
                <label for="file" class="control-label">Select Key Task</label>
                <select name="task_id" class="form-control" style="" id="addItemTask">
                    <option value="">select task</option>
                    <?php foreach($tasks as $t): ?>
                        <option value="<?php echo $t->id; ?>" class="<?php echo $t->template_id; ?>"><?php echo $t->task_name; ?></option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="control-group">
                <label for="file" class="control-label">Select Group</label>
                <select name="group" data-item="<?php echo $id; ?>" class="form-control" style="">
                    <option value="" style="">Select group</option>
                    <?php
                    foreach ($groups as $group) {
                        ?>
                        <option  <?php if ($group->id == $item['group_id']) { ?> selected="selected" <?php } ?> value="<?php echo $group->id; ?>"><?php echo $group->name; ?></option>
                        <?php
                    }
                    ?>
                </select>
            </div>
            <div class="control-group" style="text-align: center">
                <input style="" id="" type="submit" value="Add" class="btn btn-default">
            </div>
        </div>
    </form>

</div>
<div id="addContact" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
        <h3 id="myModalLabel">Add Contact</h3>
    </div>
    <form id="" action="<?php echo site_url('constructions/add_tendering_contact'); ?>" method="post">
        <input type="hidden" name="job_id" value="<?php echo $development_id; ?>">
        <input type="hidden" name="cp" value="<?php echo $_GET['cp']; ?>">
        <div class="modal-body">
            <div class="control-group">
                <?php
                $this->db->where('wp_company_id',$wp_company_id);
                $templates = $this->db->get('construction_template')->result();
                ?>
                <label for="" class="control-label">Select Category</label>
                <select name="category" class="form-control" style="" id="addContactCategory">
                    <option value="">select category</option>
                    <?php foreach($contact_categories as $c): ?>
                        <option value="<?php echo $c->id; ?>"><?php echo $c->category_name; ?></option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="control-group">
                <label for="" class="control-label">Select Company</label>
                <select name="company" class="form-control" style="" id="addContactCompany">
                    <option value="">select company</option>
                    <?php foreach($contact_companies as $c): ?>
                        <option class="<?php echo implode(' ',array_filter(explode('|',$c->category_id))); ?>" value="<?php echo $c->id; ?>"><?php echo $c->company_name; ?></option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="control-group">
                <label for="" class="control-label">Select Contact</label>
                <select name="contact" class="form-control" style="" id="addContact2">
                    <option value="">select contact</option>
                    <?php foreach($contact_contacts as $c): ?>
                        <option class="<?php echo $c->company_id; ?>" value="<?php echo $c->id; ?>"><?php echo $c->contact_first_name.' '.$c->contact_last_name; ?></option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="control-group">
                <label for="" class="control-label">Select Item</label>
                <select name="item" class="form-control" style="" id="">
                    <option value="">select item</option>
                    <?php foreach($tendering_items as $c): ?>
                        <option class="" value="<?php echo $c->id; ?>"><?php echo $c->name; ?></option>
                    <?php endforeach; ?>
                </select>

            </div>
            <div class="control-group" style="text-align: center">
                <input style="" id="" type="submit" value="Add" class="btn btn-default">
            </div>
        </div>
    </form>

</div>

<script>
    var contacts = <?php echo json_encode($group_contacts); ?>;
    var fileSelected = false, groupSelected = false;
    var active_calls = 0;
    var tender_send_list = {}; //{contact_id: [item_id,...,item_id,...]}
    $(document).ready(function () {
        $(".accordion-header").click(function(){
            $(this).next().slideToggle();
        });
        /*send tender functionality*/
        $("#selGroup").change(function(){
            var grp = $(this).val();
            tender_send_list = {};
            $("#tbl").empty();
            if(contacts[grp] == undefined){
                groupSelected = false;
                $("#btnSendTender").hide();
                $("#tbl").html('No contact in this group.'); return;
            }
            for(var item in contacts[grp]){
                for(var contact in contacts[grp][item]){
                    var c = contacts[grp][item][contact];
                    var tr = $("<tr/>");
                    $("<td/>").html(c.item).appendTo(tr);
                    if(c.contact_name != undefined){
                        $("<td/>").html(c.contact_name).appendTo(tr);
                        $("<td/>").html(c.contact_email).appendTo(tr);
                    }else{
                        $("<td/>").html(c.company_name).appendTo(tr);
                        $("<td/>").html(c.company_email).appendTo(tr);
                    }

                    /*task #4168*/
                    var email = c.contact_email || c.company_email;
                    //the preview button
                    $("<td/>",{class:'contact_'+ c.contact_id}).html(
                        "<img style='cursor:pointer' title='preview' class='preview' src='<?php echo site_url('images/eye.png'); ?>' id='preview_"+ c.contact_contact_list_id+"' data-email='"+email+"' />"
                    ).appendTo(tr);

                    $("<td/>").html("<input type='checkbox' value='"+ email+"-"+ c.item_id+"' class='contact_item' checked />").appendTo(tr);
                    if(tender_send_list[email] == undefined){
                        tender_send_list[email] = [];
                    }
                    tender_send_list[email].push(c.item_id);

                    tr.appendTo($("#tbl"));
                    groupSelected = true;
                }
            }
            if(fileSelected){

                $("#btnSendTender").show();
            }
        });

        /*adding/removing from tender send list*/
        $(document).delegate(".contact_item", 'change',function(){
           var email = $(this).val().split('-')[0];
           var item_id = $(this).val().split('-')[1];
           if($(this).is(":checked")){
               tender_send_list[email].push(item_id);
           }else{
               index = tender_send_list[email].indexOf(item_id);
               tender_send_list[email].splice(index,1);
           }
        });

        $("#frmSendTender").ajaxForm({
            beforeSubmit: function(arr, $form, options) {
                $("#filename").text("uploading...");
                $("#btnSendTender").prop('disabled',true);
            },
            success: function(data){
                var url = "<?php echo site_url('constructions/send_tender/'); ?>";
                if(data != -1){
                    $("#filename").text("sending...").css('color','green').css('font-weight', 'bold');
                    for(email in tender_send_list){
                        if(tender_send_list[email].length == 0) continue;
                        $.ajax(url + '/' + encodeURIComponent(email) + '/' + data + '/' + tender_send_list[email].join(','),{

                        })
                    }

                }else{
                    $("#filename").text("failed").css('color','red');
                }
            },
            error: function(){
                $("#filename").text("error").css('color','red');
            }
        });
        /*showing the email preview*/
        $("body").delegate('img.preview','click', function(){
            /*getting the contact id*/
            var contact_id = $(this).prop('id').replace('preview_','');
            var email = $(this).attr('data-email');
            if(tender_send_list[email].length == 0) return;
            var contact_items = [];
            var item_list = "<ul style='list-style: inside none disc;'>";
            var grp = $("#selGroup").val();
            for(var item in contacts[grp]) {
                for (var contact in contacts[grp][item]) {
                    var c = contacts[grp][item][contact];
                    if((c.contact_email || c.company_email) == email && tender_send_list[email].indexOf(c.item_id) != -1){
                        contact_items.push(c);
                        item_list += "<li>"+ c.item + "</li>";
                    }
                }
            }
            item_list += "</ul>";
            var name = contact_items[0].contact_name ? contact_items[0].contact_name : contact_items[0].company_name;
            $("<div />").html(
                "Dear "+name+",<br><br>"+
                "You have the opportunity to quote these following items:<br><br>"+
                item_list +
                "<br><br>" +
                "Please contact Sophie Cruttenden - sophie@williamsproperty.co.nz - 027 695 0132.<br><br>"+
                "Please use the attached document for your pricing. <br><br>"+
                "Thank you.<br><br>"

            ).dialog({
                    modal: true, width: 800
                });

        });

    });
    $(document).on('change', '.btn-file :file', function() {
        var input = $(this),
            numFiles = input.get(0).files ? input.get(0).files.length : 1,
            label = input.val().replace(/\\/g, '/').replace(/.*\//, '');

            $(this).parent().siblings(".file-name").text(label);

        if($(this).prop('name') == 'tenderFile'){

            fileSelected = true;
            if(groupSelected){
                $("#btnSendTender").show();
            }
        }
    });

   $(document).ajaxStop(function(){
       //location.reload();

   });
    /*********************************************************/


    $(document).ready(function(){
        $("#selContractor").change(function(){
            var cid = $(this).val();
            var items = [];

            $("#listItem").empty();

            for(var grp in contacts){
                for(var itms in contacts[grp]){
                   for(var conts in contacts[grp][itms]){
                       var c = contacts[grp][itms][conts];
                       if((cid.indexOf('contact_') != -1 && ('contact_'+c.contact_contact_list_id == cid)) || (cid.indexOf('company_') != -1 && ('company_'+c.contact_company_id == cid)) && items.indexOf(c.item_id) == -1){
                           items.push(c.item_id);
                           $("<input />",{
                               type:'checkbox',
                               name:'item_id[]',
                               value: c.item_id
                           }).appendTo($("#listItem"));
                           $("<span />").html(" "+ c.item + "<br>").appendTo($("#listItem"));
                       }
                   }
                }
            }
        });

        /*upload quote*/
        $("#frmUploadQuote").submit(function(event){
            if($(this).find("input[name=quoteFile]").val() == ""){
                alert('Must select a file.');
                event.preventDefault(); return;
            }
            if($(this).find("select[name=contact]").val() == null){
                alert('Must select a contractor.');
                event.preventDefault(); return;
            }
            if($(this).find("input[name='item_id[]']:checked").length == 0){
                alert('Must select an item.');
                event.preventDefault(); return;
            }
        })
    });
    var click_others = true;
    function updateStatus(params,el){
        params.status = $(el).is(":checked") ? 1:0;
        $.ajax('<?php echo site_url("constructions/update_tendering_status"); ?>',{
            data: params,
            global: false,
            type: 'post',
            success: function(data){

            }
        })
    }
    /*task #4580*/

    var job_id = <?php echo $development_id; ?>;
    var cp = "<?php echo $_GET['cp']; ?>";
    $(document).ready(function(){

        $("#addItemTask").chained("#addItemTemplate",{});

        $("#addContactCompany").chained("#addContactCategory",{});
        $("#addContact2").chained("#addContactCompany",{});

        $(".delete-item").click(function(e){
            var item_delete = $(
                '<div id=\"dialog-confirm\" title=\"Delete Item\">' +
                '<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left; margin:0 7px 20px 0;\"></span>Are you sure you want to delete this item?</p>' +
                '</div>');
            e.stopPropagation();
            var item_id = $(this).attr('data-item-id');
            item_delete.dialog({
                resizable: false,
                height:180,
                modal: true,
                buttons: {
                    "Delete": function() {
                        $("#dialog-confirm").html("deleting item...");
                        $.ajax('<?php echo site_url('constructions/delete_tendering_item'); ?>',{
                            type: 'post',
                            global: false,
                            data: {
                                job_id: job_id,
                                item_id: item_id
                            },
                            success:function(){
                                $("#dialog-confirm").html("<span style='color: green'>deleted.</span>");
                                location.reload();
                            },
                            error:function(){
                                $("#dialog-confirm").html("<span style='color: red'>error deleting item.</span>");
                            }
                        })
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                },
                close: function( event, ui ) {
                    item_delete.dialog('instance').destroy();
                }
            });
        });

        $(".delete-contact").click(function(e){
            var item_delete = $(
                '<div id=\"dialog-confirm\" title=\"Delete Contact\">' +
                '<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left; margin:0 7px 20px 0;\"></span>Are you sure you want to delete this contact?</p>' +
                '</div>');
            e.stopPropagation();
            var contact_id = $(this).attr('data-contact-id');
            item_delete.dialog({
                resizable: false,
                height:180,
                modal: true,
                buttons: {
                    "Delete": function() {
                        $("#dialog-confirm").html("deleting contact...");
                        $.ajax('<?php echo site_url('constructions/delete_tendering_contact'); ?>',{
                            type: 'post',
                            global: false,
                            data: {
                                job_id: job_id,
                                contact_id: contact_id
                            },
                            success:function(){
                                $("#dialog-confirm").html("<span style='color: green'>deleted.</span>");
                                location.reload();
                            },
                            error:function(){
                                $("#dialog-confirm").html("<span style='color: red'>error deleting contact.</span>");
                            }
                        })
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                },
                close: function( event, ui ) {
                    item_delete.dialog('instance').destroy();
                }
            });
        });


		/*  delete quote */
		$(".delete_quote").click(function(e){
            var item_delete = $(
                '<div id=\"dialog-confirm\" title=\"Delete Quote\">' +
                '<p><span class=\"ui-icon ui-icon-alert\" style=\"float:left; margin:0 7px 20px 0;\"></span>Are you sure you want to delete this quote?</p>' +
                '</div>');
            e.stopPropagation();
            var quote_id = $(this).attr('data-quote-id');
            item_delete.dialog({
                resizable: false,
                height:180,
                modal: true,
                buttons: {
                    "Delete": function() {
                        $("#dialog-confirm").html("deleting quote...");
                        $.ajax('<?php echo site_url('constructions/delete_tendering_quote'); ?>',{
                            type: 'post',
                            global: false,
                            data: {
                                job_id: job_id,
                                quote_id: quote_id
                            },
                            success:function(){
                                $("#dialog-confirm").html("<span style='color: green'>Quote has deleted successfully!.</span>");
                                location.reload();
                            },
                            error:function(){
                                $("#dialog-confirm").html("<span style='color: red'>Error deleting quote.</span>");
                            }
                        })
                    },
                    Cancel: function() {
                        $( this ).dialog( "close" );
                    }
                },
                close: function( event, ui ) {
                    item_delete.dialog('instance').destroy();
                }
            });
        });

    })
</script>