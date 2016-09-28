    <?php

    $form_attributes = array('class' => 'contact-add-form', 'id' => 'contact_add_form', 'method' => 'post');
    $id = form_hidden('id', isset($contact->id) ? $contact->id : '');

    $contact_position = form_label('Position', 'contact_title');
    $contact_position .= form_input(array(
        'name' => 'contact_title',
        'id' => 'edit-contact_title',
        'value' => isset($contact->contact_title) ? $contact->contact_title : '',
        'class' => 'form-control',
        'placeholder' => 'Enter Contact Position',
        'required' => TRUE
    ));

    $contact_first_name = form_label('First Name', 'contact_first_name');
    $contact_first_name .= form_input(array(
        'name' => 'contact_first_name',
        'id' => 'edit-contact_first_name',
        'value' => isset($contact->contact_first_name) ? $contact->contact_first_name : '',
        'class' => 'form-control',
        'placeholder' => 'Enter First Name',
        'required' => TRUE
    ));

    $contact_last_name = form_label('Last Name', 'contact_last_name');
    $contact_last_name .= form_input(array(
        'name' => 'contact_last_name',
        'id' => 'edit-contact_last_name',
        'value' => isset($contact->contact_last_name) ? $contact->contact_last_name : '',
        'class' => 'form-control',
        'placeholder' => 'Enter Last Name',
        'required' => TRUE
    ));

    $ci = &get_instance();
    $ci->load->model('company_model');
    $comp_options = $ci->company_model->get_company_list();
    $company_options = array('' => '--Select Company--') + $comp_options;
    $cid = isset($company_id) ? $company_id : 0;
    $companyid = isset($contact->company_id) ? $contact->company_id : $cid;
    $company_js = 'id="company_id" class="form-control selectpicker1" required="true"';
    $project_company = form_label('Company', 'company_id');
    $project_company .= form_dropdown('company_id', $company_options, $companyid, $company_js);

    $system_user_id = isset($contact->system_user_id) ? $contact->system_user_id : '';
    $system_user_list_js = 'id="systerm_user_id" class="form-control"';
    $system_users_option = array('' => '--Select Connect User--') + $system_users;
    $system_user_list = form_label('Connect User', 'system_user_id');
    $system_user_list .= form_dropdown('system_user_id', $system_users_option, $system_user_id, $system_user_list_js);


    $image_id = form_hidden('image_id', isset($contact->contact_image_id) ? $contact->contact_image_id : '');
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

    $contact_notes = form_label('Notes', 'contact_notes');
    $contact_notes .= form_textarea(array(
        'name' => 'contact_notes',
        'id' => 'edit-contact_notes',
        'value' => isset($contact->contact_notes) ? $contact->contact_notes : set_value('contact_notes', ''),
        'class' => 'form-control',
        'size' => '60',
        'rows' => 5,
        'cols' => 20,
    ));

    $contact_phone_number = form_label('Phone Number', 'contact_phone_number');
    $contact_phone_number .= form_input(array(
        'name' => 'contact_phone_number',
        'id' => 'edit-contact_phone_number',
        'value' => isset($contact->contact_phone_number) ? $contact->contact_phone_number : '',
        'class' => 'form-control',
        'placeholder' => 'Enter Phone Number',
    ));

    $contact_mobile_number = form_label('Mobile Number', 'contact_mobile_number');
    $contact_mobile_number .= form_input(array(
        'name' => 'contact_mobile_number',
        'id' => 'edit-contact_mobile_number',
        'value' => isset($contact->contact_mobile_number) ? $contact->contact_mobile_number : '',
        'class' => 'form-control',
        'placeholder' => 'Enter Mobile Number',
    ));

    $contact_email = form_label('Email Address', 'contact_email');
    $contact_email .= form_input(array(
        'name' => 'contact_email',
        'id' => 'edit-contact_email',
        'value' => isset($contact->contact_email) ? $contact->contact_email : '',
        'class' => 'form-control',
        'placeholder' => 'Enter Email Address',
    ));

    $ci->load->model('category_model');
    $cat_options = $ci->category_model->get_category_option_list();
    $category_options = array('0' => '--Select Category--') + $cat_options;
    $catid = isset($category_id) ? $category_id : 0;
    $companyid = isset($contact->category_id) ? $contact->category_id : $cid;
    $category_js = 'id="category_id" onChange="" class="form-control selectpicker1" required="true"';
    $category_list = form_label('Category', 'category_id');
    $category_list .= form_dropdown('category_id', $category_options, $companyid, $category_js);


    $contact_address = form_label('Address', 'contact_address');
    $contact_address .= form_input(array(
        'name' => 'contact_address',
        'id' => 'edit-contact_address',
        'value' => isset($contact->contact_address) ? $contact->contact_address : '',
        'class' => 'form-control',
        'placeholder' => 'Enter Address',
    ));

    $contact_city = form_label('City', 'contact_city');
    $contact_city .= form_input(array(
        'name' => 'contact_city',
        'id' => 'edit-contact_city',
        'value' => isset($contact->contact_city) ? $contact->contact_city : '',
        'class' => 'form-control',
        'placeholder' => 'Enter City',
    ));

    $contact_country = form_label('Country', 'contact_country');
    $contact_country .= form_input(array(
        'name' => 'contact_country',
        'id' => 'edit-contact_country',
        'value' => isset($contact->contact_country) ? $contact->contact_country : '',
        'class' => 'form-control',
        'placeholder' => 'Enter Country',
    ));


    $contact_website = form_label('Website', 'contact_website');
    $contact_website .= form_input(array(
        'name' => 'contact_website',
        'id' => 'edit-contact_website',
        'value' => isset($contact->contact_website) ? $contact->contact_website : '',
        'class' => 'form-control',
        'placeholder' => 'Enter Website Address',
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

    ?>

    <script>
        window.Url = "<?php print base_url(); ?>";
        $(document).ready(function () {

            $("#company_id").change(function () {
                $.ajax('<?php echo base_url();?>contact/get_contact_details/' + $(this).val(), {
                    success: function (data) {

                        $("#category_id").val(data);

                    }

                })
            });

        });
    </script>


    <div class="clear"></div>

    <?php
    $this->breadcrumbs->push('Contact', 'contact/contact_list');
    if (isset($contact->id)) {
        $this->breadcrumbs->push($contact->contact_first_name . ' ' . $contact->contact_last_name, 'contact/contact_details/' . $contact->id);
        $this->breadcrumbs->push('Modify Contact', 'contact/contact_add/' . $contact->id);
    } else {
        $this->breadcrumbs->push('Contact Add', 'contact/contact_add/');
    }
    ?>
    <div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div>

    <div id="contact_add_edit_page" class="content-inner">

        <?php

        echo '<div id="" class="" style="color:red;">' . validation_errors() . '</div>';
        echo form_open_multipart($action, $form_attributes);
        echo '<div id="sid-wrapper" class="field-wrapper">' . $id . '</div>';

        ?>

        <script>

            function showmore(clobj) {
                $('#contact_more').css('display', 'block');
                $('#showmore').css('display', 'none');
            }
            function showless(clobj) {
                $('#contact_more').css('display', 'none');
                $('#showmore').css('display', 'block');
            }

        </script>

        <div class="contact_add">
            <div class="row">
                <div class="col-xs-12 col-sm-4 col-md-3"></div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <?php echo $contact_position; ?>
                    <?php echo $contact_first_name; ?>
                    <?php echo $contact_last_name; ?>
                    <?php echo $project_company; ?>
                    <?php echo $system_user_list; ?>

                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <?php echo $contact_phone_number; ?>
                    <?php echo $contact_mobile_number; ?>
                    <?php echo $contact_email; ?>
                    <?php echo $category_list; ?>

                    <a id="showmore" class="less" href="#" onclick="showmore(this);">Show More</a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3"></div>
            </div>


            <div class="row" id="contact_more" style="display:none">
                <div class="col-xs-12 col-sm-4 col-md-3"></div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <?php echo $image_id;
                    if (isset($image_file)) {
                        echo '<div id="sid-wrapper" class="field-wrapper file">' . $image . '<div id="fakefile1" class="fakefile">' . $image_file . '</div></div>';
                    } else {
                        echo '<div id="sid-wrapper" class="field-wrapper file">' . $image . '<div id="fakefile1" class="fakefile">Upload Images....</div></div>';
                    } ?>

                    <?php echo $contact_notes; ?>

                </div>
                <div class="col-xs-12 col-sm-4 col-md-3">
                    <?php echo $contact_address; ?>
                    <?php echo $contact_city; ?>
                    <?php echo $contact_country; ?>
                    <?php echo $contact_website; ?>
                    <a id="showless" class="less" href="#" onclick="showless(this);">Show Less</a>
                </div>
                <div class="col-xs-12 col-sm-4 col-md-3"></div>
            </div>

            <p>&nbsp;</p>

            <div class="row">
                <div class="col-xs-9 col-sm-10 col-md-9"></div>
                <div class="col-xs-3 col-sm-2 col-md-3"><?php echo $submit; ?></div>
            </div>
        </div>


        <?php
        echo form_close();
        ?>
    </div>