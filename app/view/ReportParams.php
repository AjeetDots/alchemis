<?php

/**
 * Defines the app_view_ReportParams class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_ReportParams extends app_view_View
{
	protected function doExecute()
	{
		$user = $this->request->getObject('user');
        $this->smarty->assign('user', $user);

		$report_id = $this->request->getObject('report_id');
		$this->smarty->assign('report_id', $report_id);

		$report = $this->request->getObject('report');
		$this->smarty->assign('report', $report);

		$teams = $this->request->getObject('teams');
		$this->smarty->assign('teams', $teams);

		$clients = $this->request->getObject('clients');
		$this->smarty->assign('clients', $clients);

		$status = $this->request->getObject('status');
		$this->smarty->assign('status', $status);

		$users = $this->request->getObject('users');
		$this->smarty->assign('users', $users);

		$date_from = $this->request->getObject('date_from');
		$this->smarty->assign('date_from', $date_from);

		$date_to = $this->request->getObject('date_to');
		$this->smarty->assign('date_to', $date_to);

		$this->smarty->display('ReportParams.tpl');
	}
}

?>