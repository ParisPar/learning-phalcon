<?php 

//Assign the global configuration file
$config = require __DIR__.'/../../../config/config.php';

//Assign module specific configuration
$module_config = array(
	'application' => array(
		'controllersDir' => __DIR__.'/../Controllers',
		'controllersDir' => __DIR__.'/../Controllers',
		'controllersDir' => __DIR__.'/../Controllers',
		'baseUri' => '/',
		'cryptSalt' => 'aoj90g89d5jgd95g8',
		'publicUrl' => 'http://test.com/learning-phalcon/'
	)
);

$config->merge($module_config);

return $config;