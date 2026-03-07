<?php

/**
 * Defines the app_view_AdminReports class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_AdminReports extends app_view_View
{
	protected function doExecute()
	{
		// Last run
		$last_run_date = $this->request->getObject('last_run_date');
		$this->smarty->assign('last_run_date', $last_run_date);
		
		// Report list
		$reports = $this->request->getObject('reports');
		$this->smarty->assign('reports', $reports);
		
		$this->smarty->display('AdminReports.tpl');
	}
}

?>