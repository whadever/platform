<script>

window.print();

</script>

<?php 
	$user = $this->session->userdata('user'); 

	//print_r($user);

	$this->db->select('application_role_id');
	$this->db->where('user_id',$user->uid);
	$this->db->where('application_id',3);
	$user_app_role = $this->db->get('users_application')->row();
	
	$app_role_id = $user_app_role->application_role_id; 

	$wp_company_id = $user->company_id;

	$this->db->select("wp_company.*,wp_file.*");
	$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->db->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->db->get('wp_company')->row();

	//print_r($wpdata);
	$main_url = 'http://'.$wpdata->url;
	$colour_one = $wpdata->colour_one;
	$colour_two = $wpdata->colour_two;
	$logo = 'http://www.wclp.co.nz/uploads/logo/'.$wpdata->filename;

?>  

<div class="logo">
	        <a class="" href="<?php echo base_url();?>">
	            <img src="<?php echo $logo;?>" height="67" title="TMS" alt="TMS" />
	        </a>
</div>

<div style="height:100px"></div>

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
        width: 800,
        height: 550,
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
        width: 800,
        height: 550,
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
        <div class="col-xs-12 col-sm-12 col-md-12"> 
            <div id="open_task_report"></div>
        </div>
		<div style="height:300px"></div>
        <div class="col-xs-12 col-sm-12 col-md-12"> 
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
        width:800,
        height: 550,
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
        width: 800,
        height:550,
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

	<div style="height:400px"></div>
	<div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12"> 
           <div id="new_task_report"></div> 
        </div>
		<div style="height:300px"></div>
        <div class="col-xs-12 col-sm-12 col-md-12"> 
            <div id="overdue_task_report"></div>
        </div>
    </div>   