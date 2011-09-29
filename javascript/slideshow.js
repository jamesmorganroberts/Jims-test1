





 
	
  
  
 
 
 
 $(document).ready(function() {
 
	var transition = "inactive";
	var pointer = "out";
	var slide_entered = "";
	var timer = setInterval( change_slide, 9000);
	
	var hide_counter = 1;
	var show_counter = 1;
	var hide_slide = "";
	var show_slide = "";
	
	$("#slide2").hide();
	$("#slide3").hide();
	$("#slide4").hide();
	$("#slide5").hide();
	
	$("#slidetext2").hide();
	$("#slidetext3").hide();
	$("#slidetext4").hide();
	$("#slidetext5").hide();
 
 
	$("a.slide_link").mouseover(
	  function () { 
	  
		show_counter = $(this).attr("name");
		pointer = "in";
		
		show_the_slide2();
		hide_counter = show_counter;
	  }
	);
	
	
	$("a.slide_link").mouseout(
	  function () {
		
		pointer = "out";
	  
		
	  }
	);
	
 
 
						   
	
	
	function change_slide(){
		
		if(pointer=="out"){			
			
			
			show_counter++;
			
			if(hide_counter==6){hide_counter = 1;}
			if(show_counter==6){show_counter = 1;}
			
			//hide_slide = "#slide" + hide_counter;
			//show_slide = "#slide" + show_counter;
			
			hide_the_slide();
			hide_counter = show_counter;
		}
	}
	
	
	
	function opac(){
	
		$('#slide1').animate({
			opacity: 0.25,
			left: '+=50',
			height: 'toggle'
		  }, 5000, function() {
			// Animation complete.
		  });
	
	}
	
	
	
	
	function show_the_slide2(){	
			
		show_slide = "#slide" + show_counter;
		show_text = "#slidetext" + show_counter;
		
		$('#slide1').hide();
		$('#slide2').hide();
		$('#slide3').hide();
		$('#slide4').hide();
		$('#slide5').hide();
		$('#slidetext1').hide();
		$('#slidetext2').hide();
		$('#slidetext3').hide();
		$('#slidetext4').hide();
		$('#slidetext5').hide();
		$(show_slide).show();
		$(show_text).show();
	}
	

	
	
	function show_the_slide(){	
			
		show_slide = "#slide" + show_counter;
		show_text = "#slidetext" + show_counter;
		//alert('show_the_slide' + show_slide);
		$(show_slide).fadeIn(600);
		$(show_text).fadeIn(600,transition_inactive);
	
	}
	
	function hide_the_slide(){
		
		transition = "active";
		hide_slide = "#slide" + hide_counter;
		hide_text = "#slidetext" + hide_counter;
		$(hide_slide).fadeOut(600);
		$(hide_text).fadeOut(600,show_the_slide);
	}
	
	
	function transition_inactive(){
	
		transition = "inactive";
	
	}
	
	
	
	
	
	
	


});