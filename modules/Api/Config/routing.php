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
	'action'  => 'list'
));

$articles->addGet('/{id}',array(
	'action'  => 'get'
));

$articles->addPut('/{id}',array(
	'action'  => 'update'
));

$articles->addDelete('/{id}',array(
	'action'  => 'delete'
));

$articles->addPost('',array(
	'action'  => 'create'
));

$hashtags = new \Phalcon\Mvc\Router\Group(array(
	'module' => 'api',
	'controller' => 'hashtags'
));

$hashtags->setPrefix($versions['v1'].'/hashtags');

$hashtags->addGet('',array(
	'action' => 'list'
));

$hashtags->addGet('/{id}',array(
	'action' => 'get'
));

$hashtags->addPut('/{id}',array(
	'action' => 'update'
));

$hashtags->addDelete('/{id}',array(
	'action' => 'delete'
));

$hashtags->addPost('',array(
	'action' => 'create'
));

// Categories group
$categories = new \Phalcon\Mvc\Router\Group([
    'module' => 'api',
    'controller' => 'categories',
]);

$categories->setPrefix($versions['v1'].'/categories');

$categories->addGet('',         ['action' => 'list']);
$categories->addGet('/{id}',    ['action' => 'get']);
$categories->addPut('/{id}',    ['action' => 'update']);
$categories->addDelete('/{id}', ['action' => 'delete']);
$categories->addPost('',        ['action' => 'create']);

$router->mount($categories);



// Add the group to the router
$router->mount($articles);
$router->mount($hashtags);
