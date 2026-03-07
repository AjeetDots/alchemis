<?php

//require_once('app/mapper/CampaignMapper.php');
//require_once('app/domain/Campaign.php');

class app_command_CampaignCreate extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
//		$name = $request->getProperty('campaign_name');
//		
//		if (!$name)
//		{
//			$request->addFeedback('no name provided');
//			return self::statuses('CMD_INSUFFICIENT_DATA');
//		}
//		else
//		{
//			$campaign_obj = new app_domain_Campaign(null, $name);
////			$venue_obj->addSpace(new app_domain_Space(null, 'The Space Upstairs'));
//			$request->setObject('campaign', $campaign_obj);
//			$request->addFeedback("'$name' added ({$campaign_obj->getId()})");
			return self::statuses('CMD_OK');
//		}
	}
}

?>