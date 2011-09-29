<?php

require_once("includes/SuperDate.php");
require_once("includes/DBExporter.php");

class ActivityLog extends DBExporter {

	// members
	protected $id;
	protected $datetime;
	protected $userId;
	protected $moduleId;
	protected $description;

	// constructor
	public function __construct($id=false) {
		$this->datetime = new SuperDate();
		if($id) {
			$this->loadFromDB($id);
		}
	}

	public function getId() {
		return $this->id;
	}
			
	public function setId($id) {
		if($id!=$this->id) {
			$this->markDirty();          
			$this->id = $id;
		}
	}

	public function getDatetime() {
		return $this->datetime;
	}
	
	public function setDatetime($datetime) {
		if(is_object($datetime) && get_class($datetime)=="SuperDate") {
			if($this->datetime->getAsSeconds() != $datetime->getAsSeconds()) {
				$this->datetime = $datetime;
				$this->markDirty();          
			}
		} else {
			if(!$this->datetime->equals($datetime)) {
				$this->datetime->initialiseMySQLDate($datetime);
				$this->markDirty();          
			}
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

	public function getModuleId() {
		return $this->moduleId;
	}
			
	public function setModuleId($moduleId) {
		if($moduleId!=$this->moduleId) {
			$this->markDirty();          
			$this->moduleId = $moduleId;
		}
	}

	public function getDescription() {
		return $this->description;
	}
			
	public function setDescription($description) {
		if($description!=$this->description) {
			$this->markDirty();          
			$this->description = $description;
		}
	}

	// allow all values to be set by passing in an associative array
	public function loadFromArray($p) {  
		$this->setId($p['id']);
		$this->setDatetime($p['datetime']);
		$this->setUserId($p['user_id']);
		$this->setModuleId($p['module_id']);
		$this->setDescription($p['description']);
		$this->markUnchanged();
	}

	
	// Loads from the database given an id
	public function loadFromDB($id) {
		global $mysqlread;
		// load the data
		$query = "SELECT * FROM activity_log WHERE id = '".mysql_escape_string($id)."'";
		$p = $mysqlread->getSingleRow($query);
		if($p) {
			$this->loadFromArray($p);
			return 1;
		}
		return 0;
	}

	// Updates an existing object in the database, overwriting the values in this object
	public function updateDB() {
		global $mysqlwrite;
		// update the database
		$query = "REPLACE INTO activity_log
			    				SET id = '".mysql_escape_string($this->getId())."',
											datetime = '".mysql_escape_string($this->getDatetime()->getAsMySQLDate()." ".$this->getDatetime()->getAsTime())."',
											user_id = '".mysql_escape_string($this->getUserId())."',
											module_id = '".mysql_escape_string($this->getModuleId())."',
											description = '".mysql_escape_string($this->getDescription())."'";
		$mysqlwrite->doQuery($query);
		$this->markUnchanged();
	}

	// Delete this object
	public function deleteDB() {
		global $mysqlwrite;
		// delete this id
		$query = "DELETE FROM activity_log WHERE id = '".mysql_escape_string($this->getId())."'";
		$mysqlwrite->doQuery($query);
	}

	// Saves this to the database for the first time
	public function saveDB() {
		global $mysqlwrite;
		// update the database
		$query = "INSERT INTO activity_log
								SET datetime = '".mysql_escape_string($this->getDatetime()->getAsMySQLDate()." ".$this->getDatetime()->getAsTime())."',
										user_id = '".mysql_escape_string($this->getUserId())."',
										module_id = '".mysql_escape_string($this->getModuleId())."',
										description = '".mysql_escape_string($this->getDescription())."'";
		$this->setId($mysqlwrite->doQuery($query));
		$this->markUnchanged();
	}

}

?>	