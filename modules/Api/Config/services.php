<?php 

$di['dispatcher'] = function() use ($di) {
	$eventsManager = $di->getShared('eventsManager');

  /*
  Phalcon\Mvc\Dispatcher is able to send events to an EventsManager if it is present. 
  Events are triggered using the type “dispatch”. 
  Some events when returning boolean false could stop the active operation.
   */
  $apiListener = new \App\Core\Listeners\ApiListener();
  $eventsManager->attach('dispatch', $apiListener);//Listen to all dispatch events

  $dispatcher = new \Phalcon\Mvc\Dispatcher();
  $dispatcher->setEventsManager($eventsManager);
  $dispatcher->setDefaultNamespace('App\Api\Controllers');

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