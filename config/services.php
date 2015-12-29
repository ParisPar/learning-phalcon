<?php 

use \Phalcon\Logger\Adapter\File as Logger;

//The session service is available by default, but we overwrite it
//because we want to use Redis to store our session
$di['session'] = function() use ($config) {
	$session = new \Phalcon\Session\Adapter\Redis(array(
		'uniqueId' => $config->session->unique_id,
		'path' 		 => $config->session->path,
		'name' 		 => $config->session->name,
	));

	$session->start();

	return $session;
};

//The security session is available by default, but we overwrite it
//because we want to set it's options
$di['security'] = function() {
	$security = new \Phalcon\Security();
	$security->setWorkFactor(10);

	return $security;
};


//Connect to the Redis server
$di['redis'] = function() use ($config) {
	$redis = new \Redis();
	$redis->connect($config->redis->host, $config->redis->port);
 
	return $redis;
};

//Overwrite for backwards compatibility
$di['url'] = function() use ($config) {
	$url = new \Phalcon\Mvc\Url();

	return $url;
};

//Custom DI component to use for the Volt template engine
$di['voltService'] = function($view, $di) use ($config) {
	$volt = new \Phalcon\Mvc\View\Engine\Volt($view, $di);

	if(!is_dir($config->view->cache->dir)){
		mkdir($config->view->cache->dir);
	}

	$volt->setOptions(array(
		'compiledPath' => $config->view->cache->dir,
		'compiledExtension' => '.compiled',
		'compileAlways' => true
	));

	return $volt;
};

//Log errors in the logs folder
$di['logger'] = function() {
	$file = __DIR__.'/../logs/'.date('Y-m-d').'.log';
	$logger = new Logger($file, array(
		'mode' => 'w+'
	));

	return $logger;
};

$di['cache'] = function() use ($di, $config) {
	$frontend = new \Phalcon\Cache\Frontend\Igbinary(array(
		'lifetime' => 3600*24
	));

	$cache = new \Phalcon\Cache\Backend\Redis($frontend, array(
		'redis' => $di['redis'],
		'prefix' => $config->application->name.':'
	));

	return $cache;
};

$di['db'] = function () use ($config) {

    $connection = new \Phalcon\Db\Adapter\Pdo\Mysql(array(
        "host" => $config->database->host,
        "username" => $config->database->username,
        "password" => $config->database->password,
        "dbname" => $config->database->dbname,
        "options" => array(
            \PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'",
            \PDO::ATTR_CASE => \PDO::CASE_LOWER,
            \PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true,
            \PDO::ATTR_PERSISTENT => true
        )
    ));

    return $connection;
};

$di['core_article_manager'] = function() {
	return new App\Core\Managers\ArticleManager();
};

$di['mongo'] = function() {
	$mongo = new MongoClient();
	return $mongo->selectDB('bitpress');
};

$di['collectionManager'] = function() {
	return new \Phalcon\Mvc\Collection\Manager();
};

$di['modelsCache'] = $di['cache'];

$di['core_user_manager'] = function() {
	return new App\Core\Managers\UserManager();
};

$di['core_category_manager'] = function() {
	return new App\Core\Managers\CategoryManager();
};

$di['acl'] = function() use ($di) {

	//This namespace is defined inside Phalcon incubator
	$acl = new \Phalcon\Acl\Adapter\Database([
		'db' => $di['db'],
		'roles' => 'acl_roles',
		'rolesInherits' => 'acl_roles_inherits',
		'resources' => 'acl_resources',
		'resourcesAccesses' => 'acl_resources_accesses',
		'accessList' => 'acl_access_list'
	]);

	$acl->setDefaultAction(\Phalcon\Acl::DENY);

	return $acl;
};

