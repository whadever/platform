jQuery(document).ready(function(){
// // salary add form
    // ajax call: get basic salary by empid
    if (jQuery('#emp-add-form').length){
        jQuery('#compname-wrapper select').bind('change',function(){
            
            $.ajax({  
                url: window.mbsBaseUrl + "sub_company/subcompany_load_ajaxcall/"+jQuery('#compname-wrapper select').val(),  
                dataType: 'html',  
                type: 'GET',  
                // data: 'eid='+jQuery('#eid-wrapper select').val(),  
                success:     
                function(data){  
                 //console.log(data);
                 if(data){  
                     //jQuery('#subcompname-wrapper').append(data);
                     jQuery('#subcompname-wrapper select').empty();
                    jQuery('#subcompname-wrapper select').append(data);
                     
                   
                 }  
                }
               });            
        });        
    }

$('.monthly-no-of-working-days').change(function(){
  // product blur
  $this = $(this);   
  var eId = $this.attr('id');
  var productId = $('#'+eId).val();
  //alert(productId);
  // get product price
});
 jQuery('.monthly-no-of-working-days').trigger('change');
 
$('form#salary-add-monthly-form table tr td input').blur(function(){
    var workingDays = $(this).parents('tr').find('input.monthly-no-of-working-days').val();
    var salaryPerday = $(this).parents('tr').find('input.monthly-salary-per-day').val();
    var totalSalary= workingDays*salaryPerday;
    
    
    $(this).parents('tr').find('input.monthly-salary-total').val(totalSalary);
    
    
});
 
$('table.content-multiple-table tr td input.form-text,table.content-multiple-table tr td .form-select').change(function(){
  // product blur
  $this = $(this);   
  var eId = $this.attr('id');
  var productId = $('#'+eId).val();
  // get product price
  if (eId.indexOf('requisition-product-nid-nid') != -1){  
   if (productId != ''){   
    var price = getProductPrice(productId);
    $this.parents('tr').find('input:eq(1)').val(price);
    var qty = $this.parents('tr').find('input:eq(0)').val();
    var priceTotal = 0;
    if (qty != ''){
     priceTotal = price * qty;
     $this.parents('tr').find('input:eq(2)').val(formatNumber(priceTotal));     
    }
    // requisition total
    priceTotal = 0;
    $('table.content-multiple-table tbody tr').each(function(){
     var price =  $(this).find('td input:eq(2)').val();
     if(price !='') priceTotal = parseFloat(priceTotal) + parseFloat(price);     
    });
    // console.log(priceTotal);
    // put priceTotal value to Requisition total field
    $('input#edit-field-requisition-total-amount-0-value').val(formatNumber(priceTotal)); 
   }else{
    $this.parents('tr').find('input:eq(0)').val('');
    $this.parents('tr').find('input:eq(1)').val('');
    $this.parents('tr').find('input:eq(2)').val('');
    // calculate requisition total = all product total 
    priceTotal = 0;
    $('table.content-multiple-table tbody tr').each(function(){
     var price =  $(this).find('td input:eq(2)').val();
     if(price !='') priceTotal = parseFloat(priceTotal) + parseFloat(price);     
    });
    // console.log(priceTotal);
    // put priceTotal value to Requisition total field
    $('input#edit-field-requisition-total-amount-0-value').val(formatNumber(priceTotal));    
   }   
  }

 });
 
 });