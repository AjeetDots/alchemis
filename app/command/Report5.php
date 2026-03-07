<?php

/**
 * Defines the app_command_Report5 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('app/report/Report5.php');

/**
 * @package Alchemis
 */
class app_command_Report5 extends app_command_Command
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
		
		// Client ID
		if ($request->propertyExists('client_id'))
		{
			$client_id = $request->getProperty('client_id');
		}
		else
		{
			throw new Exception('Client ID not supplied');
		}
		
		// Project ref
		if ($request->propertyExists('project_ref'))
		{
			$project_ref = $request->getProperty('project_ref');
		}
		else
		{
			$project_ref = null;
		}
		
		// Effectives/non-effectives
		if ($request->propertyExists('effectives'))
		{
			$effectives = $request->getProperty('effectives');
		}
		else
		{
			throw new Exception('No effective/non-effective selection');
		}
			
		// Summary Figures
		if ($request->propertyExists('summary_figures'))
		{
			$summary_figures = (bool)$request->getProperty('summary_figures');
		}
		else
		{
			$summary_figures = false;
		}
		
		// All Statuses
		if ($request->propertyExists('all_statuses'))
		{
			$all_statuses = (bool)$request->getProperty('all_statuses');
		}
		else
		{
			$all_statuses = false;
		}

		// Full history
		if ($request->propertyExists('full_history'))
		{
			$full_history = (bool)$request->getProperty('full_history');
		}
		else
		{
			$full_history = false;
		}

		// Validate and set
		$this->validateParameters($start, $end, $client_id, $project_ref, $effectives, $summary_figures, $all_statuses, $full_history);
		$pdf = new app_report_Report5($start, $end, $client_id, $project_ref, $effectives, $summary_figures, $all_statuses, $full_history);
		$pdf->Output(date('Y-m-d') . ' Alchemis Activity Report of Conversation Notes.pdf', 'I');
	}

	/**
	 * Validates the input.
	 * @param string $start
	 * @param string $end
	 * @param integer $client_id
	 * @param boolean $full_history
	 * @return boolean
	 */
	protected function validateParameters($start, $end, $client_id, $project_ref, $effectives, $summary_figures, $all_statuses, $full_history)
	{
		if ($end < $start)
		{
			throw new Exception('End date is before start date: (start => ' . $start . ', end => ' . $end . ')');
		}
	}

}

?>