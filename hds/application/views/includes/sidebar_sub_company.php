<?php

    $ci = & get_instance();

	$ci->load->model('sub_company_model');

	$sub_comp_names = $ci->sub_company_model->sub_company_name()->result();

?>

<div class="sidebar">  
 <div class="block">
  <h2>Sub Company List</h2>
  <ul class="sidebar-nav">

   <?php foreach($sub_comp_names as $comp_name): ?>

	<li><?php echo anchor('sub_company/sub_company_detail/'. $comp_name->sub_cid , $comp_name->sub_com_name); ?></li>	

   <?php endforeach; ?>	    

  </ul>
 </div>
</div>