<script>
    window.wbsBaseUrl = "<?php print base_url(); ?>";
</script>
 <?php 
 $this->load->helper('url');
 
 $model = $this->load->model('permission_model');
 $redirect_login_page = base_url().'user';
if(!$this->session->userdata('user')){redirect($redirect_login_page); }

 ?>



<div class="container maincontent"> 

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-10 col-lg-10 job-title">
			<h3>Job #<?php echo $development_details->job_number.": ";  echo $title;  ?></h3>
		</div>
		<div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 job-title">
			<a href="<?php echo base_url('job/change_job'); ?>" data-fancybox-type="iframe" class="fancybox btn new-job" style="background-color:#f9b800; color:white; width:100%">
				Change Job
			</a>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
			<!-- Start menu -->
			<div id="cons_menu">
			
				<div id="first_level" class="con_menu">
					<div id="pre_construction" class="files"><a class="btn btn-default btn-cons" href="#one">Pre Construction</a></div>
					<div id="construction" class="mail"><a class="btn btn-default btn-cons" href="#two">Construction</a></div>
				</div>
	
				<div id="second_level" style="display:none;clear:both">
	
					<div id="one_child">
						<div class="sub-menu">
							<div id="check_list"><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>job/checklist/<?php echo $_SESSION['current_job']; ?>">Check List</a></div>
							<div id="job_info"><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/construction_detail/<?php echo $_SESSION['current_job']; ?>">Job Information</a></div>
							<div id="trade_contact_list"><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>job/trade_contact_list/<?php echo $_SESSION['current_job']; ?>">Trade Contact List</a></div>
						</div>
					</div>
	
					<div id="two_child">
						<div class="sub-menu">
							<div id="dashboard"><a href="<?php echo base_url(); ?>stage/plan_vs_actual/<?php  echo $_SESSION['current_job']; ?>/1" class="btn btn-default btn-cons">Dashboard</a></div>
							<div id="program"><a href="<?php echo base_url(); ?>constructions/phases_underway/<?php echo $_SESSION['current_job']; ?>" class="btn btn-default btn-cons">Program</a></div>
							<div id="photos"><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/construction_photos/<?php echo $_SESSION['current_job']; ?>">Photos</a></div>
							<div id="notes"><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/notes/<?php echo $_SESSION['current_job']; ?>">Notes</a></div>
							<div id="documents"><a class="btn btn-default btn-cons" href="<?php echo base_url(); ?>constructions/construction_documents/<?php echo $_SESSION['current_job']; ?>">Documents</a></div>
						</div>
					</div>
	
				</div>
	
			
			</div>
			<!-- end menu -->
	
			<script>
			function mainmenu(){
				
				$("#pre_construction").click(function(){
					$("#second_level").css({display: "block"});
					$("#two_child").css({display: "none"});
					$("#one_child").css({"visibility": "visible"}).show(400);
	
					$("#cons_menu a").css("background-color","#5a5b5d");
					$("#pre_construction a").css("background-color","#f9b800");
				});
	
				$("#construction").click(function(){
					$("#second_level").css({display: "block"});
					$("#one_child").css({display: "none"});
					$("#two_child").css({"visibility": "visible"}).show(400);
	
					$("#cons_menu a").css("background-color","#5a5b5d");
					$("#construction a").css("background-color","#f9b800");
				});
	
	
			}

			function change_bg(btn_id)
			{
				$("#" + btn_id + " a").css("background-color","#f9b800");		
			}
		
			function show_sub(sub_menu_id)
			{
				$("#" + sub_menu_id ).css({display: "block"});
			}
	
			function visible_sub(sub_menu_box_id)
			{
				$("#" + sub_menu_box_id ).css({"visibility": "visible"}).show(400);
			}
			
			function hide_menu(hide_box_id)
			{
				$("#" + hide_box_id ).css({display: "none"});
			}
			
			$(document).ready(function(){					
				mainmenu();
	
				<?php 
					$controller_name = $this->uri->segment(1);
					$page_name = $this->uri->segment(2);
					
					if($page_name == "plan_vs_actual")
					{ 
				?>
	
					change_bg('construction');
					show_sub('second_level');
					visible_sub('two_child');
					hide_menu('one_child');
					change_bg('dashboard');
				<?php
					}
				?>
	
			});
			</script>
		</div>
	</div>

	           
    <div id="devlopment-content">
      <?php echo $stage_content; ?>   
    </div>
    <div class="clear"></div>
</div>