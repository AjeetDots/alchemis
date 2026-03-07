<?php

/**
 * Defines the app_command_Report2 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('app/report/Report6.php');

/**
 * @package Alchemis
 */
class app_command_Report6 extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		// Get the report ID
		$report_id = $request->getProperty('report_id');
//		$request->setObject('report_id', $report_id);
		
		// Get (or set defaults) parameters
		// Handle start
		if ($request->propertyExists('start'))
		{
			$start = $request->getProperty('start');
		}
		else
		{
			// Assume 1 month ago
			$start = date('Y-m-d', mktime(0, 0, 0, date('m')-1, date('d'), date('Y')));
		}
//		$request->setObject('start', $start);

		// Handle end
		if ($request->propertyExists('end'))
		{
			$end = $request->getProperty('end');
		}
		else
		{
			// Assume yesterday
			$end = date('Y-m-d', mktime(0, 0, 0, date('m'), date('d')-1, date('Y')));
		}
//		$request->setObject('end', $end);
		
		// Handle start
		if ($request->propertyExists('nbm_exclusions') && trim($request->getProperty('nbm_exclusions')) != '')
		{
			$nbm_exclusions = explode(',', $request->getProperty('nbm_exclusions'));
		}
		else
		{
			// Assume no exclusions
			$nbm_exclusions = null;
		}

		// Client Id
		if ($request->propertyExists('client_id') && $request->getProperty('client_id') > 0)
		{
			$client_id = $request->getProperty('client_id');
		}
		else
		{
			$client_id = null;
		}
		
		// Handle order by
		if ($request->propertyExists('order_by'))
		{
			$order_by = $request->getProperty('order_by');
		}
		else
		{
			// Assume no exclusions
			$order_by = 0;
		}

		// Handle include_imperative_target
		if ($request->propertyExists('include_imperative_target'))
		{
			$include_imperative_target = (bool)$request->getProperty('include_imperative_target');
		}
		else
		{
			// Assume no exclusions
			$include_imperative_target = true;
		}		

		// Create and output the report
		$pdf = new app_report_Report6($start, $end, $order_by, $include_imperative_target, $client_id);
		$pdf->Output(date('Y-m-d') . ' Client Services Report.pdf', 'I');
	}

	/**
	 * Validates the input.
	 * @param string $start
	 * @param string $end
	 * @param integer $team_id
	 * @param integer $nbm_id
	 * @return boolean
	 */
	protected function validateParameters($start, $end, $team_id = null, $nbm_id = null)
	{
		if ($end < $start)
		{
			throw new Exception('End date is before start date: (start => ' . $start . ', end => ' . $end . ')');
		}
	}

}

?>