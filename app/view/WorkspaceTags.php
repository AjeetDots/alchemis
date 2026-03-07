<?php

/**
 * Defines the app_view_WorkspaceTags class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_WorkspaceTags extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('tags', $this->request->getObject('tags'));
		$this->smarty->assign('parent_object_type', $this->request->getObject('parent_object_type'));
		$this->smarty->assign('parent_object_id', $this->request->getObject('parent_object_id'));
		$this->smarty->assign('category_id', $this->request->getObject('category_id'));
		$this->smarty->assign('category', $this->request->getProperty('category'));
		$this->smarty->display('WorkspaceTags.tpl');
	}
}

?>