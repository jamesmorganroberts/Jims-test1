<?PHP






if(!isset($_SESSION['user_id'])) {

	// Not logged in - kicked to index
	unset($_SESSION['user_id']);
	header("Location: index.php");
	exit;

} 


?>