<?php

/**
 * Defines the app_view_Scoreboard class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_Scoreboard extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('user', $this->request->getObject('user'));
		$this->smarty->assign('scoreboard', $this->request->getObject('scoreboard'));
		$this->smarty->assign('effectives', $this->request->getObject('effectives'));
		$this->smarty->assign('non_effectives', $this->request->getObject('non_effectives'));
		$this->smarty->assign('meetings_set', $this->request->getObject('meetings_set'));
		$this->smarty->assign('information_requests', $this->request->getObject('information_requests'));
		
		$this->smarty->display('Scoreboard.tpl');
	}
}

?>