





 
	
  
  
 
 
 
 $(document).ready(function() {
 


	$(".thumb").mouseover(
	  function () { 
	  
		$(this).css({ opacity: 1 });
		//$(this).css('border', '1px solid #fff');
		
	  }
	);
	
	
	$(".thumb").mouseout(
	  function () { 
	  
		$(this).css({ opacity: 0.4 });
		//$(this).css('border', '1px solid #cacaca');
	  }
	);
	
	
	

	$("a.thumb_link").live('click',function() {	
		
		var alt = $(this).attr("alt");
		//event.preventDefault();
		//alert(alt);
		//$("#selected_design").hide();
		//$('#selected_design').load( 'ajax/load_web.php?image=' + alt);
		$('#selected_design').load('http://www.jamesmroberts.co.uk/new/ajax/load_web.php?image=' + alt);
		$("#selected_design").hide();
		
		
		
		
		
	});
	
	
	
	$('#image_load').load(function() {
	
		
		//$("#selected_design").fadeIn();
	
	});
	
	
	
	
	
});