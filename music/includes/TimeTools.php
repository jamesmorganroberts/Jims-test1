<?php

class TimeTools {
  // return number of milli seconds (for use as a timer)
  static function getMS() {
    $mtime = microtime(); 
    $mtime = explode(" ", $mtime); 
    $mtime = $mtime[1] + $mtime[0]; 
    return $mtime; 
  }  
}

?>