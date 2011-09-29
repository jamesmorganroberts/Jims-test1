// JavaScript Document

document.observe('dom:loaded', function() {

		// on load
		$("business_products").observe("click", load_business_products);
		load_business_products();
		
		
		if($("upload_images") != undefined) {
			
			$("upload_images").observe("click", load_upload_images);
			
		}
		
		
		

});


function load_upload_images(){
	
	//alert('load upload images');
		new Ajax.Request('adminviews/upload_images.php', {							 					 					
		onSuccess: function(transport){				
			var response = transport.responseText || "no response text";		
			$('ajax_container').innerHTML = transport.responseText;													
		},
		onFailure: function(){ 
			alert('An error has occurred, please try again');
		}				
	});
	
//-e load_upload_images
}



function load_business_products(){
	
	
	
	new Ajax.Request('adminviews/business_products.php', {							 					 					
		onSuccess: function(transport){				
			var response = transport.responseText || "no response text";		
			$('ajax_container').innerHTML = transport.responseText;													
		},
		onFailure: function(){ 
			alert('An error has occurred, please try again');
		}				
	});

	
//-e load_business_products
}