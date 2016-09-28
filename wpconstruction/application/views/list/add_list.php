<link rel="stylesheet" href="<?php echo base_url();?>css/styles.css" type="text/css" media="screen" /> 
<link rel="stylesheet" href="<?php echo base_url();?>bootstrap/css/bootstrap.css" type="text/css" media="screen" />
<style>
	select{
		width: auto;
	}
	input#stage{
		width: 80%;
	}
	input.list{
		width: 70%;
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
</style>
<div class="popup_title">
	<h2 class="popup_title2">Add List</h2>
</div>

<div class="Job_add row" style="min-height: 300px">

	<div id="frm" class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">Stage</div>
		<div class="col-lg-9 col-md-9 col-sm-9 col-xs-9">
			<input id="stage" type="text" data-id=""><img class="loading"
														  src="<?php echo base_url() . 'images/ajax-saving.gif'; ?>"/>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 col-xs-3" style="margin-top: 5px">List(s)</div>
		<div id="lists" class="col-lg-9 col-md-9 col-sm-9 col-xs-9" style="margin-top: 5px">
			<input class="list" type="text" data-id="" disabled>
			<img class="loading" src="<?php echo base_url() . 'images/ajax-saving.gif'; ?>"/>
		</div>
		<input type="submit" class="btn" id="save" value="Save" style="background-color: #f9b800;color: white; float: right"></td>
		<div class="clear"></div>

		<div style="clear:both;"></div>
	</div>
</div>

	<script type="text/javascript" src="<?php echo base_url();?>js/jquery-1.10.1.min.js"></script>
	<script>
		var base_url = "<?php echo base_url();?>";
		var stage = "";
		var tasks = {};

		/* add/update stage */
		$(document).ready(function(){
			$("#stage").blur(function(){
				/*if stage is already added we will update, add otherwise*/
				var stage_name = $(this).val();
				var el = $(this);
				if(stage == ""){

					if(stage_name == "") return;

					$(this).siblings(".loading").css('visibility','visible');

					el.prop('disabled',true);

					$.ajax(base_url+'job/add_stage',{
						type: 'POST',
						data:{
							name: stage_name
						},
						success:function(data){
							if(data != '-1'){
								$("#stage").attr('data-id',data);
								$("#stage").siblings(".loading").css('visibility','hidden');
							}
							stage = stage_name;

							$(".list:first").prop('disabled',false).focus();

							el.prop('disabled',false);
						}
					})
				}else{
					if($(this).val() == "" || $(this).val() == stage){
						$(this).val(stage);
						return;
					}
					$(this).siblings(".loading").css('visibility','visible');

					el.prop('disabled',true);

					$.ajax(base_url+'job/update_stage/'+$(this).attr('data-id'),{
						type: 'POST',
						data:{
							name: stage_name
						},
						success:function(data){
							if(data != '-1'){
								$("#stage").siblings(".loading").css('visibility','hidden');
							}
							stage = stage_name;

							el.prop('disabled',false);
						}
					})
				}

			});

			/* add update lists */

			$("#frm").delegate('.list','keyup',function(){
				if($(this).val().length != 0 && $(".list").index($(this)) == $(".list").length-1){
					/* add a new text box below it */
					$("#lists").append($("<input />",{
						'type':'text','class':'list','data-id':''
					})).append($("<img />",{
						'src':'<?php echo base_url().'images/delete.png'; ?>','class':'btn-delete'
					})).append($("<img />",{
						'src':'<?php echo base_url().'images/ajax-saving.gif'; ?>','class':'loading'
					}));
				}
			});

			$("#frm").delegate('.list', 'blur',function(){

				var task_name = $(this).val();
				var el = $(this);
				if(stage == ""){
					alert('Stage name is empty.'); return;
				}

				if($(this).attr('data-id') == ""){

					if($(this).val() == '') return;

					$(this).nextAll(".loading:first").css('visibility','visible');
					el.prop('disabled',true);
					$.ajax(base_url+'job/add_task',{
						type: 'POST',
						data:{
							name: task_name,
							stage: $("#stage").attr('data-id')
						},
						success:function(data){
							if(data != '-1'){
								el.attr('data-id',data);
								el.nextAll(".loading:first").css('visibility','hidden');
							}
							tasks[data] = task_name;
							el.prop('disabled',false);
						}
					})
				}else{
					if($(this).val() == "" || $(this).val() == tasks[$(this).attr('data-id')]){
						$(this).val(tasks[$(this).attr('data-id')]);
						return;
					}
					$(this).nextAll(".loading:first").css('visibility','visible');
					el.prop('disabled',true);
					$.ajax(base_url+'job/update_task/'+$(this).attr('data-id'),{
						type: 'POST',
						data:{
							name: task_name
						},
						success:function(data){
							if(data != '-1'){
								el.nextAll(".loading:first").css('visibility','hidden');
							}
							tasks[el.attr('data-id')] = task_name;
							el.prop('disabled',false)
						}
					})
				}
			});

			/*delete task*/
			$("#frm").delegate('.btn-delete', 'click',function(){
				var id = $(this).prev().attr('data-id');
				if(id == '') return;
				var el = $(this);
				el.prev().remove();
				el.next().remove();
				el.remove();
				$.ajax(base_url+'job/delete_task/'+id,{
					success:function(data){
						if(data != '-1'){

						}
					}
				})
			});

			$("#save").click(function(){
				$("#frm input").each(function(){
					//$(this).trigger('blur');
					parent.$.fancybox.close();
				})
			})
		});
	</script>