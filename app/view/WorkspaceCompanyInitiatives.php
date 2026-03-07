<?php

/**
 * Defines the app_view_WorkspaceCompanyInitiatives class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_WorkspaceCompanyInitiatives extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('posts', $this->request->getObject('posts'));
		$this->smarty->assign('post_id', $this->request->getObject('post_id'));
		$this->smarty->display('WorkspaceCompanyInitiatives.tpl');
	}
}

?>