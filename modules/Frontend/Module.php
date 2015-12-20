<?php 

/*
See Phalcon documentation 'MVC Applications - Multi module'

Also github sample apps
https://github.com/phalcon/mvc/tree/master/multiple-factory-default
 */

namespace App\Frontend;

use Phalcon\Mvc\ModuleDefinitionInterface;

class Module {

	//Register a specific autoloader for the module
	public function registerAutoloaders() {

		$loader = new \Phalcon\Loader();
		$loader->registerNamespaces(array(
			'App\Frontend\Controllers' => __DIR__ . '/Controllers/',
		));
		$loader->register();
	} 

	//Register specific services for the module
	public function registerServices(\Phalcon\DiInterface $di) {
		$config = include __DIR__.'/Config/config.php';
		$di['config'] = $config;
		include __DIR__.'/Config/services.php';
	}


}