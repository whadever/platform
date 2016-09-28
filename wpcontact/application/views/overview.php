<?php if(isset($massage)) echo $message;  ?>

<?php 
$user_id = $user->uid;
$role_id = $user->rid;
if($role_id==2){
    $manager_id = $user_id;
    $developer_id =0;
}
else if($role_id==3){
    $manager_id =0;
    $developer_id = $user_id;
}
else{
    $manager_id =0;
    $developer_id = 0;
}

?>

<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
		</div>
	</div>
</div>

<div class="content-inner">
   
		    <div class="row">

		      <div class="col-md-4 margin-bottom">
		          <div class="overview-request-box">
		            <h2 align="center">New Company(s) <span class="request-count-color"><?php echo count($new_company_list); ?></span></h2>
		            <div class="overview-request-list">
						<table>                
	                    <?php
	                    if(isset($new_company_list)){
	                    foreach($new_company_list as $company){
	                        echo '<tr>';
	                        echo '<td>';
	                        echo '<h4>'.$company->company_name.'</h4>';
	                        echo date("Y-m-d H:i:s", strtotime($company->created));
	                        echo '</td>';

							//echo '<td>';
							//echo '<a style="float:right;margin-left:10px;" href="'.base_url().'request/request_detail/'.$request->id.'"><img src="'.base_url().'images/icon_search.png" /></a>';
							//echo '</td>';

	                       echo '</tr>';
	                     }
	                    }
	                    ?>
	                    </table>
		            </div>
		        </div>
		      </div>

			  <div class="col-md-4 margin-bottom">
		         <div class="overview-request-box">
		             <h2 align="center">New Contact(s) <span class="request-count-color"><?php echo count($new_contact_list); ?></span></h2> 
		             <div class="overview-request-list">
						<table>                
				          <?php
				          if(isset($new_contact_list)){
				          foreach($new_contact_list as $contact){
				              echo '<tr>';
				              echo '<td>';
				              echo '<h4>'.$contact->contact_first_name.' '.$contact->contact_last_name.'</h4>';
				              echo date("Y-m-d H:i:s", strtotime($contact->created));
				              
				              echo '</td>';

								echo '<td>';
								echo '<a style="float:right;margin-left:10px;" href="'.base_url().'contact/contact_details/'.$contact->id.'"><img src="'.base_url().'images/icon_search.png" /></a>';
								echo '</td>';

				             echo '</tr>';
				           }
				          }
				          ?>
				         </table>
					 </div>
		         </div>
		      </div>
		      
		      <div class="col-md-4 margin-bottom">
		          <div class="overview-request-box">
		              <h2 align="center">New Categories <span class="request-count-color"><?php echo count($new_category_list); ?></span></h2>
		              <div class="overview-request-list">
						<table>                
				          <?php
				          if(isset($new_category_list)){
				          foreach($new_category_list as $category){
				              echo '<tr>';
				              echo '<td>';
				              echo '<h4>'.$category->category_name.'</h4>';
				              echo date("Y-m-d H:i:s", strtotime($category->created));
				              echo '</td>';
				             
								//echo '<td>';
								//echo '<a style="float:right;margin-left:10px;" href="'.base_url().'request/request_detail/'.$request->id.'"><img src="'.base_url().'images/icon_search.png" /></a>';
								//echo '</td>';

				             echo '</tr>';
				           }
				          }
				          ?>
				         </table>
					  </div>
		         </div>
		      </div>
		
				
        
   		 </div>
</div>



<style>
	.margin-bottom {
		margin-bottom: 20px;
	}
	.total-task-count {
	    background: #ebebeb;
	    border: 3px solid #d7d7d7;
	    border-radius: 5px;
	    padding: 0 10px;	
	}
    .overview-request-box{
        background: #fff; 
        border:2px solid #ebebeb;
    }
    .overview-request-list{
        height: 300px;
        overflow: auto;
    }
	.overview-request-box > h2, .total-task-count > h2 {
	    background: #ebebeb;
	    margin: 0;
	    padding: 12px 5px;
		color: #000;
	}
	.request-count-color {
	    color: #cc1618;
	}
	.overview-request-list h4 {
	    margin: 0 0 5px;
	}
	.overview-request-list table tbody td:last-child {
	    border-right: 0px solid #ebebeb;
	}
	.overview-request-list table tbody td {
	    border-bottom: 1px solid #ebebeb;
		padding: 5px 10px;
	}
	.overview-request-list table {
	    border: 0px solid #ebebeb;
	}
</style>

