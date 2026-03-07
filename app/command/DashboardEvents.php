<?php

/**
 * Defines the app_command_DashboardEvents class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/Event.php');

/**
 * @package Alchemis
 */
class app_command_DashboardEvents extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
//		$request->setObject('user', $user);
		
		$events = app_domain_Event::findByUserId($user['id']);
		$request->setObject('events', $events);
		
		return self::statuses('CMD_OK');
	}
}

?>