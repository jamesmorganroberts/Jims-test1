<?PHP

// Class generation page (define details) ----------------------------------------------------------

require_once('includes/config.php');

$dbdatabasename = stripslashes($_REQUEST['dbdatabasename']);
$dbtablename = stripslashes($_REQUEST['dbtablename']);

if($dbdatabasename) {
	if(!mysql_select_db($dbdatabasename, $linkLocal)) {
		exit();
	}
} else {
	header("Location: index.php");
	exit();
}

if(!$dbtablename) {
	header("Location: pick_table.php?dbdatabasename=".$dbdatabasename);
	exit();
}

// Attempt default naming convention...
$strClassName = $dbtablename;
$strClassName = str_replace("_"," ",$strClassName);
$strClassName = ucwords($strClassName);
$strClassName = preg_replace('/\s\s+/', ' ', trim($strClassName));
$strClassName = str_replace(" ","",$strClassName);

$tableStructure = array();
$strSQL = "DESCRIBE ".$dbtablename;
$qid = mysql_query($strSQL);		
while($rs = mysql_fetch_assoc($qid)) {
	$tableStructure[] = $rs;
}
	
$arrFields = array();
foreach($tableStructure AS $k => $v) {
	$arrFields[] = $v['Field'];			
}
	
require_once('includes/header.php');
	
?>
<div id="breadcrumbs"><a href="/index.php">Home</a> &gt; <a href="/pick_table.php?dbdatabasename=<?=$dbdatabasename?>"><?=$dbdatabasename?></a> &gt; <?=$dbtablename?></div>
<div>
	<h1>Define Class</h1>
	
	<form method="post" action="end_result.php">
	<input type="hidden" name="dbdatabasename" value="<?=$dbdatabasename?>" />
	<input type="hidden" name="dbtablename" value="<?=$dbtablename?>" />
		
		<label for="class">Class Name : </label><br />
		<input type="text" name="class" value="<?=$strClassName?>" /><br />
		
		<label for="members">Enter Object Member Names : </label><br />
		<textarea name="members" style="width:200px;height:200px;"><?
		
		$soArrFields = sizeof($arrFields);
		foreach($arrFields AS $k => $v) {
			
			// Attempt default naming convention...
			$strName = $v;
			$strName = str_replace("_"," ",$strName);
			$strName = ucwords($strName);
			$strName = preg_replace('/\s\s+/', ' ', trim($strName));
			$strName = str_replace(" ","",$strName);
			$strName = strtolower($strName[0]).substr($strName,1);
			echo $strName;
			
			if($k<($soArrFields - 1)) {
				echo "\n";
			}
		}
		
		?></textarea><br />
		
		<input type="submit" value="Continue ->" />
	
	</form>
</div>
<?

require_once('includes/footer.php');

?>