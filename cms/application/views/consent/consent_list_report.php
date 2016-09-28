<style>

table tbody td {
    border: 1px solid #004370;
    padding: 0 10px;
}
table tbody td:last-child {
    border-right: 1px solid #004370;
}
.report-header {
    padding-bottom: 10px;
}
.report-header .button-right .print {
    float: right;
    margin-right: 10px;
    width: 40px;
}
.report-header .button-right .download {
    float: right;
    width: 42px;
}
.report-header .print a > img, .report-header .download a > img {
    height: 40px;
    width: 40px;
}
</style>

<div id="consent_list_report">

	<div class="report-header">
		<div class="button-right">
			<div class="download">
				<a href="<?php echo base_url(); ?>consent/consent_list_report_download/<?php echo $id; ?>" id="download_link" target="_blank" class="black_text">
					<img src="<?php echo base_url(); ?>images/icon/icon_down_load.png"><br>
				</a>
			</div>
			<div class="print">
				<a target="_blank" href="<?php echo base_url(); ?>consent/consent_list_report_print/<?php echo $id; ?>" id="print_link" class="black_text">
					<img src="<?php echo base_url(); ?>images/icon/btn_horncastle_printer_old.png"><br>
				</a>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	
	<div class="report-content">
		
		<?php echo $report_message; ?>
	
	</div>

<div class="clear"></div>
</div>