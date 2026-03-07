<?php

/**
 * Defines the app_view_PostInitiativeEdit class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_PostInitiativeEdit extends app_view_ManipulationView
{
	protected function doExecute()
	{
		// Init
		$this->smarty->assign('id', $this->request->getProperty('id'));
		$this->smarty->assign('post_initiative', $this->request->getObject('post_initiative'));
		$this->smarty->assign('parent_tab', $this->request->getProperty('parent_tab'));
		$this->smarty->assign('status_id', $this->request->getProperty('status_id'));
		$this->smarty->assign('status_options', $this->request->getObject('status_options'));
		$this->smarty->assign('success', $this->request->getProperty('success'));
		
		// Get any feedback
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		
		if ($this->request->getProperty('success')) {
			$this->smarty->assign('scoreboard', $this->request->getObject('scoreboard'));
		}
		
		$this->smarty->display('PostInitiativeEdit.tpl');
	}
}

?>