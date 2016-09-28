<script>
    window.print();
</script>
  <div class="box-title"><h1>Development Information<?php //echo $title; ?> </h1></div>
 <div class="development-info-table"><?php if(isset($table)) { echo $table;	} ?> </div>               

 
<div class="box-title"><h3>Feature Photo</h3> </div>
<div style="min-height: 360px;">
    <img width="" height="" src="<?php echo base_url().'uploads/development/'.$feature_photo->filename?>"/>
</div>
           <?php //print_r($feature_photo); echo $feature_photo->filename; ?>    
           
            