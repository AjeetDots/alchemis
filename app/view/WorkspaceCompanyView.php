<?php

/**
 * Defines the app_view_WorkspaceCompanyView class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_WorkspaceCompanyView extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('company_id', $this->request->getObject('company_id'));
		$this->smarty->assign('tab', 'Workspace');
		$this->smarty->display('WorkspaceCompanyView.tpl');
	}
}

?>