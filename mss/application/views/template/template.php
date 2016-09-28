<?php
$ci = & get_instance();
$ci->load->model('schedule_model');
?>

<div class="page-title">
	<div class="row">
		<div class="col-xs-2 col-sm-2 col-md-1">
			<img width="" height="65" src="<?php echo base_url(); ?>/images/mss_template.png"  title="Manage Template" alt="Template"/>
		</div>
		<div class="col-xs-10 col-sm-10 col-md-7">
			<h4>Manage Template</h4>
			<p>Create customisable templates from your Products and Warranties.</p>
		</div>
	</div>
</div>

<div class="content">
<div class="row">	
	<div class="content-header">
		<div class="col-xs-6 col-sm-10 col-md-10">
			<div class="title"><?php echo $title; ?></div>
		</div>
		<div class="col-xs-6 col-sm-2 col-md-2">		
			<a data-toggle="modal" class="form-submit btn btn-info new-button" href="#AddTemplate"><img class="plus-icon" src="<?php echo base_url(); ?>images/plus_icon.png" />Add Template</a>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-xs-12 col-sm-12 col-md-12">
		<div class="content-body">
			<div class="table-responsive">
			<table class="table">
				<thead>
					<tr>
						<th width="80%" class="highlight">Templates</th>
						<th>Edit</th>
						<th>Remove</th>
					</tr>
				</thead>
				<tbody>
				<?php foreach($template_list as $template){ ?>
					<tr>
						<td><?php echo $template->template_name; ?></td>
						<td><a data-toggle="modal" class="" href="#EditTemplate_<?php echo $template->id; ?>">Edit</a></td>
						<td><a data-toggle="modal" class="" href="#DeteleTemplate_<?php echo $template->id; ?>">Remove</a></td>
					</tr>
				<?php } ?>
				</tbody>
			</table>
			</div>
		</div>
	</div>
</div>


<!-- MODAL Edit Template -->

<?php foreach($template_list as $template){ ?>

<div id="EditTemplate_<?php echo $template->id; ?>" class="modal hide fade template" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-header">
		<h3 id="myModalLabel">Edit Template</h3>
	</div>

	<div class="modal-body">	
	    <div class="row">
			<form method="POST" action="<?php echo base_url(); ?>template/template_update/<?php echo $template->id; ?>">
				
				<div class="col-xs-12 col-sm-12 col-md-12">
	      			<div class="form-group">
	      				<label for="template_name">Template Name:*</label>
	      				<input required="" class="form-control" type="text" name="template_name" id="template_name" value="<?php echo $template->template_name; ?>" />
	      			</div>
	      		</div>		     		
			
				<div class="col-xs-12 col-sm-12 col-md-12">
	      			<div class="form-group">
	      				<label for="note">Products:</label>
	      				 
						 <select name="product_id[]" multiple="multiple" placeholder="--Select Product(s)--" class="form-control fSelect"> 
						
						<?php
						$query = $ci->schedule_model->get_products();
						$rows = $query->result();
						foreach($rows as $row)
						{
							$query_p =$this->db->query("SELECT product_id FROM template_product where template_id=$template->id");
							$rows_p = $query_p->result();
							$default = '';
							for($a = 0; $a < count($rows_p); $a++)
							{
								if($rows_p[$a]->product_id == $row->id)
								{
									$default = 'selected="selected"';
									break;
								}
							}
						?>
						<option value="<?php echo '#'.$row->product_type_id.'#'.$row->id.'#'.$row->product_specifications; ?>" <?php echo $default; ?>><?php echo $row->product_name; ?></option>
						
						<?php
						}
						?>
						</select>
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-6 col-md-6">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<input class="btn create" type="submit" name="submit" value="Save" />
				</div>
	      		
			</form>
		</div>
			
	</div>
</div>

<div id="DeteleTemplate_<?php echo $template->id; ?>" aria-hidden="true" aria-labelledby="myModalLabel" role="dialog" tabindex="-1" class="modal hide fade">
	<div class="modal-header">
		<h3 id="myModalLabel">Delete Template</h3>
	</div>
	<div class="modal-body">
		<form accept-charset="utf-8" method="post" action="<?php echo base_url(); ?>template/template_delete/<?php echo $template->id; ?>">
			<div class="row">
				<div class="col-xs-12 col-sm-12 col-md-12">
					<p>Are you sure to delete this Template?</p>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button aria-hidden="true" data-dismiss="modal" class="btn cancel width100" type="button">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<input type="submit" class="btn create" value="Delete">
				</div>
			</div>
		</form>
	</div>
</div>

<?php } ?>


<!-- MODAL Add Template -->
<div id="AddTemplate" class="modal hide fade template" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">

	<div class="modal-header">
		<h3 id="myModalLabel">Create Template</h3>
	</div>

	<div class="modal-body">	
	    <div class="row">
			<form method="POST" action="<?php echo base_url(); ?>template/template_add">
				
				<div class="col-xs-12 col-sm-12 col-md-12">
	      			<div class="form-group">
	      				<label for="template_name">Template Name:*</label>
	      				<input required="" class="form-control" type="text" name="template_name" id="template_name" value="" />
	      			</div>
	      		</div>		     		
			
				<div class="col-xs-12 col-sm-12 col-md-12">
	      			<div class="form-group">
	      				<label for="note">Products:</label>
						<select name="product_id[]" multiple="multiple" placeholder="--Select Product(s)--" class="form-control fSelect"> 
	      				
						<?php
						$query = $ci->schedule_model->get_products();
						$rows = $query->result();
						foreach($rows as $row)
						{
						?>
						<option value="<?php echo '#'.$row->product_type_id.'#'.$row->id.'#'.$row->product_specifications; ?>"><?php echo $row->product_name; ?></option>
						
						<?php
						}
						?>
						</select>
	      			</div>
	      		</div>
	
				<div class="col-xs-12 col-sm-6 col-md-6">
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<button type="button" class="btn cancel width100" data-dismiss="modal" aria-hidden="true">Cancel</button>
				</div>
				<div class="col-xs-12 col-sm-3 col-md-3">
					<input class="btn create" type="submit" name="submit" value="Create" />
				</div>
	      		
			</form>
		</div>
			
	</div>
</div>


</div>


<?php
$p_type = '<option value="">--Refine by Category--</option>';
$query1 = $ci->schedule_model->get_product_type();
$rowss = $query1->result();
foreach($rowss as $rows)
{
$p_type .= '<option value="'.$rows->id.'">'.$rows->product_type_name.'</option>';
}
?>

<script>
	
	jQuery(document).ready(function(){
        $('.fSelect').fSelect({
			placeholder: '--Select Product(s)--',
			categories: '<?php echo $p_type; ?>'
		});
    });
</script>