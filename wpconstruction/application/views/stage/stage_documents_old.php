<style>

.link_active{background:none repeat scroll 0 0 #ccc;border-radius:10px;display:block;height:30px;margin:5px;padding:5px;}
.link_normal{display:block;height:30px;margin:5px;padding:5px;}
.delete-document{background:#cac9c9;border-radius:5px;padding:5px;}
#delete-document-modal .modal-body{padding:0;}
#delete-document-modal .modal-footer{padding: 10px 0 0;}

</style>  
<?php 
$note_development_id= isset($note->development_id)? $note->development_id: $development_id;

$add_new_note = array(
          'src' => base_url().'images/icon/upload_document_icon.png',
          'alt' => 'New document Upload',
          'class' => 'note-developments',
          'width' => '45',          
          'title' => 'New document Upload',
          'style'=>''
          
);


$email_document_img = array(
         	'src' => 'images/icon/btn_horncastle_mail.png',
          	'alt' => 'Email Document',
          	'class' => 'email-document',
          	'width' => '40',
			'height' => '40',          
          	'title' => 'Email This Document',
          	'style'=>''
          
);
$email_document_button= array(
              'name'        => 'savedocument',
              'id'          => 'btnSaveSocument',
             'class'          => 'btn-photo',
             'content' => img($email_document_img),
            'title'=>'Email This Document'
            );


$save_document_button = array(
              'name'        => 'savedocument',
              'id'          => 'btnSaveSocument',
             'class'          => 'btn-photo',
             'content' => '<img border="0" width="40" height="40" src="'.base_url().'images/icon/btn_horncastle_save.png"/>',
            'title'=>'Save This Document'
            );
        
        $print_document_button = array(
              'name'        => 'printdocument',
              'id'          => 'btnPrintDocument',
             'class'          => 'btn-photo',
             'content' => '<img border="0" width="40" height="40" src="'.base_url().'images/icon/btn_horncastle_printer.png"/>',
            'title'=>'Print This Documents'
            );
            
            
?>
<div id="development-notes">

	 <div id="stage-doc" class="notes-box" style="text-align: center; width:18%;">
        <div class="box-title">Type</div> 
        <div>
        	<ul>
        		<li style="border-bottom: 1px solid;">
        			<a class="<?php if(isset($category_id)){if($category_id==1)echo 'link_active'; else echo 'link_normal';}else { echo 'link_active'; }  ?>" href="<?php echo base_url().'stage/stage_documents_bycategory/'.$development_id.'/'.$stage_id; ?>/1"> Financial</a>  
        		</li>
        		<li style="border-bottom: 1px solid;">
        			<a class="<?php if(isset($category_id)){if($category_id==2) echo 'link_active'; else echo 'link_normal';}else echo 'link_normal'; ?>" href="<?php echo base_url().'stage/stage_documents_bycategory/'.$development_id.'/'.$stage_id; ?>/2"> Land </a> 
        		</li>
        	</ul>
        </div>
        <div style="margin-top: 200px;">
        	<span>Add New</span><br/><hr style="margin:5px;"/>
        	 <a id="btnaddnewnote" class="" href="#"><?php echo img($add_new_note); ?></a>
        </div>
        	
        
       
    </div>
    
    
    
    <div id="documents-list" class="notes-box" style=" width:25%;" >
        <div class="box-title">Documents List </div>
        <div id="notes_list_box">
            <ul>
                
           
        <?php
        foreach($documents as $document){
        	
            ?>
            <li>
            <div class="notes_list_item" style="border-bottom: 1px solid #ccc; padding:5px 5px;" onClick="loadNotes(<?php echo $document->id;?>)">
                <div class="document_title" style="text-align:center; font-weight:bold;">
                   <?php echo $document->filename_custom;  ?>
                </div>          
                <div class="notes_bottom">
                   <div class="notes_bottom-left" style="float:left;">
                     <?php  echo $document->username;  ?> 
                      
                    </div>
                   <div class="notes_bottom-right" style="float:right;">
                        
                         <?php echo date('Y-m-d', $document->created); 
                        
                         ?>
                   </div>
                   <div class="clear"></div>
                </div>
				<input type="hidden" value="<?php echo $document->id; ?>">
           </div>
           </li>
          <?php }   ?> </ul>
          </div>
    </div>
    <div id="documents-detail" class="notes-box" style="width:47%;"  >
        <div class="box-title">Documents Detail</div> 
        <div id="notes_load_box" style="padding:0px; width:100%; height:350px; margin:0px;">
        <?php 
			foreach($stage_documents as $stage_document){
				?>

				<object data="<?php echo base_url();?>uploads/stage/documents/<?php echo $stage_document->filename; ?>" type="application/pdf" width="100%" height="100%">
 
  <p>It appears you don't have a PDF plugin for this browser.
  You can <a href="<?php echo base_url();?>uploads/stage/documents/<?php echo $stage_document->filename; ?>">click here to
  download the PDF file.</a></p>
				</object>

				
			<?php }
		?>
       

		
        </div>
    </div>
    
    <div id="stage-button" class="notes-box" style="text-align: center; width:10%;">
        <div class="box-title"> </div> 
        <?php echo form_button($print_document_button); ?>
        <?php echo form_button($email_document_button); ?>	
        <?php echo form_button($save_document_button); ?>
        <button title="Delete This Document" class="btn-photo delete-document" id="btnDeleteDocument" type="button" name="savedocument">			
				<img width="30" title="Delete This Document" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png">		
		</button>
       
    </div>
   
    <div class="clear"></div>
    
<div id="delete-document-modal">
	<div class="modal-body">
		<p>Are you sure want to delete this Stage Document?</p>
	</div>
	<div class="modal-footer">
		<form action="<?php echo base_url();?>stage/stage_document_delete" method="post">
			<input type="hidden" id="dev_id" name="dev_id" value="<?php echo $development_id;?>"/>
			<input type="hidden" id="stage_no" name="stage_no" value="<?php echo $stage_id;?>"/>
			<input type="hidden" id="stage_document_id" name="stage_document_id" value=""/>
			<input id="delete-document-stage" class="btn" type="submit" value="Ok"/>
		</form>
		
		<div class="clear"></div>
	</div>

</div>     
    
    <div id="add-note-dialog-box" title="">

		<div class="doc_add_title" style="text-align:center; margin:0px 0px 20px 0px"><h4>Add Stage Document</h4></div>
    
    	<?php 
    	$form_attributes = array('class' => 'addform', 'id' => 'addnoteform');
    	$label_attributes = array(
    		'class' => 'col-sm-4 control-label',
    		'style' => 'color: #000;',
		);
$file_title = array(
              'name'        => 'file_title',
              'id'          => 'file_title',
              'value'       => '',
              'maxlength'   => '100',
              'size'        => '50',
              'style'       => 'width:50%',
              'class'       => 'form-control',
            );
$upload_document = array(
              'name'        => 'upload_document',
              'id'          => 'upload_document',
              'type'       => 'file',
              
            );
$dropdown_js = 'class="form-control" style="width:50%"';

    	$action =  base_url().'stage/save_stage_document/'.$development_id.'/'.$stage_id;
    	echo form_open_multipart($action, $form_attributes);
    	echo '<div class="form-group">';
    	echo form_label('Document Title:', 'file_title', $label_attributes);    	
    	echo form_input($file_title);
    	echo '</div>';
    	
    	echo '<div class="form-group">';
    	echo form_label('Document File:', 'upload_document', $label_attributes); 
    	echo form_upload($upload_document);
    	echo '</div>';
    	
    	echo '<div class="form-group">';
    	echo form_label('Document Category:', 'document_category', $label_attributes);
    	$options = array(
                  '0'  => '-- Select Category --',
                  '1'    => 'Financial',
                  '2'   => 'Land',
                  
                );

			//$shirts_on_sale = array('small', 'large');

		echo form_dropdown('document_category', $options, 0, $dropdown_js);
		echo '</div>';
		
		echo '<div class="form-group">';
		echo form_label('', 'document_submit', $label_attributes);
    	echo form_submit('document_submit', 'Upload File', "class='btn btn-default'"); 
    	echo '</div>';
    	
    	echo form_close();
    	
    	?>
       
       
        
   
    </div>
</div>

<script>
    function loadNotes(document_id){
        
        $.ajax({  
                url: "<?php print base_url(); ?>stage/stage_document_detail/"+document_id,  
                dataType: 'html',  
                type: 'GET',  
                 
                success:     
                function(data){  
                 //console.log(data);
                 if(data){  
                     //jQuery('#subcompname-wrapper').append(data);
                     jQuery('#notes_load_box').empty();
                     jQuery('#notes_load_box').append(data);
                     
                   
                 }  
                }
               });
        
    }
</script>

<script type="text/javascript">
    
 $(document).ready(function () {
        $("#add-note-dialog-box").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 350,
            modal: true
        });

		$("#delete-document-modal").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 150,
            modal: true
        });
 
        $("#btnaddnewnote").click(
            function () {
                $("#add-note-dialog-box").dialog('open');
                return false;
            }
        );

		$("#btnDeleteDocument").click(
        		
                function () {
                	document_id = $('li.notesactive input').val();
                	
                	if(document_id){
                		$("#delete-document-modal").dialog('open');
                		$('#stage_document_id').val(document_id);
                		return false;
                    } 
                	else{
						alert('Select a Document');
                    } 
                    
                }
         );
  
	$('li').click(function() {
            $('li').removeClass('notesactive'); 
            $(this).addClass('notesactive'); 
        });	
            
 });
        
</script>

<style>
    ul li{list-style-type: none;}
    li.notesactive{
        background: #ECEBF0;
    }
</style>