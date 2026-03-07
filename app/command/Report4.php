<?php

/**
 * Defines the app_command_Report4 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('app/report/Report4.php');

/**
 * @package Alchemis
 */
class app_command_Report4 extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$report_id = $request->getProperty('report_id');
		$request->setObject('report_id', $report_id);

		// Start date
		if ($request->propertyExists('start'))
		{
			$start = $request->getProperty('start');
		}
		else
		{
			// Use first day of current month
			$start = date('Y-m-d', mktime(0, 0, 0, date('m'), 1, date('Y')));
		}
		
		// End date
		if ($request->propertyExists('end'))
		{
			$end = $request->getProperty('end');
		}
		else
		{
			// Use last day of current month
			$end = date('Y-m-d', mktime(0, 0, 0, date('m')+1, 0, date('Y')));
		}
		
		// Team ID
		if ($request->propertyExists('team_id'))
		{
			$team_id = $request->getProperty('team_id');
		}
		else
		{
			$team_id = null;
		}
		
		// NBM ID
		if ($request->propertyExists('nbm_id') && $request->getProperty('nbm_id') != 0)
		{
			$nbm_id = $request->getProperty('nbm_id');
		}
		else
		{
			$nbm_id = null;
		}
		
		// Validate and set
		$this->validateParameters($start, $end, $team_id, $nbm_id);
		$request->setObject('start',   $start);
		$request->setObject('end',     $end);
		$request->setObject('team_id', $team_id);
		$request->setObject('nbm_id',  $nbm_id);
		
		$pdf = new app_report_Report4($start, $end, $team_id, $nbm_id);
		$pdf->Output(date('Y-m-d') . ' Sales Team Summary vs Target for Period.pdf', 'I');
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