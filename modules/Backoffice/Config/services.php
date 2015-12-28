<?php 

$di['dispatcher'] = function() use ($di) {
	$eventsManager = $di->getShared('eventsManager');

  $eventsManager->attach('dispatch', new App\Core\Security\Acl($di));

  $dispatcher = new \Phalcon\Mvc\Dispatcher();
  $dispatcher->setEventsManager($eventsManager);
  $dispatcher->setDefaultNamespace('App\Backoffice\Controllers');

  return $dispatcher;
};

$di['url']->setBaseUri(''.$config->application->baseUri.'');

$di['view'] = function () {
  $view = new \Phalcon\Mvc\View();
  $view->setViewsDir(__DIR__.'/../Views/Default/');

  //Use the global voltSevice we defined in config/services.php
  $view->registerEngines(array(
    '.volt' => 'voltService'
  ));

  return $view;
};

$di['auth'] = function() {
  return new App\Core\Security\Auth();
};
