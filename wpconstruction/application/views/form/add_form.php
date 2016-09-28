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
</style>
<a class="" style="margin-bottom: 5px; display: block" href="<?php echo site_url('job/show_popup_menu'); ?>"><< main menu</a>
<div class="popup_title">
	<h2 class="popup_title2">Add Form</h2>
</div>

<div class="Job_add row" style="min-height: 300px">
	<form action="<?php echo site_url('constructions/create_form'); ?>" method="post">
	<div id="frm" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Form Name</div>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
			<input required id="stage" class="form" type="text" name="name">
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Stage</div>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9" id="stages">
			<a href="#" class="add_stage" style="float: right">+ Add Stage</a>

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
		var stage = "";
		var tasks = {};

		/* add/update stage */
		$(document).ready(function () {
			$("a.add_stage").click(
				function () {
					$("#stages").append(
						$("<ul />")
							.append($("<input />", {
								name: "stage["+$(".stage").length+"]",
								type: "text",
								class: "stage",
								required: true,
								placeholder: "stage name",
								'data-index': $(".stage").length
						})).append($("<img />",{
								'src':'<?php echo base_url().'images/delete.png'; ?>','class':'btn-delete'
							})).append("<br>")
							.append($("<a />", {
								href: "#",
								class: "add_task"
							}).html("+ Add Task"))
					);
					return false;
				}
			);

			$("#stages").delegate(".btn-delete","click",function(){
				$(this).parent("ul").remove();
			});

			$("#stages").delegate("li .btn-delete","click",function(){
				$(this).parent("li").remove();
			});

			$("#stages").delegate(".add_task","click",function(){
				$(this).parent("ul").append($("<li/>").append($("<input/>",{
					type: "text",
					class: "list",
					name: "task["+$(this).parent("ul").find(".stage").attr('data-index')+"][]",
					placeholder: "task name",
					required: true

				})).append($("<img />",{
					'src':'<?php echo base_url().'images/delete.png'; ?>','class':'btn-delete'
				})));

				return false;

			})


		});
	</script>