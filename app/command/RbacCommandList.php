<?php

require_once('app/domain/RbacCommand.php');
require_once('app/domain/RbacPermission.php');

require_once('app/mapper/RbacPermissionMapper.php');

/**
 * @package alchemis
 */
class app_command_RbacCommandList extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		
		// Parameters
		$command_id = $request->getProperty('command_id');
		
		if ($permission_name = $request->getProperty('permission_name'))
		{
			$command = app_domain_RbacCommand::find($command_id);
			$permission = new app_domain_RbacPermission(null, $permission_name);
			$command->addPermission($permission);
		}

//		$session = Auth_Session::singleton();
//		$session->hasPermission($userId, $action, 'app_command_RbacCommandList');

		$request->setObject('commands', app_domain_RbacCommand::findAll());
		$request->setObject('permissions', app_domain_RbacPermission::findByCommand($command_id));
		return self::statuses('CMD_OK');

//		$collection = app_domain_Rbac::findCommands();
//		$request->setObject('commands', $collection);
//		return self::statuses('CMD_OK');
	}
}

?>