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
        
        
        //var note_value=encodeURI(note);
        
        if(value.length == 0){
            alert('Please enter your note.');
        }else{
        var rid = $('#request_id').val();
		var request_no = $('#request_no').val();
        $('#mynote').val('');
        
        $('#notify_user_checkbox').removeAttr('checked');
        
        $('#assign_user_select').prop('defaultSelected'); 
        $('#user_select_list').css("display","none");
        $.ajax({  
        url:window.mbsBaseUrl + "notes/show_notes/"+rid+"/"+userid,  
        dataType: 'html', 
		 data: { notes: mynote, request_no: request_no}, 
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

	$this->breadcrumbs->push('Task', 'request/request_list');
	 //$this->breadcrumbs->push('Task', site_url('request_list') );
	$this->breadcrumbs->push($request_info->request_title, 'request/request_detail/'.$request_info->request_no);
	 //$this->breadcrumbs->push('Task Detail', site_url('request_detail/id') );
        $this->breadcrumbs->push('Notes', 'notes/index/'.$request_id);
	// unshift crumb
	//$this->breadcrumbs->unshift('Home', '/');
	// $this->breadcrumbs->unshift('Home', site_url('') );

	echo $this->breadcrumbs->show();
?>
<?php

//        $manager_option = $ci->request_model->get_manager_list();
//        //$manager_options = array('0' => '-- Select Manager --') + $manager_option;
//        
//	$manager_default2 = isset($request->assign_manager_id) ? $request->assign_manager_id : 0;
//        $manager_default= explode(",", $manager_default2);
//        $manager_js = 'id="assign_manager_select" class="multiselectbox"';
//	$assign_manager = form_label('Assign Manager', 'assign_manager_id');        
//	$assign_manager .= form_multiselect('assign_manager_id[]', $manager_option, $manager_default, $manager_js);

        $ci = & get_instance();
	$ci->load->model('notes_model'); 
        $user_option = $ci->notes_model->get_user_list();
        //$manager_options = array('0' => '-- Select Manager --') + $manager_option;
        
	
        $user_default= 0;
        $user_js = 'id="assign_user_select" class="multiselectbox dropup"';
	//$assign_user = form_label('Assign User', 'assign_manager_id');        
	$assign_user = form_multiselect('assign_user_id[]', $user_option, $user_default, $user_js);

?>
 <div class="content-inner task-note">

	<div class="row">
		<div class="col-md-12">
			<div id="notes_container"> <?php echo $prev_notes; ?></div>
		</div>
	  
	  	<!-- <button id="button"> Get Time </button> -->
		<div class="col-md-12">
			<div id="notes_container_bottom"> 
				<div>
					<?php 
					$action= base_url().'notes/upload_note_image';
					$form_attributes = array('class' => 'notes-add-form', 'id' => 'notes-form', 'method'=>'post');
					echo form_open_multipart($action, $form_attributes); 
					?>
				   	<input type="hidden" name="request_id" id="request_id" value="<?php echo $request_id; ?>"/>
					<input type="hidden" name="request_no" id="request_no" value="<?php echo $request_info->request_no; ?>"/>
				   	<table border="0" class="" width="100%">
					   <tr>
						   <td><textarea id="mynote" cols="150" rows="3"> </textarea> </td>

						   <td><input style="height:50px;width:65px;float: right;" type="submit" id="submitnote" value=""/></td>
						   <td><input type="file" name="note_image" id="note_image"><img style="margin-right:5px;" width="20" border="0" src="<?php echo base_url();?>images/attachment.png"/> <div id='preview'> </div></td>  
					   </tr>
				   	</table>
					<?php echo form_close(); ?>
				</div>

				<div id="notify_user_select_box">
					<span id="notify_user_text">Notify User</span>
					<input id="notify_user_checkbox" type="checkbox"/>
					<span id="user_select_list" style="display:none;"><?php echo $assign_user; ?></span>
				</div>
			</div>
		</div>
	</div>

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
<script>  
var objDiv = document.getElementById("notes_container");
objDiv.scrollTop = objDiv.scrollHeight;
  
$(document).ready(function() {      
   
    
   
//    $(".multiselectbox").multiselect({
//        selectedText: "# of # selected"
//    });
    //$( "select" ).selectmenu();
    $('.multiselectbox').selectpicker({

		dropupAuto: false

	});
});

 $( document ).ready(function () {
       $('#notify_user_checkbox').click(function() {
            $("#user_select_list").toggle(this.checked);
        });
    });

</script>