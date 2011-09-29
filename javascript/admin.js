// JavaScript Document
document.observe('dom:loaded', function() {

		// on load
		$("find_business").observe("click", load_find_business);
		$("create_user").observe("click", load_create_user);
		$("find_user").observe("click", load_find_user);
	

		
		load_find_business();
		
		//---ajax pages
		

});



/*
function page_state(){
	
	
	$('form').disabled = true;
		
	new Ajax.Request('adminviews/page_state.php', {
									 					 					
					onSuccess: function(transport){
						
						var response = transport.responseText || "no response text";
					
						$('ajax_container').innerHTML = transport.responseText;
						$$('ul.page_state_menu li a').invoke('observe', 'click', append_page_link);
						
					},
					onFailure: function(){ 
						alert('An error has occurred, please try again');
					}
					
	});
	
//-e page_state
}


function append_page_link(event){
	
	var element = Event.element(event);
	var id = element.readAttribute('class');
	alert('class' + id);
	
	$('page_state').stopObserving('click');
	$('page_state').setAttribute('class', id);
	$$('.state4').invoke('observe', 'click', state4);
	
	alert('end');

//-e append_page_link
}




function state4(){
	
	alert("state4");
	
}






function page_state_li(event){	
	
	var element = Event.element(event);
	var id = element.readAttribute('id');
	
	new Ajax.Request('adminviews/page_state.php?page=' + id, {								 					 							
		onSuccess: function(transport){
			$('ajax_container').innerHTML = transport.responseText;
			$$('ul.page_state_menu li a').invoke('observe', 'click', page_state_li);
		},
		onFailure: function(){ 					
			alert('An error has occurred, please try again');
		}			
	})
	
}

*/


function find_business(){
	

	
	$('form').disabled = true;
		
	new Ajax.Request('adminviews/admin_find.php', {
									 					 
					parameters: $('form').serialize(),
					
					onSuccess: function(transport){
						
						var response = transport.responseText || "no response text";
					
						$('ajax_container').innerHTML = transport.responseText;
						
						$("submit_button").observe("click", find_business);
						
						
					},
					onFailure: function(){ 
						alert('An error has occurred, please try again');
					}
					
	});


//-e find_business	
}




function load_find_business(){
	
	event.stop()
	//alert("load_find_business");
	//var myAjax = new Ajax.Updater('ajax_container','adminviews/admin_find.php', {});
	
	
	new Ajax.Request('adminviews/admin_find.php', {
									 					 
										
					onSuccess: function(transport){
									
						$('ajax_container').innerHTML = transport.responseText;
						$("submit_button").observe("click", find_business);
												
					},
					onFailure: function(){ 
						
						alert('An error has occurred, please try again');

					}
					
	});
	
	
//-e load_find_business
}










function load_create_user(){
	
	event.stop()
	new Ajax.Request('adminviews/admin_create_user.php', {
									 					 							
		onSuccess: function(transport){
						
			$('ajax_container').innerHTML = transport.responseText;
			$("submit_button").observe("click", create_user);
												
		},
		onFailure: function(){ 
						
			alert('An error has occurred, please try again');

		}
					
	})
	
//-e load_create_user
}







function create_user(){
	

	
	$('form').disabled = true;
		
	new Ajax.Request('adminviews/admin_create_user.php', {
									 					 
					parameters: $('form').serialize(),
					
					onSuccess: function(transport){
						
						var response = transport.responseText || "no response text";
					
						$('ajax_container').innerHTML = transport.responseText;
						
						$("submit_button").observe("click", create_user);
						
						
					},
					onFailure: function(){ 
						alert('An error has occurred, please try again');
					}
					
	});


//-e create_user	
}







function load_find_user(){
	
	//alert('load_find_user');
	
	event.stop()
	new Ajax.Request('adminviews/admin_find_user.php', {
									 					 							
		onSuccess: function(transport){
						
			$('ajax_container').innerHTML = transport.responseText;
			$("submit_button").observe("click", find_user);
			
												
		},
		onFailure: function(){ 
						
			alert('An error has occurred, please try again');

		}
					
	})
	
//-e load_find_user
}



function find_user(){
	
	//alert('find_user');
	
		
	$('form').disabled = true;
		
	new Ajax.Request('adminviews/admin_find_user.php', {
									 					 
					parameters: $('form').serialize(),
					
					onSuccess: function(transport){
						
						var response = transport.responseText || "no response text";
					
						$('ajax_container').innerHTML = transport.responseText;
												
						$$('a.load_user').invoke('observe', 'click', load_user);
						
						$("submit_button").observe("click", find_user);
						
						
					},
					onFailure: function(){ 
						alert('An error has occurred, please try again');
					}
					
	});


//-e find_user	
}









  
function load_user(event) {  

	//alert('load user.......');
	
	$('ajax_container').innerHTML = '<div id="user_form"></div><div id="business_list"></div><div id="business_form"></div>';
 
	var element = Event.element(event);
	var id = element.readAttribute('id');
	
	//alert(id);

	new Ajax.Request('adminviews/admin_display_user.php?id=' + id, {								 					 							
		onSuccess: function(transport){
			$('user_form').innerHTML = transport.responseText;
			$("submit_button").observe("click", update_user);							
		},
		onFailure: function(){ 					
			alert('An error has occurred, please try again');
		}			
	})
	
	
	new Ajax.Request('adminviews/display_business.php?id=' + id, {								 					 							
		onSuccess: function(transport){
			$('business_list').innerHTML = transport.responseText;
			
			$$('a.remove_business').invoke('observe', 'click', remove_business);
			$$('a.show_business').invoke('observe', 'click', show_business);
			
		},
		onFailure: function(){ 					
			alert('An error has occurred, please try again');
		}			
	})

	
	
	new Ajax.Request('adminviews/add_business.php?id=' + id, {								 					 							
		onSuccess: function(transport){
			$('business_form').innerHTML = transport.responseText;
			$('add_business').observe("click", add_business);							
		},
		onFailure: function(){ 					
			alert('An error has occurred, please try again');
		}			
	})
	
//-e load_user
}



function update_user(){
	
	//alert('update user');
	
	new Ajax.Request('adminviews/admin_display_user.php?mode=update_user', {
									 					 
					parameters: $('form').serialize(),
					
					onSuccess: function(transport){
						
						var response = transport.responseText || "no response text";
					
						$('user_form').innerHTML = transport.responseText;
						$("submit_button").observe("click", update_user);
						$("add_business").observe("click", add_business);
						
						
					},
					onFailure: function(){ 
						alert('An error has occurred, please try again');
					}
					
	});
	
//-e update_user
}


function add_business(){
	
	//alert('add business');
	//alert('update user');
	
	new Ajax.Request('adminviews/add_business.php', {		
		parameters: $('add_bs').serialize(),
		onSuccess: function(transport){
			
			$('business_form').innerHTML = transport.responseText;
			$('add_business').observe("click", add_business);		
			
			display_business();
			
			
		},
		onFailure: function(){ 					
			alert('An error has occurred, please try again');
		}			
	})
	
	
}



function remove_business(event) { 

	var element = Event.element(event);
	var id = element.readAttribute('id');
	//alert("remove_business id:" + id);

	new Ajax.Request('adminviews/display_business.php?page=remove&alexid=' + id, {		
		parameters: $('display_business_form').serialize(),
		onSuccess: function(transport){
			$('business_list').innerHTML = transport.responseText;
			$('delete_yes').observe("click", delete_business);					
			$('delete_no').observe("click", display_business);					
		},
		
		onFailure: function(){ 					
			alert('An error has occurred, please try again');
		}	
		
	})
}


function delete_business(){
	
	new Ajax.Request('adminviews/display_business.php?page=delete&alexid=' + id, {		
		parameters: $('display_business_form').serialize(),
		onSuccess: function(transport){
			$('business_list').innerHTML = transport.responseText;
			$$('a.remove_business').invoke('observe', 'click', remove_business);
		},
		
		onFailure: function(){ 					
			alert('An error has occurred, please try again');
		}	
		
	})
		
	
}



function display_business(){
	
	new Ajax.Request('adminviews/display_business.php?', {
		parameters: $('display_business_form').serialize(),
		onSuccess: function(transport){
			$('business_list').innerHTML = transport.responseText;			
			$$('a.remove_business').invoke('observe', 'click', remove_business);
			$$('a.show_business').invoke('observe', 'click', show_business);
			
		},
		onFailure: function(){ 					
			alert('An error has occurred, please try again');
		}			
	})
	
}


function show_business(event){
	
	var element = Event.element(event);
	var id = element.readAttribute('id');
	
	//alert("show_business id:" + id);
	
	new Ajax.Request('adminviews/admin_find.php?alexid=' + id, {
					
					onSuccess: function(transport){
						
						var response = transport.responseText || "no response text";
					
						$('ajax_container').innerHTML = transport.responseText;
						
						$("submit_button").observe("click", find_business);
						
						
					},
					onFailure: function(){ 
						alert('An error has occurred, please try again');
					}
					
	});
	

	
}





