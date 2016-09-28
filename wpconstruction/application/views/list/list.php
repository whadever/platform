<?php
foreach($list as $l):
?>
    <input class="list" type="text" data-id="<?php echo $l['id']; ?>" value="<?php echo $l['task_name']; ?>" />
    <img class="btn-delete" src="<?php echo base_url() . 'images/delete.png'; ?>"/>
    <img class="loading" src="<?php echo base_url() . 'images/ajax-saving.gif'; ?>"/>
<?php endforeach; ?>

<input placeholder="new task" class="list" type="text" data-id="">
<img class="btn-delete" src="<?php echo base_url() . 'images/delete.png'; ?>"/>
<img class="loading" src="<?php echo base_url() . 'images/ajax-saving.gif'; ?>"/>
