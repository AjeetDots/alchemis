<?php

require_once('include/Utils/Utils.class.php');
require_once('include/Utils/String.class.php');

class app_command_CampaignDetails extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get campaign
		$client_id = $request->getProperty('id');
		$campaign = app_domain_Campaign::findByClientId($client_id);
		$request->setObject('campaign', $campaign);
	}	
}	
?>
