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
    $controllers = $this->config->item('mbs_controllers');
    $operations = $this->config->item('mbs_operations');
?>

<div class="sidebar"> 
    <div class="block">   
         
        <ul class="sidebar-nav admin">
            <li class="<?php if($this->uri->segment(1)=="admindevelopment") echo "active" ?>"><?php echo anchor('admindevelopment/development_list','Development',array('class'=>'development')); ?>
				<ul class="sub-sidebar-nav">
	                <li class="<?php if($this->uri->segment(2)=="development_list") echo "active" ?>">
	                    <a href="<?php echo base_url();?>admindevelopment/development_list"> Development Info </a> 
	                </li>
	                
	                <?php if($this->uri->segment(2)=="development_add_template") : ?>
	                <li class="<?php if($this->uri->segment(2)=="development_add_template") echo "active" ?>">
	                    <a href="<?php echo base_url();?>admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_add_template_update") : ?>
	                <li class="<?php if($this->uri->segment(2)=="development_add_template_update") echo "active" ?>">
	                    <a href="<?php echo base_url();?>admindevelopment/development_start"> Add Development</a> 
	                </li>                
	                <?php elseif($this->uri->segment(2)=="development_add") : ?>
	                <li class="<?php if($this->uri->segment(2)=="development_add") echo "active" ?>">
	                    <a href="<?php echo base_url();?>admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_update") : ?>
	                <li class="<?php if($this->uri->segment(2)=="development_update") echo "active" ?>">
	                    <a href="<?php echo base_url();?>admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_add_stage") : ?>
	                <li class="<?php if($this->uri->segment(2)=="development_add_stage") echo "active" ?>">
	                    <a href="<?php echo base_url();?>admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_add_stage_update") : ?>
	                <li class="<?php if($this->uri->segment(2)=="development_add_stage_update") echo "active" ?>">
	                    <a href="<?php echo base_url();?>admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_review") : ?>
	                <li class="<?php if($this->uri->segment(2)=="development_review") echo "active" ?>">
	                    <a href="<?php echo base_url();?>admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_review_update") : ?>
	                <li class="<?php if($this->uri->segment(2)=="development_review_update") echo "active" ?>">
	                    <a href="<?php echo base_url();?>admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php else : ?>
	                <li class="<?php if($this->uri->segment(2)=="development_start") echo "active" ?>">
	                    <a href="<?php echo base_url();?>admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php endif; ?>
	                
	            </ul>
			</li>	    
            <li class="<?php if($this->uri->segment(1)=="template") echo "active" ?>"><?php echo anchor('template/template_list','Templates',array('class'=>'template')); ?>
				<ul class="sub-sidebar-nav">
	            	<?php if($this->uri->segment(2)=="template_detail") : ?>
	                <li class="<?php if($this->uri->segment(2)=="template_detail") echo "active" ?>">
	                    <a href="<?php echo base_url();?>template/template_list"> Template List </a> 
	                </li>
	                <?php else : ?>
	                <li class="<?php if($this->uri->segment(2)=="template_list") echo "active" ?>">
	                    <a href="<?php echo base_url();?>template/template_list"> Template List </a> 
	                </li>
	                <?php endif; ?>
	                                
	                <?php if($this->uri->segment(2)=="template_basic_info") : ?>
	                <li class="<?php if($this->uri->segment(2)=="template_basic_info") echo "active" ?>">
	                    <a href="<?php echo base_url();?>template/template_basic_info"> Add Template</a> 
	                </li> 
	                <?php elseif($this->uri->segment(2)=="template_basic_info_update") : ?>
	                
	                <li class="<?php if($this->uri->segment(2)=="template_basic_info_update") echo "active" ?>">
	                    <a href="<?php echo base_url();?>template/template_start"> Add Template</a> 
	                </li>               
	                <?php elseif($this->uri->segment(2)=="template_design") : ?>
	                
	                <li class="<?php if($this->uri->segment(2)=="template_design") echo "active" ?>">
	                    <a href="<?php echo base_url();?>template/template_start"> Add Template</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="template_design_update") : ?>               
	                <li class="<?php if($this->uri->segment(2)=="template_design_update") echo "active" ?>">
	                    <a href="<?php echo base_url();?>template/template_start"> Add Template</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="template_review") : ?>
	                <li class="<?php if($this->uri->segment(2)=="template_review") echo "active" ?>">
	                    <a href="<?php echo base_url();?>template/template_start"> Add Template</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="template_review_update") : ?>
	                <li class="<?php if($this->uri->segment(2)=="template_review_update") echo "active" ?>">
	                    <a href="<?php echo base_url();?>template/template_start"> Add Template</a> 
	                </li>
	                <?php else : ?>
	                <li class="<?php if($this->uri->segment(2)=="template_start") echo "active" ?>">
	                    <a href="<?php echo base_url();?>template/template_start"> Add Template</a> 
	                </li>
	                <?php endif; ?>
	                
	            </ul>
	           
			</li>
            <!----<li class="<?php if($this->uri->segment(1)=="user") echo "active" ?>"><?php echo anchor('user/user_list','User',array('class'=>'user')); ?>
				<ul class="sub-sidebar-nav">
	                <li class="<?php if($this->uri->segment(2)=="user_list") echo "active" ?>">
	                    <a href="<?php echo base_url();?>user/user_list"> User List </a> 
	                </li>
	                
	                
	                <?php if($this->uri->segment(2)=="user_update") : ?>
	                <li class="<?php if($this->uri->segment(2)=="user_update") echo "active" ?>">
	                    <a href="<?php echo base_url();?>user/user_update"> Add User</a> 
	                </li> 
					<?php else : ?>
	                <li class="<?php if($this->uri->segment(2)=="user_add") echo "active" ?>">
	                    <a href="<?php echo base_url();?>user/user_add"> Add User</a> 
	                </li>  
	                <?php endif; ?>
	                
	            </ul>
			</li>--->
        </ul> 
        
    </div>
    <div class="sidebar-block-bottom">
        <div class="sidebar-block-bottom-left"> <?php  date_default_timezone_set('NZ'); echo date("h:i a", time()); ?></div>
       
        <div class="sidebar-block-bottom-right"><?php echo date('d.m.Y', time()); echo '<br/>'; $today = getdate(); echo $today['weekday']; ?></div>
        
    </div>
    
     
</div> 