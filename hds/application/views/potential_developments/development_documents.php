<?php
$user = $this->session->userdata('user');
$user_app_role_id = $user->application_role_id; 

if($user_app_role_id==2 || $user_app_role_id==4){
	$per = '';
}else{
	$per = 'style="display:none;"';
}

?>
<div id="development-notes" class="document">

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
							<th></th><th>Document</th><th>Date Added</th>
						</tr>
					</thead>
					<tbody>
					<?php
        				foreach($documents as $document)
						{	
            		?>
						<tr id="document_<?php echo $document->id;?>" onClick="loadDocument(<?php echo $document->id;?>)">
							<td><a <?php echo $per; ?> href="#EditDoc_<?php echo $document->id; ?>" role="button" data-toggle="modal"><img width="16" height="16" src="<?php echo base_url();?>icon/icon_edit.png" /></a></td>
							<td><?php echo $document->filename_custom;  ?></td>
							<td><?php echo date('d-m-Y', $document->created); ?></td>
							<input type="hidden" value="<?php echo $document->id; ?>">
						</tr>
					<?php
						}
					?>
					</tbody>
				</table>

				<?php
        			foreach($documents as $document)
					{	
            	?>
				<!-- MODAL Phase Delete-->
					<div id="EditDoc_<?php echo $document->id; ?>" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					<form class="form-horizontal" action="<?php echo base_url();?>potential_developments/development_document_update/<?php echo $document->id; ?>" method="POST">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
							<h3 id="myModalLabel">Edit Document</h3>
						</div>
						<div class="modal-body">
							<div class="control-group">
								<label class="control-label" for="filename_custom">Document Title </label>
								<div class="controls">
									<input type="text" id="filename_custom" name="filename_custom" value="<?php echo $document->filename_custom; ?>">
								</div>
							</div>
						</div>
						<div class="modal-footer delete-task">
							<input type="submit" value="Save" name="submit" class="btn" />
							<input type="hidden" value="<?php echo $development_id; ?>" name="development_id" />
						</div>
					</form>
					</div>
				<!-- MODAL Phase Delete-->
				<?php } ?>
			</div>
		</div>
	</div>
	<div class="document-right">
		<div class="document-right-top">
			<div class="document-button">
					<input type="hidden" id="ScreenDocument" value="0">
					<a href="#LoadDoc" role="button" data-toggle="modal" title="Full Screen Document" class="full-screen-document" id="full-screen-document">			
						<img width="50" height="50" border="0" src="<?php echo base_url();?>icon/duc_view_icon.png">		
					</a>
					<a <?php echo $per; ?> href="#" title="Delete This Document" class="delete-document" id="DeleteDocument">			
						<img width="50" height="50" border="0" src="<?php echo base_url();?>icon/delete_document.png">		
					</a>
					<a href="#" title="Print This Documents" class="print-document">
						<img width="50" height="50" border="0" src="<?php echo base_url();?>icon/print_document.png">
					</a>
					<a href="#" title="Save This Document" class="save-document">
						<img width="50" height="50" border="0" src="<?php echo base_url();?>icon/download_document.png">
					</a>
					<a <?php echo $per; ?> href="#" title="Add Document" class="add-document" id="AddDocument">
						<img width="50" height="50" border="0" src="<?php echo base_url();?>icon/add_document.png">
					</a>
			<div class="clear"></div>			
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
		<div class="document-right-bottom">
			<div class="document-detail">
				<?php 
					foreach($developments_documents as $developments_document){
		
				?>
					<object data="<?php echo base_url();?>uploads/development/documents/<?php echo $developments_document->filename; ?>" type="application/pdf" width="100%" height="100%">
	 
	  <p>It appears you don't have a PDF plugin for this browser.
	  You can <a href="<?php echo base_url();?>uploads/development/documents/<?php echo $developments_document->filename; ?>">click here to
	  download the PDF file.</a></p>
					</object>					
						
				<?php 
					}
				?>  
			</div>
		</div>
	</div>
	<div class="clear"></div>
<?php 
	foreach($developments_documents as $developments_document){
?>

	<div id="LoadDoc" class="modal hide fade stage-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">					
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">x</button>
			<h3 id="myModalLabel">&nbsp;</h3>
		</div>
		<div class="modal-body" id="model-document-detail">
			<object data="<?php echo base_url();?>uploads/development/documents/<?php echo $developments_document->filename; ?>" type="application/pdf" width="100%" height="100%">
	 
	  <p>It appears you don't have a PDF plugin for this browser.
	  You can <a href="<?php echo base_url();?>uploads/development/documents/<?php echo $developments_document->filename; ?>">click here to
	  download the PDF file.</a></p>
					</object>
		</div>
	</div>

<?php 
	}
?> 

	<div id="delete-document-modal">
		<div class="modal-body">
			<p>Are you sure want to delete this Development Document?</p>
		</div>
		<div class="modal-footer">
			<form action="<?php echo base_url();?>potential_developments/development_document_delete" method="post">
				<input type="hidden" id="dev_id" name="dev_id" value="<?php echo $development_id;?>"/>
				<input type="hidden" id="dev_document_id" name="dev_document_id" value=""/>
				<input id="delete-document-dev" class="btn" type="submit" value="Ok"/>
			</form>
			
			<div class="clear"></div>
		</div>	
	</div>  

	<div id="error-document-modal">
		<div class="doc_add_title" style="border-bottom: 1px solid #eee;color: #666;font-family: arial;padding: 0px 0 10px 25px;text-align: left;"><h4>Incorrect File</h4></div>
		<div class="modal-body" style="padding:8px 25px;">
			<p style="text-align: center; color: #666; font-family: arial; font-size: 13px;">The file you are attempting to upload the incorrect type. Please ensure you are uploading a PDF, and try again.</p>
		</div>
		
	</div>   
    
    
    <div id="add-note-dialog-box" title="">
		
		<div class="doc_add_title" style="margin: -9px 0 20px 10px;text-align: left;"><h4>Add Document</h4></div>
<style>
.ui-dialog #error-document-modal.ui-dialog-content {
    padding: 0;
}
#add-note-dialog-box .form-control:focus{box-shadow: 0 0 0 0;}
#add-note-dialog-box .btn.btn-default {
    background: #ecebf0;
    border: 0 none;
    border-radius: 0;
}
#add-note-dialog-box .btn.btn-default:hover {
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
              'name'        => 'file_title',
              'id'          => 'file_title',
              'value'       => '',
              'maxlength'   => '100',
              'size'        => '50',
              'style'       => 'width:60%;border: 2px solid #ecebf0;border-radius: 0;box-shadow: 0 0 0;',
              'class'       => 'form-control',
            );
		$upload_document = array(
              'name'        => 'upload_document',
              'id'          => 'upload_document',
              'type'       => 'file',
			  'class'       => 'file',
              
            );
		$dropdown_js = 'class="form-control" style="width:60%;border: 2px solid #ecebf0;border-radius: 0;"';
		
    	$action =  base_url().'potential_developments/save_development_document/'.$development_id;
    	echo form_open_multipart($action, $form_attributes);
		
		echo '<div id="error">';

    	echo '</div>';

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
  		echo '<input type="file" name="upload_document" class="file_input_hidden" onchange="'.$onchange.'" onmouseover="'.$onmouseover.'" onmouseout="'.$onmouseout.'" />';
		echo '</div>';
		echo '</div>';
		echo '<div style="text-align: right; width: 93%;"><span  style="font-size: 70%;">Files accepted: PDF<!-- | Max. size: 5MB--></span></div>';
    	echo '</div>';
    	
    	/*echo '<div class="form-group">';
    	echo form_label('Document Category:', 'document_category', $label_attributes);
    	$options = array(
				  '0'  => '-- Select Category --',
                  '1'    => 'Financial',
                  '2'   => 'Land',
                  
                );

			//$shirts_on_sale = array('small', 'large');

		echo form_dropdown('document_category', $options, 0, $dropdown_js);
		echo '</div>';*/

		echo '<div class="form-group">';
    	echo form_label('Notify User', 'notify_user', $label_attributes);
		echo '<select name="notify_user" id="notify_user" class="form-control" style="width:60%;border: 2px solid #ecebf0;border-radius: 0;box-shadow: 0 0 0;">';
		echo '<option value="">-- Select User --</option>';

		$user=  $this->session->userdata('user'); 
		$wp_company_id =$user->company_id;

		$this->db->where('users.company_id', $wp_company_id);
		$this->db->where('application_id', '1');
		$this->db->where_in('application_role_id', array('2','3','4'));
		$this->db->join('users', 'users.uid = users_application.user_id', 'left');
		$this->db->order_by('username', 'ASC');
		$results = $this->db->get('users_application')->result();	
		foreach($results as $row)
		{
			echo '<option value="'.$row->uid.'">'.$row->username.'</option>';
		}

		echo '</select>';
		echo '</div>';


		echo '<div class="form-group">';
		echo '<input type="hidden" value="'.$this->uri->segment(2).'" name="url">';
		echo '<input type="hidden" value="'.$this->uri->segment(4).'" name="file_category">';
		echo form_label('', 'document_submit', $label_attributes);
    	echo form_submit('document_submit', 'Add', "class='btn btn-default'"); 
    	echo '</div>';
    	
    	echo form_close();
    	
    	?>
       
       
        
   
    </div>
</div>

<script>

    function loadDocument(document_id){

        $('tr').removeClass('documentactive'); 
        $('#document_' + document_id).addClass('documentactive'); 

        $.ajax({  
                url: "<?php print base_url(); ?>potential_developments/development_document_detail/"+document_id,  
                dataType: 'html',  
                type: 'GET',  
                 
                success:     
                function(data){  
                 //console.log(data);
                 if(data){  
                     //jQuery('#subcompname-wrapper').append(data);
                     jQuery('.document-detail').empty();
                     jQuery('.document-detail').append(data);

					 jQuery('#model-document-detail').empty();
                     jQuery('#model-document-detail').append(data);
                   
                 }  
                }
         });

		
        
    }
</script>


<?php if($_GET['error']==1){ ?>

<script type="text/javascript">
    
	$(document).ready(function () {
        $("#error-document-modal").dialog({ 
            autoOpen: true,
            width : 520, 
            height: 200,
            modal: true,
			buttons: {
		        Dismiss: function() {
		          $( this ).dialog( "close" );
		        }
		      }
        });
	});
</script>

<?php }else{ ?>
<script type="text/javascript">
    
	$(document).ready(function () {
        $("#error-document-modal").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 200,
            modal: true,
			buttons: {
		        Dismiss: function() {
		          $( this ).dialog( "close" );
		        }
		      }
        });
	});
</script>
<?php } ?>

<script type="text/javascript">
    
	$(document).ready(function () {

		

        $("#add-note-dialog-box").dialog({ 
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
                $("#add-note-dialog-box").dialog('open');
                return false;
            }
        );

		$("#DeleteDocument").click(
        		
                function () {
                	document_id = $('tr.documentactive input').val();
                	
                	if(document_id){
                		$("#delete-document-modal").dialog('open');
                		$('#dev_document_id').val(document_id);
                		return false;
                    } 
                	else{
						alert('Select a Document');
                    } 
                    
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
				url: window.wbsBaseUrl + 'potential_developments/development_document_search/' + development_id + '/' + encodeURIComponent(search_val),
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
	
		$query = $this->db->query("SELECT * FROM potential_development_documents where development_id=$development_id order by filename_custom ASC");
		$dev_documents = $query->result();
	
		if($dev_documents)
		{
			foreach($dev_documents as $dev_document)
			{
				echo '{ label: "'.$dev_document->filename_custom.'", value: "'.$dev_document->filename_custom.'", desc: "'.date('d-m-Y', $dev_document->created).'" },';
				 
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
				url: window.wbsBaseUrl + 'potential_developments/development_document_search/' + development_id + '/' + encodeURIComponent(search_val),
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

	function ScreenDocument()
	{
		var id = $( "#ScreenDocument" ).val();
		if(id==0){
			$(".document-left").css('display','none');
			$(".document-right").css('width','100%');
			$("#ScreenDocument").val('1');
		}else{
			$(".document-left").css('display','block');
			$(".document-right").css('width','50%');
			$("#ScreenDocument").val('0');
		}
	}
</script>
