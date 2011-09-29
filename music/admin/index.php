<?php
error_reporting(E_ALL);
ini_set('display_errors','On');

if(isset($_GET['page'])){ $page = $_GET['page']; }
else{ $page = "game"; }
if($page=="/kunden/homepages/40/d242703152/htdocs/gygus/website"){ header('Location: http://www.gygus.co.uk/website/'); }

require_once("includes/tools.php");
require_once("includes/html.php");
require_once("includes/config.php");
require_once('includes/url.php');











$url = new url_data($page);
$filename = './controllers/'.$url->controller.'.php';




if (file_exists($filename)){ 

	require_once('./controllers/'.$url->controller.'.php');
}
else{
	require_once('./controllers/game.php');
}


$controller = new controller($url);  




?>