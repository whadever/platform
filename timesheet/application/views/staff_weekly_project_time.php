<body>
<style>
	table thead {
		border: 0 none;
	}
	table th {
		border: 0;
	}
	table td{
		font-size: 14px;
	}
	table input, table select, table textarea {
		border: 1px solid oldlace;
		height: 29px;
	}
	table{
		margin: 10px;
		width: 97%;
	}
	.ui-widget-overlay {
		background: black none repeat scroll 0 0;
		opacity: 0.3;
	}
	tr#top_row {
		background-color: grey;
	}
</style>
<?php if(isset($massage)) echo $message;  ?>

	<div class="row modal-header">
		<div class="col-md-12">
			<div style="float: left;color: white; margin-top: 10px;">
				<span style="font-weight: bold; font-size: 130%;"><?php echo $username; ?></span><br>
				<span style="font-size: 220%; font-weight: bold;">Time Sheets: <?php echo $project_name; ?></span>
			</div>
			<!--<div class="cal <?php /*echo strtolower($dt->format('M')); */?>" >
				<span class="day"><?php /*echo $dt->format('d'); */?></span>
			</div>-->
		</div>
	</div>
<div class="content"  style="max-height: 400px; height: 400px; overflow: auto">
	<table style="margin-top: 5px" id="tbl">
		<thead>
		<tr>
			<th width="">Day</th>
			<th width="">Total Hour</th>
			<th width="">Notes</th>
		</tr>

		</thead>
		<tbody>
		<?php foreach($times as $t): ?>
		<tr>
			<td>
				<?php
				$dt = date_create_from_format('Y-m-d',$t->day);
				echo $dt->format('l')."<br>".$dt->format('d F');
				?>
			</td>
			<td>
				<?php
				$hours = floor($t->total_time / 60);
				$minutes = ($t->total_time  % 60);
				echo sprintf('%02d:%02d', $hours, $minutes);
				?>
			</td>
			<td>
				<?php echo $t->notes; ?>
			</td>
		</tr>
		<?php endforeach; ?>
		</tbody>
	</table>

</div>

<script>
	/*this is a patch*/
	var matched, browser;
	jQuery.uaMatch = function( ua ) {
		ua = ua.toLowerCase();

		var match = /(chrome)[ \/]([\w.]+)/.exec( ua ) ||
			/(webkit)[ \/]([\w.]+)/.exec( ua ) ||
			/(opera)(?:.*version|)[ \/]([\w.]+)/.exec( ua ) ||
			/(msie) ([\w.]+)/.exec( ua ) ||
			ua.indexOf("compatible") < 0 && /(mozilla)(?:.*? rv:([\w.]+)|)/.exec( ua ) ||
			[];

		return {
			browser: match[ 1 ] || "",
			version: match[ 2 ] || "0"
		};
	};

	matched = jQuery.uaMatch( navigator.userAgent );
	browser = {};

	if ( matched.browser ) {
		browser[ matched.browser ] = true;
		browser.version = matched.version;
	}

	// Chrome is Webkit, but Webkit is also Safari.
	if ( browser.chrome ) {
		browser.webkit = true;
	} else if ( browser.webkit ) {
		browser.safari = true;
	}

	jQuery.browser = browser;
	/********************************************/

	jQuery(document).ready(function(){
		jQuery('.content').mCustomScrollbar({
			theme:"dark"
		});

		$('.timepicker').timepicker({
			'timeFormat': 'H:i',
			step: 10
		});

		$("table form").ajaxForm({
			dataType: 'json',
			success: function(data){
				if(data.status == 'error'){
					$( "#error-message" ).html(data.message).dialog({
						modal: true,
						buttons: {
							Ok: function() {
								$( this ).dialog( "close" );
							}
						}
					});
				}else{
					$("#success-message").dialog({
						buttons: {
							Ok: function() {
								$( this ).dialog( "close" );

							}
						},
						close: function( event, ui ) {
							location.reload();
						}
					});
				}
			}
		});
		$('a.del').click(function (event)
		{
			event.preventDefault();

			var url = $(this).attr('href');

			$( "#dialog-confirm" ).dialog({
				resizable: false,
				height:200,
				modal: true,
				buttons: {
					"Delete": function() {
						$.get(url, function(data) {
							location.reload();
						});
						$( this ).dialog( "close" );
					},
					Cancel: function() {
						$( this ).dialog( "close" );
					}
				}
			});


		});
	});
</script>


</body>
</html>



