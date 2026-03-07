<?php

/**
 * Defines the app_view_WorkspaceCompanyBrands class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_WorkspaceCompanyBrands extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('tags', $this->request->getObject('tags'));
		$this->smarty->assign('company_id', $this->request->getObject('company_id'));
		$this->smarty->assign('category_id', $this->request->getObject('category_id'));
		$this->smarty->display('WorkspaceCompanyBrands.tpl');
	}
}

?>