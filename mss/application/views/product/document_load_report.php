<!DOCTYPE html>

<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
	<title>Maintenance Schedule System  -  <?php if (isset($title)) {echo $title;} ?></title>
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.min.css" type="text/css" media="screen" />
	<link rel="stylesheet" href="<?php echo base_url();?>css/report.css" type="text/css" media="screen" />
</head>

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

.table {
    margin-bottom: 20px;
    max-width: 100%;
    width: 100%;
}

</style>


<body>

<?php
$pdflivrelink = base_url().'uploads/document/'.$report->filename;

?>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
<script>


jQuery(document).ready(function() {
   $("#theIframeId").each(function() {
       // width and height, including borders, padding, margins, etc.
       var height = this.offsetHeight;
       objIframe = document.getElementById('theIframeId');    
       objIframe.style.height = height;
   });
});
</script>
	
<div class="container-fluid">
	<div class="report schedule-report">
		<div class="report-header">
			<img class="report-logo" src="<?php echo base_url(); ?>images/report_logo.png" />
		</div>

		<div class="report-body">
						
			<div class="row">
				<div class="col-xs-12">
					<h2><?php echo $report->filename; ?></h2>
					<div class="document-detail">
						<?php if($report->filetype=='application/pdf'){ ?>
<?php
$pdflivrelink = base_url().'uploads/document/'.$report->filename;

?>
<iframe width="100%" height="2200" scrolling="no" src="<?php echo base_url();?>uploads/document/<?php echo $report->filename; ?>" >	</iframe>					

						<?php }else{ ?>	
						<object data="<?php echo base_url();?>uploads/document/<?php echo $report->filename; ?>" type="application/pdf" width="100%" height="100%">			 
			  				<p>It appears you don't have a PDF plugin for this browser.
			  				You can <a href="<?php echo base_url();?>uploads/document/<?php echo $report->filename; ?>">click here to
			  				download the PDF file.</a></p>
						</object>
						<?php } ?>												 
					</div>
					
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