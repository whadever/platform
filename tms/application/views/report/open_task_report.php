<style>
    #report_button{
        float: right;        
    }
    #report_button ul li{
        display: inline;
        list-style-type: none;
    }
</style>
<div id="report_button">
<ul>
    <li><a class="btn btn-default" href="<?php echo base_url();?>report/report_list">New Task Report</a></li>
    <li><a class="btn btn-default" href="<?php echo base_url();?>report/close_task_report">Close Task Report</a></li>
    <li><a class="btn btn-default" href="<?php echo base_url();?>report/overdue_task_report">Overdue Task Report</a></li>
</ul>
</div>

<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script type="text/javascript">
    google.load('visualization', '1', {packages: ['corechart']});
    google.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
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
     $str = '[ '.$weeks[$k].',';
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
        width: 800,
        height: 450,
        pointSize: 5,
        pointShape: 'circle',
        backgroundColor: 'white',
        tooltip: { isHtml: true },
        hAxis: {
          title: 'WEEk'
        },
        vAxis: {
          title: 'TASK'
        },
        series: {
          1: {curveType: 'function'}
        }
      };

      var chart = new google.visualization.LineChart(document.getElementById('ex2'));

      chart.draw(data, options);
    }
      
    </script>
  
   

<div class="all-title">
   <?php echo $title;  ?>
</div>



 <div id="ex2" style="width: 900px; height: 500px;"></div>

                  