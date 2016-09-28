<style>
    .field_label {
        font-weight: bold;
    }

    .notes {
        border: 1px solid;
        border-radius: 9px;
        margin-left: 15px;
        padding: 2px 8px;
    }
</style>

<div id="contact-detail" class="content-inner">
    <?php
    $user = $this->session->userdata('user');
    $user_role_id = $user->rid;
    $this->breadcrumbs->push('Contact', 'contact/contact_list');
    $this->breadcrumbs->push($contact->contact_first_name . ' ' . $contact->contact_last_name, 'contact/contact_details/' . $contact->id);
    ?>
    <div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div>

    <div class="box cdetails">
        <div class="row">
            <div class="col-xs-12 col-sm-1 col-md-1"></div>

            <div class="col-xs-12 col-sm-6 col-md-2">
                <div id="table-company">
                    <?php if ($contact->filename): ?>
                        <img src="https://williamscorporation.co.nz/wp/wpcontact/uploads/request/document/<?php echo $contact->filename; ?>"
                             style="width:100%"/>

                    <?php else: ?>
                        <img src="<?php echo base_url(); ?>images/no_photo.png"
                             style="width:100%"/>
                    <?php endif; ?>
                </div>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-8">
                <h1 style="margin:0 0 3px; color:#221e1e; font-weight: bold"><?php echo $contact->contact_first_name . ' ' . $contact->contact_last_name; ?></h1>

                <h3 style="margin:0 0 8px;color:#221e1e"><?php echo $contact->contact_title; ?></h3>

                <h3 style="margin:0 0 8px;color:#221e1e"><a href="<?php echo site_url('company/company_details/'.$contact->company_id); ?>"> <?php echo $contact->company_name; ?></a></h3>

                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 field_label">Phone:</div>
                            <div class="col-xs-6 col-sm-6 col-md-6"><?php echo $contact->contact_phone_number; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 field_label">Mobile Number:</div>
                            <div class="col-xs-6 col-sm-6 col-md-6"><?php echo $contact->contact_mobile_number; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 field_label">Email Address:</div>
                            <div class="col-xs-6 col-sm-6 col-md-6"><?php echo $contact->contact_email; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 field_label">Notes:</div>
                            <textarea id="note" class="col-xs-12 col-sm-9 col-md-9 notes"><?php echo $contact->contact_notes; ?></textarea>
                            <!--<div class="col-xs-12 col-sm-2 col-md-2" style="cursor: pointer"><img id="btnSave" src="<?php /*echo base_url() */?>images/icons/btn_save.png"/></div>-->
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-2 field_label">
                                Address:
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-8">
                                <?php echo $contact->contact_address; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <script>
        var base_url = '<?php echo base_url(); ?>';
        var contact_id = <?php echo $contact->id; ?>;
        $(document).ready(function(){

            $("#note").blur(function(){
                $("#note").prop('disabled',true);
                $.ajax(base_url+'contact/edit_note/'+contact_id,{
                    method: 'post',
                    data:{
                        note: $("#note").val()
                    },
                    success:function(){
                        $("#note").prop('disabled',false);
                    }
                })
            })
        })
    </script>