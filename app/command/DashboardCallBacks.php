<?php

/**
 * Defines the app_command_DashboardCallBacks class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/PostInitiative.php');
require_once('app/mapper/PostInitiativeMapper.php');
require_once('app/domain/InformationRequest.php');
require_once('app/mapper/InformationRequestMapper.php');

/**
 * @package Alchemis
 */
class app_command_DashboardCallBacks extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		$request->setObject('user', $user);
		
		// Date range - today
		$start_datetime = date('Y-m-d 00:00:00');
		$end_datetime   = date('Y-m-d 23:59:59');
		$request->setObject('start_datetime', $start_datetime);
		$request->setObject('end_datetime', $end_datetime);
		
		// Call backs due today
		$call_backs = app_domain_PostInitiative::findCallBacksByUserId($user['id'], $start_datetime, $end_datetime);
		$request->setObject('call_backs', $call_backs);
		
		return self::statuses('CMD_OK');
	}
}

?>