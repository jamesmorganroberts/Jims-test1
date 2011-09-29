// JavaScript Document



/*


$('form').live('submit', function() {
									
	alert('submit');						
	
	//$('#ajax_container').load('test.php?jim=class');
	
	//$.post("test.php", $(this).serialize());
	
	
	$.post('test.php', $(this).serialize(), function(data) {
    	
		alert(data);
		//$('#ajax_container').html(data);
  
	});

	return false;
	 
});



$('#form').live('submit', function() {
																	
																	
	var id = $('#id').val();


	$.ajax({
				 
   type: "POST",
   url: 'ajax_nt_company_details.php?id=' + id,
   data: $(this).serialize(),
   
	 success: function(msg){
     alert("Data Saved: " + msg);
		 $('#ajax_container').html(msg);
		 
   },
	 
	 error: function (request, status, error) {
        alert(request.responseText);
	 }
 	
	});

	return false;
	 
});


*/