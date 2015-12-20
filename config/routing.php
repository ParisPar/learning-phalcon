<?php 

$di['router'] = function() use ($default_module, $modules, $di, $config) {

	//Router has a default behavior that provides a very simple 
	//routing that always expects a URI that matches the following 
	//pattern: /:controller/:action/:params
	//Passing false removes this behavior
	$router = new \Phalcon\Mvc\Router(false);

	//Removes all the pre-defined routes
	$router->clear();

	$moduleRouting = __DIR__.'/../modules/'.ucfirst($default_module).'/Config/routing.php';

	if(file_exists($moduleRouting) && is_file($moduleRouting)) {
		$router = include $moduleRouting;
	} else {
		//All routes point to the default module. In our case the Frontend module

		$router->add('#^/(|/)$#', array(
			'module' => $default_module,
			'controller' => 'index',
			'action' => 'index'
		));

		$router->add('#^/([a-zA-Z0-9\_]+)[/]{0,1}$#', array(
			'module' => $default_module,
			'controller' => 1,
		));

		$router->add('#^/{0,1}([a-zA-Z0-9\_]+)/([a-zA-Z0-9\_]+)(/.*)*$#', array(
			'module' => $default_module,
			'controller' => 'index',
			'action' => 2,
			'params' => 3
		));


	}

	return $router;
};