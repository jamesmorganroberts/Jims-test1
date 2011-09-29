
function pageLoad(){
	
	//hideAll();
	
	//document.getElementById("Boeing747-400").style.display = 'block';
	//alert('dfgdg');
	
	
	
	
}

function clearErrorNotices(){
	
	document.getElementById("nameError").innerHTML = "";	
	document.getElementById("emailError").innerHTML = "";
}


function validate(){

	clearErrorNotices();
	
	var reg = /^([A-Za-z0-9_\-\.])+\@([A-Za-z0-9_\-\.])+\.([A-Za-z]{2,4})$/;
   	var address = document.myForm.email.value;
	var name = document.myForm.name.value;
	
	if(name == ""){
		var valid_name = "false";
		 //alert('Please enter name');
		document.getElementById("nameError").innerHTML = "* Please enter name";
	}
	else{ var valid_name = "true"; }
	
	if(reg.test(address) == false) {
      //alert('Invalid Email Address');
	  document.getElementById("emailError").innerHTML = "* Please enter valid email";
      var valid_email = "false";
    }
	else{
	
		//alert('valid email');
		var valid_email = "true";
	}
	
	
	if(valid_email == "true" && valid_name == "true")
	{
		//alert('VALID');
		document.forms["myForm"].submit();
	}
	else {
		//alert('INVALID');
	}

	
	
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

