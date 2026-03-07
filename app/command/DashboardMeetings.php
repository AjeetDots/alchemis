<?php

/**
 * Defines the app_command_DashboardMeetings class. 
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
class app_command_DashboardMeetings extends app_command_Command
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
		
		// Meetings today
		$todays_meetings = app_domain_Meeting::findByUserId($user['id'], $start_datetime, $end_datetime);
		$request->setObject('todays_meetings', $todays_meetings);

		// Meetings of a given status
		$meeting_status_id = $request->getProperty('meeting_status_id');
		if (is_null($meeting_status_id))
		{
			$meeting_status_id = 18;
		}
//		$meetings = app_domain_Meeting::findByStatusId($meeting_status_id);
		$meetings = app_domain_Meeting::findByUserIdStatusId($user['id'], $meeting_status_id);
//		$meetings = app_domain_Meeting::findByUserIdStatusId(63, 12);
//		$meetings = app_domain_Meeting::findByUserIdStatusId(52, 12);
		$request->setObject('meetings', $meetings);
		$request->setObject('meeting_status_id', $meeting_status_id);

		// Meeting status
		$statuses = app_domain_Meeting::lookupStatuses();
		$request->setObject('meeting_statuses', $statuses);
		
		return self::statuses('CMD_OK');
	}
}

?>