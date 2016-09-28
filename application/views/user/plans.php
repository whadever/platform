<style>
    input[type="file"]:focus, input[type="checkbox"]:focus, input[type="radio"]:focus {
        outline: medium none;
        outline-offset: -2px;
    }
    .box {
        border: 1px solid #fbb93e;
        border-radius: 5px;
    }
    .box-title {
        background-color: #fbb93e;
        color: white;
        font-size: 1.5em;
        font-weight: bold;
        padding: 10px;
    }
    .box-body {
        color: black;
        padding: 10px;
    }
    table {
        width: 100%;
    }
    th,td {
        border: 1px solid white;
        padding: 10px;
        text-align: center;
    }
    th{

        font-size: 1.2em;
        position: relative; z-index: -1
    }
    tr:nth-child(2) th{
        position: static;
        padding: 0;
    }

    #most_popular {
        background-color: #fbbf16;
        bottom: 66px;
        display: block;
        font-size: 0.8em;
        left: 0;
        position: absolute;
        width: 100%;
    }
    input[type='checkbox']{
        margin-left: 5px;
    }
    #total {
        display: table-cell;
        font-size: 2.6em;
        font-weight: bold;
        padding-left: 13px;
    }
    /*overlay*/
    #overlay {
        position: fixed;
        left: 0;
        top: 0;
        bottom: 0;
        right: 0;
        background: #000;
        opacity: 0.8;
        filter: alpha(opacity=80);
    }
    #loading {
        border-radius: 8px;
        left: 50%;
        position: absolute;
        top: 50%;
        width: 45px;
    }
</style>
<div id="user-page" class="content-inner">

    <div class="row">
        <div class="col-md-12">
            <div id="infoMessage">

                <?php if($this->session->flashdata('success-message')){ ?>

                    <div class="alert alert-success" id="success-alert">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>Success! </strong>
                        <?php echo $this->session->flashdata('success-message');?>
                    </div>
                <?php } ?>

                <?php if($this->session->flashdata('warning-message')){ ?>

                    <div class="alert alert-warning" id="warning-alert">
                        <button type="button" class="close" data-dismiss="alert">x</button>
                        <strong>Success! </strong>
                        <?php echo $this->session->flashdata('warning-message');?>
                    </div>
                <?php } ?>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="box">
                <div class="box-title">
                    Billing &amp; Ad-Ons
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form action="<?php echo site_url('user/get_total_amount'); ?>" id="frmPlan">
                            Current Package: <?php echo $current_plan->name; ?> <br><br>
                            <table>
                                <thead>
                                <tr>
                                    <th style="background-color: #FDE399">WILLIAMS PLATFORM PACKAGES</th>
                                    <th style="background-color: #FDE399">BASIC</th>
                                    <th style="background-color: #FBDC82">PLUS</th>
                                    <th style="background-color: #FBCA3F"><span id="most_popular">Most Popular <br></span>PRO</th>
                                    <th style="background-color: #FCD057">PREMIUM</th>
                                    <th style="background-color: #FCD057">BUSINESS CLASS</th>
                                    <th style="background-color: #FCD057">CORPORATE</th>
                                </tr>
                                <tr>
                                    <th style="background-color: #FDE399"></th>
                                    <th style="background-color: #FDE399"><input type="radio" name="package" value="1"></th>
                                    <th style="background-color: #FBDC82"><input type="radio" name="package" value="2"></th>
                                    <th style="background-color: #FBCA3F"><input type="radio" name="package" value="3"></th>
                                    <th style="background-color: #FCD057"><input type="radio" name="package" value="4"></th>
                                    <th style="background-color: #FCD057"><input type="radio" name="package" value="5"></th>
                                    <th style="background-color: #FCD057"><input type="radio" name="package" value="6"></th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr style="background-color: #EDEDED">
                                        <td>Number of Users</td>
                                        <td><?php echo $plans[1]['no_of_users']; ?></td>
                                        <td><?php echo $plans[2]['no_of_users']; ?></td>
                                        <td><?php echo $plans[3]['no_of_users']; ?></td>
                                        <td><?php echo $plans[4]['no_of_users']; ?></td>
                                        <td><?php echo $plans[5]['no_of_users']; ?></td>
                                        <td><?php echo $plans[6]['no_of_users']; ?></td>
                                    </tr>
                                    <tr style="background-color: #E1E1E3">
                                        <td>Platform Access (Based on Number of Users)</td>
                                        <td class="plan_1">$<?php echo number_format($plans[1][0],2); $total_1 = $plans[1][0]; ?><input data-price="<?php echo $plans[1][0]; ?>" type="checkbox" name="plan_1[]" value="0"></td>
                                        <td class="plan_2">$<?php echo number_format($plans[2][0],2); $total_2 = $plans[2][0]; ?><input data-price="<?php echo $plans[2][0]; ?>" type="checkbox" name="plan_2[]" value="0"></td>
                                        <td class="plan_3">$<?php echo number_format($plans[3][0],2); $total_3 = $plans[3][0]; ?><input data-price="<?php echo $plans[3][0]; ?>" type="checkbox" name="plan_3[]" value="0"></td>
                                        <td class="plan_4">$<?php echo number_format($plans[4][0],2); $total_4 = $plans[4][0]; ?><input data-price="<?php echo $plans[4][0]; ?>" type="checkbox" name="plan_4[]" value="0"></td>
                                        <td class="plan_5">$<?php echo number_format($plans[5][0],2); $total_5 = $plans[5][0]; ?><input data-price="<?php echo $plans[5][0]; ?>" type="checkbox" name="plan_5[]" value="0"></td>
                                        <td class="plan_6">$<?php echo number_format($plans[6][0],2); $total_6 = $plans[6][0]; ?><input data-price="<?php echo $plans[6][0]; ?>" type="checkbox" name="plan_6[]" value="0"></td>
                                    </tr>
                                    <tr style="background-color: #EDEDED">
                                        <td>Task Management</td>
                                        <td class="plan_1">$<?php echo number_format($plans[1][3],2);  $total_1 += $plans[1][3];   ?><input data-price="<?php echo $plans[1][3]; ?>" type="checkbox" name="plan_1[]" value="3"></td>
                                        <td class="plan_2">$<?php echo number_format($plans[2][3],2);  $total_2 += $plans[2][3];   ?><input data-price="<?php echo $plans[2][3]; ?>" type="checkbox" name="plan_2[]" value="3"></td>
                                        <td class="plan_3">$<?php echo number_format($plans[3][3],2);  $total_3 += $plans[3][3];   ?><input data-price="<?php echo $plans[3][3]; ?>" type="checkbox" name="plan_3[]" value="3"></td>
                                        <td class="plan_4">$<?php echo number_format($plans[4][3],2);  $total_4 += $plans[4][3];   ?><input data-price="<?php echo $plans[4][3]; ?>" type="checkbox" name="plan_4[]" value="3"></td>
                                        <td class="plan_5">$<?php echo number_format($plans[5][3],2);  $total_5 += $plans[5][3];   ?><input data-price="<?php echo $plans[5][3]; ?>" type="checkbox" name="plan_5[]" value="3"></td>
                                        <td class="plan_6">$<?php echo number_format($plans[6][3],2);  $total_6 += $plans[6][3];   ?><input data-price="<?php echo $plans[6][3]; ?>" type="checkbox" name="plan_6[]" value="3"></td>
                                    </tr>
                                    <tr style="background-color: #E1E1E3">
                                        <td>Contact Management</td>
                                        <td class="plan_1">FREE <input data-price="<?php echo $plans[1][4]; ?>" type="checkbox" name="plan_1[]" value="4"></td>
                                        <td class="plan_2">FREE <input data-price="<?php echo $plans[2][4]; ?>" type="checkbox" name="plan_2[]" value="4"></td>
                                        <td class="plan_3">FREE <input data-price="<?php echo $plans[3][4]; ?>" type="checkbox" name="plan_3[]" value="4"></td>
                                        <td class="plan_4">FREE <input data-price="<?php echo $plans[4][4]; ?>" type="checkbox" name="plan_4[]" value="4"></td>
                                        <td class="plan_5">FREE <input data-price="<?php echo $plans[5][4]; ?>" type="checkbox" name="plan_5[]" value="4"></td>
                                        <td class="plan_6">FREE <input data-price="<?php echo $plans[6][4]; ?>" type="checkbox" name="plan_6[]" value="4"></td>
                                    </tr>
                                    <tr style="background-color: #EDEDED">
                                        <td>Time Sheet System</td>
                                        <td class="plan_1">$<?php echo number_format($plans[1][7],2); $total_1 += $plans[1][7]; ?><input data-price="<?php echo $plans[1][7]; ?>" type="checkbox" name="plan_1[]" value="7"></td>
                                        <td class="plan_2">$<?php echo number_format($plans[2][7],2); $total_2 += $plans[2][7]; ?><input data-price="<?php echo $plans[2][7]; ?>" type="checkbox" name="plan_2[]" value="7"></td>
                                        <td class="plan_3">$<?php echo number_format($plans[3][7],2); $total_3 += $plans[3][7]; ?><input data-price="<?php echo $plans[3][7]; ?>" type="checkbox" name="plan_3[]" value="7"></td>
                                        <td class="plan_4">$<?php echo number_format($plans[4][7],2); $total_4 += $plans[4][7]; ?><input data-price="<?php echo $plans[4][7]; ?>" type="checkbox" name="plan_4[]" value="7"></td>
                                        <td class="plan_5">$<?php echo number_format($plans[5][7],2); $total_5 += $plans[5][7]; ?><input data-price="<?php echo $plans[5][7]; ?>" type="checkbox" name="plan_5[]" value="7"></td>
                                        <td class="plan_6">$<?php echo number_format($plans[6][7],2); $total_6 += $plans[6][7]; ?><input data-price="<?php echo $plans[6][7]; ?>" type="checkbox" name="plan_6[]" value="7"></td>
                                    </tr>

                                    <tr style="background-color: #E1E1E3">
                                        <td>Reporting System</td>
                                        <td class="plan_1">$<?php echo number_format($plans[1][8],2); $total_1 += $plans[1][8]; ?><input data-price="<?php echo $plans[1][8]; ?>" type="checkbox" name="plan_1[]" value="8"></td>
                                        <td class="plan_2">$<?php echo number_format($plans[2][8],2); $total_2 += $plans[2][8]; ?><input data-price="<?php echo $plans[2][8]; ?>" type="checkbox" name="plan_2[]" value="8"></td>
                                        <td class="plan_3">$<?php echo number_format($plans[3][8],2); $total_3 += $plans[3][8]; ?><input data-price="<?php echo $plans[3][8]; ?>" type="checkbox" name="plan_3[]" value="8"></td>
                                        <td class="plan_4">$<?php echo number_format($plans[4][8],2); $total_4 += $plans[4][8]; ?><input data-price="<?php echo $plans[4][8]; ?>" type="checkbox" name="plan_4[]" value="8"></td>
                                        <td class="plan_5">$<?php echo number_format($plans[5][8],2); $total_5 += $plans[5][8]; ?><input data-price="<?php echo $plans[5][8]; ?>" type="checkbox" name="plan_5[]" value="8"></td>
                                        <td class="plan_6">$<?php echo number_format($plans[6][8],2); $total_6 += $plans[6][8]; ?><input data-price="<?php echo $plans[6][8]; ?>" type="checkbox" name="plan_6[]" value="8"></td>
                                    </tr>
                                    <tr style="background-color: white">
                                        <td rowspan="2">Construction System</td>
                                        <td class="plan_1" rowspan="2">$50.00/Job<br/>per Month<?php $total_2 += 50; ?><br/><input data-price=50 type="checkbox" name="plan_1[]" value="5"></td>
                                        <td class="plan_2" rowspan="2">$50.00/Job<br/>per Month<?php $total_2 += 50; ?><br/><input data-price=50 type="checkbox" name="plan_2[]" value="5"></td>
                                        <td class="plan_3" rowspan="2">$50.00/Job<br/>per Month<?php $total_2 += 50; ?><br/><input data-price=50 type="checkbox" name="plan_3[]" value="5"></td>
                                        <td class="plan_4" rowspan="2">$50.00/Job<br/>per Month<?php $total_2 += 50; ?><br/><input data-price=50 type="checkbox" name="plan_4[]" value="5"></td>
                                        <td class="plan_5" rowspan="2">$50.00/Job<br/>per Month<?php $total_2 += 50; ?><br/><input data-price=50 type="checkbox" name="plan_5[]" value="5"></td>
                                        <td class="plan_6" rowspan="2">$50.00/Job<br/>per Month<?php $total_2 += 50; ?><br/><input data-price=50 type="checkbox" name="plan_6[]" value="5"></td>

                                    </tr>
                                    <tr>

                                    </tr>
                                    <tr id="estimated_total" style="background-color: #EDEDED">
                                        <td>Estimated Total</td>
                                        <td class="plan_1"></td>
                                        <td class="plan_2"></td>
                                        <td class="plan_3"></td>
                                        <td class="plan_4"></td>
                                        <td class="plan_5"></td>
                                        <td class="plan_6"></td>
                                    </tr>
                                </tbody>
                            </table>
                            <button style="float: right; margin-top: 5px">Click Here to Confirm</button>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div id="payment_box" style="display: none">
                                <img src="<?php echo site_url('images/master.png'); ?>" style="height: 24px; float: right;">
                                <img src="<?php echo site_url('images/visa.png'); ?>" style="height: 24px; float: right;">
                                <img src="<?php echo site_url('images/payment_express.gif'); ?>" style="height: 24px; float: right;">
                                <div class="" style="clear:both">
                                <span style="font-size: 1.2em; display: table-cell; vertical-align: middle; float: left; padding-top: 15px;">New Total:</span> <span id="total"></span>
                                <div class="box" style="clear: both">
                                    <div class="box-title">Billing Information</div>
                                    <div class="box-body">
                                        <form id="frmPayment">
                                            <?php $hide = ""; ?>
                                            <?php if(!empty($company->payment_token)): ?>
                                                <input type="radio" checked name="new_payment_info" value="no"> Use existing payment information
                                                <br>
                                                <input type="radio" name="new_payment_info" value="yes"> New payment information
                                                <?php $hide = "style='display:none'"; ?>
                                            <?php endif; ?>
                                            <table <?php echo $hide; ?>>
                                                <tr>
                                                    <td colspan="2">
                                                        <input class="form-control" type="text" name="CardName" value="" placeholder="Name on Card">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input class="form-control" type="text" maxlength="16" name="CardNum" value="" placeholder="Card Number">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2" style="text-align: left; padding-bottom: 0px; color: gray;">Expiry</td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <select name="ExMnth" size="1" class="form-control">
                                                            <?php
                                                            for ($m = 1; $m < 13; $m++) {
                                                                $mm = sprintf("%02d", $m);
                                                                echo "<option value=\"$mm\"";
                                                                if ($mm == $ccmm) {
                                                                    echo " selected";
                                                                }
                                                                echo ">$mm\n";
                                                            }
                                                            ?>
                                                        </select>
                                                    </td>
                                                    <td>
                                                        <input name="ExYear" maxlength="2" class="form-control" placeholder="year"/>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="2">
                                                        <input class="form-control" type="text" maxlength="16" name="cvc" value="" placeholder="CVC">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td></td>
                                                    <td style="text-align: right"></td>
                                                </tr>
                                            </table>
                                            <button style="margin-left: 80%;">Confirm</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    var get_total_url = "<?php echo site_url('user/get_total_amount'); ?>";
    var payment_url = "<?php echo site_url('user/payment'); ?>";
    var loader_url = "<?php echo site_url('images/loader.gif'); ?>";
    var current_plan = <?php echo $current_plan->id; ?>;
    var current_applications = <?php echo json_encode($current_applications); ?>;
    var application_prices = <?php echo json_encode($plans); ?>;
    var selected_plan = '';
        $(document).ready(function(){

            $("table input[type='checkbox']").prop('disabled',true);

            /*selecting a new plan*/
            $("input[name='package']").change(function(){

                /*hiding the payment box*/
                $("#payment_box").hide();

                var package = $(this).val();
                selected_plan = package;
                $("table input[type='checkbox']").prop('checked',false).prop('disabled',true);
                $(".plan_"+package).find("input[type='checkbox']").prop('disabled',false);
                $(".plan_"+package).eq(0).find("input[type='checkbox']").prop("checked",true).prop('disabled',true);
                $(".plan_"+package).eq(2).find("input[type='checkbox']").prop("checked",true).prop('disabled',true);

                /*removing all the estimated cost value*/
                $("#estimated_total td:gt(0)").empty();
                estimated_cost = 0;

                /*calculating and showing the total cost */
                estimated_cost = parseFloat($(".plan_"+package).eq(0).find("input[type='checkbox']").attr('data-price'));
                $("#estimated_total .plan_"+selected_plan).text('$'+estimated_cost.toFixed(2));



            });

            /*selecting the current plan*/
            $("input[name='package'][value="+current_plan+"]").click();

            /*selecting the current applications*/
            for(a in current_applications){
                var appid = current_applications[a].application_id;
                $(".plan_"+current_plan+" "+"input[type='checkbox'][value='"+appid+"']").prop('checked',true)

            }

            /*calculating the current total price*/
            var total_price = 0;
            for(i in current_applications){
                total_price += parseFloat(application_prices[current_plan][current_applications[i].application_id]);
            }
            estimated_cost = total_price;

            $("#estimated_total .plan_"+current_plan).text('$'+total_price.toFixed(2));

            $("input[data-price]").click(function(){
                /*updating the total estimated cost*/
                var price = $(this).attr('data-price');
                if($(this).is(':checked')){
                    estimated_cost += parseFloat(price);
                }else{
                    estimated_cost -= parseFloat(price);
                }
                $("#estimated_total .plan_"+selected_plan).text('$'+estimated_cost.toFixed(2));

            });

            $("#frmPlan").submit(function(e){
                e.preventDefault();
                var over = $('<div id="overlay">' +
                    '<img id="loading" src="' + loader_url +'">' +
                    '</div>');
                over.appendTo('body');
                var params = $(this).serialize();
                $.ajax(get_total_url,{
                    type: 'post',
                    data: params,
                    success: function(data){
                        $("#total").text("NZD "+parseFloat(data).toFixed(2));
                        over.remove();
                        /*showing the payment box*/
                        $("#payment_box").show();
                        document.body.scrollTop = document.documentElement.scrollTop = 0;
                    }
                })
            });

            /*payment*/
            $("#frmPayment").submit(function(e){
                e.preventDefault();
                var over = $('<div id="overlay">' +
                    '<img id="loading" src="' + loader_url +'">' +
                    '</div>');
                over.appendTo('body');
                var params = $("#frmPlan").serialize()+"&"+$(this).serialize();
                $.ajax(payment_url,{
                    type: 'post',
                    data: params,
                    dataType: 'json',
                    success: function(data){
                        over.remove();
                        if(data.status == 'success'){
                            $("<div title='success!'>Payment Successful.</div>").dialog({
                                modal: true,
                                buttons: {
                                    Ok: function() {
                                        $( this ).dialog( "close" );
                                    }
                                },
                                close: function( event, ui ) {
                                    window.location = "<?php echo site_url('user'); ?>";
                                }
                            });
                        }else{
                            alert('Error processing payment');
                        }
                    }
                })
            });

            $("input[name='new_payment_info']").change(function(){
                if($(this).val() == 'yes'){
                    $("#frmPayment table").show();

                }else{
                    $("#frmPayment table").hide();
                }
            });
        })
    var estimated_cost = 0;
</script>