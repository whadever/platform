<?php if(isset($massage)) echo $message;  ?>



<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
			<input type="button" value="Back" class="btn btn-default add-button" onclick="history.go(-1);"/>
		</div>
	</div>
</div>


<div class="content-inner">

            <div class="row">
	            <div class="col-md-12 margin-bottom">
	                <div class="total-task-count">
	                    <h2>Download File </h2>
                            <?php 
                                echo $filenotfound;
                            ?>
	                </div>
	            </div>
            </div>
   
		    
</div>





