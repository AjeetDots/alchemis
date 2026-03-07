<?php

require_once('app/domain/Campaign.php');

class app_command_CampaignList extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$collection = app_domain_Campaign::findAll();
		$request->setObject('campaigns', $collection);
		return self::statuses('CMD_OK');
	}
}

?>