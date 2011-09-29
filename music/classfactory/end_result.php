<?PHP

// Generate class! ---------------------------------------------------------------------------------

require_once('includes/config.php');

$dbdatabasename = stripslashes($_REQUEST['dbdatabasename']);
$dbtablename = stripslashes($_REQUEST['dbtablename']);

if($dbdatabasename) {
	if(!mysql_select_db($dbdatabasename, $linkLocal)) {
		exit();
	}
}

$class = stripslashes($_POST['class']);
$arrMembers = explode("\n",$_POST['members']);	

foreach($arrMembers AS $k => $v) {
	$v = str_replace("\r","",$v);
	$v = str_replace("\n","",$v);
	$v = str_replace("\t","",$v);
	$arrMembers[$k] = $v;
}

$tableStructure = array();
$strSQL = "DESCRIBE ".$dbtablename;
$qid = mysql_query($strSQL);		
while($rs = mysql_fetch_assoc($qid)) {
	$tableStructure[] = $rs;
}

foreach($tableStructure AS $k => $v) {
	if($v['Key'] == "PRI") {
	
		$primaryKey = $v['Field'];			
		
		$primaryKeyOK = ereg_replace("[^A-Za-z0-9_]", "", $primaryKey);
		if($primaryKey != $primaryKeyOK) {
			$primaryKey = "`".$primaryKey."`";
		}
		
		$primaryKeyIndex = $k;	
		$primaryKeyName = strtoupper(substr($arrMembers[$k],0,1));
		$primaryKeyName = $primaryKeyName.substr($arrMembers[$k],1);
	
	}
}

require_once('includes/header.php');
	
?>
<div id="breadcrumbs"><a href="index.php">Home</a> &gt; <a href="/pick_table.php?dbdatabasename=<?=$dbdatabasename?>"><?=$dbdatabasename?></a> &gt; <a href="/generate_class.php?dbdatabasename=<?=$dbdatabasename?>&dbtablename=<?=$dbtablename?>"><?=$dbtablename?></a> &gt; Your Class</div>
<div>
	<h1>Your Class!</h1>
	
	<textarea style="width:750px;height:500px" id="classresult">
<?

// Opening class definition ------------------------------------------------------------------------
	
$str = "<?php\n\n";	
echo $str;

$str = "require_once(\"includes/SuperDate.php\");\n";
$str.= "require_once(\"includes/DBExporter.php\");\n\n";
$str.= "class ".$class." extends DBExporter {\n";
echo $str;

// Any const values need defining for ENUMS?

foreach($tableStructure AS $k => $v) {
	if(strpos($v['Type'],'enum')!==false) {
	
		$str = "\n	//".$v['Field']."\n";
		echo $str;
	
		$name = strtoupper($arrMembers[$k]);
	
		$arrTemp = explode("(",$v['Type']);
		$arrTemp = explode(")",$arrTemp[1]);
		$arrTemp = explode(",",$arrTemp[0]);
		foreach($arrTemp AS $t => $w) {
						
			$w = substr($w,1,-1);
			$wname = strtoupper($w);
			if($wname=="") {
				$wname = "NONE";
			}
			
			$str = "	const ".$name."_".$wname." = '".$w."';\n";
			echo $str;
			
		}
	
	}
}

$str = "\n	// members\n";
echo $str;
	
foreach($arrMembers AS $k => $v) {

	$str = "	protected $".$v.";\n";
	echo $str;
	
}

// Constructor -------------------------------------------------------------------------------------

$str = "\n	// constructor\n";
$str.= "	public function __construct";
	
if($primaryKey) {
	$str.="($".$arrMembers[$primaryKeyIndex]."=false) {\n";
} else {
	$str.="($id=false) {\n";
}

echo $str;
	
// List any Superdate objects ----------------------------------------------------------------------
	
foreach($arrMembers AS $k => $v) {
	if($tableStructure[$k]['Type'] == "datetime" || $tableStructure[$k]['Type'] == "date") {
		$str = "		\$this->".$v." = new SuperDate();\n";
		echo $str;
	}
}

if($primaryKey) {
	$str = "		if($".$arrMembers[$primaryKeyIndex].") {\n";
	$str.= "			\$this->loadFromDB($".$arrMembers[$primaryKeyIndex].");\n";
	$str.= "		}\n";
	echo $str;
} else {
	$str = "		if($"."id) {\n";
	$str.= "			\$this->loadFromDB($"."id);\n";
	$str.= "		}\n";
	echo $str;
}
	
$str = "	}\n\n";
echo $str;
	
	// Build setters & getters -----------------------------------------------------------------------
	
	foreach($arrMembers AS $k => $v) {

	$name = strtoupper(substr($v,0,1));
	$name = $name.substr($v,1);
	
	// Timedate/Getter/Setter
	if($tableStructure[$k]['Type'] == "datetime" || $tableStructure[$k]['Type'] == "date") {
	
?>
	public function get<?=$name?>() {
		return $this-><?=$v?>;
	}
	
	public function set<?=$name?>($<?=$v?>) {
		if(is_object($<?=$v?>) && get_class($<?=$v?>)=="SuperDate") {
			if($this-><?=$v?>->getAsSeconds() != $<?=$v?>->getAsSeconds()) {
				$this-><?=$v?> = $<?=$v?>;
				$this->markDirty();          
			}
		} else {
			if(!$this-><?=$v?>->equals($<?=$v?>)) {
				$this-><?=$v?>->initialiseMySQLDate($<?=$v?>);
				$this->markDirty();          
			}
		}
	}

<?
	
	} else {	
	
?>
	public function get<?=$name?>() {
		return $this-><?=$v?>;
	}
			
	public function set<?=$name?>($<?=$v?>) {
<?
	if(strpos($tableStructure[$k]['Type'],"enum")!==false) {
		// Drop in assert...		
?>		assert(in_array($<?=$v?>,$this->get<?=$name?>Enumeration()));
<?	
	}
?>		if($<?=$v?>!=$this-><?=$v?>) {
			$this->markDirty();          
			$this-><?=$v?> = $<?=$v?>;
		}
	}

<?

	}

	
	}	

	
	// Build enumeration functions -------------------------------------------------------------------

	foreach($tableStructure AS $k => $v) {
		if(strpos($v['Type'],'enum')!==false) {
		
			$name = strtoupper(substr($arrMembers[$k],0,1));
			$name = $name.substr($arrMembers[$k],1);
		
?>
		
	// create array for <?=$v['Field']?> enumeration
	public function get<?=$name?>Enumeration() {
		$arr = array();
<?

		$name = strtoupper($arrMembers[$k]);
		
		$arrTemp = explode("(",$v['Type']);
		$arrTemp = explode(")",$arrTemp[1]);
		$arrTemp = explode(",",$arrTemp[0]);
		foreach($arrTemp AS $t => $w) {
							
			$w = substr($w,1,-1);
			$wname = strtoupper($w);
			if($wname=="") {
				$wname = "NONE";
			}
				
?>		$arr[] = <?=$class?>::<?=$name?>_<?=$wname?>;
<?						
		}
?>
		return $arr;
	}
	
<?

		}
	}

	
	// Build a loadFromArray -------------------------------------------------------------------------
	
?>
	// allow all values to be set by passing in an associative array
	public function loadFromArray($p) {  
	<?
	
	foreach($arrMembers AS $k => $v) {
	
		$name = strtoupper(substr($v,0,1));
		$name = $name.substr($v,1);
	
		?>	$this->set<?=$name?>($p['<?=$tableStructure[$k]['Field']?>']);
	<?
	}
	
	?>
	$this->markUnchanged();
	}

<?

	// Build a loadFromDB ----------------------------------------------------------------------------
	
?>	
	// Loads from the database given an id
	public function loadFromDB($id) {
		global $mysqlread;
		// load the data
		$query = "SELECT * FROM <?=$dbtablename?> WHERE <?
	
		// Do we have a primary key? It'll be this!
		if($primaryKey) {
	?><?=$primaryKey?> = '".mysql_escape_string($id)."'";
	<?
		}
		
		else {
	?>???;
	<?
		}
		
	?>
	$p = $mysqlread->getSingleRow($query);
		if($p) {
			$this->loadFromArray($p);
			return 1;
		}
		return 0;
	}

<?

	// Build an updateDB -----------------------------------------------------------------------------

$str = "	// Updates an existing object in the database, overwriting the values in this object\n";
$str.= "	public function updateDB() {\n";
$str.= "		global \$mysqlwrite;\n";
$str.= "		// update the database\n";
$str.= "		\$query = \"REPLACE INTO ".$dbtablename."\n";
$str.= "			    				SET ";					
echo $str;						
					
$str = "";
foreach($arrMembers AS $k => $v) {

	$name = strtoupper(substr($v,0,1));
	$name = $name.substr($v,1);

	$fieldname = $tableStructure[$k]['Field'];	
	$fieldnameok = ereg_replace("[^A-Za-z0-9_]", "", $fieldname);
	if($fieldname != $fieldnameok) {
		$fieldname = "`".$fieldname."`";
	}

	if($k > 0) {
		$str.="											";
	}
	if($tableStructure[$k]['Type'] == "datetime") {		
		$str.= $fieldname." = '\".mysql_escape_string(\$this->get".$name."()->getAsMySQLDate().\" \".\$this->get".$name."()->getAsTime()).\"',\n";
	} elseif($tableStructure[$k]['Type'] == "date") {		
		$str.= $fieldname." = '\".mysql_escape_string(\$this->get".$name."()->getAsMySQLDate()).\"',\n";
	} else {		
		$str.= $fieldname." = '\".mysql_escape_string(\$this->get".$name."()).\"',\n";
	}
	
}

$str = substr($str,0,-2);
echo $str;

$str = "\";\n";
$str.= "		\$mysqlwrite->doQuery(\$query);\n";
$str.= "		\$this->markUnchanged();\n";
$str.= "	}\n";
echo $str;

	// Build a deleteDB ------------------------------------------------------------------------------
	
$str = "\n	// Delete this object\n";
$str.= "	public function deleteDB() {\n";
$str.= "		global \$mysqlwrite;\n";
$str.= "		// delete this id\n";
$str.= "		\$query = \"DELETE FROM ".$dbtablename." WHERE ";
echo $str;
		
	// Do we have a primary key? It'll be this!
		
	if($primaryKey) {
		$str = $primaryKey." = '\".mysql_escape_string(\$this->get".$primaryKeyName."()).\"'\";\n";
		echo $str;
	} else {
		$str = "\n\n\n???;\n\n\n\n";
		echo $str;
	}
			
$str = "		\$mysqlwrite->doQuery(\$query);\n";
$str.= "	}\n";
echo $str;

	// Build a saveDB --------------------------------------------------------------------------------
	
$str = "\n	// Saves this to the database for the first time\n";
$str.= "	public function saveDB() {\n";
$str.= "		global \$mysqlwrite;\n";
$str.= "		// update the database\n";
$str.= "		\$query = \"INSERT INTO ".$dbtablename."\n";
$str.= "								SET ";
echo $str;
		
$str = "";
$boolFirst = true;
foreach($arrMembers AS $k => $v) {
	if($tableStructure[$k]['Key'] != "PRI") {

		$name = strtoupper(substr($v,0,1));
		$name = $name.substr($v,1);
		
		$fieldname = $tableStructure[$k]['Field'];	
		$fieldnameok = ereg_replace("[^A-Za-z0-9_]", "", $fieldname);
		if($fieldname != $fieldnameok) {
			$fieldname = "`".$fieldname."`";
		}

		if(!$boolFirst) {
			$str.="										";
		}
		if($tableStructure[$k]['Type'] == "datetime") {		
			$str.= $fieldname." = '\".mysql_escape_string(\$this->get".$name."()->getAsMySQLDate().\" \".\$this->get".$name."()->getAsTime()).\"',\n";
		} elseif($tableStructure[$k]['Type'] == "date") {		
			$str.= $fieldname." = '\".mysql_escape_string(\$this->get".$name."()->getAsMySQLDate()).\"',\n";
		} else {			
			$str.= $fieldname." = '\".mysql_escape_string(\$this->get".$name."()).\"',\n";
		}
		$boolFirst = false;
		
	}
}

$str = substr($str,0,-2);
echo $str;
	
$str ="\";\n";
echo $str;
	
if($primaryKey) {
	// Assign primary key!
	$str = "		\$this->set".$primaryKeyName."(\$mysqlwrite->doQuery(\$query));\n";
	echo $str;
}
	
$str = "		\$this->markUnchanged();\n";
$str.= "	}\n\n";
echo $str;

// Closing class definition
	
$str = "}\n\n";
echo $str;

echo "?>";

?>
	</textarea>
	<script type="text/javascript">
		$('classresult').focus();
		$('classresult').select();
	</script>
</div>
<?

require_once('includes/footer.php');

?>