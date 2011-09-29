<?php
  /*
   ==================================================================================
   Project     : SEOTrackz
   File        : seotrackz/includes/MySQLDB.php
   Description : General MySQL functions
   Parameters  : -
   Author      : Glynn Bird
   Date        : August 2007
   ==================================================================================
   */
  
   require_once("includes/TimeTools.php");

   class MySQLDB {
     
     // the mysqli object that maintains a connection to the MySQL server
     protected $mysqli;
     
     // the error from the last query
     protected $lastError;
     
     // connection 
     protected $server;
     protected $port;
     protected $username;
     protected $password;
     protected $database;

     public $queryLog;
     public $debugMode;
     
    // construct
    public function __construct($server, $username, $password, $database, $port=3306) {
      
		//echo 'server:'.$server.'<br/>';
		//echo 'username:'.$username.'<br/>';
		//echo 'password:'.$password.'<br/>';
		//echo 'database:'.$database.'<br/>';
		//echo 'port:'.$port.'<br/>';
	  
      // store connection information for future reference
      $this->server = $server;
      $this->port = $port;
      $this->username = $username;
      $this->password = $password;
      $this->database = $database;
      $this->debugMode = 0;
      $this->queryLog = '';
      
      // clear $mysqli attribute
      $this->mysqli = false;
    }
    
    // clear MySQL connection 
    public function __destruct() {
      if($this->mysqli) {
        $this->mysqli->close();
      }
    }
    
    public function getServer() {
    	return $this->server;
    }
    
    public function getPort() {
    	return $this->port;
    }
        
    public function getUsername() {
    	return $this->username;
    }
    
    public function getPassword() {
    	return $this->password;
    }
    
    public function getDatabase() {
    	return $this->database;
    }    

    // get the number of affected rows from this operation
    public function getAffectedRows() {
      if($this->mysqli) {
        return $this->mysqli->affected_rows;
      } else {
        return FALSE;
      }
    }
    
    // do the query $query, returning the first field of the first row of results
    public function getSingleField($query) {
 
      if($this->debugMode) {
        $start = TimeTools::getMS();        
      }
      
      // make connection
      $this->makeConnection();
      $this->lastError = "";
      
      $result=$this->mysqli->query($query);
      if(!$result) {
        $this->lastError = sprintf("mysql getSingleField :".$this->mysqli->error." in ".$query."<br />\n");
        syslog(LOG_WARNING, $this->lastError);
//        print($this->lastError);
        return FALSE;
      }
      $Name=$result->fetch_row();
      $result->close();
      if($this->debugMode) {
        $this->queryLog .= $query.sprintf(" (%.03f)\n",TimeTools::getMS()-$start);
      }
      return $Name[0];
    }
    
    // do the query $query, returning the first row of results
    public function getSingleRow($query) {
 
      if($this->debugMode) {
        $start = TimeTools::getMS();        
      }
      
      // make connection
      $this->makeConnection();
      $this->lastError = "";

      $result=$this->mysqli->query($query);
      if(!$result) {
        $this->lastError = sprintf("mysql getSingleRow :".$this->mysqli->error." in ".$query."<br />\n");
        syslog(LOG_WARNING, $this->lastError);
//        print($this->lastError);
        return FALSE;
      }
      $Name=$result->fetch_assoc();
      $result->close();
      if($this->debugMode) {
        $this->queryLog .= $query.sprintf(" (%.03f)\n",TimeTools::getMS()-$start);
      }
      return $Name;

    
    }
    
    
    // do the query $query, returning the an array of results
   public function getManyRows($query) {
 
     if($this->debugMode) {
       $start = TimeTools::getMS();        
     }
     
     // make connection
     $this->makeConnection();
     $this->lastError = "";
     
      $result=$this->mysqli->query($query);
      if(!$result) {
        $this->lastError = sprintf("mysql getManyRows :".$this->mysqli->error." in ".$query."<br />\n");
        syslog(LOG_WARNING, $this->lastError);
//        print($this->lastError);
        return FALSE;
      }
      $ManyRows=FALSE;
      while($D=$result->fetch_assoc())
      {
        $ManyRows[]=$D;
      }
      $result->close();
     if($this->debugMode) {
       $this->queryLog .= $query.sprintf(" (%.03f)\n",TimeTools::getMS()-$start);
     }
     return $ManyRows;
    }
    
    
    // do the query $query, returning the an array of results
    public function getManyRowsWithKey($query,$Key) {
 
      if($this->debugMode) {
        $start = TimeTools::getMS();        
      }
      // make connection
      $this->makeConnection();
      $this->lastError = "";
      
      $result=$this->mysqli->query($query);
      if(!$result) {
        $this->lastError = sprintf("mysql getManyRowsWithKey :".$this->mysqli->error." in ".$query."<br />\n");
        syslog(LOG_WARNING, $this->lastError);
//        print($this->lastError);
        return FALSE;
      }
      while($D=$result->fetch_assoc())
      {
        $ManyRows[$D[$Key]]=$D;
      }
      $result->close();
      if($this->debugMode) {
        $this->queryLog .= $query.sprintf(" (%.03f)\n",TimeTools::getMS()-$start);
      }
      return $ManyRows;
    }
    
    // do a query, returning the insert_id, if applicable
    public function doQuery($query) {
      
      if($this->debugMode) {
        $start = TimeTools::getMS();        
      }
      
      // make connection
      $this->makeConnection();
      $this->lastError = "";
      
      // do the query
        //syslog(LOG_WARNING, $query);
      $result = $this->mysqli->query($query);
      if(!$result) {
        $this->lastError = "mysql doQuery :".$this->mysqli->error." in ".$query."<br />\n";
        syslog(LOG_WARNING, $this->lastError);
//        print($this->lastError);
        return FALSE;
      }
      if($this->debugMode) {
        $this->queryLog .= $query.sprintf(" (%.03f)\n",TimeTools::getMS()-$start);
      }
      return $this->mysqli->insert_id;
    }
    
    public function getLastError() {
      return $this->lastError;
    }
    
    // split a MYSQL date into English Format
    static function splitDate($MysqlDate,$Mode) {
      
      // Mode 0=date only  1=date+time 2=time only
      $RetVal="";
      $TD=explode(" ",$MysqlDate);
      if($Mode<2)
      {
        $Date=$TD[0];
        $DateBits=explode("-",$TD[0]);
        $Date=$DateBits[2]."/".$DateBits[1]."/".$DateBits[0];
        $RetVal=$Date;
      }
      if($Mode>=1)
      {
        if($Mode==1)
          $RetVal.=" ";
        $TimeBits=explode(":",$TD[1]);
        $Time=$TimeBits[0].":".$TimeBits[1];
        $RetVal.=$Time;
      }
      return $RetVal;
    }  
    
    // make a connection to the database
    protected function makeConnection() {
      if(!$this->mysqli || !$this->mysqli->ping()) {
        // create mysqli instance
        $this->mysqli = new mysqli($this->server, $this->username, $this->password, $this->database, $this->port);
        
        // if there was an error
        if (mysqli_connect_errno()) {
          $this->lastError = sprintf("Connect failed: %s\n", mysqli_connect_error());
          syslog(LOG_ERR,$this->lastError);
        } else {
          $this->lastError = "";
        }        
      }
    }
     
    public function getDescription() {
      return sprintf("-h %s:%s -p %s -u %s",$this->server,$this->port,"********",$this->database); 
    }
    
  }
?>
