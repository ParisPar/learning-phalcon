<?php

class UserTask extends BaseTask {

	public function testAction() {
		$this->consoleLog('OK');
	}

	//Example usage: php modules/cli.php user create admin2 admin2 admin2@fake.com password 1 Barcelona 1985-03-25 Administrator

	public function createAction($params = null) {
		if(!is_array($params) || count($params) < 5) {
			$this->quit('Usage: php modules/cli.php user create fname lname email password isactive');
		}

		$this->confirm('You will create a user with the following data:'.implode(' | ',$params));

		$manager = $this->getDI()->get('core_user_manager');

		try {
			$user = $manager->create(array(
				'user_first_name' => $params[0],
				'user_last_name' => $params[1],
				'user_email' => $params[2],
				'user_password' => $params[3],
				'user_is_active' => $params[4],
				'user_profile_location' => $params[5],
				'user_profile_birthday' => $params[6],
			), $params[]);

			$this->consoleLog(sprintf(
				"User %s %s has been created. ID: %d",
				$user->getUserFirstName(),
				$user->getUserLastName(),
				$user->getId()
			));

		} catch (\Exception $e) {
			$this->consoleLog("There were some errors creating the user: ","red");
			$errors = json_decode($e->getMessage(), true);

			if (is_array($errors)) {
				foreach ($errors as $error) {
					$this->consoleLog("  - $error", "red");
				}
			} else {
				$this->consoleLog("  - $errors", "red");
			}
		}
	}

}