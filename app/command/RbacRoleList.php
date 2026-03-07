<?php

require_once('app/domain/RbacRole.php');

class app_command_RbacRoleList extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
//		$request->setObject('commands', app_domain_Rbac::findCommands());
		$request->setObject('roles', app_domain_RbacRole::findAll());
		return self::statuses('CMD_OK');
	}
}

?>