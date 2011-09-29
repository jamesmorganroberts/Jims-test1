// Confirmation popup
 
function AreYouSure()
{
  if(confirm("Are you sure you want to do this?")) {
    return true;
  }   
  return false;
}

// Fade background out

function fadebackgroundtoblack() {
	if($('wrap')) {
    new Effect.Opacity('wrap', { duration:1.5, from:1.0, to:0.3 });
    $('top').scrollTo();
  } 
}

// Restore background

function fadebackgroundfromblack() {
	if($('wrap')) {
    new Effect.Opacity('wrap', { duration:1.5, from:0.3, to:1.0 });
  } 
}

// Function to simplify the hide/show of centralised, floating divs
function showHideDiv(divname) {

	if($(divname)) {
		if(!$(divname).visible())
		{	
			fadebackgroundtoblack();
			new Effect.Center(divname);
			new Effect.Appear(divname);			
		} else {	
			new Effect.Fade(divname);	
			fadebackgroundfromblack();
		}
	}
	
}

// Center effect for Scriptaculous

Effect.Center = function(element)
{
  try
  {
    element = $(element);
  }
  catch(e)
  {
    return;
  }
  
  var my_width  = 0;
  var my_height = 0;
  
  if ( typeof( window.innerWidth ) == 'number' )
  {    
    my_width  = window.innerWidth;
    my_height = window.innerHeight;
  }
  else if ( document.documentElement && ( document.documentElement.clientWidth || document.documentElement.clientHeight ) )
  {    
    my_width  = document.documentElement.clientWidth;
    my_height = document.documentElement.clientHeight;
  }
  else if ( document.body && ( document.body.clientWidth || document.body.clientHeight ) )
  {    
    my_width  = document.body.clientWidth;
    my_height = document.body.clientHeight;
  }
  
  element.style.position = 'absolute';
  element.style.display  = 'block';
  element.style.zIndex   = 99;
  
  var scrollY = 0;
  
  if ( document.documentElement && document.documentElement.scrollTop )
  {
    scrollY = document.documentElement.scrollTop;
  }
  else if ( document.body && document.body.scrollTop )
  {
    scrollY = document.body.scrollTop;
  }
  else if ( window.pageYOffset )
  {
    scrollY = window.pageYOffset;
  }
  else if ( window.scrollY )
  {
    scrollY = window.scrollY;
  }
  
  var elementDimensions = Element.getDimensions(element);

  var setX = ( my_width  - elementDimensions.width  ) / 2;
	//var setY = ( my_height - elementDimensions.height ) / 3 + scrollY;
  var setY=100;
  setX = ( setX < 0 ) ? 0 : setX;
  setY = ( setY < 0 ) ? 0 : setY;
  
  element.style.left = setX + "px";
  element.style.top  = setY + "px"; 
}

function checkAll(formid,elementname,checkbuttonid)
{
	var form = $(formid);
	var i = form.getElements('checkbox');
	
	i.each(function(item) {
		if(item.name == elementname) {
			item.checked=true;
		}
	});

	var innerHTML = '<input type="button" value="Un-Check All" onclick="uncheckAll(\'' + formid + '\',\'' + elementname + '\',\'' + checkbuttonid + '\'); return false;" />';
	$(checkbuttonid).innerHTML = innerHTML;
}

function uncheckAll(formid,elementname,checkbuttonid)
{
	var form = $(formid);
	var i = form.getElements('checkbox');
	
	i.each(function(item) {
		if(item.name == elementname) {
			item.checked=false;
		}
	});
	
	var innerHTML = '<input type="button" value="Check All" onclick="checkAll(\'' + formid + '\',\'' + elementname + '\',\'' + checkbuttonid + '\'); return false;" />';
	$(checkbuttonid).innerHTML = innerHTML;
}
