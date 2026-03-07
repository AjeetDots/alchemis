<?php

/**
 * Defines the app_view_RbacCommandList class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_RbacCommandList extends app_view_View
{
	protected function doExecute()
	{
		$collection = $this->request->getObject('commands');
		$this->smarty->assign('commands', $collection->toArray());
		
		// Commands
		$commands = $this->request->getObject('commands');
		$command_options = array();
		foreach ($commands as $command)
		{
			$command_options[$command->getId()] = $command->getName();
		}
		$this->smarty->assign('commands_dd', $command_options);
		$this->smarty->assign('command_dd_id', $this->request->getProperty('command_id'));
		
		// Permissions
		$collection = $this->request->getObject('permissions');
		$this->smarty->assign('permissions', $collection->toArray());
		$this->smarty->assign('tab', 'Workspace');
		$this->smarty->display('RbacCommandList.tpl');
	}
}

?>