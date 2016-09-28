<?php 
	$user = $this->session->userdata('user'); 
	$wp_company_id = $user->company_id;

	$this->db->select("wp_company.*,wp_file.*");
	$this->db->join('wp_file', 'wp_file.id = wp_company.file_id', 'left'); 
 	$this->db->where('wp_company.id', $wp_company_id);	
	$wpdata = $this->db->get('wp_company')->row();

	$logo = 'http://'.$_SERVER['HTTP_HOST'].'/uploads/logo/'.$wpdata->filename;
?> 
<!--task #4125-->
<script type="text/javascript" src="<?php echo base_url();?>js/jquery.printElement.js"></script>
<script>
window.BaseUrl = "<?php echo base_url(); ?>";
jQuery(document).ready(function()
{
	$('#clear_search').click(function(){
        newurl = window.BaseUrl + 'notes/notes_list';
		window.location = newurl;
    });

	/*printing...*/
	$("#pirnt_report").click(function(e){
		e.preventDefault();
		$('#printDiv>div').printElement();
	})
});
/*tour. task #4421*/
var config = [
		{
			"name" 		: "tour_1",
			"bgcolor"	: "black",
			"color"		: "white",
			"position"	: "B",
			"text"		: "See who put the notes, what is the notes about, when it is posted, persons notified around your company.",
			"time" 		: 5000,
			"buttons"	: ["<span class='btn btn-xs btn-default endtour'>Close</span>"]
		}

	],
//define if steps should change automatically
	autoplay	= false,
//timeout for the step
	showtime,
//current step of the tour
	step		= 0,
//total number of steps
	total_steps	= config.length;
$(document).ready(function(){
	$("#request_list_view").addClass('tour_1');
})
</script>
<div class="content-inner">
<div class="row">
	<div class="col-md-12">
		<a href="#" id="pirnt_report" style="float: right">
			<img src="<?php echo base_url()?>images/print.png"/>
		</a>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-4 col-md-3">
		<img height="67" alt="TMS Notes" title="WP TMS" src="<?php echo $logo; ?>">
	</div>
	<div class="col-xs-12 col-sm-4 col-md-6">
		<form class="note_email" action="<?php echo base_url(); ?>notes/notes_list" method="get">
			<div class="col-xs-12 col-sm-12 col-md-4">
				<label for="note_form_date">From Date</label>
				<input type="text" required="1" placeholder="From Date" class="form-control" id="note_form_date" value="<?php echo $_GET['from_date']; ?>" name="from_date">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-4">
				<label for="note_to_date">To Date</label>
				<input type="text" required="1" placeholder="To Date" class="form-control" id="note_to_date" value="<?php echo $_GET['to_date']; ?>" name="to_date">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-2">
				<label for="note_to_date"></label>
				<input type="submit" class="form-control" id="submit" value="Search" name="submit">
			</div>
			<div class="col-xs-12 col-sm-12 col-md-2">
				<label for="note_to_date"></label>
				<input type="button" class="form-control" id="clear_search" value="Clear" name="button">
			</div>
		</form>
	</div>
	<div class="col-xs-12 col-sm-4 col-md-3 note-email-date" style="text-align:right;">
		<h3>Task Notes Report</h3>
		<?php 
		$today = date("l, j F Y"); 
   		if(!empty($_GET)){
			echo date('Y-m-d',strtotime($_GET['from_date'])).' To '.date('Y-m-d',strtotime($_GET['to_date']));
		}else{
			echo $today;
		}
		?>
	</div>
</div>


<div id="request_list_view" class="row table-list">
	<div class="col-md-12">
		<?php if(isset($table)) { echo $table;	} ?> 
		<div class="pagination"> 
			<?php if(isset($pagination)) { echo $pagination;	} ?>
		</div> 
	</div>
</div>
</div>
<div id="printDiv" style="display: none">
	<div>
	<img height="67" alt="TMS Notes" title="WP TMS" src="<?php echo $logo; ?>">
		<div style="text-align:right;">
			<h3>Task Notes Report</h3>
			<?php
			$today = date("l, j F Y");
			if(!empty($_GET)){
				echo date('Y-m-d',strtotime($_GET['from_date'])).' To '.date('Y-m-d',strtotime($_GET['to_date']));
			}else{
				echo $today;
			}
			?>
		</div>
	<?php if(isset($table)) { echo $table;	} ?>
	</div>
</div>