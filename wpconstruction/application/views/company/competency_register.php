<style xmlns="http://www.w3.org/1999/html">
    .field_label {
        font-weight: bold;
    }
    .notes {
        border: 1px solid;
        border-radius: 9px;
        margin-left: 15px;
        padding: 2px 8px;
    }
    .contact-name {
        color: black;
        font-weight: bold;
        margin: 0;
    }
    .contractor #btnSave{
        display: none;
    }
</style>
<div id="contact-detail" class="content-inner <?php echo $user_app_role; ?>">
    <?php
    $user = $this->session->userdata('user');
    $user_role_id = $user->rid;
    $this->breadcrumbs->push('Contact', 'contact/contact_list');
    $this->breadcrumbs->push($company->company_name, 'company/company_details/' . $company->id);
    $domain = $_SERVER["SERVER_NAME"];
    ?>
    <div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div>
    
    <div class="box cdetails">
        <div class="">
            <div class="col-xs-12 col-sm-12 col-md-1"></div>

            <div class="col-xs-12 col-sm-12 col-md-9">
				<h3>Competency register for <?php echo $company->company_name; ?> contacts.</h3>
			</div>
                <div class="row" style="margin-top: 20px">
                    <div class="col-xs-12 col-sm-10 col-md-10">
                        <?php foreach ($contacts as $contact): ?>

							<?php
								$contact_id = $contact['id'];
								$competency_register = $this->db->query("select * from contact_competency_register where contact_id = {$contact_id} limit 0,1")->row();
								$cr = (array)$competency_register;
							?>
							

                            <div class="col-xs-12 col-sm-12 col-md-12" style="margin-top: 20px;">
								<form name="competency_registration" action="<?php echo base_url() ?>company/save_competency_registration/<?php echo $contact['id'] ?>" method="post">
	                                <div class="col-xs-12 col-sm-12 col-md-12">
	                                    <a href="<?php echo base_url(); ?>contact/contact_details/<?php echo $contact['id']; ?>" >
	                                    	<h2 class="contact-name"><?php echo $contact['name']; ?></h2>
	                                    </a>
	                                    <h5 style="font-weight: bold; margin: 0"><?php echo $contact['title']; ?></h5>
										<br>
	                                </div>
	
									
									<div class="col-xs-12 col-sm-4 col-md-6" style="border-bottom:1px dotted #ccc; padding-bottom:10px">
					                    <label for="cr[company_induction]">Company Induction</label><input type="text" placeholder="" class="form-control" id="" value="<?php echo $cr['company_induction']; ?>" name="cr[company_induction]">
					                    <label for="cr[passport_id_number]">Site Safe Passport ID Number</label><input type="text" placeholder="" class="form-control" id="" value="<?php echo $cr['passport_id_number']; ?>" name="cr[passport_id_number]">
					                    <label for="cr[passport_expiry_date]">Site Safe Passport (Expiry Date)</label><input type="text" placeholder="" class="form-control datepicker" id="" value="<?php echo $cr['passport_expiry_date']; ?>" name="cr[passport_expiry_date]">
					                    <label for="cr[first_aid_course]">First Aid Course</label><input type="text" placeholder="" class="form-control" id="" value="<?php echo $cr['first_aid_course']; ?>" name="cr[first_aid_course]">
					                    <label for="cr[working_at_heights]">Working At Heights</label><input type="text" placeholder="" class="form-control" id="" value="<?php echo $cr['working_at_heights']; ?>" name="cr[working_at_heights]">
					                    <label for="cr[confined_spaces]">Confined Spaces</label><input type="text" placeholder="" class="form-control" id="" value="<?php echo $cr['confined_spaces']; ?>" name="cr[confined_spaces]">
					
					                </div>
					                <div class="col-xs-12 col-sm-4 col-md-6" style="border-bottom:1px dotted #ccc; padding-bottom:10px">
					                    <label for="cr[driver_licence_details]">Driver's Licence Details</label><input type="text" placeholder="" class="form-control" id="" value="<?php echo $cr['driver_licence_details']; ?>" name="cr[driver_licence_details]">
					                    <label for="cr[d_l_expiry_date]">Driver's Licence Expiry Date</label><input type="text" placeholder="" class="form-control datepicker" id="" value="<?php echo $cr['d_l_expiry_date']; ?>" name="cr[d_l_expiry_date]">
					                    <label for="cr[other]">Other</label><input type="text" placeholder="" class="form-control" id="" value="<?php echo $cr['other']; ?>" name="cr[other]">
					                    <label for="cr[no_of_years_in_job]">Number of Years in Job</label><input type="text" placeholder="" class="form-control" id="" value="<?php echo $cr['no_of_years_in_job']; ?>" name="cr[no_of_years_in_job]">
					                    <label for="cr[determined_competency_rating]">Determined Competency Rating <br>(Trainer, Competent, Require Supervision)</label><input type="text" placeholder="" class="form-control" id="" value="<?php echo $cr['determined_competency_rating']; ?>" name="cr[determined_competency_rating]">
										<input type="hidden" value="<?php echo $cr['id']; ?>" name="cr_id" >
										<input type="hidden" value="<?php echo $company->id; ?>" name="company_id" >
										<input type="submit" value="Save" class="btn btn-seconday" name="save" style="margin-top:5px" >
					                </div>
								</form>
                             </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            
        </div>

    </div>

    <script>
        var base_url = '<?php echo base_url(); ?>';
        var company_id = <?php echo $company->id; ?>;
        $(document).ready(function(){

            $("#btnSave").click(function(){
                $("#note").prop('disabled',true);
                $.ajax(base_url+'company/edit_note/'+company_id,{
                    method: 'post',
                    data:{
                        note: $("#note").val()
                    },
                    success:function(){
                        $("#note").prop('disabled',false);
                    }
                })
            })
        })
    </script>