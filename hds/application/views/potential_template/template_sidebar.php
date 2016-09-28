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
    <div class="block grey">   
         
        <ul class="accordion" id="accordion-1">
            <li class="dcjq-current-parent">
				<a <?php if($this->uri->segment(1)=="potential_admindevelopment"){ echo'class="active"'; } ?> href="#"> Development </a>
				<ul style="<?php if($this->uri->segment(1)=="potential_admindevelopment"){ echo 'display: block;'; }else{ echo 'display: none;'; } ?>">
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_admindevelopment" && $this->uri->segment(2)=="development_list"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_admindevelopment/development_list"> Development Info </a> 
	                </li>
	                
	                <?php if($this->uri->segment(2)=="development_add_template") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_admindevelopment" && $this->uri->segment(2)=="development_add_template"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_add_template_update") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_admindevelopment" && $this->uri->segment(2)=="development_add_template_update"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_admindevelopment/development_start"> Add Development</a> 
	                </li>                
	                <?php elseif($this->uri->segment(2)=="development_add") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_admindevelopment" && $this->uri->segment(2)=="development_add"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_update") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_admindevelopment" && $this->uri->segment(2)=="development_update"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_add_stage") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_admindevelopment" && $this->uri->segment(2)=="development_add_stage"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_add_stage_update") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_admindevelopment" && $this->uri->segment(2)=="development_add_stage_update"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_review") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_admindevelopment" && $this->uri->segment(2)=="development_review"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="development_review_update") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_admindevelopment" && $this->uri->segment(2)=="development_review_update"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php else : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_admindevelopment" && $this->uri->segment(2)=="development_start"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_admindevelopment/development_start"> Add Development</a> 
	                </li>
	                <?php endif; ?>
	                
	            </ul>
			</li>	    
            <li  class="dcjq-current-parent">
				<a <?php if($this->uri->segment(1)=="potential_template"){ echo'class="active"'; } ?> href="#"> Templates </a>
				<ul style="<?php if($this->uri->segment(1)=="potential_template"){ echo 'display: block;'; }else{ echo 'display: none;'; } ?>">
	            	<?php if($this->uri->segment(2)=="template_detail") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_template" && $this->uri->segment(2)=="template_detail"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_template/template_list"> Template List </a> 
	                </li>
	                <?php else : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_template" && $this->uri->segment(2)=="template_list"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_template/template_list"> Template List </a> 
	                </li>
	                <?php endif; ?>
	                                
	                <?php if($this->uri->segment(2)=="template_basic_info") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_template" && $this->uri->segment(2)=="template_basic_info"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_template/template_basic_info"> Add Template</a> 
	                </li> 
	                <?php elseif($this->uri->segment(2)=="template_basic_info_update") : ?>
	                
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_template" && $this->uri->segment(2)=="template_basic_info_update"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_template/template_start"> Add Template</a> 
	                </li>               
	                <?php elseif($this->uri->segment(2)=="template_design") : ?>
	                
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_template" && $this->uri->segment(2)=="template_detail"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_template/template_start"> Add Template</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="template_design_update") : ?>               
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_template" && $this->uri->segment(2)=="template_design_update"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_template/template_start"> Add Template</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="template_review") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_template" && $this->uri->segment(2)=="template_review"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_template/template_start"> Add Template</a> 
	                </li>
	                <?php elseif($this->uri->segment(2)=="template_review_update") : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_template" && $this->uri->segment(2)=="template_review_update"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_template/template_start"> Add Template</a> 
	                </li>
	                <?php else : ?>
	                <li>
	                    <a <?php if($this->uri->segment(1)=="potential_template" && $this->uri->segment(2)=="template_start"){ echo'class="active_menu"'; } ?> href="<?php echo base_url();?>potential_template/template_start"> Add Template</a> 
	                </li>
	                <?php endif; ?>
	                
	            </ul>
			</li>
            <!---<li class="<?php if($this->uri->segment(1)=="user") echo "active" ?>"><?php echo anchor('user/user_list','User',array('class'=>'user')); ?>
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
		<span><?php  date_default_timezone_set('NZ'); echo date("h:i a", time()).' | '; ?><?php echo date('d.m.Y', time()); echo ' | '; $today = getdate(); echo $today['weekday']; ?></span>      
    </div>
    
     
</div> 