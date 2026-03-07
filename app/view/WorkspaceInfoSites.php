<?php

/**
 * Defines the app_view_WorkspaceInfoSites class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_WorkspaceInfoSites extends app_view_View
{
	protected function doExecute()
	{
		$collection = $this->request->getObject('sites');
		$this->smarty->assign('sites', $collection->toArray());
		$this->smarty->assign('company', $this->request->getObject('company'));
		$this->smarty->assign('company_posts_first_name', $this->request->getObject('company_posts_first_name'));
		$this->smarty->display('WorkspaceInfoSites.tpl');
	}
}

?>