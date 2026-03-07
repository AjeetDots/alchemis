<?php

/**
 * Defines the app_command_CampaignDocuments class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Document.php');

/**
 * @package Alchemis
 */
class app_command_CampaignDocuments extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		if ($campaign_id = $request->getProperty('campaign_id'))
		{
            $campaign = app_model_Campaign::find($campaign_id);
			$request->setObject('campaign_id', $campaign->id);
		}
		else
		{
			// Create a new plan
			throw new Exception('Campaign ID not supplied');
		}
		
		$collection = app_domain_Document::findByCampaignId($campaign->id);
		$request->setObject('documents', $collection);
		return self::statuses('CMD_OK');
	}
}

?>
