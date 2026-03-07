<?php

/**
 * Defines the app_command_Search class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

/**
 * @package Alchemis
 */
class app_command_Search extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		
		// available campaigns list
		$client_initiatives = app_domain_CampaignNbm::findCampaignInitiativesByUserId($user['id']);
		$request->setObject('client_initiatives', $client_initiatives);
		
		return self::statuses('CMD_OK');
	}
}

?>