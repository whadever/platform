<style>
    .field-label {
        color: #666;
        display: block;
        font-size: 14px;
    }
    .field-label.required:after{
        content: "*";
        color: red;
        font-weight: bold;
    }
    #overlay {
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        right: 0;
        background: #000;
        opacity: 0.8;
        filter: alpha(opacity=80);
    }
    #loading {
        width: 220px;
        height: 19px;
        position: absolute;
        top: 50%;
        left: 45%;
        margin: -28px 0 0 -25px;
    }

    #submission_period {
        display: table;
        margin-bottom: 25px;
    }
    #submission_period * {
        display: table-cell;
        margin-right: 20px;
    }
    small, .small {
        color: gray;
        font-size: 82%;
    }

</style>
<div id="all-title">
    <div class="row">
        <div class="col-md-8">
            <img width="35" src="<?php echo base_url() ?>images/title-icon.png"/>
            <span class="title-inner"><?php echo $title; ?></span>
        </div>
        <?php if($submission_period): ?>
        <div class="col-md-4 text-right">
            <img width="35" style="visibility: hidden" src="http://localhost/wclp/rs/images/title-icon.png">
            <span class="title-inner">Report for: <?php echo date('d/m/Y',strtotime($submission_period->from)); ?>
            <?php if($submission_period->from != $submission_period->to){
                echo " to ".date('d/m/Y',strtotime($submission_period->to));
            }?>
            </span>
        </div>
        <?php endif; ?>
    </div>
</div>

<div class="content-inner">

    <div class="row">

        <form method="post" enctype="multipart/form-data" id="frmReport">

        <?php if(($user_role == 'manager' || $user_role == 'admin') && $form->frequency == 'monthly'): ?>
            <div class="col-md-12" id="submission_period">
                <span style="width: 15%">Modify submission period: <small>(this will be displayed in the report)</small></span> <input required type="text" class="date form-control" name="from_date" style="" value="<?php echo $submission_period->from; ?>"> <span>&nbsp;&nbsp;to</span> <input required type="text" class="date form-control" name="to_date" style=""  value="<?php echo $submission_period->to; ?>">
            </div>
        <?php endif; ?>

        <div class="col-md-6" style="">

            <?php print_fields($form_fields, 1); ?>

        </div>

        <div class="col-md-6" style="">

            <?php print_fields($form_fields, 2); ?>

        </div>

        <div class="col-md-6" style="">

            <span style="   color: red;
                            font-weight: bold;
                            float: left;
                            font-size: 20px;
                            margin-right: 5px;">*</span>
            <span style="color: #666">required fields<span>

        </div>
        <div class="col-md-6" style="text-align: right">

           <button type="button" class="btn btn-default" id="btnPreview" name="preview">Preview</button>

           <input type="submit" name="submit" value="Submit" class="btn" style="background-color: <?php echo $color_one; ?>; color: white">

        </div>

        </form>

    </div>

</div>

<?php
function print_fields($form_fields, $col){
  ?>

    <?php foreach($form_fields as $field): ?>
        <?php if($field->column == $col): ?>
            <?php $required = ($field->required == 1) ? "required" : "";?>
            <div class="form-group">
                <label class="field-label <?php echo $required; ?>" for="field_<?php echo $field->id; ?>"><?php echo $field->title; ?></label>

                <?php if($field->type == 'text'): ?>
                    <textarea name="field_<?php echo $field->id; ?>" class="form-control" id="" placeholder="" <?php echo $required; ?>></textarea>
                <?php endif; ?>

                <?php if($field->type == 'select'): ?>
                    <select name="field_<?php echo $field->id; ?>"  class="form-control">
                        <?php if($required == ""): ?>
                            <option value="">---select---</option>
                        <?php endif; ?>
                        <?php foreach($field->select_options as $val): ?>
                            <option value="<?php echo $val; ?>"> <?php echo $val; ?></option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>

                <?php if($field->type == 'date'): ?>
                    <input type="text" name="field_<?php echo $field->id; ?>" class="form-control date" id="" placeholder="" <?php echo $required; ?>>
                <?php endif; ?>

                <?php if($field->type == 'radio-group-yes-no-na'): ?>

                    <input type="radio" name="field_<?php echo $field->id; ?>" value="yes"> Yes
                    <input type="radio" name="field_<?php echo $field->id; ?>" value="no"> No
                    <input type="radio" name="field_<?php echo $field->id; ?>" value="na"> N/A

                <?php endif; ?>

                <?php if($field->type == 'numbers'): ?>

                    <select name="field_<?php echo $field->id; ?>" class="form-control">
                        <?php if($required == ""): ?>
                            <option value="">---select---</option>
                        <?php endif; ?>
                        <?php for($i=0; $i <= 100; $i++ ): ?>
                            <option value="<?php echo $i; ?>"> <?php echo $i; ?></option>
                        <?php endfor; ?>
                    </select>

                <?php endif; ?>

                <?php if($field->type == 'document'): ?>
                    <input type="file" name="field_<?php echo $field->id; ?>" class="" id="" <?php echo $required; ?>>
                <?php endif; ?>


            </div>
        <?php endif; ?>
    <?php endforeach; ?>

<?php
}
?>
<div id="overlay" style="display: none">
    <img id="loading" src="<?php echo site_url('images/loader.gif'); ?>">
</div>
<script>
    $(".date").datepicker({ dateFormat: 'yy-mm-dd' });
    var form_id = <?php echo $this->uri->segment(3); ?>;
    var url = "<?php echo site_url('form/preview'); ?>/"+form_id+"/"+<?php echo $submission_period->id; ?>;
    $(document).ready(function(){
        $("#btnPreview").click(function(){
            $("#overlay").show();
            $.ajax(url, {
                data: new FormData($("#frmReport")[0]),
                type: 'post',
                processData: false,
                contentType: false,
                success: function(data){
                    $("<div />",{
                        title: 'Preview'
                    }).html(data).dialog({
                        width: 650,
                        height: 650,
                        modal: true
                    });
                    $("#overlay").hide();
                }
            })
        })
    })
</script>

