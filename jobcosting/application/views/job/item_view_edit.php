<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title; ?></span>
		</div>
	</div>
</div>
<div class="row">&nbsp;</div>
<div class="row">
    <div class="col-xs-12 col-sm-3 col-md-3"></div>
    <div class="col-xs-12 col-sm-6 col-md-6">
        <table class="table table-striped">
            <thead>
              <tr>
                <th>Names</th>
                <th>Units</th>
                <th>Edit Items</th>
              </tr>
            </thead>
            <tbody>
            <?php
			$item_unit = array( '1'=>'Days', '2'=>'Hours', '3'=>'m2');
			foreach ($items as $item)
			{
              $id=$item['id'];
			  ?>
			  <tr>
                <td><?php echo $item['item_name']; ?></td>
                <td><?php echo $item_unit[$item['item_unit']]; ?></td>
                <td><?php echo anchor('items/item_edit/'.$id, 'Edit'); ?></td>
              </tr>
              <?php
			}
			?>
            </tbody>
          </table>
	<div class="col-xs-12 col-sm-3 col-md-3"></div>
</div>