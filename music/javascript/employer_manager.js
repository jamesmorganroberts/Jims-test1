// JavaScript Document
var root = 'http://' + document.location.hostname + '/';

$(document).ready(function() {
						   
	$.scrollTo("#force_height", 0);	
	
	
	$("td.em_cu_results").live('click',function() {
											 							 
		var alt = $(this).parent().attr("alt");	
		
		//alert('alt' + alt);		
		
		document.location.href = root + 'employer/select_employer/' + alt;

		this.blur();
		
		return false;
	});
	
	
	
	$("td.em_cu_selected").live('click',function() {
											 							 
		var alt = $(this).parent().attr("alt");	
		
		//alert('alt' + alt);		
		
		document.location.href = root + 'employer/select_employer/' + alt;

		this.blur();
		
		return false;
	});
	


});