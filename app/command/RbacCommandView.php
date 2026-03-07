<?php
echo "bo";
require_once('app/mapper/RbacCommandMapper.php');
require_once('app/domain/RbacCommand.php');

class app_command_RbacCommandView extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$mapper = new app_mapper_RbacCommandMapper();
		$command = $mapper->find($request->getProperty('command_id'));
		$request->setObject('command', $command);
		return self::statuses('CMD_OK');
	}
}

?>