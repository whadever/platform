<style>
    .search_contact {
        border: 1px solid #ccc;
        border-radius: 5px;
        font-size: 12px;
        height: 33px;
        margin: 0 10px 0 0;
        padding: 6px;
        width: 90%;
    }
    .highlight { background-color: yellow }
</style>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.highlight.js"></script>
<script>

    $.fn.eqAnyOf = function (arrayOfIndexes) {
        return this.filter(function (i) {
            return $.inArray(i, arrayOfIndexes) > -1;
        });
    };


    window.url = '<?php echo base_url(); ?>';

    $(document).ready(function () {

        $("#infoMessage").fadeTo(5000, 500).slideUp(500, function () {
            $('#infoMessage').remove();
            //$("#success-alert").alert('close');
        });

        $('.clickdiv').click(function () {
            //$(this).find('.hiders').toggle();
            $('.hiders').slideToggle();
            $('#minus').toggle();
            $('#plus').toggle();
        });

        $("#search_company").bind("keyup", advance_search);

        $("#company_id").selectpicker();

    });


    /*function advance_search() {

        var search_key = $("#search_company").val();
        var count = 0;
        if (search_key != '') {

            $.ajax({
                type: 'GET',
                url: window.url + 'company/company_search/' + search_key,
                success: function (data) {
                    $('#company_list').html(data);
                    $('.pagination').html('');
                },

            });

        }

    }*/
    function advance_search()
    {

        var filter = $("#search_company").val(), count = 0;
        var parr = new Array();
        parr = [0,1,2,3,4,5,6,7];

        $("#company_list table tr").each(function()
        {
            /*for contact numbers we will not consider the gaps when searching*/
            var contact_no = $(this).find("td").eq(3).text().replace(/\s+/g,'');

            if ($(this).find("td").eqAnyOf(parr).text().search(new RegExp(filter, "i")) < 0  && contact_no.search(new RegExp(filter, "i")) < 0)
            {
                if(this.id != 'header')
                {
                    $(this).fadeOut();
                }
            }
            else
            {
                $(this).show();
                count++;
            }

            /*highlighting search term*/
            var body = $(this).find("td").eqAnyOf(parr);
            body.unhighlight();
            body.highlight( $('#search_company').val() );

        });
    }

</script>

<div id="all-title">
    <img width="35" src="<?php echo base_url() ?>images/title-icon.png"/>
    <span class="title-inner"><?php echo $title; ?></span>
</div>
<div class="clear"></div>
<div class="content-inner">
    <div id="infoMessage">

        <?php if ($this->session->flashdata('success-message')) { ?>

            <div class="alert alert-success" id="success-alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>Success! </strong>
                <?php echo $this->session->flashdata('success-message'); ?>
            </div>
        <?php } ?>

        <?php if ($this->session->flashdata('warning-message')) { ?>

            <div class="alert alert-warning" id="warning-alert">
                <button type="button" class="close" data-dismiss="alert">x</button>
                <strong>Success! </strong>
                <?php echo $this->session->flashdata('warning-message'); ?>
            </div>
        <?php } ?>

    </div>


    <div class="row">
        <div class="col-md-12">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-4">
                    <div class="contactsearchbox">
                        <input type="text" id="search_company" class="search_contact" name="search"
                               placeholder="Search"/><!--Advance Search-->
                    </div>
                </div>
                <div class="col-xs-12 col-sm-12 col-md-8">
                   <!-- <span style="float:right;"><a data-toggle="modal" data-target="#AddCompany">Add Company <img
                                src="<?php /*echo base_url() */?>images/icons/icon_add_company.png" width="40"/></a> </span>-->
                    <span style="float:right;padding-left: 10px;"><a href="<?php echo base_url().'company/add_bulk_company'; ?>">Add bulk Company <img
                                src="<?php echo base_url() ?>images/icons/icon_add_company.png" width="40"/></a> </span>
                                
                    <span style="float:right;"><a href="<?php echo base_url().'company/company_add'; ?>">Add Company <img
                                src="<?php echo base_url() ?>images/icons/icon_add_company.png" width="40"/></a> </span>
                                
                    <span style="float:right;    margin-right: 10px;">
                    	<a class="btn btn-default" href="<?php echo base_url().'company/company_export_to_excel'; ?>">
                    		EXCEL </a> </span>
                </div>
            </div>
        </div>
    </div>

    <hr/>

    <!-- Add Company Modal -->
    <div class="modal fade" id="AddCompany" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header" style="background: #d72530; color: white;font-weight:bold; font-size:18px;">
                    Add Company
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <?php
                    $ci = &get_instance();
                    $form_attributes = array('class' => 'company-add-form', 'id' => 'entry-form', 'method' => 'post');
                    $action = 'company/company_add';
                    $company_id = form_hidden('id', isset($company->id) ? $company->id : '');

                    $company_name = form_label('Company Name (*)', 'contact_title');
                    $company_name .= form_input(array(
                        'name' => 'company_name',
                        'id' => 'edit-company_name',
                        'value' => isset($company->company_name) ? $company->company_name : '',
                        'class' => 'form-control',
                        'placeholder' => 'Enter Company Name',
                        'required' => TRUE
                    ));


                    $ci->load->model('category_model');
                    $cat_options = $ci->category_model->get_category_option_list();
                    $category_options = array('0' => '--Select Category--') + $cat_options;
                    $catid = isset($category_id) ? $category_id : 0;
                    $companyid = isset($company->category_id) ? $company->category_id : $company_id;
                    $company_js = 'id="company_id" onChange="" class="form-control selectpicker1" required="true" multiple';
                    $category_list = form_label('Category', 'category_id');
                    $category_list .= form_dropdown('category_id[]', $category_options, $companyid, $company_js);


                    $image_id = form_hidden('image_id', isset($company->image_id) ? $company->image_id : '');
                    $image = form_label('Image', 'upload_image');
                    if (isset($contact->image)) {
                        $image_file = $contact->image;
                        $image .= form_upload(array(
                            'name' => 'upload_image',
                            'id' => 'upload-image',
                            'class' => 'form-file form-control',
                            'type' => 'file',
                        ));

                    } else {
                        $image .= form_upload(array(
                            'name' => 'upload_image',
                            'id' => 'upload-image',
                            'class' => 'form-file form-control',
                            'type' => 'file',
                        ));
                    }

                    $company_notes = form_label('Notes', 'contact_notes');
                    $company_notes .= form_textarea(array(
                        'name' => 'company_notes',
                        'id' => 'edit-contact_notes',
                        'value' => isset($company->company_notes) ? $company->company_notes : set_value('company_notes', ''),
                        'class' => 'form-control',
                        'size' => '60',
                        'rows' => 5,
                        'cols' => 20,
                    ));


                    $submit = form_label(' ', 'submit');
                    $submit .= form_submit(array(
                        'name' => 'submit',
                        'id' => 'edit-submit',
                        'value' => 'Save',
                        'class' => 'btn btn-default',
                        'type' => 'submit',
                        'onclick' => 'checkEmail();',
                    ));


                    echo '<div id="" class="" style="color:red;">' . validation_errors() . '</div>';
                    echo form_open_multipart($action, $form_attributes);
                    echo '<div id="sid-wrapper" class="field-wrapper">' . $company_id . '</div>';

                    ?>


                    <div class="company_add">
                        <div class="row">
                            <div class="col-xs-12 col-sm-12 col-md-12">
                                <?php echo $company_name; ?>
                                <?php echo $category_list; ?>
                                <?php echo $image; ?>
                                <?php echo $company_notes; ?>
                            </div>
                        </div>
                        <p>&nbsp;</p>

                        <div class="row">
                            <div class="col-xs-9 col-sm-10 col-md-8">
                            </div>
                            <div class="col-xs-9 col-sm-10 col-md-2">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                            </div>
                            <div class="col-xs-3 col-sm-2 col-md-2"><?php echo $submit; ?></div>
                        </div>
                    </div>
                    <?php
                    echo form_close();
                    ?>
                </div>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-md-12">
            <div id="contact_list_view">

                <div id="company_list">
                    <?php if (isset($table)) {
                        echo $table;
                    } ?>
                </div>
                <div class="pagination">
                    <?php if (isset($pagination)) {
                        echo $pagination;
                    } ?>
                </div>

            </div>
        </div>
    </div>

</div>