<?PHP

$dbhostname = "db374577124.db.1and1.com";
$dbusername = "dbo374577124";
$dbpassword = "sidfriend";

if(!$linkLocal = mysql_connect($dbhostname, $dbusername, $dbpassword, TRUE)) {
	echo mysql_error();
	exit();
}

?>