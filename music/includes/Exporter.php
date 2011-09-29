<?php

  /*
   ==================================================================================
   Project     : SEOTrackz
   File        : seotrackz/includes/Exporter.php
   Description : A class that can be exported as a XML, Text, JSON or PHP-seralized format.
   Parameters  : -
   Author      : Glynn Bird
   Date        : August 2007
   ==================================================================================
   */
  require_once("includes/XMLTools.php");
  
  class Exporter {
    
    // enumeration of $xmlFormat indicating whether the XML format is many elements or a single element with several attributes
    const MANY_ELEMENTS = "many_elements";
    const SINGLE_ELEMENT = "single_element";
    
    // a list of member variables that are never included in XML serializations of this object
    const IGNORE_ATTRIBUTES = "dirtyStatus,xmlFormat,xsltProcessor,brandingFile";
    
    // the format of the XML
    protected $xmlFormat = Exporter::MANY_ELEMENTS;
   
    // return the top of an XML document
    public function getXMLDeclaration() {
      return "<"."?"."xml version=\"1.0\""."?".">\n";
    }
    
    // setter for $xmlFormat
    public function setXmlFormat($xmlFormat) {
      assert($xmlFormat == Exporter::MANY_ELEMENTS || $xmlFormat == Exporter::SINGLE_ELEMENT);
      $this->xmlFormat=$xmlFormat;
    }
  
    // getter for $xmlFormat
    public function getXmlFormat() {
      return $this->xmlFormat;
    }
    
    // Gives an XML version of the class. This objected goes through each attribute in the class and renders it as XML. 
    // It handles attributes which are arrays of objects derived from Exporter and arrays of other basic data types.
    
    public function exportAsXML($strStoreTag=NULL) {
      // create list of attributes to ignore     
      $ignore = explode(",", Exporter::IGNORE_ATTRIBUTES);      

      // get each attribute of this class
      $classVars = get_object_vars($this);
      $xml = "";

      // if we are to create many elements
      if($this->getXmlFormat() == Exporter::MANY_ELEMENTS) {
        
        // iterate through each attribute
        foreach ($classVars as $key=>$val) {
        
          // if this is an array
          if(is_array($val)) {

            // add a new element          
            $xml .= sprintf("<%s>\n",$key);  
            
            // iterate through each member of the array
            foreach($val as $newkey=>$newval) {
                            
              // Put an encapsulating tag around every store item  
              if($key == "store" && $strStoreTag)
              {
                $xml.="<".$strStoreTag.">\n";
              }              
              
              // if the memember is itself an Exporter object
              if(get_class($newval) && is_subclass_of($newval,'Exporter')) {
                // call its exportAsXML function
                $xml .= $newval->exportAsXML();
              } else {
                // render this value as XML
                if(array_search($newkey, $ignore)===FALSE) {
                  $xml .= XMLTools::toXML($newkey,$newval);
                }
              }              
              
              // Close encapsulating tag around every store item  
              if($key == "store" && $strStoreTag)
              {
                $xml.="</".$strStoreTag.">\n";
              }            

            }
            
            // end the encapsulating element  
            $xml .= sprintf("</%s>\n",$key);
            
          } else {
            
            // if the memember is itself an Exporter object
            if(get_class($val) && is_subclass_of($val,'Exporter')) {
              // add a new element
              $xml .= sprintf("<%s>\n",$key);

              // call its exportAsXML function
              $xml .= $val->exportAsXML();
              
              // add a new element
              $xml .= sprintf("</%s>\n",$key);

            } else {
              // render this value as XML
              if(array_search($key, $ignore)===FALSE) {
                $xml .= XMLTools::toXML($key,$val);
              }
            }                

          }  

         }
        
      }  else {
        // create single element with lots of attributes       
        // add a new element
        $xml .= sprintf("<%s ", get_class($this));
        // iterate through each attribute
        foreach ($classVars as $key=>$val) {
          if(array_search($key, $ignore)===FALSE) {
            $xml .= sprintf("%s=\"%s\" ",$key, XMLTools::xmlify($val));
          }  
        }
        $xml .= "/>\n";
      }
      
      return $xml;
    }
    
    
    // create an array before 'jsoning' it
    function objectArray( $object ) {
      
      if ( is_array( $object ))
        return $object ;
      
      if ( !is_object( $object ))
        return false ;
      
      $serial = serialize( $object ) ;
      $serial = preg_replace( '/O:\d+:".+?"/' ,'a' , $serial ) ;
      if( preg_match_all( '/s:\d+:"\\0.+?\\0(.+?)"/' , $serial, $ms, PREG_SET_ORDER )) {
        foreach( $ms as $m ) {
          $serial = str_replace( $m[0], 's:'. strlen( $m[1] ) . ':"'.$m[1] . '"', $serial ) ;
        }
      }

     return @unserialize( $serial ) ;

    }

    // Gives a JSON version of the class
    public function ExportAsJSON() {
     return json_encode($this->objectArray($this));
    //  return json_encode($this);
    }
    
    // Gives a text version of the class, for debugging
    public function ExportAsText() {
      return print_r($this,true);
    }
    
    // Gives a PHP version of this class
    public function ExportAsPHP() {
      return serialize($this);
    }
    
    
  }


?>
