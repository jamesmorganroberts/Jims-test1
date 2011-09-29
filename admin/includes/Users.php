<?php

require_once("includes/SuperDate.php");
require_once("includes/DBExporter.php");



class Users extends DBExporter {

	// members
	protected $id;
	protected $name;
	protected $surname;
	protected $date;
	protected $username;
	protected $password;

	// constructor
	public function __construct($id=false) {
		$this->date = new SuperDate();
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

	public function getName() {
		return $this->name;
	}
			
	public function setName($name) {
		if($name!=$this->name) {
			$this->markDirty();          
			$this->name = $name;
		}
	}

	public function getSurname() {
		return $this->surname;
	}
			
	public function setSurname($surname) {
		if($surname!=$this->surname) {
			$this->markDirty();          
			$this->surname = $surname;
		}
	}

	public function getDate() {
		return $this->date;
	}
	
	public function setDate($date) {
		if(is_object($date) && get_class($date)=="SuperDate") {
			if($this->date->getAsSeconds() != $date->getAsSeconds()) {
				$this->date = $date;
				$this->markDirty();          
			}
		} else {
			if(!$this->date->equals($date)) {
				$this->date->initialiseMySQLDate($date);
				$this->markDirty();          
			}
		}
	}

	public function getUsername() {
		return $this->username;
	}
			
	public function setUsername($username) {
		if($username!=$this->username) {
			$this->markDirty();          
			$this->username = $username;
		}
	}

	public function getPassword() {
		return $this->password;
	}
			
	public function setPassword($password) {
		if($password!=$this->password) {
			$this->markDirty();          
			$this->password = $password;
		}
	}

	// allow all values to be set by passing in an associative array
	public function loadFromArray($p) {  
		$this->setId($p['id']);
		$this->setName($p['name']);
		$this->setSurname($p['surname']);
		$this->setDate($p['date']);
		$this->setUsername($p['username']);
		$this->setPassword($p['password']);
		$this->markUnchanged();
	}

	
	// Loads from the database given an id
	public function loadFromDB($id) {
		global $mysqlread;
		// load the data
		$query = "SELECT * FROM users WHERE id = '".mysql_escape_string($id)."'";
		
		
		
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
		$query = "REPLACE INTO users
			    				SET id = '".mysql_escape_string($this->getId())."',
											name = '".mysql_escape_string($this->getName())."',
											surname = '".mysql_escape_string($this->getSurname())."',
											date = '".mysql_escape_string($this->getDate()->getAsMySQLDate()." ".$this->getDate()->getAsTime())."',
											username = '".mysql_escape_string($this->getUsername())."',
											password = '".mysql_escape_string($this->getPassword())."'";
		$mysqlwrite->doQuery($query);
		$this->markUnchanged();
	}

	// Delete this object
	public function deleteDB() {
		global $mysqlwrite;
		// delete this id
		$query = "DELETE FROM users WHERE id = '".mysql_escape_string($this->getId())."'";
		$mysqlwrite->doQuery($query);
	}

	// Saves this to the database for the first time
	public function saveDB() {
		global $mysqlwrite;
		// update the database
		$query = "INSERT INTO users
								SET name = '".mysql_escape_string($this->getName())."',
										surname = '".mysql_escape_string($this->getSurname())."',
										date = '".mysql_escape_string($this->getDate()->getAsMySQLDate()." ".$this->getDate()->getAsTime())."',
										username = '".mysql_escape_string($this->getUsername())."',
										password = '".mysql_escape_string($this->getPassword())."'";
		$this->setId($mysqlwrite->doQuery($query));
		$this->markUnchanged();
	}

}

?>	