<style>


a#emailphotomessage img.btn-photo {
    margin: 0 4px 0 0;
    padding: 0;
}
.btn_dev_info {
    float: left;
    height: 60px;
    margin: 0 2px;
    width: 60px;
}

#email-development-modal .modal-footer, #delete-photo-modal .modal-footer {
    margin: 0;
    padding: 10px;
}
#email-development-modal .modal-body, #delete-photo-modal .modal-body {
    padding: 10px;
}
#email-ok, #delete-photo-dev {
    background: #eee;
}
#btnOpenMe {
    border: medium none;
    height: 60px;
    margin-left: 0;
    opacity: 0;
    padding: 0;
    position: absolute;
    width: 60px;
}
#delete-photo {
    background: #cac9c9;
    padding: 8px;
	border-radius: 8px;
}
#photo-notes {
    background: #cac9c9;
    padding: 8px;
	border-radius: 8px;
}

/*flex slider custom css by mamun*/
.flexslider{
        background: none;
        border:5px solid #002855;
		border-radius: 10px;  
        margin:0px;
        position: relative;
        box-shadow: none;
        height: 400px;
		width: 51.06271%;
} 
.flex-viewport {
	height: 400px;
	
}
.flexslider .flex-control-thumbs {
    border: 10px solid #002855;
    border-radius: 10px;
    height: 400px;
    left: 100%;
    margin-left: 5%;
    margin-top: 0;
    overflow-x: hidden;
    overflow-y: auto;
    position: relative;
    top: -405px;
    width: 94%;
}

.flexslider:hover #gallery_main a
{
  opacity: 1!important;

}

.flexslider .slides img {
    margin: 0 auto;
    max-height: 310px;
}
.flexslider .slides .btn_dev_info img {
    height: 60px;
    width: 60px;
}


 .flexslider .flex-control-thumbs li{
        width:48%;
        margin:2% 1%;
    }
    
  .flexslider .flex-direction-nav a{margin-top: 0px;}
  
  .flexslider:hover .flex-next { opacity: 0.7; right: 50.2%; }
  
  .flexslider .flex-direction-nav .flex-next{right: 52%;}
  
  .flexslider .flex-direction-nav .flex-prev{left:10px;}
  
  .flexslider ul.slides li:first-child{} 
  .flexslider ul.slides{margin:0px; }
  .flexslider .slides > li {
     display: list-item;
    
    -webkit-backface-visibility: hidden;
}
.flexslider .slides > li:not(:first-child){display: none; -webkit-backface-visibility: hidden;}
</style>
<?php 

if(isset($_REQUEST['sent_email'])){
	$email_sent_status = $_REQUEST['sent_email'];
	if($email_sent_status==1){?> <script>alert("Message Sent successfully")</script> <?php }
	if($email_sent_status==0){?> <script>alert("Message didn't Sent")</script><?php }
}

$email_photo = array(
           'src' => 'images/icon/btn_reply_massage.png',
          'alt' => 'Email Photo',
          'class' => 'btn-photo',
          'width' => '60',          
          'title' => 'Email Photo'
          
);
        $upload_photo_icon = array(
           'src' => 'images/add_photo.png',
          'alt' => 'Upload Photo',
          'class' => 'upload-photo',
          'width' => '60',          
          'title' => 'Upload Photo',
          
);
        $upload_photo_button = array(
              'name'        => 'uploadphoto',
              'id'          => 'btnOpenMe',
             'class'          => 'btnuploadimage',
             'content' => 'Upload Image',
            'title'=>'Upload Photo'
            );
       
        $save_photo_button = array(
              'name'        => 'savephote',
              'id'          => 'btnSavePhoto',
             'class'          => 'btn-photo',
             'content' => '<img border="0" width="25" height="25" src="'.base_url().'images/icon/btn_horncastle_save.png"/>',
            'title'=>'Save Photo'
            );
        
        $print_photo_button = array(
              'name'        => 'printphoto',
              'id'          => 'btnPrintPhoto',
             'class'          => 'btn-photo',
             'content' => '<img border="0" width="25" height="25" src="'.base_url().'images/icon/btn_horncastle_printer.png"/>',
            'title'=>'Print Photo'
       	);
       	$save_developments_img = array(
          'src' => base_url().'images/icon/btn_horncastle_save.png',
          'alt' => 'Save Developments Info',
          'class' => 'save-developments',
          'width' => '45',          
          'title' => 'Save Developments Info',
          'style'=>''
          
);
        $print_developments_img = array(
		          'src' => base_url().'images/icon/btn_horncastle_printer.png',
		          'alt' => 'Print Developments Photo',
		          'class' => 'print-developments',
		          'width' => '45',          
		          'title' => 'Print Developments Photo',
		          'style'=>''
		          
			);
$atts = array(
                      'width'      => '800',
                      'height'     => '600',
                      'scrollbars' => 'yes',
                      'status'     => 'yes',
                      'resizable'  => 'yes',
                      'screenx'    => '0',
                      'screeny'    => '0',
                      'class'    => 'btn_dev_info',
                      'id'=>'print-development'  
                    );
//print_r(count($photos));
?>

<!-- Place somewhere in the <body> of your page -->
<?php 
$user = $this->session->userdata('user');
$ci = &get_instance();
$ci->load->model('user_model');
$user_role = $ci->user_model->user_app_role_load($user->uid);
$user_role = $user_role->application_role_id;
?>

<div class="archive">

<div class="archive-left">
	<div class="archive-left-inner">
		<img width="35" src="<?php echo base_url();?>images/archive_area.png">Archive Area
	</div>
</div>

<div class="archive-right">
	<div class="archive-right-inner">
		<a href="<?php echo base_url(); ?>developments/development_photos/<?php echo $development_id;?>"><img width="25" alt="Back"  title="Back" src="<?php echo base_url();?>images/archive_back.png">Back</a>
	</div>
</div>

</div>

<div class="clear"></div>    

<section class="slider">

         <?php if(count($photos)>0) { ?>

        <div class="flexslider">
         
          <ul class="slides">

              <?php foreach ($photos as $photo) { ?>

                 <li data-thumb="<?php echo base_url(); ?>uploads/development/<?php echo $photo->filename; ?>">
                     <div class="photos_ga" style="padding:3px;background:#002855;color: #fff;font-weight: bold;">
                     <span style="float:left;"><?php if($photo->photo_caption!=NULL){ echo $photo->photo_caption; }else{ echo $photo->filename; } ?>
                    </span>
                    <span style="float:right;"> <?php echo date('d.m.Y', $photo->created); ?> </span>
                    <span class="clear"></span>  <br />
                     Uploaded By: <?php $uid = $photo->uid; $u_name = $this->db->query("SELECT username FROM users WHERE uid='$uid'")->row(); echo $u_name->username; ?>
                     </div>
                     <?php if($photo->stage_no==''){ ?><input type="hidden" value="<?php echo $photo->id; ?>"><?php } ?>
					
                     <div id="gallery_main">									 
                         <a class="fancybox" href="<?php echo base_url(); ?>uploads/development/<?php echo $photo->filename; ?>"> 
                                <img id="photo_<?php echo $photo->id;  ?>" src="<?php echo base_url(); ?>uploads/development/<?php echo $photo->filename; ?>" /> 
                         </a>
					  </div>
					
                     <!---<div class="image-capton" style="background:#ECEBF0; padding: 5px;"><?php //echo $photo->photo_caption; ?></div>--->
					 <?php 
						if($photo->stage_no==''){
							if($user_role==2 || $user_role==4){ 
					 ?>
							<div class="photo-action" style="padding: 5px;"> 
								<span class=""><input class="feature_photo" type="checkbox" <?php if($photo->featured==1){echo 'checked';}?>> Feature Photograph</span>
								<span class="pull-right"><input class="private_photo"  type="checkbox" <?php if($photo->private==1){echo 'checked';}?> > Private </span>
							</div>
					 <?php 
							} 
						}
					 ?>	
                    
                    
                </li>  
              <?php } ?>
               
               
          </ul>
         
         
        </div>
         <?php } else{ ?>
            <div style="">
				<div class="flexslider">
					<div class="flex-viewport" style="overflow: hidden; position: relative;">
						<div style="">
	                    	<div class="" style="background:#002855;color: #fff;font-weight: bold;"></div>		
						</div>
					</div>
					<div class="flex-control-nav flex-control-thumbs"></div>
				</div>
            </div>
                
          <?php } ?>

		<!-- button starts here -->
</section>		
		
     <!--<div id="button-group" style="clear:both">
         <div class="button-wrapper" style="position: relative; margin-top: 10px; float: left;">
        <?php  
            echo '<a id="emailphotomessage" class="" href="#">'.img($email_photo).'</a>';
            if($user_role==2 || $user_role==3 || $user_role==4){
				echo form_button($upload_photo_button);
	            echo img($upload_photo_icon);
			}
        ?>
		
         </div>
         
    </div>--->
    


    <div class="button-box" style="padding:0px; margin:10px 2px 5px 2px;float: left;">                  	
        <?php 
			//$user =  $this->session->userdata('user');
			//print_r($user);
            //$photo_id= $photo->id;	                        
            $email_developments_photo = array(
			          'src' => base_url().'images/icon/btn_horncastle_mail.png',
			          'alt' => 'Email Developments Photo',
			          'class' => 'email-developments',
			          'width' => '60',          
			          'title' => 'Email Developments Photo',
			          'style'=>''
			          
			);
		?>
        
		<a id="email-development" class="btn_dev_info" data-toggle="modal" role="button" href="#email-development-modal"><?php echo img($email_developments_photo);?>
		</a>
        
        
		<?php    
			//echo anchor('#', img($save_developments_img), array('title' => 'Save Developments Photo', 'class'=> 'btn_dev_info', 'id'=>'save-development'));
			//echo anchor_popup('#', img($print_developments_img), $atts);
		?>
		<a id="save-development" class="btn_dev_info" title="Save Developments Photo" href="#"><img width="60" style="" title="Save Developments Info" class="save-developments" alt="Save Developments Info" src="<?php echo base_url();?>images/icon/btn_horncastle_save.png">
		</a>
		<a id="print-development" class="btn_dev_info" href="#"><img width="60" style="" title="Print Developments Photo" class="print-developments" alt="Print Developments Photo" src="<?php echo base_url();?>images/icon/btn_horncastle_printer.png">
		</a>
		<?php if($user_role==2 || $user_role==4){ ?><a id="delete-photo" class="btn_dev_info" data-toggle="modal" role="button" href="#delete-photo-modal">
			<img width="45" title="Delete Developments Photo" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png">
		</a><?php } ?>
		<a id="photo-notes" class="btn_dev_info"  href="#">
			<img width="45" alt="Photo Notes"  title="Photo Notes" src="<?php echo base_url();?>images/icon/btn_horncastle_note.png">
		</a>
     
    </div>
                    
	<div class="clear"></div>       


  
    
<script>

$(document).ready(function () {

	$("#photo-notes").click(function () {

			photo_id = $('li.flex-active-slide input').val();			
			if(photo_id == null){
				alert("No Photo Selected in the photo gallery");
			}else{
				window.location = window.wbsBaseUrl+"developments/photo_notes/"+photo_id+"/"+<?php echo $development_id?>;
			}
	});	 
	
	$("#save-development").click(
		function () {
			photo_id1 = $('li.flex-active-slide input').val();
			if(photo_id1 == null){
				alert("No Photo Selected in the photo gallery");
			}else{
				window.location = window.wbsBaseUrl+"developments/pdf_developments_photo/"+photo_id1;
			}
		}
    );
    $("#print-development").click(
		function () {
			photo_id1 = $('li.flex-active-slide input').val();
			if(photo_id1 == null){
				alert("No Photo Selected in the photo gallery");
			}else{
				window.open(window.wbsBaseUrl+'developments/print_developments_photo/'+photo_id1, '_blank', 'width=800,height=600,scrollbars=yes,status=yes,resizable=yes,screenx=0,screeny=0');
			}
		}
    );
    
   $("#email-development").click(
		function () {
			photo_id1 = $('li.flex-active-slide input').val();

			$.ajax({
            url: "<?php print base_url(); ?>developments/email_outlook_development/"+photo_id1,  
                dataType: 'html',  
                type: 'POST',  
                 
                success:     
                function(data){ 
				//console.log(data);
				 //$('#email-ok').href='';
                 //$('#email-ok').href='data';
                 $("a#email-ok").attr("href", data);
                }
        	});

		}
    );

	$("#delete-photo").click(
		function () {
			photo_id = $('li.flex-active-slide input').val();
			
			$("#dev_photo_id").val(photo_id); 

		}
    );
	$(".feature_photo").click(function () {

					photo_id = $('li.flex-active-slide input').val();
					checked = $(this).is(':checked') ? 1 : 0;
					
					
					$.ajax({
		            	url: "<?php print base_url(); ?>developments/photo_action_featured/"+photo_id+"/"+checked,  
		                dataType: 'html',  
		                type: 'POST', 		                 
		                success:     
		                function(data){ 							 
                     		//console.log(data);
                     		//alert(data);		                 
		                }
		        	});
	
		});
		$(".private_photo").click(function () {

					photo_id = $('li.flex-active-slide input').val();
					checked = $(this).is(':checked') ? 1 : 0;
					
					$.ajax({
		            	url: "<?php print base_url(); ?>developments/photo_action_private/"+photo_id+"/"+checked,  
		                dataType: 'html',  
		                type: 'POST', 		                 
		                success:     
		                function(data){ 							 
                     		//console.log(data);
                     		//alert(data);		                 
		                }
		        	});
	
		});
         



         
});

	
</script>

	<div id="photo-message-dialog-box" title="">    
       
        <form id="addmessageform" action="<?php echo base_url();?>developments/send_development_photo_message" method="post">
		<input type="hidden" id="photo_dev_id" name="photo_dev_id" value="<?php echo $development_id;?>"/>
		<input type="hidden" id="photo_id" name="photo_id" value=""/>
        <span>Reply Message</span><br/>
        <textarea name="photo_message" style="width: 400px; height: 200px;"></textarea>

        <input type="submit" value="Send"/>
        </form>
           
    </div>

<div id="email-development-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-body">
		<p>Are you sure want to send this mail?</p>
	</div>
	<div class="modal-footer">	
		<a id="email-ok" href="#" class="btn">Ok</a>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		<div class="clear"></div>
	</div>

</div>

<div id="delete-photo-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-body">
		<p>Are you sure want to delete this Development Photo?</p>
	</div>
	<div class="modal-footer">
		<form action="<?php echo base_url();?>developments/development_archive_photo_delete" method="post">
		<input type="hidden" id="dev_id" name="dev_id" value="<?php echo $development_id;?>"/>
		<input type="hidden" id="dev_photo_id" name="dev_photo_id" value=""/>
		<input id="delete-photo-dev" class="btn" type="submit" value="Ok"/>
		<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		</form>
		
		<div class="clear"></div>
	</div>

</div>
    
    
<div id="dialog" title="">
    
    
        <div id='imagebox' style="width:250px; height:300px; border: 1px solid;float: left;"> 
            <div id="preview" style="width:250px; height:250px;">
                
            </div>
            <form id="uploadform" action="<?php echo base_url();?>developments/upload_development_photo/<?php echo $development_id;?>" method="post" enctype="multipart/form-data" >
                
                <input type="file" name="photoimg" id="photoimg" style="width:230px"/>
            </form>
        </div>
        <div style="float:left;width:200px; height: 300px;margin-left: 10px;">
                <form id="photoinfoform" action="<?php echo base_url();?>developments/save_development_photo/<?php echo $development_id;?>" method="post">
                <span>Add Development Photo</span>
                 <input type="hidden" name="photo_insert_id" id="photo_insert_id" value=""/>
               
                <label>Caption</label> <br/>
                <textarea name="photo_caption"></textarea>
                <!-- <label>Category</label> <br/>
                <select name="photo_category" style="width: 200px;">
                    <option value="0">select category </option>
                    <option value="1">category 1</option>
                    <option value="2">category 2</option>
                </select><br/> -->
                <input type="submit" value="Save"/>
                
                </form>
        </div>


</div>


 <script type="text/javascript">
// Can also be used with $(document).ready()
$(window).load(function() {
  $('.flexslider').flexslider({
      slideshow: false,
    animation: "slide",
    controlNav: "thumbnails"
  });
});
</script>
<script type="text/javascript">
    
$(document).ready(function () {
        $("#dialog").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 350,
            modal: true
        });

		$("#photo-message-dialog-box").dialog({ 
            autoOpen: false,
            width : 520, 
            height: 350,
            modal: true
        });
		
 
        $("#btnOpenMe").click(
            function () {
                $("#dialog").dialog('open');
                return false;
            }
        );
		
  
		$("#emailphotomessage").click(
        		
                function () {
                	photo_id = $('li.flex-active-slide input').val();

                		$("#photo-message-dialog-box").dialog('open');
                		$('#photo_id').val(photo_id);
                		return false;
                    
                    
                }
            );

		
           $('#photoimg').on('change', function(){             
            var options = { 
                target:     '#preview',     
                success:    function() { 
                    var photo_id= $('#development_photo_id').val();
                    $('#photo_insert_id').val(photo_id);         
                } 
            }; 

            $("#preview").html('');
            $("#preview").html('<img src="loader.gif" alt="Uploading...."/>');
            $("#uploadform").ajaxForm(options).submit();
            
         });

		
      
        
 });
</script>
<script>
    
    function email_dev_photo(photo_name){
       
        $.ajax({
            url: "<?php print base_url(); ?>developments/email_dev_photo/"+photo_name,  
                dataType: 'html',  
                type: 'GET',  
                 
                success:     
                function(data){  
                 //console.log(data);
                 if(data){  
                     
                     
                     console.log(data);
                     alert(data);
                     
                   
                 }  
                }
        });
    }
    
   
    
</script>
<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo base_url();?>fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url();?>fancybox/jquery.fancybox.js"></script>


      <script type="text/javascript">
          
         
	$(document).ready(function() {
             $(".fancybox").fancybox();       
		
	});

      

    </script>

<script>

$(document).ready(function(){
    $(".flex-direction-nav").click(function(){
        jQuery('.slides li').css("margin-left", "0px");
console.log('hare');
    });

	$(".flex-nav-next").click(function(){
        $("ul.slides li").css("margin-left", "0px");
console.log('hare');
    });
});

</script>

<script>
$( window ).load(function() {
  //jQuery('.flex-active-slide').css("margin-left", "20px");
});
</script>