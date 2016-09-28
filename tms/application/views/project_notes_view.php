<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-select.css">
  <script type="text/javascript" src="<?php echo base_url();?>/js/bootstrap-select.js"></script>
<script> 
    // using JQUERY's ready method to know when all dom elements are rendered
    $( document ).ready(function () {
      
	  
	$("#submitnote").click(function (e) {
            
        e.preventDefault();
        var note = $('#mynote').val(); 
        var userid = $('#assign_user_select').val();
        
        var value=$.trim(note);
        var mynote = note.replace(/\n/g,"<br>")        
        
       
        
        if(value.length == 0){
            alert('Please enter your note.');
        }else{
        var pid = $('#project_id').val();
        $('#mynote').val('');
        
        $('#notify_user_checkbox').removeAttr('checked');        
        $('#assign_user_select').prop('defaultSelected'); 
        $('#user_select_list').css("display","none");
        
        
        $.ajax({  
        url:window.mbsBaseUrl + "project_notes/show_notes/"+pid+"/"+userid,  
        dataType: 'html', 
 		data: { notes: mynote},
        type: 'GET',   
        success:function(data){						  
                         if(data){  	                    
                             //console.log(data);
                               // $("#text").append("Me : " + data + "<br />");
                                $("#notes_container").html(data);
                        }  
                }
	   });		
	              
       }
    });

    String.prototype.nl2br = function()
        {
            return this.replace(/\n/g, "<br />");
        }
	  
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
                    var1: $("#project_id").val()   //assuming #inputText is a text field
                },
                success: function() {
                    refresh_files();
                }
             
        }).submit();
     
 });    
       
       //var rid = $('#project_id').val();
      // refresh_files(rid);
       
       
});

window.onload=function(){
    var rid = $('#project_id').val();
    $.ajax({  
        url:window.mbsBaseUrl +"project_notes/show_notes_with_image/"+rid,  
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
   var rid = $('#project_id').val();
   $.ajax({  
        url:window.mbsBaseUrl +"project_notes/show_notes_with_image/"+rid,  
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

<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
			<input type="button" value="Back" class="btn btn-default add-button" onclick="history.go(-1);"/>
		</div>
	</div>
</div>
<?php
	//print_r($project_info);
	$this->breadcrumbs->push('Projects', 'project/project_list');	 
	$this->breadcrumbs->push($project_info->project_name, 'project/project_detail/'.$project_id);	
    $this->breadcrumbs->push('Project Notes', 'project_notes/index/'.$project_id);	
	echo $this->breadcrumbs->show();
?>
<?php


        $ci = & get_instance();
	$ci->load->model('notes_model'); 
        $user_option = $ci->notes_model->get_user_list();
        
        $user_default= 0;
        $user_js = 'id="assign_user_select" class="multiselectbox"';	      
	$assign_user = form_multiselect('assign_user_id[]', $user_option, $user_default, $user_js);

?>
<div id="note_page" class="content-inner">    
  
  <div id="notes_container"> <?php echo $prev_notes; ?>   </div>
  <div class="clear"></div>
  
  <!-- <button id="button"> Get Time </button> -->
 <div id="notes_container_bottom" style=""> 
     <div> 
        <?php 
        $action= base_url().'project_notes/upload_note_image';
        $form_attributes = array('class' => 'notes-add-form', 'id' => 'notes-form', 'method'=>'post');
        echo form_open_multipart($action, $form_attributes); 
        ?>
           <input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id?>"/>
           <table border="0" class="" width="100%" style="background:#fff;">
               <tr>
                    <td><textarea id="mynote" cols="150" rows="3"> </textarea> </td>
                   <td> <input style="height:50px;width:65px;" type="submit" id="submitnote" value=""/></td>
                   <td> <input type="file" name="note_image" id="note_image"><img style="margin-right:0px;" width="20" border="0" src="<?php echo base_url();?>images/attachment.png"/> <div id='preview'> </div></td>   

               </tr>
           </table>




        <?php 	echo form_close(); ?>
        </div> 
        <div id="notify_user_select_box" style="">
              <input id="notify_user_checkbox" type="checkbox"/>
              <span style="">Notify User</span> 
              <span id="user_select_list" style="display:none;"><?php echo $assign_user; ?></span>

        </div>
    </div>     
   
 
</div>

<script> 
var objDiv = document.getElementById("notes_container");
objDiv.scrollTop = objDiv.scrollHeight;   
$(document).ready(function() {      
 
    $('.multiselectbox').selectpicker();
});

 $( document ).ready(function () {
       $('#notify_user_checkbox').click(function() {
            $("#user_select_list").toggle(this.checked);
        });
    });

</script>