<?php

/**
 * Defines the app_view_RbacRoleList class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_RbacRoleList extends app_view_View
{
	protected function doExecute()
	{
		$collection = $this->request->getObject('roles');
		$this->smarty->assign('roles', $collection->toArray());
		$this->smarty->assign('tab', 'Workspace');
		$this->smarty->display('RbacRoleList.tpl');
	}
}

?>