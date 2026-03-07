<?php

/**
 * Defines the app_view_CommunicationSaved class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_CommunicationSaved extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('scoreboard', $this->request->getObject('scoreboard'));
		$this->smarty->assign('post_id', $this->request->getProperty('post_id'));
		$this->smarty->assign('initiative_id', $this->request->getProperty('initiative_id'));
		$this->smarty->assign('post_initiative_id', $this->request->getProperty('post_initiative_id'));
		$this->smarty->assign('source_tab', $this->request->getProperty('source_tab'));
		$this->smarty->display('CommunicationSaved.tpl');
	}
}

?>