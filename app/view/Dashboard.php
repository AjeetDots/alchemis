<?php

/**
 * Defines the app_view_Dashboard class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_Dashboard extends app_view_View
{
	protected function doExecute()
	{
		// User
		$session_user = $this->request->getObject('session_user');
		$this->smarty->assign('session_user_id', $session_user->getId());
		
		$start_datetime = $this->request->getObject('start_datetime');
		$end_datetime   = $this->request->getObject('end_datetime');
		
//		$this->smarty->assign('start_datetime', $start_datetime);
//		$this->smarty->assign('end_datetime',   $end_datetime);
//		$start_date = substr($start_datetime, 0, 10);
//		$end_date   = substr($start_datetime, 0, 10);

		// Call backs
		$call_backs = $this->request->getObject('call_backs');
		$timed_call_backs = array();
		$other_call_backs = array();
		foreach ($call_backs as $call_back)
		{
			if (preg_match('/00:00:00$/i', $call_back['next_communication_date']))
			{
				$other_call_backs[] = $call_back;
			}
			else
			{
				$timed_call_backs[] = $call_back;
			}
		}
		$this->smarty->assign('timed_call_backs', $timed_call_backs);
		$this->smarty->assign('other_call_backs', $other_call_backs);
		$this->smarty->assign('call_back_count',  count($timed_call_backs) + count($other_call_backs));
		
		// Information requests
		$information_requests  = $this->request->getObject('information_requests');
		$timed_information_requests = array();
		$other_information_requests = array();
		foreach ($information_requests as $information_request)
		{
			if (preg_match('/00:00:00$/i', $information_request['date']))
			{
				$other_information_requests[] = $information_request;
			}
			else
			{
				$timed_information_requests[] = $information_request;
			}
		}
		$this->smarty->assign('timed_information_requests', $timed_information_requests);
		$this->smarty->assign('other_information_requests', $other_information_requests);
		$this->smarty->assign('information_request_count',  count($timed_information_requests) + count($other_information_requests));

		// Today's meetings
		$todays_meetings = $this->request->getObject('todays_meetings');
		$this->smarty->assign('todays_meetings', $todays_meetings);
//		$meetings = $this->request->getObject('meetings');
//		$this->smarty->assign('meetings', $meetings);

		// Meetings of a given status
		$this->smarty->assign('meeting_statuses',  $this->request->getObject('meeting_statuses'));
		$this->smarty->assign('meeting_status_id', $this->request->getObject('meeting_status_id'));
		
		
		// User's clients
		$this->smarty->assign('clients',          $this->request->getObject('clients'));
		
		// User's campaigns
		$this->smarty->assign('yesterday', strtotime('-1 day'));
		// Campaign Progress heading: use UTC "yesterday" so live and local show the same date
		$utc = new DateTimeZone('UTC');
		$yesterdayUtc = new DateTime('now', $utc);
		$yesterdayUtc->modify('-1 day');
		$this->smarty->assign('campaign_progress_date_label', $yesterdayUtc->format('l j F Y'));
		$this->smarty->assign('campaigns', $this->request->getObject('campaigns'));
		
		// Messages
		$this->smarty->assign('messages', $this->request->getObject('messages'));
		
		// Actions
		$this->smarty->assign('actions', $this->request->getObject('actions'));
		$this->smarty->assign('more_actions', $this->request->getObject('more_actions'));


		// Top Line Summary Stats
		$this->smarty->assign('clients_dropdown', $this->request->getObject('clients_dropdown'));
		$this->smarty->assign('client_selected', $this->request->getObject('client_selected'));
		$this->smarty->assign('client_id', $this->request->getObject('client_selected'));
//		$this->smarty->assign('top_line_stats', $this->request->getObject('top_line_stats'));
		$this->smarty->assign('client_targets', $this->request->getObject('client_targets'));
		$this->smarty->assign('client_actuals', $this->request->getObject('client_actuals'));
		
		// Recommended Actions
		$this->smarty->assign('weekdays_remaining', $this->request->getObject('weekdays_remaining'));
		$this->smarty->assign('recommended_calls', $this->request->getObject('recommended_calls'));
		$this->smarty->assign('recommended_effectives', $this->request->getObject('recommended_effectives'));
		$this->smarty->assign('recommended_meets_set', $this->request->getObject('recommended_meets_set'));
		$this->smarty->assign('recommended_meets_attended', $this->request->getObject('recommended_meets_attended'));
		$this->smarty->assign('lapsed_rate', $this->request->getObject('lapsed_rate'));
		
		// Calendar data
		$this->smarty->assign('display', $this->request->getObject('display'));
		$this->smarty->assign('month_data', $this->request->getObject('month_data'));
		$this->smarty->assign('year', $this->request->getObject('year'));
		$this->smarty->assign('month', $this->request->getObject('month'));
		
		// Team Zone
		$this->smarty->assign('team_stats', $this->request->getObject('team_stats'));
		$this->smarty->assign('user_team', $this->request->getObject('user_team'));
		
		$this->smarty->assign('tab', 'Dashboard');
		
		// Determine media type
		$this->smarty->assign('media', $this->request->getObject('media'));
		$media = $this->request->getObject('media');
		if ($media == 'print')
		{
			$this->smarty->display('Dashboard.print.tpl');
		}
		else
		{
			$this->smarty->display('Dashboard.tpl');
		}
	}

}

?>