<?php

require_once('app/domain/RbacUser.php');

class app_command_RbacUserList extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$request->setObject('users', app_domain_RbacUser::findAll());
		return self::statuses('CMD_OK');
	}
}

?>