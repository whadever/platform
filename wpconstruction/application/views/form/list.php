<link rel="stylesheet" href="<?php echo base_url();?>css/jquery-ui.css">
<div id="infoMessage">

    <?php if($this->session->flashdata('success-message')){ ?>

        <div class="alert alert-success" id="success-alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <?php echo $this->session->flashdata('success-message');?>
        </div>
    <?php } ?>

    <?php if($this->session->flashdata('warning-message')){ ?>

        <div class="alert alert-warning" id="warning-alert">
            <button type="button" class="close" data-dismiss="alert">x</button>
            <?php echo $this->session->flashdata('warning-message');?>
        </div>
    <?php } ?>

</div>
<a class="" style="margin-bottom: 5px; display: block" href="<?php echo site_url('job/show_popup_menu'); ?>"><< main menu</a>
<div class="popup_title">
    <h2 class="popup_title2">List Form</h2>
</div>

<table class="table"><?php
foreach($forms as $form):
?>
    <tr>
        <td style="color: #888; font-size: 18px; vertical-align: middle;"><?php echo $form->name; ?></td>
        <td style="text-align: right">
            <a class="btn btn-default" href="<?php echo site_url('constructions/edit_form/'.$form->id); ?>"><img class="btn-delete" height="16px" src="<?php echo base_url() . 'images/icon/edit_pen.png'; ?>"/> Edit</a>
            <a class="btn btn-danger" href="<?php echo site_url('constructions/delete_form/'.$form->id); ?>"><img class="btn-delete" src="<?php echo base_url() . 'images/delete.png'; ?>"/> delete</a>
        </td>
    </tr>
<?php endforeach; ?>
</table>
<div id="dialog-confirm" title="Delete Form" style="display: none">
    <p style="text-align: justify"><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 0 0;"></span>Are
        Are you sure you want to delete this form?</p>
</div>
<script src="<?php echo base_url();?>js/jquery-ui.js"></script>
<script>
    $(document).ready(function(){
        $(".btn-danger").click(function(){
            var url = $(this).prop('href');
            $("#dialog-confirm").dialog({
                resizable: false,
                height: 200,
                modal: true,
                buttons: {
                    "Yes": function () {
                        window.location = url;
                    },
                    Cancel: function () {

                        $(this).dialog("close");
                    }
                }
            });
            return false;
        });

        $("#infoMessage").fadeTo(3000, 500).slideUp(500, function(){
            $('#infoMessage').remove();
        });
    })
</script>

