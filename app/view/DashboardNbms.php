<?php

/**
 * Defines the app_view_DashboardNbms class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_DashboardNbms extends app_view_View
{
	protected function doExecute()
	{
		$nbms = $this->request->getObject('nbms');
//		$teams = $teams->toArray();
//		echo '<pre>';
//		print_r($nbms);
//		echo '</pre>';
		
//		$this->smarty->assign('actions', $actions->toArray());
		$this->smarty->assign('nbms', $nbms);
		
		// Teams
		$teams = $this->request->getObject('teams');
		$this->smarty->assign('teams', $teams);
		
		// Get any feedback
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		$this->smarty->display('DashboardNbms.tpl');
	}
}

?>