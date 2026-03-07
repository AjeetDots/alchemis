<?php

/**
 * Defines the app_command_Scoreboard class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/mapper/ScoreboardMapper.php');
require_once('app/domain/Scoreboard.php');

/**
 * @package Alchemis
 */
class app_command_Scoreboard extends app_command_Command
{
	function doExecute(app_controller_Request $request)
	{
		require_once('Auth/Session.php');
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		$request->setObject('user', $user);
		
		$start_date =  date('Y-m-d') . ' 00:00:00';
		$end_date =  date('Y-m-d') . ' 23:59:59';
		
//		$start_date =  '2007-11-09 00:00:00';
//		$end_date =  '2007-12-01 23:59:59';
		
		$scoreboard = app_domain_Scoreboard::findByUserIdStartDateEndDate($user['id'],$start_date,$end_date);
		$request->setObject('scoreboard', $scoreboard);
		$effectives = app_domain_Scoreboard::findEffectivesGroupedByInitiative($user['id'],$start_date,$end_date);
		$request->setObject('effectives', $effectives);
		$non_effectives = app_domain_Scoreboard::findNonEffectiveCountGroupedByInitiative($user['id'],$start_date,$end_date);
		$request->setObject('non_effectives', $non_effectives);
		$meetings_set = app_domain_Scoreboard::findMeetingsSetGroupedByInitiative($user['id'],$start_date,$end_date);
		$request->setObject('meetings_set', $meetings_set);
		
		$information_requests = app_domain_Scoreboard::findInformationRequestGroupedByInitiative($user['id'],$start_date,$end_date);
		$request->setObject('information_requests', $information_requests);
		
		
		return self :: statuses('CMD_OK');
	}
}

?>