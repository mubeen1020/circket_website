 

	 $( document ).on( 'focus', '.accoutns-options-form input', function(){
	        $( this ).attr( 'autocomplete', 'off' );
	  });

$('[href*="#Promocode"]').addClass("disabled");

 $(document).on("change", ".accoutns-options input[type='radio']", function(){
	 populateAcInfoData();
	 labelsUp(); 
         	 if($("#paypal").is(':checked')){
         		
 		         $(".input-group-addon").html("<i class='fa fa-usd' aria-hidden='true'></i>");
 		         $("#gatewayName").val("paypal");
 		         $(".beneficiaryName").html("Name");
 		         $(".accountNumber").html("Account Id");
 		         $(".bankIfscCode").hide();
 		         $(".publicKey").show();
 		         $(".secretKey").show();
 		         $(".publicKeySpan").html("Client Id"); 		        
         	 }
         	 
 	         if($("#razorpay").is(':checked')){
 	        	
 		         $(".input-group-addon").html("<i class='fa fa-usd' aria-hidden='true'></i>");
 		         $("#gatewayName").val("razorpay");
 		         $(".beneficiaryName").html("Beneficiary Name");
 		         $(".accountNumber").html("Account Number");
 		         $(".bankIfscCode").show();
 		         $(".publicKey").hide();
 		         $(".secretKey").hide();
 		         
 	         }
          
 	         if($("#stripe").is(':checked')){
 	        	
 		         $(".input-group-addon").html("<i class='fa fa-usd' aria-hidden='true'></i>");
 		         $("#gatewayName").val("stripe");
   		         $(".beneficiaryName").html("Name");
		         $(".accountNumber").html("Account Id");
 		         $(".bankIfscCode").hide();
 		         $(".publicKey").show();
 		         $(".secretKey").show(); 	
 		          $(".publicKeySpan").html("public Key");	        
 	         }
          
          
          });
//       promo code start and end date
 
 

 $(document).on("click", ".add-duplicate", function(){
	 
         	 if($("#paypal").is(':checked')){
         		
 		         $(".input-group-addon").html("<i class='fa fa-usd' aria-hidden='true'></i>");
 		        $(".beneficiaryName").html("Name");
		         $(".accountNumber").html("Account Id"); 	
		         $(".publicKey").html("public Key");
 		         $(".secretKey").html("secret Key");

 		         $(".bankIfscCode").hide();
 		         
         	 }
         	 
 	         if($("#razorpay").is(':checked')){
 	        	
 		         $(".input-group-addon").html("<i class='fa fa-inr' aria-hidden='true'></i>");
 		        $(".beneficiaryName").html("Beneficiary Name");
		         $(".accountNumber").html("Account Number");

 		         $(".bankIfscCode").show();
 		         
 	         }
          
 	         if($("#stripe").is(':checked')){
 	        	
 		         $(".input-group-addon").html("<i class='fa fa-usd' aria-hidden='true'></i>");
 		        $(".beneficiaryName").html("Name");
		         $(".accountNumber").html("Account Id"); 
		         $(".publicKey").html("public Key");
 		         $(".secretKey").html("secret Key");

 		         $(".bankIfscCode").hide();
 	         }
          
          
          });
//       promo code start and end date
 
 
 $(document).on("click", ".promocode-tab", function(){
	 
	 setTimeout(function(){ 
    	 fromtoDate(); 
    	 labelsUp(); 
		 disabledPercentageAmt();
    	    }, 1000);
    	 });
 

function fromtoDate(){
	
        $('.date_timepicker_start').datetimepicker({
        	format: 'MM/DD/YYYY'
        });
        $('.date_timepicker_end').datetimepicker({
            useCurrent: false ,//Important! See issue #1075
            format: 'MM/DD/YYYY'
        });
        $(".date_timepicker_start").on("dp.change", function (e) {
            $('.date_timepicker_end').data("DateTimePicker").minDate(e.date);
        });
        $("#datetimepicker7").on("dp.change", function (e) {
            $('#datetimepicker6').data("DateTimePicker").maxDate(e.date);
        });
   

        	  }
         
        	 fromtoDate();

        	 $('.add-duplicate').click(function(event) {
        	   setTimeout(function(){ 
        	 fromtoDate(); 
        	    }, 1000);
        	 });
        	 
        	 
//        Registration Fee Script
        	 
   	 
        	 $('#RegistrationFee').on('submit', function (e) {
        		  if (e.isDefaultPrevented()) {
        		    // handle the invalid form...
        		  } else {
        			  e.preventDefault();
        			 
        			   if($(".RegSeries").val()!="" && $(".RegAmount").val()!=""){
        				   $('[href*="#RegistrationFee"]').parent().removeClass('active');
        				   $("#RegistrationFee").removeClass('active in');
        				   $('[href*="#Promocode"]').parent().addClass('active');
        				   $("#Promocode").addClass('active in');
        				   $regSeries=$(".RegSeries").val();
        				   $regAmount=$(".RegAmount").val();
        				   $(".RegSeriesSelected").val($regSeries);
        				   $(".RegAmountValue").val($regAmount);
        				   $('[href*="#Promocode"]').css("");
        				   fromtoDate();
        				  
        						 labelsUp(); 
        						 disabledPercentageAmt();
        						 
        						 $("#registration-fee-success-alert").fadeTo(2000, 500).slideUp(500, function() {
        				  		      $("#registration-fee-success-alert").slideUp(500);
        				  		    });
        						
        			   }
//        			   else{
//        				   $(".RegSeries, .RegAmount").css("border", "1px solid #f00");
//        			   }
        		  }
        		}) 
        	 
//   $("#regNext").on("click", function(e){
//	   
//   });
   
   
   
   $(document).on("input", ".promoPercentage", function(){
	   if($(this).val()){
		   $(this).parents(".accoutns-options-form").find(".promoAmount").attr("disabled", "disabled");
	   }
	   else{
		   $(this).parents(".accoutns-options-form").find(".promoAmount").removeAttr( "disabled" );
	   }
	   if($(this).val()>100){
		   $(this).parents(".accoutns-options-form").find(".promoPercentageErr").html('Please enter lessthan or equal to 100%');
		 
	   }
	   else{
		   $(this).parents(".accoutns-options-form").find(".promoPercentageErr").html("");
		
	   }
	   var promocond=true;
	   $(".promoPercentage").each(function(){
		  
		   if($(this).val()>100){
			   promocond=false; 
		   }
	   });
	  
	   if(!promocond){
		   $(".promocodes-submit").attr("disabled", "disabled");
	   }
	   else{
		   $(".promocodes-submit").removeAttr( "disabled" );
	   }
	   
	   
	   
	   
   });
    
        	   	 
        	 
   
   $(document).on("input", ".promoAmount", function(){
	   if($(this).val()){
		   $(this).parents(".accoutns-options-form").find(".promoPercentage").attr("disabled", "disabled");
	   }
	   else{
		   $(this).parents(".accoutns-options-form").find(".promoPercentage").removeAttr( "disabled" );
	   }
	   $amountVal=parseInt($(".RegAmountValue").val());
	   if($(this).val()>$amountVal){
		  
		   $(this).parents(".accoutns-options-form").find(".promoAmountErr").html('Please enter lessthan or equal to '+ $amountVal);
	   }
	   else{
		   $(this).parents(".accoutns-options-form").find(".promoAmountErr").html('');
	   }
	   var promoAmountcond=true;
	   $(".promoAmount").each(function(){
		  
		   if($(this).val()>$amountVal){
			   promoAmountcond=false; 
		   }
	   });
	   
	   if(!promoAmountcond){
		   $(".promocodes-submit").attr("disabled", "disabled");
	   }
	   else{
		   $(".promocodes-submit").removeAttr( "disabled" );
	   }
	  
	  
	   
	   
	   
	   
   });
   
   
   $(document).on("click focus blur keyup",".common-box-body .form-control", function(e) {
     $(this)
       .parents(".form-group")
       .toggleClass("focused",e.type==="click"|| e.type === "focus" || e.type === "keyup" || this.value.length > 0);
   })
   .trigger("blur");

 $(document).on("click",".control-label", function(e) {
   $(this)
     .parents(".form-group")
     .toggleClass("focused");
 });
 
 function labelsUp(){
 $(".common-box-body .form-control").each(function(){
	if($(this).val()){
	
		$(this).parents(".form-group").addClass("focused");
		
	}
	else{
		$(this).parents(".form-group").removeClass("focused");
	}
 });
 }
 
 function disabledPercentageAmt(){
	 $(".promoPercentage").each(function(){
		 if($(this).val()>0){
			   $(this).parents(".accoutns-options-form").find(".promoAmount").attr("disabled", "disabled");
		   }
		   else{
			   $(this).parents(".accoutns-options-form").find(".promoAmount").removeAttr( "disabled" );
		   }
		 
	 });
	 $(".promoAmount").each(function(){
		 if($(this).val()>0){
			   $(this).parents(".accoutns-options-form").find(".promoPercentage").attr("disabled", "disabled");
		   }
		   else{
			   $(this).parents(".accoutns-options-form").find(".promoPercentage").removeAttr( "disabled" );
		   }
	 });
	
	
 }
 
 setTimeout(function(){ 
	 labelsUp(); 
	 }, 1000);


$(document).on("input", ".accountPercentage", function(){
	
	   if($(this).val()>100){
		   $(this).parents(".accoutns-options-form").find(".accountPercentageErr").html('Please enter lessthan or equal to 100%');
	   }
	   else{
		   $(this).parents(".accoutns-options-form").find(".accountPercentageErr").html("");
	   }
});  


 
 
  
   
   
   
        	 