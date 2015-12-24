<?php

namespace App\Core\Listeners;

class ApiListener extends \Phalcon\Mvc\User\Plugin {

	//Triggered before executing the controller/action method. At this point the 
	//dispatcher has initialized the controller and knows if the action exists.
	public function beforeExecuteRoute($event, $dispatcher) {
		$hasValidKey = $this->checkForValidApiKey();
		$ipRateLimit = $this->checkIpRateLimit();

		if($hasValidKey == false || $ipRateLimit == false) {
			return false;
		}

		if($this->resourceWithToken() == false) {
			return false;
		}
	}

	private function checkForValidApiKey() {
		$apiKey = $this->request->getHeader('APIKEY');

		if(!in_array($apiKey, $this->config->apiKeys->toArray())) {
			$this->response->setStatusCode(403, 'Forbidden');
			$this->response->sendHeaders();
			$this->response->send();
			$this->view->disable();

			return false;
		}

		return true;
	}

	private function checkIpRateLimit() {
		$ip = $this->request->getClientAddress();
		$time = time();
		$key = $ip.':'.$time;

		$redis = $this->getDI()->get('redis');
		$current = $redis->get($key);//Get the value related to the specified key

		if($current != null && $current > 5) {
			$this->response->setStatusCode(429, 'Too Many Requests');
			$this->response->sendHeaders();
			$this->response->send();
			$this->view->disable();

			return false;
		} else {
			//returns the Redis instance and enters multi-mode. Once in multi-mode, 
			//all subsequent method calls return the same object until exec() is called.
			$redis->multi();

			$redis->incr($key, 1);//Increment the number stored at key by one.
			$redis->expire($key, 5);//Sets an expiration date (a timeout) on an item. pexpire requires a TTL in milliseconds.

			$redis->exec();

		}

		return true;
	}

	private function resourceWithToken() {
		if(in_array($this->dispatcher->getActionName(), ['update','delete','create'])) {
			if($this->request->getHeader('TOKEN') != 'mySecretToken') {
				$this->response->setStatusCode(405, 'Method Not Allowed');
				$this->response->sendHeaders();
				$this->response->send();
				$this->view->disable();

				return false;
			}
			return true;
		}
	}



}