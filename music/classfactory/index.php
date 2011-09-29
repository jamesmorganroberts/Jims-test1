<?PHP

// First page (pick a DB) --------------------------------------------------------------------------

require_once('includes/config.php');
require_once('includes/header.php');
	
?>
<div id="breadcrumbs">Home</div>
<div>
	<h1>Select Database</h1>
	
	<form method="post" action="pick_table.php">
		<?
		
		$strSQL = "SHOW DATABASES";
		$qid = mysql_query($strSQL);
		if(mysql_num_rows($qid) > 0) {
			?>
			<label for="dbdatabasename">Database : </label><select name="dbdatabasename">
			<option value="">Please Select A Database...</option>
			<?
			
			while($rs = mysql_fetch_assoc($qid)) {
				if($rs['Database']!='information_schema') {
					?><option value="<?=$rs['Database']?>"><?=$rs['Database']?></option><?			
				}
			}
			
			?>
			</select>
			<input type="submit" value="Continue ->" />
			<?
		
		} else {
		
			?>NO DATABASES IDENTIFIED! PLEASE CHECK CONFIG!<?
		
		}
		
		?>	
	</form>
</div>
<?

require_once('includes/footer.php');

?>