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
        var note_param = myval.replace(/\//g, "forward_slash");
        var note_param1 = note_param.replace(/#/g, "sign_of_hash");
        var note_param2 = note_param1.replace(/\?/g, "sign_of_intertogation");
		var note_param3 = note_param2.replace(/\+/g, "sign_of_plus");
		var note_param4 = note_param3.replace(/\!/g, "sign_of_exclamation");
		var note_param5 = note_param4.replace(/\%/g, "percentage");
		var note_param6 = note_param5.replace(/\\/g, "back_slash");
        
        //var note_value=encodeURI(note);
        
        if(value.length == 0){
            alert('Please enter your note.');
        }else{
        var rid = $('#request_id').val();
        $('#mynote').val('');

		
        
		var atLeastOneIsChecked = $('#private_note').is(":checked")
		if(atLeastOneIsChecked)
		{
			var private = $('#private_note').val();
		}
		else
		{
			var private = '0';
		}

		$("#assign_user_select > option").attr("selected",false);

        $('#private_note').removeAttr('checked');

        $('#notify_user_checkbox').removeAttr('checked');

 		
        $('#user_select_list').css("display","none");

        $.ajax({  
        url:"<?php echo base_url();?>potential_developments/show_notes/"+rid+"/"+userid,  
        dataType: 'html',
		data: { notes: note_param6, private: private },  
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
        url:"<?php echo base_url();?>potential_developments/show_notes_with_image/"+rid,  
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
       url:"<?php echo base_url();?>potential_developments/show_notes_with_image/"+rid,  
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
            url:"<?php echo base_url();?>potential_developments/notes_delete/"+pid+"/"+noteid,  
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
 <?php 
$user = $this->session->userdata('user');
$ci = &get_instance();
$ci->load->model('user_model');
$user_role = $ci->user_model->user_app_role_load($user->uid);
$user_role = $user_role->application_role_id;
?>

<div id="note_page">
  
    
        <div style="text-align: center; color: #fff; font-size: 20px; padding: 0 0 5px;"> 
            Developments Notes</div>
    
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
       <input type="hidden" name="request_id" id="request_id" value="<?php echo $development_id;?>"/>
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
          <?php if($user_role==2){ ?><span style="float: right;"><input value="1" id="private_note" name="private_note" type="checkbox" /><span style="padding:0 10px 0 5px;">Private</span></span><?php } ?>	
      </div>
  </div>
  
</div>  


			<div class="development-photo all-feature-img">
                <div class="box-title">Feature Photo </div>
                <div style="min-height: 280px; text-align:center; width: 100%;">
				
        		<div class="flexslider" style=" border: 0px solid #fbb900 !important;">
         
          		<ul class="slides">
					<?php

						foreach($feature_photos as  $feature_photo){

            	if(isset($feature_photo->filename)){
            	 $image_link =base_url().'uploads/development/'.$feature_photo->filename;
            	}
            	else{
            		 $image_link = base_url().'images/pms_home.png';
            	}

 
						$imagedata = getimagesize($image_link);
						$image_width= $imagedata[0];
						$image_height= $imagedata[1];
						if($image_width <442 && $image_height<355){
							$width='';
							$height=$image_height.'px';
							
						}
						else{
							if($image_height>$image_width){
								$width='auto !important;';
								$height='355px !important';
							}
							else{
								$width='100% !important';
								$height='100%';
							}
					}
					?>
					<li>
                    <a class="fancybox" rel="gallery1" href="<?php echo $image_link; ?>"><img style="width:<?php echo $width;?>;height:<?php echo $height;?>;" src="<?php echo $image_link; ?>"/></a>
					</li>
<?php } ?>
					</ul></div><!-- flexslider -->
                </div>


            </div>  
   
 
<script>  
var objDiv = document.getElementById("notes_container");
objDiv.scrollTop = objDiv.scrollHeight;  

 $( document ).ready(function () {
		$('#assign_user_select').selectpicker();

       	$('#notify_user_checkbox').click(function() {
            $("#user_select_list").toggle(this.checked);
        });
       
    });

$( document ).ready(function () {
       $('#notify_user_checkbox').click(function() {
            $("#user_select_list").toggle(this.checked);
        });
    });

</script>

<script type="text/javascript">
// Can also be used with $(document).ready()
$(window).load(function() {
  $('.flexslider').flexslider({
       animation: "slide"
  });
});
</script>

<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo base_url();?>fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url();?>fancybox/jquery.fancybox.js"></script>


    <script type="text/javascript">
		$(document).ready(function() {
             $(".fancybox").fancybox();       
		
		});
    
    </script>