<?php 
$note_development_id= isset($note->development_id)? $note->development_id: $development_id;
if(isset($_REQUEST['sent_email'])){
	$email_sent_status = $_REQUEST['sent_email'];
	if($email_sent_status==1){?> <script>alert("Message Sent successfully")</script> <?php }
	if($email_sent_status==0){?> <script>alert("Message didn't Sent")</script><?php }
}


$add_new_note = array(
          'src' => base_url().'images/add_photo.png',
          'alt' => 'Add New Note',
          'class' => 'note-developments',
          'width' => '50',          
          'title' => 'Add New Note',
          'style'=>''
          
);
$email_note = array(
           'src' => 'images/icon/btn_horncastle_mail.png',
          'alt' => 'Email Note',
          'class' => 'email-note',
          'width' => '50',          
          'title' => 'Email Note',
          'style'=>''
			//'onclick'=>'email_this_notes()'
          
);
?>
<div id="development-notes">
    <div id="devlopment-notes-list" class="notes-box" >
        <div class="box-title">Notes </div>
        <div id="notes_list_box">
            <ul>
                
           
        <?php
        foreach($notes as $note){
            ?>
            <li>
            <div class="notes_list_item" style="border-bottom: 1px solid #ccc; padding:0 5px;" onClick="loadNotes(<?php echo $note->nid;?>)">
                <div class="notes_title" style="">
                   <?php echo $note->notes_title;  ?>
                   <input type="hidden" value="<?php echo $note->nid;?>"/>
                </div>          
                <div class="notes_bottom">
                   <div class="notes_bottom-left" style="float:left;">
                       <?php echo date('d-m-Y', strtotime($note->created)); echo '<br />'; echo date("h:i a", strtotime($note->created)); ?>
                       <?php  ?>
                    </div>
                   <div class="notes_bottom-right" style="float:right;">
                        <?php  echo $note->username;  ?>
                   </div>
                   <div class="clear"></div>
                </div>
           </div>
           </li>
          <?php }   ?> </ul>
          </div>
    </div>
    <div id="devlopment-notes-detail" class="notes-box" >
        <div class="box-title">Notes Detail</div> 
        <div id="notes_load_box" style="padding:5px; height:350px; overflow:auto; ">
		<?php 

				foreach($developments_notes as $developments_note){
				echo '<p>Subject: '.$developments_note->notes_title.'</p>';
		        echo '<p>';
		        echo date('d-m-Y', strtotime($developments_note->created)); echo '&nbsp; &nbsp;&nbsp; ';
		        echo date("h:i a", strtotime($developments_note->created));
		        echo '<span style="float:right">Author :'.$developments_note->username.'</span>'; 
		        echo '</p>';
		        echo '<hr style="margin-top:0px;"/>';
		        echo '<p>'.$developments_note->notes_body.'</p>';
				}

		?>
		</div>
    </div>
    <div id="devlopment-notes-search" class="notes-box" style="text-align: center">
        <div class="box-title">Search Notes</div> 
        <form method="post" action="<?php echo base_url();?>developments/search_development_notes/<?php echo $note_development_id ?>">
        <input name="search_notes" placeholder="search notes" style="width: 90%; margin:10px;" type="text" value=""/><br/><br/>
        </form>
        <a id="btnaddnewnote" class="" href="#"><?php echo img($add_new_note); ?></a> 
        <a id="emailnotemessage" class="" href="#">  <?php echo img($email_note); ?></a>
       
        
    </div>
    <div class="clear"></div>
    
    <div id="add-note-dialog-box" title="">
    
    
       
        <form id="addnoteform" action="<?php echo base_url();?>developments/save_development_note/<?php echo $note_development_id;?>" method="post">
        <span>New Note title</span><br/>
        <input name="notes_title" type="text" value="" style="width:300px"/> <br/><br/>
        <textarea name="notes_body" style="width: 400px; height: 200px;"></textarea>

        <input type="submit" value="Save"/>
        </form>
        
   
    </div>
    
    <div id="note-message-dialog-box" title="">    
       
        <form id="addmessageform" action="<?php echo base_url();?>developments/send_development_note_message/<?php echo $note_development_id;?>" method="post">
        
        <input type="hidden" id="note_id" name="note_id" value=""/>
       <span>Reply Message</span><br/>
        <textarea name="notes_message" style="width: 400px; height: 200px;"></textarea>

        <input type="submit" value="Send"/>
        </form>
        
   
    </div>
    
    
</div>

<script>
    function loadNotes(note_id){
        
        $.ajax({  
                url: "<?php print base_url(); ?>developments/development_notes_details/"+note_id,  
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
        $("#note-message-dialog-box").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 350,
            modal: true
        });
 
        $("#btnaddnewnote").click(
            function () {
            	
                $("#add-note-dialog-box").dialog('open');
                return false;
            }
        );

        $("#emailnotemessage").click(
        		
                function () {
                	note_id = $('li.notesactive input').val();
                	
                	if(note_id){
                		$("#note-message-dialog-box").dialog('open');
                		$('#note_id').val(note_id);
                		return false;
                    } 
                	else{
						alert('Select a Note');
                    } 
                    
                }
            );
  
	$('li').click(function() {
            $('li').removeClass('notesactive'); 
            $(this).addClass('notesactive'); 
        });	

	
            
 });

 function email_this_notes(){
	 note_id = $('li.notesactive input').val();

	 $.ajax({
         url: "<?php print base_url(); ?>developments/email_development_notes/"+note_id,  
             dataType: 'html',  
             type: 'GET',                
             success:     
             function(data){               
              	if(data){                
                	alert(data);
             }  
     	}
     });
	 
}
    
</script>

<style>
    ul li{list-style-type: none;}
    li.notesactive{
        background: #ECEBF0;
    }
	#devlopment-notes-search > a {
	    float: left;
	    padding: 0 36% 5px;
	    text-align: left;
	    width: 100%;
	}
</style>