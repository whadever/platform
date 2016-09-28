<?php
$user = $this->session->userdata('user');
$user_id = $user->uid;
$this->db->select('application_role_id');
$this->db->where('user_id',$user_id);
$this->db->where('application_id',5);
$app_role_id = $this->db->get('users_application')->row()->application_role_id;
?>

<style>
.contractor #DeleteDocument{
	display: none;
}
.fileinputs > input#upload_document {
 	opacity: 0;
 	position: relative;
	width: 60%;
	z-index: 2;
	text-align: left;
}
div.fileinputs {
 	position: relative;
}
div.upload-file {
	left: 33.3333%;
	position: absolute;
	top: 0;
	width: 60%;
 	z-index: 1;
 	border: 2px solid #ecebf0;
}
.upload-file > input {
 	border: medium none;
 	width: 82%;
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
.document-table .btn {
    font-size: 100%;
    padding: 1px 12px;
}
.ten_docs:first-child td:first-child {
    border-top-style: solid;
    border-top-width: 2px;
}
.ten_docs td:first-child {
    border-left: 2px solid;
}
.ten_docs:first-child td:nth-child(2) {
    border-top: 2px solid;
}
.ten_docs td:nth-child(2) {
    border-right: 2px solid;
}
.ten_docs:last-child td:first-child {
    border-bottom: 2px solid;
}
.ten_docs:last-child td:nth-child(2) {
    border-bottom: 2px solid;
}
.document-table table {
    border-collapse: separate;
}
</style>
<link rel="stylesheet" href="<?php echo base_url() ?>css/sumoselect.css" />    
<script src = "<?php echo base_url() ?>js/jquery.sumoselect.js"></script>

<div id="development-notes" class="document <?php echo $user_app_role; ?>">

    <div class="document-left">
        <div class="document-left-top">
            <div class="document-search">
                <form action="#" method="POST">
                    <label for="document_search">Search</label>
                    <input name="document_search" type="text" id="document_search" value="" />
                </form>
            </div>
        </div>
        <div class="document-left-bottom">
            <div class="document-table">
                <table>
                    <thead>
                        <tr>
                            <th>Document</th><th>Date Added</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
						$user = $this->session->userdata('user');
						$uid = $user->uid;

                        $this->db->select('application_role_id');
                        $this->db->where('user_id',$uid);
                        $this->db->where('application_id',5);

                        $app_role_id = $this->db->get('users_application')->row()->application_role_id;

                        /*task #4130*/
                        $ten_docs = array(
                            'Sale and Purchase Agreement',
                            'Geotech',
                            'Initial Plans for Tendering',
                            'Submitted Council Plans',
                            'Consented Council Plans',
                            'Consented Council Supporting Docs',
                            'Consented Council Specification',
                            'Signed Landscaping Plan',
                            'Signed Interior Colors',
                            'Signed Exterior Colors',
			    'Property Title',
			    'Signed Investor Proposal'
                        );

                        foreach ($documents as $document) {
							$per_user_arr = explode(",",$document->permitted_users);
                            $class = (in_array($document->filename_custom, $ten_docs)) ? 'ten_docs' : ''; //task #4135
							if(in_array($uid,$per_user_arr) OR $uid == $document->uid OR $document->permitted_users == ''){

                            if($app_role_id == 5 && ( !in_array($document->filename_custom, $ten_docs) or $document->document_group_permission == 2 ) ){
								continue;
							}
							
							if($app_role_id == 4 && ($document->filename_custom=='Property Title' || $document->filename_custom=='Signed Investor Proposal')){
								continue;
							}

							if( ($app_role_id == 3 && $document->document_group_permission == 2) or ($app_role_id == 4 && $document->document_group_permission == 2 )){
								continue;
							}

							if( ($app_role_id == 3 && $document->document_group_permission == 3) or ($app_role_id == 4 && $document->document_group_permission == 3 )){
								continue;
							}
							
                            ?>
                            <tr id="document_<?php echo $document->id; ?>" class="<?php echo $class; ?>" onClick="loadDocument(<?php echo $document->id; ?>)">
                                <td>
                                    <?php echo $document->filename_custom; ?>
                                    <?php if(in_array($document->filename_custom, $ten_docs) && $document->filesize == 0): ?>
                                    <form style="float: right" action="<?php echo site_url('constructions/development_document_update/'.$development_id.'/'.$document->id.'?cp='.$_GET['cp']); ?>" method="POST" enctype="multipart/form-data">
										
										<?php 
                                    	//task #4615 Investor Read Only Document
                                    	if($app_role_id != 5 AND $app_role_id != 4 AND $app_role_id != 3): 
                                    	?>
                                        <span class="btn btn-default btn-file">
                                            Browse <input type="file" name="upload_document" onchange="this.form.submit()">
                                        </span>
										 <?php endif; ?>

                                        <input type="hidden" id="" name="file_title" value="<?php echo $document->filename_custom; ?>">
                                        <?php
                                        $notify_users = ($document->notify_user)?explode(',',$document->notify_user):array();
                                        foreach ($notify_users as $row) {
                                        ?>
                                            <input type="hidden" name="notify_user[]" value="<?php echo $row; ?>">
                                        <?php
                                        }
                                        ?>
                                        <?php
                                        $notify_users = ($document->permitted_users)?explode(',',$document->permitted_users):array();
                                        foreach ($notify_users as $row) {
                                            ?>
                                            <input type="hidden" name="permission_users[]" value="<?php echo $row; ?>">
                                            <?php
                                        }
                                        ?>
                                    </form>
                                    <?php elseif(in_array($document->filename_custom, $ten_docs) && $document->filesize > 0): ?>
									<?php 
                                    //task #4615 Investor Read Only Document
                                    if($app_role_id != 5 AND $app_role_id != 4 AND $app_role_id != 3 ): 
                                    ?>
										<a data-toggle="modal" title="Edit This Document" href="#Document_Edit_<?php echo $document->id; ?>"><span class="btn wpconicon pull-right">Update</span></a>
									<?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php echo ($document->filesize != 0) ? date('d-m-Y', $document->created) : ''; //task #4135 ?>
                                    <!-- MODAL Document Edit -->
                                    <div id="Document_Edit_<?php echo $document->id; ?>"
                                         class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
                                         aria-hidden="true">


                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
                                            <h3 id="myModalLabel">Edit Document</h3>
                                        </div>
                                        <div class="modal-body">

                                            <form class="form-horizontal frmPhaseUpdate" action="<?php echo site_url('constructions/development_document_update/'.$development_id.'/'.$document->id.'?cp='.$_GET['cp']); ?>" method="POST" enctype="multipart/form-data">
                                                <div class="control-group">
                                                    <label class="control-label" for="phase_name">Document Title</label>

                                                    <div class="controls">
                                                        <input type="text" id="file_title" name="file_title" value="<?php echo $document->filename_custom; ?>">
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label" for="document_file">Document File</label>
                                                    <div class="controls">
                                                        <br>
                                                        <input type="file" name="upload_document" />
                                                        <?php echo $document->filename; ?>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <label class="control-label" for="planned_finished_date">Notify User</label>

                                                    <div class="controls">
                                                        <br>
                                                        <select name="notify_user[]" id="notify_user" class="form-control SlectBox" multiple style="width:100%;border: 2px solid #eee;border-radius: 0;box-shadow: 0 0 0; height:35px;">
                                                            <option value="">-- Select User --</option>
                                                            <?php
                                                            $selected = '';
                                                            $user = $this->session->userdata('user');
                                                            $wp_company_id = $user->company_id;

                                                            $this->db->where('users.company_id', $wp_company_id);
                                                            $this->db->where('application_id', '5');
                                                            $this->db->where('application_role_id', '2');
                                                            $this->db->join('users', 'users.uid = users_application.user_id', 'left');
                                                            $this->db->order_by('username', 'ASC');
                                                            $results = $this->db->get('users_application')->result();
                                                            /*task #4045*/
                                                            $notify_users = explode(',',$document->notify_user);
                                                            foreach ($results as $row) {
                                                                if(in_array($row->uid, $notify_users)){$selected = "selected";}else{$selected = '';}
                                                                echo '<option '.$selected.' value="' . $row->uid . '">' . $row->username . '</option>';
                                                            }
                                                            ?>
                                                        </select>
														
                                                    </div>
                                                </div>
                                                <div class="control-group">
                                                    <label class="control-label" for="phase_person_responsible">User Permission</label>
                                                    <div class="controls" style="clear: both;">
                                                        <!-- <select name="permission_users[]" class="SlectBox" multiple="multiple" style="">
                                                            <option value="">-- Select User --</option>
                                                            <?php
                                                            $user = $this->session->userdata('user');
                                                            $wp_company_id = $user->company_id;

                                                            $selected = '';
                                                            $pu = explode(',',$document->permitted_users);
                                                            foreach ($results as $row) {
                                                                if(in_array($row->uid, $pu)){$selected = "selected";}else{$selected = '';}
                                                                echo '<option '.$selected.'  value="' . $row->uid . '">' . $row->username . '</option>';
                                                            }
                                                            ?>
                                                        </select> -->

														<select style="width: 200px;" name="document_group_permission">
											                <option value="">Select Permission Group </option>
											                <option value="1" <?php if($document->document_group_permission == 1){echo 'selected';} ?> >Public</option>
											                <option value="2" <?php if($document->document_group_permission == 2){echo 'selected';} ?>>Private</option>
															<option value="3" <?php if($document->document_group_permission == 3){echo 'selected';} ?>>Investor</option>
											            </select>
                                                    </div>
                                                </div>

                                                <div class="control-group">
                                                    <div class="controls">
                                                        <div class="save">
                                                            <input type="submit" value="Submit" name="submit"/>
                                                        </div>
                                                    </div>
                                                </div>
                                            </form>

                                        </div>



                                    </div>
                                    <!-- MODAL Document Edit-->
                                </td>
                        		<input type="hidden" value="<?php echo $document->id; ?>">

							<?php  } ?>
                        </tr>
                        <?php
                    }
                    ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

	<script type="text/javascript">
	$(document).ready(function () {
		$('.SlectBox').SumoSelect();
		});
	</script>

    <div class="document-right">
        <div class="document-right-top">
            <div class="document-button">
            <?php if($app_role_id != 5 AND $app_role_id != 4 AND $app_role_id != 3): ?>
				<a class="wpconicon" style="padding:5px 2px 5px 8px" href="" title="Edit This Document" class="edit-document" data-toggle="modal" id="EditDocument">	
					<i class="fa fa-edit fa-1x"></i>
				</a>	
                <a class="wpconicon" style="padding:5px 2px 5px 8px" href="#" title="Delete This Document" class="delete-document" id="DeleteDocument">			
                    <i class="fa fa-remove fa-1x"></i>	
                </a>
                <!--<a href="#" title="Print This Documents" class="print-document">
                    <img width="50" height="50" border="0" src="<?php echo base_url(); ?>icon/print_document.png">
                </a>-->
                <!--<a href="#" id="doc_down" title="Save This Document" class="save-document">
                    <img width="50" height="50" border="0" src="<?php echo base_url(); ?>icon/download_document.png">
                </a>-->
                <a class="wpconicon" style="padding:5px 8px 5px 8px" href="#" title="Add Document" class="add-document" id="AddDocument">
                    <i class="fa fa-plus fa-1x"></i>
                </a>
                <div class="clear"></div>
            <?php endif; ?>					
            </div>
            <div class="clear"></div>
        </div>
        <div class="clear"></div>
        <div class="document-right-bottom">
            <div class="document-detail">
                <?php
                
                foreach ($developments_documents as $developments_document) {
                    
                    if(preg_match('/^.*\.(jpg|jpeg|gif|png|bmp)$/i', strtolower($developments_document->filename))){
						echo '<a href="'.base_url().'uploads/development/documents/'.$developments_document->filename.'">Download</a>';
                        echo "<img src = '".base_url()."uploads/development/documents/".$developments_document->filename."' width='100%' height='100%' />";
                    }else if(preg_match('/^.*\.(docx|xlsx)$/i', strtolower($developments_document->filename))){
                        echo '<a href="'.base_url().'uploads/development/documents/'.$developments_document->filename.'">Download</a>';
                    }else{
                    
                    ?>
                        <object data="<?php echo base_url(); ?>uploads/development/documents/<?php echo $developments_document->filename; ?>" type="application/pdf" width="100%" height="100%">

                            <p>It appears you don't have a PDF plugin for this browser.
                                You can <a href="<?php echo base_url(); ?>uploads/development/documents/<?php echo $developments_document->filename; ?>">click here to
                                    download the PDF file.</a></p>
                        </object>						
                    <?php
                    }
                }
                ?>  
            </div>
        </div>
    </div>
    <div class="clear"></div>

    <div id="delete-document-modal">
        <div class="modal-body">
            <p>Are you sure want to delete this Construction Document?</p>
        </div>
        <div class="modal-footer">
            <form action="<?php echo site_url("constructions/development_document_delete?cp={$_GET['cp']}"); ?>" method="post">
                <input type="hidden" id="dev_id" name="dev_id" value="<?php echo $development_id; ?>"/>
				<input type="hidden" value="<?php echo $this->uri->segment(4); ?>" name="file_category">
                <input type="hidden" id="dev_document_id" name="dev_document_id" value=""/>
                <input id="delete-document-dev" class="btn" type="submit" value="Yes"/>
                <input id="close-botton" class="btn" type="button" value="No"/>
            </form>

            <div class="clear"></div>
        </div>	
    </div>    


    <div id="AddDocumentBox" title="">

        <div class="doc_add_title" style="margin: -19px 0 20px 10px;text-align: left;"><h4>Add Document</h4></div>

		

        <style>
            #AddDocumentBox .form-control:focus{box-shadow: 0 0 0 0;}
            #AddDocumentBox .btn.btn-default {
                background: #ecebf0;
                border: 0 none;
                border-radius: 0;
            }
            #AddDocumentBox .btn.btn-default:hover {
                background: #ecebf0;
                border: 0 none;
                border-radius: 0;
            }
            .ui-widget-content {
                border: 2px solid #ecebf0;
                border-radius: 0 !important;
            }

            .fileinputs > input#upload_document {
                opacity: 0;
                position: relative;
                width: 60%;
                z-index: 2;
                text-align: left;
            }
            div.fileinputs {
                position: relative;
            }
            div.upload-file {
                left: 33.3333%;
                position: absolute;
                top: 0;
                width: 60%;
                z-index: 1;
                border: 2px solid #ecebf0;
            }
            .upload-file > input {
                border: medium none;
                width: 82%;
            }
        </style>

        <style type="text/css">
            .file_upload {
                border: 2px solid #ecebf0;
                margin-left: 33.3333%;
                width: 60%;
                padding: 2px 0;
            }
            .file_upload input.file_input_textbox {
                border: medium none;
                width: 78%;
            }
            .file_input_textbox {height:25px;width:200px;float:left; }
            .file_input_div     {position: relative;width:21%;height:26px;overflow: hidden; }
            .file_input_button {
                background-image: url("<?php echo base_url(); ?>images/file_upload.png");
                border: 1px solid #f0f0ee;
                font-weight: bold;
                height: 25px;
                margin: 0;
                padding: 0;
                position: absolute;
                top: 0;
                width: 100%;
            }
            .file_input_button_hover{
                background-image: url("<?php echo base_url(); ?>images/file_upload.png");
                border: 1px solid #f0f0ee;
                font-weight: bold;
                height: 25px;
                margin: 0;
                padding: 0;
                position: absolute;
                top: 0;
                width: 100%;
            }
            .file_input_hidden  {font-size:45px;position:absolute;right:0px;top:0px;cursor:pointer;
                                 opacity:0;filter:alpha(opacity=0);-ms-filter:"alpha(opacity=0)";-khtml-opacity:0;-moz-opacity:0; }
        </style>


		

        <?php
        $form_attributes = array('class' => 'addform', 'id' => 'addnoteform');
        $label_attributes = array(
            'class' => 'col-sm-4 control-label',
            'style' => 'color: #000;padding-top: 7px;text-align: right;',
        );
        $file_title = array(
            'name' => 'file_title',
            'id' => 'file_title',
            'value' => '',
            'maxlength' => '100',
            'size' => '50',
            'style' => 'width:60%;border: 2px solid #ecebf0;border-radius: 0;box-shadow: 0 0 0;',
            'class' => 'form-control',
        );
        $upload_document = array(
            'name' => 'upload_document',
            'id' => 'upload_document',
            'type' => 'file',
            'class' => 'file',
        );
        $dropdown_js = 'class="form-control" style="width:60%;border: 2px solid #ecebf0;border-radius: 0;"';

        $action = base_url() . 'constructions/save_development_document/' . $development_id. "?cp={$_GET['cp']}";
        echo form_open_multipart($action, $form_attributes);
        echo '<div class="form-group">';
        echo form_label('Document Title', 'file_title', $label_attributes);
        echo form_input($file_title);
        echo '</div>';

        echo '<div class="form-group fileinputs">';
        echo form_label('Document File', 'upload_document', $label_attributes);
        $onchange = "javascript: document.getElementById('fileName').value = this.value";
        $onmouseover = "document.getElementById('fileInputButton').className='file_input_button_hover';";
        $onmouseout = "document.getElementById('fileInputButton').className='file_input_button';";

        echo '<div class="file_upload">';
        echo '<input type="text" id="fileName" class="file_input_textbox" readonly="readonly">';
        echo '<div class="file_input_div">';
        echo '<input id="fileInputButton" type="button" value="" class="file_input_button" />';
        echo '<input type="file" name="upload_document" class="file_input_hidden" onchange="' . $onchange . '" onmouseover="' . $onmouseover . '" onmouseout="' . $onmouseout . '" />';
        echo '</div>';
        echo '</div>';

        echo '</div>';

        /* echo '<div class="form-group">';
          echo form_label('Document Category:', 'document_category', $label_attributes);
          $options = array(
          '0'  => '-- Select Category --',
          '1'    => 'Financial',
          '2'   => 'Land',

          );

          //$shirts_on_sale = array('small', 'large');

          echo form_dropdown('document_category', $options, 0, $dropdown_js);
          echo '</div>'; */

        echo '<div class="form-group" id="notify_user_div">';
        echo form_label('Notify User', 'notify_user[]', $label_attributes);
        echo '<select name="notify_user[]" id="notify_user" multiple class="form-control SlectBox" style="border: 2px solid #ecebf0;border-radius: 0;box-shadow: 0 0 0; float: right">';
        echo '<option value="">-- Select User --</option>';

        $user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;

        $this->db->where('users.company_id', $wp_company_id);
        $this->db->where('application_id', '5');
        //$this->db->where('application_role_id', '2');
        $this->db->join('users', 'users.uid = users_application.user_id', 'left');
        $this->db->order_by('username', 'ASC');
        $results = $this->db->get('users_application')->result();
        foreach ($results as $row) {
            echo '<option value="' . $row->uid . '">' . $row->username . '</option>';
        }

        echo '</select>';
        echo '</div>';


		/*echo '<div class="form-group">';
        echo form_label('User Permission', 'permission_user', $label_attributes);
        echo '<select name="permission_user[]" id="permission_user" class="SlectBox" multiple="multiple">';
        echo '<option value="">-- Select User --</option>';

        $user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;

        foreach ($results as $row) {
            echo '<option value="' . $row->uid . '">' . $row->username . '</option>';
        }

        echo '</select>';
        echo '</div>'; */

		echo form_label('User Group Permission', 'permission_user', $label_attributes);
		echo '<select style="width: 200px;" name="document_group_permission">
                <option value="">Select Permission Group </option>
                <option value="1">Public</option>
                <option value="2">Private</option>
				<option value="3">Investor</option>
            </select>';
		

        echo '<div class="form-group">';
        echo '<input type="hidden" value="' . $this->uri->segment(2) . '" name="url">';
        echo '<input type="hidden" value="' . $this->uri->segment(4) . '" name="file_category">';
        echo form_label('', 'document_submit', $label_attributes);
        echo form_submit('document_submit', 'Add', "class='btn btn-default'");
        echo '</div>';

        echo form_close();
        ?>


    </div>
</div>



<script>

    var activeDocument = null;

    function loadDocument(document_id){

        activeDocument = document_id;

        $('tr').removeClass('documentactive'); 
        $('#document_' + document_id).addClass('documentactive'); 
		link = "#Document_Edit_" + document_id;
		$("#EditDocument").attr("href", link )
        $.ajax({  
            url: "<?php print base_url(); ?>constructions/development_document_detail/"+document_id,  
            dataType: 'html',  
            type: 'GET',  
                 
            success:     
                function(data){  
                //console.log(data);
                if(data){  
                    //jQuery('#subcompname-wrapper').append(data);
                    jQuery('.document-detail').empty();
                    jQuery('.document-detail').append(data);
                   
                }  
            }
        });
    }
</script>

<script type="text/javascript">
    
    $(document).ready(function () {
        $("#AddDocumentBox").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 300,
            modal: true
        });

        $("#delete-document-modal").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 150,
            modal: true
        });
 
        $("#AddDocument").click(
        function () {
            $("#AddDocumentBox").dialog('open');
            return false;
        });

		$("#EditDocument").click( function () {
			document_id = $('tr.documentactive input').not("[type='file']").val();
			if(!document_id){
				alert('Select a Document');
			}
        });

        $("#DeleteDocument").click( function () {
            //document_id = $('tr.documentactive input').val();
            if(activeDocument){
                $("#delete-document-modal").dialog('open');
                $('#dev_document_id').val(activeDocument);
                return false;
            } 
            else{
                alert('Select a Document');
            } 
                    
        }
    );
		
        $("#close-botton").click(
        function () {
            $( "#delete-document-modal" ).dialog( "close" );
        }
    );
    });

    jQuery(document).ready(function()
    {
        /*
                $("#document_search").keyup(function()
                {
                        var search_val = $('#document_search').val();
                        var development_id = <?php echo $development_id; ?>;
	
                        $.ajax({
                                url: window.wbsBaseUrl + 'developments/development_document_search/' + development_id + '/' + encodeURIComponent(search_val),
                                type: 'POST',
                                success: function(data) 
                                {
                                        $(".document-table").empty();
                                        $(".document-table").append(data);
                                }
	
                        });
	
                });*/
	
    });
	
	        
    $(function() 
    {
        var projects = [ <?php
        $query = $this->db->query("SELECT * FROM construction_development_documents where development_id=$development_id order by filename_custom ASC");
        $dev_documents = $query->result();

        if ($dev_documents) {
            foreach ($dev_documents as $dev_document) {
                echo '{ label: "' . $dev_document->filename_custom . '", value: "' . $dev_document->filename_custom . '", desc: "' . date('d-m-Y', $dev_document->created) . '" },';
            }
            //$ppp = $ppp;
        }
        ?> ];
	 
            $( "#document_search" ).autocomplete({
                minLength: 0,
                source: projects,
                focus: function( event, ui ) {
                    $( "#document_search" ).val( ui.item.label );
                    return false;
                },
                select: function( event, ui ) {
                    $( "#document_search" ).val( ui.item.label );
                    //$( "#project-id" ).val( ui.item.value );
                    //$( "#project-description" ).html( ui.item.desc );
	        
                    var search_val = $( "#document_search" ).val();
                    var development_id = <?php echo $development_id; ?>;
	
                    $.ajax({
                        url: "<?php print base_url(); ?>constructions/development_document_search/" + development_id + '/' + encodeURIComponent(search_val),
                        type: 'POST',
                        success: function(data) 
                        {
                            $(".document-table").empty();
                            $(".document-table").append(data);
                        }
	
                    });
	
                    return false;
                }
            })
            .autocomplete( "instance" )._renderItem = function( ul, item ) {
                return $( "<li>" )
                .append( "<a>" + item.label + "<span style='float:right;'>" + item.desc + "</span></a>" )
                .appendTo( ul );
            };
        });
</script>