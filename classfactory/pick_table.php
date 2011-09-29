<?PHP

// Second page (pick a Table) ----------------------------------------------------------------------

require_once('includes/config.php');

$dbdatabasename = stripslashes($_REQUEST['dbdatabasename']);

if($dbdatabasename) {
	if(!mysql_select_db($dbdatabasename, $linkLocal)) {
		exit();
	}
} else {
	header("Location: index.php");
	exit();
}

$fieldname = 'Tables_in_'.$dbdatabasename;

require_once('includes/header.php');

?>
<div id="breadcrumbs"><a href="index.php">Home</a> &gt; <?=$dbdatabasename?></div>
<div>
	<h1>Select Table</h1>
	
	<form method="post" action="generate_class.php">
	<input type="hidden" name="dbdatabasename" value="<?=$dbdatabasename?>" />
		<?
		
		$strSQL = "SHOW TABLES FROM ".$dbdatabasename;
		$qid = mysql_query($strSQL);
		if(mysql_num_rows($qid) > 0) {
			?>
			<label for="dbtablename">Table : </label><select name="dbtablename">
			<option value="">Please Select A Table...</option>
			<?
			
			while($rs = mysql_fetch_assoc($qid)) {
				?><option value="<?=$rs[$fieldname]?>"><?=$rs[$fieldname]?></option><?			
			}
			
			?>
			</select>
			<input type="submit" value="Continue ->" />
			<?
		
		} else {
		
			?>NO TABLES IDENTIFIED IN DATABASE <?=$dbdatabasename?>!<?
		
		}
		
		?>	
	</form>
</div>
<?

require_once('includes/footer.php');

?>