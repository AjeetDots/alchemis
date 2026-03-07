<?php

/**
 * Defines the app_view_WorkspacePost class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_WorkspacePost extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('post', $this->request->getObject('post'));
		$this->smarty->assign('contact', $this->request->getObject('contact'));
		$this->smarty->assign('company_id', $this->request->getObject('company_id'));
		$this->smarty->assign('post_initiatives', $this->request->getObject('post_initiatives'));
		$this->smarty->assign('client_initiatives', $this->request->getObject('client_initiatives'));
		$this->smarty->display('WorkspacePost.tpl');
	}
}

?>