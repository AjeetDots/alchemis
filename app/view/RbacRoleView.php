<?php

/**
 * Defines the app_view_RbacRoleView class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_RbacRoleView extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('role', $this->request->getObject('role'));
		$this->smarty->assign('permissions', $this->request->getObject('permissions'));
		
		// Commands
		$commands = $this->request->getObject('commands');
		$command_options = array();
		foreach ($commands as $command)
		{
			$command_options[$command->getId()] = $command->getName();
		}
		$this->smarty->assign('commands', $command_options);
		$this->smarty->assign('command_id', $this->request->getProperty('command_id'));
		
		$this->smarty->assign('tab', 'Workspace');
		$this->smarty->display('RbacRoleView.tpl');
	}
}

?>