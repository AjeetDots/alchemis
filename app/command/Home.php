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
require_once('app/mapper/ScoreboardMapper.php');


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

		
		// available campaigns list
		$client_initiatives = app_domain_CampaignNbm::findCampaignInitiativesByUserId($user['id']);
		$request->setObject('client_initiatives', $client_initiatives);
		
		// Fake client ID
		$request->setObject('client_id', $client_initiatives[0]['initiative_id']);
		
		// Scoreboard information
		$scoreboard = app_domain_Scoreboard::findByUserIdStartDateEndDate($user['id'], date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
		$request->setObject('scoreboard', $scoreboard);
		
		$redirect = $session->getRedirect();
		
		// Redirect info - if it exists
		if ($session->hasRedirect()) {
			
			$redirect = $session->getRedirect();
			
			if (is_array($redirect)) {
				echo '$redirect: ' . $redirect['query_string'];
				$request->setProperty('redirect', $redirect['query_string']);
			} else {
				echo '$redirect: ' . $redirect->query_string;
				$request->setProperty('redirect', $redirect->query_string);
			}
		} else {
			$request->setProperty('redirect', '');
		}
		
		return self::statuses('CMD_OK');
	}
}

?>
