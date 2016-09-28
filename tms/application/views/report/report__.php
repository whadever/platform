<style>
    #report_button{
        float: right;        
    }
    #report_button ul li{
        display: inline;
        list-style-type: none;
    }
    #report-title{
         background:#ebebeb;          
        padding: 10px 20px;
    }
    
</style>

<div id="report-title">
    <img width="50" src="<?php echo base_url()?>images/title-icon.png"/>
    <span style="font-size:16px;"><?php echo $title;  ?></span>
    <!--<span style="float:right;"><a href="project_add" class="btn btn-default add-button">Add Project</a> </span>
    <li style="float:right;" class="dropdown <?php  if($this->uri->segment(1)=='report') echo 'active'; ?>">
		<?php echo anchor('report/report_list','Select Reports',array('class'=>'dropdown-toggle report_list', 'data-toggle'=>'dropdown')); ?>
		<ul class="dropdown-menu">
			<li class=<?php  if($this->uri->segment(1)=="report") echo "active" ?>><?php echo anchor('report/report_list','Task Overview',array('class'=>'report_list')); ?></li>
			<li class=<?php  if($this->uri->segment(1)=="notes") echo "active" ?>><?php echo anchor('notes/notes_list', 'Notes Report',array('class'=>'notes')); ?></li>
		</ul>
	</li>-->
</div>
<!--
<div id="report_button">
<ul>
    <li><a class="btn btn-default" href="<?php echo base_url();?>report/report_list">New Task Report</a></li>
    <li><a class="btn btn-default" href="<?php echo base_url();?>report/close_task_report">Close Task Report</a></li>
    <li><a class="btn btn-default" href="<?php echo base_url();?>report/overdue_task_report">Overdue Task Report</a></li>
</ul>
</div>
-->
<?php

//print_r($user_open_task);
//echo date("Y-m-d", strtotime("Monday 8 week ago"));
//print_r($user_name); 

//$predefinedYear = date("Y");


?>


<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script type="text/javascript">
    google.load('visualization', '1', {packages: ['corechart']});
    google.setOnLoadCallback(drawChartOpen);

    function drawChartOpen() {

      var data = new google.visualization.DataTable();
		data.addColumn('datetime', 'Year');
      
		data.addColumn('number', 'Task'); 
       
      	data.addRows([
          
<?php 
$str = '';
 for($k=7;$k>=0; $k--)
 {


	$week_first_day = date("Y-m-d", strtotime("Monday $k week ago"));

	$year  = date("Y",strtotime($week_first_day));
	$Month = date("m",strtotime($week_first_day));
	$Day   = date("d",strtotime($week_first_day));

     $str = $str.'[ new Date('.$year.','.($Month-1).','.$Day.'),1],';
     
 }
  echo $str;  
?>
  ]); 


      var options = {
		title:'Open Tasks -Weekly',
        width: 700,
        height: 350,
        pointSize: 5,
        legend:'bottom',
        pointShape: 'circle',
        backgroundColor: 'white',
        tooltip: { isHtml: true },
        hAxis: {
          title: 'WEEK'
        },
        vAxis: {
          	title: 'TASK',
			gridlines: { count: -1},
			viewWindow:
		 	{
				min:0
		 	}
        },
        series: {
          1: {curveType: 'function'}
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('new_task_report'));

      chart.draw(data, options);
    }
      
    </script>
    
    
    
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6"> 
           <div id="new_task_report"></div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6"> 
            <div id="overdue_task_report"></div>
        </div>
    </div>

                  