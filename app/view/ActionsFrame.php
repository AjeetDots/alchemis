<?php

/**
 * Defines the app_view_ActionsFrame class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_ActionsFrame extends app_view_View
{
	protected function doExecute()
	{
		// Current user (NBM)
		$user_id = $this->request->getObject('user_id');
		$this->smarty->assign('user_id', $user_id);
		
		// Clients associated with NBM
		$client_options = $this->request->getObject('client_options');
		$this->smarty->assign('client_options', $client_options);
		
		// NBMs
		$nbm_options = $this->request->getObject('nbm_options');
		$this->smarty->assign('nbm_options', $nbm_options);

		// Default menu item
		$menu_item = $this->request->getObject('menu_item');
		$this->smarty->assign('menu_item', $menu_item);

		// Redirect
		$this->smarty->assign('redirect', $this->request->getProperty('redirect'));
		
		$this->smarty->assign('tab', 'Actions');
		$this->smarty->display('ActionsFrame.tpl');
		
	}
}

?>