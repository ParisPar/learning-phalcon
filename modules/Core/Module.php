<?php 

/*
See Phalcon documentation 'MVC Applications - Multi module'
 */

namespace App\Core;

use Phalcon\Mvc\ModuleDefinitionInterface;

class Module implements ModuleDefinitionInterface {

	//Register a specific autoloader for the module
	public function registerAutoloaders(\Phalcon\DiInterface $di = null) {

	} 

	//Register specific services for the module
	public function registerServices(\Phalcon\DiInterface $di) {
		$config = include __DIR__.'/Config/config.php';
		$di['config'] = $config;
		include __DIR__.'/Config/services.php';
	}


}