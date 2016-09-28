<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
		</div>
	</div>
</div>
<?php echo form_open('templates/template_view/'.$this->uri->segment(3)); ?>


<script>
  $(function() {
    $( "#draggable li" ).draggable({
      //connectToSortable: "#sortable",
      helper: "clone",
      revert: "invalid"
    });
    $(".drop").droppable({		
		drop: function( event, ui ) {		
			if (ui.draggable.is('#draggable li')) {
				id = $('#draggable li.ui-draggable-dragging p').html();
				text = $('#draggable li.ui-draggable-dragging span').html();
				check_id = $('#droppable li#item_'+id+' input').val();
				if(id == check_id){
					alert('Item already added.');
				}else{
					$( this )
					.find( "#droppable" )
					.prepend( '<li id="item_'+id+'"><input type="hidden" name="items[]" value="'+id+'">'+text+'</li>' );
				}
			}
		}
	});
	//$( "ul, li" ).disableSelection();
  });
 	
 	function JobChange(){
		job_id = $("#job_name").val();
        if(job_id!=''){
			newurl = "<?php echo base_url(); ?>" + 'templates/template_view/' + job_id;
			window.location = newurl;
		}else{
			newurl = "<?php echo base_url(); ?>" + 'templates/template_view/';
			window.location = newurl;
		}
	}
  
</script>

    <div class="row create-template">
        <div class="col-xs-12 col-sm-12 col-md-2"></div>
        <div class="col-xs-12 col-sm-12 col-md-2">
            <label for="job_name">Template Name</label>
			<select onchange="JobChange();" id="job_name" class="form-control input-sm" required="1" name="job_name">
				<option value="">--Select a Template--</option>
				<?php 
				foreach($templates as $template){
					if($template->id==$this->uri->segment(3)){ $se = 'selected'; }else{ $se = ''; }
					echo '<option '.$se.' value="'.$template->id.'">'.$template->job_name.'</option>';
				}
				?>				
			</select>
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
            <div class="panel panel-default">
                <div class="panel-heading">Drag</div>
                <div class="panel-body">
	                <ul id="draggable">
	                	<?php 
						foreach($items as $item)
						{
						?>
							<li><p style="display: none;"><?php echo $item->id; ?></p><span><?php echo $item->item_name; ?></span></li>
	                    <?php
	                    }
						?>
					</ul>
                </div>
            </div>
            
        </div>
        <div class="col-xs-12 col-sm-6 col-md-3">
        	<div class="panel panel-default">
                <div class="panel-heading">Drop</div>
                <div class="panel-body drop">
                	<ul id="droppable">
                		<?php 
                		if(isset($template_items)){
							foreach($template_items as $item)
							{
						?>
							<li id="item_<?php echo $item->id; ?>"><input type="hidden" name="items[]" value="<?php echo $item->id; ?>"><?php echo $item->item_name; ?></li>
	                    <?php
	                    	}
	                    }
						?>
					</ul>
                </div>
            </div>
        </div>
        <div class="col-xs-12 col-sm-12 col-md-2"></div>
    </div>
    <div class="row"> 
    	<div class="col-xs-12 col-sm-12 col-md-2"></div>
        <div class="col-xs-12 col-sm-12 col-md-8">
        <?php 
            $attr_back = array(
              'name'	=> 'back',
              'id'		=> 'back',
              'value'	=> 'Back',
              'class'	=> 'btn btn-danger pull-right"',
              'type'	=> 'submit',
            ); 
            $attr_next = array(
              'name'	=> 'submit',
              'id'		=> 'next',
              'value'	=> 'Next',
              'class'	=> 'btn btn-danger pull-right"',
              'type'	=> 'submit',
            );
            echo form_submit($attr_next);
            echo '<a class="btn btn-danger pull-right" href="'.base_url().'templates/template">Back</a>';
        ?>
        </div>
    </div>
	
<?php echo form_close(); ?>

