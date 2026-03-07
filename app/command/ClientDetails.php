<?php

require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

class app_command_ClientDetails extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get client
		$client_id = $request->getProperty('id');
		$client = app_domain_Client::find($client_id);
		$request->setObject('client', $client);
	}	
}	
?>
