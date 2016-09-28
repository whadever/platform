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
	<div class="row">
		<div class="col-xs-12-col-sm-12 col-md-9 col-lg-9 col-xl-9">
	    	<img width="50" src="<?php echo base_url()?>images/title-icon.png"/>
	    	<span style="font-size:16px;"><?php echo $title;  ?></span>
		</div>
		<div class="col-xs-12-col-sm-12 col-md-3 col-lg-3 col-xl-3">
			<a href="javascript:window.print()" class="btn btn-lg">
          		<span class="glyphicon glyphicon-print"></span> Print 
        	</a>
		</div>
	</div>	
</div>
<?php
$ci = & get_instance();
$ci->load->model('report_model');
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
 for($k=7;$k>=-1; $k--)
 {


	$week_first_day = date("Y-m-d", strtotime("Monday $k week ago"));
	$week_last_day	= date("Y-m-d", strtotime("Sunday $k week ago"));

	$year  = date("Y",strtotime($week_first_day));
	$Month = date("m",strtotime($week_first_day));
	$Day   = date("d",strtotime($week_first_day));

	$tasks = $ci->report_model->get_tasks($week_first_day,$week_last_day,"open");
	$task_number = $tasks->task_quantity;
	$str = $str.'[ new Date('.$year.','.($Month-1).','.$Day.'),'.$task_number.'],';
 }
  echo $str;  
?>
  ]); 


      var options = {
		title:'Opened Tasks -Weekly',
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

      var chart = new google.visualization.LineChart(document.getElementById('open_task_report'));

      chart.draw(data, options);
    }


	//close request
      
      google.setOnLoadCallback(drawChartClose);
      
      function drawChartClose() {

      var data = new google.visualization.DataTable();
      data.addColumn('datetime', 'Year');
      <?php 
        //foreach ($user_name as $uname) { ?>
           data.addColumn('number', 'Task'); 
       <?php //}
      ?>
      
     

      data.addRows([
      
    

<?php 
$str = '';
 for($k=7;$k>=-1; $k--)
 {


	$week_first_day = date("Y-m-d", strtotime("Monday $k week ago"));
	$week_last_day	= date("Y-m-d", strtotime("Sunday $k week ago"));

	$year  = date("Y",strtotime($week_first_day));
	$Month = date("m",strtotime($week_first_day));
	$Day   = date("d",strtotime($week_first_day));

	$tasks = $ci->report_model->get_tasks($week_first_day,$week_last_day,"closed");
	$task_number = $tasks->task_quantity;
	$str = $str.'[ new Date('.$year.','.($Month-1).','.$Day.'),'.$task_number.'],';
 }
  echo $str;  
?>
  ]);
  var options = {
      title:'Closed Tasks -Weekly',
        width: 700,
        height: 350,
        pointSize: 5,
        pointShape: 'circle',
        legend:'bottom',
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
				min:0.00
		 	}
        },
        series: {
          1: {curveType: 'function'}
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('close_task_report'));

      chart.draw(data, options);
    }
    </script>   
    
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6"> 
            <div id="open_task_report"></div>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6"> 
            <div id="close_task_report"></div>
        </div>
    </div>

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
 for($k=7;$k>=-1; $k--)
 {


	$week_first_day = date("Y-m-d", strtotime("Monday $k week ago"));
	$week_last_day	= date("Y-m-d", strtotime("Sunday $k week ago"));

	$year  = date("Y",strtotime($week_first_day));
	$Month = date("m",strtotime($week_first_day));
	$Day   = date("d",strtotime($week_first_day));

	$tasks = $ci->report_model->get_tasks($week_first_day,$week_last_day,"new");
	$task_number = $tasks->task_quantity;
	$str = $str.'[ new Date('.$year.','.($Month-1).','.$Day.'),'.$task_number.'],';
 }
  echo $str;  
?>
  ]); 


      var options = {
		title:'New Tasks -Weekly',
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


	//close request
      
      google.setOnLoadCallback(drawChartClose);
      
      function drawChartClose() {

      var data = new google.visualization.DataTable();
      data.addColumn('datetime', 'Year');
      <?php 
        //foreach ($user_name as $uname) { ?>
           data.addColumn('number', 'Task'); 
       <?php //}
      ?>
      
     

      data.addRows([
      
    

<?php 
$str = '';
 for($k=7;$k>=-1; $k--)
 {


	$week_first_day = date("Y-m-d", strtotime("Monday $k week ago"));
	$week_last_day	= date("Y-m-d", strtotime("Sunday $k week ago"));

	$year  = date("Y",strtotime($week_first_day));
	$Month = date("m",strtotime($week_first_day));
	$Day   = date("d",strtotime($week_first_day));

	$tasks = $ci->report_model->get_tasks($week_first_day,$week_last_day,"overdue");
	$task_number = $tasks->task_quantity;
	$str = $str.'[ new Date('.$year.','.($Month-1).','.$Day.'),'.$task_number.'],';
 }
  echo $str;  
?>
  ]);
  var options = {
      title:'Overdue Tasks -Weekly',
        width: 700,
        height: 350,
        pointSize: 5,
        pointShape: 'circle',
        legend:'bottom',
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
				min:0.00
		 	}
        },
        series: {
          1: {curveType: 'function'}
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('overdue_task_report'));

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



                  