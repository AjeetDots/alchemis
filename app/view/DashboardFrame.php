<?php

/**
 * Defines the app_view_DashboardFrame class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_DashboardFrame extends app_view_View
{
	protected function doExecute()
	{
		// Current user (NBM)
		$user_id = $this->request->getObject('user_id');
		$this->smarty->assign('user_id', $user_id);
		
		// NBMs
		$nbm_options = $this->request->getObject('nbm_options');
		$this->smarty->assign('nbm_options', $nbm_options);
		
		$this->smarty->assign('tab', 'Dashboard');
		$this->smarty->display('DashboardFrame.tpl');
	}
}

?>