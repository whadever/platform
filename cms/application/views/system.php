<script>
$(function(){ 
    $(document).on('focus', ".live_datepicker", function(){
        $(this).datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'dd-mm-yy',
			onClose: function(dateText, inst) 
			{
       			this.fixFocusIE = true;
       			this.focus();
   			}
        });
    });
});
</script>

<div class="system">

	<div class="row">
		
					<form action="<?php echo base_url(); ?>update_system/system_add" method="post">
				        <div class="col-xs-12 col-sm-8 col-md-8">
			                <label for="date">Date</label>
							<input type="text" class="live_datepicker" id="date" value="" name="date">
			            </div>

						<div class="col-xs-6 col-sm-2 col-md-2">
							<label for="">&nbsp;</label>
							<input type="submit" class="form-control" id="submit" value="Submit" name="submit">
			            </div>
					</form>
	
	</div>

</div>