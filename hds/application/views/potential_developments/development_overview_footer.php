<div class="clear"></div>

	<div class="overview-area-footer">	
			<div class="container">

				<div class="sidebar-block-bottom">
        			<div class="sidebar-block-bottom-left"> <?php  date_default_timezone_set('NZ'); echo date("h:i a", time()); ?></div>
       
        			<div class="sidebar-block-bottom-right"><?php echo date('d.m.Y', time()); echo '<br/>'; $today = getdate(); echo $today['weekday']; ?></div>
        
    			</div>

			</div>
	</div>

</div>

</div>
	 

   </div>

</body>

</html>