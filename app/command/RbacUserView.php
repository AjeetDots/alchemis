<?php

require_once('app/domain/RbacUser.php');

class app_command_RbacUserView extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Parameters
		$user_id = $request->getProperty('user_id');
		
		// Get User
		$user = app_domain_RbacUser::find($user_id);
		
		
		if ($role_id = $request->getProperty('role_id'))
		{
			echo "adding";
//			$role = app_domain_RbacRole::find($role_id);
			$role = new app_domain_RbacRole($role_id);
			$user->addRole($role);
		}
		
		$request->setObject('user', $user);
		echo "<pre>";
		print_r($user);
		echo "</pre>";
		return self::statuses('CMD_OK');
	}
}

?>