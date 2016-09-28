<style>
	.search_contact {
		border: 1px solid #ccc;
		border-radius: 5px;
		font-size: 12px;
		height: 33px;
		margin: 0 10px 0 0;
		padding: 6px;
		width: 90%;
	}
</style>
<script>


window.url = '<?php echo base_url(); ?>';

    $(document).ready(function() {
    
    
            
 });
 
</script>

<div id="all-title">
    <img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
    <span class="title-inner"><?php echo $title;  ?></span>
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
   
<div class="row">
	<div class="col-md-12">  
		<div id="contact_list_view">
			
			<div id="company_list">
				<?php if(isset($table)){echo $table;} ?>
			</div>
			

		</div>
	</div>
</div>  
              
</div>