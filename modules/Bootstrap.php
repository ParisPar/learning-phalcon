<?php 

class Bootstrap extends \Phalcon\Mvc\Application {
	private $modules;
	private $default_module = 'frontend';

	public function __construct($default_module){

		$this->modules = array(
			'core' => array(
				'className' => 'App\Core\Module',
				'path'			=> __DIR__.'/Core/Module.php'
			),
			'api' => array(
				'className' => 'App\Api\Module',
				'path'			=> __DIR__.'/Api/Module.php'
			),
			'frontend' => array(
				'className' => 'App\Frontend\Module',
				'path'			=> __DIR__.'/Frontend/Module.php'
			),
			'backend' => array(
				'className' => 'App\Backend\Module',
				'path'			=> __DIR__.'/Backend/Module.php'
			),
		);

		$this->default_module = $default_module;
	}

	private function _registerServices() {
		$default_module = $this->default_module;
		$di 						= new \Phalcon\DI\FactoryDefault;
		$config					= require __DIR__.'/../config/config.php';
		$modules				= $this->modules;

		include_once __DIR__.'/../config/loader.php';
		include_once __DIR__.'/../config/services.php';
		include_once __DIR__.'/../config/routing.php';

		$this->setDI($di);
	}

	public function init(){
		$debug = new \Phalcon\Debug();
		$debug->listen();

		$this->_registerServices();

		/*
		When Phalcon\Mvc\Application have modules registered, always is necessary 
		that every matched route returns a valid module. Each registered module 
		has an associated class offering functions to set the module itself up. 
		Each module class definition must implement two methods: registerAutoloaders() 
		and registerServices(), they will be called by Phalcon\Mvc\Application 
		according to the module to be executed.
		 */
		$this->registerModules($this->modules);

		echo $this->handle()->getContent();
	}



}