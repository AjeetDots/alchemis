<?php

/**
 * Defines the app_view_WorkspaceNotes class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_WorkspaceNotes extends app_view_View
{
	protected function doExecute()
	{

		$this->smarty->assign('post_initiative_id', $this->request->getProperty('post_initiative_id'));
		$this->smarty->assign('company_id', $this->request->getProperty('company_id'));
		$this->smarty->assign('initiative_id', $this->request->getProperty('initiative_id'));
		
		$this->smarty->assign('notes', $this->request->getObject('notes'));
		$this->smarty->display('WorkspaceNotes.tpl');
	}
}

?>