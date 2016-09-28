<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css" type="text/css" media="screen" /> 
<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
<style>
	select{
		width: auto;
	}
	input.stage, .form{
		width: 80%;
	}
	input.list{
		width: 70%;
	}
	#frm input {
		border: 1px solid #aaa;
		margin: 4px 0;
	}
	.loading{
		visibility: hidden;
	}
	.btn-delete{
		cursor: pointer;
	}
	#save {
		background-color: #f9b800;
		color: white;
		float: right;
		margin-right: 101px;
		margin-top: 28px;
	}
	#frm .list {
		margin-left: 62px;
	}
	#overlay {
		background-color: #000;
		background-image: url("<?php echo base_url(); ?>images/ajax-loading.gif");
		background-position: 50% center;
		background-repeat: no-repeat;
		height: 100%;
		left: 0;
		opacity: 0.5;
		position: fixed;
		top: 0;
		width: 100%;
		z-index: 10000;
	}
	.task-delete {
		float: unset;
	}
</style>
<a class="" style="margin-bottom: 5px; display: block" href="<?php echo site_url('job/show_popup_menu'); ?>"><< main menu</a>
<div class="popup_title">
	<h2 class="popup_title2"><?php echo $form_info['name']; ?></h2>
</div>

<div class="Job_add row" style="min-height: 300px">
	<form action="<?php echo site_url('constructions/edit_form/'.$form_info['id']); ?>" method="post">
	<div id="frm" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Form Name</div>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
			<input required id="stage" class="form" type="text" name="name" value="<?php echo $form_info['name']; ?>">
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Stage</div>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9" id="stages">
			<a href="#" class="add_stage" style="float: right">+ Add Stage</a>
			<?php foreach($form_info['stages'] as $stage_id => $stage): ?>
				<ul>
					<input data-id="<?php echo $stage_id; ?>" type="text" class="stage" required="required" value="<?php echo $stage['name']; ?>"><img src="<?php echo base_url().'images/delete.png'; ?>" class="btn-delete stage-delete"  data-id="<?php echo $stage_id; ?>" >
					<br>
					<a href="#" class="add_task" data-stage-id="<?php echo $stage_id; ?>">+ Add Task</a>
					<?php foreach($stage['tasks'] as $task_id => $task): ?>
						<?php if($task_id): ?>
						<li>
							<input type="text" data-id="<?php echo $task_id; ?>" data-stage-id="<?php echo $stage_id; ?>" class="list" value="<?php echo $task; ?>"><img src="<?php echo base_url().'images/delete.png'; ?>" class="btn-delete task-delete" data-id="<?php echo $task_id; ?>" data-stage-id="<?php echo $stage_id; ?>" >
						</li>
						<?php endif; ?>
					<?php endforeach; ?>
				</ul>
			<?php endforeach; ?>

		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3"></div>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9" style="text-align: center" >
			<input type="submit" value="Submit" class="btn btn-default">

		</div>
	</div>
	</form>
</div>

	<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
	<script>
		var base_url = "<?php echo base_url();?>";
		var form_id = <?php echo $form_info['id']; ?>;

		/* add/update stage */
		$(document).ready(function () {
			/*adding new stage*/
			$("a.add_stage").click(
				function () {
					var overlay = jQuery('<div id="overlay"> </div>');
					overlay.appendTo(document.body);
					$.ajax(base_url+'constructions/update_form/'+form_id+'/stage/add',{
						success: function(data){
							$("#stages").append(
								$("<ul />")
									.append($("<input />", {
										type: "text",
										class: "stage",
										required: true,
										value: "New Stage",
										'data-id': data,
										id: 'st_'+data
									})).prop('defaultValue','New Stage').append($("<img />",{
										'src':'<?php echo base_url().'images/delete.png'; ?>','class':'btn-delete stage-delete', 'data-id': data
									})).append("<br>")
									.append($("<a />", {
										href: "#",
										class: "add_task",
										'data-stage-id': data
									}).html("+ Add Task"))
							);

							$('#st_'+data).select().focus();

							overlay.remove();
						}
					});

					return false;
				}
			);

			/*deleting a stage*/
			$("#stages").delegate(".stage-delete","click",function(){
				var overlay = jQuery('<div id="overlay"> </div>');
				overlay.appendTo(document.body);
				var stage_id = $(this).attr('data-id');
				var el = $(this);
				$.ajax(base_url+'constructions/update_form/'+form_id+'/stage/delete/'+stage_id,{
					success: function(data){
						el.parent("ul").remove();
						overlay.remove();
					}
				});

			});

			/*updating a stage*/
			$("#stages").delegate(".stage","blur",function(){
				if($(this).val() == ''){
					$(this).val($(this).prop('defaultValue'));
					return;
				}
				if($(this).val() != $(this).prop('defaultValue')){
					var stage_id = $(this).attr('data-id');
					var el = $(this);
					$.ajax(base_url+'constructions/update_form/'+form_id+'/stage/update/'+stage_id,{
						type: 'post',
						data: {name: el.val()},
						success: function(data){
							el.prop("defaultValue", el.val());
						}
					});
				}

			});

			/*adding new task*/
			$("#stages").delegate(".add_task","click",function(){
				var overlay = jQuery('<div id="overlay"> </div>');
				var el = $(this);
				overlay.appendTo(document.body);
				$.ajax(base_url+'constructions/update_form/'+form_id+'/task/add',{
					type: 'post',
					data:{stage_id: el.attr('data-stage-id')},
					success: function(data){
						el.parent("ul").append($("<li/>").append($("<input/>",{
							type: "text",
							class: "list",
							required: true,
							'data-id': data,
							'data-stage-id': el.attr('data-stage-id'),
							id: 'tsk_'+data,
							value: 'New Task'

						})).prop('defaultValue','New Task').append($("<img />",{
							'src':'<?php echo base_url().'images/delete.png'; ?>','class':'btn-delete task-delete', 'data-id': data, 'data-stage-id': el.attr('data-stage-id')
						})));

						$('#tsk_'+data).select().focus();

						overlay.remove();
					}
				});

				return false;

			});

			/*updating a task*/
			$("#stages").delegate(".list","blur",function(){
				if($(this).val() == ''){
					$(this).val($(this).prop('defaultValue'));
					return;
				}
				if($(this).val() != $(this).prop('defaultValue')){
					var task_id = $(this).attr('data-id');
					var el = $(this);
					$.ajax(base_url+'constructions/update_form/'+form_id+'/task/update/'+task_id,{
						type: 'post',
						data: {name: el.val(), stage_id: el.attr('data-stage-id')},
						success: function(data){
							el.prop("defaultValue", el.val());
						}
					});
				}

			});

			/*deleting a task*/
			$("#stages").delegate("li .task-delete","click",function(){
				var overlay = jQuery('<div id="overlay"> </div>');
				overlay.appendTo(document.body);
				var task_id = $(this).attr('data-id');
				var stage_id = $(this).attr('data-stage-id');
				var el = $(this);
				$.ajax(base_url+'constructions/update_form/'+form_id+'/task/delete/'+task_id,{
					type: 'post',
					data: {stage_id: stage_id},
					success: function(data){
						el.parent("ul").remove();
						overlay.remove();
					}
				});
				$(this).parent("li").remove();
			});




		});

	</script>