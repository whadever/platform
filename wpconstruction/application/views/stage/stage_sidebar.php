<?php
/**
 *common variable
 *
 * $user	: full user object
 * $controllers	: array of controllers defined in config file where key is numeric and value is controller name like employee, company etc
 * $operations	: array of operations defined in config file where key is numeric and value is operation name like add, update, delete, print etc
 *
 *
 */
    //$controllers = $this->config->item('mbs_controllers');
    //$operations = $this->config->item('mbs_operations');

//echo $number_of_stages;
//echo 'stage id'.$stage_id;
?>



<div class="sidebar"> 
    <div class="block">   
         
        <ul class="sidebar-nav">
			<li><?php echo anchor(base_url().'developments/development_detail/'.$development_id,'Development',array('class'=>'stage')); ?>
				<ul class="sub-sidebar-nav">
	                <li class="<?php if($this->uri->segment(2)=="development_detail") echo "active" ?>">
	                    <a href="<?php echo base_url();?>developments/development_detail/<?php echo $development_id; ?>"> Development Info </a> 
	                </li>
	                <li class="<?php if($this->uri->segment(2)=="development_overview") echo "active" ?>">
	                    <a href="<?php echo base_url();?>developments/development_overview/<?php echo $development_id; ?>">Development Overview </a> 
	                </li>
					<li class="<?php if($this->uri->segment(2)=="phases_underway") echo "active" ?>">
	                    <a href="<?php echo base_url();?>developments/phases_underway/<?php echo $development_id; ?>">Phases Underway </a> 
	                </li>
	                <li class="<?php if($this->uri->segment(2)=="development_photos") echo "active" ?>">
	                    <a href="<?php echo base_url();?>developments/development_photos/<?php echo $development_id; ?>">Development Photos </a> 
	                </li>
	                <li class="<?php if($this->uri->segment(2)=="notes") echo "active" ?>">
	                    <a href="<?php echo base_url();?>developments/notes/<?php echo $development_id; ?>">Development Notes </a> 
	                </li>
	                <li class="<?php if($this->uri->segment(2)=="development_documents") echo "active" ?>">
	                    <a href="<?php echo base_url();?>developments/development_documents/<?php echo $development_id; ?>">Development Documents </a> 
	                </li>
	            </ul>
			</li>	
			<?php for($i=1; $i<=$number_of_stages; $i++){ ?>
			
			<?php if($i > 6) { ?>
			<style>
				.sidebar-nav li#menu-<?php echo $i; ?> ul.sub-sidebar-nav {
				    top: -205px;
				}
			</style>
			<?php } ?>
<?php
	$query = $this->db->query("SELECT MIN(stage_task_status) as all_task_status FROM stage_task where development_id=$development_id and stage_no=$i");
	$all_stage_task = $query->result();
//print_r($all_stage_phase);
	if($all_stage_task[0]->all_task_status == 1)
	{ 
		$image= '<img width="20" height="20" src="'.base_url().'images/icon/status_complate.png" />';
		$stage = 'complate';
	} 
	else
	{
		$image= '';
		$stage = '';
	}
?>
		
			<li id="menu-<?php echo $i; ?>" class="<?php if($stage_id==$i)echo 'active'; ?>"><?php echo anchor( base_url().'stage/stage_info/'.$development_id.'/'.$i,'Stage '.$i, array('class'=>$stage)); ?><?php echo $image; ?>
	            <ul class="sub-sidebar-nav">
	                <li class="<?php if($this->uri->segment(2)=="stage_info") echo "active" ?>">
	                    <a href="<?php echo base_url().'stage/stage_info/'.$development_id.'/'.$i; ?>"> Stage Info </a> 
	                </li>
	                <li class="<?php if($this->uri->segment(2)=="stage_overview") echo "active" ?>">
	                    <a href="<?php echo base_url().'stage/stage_overview/'.$development_id.'/'.$i; ?>">Stage Overview </a> 
	                </li>
	                <li class="<?php if($this->uri->segment(2)=="phases_list") echo "active" ?>">
	                    <a href="<?php echo base_url().'stage/phases_list/'.$development_id.'/'.$i; ?>">Phases List </a> 
	                </li>
	                <li class="<?php if($this->uri->segment(2)=="plan_vs_actual") echo "active" ?>">
	                    <a href="<?php echo base_url().'stage/plan_vs_actual/'.$development_id.'/'.$i; ?>">Plan Vs Actual</a> 
	                </li>
	                
	                <li class="<?php if($this->uri->segment(2)=="stage_photos") echo "active" ?>">
	                    <a href="<?php echo base_url().'stage/stage_photos/'.$development_id.'/'.$i; ?>">Stage Photos </a> 
	                </li>
	                <li class="<?php if($this->uri->segment(2)=="stage_notes") echo "active" ?>">
	                    <a href="<?php echo base_url().'stage/notes/'.$development_id.'/'.$i; ?>">Stage Notes </a> 
	                </li>
					<li class="<?php if($this->uri->segment(2)=="stage_documents") echo "active" ?>">
	                    <a href="<?php echo base_url().'stage/stage_documents/'.$development_id.'/'.$i; ?>">Stage Documents </a> 
	                </li>
	                
	            </ul>

			</li>
			
			<?php  } ?>
              
        </ul> 
        
    </div>
    <div class="sidebar-block-bottom">
        <div class="sidebar-block-bottom-left"> <?php  date_default_timezone_set('NZ'); echo date("h:i a", time()); ?></div>
       
        <div class="sidebar-block-bottom-right"><?php echo date('d.m.Y', time()); echo '<br/>'; $today = getdate(); echo $today['weekday']; ?></div>
        
    </div>
    
     
</div> 