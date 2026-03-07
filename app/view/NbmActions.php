<?php

/**
 * Defines the app_view_NbmActions class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_NbmActions extends app_view_View
{
	protected function doExecute()
	{
		// User
		$this->smarty->assign('user', $this->request->getObject('user'));
		
		// Actions
		$actions = $this->request->getObject('actions');
		$actions = $actions->toArray();
		$this->smarty->assign('actions', $actions);
		
		$this->smarty->display('NbmActions.tpl');
	}
}

?>