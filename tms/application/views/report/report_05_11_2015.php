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

//print_r($user_name); 

$predefinedYear = date("Y");


?>


<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script type="text/javascript">
    google.load('visualization', '1', {packages: ['corechart']});
    google.setOnLoadCallback(drawChartOpen);

    function drawChartOpen() {

      var data = new google.visualization.DataTable();
		data.addColumn('datetime', 'Year');
      
      <?php 
        foreach ($user_name as $uname) { ?>
           data.addColumn('number', '<?php echo $uname->name; ?>'); 
       <?php }
      ?>
      
     

      data.addRows([
          
<?php 

 //print_r($user_open_task);
 
 $weeks = array();

 $total_user =  count($user_open_task);
 for($i=0; $i< count($user_open_task); $i++)
 {
     $arr1 = $user_open_task[$i];
     
     for($j=0; $j<count($arr1); $j++)
     {
         $obj1 = $arr1[$j];
         $week = $obj1->week;
         if(!in_array($week, $weeks))
         {
            $weeks[] = $week;
         }

     }

 }

 
 sort($weeks);
 // week loop
 for($k=0;$k<count($weeks); $k++)
 {


	$predefinedWeeks = $weeks[$k];
	// find first m?nday of the year
	$firstMon = strtotime("mon jan {$predefinedYear}");
	
	// calculate how much weeks to add
	$weeksOffset = $predefinedWeeks - date('W', $firstMon);
	
	// calculate searched monday
	$searchedMon = strtotime("+{$weeksOffset} week " . date('Y-m-d', $firstMon));
	
	$Month = date("m",$searchedMon);

	$Day = date("d",$searchedMon);


     $str = '[ new Date('.$predefinedYear.','.$Month.','.$Day.'),';
     // user loop
     for($l=0; $l < $total_user; $l++)
     {
         $flag = 0;
         $arr3 = $user_open_task[$l];
         for($m=0;$m<count($arr3); $m++)
         {
             $user_week = $arr3[$m]->week;
             if($weeks[$k] == $user_week )
             {
                 $str = $str.$arr3[$m]->request_quantity.',';
                 $flag = 1;
             }
         } 
      if($flag == 0)
      {
          $str = $str.'0,';
      }
         
     } 
     $str = $str.'],';
     echo $str; 
     
     
 }
 
// exit;
 
 
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
          title: 'WEEK',
			format: 'dd-MMM'
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
        foreach ($user_name as $uname) { ?>
           data.addColumn('number', '<?php echo $uname->name; ?>'); 
       <?php }
      ?>
      
     

      data.addRows([
          
<?php 

 //print_r($user_new_task);
 
 $weeks = array();

 $total_user =  count($user_close_task);
 for($i=0; $i< count($user_close_task); $i++)
 {
     $arr1 = $user_close_task[$i];
     
     for($j=0; $j<count($arr1); $j++)
     {
         $obj1 = $arr1[$j];
         $week = $obj1->week;
         if(!in_array($week, $weeks))
         {
            $weeks[] = $week;
         }
         
     }

 }

 
 sort($weeks);
 // week loop
 for($k=0;$k<count($weeks); $k++)
 {

	$predefinedWeeks = $weeks[$k];
	// find first m?nday of the year
	$firstMon = strtotime("mon jan {$predefinedYear}");
	
	// calculate how much weeks to add
	$weeksOffset = $predefinedWeeks - date('W', $firstMon);
	
	// calculate searched monday
	$searchedMon = strtotime("+{$weeksOffset} week " . date('Y-m-d', $firstMon));
	
	$Month = date("m",$searchedMon);

	$Day = date("d",$searchedMon);


     $str = '[ new Date('.$predefinedYear.','.$Month.','.$Day.'),';

     // user loop
     for($l=0; $l < $total_user; $l++)
     {
         $flag = 0;
         $arr3 = $user_close_task[$l];
         for($m=0;$m<count($arr3); $m++)
         {
             $user_week = $arr3[$m]->week;
             if($weeks[$k] == $user_week )
             {
                 $str = $str.$arr3[$m]->request_quantity.',';
                 $flag = 1;
             }
         } 
      if($flag == 0)
      {
          $str = $str.'0,';
      }
         
     } 
     $str = $str.'],';
     echo $str;
     
     
 }
 
 
 
 
?>
  ]);
  var options = {
      title:'Close Tasks -Weekly',
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
    
    <hr/>
    
    <script type="text/javascript">
    google.load('visualization', '1', {packages: ['corechart']});
    google.setOnLoadCallback(drawChartNew);

    function drawChartNew() {

      var data = new google.visualization.DataTable();
      data.addColumn('datetime', 'Year');
      <?php 
        foreach ($user_name as $uname) { ?>
           data.addColumn('number', '<?php echo $uname->name; ?>'); 
       <?php }
      ?>
      
     

      data.addRows([
          
<?php 

 //print_r($user_new_task);
 
 $weeks = array();

 $total_user =  count($user_new_task);
 for($i=0; $i< count($user_new_task); $i++)
 {
     $arr1 = $user_new_task[$i];
     
     for($j=0; $j<count($arr1); $j++)
     {
         $obj1 = $arr1[$j];
         $week = $obj1->week;
         if(!in_array($week, $weeks))
         {
            $weeks[] = $week;
         }
         
     }

 }

 
 sort($weeks);
 // week loop
 for($k=0;$k<count($weeks); $k++)
 {
     $predefinedWeeks = $weeks[$k];
	// find first m?nday of the year
	$firstMon = strtotime("mon jan {$predefinedYear}");
	
	// calculate how much weeks to add
	$weeksOffset = $predefinedWeeks - date('W', $firstMon);
	
	// calculate searched monday
	$searchedMon = strtotime("+{$weeksOffset} week " . date('Y-m-d', $firstMon));
	
	$Month = date("m",$searchedMon);

	$Day = date("d",$searchedMon);


     $str = '[ new Date('.$predefinedYear.','.$Month.','.$Day.'),';
     // user loop
     for($l=0; $l < $total_user; $l++)
     {
         $flag = 0;
         $arr3 = $user_new_task[$l];
         for($m=0;$m<count($arr3); $m++)
         {
             $user_week = $arr3[$m]->week;
             if($weeks[$k] == $user_week )
             {
                 $str = $str.$arr3[$m]->request_quantity.',';
                 $flag = 1;
             }
         } 
      if($flag == 0)
      {
          $str = $str.'0,';
      }
         
     } 
     $str = $str.'],';
     echo $str;
     
     
 }
 
 
 
 
?>
  ]);


      var options = {
           title:'New Tasks -Weekly',
        width: 700,
        height: 350,
        pointSize: 5,
        pointShape: 'circle',        
        backgroundColor:{chartArea:'#ffffff', fill:'#ffffff'} ,  
       
        legend: { position: 'bottom' },
        tooltip: { isHtml: true },
        hAxis: {
          title: 'WEEK',
          gridlines: null
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
      
      google.setOnLoadCallback(drawChartOverdue);

    function drawChartOverdue() {

      var data = new google.visualization.DataTable();
      data.addColumn('datetime', 'Year');
      <?php 
        foreach ($user_name as $uname) { ?>
           data.addColumn('number', '<?php echo $uname->name; ?>'); 
       <?php }
      ?>
      
     

      data.addRows([
          
<?php 

 //print_r($user_new_task);
 
 $weeks = array();

 $total_user =  count($user_overdue_task);
 for($i=0; $i< count($user_overdue_task); $i++)
 {
     $arr1 = $user_overdue_task[$i];
     
     for($j=0; $j<count($arr1); $j++)
     {
         $obj1 = $arr1[$j];
         $week = $obj1->week;
         if(!in_array($week, $weeks))
         {
            $weeks[] = $week;
         }
         
     }

 }

 
 sort($weeks);
 // week loop
 for($k=0;$k<count($weeks); $k++)
 {
     $predefinedWeeks = $weeks[$k];
	// find first m?nday of the year
	$firstMon = strtotime("mon jan {$predefinedYear}");
	
	// calculate how much weeks to add
	$weeksOffset = $predefinedWeeks - date('W', $firstMon);
	
	// calculate searched monday
	$searchedMon = strtotime("+{$weeksOffset} week " . date('Y-m-d', $firstMon));
	
	$Month = date("m",$searchedMon);

	$Day = date("d",$searchedMon);


     $str = '[ new Date('.$predefinedYear.','.$Month.','.$Day.'),';
     // user loop
     for($l=0; $l < $total_user; $l++)
     {
         $flag = 0;
         $arr3 = $user_overdue_task[$l];
         for($m=0;$m<count($arr3); $m++)
         {
             $user_week = $arr3[$m]->week;
             if($weeks[$k] == $user_week )
             {
                 $str = $str.$arr3[$m]->request_quantity.',';
                 $flag = 1;
             }
         } 
      if($flag == 0)
      {
          $str = $str.'0,';
      }
         
     } 
     $str = $str.'],';
     echo $str;
     
     
 }
 
 
 
 
?>
  ]);


      var options = {
           title:'Overdue Tasks -Weekly',
        width: 700,
        height: 350,
        pointSize: 5,
        pointShape: 'circle',
        backgroundColor: 'white',
        tooltip: { isHtml: true },
        legend: { position: 'bottom' },
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

                  