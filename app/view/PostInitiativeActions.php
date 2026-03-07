<?php

/**
 * Defines the app_view_PostInitiativeActions class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_PostInitiativeActions extends app_view_View
{
	protected function doExecute()
	{
		
		$this->smarty->assign('referrer_type', $this->request->getProperty('referrer_type'));
		$this->smarty->assign('type_id', $this->request->getProperty('type_id'));
		$this->smarty->assign('action_type', $this->request->getProperty('action_type'));
		
		$this->smarty->assign('post_initiative_id', $this->request->getProperty('post_initiative_id'));
		$this->smarty->assign('post', $this->request->getObject('post'));
		$this->smarty->assign('initiative_name', $this->request->getProperty('initiative_name'));
		
//		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->assign('actions', $this->request->getObject('actions'));
		$this->smarty->display('PostInitiativeActions.tpl');
	}
}

?>