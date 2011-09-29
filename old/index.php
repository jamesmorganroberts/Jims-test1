<?php


error_reporting(0);
require_once('./includes/settings_jamesmroberts.php');
require_once('./models/url.php');
$url = new url_data($_GET['page']);

//----------------------------------------------



$filename = './models/'.$url->controller.'.php';

if (file_exists($filename)){
	
	// do nothing
			
} 
else { 

	$url->controller = 'html';
	$url->get_array['page'] = 'home';
}


require_once('./views/common.php');
require_once('./models/common.php');
require_once('./views/'.$url->controller.'.php');
require_once('./models/'.$url->controller.'.php');
session_start();

//------------------------------------------------------

$model = new model($url);
$view = new view($model,$url);



?>