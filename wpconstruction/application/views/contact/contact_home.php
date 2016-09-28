<style>
	select{
		font-size: 14px;
		font-weight: bold;
	}
	.task_list {
		background-color: white;
		font-size: 18px;
		margin-top: 12px;
	}
	.task_list tbody td {
		font-size: 14px;
	}
	.task_list td {
		padding: 7px;
	}
	.task_list tr:nth-child(2n) {
		background-color: #e5e4e2;
	}
	.task_note {
		background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
		border: medium none;
		width: 95%;
	}
	.task_note:focus{
		border: 1px black solid;
	}
	img.loading{
		visibility: hidden;
	}
</style>


  <?php echo $maincontent; ?>
<div class="clear"></div>