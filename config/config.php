<?php 

return new \Phalcon\Config(array(
	'application' => array(
		'name' => 'Learning Phalcon'
	),
	'root_dir' => __DIR__.'/../',
	'redis' => array(
		'host' => '127.0.0.1',
		'port' => 6379
	),
	'session' => array(
		'unique_id' => 'learning_phalcon',
		'name' => 'learning_phalcon',
		'path' => 'tcp://127.0.0.1:6379?weight=1'
	),
	'view' => array(
		'cache' => array(
			'dir' => __DIR__.'/../cache/volt'
		)
	),
	'database' => array(
		'adapter' => 'Mysql',
		'host' => 'localhost',
		'username' => 'phalcon_user',
		'password' => 'password',
		'dbname' => 'learning_phalcon'
	),
	'apiKeys' => array(
		'serjg89s85g90s85g9w8409g8s09g8sr09g8se09g8e90g859e0g8sseg98j'
	)
));