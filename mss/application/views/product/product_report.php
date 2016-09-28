<!DOCTYPE html>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Maintenance Schedule System  -  <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/report.css" type="text/css" media="screen" />


<style media="print" type="text/css">
body{
  -webkit-print-color-adjust:exact;
}
.container-fluid{
width:100%;
}
.report {
    border: 2px solid #0d446e;
    margin: 20px 0;
	padding-top: 10px;
}
.report-header {
    padding: 0 30px;
    text-align: right;
}
.report-logo {
    width: 240px;
}
.report-body {
    padding: 10px 30px;
}
h2 {
    margin-top: 0;
	margin-bottom: 5px;
}
h4 {
    margin: 0 0 5px;
}
p {
    margin: 0 0 5px;
}
ul {
    margin-bottom: 15px;
    margin-top: 0;
    padding-left: 12px;
}
li {
    padding-left: 10px;
}
td {
    border-bottom: 1px solid #fff;
    padding: 8px;
}
tr:first-child td{
	background: #0d446e;
	color: #fff;
    font-weight: bold;
	border-right: 0px solid #fff;
}
td:first-child {
    background: #b2c6d4;
	border-right: 1px solid #fff;
	width: 40%;
}
td:last-child {
    background: #e5ecf0;
}
.report-footer {
    background: #0d446e;
    color: #fff;
    padding: 10px 30px;
}
i {
    font-size: 15px;
    font-weight: bold;
}
.report-footer > p {
    font-size: 10px;
}
.document-detail {
    height: 500px;
}
.table {
    margin-bottom: 20px;
    max-width: 100%;
    width: 100%;
}
</style>

</head>

<body>
 	
<div class="container-fluid">
	<div class="report schedule-report">
		<div class="report-header">
			<img class="report-logo" src="<?php echo base_url(); ?>images/report_logo.png" />
		</div>

		<div class="report-body">
						
			<div class="row">
				<div class="col-xs-12">
					<h2>Products and Warranties</h2>
					<?php foreach($reports as $report){ ?>
					<?php
					$file = $report->filename==''? 'No document': '<a target="_bank" href="'.base_url().'product/document_load_report/'.$report->product_document_id.'">'.$report->filename.'</a>';
					$product_warranty_year = $report->product_warranty_year=='0'? '': $report->product_warranty_year.' Years ';
					$product_warranty_month = $report->product_warranty_month=='0'? '': $report->product_warranty_month.' Months';
					$product_maintenance_year = $report->product_maintenance_year=='0'? '': $report->product_maintenance_year.' Years ';
					$product_maintenance_month = $report->product_maintenance_month=='0'? '': $report->product_maintenance_month.' Months';
					?>
					<table class="table">
						<tbody>
							<tr>
								<td colspan="2"><?php echo $report->product_name; ?></td>
							</tr>
							<tr>
								<td>Product Type:</td><td><?php echo $report->product_type_name; ?></td>
							</tr>
							<tr>
								<td>Product Warranty:</td><td><?php echo $product_warranty_year.''.$product_warranty_month; ?></td>
							</tr>
							<tr>
								<td>Maintenance Period:</td><td><?php echo $product_maintenance_year.''.$product_maintenance_month; ?></td>
							</tr>
							<tr>
								<td>Description:</td><td><?php echo $report->description_of_maintenance; ?></td>
							</tr>
							<tr>
								<td>Document:</td><td><?php echo $file; ?></td>
							</tr>
						</tbody>
					</table>
					<?php } ?>
					
				</div>
			</div>
		</div>
		<div class="report-footer">
			<i>We call Canterbury home</i>
			<p>38 Lowe St, Addington, PO Box 8255, Riccarton, Christchurch, New Zealand. Ph: (03) 348 8905 0800 NEW HOME <br>info@horncastle.co.nz <strong>www.horncastle.co.nz</strong> Proud to be Naming Partner for <strong>Horncastle Arena</strong></p>
		</div>
	</div>

</div>

</body>
</html>