<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="icon" href="<?php echo base_url(); ?>images/favicon.ico" type="image/gif">
    <link rel="stylesheet" href="<?php echo base_url();?>css/newtms.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap-select.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap-modal.css" type="text/css" media="screen" />
    <!-- Add fancyBox -->
    <link rel="stylesheet" href="<?php echo base_url();?>fancybox/jquery.fancybox.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>css/fuelux.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>css/select2.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="<?php echo base_url();?>css/style.css" type="text/css" media="screen" />
    <!-- Responsive Style Sheets -->
    <link rel="stylesheet" href="<?php echo base_url();?>css/responsive.css" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery.datetimepicker.css"/>
    <link rel="stylesheet" href="<?php echo base_url();?>css/print.css" type="text/css" media="print"/>
    <link rel="stylesheet" href="<?php echo base_url();?>css/jquery.multiselect.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?php echo base_url();?>css/jquery.mCustomScrollbar.css" type="text/css" media="screen"/>
    <link rel="stylesheet" href="<?php echo base_url();?>css/jquery.timepicker.css" type="text/css" media="screen"/>


    <script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.form.js"></script>

    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.datetimepicker.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.mtz.monthpicker.js"></script>

    <script type="text/javascript" src="<?php echo base_url();?>js/custom.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/new.js"></script>

    <script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap-tooltip.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap-modal.js" ></script>
    <script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap-modalmanager.js" ></script>
    <script type="text/javascript" src="<?php echo base_url();?>bootstrap/js/bootstrap-select.js" ></script>
    <script type="text/javascript" src="<?php echo base_url();?>fancybox/jquery.fancybox.js"></script>

    <script type="text/javascript" src="<?php echo base_url();?>js/fuelux.js" ></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/select2.js" ></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.mCustomScrollbar.min.js" ></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.timepicker.min.js" ></script>

    <link rel="stylesheet" href="//code.jquery.com/ui/1.10.4/themes/smoothness/jquery-ui.css">
    <script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <script type="text/javascript" src="<?php echo base_url();?>js/jquery.multiselect.js"></script>

    <?php
    $user = $this->session->userdata('user');
    $wp_company_id = $user->company_id;

    $this->db->select("wp_company.*,wp_file.*");
    $this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left');
    $this->db->where('wp_company.id', $wp_company_id);
    $wpdata = $this->db->get('wp_company')->row();

    //print_r($wpdata);
    $main_url = 'http://'.$wpdata->url;
    $colour_one = $wpdata->colour_one;
    $colour_two = $wpdata->colour_two;
    $logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

    ?>

    <style>
        body {
            /*color: <?php echo $colour_one; ?> !important;*/
        }
        .header {
            border-bottom: 2px solid <?php echo $colour_one; ?> !important;
        }
        .header .nav > li.active > a {
            background-color: <?php echo $colour_two; ?> !important;
        }
        .header .nav > li > a {
            background-color: <?php echo $colour_one; ?> !important;
        }
        .footer {
            border-top: 2px solid <?php echo $colour_one; ?> !important;
        }
        #maincontent {
            border: 2px solid <?php echo $colour_one; ?> !important;
        }
        #maincontent hr {
            background-color: <?php echo $colour_one; ?> !important;
        }
        a {
            color: <?php echo $colour_one; ?> !important;
        }
        .header .nav > li > a {
            color: <?php echo $colour_two; ?> !important;
        }
        .header .nav > li.active > a {
            color: <?php echo $colour_one; ?> !important;
        }
        .footer {
            color: <?php echo $colour_one; ?> !important;
        }
        .request-count-color {
            color: <?php echo $colour_two; ?> !important;
        }
        .line {
            background: <?php echo $colour_two; ?> !important;
        }
        #all-title .title-inner {
            color: <?php echo $colour_one; ?> !important;
        }
        .header-top-right {
            color: <?php echo $colour_two; ?> !important;
        }
        .dropdown-menu > .active > a, .dropdown-menu > .active > a:hover, .dropdown-menu > .active > a:focus {
            background-color: <?php echo $colour_two; ?> !important;
        }
        .userme {
            background-color: <?php echo $colour_one; ?> !important;
        }
        .red {
            color: <?php echo $colour_one; ?> !important;
        }
        #project_list_view table tbody td{border-color: <?php echo $colour_two; ?> !important;}

        .modal-header {
            /*background: <?php echo $colour_two; ?> !important;*/
            background-color: #C12026;
        }
        div.modal-header{
            min-height: auto;
            padding: 3px;
        }
        .modal-header .jan{
            background: url("<?php echo base_url()?>images/cal/small/jan.png");
        }
        .modal-header .feb{
            background: url("<?php echo base_url()?>images/cal/small/feb.png");
        }
        .modal-header .mar{
            background: url("<?php echo base_url()?>images/cal/small/mar.png");
        }
        .modal-header .apr{
            background: url("<?php echo base_url()?>images/cal/small/apr.png");
        }
        .modal-header .may{
            background: url("<?php echo base_url()?>images/cal/small/may.png");
        }
        .modal-header .jun{
            background: url("<?php echo base_url()?>images/cal/small/jun.png");
        }
        .modal-header .jul{
            background: url("<?php echo base_url()?>images/cal/small/jul.png");
        }
        .modal-header .aug{
            background: url("<?php echo base_url()?>images/cal/small/aug.png");
        }
        .modal-header .sep{
            background: url("<?php echo base_url()?>images/cal/small/sep.png");
        }
        .modal-header .oct{
            background: url("<?php echo base_url()?>images/cal/small/oct.png");
        }
        .modal-header .nov{
            background: url("<?php echo base_url()?>images/cal/small/nov.png");
        }
        .modal-header .dec{
            background: url("<?php echo base_url()?>images/cal/small/dec.png");
        }
        .modal-header .cal {
            background-repeat: no-repeat;
            background-position: right bottom;
            float: right;
            height: 82px;
            width: 70px;
            text-align: center;
        }
        .modal-header .day{
            color: black;
            display: block;
            font-size: 40px;
            height: 100%;
            margin-top: 32px;
        }
        .row{
            margin: auto;
        }

    </style>

</head>