<?php

/**
 * Defines the app_command_Home class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('Auth/Session.php');
require_once('app/domain/Client.php');
require_once('app/mapper/ClientMapper.php');
require_once('app/domain/Scoreboard.php');


/**
 * @package Alchemis
 */
class app_command_Home extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		
		// Get user information from the session
		$session = Auth_Session::singleton();
		
		$user = $session->getSessionUser();
		
		//echo 'userId = ' . $user['id'];
		//exit();
		$request->setObject('user', $user);

		
		// Available campaigns list (lightweight; scoreboard loaded via AJAX to keep this request fast)
		$client_initiatives = app_domain_CampaignNbm::findCampaignInitiativesByUserId($user['id']);
		$request->setObject('client_initiatives', $client_initiatives);

		// Default client/initiative ID (avoid notice when user has no initiatives)
		$client_id = 0;
		if (!empty($client_initiatives) && isset($client_initiatives[0]['initiative_id'])) {
			$client_id = $client_initiatives[0]['initiative_id'];
		}
		$request->setObject('client_id', $client_id);

		// Placeholder scoreboard only – real data loaded via AJAX (get_home_scoreboard) to avoid heavy DB/CPU on this URL
		$request->setObject('scoreboard', new app_domain_Scoreboard());

		// Redirect info - if it exists (no debug output in production)
		if ($session->hasRedirect()) {
			$redirect = $session->getRedirect();
			if (is_array($redirect)) {
				$request->setProperty('redirect', $redirect['query_string']);
			} else {
				$request->setProperty('redirect', $redirect->query_string);
			}
		} else {
			$request->setProperty('redirect', '');
		}
		
		return self::statuses('CMD_OK');
	}
}

?>
