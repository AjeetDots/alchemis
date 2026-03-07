<?php

/**
 * Defines the app_command_CalendarDay class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/CalendarReader.php');

/**
 * @package Alchemis
 */
class app_command_CalendarDay extends app_command_Command
{
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
		}
		else
		{
			$client_id = null;
		}
		
		// Handle the date
		$date = $request->getProperty('date');
		if (empty($date))
		{
			// Default date to today (i.e. current month)
			$date = date('Y-m-d');
		}
		$request->setObject('date', $date);
		
		// Get data for the day
		$request->setObject('display', 'day');
		$entries = app_domain_CalendarReader::getDay($date, $nbm_id, $client_id);
		$request->setObject('entries', $entries);
		
		return self::statuses('CMD_OK');
	}

}

?>