// JavaScript Document


document.observe('dom:loaded', function() {

		$("submit_button").observe("click", validate);
		$('error_box').hide();

});







function validate(){
	
	//alert('validate');
		
	$('form').disabled = true;
		
	new Ajax.Request('manage_validate.php', {
									 					 
					parameters: $('form').serialize(),
					
					onSuccess: function(transport){
						
						var response = transport.responseText || "no response text";
						//alert("Success! \n\n" + response);
						
						var json = transport.responseText.evalJSON(true);
						
						if(json.errormsg == "") {
							
							$('form').submit();
							
						}
						else{
							
							//alert('json' + json.errormsg);
							$('error_box').innerHTML = json.errormsg;	
							$('error_box').show();
							$('msg_box').hide();
						
						}
											
						
					},
					onFailure: function(){ 
						alert('An error has occurred, please try again');
					}
					
	});

}




/*

new Ajax.Request('manage_validate.php', {
					parameters: $('form3079_' + bid).serialize(), 
					onSuccess: function(transport) {

						var json = transport.responseText.evalJSON(true);

						// Remove highlighted errors
						$('error_box_3079_' + bid).hide();
						var highlighted = $$('input.errorfield');
						for (var index = 0; index < highlighted.length; ++index) {
							var arr = highlighted[index];
							if($(arr)) {
								$(arr).removeClassName('errorfield');  					
							}
						}				

						if(json.errormsg == "") {

							// All was well!
							$('form3079_' + bid).submit();

						} else {

							// Highlight any new errors		
							$('error_box_3079_' + bid).innerHTML = json.errormsg;				
							$('error_box_3079_' + bid).show();
							for (var index = 0; index < json.highlight.length; ++index) {
								var arr = json.highlight[index];
								if($(arr)) {
									$(arr).addClassName('errorfield');  					
								}
							}
							$('submit_' + bid).disabled = false;

						}

					},
					onFailure: function(transport) {

						alert('An error has occurred, please try again');
						$('submit_' + bid).disabled = false;

					}
				});

*/
