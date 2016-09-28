<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
		</div>
	</div>
</div>
<?php echo form_open('templates/template_note/'.$this->uri->segment(3)); ?>
    <div class="row"> 
    	<div class="col-xs-12 col-sm-12 col-md-12">
    		<p>Name of the Template: <span style="font-size: 20px;"><?php echo $templates->job_name; ?></span></p>
    		<div class="form-group">
				<label for="note">Notes:</label>
				<textarea class="form-control" name="notes"><?php echo $templates->notes; ?></textarea>
			</div>
    	</div>
    	<div class="col-xs-12 col-sm-12 col-md-12">
        <?php 
            $attr_next = array(
              'name'	=> 'submit',
              'id'		=> 'next',
              'value'	=> 'Save',
              'class'	=> 'btn btn-danger pull-right"',
              'type'	=> 'submit',
            );
            echo form_submit($attr_next);
            echo '<a class="btn btn-danger pull-right" href="'.base_url().'templates/template_view/'.$this->uri->segment(3).'">Back</a>';
        ?>
        </div>
    </div>
<?php echo form_close(); ?>
