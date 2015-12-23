<?php 

$versions = [
	'v1' => '/api/v1',
	'v2' => '/api/v2'
];

$router->removeExtraSlashes(true);

//If a set of routes have common paths they can be grouped to easily maintain them:
$articles = new \Phalcon\Mvc\Router\Group(array(
	'module' => 'api',
	'controller' => 'articles'
));

// All the routes start with /api/v1
$articles->setPrefix($versions['v1'].'/articles');

$articles->addGet('',array(
	'module' => 'api',
	'controller' => 'articles',
	'action'  => 'list'
));

$articles->addGet('/{id}',array(
	'module' => 'api',
	'controller' => 'articles',
	'action'  => 'get'
));

$articles->addPut('/{id}',array(
	'module' => 'api',
	'controller' => 'articles',
	'action'  => 'update'
));

$articles->addDelete('/{id}',array(
	'module' => 'api',
	'controller' => 'articles',
	'action'  => 'delete'
));

$articles->addPost('',array(
	'module' => 'api',
	'controller' => 'articles',
	'action'  => 'create'
));



// Add the group to the router
$router->mount($articles);
