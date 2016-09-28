<style>
.development-overview-home {
    border: 1px solid #002855;
    border-radius: 5px;
    box-shadow: 0 0 1px #fff;
    margin: 0 auto;
    width: 88%;
}
#devlopment-overview-content {
    height: 475px;
    overflow-x: hidden;
    overflow-y: scroll;
}
.overview-areas {
    padding: 26px 8px 0 26px;
}
.overview-area-list {
    border: 1px solid #002855;
    border-radius: 5px;
    float: left;
    margin: 0 1% 2% 0;
    padding: 1%;
    text-align: center;
    width: 24%;
	height: 130px;
	font-size: 13px;
    font-weight: bold;
}
.overview-area-list .development-name {
    color: #222;
    font-size: 13px;
    font-weight: bold;
    line-height: 16px;
    padding-bottom: 5px;
}
.overview-area-list .overview-area-top {
    height: 100px;
}
.overview-area-list .development-link {
    float: right;
    font-size: 40px;
    left: 9px;
    line-height: 21px;
    position: relative;
    top: -3px;
	font-weight: normal;
}
.overview-area-list .development-link > a {
    color: #f3c203;
	text-decoration: none;
}
.overview-area-list .development-link > a:hover {
    color: #f3c203;
	text-decoration: none;
}
.sidebar-block-bottom {
    width: 150px;
}
.overview-area-footer .container {
    padding: 10px 44% 0;
}
.overview-area-footer .sidebar-block-bottom-left {
    float: left;
    height: 27px;
    margin-top: 8px;
    text-align: center;
    vertical-align: bottom;
    width: 45%;
}
.overview-area-footer .sidebar-block-bottom {
    background: none repeat scroll 0 0 #ecebf0;
    border: 1px solid #222;
    border-radius: 10px;
    font-size: 12px;
    height: 40px;
    margin: 0 5px;
    padding: 3px 0;
}
.overview-area-list span.string {
    color: #004272;
}
</style>

<div class="overview-areas">
	<div class="overview-area">
		<div class="overview-area-inner">
			<?php
				foreach($developments as $development) {

				$flag = 0;
				$phase_data = array();
				$stage_data = array();  
			?>

				<div class="overview-area-list">
					<div class="overview-area-top">
					<div class="development-name"><?php echo $development->development_name; ?></div>

				<?php
					$development_id = $development->id;

					$this->db->select('`phase_name`,`planned_start_date`,`planned_finished_date`');
					$this->db->where('development_id', $development_id);
					$this->db->order_by('planned_finished_date', 'ASC');
					$development_phase_statuss = $this->db->get('development_phase')->result();

					$this->db->select('`phase_name`,`planned_start_date`,`planned_finished_date`,`stage_no`');
	  				$this->db->where('development_id', $development_id);
					$this->db->order_by('planned_finished_date', 'ASC');
					$stage_phase_statuss = $this->db->get('stage_phase')->result();

					$now = date('Y-m-d');
					$today_time = strtotime($now);
					$phase_date = '';
					$stage_date = '';
					foreach($development_phase_statuss as $development_phase_status) 
					{
						
						if($development_phase_status->planned_finished_date != '0000-00-00')
						{
							$flag = 1;
						}

						if($today_time < strtotime($development_phase_status->planned_finished_date))
						{	
							$phase_date = 1;
							$phase_data[0]= $development_phase_status->phase_name;
							$phase_data[1]=$development_phase_status->planned_finished_date;
										
							break;
						}	

					}

					foreach($stage_phase_statuss as $stage_phase_status) 
					{
						if($stage_phase_status->planned_finished_date != '0000-00-00')
						{
							$flag = 1;
						}

						if($today_time < strtotime( $stage_phase_status->planned_finished_date ) )
						{  
							$stage_date = 1;
							$stage_data[0]= $stage_phase_status->stage_no;
							$stage_data[1]=$stage_phase_status->planned_finished_date;

							break;
						}

						
					}


					if($flag == 0)
					{
						echo '<div class="development-status"><span class="string">Status</span></div>';
						echo '<div class="development-date"><span class="string">Planned Completion</span></div>';	
					}
					elseif($phase_date==1 && $stage_date=='')
					{
						echo '<div class="development-status"><span class="string">Status</span> <br />'.$phase_data[0].'</div>';
						echo '<div class="development-date"><span class="string">Planned Completion</span> <br />'.$phase_data[1].'</div>';	
					}
					elseif($stage_date==1 && $phase_date=='')
					{
						echo '<div class="development-status"><span class="string">Status</span> <br /> Stage '.$stage_data[0].'</div>';
						echo '<div class="development-date"><span class="string">Planned Completion</span> <br />'.$stage_data[1].'</div>';	
					}
					else
					{

						if(strtotime($phase_data[1]) < strtotime($stage_data[1]) )
						{
							echo '<div class="development-status"><span class="string">Status</span> <br />'.$phase_data[0].'</div>';
							echo '<div class="development-date"><span class="string">Planned Completion</span> <br />'.$phase_data[1].'</div>';
						}
					
						else
						{
							echo '<div class="development-status"><span class="string">Status</span> <br /> Stage '.$stage_data[0].'</div>';
							echo '<div class="development-date"><span class="string">Planned Completion</span> <br />'.$stage_data[1].'</div>';					
						}

					}
					
				?>
					</div>
					<div class="development-link"><a href="<?php echo base_url(); ?>potential_developments/development_detail/<?php echo $development->id?>">+</a></div>
				</div>
			<?php
				}
			?>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>	 
</div> 	 

