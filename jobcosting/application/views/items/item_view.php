<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/bootstrap-select.css" />
<script type="text/javascript" src="<?php echo base_url();?>/js/bootstrap-select.js"></script>

<div id="all-title">
	<div class="row">
		<div class="col-xs-12 col-sm-4 col-md-4">
        	<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
        </div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-sm-3 col-md-3"></div>
	<div class="col-xs-12 col-sm-6 col-md-6 text-right">
		<a class="btn btn-danger pull-right" href="<?php echo base_url() ?>items/item_create">Create Item</a>
		<!--<a data-toggle="modal" href="#LoadTempalte" class="btn btn-danger pull-right">Load Key Tasks from Construction System</a>-->
	</div>
</div>
<div class="row">&nbsp;</div>
<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3"></div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <table class="table table-bordered table-striped">
            <thead>
              <tr>
              	<th>#</th>
                <th>Names</th>
                <th>Units</th>
                <th>Price</th>
                <th>Edit Items</th>
              </tr>
            </thead>
            <tbody>
            <?php
			$item_unit = array( '1'=>'Days', '2'=>'Hours', '3'=>'m2', '4'=>'Units', '5'=>'Dollars');
			$i = 1;
			foreach ($items as $item)
			{
              $id=$item->id;
			  ?>
			  <tr>
                <td><?php echo $id; ?></td>
                <td><?php echo $item->item_name; ?></td>
                <td><?php echo $item_unit[$item->item_unit]; ?></td>
                <td><?php echo $item->item_price; ?></td>
                <td>
                    <a onclick="return confirm('Are you sure want to delete this Item?');" href="<?php echo base_url()?>items/item_delete/<?php echo $id;?>"><img class="edit_icon" style="margin-left:5px;" src="<?php echo base_url(); ?>images/delete-icon.png" width="15px"/></a>
                    <a href="<?php echo base_url()?>items/item_edit/<?php echo $id;?>"><img class="edit_icon" style="margin-left:5px;" src="<?php echo base_url(); ?>images/job_edit.png" width="15px"/></a>
                	
                </td>
              </tr>
              <?php
              $i++;
			}
			?>
            </tbody>
          </table>
	</div>
    <div class="col-xs-12 col-sm-3 col-md-3"></div>
</div>
<!--
<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3"></div>
    <div class="col-xs-12 col-sm-6 col-md-6">
	<?php
	echo '<a class="btn btn-danger pull-right" href="'.base_url().'items/item">Back</a>';  
	?>
	</div>
	<div class="col-xs-12 col-sm-3 col-md-3"></div>
</div>
<div class="row">&nbsp;</div>
-->

<!-- MODAL Load Template -->
<div id="LoadTempalte" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<form method="POST" action="<?php echo base_url(); ?>items/load_key_task">
		<div class="modal-header">
			<h3 id="myModalLabel">Template List</h3>
		</div>
	
		<div class="modal-body">	
			<div class="row">		
				<div class="col-xs-12 col-sm-12 col-md-12">
                  	<div class="form-group">
                  		<label for="client_id">Template:*</label>
                  		<select multiple="" required="" name="template_id[]" id="client_add" class="form-control multiselectbox">
							<option style="display: none;" value="">--Select a Tempalte--</option>
							<?php
							$user = $this->session->userdata('user');
							$wp_company_id = $user->company_id;
							
							$this->db->where('wp_company_id',$wp_company_id);
							$rows = $this->db->get('construction_template')->result();
							foreach($rows as $row)
							{
								$this->db->where('template_id',$row->id);
								$this->db->where('type_of_task','key_task');
								$key = $this->db->get('construction_template_task')->row();
								if($key):
							?>
							<option value="<?php echo $row->id; ?>"><?php echo $row->template_name; ?></option>
							<?php
								endif;
							}
							?>
						</select>

					</div>
                </div>


				<label for="date_issued"></label>
				<div class="col-xs-6 col-sm-12 col-md-12">
					<input class="btn create width100 pull-right" type="submit" name="submit" value="OK" />
					<button style="margin-right: 10px;" type="button" class="btn cancel width100 pull-right" data-dismiss="modal" aria-hidden="true">Cancel</button>
					
				</div>
			</div>			
		</div>	    
	</form>
</div>
<div class="content-inner"> 
<div class="row">
    <div class="col-md-12"> 
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
    </div>
</div>


<script type="text/javascript">
	$(document).ready(function() {
		$('.multiselectbox').selectpicker();
	});
 </script>