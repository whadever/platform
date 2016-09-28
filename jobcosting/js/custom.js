jQuery(document).ready(function(){
    $('#edit-no-of-working-days,#edit-basic-salary,#edit-overtime,#edit-bonus').bind('keyup',function(){
        calculate_salary();
    });
    
    // salary add form
    // ajax call: get basic salary by empid
    if (jQuery('#salary-add-form').length){
        jQuery('#eid-wrapper select').bind('change',function(){
            $.ajax({  
                url: window.mbsBaseUrl + "employee_profile/get_basic_salary_by_empid/"+jQuery('#eid-wrapper select').val(),  
                dataType: 'json',  
                type: 'GET',  
                // data: 'eid='+jQuery('#eid-wrapper select').val(),  
                success:     
                function(data){  
                // console.log(data);
                 if(data){  
                     jQuery('#edit-basic-salary').val(data['basic_salary']);
                 }  
                }
               });            
        });        
    }
    
    
    
    // load salary field on window load 
    jQuery('#eid-wrapper select').trigger('change');
    
    // add calendar in salaly add form
    $(function() {
      $( "#edit-salary-paidfor-month1" ).datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy'
      });
    }); 
    $(function(){
        options = {
        pattern: 'mm-yyyy', // Default is 'mm/yyyy' and separator char is not mandatory
        selectedYear: 2014,
        startYear: 2008,
        finalYear: 2020,
        monthNames: ['Jan', 'Fev', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Dec']
        };
         $('#edit-salary-paidfor-month').monthpicker(options);
        
    });
   
    // add calendar in Request add form
    $(function() {
      $( "#edit-estimated_completion, #edit-project_date, #edit-company_date").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: 'dd-mm-yy'
      });
    });     
    
    
    // add calendar in leave add form
    // timepicker:false,
    // datepicker:false,
    $(function() {
      $( "#edit-leave-start-date,#edit-leave-end-date" ).datetimepicker({
          format:'d-m-Y H:i'
      });
    });     
    
    
    function calculate_salary(){
        var noOfWorkingDays = parseFloat($('#edit-no-of-working-days').val()) ;
        var basicSalary = parseFloat($('#edit-basic-salary').val());
        var overtimeHour = parseFloat($('#edit-overtime').val());
        var bonus = parseFloat($('#edit-bonus').val());
        var salaryPerDay = 0;
        var salaryTotal = 0;
        var overtimeAmount = 0;
        var netSalary = 0;
        
        // salary per day = basic salary / 30
        if (basicSalary > 0){
            salaryPerDay = basicSalary / 30;
            salaryPerDay = salaryPerDay.toFixed(2);
            //console.log(salaryPerDay);
        }
        
        
        if (noOfWorkingDays > 0 && salaryPerDay > 0){
            salaryTotal = salaryPerDay * noOfWorkingDays;
            salaryTotal = salaryTotal.toFixed(2);
            //console.log(salaryTotal);
        }
        
        // overtime amount =  basic salary / (30 x 8 hours ) x overtime hours
        if (noOfWorkingDays > 0 && salaryPerDay > 0 && overtimeHour > 0 ){
            overtimeAmount = basicSalary / (30*8) * overtimeHour;
            overtimeAmount = overtimeAmount.toFixed(2);
            //console.log(overtimeAmount);
        }        
        
        console.log('basic='+basicSalary + ',overtime='+overtimeAmount + ',bonus='+bonus);
        
        // net salary = (basic salary+overtime+bonus) - monthly loan
        if (basicSalary > 0 && overtimeAmount > 0 && bonus > 0 ){
            netSalary = parseFloat(basicSalary) + parseFloat(overtimeAmount) + parseFloat(bonus);
            netSalary = netSalary.toFixed(2);
            console.log(netSalary);
        }          
        
         $('#edit-salary-per-day').val(salaryPerDay);
         $('#edit-salary-total').val(salaryTotal);
         $('#edit-overtime-amount').val(overtimeAmount);
         $('#edit-net-salary').val(netSalary);
    }
    
    $('.selectpicker').selectpicker();
    //$('.selectpicker1').selectlist();
    //$('.selectpicker1').selectize();
    //$('.selectpicker1').select2();
    
    
});
  
	$(document).ready(function() {
             $(".fancybox").fancybox({
		'transitionIn'	:'fade',
		'transitionOut'	:'fade',
		'speedIn'	:300, 
		'speedOut'	:200, 
		'overlayShow'	:false
	});       
		
	});
        