<?php $note_project_id= isset($note->project_id)? $note->project_id: $project_id;?>
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
        <div id="notes_load_box" style="padding:5px;"></div>
    </div>
    <div id="devlopment-notes-search" class="notes-box" style="text-align: center">
        <div class="box-title">Search Notes</div> 
        <form method="post" action="<?php echo base_url();?>project/search_development_notes/<?php echo $note_project_id ?>">
        <input name="search_notes" placeholder="search notes" style="width: 90%; margin:10px;" type="text" value=""/><br/><br/>
        </form>
        <a id="btnaddnewnote" class="btn btn-default" href="#">Add New Note</a> <br/><br/>
        <a class="btn btn-info" href="#">Email Note</a><br/>
    </div>
    <div class="clear"></div>
    
    <div id="add-note-dialog-box" title="">
    
    
       
        <form id="addnoteform" action="<?php echo base_url();?>project/save_development_note/<?php echo $note_project_id;?>" method="post">
        <span>New Note title</span><br/>
        <input name="notes_title" type="text" value="" style="width:300px"/> <br/><br/>
        <textarea name="notes_body" style="width: 400px; height: 200px;"></textarea>

        <input type="submit" value="Save"/>
        </form>
        
   
    </div>
</div>

<script>
    function loadNotes(note_id){
        
        $.ajax({  
                url: "<?php print base_url(); ?>project/development_notes_details/"+note_id,  
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
 
        $("#btnaddnewnote").click(
            function () {
                $("#add-note-dialog-box").dialog('open');
                return false;
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