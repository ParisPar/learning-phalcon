<?php 

//Assign the global configuration file
$config = require __DIR__.'/../../../config/config.php';

//Assign module specific configuration
$module_config = new \Phalcon\Config(array(
	'application' => array(
		'controllersDir' => __DIR__.'/../Controllers/',
		'modelsDir' => __DIR__.'/../Models/',
		'viewsDir' => __DIR__.'/../Views/',
		'baseUri' => '/learning-phalcon/backoffice/',
		'cryptSalt' => 'aoj90g89d5jgd95g8',
		'publicUrl' => 'http://test.com/learning-phalcon/'
	)
));

$config->merge($module_config);

return $config;