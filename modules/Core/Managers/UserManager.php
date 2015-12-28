<?php

namespace App\Core\Managers;

use App\Core\Models\User;
use App\Core\Models\UserGroup;
use App\Core\Models\UserProfile;

class UserManager extends BaseManager {

	public function find($parameters = null) {
		return User::find($parameters);
	}

	public function create($data, $user_group_name = 'Guest') {
		$security = $this->getDI()->get('security');

		$user = new User();
		$user->setUserFirstName($data['user_first_name']);
		$user->setUserLastName($data['user_last_name']);
		$user->setUserEmail($data['user_email']);
		$user->setUserPassword($security->hash($data['user_password']));
		$user->setUserIsActive($data['user_is_active']);

		$o_acl_role = new AclRoles::findFirstByName($user_role);

		if(!$o_acl_role)
			throw new \Exception("Role $user_role does not exist");

		$o_user_role[0] = new UserRole();
		$o_user_role[0]->setUserRole($user_role);

		$user->roles = $o_user_role;

		$profile = new UserProfile();
		$profile->setUserProfileLocation($data['user_profile_location']);
		$profile->setUserProfileBirthday($data['user_profile_birthday']);

		$user->profile = $profile;

		return $this->save($user);

	}

	public function findFirstGroupByName($user_group_name) {
		return UserGroup::findFirstByUserGroupName($user_group_name);
	}

	

}
