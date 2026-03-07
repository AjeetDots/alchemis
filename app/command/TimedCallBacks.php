<?php

/**
 * Defines the app_command_TimedCallBacks class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2012 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/PostInitiative.php');
require_once('app/mapper/PostInitiativeMapper.php');

/**
 * @package Alchemis
 */
class app_command_TimedCallBacks extends app_command_ManipulationCommand
{
	public function doExecute(app_controller_Request $request)
	{
		$task = $request->getProperty('task');
	
		if ($task == 'cancel')
		{
			// ???
		}
		elseif ($task == 'save')
		{
			if ($this->processForm($request))
			{
				$user_id = $_SESSION['auth_session']['user']['id'];
				$scoreboard = app_domain_Scoreboard::findByUserIdStartDateEndDate($user_id, date('Y-m-d') . ' 00:00:00', date('Y-m-d') . ' 23:59:59');
				$request->setObject('scoreboard', $scoreboard);
				$request->setProperty('success', true);
				$this->init($request);
			}
			else
			{
				return self::statuses('CMD_ERROR');
			}
		}
		else
		{
			$this->init($request);
			return self::statuses('CMD_OK');
		}
	}
	
	public function init(app_controller_Request $request)
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
	
	/**
	* Handles the processing of the form, trying to save each object. Assumes
	* any validation has already been performed.
	* @param app_controller_Request $request
	*/
	protected function processForm(app_controller_Request $request)
	{
	
		$properties = $request->getProperties();
		foreach ($properties as $key => $item)
		{
			$temp = strpos($key, 'chk_post_initiative_id_');
				
			if ($temp !== false)
			{
				$post_initiative_id = trim(substr($key,23));
				$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
				$post_initiative->setPriorityCallBack(false);
				$post_initiative->commit();
			}
			
			$temp = strpos($key, 'select_post_initiative_id_');
			
			if ($temp !== false)
			{
				
				if ($item != '0') {
					$post_initiative_id = trim(substr($key,26));
					$post_initiative = app_domain_PostInitiative::find($post_initiative_id);
					$date = new DateTime($post_initiative->getNextCommunicationDate());
					
					switch ($item) {
						case '10mins':
							$date->add(new DateInterval('P0Y0DT0H10M'));
							break;
						case '2hours':
							$date->add(new DateInterval('P0Y0DT2H0M'));
							break;
						case '4hours':
							$date->add(new DateInterval('P0Y0DT4H0M'));
							break;
						case 'next_working_day':
							$newdate = date('Y-m-d', strtotime($date->format('Y-m-d') . ' +1 Weekday'));
							$newdate = DateTime::createFromFormat('Y-m-d', $newdate);
							$time = getdate(strtotime($date->format('Y-m-d H:i:s')));
							$newdate->setTime($time['hours'], $time['minutes'], $time['seconds']);
							$date = $newdate;
							break;
					}
					
					
					$post_initiative->setNextCommunicationDate($date->format('Y-m-d H:i:s'));
					$post_initiative->commit();
				}
			}
		}
	
		
		return true;
	}
}

?>