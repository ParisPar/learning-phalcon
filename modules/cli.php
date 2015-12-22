#!/usr/bin/env php
<?php

/*

 As in regular MVC applications, a bootstrap file is used to bootstrap 
 the application. Instead of the index.php bootstrapper in web applications, 
 we use a cli.php file for bootstrapping the application.

*/

umask(0022);
set_time_limit(1200);

//Include Composer's autoloader
require_once __DIR__.'/../vendor/autoload.php';

use Phalcon\DI\FactoryDefault\CLI as CliDI;
use Phalcon\CLI\Console as ConsoleApp;
use Crada\Apidoc\Extractor;

class Cli
{
    private $arguments;
    private $params;
    private $console;

    public function __construct($argv)
    {
        //Using the CLI factory default services container
        $di = new CliDI();

        //Register the autoloader and tell it to register the tasks directory
        include __DIR__ .'/../config/loader.php';

        //Load the configuration file
        $config  = include __DIR__ . '/../config/config.php';

        $di->set('config', $config);

        include __DIR__ . '/../config/services.php';

        //Create a console application
        $console = new ConsoleApp();
        $console->setDI($di);

        //Process the console arguments
        foreach ($argv as $k => $arg) {
            if ($k == 1) {
                $this->arguments['task'] = $arg;
            } elseif ($k == 2) {
                $this->arguments['action'] = $arg;
            } elseif ($k >= 3) {
                $this->params[] = $arg;
            }
        }

        if (count($this->params) > 0) {
            $this->arguments['params'] = $this->params;
        }

        $this->console = $console;
    }

    public function readTasks()
    {
        if ($handle = opendir(__DIR__.'/Task/')) {

            require_once __DIR__.'/Task/BaseTask.php';
            $util = new BaseTask();
            $util->consoleLog('Learning Phalcon CLI','grey');
            $util->consoleLog(str_repeat('-', 80),'grey');


            while (false !== ($entry = readdir($handle))) {
                if ($entry != '.' && $entry != '..' && $entry != 'BaseTask.php' && preg_match("/\.php$/",$entry)) {
                    $entries[] = $entry;
                }
            }

            asort($entries);

            $charCountActionName = 0;

            foreach ($entries as $entry) {
                $task = str_replace('Task.php', '', $entry);

                require_once __DIR__.'/Task/'.$entry;
                $tmp_className = str_replace('.php','',$entry);
                $tmp = new $tmp_className();

                $taskName = PHP_EOL.strtolower(preg_replace('/\B([A-Z])/', '_$1', $task));
                $taskDescription = '';

                $util->consoleLog(str_pad($taskName, 25).$taskDescription, 'yellow');

                $st_classMethods = get_class_methods($tmp);
                asort($st_classMethods);

                foreach ($st_classMethods as $value) {
                    if (preg_match('/Action/', $value)) {

                        $theActionName = str_pad(str_replace('Action', '', $value), 6);
                        if (strlen($theActionName) > $charCountActionName) {
                            $charCountActionName = strlen($theActionName);
                        }
                    }
                }

                foreach ($st_classMethods as $value) {
                    if (preg_match('/Action/', $value)) {

                        $theActionName = str_replace('Action', '', $value);
                        $theActionDescription = '';
                        $annotations = Extractor::getMethodAnnotations($tmp_className, $value);

                        if (count($annotations) > 0) {
                            foreach ($annotations as $key => $st_values) {
                                if ($key == 'Description') {
                                    $theActionDescription .= implode(', ', $st_values);
                                }
                            }
                        }
                        $util->consoleLog(str_pad($theActionName, $charCountActionName + 5)."\033[0;28m".$theActionDescription, 'green');
                    }
                }
            }
            closedir($handle);
        }
    }

    public function getArguments()
    {
        return $this->arguments;
    }

    public function getConsole()
    {
        return $this->console;
    }
}


try {
    $cli       = new Cli($argv);
    $arguments = $cli->getArguments();

    if (0 === count($arguments)) {
        $cli->readTasks();
    } else {
        $console = $cli->getConsole();
        $console->handle($arguments);
    }
} catch (\Phalcon\Exception $e) {
    echo $e->getMessage();
}
