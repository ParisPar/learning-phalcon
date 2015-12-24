<?php

namespace App\Core\Listeners;

class ApiListener extends \Phalcon\Mvc\User\Plugin {

	//Triggered before executing the controller/action method. At this point the 
	//dispatcher has initialized the controller and knows if the action exists.
	public function beforeExecuteRoute($event, $dispatcher) {
		$hasValidKey = $this->checkForValidApiKey();

		if($hasValidKey == false) {
			return false;
		}
	}

	public function checkForValidApiKey() {
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



}