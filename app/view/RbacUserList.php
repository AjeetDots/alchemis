<?php

/**
 * Defines the app_view_RbacUserList class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_RbacUserList extends app_view_View
{
	protected function doExecute()
	{
		$collection = $this->request->getObject('users');
		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->assign('users', $collection);
		$this->smarty->assign('tab', 'Workspace');
		$this->smarty->display('RbacUserList.tpl');
	}
}

?>