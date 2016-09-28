<?php if(isset($massage)) echo $message;  ?>

<?php 
$user_id = $user->uid;
$role_id = $user->rid;
if($role_id==2){
    $manager_id = $user_id;
    $developer_id =0;
}
else if($role_id==3){
    $manager_id =0;
    $developer_id = $user_id;
}
else{
    $manager_id =0;
    $developer_id = 0;
}

$ci = &get_instance();

?>

<div id="all-title" xmlns="http://www.w3.org/1999/html">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
		</div>
	</div>
</div>

<div class="content-inner">

            <div class="row">
	            <div class="col-md-12 margin-bottom">
	                <div class="total-task-count">							
	                    <h2 style="float: left">You have a total of <strong><?php echo count($todays_task_list)+count($new_requests_list)+count($overview_overdue_requests_list)+count($open_requests); ?></strong> tasks that require your attention</h2>
						<!--task 4102-->
						<?php if($user_app_role == 'manager'): ?>
							<form style="float:right;padding-top: 12px;padding-bottom: 12px;" action="<?php echo site_url('overview'); ?>" method="post">
								<label style="float:left;padding-top: 8px;color: #000;">View as:</label>
								<select name="view" class="form-control" style="float: right; width: 70%;"  onchange="this.form.submit()">
									<option value="0">View All</option>
									<option value="1" <?php if($view == 1)echo "selected"; ?> >Manager Only</option>
									<option value="2" <?php if($view == 2)echo "selected"; ?> >Contractor Only</option>
								</select>
							</form>
						<?php endif; ?>
						<div style="clear: both"></div>
	                </div>
	            </div>
            </div>
   
		    <div class="row">

				<div class="col-md-3 margin-bottom">
		            <div class="overview-request-box tour tour_1">
		                <div class="todays-task-header clearfix">
		                <h2 align="center">Today's Tasks <span
		                        class="request-count-color" id="todays_task_count"><?php echo count($todays_task_list); ?></span></h2>
		                <a href="<?php echo site_url('overview/clear_todays_task_list'); ?>" class="btn btn-default">
		                    CLEAR ALL
		                </a>
		                </div>
		                <div id="overview-request-list" class="overview-request-list droppable" style="clear: both">
		                    <table>
		                        <?php
		                        if (isset($todays_task_list)) {
		                            foreach ($todays_task_list as $request) {
		                                echo '<tr>';
		                                echo '<td>';
		                                // echo $request->request_title.' - '. $request->request_description.'<br />';
		                                echo '<h4>' . $request->request_no . '. ' . $request->request_title . '</h4>';
		                                echo date("d-m-Y H:i:s", strtotime($request->created));
		                                echo '</td>';
		
		                                echo '<td style="position: relative">';

		                                echo '<a style="float:right;margin-right:20px" href="' . base_url() . 'request/request_detail/' . $request->request_no . '/' . $request->id . '?from=overview"><img src="' . base_url() . 'images/icon_search.png" /></a>';
		                                if($ci->overview_model->check_new_notes($request->id)){
											echo '<a style="float:right;" href="'.base_url().'notes/index/'.$request->request_no.'"><img src="'.base_url().'images/new_notes.png" /></a>'; 
										}
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

		      <div class="col-md-3 margin-bottom">
		          <div class="overview-request-box tour tour_2">
		            <h2 align="center">New Tasks <span class="request-count-color" id="new_task_count"><?php echo count($new_requests_list); ?></span></h2>
		            <div class="overview-request-list">
						<table>                
	                    <?php
	                    if(isset($new_requests_list)){
	                    foreach($new_requests_list as $request){
	                        echo '<tr class="draggable"  data-list="new_task" data-id="'.$request->id.'">';
	                        echo '<td style="width:65%">';
	                        // echo $request->request_title.' - '. $request->request_description.'<br />';
	                        echo '<h4>'.$request->request_no.'. '.$request->request_title.'</h4>';
	                        echo date("d-m-Y H:i:s", strtotime($request->created));
	                        echo '</td>';

							echo '<td valign="top">';
							
							echo '<a style="float:right;margin-left:8px;" href="'.base_url().'request/request_detail/'.$request->request_no. '/' . $request->id . '?from=overview"><img src="'.base_url().'images/icon_search.png" /></a>';
							if($ci->overview_model->check_new_notes($request->id)){
								echo '<a style="float:right;" href="'.base_url().'notes/index/'.$request->request_no.'"><img src="'.base_url().'images/new_notes.png" /></a>'; 
							}
							echo '</td>';

	                       echo '</tr>';
	                     }
	                    }
	                    ?>
	                    </table>
		            </div>
		        </div>
		      </div>

			  <div class="col-md-3 margin-bottom">
		         <div class="overview-request-box tour tour_3">
		             <h2 align="center">Open Tasks <span class="request-count-color" id="open_task_count"><?php echo count($open_requests); ?></span></h2> 
		             <div class="overview-request-list">
						<table>                
				          <?php
				          if(isset($open_requests)){
				          foreach($open_requests as $request){
				              echo '<tr class="draggable"  data-list="open_task" data-id="'.$request->id.'">';



				              echo '<td style="width:65%">';
				             
				              // echo $request->request_title.' - '. $request->request_description.'<br />';
				              echo '<h4>'.$request->request_no.'. '.$request->request_title.'</h4>';
				              echo date("d-m-Y H:i:s", strtotime($request->created));
				              
				              echo '</td>';

								echo '<td>';
								echo '<a style="float:right;margin-left:8px;" href="'.base_url().'request/request_detail/'.$request->request_no. '/' . $request->id .'?from=overview"><img src="'.base_url().'images/icon_search.png" /></a>';
								if($ci->overview_model->check_new_notes($request->id)){
									echo '<a style="float:right;" href="'.base_url().'notes/index/'.$request->request_no.'"><img src="'.base_url().'images/new_notes.png" /></a>'; 
								}
								echo '</td>';

				             echo '</tr>';
				           }
				          }
				          ?>
				         </table>
					 </div>
		         </div>
		      </div>
		      
		      <div class="col-md-3 margin-bottom">
		          <div class="overview-request-box tour tour_4">
		              <h2 align="center">Overdue Tasks <span class="request-count-color" id="overdue_task_count"><?php echo count($overview_overdue_requests_list); ?></span></h2>
		              <div class="overview-request-list">
						<table>                
				          <?php
				          if(isset($overview_overdue_requests_list)){
				          foreach($overview_overdue_requests_list as $request){
				              echo '<tr class="draggable"  data-list="overdue_task" data-id="'.$request->id.'">';
				              echo '<td style="width:60%">';
				              // echo $request->request_title.' - '. $request->request_description.'<br />';
				              echo '<h4>'.$request->request_no.'. '.$request->request_title.'</h4>';
				              echo date("d-m-Y H:i:s", strtotime($request->created));
				              echo '</td>';
				             
								echo '<td>';
								echo '<a style="float:right;margin-left:8px;" href="'.base_url().'request/request_detail/'.$request->request_no. '/' . $request->id .'?from=overview"><img src="'.base_url().'images/icon_search.png" /></a>';
								if($ci->overview_model->check_new_notes($request->id)){
									echo '<a style="float:right;" href="'.base_url().'notes/index/'.$request->request_no.'"><img src="'.base_url().'images/new_notes.png" /></a>'; 
								}
								echo '</td>';

				             echo '</tr>';
				           }
				          }
				          ?>
				         </table>
					  </div>
		         </div>
		      </div>
		
				
        
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
				var id = el.draggable.attr('data-id');
                var new_row =  el.draggable.clone();
                new_row.find("td:last-child").append('<a href="'+base_url+'overview/remove_from_todays_task/'+id+'"><img class="close_image" src="' + base_url + 'images/close-icon.png" /></a>');
                $($(this).find("tbody")[0]).append(new_row);
                
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
        });

    });

	/*tour. task #4421*/
	var config = [
			{
				"name" 		: "tour_1",
				"bgcolor"	: "black",
				"color"		: "white",
				"position"	: "LT",
				"text"		: "Drag and drop all tasks from New Tasks, Open Tasks, and Overdue Tasks. Those tasks will stay there for 24 hours and will be back to its place after that.",
				"time" 		: 5000,
				"buttons"	: ["<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>"]
			},
			{
				"name" 		: "tour_2",
				"bgcolor"	: "black",
				"color"		: "white",
				"text"		: "When you created a new task or a new task assigned to you, it will appear in 'New Tasks'. See the description of the task to move it from 'New Tasks'.",
				"position"	: "LT",
				"time" 		: 5000,
				"buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>", "<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
			},
			{
				"name" 		: "tour_3",
				"bgcolor"	: "black",
				"color"		: "white",
				"text"		: "This is to show all ongoing tasks that needs to be completed.",
				"position"	: "LT",
				"time" 		: 5000,
				"buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>", "<span class='btn btn-xs btn-default nextstep'>next</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
			},
			{
				"name" 		: "tour_4",
				"bgcolor"	: "black",
				"color"		: "white",
				"text"		: "This to show all overdue tasks  that needs to be completed.",
				"position"	: "RT",
				"time" 		: 5000,
				"buttons"	: ["<span class='btn btn-xs btn-default prevstep'>prev</span>", "<span class='btn btn-xs btn-default endtour'>end tour</span>", "<span class='btn btn-xs btn-default restarttour'>restart tour</span>"]
			}

		],
	//define if steps should change automatically
		autoplay	= false,
	//timeout for the step
		showtime,
	//current step of the tour
		step		= 0,
	//total number of steps
		total_steps	= config.length;
</script>
