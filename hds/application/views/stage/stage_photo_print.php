<script>
    window.print();
</script>
  <div class="box-title">Stage Photo <?php //echo $title; ?> </div>
 <div class="development-info-table"><?php if(isset($table)) { echo $table;	} ?> </div>          

 

<div style="min-height: 360px;">
    <img width="" height="" src="<?php echo base_url().'uploads/development/'.$photo->filename?>"/>
</div>
         
                        