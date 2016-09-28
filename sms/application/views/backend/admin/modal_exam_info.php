<?php 
$edit_data		=	$this->db->get_where('sms_exam' , array('exam_id' => $param2) )->result_array();
foreach ( $edit_data as $row):
?>

<div class="profile-env">
	
	
	<header>

		<div class="col-sm-9">
			
			<h2>Exam Information</h2>
			
		</div>
	</header>

	<section class="profile-info-tabs">
		
		<div class="row">
			
			<div class="">
            		<br>
                <table class="table table-bordered">
                
                    <tr>
                        <td>Name</td>
                        <td><b><?php echo $row['name'];?></b></td>
                    </tr>

                    <tr>
                        <td><?php echo get_phrase('class'); ?></td>
                        <td><b><?php echo $this->crud_model->get_class_name($row['class_id']);?></b></td>
                    </tr>
               
                
                 
                    <tr>
                        <td>Date</td>
                        <td><b><?php echo $row['date'];?></b></td>
                    </tr>
                
                
                
                    <tr>
                        <td>Category</td>
                        <td><b><?php echo $row['category'];?></b></td>
                    </tr>
           
              
                    
                    <tr>
                        <td>Comment</td>
                        <td><b><?php echo $row['comment'];?></b></td>
                    </tr>
             
              
                    <tr>
                        <td>Recurring</td>
                        <td><b><?php echo $row['recurring_yes_no'];?><?php if($row['recurring']!=''){ echo ' | '.$row['recurring']; }?></b></td>
                    </tr>
                
                
                    <tr>
                        <td>Document</td>
                        <td><b><?php echo $row['document'];?></b>
                        </td>
                    </tr>
                
                    
            	</table>

            </div>
        </div>
    </section>
</div>
<?php endforeach;?>