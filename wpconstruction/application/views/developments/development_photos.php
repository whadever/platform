<script type="text/javascript" src="<?php echo base_url();?>js/jQueryRotate.js"></script>

<style>

.flexslider:hover #gallery_main a{
  opacity: 1!important;
}

.flexslider .slides img {
    margin: 0 auto;
    max-height: 360px;
}

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

.flexslider .slides .btn_dev_info img {

    height: 60px;

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

/*#btnOpenMe {

    border: medium none;

    height: 60px;

    margin-left: 0;

    opacity: 0;

    padding: 0;

    position: absolute;

    width: 60px;

}*/
#btnOpenMe {
    opacity: 1;
    position: unset;
}

#delete-photo,#rotate-photo{

    background: #cac9c9;

    padding: 8px;

	border-radius: 8px;

}

#delete-photo.contractor{

    display: none;

}

.ui-datepicker-calendar {

    display: none;

}

.flexslider:hover .flex-next {
    opacity: 0.7;
    right: 12px;
}

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

if(!$month){
	$month = date("Y-m");
}

if($this->uri->segment(4)){
	//$dt = date_create_from_format('Y-m-d',$month.'-05');
	$dt = date_create_from_format('Y-m-d',$month.'-05');
}else{
	$dt = date_create_from_format('Y-m-d',date("Y-m-d"));
}


$val = $dt->format('F')." ".$dt->format('Y');

?>
<div class="row">
    <div class="col-md-9">
        <div id="development-photo-slider-box" style="width:100%;">

            <section class="slider">

                <?php if(count($photos)>0) { ?>

                    <div class="flexslider" id="slider">

                        <ul class="slides">

                            <?php foreach ($photos as $photo) { ?>

                                <script>
                                    $(document).ready(function () {
                                        $("#zoom_in_<?php echo $photo->id;  ?>").click(function()
                                        {
                                            current_height = $("#photo_<?php echo $photo->id;  ?>").css('max-height');
                                            var num = current_height.length;
                                            var res = current_height.substring(0, num-2);
                                            current = parseInt(res) + 1;
                                            new_size = current + 50;
                                            newsizepx = new_size + 'px';
                                            $(".flexslider .slides img#photo_<?php echo $photo->id;  ?>").css("max-height", newsizepx );
                                        });
                                        // zoom out code
                                        $("#zoom_out_<?php echo $photo->id;  ?>").click(function()
                                        {
                                            current_height = $("#photo_<?php echo $photo->id;  ?>").css('max-height');
                                            var num = current_height.length;
                                            var res = current_height.substring(0, num-2);
                                            current = parseInt(res) + 1;
                                            new_size = current - 50;
                                            newsizepx = new_size + 'px';
                                            $(".flexslider .slides img#photo_<?php echo $photo->id;  ?>").css("max-height", newsizepx );
                                        });
                                    });
                                </script>

                                <li data-thumb="<?php echo base_url(); ?>uploads/development/<?php echo $photo->filename; ?>">

                                    <div style="border:5px solid #fdd07c;border-radius: 10px;height: 450px;">

                                        <div class="" style="background:#fdd07c;color: #fff;font-weight: bold;">

                                            <span style="float:left;">

                                                <?php if(!empty($photo->photo_caption)){ echo $photo->photo_caption; }else{ echo $photo->filename; } ?>

                                            </span>

                                            <span style="float:right;"> <?php echo date('d.m.Y', $photo->created); ?> </span><br>
											<span style="float:right;"> Photo Privacy: <?php if($photo->photo_permission==1){echo "Public";}elseif($photo->photo_permission==2){echo "Private";}elseif($photo->photo_permission==3){echo "Investor";} ?> </span>

                                            <span class="clear"></span>  <br />

                                            Uploaded By: <?php echo $photo->username; ?>.

                                            <!--task #4581-->
                                            <?php if($photo->project_id != $development_id): ?>
                                                <?php echo " Photo from ".$photo->development_name; ?>
                                            <?php endif; ?>

                                        </div>

                                        <input type="hidden" value="<?php echo $photo->id; ?>">

                                        <div id="gallery_main">

                                            <a class="fancybox" href="<?php echo base_url(); ?>uploads/development/<?php echo $photo->filename; ?>">

                                                <img id="photo_<?php echo $photo->id;  ?>" src="<?php echo base_url(); ?>uploads/development/<?php echo $photo->filename; ?>" />

                                            </a>

                                        </div>

                                        <!---<div class="image-capton" style="background:#ECEBF0; padding: 5px;"><?php //echo $photo->photo_caption; ?></div>--->

                                    </div>

                                </li>

                            <?php } ?>

                        </ul>

                    </div>

                <?php } else{ ?>

                    <div style="width: 100%; height: 600px;">

                        <div class="flexslider">

                            <div class="flex-viewport" style="overflow: hidden; position: relative;">

                                <div class="devphotoLeft">

                                    <div class="" style="background:#fdd07c;color: #fff;font-weight: bold;">No images uploaded at the moment </div>

                                </div>

                            </div>

                            <div class="flex-control-thumbs11" style="height:400px!IMPORTANT">
								<img src="<?php echo base_url() ?>images/no_image_available.png" width="100%" style="height:380px !IMPORTANT" />
							</div>

                        </div>

                    </div>

                <?php } ?>

                
                <?php if(count($photos)>0):?>

                    <div id="carousel" class="flexslider" style="margin-top:-50px;">
                        <ul class="slides">
                            <?php foreach ($photos as $photo): ?>
                                <li>
                                    <img height="135px" src="<?php echo base_url(); ?>uploads/development/<?php echo $photo->filename; ?>" />
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                <?php endif; ?>
                
	
                <script>


                    $(document).ready(function () {

                        window.BaseUrl = '<?php print base_url(); ?>';

                        $("#save-development").click(

                            function () {

                                photo_id1 = $('li.flex-active-slide input').val();

                                window.location = window.BaseUrl+"constructions/pdf_developments_photo/"+photo_id1;

                            }

                        );

                        $("#print-development").click(

                            function () {

                                photo_id1 = $('li.flex-active-slide input').val();

                                window.open(window.BaseUrl+'constructions/print_developments_photo/'+photo_id1, '_blank', 'width=800,height=600,scrollbars=yes,status=yes,resizable=yes,screenx=0,screeny=0');

                            }

                        );

                        $("#email-development").click(

                            function () {

                                photo_id1 = $('li.flex-active-slide input').val();

                                $.ajax({

                                    url: "<?php print base_url(); ?>constructions/email_outlook_development/"+photo_id1,

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

                                $("#dev_photo_id").val(photo_id);

                            }

                        );
                        var angle = 0;
                        $("#rotate-photo").on('click',
                        	function(){
                        		
                        		photo_id = $('li.flex-active-slide input').val();
                                        angle += 90;
                                        $("#photo_"+ photo_id).rotate(angle);
                                      
                        	}
                        );


                        $("#photo-notes").click(function () {

                            photo_id = $('li.flex-active-slide input').val();

                            if(photo_id == null){

                                alert("No Photo Selected in the photo gallery");

                            }else{

                                window.location = window.BaseUrl+"constructions/photo_notes/"+photo_id+"/"+<?php echo $development_id?>;

                            }

                        });

                    });

                </script>

            </section>

        </div>
    </div>
    <div class="col-md-3">

       <!-- <div align="right" style="margin-bottom:5px;"><a href="<?php echo base_url(); ?>constructions/construction_photos/<?php echo $development_id?>/all?cp=<?php echo $_GET['cp']; ?>" class="btn btn-default">View All</a></div> -->

        <!-- <span style="float: right; "><b>Select Month:</b>  <input name="month" placeholder="" value="<?php echo $val; ?>" id="month" class="date-picker-photo" style="text-align: center; font-size: 100%" /></span> -->

        <div class="button-box" style="padding:0px; margin:10px 2px 5px 2px;float: left;">

            <?php

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

            <a id="save-development" class="btn_dev_info" title="Save Developments Photo" href="#"><img width="60" style="" title="Save Developments Info" class="save-developments" alt="Save Developments Info" src="<?php echo base_url();?>images/icon/btn_horncastle_save.png">

            </a>

            <a id="print-development" class="btn_dev_info" href="#"><img width="60" style="" title="Print Developments Photo" class="print-developments" alt="Print Developments Photo" src="<?php echo base_url();?>images/icon/btn_horncastle_printer.png">

            </a>

            <a id="rotate-photo" class="btn_dev_info" href="#">
                <img height="45" title="Rotate Developments Photo" class="save-developments" src="<?php echo base_url();?>images/icon/btn_rotate.png">
            </a>

			<?php if($user_app_role == 'manager' or $user_app_role == 'admin' ):?>
            <a id="delete-photo" class="btn_dev_info <?php echo $user_app_role; ?>" data-toggle="modal" role="button" href="#delete-photo-modal">
                <img width="45" title="Delete Developments Photo" src="<?php echo base_url();?>images/icon/btn_horncastle_trash.png">
            </a>
			<?php endif; ?>
            <!---<a id="photo-notes" class="btn_dev_info"  href="#">
                <img width="45" alt="Photo Notes"  title="Photo Notes" src="<?php echo base_url();?>images/icon/btn_horncastle_note.png">
            </a>--->
			<?php if($user_app_role == 'manager' or $user_app_role == 'admin' ):?>
            <a title="Upload Photo" class="btnuploadimage" id="btnOpenMe" type="button" name="uploadphoto">
                <img width="60" title="Upload Photo" class="upload-photo" alt="Upload Photo" src="<?php echo base_url();?>images/add_photo.png">
            </a>
			<?php endif; ?>
            <!--<div id="button-group" style="/*clear:both*/">

                <div class="button-wrapper" style="/*position: relative; margin-top: 10px; float: left;*/float:none">

                    <?php
/*
                    //echo '<a id="emailphotomessage" class="" href="#">'.img($email_photo).'</a>';

                    echo form_button($upload_photo_button);

                    echo img($upload_photo_icon);

                    */?>

                </div>

            </div>-->

        </div>
    </div>
</div>

<div id="photo-message-dialog-box" title="">

    <form id="addmessageform" action="<?php echo base_url();?>constructions/send_development_photo_message" method="post">

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

		<p>Are you sure want to delete this Construction Photo?</p>

	</div>

	<div class="modal-footer">

		<form action="<?php echo base_url();?>constructions/development_photo_delete?cp=<?php echo $_GET['cp']; ?>" method="post">

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

            <div id="preview" style="width:250px; height:250px;overflow: auto">

            </div>

            <form id="uploadform" action="<?php echo base_url();?>constructions/upload_development_photo/<?php echo $development_id."?cp={$_GET['cp']}";?>" method="post" enctype="multipart/form-data" >

                <input type="file" name="photoimg[]" id="photoimg" multiple style="width:230px"/>

            </form>

        </div>

    <div style="float:left;width:200px; height: 300px;margin-left: 10px;">

        <form id="photoinfoform" action="<?php echo base_url(); ?>constructions/save_development_photo/<?php echo $development_id . "?cp={$_GET['cp']}"; ?>" method="post">

            <span>Add Construction Photo</span>

            <input type="hidden" name="photo_insert_id" id="photo_insert_id" value=""/>

            <label>Caption</label> <br/>

            <textarea name="photo_caption"></textarea>

            <label>Permission Group</label> <br/>

            <select name="photo_permission" style="width: 200px;">
                <option value="">Select Permission Group </option>
                <option value="1">Public</option>
                <option value="2">Private</option>
				<option value="3">Investor</option>
            </select><br/><br/>

            <input type="submit" value="Save" class="btn btn-danger">

        </form>

    </div>

</div>

<style>
.flexslider .flex-control-thumbs {
    border: 4px solid #000;
    position: static;
    bottom: -235px;
    -webkit-border-radius: 4px;
    -moz-border-radius: 4px;
    -o-border-radius: 4px;
    border-radius: 4px;
    margin-bottom: 10px;
}
.flex-control-thumbs11 {
    margin: 5px 0 0;
    position: static;
    overflow: hidden;
    bottom: -40px;
    text-align: center;
    width: 100%;
}
</style>

<script type="text/javascript">

    // Can also be used with $(document).ready()

    $(window).load(function () {

        $('#slider').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            sync: "#carousel"

        });

        $('#carousel').flexslider({
            animation: "slide",
            controlNav: false,
            animationLoop: false,
            slideshow: false,
            itemWidth: 210,
            itemMargin: 5,
            asNavFor: '#slider'
        });

    });
    
    /*$(window).load(function() {
		$('.flexslider').flexslider({
			animation: "slide",
			controlNav: "thumbnails",
			slideshow: false
		});
	});*/

</script>

<script type="text/javascript">

    $(document).ready(function () {

        $("#dialog").dialog({

            autoOpen: false,

            width: 520,

            height: 350,

            modal: true

        });

        $("#photo-message-dialog-box").dialog({

            autoOpen: false,

            width: 720,

            height: 550,

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

        $('#photoimg').on('change', function () {

            var options = {

                target: '#preview',

                success: function () {

                    var photo_id = $('#development_photo_id').val();

                    $('#photo_insert_id').val(photo_id);

                }

            };

            $("#preview").html('');

            $("#preview").html('<img src="loader.gif" alt="Uploading...."/>');

            $("#uploadform").ajaxForm(options).submit();

        });
        // zoom in code
    });

</script>

<script>

    function email_dev_photo(photo_name) {

        $.ajax({

            url: "<?php print base_url(); ?>constructions/email_dev_photo/" + photo_name,

            dataType: 'html',

            type: 'GET',

            success: function (data) {

                //console.log(data);

                if (data) {

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

    <?php

          $y = $dt->format('Y'); $m = $dt->format('n')-1;

       ?>

    var defaultDate = new Date(<?php echo $y; ?>, <?php echo $m; ?>, 1);

    $(document).ready(function () {

        $(".fancybox").fancybox({

            'width': '760',

            'height': '550'

        });

        /*month picker*/

        $('.date-picker-photo').datepicker({

            changeMonth: true,

            changeYear: true,

            showButtonPanel: true,

            dateFormat: 'MM yy',

            defaultDate: defaultDate,

            onClose: function (dateText, inst) {

                var month = parseInt($("#ui-datepicker-div .ui-datepicker-month :selected").val()) + 1;

                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();

                //$(this).datepicker('setDate', new Date(year, month, 1));

                window.location = "<?php echo base_url()."constructions/construction_photos/".$this->uri->segment(3)."/"; ?>" + year + '-' + month + '?cp=<?php echo $_GET['cp']; ?>';

            }

        });


    });

</script>