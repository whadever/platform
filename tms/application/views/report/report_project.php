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
			<a target="_blank" href="<?php echo base_url()?>report/print_report" class="btn btn-lg">
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
      
		data.addColumn('number', 'Project'); 
       
      	data.addRows([
          
<?php 
$str = '';
 for($k=5;$k>=-1; $k--)
 {


	$week_first_day = date("Y-m-d", strtotime("Monday $k week ago"));
	$week_last_day	= date("Y-m-d", strtotime("Sunday $k week ago"));

	$year  = date("Y",strtotime($week_first_day));
	$Month = date("m",strtotime($week_first_day));
	$Day   = date("d",strtotime($week_first_day));

	$tasks = $ci->report_model->get_projects($week_first_day,$week_last_day,"open");
	$task_number = $tasks->project_quantity;
	$str = $str.'[ new Date('.$year.','.($Month-1).','.$Day.'),'.$task_number.'],';
 }
  echo $str;  
?>
  ]); 


      var options = {
		title:'Opened Projects -Monthly',
        width: 700,
        height: 420,
        pointSize: 5,
        legend:'bottom',
        pointShape: 'circle',
        backgroundColor: 'white',
        tooltip: { isHtml: true },
        hAxis: {
          title: 'MONTH'
        },
        vAxis: {
          	title: 'PROJECT',
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
           data.addColumn('number', 'Project'); 
       <?php //}
      ?>
      
     

      data.addRows([
      
    

<?php 
$str = '';
 for($k=5;$k>=-1; $k--)
 {


	$week_first_day = date("Y-m-d", strtotime("Monday $k week ago"));
	$week_last_day	= date("Y-m-d", strtotime("Sunday $k week ago"));

	$year  = date("Y",strtotime($week_first_day));
	$Month = date("m",strtotime($week_first_day));
	$Day   = date("d",strtotime($week_first_day));

	$tasks = $ci->report_model->get_projects($week_first_day,$week_last_day,"closed");
	$task_number = $tasks->project_quantity;
	$str = $str.'[ new Date('.$year.','.($Month-1).','.$Day.'),'.$task_number.'],';
 }
  echo $str;  
?>
  ]);
  var options = {
      title:'Completed Projects -Monthly',
        width: 700,
        height: 420,
        pointSize: 5,
        pointShape: 'circle',
        legend:'bottom',
        backgroundColor: 'white',
        tooltip: { isHtml: true },
        hAxis: {
          title: 'MONTH'
        },
        vAxis: {
          	title: 'PROJECT',
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
      
		data.addColumn('number', 'Project'); 
       
      	data.addRows([
          
<?php 
$str = '';
 for($k=5;$k>=-1; $k--)
 {


	$week_first_day = date("Y-m-d", strtotime("Monday $k week ago"));
	$week_last_day	= date("Y-m-d", strtotime("Sunday $k week ago"));

	$year  = date("Y",strtotime($week_first_day));
	$Month = date("m",strtotime($week_first_day));
	$Day   = date("d",strtotime($week_first_day));

	$tasks = $ci->report_model->get_projects($week_first_day,$week_last_day,"new");
	$task_number = $tasks->project_quantity;
	$str = $str.'[ new Date('.$year.','.($Month-1).','.$Day.'),'.$task_number.'],';
 }
  echo $str;  
?>
  ]); 


      var options = {
		title:'New Projects -Monthly',
        width: 700,
        height: 420,
        pointSize: 5,
        legend:'bottom',
        pointShape: 'circle',
        backgroundColor: 'white',
        tooltip: { isHtml: true },
        hAxis: {
          title: 'MONTH'
        },
        vAxis: {
          	title: 'PROJECT',
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
           data.addColumn('number', 'Project'); 
       <?php //}
      ?>
      
     

      data.addRows([
      
    

<?php 
$str = '';
 for($k=5;$k>=-1; $k--)
 {


	$week_first_day = date("Y-m-d", strtotime("Monday $k week ago"));
	$week_last_day	= date("Y-m-d", strtotime("Sunday $k week ago"));

	$year  = date("Y",strtotime($week_first_day));
	$Month = date("m",strtotime($week_first_day));
	$Day   = date("d",strtotime($week_first_day));

	$tasks = $ci->report_model->get_projects($week_first_day,$week_last_day,"overdue");
	$task_number = $tasks->project_quantity;
	$str = $str.'[ new Date('.$year.','.($Month-1).','.$Day.'),'.$task_number.'],';
 }
  echo $str;  
?>
  ]);
  var options = {
      title:'Overdue Projects -Monthly',
        width: 700,
        height:420,
        pointSize: 5,
        pointShape: 'circle',
        legend:'bottom',
        backgroundColor: 'white',
        tooltip: { isHtml: true },
        hAxis: {
          title: 'MONTH'
        },
        vAxis: {
          	title: 'PROJECT',
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

    /*tour. task #4421*/
    var config = [
            {
                "name" 		: "tour_1",
                "bgcolor"	: "black",
                "color"		: "white",
                "position"	: "B",
                "text"		: "See the progress of tasks around your company from overdue, open, closed.",
                "time" 		: 5000,
                "buttons"	: ["<span class='btn btn-xs btn-default endtour'>close</span>"]
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
    $(document).ready(function(){
        $("#maincontent").addClass('tour_1');
    })
    </script> 

	<div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6"> 
           <div id="new_task_report"></div> 
        </div>
        <div class="col-xs-12 col-sm-6 col-md-6"> 
            <div id="overdue_task_report"></div>
        </div>
    </div>



  <div>
  
<!-- test Purpose Monthly Graph  
<div id='testchart'></div>
<script type="text/javascript">
alert(new Date("2016-02-01T15:18:21+00:00"));
function drawChart() {
    var data = new google.visualization.DataTable();
    data.addColumn('date', 'Date');
    data.addColumn('number', '2016');

    data.addRows([
		[new Date("2016-01-01"), 20.43],
        [new Date("2016-02-01T15:18:21+00:00"), 21.77],
        [new Date("2016-03-01T15:18:59+00:00"), 20.96],
        [new Date("2016-04-01T15:19:22+00:00"), 15.75],
        [new Date("2016-05-01T15:19:44+00:00"), 6.92],
        [new Date("2016-06-01T08:12:00+00:00"), 4.46],
        [new Date("2016-07-06T07:54:00+00:00"), 2.54],
        [new Date("2016-08-01T15:30:21+00:00"), 2.96],
        [new Date("2016-09-01T15:30:35+00:00"), 2.94],
        [new Date("2016-10-01T15:30:58+00:00"), 3.3],
        [new Date("2016-11-01T15:31:37+00:00"), 10.72],
        [new Date("2016-12-01T07:54:15+00:00"), 17.04]
     ]);

    var dView = new google.visualization.DataView(data);
    dView.setColumns([
                        {calc:getmon, type:'number', label:"Month"}
                         ,1]);
    function getmon(dataTable, rowNum){
        var rd = dataTable.getValue(rowNum, 0);
        var rm = rd.getMonth();
        return {v:rm};
    }
 
    var options = {
            hAxis: {
            title: 'Month'
            ,showTextEvery: 1
            ,ticks: [{v:0, f:'Jan'}, {v:1, f:'Feb'}, {v:2, f:'Mar'}, {v:3, f:'Apr'}, {v:4, f:'May'}, {v:5, f:'Jun'}, {v:6, f:'Jul'}, {v:7, f:'Aug'}, {v:8, f:'Sep'}, {v:9, f:'Oct'}, {v:10, f:'Nov'}, {v:11, f:'Dec'}]
            }
}

    var chart = new google.visualization.LineChart(document.getElementById('testchart'));
    chart.draw(dView, options);
}
drawChart();
</script>-->