<style>
    .title {
        margin-bottom: 30px;
    }
    .title-inner {
        border: 2px solid #231f20;
        border-radius: 10px;
        padding: 15px 20px;
    }
    .title-inner > p {
        font-size: 20px;
        transform: translateY(21%);
    }
    .title-inner > img {
        background-color: gray;
        border-radius: 6px;
    }

    label {
        margin-top: 15px;
    }
    label::after {
        color: maroon;
        content: "*";
        padding-left: 2px;
    }
    .table > tbody > tr > td, .table > tbody > tr > th, .table > tfoot > tr > td, .table > tfoot > tr > th, .table > thead > tr > td, .table > thead > tr > th {
        border: 0;
    }
</style>
<div id="user-page" class="content-inner">

    <div class="row">
        <div class="title col-xs-8 col-sm-8 col-md-8">
            <div class="title-inner">
                <img width="40" src="<?php echo site_url('images/add_user_1.png'); ?>">

                <p><strong>Update payment information</strong></p>

                <div style="clear: both"></div>
            </div>
        </div>
        <div class="col-md-4">
            <img src="<?php echo site_url('images/master.png'); ?>" style="height: 24px; float: right;">
            <img src="<?php echo site_url('images/visa.png'); ?>" style="height: 24px; float: right;">
            <img src="<?php echo site_url('images/payment_express.gif'); ?>" style="height: 24px; float: right;">
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 col-md-offset-3">
            <div id="infoMessage">
                <?php if ($this->session->flashdata('warning-message')) { ?>

                    <div class="alert alert-warning" id="warning-alert">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>Error </strong>
                        <?php echo $this->session->flashdata('warning-message'); ?>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-3 col-md-offset-5">
            <div id="payment_box">
                <div class="box" style="clear: both">
                    <div class="box-body">
                        <form id="frmPayment" action="<?php echo site_url('user/payment_info_update'); ?>" method="post">
                            <label for="name_on_card">Name on Card</label><br>
                            <input class="form-control" type="text" name="CardName" value="" placeholder="" id="name_on_card">
                            <label for="card_no">Card Number</label><br>
                            <input id="card_no" class="form-control" type="text" maxlength="16" name="CardNum" value="" placeholder="">
                            <label for="expiration">Expiration Date</label><br>
                            <select name="ExMnth" size="1" class="form-control" id="expiration" style="float: left; width: 50%;">
                                <?php
                                for ($m = 1; $m < 13; $m++) {
                                    $mm = sprintf("%02d", $m);
                                    echo "<option value=\"$mm\"";
                                    echo ">$mm\n";
                                }
                                ?>
                            </select>
                            <input name="ExYear" maxlength="2" class="form-control" placeholder="year" style="float: left; width: 50%;"/>

                            <label for="cvc">Card Verification Number</label><br>
                            <input id="cvc" class="form-control" type="text" maxlength="16" name="cvc" value="" placeholder="">
                            <button class="btn btn-default" style="margin-top: 20px">Confirm</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
