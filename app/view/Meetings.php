<?php

/**
 * Defines the app_view_Meetings class. 
 * @author    David Carter <david.carter@illumen.co.uk>
 * @copyright 2007 Illumen Ltd
 * @package   Alchemis
 * @version   SVN: $Id$
 */

require_once('app/view/View.php');

/**
 * @package Alchemis
 */
class app_view_Meetings extends app_view_View
{
	protected function doExecute()
	{
		
		$this->smarty->assign('referrer_type', $this->request->getProperty('referrer_type'));
		$this->smarty->assign('post_initiative_id', $this->request->getProperty('post_initiative_id'));
		$this->smarty->assign('post', $this->request->getObject('post'));
		$this->smarty->assign('initiative_name', $this->request->getProperty('initiative_name'));
		$this->smarty->assign('company_id', $this->request->getProperty('company_id'));
		
//		$this->smarty->assign('feedbackString', $this->request->getFeedbackString('</li><li>'));
		$this->smarty->assign('meetings', $this->request->getObject('meetings'));
		$this->smarty->display('Meetings.tpl');	
	}
}

?>