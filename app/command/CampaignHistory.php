<?php

require_once('app/domain/Campaign.php');

class app_command_CampaignHistory extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$campaign_id = $request->getProperty('campaign_id');

//		$venue = app_domain_Venue::find($venue_id);
//		$venue = new app_domain_Venue($venue_id);

//		$mapper = new app_mapper_CampaignMapper();
//		$campagin = $mapper->getHistory($campaign_id);
////		$venue->setName('Hello Ian');
//		$request->setObject('campaign', $campaign);
//		return self::statuses('CMD_OK');

		$collection = app_domain_Campaign::getHistory($campaign_id);
		$request->setObject('campaigns', $collection);
		return self::statuses('CMD_OK');
	}
}

?>