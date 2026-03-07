<?php

//require_once('app/domain/Client.php');

class app_command_DataMaintenanceHome extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
//		$collection = app_domain_Client::findAll();
//		$request->setObject('clients', $collection);
		return self::statuses('CMD_OK');
	}
}

?>