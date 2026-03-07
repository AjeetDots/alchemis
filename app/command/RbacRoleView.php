<?php

require_once('app/domain/RbacRole.php');

class app_command_RbacRoleView extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Parameters
		$role_id = $request->getProperty('role_id');
		$command_id = $request->getProperty('command_id');
		
		// Get Role
		$role = app_domain_RbacRole::find($role_id);
		$request->setObject('role', $role);
		
		// Get Permissions
		$permissions = app_domain_RbacRole::findByRoleAndCommand($role_id, $command_id);
		$request->setObject('permissions', $permissions);

		// Get Users
		$permissions = app_domain_RbacRole::findByRoleAndCommand($role_id, $command_id);
		
		$users = $role->getUsers();
		$request->setObject('users', $users);
		
		// Get Commands
		$commands = app_domain_RbacCommand::findAll();
		$request->setObject('commands', $commands);
		
		return self::statuses('CMD_OK');
	}
}

?>