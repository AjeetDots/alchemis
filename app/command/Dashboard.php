<?php

/**
 * Defines the app_command_Dashboard class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/InformationRequest.php');
require_once('app/domain/PostInitiative.php');
require_once('app/domain/TeamNbm.php');
require_once('app/domain/RbacUser.php');

/**
 * @package Alchemis
 */
class app_command_Dashboard extends app_command_Command
{
	
	/**
	 * Override parent::hasPermission()
	 */
	protected function hasPermission(app_controller_Request $request)
	{
		if ($request->propertyExists('nbm_id'))
		{
			return $this->session_user->hasPermission('permission_view_global_calendar');
		}
		else
		{
			return true;
		}
	}

	/**
	 * @param app_controller_Request $request
	 */
	public function doExecute(app_controller_Request $request)
	{
		// Handle NBM ID
		$nbm_id = $request->getProperty('nbm_id');
		if ($nbm_id)
		{
			$request->setObject('nbm_id', $nbm_id);
			$request->setObject('nbm_name', app_domain_RbacUser::getUserName($nbm_id));
//			$user = app_domain_RbacUser::find($nbm_id);
		}
		else
		{
			// Get user information from the session
			$session = Auth_Session::singleton();
			$user = $session->getSessionUser();
			$request->setObject('user', $user);
			$nbm_id = $user['id'];
		}
		
//		// Get user information from the session
//		$session = Auth_Session::singleton();
//		$user = $session->getSessionUser();
//		$request->setObject('user', $user);
		
		// Date range - today
		$start_datetime = date('Y-m-d 00:00:00');
		$end_datetime   = date('Y-m-d 23:59:59');
//		$start_datetime = date('2007-09-01 00:00:00');
//		$end_datetime   = date('2007-09-30 23:59:59');
		$request->setObject('start_datetime', $start_datetime);
		$request->setObject('end_datetime', $end_datetime);
		
		// Call backs due today
		$call_backs = app_domain_PostInitiative::findCallBacksByUserId($nbm_id, $start_datetime, $end_datetime);
		$request->setObject('call_backs', $call_backs);
		
		// Information requests due today
		$information_requests = app_domain_InformationRequest::findByUserId($nbm_id, $start_datetime, $end_datetime);
		$request->setObject('information_requests', $information_requests);
		
		// Meetings today
		$todays_meetings = app_domain_Meeting::findByUserId($nbm_id, $start_datetime, $end_datetime);
		$request->setObject('todays_meetings', $todays_meetings);

		// Meetings of a given status
		$meeting_status_id = $request->getProperty('meeting_status_id');
		if (is_null($meeting_status_id))
		{
			$meeting_status_id = 18;
		}
//		$meetings = app_domain_Meeting::findByStatusId($meeting_status_id);
//		$request->setObject('meetings', $meetings);
//		$request->setObject('meeting_status_id', $meeting_status_id);

		// Meeting status
		$statuses = app_domain_Meeting::lookupStatuses();
		$request->setObject('meeting_statuses', $statuses);
		
		
		// User's clients
		$clients = app_domain_Client::findByUserId($nbm_id);
		$request->setObject('clients', $clients);
		
		// Clients for drop-down
		$clients_dropdown = app_domain_Client::findByUserIdForDropdown($nbm_id);
		$request->setObject('clients_dropdown', $clients_dropdown);
		
		// User's campaigns
		$campaigns = app_domain_Campaign::findProgressByUserId($nbm_id);
		$request->setObject('campaigns', $campaigns);

		// Messages
		$messages = app_domain_Message::findSet(3);
		$request->setObject('messages', $messages);
		
		// Actions
		$actions = app_domain_Action::findCurrentByUserId($nbm_id, 5)->toArray();
		$request->setObject('actions', $actions);

		// Top Line Summary Stats
		$client_id = $request->getProperty('client_id');
		if (is_null($client_id) || $client_id == 0)
		{
			$client_id = $clients[0]['id'];
		}
		$request->setObject('client_selected', $client_id);
		
		// Targets
		$client_targets = app_domain_Client::findTargetsByClientIdAndYearmonth($client_id, date('Ym'));
		$request->setObject('client_targets', $client_targets);
		
		// Actuals
		$client_actuals = app_domain_Client::findActualsByClientIdAndYearmonth($client_id, date('Ym'));
		$request->setObject('client_actuals', $client_actuals);
//		echo '<pre>';
//		print_r($top_line_stats);
//		echo '</pre>';
//		$request->setObject('top_line_stats', $top_line_stats);
		
		/*
		 * Recommended Actions
		 */
		$weekdays_remaining = 6 - date('N');
		$request->setObject('weekdays_remaining', $weekdays_remaining);
		$start_date = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
//		$end_date   = date('Y-m-d', mktime(0, 0, 0, date('m') + 1, 0, date('Y')));
		$end_date   = date('Y-m-d');
		$recommended_calls          = app_domain_ReportReader::getRequiredCalls($start_date, $end_date, null, $nbm_id);
		$recommended_effectives     = app_domain_ReportReader::getRequiredEffectives($start_date, $end_date, null, $nbm_id);
		$recommended_meets_set      = app_domain_ReportReader::getRequiredMeetingsSet($start_date, $end_date, null, $nbm_id);
		$recommended_meets_attended = app_domain_ReportReader::getRequiredMeetingsAttended($start_date, $end_date, null, $nbm_id);
		$request->setObject('recommended_calls',          $recommended_calls);
		$request->setObject('recommended_effectives',     $recommended_effectives);
		$request->setObject('recommended_meets_set',      $recommended_meets_set);
		$request->setObject('recommended_meets_attended', $recommended_meets_attended);

		$weekdays_remaining = 6 - date('N');
		$request->setObject('weekdays_remaining', $weekdays_remaining);

		$weekdays_remaining = 6 - date('N');
		$request->setObject('weekdays_remaining', $weekdays_remaining);
		
		// Get calendar data
		$client_id = null;
		$this->doMonth($request, date('Y-m'), $nbm_id, $client_id);
		
		// Team Zone
		$team_stats = app_domain_Team::findDashboardStatistics();
		$request->setObject('team_stats', $team_stats);
		$user_team = app_domain_RbacUser::findTeamIdByUserId($nbm_id);
		$request->setObject('user_team', $user_team);
		
		// Determine media type
		$request->setObject('media', $request->getProperty('media'));
		
		// Assign to smarty
		return self::statuses('CMD_OK');
	}

	/**
	 * @param app_controller_Request $request
	 * @param string $date
	 * @param integer $nbm_id
	 * @param integer $client_id
	 */
	private function doMonth(app_controller_Request $request, $date, $nbm_id, $client_id)
	{
		$request->setObject('display', 'month');
		$month_data = app_domain_CalendarReader::getMonth($date, $nbm_id, $client_id);
		$request->setObject('month_data', $month_data);
		$year  = substr($date, 0, 4);
		$month = substr($date, 5, 2);
		$request->setObject('year', $year);
		$request->setObject('month', $month);
	}

}

?>