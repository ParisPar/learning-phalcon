<?php

namespace App\Core\Security;

use App\Core\Models\User;
use App\Core\Models\UserRememberTokens;
use App\Core\Models\UserSuccessLogins;
use App\Core\Models\UserFailedLogins;


class Auth extends \Phalcon\Mvc\User\Component {//Extending \Phalcon\Mvc\User\Component means we have access to the DI container

  /**
   * Check user credentials
   * @param  array $credentials 
   */
  public function check($credentials) {
    $user = User::findFirstByUserEmail(strtolower($credentials['email']));

    if($user == false) {
			$this->registerUserThrottling(null);//Use login throttling to counter brute force attacks
			throw new \Exception('Wrong email/password');
		}

		if(!$this->security->checkHash($credentials['password'], $user->getUserPassword())) {
			$this->registerUserThrottling(null);//Use login throttling to counter brute force attacks
			throw new \Exception('Wrong email/password');
		}

		$this->checkUserFlags($user);
		$this->saveSuccessLogin($user);//Set cookies and tokens for the Remember Me environment

		if(isset($credentials['remember'])) {
			$this->createRememberEnvironment($user);
		}

		$this->setIdentity($user);//Set user info in session
	}

	/**
	 * Set user info in session
	 * @param App\Core\Model\User $user
	 */
	private function setIdentity($user) {
		$st_identity = [
		'id' => $user->getId(),
		'email' => $user->getUserEmail(),
		'name' => $user->getUserFirstName() . ' ' . $user->getUserLastName(),
		'roles' => [
		'Administrator'
		]
		]
		$this->session->set('identity', $st_identity);
	}

	/**
	 * Login user - without remember me
	 * @param  App\Core\Forms\UserSigninForm $form [description]
	 * @return \Phalcon\Http\ResponseInterface
	 */
	public function signin($form) {
		if(!$this->request->isPost()) {
			if($this->hasRememberMe()) {
				return $this->loginWithRememberMe();
			}
		} else {
			if($form->isValid($this->request->getPost()) == false) {
				foreach($form->getMessages() as $message) {
					$this->flashSession->error($message->getMessage());
				}
			} else {
				$this->check([
				'email' => $this->request->getPost('email'),
				'password' => $this->request->getPost('password'),
				'remember' => $this->request->getPost('remember'),
				]);

				$redirect = $this->getDi()->get('config')->auth->redirect;

					//Redirects user to index/index on success
				return $this->response->redirect($redirect->success);
			}
		}	
		
		return false;
	}

	/**
	 * Save successful login information for the user
	 * @param  App\Core\Model\User $user
	 */
	public function saveSuccessLogin($user) {
		$successLogin = new UserSuccessLogins();
		$successLogin->setUserId($user->getId());
		$successLogin->setIpAddress($this->request->getClientAddress());
		$successLogin->setUserAgent($this->request->getUserAgent());

		if($successLogin->save()) {
			$messages = $successLogin->getMessages();
			throw new \Exception($messages[0]);
		}
	}

  /**
   * Create user login throttling environment. This method log failed
   * attempts with a timestamp, and checks the number of attempts for
   * a user from a certain IP. If the number of attempts is greater
   * than 3, we delay the response
   * @param  int $user_id
   */
  public function registerUserThrottling($user_id) {
    $failedLogin = new UserFailedLogins();
    $failedLogin->setUserId($user_id == null ? new \Phalcon\Db\Raw\Value('NULL') : $user_id);
    $failedLogin->setIpAddress($this->request->getClientAddress());
    $failedLogin->setAttempted(time());
    $failedLogin->save();

    $attempts = UserFailedLogins::count([
    'ip_address = ?0 AND attempted >= ?1',
    'bind' => [
    $this->request->getClientAddress(),
    time() - 3600 * 6
    ]
    ])

    switch ($attempts) {
     case 1:
     case 2:
     break;
     case 3:
     case 4:
     sleep(2);
     break;
     default:
     sleep(4);
     break;
   }
 }

  /**
     * Creates the remember me environment settings, the related cookies and generates tokens
     *
     * @param App\Core\Models\User $user
     */
  public function createRememberEnviroment($user) {
    $user_agent = $this->request->getUserAgent();
    $token = md5($user->getEmail().$user->getPassword().$user_agent);

    $remember = new UserRememberTokens();
    $remember->setUserId($user->getId());
    $remember->setToken($token);
    $remember->setUserAgent($user_agent);
    $remember->setCreatedAt(time());

    if ($remember->save() != false) {
      $expire = time() + 86400 * 30;
      $this->cookies->set('RMU', $user->getId(), $expire);
      $this->cookies->set('RMT', $token, $expire);
    }
  }

    /**
     * Check if the session has a remember me cookie
     *
     * @return boolean
     */
    public function hasRememberMe() {
      return $this->cookies->has('RMU');
    }

	/**
     * Logs on using the information in the coookies
     *
     * @return Phalcon\Http\Response
     */
	public function loginWithRememberMe($redirect = true)
	{
		$userId = $this->cookies->get('RMU')->getValue();
		$cookieToken = $this->cookies->get('RMT')->getValue();

		$user = User::findFirstById($userId);

		$redirect = $this->getDI()->get('config')->auth->redirect;

		if ($user) {
			$userAgent = $this->request->getUserAgent();

      //Recreate the token using the user data from the database plus his agent
			$token = md5($user->getUserEmail().$user->getUserPassword().$userAgent);

      //If the recreated token matched the one stored in cookie try to find it in the database
			if ($cookieToken == $token) {
				$remember = UserRememberTokens::findFirst([
				'user_id = ?0 AND token = ?1',
				'bind' => [
				$user->getId(),
				$token,
				],
				]);

        //If the token was found in the database check if it was created more than a month ago. If not, login the user
				if ($remember) {
					if ((time() - (86400 * 30)) < $remember->getCreatedAt()) {
						$this->checkUserFlags($user);
						$this->setIdentity($user);
						$this->saveSuccessLogin($user);

						if (true === $redirect) {
							return $this->response->redirect($redirect->success);
						}

						return;
					}
				}
			}
		}

    //If the login wasn't successful remove all cookie data
		$this->cookies->get('RMU')->delete();
		$this->cookies->get('RMT')->delete();

		return $this->response->redirect($redirect->failure);
	}

	public function isUserSignedIn() {
		$identity = $this->getIdentity();

		if(is_array($identity) && isset($identity['id']))
			return true; 

		return false;
	}

	/**
	 * Check if the user is banned/inactive/suspended etc...
	 * @param  App\Core\Model\User $user
	 */
	public function checkUserFlags($user) {
		if($user->getUserIsActive() == false)
			throw new \Exception('The user is inactive');
	}

	public function getIdentity() {
		return $this->session->get('identity');
	}

   /**
     * Returns the name of the user
     *
     * @return string
     */
   public function getUserName()
   {
    $identity = $this->session->get('identity');

    return isset($identity['name']) ? $identity['name'] : false;
  }
    /**
     * Returns the id of the user
     *
     * @return string
     */
    public function getUserId()
    {
      $identity = $this->session->get('identity');

      return isset($identity['id']) ? $identity['id'] : false;
    }

  /**
   * Remove user data from session and cookies
   */
  public function remove() {
    if($this->cookies->has('RMU'))
      $this->cookies->get('RMU')->delete();

    if($this->cookies->has('RMT'))
      $this->cookies->get('RMT')->delete();

    $this->session->remove('identity');
  }

  /**
     * Auths the user by his id
     *
     * @param int $id
     */
  public function authUserById($id)
  {
    $user = User::findFirstById($id);

    if ($user == false) {
      throw new \Exception('The user does not exist');
    }

    $this->checkUserFlags($user);
    $this->setIdentity($user);

    return true;
  }

  /**
     * Get the entity related to user in the active identity
     *
     * @return App\Core\Models\User
     */
  public function getUser()
  {
    $identity = $this->session->get('identity');

    if (isset($identity['id'])) {
      $user = User::findFirstById($identity['id']);
      if ($user == false) {
        throw new \Exception('The user does not exist');
      }

      return $user;
    }

    return false;
  }

  /**
     * Generate a random password
     *
     * @param  integer $length
     * @return string
     */
    public function generatePassword($length = 8)
    {
        $chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789#@%_.";

        return substr(str_shuffle($chars), 0, $length);
    }

}