<?php

/**
 * Defines the app_command_Calendar class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/CalendarReader.php');

/**
 * @package Alchemis
 */
class app_command_Calendar extends app_command_Command
{

	/**
	 * Override parent::hasPermission()
	 */
	protected function hasPermission(app_controller_Request $request)
	{
		if (!$request->propertyExists('nbm_id') && !$request->propertyExists('client_id'))
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
		}
		else
		{
			$nbm_id = null;
		}

		// Handle client ID
		$client_id = $request->getProperty('client_id');
		if ($client_id)
		{
			$request->setObject('client_id', $client_id);
			$request->setObject('client_name', app_domain_Client::lookupClientNameById($client_id));
		}
		else
		{
			$client_id = null;
		}

		// Handle the date by converting to the first day of the month
		$date = $request->getProperty('date');
		if (empty($date))
		{
			// Default date to today (i.e. current month)
			$date = date('Y-m-d');
		}
		$year  = date('Y', strtotime($date));
		$month = date('m', strtotime($date));
		$day   = date('d', strtotime($date));
		
		$request->setObject('date', date('Y-m-d', mktime(0, 0, 0, $month, $day, $year)));
		$request->setObject('year', $year);
		$request->setObject('month', $month);
		$request->setObject('day', (int) $day);

		// Get data for the month
		$request->setObject('display', 'month');
		$month_data = app_domain_CalendarReader::getMonth($date, $nbm_id, $client_id);
		$request->setObject('month_data', $month_data);
		
//		echo '<pre>';
//		print_r($month_data);
//		echo '</pre>';
		
		
		return self::statuses('CMD_OK');
	}

}

?>