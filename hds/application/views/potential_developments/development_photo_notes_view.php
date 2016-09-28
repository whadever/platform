<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-select.css">
  <script type="text/javascript" src="<?php echo base_url();?>/js/bootstrap-select.js"></script>
 
<?php

$user = $this->session->userdata('user');
$user_app_role_id = $user->application_role_id; 

if($user_app_role_id==2 || $user_app_role_id==3 || $user_app_role_id==4){
	$per = '';
}else{
	$per = 'style="display:none;"';
}

?> 
  <script> 
    // using JQUERY's ready method to know when all dom elements are rendered
    $( document ).ready(function () {      
	  
	$("#submitnote").click(function (e) {
            
        e.preventDefault();
        var note = $('#mynote').val();
        var userid = $('#assign_user_select').val();
        
        var value=$.trim(note);
        var myval = note.replace(/\n/g,"<br>")        
        
        
        //var note_value=encodeURI(note);
        
        if(value.length == 0){
            alert('Please enter your note.');
        }else{
        var rid = $('#request_id').val();
        $('#mynote').val('');
        
        $('#notify_user_checkbox').removeAttr('checked');
        
        $('#assign_user_select').prop('defaultSelected'); 
        $('#user_select_list').css("display","none");
        
        $.ajax({  
        url:"<?php echo base_url();?>potential_developments/insert_photo_notes/"+rid+"/"+userid,  
        dataType: 'html',
		data: { notes: myval },  
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
        }
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
        url:"<?php echo base_url();?>potential_developments/show_photo_notes_with_image/"+rid,  
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
       url:"<?php echo base_url();?>potential_developments/show_photo_notes_with_image/"+rid,  
        dataType: 'html',  
        type: 'GET',   
        success:function(data){						  
                         if(data){  	                    
                            
                              $("#notes_container").html(data);
                               
                        }  
                }
	   });
  
}
$(document).ready(function() {
    $("div").on("mouseover", ".right", function() {  
        $(this).addClass("divhover");    
    });
     $("div").on("mouseout", ".right", function() {  
        $(this).removeClass("divhover");    
    });
     $("div").on("mouseover", ".left", function() {  
        $(this).addClass("divhover");    
    });
     $("div").on("mouseout", ".left", function() {  
        $(this).removeClass("divhover");    
    });
    
}); 
function notesDelete(noteid){
    //alert(noteid);
    var pid = $('#request_id').val();   
    var r = confirm("Are you sure you want to delete this note?");

    if (r == true) {
        $.ajax({  
            url:"<?php echo base_url();?>potential_developments/photo_notes_delete/"+pid+"/"+noteid,  
            dataType: 'html',  
            type: 'GET',   
            success:function(data){						  
                if(data){  
                            
                    $("#notes_container").html(data);
                               
                 }  
            }
	});
    } else {
        return false;
    } 
   
}
</script>

<style>
 .submitnote {
    background: url(<?php echo base_url(); ?>images/send.png) no-repeat;
    cursor: pointer;
    background-size: 50px 50px;
	margin: 5px 10px;
	padding: 0;
	border:0;
} 

</style>

<?php


        $ci = & get_instance();
	$ci->load->model('developments_model'); 
        $user_option = $ci->potential_developments_model->get_user_list();
        
        $user_default= 0;
        $user_js = 'id="assign_user_select" class="multiselectbox"';	      
	$assign_user = form_multiselect('assign_user_id[]', $user_option, $user_default, $user_js);

?>
 
<div id="note_page" style="background:none !important;width:100%;">
  
    
        <div id="note_page_header_photo" style="background:#002855; text-align: center; color: #fff; font-size:20px; padding:5px;border-radius:5px;"> 
            Development Photo Notes</div>
  <div class="row1"> 
<div class="notes_box" style="width:50%; float:left;"> 
<p>&nbsp;</p>
<img width="450px" src="<?php echo base_url(); ?>uploads/development/<?php echo $photo->filename; ?>" /> 
</div>
<div class="notes_box" style="width:50%; float:left;"> 
  <!--<textarea id="text" readonly>   </textarea> -->
  <div id="notes_container"> <?php  echo $prev_notes;  ?>   </div>
  <div class="clear"></div>
  
  <!-- <button id="button"> Get Time </button> -->
  <div <?php echo $per; ?> id="note-bottom" style="background:#fff;border-bottom-left-radius: 8px;border-bottom-right-radius: 8px;"> 
      <div>
    <?php 
    $action= base_url().'notes/upload_note_image';
    $form_attributes = array('class' => 'notes-add-form', 'id' => 'notes-form', 'method'=>'post');
    echo form_open_multipart($action, $form_attributes); 
    ?>
       <input type="hidden" name="dev_id" id="dev_id" value="<?php echo $development_id;?>"/>
	   <input type="hidden" name="request_id" id="request_id" value="<?php echo $photo->id;?>"/>

       <table border="0" class="" width="100%" style="background:#fff;">
           <tr>
               <td style="width: 100%;"><textarea id="mynote" cols="150" rows="3"></textarea> </td>

               <td> <input style="height:50px;width:50px;" type="button" class="submitnote" id="submitnote" value=""/></td>
                 
           </tr>
       </table>




    <?php 	echo form_close(); ?>
      </div>
      <div id="notify_user_select_box" style="padding: 5px; border-top:1px solid #002855">
          
          <input id="notify_user_checkbox" type="checkbox"/>
          <span style="">Notify User</span> 
          <span id="user_select_list" style="display:none;"><?php echo $assign_user; ?></span>
          
      </div>
	</div> 
	</div>
  </div><!-- end div row -->
  
</div>     

   
   
 
<script>    

 $( document ).ready(function () {
       $('#notify_user_checkbox').click(function() {
            $("#user_select_list").toggle(this.checked);
        });
        $('#assign_user_select').selectpicker();
    });

</script>