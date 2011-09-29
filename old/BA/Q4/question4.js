
function pageLoad(){
	
	hideAll();
	
	document.getElementById("Boeing747-400").style.display = 'block';
	
}

function hidePlanes() {
	
	

	
	var w = document.myForm.myList.selectedIndex;
	
	var selected_value = document.myForm.myList.options[w].value;
	
	hideAll();
	
	document.getElementById(selected_value).style.display = 'block';
	
}


function hideAll(){
	
	var elements = new Array();
	elements = getElementsByClassName('planeInfo');
	for(i in elements ){
		 elements[i].style.display = "none";
	}
	
}


function getElementsByClassName(classname, node)  {
    if(!node) node = document.getElementsByTagName("body")[0];
    var a = [];
    var re = new RegExp('\\b' + classname + '\\b');
    var els = node.getElementsByTagName("*");
    for(var i=0,j=els.length; i<j; i++)
        if(re.test(els[i].className))a.push(els[i]);
    return a;
}

