<?php

  /*
   ==================================================================================
   Project     : CRISP
   File        : crisp/includes/WebTools.php
   Description : General Tools for Web-based applications
   Parameters  : -
   Author      : Glynn Bird
   Date        : September 2007
   ==================================================================================
   */


  require_once("includes/MySQLDB.php");
  require_once("includes/config.php"); 
  require_once("includes/GoogleVis.php");
	require_once("includes/View.php");
	require_once("includes/ImageTools.php");
   
  
if (! function_exists('json_encode')) {
  function json_encode($data) {
    switch (gettype($data)) {
      case 'boolean':
        return ($data ? 'true' : 'false');
      case 'null':
      case 'NULL':
        return 'null';
      case 'integer':
      case 'double':
        return $data;
      case 'string':
        return '"'. str_replace(array("\\",'"',"/","\n","\r","\t"), array("\\\\",'\"',"\\/","\\n","\\r","\\t"), $data) .'"';
      case 'object':
      case 'array':
        if ($data === array()) return '[]'; # empty array
        if (range(0, count($data) - 1) !== array_keys($data) ) { # string keys, unordered, non-incremental keys, .. - whatever, make object
          $out = "\n".'{';
          foreach($data as $key => $value) {
            $out .= json_encode((string) $key) . ':' . json_encode($value) . ',';
          }
          $out = substr($out, 0, -1) . "\n". '}';
        }else{
          # regular array
          $out = "\n".'[' . join("\n".',', array_map('json_encode', $data)) ."\n".']';
        }
        return $out;
    }
  }
}

if ( !function_exists('json_decode') ){
    function json_decode($content, $assoc=false){
                require_once 'includes/JSON.php';
                if ( $assoc ){
                    $json = new Services_JSON(SERVICES_JSON_LOOSE_TYPE);
        } else {
                    $json = new Services_JSON;
                }
        return $json->decode($content);
    }
}


	function renderIcon($name,$size='small') {
		
		$altname = str_replace("_"," ",$name);
		$altname = ucwords($altname);
	
		if(file_exists("images/icon_".$name."_".$size.".png")) {
			$str = '<img src="images/icon_'.$name.'_'.$size.'.png" border="0" alt="'.$altname.'"';
			
			if($size=="small") {
				$str.=' width="16" height="16"';
			} elseif($size=="med") {
				$str.=' width="32" height="32"';
			}
			
			$str.=' />';		
		}	else {
			$str = $altname;
		}
		
		return $str;
	
	}
  
  class WebTools {

    // the length of an HTTP session in minutes
    const SESSION_LIFETIME=0; // 0 = use session cookies


		static function dumpGetPost(){
			
			echo '<b>POST:</b>';
			print_r($_POST);
			echo '<b>GET:</b>';
			print_r($_GET);			
		
		//-e dumpGetPost
		}
		


    // Returns the CGI variable, whether it be from the $_GET or $_POST array.
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
    
    // Returns whether the current web request comes from an internal IP address.
    static function isThisInternal() {
      // split the array by spaces
      $IPS = explode(" ",NTConstants::INTERNAL_IPS);

      // look for a match
      foreach($IPS as $IP) {     
        if(preg_match($IP,$_SERVER["REMOTE_ADDR"])) {
          return 1;
        }
      }
      return 0;
    }
    
    // start session
    static function startSession() {
      session_set_cookie_params(WebTools::SESSION_LIFETIME*60);
      session_start();
    }
    
    static function connectDBRead($server,$username,$password,$database,$port) {
      global $mysqlread;
      
      // connect to database
      $mysqlread = new MySQLDB($server,$username,$password,$database,$port);      
    }
    
    static function connectDBWrite($server,$username,$password,$database,$port) {
      global $mysqlwrite;
      
      // connect to database
      $mysqlwrite = new MySQLDB($server,$username,$password,$database,$port);
    }

		// Return array of the dirs in a given path
    static function GetDirArray($sPath) {
			$handle=opendir($sPath);
			while (false !== ($file = readdir($handle))) {
				if (is_dir("$sPath/$file")) {
					if ($file != "." && $file != "..")
					{	$retVal[count($retVal)] = $file;        }
				}
			}
			closedir($handle);
			if (is_array($retVal))
			{	sort($retVal);  }
			return $retVal;
		}
		
		// Return array of the files in a given path
		static function GetFileArray($sPath, $filetype=NULL) {
			$handle=opendir($sPath);
			while (false !== ($file = readdir($handle))) {
				if ($filetype) {
					if (!is_dir("$sPath/$file")) {
						if ($file != "." && $file != ".." && $filetype==(substr($file, -4)))
						{	$retVal[count($retVal)] = $file;        }
					}
				}
		
				else {
					if (!is_dir("$sPath/$file")) {
						if ($file != "." && $file != "..")
						{	$retVal[count($retVal)] = $file;        }
					}
				}
			}
			closedir($handle);
			if (is_array($retVal))
			{	sort($retVal);  }
			return $retVal;
		}

		static function curPageName() {
			return substr($_SERVER["SCRIPT_NAME"],strrpos($_SERVER["SCRIPT_NAME"],"/")+1);
		}

  }

  // load the config
  if(!$CONFIG_IS_LOADED) {
    die("please edit your includes/config.php to configure this webserver. There's a template one called _config.php to assist you.");
  }
  
  // connect to local database read only
  WebTools::connectDBRead($MYSQL_READONLY_SERVER, $MYSQL_READONLY_USERNAME, $MYSQL_READONLY_PASSWORD,$MYSQL_READONLY_DATABASE,$MYSQL_READONLY_PORT);
  
  // connect to main DB Server for writes
  WebTools::connectDBWrite($MYSQL_READWRITE_SERVER,$MYSQL_READWRITE_USERNAME,$MYSQL_READWRITE_PASSWORD,$MYSQL_READWRITE_DATABASE,$MYSQL_READWRITE_PORT);
    
  if(!isset($NoSessionStuff)) {

    // use custom session handler
		WebTools::startSession();
		
	}
  
?>