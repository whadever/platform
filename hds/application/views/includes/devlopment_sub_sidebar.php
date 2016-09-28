<?php

  //  $ci = & get_instance();

	//$ci->load->model('sub_company_model');

	//$sub_comp_names = $ci->sub_company_model->sub_company_name()->result();
//echo $this->uri->segment(1);
?>


 <div class="development-sidebar">
            <ul class="sub-sidebar-nav">
                <li class="<?php if($this->uri->segment(2)=="project_detail") echo "active" ?>">
                    <a href="<?php echo base_url();?>project/project_detail/<?php echo $project_id; ?>"> Development Info </a> 
                </li>
                <li class="<?php if($this->uri->segment(2)=="development_overview") echo "active" ?>">
                    <a href="<?php echo base_url();?>project/development_overview/<?php echo $project_id; ?>">Development Overview </a> 
                </li>
                <li class="<?php if($this->uri->segment(2)=="development_photos") echo "active" ?>">
                    <a href="<?php echo base_url();?>project/development_photos/<?php echo $project_id; ?>">Development Photos </a> 
                </li>
                <li class="<?php if($this->uri->segment(2)=="development_notes") echo "active" ?>">
                    <a href="<?php echo base_url();?>project/development_notes/<?php echo $project_id; ?>">Development Notes </a> 
                </li>
                <li class="<?php if($this->uri->segment(2)=="phases_underway") echo "active" ?>">
                    <a href="<?php echo base_url();?>project/phases_underway/<?php echo $project_id; ?>">Phases Underway </a> 
                </li>
                
            </ul>
           
        </div>