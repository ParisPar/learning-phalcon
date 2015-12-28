<?php

namespace App\Core\Forms;

use Phalcon\Forms\Form;
use Phalcon\Forms\Element\Text;
use Phalcon\Forms\Element\Password;
use Phalcon\Forms\Element\Submit;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Validation\Validator\PresenseOf;
use Phalcon\Validation\Validator\Email;
use Phalcon\Validation\Validator\Identical;

class UserSigninForm extends Form {

	public function initialize() {

		//Phalcon\Forms\Element
		//public __construct (string $name, [array $attributes])
		$email = new Text('email', array(
			'placeholder' => 'Email'
		));

		$email->addValidators(array(
			new PresenceOf(array(
				'message' => 'The email is required'
			)),
			new Email(array(
				'message' => 'The email is not valid'
			))
		));

		$this->add($email);

		$password = new Password('password', array(
			'placeholder' => 'Password'
		));

		$password->addValidator(new PresenceOf(array(
			'message' => 'The password is required'
		)));

		$this->add($password);

		$remember = new Check('remember',array(
			'value' => 'yes'
		));

		$remember->setLabel('Remember me');

		$this->add($remeber);

		//Cross-Site Request Forgery
		$crsf = new Hidden('crsf');

		$crsf->addValidator(new Identical(array(
			'value' => $this->security->getSessionToken(),
			'message' => 'CRSF validation failed'
		)));

		$this->add($crsf);

		$this->add(new Submit('signin',array(
			'class' => 'btn btn-lg btn-primary btn-block'
		)));
	}

}