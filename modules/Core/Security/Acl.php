<?php

namespace App\Core\Security;

class Acl extends \Phalcon\Mvc\User\Plugin {


	//Using the beforeDispatch method we can check for what the user is requesting, and find out where they 
	//have the role needed to access the resource. We need to enable the acl service in out global services.php
	//and also modify the dispatcher service to use it.
	public function beforeDispatch(\Phalcon\Events\Event $event, \Phalcon\Mvc\Dispatcher $dispatcher) {

		//Get the request controller and action names
		$controller = $dispatcher->getControllerName();
		$action = $dispatcher->getActionName();

		$redirect = $this->getDI()->get('config')->auth->redirect;

		if($controller == 'auth' && $action == 'signin')
			return true;

		$account = $this->auth->getIdentity();//auth service is registered in our global services.php

		// echo '<pre>';
		// var_dump($account);
		// echo '</pre>';
		// exit();

		if(!$account) {
			if($this->getDI()->get('auth')->hasRememberMe()){
				return $this->getDI()->get('auth')->loginWithRememberMe();
			}
		}

		if(!is_array($account) || !array_key_exists('roles',$account)){
			$this->view->disable();
			$this->response->setStatusCode(403, 'Forbidden');
			$this->flashSession->error('You are not allowed to access this section');

			return $this->response->redirect($redirect->failure);
		}

		$acl = $this->getDI()->get('acl');

		foreach($account['roles'] as $role) {
			if($acl->isAllowed($role, $controller, $action) == \Phalcon\Acl::ALLOW) {
				return true;
			}
		}


		$this->view->disable();
		$this->response->setStatusCode(403, 'Forbidden');
		$this->flashSession->error('You are not allowed to access this section');
		return $this->response->redirect($redirect->failure);

	}
}