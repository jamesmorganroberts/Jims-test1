var randomnumber=Math.floor(Math.random()*11);
var page = window.location.pathname;
var root = 'http://' + document.location.hostname + '/';
var loading_image = '<img src="http://www.centraljobshub.co.uk/images/loading2.gif" alt="loading page">';

function clear_ajax_pages() {
	
	$(".edit_page").html("");
	$(".pager_page").html("");
	$(".pager_page").hide();	
	$(".edit_page").hide(); 
	$("#filter_open").hide();
	$("#loading_pager").hide();
	$("#loading_edit_page").hide();

}



function reload_page() {
	
	//alert('reload page');	
	
	var parent_page = $("form.display_form").parent().attr("id") + "_pager";
	var parent_div = '#' + $("form.display_form").parent().attr("id") + "_pager";
		
	//alert(parent_page);
	
	$(parent_div).load( root + 'ajax/index.php?page=' + parent_page + '/' + page);
	
}



//setTimeout("function();",3000);


$(document).ready(function() {
						   
$(function(){
	$('input.date').live('click', function() {
		$(this).datepicker({showOn:'focus',dateFormat: 'dd-mm-yy'}).focus();
	});
});



					   

  $('form').live('submit', function() {
									
	//alert('submit');						
									
	var parent_page = $(this).parent().attr("id");
	var parent_div = "#" + $(this).parent().attr("id");
	
	//alert('parent_page ' + parent_page);
	//alert('parent_div ' + parent_div);	
	 
	var jim1 = "#" + parent_page + "_pager";
	var jim2 = parent_page + "_pager";

     $.post(root + 'ajax/index.php?page=' + parent_page + '/' + page, $(this).serialize(), function(data) {
      $(parent_div).html(data);
     });

     $(jim1).load( root + 'ajax/index.php?page=' + jim2 + '/' + page);

	 return false;
	 
  });
  
  
    
  
  $("#submit_button").live('click',function(event) {
											
		event.preventDefault();


		var parent_page = $("form.display_form").parent().attr("id");
		var parent_div = '#' + $("form.display_form").parent().attr("id");
		
		var jim1 = "#" + parent_page + "_pager";
		var jim2 = parent_page + "_pager";
		
		//alert('parent_page ' + parent_page);
		//alert('parent_div ' + parent_div);	
		
		//alert('submit pressed');
		
		$.post(root + 'ajax/index.php?page=' + parent_page + '/' + page, $("form.display_form").serialize(), function(data) {
     	 $(parent_div).html(data);
     	});

     	$(jim1).load( root + 'ajax/index.php?page=' + jim2 + '/' + page);
		
		$("#submit_button").blur();
		
	
		return false;
  });
  
  
  
  
  
  $("#filter_action").live('click',function() {		
		//alert('open_filter');
		var filter = $(this).attr("alt");
		this.blur();
		var parent_page = $("#filter").parent().attr("id");
		var parent_div = '#' + parent_page
		//alert('parent_page:' + parent_page + 'filter_action:' + filter);
		$(parent_div).load( root + 'ajax/index.php?page=' + parent_page + '/' + page + '/filter_action/' + filter);
		$("#filter").hide();
		$("#filter").fadeIn();
		
		return false;									  
  });
  


  $("#cancel_button").live('click',function() {		

		var parent_page = $("form.display_form").parent().attr("id");
		var parent_div = '#' + $("form.display_form").parent().attr("id");
		
		//alert('cancel pressed');
		
		$(parent_div).load( root + 'ajax/index.php?page=' + parent_page + '/' + page);
				
		this.blur();
	
		return false;
  });


  $("a.list_counter_select").live('click',function() {		
		var list_command = $(this).attr("alt");
		var parent_page = $("table.list_counter").parent().attr("id");
		this.blur();
		//alert('list counter clicked, parent div:' + parent_page + "list_select" + list_command);
		$('#' + parent_page ).load( root + 'ajax/index.php?page=' + parent_page  + '/' + page + '/list_command/' + list_command);
		return false;
	});
  
  
  $("a.order_by").live('click',function() {	
											
		var order_by = $(this).attr("alt");
		var parent_page = $("table.repeater_header").parent().attr("id");
		var parent_div = '#' + parent_page
		this.blur();
		
		//alert('parent_div=' + parent_div + " parent_page=" + parent_page + " order_by:" + order_by );
		
		$(parent_div).load( root + 'ajax/index.php?page=' + parent_page + '/' + page + '/order_by/' + order_by);
		
		return false;
	});
  
  
  $("a.pager_select").live('click',function() {		
		var pager = $(this).attr("alt");
		var parent_page = $("table.pager_panel").parent().attr("id");
		this.blur();
		//alert('pager clicked, parent div:' + parent_page + "pager" + pager);
		$('#' + parent_page ).load( root + 'ajax/index.php?page=' + parent_page  + '/' + page + '/pager/' + pager);
		return false;
	});
  
    $("a.button_select").live('click',function() {	
			
		
		var alt = $(this).attr("alt");	
		var parent_div = $("table.form_edit_menu").parent().attr("id"); 
        var parent_page = parent_div.replace(/\_pager/g,"");
		this.blur();
		
		//alert("button select parent_page" + parent_page);
		
		$('#' + parent_page ).load( root + 'ajax/index.php?page=' + parent_page + '/' + page + '/button/' + alt);
		$('#' + parent_page + '_pager' ).load( root + 'ajax/index.php?page=' + parent_page + '_pager/' + page );
		return false;
	});
	
	$("tr.tr_results").live('click',function() {
											 							 
		var id = $(this).attr("alt");	
		var parent_div = $("table.repeater_header").parent().attr("id"); 
        var parent_page = parent_div.replace(/\_pager/g,"");
		
		//alert("parent_div" + parent_div);
		//alert("parent_page" + parent_page);	
		
		$('#' + parent_page ).load( root + 'ajax/index.php?page=' + parent_page + '/' + page + '/selected_id/' + id);
		$('#' + parent_page + '_pager' ).load( root + 'ajax/index.php?page=' + parent_page + '_pager/' + page + '/selected_id/' + id);
		
		return false;
	});

	

	
	
	
	
});




$(document).ready(function() {
						   
						   
	$.scrollTo("#force_height", 0);	
	
	clear_ajax_pages();
	
	$('#employer_details').load( root + 'ajax/index.php?page=employer_details/' + page);
	$("#employer_details").fadeIn();
	
	//$.scrollTo({ top: '+=200px', left: '+=0px' }, 0);
	//alert("finished");
	//window.location.href='#employer';
	
	
	
	//$.scrollTo({ top: '+=200px', left: '+=0px' }, 0);
	//self.scrollTo(0,200);
	
	
	
	
	
	
	$("#details_li").click(function() {
									
		$.scrollTo("#force_height", 0);	
		clear_ajax_pages();
		
		$("ul.sub_tabs li").removeClass("active_sub"); 
		$(this).addClass("active_sub"); 
		
		$('#employer_details').html(loading_image);
		
		$('#employer_details').load( root + 'ajax/index.php?page=employer_details/' + page);

		$("#employer_details").fadeIn();
		
		this.blur();
		
		
		return false;
	});
	
	$("#activity_li").click(function() {
									 
		$.scrollTo("#force_height", 0);		
		clear_ajax_pages();
		
		$("ul.sub_tabs li").removeClass("active_sub"); 
		$(this).addClass("active_sub");
		
		$('#employer_activity').html(loading_image);
		$('#employer_activity_pager').html(loading_image);
		
		$('#employer_activity').load( root + 'ajax/index.php?page=employer_activity/' + page);

		$('#employer_activity_pager').load( root + 'ajax/index.php?page=employer_activity_pager/' + page);

		
		$("#employer_activity_pager").fadeIn();
		$("#employer_activity").fadeIn();
		this.blur();
		
		return false;

	});
	
	
	$("#contacts_li").click(function() {
		
		$.scrollTo("#force_height", 0);
		clear_ajax_pages();		
		
		$("ul.sub_tabs li").removeClass("active_sub"); 
		$(this).addClass("active_sub"); 
		
		$('#employer_contacts').html(loading_image);
		$('#employer_contacts_pager').html(loading_image);

		$('#employer_contacts').load( root + 'ajax/index.php?page=employer_contacts/' + page);
	
		$('#employer_contacts_pager').load( root + 'ajax/index.php?page=employer_contacts_pager/' + page);

		$("#employer_contacts_pager").fadeIn();
		$("#employer_contacts").fadeIn();
		this.blur();
		
		return false;

	});


	
	$("#vacancies_li").click(function() {
									  
		$.scrollTo("#force_height", 0);							  
		clear_ajax_pages();		
		
		$("ul.sub_tabs li").removeClass("active_sub"); 
		$(this).addClass("active_sub"); 
		
		$('#employer_vacancies').html(loading_image);
		$('#employer_vacancies_pager').html(loading_image);
		
		$('#employer_vacancies').load( root + 'ajax/index.php?page=employer_vacancies/' + page);
		$('#employer_vacancies_pager').load( root + 'ajax/index.php?page=employer_vacancies_pager/' + page); 

		$("#employer_vacancies_pager").fadeIn();
		$("#employer_vacancies").fadeIn();
		
		return false;
	});

});

