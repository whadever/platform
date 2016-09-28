
 <div class="row">
   
   <div id="project_list_view" class="">

    <?php foreach ($developments as $development){ ?>
    <div class="project-home-box">
        
            <h6 align="center"><?php echo $development->development_name; ?></h6>
            <hr style="border: 10px solid red; margin: 20px -10px;"/>  
            <div class="project-home-box-plus"><a href="<?php echo base_url() ;?>potential_developments/development_detail/<?php echo $development->id; ?>"><img src="<?php echo base_url() ;?>images/plus_icon.png"/></a></div>
           
          
        
    </div>
    <?php	} ?>
     
       <div class="clear"></div>
    <div><img src="<?php echo base_url();?>images/devlopment_stage_color.png"/> </div>
</div>
    
    
</div>
<a title="This link now here is for just check purpose only" href="<?php echo base_url();?>admindevelopment/development_add">Add new Developments</a>
  
  
 

			
			
			
	