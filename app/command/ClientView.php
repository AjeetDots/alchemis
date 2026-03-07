<?php

require_once('app/mapper/ClientMapper.php');
require_once('app/domain/Client.php');

class app_command_ClientView extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$client = app_domain_Client::find($request->getProperty('client_id'));
		$request->setObject('client', $client);
		return self::statuses('CMD_OK');
	}
}

?>