<style>
.flexslider .slides img {
    margin: 0 auto;
    max-height: 310px;
}
a#emailphotomessage img.email-photo {
    margin: 0 4px 0 0;
    padding: 0;
}
.flexslider .slides button {
	background: none repeat scroll 0 0 rgba(0, 0, 0, 0);
    border: 0 none;
}
.flexslider .slides button img {
    height: 60px;
    width: 60px;
}
.flexslider .slides button#btnSavePhoto {
	margin-right: 3px;
}
.btn_dev_info {
    float: left;
    height: 60px;
    margin: 0 2px;
    width: 60px;
}
#email-stage-modal .modal-footer, #delete-photo-modal .modal-footer {
    margin: 0;
    padding: 10px;
}
#email-stage-modal .modal-body, #delete-photo-modal .modal-body {
    padding: 10px;
}
#email-ok, #delete-photo-stage {
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
</style>

<?php 
$email_photo = array(
           'src' => 'images/icon/btn_reply_massage.png',
          'alt' => 'Email Photo',
          'class' => 'email-photo',
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
		$mail_photo_button = array(
              'name'        => 'savephote',
              'id'          => 'btnEmailPhoto',
             'class'          => 'btn-photo1',
             'content' => '<img border="0" src="'.base_url().'images/icon/btn_horncastle_mail.png"/>',
            'title'=>'Mail Photo'
            );
        $save_photo_button = array(
              'name'        => 'savephote',
              'id'          => 'btnSavePhoto',
             'class'          => 'btn-photo1',
             'content' => '<img border="0" src="'.base_url().'images/icon/btn_horncastle_save.png"/>',
            'title'=>'Save Photo'
            );
        
        $print_photo_button = array(
              'name'        => 'printphot0',
              'id'          => 'btnPrintPhoto',
             'class'          => 'btn-photo1',
             'content' => '<img border="0" src="'.base_url().'images/icon/btn_horncastle_printer.png"/>',
            'title'=>'Print Photo'
            );

					$atts = array(
                      'width'      => '800',
                      'height'     => '600',
                      'scrollbars' => 'yes',
                      'status'     => 'yes',
                      'resizable'  => 'yes',
                      'screenx'    => '0',
                      'screeny'    => '0',
                      'class'    => ''  
                    );

//print_r(count($photos));

if(isset($_REQUEST['sent_email'])){
	$email_sent_status = $_REQUEST['sent_email'];
	if($email_sent_status==1){?> <script>alert("Message Sent successfully")</script> <?php }
	if($email_sent_status==0){?> <script>alert("Message didn't Sent")</script><?php }
}

?>

<!-- Place somewhere in the <body> of your page -->


<div id="development-photo-slider-box" style="width:100%">
<section class="slider">
         <?php if(count($photos)>0) { ?>
        <div class="flexslider">
         
          <ul class="slides">
              <?php foreach ($photos as $photo) { ?>
                 <li data-thumb="<?php echo base_url(); ?>uploads/development/<?php echo $photo->filename; ?>">
                     <div style="border:5px solid #002855;border-radius: 10px;height: 400px;">
                     <div class="" style="background:#002855;color: #fff;font-weight: bold;">
                     <span style="float:left;"><?php echo $photo->filename; ?>
                    </span>
                    <span style="float:right;"> <?php echo date('d.m.Y', $photo->created); ?> </span>
                    <span class="clear"></span>  <br />
                     Uploaded By: <?php echo $photo->username; ?>
                     </div>
                     <input type="hidden" value="<?php echo $photo->id; ?>">
                     <div class="">
                         <a class="fancybox" href="<?php echo base_url(); ?>uploads/development/<?php echo $photo->filename; ?>">
                         <img src="<?php echo base_url(); ?>uploads/development/<?php echo $photo->filename; ?>" /> 
                         </a>
                     </div>
                     <div class="image-capton" style="background:#ECEBF0; padding: 5px;"><?php echo $photo->photo_caption; ?></div>
                     </div>
                    
                    
                </li>  
              <?php } ?>
               
               
          </ul>
         
         
        </div>
         <?php } else{ ?>
            <div style="width: 100%; height: 340px;">
				<div class="flexslider">
					<div class="flex-viewport" style="overflow: hidden; position: relative;">
						<div style="border:5px solid #002855;border-radius: 10px;height: 400px;">
	                    	<div class="" style="background:#002855;color: #fff;font-weight: bold;"></div>		
						</div>
					</div>
					<div class="flex-control-nav flex-control-thumbs"></div>
				</div>
            </div>
          <?php } ?>
     <div id="button-group" style="clear:both">
         <div class="button-wrapper" style="position: relative; margin-top: 10px; float: left;">
        <?php  
            //echo img($email_photo);
			echo '<a id="emailphotomessage" class="" href="#">'.img($email_photo).'</a>';
            echo form_button($upload_photo_button);
            echo img($upload_photo_icon);
        ?>
         </div>
         
        </div>


<script>

$(document).ready(function () {
	
	$("#save-stage").click(
		function () {
			photo_id1 = $('li.flex-active-slide input').val();
			window.location = window.wbsBaseUrl+"stage/pdf_stage_photo/"+photo_id1;
		}
    );
    $("#print-stage").click(
		function () {
			photo_id1 = $('li.flex-active-slide input').val();
			window.open(window.wbsBaseUrl+'stage/print_stage_photo/'+photo_id1, '_blank', 'width=800,height=600,scrollbars=yes,status=yes,resizable=yes,screenx=0,screeny=0');
		}
    );
    
   $("#email-stage").click(
		function () {
			photo_id1 = $('li.flex-active-slide input').val();

			$.ajax({
            url: "<?php print base_url(); ?>stage/email_outlook_stage/"+photo_id1,  
                dataType: 'html',  
                type: 'POST',  
                 
                success:     
                function(data){ 
				console.log(data);
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
			$("#stage_photo_id").val(photo_id); 

		}
    );
         
});
	

</script>

    <div class="button-box" style="padding:0px; margin:10px 2px 5px 2px;float: left;">                  	
        <?php 
            //$photo_id= $photo->id;	                        
            $email_developments_photo = array(
			          'src' => base_url().'images/icon/btn_horncastle_mail.png',
			          'alt' => 'Email Stage Photo',
			          'class' => 'email-developments',
			          'width' => '60',          
			          'title' => 'Email Stage Photo',
			          'style'=>''
			          
			);
		?>
        
		<a id="email-stage" class="btn_dev_info" data-toggle="modal" role="button" href="#email-stage-modal"><?php echo img($email_developments_photo);?>
		</a>
        
 
		<a id="save-stage" class="btn_dev_info" title="Save Stage Photo" href="#"><img width="60" style="" title="Save Stage Info" class="save-developments" alt="Save Stage Info" src="<?php echo base_url();?>images/icon/btn_horncastle_save.png">
		</a>
		<a id="print-stage" class="btn_dev_info" href="#"><img width="60" style="" title="Print Stage Photo" class="print-developments" alt="Print Stage Photo" src="<?php echo base_url();?>images/icon/btn_horncastle_printer.png">
		</a>
		<a id="delete-photo" class="btn_dev_info" data-toggle="modal" role="button" href="#delete-photo-modal">
			<img width="45" title="Delete Stage Photo" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png">
		</a>
     
    </div>					
					
					      
</section>
</div>    
    
    
<div id="email-stage-modal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
		<p>Are you sure want to delete this Stage Photo?</p>
	</div>
	<div class="modal-footer">	
		<form action="<?php echo base_url();?>stage/stage_photo_delete" method="post">
			<input type="hidden" id="dev_id" name="dev_id" value="<?php echo $development_id;?>"/>
			<input type="hidden" id="stage_no" name="stage_no" value="<?php echo $stage_id;?>"/>
			<input type="hidden" id="stage_photo_id" name="stage_photo_id" value=""/>
			<input id="delete-photo-stage" class="btn" type="submit" value="Ok"/>
			<button class="btn" data-dismiss="modal" aria-hidden="true">Close</button>
		</form>

		<div class="clear"></div>
	</div>

</div>


	<div id="photo-message-dialog-box" title="">    
       
        <form id="addmessageform" action="<?php echo base_url();?>stage/send_stage_photo_message" method="post">
		<input type="hidden" id="photo_dev_id" name="photo_dev_id" value="<?php echo $development_id;?>"/>
		<input type="hidden" id="photo_id" name="photo_id" value=""/>
		<input type="hidden" id="stage_no" name="stage_no" value="<?php echo $stage_id;?>"/>
        <span>Reply Message</span><br/>
        <textarea name="photo_message" style="width: 400px; height: 200px;"></textarea>

        <input type="submit" value="Send"/>
        </form>
           
    </div>
    
    
<div id="dialog" title="">
    
    
        <div id='imagebox' style="width:250px; height:300px; border: 1px solid;float: left;"> 
            
            <div id="preview" style="width:250px; height:250px;">
                
            </div>
            <form id="uploadform" action="<?php echo base_url().'stage/upload_stage_photo/'.$development_id.'/'.$stage_id;?>" method="post" enctype="multipart/form-data" >
                
                <input type="file" name="photoimg" id="photoimg" style="width:230px"/>
            </form>
        </div>
        <div style="float:left;width:200px; height: 300px;margin-left: 10px;">
                <form id="photoinfoform" action="<?php echo base_url().'stage/save_stage_photo/'.$development_id.'/'.$stage_id;?>" method="post">
                <h4>Add Stage Photo</h4>
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
<!-- Add fancyBox -->
<link rel="stylesheet" href="<?php echo base_url();?>fancybox/jquery.fancybox.css" type="text/css" media="screen" />
<script type="text/javascript" src="<?php echo base_url();?>fancybox/jquery.fancybox.js"></script>


      <script type="text/javascript">
          
         
	$(document).ready(function() {
             $(".fancybox").fancybox({
                'width'     : '560',
                'height'    : '450',
                'autoScale'     : false,
                'transitionIn'  : 'none',
                'transitionOut' : 'none',        
                'type'      : 'iframe'
            });       
		
	});
        

    </script>