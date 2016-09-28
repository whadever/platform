<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-select.css">
<script type="text/javascript" src="<?php echo base_url();?>/js/bootstrap-select.js"></script>
 
 
  <script> 
    // using JQUERY's ready method to know when all dom elements are rendered
    $( document ).ready(function () {      
	  
	$("#submitnote").click(function (e) {
            
        e.preventDefault();
        var note = $('#mynote').val();
        var company_id = $('#company_select').val();
        var userid = $('#contact_select').val();
        
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
        //$('#assign_user_select').attr('checked',false);
        $('#notify_user_checkbox').removeAttr('checked');
        //$('#assign_user_select').val('');
		$("#company_select > option").attr("selected",false);

		$('.dropdown-menu li').removeClass("selected");
		$('.filter-option.pull-left').empty();
		$('.filter-option.pull-left').append('Nothing selected');

        //$('#assign_user_select').prop('defaultSelected'); 
        $('#user_select_list').css("display","none");
        $('#contact_select_list').css("display","none");
            $("#contact_select").empty();
            $("#contact_select").selectpicker('refresh');

        $.ajax({  
        url:"<?php echo base_url();?>constructions/show_notes/"+rid+"/"+company_id+"/"+userid+"?cp=<?php echo $_GET['cp']; ?>",
        dataType: 'html',
		data: { notes: note_param6 },  
        type: 'GET',   
        success:function(data){						  
                         if(data){  	                    
                             //console.log(data);
                               // $("#text").append("Me : " + data + "<br />");
                                $("#notes_container").html(data);
								var objDiv = document.getElementById("notes_container");
							  objDiv.scrollTop = objDiv.scrollHeight;
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
        url:"<?php echo base_url();?>constructions/show_notes_with_image/"+rid+"?cp=<?php echo $_GET['cp']; ?>",
        dataType: 'html',  
        type: 'GET',   
        success:function(data){						  
                         if(data){  	                    
                            
                              $("#notes_container").html(data);
							  var objDiv = document.getElementById("notes_container");
							  objDiv.scrollTop = objDiv.scrollHeight;
                               
                        }  
                }
	   });
};


//$(document).ready(function() { 
function refresh_files()
{
   var rid = $('#request_id').val();
   $.ajax({  
       url:"<?php echo base_url();?>constructions/show_notes_with_image/"+rid,  
        dataType: 'html',  
        type: 'GET',   
        success:function(data){						  
                         if(data){  	                    
                            
                              $("#notes_container").html(data);
                               var objDiv = document.getElementById("notes_container");
							  objDiv.scrollTop = objDiv.scrollHeight;
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
            url:"<?php echo base_url();?>constructions/notes_delete/"+pid+"/"+noteid,  
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
 #submitnote {
    background: url(<?php echo base_url(); ?>images/send.png) no-repeat;
    cursor: pointer;
    background-size: 50px 50px;
	margin: 5px 10px;
	padding: 0;
	border:0;
}
    #company_select option{
        padding: 6px 12px;
    }
 .bootstrap-select {
     border: medium none;
     margin-bottom: 5px;
     padding: 0;
 }

</style>

<?php

		/*$ci = & get_instance();
		$ci->load->model('developments_model');
		$ci->load->model('company_model');*/
		//$user_option = $ci->developments_model->get_user_list();
		//$user_option = $ci->company_model->get_company_list();
        $user = $this->session->userdata('user');
        $wp_company_id = $user->company_id;
        $sql = "SELECT comp.id company_id,
                       comp.company_name,
                       cont.id contact_id,
                       CONCAT(contact_first_name, ' ', contact_last_name) contact_name
                FROM contact_company comp, contact_contact_list cont
                WHERE cont.company_id = comp.id AND comp.wp_company_id = {$wp_company_id}
                ORDER BY company_name, contact_name";
        $res = $this->db->query($sql)->result();
        $companies = array();
        $companies['null'] = "---Select Company---";
        $company_contacts = array();
        foreach($res as $c){
            $companies[$c->company_id] = $c->company_name;
            $company_contacts[$c->company_id][$c->contact_id] = $c->contact_name;
        }

        //$user_option = array_merge(array(''=>'---Select Company---'),$user_option);
		//$user_default= 0;
		$user_js = 'id="company_select" class="multiselectbox" style="width: 30%; margin: 10px 0px; display: inline"';
		$company_dropdown = form_dropdown('company_id', $companies, '', $user_js);

        $contact_attr = 'id="contact_select" class="multiselectbox" title="---Select Contact---" ';
        $contact_dropdown = form_multiselect('contact_id[]', array(), '', $contact_attr);

        //echo "<script>var contacts = ".json_encode($company_contacts)."; </script>";


?>
 
<div id="note_page">
  
    
        <div style="text-align: center; color: #fff; font-size: 20px; padding: 0 0 5px;"> 
            Job Notes</div>
    
  <!--<textarea id="text" readonly>   </textarea> -->
  <div id="notes_container"> <?php  echo $prev_notes;  ?>   </div>
  <div class="clear"></div>
  
  <!-- <button id="button"> Get Time </button> -->
  <div id="note-bottom" style="background:#fff;border-bottom-left-radius: 8px;border-bottom-right-radius: 8px;"> 
      <div>
    <?php 
    $action= base_url().'notes/upload_note_image';
    $form_attributes = array('class' => 'notes-add-form', 'id' => 'notes-form', 'method'=>'post');
    echo form_open_multipart($action, $form_attributes); 
    ?>
       <input type="hidden" name="request_id" id="request_id" value="<?php echo $development_id;?>"/>
       <table border="0" class="" width="100%" style="background:#fff;">
           <tr>
               <td style="width: 100%;"><textarea id="mynote" cols="150" rows="3"> </textarea> </td>

               <td> <input style="height:50px;width:50px;" type="submit" id="submitnote" value=""/></td>
                 
           </tr>
       </table>




    <?php 	echo form_close(); ?>
      </div>
      <div id="notify_user_select_box" style="padding: 5px; border-top:1px solid #004272">
          
          <input id="notify_user_checkbox" type="checkbox"/>
          <span style="">Notify User</span> 
          <span id="user_select_list" style="display:none;"><?php echo $company_dropdown; ?></span>
          <span id="contact_select_list" style="display:none;"><?php echo $contact_dropdown; ?></span>

      </div>
  </div>
  
</div>


<script>
    var objDiv = document.getElementById("notes_container");
    objDiv.scrollTop = objDiv.scrollHeight;

    $(document).ready(function () {
        $('#notify_user_checkbox').click(function () {
            $("#user_select_list").toggle(this.checked);
            $("#contact_select_list").toggle(this.checked);
        });
        $('.multiselectbox').selectpicker();

        $("#company_select").change(function () {
			// Task# 4289
			var cid = $(this).val();

			jQuery.ajax({
				url: "<?php echo base_url(); ?>" + "constructions/load_contact_by_company?cid=" + encodeURIComponent(cid),
				type: 'GET',
				success: function(data) 
				{
					//console.log(data);
					$("#contact_select").empty();
					$("#contact_select").append(data);
					$("#contact_select").selectpicker('refresh');
				},
			});
			
        });
    });

</script>