

<?php
	$form_attributes = array('class' => 'company-add-form', 'id' => 'entry-form','method'=>'post');

	$id = form_hidden('id', isset($company->id) ? $company->id : '');
        
       
        if(isset($company->id)){
            $company_no = form_label('Company Number', 'company_no');
            $company_no .= form_input(array(
	              'name'        => 'company_no',
	              'id'          => 'edit-company_no',
	              'value'       => isset($company->id) ? $company->id : '',
	              'class'       => 'form-control',
                      'placeholder'=>'00001',
                      'readonly'=>'true'  

                ));            

        }else{

            $company_no = form_label('Company Id', 'company_no');
            $company_no .= form_input(array(
	              'name'        => 'company_no',
	              'id'          => 'edit-company_no',
	              'value'       => isset($company->id) ? $company->id : '',
	              'class'       => 'form-control',
                'placeholder'=>'00001',
                  'readonly'=>'true'  
                ));             

        }

        
   
        
        
	

	$company_name = form_label('', 'company_name');
	$company_name .= form_input(array(
	              'name'        => 'company_name',
	              'id'          => 'edit-company_name',
	              'value'       => isset($company->company_name) ? $company->company_name : '',
	              'class'       => 'form-control input-lg',
                      'placeholder'=>'Enter Company Name',
                      'required'    => TRUE
	));

	$company_description = form_label('Description', 'company_description');
	$company_description .= form_textarea(array(
	              'name'        => 'company_description',
	              'id'          => 'edit-company_description',
	              'value'       => isset($company->company_description) ? $company->company_description : '',
	              'class'       => 'form-control',
             'rows'   => '8',
              'cols'        => '100',
              'style'       => 'width:100%',
                        'required'    => TRUE

	));

	
	$selected = isset($company->company_status) ? $company->company_status : 1;
	
	$status_options = array(
                  
                  '1'  => 'Open',
                  '2'    => 'Closed',             
                 
                );
        $company_status = form_label('Status', 'company_status');
        $status_js= 'class="form-control selectpicker"';
        $company_status .= form_dropdown('company_status', $status_options, $selected, $status_js);

        $company_date = form_label('Company Date', 'company_date');
	$company_date .= form_input(array(
	              'name'        => 'company_date',
	              'id'          => 'edit-company_date',
	              'value'       => isset($company->company_date) ? $company->company_date : '',
	              'class'       => 'form-control',
                      'placeholder'=>'21-01-2005',
                      'required'    => TRUE
	));
      
	
       
        $submit = form_label(' ', 'submit');
	$submit .= form_submit(array(
	              'name'        => 'submit',
	              'id'          => 'edit-submit',
	              'value'       => 'Save',
	              'class'       => 'btn btn-default',
	              'type'        => 'submit',
                  'onclick'     => 'checkEmail();',
	));
?>

<div id="all-title">
	<div class="row">
		<div class="col-md-12">
			<img width="35" src="<?php echo base_url()?>images/title-icon.png"/>
			<span class="title-inner"><?php echo $title;  ?></span>
			<input type="button" value="Back" class="btn btn-default add-button" onclick="history.go(-1);"/>
		</div>
	</div>
</div>
<div class="clear"></div>
 <?php   
            
	$this->breadcrumbs->push('Company', 'company/company_list');
        if(isset($company->id)){
            $this->breadcrumbs->push($company->company_name, 'company/company_detail/'.$company->id);
            $this->breadcrumbs->push('Modify Company', 'company/company_update/'.$company->id);
        }else{
            $this->breadcrumbs->push('Company Add', 'company/company_add/');
        }
	//echo $this->breadcrumbs->show();
    ?>
    <div class="breadcrumb-box"> <?php echo $this->breadcrumbs->show(); ?></div> 

<div id="company_add_edit_page" class="content-inner">
    
<?php
	echo '<div id="" class="" style="color:red;">'.validation_errors(). '</div>';
	echo form_open_multipart($action, $form_attributes);
	//echo form_fieldset('',array('class'=>"comp-add-fieldset"));
	echo '<div id="sid-wrapper" class="field-wrapper">'. $id . '</div>';
        
?>


    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-3"><h3>Company Name : </h3></div>
        <div class="col-xs-12 col-sm-8 col-md-5"><?php echo $company_name ; ?></div>
        <div class="col-md-4"></div>
    </div>
    <p>&nbsp;</p>
    <div class="row">
        <div class="col-xs-12 col-sm-4 col-md-4"><?php echo $company_no ; ?></div>        
        <div class="col-xs-12 col-sm-4 col-md-4"><?php echo $company_status ; ?></div>
        <div class="col-md-4"></div>
    </div>
    <p>&nbsp;</p>
    <div class="row">
        <div class="col-xs-12 col-sm-12 col-md-12"><?php echo $company_description ; ?></div>        
    </div>
    <p>&nbsp;</p>
    <div class="row">
        <div class="col-xs-9 col-sm-10 col-md-11"></div>
        <div class="col-xs-3 col-sm-2 col-md-1"><?php echo $submit ; ?></div>        
    </div>


<?php
	//echo form_fieldset_close(); 
	echo form_close();
?>
</div>