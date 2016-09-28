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
         
        <ul class="sidebar-nav">
              <li class="active"><?php echo anchor('#','Development',array('class'=>'stage')); ?></li>	    
              <li><?php echo anchor('#','Stage 1',array('class'=>'stage')); ?></li>
              <li><?php echo anchor('#','Stage 2',array('class'=>'stage')); ?></li>
              <li><?php echo anchor('#','Stage 3',array('class'=>'stage')); ?></li>
              <li><?php echo anchor('#','Stage 4',array('class'=>'stage')); ?></li>
              <li><?php echo anchor('#','Stage 5',array('class'=>'stage')); ?></li>
              <li><?php echo anchor('#','Stage 6',array('class'=>'stage')); ?></li>
              <li><?php echo anchor('#','Stage 7',array('class'=>'stage')); ?></li>
        </ul> 
        
    </div>
    <div class="sidebar-block-bottom">
        <div class="sidebar-block-bottom-left"> <?php  date_default_timezone_set('NZ'); echo date("h:i a", time()); ?></div>
       
        <div class="sidebar-block-bottom-right"><?php echo date('d.m.Y', time()); echo '<br/>'; $today = getdate(); echo $today['weekday']; ?></div>
        
    </div>
    
     
</div> 