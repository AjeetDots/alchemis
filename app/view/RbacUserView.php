<?php

/**
 * Defines the app_view_RbacUserView class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_RbacUserView extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('user', $this->request->getObject('user'));
		
		// Commands
		$user = $this->request->getObject('user');
		$roles = $user->findAvailableRoles();
		$role_options = array();
		foreach ($roles as $role)
		{
			$role_options[$role->getId()] = $role->getName();
		}
		$this->smarty->assign('available_roles', $role_options);
		
//		$this->smarty->assign('role_dd_id', $this->request->getProperty('command_id'));

		$this->smarty->assign('tab', 'Workspace');
		$this->smarty->display('RbacUserView.tpl');
	}
}

?>