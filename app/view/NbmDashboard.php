<?php

/**
 * Defines the app_view_NbmDashboard class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_NbmDashboard extends app_view_View
{
	protected function doExecute()
	{
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

		// Meetings
		$todays_meetings = $this->request->getObject('todays_meetings');
		$this->smarty->assign('todays_meetings', $todays_meetings);

		$meetings = $this->request->getObject('meetings');
		$this->smarty->assign('meetings', $meetings);

		// Meetings of a given status
		$this->smarty->assign('meeting_statuses',  $this->request->getObject('meeting_statuses'));
		$this->smarty->assign('meeting_status_id', $this->request->getObject('meeting_status_id'));
		
		// Messages
		$this->smarty->assign('messages',  $this->request->getObject('messages'));



		/*
		 * Recommended Actions
		 */
		$this->smarty->assign('weekdays_remaining',  $this->request->getObject('weekdays_remaining'));


		$this->smarty->assign('tab', 'Dashboard');
		$this->smarty->display('NbmDashboard.tpl');
	}

}

?>