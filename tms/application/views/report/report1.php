
<script type="text/javascript" src="https://www.google.com/jsapi?autoload={'modules':[{'name':'visualization','version':'1','packages':['corechart']}]}"></script>
<script type="text/javascript">
    google.load('visualization', '1', {packages: ['corechart']});
    google.setOnLoadCallback(drawChart);

    function drawChart() {

      var data = new google.visualization.DataTable();
      data.addColumn('number', 'X');
      data.addColumn('number', 'Cornor');
     

      data.addRows([
          <?php 
foreach ($user_new_task as $value) {
        echo '['.$value->week. ', '.$value->request_quantity.'], ';
}
?>
       
      ]);


      var options = {
        width: 800,
        height: 450,
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

                  

			
			
			
	