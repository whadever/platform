<?php if(isset($massage)) echo $message;  ?>
<div id="all-title">
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
		</div>
	</div>
</div>

<div class="content-inner">

            <div class="row">
	            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12 margin-bottom">
	                <div class="total-task-count">							
	                    <h2 style="float: left">You have a total of <strong><?php echo count($todays_task_list)+count($new_requests_list)+count($overview_overdue_requests_list)+count($open_requests); ?></strong> tasks that require your attention</h2>
						<div style="clear:both"></div>
	                </div>
					
	            </div>
            </div>
   
		    <div class="row">

				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2"></div>

				<div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 margin-bottom">
		            <div class="overview-request-box">
		                <div class="todays-task-header clearfix">
		                	<h2 align="center">Pending Tasks <span class="request-count-color" id="todays_task_count"><?php echo count($todays_task_list); ?></span></h2>
		                </div>
		                <div id="overview-request-list" class="overview-request-list droppable" style="clear: both">
		                    <table>
		                        <?php
		                        if (isset($pending_tasks)) {
		                            foreach ($pending_tasks as $request) {
		                                echo '<tr>';
		                                echo '<td>';
		                                // echo $request->request_title.' - '. $request->request_description.'<br />';
		                                echo '<h4>' . $request->request_no . '. ' . $request->request_title . '</h4>';
		                                echo date("d-m-Y H:i:s", strtotime($request->created));
		                                echo '</td>';
		
		                                echo '<td style="position: relative">';
		                                echo '<a style="float:right;margin-right:20px" href="' . base_url() . 'task/task_update/' . $request->request_no . '/' . $request->id . '?from=overview"><img src="' . base_url() . 'images/icon_search.png" /></a>';
		                                echo '<a href="'.site_url('overview/remove_from_todays_task/'.$request->id).'"><img class="close_image" src="' . base_url() . 'images/close-icon.png" /></a>';
		                                echo '</td>';
		
		                                echo '</tr>';
		                            }
		                        }
		                        if(empty($todays_task_list)){
		                            echo "<tbody></tbody>";
		                        }
		                        ?>
		                    </table>
		                </div>
		            </div>
		        </div>

		      <div class="col-xs-12 col-sm-12 col-md-4 col-lg-4 col-xl-4 margin-bottom">
		          <div class="overview-request-box">
		            <h2 align="center">Closed Tasks<span class="request-count-color" id="new_task_count"><?php echo count($new_requests_list); ?></span></h2>
		            <div class="overview-request-list">
						<table>                
	                    <?php
	                    if(isset($closed_tasks)){
	                    foreach($closed_tasks as $request){
	                        echo '<tr class="draggable"  data-list="new_task" data-id="'.$request->id.'">';
	                        echo '<td>';
	                        // echo $request->request_title.' - '. $request->request_description.'<br />';
	                        echo '<h4>'.$request->request_no.'. '.$request->request_title.'</h4>';
	                        echo date("d-m-Y H:i:s", strtotime($request->created));
	                        echo '</td>';

							echo '<td>';
							echo '<a style="float:right;margin-left:10px;" href="'.base_url().'request/request_detail/'.$request->request_no. '/' . $request->id . '?from=overview"><img src="'.base_url().'images/icon_search.png" /></a>';
							echo '</td>';

	                       echo '</tr>';
	                     }
	                    }
	                    ?>
	                    </table>
		            </div>
		        </div>
		      </div>
				<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 col-xl-2"></div>
   
   		 </div>
</div>



<style>
	 .margin-bottom {
        margin-bottom: 20px;
    }

    .total-task-count {
        background: #ebebeb;
        border: 3px solid #d7d7d7;
        border-radius: 5px;
        padding: 0 10px;
    }

    .overview-request-box {
        background: #fff;
        border: 2px solid #ebebeb;
    }

    .overview-request-list {
        height: 370px;
        overflow: auto;
    }

    .overview-request-box > h2, .total-task-count > h2 {
        background: #ebebeb;
        margin: 0;
        padding: 12px 5px;
        color: #000;
    }

    .request-count-color {
        color: #cc1618;
    }

    .overview-request-list h4 {
        margin: 0 0 5px;
    }

    .overview-request-list table tbody td:last-child {
        border-right: 0px solid #ebebeb;
    }

    .overview-request-list table tbody td {
        border-bottom: 1px solid #ebebeb;
        padding: 5px 10px;
    }

    .overview-request-list table {
        border: 0px solid #ebebeb;
    }

    .close_image{
        position: absolute;
        right: 5px;
        top: 38%;
        cursor: pointer;
    }
    #overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: #000;
        filter:alpha(opacity=50);
        -moz-opacity:0.5;
        -khtml-opacity: 0.5;
        opacity: 0.5;
        z-index: 10000;
    }
    #overlay img {
        left: 42%;
        position: absolute;
        top: 50%;
    }
    #overview-request-list tr td:nth-child(2) {
        position: relative;
    }
    #overview-request-list tr td:nth-child(2) a {
        float: right;
        margin-right: 20px;
    }
    .todays-task-header h2 {
        color: #000;
        float: left;
        font-size: 24px;
        margin-bottom: 10px;
        margin-top: 13px;
        width: 70%;
    }
    .todays-task-header {
        background: #ebebeb none repeat scroll 0 0;
    }
    .todays-task-header .btn {
        font-size: 80%;
        margin: 10px 0;
        width: 30%;
    }
	.overview-request-box > h2 {
	    font-size: 24px;
	}
</style>

<script>
    var base_url = "<?php echo site_url(); ?>";
    $(document).ready(function(){
        $(".draggable").draggable({
            revert: "invalid",
            helper: "clone",
            stack: ".draggable",
            scroll: false
        });
        $(".droppable").droppable({
            drop: function (e, el) {
                el.helper.remove();
                var new_row =  el.draggable.clone();
                new_row.find("td:last-child").append('<img class="close_image" src="' + base_url + 'images/close-icon.png" />');
                $($(this).find("tbody")[0]).append(new_row);
                var id = el.draggable.attr('data-id');
                //var overlay = $("<div id='overlay'><img src='" + base_url + "images/loader.gif'"+" /></div>").appendTo($("body"));
                var added_el_list = el.draggable.attr('data-list');
                el.draggable.remove();
                $.ajax(base_url+"overview/add_to_todays_task/"+id,{
                    success:function(){
                        $("#"+added_el_list+"_count").text(parseInt($("#"+added_el_list+"_count").text()-1));
                        $("#todays_task_count").text(parseInt($("#todays_task_count").text())+1);
                        //overlay.remove();
                    }
                })

            }
        })
    });
</script>
