<?php

/**
 * Defines the app_view_DashboardMeetings class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_DashboardMeetings extends app_view_View
{
	protected function doExecute()
	{
		$start_datetime = $this->request->getObject('start_datetime');
		$end_datetime   = $this->request->getObject('end_datetime');
		
//		$this->smarty->assign('start_datetime', $start_datetime);
//		$this->smarty->assign('end_datetime',   $end_datetime);
//		$start_date = substr($start_datetime, 0, 10);
//		$end_date   = substr($start_datetime, 0, 10);

		// Meetings
		$todays_meetings = $this->request->getObject('todays_meetings');
		$this->smarty->assign('todays_meetings', $todays_meetings);

		$meetings = $this->request->getObject('meetings');
		$this->smarty->assign('meetings', $meetings);

		// Meetings of a given status
		$this->smarty->assign('meeting_statuses',  $this->request->getObject('meeting_statuses'));
		$this->smarty->assign('meeting_status_id', $this->request->getObject('meeting_status_id'));
		
		$this->smarty->assign('tab', 'Dashboard');
		$this->smarty->display('DashboardMeetings.tpl');
	}

}

?>