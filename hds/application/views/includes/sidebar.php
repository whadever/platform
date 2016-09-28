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
        <h2>Entry Form</h2>    
        <ul class="sidebar-nav">
              <li><?php echo anchor('request/request_add','Add New Request',array('class'=>'request-add')); ?></li>
	      
              <li><?php echo anchor('project/project_add','Add New Project',array('class'=>'project-add')); ?></li>	    
              <li><?php echo anchor('note/note_add','Add note',array('class'=>'note-add')); ?></li>	 
       
        </ul>
    </div>
    
    <div class="block">   
        <h2>Report</h2>    
        <ul class="sidebar-nav">
              <li><?php echo anchor('request/request','Requests',array('class'=>'request')); ?></li>	    
              <li><?php echo anchor('project/project','Projects',array('class'=>'project')); ?></li>	    
        </ul>
    </div>
    
    <div class="block utility">   
        <h2>Utility</h2>    
        <ul class="sidebar-nav">
              <li><?php echo anchor('backup/database','Backup DB',array('class'=>'backup')); ?></li>	    
              <li><?php echo anchor('backup/download','Database list',array('class'=>'download')); ?></li>	    	    
        </ul>
    </div>    
</div> 