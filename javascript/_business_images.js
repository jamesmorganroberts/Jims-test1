// JavaScript Document

document.observe('dom:loaded', function() {

	// on load
	
	var alexid = $('alexid').getValue();
	load_page('gallery.php?alexid=' + alexid);


});






function click_handler(event){
	
	var alexid = $('alexid').getValue();
	var element = Event.element(event);
	var class = element.readAttribute('class');
	
	if(class != null){
		
		if(class == "upload_image"){
			
			var imageid = element.readAttribute('id');
			load_page("gallery.php?page=upload&imageid=" + imageid + "&alexid" + alexid);
			
		}	
		
		if(class == "load_gallery") {
			
			load_page("gallery.php?alexid=" + alexid );
			
		}
		
	}	
//-e click_handler	
}


function load_page(page){
	
	//alert('load upload images');
	new Ajax.Request(page, {							 					 					
		onSuccess: function(transport){				
			var response = transport.responseText || "no response text";		
			$('ajax_container').innerHTML = transport.responseText;		
			
			$$('a').invoke('observe', 'click', click_handler);
			$("submit_button").observe("click", check_form);
	
		},
		onFailure: function(){ 
			alert('An error has occurred, page:' + page);
		}				
	});
}



function check_form(event){
	
	
	var element = Event.element(event);
	var form = element.up('form');
	var form_id = form.readAttribute('id');
	
	alert(form_id);
	
	if(form_id=="image_upload_form") { submit_form('gallery.php?page=receive',form_id); }
	
		
	
}



function submit_form(page,form){
	
	alert('submit_form');
	
	//alert('load upload images');
	new Ajax.Request(page, {	
		parameters: $(form).serialize(),
		onSuccess: function(transport){				
			var response = transport.responseText || "no response text";		
			$('ajax_container').innerHTML = transport.responseText;		
			
			$$('a').invoke('observe', 'click', click_handler);
			$("submit_button").observe("click", submit_form);
	
		},
		onFailure: function(){ 
			alert('An error has occurred, page:' + page);
		}				
	});
	
//-e submit form
}





