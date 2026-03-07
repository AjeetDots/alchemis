<?php

/**
 * Defines the app_view_WorkspacePostInitiative class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_WorkspacePostInitiative extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('post_id', $this->request->getObject('post_id'));
		$this->smarty->assign('company_id', $this->request->getObject('company_id'));
		$this->smarty->assign('initiative_id', $this->request->getObject('initiative_id'));
		$this->smarty->assign('post_initiative', $this->request->getObject('post_initiative'));
		$this->smarty->assign('project_refs', $this->request->getObject('project_refs'));
		$this->smarty->assign('meetings', $this->request->getObject('meetings'));
		$this->smarty->assign('information_requests', $this->request->getObject('information_requests'));
		$this->smarty->display('WorkspacePostInitiative.tpl');
	}
}

?>