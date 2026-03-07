<?php

/**
 * Defines the app_command_AjaxDashboard class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/command/AjaxCommand.php');
require_once('app/domain/Meeting.php');

/**
 * @package Alchemis
 */
class app_command_AjaxDashboard extends app_command_AjaxCommand
{
	/**
	 * Excute the command.
	 */
	public function execute()
	{
		error_reporting (E_ALL & ~E_NOTICE);
		
		$debug = false;
		if ($debug) echo "<pre>";
		if ($debug) print_r($this->request);
		if ($debug) echo "</pre>";
		
		// Get user information from the session
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
//		$request->setObject('user', $user);
			
		switch ($this->request->cmd_action)
		{
			case 'load_meeting_status':
				if (!isset($this->request->item_id)) {
					$this->request->success = false;
					break;
				}
				$id = $this->request->item_id;
				$meetings = app_domain_Meeting::findByUserIdStatusId($user['id'], $id);
				$this->request->line_html = $this->getMeetingsHtml($meetings);
				$this->request->success = true;
				break;
			
			case 'call_backs_due_in_interval':
				if ($this->request->callBackCount == 0) {
					$date = new DateTime();
					$nextDate = new DateTime();
				} else {
					if (is_null($session->getLastCallBackQueryTime())) {
						$date = new DateTime();
						$nextDate = new DateTime();
					} else {
						$date = DateTime::createFromFormat('Y-m-d H:i:s', $session->getLastCallBackQueryTime());
						$date->add(new DateInterval('P0Y0DT0H10M'));
						$nextDate = DateTime::createFromFormat('Y-m-d H:i:s', $session->getLastCallBackQueryTime());
						$nextDate->add(new DateInterval('P0Y0DT0H10M'));
					}
				}
				$nextDate->add(new DateInterval('P0Y0DT0H10M'));
				$session->setLastCallBackQueryTime($date->format('Y-m-d H:i:s'));
				
				$callbacks = app_domain_PostInitiative::findPriorityCallBacksByUserId($user['id'], $date->format('Y-m-d H:i:s'), $nextDate->format('Y-m-d H:i:s'));
				$this->request->date = $date->format('Y-m-d H:i:s');
				$this->request->next_date = $nextDate->format('Y-m-d H:i:s');
				$this->request->session_obj = $session->getLastCallBackQueryTime();
// 				$this->request->date = date('Y-m-d H:i:s', $date);
// 				$this->request->next_date = date('Y-m-d H:i:s', $nextDate);
				$this->request->callback_count = count($callbacks);
				$this->request->success = true;
				
				
				
				
				break;
				
			default:
				// TODO
				//  - should throw/log an error of some sort?
				break;
		}
		
		$this->response->data[] = $this->request;
	}

	/**
	 * Return the pre-populated HTML for displaying the meetings.
	 * @param array meetings
	 * @return string
	 */
	protected function getMeetingsHtml($meetings)
	{
		require_once('app/view/ViewHelper.php');
		$smarty = ViewHelper::getSmarty();
		$smarty->assign('meetings', $meetings);
		return $smarty->fetch('html_DashboardMeetings.tpl');
	}

}

?>