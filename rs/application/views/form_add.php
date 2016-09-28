<link rel="stylesheet" href="<?php echo base_url();?>css/form-builder.css" type="text/css" media="screen" />
<style>
    .frmb a.del-button {
        border: 1px solid #fff;
        color: #000;
        display: inline-block;
        height: 12px;
        line-height: 13px;
        text-align: center;
        text-decoration: none;
        width: 11px;
    }
    .frmb a.del-button, .frmb a.remove {
        border-color: #b3b3b3;
        border-radius: 0 5px 0 0;
        display: inline-block;
        opacity: 0;
        padding: 3px 4px;
        position: absolute;
        right: -1px;
        top: -1px;
        box-sizing: content-box;
    }
    .active-tour #frmb-0-control-box li {
        background-color: unset;
        border: medium none;
    }
</style>
<div id="all-title">
    <div class="row">
        <div class="col-md-12">
            <img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
            <span class="title-inner"><?php echo $title;  ?></span>
        </div>
    </div>
</div>
<?php
    $id = (isset($form)) ? $form->id : "";
?>
<form action="<?php echo site_url('form/create/'.$id); ?>" method="post">

<div class="content-inner row tour tour_4">
    <div class="col-md-2">
        <h4 style="color: #666">Name of the Report</h4>
    </div>
    <?php
    $name = (isset($form)) ? " value = '{$form->name}' " : "";
    ?>
    <div class="col-md-7" style="">
        <input type="text" class="form-control" <?php echo $name; ?> name="name" required>
    </div>

</div>
<div class="content-inner row">
    <!--<div class="row">
        <div class="col-md-10" style="background-color: <?php /*echo $color_one; */?>; color: white;"><h4 align="center">Form</h4></div>
        <div class="col-md-2"  style="background-color: <?php /*echo $color_one; */?>; color: white;"><h4 align="center">Items</h4></div>
    </div>-->
    <div class="row" style="margin: 0">
        <textarea name="fields"></textarea>
    </div>
</div>
<div class="row content-inner">
    <div class="col-md-2 col-md-offset-10" style="text-align: center">
        <input class="btn" type="submit" value="Save and Assign Staff" style="background-color: <?php echo $color_one; ?>; color: white">
    </div>
</div>
</form>
<script type="text/javascript" src="<?php echo base_url();?>js/form-builder.js" ></script>
<script>
    var current_fields = [];
    <?php
        if(isset($form_fields)):
    ?>
    current_fields = <?php echo json_encode($form_fields); ?>;
    <?php endif; ?>
    jQuery(document).ready(function($) {
        'use strict';
        $('textarea').formBuilder();

        $("form").submit(function(){
            $("textarea").val($(".frmb").toXML());
        });

        /*task #4429*/
        $(".frmb-wrap").addClass('tour tour_5');
        $("#frmb-0-cb-wrap").addClass('tour tour_6');
    });

    /*task #4429*/
    config.push({
        "name" 		: "tour_4",
        "bgcolor"	: "black",
        "color"		: "white",
        "position"	: "T",
        "text"		: "Name the report template.",
        "time" 		: 5000,
        "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default nextstep'>next</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
    });
    config.push({
        "name" 		: "tour_5",
        "bgcolor"	: "black",
        "color"		: "white",
        "position"	: "B",
        "text"		: "Drop your item(s) from Form Elements.",
        "time" 		: 5000,
        "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default nextstep'>next</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
    })
    config.push({
        "name" 		: "tour_6",
        "bgcolor"	: "black",
        "color"		: "white",
        "position"	: "RT",
        "text"		: "Easily drag an item from Form Elements to Preview field.",
        "time" 		: 5000,
        "buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>","<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
    });
    total_steps	= config.length;
</script>