<?php

/**
 * Defines the app_view_DashboardActions class.
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_DashboardActions extends app_view_View
{
	protected function doExecute()
	{
		$actions = $this->request->getObject('actions');
		$actions = $actions->toArray();
//		echo '<pre>';
//		print_r($actions);
//		echo '</pre>';
		
//		$this->smarty->assign('actions', $actions->toArray());
		$this->smarty->assign('actions', $actions);
		$this->smarty->display('DashboardActions.tpl');
	}
}

?>