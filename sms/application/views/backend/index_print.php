<html>
<head>
	<title><?php echo $page_title; ?> | Cricket Live Foundation - School Management System</title>	
	
<style>
	
.print-container {
    border: 2px solid #231f20;
    margin: 0 auto;
    padding: 15px 20px 10px;
}

.print-header {
    border-bottom: 2px solid #818285;
    margin-bottom: 10px;
    padding-bottom: 10px;
}
.print-footer {
    border-top: 2px solid #818285;
    margin-top: 10px;
    padding-top: 5px;
    text-align: right;
}
.page-header {
    float: right;
}
.page-header h3 {
    margin-bottom: 0;
}
.page-header p {
    margin: 10px 0 0;
}
.print-content{
	min-height:400px;
}
table{
	width: 100%;
}
table thead tr {
   background: #818285;
   color: #fff;
}
table tbody tr:nth-child(2n+1) {
   background: #d1d2d4;
}
table tbody tr:nth-child(2n+2) {
   background: #e7e7e8;
}
table td, table th{
	padding: 5px;
}
</style>

<script>
	window.print();
</script>
</head>


<body>

<div class="print-container">
	<div class="print-header">
		<img src="<?php echo base_url(); ?>uploads/logo.png">
		<div class="page-header">
			<h3>School System</h3>
			<p><?php echo $page_title; ?></p>
		</div>
	</div>
	
	<div class="print-content">
		<?php include 'admin/'.$page_name.'.php'; ?>
	</div>	
	
	<div class="print-footer">
		<span><?php $today = getdate(); echo $today['weekday']; ?> | <?php echo date('d/m/Y', time()); ?> | <?php echo date("h:i a", time()); ?></span>    
	</div>	
</div>

</body>
</html>