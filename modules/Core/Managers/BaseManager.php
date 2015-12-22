<?php

namespace App\Core\Managers;

class BaseManager extends \Phalcon\Mvc\User\Module {

	public function save($object, $type = 'save') {

		switch($type) {
			case 'save':
				$result = $object->save();
				break;
			case 'create':
				$result = $object->create();
				break;
			case 'update':
				$result = $object->update();
				break;
		}

		if($result === false) {
			foreach ($object->getMessages() as $message) {
				$error[] = (string) $message;
			}

			throw new \Exception(json_encode($error));
		}

		return $object;
	}
	
}