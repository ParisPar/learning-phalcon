<?php

class AclTask extends BaseTask {

	public function initAction() {
		$acl = $this->getDI()->get('acl');

		$roles = array(
			'Administrator' => new \Phalcon\Acl\Role('Administrator'),
			'Guest' => new \Phalcon\Acl\Role('Guest')
		);

		foreach($roles as $role) {
			$this->acl->addRole($role);
		}

		$userResources = array(
			'index' => array(
				'index'
			)
		);

		foreach($userResources as $resource => $actions) {
			$acl = $this->getDI()->get('acl');

			$this->acl->addResource(new \Phalcon\Acl\Resource($resource), $actions);

			foreach($actions as $action) {
				$this->acl->allow('Administrator', $resource, $action);
			}
		}

		$this->consoleLog('Default resources created');
	}

}