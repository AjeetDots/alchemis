<?php

/**
 * Defines the app_view_PostInitiativeNoteCreate class. 
 * @author    Ian Munday <ian.munday@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/ManipulationView.php');

/**
 * @package Alchemis
 */
class app_view_PostInitiativeNoteCreate extends app_view_View
{
	protected function doExecute()
	{
		$this->smarty->assign('post_id', $this->request->getProperty('post_id'));
		$this->smarty->assign('initiative_id', $this->request->getProperty('initiative_id'));
		$this->smarty->assign('post_initiative_id', $this->request->getProperty('post_initiative_id'));
		$this->smarty->assign('communication_id', $this->request->getProperty('communication_id'));
		$this->smarty->assign('feedback', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->assign('success', $this->request->getProperty('success'));
		$this->smarty->display('PostInitiativeNoteCreate.tpl');
	}
}

?>