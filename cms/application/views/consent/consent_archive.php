<style>
select{width: 90px;}
input[type="text"]{ width: 90px;}
report-link{background: none repeat scroll 0 0 #eee;border-radius: 3px;color: #000;padding: 3px 7px;}
.report-link:hover{text-decoration:none;color: #000;}
input.form-text{width:80%}
@media screen and (max-width:1280px){
	.consent_table{width:250%; max-width:250% !IMPORTANT}
}
@media screen and (min-width:1300px){
	.consent_table{width:120%; max-width:120% !IMPORTANT}
}
</style>


<?php

	$st = microtime(true);
	$this->load->library('session');
			
	if(isset($_POST['from_month']))
	{
		$start_month = $_POST['from_month'];
		$smonths['f_month'] = $start_month;
		$this->session->set_userdata($smonths);
	}
	else
	{
		$start_month = 6;
	}
	if(isset($_POST['to_month']))
	{
		$end_month = $_POST['to_month'];
		$smonths['t_month'] = $end_month;
		$this->session->set_userdata($smonths);
	}
	else
	{
		$end_month = 0;
	}

	$total_months = $start_month + $end_month;

	$s_month = $this->session->userdata('f_month');
	$e_month = $this->session->userdata('t_month');


	if($s_month == '')
	{
		$s_month = 6;
	}

	if($e_month == '')
	{
		$e_month = 0;
	}
	
	$ci = &get_instance();
	$ci->load->model('consent_model');
	$user_info = $ci->consent_model->user_option();

	$consent_by_list = $ci->consent_model->get_user_category_list(3);
	$project_manager_list = $ci->consent_model->get_user_category_list(4);
	$builder_list = $ci->consent_model->get_user_category_list(5);
                               
?>
<script type="text/javascript">

window.Url = "<?php print base_url(); ?>";


// search function
$.fn.eqAnyOf = function (arrayOfIndexes) {
    return this.filter(function(i) {
        return $.inArray(i, arrayOfIndexes) > -1;
    });
};

$(document).ready(function() {	
$("#filter").bind("keyup",advance_search);
});

function advance_search()
{
	
        // Retrieve the input field text and reset the count to zero
        var filter = $("#filter").val(), count = 0;

		if(filter !='')
		{
			var parr = new Array(); 
			
			if($('#sjobno').is(":checked")){ pos = parr.length;	parr[pos] = 0 ;	}
			if($('#sconsent_name').is(":checked")){ pos = parr.length; parr[pos] = 1 ; } 
			if($('#sdesign').is(":checked")){ pos = parr.length; parr[pos] = 2 ; }
			if($('#sapproval_date').is(":checked")){ pos = parr.length;	parr[pos] = 3 ;	}
			if($('#spim_logged').is(":checked")){ pos = parr.length; parr[pos] = 4 ; } 
			if($('#sin_council').is(":checked")){ pos = parr.length; parr[pos] = 5 ; }
			if($('#sconsent_out').is(":checked")){ pos = parr.length;	parr[pos] = 6 ;	}
			if($('#sdrafting_issue_date').is(":checked")){ pos = parr.length; parr[pos] = 7 ; } 
			if($('#sconsent_by').is(":checked")){ pos = parr.length; parr[pos] = 8 ; }
			if($('#saction_required').is(":checked")){ pos = parr.length;	parr[pos] = 9 ;	}
			if($('#scouncil').is(":checked")){ pos = parr.length; parr[pos] = 10 ; } 
			if($('#sbc_number').is(":checked")){ pos = parr.length; parr[pos] = 11 ; }
			if($('#sno_units').is(":checked")){ pos = parr.length;	parr[pos] = 12 ;	}
			if($('#scontract_type').is(":checked")){ pos = parr.length; parr[pos] = 13 ; } 
			if($('#stype_of_build').is(":checked")){ pos = parr.length; parr[pos] = 14 ; }
			if($('#svariation_pending').is(":checked")){ pos = parr.length;	parr[pos] = 15 ;	}
			if($('#sfoundation_type').is(":checked")){ pos = parr.length; parr[pos] = 16 ; } 
			if($('#sdate_logged').is(":checked")){ pos = parr.length; parr[pos] = 17 ; }
			if($('#sdate_issued').is(":checked")){ pos = parr.length;	parr[pos] = 18 ;	}
			if($('#sdays_in_council').is(":checked")){ pos = parr.length; parr[pos] = 19 ; } 
			if($('#sorder_site_levels').is(":checked")){ pos = parr.length; parr[pos] = 20 ; }
			if($('#sorder_soil_report').is(":checked")){ pos = parr.length;	parr[pos] = 21 ;	}
			if($('#sseptic_tank_approval').is(":checked")){ pos = parr.length; parr[pos] = 22 ; } 
			if($('#sdev_approval').is(":checked")){ pos = parr.length; parr[pos] = 23 ; }
			if($('#sproject_manager').is(":checked")){ pos = parr.length; parr[pos] = 24 ; }
			if($('#sallocated_to_pm').is(":checked")){ pos = parr.length;	parr[pos] = 25 ;	}
			if($('#sunconditional_date').is(":checked")){ pos = parr.length; parr[pos] = 26 ; } 
			if($('#shandover_dat').is(":checked")){ pos = parr.length; parr[pos] = 27 ; }
			
			if(parr.length == 0)
			{
				for(j=0; j<28; j++)
				{
					parr[j] = j; 	
				}
			}

			var table_open_ids = [];
			var all_table_ids = [];
			// Loop through the consent row list
			$(".consent_table tr").each(function()
			{
	 
					// If the list item does not contain the text phrase fade it out
				if ($(this).find("td").eqAnyOf(parr).text().search(new RegExp(filter, "i")) < 0) 
				{
					$(this).fadeOut();
					// Show the list item if the phrase matches and increase the count by 1
				}
				else 
				{	
					$(this).show();
					count++;
					tid = $(this).closest('li').attr('id');
					
					if(table_open_ids.length == 0){
						table_open_ids[0] = tid;
					}
					
					for(i=0; i<table_open_ids.length; i++){
						if( jQuery.inArray( tid, table_open_ids ) == -1 ){
							var pos = table_open_ids.length;
							table_open_ids[pos] = tid;
						}
					}
				}

			});
			$("#msg").html( count + ' results were found for "' + filter + '"' );
			
			
			<?php 
				$i = 0;
				for($p = $s_month; $p >= $e_month; $p--)
				{
					
					?>
					all_table_ids[<?php echo $i; ?>] = <?php echo $p; ?>;
			<?php   $i++;	
				}	
				?>
		
			
			for(j=0; j<all_table_ids.length; j++)
			{
				$('#' + all_table_ids[j] ).removeClass('accordion-active');
				$('#' + all_table_ids[j] ).find('.accordion-content').slideUp(300);			
			}
			
			for(j=0; j<table_open_ids.length; j++)
			{
				$('#' + table_open_ids[j] ).addClass('accordion-active');
				$('#' + table_open_ids[j] ).find('.accordion-content').slideDown(300);			
			}
		
		}
		else
		{
			$("#msg").html( '' );
		}
}


function clear_search()
{
	
	$("#filter").val( '' );
	$(".consent_table tr").each(function()
	{
		$(this).show();	
	});
	$("#msg").html( '' );
	$('#search_option').find('input[type=checkbox]:checked').removeAttr('checked');
	var all_table_ids = [];
	<?php 
		$i = 0;
		for($p = $s_month; $p >= $e_month; $p--)
		{
			
			?>
			all_table_ids[<?php echo $i; ?>] = <?php echo $p; ?>;
	<?php   $i++;	
		}	
		?>

	for(j=0; j< all_table_ids.length - 1; j++)
	{
		$('#' + all_table_ids[j] ).removeClass('accordion-active');
		$('#' + all_table_ids[j] ).find('.accordion-content').slideUp(300);			
	}
}

// all scroll script
function divScroll(curid)
{

   <?php 
	for($p = $s_month; $p >= $e_month; $p--)
	{
		
		$consent_id = 'consent'.$p;	
	?>
	
	var consid = '<?php echo $consent_id; ?>'; 
	var leftobj = document.getElementById(consid);
	var rightobj = document.getElementById(curid);
	
	leftobj.scrollLeft = rightobj.scrollLeft;
	
	<?php
	}
   
   ?>
}




// row select function
function selectrow(tr_id,tr_class)
{
	if(tr_class == '')
	{
		document.getElementById('delete_consent_item').href= window.Url + 'consent/consent_delete/'+tr_id;
		$("tr").removeClass("checked");
		document.getElementById(tr_id).className = 'checked';
		$('#delete_consent').css("display","block");
	}
	else
	{

		document.getElementById('delete_consent_item').href= window.Url + 'consent/consent_list/#';
		$("tr").removeClass("checked");
		$('#delete_consent').css("display","none");

	}

}

// sortable/moveable function
$(function()
{

	<?php 
			$tableids = '';
			for($p=$s_month; $p >= $e_month; $p--)
			{
				$tableids = $tableids.'#table'.$p." tbody.tbody".$p.', ';
			}
		?>
		
		var tblids = '<?php echo $tableids; ?>';
		var numoftblids = tblids.length;
    	var restable = tblids.substring(0, numoftblids - 2);


	$( restable ).sortable({
		connectWith: ".connectedSortable",
		items: ">*:not(.sort-disabled)",
		update : function () { 
			var table_body_id = $(this).attr('id');
			var order = $(this).sortable('serialize');
			$.ajax({
				url: window.Url + 'consent/consent_ordering/' + encodeURIComponent(order) + '/' + table_body_id,
				type: 'POST',
				data: order,
				success: function(data) 
				{
					
				},
			        
			});
			}
	});
	
});

// job number check function
function checkjobno()
{

	var job_no = $('input#job-no').val();
		
	var html = $.ajax({
			async: false,
			url: window.Url + 'consent/check_job_no?job_no=' + job_no,
			type: 'POST',
			dataType: 'html',
			//data: {'pnr': a},
			timeout: 2000,
		}).responseText;
		if(html==1){
			$('input#job-no').css('border', '1px solid #FF0000');
			$('#consentSave').css("display","none");
			return false;
		}else{
			$('input#job-no').css('border', '1px solid #01416f');
			$('#consentSave').css("display","block");
			return true;
		}			
}


$(document).ready(function () {
	
	$(".live_datepicker").attr('maxlength','8');

	

	// zoom in code
	$('#zoomin').click(function()
	{
		
		var fontsize = $('table td').css('font-size');

		var num = fontsize.length;
    	var res = fontsize.substring(0, num-2);

        newsize = parseInt(res) + 1;

		if(newsize < 18)
		{

			newsizepx = newsize + 'px';

        	$('table td').css('font-size',newsizepx);
			$('table th').css('font-size',newsizepx);
		}

		var width = $('.consent_table').width();
		var widthpx = $(".consent_table").offsetParent().width();
		var percent = 100*width/widthpx;
		var new_tbl_width_out = percent + 100;

		if(new_tbl_width_out < 800 )
		{

			new_tbl_width_out_per = new_tbl_width_out + '%';
			
			$('.consent_table').css("width", new_tbl_width_out_per );
			$('.consent_table').css("max-width", new_tbl_width_out_per );			

		}
		

	});


	// zoom out code
	$('#zoomout').click(function(){


		widthpx = 0;
		var fontsize = $('table td').css('font-size');

		var num = fontsize.length;
    	var res = fontsize.substring(0, num-2);
        var newsize = parseInt(res) - 1;

		if(newsize > 7)
		{

			newsizepx = newsize + 'px';

        	$('table td').css('font-size',newsizepx);
			$('table th').css('font-size',newsizepx);
		}

		var width = $('.consent_table').width();
		var widthpx = $(".consent_table").offsetParent().width();
		var percent = 100*width/widthpx;	
		new_tbl_width_out = percent - 100;

		if(new_tbl_width_out > 100 )
		{

			new_tbl_width_out_per = new_tbl_width_out + '%';
			
			$('.consent_table').css("width", new_tbl_width_out_per );
			$('.consent_table').css("max-width", new_tbl_width_out_per );

		}

		if(new_tbl_width_out < 100)
		{

			new_tbl_width_out_per = '100' + '%';			
			$('.consent_table').css("width", new_tbl_width_out_per );
			$('.consent_table').css("max-width", new_tbl_width_out_per );

		}

	});


	$('.accordions').each(function(){
		
		// Set First Accordion As Active
		$(this).find('.accordion-content').hide();
		if($(this).hasClass('toggles')){
			$(this).find('.accordion:first-child').addClass('accordion-active');
			$(this).find('.accordion:first-child .accordion-content').show();
		}
		
		// Set Accordion Events
		$(this).find('.accordion-header').click(function(){
			
			if(!$(this).parent().hasClass('accordion-active')){
				
				// Close other accordions
				if(!$(this).parent().parent().hasClass('toggles')){
					$(this).parent().parent().find('.accordion-active').removeClass('accordion-active').find('.accordion-content').slideUp(300);
				}
				
				// Open Accordion
				$(this).parent().addClass('accordion-active');
				$(this).parent().find('.accordion-content').slideDown(300);
			
			}else{
				
				// Close Accordion
				$(this).parent().removeClass('accordion-active');
				$(this).parent().find('.accordion-content').slideUp(300);
				
			}
			
			k = 0;
			ids = '';
			<?php 
			for($p=$s_month; $p >= $e_month; $p--)
			{
		
			?>
				
				if( $('#' + <?php echo $p; ?>).hasClass('accordion-active') == true)
				{
					ids = ids + <?php echo $p; ?> + '_' ;
				}
				
			<?php } ?>
			
			if(ids == '')
			{
				document.getElementById('download_link').href = '#';
				document.getElementById('print_link').href = '#';
				$('#download_link').attr('target', '_self');
				$('#print_link').attr('target', '_self');	
			}
			else
			{
				document.getElementById('download_link').href = 'consent_list_download/' + ids + '/' + <?php echo $s_month ?> + '/' + <?php echo $e_month ?>;
				document.getElementById('print_link').href = 'consent_list_print/' + ids + '/' + <?php echo $s_month ?> + '/' + <?php echo $e_month ?>;
				$('#download_link').attr('target', '_blank');
				$('#print_link').attr('target', '_blank');
			}

			
			document.getElementById('email_link_outlook').value = ids;
					
			var email_link_outlook = $('#email_link_outlook').val();
		
			$.ajax({
				url: window.Url + 'consent/consent_list_email/' + email_link_outlook,
				type: 'GET',
				success: function(data) 
				{
					//$("#print_link").val();
					document.getElementById('email_link').href = 'mailto:?subject=Consent%20List&body=' + data;
				},
					
			});
			
		});
	
	});	
});

</script>
		

	<!-- Start CMS Toolbar -->
	<div style="clear:both">
		<div class="consent_toolbar">
		
			
			<div id="cancel_cons" >
				<a class="black_text" id="consent_back" href="<?php echo base_url() ?>consent/consent_list">
					<img src="<?php echo base_url(); ?>images/icon/btn_back.png"  /><br />
					<span>Back</span>
				</a>
					   
			</div>

			<div id="search_box">
				<div style="font-size:12px;">
					<div style="float:left">Search: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="clear_search()">Clear Search</a></div>
					<div style="float:right"><a onclick="javascript:$('#search_option').toggle('slow');"  href="#">Advanced</a></div>
				</div>
				<input type="text" class="text-input" id="filter" value="" style="width:100%" />
				<div id="msg" style="font-style:italic"></div>
				<div id="search_option" style="width:100%; clear:both; display:none">

					<div style="width:15%; float:left; font-size:11px;">
						<input type="checkbox" name="sjobno" id="sjobno" class="check_class" onclick="advance_search()" /> Job No.<br>
						<input type="checkbox" name="sdrafting_issue_date" id="sdrafting_issue_date"  onclick="advance_search()" /> Drafting Issue Date<br>
						<input type="checkbox" name="stype_of_build"  id="stype_of_build" onclick="advance_search()" /> Type of Build<br>
						<input type="checkbox" name="sorder_soil_report" id="sorder_soil_report" onclick="advance_search()" /> Order Soil Report <br>
					</div>
					
					<div style="width:16%; float:left; font-size:11px;">
						<input type="checkbox" name="sconsent_name" id="sconsent_name" onclick="advance_search()" /> Consent<br>
						<input type="checkbox" name="sconsent_by" id="sconsent_by" onclick="advance_search()" /> Consent by<br>
						<input type="checkbox" name="svariation_pending" id="svariation_pending" onclick="advance_search()" /> Variation Pending<br>
						<input type="checkbox" name="sseptic_tank_approval" id="sseptic_tank_approval" onclick="advance_search()" /> Septic Tank Approval<br>
					</div>

					<div style="width:14%; float:left; font-size:11px;">
						<input type="checkbox" name="sdesign" id="sdesign" id="sdesign" onclick="advance_search()" /> Design<br>
						<input type="checkbox" name="saction_required" id="saction_required"  onclick="advance_search()" /> Action Required<br>
						<input type="checkbox" name="sfoundation_type" id="sfoundation_type" onclick="advance_search()" /> Foundation Type<br>
						<input type="checkbox" name="sdev_approval" id="sdev_approval" onclick="advance_search()" /> Dev Approval<br>
					</div>

					<div style="width:14%; float:left; font-size:11px;">
						<input type="checkbox" name="sapproval_date" id="sapproval_date" onclick="advance_search()" /> Approval Date<br>
						<input type="checkbox" name="scouncil" id="scouncil" onclick="advance_search()" /> Council<br>
						<input type="checkbox" name="sdate_logged" id="sdate_logged" onclick="advance_search()" /> Date Logged<br>
						<input type="checkbox" name="sproject_manager" id="sproject_manager"  onclick="advance_search()"/> Project Manager<br>
					</div>

					<div style="width:13%; float:left; font-size:11px;">
						<input type="checkbox" name="spim_logged" id="spim_logged" onclick="advance_search()" /> Pim Logged<br>
						<input type="checkbox" name="sbc_number" id="sbc_number" onclick="advance_search()" /> Bc Number<br>
						<input type="checkbox" name="sdate_issued" id="sdate_issued" onclick="advance_search()" /> Date Issued<br>
						<input type="checkbox" name="sallocated_to_pm" id="sallocated_to_pm" onclick="advance_search()" /> Allocated to PM<br>
					</div>

					<div style="width:14%; float:left; font-size:11px;">
						<input type="checkbox" name="sin_council" id="sin_council" onclick="advance_search()" /> In Council<br>
						<input type="checkbox" name="sno_units" id="sno_units" onclick="advance_search()" /> No. Units<br>
						<input type="checkbox" name="sdays_in_council" id="sdays_in_council" onclick="advance_search()" /> Days in Council<br>
						<input type="checkbox" name="sunconditional_date" id="sunconditional_date" onclick="advance_search()" /> Unconditional Date<br>
					</div>

					<div style="width:14%; float:left; font-size:11px;">
						<input type="checkbox" name="sconsent_out" id="sconsent_out"  onclick="advance_search()"/> Consent Out<br>
						<input type="checkbox" name="scontract_type" id="scontract_type" onclick="advance_search()" /> Contract Type<br>
						<input type="checkbox" name="sorder_site_levels" id="sorder_site_levels" onclick="advance_search()" /> Order Site Levels<br>
						<input type="checkbox" name="shandover_dat" id="shandover_dat" onclick="advance_search()" /> Handover Date<br>
					</div>

					
				</div>

			</div>

			<div class="zoomout">
				<a href="#"><img id="zoomout" src="<?php echo base_url() ?>images/icon/icon_collapse.png" /></a>
			</div>

			<div class="zoomin">
				<a href="#"><img id="zoomin" src="<?php echo base_url() ?>images/icon/icon_expand.png" /></a>
			</div>

			<div class="download">
				<a class="black_text" target="_blank" id="download_link" href="<?php echo base_url(); ?>consent/consent_list_download/<?php echo $s_month; ?>_/<?php echo $s_month ?>/<?php echo $e_month ?>">
					<img src="<?php echo base_url(); ?>images/icon/icon_down_load.png" /><br />
					<span>Download</span>
				</a>
			</div>
			
			<div class="print">
				<a class="black_text" id="print_link" href="<?php echo base_url(); ?>consent/consent_list_print/<?php echo $s_month; ?>_/<?php echo $s_month ?>/<?php echo $e_month ?>" target="_blank">
					<img src="<?php echo base_url(); ?>images/icon/btn_horncastle_printer_old.png" /><br />
					<span>Print</span>
				</a>
			</div>
			
			
			
			<input type="hidden" name="email_link_outlook" id="email_link_outlook" value="0-">
			<div class="clear"></div>
		</div>
	</div>
	<!-- End CMS Toolbar -->
	
	<div id="consent_list" style="clear:both">
		<ul class="accordions toggles">
			<?php echo $report_message; ?>
		</ul>
		<div class="clear"></div>
	</div>

<div class="clear"></div>