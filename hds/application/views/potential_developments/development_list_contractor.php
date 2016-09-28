 <?php 
 $this->load->helper('url');
 
 $model = $this->load->model('permission_model');
 $redirect_login_page = base_url().'user';
if(!$this->session->userdata('user')){redirect($redirect_login_page); }

 ?>

<html>
    
<head>
    <title> Developments - Developments List </title> 
	<link rel="shortcut icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">
	<link rel="icon" href="<?php echo base_url();?>icon/favicon.ico" type="image/x-icon">

<style>
html {
    background-image: url("<?php echo base_url(); ?>images/home_bg.jpg");
    background-position: center center;
    background-repeat: no-repeat;
    background-size: 100% 100%;
    height: 100%;
}
.dev-list-left {
    float: left;
    width: 25%;
}
.dev-list-main {
    float: left;
    width: 50%;	
}
.dev-list-right {
    float: right;
    width: 25%;
}
.dev-list-right > a {
    float: right;
}
.dev-main {
    margin: 62px 5% 0;
	font-family:arial;
}
.dev-main-button {
    background: none repeat scroll 0 0 #002855;
    border-radius: 5px;
    margin-top: 10px;
    padding: 12px 30px;
	text-align: center;
}
.dev-main-button > a {
    color: #fff;
    font-weight: bold;
    text-decoration: none;
}
.dev-main-inner {
    background: none repeat scroll 0 0 #002855;
    border-radius: 5px;
    padding: 20px 30px 30px;
}
.dev-main-title {
    color: #fff;
    font-size: 24px;
	text-align: center;
}

.dev-main-body {
    background: none repeat scroll 0 0 #efefef;
    border-radius: 5px;
    margin-top: 15px;
    padding: 3px 5px 10px;
}

.dev-main-table {
    background: none repeat scroll 0 0 #fff;
	border-radius: 5px;
}
.dev-main-table-inner {
    height: 300px;
    overflow-x: hidden;
    overflow-y: scroll;
}
.dev-list-main table {
    padding: 5px 10px;
    width: 100%;
	color: #222;
}
.dev-list-main table tr {
    cursor: pointer;
}
.dev-main-search form {
    background: none repeat scroll 0 0 #fff;
    border-radius: 5px;
	height: 32px;
}
.dev-main-search img {
    float: left;
    padding: 1%;
    width: 8%;
}
.dev-main-search input[type="text"] {
    border: 1px solid #eee;
    font-size: 11px;
    margin-right: 1%;
	margin-left:1%;
	margin-top: 5px;
	margin-bottom:5px;
    padding-left:1%;
    width: 32%;
	height: 25px;
	float: left;
}
.dev-list-main table tr.checked {
    background: none repeat scroll 0 0 #eee;
}
.dev-list-main table tr:hover {
    background: none repeat scroll 0 0 #eee;
}

select#location {
    border: 1px solid #eee;
    font-size: 11px;
    
    width: 34%;
	margin-top: 5px;
	margin-bottom:5px;
	margin-right: 1%;
	height: 25px;
	float: left;
}
select#all_open_close {
    border: 1px solid #eee;
    font-size: 11px;
    
    width: 30%;
	margin-top: 5px;
	margin-bottom:5px;
	height: 25px;
	float: left;
}
input#development-submit {
    background: none;
    border: medium none;
    cursor: pointer;
    height: 32px;
    margin-left: -9%;
    padding: 0;
    width: 8%;
	float: left;
}
</style>
<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>

<script>

window.Url = "<?php print base_url(); ?>";
jQuery(document).ready(function() {

	$("#development-name").keyup(function() {

		var selectedDevelopmentName1 = this.value;
		if(selectedDevelopmentName1)
		{
			var selectedDevelopmentName = selectedDevelopmentName1;
		}
		else
		{
			var selectedDevelopmentName = 'ZiaurRahman123';
		}
		var selectedStatusId = $("#all_open_close").val();
		var selectedLocationId = $("#location").val();

		$.ajax({
			url: window.Url + 'potential_developments/change_development_status_contractor/' + selectedStatusId,
			type: 'GET',
			data: { selectedDevelopmentName2 : selectedDevelopmentName, selectedLocationId2 : selectedLocationId },
			success: function(data) 
			{
				//console.log(data); 	
				$('.dev-main-table-inner').empty();
				$('.dev-main-table-inner').append(data);			        
			},
		        
		});
	});

	$("#location").change(function() {

		var selectedLocationId = this.value;
		var selectedStatusId = $("#all_open_close").val();
		var selectedDevelopmentName1 = $("#development-name").val();
		if(selectedDevelopmentName1)
		{
			var selectedDevelopmentName = selectedDevelopmentName1;
		}
		else
		{
			var selectedDevelopmentName = 'ZiaurRahman123';
		}

		$.ajax({
			url: window.Url + 'potential_developments/change_development_status_contractor/' + selectedStatusId,
			type: 'GET',
			data: { selectedDevelopmentName2 : selectedDevelopmentName, selectedLocationId2 : selectedLocationId },
			success: function(data) 
			{
				//console.log(data); 	
				$('.dev-main-table-inner').empty();
				$('.dev-main-table-inner').append(data);			        
			},
		        
		});
	});

	$("#all_open_close").change(function() {

		var selectedStatusId = this.value;
		var selectedLocationId = $("#location").val();
		var selectedDevelopmentName1 = $("#development-name").val();
		if(selectedDevelopmentName1)
		{
			var selectedDevelopmentName = selectedDevelopmentName1;
		}
		else
		{
			var selectedDevelopmentName = 'ZiaurRahman123';
		}

		$.ajax({
			url: window.Url + 'potential_developments/change_development_status_contractor/' + selectedStatusId,
			type: 'GET',
			data: { selectedDevelopmentName2 : selectedDevelopmentName, selectedLocationId2 : selectedLocationId },
			success: function(data) 
			{
				//console.log(data); 	
				$('.dev-main-table-inner').empty();
				$('.dev-main-table-inner').append(data);			        
			},
		        
		});
	});

});
</script>

<script>
	function setdevelopmentid(did)
	{ 		 
		document.getElementById('overview-development').href='development_detail/'+did;
		$("tr").removeClass("checked");
		document.getElementById('check_'+did).className='checked';
		$("td span").css('display','block');	
		$("td a").css('display','none');
		$(".checked td span").css('display','none');	
		$(".checked td a").css('display','block');					
	}
</script>

</head>
<body>

<div class="mianbody">
	
	<div class="clear"></div>
	
	<div class="dev-list-overview">
		<div class="dev-list-left">
			<a href="<?php echo base_url(); ?>welcome" class="brand">         
	                <img height="67" alt="Home" title="Home" src="<?php echo base_url(); ?>images/btn_home.png">
	        </a>
			<a class="brand" onclick="window.history.go(-1)">
                <img src="<?php echo base_url();?>images/btn_up.png" height="67" title="Back" alt="Back" />
            </a>
		<div class="clear"></div>
		</div>
		
		<div class="dev-list-main">
			<div class="dev-main">
				<div class="dev-main-inner">
					<div class="dev-main-header">
						<div class="dev-main-title">Please Select A Development</div>
					</div>
					<div class="dev-main-body">
						<div class="dev-main-search">
							<form action="#" method="post">
								<?php //$get = $_GET; ?>
								
								<!----<img height="27" width="30" alt="Home" title="Home" src="<?php echo base_url(); ?>images/search.jpg">--->
								<!----<input id="development-submit" type="submit" name="submit" />--->
								<input type="text" id="development-name" name="development_name" value="<?php if(isset($get['development_name'])){ echo $get['development_name']; } ?>" placeholder="Search Development..." />
								<select name="development_city" id="location">
									<option value="0">All Cities</option>
									<option <?php if(isset($get['development_city'])){ $development_city = $get['development_city']; if($development_city == 'Christchurch') {echo 'selected'; } } ?> value="Christchurch">Christchurch</option>
									<option <?php if(isset($get['development_city'])){ $development_city = $get['development_city']; if($development_city == 'Auckland') {echo 'selected'; } } ?> value="Auckland">Auckland</option>
								</select>
								<select name="all_open_close" id="all_open_close">
									<option value="0">Open</option>
									<option <?php if(isset($get['all_open_close'])){ $all_open_close = $get['all_open_close']; if($all_open_close == '2') {echo 'selected'; } } ?> value="2">All</option>
									<option <?php if(isset($get['all_open_close'])){ $all_open_close = $get['all_open_close']; if($all_open_close == '1') {echo 'selected'; } } ?> value="1">Closed</option>
								</select>
							</form>
						</div>
						<div class="clear"></div>
						<div class="dev-main-table">
							<div class="dev-main-table-inner">
								<table>
									<tbody>
									<?php
										for($i=1; $i<= count($developments); $i++ )
										{
											$j = 1;
											if($developments[$i][$j+1] == 0)
											{
									?>
												<tr id="check_<?php echo $developments[$i][$j]; ?>" onclick="<?php echo 'setdevelopmentid('. $developments[$i][$j] . ');'; ?>"><td><span><?php echo $developments[$i][$j+2]; ?></span><a style="display: none;" href="development_detail/<?php echo $developments[$i][$j]; ?>"><?php echo $developments[$i][$j+2]; ?></a></td></tr>
									<?php 
											}
										}
									?>
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<div class="dev-main-button">
					<a id="overview-development" href="#">View Development</a>
				</div>
				<!---<div class="dev-main-button">
					<a id="overview" href="<?php echo base_url(); ?>developments/development_overview_area">Development Overview</a>
				</div>--->
			</div>
		<div class="clear"></div>	
		</div>
		
		<div class="dev-list-right">
			<a href="<?php echo base_url(); ?>user/user_logout"> 
				<img height="67" alt="staff1" title="staff1" src="<?php echo base_url(); ?>images/btn_power.png"> 
			</a>
		<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	
	<div class="clear"></div>

</div>

</body>

</html>