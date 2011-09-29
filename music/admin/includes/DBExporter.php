<?php

  /*
   ==================================================================================
   Project     : SEOTrackz
   File        : seotrackz/includes/DBExporter.php
   Description : A class that can be serialized as XML, text, JSON and PHP formats. 
                 This object also maintains a “dirtyStatus” to record whether the the 
                 object needs writing to the database or not. Other classes are derived 
                 from Exporter and are expected to override loadFromDB(), updateDB() and 
                 saveDB(). The “setters” of derived classes should set the dirtyStatus to “dirty”. 
   Parameters  : -
   Author      : Glynn Bird
   Date        : September 2007
   ==================================================================================
   */

  require_once("includes/Exporter.php");

  abstract class DBExporter extends Exporter {

    // enumeration of $dirtyStatus indicating whether this object needs saving or not
    const DIRTY_STATUS_UNCHANGED = "UNCHANGED";
    const DIRTY_STATUS_DIRTY = "DIRTY";
    const DIRTY_STATUS_DELETED = "DELETED";
    const DIRTY_STATUS_NEW = "NEW";
        
    // whether this object
    protected $dirtyStatus = DBExporter::DIRTY_STATUS_UNCHANGED;
    
    // setter for dirtyStatus
    public function setDirtyStatus($dirtyStatus) {
      assert($dirtyStatus == DBExporter::DIRTY_STATUS_UNCHANGED ||
             $dirtyStatus == DBExporter::DIRTY_STATUS_DIRTY ||
             $dirtyStatus == DBExporter::DIRTY_STATUS_DELETED ||
             $dirtyStatus == DBExporter::DIRTY_STATUS_NEW);
      $this->dirtyStatus = $dirtyStatus;
    }
    
    // getter for dirtyStatus
    public function getDirtyStatus() {
      return $this->dirtyStatus;
    }
    
    // mark this object as dirty
    public function markDirty() {
      $this->setDirtyStatus(DBExporter::DIRTY_STATUS_DIRTY);
    }

    // mark this object as new
    public function markNew() {
      $this->setDirtyStatus(DBExporter::DIRTY_STATUS_NEW);
    }

    // mark this object as Unchanged
    public function markUnchanged() {
      $this->setDirtyStatus(DBExporter::DIRTY_STATUS_UNCHANGED);
    }
    
    // mark this object for deletion
    public function markForDeletion() {
      $this->dirtyStatus = DBExporter::DIRTY_STATUS_DELETED;
    }
  
    // Abstract: allow all values to be set by passing in an associative array
    abstract public function loadFromArray($p);
      
    // Abstract. Loads a contact from the database given an id
    abstract function loadFromDB($id);
    
    // Abstract. Updates an existing object in the database, overwriting the values in this object, 
    // or deletes the object if it was marked for deletion.
    abstract function updateDB();
    
    // Abstract. Saves this contact to the database for the first time
    abstract function saveDB();
    
    // Abstract. Deletes this object from the database 
    abstract function deleteDB();

    // save this object, and any other objects contained within it to the database
    public function save() {
      
      // save this object, if necessary
      switch($this->dirtyStatus) {
        case DBExporter::DIRTY_STATUS_NEW:    $this->saveDB();break;
        case DBExporter::DIRTY_STATUS_DIRTY:  $this->updateDB(); break;
        case DBExporter::DIRTY_STATUS_DELETED: $this->deleteDB(); break;
        case DBExporter::DIRTY_STATUS_UNCHANGED: break; // do nothing;
        default: break; // do nothing
      }
      
      // save child objects if necessary
      $classVars = get_object_vars($this);
      $xml = "";
      
      // iterate through each attribute
      foreach ($classVars as $key=>$val) {
        
        // if this is an array
        if(is_array($val)) {
          
          // iterate through each member of the array
          foreach($val as $newkey=>$newval) {
            
            // if the memember is itself an DBExporter object
            if(get_class($newval) && is_subclass_of($newval,'DBExporter')) {
              
              // call its save function
              $newval->save();
            }
          }
        } else {
          // if the memember is itself an DBExporter object
          if(get_class($val) && is_subclass_of($val,'DBExporter')) {
            
            // call its save function
            $val->save();
          }          
        }
      }
      
    }
  }


?>
