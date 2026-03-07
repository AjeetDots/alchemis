<?php

/**
 * Defines the app_command_AdminReports class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');

/**
 * @package Alchemis
 */
class app_command_AdminReports extends app_command_Command
{
	/**
	 * Override parent::hasPermission()
	 * @param app_controller_Request $request
	 */
	protected function hasPermission(app_controller_Request $request)
	{
		return $this->session_user->hasPermission('permission_admin_reports');
	}

	public function doExecute(app_controller_Request $request)
	{
		// Last run
		$last_run_date = app_domain_ReportReader::getDataStatisticsLastRun();
		$request->setObject('last_run_date', $last_run_date);
		
		// Report list
		$reports = app_domain_ReportReader::findAll();
		$request->setObject('reports', $reports);
		
		return self::statuses('CMD_OK');
	}
}

?>