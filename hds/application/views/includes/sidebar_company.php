<?php

    $ci = & get_instance();

	$ci->load->model('company_model');

	$comp_names = $ci->company_model->company_name()->result();

?>

<div class="sidebar">  
 <div class="block"> 
  <h2>Company List</h2> 
  <ul class="sidebar-nav">

   <?php foreach($comp_names as $comp_name): ?>

	<li><?php echo anchor('company/company_detail/'. $comp_name->cid , $comp_name->compname); ?></li>	

   <?php endforeach; ?>	    

  </ul>
 </div>
</div>