<?php 

$router = new \Phalcon\Mvc\Router(false);
$router->clear();

$router->add('/',array(
	'module' => 'backoffice',
	'controller' => 'index',
	'action' => 'index'
));

return $router;