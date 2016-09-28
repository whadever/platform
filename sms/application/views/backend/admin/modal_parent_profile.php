<?php 
	$data = $this->db->get_where('parent' , array('parent_id' => $param2))->result_array();
	foreach ($data as $row):
?>

<div class="profile-env">
	
	<header class="row">
		
		<div class="col-sm-3">
			
			<a href="#" class="profile-picture">
				<img src="<?php echo $this->crud_model->get_image_url('parent' , $row['parent_id']);?>" 
                	class="img-responsive img-circle" />
			</a>
			
		</div>
		
	</header>
	
	<section class="profile-info-tabs">
		
		<div class="row">
			
			<div class="">
            		<br>
                <table class="table table-bordered">
                
                    <?php if($row['name'] != ''):?>
                    <tr>
                        <td>Name</td>
                        <td><b><?php echo $row['name'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['children'] != ''):?>
                    <tr>
                        <td>Children</td>
                        <td><b><?php echo $row['children'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['email'] != ''):?>
                    <tr>
                        <td>Email</td>
                        <td><b><?php echo $row['email'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                
                    <?php if($row['phone'] != ''):?>
                    <tr>
                        <td>Phone</td>
                        <td><b><?php echo $row['phone'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                    <?php if($row['address'] != ''):?>
                    <tr>
                        <td>Address</td>
                        <td><b><?php echo $row['address'];?></b>
                        </td>
                    </tr>
                    <?php endif;?>
                    
                    <?php if($row['profession'] != ''):?>
                    <tr>
                        <td>Profession</td>
                        <td><b><?php echo $row['profession'];?></b></td>
                    </tr>
                    <?php endif;?>
                
                    
                </table>
			</div>
		</div>		
	</section>
	
	
	
</div>


<?php endforeach;?>