<?php 

error_reporting(E_ALL);
ini_set('display_errors', 1);


header('Content-Type: text/html; charset=utf-8');
mb_internal_encoding('UTF-8');

require_once __DIR__.'/../modules/Bootstrap.php';

//Add Composer autoloader
require_once __DIR__.'/../vendor/autoload.php';

$app = new Bootstrap('frontend');
$app->init();

 ?>