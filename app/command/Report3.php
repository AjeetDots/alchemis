<?php

/**
 * Defines the app_command_Report3 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('app/report/Report3.php');

/**
 * @package Alchemis
 */
class app_command_Report3 extends app_command_Command
{
	public function doExecute(app_controller_Request $request)
	{
		$report_id = $request->getProperty('report_id');
		$request->setObject('report_id', $report_id);
		
		if ($request->propertyExists('start'))
		{
			$start = $request->getProperty('start');
		}
		else
		{
			$start = date('Ym', mktime(0, 0, 0, date('m')-3, 1, date('Y')));
		}
		$request->setObject('start', $start);

		if ($request->propertyExists('user_id'))
		{
			$user_id = $request->getProperty('user_id');
		}
		else
		{
			$user_id = 0;
		}
		$request->setObject('user_id', $user_id);

		$pdf = new app_report_Report3($start, $user_id);
		$pdf->Output(date('Y-m-d') . ' Source of Meetings Set.pdf', 'I');
	}
}

?>