// JavaScript Document




$(document).ready(function() {
			   

	var id = $('#id').val();
	//alert('onload');
	$('#ajax_container').load('ajax_nt_company_details.php?mode=null&id=' + id);			


});






    
  
$("#submit_button").live('click',function(event) {
											
	event.preventDefault();

	//alert('clicked');

	var id = $('#id').val();

	$.ajax({
				 
   type: "POST",
   url: 'ajax_nt_company_details.php?id=' + id,
   data: $('#form').serialize(),
   
	 success: function(msg){
     
		 //alert("Data Saved: " + msg);
		 $('#ajax_container').html(msg);
		 
   },
	 
	 error: function (request, status, error) {
        //alert(request.responseText);
	 }
 	
	});

	$("#submit_button").blur();
	
	return false;
});
  







