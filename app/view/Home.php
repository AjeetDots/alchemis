<?php

/**
 * Defines the app_view_Home class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_Home extends app_view_View
{
	protected function doExecute()
	{
		// Push user data to the template
		$this->smarty->assign('client_initiatives', $this->request->getObject('client_initiatives'));
		$this->smarty->assign('client_id', $this->request->getObject('client_id'));
		$this->smarty->assign('user', $this->request->getObject('user'));
		$this->smarty->assign('scoreboard', $this->request->getObject('scoreboard'));
		$this->smarty->assign('tab', 'Dashboard');
		$this->smarty->assign('redirect', $this->request->getProperty('redirect'));
		$this->smarty->display('Home.tpl');
	}
}

?>