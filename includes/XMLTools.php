<?php
  
  /*
   ==================================================================================
   Project     : SEOTrackz
   File        : seotrackz/includes/XMLTools.php
   Description : XML Tools for XML-based stuff
   Parameters  : -
   Author      : Glynn Bird
   Date        : September 2007
   ==================================================================================
   */
  
  class XMLTools {
  
    // return the top of an XML document
    static function getXMLDeclaration() {
      return "<"."?"."xml version=\"1.0\""."?".">\n";
    }
    
    // Converts plain text into UTF8 XML compatible text.
    static function xmlify($str) {
       
    	// Hack added to deal with RecentSearchCollection SuperDate issue... needs a fix! 
    	if(!is_object($str))
    	{    	
	      $str = htmlspecialchars($str);	      
   	  	$str = str_replace("\xc2\xa3","&#163;",$str);	// Correct £ symbols
   	  	//$str = str_replace("\xc3\xa9","&#232;",$str);	// Correct è symbols
      	$str = utf8_encode($str);
      	return $str;
    	}    
    }
    
    // Creates an XML element from a key and value pair.
    static function toXML($key,$val) {
    
      $str = "";
      
      if(strlen($val) == 0) {
        $str = sprintf("<%s />\n",$key);
      } else {
        $str = sprintf("<%s>%s</%s>\n",
                       $key,
                       XMLTools::xmlify($val),
                       $key);
      }   
      return $str;
    }
    
    // convert an associative array to XML
    static function arrayToXML($a,$indent) {
      
      $xml = "";
      if ($a) {
        foreach($a as $key=>$value) {
          $xml .= str_repeat(" ",$indent).XMLTools::toXML($key,$value);
        }  
      }
      return $xml;
    }
    
    // cleans up XML - commenting out CDATA sections to make Javascript work and removes blank namespaces
    static function cleanUpXML($xml) {
      $xml = str_replace("<![CDATA[","/*<![CDATA[*/",$xml);
      $xml = str_replace("]]>","/*]]>*/",$xml);
      $xml = str_replace("xmlns=\"\"","",$xml);
      return $xml;
    }
    
    // perform a one-off XSLT transformation
    static function xslt($xmlstr,$xslstr,$params=false) {
      
      // create XSL transformation
      $xsltProcessor = new XSLTProcessor();
      $xsl = new DOMDocument();
      $xsl->loadXML( $xslstr );
      $xsltProcessor->importStylesheet( $xsl );     
      
      // apply parameters
      if($params) {
        foreach($params as $key=>$value) {
          $xsltProcessor->setParameter('', $key, $value);
        }
      }
      
      // load XML
      $doc = new DOMDocument();
      @$doc->loadXML($xmlstr);
      
      // calculate transformed XML
      $retval = $xsltProcessor->transformToXML( $doc );
      
      // tidy up and return
      return XMLTools::cleanUpXML($retval);
      
    }
    
    // XML indenting function
    static function indent($xml) {
      $lint = "/usr/bin/xmllint";
      $f1 = tempnam("/tmp","");
      $f2 = tempnam("/tmp","");
      file_put_contents($f1,$xml);
      $cmd = sprintf("%s --format %s > %s",$lint,escapeshellarg($f1),escapeshellarg($f2));
      exec($cmd);
      $xml = file_get_contents($f2);
   //   print($xml);
      unlink($f1);
      unlink($f2);
      return $xml;
    }
    
    // escape json text
    static public function jsonescape($text) {
      
      $text = preg_replace('/[^(\x20-\x7F)]*/','', $text);
      $text = preg_replace('/\s\s+/', ' ', trim($text));
      // This has been removed, because although we want to escape the " symbols when making a Povo request, single ' need to stay intact (e.g \"Queen's Head Pub\")
      //$text = str_replace("'","\\'",$text);
      $text = str_replace("\"","\\\"",$text);
      $text = str_replace("\\\\","\\",$text);	
      return $text;	
    }
    

		// Given XML Data return it in an array structure...
		static function xmlToArray($xmldata)
		{
			ini_set ('track_errors', '1');			
			$xmlreaderror = false;			
			$parser = xml_parser_create ('ISO-8859-1');
			xml_parser_set_option ($parser, XML_OPTION_SKIP_WHITE, 1);
			xml_parser_set_option ($parser, XML_OPTION_CASE_FOLDING, 0);
			if (!xml_parse_into_struct ($parser, $xmldata, $vals, $index)) {
				$xmlreaderror = true;
				//echo "XML Error";
			}
			xml_parser_free ($parser);
		
			if (!$xmlreaderror) {
				$result = array ();
				$attributes = array();
				$i = 0;
				if (isset ($vals [$i]['attributes'])) {
					foreach (array_keys ($vals [$i]['attributes']) as $attkey)
					{	$attributes [$attkey] = $vals [$i]['attributes'][$attkey];      }
				}
				$result [$vals [$i]['tag']] = array_merge ($attributes, XMLTools::xmlGetChildren($vals, $i, 'open'));
			}
			
			ini_set ('track_errors', '0');
			return $result;
		}
		
		static function xmlGetChildren($vals, &$i, $type)
		{
			if ($type == 'complete') {
				if (isset ($vals [$i]['value']))
				{	return ($vals [$i]['value']);   }
				else
				{	return '';                      }
			}
			
			$children = array (); // Contains node data
			
			// Loop through children
			while ($vals [++$i]['type'] != 'close')	{
				$type = $vals [$i]['type'];
			
				// Do we have this already? Or do we need to create an array?
			
				if (isset ($children [$vals [$i]['tag']])) {
					if (is_array ($children [$vals [$i]['tag']])) {
						$temp = array_keys ($children [$vals [$i]['tag']]);
			
						// Exists - is already an array
			
						if (is_string ($temp [0])) {
							$a = $children [$vals [$i]['tag']];
							unset ($children [$vals [$i]['tag']]);
							$children [$vals [$i]['tag']][0] = $a;
						}
					}
			
					else {
						$a = $children [$vals [$i]['tag']];
						unset ($children [$vals [$i]['tag']]);
						$children [$vals [$i]['tag']][0] = $a;
					}
			
					$children [$vals [$i]['tag']][] = XMLTools::xmlGetChildren($vals, $i, $type);
				}
			
				else
				{	$children [$vals [$i]['tag']] = XMLTools::xmlGetChildren($vals, $i, $type);         }
				
				// Attribute values...
				
				if (isset ($vals [$i]['attributes'])) {
					$attributes = array ();
					foreach (array_keys ($vals [$i]['attributes']) as $attkey)
					{	$attributes [$attkey] = $vals [$i]['attributes'][$attkey];      }
				
					// Do we already have one of these?
					
					if (isset ($children [$vals [$i]['tag']])) {
						// Attribute, but no value
						if ($children [$vals [$i]['tag']] == '') {
							unset ($children [$vals [$i]['tag']]);
							$children [$vals [$i]['tag']] = $attributes;
						}
					
						// Array of identical items with attributes
						
						elseif (is_array ($children [$vals [$i]['tag']])) {
							$index = count ($children [$vals [$i]['tag']]) - 1;
							
							// Is this an array?
							
							if ($children [$vals [$i]['tag']][$index] == '') {
								unset ($children [$vals [$i]['tag']][$index]);
								$children [$vals [$i]['tag']][$index] = $attributes;
							}
						
							$children [$vals [$i]['tag']][$index] = array_merge ($children [$vals [$i]['tag']][$index], $attributes);
						}
						
						else {
							$value = $children [$vals [$i]['tag']];
							unset ($children [$vals [$i]['tag']]);
							$children [$vals [$i]['tag']]['value'] = $value;
							$children [$vals [$i]['tag']] = array_merge ($children [$vals [$i]['tag']], $attributes);
						}
					}
					
					else
					{	$children [$vals [$i]['tag']] = $attributes;    }
				}
			}
			
			return $children;
		}
		
		function strip_invalid_xml_chars($in) {
			$out = "";
			$length = strlen($in);
			for ( $i = 0; $i < $length; $i++) {
				
				$current = ord($in{$i});
		
				if ( ($current == 0x9) || ($current == 0xA) || ($current == 0xD) || (($current >= 0x20) && ($current <= 0xD7FF)) || (($current >= 0xE000) && ($current <= 0xFFFD)) || (($current >= 0x10000) && ($current <= 0x10FFFF)))
				{
					$out .= chr($current);
				}
				else
				{
					$out .= " ";
				}
			
			}
			
			return $out;
		
		}
		
		// Nicely indent (valid) XML
		static function formatXmlString($xml) {  
  
			// add marker linefeeds to aid the pretty-tokeniser (adds a linefeed between all tag-end boundaries)
			$xml = preg_replace('/(>)(<)(\/*)/', "$1\n$2$3", $xml);
			
			// now indent the tags
			$token      = strtok($xml, "\n");
			$result     = ''; // holds formatted version as it is built
			$pad        = 0; // initial indent
			$matches    = array(); // returns from preg_matches()
			
			// scan each line and adjust indent based on opening/closing tags
			while ($token !== false) : 
			
				// test for the various tag states
				
				// 1. open and closing tags on same line - no change
				if (preg_match('/.+<\/\w[^>]*>$/', $token, $matches)) : 
					$indent=0;
				// 2. closing tag - outdent now
				elseif (preg_match('/^<\/\w/', $token, $matches)) :
					$pad--;
				// 3. opening tag - don't pad this one, only subsequent tags
				elseif (preg_match('/^<\w[^>]*[^\/]>.*$/', $token, $matches)) :
					$indent=1;
				// 4. no indentation needed
				else :
					$indent = 0; 
				endif;
				
				// pad the line with the required number of leading spaces
				$line    = str_pad($token, strlen($token)+$pad, ' ', STR_PAD_LEFT);
				$result .= $line . "\n"; // add to the cumulative result, with linefeed
				$token   = strtok("\n"); // get the next token
				$pad    += $indent; // update the pad size for subsequent lines    
			endwhile; 
			
			return $result;
		
		}

  }
  
  /*
   Working with XML. Usage: 
   $xml=xml2ary(file_get_contents('1.xml'));
   $link=&$xml['ddd']['_c'];
   $link['twomore']=$link['onemore'];
   // ins2ary(); // dot not insert a link, and arrays with links inside!
   echo ary2xml($xml);
   */
  
  // XML to Array
  function xml2ary(&$string) {
    $parser = xml_parser_create();
    xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
    xml_parse_into_struct($parser, $string, $vals, $index);
    xml_parser_free($parser);
    
    $mnary=array();
    $ary=&$mnary;
    foreach ($vals as $r) {
      $t=$r['tag'];
      if ($r['type']=='open') {
        if (isset($ary[$t])) {
          if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
          $cv=&$ary[$t][count($ary[$t])-1];
        } else $cv=&$ary[$t];
        if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
        $cv['_c']=array();
        $cv['_c']['_p']=&$ary;
        $ary=&$cv['_c'];
        
      } elseif ($r['type']=='complete') {
        if (isset($ary[$t])) { // same as open
          if (isset($ary[$t][0])) $ary[$t][]=array(); else $ary[$t]=array($ary[$t], array());
          $cv=&$ary[$t][count($ary[$t])-1];
        } else $cv=&$ary[$t];
        if (isset($r['attributes'])) {foreach ($r['attributes'] as $k=>$v) $cv['_a'][$k]=$v;}
        $cv['_v']=(isset($r['value']) ? $r['value'] : '');
        
      } elseif ($r['type']=='close') {
        $ary=&$ary['_p'];
      }
    }    
    
    _del_p($mnary);
    return $mnary;
  }
  
  // _Internal: Remove recursion in result array
  function _del_p(&$ary) {
    foreach ($ary as $k=>$v) {
      if ($k==='_p') unset($ary[$k]);
      elseif (is_array($ary[$k])) _del_p($ary[$k]);
    }
  }
  
  // Array to XML
  function ary2xml($cary, $d=0, $forcetag='') {
    $res=array();
    foreach ($cary as $tag=>$r) {
      if (isset($r[0])) {
        $res[]=ary2xml($r, $d, $tag);
      } else {
        if ($forcetag) $tag=$forcetag;
        $sp=str_repeat("\t", $d);
        $res[]="$sp<$tag";
        if (isset($r['_a'])) {foreach ($r['_a'] as $at=>$av) $res[]=" $at=\"$av\"";}
        $res[]=">".((isset($r['_c'])) ? "\n" : '');
        if (isset($r['_c'])) $res[]=ary2xml($r['_c'], $d+1);
        elseif (isset($r['_v'])) $res[]=$r['_v'];
        $res[]=(isset($r['_c']) ? $sp : '')."</$tag>\n";
      }
      
    }
    return implode('', $res);
  }
  
  // Insert element into array
  function ins2ary(&$ary, $element, $pos) {
    $ar1=array_slice($ary, 0, $pos); $ar1[]=$element;
    $ary=array_merge($ar1, array_slice($ary, $pos));
  }
    
?>