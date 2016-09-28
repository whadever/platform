<!--<link href="<?php /*echo base_url();*/?>css/style_contact.css" rel="stylesheet" type="text/css"/>-->
<style xmlns="http://www.w3.org/1999/html">
    .field_label {
        font-weight: bold;
    }

    .notes {
        border: 1px solid;
        border-radius: 9px;
        margin-left: 15px;
        padding: 2px 8px;
    }
    .contact-name {
        color: black;
        font-weight: bold;
        margin: 0;
    }
    .contractor #btnSave{
        display: none;
    }
</style>


<div id="contact-detail" class="content-inner <?php echo $user_app_role; ?>">
    <?php
    $user = $this->session->userdata('user');
    $user_role_id = $user->rid;
    $this->breadcrumbs->push('Contact', 'contact/contact_list');
    $this->breadcrumbs->push($company->company_name, 'company/company_details/' . $company->id);
    $domain = $_SERVER["SERVER_NAME"];
    ?>
    <div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div>
    <?php if(in_array($domain,array('xprobuilders.wclp.co.nz','horncastle.wclp.co.nz'))): ?>
        <!---<a href="<?php echo site_url('company/competency_register_pdf/'.$company->id); ?>" class="btn btn-default" style="float: right">Competency Register</a>--->
    <?php endif; ?>
    <div class="box cdetails">
        <div class="">
            <div class="col-xs-12 col-sm-12 col-md-1"></div>

            <div class="col-xs-12 col-sm-12 col-md-2">
                <div id="table-company">
                    <?php if ($company->filename): ?>
                        <img width="150" src="<?php echo base_url(); ?>uploads/request/document/<?php echo $company->filename; ?>"
                             style=""/>

                    <?php else: ?>
                        <img  src="<?php echo base_url(); ?>images/no_image.jpg" style="border: 1px solid black"/>
                    <?php endif; ?>

                    <?php /*if($company->filename) { */ ?><!--<img src="<?php /*echo base_url(); */ ?>uploads/request/document/<?php /*echo $company->filename; */ ?>" style="width:100%" />--><?php /*} */ ?>
                </div>

            </div>
            <div class="col-xs-12 col-sm-12 col-md-6">
                <h1 style="margin:0 0 3px; color:#221e1e; font-weight: bold"><?php echo $company->company_name; ?></h1>

                <h3 style="margin:0px;color:#221e1e"><?php //echo $company->contact_title; ?></h3>

                <div class="row">
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 field_label">Contact Number:</div>
                            <div class="col-xs-6 col-sm-6 col-md-6"><?php echo $company->contact_number; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 field_label">City:</div>
                            <div class="col-xs-6 col-sm-6 col-md-6"><?php echo $company->company_city; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 field_label">Country:</div>
                            <div class="col-xs-6 col-sm-6 col-md-6"><?php echo $company->company_country; ?></div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-6 field_label">Notes:</div>
                            <textarea id="note" class="col-xs-12 col-sm-9 col-md-9 notes"><?php echo $company->company_notes; ?></textarea>
                            <div class="col-xs-12 col-sm-2 col-md-2" style="cursor: pointer"><img id="btnSave" src="<?php echo base_url() ?>images/icons/btn_save.png"/></div>
                        </div>
                    </div>
                    <div class="col-xs-12 col-sm-6 col-md-6">
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-2 field_label">
                                Address:
                            </div>
                            <div class="col-xs-6 col-sm-6 col-md-8">
                               <span style="margin-left: 10px"> <?php echo $company->company_address; ?></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-6 col-sm-6 col-md-2 field_label">Website:</div>
                            <div class="col-xs-6 col-sm-6 col-md-6"><span style="margin-left: 10px"><a href="<?php echo $company->company_website; ?>" target="_blank"><?php echo $company->company_website; ?></a></span></div>
                        </div>
                    </div>
                </div>
                <div class="row" style="margin-top: 20px">
                    <div class="col-xs-12 col-sm-10 col-md-10">
                        <?php foreach ($contacts as $contact): ?>
                            <div class="col-xs-12 col-sm-6 col-md-6">

                                <div class="col-xs-12 col-sm-12 col-md-4">
                                    <a href="<?php echo base_url(); ?>contact/contact_details/<?php echo $contact['id']; ?>" >
                                    <?php if ($contact['filename']): ?>

                                        <img class=""
                                             src="<?php echo base_url(); ?>uploads/request/document/<?php echo $contact['filename']; ?>"
                                             style="max-width: 68px"/>


                                    <?php else: ?>
                                        <img src="<?php echo base_url(); ?>images/no_photo.png"
                                             style="max-width: 68px"/>
                                    <?php endif; ?>
                                    </a>
                                </div>
                                <div class="col-xs-12 col-sm-12 col-md-8">
                                    <a href="<?php echo base_url(); ?>contact/contact_details/<?php echo $contact['id']; ?>" >
                                    <h2 class="contact-name"><?php echo $contact['name']; ?></h2>
                                    </a>
                                    <h5 style="font-weight: bold; margin: 0"><?php echo $contact['title']; ?></h5>
                                </div>

                             </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="col-xs-12 col-sm-12 col-md-3">
            	<?php if(in_array($domain,array('xprobuilders.wclp.co.nz','horncastle.wclp.co.nz'))): ?>
        			<a href="<?php echo site_url('company/competency_register_pdf/'.$company->id); ?>" class="form-control btn btn-default" style="float: right">Competency Register Report</a>
    			<?php endif; ?>
				<hr>
            	<a href="<?php echo base_url(); ?>company/competency_register/<?php echo $company->id; ?>" class="form-control btn btn-default">Competency Register</a>
            </div>
        </div>

    </div>

    <script>
        var base_url = '<?php echo base_url(); ?>';
        var company_id = <?php echo $company->id; ?>;
        $(document).ready(function(){

            $("#btnSave").click(function(){
                $("#note").prop('disabled',true);
                $.ajax(base_url+'company/edit_note/'+company_id,{
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