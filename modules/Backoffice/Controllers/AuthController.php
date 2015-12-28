<?php

namespace App\Backoffice\Controllers;

use App\Core\Forms\UserSigninForm;

class AuthController extends BaseController {

	public function signinAction() {

		$form = new UserSigninForm();

		if($this->request->isPost()) {
			try {
				$this->auth->signin($form);
			} catch (\Exception $e) {
				$this->flash->error($e->getMessage());
			}
		}

		$this->view->signinForm = $form;
	}

	public function signoutAction() {
		$this->auth->remove();

		return $this->response->redirect('auth/signin');
	}



}