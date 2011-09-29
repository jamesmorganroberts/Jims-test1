<?php



class tools{


	function __construct() {
			
		
	  
	//-e construct
	}
	
	static function getOrPost($VarName)
    {     
  
      if(array_key_exists($VarName,$_POST)) {
      	if(is_array($_POST[$VarName])) {
      		$Var = array();
      		foreach($_POST[$VarName] AS $key => $val) {
      			$Var[$key] = $val;
      		}
      	}
      	else {
      		$Var=(string)$_POST[$VarName];        
      	}
      }
      
      elseif(array_key_exists($VarName,$_GET)){
      	if(is_array($_GET[$VarName])) {
      		$Var = array();
      		foreach($_GET[$VarName] AS $key => $val) {
      			$Var[$key] = $val;
      		}
      	}
      	else {
      		$Var=(String)$_GET[$VarName];        
      	}      
      }
      
      else {
        $Var=FALSE;
      }
      
      if(strlen($Var)==0) {
        $Var=FALSE;        
      }

      return $Var;
    }


//-e class
}

?>