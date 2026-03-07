<?php

/**
 * Defines the app_command_Report1 class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/domain/ReportReader.php');
require_once('app/report/Report1.php');

/**
 * @package Alchemis
 */
class app_command_Report1 extends app_command_Command
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
		$report_id = $request->getProperty('report_id');
		$request->setObject('report_id', $report_id);

		$year_month = $request->getProperty('year_month');
		$request->setObject('year_month', $year_month);
		
		// User ID
		
		
		if (true || $this->session_user->hasPermission('permission_report1_view'))
		{
		}
		
		if ($request->propertyExists('nbm_id'))
		{
			$nbm_id = $request->getProperty('nbm_id');
		}
		else
		{
			$nbm_id = 0;
		}
		$request->setObject('nbm_id', $nbm_id);
		
		// Handle target rows
		if ($request->propertyExists('include_zero_targets'))
		{
			$include_zero_targets = (bool)$request->getProperty('include_zero_targets');
		}
		else
		{
			// Assume no exclusions
			$include_zero_targets = false;
		}
		
		// Handle NBM exclusions
		if ($request->propertyExists('nbm_exclusions') && trim($request->getProperty('nbm_exclusions')) != '')
		{
			$nbm_exclusions = explode(',', $request->getProperty('nbm_exclusions'));
		}
		else
		{
			// Assume no exclusions
			$nbm_exclusions = null;
		}

		// exclusions based on client id
		$session = Auth_Session::singleton();
		$user = $session->getSessionUser();
		$user = app_model_User::find($user['id']);
		
		$pdf = new app_report_Report1($year_month, $nbm_id, $include_zero_targets, $nbm_exclusions, $user->client_id);
		$pdf->Output(date('Y-m-d') . ' Alchemis Allocation Report.pdf', 'I');
	}
}

?>