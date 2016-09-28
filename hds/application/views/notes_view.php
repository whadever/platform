
<script> 
    // using JQUERY's ready method to know when all dom elements are rendered
    $( document ).ready(function () {
      
	  
	$("#submitnote").click(function (e) {
            
        e.preventDefault();
        var note = $('#mynote').val();
        var rid = $('#request_id').val();
        //$('#mynote').val('');
        var value=$.trim(note);
        var myval = note.replace(/\n/g,"<br>")        
        var note_param = myval.replace(/\//g, "forward_slash");
        var note_param1 = note_param.replace(/#/g, "sign_of_hash");
        var note_param2 = note_param1.replace(/\?/g, "sign_of_intertogation");
		var note_param3 = note_param2.replace(/\+/g, "sign_of_plus");
		var note_param4 = note_param3.replace(/\!/g, "sign_of_exclamation");
		var note_param5 = note_param4.replace(/\%/g, "percentage");
		var note_param6 = note_param5.replace(/\\/g, "back_slash");


	$.ajax({  
        url:window.mbsBaseUrl + "notes/show_notes/"+rid+"/"+note_param6,  
        dataType: 'html',  
        type: 'GET',   
        success:function(data){						  
                         if(data){  	                    
                             //console.log(data);
                               // $("#text").append("Me : " + data + "<br />");
                                $("#notes_container").html(data);
                        }  
                }
	   });		
	   //$.get(url, function (note) {  $("#text").append("Me :" + note + "<br />");   });            
        
      });
	  
    });
  </script>
  <script type="text/javascript" >
 $(document).ready(function() { 
		
	$('#note_image').on('change', function(e){ 
         e.preventDefault();   
        $("#preview").html('');
	$("#preview").html('<img class="image-loader" src="'+window.mbsBaseUrl+'images/loader.gif" alt="Uploading...."/>');
	$("#notes-form").ajaxForm({
                target: '#preview',
                data: {
                    var1: $("#request_id").val()   //assuming #inputText is a text field
                },
                success: function() {
                    refresh_files();
                }
             
        }).submit();
     
 });    
       
       //var rid = $('#request_id').val();
      // refresh_files(rid);
       
       
});

window.onload=function(){
    var rid = $('#request_id').val();
    $.ajax({  
        url:window.mbsBaseUrl +"notes/show_notes_with_image/"+rid,  
        dataType: 'html',  
        type: 'GET',   
        success:function(data){						  
                         if(data){  	                    
                            
                              $("#notes_container").html(data);
                               
                        }  
                }
	   });
};


//$(document).ready(function() { 
function refresh_files()
{
   var rid = $('#request_id').val();
   $.ajax({  
        url:window.mbsBaseUrl +"notes/show_notes_with_image/"+rid,  
        dataType: 'html',  
        type: 'GET',   
        success:function(data){						  
                         if(data){  	                    
                            
                              $("#notes_container").html(data);
                               
                        }  
                }
	   });
  
}
//});
</script>

<style>
  
</style>

<div id="note_page">
  
<p><span style="background:#fff;font-size: 20px; padding: 5px 20px;">Request Notes</span>
    <span style="color:#fff;font-size: 20px; padding: 5px 20px;"> 
        Request - #<?php echo sprintf('%07d', $request_info->request_no); ?> - <?php echo $request_info->request_title ?>
    </span>
</p>
  <!--<textarea id="text" readonly>   </textarea> -->
  <div id="notes_container"> <?php echo $prev_notes; ?>   </div>
  <div class="clear"></div>
  
  <!-- <button id="button"> Get Time </button> -->
  
<?php 
$action= base_url().'notes/upload_note_image';
$form_attributes = array('class' => 'notes-add-form', 'id' => 'notes-form', 'method'=>'post');
echo form_open_multipart($action, $form_attributes); 
?>
   <input type="hidden" name="request_id" id="request_id" value="<?php echo $request_id?>"/>
   <table border="0" class="" width="100%">
       <tr>
           <td><input type="text" name="note" id="mynote" size="100"/></td>
           <td> <input type="file" name="note_image" id="note_image"><img style="margin-left:-50px;" width="50" border="0" src="<?php echo base_url();?>images/camera.png"/> <div id='preview'> </div></td>   
           <td> <input style="height:50px;width:100%;" type="submit" id="submitnote" value="Send"/></td>
       </tr>
   </table>
   
  
  
   
<?php 	echo form_close(); ?>

</div>     
   
   
   
   <?php

/*foreach ($prev_notes as $notes) {
        if($notes->notes_by==$user_id)
        {
           $showuser= 'Me'; 
           $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
           $align_class = 'right';
           
           echo '<div class="'.$align_class.'"><span class="time-left">'.$creation_time.'</span><br/><div class="userme"> :'.$showuser.'</div> <div style="float: right;" class="notes_body">'.$notes->notes_body.'</div> </div>';
           echo '<div class="clear"></div>';

        }else{
            $showuser= $notes->name; 
            $creation_time= date('g:i a d/m/Y', strtotime($notes->created));
            $align_class ='left';
            echo '<div class="'.$align_class.'"><span class="time-right">'.$creation_time.'</span><br/><div class="useranother">'.$showuser.':</div>  <div style="float: left;" class="notes_body">'.$notes->notes_body.'</div></div>'; 
            echo '<div class="clear"></div>';
        }   
}*/
?>
