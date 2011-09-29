<?php

require_once("includes/SuperDate.php");
require_once("includes/DBExporter.php");

class UserToBusiness extends DBExporter {

	// members
	protected $userId;
	protected $alexid;

	// constructor
	public function __construct($alexid=false) {
		if($alexid) {
			$this->loadFromDB($alexid);
		}
	}

	public function getUserId() {
		return $this->userId;
	}
			
	public function setUserId($userId) {
		if($userId!=$this->userId) {
			$this->markDirty();          
			$this->userId = $userId;
		}
	}

	public function getAlexid() {
		return $this->alexid;
	}
			
	public function setAlexid($alexid) {
		if($alexid!=$this->alexid) {
			$this->markDirty();          
			$this->alexid = $alexid;
		}
	}

	// allow all values to be set by passing in an associative array
	public function loadFromArray($p) {  
		$this->setUserId($p['user_id']);
		$this->setAlexid($p['alexid']);
		$this->markUnchanged();
	}

	
	// Loads from the database given an id
	public function loadFromDB($id) {
		global $mysqlread;
		// load the data
		$query = "SELECT * FROM user_to_business WHERE alexid = '".mysql_escape_string($id)."'";
		$p = $mysqlread->getSingleRow($query);
		if($p) {
			$this->loadFromArray($p);
			return 1;
		}
		return 0;
	}
		
	public static function getAlexids($id){

	  global $mysqlread;
	  
	  $query = "SELECT * FROM user_to_business WHERE user_id = '".mysql_escape_string($id)."'";	  
	  $alexids = $mysqlread->getManyRows($query);
	  $array = array();
		if($alexids) {
	 		foreach($alexids as $a) {
				$array[] = $a['alexid'];
	    }
	  }
	  return $array;
	    
	//- e getAlexids  
	}	

	// Updates an existing object in the database, overwriting the values in this object
	public function updateDB() {
		global $mysqlwrite;
		// update the database
		$query = "REPLACE INTO user_to_business
			    				SET user_id = '".mysql_escape_string($this->getUserId())."',
											alexid = '".mysql_escape_string($this->getAlexid())."'";
		$mysqlwrite->doQuery($query);
		$this->markUnchanged();
	}

	// Delete this object
	public function deleteDB() {
		global $mysqlwrite;
		// delete this id
		$query = "DELETE FROM user_to_business WHERE user_id = '".mysql_escape_string($this->getUserId())."' AND alexid = '".mysql_escape_string($this->getAlexid())."'";
		$mysqlwrite->doQuery($query);
	}

	// Saves this to the database for the first time
	public function saveDB() {
		global $mysqlwrite;
		// update the database
		$query = "INSERT INTO user_to_business
			    				SET user_id = '".mysql_escape_string($this->getUserId())."',
											alexid = '".mysql_escape_string($this->getAlexid())."'";
		$mysqlwrite->doQuery($query);
		$this->markUnchanged();
	}

}

?>