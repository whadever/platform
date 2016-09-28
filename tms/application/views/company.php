<script>
function sort_company(){
    var sort_by= $('#select_sort_by').val();
    alert(sort_by);
}
$(function() {
    var icons = {
      header: "ui-icon-circle-plus",
      activeHeader: "ui-icon-circle-minus"
    };
    $( "#accordion" ).accordion({
      icons: icons,
      active: false,
    collapsible: true,
        heightStyle: 'content'

   
    });
    $( "#toggle" ).button().click(function() {
      if ( $( "#accordion" ).accordion( "option", "icons" ) ) {
        $( "#accordion" ).accordion( "option", "icons", null );
      } else {
        $( "#accordion" ).accordion( "option", "icons", icons );
      }
    });
  });
  
$(document).ready(function() {
    
    $("#infoMessage").fadeTo(5000, 500).slideUp(500, function(){
          $('#infoMessage').remove();
          //$("#success-alert").alert('close');
    }); 
    
    $('.clickdiv').click(function(){
        //$(this).find('.hiders').toggle();
        $('.hiders').slideToggle();
        $('#minus').toggle();
        $('#plus').toggle();
    });
    
   
            
 });
 
</script>



<?php



?>


<div id="newtms-title">
    <img width="50" src="<?php echo base_url()?>images/title-icon.png"/>
    <span style="font-size:16px;"><?php echo $title;  ?></span>
    <?php 
    $user=  $this->session->userdata('user');  
    $user_role_id =$user->rid; 
    if($user_role_id!=3){ ?> 
     	<span style="float:right;"><a class="btn btn-default" href="company_add">Add Company </a> </span>
	<?php } ?>
</div>
<div class="clear"></div>
<div class="content-inner"> 
<div id="infoMessage">
    
    <?php if($this->session->flashdata('success-message')){ ?>
    
    <div class="alert alert-success" id="success-alert">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Success! </strong>
    <?php echo $this->session->flashdata('success-message');?>
    </div>    
    <?php } ?>
    
    <?php if($this->session->flashdata('warning-message')){ ?>
    
    <div class="alert alert-warning" id="warning-alert">
    <button type="button" class="close" data-dismiss="alert">x</button>
    <strong>Success! </strong>
    <?php echo $this->session->flashdata('warning-message');?>
    </div>    
    <?php } ?>
      
</div>



 
        
<div class="searchbox">
    <div class="clickdiv" style="background:#EBEBEB;padding: 5px;">
        <strong> <span> Search </span> 
        <span id="plus">+</span><span id="minus" style="display:none;">-</span></strong>
    </div> 
    <div class="hiders" style="display:none;">
        <div class="row"> 
            <?php
            $company_name = form_label('Company Name', 'company_name');
            $company_name .= form_input(array(
	              'name'        => 'company_name',
	              'id'          => 'company_name',
	              'value'       => isset($company_search_name)? $company_search_name:'',
	              'class'       => 'form-control',
                       'placeholder' => 'Company Name'

            ));            

            $options = array(
                  'company_name'  => 'Company Name',
                 'company_status'   => 'Company Status',
                 'id' => 'Latest',                  
            );
            $sort_by = 'company_name';
            $js = 'id="select_sort_by" onChange="this.form.submit();" class="form-control selectpicker1"';
            
            $company_sort = form_label('Sort By', 'company_sort');
            $company_sort .= form_dropdown('project_sort', $options, $sort_by, $js);
            
            echo form_open('company/company_list');          
           
            ?>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <?php echo '<div id="" class="">'. $company_name . '</div>'; ?>
            </div>
            <div class="col-xs-12 col-sm-6 col-md-6">
                <?php   //echo '<div id="project-wrapper" class="field-wrapper">'. $company_sort . '</div>'; ?>
                <?php   echo  $company_sort; ?>
            </div>
             <?php echo form_close(); ?>
           
        </div>
        
     </div>
</div>
            


<hr/>

   
<div class="row">
<div class="col-md-12">
<div id="company_list_view" class="">
<div id="accordion">	

    <?php foreach ($companys as $company){ ?>
    
            <h3 style="margin-top: 0px;">
                <?php echo $company->company_name; ?>
            </h3>
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-6">
        <table class="company table table-bordered">
            <tr>
                <td>Company Name </td>
                <td><?php echo $company->company_name; ?></td>
            </tr>
            <tr>
                <td>Company Description </td>
                <td> <?php echo $company->company_description; ?></td>
            </tr>
            <tr>
                <td>Company Status </td>
                <td><?php if($company->company_status==1) echo 'Open'; else if($company->company_status==2) echo 'Closed' ; else echo 'Undefined'; ?> </td>
            </tr>
        </table>
        </div>
        <div class="col-xs-10 col-sm-5 col-md-5">
             <?php
             //copmany project
             $this->db->select('`id`, `project_no`, `project_name`, `project_status`'); 
                $this->db->where('company_id', $company->id); 
				$this->db->order_by('project_name', 'ASC');              
				$company_project=  $this->db->get('project');
                
              //$company_project= $this->company_model->get_company_project($company->id);
              //$company_project_num=$company_project->num_rows;
              
              $company_project_list= $company_project->result();
			  //$company_project_num= $this->db->count_all_results();
              $company_project_num = count($company_project_list);
                
               if($company_project_num>0){
               
				$this->table->set_empty("");
                $this->table->set_caption('Company Project('.$company_project_num.')'); 
                //$this->table->set_caption('Projects'); 
                //$this->table->set_heading('Project No', $title, 'Status');
               
                foreach ($company_project_list as $project){                  

		$this->table->add_row(                       
			'<a href="'.  base_url().'project/project_detail/'.$project->id.'"><img width="25" src="'.base_url().'images/btn_detail_plus.png"/></a>',
                        $project->project_name,			
			$project->project_status==1?'Open':'Closed'
			); 
		}
              $tmpl3 = array ( 'table_open'  => '<table border="0" cellpadding="2" cellspacing="1" class="company_project table">' );
              $this->table->set_template($tmpl3);
              echo $this->table->generate();
              $this->table->clear();
               }else{
                   echo '<p>No Projects found</p>';
               }
              ?>
             <?php //if(isset($company_project_table)) { echo $company_project_table;} ?>  
            
         </div>
        <div class="col-xs-2 col-sm-1 col-md-1">
            
            <a href="<?php echo base_url(); ?>company/company_detail/<?php echo $company->id; ?>"> 
            <img width="50" src="<?php echo base_url(); ?>images/btn_detail_plus.png"/>
            </a>                
            
        </div>
        
        
    </div>
    
    <?php	} ?>	

</div>
</div>
</div> 
</div>
</div>
			
<script>			
jQuery(document).ready(function(){
    //$('.selectpicker1').selectpicker();
   // $('.selectpicker1').select2();
    
});
</script>
	